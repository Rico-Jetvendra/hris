<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Branch extends Model{
    protected $table = 't_branch';

    protected $primaryKey = 'branch_id';

    public $incrementing = true;

    protected $keyType = 'int';

    // Enable Laravel timestamps with custom column names
    public $timestamps = true;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    const DELETED_AT = 'deleted_date';

    protected $fillable = [
        'branch_name',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_date',
    ];

    protected $casts = [
        'branch_id'  => 'integer',
        'status'       => 'integer',
        'created_by'   => 'integer',
        'updated_by'   => 'integer',
        'deleted_by'   => 'integer',
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
        'deleted_date' => 'datetime',
    ];

    public function employees(){
        return $this->hasMany(Employee::class, 'employee_branch', 'branch_id');
    }

    protected static function booted(){
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('t_branch.status', 1)->where('t_branch.deleted_date', null);
        });
    }
}
