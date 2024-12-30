<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Modules\User\Entities\User;
use Modules\User\Entities\Tier;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

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

        $credentials = ['email' => $request->get('email'), 'password' => $request->get('password'), 'status' => 1];
        $ipAddress = $request->ip();
        $user = User::where('email', $request->get('email'))->first();

        // Handle email verification
        if ($user) {
            if (is_null($user->verified_at)) {
                return redirect('login')->with('error', 'Oops! Your email is not verified');
            }

            // Check for failed login attempts
            if ($user->total_failed_login >= 5) {
                $lockoutTime = Carbon::parse($user->last_failed_login)->addMinutes(15);
                if (Carbon::now()->lt($lockoutTime)) {
                    $remainingMinutes = $lockoutTime->diffInMinutes(Carbon::now());
                    return redirect('login')->with('error', "Too many failed login attempts. Please try again after $remainingMinutes minutes.");
                } else {
                    // Reset failed attempts after the lockout period
                    $user->total_failed_login = 0;
                    $user->save();
                }
            }
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Update last login details
            $user->last_login = Carbon::now()->toDateTimeString();
            $user->last_login_ip = $ipAddress;
            $user->total_logins = $user->total_logins + 1;

            // Reset failed login attempts on successful login
            $user->total_failed_login = 0;
            $user->last_failed_login = null;
            $user->save();

            // Fetch tier from the `tiers` table
            $tierEntry = Tier::where('email_or_domain', $user->email)->first();

            if (!$tierEntry) {
                // If no exact match is found, check for partial domain match
                $domain = explode('@', $user->email)[1];
                $tierEntry = Tier::where('email_or_domain', $domain)->first();
            }

            $userTier = $tierEntry ? $tierEntry->tier_level : 0;

            $token = $user->createToken('MyApp')->accessToken;
            \Session::put('user_id', $user->id);
            \Session::put('name', $user->first_name . ' ' . $user->last_name);
            \Session::put('email', $user->email);
            \Session::put('user_tier', $userTier);
            \Session::put('token', $token);
            $role = $user->getRoleNames()->toArray();
            \Session::put('role', $role);

            return redirect()->intended('dashboard');
        } else {
            // Handle failed login attempts
            if ($user) {
                $user->last_failed_login = Carbon::now()->toDateTimeString();
                $user->total_failed_login = $user->total_failed_login + 1;
                $user->save();
            }

            return redirect('login')->with('error', 'Oops! You have entered invalid credentials');
        }
    }
}
