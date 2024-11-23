<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActionToken;
use Illuminate\Http\Request;

class VerifyEmailAccountController extends Controller
{
    public function verifyEmail(Request $request, String $token)
    {
        $isTokenExists = UserActionToken::where('token', $token)->where('expires_at', '>', now())->get()->first();
        if ($isTokenExists) {
            $user = User::find($isTokenExists->user_id);
            $user->email_verified_at = now();
            $user->save();
            $isTokenExists->delete();
        }
        $isEmailVerified = isset($isTokenExists) ? true : false;
        return view('verify-email', compact('isEmailVerified'));
    }
}
