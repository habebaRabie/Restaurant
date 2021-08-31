<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the user login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
      //  return view('auth.login');
      return response()->json(['message' => 'this is the user login form page']);
    }

    /**
     * Display the admin login view.
     *
     * @return \Illuminate\View\View
     */
    public function create_admin()
    {
      //  return view('auth.login');
      return response()->json(['message' => 'this is the admin login form page']);
    }

    /**
     * Handle an incoming user login request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam remember boolean
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @responseFile responses/user.post.login
     */
    public function store(LoginRequest $request)
    {
          $validator = Validator()->make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember' => 'default:false'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data','Errors in'=>$validator->getMessageBag()], 400);
        } else {
            $credentials = $request->only('email', 'password');
            $token = Auth::attempt($credentials, true);
            if ($token){
                return response()->json(['message' => 'logged in successfully','AccessToken:'=>$token], 200);
            }
            else{
                return response()->json(['message' => 'No such user, invalid email or password'], 400);
            }
        }
    }

    /**
     * Handle an incoming admin login request.
     *
     * @authenticated
     * 
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @bodyParam email string required
     * @bodyParam password string required
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @responseFile responses/admin.post.login
     */
    public function store_admin(Request $request)
    {
    
          $validator = Validator()->make($request->except('superadmin'), [
            'email' => 'required|string',
            'password' => 'required|string',
            
            
        ]);
     
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data','Errors in'=>$validator->getMessageBag()], 400);
        } 
            $credentials = $request->only(['email', 'password']);
            
            $token = Auth::guard('admin-api')->attempt($credentials);
            if ($token){
                return response()->json(['message' => 'logged in successfully','AccessToken:'=>$token], 200);
            }
            else{
                return response()->json(['message' => 'No such user, invalid email or password'], 400);
            }
        
     }

    /**
     * User logout
     * 
     * Destroy an authenticated user session.
     *
     * @authenticated
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
       auth::logout();

       return response()->json(['message' => 'logged out successfully'], 200);
    }

    /**
     * Admin logout
     * 
     * Destroy an authenticated admin session (logout).
     * 
     * @authenticated
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy_admin(Request $request)
    {

       auth::guard('admin-api')->logout();


       return response()->json(['message' => 'logged out successfully'], 200);
    }
}
