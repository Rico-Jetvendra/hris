<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VehicleAssignment extends Model{
    protected $table = 't_vehicle_assignment';

    protected $primaryKey = 'vehicle_assignment_id';

    public $incrementing = true;

    protected $keyType = 'int';

    // Enable Laravel timestamps with custom column names
    public $timestamps = true;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const DELETED_AT = 'deleted_date';

    protected $fillable = [
        'vehicle_id',
        'employee_id',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_date',
    ];

    protected $casts = [
        'vehicle_assignment_id' => 'integer',
        'vehicle_id'            => 'integer',
        'employee_id'           => 'integer',
        'status'                => 'integer',
        'created_by'            => 'integer',
        'updated_by'            => 'integer',
        'deleted_by'            => 'integer',
        'created_date'          => 'datetime',
        'updated_date'          => 'datetime',
        'deleted_date'          => 'datetime',
    ];

    protected static function booted(){
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('t_vehicle_assignment.status', 1)->where('t_vehicle_assignment.deleted_date', null);
        });

        static::creating(function ($model) {
            $model->created_by = session('user')->id ?? 1;
            $model->created_date = now();
        });

        static::updating(function ($model) {
            $model->updated_by = session('user')->id ?? 1;
            $model->updated_date = now();
        });
    }
}
