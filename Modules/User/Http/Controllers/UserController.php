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
                ->select('u.id','u.name','u.email','u.phone_number','u.created_at','u.updated_at','r.name as role','r.label as roleName','u.status','s.name as subject','g.name as grade')
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
                    ->rawColumns(['action','created_at','name','updated_at','status',])
                    ->make(true);
        }


        return view('user::index')->with(compact('usersCount'));
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

        return view('user::profile/index')->with(compact('user'));
    }

    public function profileAddress(Request $request)
    {
        $authUser = Auth::user();

        $user =     User::from('users as u')
                    ->select('u.*','s.name as stateName','c.name as cityName','d.name as districtName')
                    ->leftJoin('states as s','s.id','=','u.state')
                    ->leftJoin('cities as c','c.id','=','u.city')
                    ->leftJoin('districts as d','d.id','=','u.district')
                    ->where('u.id',$authUser->id)
                    ->first();
        $states             =   State::all();
        $districts          =   District::where('state_id',$user->state)->orderby('name','asc')->get();
        $cities             =   City::where('district_id',$user->district)->orderby('name','asc')->get();

        return view('user::profile/address')->with(compact('user','states','districts','cities'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function updateProfileAddress(Request $request)
    {
        $user = Auth::user();
        $user->address1 = $request->address1;
        $user->address2 = $request->address2;
        $user->state = $request->state;
        $user->district = $request->district;
        $user->city = $request->city;
        $user->pincode = $request->pincode;

        if($user->save()){
            return redirect('profile/address')->with('message', trans('messages.ADDRESS_UPDATED_SUCCESS'));
        }else{
            return redirect('profile/address')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $user->name = $request->name;
        $user->last_name = $request->last_name;
        /*$user->address1 = $request->address1;
        $user->address2 = $request->address2;
        $user->state = $request->state;
        $user->district = $request->district;
        $user->city = $request->city;
        $user->pincode = $request->pincode;*/

        if ($request->hasFile('file')) {

            $image1 = $request->file('file');
            $image1NameWithExt = $image1->getClientOriginalName();
            list($image1_width,$image1_height)=getimagesize($image1);
            // Get file path
            $originalName = pathinfo($image1NameWithExt, PATHINFO_FILENAME);
            $image1Name = pathinfo($image1NameWithExt, PATHINFO_FILENAME);
            // Remove unwanted characters
            $image1Name = preg_replace("/[^A-Za-z0-9 ]/", '', $image1Name);
            $image1Name = preg_replace("/\s+/", '-', $image1Name);

            // Get the original image extension
            $extension  = $image1->getClientOriginalExtension();

            if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                return redirect('profile')->with('error', trans('messages.INVALID_IMAGE'));
            }

            $image1Name = $image1Name.'_'.time().'.'.$extension;
            
            $destinationPath = public_path('uploads/users');
            if($image1_width > 800){
                $image1_canvas = Image::canvas(800, 800);
                $image1_image = Image::make($image1->getRealPath())->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image1_canvas->insert($image1_image, 'center');
                $image1_canvas->save($destinationPath.'/'.$image1Name,80);
            }else{
                $image1->move($destinationPath, $image1Name);
            }
            $image1_file = public_path('uploads/users/'. $image1Name);

            $user->file = $image1Name;
            $user->original_name = $image1NameWithExt;
        }

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
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function storeStaff(Request $request)
    {   
        $authUser = \Auth::user();

        $checkUser = User::where('email',$request->input("email"))->first();
        if($checkUser){
            return redirect('user/staff/create-staff')->with('error', 'Email already exists');
        }

        $staff_limit = \Session::get('staff_limit');
        $seller_limit = \Session::get('seller_limit');

        $roleData = Role::where('id',$request->input("role"))->first();
        
        DB::beginTransaction();
        $user = new User();

        if ($request->hasFile('file')) {

            $image1 = $request->file('file');
            $image1NameWithExt = $image1->getClientOriginalName();
            list($image1_width,$image1_height)=getimagesize($image1);
            // Get file path
            $originalName = pathinfo($image1NameWithExt, PATHINFO_FILENAME);
            $image1Name = pathinfo($image1NameWithExt, PATHINFO_FILENAME);
            // Remove unwanted characters
            $image1Name = preg_replace("/[^A-Za-z0-9 ]/", '', $image1Name);
            $image1Name = preg_replace("/\s+/", '-', $image1Name);

            // Get the original image extension
            $extension  = $image1->getClientOriginalExtension();
            if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                return redirect('user/staff')->with('error', trans('messages.INVALID_IMAGE'));
            }
            $image1Name = $image1Name.'_'.time().'.'.$extension;
            
            $destinationPath = public_path('uploads/users');
            if($image1_width > 800){
                $image1_canvas = Image::canvas(800, 800);
                $image1_image = Image::make($image1->getRealPath())->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image1_canvas->insert($image1_image, 'center');
                $image1_canvas->save($destinationPath.'/'.$image1Name,80);
            }else{
                $image1->move($destinationPath, $image1Name);
            }
            $image1_file = public_path('uploads/users/'. $image1Name);

            $user->file = $image1Name;
            $user->original_name = $image1NameWithExt;
        }

        
        $user->name                 = $request->exists("name") ? $request->input("name") : "";
        $user->email                = $request->exists("email") ? $request->input("email") : "";
        $user->password             = \Hash::make($request->input("password"));
        $user->phone_number         = $request->exists("mobileNumber") ? $request->input("mobileNumber") : "";
        $user->is_approved          = 1;

        if($request->exists("approved") && $request->input("approved") == 1){
            $user->status           = 1;
        }else{
            $user->status           = 0;
        }

        if($user->save()){

            $roleLable = ucfirst(str_replace('_', ' ', $request->input("role")));
            $msg = trans('messages.ADDED_SUCCESSFULLY');

            //Assign role to the user
            // $user->assignRole($request->input("role"));
            $modelRole = new ModelRole();
            $modelRole->role_id = $request->input("role");
            $modelRole->model_type = 'Modules\User\Entities\User';
            $modelRole->model_id = $user->id;
            $modelRole->save();

            if($user->id){
                DB::commit();
                return redirect('user/staff')->with('message', $msg);
            }else{
                return redirect('user/staff')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
            }
        } else{
            DB::rollback();
            return redirect('user/staff')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
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

            $user->email                = $request->exists("email") ? $request->input("email") : "";
            $user->subject_id                = $request->exists("subject") ? $request->input("subject") : "";
            $user->grade_id                = $request->exists("grade") ? $request->input("grade") : "";
            $user->country                = $request->exists("country") ? $request->input("country") : "";
            $user->state                = $request->exists("state") ? $request->input("state") : "";

            if($request->exists("status") && $request->input("status") == 1){
                $user->status           = 1;
            }else{
                $user->status           = 0;
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
    public function destroyStaff($id)
    {



        $user = User::findorfail($id);
        if($user->forceDelete()){
            return redirect('user/staff')->with('message', trans('messages.USER_DELETED_SUCCESS'));
        }else{
            return redirect('user/staff')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
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

    

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function staffBulkUpdate(Request $request)
    {
        $authUser = \Auth::user();
        $update = User::whereIn('id',$request->ids)->update(['status' => $request->status]);
        if($update){
            return array('success'=>true,'item' => array(),'msg'=>'true');
        }else{
            return array('success'=>false,'item'=>array(),'msg'=>'No update required');
        }
    }


    public function getBrandByOrganization(Request $request)
    {
        $organizations = explode(',', $request->organizations);

        $organization_type = \Session::get('organization_type');
        if(isset($organization_type) && $organization_type == 'MULTIPLE'){
            $brands = Brand::select('name','id')->where('status','active')->whereIn('organization_id',$organizations)->get();  
        }else{
            $brands = Brand::select('name','id')->where('status','active')->get();
        }

        
        if(!empty($brands->toArray())){
            return array('brands' => $brands,'success'=>true);
        }else{
            return array('success'=>false,'brands'=>array());
        }
    }

    public function setOrganization(Request $request)
    {
        $currentOrganization = \Session::get('currentOrganization');
        $currentOrganizationName = \Session::get('currentOrganizationName');

        $newOrgName = $request->org_name;
        $newOrgId = $request->org_id;

        session()->put('currentOrganization', $newOrgId);
        session()->put('currentOrganizationName', $newOrgName);
        if($newOrgId != $currentOrganization){
            return array('success'=>true);
        }else{
            return array('success'=>false);
        }
    }

    public function import(Request $request)
    {
        return view('user::import');
    }

}
