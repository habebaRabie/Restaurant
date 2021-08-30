<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @authenticated
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['msg' => 'Verification email already sent ',201]);
        }

        $request->user()->sendEmailVerificationNotification();

        if ($request->wantsJson())
        {
            return response()->json(['msg' => 'Email sent',201]);
        }

       else return response()->json(['msg' =>'verification-link-sent',201]);
    }
}
