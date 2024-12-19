<?php

namespace Modules\User\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Modules\User\Entities\User;
use App\PasswordReset;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Support\Facades\Hash;
use SendGrid\Mail\Mail;
use Config;
use Illuminate\Support\Str;

class PasswordResetController extends ApiBaseController
{

    /**
     * @OA\Post(
     *     path="/api/v1/password/forget-password",
     *     tags={"User"},
     *     summary="Forget Password",
     *     operationId="create",
     *     description="This api takes appUrl & email and send password reset link over email.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="appUrl",type="string",example="https://test.com/"),
     *                 @OA\Property(property="email",type="string",example="farhan@mailinator.com"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return $this->sendFailureResponse('This e-mail address is not linked to any user account.');

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );
        if ($user && $passwordReset) {

            $url = $request->appUrl.'/'.$passwordReset->token;
            $to_name = $user->FullName;
            $to_email = $user->email;
            
            $data = array('url'=>$url, "name" => $to_name);

            \Mail::send('emails.reset_password', $data, function ($message)  use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                ->subject('Reset Password')
                ->from(env('MAIL_FROM'),'sevennup');
            });

            // $user->notify(
            //     new PasswordResetRequest($passwordReset->token,$request->appUrl,$user->FullName,$user->email)
            // );
        }

        $passwordData['message'] = 'We have e-mailed your password reset link!';
        return $this->sendSuccessResponse($passwordData);
    }
    /**
     * Find token password reset
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return $this->sendFailureResponse('This link has expired.');
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return $this->sendFailureResponse('This link has expired.');
        }
        return $this->sendSuccessResponse($passwordReset);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/password/reset",
     *     tags={"User"},
     *     summary="Reset Password",
     *     operationId="reset",
     *     description="This api resets the password.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="password",type="string",example=""),
     *                 @OA\Property(property="token",type="string",example=""),
     *             ),
     *         )
     *     ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function reset(Request $request)
    {

        $request->validate([
            // 'email' => 'required|string|email',
            'password' => 'required|string',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
        ])->first();
        // ['email', $request->email]
        if (!$passwordReset)
            return $this->sendFailureResponse('This link has expired.');

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return $this->sendFailureResponse('This e-mail address is not linked to any user account.');

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        // $user->notify(new PasswordResetSuccess($passwordReset));

        /*$email = new \SendGrid\Mail\Mail();
        $email->setFrom(Config::get('constants.EMAIL_CONFIG.FROM_EMAIL'), Config::get('constants.EMAIL_CONFIG.FROM_NAME'));
        $email->addTo(
            $user->email,
            $user->name_en,
            [
                "name" => $user->name_en,
            ]
        );
        $email->setTemplateId(Config::get('constants.EMAIL_TEMPLATES.PASSWORD_RESET_SUCCESS'));
        $sendgrid = new \SendGrid(Config::get('constants.EMAIL_CONFIG.SENDGRID_API_KEY'));

        $response = $sendgrid->send($email);*/

        $data['message'] = 'Your Password has been updated successfully.';
        return $this->sendSuccessResponse($data);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/changePassword",
     *     tags={"User"},
     *     summary="Change Password",
     *     operationId="changePassword",
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="This api Changes the existing password.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="newPassword",type="string",example="Demo@123"),
     *                 @OA\Property(property="oldPassword",type="string",example="Test@123"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string',
        ]);

        if (!(Hash::check($request->oldPassword, Auth::user()->password))) {
            return $this->sendFailureResponse('Old password is incorrect');
        } elseif (strcmp($request->oldPassword, $request->newPassword) == 0) {
            return $this->sendFailureResponse('Old password and new password are same');
        } else {
            $user = Auth::user();
            $user_id = $user->id;

            $userData = User::where('id', $user_id)
                ->update(array(
                    'password' => bcrypt($request->newPassword)
                ));
            if ($userData) {

                /*$email = new \SendGrid\Mail\Mail();
                $email->setFrom(Config::get('constants.EMAIL_CONFIG.FROM_EMAIL'), Config::get('constants.EMAIL_CONFIG.FROM_NAME'));
                $email->addTo(
                    $user->email,
                    $user->name_en,
                    [
                        "name" => $user->name_en,
                    ]
                );
                $email->setTemplateId(Config::get('constants.EMAIL_TEMPLATES.PASSWORD_CHANGED'));
                $sendgrid = new \SendGrid(Config::get('constants.EMAIL_CONFIG.SENDGRID_API_KEY'));

                $response = $sendgrid->send($email);*/

                $tokens = Auth::user()->tokens;
                foreach ($tokens as $token) {
                    $token->revoke();
                    $token->delete();
                }
                $user = Auth::user();
                $newtoken =  $user->createToken('MyApp')->accessToken;

                $data['message'] = 'Your Password has been updated successfully.';
                $data['token'] = $newtoken;
                return $this->sendSuccessResponse($data);
            }
        }
    }
}
