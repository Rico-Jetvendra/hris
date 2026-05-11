<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller{
    public function index(){
        $data = Company::all();
        $columns = [
            ['label' => 'Nama Perusahaan', 'field' => 'company_name'],
            ['label' => 'Inisial', 'field' => 'company_initial'],
        ];

        return view('pages.company.index', compact('data', 'columns'));
    }

    public function create(){
        //
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'company_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_company', 'company_name')->where(fn ($query) => $query->where('status', 1))
            ],
            'remarks'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $validated['company_initial'] = $this->companyInitial($validated['company_name']);
        $validated['status'] = '1';
        $validated['created_by'] = auth()->id() ?? 1;
        $validated['created_date'] = now();

        try {
            Company::create($validated);

            return redirect()->route('web.company.index')->with('success', 'Perusahaan berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create company: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = Company::all();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $buttons = '';

                if(in_array('company.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->company_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array('company.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->company_id.'" data-name="'.$row->company_name.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data = Company::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $data = Company::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'company_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_company', 'company_name')->ignore($id, 'company_id')->where(fn ($q) => $q->where('status', 1))
            ],
            'remarks'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $validated['company_initial'] = $this->companyInitial($validated['company_name']);
        $validated['updated_date'] = now();
        $validated['updated_by'] = auth()->id() ?? 1;

        try {
            $data->update($validated);

            return redirect()->route('web.company.index')->with('success', 'Perusahaan berhasil dirubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update company: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Company::findOrFail($id);

        try {
            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => auth()->id() ?? 1
            ]);

            return redirect()->route('web.company.index')->with('success', 'Company deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete company: ' . $e->getMessage());
        }
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
