<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;

class User extends Authenticatable implements JWTSubject{
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
        "created_date",
        "created_by",
        "updated_date",
        "updated_by",
        "deleted_date",
        "deleted_by",
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        $jti = (string) Str::uuid();
        return [
            'username' => $this->username,
            'email' => $this->email,
            'kode_sales' => $this->kode_sales,
            'jti' => $jti,
            'unique_id' => md5('TAKING_ORDER|'.$jti)
        ];
    }

    public function ordersCreated(){
        return $this->hasMany(Order::class, 'created_by', 'id');
    }

    public function ordersUpdated(){
        return $this->hasMany(Order::class, 'updated_by', 'id');
    }

    public function ordersDeleted(){
        return $this->hasMany(Order::class, 'deleted_by', 'id');
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function getWhitelist(){
        return $this->whitelist;
    }

    public function getDefaultSort(){
        return $this->defaultSort;
    }
}

?>
