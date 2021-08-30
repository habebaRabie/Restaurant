<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return response()->json(['msg'=>'This is the password reset link request form'],201);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * This api handles the form submission
     * We will send the password reset link to this user. Once we have attempted
     * to send the link, we will examine the response then see the message we
     * need to show to the user. Finally, we'll send out a proper response.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam email string required
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * 
     * @response 
     * {
     *  "status",
     * "We have emailed your password reset link!"
     * }
     */
    public function store(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
        ]);


        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT){
                    return response()->json(['status', __($status)],201) ;}
                    else {
                        return response()->json(['Error msg' => __($status)],500);
                    }
    }
}

