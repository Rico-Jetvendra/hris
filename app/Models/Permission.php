<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 't_permissions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $whitelist = [
        "name",
        "description",
        "status",
    ];
    protected $defaultSort = 'created_at';

    protected $fillable = [
        "name",
        "description",
        "status",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "deleted_at",
        "deleted_by",
    ];

    public function getWhitelist(){
        return $this->whitelist;
    }

    public function getDefaultSort(){
        return $this->defaultSort;
    }

}
