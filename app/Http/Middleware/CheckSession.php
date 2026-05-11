<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession{
    public function handle(Request $request, Closure $next){
        if(!session('user')){
            return redirect()->route('web.signin')->with('error', 'Harus login terlebih dahulu!');
        }

        return $next($request);
    }
}
