<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;


use App\PasswordReset;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Helpers;
use Modules\User\Entities\User;
use Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function sendPasswordLink(Request $request)
    {


        $validator = \Validator::make($request->all(), [
            'email' => [
                'required'
            ],
        ]);
        
        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user =User::where('email',$request->email)->first();
        if($user){
            $passwordReset = PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    // 'token' => Uuid::generate()->string
                    'token' => Str::random(60)
                ]
            );
            if ($passwordReset) {

                $url = url('forgot-password/' . $passwordReset->token);
                $user->notify(
                    new PasswordResetRequest($passwordReset->token,$url,$user->name,$user->email)
                );

                return redirect('password/reset')->with('message', 'A reset password link sent on your email id.');
            }
        }else{
            return redirect('password/reset')->with('error', 'Email not found');            
        }
    }

    public function find($token)
    {

        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset){
            return redirect('password/reset')->with('error', 'Link Expired');
        }

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return redirect('password/reset')->with('error', 'Link Expired');
        }
        return view('auth/passwords/reset_password',['token' => $token]);
    }

    public function reset_password(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'new_password' => [
                'required'
            ],
            'confirm_password' => [
                'required', function ($attribute, $value, $fail) use($request) {
                    if ($value != $request->new_password) {
                        $fail('New password and Confirm password didn\'t match');
                    }
                },
            ]
        ]);
        
        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $passwordReset = PasswordReset::where([
            ['token', $request->token],
        ])->first();
        if (!$passwordReset)
            return redirect('password/reset')->with('error', 'Link Expired');

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return redirect('password/reset')->with('error', 'User not exists');

        $user->password = \Hash::make($request->new_password);
        if($user->save()){
            $passwordReset->delete();
            $user->notify(new PasswordResetSuccess($passwordReset));
            return redirect('login')->with('message', 'Password updated successfully.');
        }else{;
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
