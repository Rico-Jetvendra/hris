<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Leave extends Model{
    protected $table = 't_leave';
    protected $primaryKey = 'leave_id';
    public $timestamps = false;

    protected $fillable = [
        'employee_id',
        'leave_entitlement',
        'leave_period',
    ];

    protected $casts = [
        'leave_id'          => 'integer',
        'employee_id'       => 'integer',
        'leave_period'      => 'integer',
    ];

    protected static function booted(){
        static::addGlobalScope('latest', function ($query) {
            $query->orderBy('t_leave.leave_period', 'desc')->orderBy('t_leave.employee_id', 'desc');
        });
    }

}
