<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class VehicleDocument extends Model{
    protected $table        = 't_vehicle_document';
    protected $primaryKey   = 'vehicle_document_id';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = false;

    protected $fillable = [
        'vehicle_id',
        'document_name',
        'document_type',
        'document_size',
        'created_by',
        'created_date',
    ];

    protected $casts = [
        'vehicle_document_id' => 'integer',
        'vehicle_id'          => 'integer',
        'document_size'       => 'integer',
        'created_by'          => 'integer',
        'created_date'        => 'datetime',
    ];

    public function getCreatedDateAttribute($value){
        return $value ? Carbon::parse($value)->format('d-m-Y') : null;
    }

    public function getDocumentNameAttribute($value){
        return $value ? Storage::url($value) : null;
    }

    protected static function booted(){
        static::creating(function ($model) {
            $model->created_by = session('user')->id ?? 1;
            $model->created_date = now();
        });
    }
}
