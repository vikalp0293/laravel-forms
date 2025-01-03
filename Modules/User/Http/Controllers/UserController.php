<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\UserRequest;
use DB;
use Image;
use Auth;
use DataTables;
use Modules\User\Entities\ModelRole;
use Modules\Ecommerce\Entities\Brand;
use Maatwebsite\Excel\HeadingRowImport;

use App\Models\Audit;
use Modules\Administration\Entities\NotificationTemplate;
use Helpers;
use App\Jobs\SendNotificationJob;
use Modules\Masters\Entities\State;
use Modules\Masters\Entities\Country;
use Modules\Masters\Entities\Subject;
use Modules\Masters\Entities\Grade;

class UserController extends Controller
{

    public function __construct() {

        /* Execute authentication filter before processing any request */
        $this->middleware('auth');

        if (\Auth::check()) {
            return redirect('/');
        }

    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $userPermission = \Session::get('userPermission');

        $authUser = \Auth::user();

        $role = $authUser->getRoleNames()->toArray();

        $data = User::from('users as u')
                ->select('u.id',DB::raw("concat(u.first_name, ' ', u.last_name) as name"),'u.email','u.phone_number','u.created_at','u.updated_at','last_login','last_login_ip','total_logins','r.name as role','r.label as roleName','u.status','u.verified_at','s.name as subject','g.name as grade')
                ->leftJoin('model_has_roles as mr','mr.model_id','=','u.id')
                ->leftJoin('roles as r','mr.role_id','=','r.id')
                ->leftJoin('m_subjects as s','s.id','=','u.subject_id')
                ->leftJoin('m_grades as g','g.id','=','u.grade_id')
                ->where('r.name','generaluser')
                ->where(function ($query) use ($request) {
                    if (!empty($request->toArray())) {
                        if ($request->get('name') != '') {
                            $query->where('u.name', $request->get('name'));
                        }
                        if ($request->get('contact_number') != '') {
                            $query->where('u.phone_number', $request->get('contact_number'));
                        }
                        if((isset($request->fromDate) && isset($request->toDate))) {
                            $dateFrom =  date('Y-m-d',strtotime($request->fromDate));
                            $dateTo =  date('Y-m-d',strtotime($request->toDate. ' +1 day'));
                            $query->whereBetween('u.created_at', array($dateFrom, $dateTo));
                        } elseif (isset($request->fromDate)) {

                            $dateFrom =  date('Y-m-d',strtotime($request->fromDate));
                            $query->where('u.created_at', '>=', $dateFrom);
                        } elseif (isset($request->toDate)) {
                            $dateTo =  date('Y-m-d',strtotime($request->toDate));
                            $query->where('u.created_at', '<=', $dateTo);
                        }
                    }
                })
                ->orderby('u.id','desc')
                ->get();
        $usersCount = 0;
        if(!empty($data->toArray())){
            $usersCount = count($data);
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('name', function($row) use ($userPermission){
                            $detailLink = '#';

                            $username = $row->name.' '.$row->last_name;

                            if(!is_null($row->file)){
                                $file = public_path('uploads/users/') . $row->file;
                            }

                            if(!is_null($row->file) && file_exists($file))
                                $avatar = "<img src=".url('uploads/users/'.$row->file).">";
                            else
                                $avatar = "<span>".\Helpers::getAcronym($username)."</span>";
                            

                            $name = '
                                        <a href="'.$detailLink.'">
                                            <div class="user-card">
                                                <div class="user-avatar bg-primary">
                                                    '.$avatar.'
                                                </div>
                                                <div class="user-info">
                                                    <span class="tb-lead">'.$row->shop_name.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                                    <span>'.$username.' </span>
                                                </div>
                                            </div>
                                        </a>
                                    ';
                            return $name;
                    })
                    ->addColumn('status', function ($row) {
                        if($row->status == 1){
                            $statusValue = 'Active';
                        }else{
                            $statusValue = 'Inactive';
                        }

                        $value = ($row->status == '1') ? 'badge badge-success' : 'badge badge-danger';
                        $status = '
                            <span class="tb-sub">
                                <span class="'.$value.'">
                                    '.$statusValue.'
                                </span>
                            </span>
                        ';
                        return $status;
                    })

                    ->addColumn('verified_at', function ($row) {
                        if(!is_null($row->verified_at)){
                            $statusValue = 'Active';
                        }else{
                            $statusValue = 'Inactive';
                        }

                        $value = (!is_null($row->verified_at)) ? 'badge badge-success' : 'badge badge-danger';
                        $status = '
                            <span class="tb-sub">
                                <span class="'.$value.'">
                                    '.$statusValue.'
                                </span>
                            </span>
                        ';
                        return $status;
                    })


                    ->addColumn('action', function($row) use ($userPermission){
                           $edit = url('/').'/user/edit/'.$row->id;
                           $delete = url('/').'/user/delete/'.$row->id;
                           $confirm = '"Are you sure, you want to delete it?"';

                            $editBtn = "<li>
                                        <a href='".$edit."'>
                                            <em class='icon ni ni-edit'></em> <span>Edit</span>
                                        </a>
                                    </li>";
                            
                            $deleteBtn = "<li>
                                        <a href='".$delete."' onclick='return confirm(".$confirm.")'  class='delete'>
                                            <em class='icon ni ni-trash'></em> <span>Delete</span>
                                        </a>
                                    </li>"; 

                            $logbtn = '<li><a href="#" data-resourceId="'.$row->id.'" class="audit_logs"><em class="icon ni ni-list"></em> <span>Audit Logs</span></a></li>';

                            $changePassword = '';
                            // $changePassword = '<li><a href="#" data-resourceId="'.$row->id.'" class="changePassword"><em class="icon ni ni-lock-alt"></em> <span>Update Password</span></a></li>';

                            $btn = '';
                            $btn .= '<ul class="nk-tb-actio ns gx-1">
                                        <li>
                                            <div class="drodown mr-n1">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                        ';

                           $btn .=       $editBtn."
                                        ".$deleteBtn."
                                        ".$changePassword;

                            $btn .= "</ul>
                                            </div>
                                        </div>
                                    </li>
                                    </ul>";
                        return $btn;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date(\Config::get('constants.DATE.DATE_FORMAT') , strtotime($row->created_at));
                    })

                    ->addColumn('last_login', function ($row) {
                        if(is_null($row->last_login)){
                            return "NA";
                        }else{
                            return date(\Config::get('constants.DATE.DATE_FORMAT_FULL') , strtotime($row->last_login));
                        }
                    })

                    ->rawColumns(['action','created_at','name','updated_at','status','verified_at','last_login'])
                    ->make(true);
        }


        return view('user::index')->with(compact('usersCount'));
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

    
    public function sendVerificationEmail($email)
    {
        $authUser = Auth::user();

        $authUser->temp_email = $email;
        $authUser->save();

        $verify_link = url('/profile/verify-email/' . $authUser->id);

        $mailBody = '<a href="' . $verify_link . '" target="_blank" rel="noopener" title="reset password" style="text-decoration: none; font-size: 16px; color: #fff; background: #FF8000; border-radius: 5px;display: block;text-align: center;padding: 15px 5px; float:left; width: 25%;" margin-top:10px;> Verify Account </a>';
        $to_name = $email;
        $to_email = $email;
        $mailSubject = 'Verify Your Account';
        $data = array('name' => $to_name, "body" => $mailBody, 'mailSubject' => $mailSubject);

        \Mail::send('emails.email_template', $data, function ($message) use ($to_name, $to_email, $mailBody, $mailSubject) {
            $message->to($to_email, $to_name)
                ->subject($mailSubject)
                ->from(env('MAIL_FROM'), 'Laravel Forms');
        });        
    }

    public function verifyEmail($user_id){

        $user = User::where('id',$user_id)->first();
        if($user){

            $user->verified_at = date('Y-m-d H:i:s');
            $user->email = $user->temp_email;

            if($user->save()){
                return redirect('profile')->with('message', 'Email verified successfully');
            }

        }else{
            return redirect()->route('register')->with('error', 'User not found');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function profile()
    {
        $authUser = Auth::user();

        $user =     User::from('users as u')
                    ->select('u.*')
                    ->where('u.id',$authUser->id)
                    ->first();

        $subjects   = Subject::where('status','active')->orderBy('name','asc')->get();
        $grades     = Grade::where('status','active')->orderBy('name','asc')->get();
        $countries  = Country::where('status','active')->orderBy('name','asc')->get();
        $states = State::where('status','active')->orderBy('name','asc')->get();

        return view('user::profile/index')->with(compact('user','subjects','grades','countries','states'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->grade_id = $request->grade;
        $user->subject_id = $request->subject;
        $user->country = $request->country_id;
        $user->state = $request->state;
        $user->district = $request->district;
        $user->city = $request->city;

        \Session::put('name', $request->first_name . ' ' . $request->last_name);

        if($user->save()){
            return redirect('profile')->with('message', trans('messages.PROFILE_UPDATED_SUCCESS'));
        }else{
            return redirect('profile')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function setting()
    {   
        $user = Auth::user();
        return view('user::profile/setting')->with(compact('user'));
    }


    public function updateUserPassword(Request $request){

        $user =User::find($request->password_user_id);
        $user->password = \Hash::make($request->newPassword);
        if($user->save()){
            return redirect()->back()->with('message', trans('messages.PASSWORD_UPDATED'));
        }else{
            return redirect()->back()->with('error', trans('messages.SOMETHING_WENT_WRONG'));
        }
    }
    public function updatePassword(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'oldPassword' => [
                'required', function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, Auth::user()->password)) {
                        $fail('Current Password didn\'t match');
                    }
                },
            ],
            'newPassword' => [
                'required', function ($attribute, $value, $fail) use($request) {
                    if (\Hash::check($value, Auth::user()->password)) {
                        $fail('New password can not be the current password!');
                    }
                },
            ],
            'confirmPassword' => [
                'required', function ($attribute, $value, $fail) use($request) {
                    if ($value != $request->newPassword) {
                        $fail('New password and Confirm password didn\'t match');
                    }
                },
            ]
        ]);


        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user =User::find(Auth::user()->id);
        $user->password = \Hash::make($request->newPassword);
        if($user->save()){
            return redirect('profile/setting')->with('message', trans('messages.PASSWORD_UPDATED'));
        }else{
            return redirect('profile/setting')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            $authUser = \Auth::user();
            $user = User::from('users as u')
                    ->where('u.id',$id)
                    ->first();

            if(!$user){
                return redirect('user')->with('error', 'Invalid user');
            }

            $subjects = Subject::where('status','active')->orderBy('name','asc')->get();
            $grades = Grade::where('status','active')->orderBy('name','asc')->get();
            $countries = Country::where('status','active')->orderBy('name','asc')->get();
            $states = State::where('status','active')->orderBy('name','asc')->get();

            return view('user::create',[
                'subjects' => $subjects,
                'grades' => $grades,
                'countries' => $countries,
                'states' => $states,
                'user' => $user,
            ]);

        } catch (Exception $e) {
            return redirect('user')->with('error', $exception->getMessage());           
        }
    }

    

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {   

        try {

            $checkUser = User::where('id','!=',$id)->where('email',$request->input("email"))->first();
            if($checkUser){
                return redirect('user/edit/'.$id)->with('error', 'Email already exists');
            }

            $authUser = \Auth::user();
            $user = User::from('users as u')
                    ->select('u.*')
                    ->where('u.id',$id)
                    ->first();

            $user->first_name                = $request->exists("first_name") ? $request->input("first_name") : "";
            $user->last_name                = $request->exists("last_name") ? $request->input("last_name") : "";
            $user->email                = $request->exists("email") ? $request->input("email") : "";
            $user->subject_id                = $request->exists("subject") ? $request->input("subject") : "";
            $user->grade_id                = $request->exists("grade") ? $request->input("grade") : "";
            $user->country                = $request->exists("country") ? $request->input("country") : "";
            $user->state                = $request->exists("state") ? $request->input("state") : "";
            $user->district                = $request->exists("district") ? $request->input("district") : "";
            $user->city                = $request->exists("city") ? $request->input("city") : "";

            if($request->exists("status") && $request->input("status") == 1){
                $user->verified_at           = date('Y-m-d H:i:s');
            }else{
                $user->verified_at           = null;
            }

            if($user->save()){
                return redirect('user')->with('message', trans('messages.UPDATED_SUCCESSFULLY'));
            }else{
                return redirect('user')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
            }

        } catch (Exception $e) {
            return redirect('user')->with('error', $exception->getMessage());           
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function bulkUpdate(Request $request)
    {
        $authUser = \Auth::user();
        $update = User::whereIn('id',$request->ids)->update(['status' => $request->status]);
        if($update){

            return array('success'=>true,'item' => array(),'msg'=>'true');
        }else{
            return array('success'=>false,'item'=>array(),'msg'=>trans('messages.NO_UPDATE_REQUIRED'));
        }
    }

    public function destroy($id)
    {



        $user = User::findorfail($id);
        if($user->forceDelete()){
            return redirect('user')->with('message', trans('messages.USER_DELETED_SUCCESS'));
        }else{
            return redirect('user')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
        }
    }

}
