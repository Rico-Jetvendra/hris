<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Carbon\Carbon;

class EmployeeCompany extends Model{
    protected $table = 't_employee_company';

    protected $primaryKey = 'employee_company_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const DELETED_AT = 'deleted_date';

    protected $fillable = [
        'employee_nik',
        'employee_id',
        'department_id',
        'company_id',
        'position_id',
        'branch_id',
        'entry_date',
        'end_of_contract',
        'contract_status',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_date',
    ];

    protected $casts = [
        'employee_id'           => 'integer',
        'department_id'         => 'integer',
        'company_id'            => 'integer',
        'position_id'           => 'integer',
        'branch_id'             => 'integer',
        'status'                => 'integer',
        'created_by'            => 'integer',
        'updated_by'            => 'integer',
        'deleted_by'            => 'integer',
        'contract_status'       => 'integer',
        'created_date'          => 'datetime',
        'updated_date'          => 'datetime',
        'deleted_date'          => 'datetime',
        'entry_date'            => 'date:Y-m-d',
        'end_of_contract'       => 'date:Y-m-d',
    ];

    protected static function booted(){
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('t_employee_company.status', 1)->where('t_employee_company.deleted_date', null);
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

?>
