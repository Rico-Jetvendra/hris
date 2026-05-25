<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller{
    public function index(){
        $data = Department::all();
        $columns = [
            ['label' => 'Nama Departemen', 'field' => 'department_name'],
        ];

        return view('pages.department.index', compact('data', 'columns'));
    }

    public function create(){
        //
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'department_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_department', 'department_name')->where(fn ($query) => $query->where('status', 1))
            ],
            'remarks'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            $id = Department::create($validated);

            ActivityLogger::create([
                'subject_type'  => 'Department',
                'subject_id'    => $id->department_id,
                'new_values'    => $validated
            ]);

            return redirect()->route('web.department.index')->with('success', 'Departemen berhasil ditambah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = Department::all();
        $basePermission = permission();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($basePermission) {
                $buttons = '';

                if(in_array($basePermission.'.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->department_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array($basePermission.'.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->department_id.'" data-name="'.$row->department_name.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data = Department::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $data = Department::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'department_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_department', 'department_name')->ignore($id, 'department_id')->where(fn ($q) => $q->where('status', 1))
            ],
            'remarks'       => 'nullable|string',
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
                'subject_type'  => 'Department',
                'subject_id'    => $id,
                'new_values'    => $newValues,
                'old_values'    => $oldValues,
            ]);

            return redirect()->route('web.department.index')->with('success', 'Departemen berhasil di rubah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update department: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Department::findOrFail($id);

        try {
            $oldValues = $data->toArray();

            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => session('user')->id ?? 1
            ]);

            ActivityLogger::delete([
                'subject_type'  => 'Department',
                'subject_id'    => $id,
                'old_values'    => $oldValues
            ]);

            return redirect()->route('web.department.index')->with('success', 'Departemen berhasil di hapus!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }
}
