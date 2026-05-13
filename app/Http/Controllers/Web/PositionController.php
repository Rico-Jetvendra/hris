<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller{
    public function index(){
        $data = Position::all();
        $columns = [
            ['label' => 'Nama Jabatan', 'field' => 'position_name'],
        ];

        return view('pages.position.index', compact('data', 'columns'));
    }

    public function create(){
        //
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'position_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_position', 'position_name')->where(fn ($query) => $query->where('status', 1))
            ],
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
            Position::create($validated);

            return redirect()->route('web.position.index')->with('success', 'Jabatan berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create position: ' . $e->getMessage());
        }
    }

    public function show(){

    }

    public function data(){
        $query = Position::all();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $buttons = '';

                if(in_array('position.edit', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-warning btn-edit text-white" data-id="'.$row->position_id.'">
                        <i class="bi bi-pencil"></i>
                    </button>';
                }

                if(in_array('position.delete', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-danger btn-delete" data-id="'.$row->position_id.'" data-name="'.$row->position_name.'">
                        <i class="bi bi-trash"></i>
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $data = Position::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $data = Position::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'position_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_position', 'position_name')->ignore($id, 'position_id')->where(fn ($q) => $q->where('status', 1))
            ],
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

            return redirect()->route('web.position.index')->with('success', 'Jabatan berhasil dirubah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update position: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Position::findOrFail($id);

        try {
            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => session('user')->id ?? 1
            ]);

            return redirect()->route('web.position.index')->with('success', 'Jabatan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete position: ' . $e->getMessage());
        }
    }
}
