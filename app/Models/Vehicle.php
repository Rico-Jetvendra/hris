<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Carbon\Carbon;

class Vehicle extends Model{

    protected $table = 't_vehicle';

    protected $primaryKey = 'vehicle_id';

    public $incrementing = true;

    protected $keyType = 'int';

    // Use custom timestamp columns
    public $timestamps = true;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const DELETED_AT = 'deleted_date';

    protected $fillable = [
        'vehicle_number',
        'vehicle_brand',
        'vehicle_frame',
        'vehicle_machine',
        'vehicle_color',
        'vehicle_company',
        'vehicle_tax_due',
        'vehicle_reg_due',
        'vehicle_kir',
        'vehicle_sipa',
        'vehicle_bpkb',
        'vehicle_insurance_payment',
        'vehicle_insurance_number',
        'vehicle_insurance_period',
        'vehicle_insurance',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'vehicle_id'                 => 'integer',
        'vehicle_brand'              => 'integer',
        'vehicle_color'              => 'integer',
        'vehicle_company'            => 'integer',
        'vehicle_insurance'          => 'integer',

        'vehicle_bpkb'               => 'boolean',
        'status'                     => 'boolean',

        'vehicle_tax_due'            => 'date',
        'vehicle_reg_due'            => 'date',
        'vehicle_kir'                => 'date',
        'vehicle_insurance_payment'  => 'date',

        'created_date'               => 'datetime',
        'updated_date'               => 'datetime',
        'deleted_date'               => 'datetime',

        'created_by'                 => 'integer',
        'updated_by'                 => 'integer',
        'deleted_by'                 => 'integer',
    ];

    public function getVehicleTaxDueAttribute($value){
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }

    public function getVehicleRegDueAttribute($value){
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }

    public function getVehicleKirDueAttribute($value){
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }

    public function getVehicleInsurancePaymentAttribute($value){
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }

    public function getVehicleInsuranceStartAttribute($value){
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }

    public function getVehicleInsuranceEndAttribute($value){
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }

    protected static function booted(){
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('t_vehicle.status', 1)->where('t_vehicle.deleted_date', null);
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
