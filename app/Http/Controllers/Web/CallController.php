<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Call;
use App\Models\CallComment;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CallController extends Controller{
    public function index(){
        $data = $this->getSql()->get();
        $columns = [
            ['label' => 'Nama Panggilan', 'field' => 'call_activity'],
        ];

        return view('pages.call.index', compact('data', 'columns'));
    }

    public function data(){
        $query = $this->getSql();
        $basePermission = permission();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($basePermission) {
                $buttons = '';

                if(in_array($basePermission.'.view', session('permission', []))){
                    $buttons .= '
                    <button class="btn btn-sm btn-secondary btn-view" data-id="'.$row->call_id.'">
                        <i class="bi bi-eye"></i>
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'call_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_call', 'call_name')->where(fn ($query) => $query->where('status', 1))
            ],
            'remarks'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            $id = Call::create($validated);

            ActivityLogger::create([
                'subject_type'  => 'Call',
                'subject_id'    => $id->call_id,
                'new_values'    => $validated
            ]);

            return redirect()->route('web.call.index')->with('success', 'Panggilan berhasil ditambah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to create call: ' . $e->getMessage());
        }
    }

    public function edit($id){
        $information = $this->getSql()->where('t_call.call_id', $id)->firstOrFail();
        $comment     = CallComment::join('security.tbl_users as u', 'u.id', '=', 't_call_comment.created_by')->select('t_call_comment.*', 'u.username')->where('call_id', $id)->get();

        $data = [
            'information' => $information,
            'comment'     => $comment,
        ];

        return response()->json($data);
    }

    public function update(Request $request, $id){
        $data = Call::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'call_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('t_call', 'call_name')->ignore($id, 'call_id')->where(fn ($q) => $q->where('status', 1))
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
                'subject_type'  => 'Call',
                'subject_id'    => $id,
                'new_values'    => $newValues,
                'old_values'    => $oldValues,
            ]);

            return redirect()->route('web.call.index')->with('success', 'Panggilan berhasil di rubah!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update call: ' . $e->getMessage());
        }
    }

    public function destroy($id){
        $data = Call::findOrFail($id);

        try {
            $oldValues = $data->toArray();

            $data->update([
                'status'        => '0',
                'deleted_date'  => now(),
                'deleted_by'    => session('user')->id ?? 1
            ]);

            ActivityLogger::delete([
                'subject_type'  => 'Call',
                'subject_id'    => $id,
                'old_values'    => $oldValues
            ]);

            return redirect()->route('web.call.index')->with('success', 'Panggilan berhasil di hapus!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete call: ' . $e->getMessage());
        }
    }

    public function storeComment(Request $request){
        $validator = Validator::make($request->all(), [
            'call_id'   => 'required|exists:mysql3.t_call,call_id',
            'comment'   => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        try {
            $comment = CallComment::create([
                'call_id'   => $validated['call_id'],
                'comment'   => $validated['comment'],
                'created_by'=> session('user')->id ?? 1,
            ]);

            ActivityLogger::create([
                'subject_type'  => 'CallComment',
                'subject_id'    => $comment->id,
                'new_values'    => $comment->toArray()
            ]);

            $callComments = CallComment::join('security.tbl_users as u', 'u.id', '=', 't_call_comment.created_by')
                ->select('t_call_comment.*', 'u.username')
                ->where('call_id', $validated['call_id'])
                ->orderBy('t_call_comment.created_date', 'desc')
                ->get();

            return response()->json(['success' => true, 'message' => 'Comment added successfully.', 'comments' => $callComments]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to add comment: ' . $e->getMessage()], 500);
        }
    }

    public function storeReplies(Request $request){
        $validator = Validator::make($request->all(), [
            'call_id'           => 'required|exists:mysql3.t_call,call_id',
            'replies_id'   => 'required|numeric',
            'comment'           => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        try {
            $reply = CallComment::create([
                'call_id' => $validated['call_id'],
                'replies_id' => $validated['replies_id'],
                'comment' => $validated['comment'],
                'created_by' => session('user')->id ?? 1,
            ]);

            ActivityLogger::create([
                'subject_type' => 'CallReply',
                'subject_id' => $reply->id,
                'new_values' => $reply->toArray()
            ]);

            $callReplies = CallComment::join('security.tbl_users as u', 'u.id', '=', 't_call_comment.created_by')
                ->select('t_call_comment.*', 'u.username')
                ->where('call_id', $validated['call_id'])
                ->orderBy('t_call_comment.created_date', 'desc')
                ->get();

            return response()->json(['success' => true, 'message' => 'Reply added successfully.', 'comments' => $callReplies]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to add reply: ' . $e->getMessage()], 500);
        }
    }

    public function deleteComment($id){
        $comment = CallComment::findOrFail($id);
        $replies = CallComment::where('replies_id', $id)->get();

        try {
            $oldValues = $comment->toArray();

            $comment->delete();

            if(!$replies->isEmpty()){
                foreach($replies as $reply){
                    $replyOldValues = $reply->toArray();
                    $reply->delete();

                    ActivityLogger::delete([
                        'subject_type'  => 'CallReply',
                        'subject_id'    => $reply->id,
                        'old_values'    => $replyOldValues
                    ]);
                }
            }

            ActivityLogger::delete([
                'subject_type'  => 'CallComment',
                'subject_id'    => $id,
                'old_values'    => $oldValues
            ]);

            return response()->json(['success' => true, 'message' => 'Comment deleted successfully.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to delete comment: ' . $e->getMessage()], 500);
        }
    }

    private function getSql(){
        $sql = Call::leftJoin('t_draft_customer as dc', function ($join) {
                        $join->on('t_call.customer_id', '=', 'dc.draft_customer_id')->where('t_call.customer_type', 1);
                    })
                    ->leftJoin('t_prospect as p', function ($join) {
                        $join->on('t_call.customer_id', '=', 'p.prospect_id')->where('t_call.customer_type', 2);
                    })
                    ->leftJoin('phidb.arcustomer as c', function ($join) {
                        $join->on('t_call.customer_id', '=', 'c.id')->where('t_call.customer_type', 3);
                    })
                    ->leftJoin('phidb.arsalesrep as ar', 'ar.id', '=', 't_call.kode_sales')
                    ->select(
                        't_call.*',
                        DB::raw('
                            CASE
                                WHEN t_call.call_direction = 1 THEN "Outbound"
                                WHEN t_call.call_direction = 2 THEN "Inbound"
                                ELSE "Unknown"
                            END as call_direction_name
                        '),
                        DB::raw('
                            CASE
                                WHEN t_call.call_direction = 1 THEN "Held"
                                WHEN t_call.call_direction = 2 THEN "Not Held"
                                WHEN t_call.call_direction = 3 THEN "Planned"
                                ELSE "Unknown"
                            END as call_status_name
                        '),
                        DB::raw('
                            CASE
                                WHEN t_call.customer_type = 1 THEN "Draft Customer"
                                WHEN t_call.customer_type = 2 THEN "Prospect"
                                WHEN t_call.customer_type = 3 THEN "Existing Customer"
                                ELSE "Unknown"
                            END as customer_type_name
                        '),
                        DB::raw('COALESCE(dc.telephone_number, p.prospect_phone, c.phone) as customer_phone'),
                        DB::raw('DATE_ADD(t_call.call_started, INTERVAL t_call.call_duration SECOND) as call_ended'),
                        'ar.repnm as sales_name'
                    );

        return $sql;
    }
}
