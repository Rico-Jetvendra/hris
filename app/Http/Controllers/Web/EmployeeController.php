<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeStoreRequest;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeCompany;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\EmployeeImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller{
    public function index(){
        $data = $this->sqlQuery();

        $columns = [
            ['label' => 'NIK', 'field' => 'employee_nik'],
            ['label' => 'Nama Karyawan', 'field' => 'employee_name'],
            ['label' => 'Jabatan', 'field' => 'position_name'],
            ['label' => 'Tgl. Masuk', 'field' => 'entry_date', 'searchable' => false],
            ['label' => 'Email', 'field' => 'employee_email'],
            ['label' => 'Telpon', 'field' => 'employee_phone'],
            ['label' => 'Status', 'field' => 'contract_status'],
        ];
        $combo = $this->getSelect();

        return view('pages.employee.index', compact('data', 'columns', 'combo'));
    }

    public function create(){
        //
    }

    public function store(EmployeeStoreRequest $request){
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                $employee = Employee::create($validated['employee']);

                $initial = Company::findOrFail($validated['company']['company_id'])->first();

                $validated['company']['employee_nik'] = $this->createNIK($initial['company_initial']);
                $validated['company']['employee_id'] = $employee->employee_id;
                EmployeeCompany::create($validated['company']);
            });

            return redirect()->route('web.employee.index')->with('success', 'Karyawan berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = $this->sqlQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $buttons = '';

                if(in_array('employee.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->employee_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array('employee.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->employee_id.'" data-name="'.$row->employee_name.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->filterColumn('position_name', function($query, $keyword) {
                $query->where('p.position_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('department_name', function($query, $keyword) {
                $query->where('d.department_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('employee_nik', function($query, $keyword) {
                $query->where('ec.employee_nik', 'like', "%{$keyword}%");
            })
            ->filterColumn('contract_status', function($query, $keyword) {
                $keyword = strtolower($keyword);

                $query->where(function($q) use ($keyword) {
                    if (strpos('karyawan', $keyword) !== false) {
                        $q->where('ec.contract_status', 1);
                    }

                    if (strpos('resign', $keyword) !== false) {
                        $q->orWhere('ec.contract_status', 0);
                    }
                });
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data = $this->sqlQuery()->where('t_employee.employee_id', $id)->first();

        return response()->json($data);
    }

    public function update(EmployeeStoreRequest $request, $id){
        $employee = Employee::findOrFail($id);
        $company = EmployeeCompany::where('employee_id', $id)->first();

        $validated = $request->validated();

        try {
            DB::transaction(function () use ($employee, $company, $validated) {
                $employee->update($validated['employee']);

                $company->update($validated['company']);
            });

            return redirect()->route('web.employee.index')->with('success', 'Karyawan berhasil dirubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $employee = Employee::findOrFail($id);
        $company = EmployeeCompany::where('employee_id', $id)->first();

        try {
            $employee->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => auth()->id() ?? 1
            ]);

            $company->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => auth()->id() ?? 1
            ]);

            return redirect()->route('web.employee.index')->with('success', 'Employee deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete employee: ' . $e->getMessage());
        }
    }

    public function upload(Request $request){
        $validator = Validator::make($request->all(), [
            'file'      => 'file|mimes:xls,xlsx',
            'resign'    => 'nullable|string',
        ],[
            'file.required' => 'File wajib diupload.',
            'file.file'     => 'File tidak valid.',
            'file.mimes'    => 'File harus berupa Excel (.xls atau .xlsx).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try{
            $resign = $request->has('resign');

            Excel::import(new EmployeeImport($resign), $request->file('file'), null, \Maatwebsite\Excel\Excel::XLSX);

            return redirect()->route('web.employee.index')->with('success', 'Karyawan berhasil diupload!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan import : ' . $e->getMessage());
        }
    }

    private function sqlQuery(){
        return Employee::query()
                        ->join('t_employee_company as ec', 't_employee.employee_id', '=', 'ec.employee_id')
                        ->join('t_position as p', 'ec.position_id', '=', 'p.position_id')
                        ->join('t_department as d', 'ec.department_id', '=', 'd.department_id')
                        ->join('t_branch as b', 'ec.branch_id', '=', 'b.branch_id')
                        ->join('t_company as c', 'ec.company_id', '=', 'c.company_id')
                        ->select(
                            't_employee.employee_id',
                            't_employee.employee_name',
                            't_employee.employee_pob',
                            't_employee.employee_dob',
                            't_employee.employee_sex',
                            't_employee.employee_blood',
                            't_employee.employee_religion',
                            't_employee.employee_marriage',
                            't_employee.employee_ktp',
                            't_employee.employee_npwp',
                            't_employee.employee_education',
                            't_employee.employee_father',
                            't_employee.employee_mother',
                            't_employee.employee_email',
                            't_employee.employee_address',
                            't_employee.employee_home_phone',
                            't_employee.employee_phone',
                            't_employee.employee_remarks',
                            'p.position_id',
                            'p.position_name as position_name',
                            'd.department_id',
                            'd.department_name as department_name',
                            'b.branch_id',
                            'b.branch_name as .branch_name',
                            'c.company_id',
                            'c.company_name as company_name',
                            'ec.entry_date as entry_date',
                            'ec.end_of_contract',
                            'ec.employee_nik as employee_nik',
                            DB::raw(
                                '
                                    CASE
                                        WHEN ec.contract_status = 1 THEN "Karyawan"
                                        ELSE "Resign"
                                    END
                                as contract_status'
                            ),
                        );
    }

    private function getSelect(){
        $branch     = Branch::all();
        $company    = Company::all();
        $department = Department::all();
        $position   = Position::all();
        $sex        = config('combobox.sex');
        $religion   = config('combobox.religions');
        $education  = config('combobox.education');
        $blood      = config('combobox.blood_type');
        $marriage   = config('combobox.marriage');

        $data = [
            'branch'     => $branch,
            'company'    => $company,
            'department' => $department,
            'position'   => $position,
            'sex'        => $sex,
            'religion'   => $religion,
            'education'  => $education,
            'blood'      => $blood,
            'marriage'   => $marriage,
        ];

        return $data;
    }

    private function createNIK($initial){
        do{
            $lastNik = EmployeeCompany::
                            where('employee_nik', 'LIKE', $initial . '%')
                            ->orderBy('employee_nik', 'DESC')
                            ->value('employee_nik');

            if ($lastNik) {
                $number = (int) substr($lastNik, -5);
                $number++;
            } else {
                $number = 1;
            }

            $kode = $initial . str_pad($number, 5, '0', STR_PAD_LEFT);
        } while (EmployeeCompany::where('employee_nik', $kode)->exists());

        return $kode;
    }
}
