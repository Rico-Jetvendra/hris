<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable{
    protected $connection = 'mysql2';
    protected $table = 'tbl_users';
    public $timestamps = false;

    protected $whitelist = [
        "username",
        "email",
        "password",
        "kode_sales",
        "otp",
        "status",
        "active",
        "device_token",
        "app_version",
        "token",
        "token_expiry",
        "no_hp",
        "photo_profile",
    ];
    protected $defaultSort = 'id';

    protected $fillable = [
        "id",
        "username",
        "email",
        "password",
        "kode_sales",
        "otp",
        "status",
        "active",
        "device_token",
        "app_version",
        "token",
        "token_expiry",
        "login_date",
        "no_hp",
        "photo_profile",
        "created_date",
        "created_by",
        "updated_date",
        "updated_by",
        "deleted_date",
        "deleted_by",
    ];

    public function getWhitelist(){
        return $this->whitelist;
    }

    public function getDefaultSort(){
        return $this->defaultSort;
    }
}

?>
