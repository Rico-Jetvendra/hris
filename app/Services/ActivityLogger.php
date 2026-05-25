<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ActivityLogger{
    public static function create($data){
        self::log([
            'user_id'       => session('user')->id ?? null,

            "module"        => "HRIS",
            'action'        => 'CREATE',
            'description'   => 'Created new ' . strtolower($data['subject_type']),

            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),


            'subject_type'  => $data['subject_type'],
            'subject_id'    => $data['subject_id'],

            'new_values'    => $data['new_values']
        ]);
    }

    public static function update($data){
        self::log([
            'user_id'       => session('user')->id ?? null,

            "module"        => "HRIS",
            'action'        => 'UPDATE',
            'description'   => 'Updated ' . strtolower($data['subject_type']),

            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),

            'subject_type'  => $data['subject_type'],
            'subject_id'    => $data['subject_id'],

            'new_values'    => $data['new_values'],
            'old_values'    => $data['old_values'],
        ]);
    }

    public static function delete($data){
        self::log([
            'user_id'       => session('user')->id ?? null,

            "module"        => "HRIS",
            'action'        => 'DELETE',
            'description'   => 'Deleted ' . strtolower($data['subject_type']),

            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),

            'subject_type'  => $data['subject_type'],
            'subject_id'    => $data['subject_id'],

            'old_values'    => $data['old_values'],
            'new_values'     => [
                'status' => 0,
                'deleted_date' => now(),
                'deleted_by' => session('user_security')->id ?? 1
            ]
        ]);
    }

    public static function login($description = null){
        self::log([
            'user_id'       => session('user')->id ?? null,

            'module'        => 'HRIS',
            'action'        => 'LOGIN',
            'description'   => $description ?? 'User logged in',

            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
        ]);
    }

    public static function logout($description = null){
        self::log([
            'user_id'       => session('user')->id ?? null,

            'module'        => 'HRIS',
            'action'        => 'LOGOUT',
            'description'   => $description ?? 'User logged out',

            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
        ]);
    }

    public static function failedLogin($email = null, $description = null){
        self::log([
            'user_id'       => session('user')->id ?? null,

            'module' => 'HRIS',
            'action' => 'FAILED_LOGIN',
            'description' => $description ?? 'Failed login attempt',

            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),

            'new_values' => [
                'email' => $email
            ]
        ]);
    }

    public static function log($data){
        try {
            Http::withHeaders([
                'X-API-KEY' => env('LOGGER_API_KEY')
            ])->post(env('LOGGER_URL') . '/logs', $data);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
