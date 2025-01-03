<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Masters\Entities\State;
use Modules\Masters\Entities\Country;
use Modules\Masters\Entities\Subject;
use Modules\Masters\Entities\Grade;
use Modules\User\Entities\ModelRole;
use Illuminate\Support\Facades\Http;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::REGISTER;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {

        $subjects = Subject::where('status','active')->orderBy('name','asc')->get();
        $grades = Grade::where('status','active')->orderBy('name','asc')->get();
        $countries = Country::where('status','active')->orderBy('name','asc')->get();

        // Pass data to the view
        return view('auth.register', [
            'subjects' => $subjects,
            'grades' => $grades,
            'countries' => $countries
        ]);
    }

    public function verifyUser($user_id){
        $user = User::where('uuid',$user_id)->first();
        if($user){
            if(!is_null($user->verified_at)){
                return redirect()->route('login')->with('error', 'User already verified');
            }

            $user->verified_at = date('Y-m-d H:i:s');
            if($user->save()){
                return redirect()
                ->route('login')
                ->with('success', 'Email verified successfully');
            }

        }else{
            return redirect()->route('register')->with('error', 'User not found');
        }
    }


    public function getStateByCountry($country_id)
    {
        $states   =   State::where('country_id',$country_id)->orderBy('name','asc')->get();  
        if(!empty($states->toArray())){
            return $arrayName = array('states' => $states);
        }else{
            return false;
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => 'required|recaptchav3:register,0.5'
        ]);
    }

    public function registerUser(Request $request)
    {
        // Validate other input fields first
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => 'required' // Ensure reCAPTCHA response is present
        ]);
        // Verify the reCAPTCHA response
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = env('GOOGLE_RECAPTCHA_SECRET'); // Your secret key from the .env file
        $verificationResponse = Http::get('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $request->input('g-recaptcha-response'),
        ]);

        // Parse the response
        $verificationData = $verificationResponse->json();

        if (!$verificationData['success']) {
            return redirect()->back()->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.']);
        }

        // Create the user
        $user = new User();
        $user->uuid = \Str::uuid();
        $user->email = $request->email;
        $user->password = Hash::make($request->input("password"));
        $user->subject_id = $request->subject;
        $user->grade_id = $request->grade;
        $user->country = $request->country_id;
        $user->state = $request->state;
        $user->dob = date('Y-m-d', strtotime($request->dob));

        if ($user->save()) {
            $modelRole = new ModelRole();
            $modelRole->role_id = 2;
            $modelRole->model_type = 'Modules\User\Entities\User';
            $modelRole->model_id = $user->id;
            $modelRole->save();

            $verify_link = url('/verify-account/' . $user->uuid);

            $mailBody = '<a href="' . $verify_link . '" target="_blank" rel="noopener" title="reset password" style="text-decoration: none; font-size: 16px; color: #fff; background: #FF8000; border-radius: 5px;display: block;text-align: center;padding: 15px 5px; float:left; width: 25%;" margin-top:10px;> Verify Account </a>';
            $to_name = $user->email;
            $to_email = $user->email;
            $mailSubject = 'Verify Your Account';
            $data = array('name' => $to_name, "body" => $mailBody, 'mailSubject' => $mailSubject);

            \Mail::send('emails.email_template', $data, function ($message) use ($to_name, $to_email, $mailBody, $mailSubject) {
                $message->to($to_email, $to_name)
                    ->subject($mailSubject)
                    ->from(env('MAIL_FROM'), 'Laravel Forms');
            });

            return redirect()
                ->route('register')
                ->with('success', 'Registration successful! Please verify your email to activate your account.');
        } else {
            return redirect()->route('register')->with('error', 'Failed to register. Please try again.');
        }
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'subject_id' => $data['subject'],
            'grade_id' => $data['grade'],
            'country' => $data['country_id'],
            'state' => $data['state'],
            'dob' => date('Y-m-d', strtotime($data['dob'])),
        ]);
    }



    protected function registered(Request $request, $user)
    {
        $verify_link = url('/verify-account/' . $user->id);

        $mailBody = '<a href="' . $verify_link . '" target="_blank" rel="noopener" title="reset password" style="text-decoration: none; font-size: 16px; color: #fff; background: #FF8000; border-radius: 5px;display: block;text-align: center;padding: 15px 5px; float:left; width: 25%;" margin-top:10px;> Verify Account </a>';
        $to_name = $user->name;
        $to_email = $user->email;
        $mailSubject = 'Verify Your Account';
        $data = array('name' => $to_name, "body" => $mailBody, 'mailSubject' => $mailSubject);

        \Mail::send('emails.email_template', $data, function ($message) use ($to_name, $to_email, $mailBody, $mailSubject) {
            $message->to($to_email, $to_name)
                ->subject($mailSubject)
                ->from(env('MAIL_FROM'), 'Laravel Forms');
        });

        return redirect()
            ->route('register')
            ->with('success', 'Registration successful! Please verify your email to activate your account.');
    }
}
