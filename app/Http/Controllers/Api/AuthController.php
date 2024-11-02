<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request){
        
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required|min:6|max:8',
        ]);
        User::create($data);
        return response()->json([
            "status" => 'success',
            "message" => 'User created successfully' 
        ], 200);
    }
}
