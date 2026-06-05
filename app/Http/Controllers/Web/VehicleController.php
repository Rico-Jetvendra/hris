<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehicle\VehicleStoreRequest;
use App\Imports\VehicleImport;
use App\Models\Company;
use App\Models\Insurance;
use App\Models\Vehicle;
use App\Models\VehicleDocument;
use Carbon\Carbon;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller{
    public function index(){
        $data = $this->getSql()->get();

        $columns = [
            ['label' => 'Perusahaan', 'field' => 'company_name'],
            ['label' => 'No. Polisi', 'field' => 'vehicle_number'],
            ['label' => 'Merk', 'field' => 'vehicle_brand'],
            ['label' => 'Pajak', 'field' => 'vehicle_tax_due'],
            ['label' => 'STNK', 'field' => 'vehicle_reg_due'],
            ['label' => 'Periode Asuransi', 'field' => 'vehicle_insurance_period'],
        ];
        $combo = $this->getSelect();

        return view('pages.vehicle.index', compact('data', 'columns', 'combo'));
    }

    public function create(){
        //
    }

    public function store(VehicleStoreRequest $request){
        $validated = $request->validated();

        $validated['vehicle_bpkb']              = $request->has('vehicle_bpkb') ? 1: 0;
        $validated['vehicle_insurance_period']  = !empty($validated['vehicle_insurance_start']) ? $validated['vehicle_insurance_start'].' s/d '.$validated['vehicle_insurance_end'] : null;

        try {
            $id = Vehicle::create($validated);
            
            $allowedExtensions = ['jpg', 'jpeg', 'png'];

            foreach ($request->file('document_name') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = strtolower($file->getClientOriginalExtension());

                if (!in_array($extension, $allowedExtensions)) {
                    throw new \Exception("Invalid file type: {$extension}");
                }

                $cleanName = preg_replace('/[^A-Za-z0-9\-_]/', '', $originalName);
                $compressed = $this->compressWithImagick($file, $cleanName);

                VehicleDocument::create([
                    'vehicle_id'        => $id->vehicle_id,
                    'document_name'     => $compressed['path'],
                    'document_size'     => $compressed['size'],
                    'document_type'     => 'jpg',
                ]);
            }

            ActivityLogger::create([
                'subject_type'  => 'Vehicle',
                'subject_id'    => $id->vehicle_id,
                'new_values'    => $validated
            ]);

            return redirect()->route('web.vehicle.index')->with('success', 'Kendaraan berhasil ditambah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to create vehicle: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = $this->getSql();
        $basePermission = permission();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('vehicle_brand', function($row){
                $brands = config('combobox.brands');

                return collect($brands)->firstWhere('id', $row->vehicle_brand)['name'];
            })
            ->addColumn('vehicle_insurance_period', function($row){
                $tgl = !empty($row->vehicle_insurance_start) ? $row->vehicle_insurance_start.' s/d '.$row->vehicle_insurance_end : null;
                return $tgl ?? '-';
            })
            ->addColumn('company_name', function($row){
                return $row->company_name ?? '-';
            })
            ->addColumn('vehicle_tax_due', function($row){
                return Carbon::parse($row->vehicle_tax_due)->format('d F Y') ?? '-';
            })
            ->addColumn('vehicle_reg_due', function($row){
                return Carbon::parse($row->vehicle_reg_due)->format('d F Y') ?? '-';
            })
            ->addColumn('action', function ($row) use ($basePermission) {
                $buttons = '';

                if(in_array($basePermission.'.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->vehicle_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array($basePermission.'.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->vehicle_id.'" data-name="'.$row->vehicle_number.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->filterColumn('vehicle_brand', function($query, $keyword) {
                $brands = collect(config('combobox.brands'));

                $matchedIds = $brands
                    ->filter(function ($brand) use ($keyword) {
                        return stripos($brand['name'], $keyword) !== false;
                    })
                    ->pluck('id')
                    ->toArray();

                $query->whereIn('vehicle_brand', $matchedIds);
            })
            ->filterColumn('company_name', function($query, $keyword){
                $query->whereExists(function($sub) use ($keyword){
                    $sub->select(DB::raw(1))
                        ->from('t_company')
                        ->whereColumn('t_company.company_id', 't_vehicle.vehicle_company')
                        ->where('t_company.company_name', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data               = $this->getSql()->where('vehicle_id', $id)->first();
        $data['document']   = VehicleDocument::where('vehicle_id', $id)->get();

        return response()->json($data);
    }

    public function update(VehicleStoreRequest $request, $id){
        $data       = Vehicle::findOrFail($id);

        $validated  = $request->validated();

        $validated['vehicle_bpkb']              = $request->has('vehicle_bpkb') ? 1: 0;
        $validated['vehicle_insurance_period']  = !empty($validated['vehicle_insurance_start']) ? $validated['vehicle_insurance_start'].' s/d '.$validated['vehicle_insurance_end'] : null;

        try {
            $oldValues = [];
            $newValues = [];

            foreach ($validated as $field => $value) {
                if ($data->$field != $value) {
                    $oldValues[$field] = $data->$field;
                    $newValues[$field] = $value;
                }
            }

            $data->update($validated);
            
            $allowedExtensions = ['jpg', 'jpeg', 'png'];

            foreach ($request->file('document_name') as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = strtolower($file->getClientOriginalExtension());

                if (!in_array($extension, $allowedExtensions)) {
                    throw new \Exception("Invalid file type: {$extension}");
                }

                $cleanName = preg_replace('/[^A-Za-z0-9\-_]/', '', $originalName);
                $compressed = $this->compressWithImagick($file, $cleanName);

                $data = VehicleDocument::create([
                    'vehicle_id'        => $id,
                    'document_name'     => $compressed['path'],
                    'document_size'     => $compressed['size'],
                    'document_type'     => 'jpg',
                ]);
            }

            ActivityLogger::update([
                'subject_type'  => 'Vehicle',
                'subject_id'    => $id,
                'new_values'    => $newValues,
                'old_values'    => $oldValues,
            ]);

            return redirect()->route('web.vehicle.index')->with('success', 'Kendaraan berhasil dirubah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update vehicle: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Vehicle::findOrFail($id);

        try {
            $oldValues = $data->toArray();

            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => session('user')->id ?? 1
            ]);

            VehicleDocument::where('vehicle_id', $id)->delete();

            ActivityLogger::delete([
                'subject_type'  => 'Vehicle',
                'subject_id'    => $id,
                'old_values'    => $oldValues
            ]);

            return redirect()->route('web.vehicle.index')->with('success', 'Vehicle deleted successfully!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete vehicle: ' . $e->getMessage());
        }
    }

    public function destroyImage($id){
        $data = VehicleDocument::find($id);

        if(!$data){
            Log::error("Document Not Found");
        }

        try {
            $oldValues = $data->toArray();

            $data->delete();
            
            $path = parse_url($data->document_name, PHP_URL_PATH);
            $path = str_replace('/storage/', '', $path);

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            ActivityLogger::delete([
                'subject_type'  => 'Vehicle Document',
                'subject_id'    => $id,
                'old_values'    => $oldValues
            ]);

            return response()->json(['success' => true, 'message' => 'Document deleted successfully!']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete document: ' . $e->getMessage()]);
        }
    }

    public function upload(Request $request){
        $validator = Validator::make($request->all(), [
            'file'      => 'file|mimes:xls,xlsx',
        ],[
            'file.required' => 'File wajib diupload.',
            'file.file'     => 'File tidak valid.',
            'file.mimes'    => 'File harus berupa Excel (.xls atau .xlsx).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try{
            Excel::import(new VehicleImport, $request->file('file'), null, \Maatwebsite\Excel\Excel::XLSX);

            return redirect()->route('web.employee.index')->with('success', 'Karyawan berhasil diupload!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan import : ' . $e->getMessage());
        }
    }

    private function getSql(){
        $sql = Vehicle::query()
                ->join('t_company as cp', 't_vehicle.vehicle_company', '=', 'cp.company_id')
                ->select(
                    't_vehicle.vehicle_id',
                    't_vehicle.vehicle_number',
                    't_vehicle.vehicle_brand',
                    't_vehicle.vehicle_frame',
                    't_vehicle.vehicle_machine',
                    't_vehicle.vehicle_color',
                    't_vehicle.vehicle_company',
                    't_vehicle.vehicle_tax_due',
                    't_vehicle.vehicle_reg_due',
                    't_vehicle.vehicle_bpkb',
                    't_vehicle.vehicle_insurance_payment',
                    't_vehicle.vehicle_insurance_number',
                    't_vehicle.vehicle_insurance_period',
                    't_vehicle.vehicle_insurance',
                    't_vehicle.remarks',
                    DB::raw('SUBSTRING_INDEX(t_vehicle.vehicle_insurance_period, "s/d", 1) as vehicle_insurance_start'),
                    DB::raw('SUBSTRING_INDEX(t_vehicle.vehicle_insurance_period, "s/d", -1) as vehicle_insurance_end'),
                    'cp.company_id as company_id',
                    'cp.company_name as company_name',
                );

        return $sql;
    }

    private function getSelect(){
        $colors     = config('combobox.colors');
        $brands     = config('combobox.brands');
        $company    = Company::all();
        $insurance  = Insurance::all();

        $data   = [
            "colors"        => $colors,
            "brands"        => $brands,
            "company"       => $company,
            "insurance"     => $insurance
        ];

        return $data;
    }

    private function compressWithImagick($file, $cleanName){
        $image = new \Imagick($file->getPathname());

        // ✅ Fix rotation
        $image->autoOrient();

        // Resize
        $image->resizeImage(1280, 0, \Imagick::FILTER_LANCZOS, 1);

        // Remove metadata
        $image->stripImage();

        // Convert to JPG
        $image->setImageFormat('jpeg');
        $image->setImageCompressionQuality(25);

        $name = time() . '_' . uniqid() . '_' . $cleanName . '.jpg';
        $fullPath = storage_path('app/public/vehicle/' . $name);

        $image->writeImage($fullPath);

        $image->clear();
        $image->destroy();

        return [
            'path' => 'vehicle/' . $name,
            'size' => filesize($fullPath),
        ];
    }
}
