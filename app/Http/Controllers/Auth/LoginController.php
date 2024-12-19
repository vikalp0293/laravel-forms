<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Modules\User\Entities\User;
use Modules\Saas\Entities\Organization;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function postLogin(Request $request)
    {
        request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
     
        $credentials = ['email'=>$request->get('email'),'password'=>$request->get('password'),'status' => 1];

        // First, query the user to check if verified_at is not null
        $userVerified = User::where('email', $request->get('email'))->first();

        if($userVerified){
            if(is_null($userVerified->verified_at)){
                return redirect('login')->with('error', 'Opps! Your email is not verified');    
            }
        }else{
            return redirect('login')->with('error', 'Opps! You have entered invalid credentials');
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken;
            \Session::put('user_id', $user->id);
            \Session::put('name', $user->name.' '.$user->last_name);
            \Session::put('email', $user->email);
            \Session::put('token', $token);
            $role = $user->getRoleNames()->toArray();
            return redirect()->intended('dashboard');
        }else{
            $user = User::where('email',$request->get('email'))->first();
            if($user && !$user->status){
                return redirect('login')->with('error', 'Opps! Your account is not active');
            }

            if(is_null($user->verified_at)){
                return redirect('login')->with('error', 'Opps! Your email is not verified');
            }
        }
        return redirect('login')->with('error', 'Opps! You have entered invalid credentials');
    }
}
