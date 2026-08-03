<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Visit extends Model{
    protected $connection   = 'mysql3';
    protected $table        = 't_visit';
    protected $primaryKey   = 'visit_id';
    public $incrementing    = true;
    public $timestamps      = false;


    protected $fillable = [
        'visit_name',
        'visit_result',
        'business_potential',
        'visit_address',
        'visit_latitude',
        'visit_longitude',
        'visit_competitor',
        'visit_status',
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
        'visit_check_in',
        'check_in_latitude',
        'check_in_longitude',
        'visit_check_out',
        'check_out_latitude',
        'check_out_longitude',
        'remarks',
    ];

    protected $casts = [
        'visit_id'        => 'integer',
        'visit_status'    => 'integer',
        'customer_type'   => 'integer',
        'customer_id'     => 'integer',
        'kode_sales'      => 'integer',
        'status'          => 'integer',
        'created_by'      => 'integer',
        'updated_by'      => 'integer',
        'deleted_by'      => 'integer',
        'created_date'    => 'datetime',
        'updated_date'    => 'datetime',
        'deleted_date'    => 'datetime',
    ];

    public function getVisitCheckInAttribute($value){
        return $value ? Carbon::parse($value)->tz(session('user')->timezone)->format('d-m-Y H:i:s') : null;
    }

    public function getVisitCheckOutAttribute($value){
        return $value ? Carbon::parse($value)->tz(session('user')->timezone)->format('d-m-Y H:i:s') : null;
    }

    public function getCreatedDateAttribute($value){
        return $value ? Carbon::parse($value)->tz(session('user')->timezone)->format('d-m-Y H:i:s') : null;
    }

    protected static function booted(){
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('t_visit.status', 1)->where('t_visit.deleted_date', null);
        });

        static::addGlobalScope('latest', function ($query) {
            $query->orderBy('t_visit.created_date', 'desc');
        });

        static::creating(function ($model) {
            $model->kode_sales   = auth()->user()->kode_sales ?? 1;
            $model->created_by   = auth()->id() ?? 1;
            $model->created_date = now();
        });

        static::updating(function ($model) {
            $model->updated_by   = auth()->id() ?? 1;
            $model->updated_date = now();
        });
    }
}
