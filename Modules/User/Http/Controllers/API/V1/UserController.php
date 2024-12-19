<?php

namespace Modules\User\Http\Controllers\Api\V1;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Role;
use Modules\User\Entities\Address;
use Modules\User\Entities\User;
use Modules\User\Entities\RetailerCategories;
use Modules\User\Entities\State;
use Modules\User\Entities\City;
use Modules\User\Entities\District;
use Modules\User\Entities\OrganizationBuyer;
use Modules\User\Entities\RetailerMapping;
use Modules\User\Http\Requests\UserRequest;
use DB;
use Image;
use DataTables;
use App\Http\Controllers\ApiBaseController;
use Auth;
use Modules\Saas\Entities\Organization;
use Modules\Saas\Entities\Settings;
use Modules\User\Entities\ModuleFeature;
use Modules\User\Entities\OrganizationPermission;
use Modules\Saas\Entities\HomeSetting;
use Modules\Ecommerce\Entities\Wishlist;
use Modules\User\Transformers\UserPresenter;
use Modules\Administration\Entities\Contact;
use Modules\User\Entities\ModelRole;
use Modules\User\Entities\OrganizationStaff;
use Modules\Administration\Entities\Menu;

class UserController extends ApiBaseController
{
    public function __construct() {
        $this->success =  '200';
        $this->ok =  '200';
        $this->accessDenied =  '400';
    }
    

    /**
     * @OA\Get(
     *     path="/api/v1/user-details/{user_id}",
     *     tags={"User"},
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="API to get user details",
     *     operationId="userDetails",
     *     @OA\Parameter(name="user_id",in="path",required=true,
     *     description="user_id",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */

    public function userDetails(Request $request,$user = "")
    {
        try {

            $user = User::from('users as u')
            ->select('u.id', 'u.organization_id', 'u.name', 'u.last_name', 'u.email', 'u.phone_number', 'u.file', 'u.original_name', 'u.shop_name', 'u.gst', 'u.retailer_category', 'u.status', 'u.address1', 'u.address2', 'u.country', 'u.state', 'u.pincode', 'u.district', 'u.city', 'r.id as role_id','r.name as role','r.label as roleName','s.name as stateName','c.name as cityName','d.name as districtName')
            ->leftJoin('states as s','s.id','=','u.state')
            ->leftJoin('cities as c','c.id','=','u.city')
            ->leftJoin('districts as d','d.id','=','u.district')
            ->leftJoin('model_has_roles as mr','mr.model_id','=','u.id')
            ->leftJoin('roles as r','r.id','=','mr.role_id')
            ->where('u.id',$user)
            ->first();
            if($user){
                $user = (new UserPresenter())->present($user);
                return $this->sendSuccessResponse($user, $this->success);
            }else{
                return $this->sendFailureResponse('User not found',412);
            }

        } catch (\Exception $exception) {
            return $this->sendFailureResponse($exception->getMessage());
        }
    }

   

    /**
     * @OA\Post(
     *     path="/api/v1/contact",
     *     tags={"User"},
     *     summary="contact us",
     *     operationId="contact",
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="API for contact us.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name",type="string",example="John"),
     *                 @OA\Property(property="email",type="string",example="John@gmail.com"),
     *                 @OA\Property(property="phone",type="number",example=7412580369),
     *                 @OA\Property(property="company",type="string",example="SIPL"),
     *                 @OA\Property(property="subject",type="string",example="Test"),
     *                 @OA\Property(property="message",type="string",example="lorem lorem lorem lorem ")
     *         )
     *         ),
     *    ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function contact(Request $request)
    {
        try {

            $user = \Auth::user();

            $contact = new Contact();

            $contact->name = $request->post('name');
            $contact->organization_id = $user->organization_id;
            $contact->email = $request->post('email');
            $contact->phone = $request->post('phone');
            $contact->company = $request->post('company');
            $contact->subject = $request->post('subject');
            $contact->message = $request->post('message');
            $contact->created_by = $user->id;

            if($contact->save()){
                return $this->sendSuccessResponse($contact, $this->success);
            }else{
                return $this->sendFailureResponse('Something went wrong',412);
            }

        } catch (\Exception $exception) {
            return $this->sendFailureResponse($exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/my-profile",
     *     tags={"User"},
     *     summary="my-profile",
     *     operationId="myProfile",
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="API to update my profile.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name",type="string",example="John"),
     *                 @OA\Property(property="last_name",type="string",example="Snow")
     *         )
     *         ),
     *    ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function myProfile(Request $request)
    {
        try {

            $authUser = \Auth::user();
            $user = User::findorfail($authUser->id);

            $user->name = $request->name;
            $user->phone_number = $request->phone_number;
            
            $user->address_1 = $request->address_1;
            $user->address_2 = $request->address_2;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->country = $request->country;
            $user->zipcode = $request->zipcode;

            if($user->save()){
                return $this->sendSuccessResponse($user, $this->success);
            }else{
                return $this->sendFailureResponse('Something went wrong',412);
            }

        } catch (\Exception $exception) {
            return $this->sendFailureResponse($exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user-status",
     *     tags={"User"},
     *     summary="user-status",
     *     operationId="updateUserStatus",
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="This api will updates user status of the organization team.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user_id",type="number",example=16),
     *                 @OA\Property(property="status",type="number",example=1)
     *         )
     *         ),
     *    ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function updateUserStatus(Request $request)
    {
        try {

            $user =    User::findorfail($request->user_id);
            if($user){
                $user->status = $request->status;
                if($user->save()){

                    return $this->sendSuccessResponse($user, $this->success);
                }else{
                    return $this->sendFailureResponse('Something went wrong',412);
                }
            }else{
                return $this->sendFailureResponse('User not found',412);
            }

        } catch (\Exception $exception) {
            return $this->sendFailureResponse($exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/update-fcm-token",
     *     tags={"User"},
     *     summary="update-fcm-token",
     *     operationId="updateFcmToken",
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="This api will update fcm token of user.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="fcmToken",type="string",example="")
     *         )
     *         ),
     *    ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function updateFcmToken(Request $request){
        
        try {

            $user = User::findorfail(\Auth::user()->id);
            $user->fcm_token  = $request->input('fcmToken');
            $user->save();

            if($user){
                $user = (new UserPresenter())->present($user);
                return $this->sendSuccessResponse($user, $this->success);
            }else{
                return $this->sendFailureResponse('User not found',412);
            }


        } catch (\Exception $exception) {
            return $this->sendFailureResponse($exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     tags={"User"},
     *     summary="register",
     *     operationId="register",
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="This api for buyer registration.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name",type="string",example=""),
     *                 @OA\Property(property="email",type="string",example=""),
     *                 @OA\Property(property="password",type="string",example=""),
     *                 @OA\Property(property="phone_number",type="string",example=""),
     *                 @OA\Property(property="verify_link",type="string",example="test.com")
     *         )
     *         ),
     *    ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function register(Request $request){
        
        try {


            $checkUser =    User::where('email',$request->email)
                            ->first();

            if($checkUser){
                if($checkUser->email == $request->email){
                    return $this->sendFailureResponse('Email already exists');
                }
                if($checkUser->phone_number == $request->mobileNumber){
                    return $this->sendFailureResponse('Mobile number already exists');
                }
            }

            DB::beginTransaction();
            $user = new User();
            
            $user->name                 = $request->exists("name") ? $request->input("name") : "";
            $user->email                = $request->exists("email") ? $request->input("email") : "";
            $user->password             = \Hash::make($request->input("password"));
            $user->phone_number         = $request->exists("mobileNumber") ? $request->input("mobileNumber") : "";
            $user->status               = 1;
            $user->is_approved          = 1;


            if($user->save()){


                if(!isset($request->createdBy)){
                    $newUser = User::findorfail($user->id);
                    $newUser->created_by = $user->id;
                    $newUser->save();
                }

                $role = Role::where('name',\Config::get('constants.ROLES.CUSTOMER'))->first();

                $modelRole = new ModelRole();
                $modelRole->role_id = $role->id;
                $modelRole->model_type = 'Modules\User\Entities\User';
                $modelRole->model_id = $user->id;
                $modelRole->save();

                if($user->id){

                    $mailBody = '<a href="'.$request->verify_link.'" target="_blank" rel="noopener" title="reset password" style="text-decoration: none; font-size: 16px; color: #fff; background: #FF8000; border-radius: 5px;display: block;text-align: center;padding: 15px 5px; float:left; width: 25%;" margin-top:10px;> Verify Account </a>';
                    $to_name = $user->name;
                    $to_email = $user->email;
                    $mailSubject = 'Verify Your Account';
                    $data = array('name'=>$to_name, "body" => $mailBody,'mailSubject' => $mailSubject);

                    \Mail::send('emails.email_template', $data, function ($message)  use ($to_name, $to_email,$mailBody,$mailSubject) {
                            $message->to($to_email, $to_name)
                            ->subject($mailSubject)
                            ->from(env('MAIL_FROM'),'sevennup');
                        });

                    DB::commit();

                    $message = "A email verification link has been sent your email. Please verify your account.";

                    return $this->sendSuccessResponse($message, $this->success);
                }else{
                    DB::rollback();
                    return $this->sendFailureResponse('Something went wrong!');
                }
            }

        } catch (\Exception $exception) {
            DB::rollback();
            return $this->sendFailureResponse($exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/social-login",
     *     tags={"User"},
     *     summary="social-login",
     *     operationId="socialLogin",
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="This api for social-login.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name",type="string",example=""),
     *                 @OA\Property(property="email",type="string",example="")
     *         )
     *         ),
     *    ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function socialLogin(Request $request){
        
        try {


            DB::beginTransaction();
            $user =    User::where('email',$request->email)
                            ->first();

            if($user){
                $token = $user->createToken('MyApp')->accessToken;
            }else{
                $user = new User();
                $user->name                 = $request->exists("name") ? $request->input("name") : "";
                $user->email                = $request->exists("email") ? $request->input("email") : "";
                $user->verified_at          = date('Y-m-d H:i:s');
                $user->status               = 1;
                $user->is_approved          = 1;

                if($user->save()){


                    if(!isset($request->createdBy)){
                        $newUser = User::findorfail($user->id);
                        $newUser->created_by = $user->id;
                        $newUser->save();
                    }

                    $role = Role::where('name',\Config::get('constants.ROLES.CUSTOMER'))->first();

                    $modelRole = new ModelRole();
                    $modelRole->role_id = $role->id;
                    $modelRole->model_type = 'Modules\User\Entities\User';
                    $modelRole->model_id = $user->id;
                    $modelRole->save();

                    if($user->id){
                        $token = $user->createToken('MyApp')->accessToken;
                    }else{
                        DB::rollback();
                        return $this->sendFailureResponse('Something went wrong!');
                    }
                }
            }

            $role = User::getUserRoles($user->id);

            $roleInfo = $user->roles->pluck('id', 'label')->toArray();
            $label    = array_keys($roleInfo);
            $label    = $label[0];



            $roleData = array(
                'name'    => $role[0],
                'label'   => $label,
                'role_id' => $roleInfo[$label],
            );

            $data['user']          = $user;
            $data['user']['role']  = $roleData;
            $data['user']['token'] = $token;
            DB::commit();

            return $this->sendSuccessResponse($data, 200, $token);

        } catch (\Exception $exception) {
            DB::rollback();
            return $this->sendFailureResponse($exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/verify-account",
     *     tags={"User"},
     *     summary="verify-account",
     *     operationId="verifyAccount",
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="This api will verify-account.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email",type="string",example="test@test.com")
     *         )
     *         ),
     *    ),
     *     @OA\Response(response=200,description="OK"),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */
    public function verifyAccount(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user){

            if(!is_null($user->verified_at)){
                return $this->sendFailureResponse('Account already verified', 412);    
            }

            $user->verified_at = date('Y-m-d H:i:s');
            $user->save();
            return $this->sendSuccessResponse('Account verified successfully.', 200);
        }else{
            return $this->sendFailureResponse('Email not found', 412);
        }
    }

    public function getLanguage(Request $request,$lang = "")
    {

        if($lang == 'en'){
            $json = file_get_contents(public_path('lang/en.json'));
        }elseif($lang == 'hi'){
            $json = file_get_contents(public_path('lang/hi.json'));
        }elseif($lang == 'tamil'){
            $json = file_get_contents(public_path('lang/tamil.json'));
        }elseif($lang == 'telugu'){
            $json = file_get_contents(public_path('lang/telugu.json'));
        }elseif($lang == 'kannada'){
            $json = file_get_contents(public_path('lang/kannada.json'));
        }else{
            $json = file_get_contents(public_path('lang/en.json'));
        }

        $json = json_decode($json,true);
        return $this->sendSuccessResponse($json, 200);
    }
}
