<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the user email verification prompt.
     *
     * @authenticated
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        if($request->user()->hasVerifiedEmail())
                    return response()->json(['msg'=> 'Email already verified!'],200);
                    else return response()->json(['view'=> 'this is the Email verifiaction view'],200);
    }
}
