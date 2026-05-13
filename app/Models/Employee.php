<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Carbon\Carbon;

class Employee extends Model{
    protected $table = 't_employee';

    protected $primaryKey = 'employee_id';

    public $incrementing = true;

    protected $keyType = 'int';

    // Enable Laravel timestamps with custom column names
    public $timestamps = true;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const DELETED_AT = 'deleted_date';

    protected $fillable = [
        'employee_name',
        'employee_pob',
        'employee_dob',
        'employee_sex',
        'employee_blood',
        'employee_religion',
        'employee_marriage',
        'employee_ktp',
        'employee_npwp',
        'employee_education',
        'employee_father',
        'employee_mother',
        'employee_email',
        'employee_address',
        'employee_home_phone',
        'employee_phone',
        'employee_remarks',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_date',
    ];

    protected $casts = [
        'employee_id'           => 'integer',
        'employee_sex'          => 'integer',
        'employee_blood'        => 'integer',
        'employee_religion'     => 'integer',
        'employee_marriage'     => 'integer',
        'employee_education'    => 'integer',
        'status'                => 'integer',
        'created_by'            => 'integer',
        'updated_by'            => 'integer',
        'deleted_by'            => 'integer',
        'created_date'          => 'datetime',
        'updated_date'          => 'datetime',
        'deleted_date'          => 'datetime',
        'employee_dob'          => 'date:Y-m-d'
    ];

    public function setEmployeeNameAttribute($value){
        $this->attributes['employee_name'] = strtoupper($value);
    }

    public function setEmployeePobAttribute($value){
        $this->attributes['employee_pob'] = strtoupper($value);
    }

    public function setEmployeeFatherAttribute($value){
        $this->attributes['employee_father'] = strtoupper($value);
    }

    public function setEmployeeMotherAttribute($value){
        $this->attributes['employee_mother'] = strtoupper($value);
    }

    protected static function booted(){
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('t_employee.status', 1)->where('t_employee.deleted_date', null);
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
