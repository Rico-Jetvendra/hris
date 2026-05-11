<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
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

        $validated['status'] = '1';
        $validated['created_by'] = auth()->id() ?? 1;
        $validated['created_date'] = now();

        try {
            Branch::create($validated);

            return redirect()->route('web.branch.index')->with('success', 'Cabang berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create branch: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = Branch::all();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->branch_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->branch_id.'" data-name="'.$row->branch_name.'">
                        <i class="bi bi-trash"></i>
                    </button>
                ';
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

        $validated['updated_date'] = now();
        $validated['updated_by'] = auth()->id() ?? 1;

        try {
            $data->update($validated);

            return redirect()->route('web.branch.index')->with('success', 'Cabang berhasil di rubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update branch: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Branch::findOrFail($id);

        try {
            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => auth()->id() ?? 1
            ]);

            return redirect()->route('web.branch.index')->with('success', 'Cabang berhasil di hapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete branch: ' . $e->getMessage());
        }
    }
}
