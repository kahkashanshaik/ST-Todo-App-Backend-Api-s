<?php

use App\Http\Controllers\VerifyEmailAccountController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    $user = User::find(3);
    return view('mail.verify-email')->with([
        'link' => env('FRONTEND_APP_URL') . 'verify-email/akdfjaksdhfkasdhs',
    ]);
});

Route::get('verify-email/{token}', [VerifyEmailAccountController::class, 'verifyEmail']);
