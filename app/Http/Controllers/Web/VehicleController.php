<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehicle\VehicleStoreRequest;
use App\Models\Company;
use App\Models\Insurance;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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
        $validated['vehicle_insurance_period']  = $validated['vehicle_insurance_period_start'].' s/d '.$validated['vehicle_insurance_period_end'];

        try {
            Vehicle::create($validated);

            return redirect()->route('web.vehicle.index')->with('success', 'Kendaraan berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create vehicle: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = $this->getSql();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('vehicle_brand', function($row){
                $brands = config('combobox.brands');

                return collect($brands)->firstWhere('id', $row->vehicle_brand)['name'];
            })
            ->addColumn('vehicle_insurance_period', function($row){
                return $row->vehicle_insurance_start.' s/d '.$row->vehicle_insurance_end;
            })
            ->addColumn('action', function ($row) {
                $buttons = '';

                if(in_array('vehicle.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->vehicle_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array('vehicle.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->vehicle_id.'" data-name="'.$row->vehicle_number.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data = $this->getSql()->where('vehicle_id', $id)->first();

        return response()->json($data);
    }

    public function update(VehicleStoreRequest $request, $id){
        $validated = $request->validated();

        $validated['vehicle_bpkb']              = $request->has('vehicle_bpkb') ? 1: 0;
        $validated['vehicle_insurance_period']  = $validated['vehicle_insurance_period_start'].' s/d '.$validated['vehicle_insurance_period_end'];

        try {
            $data = Vehicle::findOrFail($id);

            $data->update($validated);

            return redirect()->route('web.vehicle.index')->with('success', 'Kendaraan berhasil dirubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update vehicle: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Vehicle::findOrFail($id);

        try {
            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => auth()->id() ?? 1
            ]);

            return redirect()->route('web.vehicle.index')->with('success', 'Vehicle deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete vehicle: ' . $e->getMessage());
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
                    'cp.company_id',
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
}
