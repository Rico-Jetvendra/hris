<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InsuranceController extends Controller{
    public function index(){
        $data = Insurance::all();
        $columns = [
            ['label' => 'Nama Asuransi', 'field' => 'insurance_name'],
        ];

        return view('pages.insurance.index', compact('data', 'columns'));
    }

    public function create(){
        //
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'insurance_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_insurance', 'insurance_name')->where(fn ($query) => $query->where('status', 1))
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
            Insurance::create($validated);

            return redirect()->route('web.insurance.index')->with('success', 'Asuransi berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create insurance: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = Insurance::all();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $buttons = '';

                if(in_array('insurance.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->insurance_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array('insurance.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->insurance_id.'" data-name="'.$row->insurance_name.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data = Insurance::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $data = Insurance::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'insurance_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_insurance', 'insurance_name')->ignore($id, 'insurance_id')->where(fn ($q) => $q->where('status', 1))
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

            return redirect()->route('web.insurance.index')->with('success', 'Asuransi berhasil dirubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update insurance: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Insurance::findOrFail($id);

        try {
            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => auth()->id() ?? 1
            ]);

            return redirect()->route('web.insurance.index')->with('success', 'Asuransi berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete insurance: ' . $e->getMessage());
        }
    }
}
