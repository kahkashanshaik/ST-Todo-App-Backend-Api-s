<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\UserActionToken;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required',
            'password' => 'required|min:6|max:8',
        ]);
        $user = User::create($data);
        $token = Str::uuid();
        UserActionToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addWeek(),
            'action' => 'verify_email'
        ]);
        Mail::to($data['email'])->send(new VerifyEmail($user, $token));
        // event(new Registered($user));
        return response()->json([
            "status" => 'success',
            "message" => 'User created successfully'
        ], 200);
    }

    public function login(Request $request)
    {
        // Get Email & password 
        $credentials = $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);
        // Compare user email ID and password with databse users.
        // If user is not authenticated
        if (!Auth::attempt($credentials)) {
            return response()->json([
                "status" => 'failure',
                "message" => 'User not authorized!',
            ], Response::HTTP_BAD_REQUEST);
        }
        // If in case user got authenticated.
        //  generate token for that login user.
        $user = User::where('email', $credentials['email'])->get();
        $token = $request->user()->createToken('auth_token')->plainTextToken;
        // return response.
        return response()->json([
            "status" => 'success',
            "token" =>  $token,
            "user" =>  $user,
            "message" => 'User Logged In successfully',
        ], 200);
    }

    public function getProfileDetails()
    {
        $id = auth()->user()->id;
        $userDetails = User::where('id', $id)->get();
        return response()->json([
            "status" => 'success',
            "user" =>  $userDetails,
            "message" => 'User details got fetched!',
        ], 200);
    }

    public function updateProfileDetails(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'password' => 'nullable|min:6|max:8',
            'department_id' => 'required|exists:departments,id',
            'address' => 'nullable'
        ]);
        $user = User::where('id', auth()->user()->id)->get()->first();
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        return response()->json([
            "status" => 'success',
            "user" =>  $user,
            "message" => 'User details got pdated!',
        ], 200);
    }
}
