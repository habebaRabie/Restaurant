<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AdminVerifyEmailController extends Controller
{
    /**
     * Mark the authenticated admin's email address as verified.
     *
     * @authenticated 
     * 
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        //dd('msg');
        if ($request->user('admin-api')->hasVerifiedEmail()) {
            return response()->json(['msg'=>'email already verified',201]);
        }

        if ($request->user('admin-api')->markEmailAsVerified()) {
            event(new Verified($request->user('admin-api')));
        }

        return response()->json(['msg'=>'Successfully verified',201]);
    }
}
