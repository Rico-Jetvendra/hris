<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller{
    public function index(){
        return view('login');
    }

    public function login(){
        // return view('login');
    }
}

?>
