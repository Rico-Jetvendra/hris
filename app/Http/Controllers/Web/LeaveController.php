<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EmployeeCompany;
use App\Models\Leave;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LeaveController extends Controller{
    public function index(){
        $data = $this->getSql()->get();
        $columns = [
            ['label' => 'Nama Karyawan', 'field' => 'employee_name'],
            ['label' => 'Cuti', 'field' => 'leave_entitlement'],
            ['label' => 'Tahun', 'field' => 'leave_period'],
        ];

        return view('pages.leave.index', compact('data', 'columns'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'leave_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_leave', 'leave_name')->where(fn ($query) => $query->where('status', 1))
            ],
            'remarks'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            $id = Leave::create($validated);

            ActivityLogger::create([
                'subject_type'  => 'Leave',
                'subject_id'    => $id->leave_id,
                'new_values'    => $validated
            ]);

            return redirect()->route('web.leave.index')->with('success', 'Cuti berhasil ditambah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to create leave: ' . $e->getMessage());
        }
    }

    public function data(){
        $query = $this->getSql();
        $basePermission = permission();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($basePermission) {
                $buttons = '';

                if(in_array($basePermission.'.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->leave_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array($basePermission.'.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->leave_id.'" data-name="'.$row->employee_name.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->filterColumn('employee_name', function($query, $keyword) {
                $query->where('e.employee_name', 'LIKE', "%{$keyword}%");
            })
            ->filterColumn('leave_entitlement', function($query, $keyword) {
                $query->where('t_leave.leave_entitlement', '=', "{$keyword}");
            })
            ->filterColumn('leave_period', function($query, $keyword) {
                $query->where('t_leave.leave_period', '=', "{$keyword}");
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data = $this->getSql()->where('leave_id', $id)->first();

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $data = Leave::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'leave_entitlement' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

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

            ActivityLogger::update([
                'subject_type'  => 'Leave',
                'subject_id'    => $id,
                'new_values'    => $newValues,
                'old_values'    => $oldValues,
            ]);

            return redirect()->route('web.leave.index')->with('success', 'Cuti berhasil di rubah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update leave: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Leave::findOrFail($id);

        try {
            $data->delete();

            return redirect()->route('web.leave.index')->with('success', 'Cuti berhasil di hapus!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete leave: ' . $e->getMessage());
        }
    }

    public function generate(){
        $year               = date('Y');
        $employees          = EmployeeCompany::where('contract_status', 1)->get();
        $existingLeaveIds   = Leave::whereYear('leave_period', $year)->pluck('employee_id')->toArray();

        foreach ($employees as $employee) {
            if (!in_array($employee->employee_id, $existingLeaveIds)) {
                Leave::create([
                    'employee_id'       => $employee->employee_id,
                    'leave_entitlement' => $employee->leave_entitlement,
                    'leave_period'      => $year,
                ]);
            }
        }

        return redirect()->route('web.leave.index')->with('success', 'Data berhasil dibuat');
    }

    public function minus(Request $request){
        $year   = date('Y');
        $leaves = Leave::where('leave_period', $year)->get();
        $days   = max(0, $request->input('days', 0));

        foreach ($leaves as $leave) {
            $leave->update([
                'leave_entitlement' => max(0, $leave->leave_entitlement - $days),
            ]);
        }

        return redirect()->route('web.leave.index')->with('success', 'Data berhasil dibuat');
    }

    private function getSql(){
        $sql = Leave::join('t_employee as e', 't_leave.employee_id', '=', 'e.employee_id')
                    ->select('t_leave.*', 'e.employee_name');

        return $sql;
    }
}
