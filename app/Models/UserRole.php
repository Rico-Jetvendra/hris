<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 't_user_roles';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $whitelist = [
        "user_id",
        "role_id",
    ];
    protected $defaultSort = 'created_at';

    protected $fillable = [
        "user_id",
        "role_id",
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
