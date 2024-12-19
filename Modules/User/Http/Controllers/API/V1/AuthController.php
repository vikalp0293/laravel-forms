<?php

namespace Modules\User\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiBaseController;
use Auth;
use Illuminate\Http\Request;
use Modules\User\Entities\User;

class AuthController extends ApiBaseController
{

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"User"},
     *     summary="api for user login",
     *     operationId="login",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email",type="integer"),
     *                 @OA\Property(property="password",type="string"),
     *                 example={"email": "9999999999", "password": "123456"}
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200,description="OK")
     * )
     */
    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => request('email'), 'password' => request('password'), 'is_approved' => 1])) {

                $user = Auth::user();
                if(is_null($user->verified_at)){
                    return $this->sendFailureResponse('Your account is not verified. Please verify your account.', 412);
                }


                $role = User::getUserRoles($user->id);

                $roleInfo = $user->roles->pluck('id', 'label')->toArray();
                $label    = array_keys($roleInfo);
                $label    = $label[0];

                $token = $user->createToken('MyApp')->accessToken;

                $roleData = array(
                    'name'    => $role[0],
                    'label'   => $label,
                    'role_id' => $roleInfo[$label],
                );

                $data['user']          = $user;
                $data['user']['role']  = $roleData;
                $data['user']['token'] = $token;

                return $this->sendSuccessResponse($data, 200, $token);
            } else {
                return $this->sendFailureResponse('Invalid login credentials or your account is not approved', 412);
            }

        } catch (\Exception $e) {
            return $this->sendFailureResponse($e->getMessage());
        }
    }
}
