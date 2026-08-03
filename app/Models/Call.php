<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Call extends Model{
    protected $connection = 'mysql3';
    protected $table = 't_call';
    protected $primaryKey = 'call_id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'call_activity',
        'call_description',
        'call_direction',
        'call_status',
        'call_started',
        'call_duration',
        'customer_type',
        'customer_id',
        'customer_name',
        'kode_sales',
        'status',
        'created_date',
        'created_by',
        'updated_date',
        'updated_by',
        'deleted_date',
        'deleted_by',
    ];

    protected $casts = [
        'call_id'         => 'integer',
        'call_direction'  => 'integer',
        'call_status'     => 'integer',
        'call_duration'   => 'integer',
        'customer_type'   => 'integer',
        'customer_id'     => 'integer',
        'kode_sales'      => 'integer',
        'status'          => 'integer',
        'created_by'      => 'integer',
        'updated_by'      => 'integer',
        'deleted_by'      => 'integer',
        'call_started'    => 'datetime',
        'created_date'    => 'datetime',
        'updated_date'    => 'datetime',
        'deleted_date'    => 'datetime',
    ];

    protected function getCallStartedAttribute($value){
        return $value ? Carbon::parse($value)->tz(session('user')->timezone)->format('Y-m-d H:i:s') : null;
    }

    protected function getCallEndedAttribute($value){
        return $value ? Carbon::parse($value)->tz(session('user')->timezone)->format('Y-m-d H:i:s') : null;
    }

    protected function getCreatedDateAttribute($value){
        return $value ? Carbon::parse($value)->tz(session('user')->timezone)->format('Y-m-d H:i:s') : null;
    }

    protected static function booted(){
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('t_call.status', 1)->where('t_call.deleted_date', null);
        });

        static::addGlobalScope('latest', function ($query) {
            $query->orderBy('t_call.created_date', 'desc');
        });

        static::creating(function ($model) {
            $model->created_by   = session('user')->id ?? 1;
            $model->created_date = now();
        });

        static::updating(function ($model) {
            $model->updated_by   = session('user')->id ?? 1;
            $model->updated_date = now();
        });
    }
}
