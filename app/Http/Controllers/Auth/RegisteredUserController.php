<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the user registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
       // return view('auth.register');
       return response()->json(['message' => 'this is the user register form view']);
    }

    /**
     * Display the admin registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create_admin()
    {
       // return view('auth.register');
       return response()->json(['message' => 'this is the admin register form view']);
    }

    /**
     * Handle an incoming user registration request.
     *
     * Only a super admin can sign up a new admin
     * 
     * 
     * @param   \Illuminate\Http\Request  $request
     * @bodyParam first_name string required   
     * @bodyParam last_name string required   
     * @bodyParam email string required   
     * @bodyParam password string required   
     * @bodyParam password_confirmation string required   
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator()->make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        if ($validator->fails()) {
            return response()->json(['message' => 'Something went wrong',$validator->getMessageBag()], 400);
        } else {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            //Auth::login($user);
            $credentials = $request->only('email', 'password');
            $token = auth::attempt($credentials);
            return response()->json(['message' => 'Successfully created your account, just verify it at your email !','user'=>$user,'AccessToken:'=>$token], 201);
        }
    }

    /**
     * Handle an incoming admin registration request.
     *
     * @authenticated
     * 
     * @param  \Illuminate\Http\Request  $request    
     * @bodyParam email string required  
     * @bodyParam username string required   
     * @bodyParam password string required   
     * @bodyParam password_confirmation string required  
     * @bodyParam superadmin boolean 
     * 
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store_admin(Request $request){
        $admin = Auth::user();

        $admin->superadmin;

        if($admin->superadmin == 1)
        {
            
            $validator = Validator()->make($request->all(), [
                'email' => 'unique:admins,email|required|regex:/^\S*$/u|filled|max:40|email',
                'username'=>'required|regex:/^\S*$/u|filled|max:40',
                'password' => 'required|filled|regex:/^\S*$/u|max:20|min:8|confirmed',
                'superadmin' => 'boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['message' => 'Something went wrong',$validator->getMessageBag()], 400);
            } else {
                $admin = Admin::create([
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'superadmin' => $request->superadmin,
                ]);

                event(new Registered($admin));

                $credentials = $request->only('email', 'password');
                $token = Auth::guard('admin-api')->attempt($credentials);

                if ($token){
                    return response()->json(['message' => 'registered successfully','AccessToken:'=>$token], 200);
                }
                else{
                    return response()->json(['message' => 'Something went wrong'], 400);
                }
                
            }
        }
        else{
            return "You are not a superadmin";
        }
    }
}