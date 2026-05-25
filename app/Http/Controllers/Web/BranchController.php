<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller{
    public function index(){
        $data = Branch::all();
        $columns = [
            ['label' => 'Nama Cabang', 'field' => 'branch_name'],
        ];

        return view('pages.branch.index', compact('data', 'columns'));
    }

    public function create(){
        //
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'branch_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_branch', 'branch_name')->where(fn ($query) => $query->where('status', 1))
            ],
            'remarks'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            $id = Branch::create($validated);

            ActivityLogger::create([
                'subject_type'  => 'Branch',
                'subject_id'    => $id->branch_id,
                'new_values'    => $validated
            ]);

            return redirect()->route('web.branch.index')->with('success', 'Cabang berhasil ditambah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to create branch: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = Branch::all();
        $basePermission = permission();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($basePermission) {
                $buttons = '';

                if(in_array($basePermission.'.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->branch_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array($basePermission.'.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->branch_id.'" data-name="'.$row->branch_name.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data = Branch::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $data = Branch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'branch_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_branch', 'branch_name')->ignore($id, 'branch_id')->where(fn ($q) => $q->where('status', 1))
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
                'subject_type'  => 'Branch',
                'subject_id'    => $id,
                'new_values'    => $newValues,
                'old_values'    => $oldValues,
            ]);

            return redirect()->route('web.branch.index')->with('success', 'Cabang berhasil di rubah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update branch: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Branch::findOrFail($id);

        try {
            $oldValues = $data->toArray();

            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => session('user')->id ?? 1
            ]);

            ActivityLogger::delete([
                'subject_type'  => 'Branch',
                'subject_id'    => $id,
                'old_values'    => $oldValues
            ]);

            return redirect()->route('web.branch.index')->with('success', 'Cabang berhasil di hapus!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete branch: ' . $e->getMessage());
        }
    }
}
