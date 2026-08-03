<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VisitAttachment extends Model{
    protected $connection   = 'mysql3';
    protected $table        = 't_visit_attachment';
    protected $primaryKey   = 'visit_attachment_id';
    public $incrementing    = true;
    protected $keyType      = 'int';
    public $timestamps      = false;

    protected $fillable = [
        'visit_id',
        'visit_attachment',
        'size',
        'file_type',
        'status',
        'created_date',
        'created_by',
    ];

    protected $casts = [
        'visit_attachment_id' => 'integer',
        'visit_id'            => 'integer',
        'size'                => 'integer',
        'status'              => 'integer',
        'created_by'          => 'integer',
        'created_date'        => 'datetime',
    ];

    public function getVisitAttachmentAttribute($value){
        return $value ? env('TAKING_ORDER_API').'storage/'.$value : null;
    }

    protected static function booted(){
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('t_visit_attachment.status', 1);
        });

        static::addGlobalScope('latest', function ($query) {
            $query->orderBy('t_visit_attachment.created_date', 'desc');
        });

        static::creating(function ($model) {
            $model->created_by   = auth()->id() ?? 1;
            $model->created_date = now();
        });
    }
}
