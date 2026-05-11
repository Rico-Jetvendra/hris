<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware{
    public function handle(Request $request, Closure $next, $permission){
        $permissions = session('permission', []);

        if(!in_array($permission, $permissions)){
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses!');
        }

        return $next($request);
    }
}
