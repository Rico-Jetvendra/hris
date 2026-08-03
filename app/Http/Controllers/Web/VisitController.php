<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\VisitAttachment;
use App\Models\VisitComment;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class VisitController extends Controller{
    public function index(){
        $data = $this->getSql()->get();
        $columns = [
            ['label' => 'Nama Kunjungan', 'field' => 'visit_name'],
            ['label' => 'Nama Customer', 'field' => 'customer_name'],
            ['label' => 'Nama Sales', 'field' => 'sales_name'],
        ];

        return view('pages.visit.index', compact('data', 'columns'));
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
                    <button class="btn btn-sm btn-secondary btn-view" data-id="'.$row->visit_id.'">
                        <i class="bi bi-eye"></i>
                    </button>';
                }

                return $buttons;
            })
            ->filterColumn('sales_name', function($query, $keyword) {
                $query->where('rep.repnm', 'like', "%{$keyword}%");
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id){
        $information = $this->getSql()->where('t_visit.visit_id', $id)->firstOrFail();
        $comment     = VisitComment::join('security.tbl_users as u', 'u.id', '=', 't_visit_comment.created_by')->select('t_visit_comment.*', 'u.username')->where('visit_id', $id)->get();
        $attachment  = VisitAttachment::where('visit_id', $id)->get();

        $data = [
            'information' => $information,
            'comment'     => $comment,
            'attachment'  => $attachment,
        ];

        return response()->json($data);
    }

    public function storeComment(Request $request){
        $validator = Validator::make($request->all(), [
            'visit_id'   => 'required|exists:mysql3.t_visit,visit_id',
            'comment'   => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        try {
            $comment = VisitComment::create([
                'visit_id'   => $validated['visit_id'],
                'comment'   => $validated['comment'],
                'created_by'=> session('user')->id ?? 1,
            ]);

            ActivityLogger::create([
                'subject_type'  => 'VisitComment',
                'subject_id'    => $comment->id,
                'new_values'    => $comment->toArray()
            ]);

            $visitComments = VisitComment::join('security.tbl_users as u', 'u.id', '=', 't_visit_comment.created_by')
                ->select('t_visit_comment.*', 'u.username')
                ->where('visit_id', $validated['visit_id'])
                ->orderBy('t_visit_comment.created_date', 'desc')
                ->get();

            return response()->json(['success' => true, 'message' => 'Comment added successfully.', 'comments' => $visitComments]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to add comment: ' . $e->getMessage()], 500);
        }
    }

    public function storeReplies(Request $request){
        $validator = Validator::make($request->all(), [
            'visit_id'           => 'required|exists:mysql3.t_visit,visit_id',
            'replies_id'   => 'required|numeric',
            'comment'           => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        try {
            $reply = VisitComment::create([
                'visit_id' => $validated['visit_id'],
                'replies_id' => $validated['replies_id'],
                'comment' => $validated['comment'],
                'created_by' => session('user')->id ?? 1,
            ]);

            ActivityLogger::create([
                'subject_type' => 'VisitReply',
                'subject_id' => $reply->id,
                'new_values' => $reply->toArray()
            ]);

            $visitReplies = VisitComment::join('security.tbl_users as u', 'u.id', '=', 't_visit_comment.created_by')
                ->select('t_visit_comment.*', 'u.username')
                ->where('visit_id', $validated['visit_id'])
                ->orderBy('t_visit_comment.created_date', 'desc')
                ->get();

            return response()->json(['success' => true, 'message' => 'Reply added successfully.', 'comments' => $visitReplies]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to add reply: ' . $e->getMessage()], 500);
        }
    }

    public function deleteComment($id){
        $comment = VisitComment::findOrFail($id);
        $replies = VisitComment::where('replies_id', $id)->get();

        try {
            $oldValues = $comment->toArray();

            $comment->delete();

            if(!$replies->isEmpty()){
                foreach($replies as $reply){
                    $replyOldValues = $reply->toArray();
                    $reply->delete();

                    ActivityLogger::delete([
                        'subject_type'  => 'VisitReply',
                        'subject_id'    => $reply->id,
                        'old_values'    => $replyOldValues
                    ]);
                }
            }

            ActivityLogger::delete([
                'subject_type'  => 'VisitComment',
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
        $sql = Visit::join('phidb.arsalesrep as rep', 't_visit.kode_sales', '=', 'rep.id')
                    ->select(
                        't_visit.*',
                        'rep.repnm as sales_name',
                        DB::raw(
                            'CASE
                                WHEN t_visit.visit_status = 1 THEN "Planned"
                                WHEN t_visit.visit_status = 2 THEN "Held"
                                WHEN t_visit.visit_status = 3 THEN "Not Held"
                                WHEN t_visit.visit_status = 4 THEN "Check-Out"
                                WHEN t_visit.visit_status = 5 THEN "Done"
                                ELSE "Unknown"
                            END as visit_status_name'
                        ),
                        DB::raw('
                            CASE
                                WHEN t_visit.customer_type = 1 THEN "Draft Customer"
                                WHEN t_visit.customer_type = 2 THEN "Prospect"
                                WHEN t_visit.customer_type = 3 THEN "Existing Customer"
                                ELSE "Unknown"
                            END as customer_type_name
                        '),
                    );

        return $sql;
    }
}
