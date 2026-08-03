<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CallComment extends Model{
    protected $connection = 'mysql3';
    protected $table = 't_call_comment';
    protected $primaryKey = 'call_comment_id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'call_id',
        'replies_id',
        'comment',
        'created_date',
        'created_by',
    ];

    protected $casts = [
        'call_id'      => 'integer',
        'replies_id'   => 'integer',
        'created_date' => 'datetime',
    ];

    protected function getCreatedDateAttribute($value){
        return $value ? Carbon::parse($value)->tz(session('user')->timezone)->format('Y-m-d H:i:s') : null;
    }

    protected static function booted(){
        static::addGlobalScope('latest', function ($query) {
            $query->orderBy('t_call_comment.created_date', 'desc');
        });

        static::creating(function ($model) {
            $model->created_by   = session('user')->id ?? 1;
            $model->created_date = now();
        });
    }
}
