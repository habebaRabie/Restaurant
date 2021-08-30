<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * should contain email, new password & password confirmation fields
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return response()->json(['msg'=>'This is the reset password form'],201);
   
    }

    /**
     * Handle an incoming new password request.
     *
     * This api handles the form submission
     * Here we will attempt to reset the user's password. If it is successful we
     * will update the password on an actual user model and persist it to the
     * database. Otherwise we will parse the error and return the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam token mixed required
     * @bodyParam emai string required
     * @bodyParam password string required
     * @bodyParam password_confirmation string required  
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * 
     * @response {
     * "msg": "this is the user login form, login with the new password ",
     * "0": "status",
     * "1": "Your password has been reset!"
     * }
     */
    public function store(Request $request)
    {
       
        $validator = Validator()->make($request->all(),[
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
             ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Something went wrong',$validator->getMessageBag()], 400);
        }


        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

       
        if ($status == Password::PASSWORD_RESET)
        {
                    return response()->json(['msg'=>'this is the user login form, login with the new password ','status', __($status)],201); }
                    else{
                    return response()->json(['email' => __($status)],500);
    }
}
}
