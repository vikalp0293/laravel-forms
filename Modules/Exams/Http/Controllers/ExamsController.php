<?php

namespace Modules\Exams\Http\Controllers;

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
use Modules\Masters\Entities\Topic;
use Modules\Masters\Entities\Subtopic;

class ExamsController extends Controller
{

    public function __construct() {

        /* Execute authentication filter before processing any request */
        $this->middleware('auth');

        if (\Auth::check()) {
            return redirect('/');
        }

    }
    
    public function index(Request $request)
    {
        $userPermission = \Session::get('userPermission');

        $authUser = \Auth::user();

        $role = $authUser->getRoleNames()->toArray();

        $data = User::from('users as u')
                 ->select('u.id',DB::raw("concat(u.first_name, ' ', u.last_name) as name"),'u.email','u.phone_number','u.created_at','u.updated_at','r.name as role','r.label as roleName','u.status','s.name as subject','g.name as grade')
                ->leftJoin('model_has_roles as mr','mr.model_id','=','u.id')
                ->leftJoin('roles as r','mr.role_id','=','r.id')
                ->leftJoin('m_subjects as s','s.id','=','u.subject_id')
                ->leftJoin('m_grades as g','g.id','=','u.grade_id')
                ->where('u.id',0)
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


        return view('exams::index')->with(compact('usersCount'));
    }

    public function create(Request $request)
    {
        try {
            $authUser = \Auth::user();
            

            $subjects = Subject::where('status','active')->orderBy('name','asc')->get();
            $grades = Grade::where('status','active')->orderBy('name','asc')->get();
            $states = State::where('status','active')->orderBy('name','asc')->get();
            

            return view('exams::create',[
                'subjects' => $subjects,
                'grades' => $grades,
                'states' => $states,
            ]);

        } catch (Exception $e) {
            return redirect('user')->with('error', $exception->getMessage());           
        }
    }

    public function getTopicBySubject($subject_id)
    {
        $topics   =   Topic::where('subject_id',$subject_id)->orderBy('name','asc')->get();  
        if(!empty($topics->toArray())){
            return $arrayName = array('topics' => $topics);
        }else{
            return false;
        }
    }

    public function getSubtopicByTopic($topic_id)
    {
        $subtopics   =   Subtopic::where('topic_id',$topic_id)->orderBy('name','asc')->get();  
        if(!empty($subtopics->toArray())){
            return $arrayName = array('subtopics' => $subtopics);
        }else{
            return false;
        }
    }

}
