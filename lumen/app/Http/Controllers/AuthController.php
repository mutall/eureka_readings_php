<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuthController extends Controller {

    public function register() {
        return view('auth/register');
    }

    public function login() {
        return view('auth/login');
    }

    public function insert(Request $request) {
        try {
            DB::table('user')->insert([
                'name' => $request->input('username'),
                'type' => $request->input('type'),
                'password' => md5($request->input('password')),
                'number' => $request->input('number'),
                'model' => $request->input('model'),
                'full_name' => $request->input('fullname'),
            ]);
            return view('auth/success', ["message"=>"Record Inserted"]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $request;
    }
}
