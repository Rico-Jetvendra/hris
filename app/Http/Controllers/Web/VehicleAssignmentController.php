<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VehicleAssignmentController extends Controller{
    public function index(){
        $data = $this->getSqlQuery()->get();
        $columns = [
            ['label' => 'No. Polisi', 'field' => 'vehicle_number'],
            ['label' => 'Nama Karyawan', 'field' => 'employee_name'],
        ];
        $selects = $this->getSelect();

        return view('pages.vehicle_assignment.index', compact('data', 'columns', 'selects'));
    }

    public function create(){
        //
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'vehicle_id'    => 'required|exists:t_vehicle,vehicle_id',
            'employee_id'   => 'required|exists:t_employee,employee_id',
            'remarks'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $validated['status'] = '1';
        $validated['created_by'] = session('user')->id ?? 1;
        $validated['created_date'] = now();

        try {
            VehicleAssignment::create($validated);

            return redirect()->route('web.vehicle-assignment.index')->with('success', 'Penempatan Kendaraan berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create vehicle_assignment: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = $this->getSqlQuery();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $buttons = '';

                if(in_array('vehicle_assignment.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->vehicle_assignment_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array('vehicle_assignment.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->vehicle_assignment_id.'" data-name="'.$row->vehiclet_number.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data = $this->getSqlQuery()->where('t_vehicle_assignment.vehicle_assignment_id', $id)->firstOrFail();

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $data = $this->getSqlQuery()->where('t_vehicle_assignment.vehicle_assignment_id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'vehicle_id'    => 'required|exists:t_vehicle,vehicle_id',
            'employee_id'   => 'required|exists:t_employee,employee_id',
            'remarks'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $validated['updated_date'] = now();
        $validated['updated_by'] = session('user')->id ?? 1;

        try {
            $data->update($validated);

            return redirect()->route('web.vehicle-assignment.index')->with('success', 'Penempatan Kendaraan berhasil di rubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update vehicle_assignment: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = $this->getSqlQuery()->where('t_vehicle_assignment.vehicle_assignment_id', $id)->firstOrFail();

        try {
            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => session('user')->id ?? 1
            ]);

            return redirect()->route('web.vehicle-assignment.index')->with('success', 'Penempatan Kendaraan berhasil di hapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete vehicle_assignment: ' . $e->getMessage());
        }
    }

    private function getSqlQuery(){
        $sql = VehicleAssignment::join('t_vehicle as vh', 't_vehicle_assignment.vehicle_id', '=', 'vh.vehicle_id')
                                ->join('t_employee as em', 't_vehicle_assignment.employee_id', '=', 'em.employee_id')
                                ->select(
                                    't_vehicle_assignment.vehicle_assignment_id',
                                    't_vehicle_assignment.remarks',
                                    'vh.vehicle_id as vehicle_id',
                                    'vh.vehicle_number as vehicle_number',
                                    'em.employee_id as employee_id',
                                    'em.employee_name as employee_name',
                                );

        return $sql;
    }

    private function getSelect(){
        $employee = Employee::all();
        $vehicle  = Vehicle::all();

        $data = [
            'employee' => $employee,
            'vehicle' => $vehicle,
        ];

        return $data;
    }
}
