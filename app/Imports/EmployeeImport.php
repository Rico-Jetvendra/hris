<?php

namespace App\Imports;

use App\Models\Company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;

use App\Models\Employee;
use App\Models\EmployeeCompany;

class EmployeeImport implements ToCollection, WithCalculatedFormulas{
    private $resign;

    public function __construct($resign){
        $this->resign = $resign;
    }

    public function collection(Collection $collection){
        $data = $this->combineRows($collection);

        foreach ($data as $row) {
            $pribadi = [
                'employee_name'         => $row['nama_pegawai'],
                'employee_pob'          => $row['tempat'],
                'employee_dob'          => $this->toDate($row['tgl_bln_thn']),
                'employee_sex'          => $this->assignCombo('sex', $row['jenis_kelamin']),
                'employee_blood'        => $this->assignCombo('blood_type', $row['gol_darah']),
                'employee_religion'     => $this->assignCombo('religions', $row['agama']),
                'employee_marriage'     => $this->assignCombo('marriage', $row['status_s_m_b']),
                'employee_ktp'          => $row['no_ktp'],
                'employee_npwp'         => $row['npwp'],
                'employee_education'    => $this->assignCombo('education', $row['pendidikan_terakhir']),
                'employee_father'       => $row['ayah'],
                'employee_mother'       => $row['ibu'],
                'employee_email'        => $row['alamat_email'],
                'employee_address'      => $row['alamat'],
                'employee_home_phone'   => $row['tlp_rumah'],
                'employee_phone'        => $row['handphone'],
                'employee_remarks'      => $row['keterangan'],
            ];

            $company = [
                'department_id'         => $this->assignID('department', $row['departement'])[0],
                'position_id'           => $this->assignID('position', $row['jabatan'])[0],
                'company_id'            => $this->assignID('company', $row['company'])[0],
                'branch_id'             => $this->assignID('branch', $row['cabang'])[0],
                'entry_date'            => $this->toDate($row['mulai_bekarja']),
                'end_of_contract'       => $this->toDate($row['akhir_bekerja']),
                'contract_status'       => $row['status'] == 'A' ? 1: 0,
                'employee_nik'          => $this->generateNIK($row['nik'], $row['company']),
            ];

            DB::transaction(function () use ($pribadi, $company) {
                $employee = Employee::create($pribadi);

                $company['employee_id'] = $employee->employee_id;
                EmployeeCompany::create($company);
            });
        }
    }

    private function combineRows($collection){
        $header = $collection[0]->toArray();

        if(strtolower(trim($header[0])) != 'tri' || strtolower(trim($header[21])) != 'company information'){
            throw new \Exception('Format excel tidak sesuai');
        }

        $fields = $this->formatting($collection[1]->toArray());
        $rows   = $collection->toArray();
        array_splice($rows, 0, 2);

        foreach($rows as $idx => $row){
            if(!$this->resign && $row[38] == 'R'){
                unset($rows[$idx]);
                continue;
            }

            $rows[$idx] = array_combine($fields, $row);
        }

        return array_values($rows);
    }

    private function formatting($collection){
        $fields = [];

        foreach($collection as $coll){
            $formatted = preg_replace('/[^A-Za-z0-9]/', '_', strtolower($coll));

            array_push($fields, $formatted);
        }

        return $fields;
    }

    private function toDate($date){
        return $date == '-' ? null: Date::excelToDateTimeObject((int) $date)->format('Y-m-d');
    }

    private function assignCombo($field, $value){
        $comboValue = config('combobox.'.$field);
        $formatted  = $this->changeStandard($field, $value);
        $result     = collect($comboValue)->first(function ($item) use ($formatted){
            return $item['name'] === $formatted;
        });

        return $result['id'] ?? 0;
    }

    private function changeStandard($field, $value){
        $string = '';

        switch($field){
            case 'sex':
                $string = ($value == 'Laki-laki' ? 'Pria': 'Wanita');
                break;
            case 'religions':
                $string = ($value == 'Budha' ? 'Buddha': $value);
                break;
            case 'education':
                switch($value){
                    case 'DIII':
                        case 'D-III':
                            case 'D III':
                                case 'Sarjana Muda':
                                    $string = 'D3';
                                    break;
                    case 'S-1':
                        case 'S-1 Hukum':
                            $string = 'S1';
                            break;
                    default:
                        $string = $value;
                        break;
                }
                break;
            case 'marriage':
                $string = ($value == 'TK' ? 'TK-0': $value);
                break;
            default:
                $string = ($value == '-' || empty($value)) ? 0: $value;
                break;
        }

        return $string;
    }

    private function generateNIK($word, $company){
        $word       = (string) $word;
        $length     = strlen($word);
        $initial    = "";
        $number     = "";
        $string     = "";

        if($length > 0 && $length <= 8){
            $initial = substr($word, 0, 3);
            $number  = substr($word, 3);

            do{
                $number = '0'.$number;
            } while (strlen($number) < 5);

            $string = $initial.$number;
        }else if($length <= 0){
            $initial = Company::where('company_name', $company)->first();

            if(!$initial){
                return '';
            }
            $NIK = EmployeeCompany::
                        where('employee_nik', 'LIKE', $initial->company_initial.'%')
                        ->orderBy('employee_nik', 'DESC')
                        ->first();

            if(!$NIK){
                return $initial->company_initial.'00001';
            }else{
                $lastNIK = $NIK->employee_nik;
                $number  = $lastNIK ? (int) substr($lastNIK, 3) + 1 : 1;
                $number  = str_pad($number, 5, '0', STR_PAD_LEFT);
            }

            $string = $initial->company_initial.$number;
        }

        return $string;
    }

    private function assignID($modelName, $name){
        $model      = "App\\Models\\" . ucfirst($modelName);
        $primaryKey = (new $model)->getKeyName();
        $fieldName  = $modelName . '_name';

        $data       = $model::where($fieldName, 'LIKE', '%'.$name.'%')->first();

        if(!$data){
            $res = $this->createNew($modelName, $name);
            $ret = [
                $res->$primaryKey,
            ];

            if($modelName == 'company'){
                $ret[1] = $res->company_initial;
            }

            return $ret;
        }

        $result = [
            $data->$primaryKey
        ];

        if($modelName == 'company'){
            $result[1] = $data->company_initial;
        }

        return $result;
    }

    private function createNew($modelName, $name){
        $model      = "App\\Models\\" . ucfirst($modelName);
        $fieldName  = $modelName . '_name';

        $insert = [
            $fieldName      => $name,
            'remarks'       => 'Import from excel',
            'status'        => '1',
            'created_by'    => auth()->id() ?? 1,
            'created_date'  => now(),
        ];

        if($modelName == 'company'){
            $insert['company_initial'] = $this->companyInitial($name);
        }

        return $model::create($insert);
    }

    private function companyInitial($word){
        $word = explode(' ', trim($word))[0];

        $length = strlen($word);
        $mid = (int) floor($length/2);

        $first  = $word[0];
        $middle = $word[$mid];
        $last   = $word[$length - 1];

        return strtoupper($first . $middle . $last);
    }
}
