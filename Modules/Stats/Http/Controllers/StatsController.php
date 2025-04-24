<?php

namespace Modules\Stats\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use Image;
use Auth;
use DataTables;

use App\Models\Audit;
use Modules\Administration\Entities\NotificationTemplate;
use Helpers;
use App\Jobs\SendNotificationJob;

use Modules\Stats\Entities\Tracking;
use Modules\Stats\Entities\StatsSession;
use Modules\Stats\Entities\StatsResult;
use Modules\Exams\Entities\Exam;
use Modules\Exams\Entities\ExamBackground;
use Modules\Exams\Entities\ExamQuestion;

class StatsController extends Controller
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

        $authUser = \Auth::user();

        $teacher = Tracking::where('user_id',$authUser->id)->first();
        $teacherCode = ''; 
        if($teacher){
            $teacherCode = $teacher->teacher_id;
            return redirect('stats/student-results');
        }else{
            return view('stats::index')->with(compact('teacherCode'));
        }
    }

    public function generateTeacherId()
    {
        $authUser = \Auth::user();

        $teacher = new Tracking();
        $teacher->user_id = $authUser->id;
        $teacher->teacher_id = $this->createTeacherId();
        if($teacher->save()){
            return redirect('stats')->with('message', 'Teacher code created successfully');
        }else{
            return redirect('stats')->with('error', 'Something went wrong');
        }
    }

    public function studentResults(Request $request)
    {

        $authUser = \Auth::user();

        $teacher = Tracking::where('user_id',$authUser->id)->first();
        $teacherCode = ''; 
        if($teacher){
            $teacherCode = $teacher->teacher_id;
        }else{
            return redirect('stats');
        }

        $data = StatsSession::from('stats_session as ss')
        ->select(
            'ss.id','ss.uuid','ss.exam_id','ss.teacher_id','ss.f_name','ss.l_name',
            \DB::raw('count(DISTINCT ss.id) AS times_taken'),
            \DB::raw('MAX(ss.created_at) AS created_at'),
            \DB::raw('SUM(CASE WHEN sr.question_result = 1 THEN 1 ELSE 0 END) AS total_correct'),
            \DB::raw('SUM(CASE WHEN sr.id > 1 THEN 1 ELSE 0 END) AS total_attempted'),
        )
        ->leftJoin('stats_results as sr', 'sr.sid', '=', 'ss.id')
        ->where('ss.teacher_id', $teacherCode)
        ->orderBy('ss.id', 'desc')
        ->groupBy('ss.f_name','ss.l_name')
        ->get();

        $resultsCount = 0;
        if(!empty($data->toArray())){
            $resultsCount = count($data);
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('student_report', function($row) {
                        $detailLink = url('/').'/stats/student-test-report/'.$row->uuid;
                        
                        $student_report = '
                                    <a href="'.$detailLink.'">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead"><em class="icon ni ni-table-view"></em> Show Report <span class="dot dot-success d-md-none ml-1"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                ';
                        return $student_report;
                    })
                    ->addColumn('test_result', function($row) {
                        $total_correct = (int) $row->total_correct;
                        $total_attempted = (int) $row->total_attempted;

                        // Avoid division by zero
                        $percentage = $total_attempted > 0 
                            ? round(($total_correct / $total_attempted) * 100) 
                            : 0;

                        $test_result = "({$total_correct}/{$total_attempted}) {$percentage}%";
                        return $test_result;
                    })
                    ->addColumn('action', function($row) {
                           $edit = url('/').'/exams/edit/'.$row->uuid;
                           $delete = url('/').'/exams/delete/'.$row->id;
                           $confirm = '"Are you sure, you want to delete it?"';
                            
                            $deleteBtn = "<li>
                                        <a href='".$delete."' onclick='return confirm(".$confirm.")'  class='delete'>
                                            <em class='icon ni ni-trash'></em> <span>Delete</span>
                                        </a>
                                    </li>"; 

                            $btn = '';
                            $btn .= '<ul class="nk-tb-actio ns gx-1">
                                        <li>
                                            <div class="drodown mr-n1">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                        ';

                           $btn .=       $deleteBtn;

                            $btn .= "</ul>
                                            </div>
                                        </div>
                                    </li>
                                    </ul>";
                        return $btn;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date(\Config::get('constants.DATE.DATE_FORMAT_FULL') , strtotime($row->created_at));
                    })
                    ->rawColumns(['action','created_at','student_report','test_result'])
                    ->make(true);
        }

        
        return view('stats::student-results')->with(compact('teacherCode','resultsCount'));
    }
    
    
    public function studentTestReport(Request $request,$stat_id)
    {

        $authUser = \Auth::user();

        $teacher = Tracking::where('user_id',$authUser->id)->first();
        $teacherCode = ''; 
        if($teacher){
            $teacherCode = $teacher->teacher_id;
        }else{
            return redirect('stats');
        }

        $statsDetail = StatsSession::where('uuid',$stat_id)->first();
        if(!$statsDetail){
            return redirect('stats/student-results')->with('error', 'Report not found');
        }

        $f_name = $statsDetail->f_name;
        $l_name = $statsDetail->l_name;

        $data = StatsSession::from('stats_session as ss')
        ->select(
            'ss.id','ss.uuid','ss.exam_id','ss.teacher_id','ss.f_name','ss.l_name',
            'e.uuid as exam_uuid','e.test_number','e.title','e.total_questions',
            \DB::raw('count(DISTINCT ss.id) AS times_taken'),
            \DB::raw('MAX(ss.created_at) AS created_at'),
            \DB::raw('SUM(CASE WHEN sr.question_result = 1 THEN 1 ELSE 0 END) AS total_correct'),
            \DB::raw('SUM(CASE WHEN sr.id > 1 THEN 1 ELSE 0 END) AS total_attempted')
        )
        ->leftJoin('exams as e', 'e.id', '=', 'ss.exam_id')
        ->leftJoin('stats_results as sr', 'sr.sid', '=', 'ss.id')
        ->where('ss.teacher_id', $teacherCode)
        ->where([
            ['ss.f_name', 'like', $f_name],
            ['ss.l_name', 'like', $l_name]
        ])
        ->orderBy('ss.id', 'desc')
        ->groupBy('ss.exam_id')
        ->get();


        $resultsCount = 0;
        if(!empty($data->toArray())){
            $resultsCount = count($data);
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('test_number', function($row) {
                        $detailLink = url('/').'/exams/edit/'.$row->exam_uuid;
                        
                        $test_number = '
                                    <a href="'.$detailLink.'">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead"><em class="icon ni ni-table-view"></em> '.$row->test_number.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                ';
                        return $test_number;
                    })
                    ->addColumn('test_result', function($row) {

                        $detailLink = url('/').'/stats/student-exam-result/'.$row->uuid;

                        $total_correct = (int) $row->total_correct;
                        $total_attempted = (int) $row->total_attempted;

                        // Avoid division by zero
                        $percentage = $total_attempted > 0 
                            ? round(($total_correct / $total_attempted) * 100) 
                            : 0;

                        $test_result = "({$total_correct}/{$total_attempted}) {$percentage}%";

                        $test_result = '
                            <a href="'.$detailLink.'">
                                <div class="user-card">
                                    <div class="user-info">
                                        <span class="tb-lead"><em class="icon ni ni-table-view"></em> '.$test_result.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                    </div>
                                </div>
                            </a>
                        ';

                        return $test_result;
                    })
                    ->addColumn('result', function($row) {
                        $detailLink = url('/').'/stats/student-test-report/'.$row->uuid;
                        
                        $result = '
                                    <a href="'.$detailLink.'">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead"><em class="icon ni ni-table-view"></em> Show Report <span class="dot dot-success d-md-none ml-1"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                ';
                        return $result;
                    })
                    ->addColumn('action', function($row) {
                           $edit = url('/').'/exams/edit/'.$row->uuid;
                           $delete = url('/').'/exams/delete/'.$row->id;
                           $confirm = '"Are you sure, you want to delete it?"';
                            
                            $deleteBtn = "<li>
                                        <a href='".$delete."' onclick='return confirm(".$confirm.")'  class='delete'>
                                            <em class='icon ni ni-trash'></em> <span>Delete</span>
                                        </a>
                                    </li>"; 

                            $btn = '';
                            $btn .= '<ul class="nk-tb-actio ns gx-1">
                                        <li>
                                            <div class="drodown mr-n1">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                        ';

                           $btn .=       $deleteBtn;

                            $btn .= "</ul>
                                            </div>
                                        </div>
                                    </li>
                                    </ul>";
                        return $btn;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date(\Config::get('constants.DATE.DATE_FORMAT_FULL') , strtotime($row->created_at));
                    })
                    ->rawColumns(['action','created_at','result','test_number','test_result'])
                    ->make(true);
        }

        
        return view('stats::student-test-report')->with(compact('teacherCode','resultsCount','stat_id'));
    }

    public function studentExamReport(Request $request,$stat_id)
    {

        $authUser = \Auth::user();

        $teacher = Tracking::where('user_id',$authUser->id)->first();
        $teacherCode = ''; 
        if($teacher){
            $teacherCode = $teacher->teacher_id;
        }else{
            return redirect('stats');
        }

        $statsDetail = StatsSession::where('uuid',$stat_id)->first();
        if(!$statsDetail){
            return redirect('stats/student-results')->with('error', 'Report not found');
        }

        $f_name = $statsDetail->f_name;
        $l_name = $statsDetail->l_name;
        $exam_id = $statsDetail->exam_id;

        $data = Exam::from('exams as e')
        ->select(
            'e.uuid as exam_uuid','e.test_number','e.title','e.total_questions',
            'eq.id as question_id','eq.question','eq.question_type','eq.option_one','eq.option_two','eq.option_three','eq.options_four','eq.correct_option','eq.short_answer_one',
            \DB::raw('SUM(CASE WHEN sr.question_result = 1 THEN 1 ELSE 0 END) AS total_correct'),
            \DB::raw('SUM(CASE WHEN sr.id > 1 THEN 1 ELSE 0 END) AS total_attempted')
        )
        ->leftJoin('exam_questions as eq', 'eq.exam_id', '=', 'e.id')
        ->leftJoin('stats_results as sr', 'sr.question_id', '=', 'eq.id')
        ->leftJoin('stats_session as ss', function($join) use ($teacherCode,$f_name,$l_name) {
            $join->on('ss.id', '=', 'sr.sid')
                ->where('ss.teacher_id', '=', $teacherCode)
                ->where('ss.f_name', 'like', $f_name)
                ->where('ss.l_name', 'like', $l_name);
        })
        ->where('ss.exam_id',$exam_id)
        ->groupBy('eq.id', 'e.uuid', 'e.test_number', 'e.title', 'e.total_questions', 'eq.question', 'eq.question_type', 'eq.option_one', 'eq.option_two', 'eq.option_three', 'eq.options_four', 'eq.correct_option', 'eq.short_answer_one')
        ->get();

        $resultsCount = 0;
        if(!empty($data->toArray())){
            $resultsCount = count($data);
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('test_number', function($row) {
                        $detailLink = url('/').'/exams/edit/'.$row->exam_uuid;
                        
                        $test_number = '
                                    <a href="'.$detailLink.'">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead"><em class="icon ni ni-table-view"></em> '.$row->test_number.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                ';
                        return $test_number;
                    })
                    ->addColumn('test_result', function($row) {

                        $detailLink = url('/').'/stats/student-exam-result/'.$row->uuid;

                        $total_correct = (int) $row->total_correct;
                        $total_attempted = (int) $row->total_attempted;

                        // Avoid division by zero
                        $percentage = $total_attempted > 0 
                            ? round(($total_correct / $total_attempted) * 100) 
                            : 0;

                        $test_result = "({$total_correct}/{$total_attempted}) {$percentage}%";

                        return $test_result;
                    })
                    ->addColumn('correct_option', function($row) {
                        $correct_option = '';
                        if($row->question_type == 'mc'){
                            if($row->correct_option == 1){
                                $correct_option = $row->option_one;
                            }
                            if($row->correct_option == 2){
                                $correct_option = $row->option_two;
                            }
                            if($row->correct_option == 3){
                                $correct_option = $row->option_three;
                            }
                            if($row->correct_option == 4){
                                $correct_option = $row->options_four;
                            }
                        }else{
                            $correct_option = $row->short_answer_one;
                        }

                        return $correct_option;
                    })
                    ->rawColumns(['correct_option','test_number','test_result'])
                    ->make(true);
        }

        return view('stats::student-exam-result')->with(compact('teacherCode','resultsCount','stat_id'));
    }

    public function testResults(Request $request)
    {

        $authUser = \Auth::user();

        $teacher = Tracking::where('user_id',$authUser->id)->first();
        $teacherCode = ''; 
        if($teacher){
            $teacherCode = $teacher->teacher_id;
        }else{
            return redirect('stats');
        }

        $data = StatsSession::from('stats_session as ss')
        ->select(
            'ss.id','ss.uuid','ss.exam_id','ss.teacher_id','ss.f_name','ss.l_name',
            'e.uuid as exam_uuid','e.test_number','e.title','e.total_questions',
            \DB::raw('count(DISTINCT ss.id) AS times_taken'),
            \DB::raw('MAX(ss.created_at) AS created_at'),
            \DB::raw('SUM(CASE WHEN sr.question_result = 1 THEN 1 ELSE 0 END) AS total_correct'),
            \DB::raw('SUM(CASE WHEN sr.id > 1 THEN 1 ELSE 0 END) AS total_attempted'),
        )
        ->leftJoin('exams as e', 'e.id', '=', 'ss.exam_id')
        ->leftJoin('stats_results as sr', 'sr.sid', '=', 'ss.id')
        ->where('ss.teacher_id', $teacherCode)
        ->orderBy('ss.id', 'desc')
        ->groupBy('ss.exam_id')
        ->get();

        $resultsCount = 0;
        if(!empty($data->toArray())){
            $resultsCount = count($data);
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('test_number', function($row) {
                        $detailLink = url('/').'/exams/edit/'.$row->exam_uuid;
                        
                        $test_number = '
                                    <a href="'.$detailLink.'">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead"><em class="icon ni ni-table-view"></em> '.$row->test_number.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                ';
                        return $test_number;
                    })
                    ->addColumn('test_result', function($row) {

                        $detailLink = url('/').'/stats/test-question-result/'.$row->exam_uuid;

                        $total_correct = (int) $row->total_correct;
                        $total_attempted = (int) $row->total_attempted;

                        // Avoid division by zero
                        $percentage = $total_attempted > 0 
                            ? round(($total_correct / $total_attempted) * 100) 
                            : 0;

                        $test_result = "({$total_correct}/{$total_attempted}) {$percentage}%";

                        $test_result = '
                            <a href="'.$detailLink.'">
                                <div class="user-card">
                                    <div class="user-info">
                                        <span class="tb-lead"><em class="icon ni ni-table-view"></em> '.$test_result.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                    </div>
                                </div>
                            </a>
                        ';

                        return $test_result;
                    })
                    ->addColumn('result', function($row) {
                        $detailLink = url('/').'/stats/test-report/'.$row->exam_uuid;
                        
                        $result = '
                                    <a href="'.$detailLink.'">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead"><em class="icon ni ni-table-view"></em> Show Report <span class="dot dot-success d-md-none ml-1"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                ';
                        return $result;
                    })
                    ->addColumn('action', function($row) {
                           $edit = url('/').'/exams/edit/'.$row->uuid;
                           $delete = url('/').'/exams/delete/'.$row->id;
                           $confirm = '"Are you sure, you want to delete it?"';
                            
                            $deleteBtn = "<li>
                                        <a href='".$delete."' onclick='return confirm(".$confirm.")'  class='delete'>
                                            <em class='icon ni ni-trash'></em> <span>Delete</span>
                                        </a>
                                    </li>"; 

                            $btn = '';
                            $btn .= '<ul class="nk-tb-actio ns gx-1">
                                        <li>
                                            <div class="drodown mr-n1">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                        ';

                           $btn .=       $deleteBtn;

                            $btn .= "</ul>
                                            </div>
                                        </div>
                                    </li>
                                    </ul>";
                        return $btn;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date(\Config::get('constants.DATE.DATE_FORMAT_FULL') , strtotime($row->created_at));
                    })
                    ->rawColumns(['action','created_at','result','test_number','test_result'])
                    ->make(true);
        }

        
        return view('stats::test-results')->with(compact('teacherCode','resultsCount'));
    }

    public function testQuestionResult(Request $request,$exam_uuid)
    {

        $authUser = \Auth::user();

        $teacher = Tracking::where('user_id',$authUser->id)->first();
        $teacherCode = ''; 
        if($teacher){
            $teacherCode = $teacher->teacher_id;
        }else{
            return redirect('stats');
        }

        $examDetail = Exam::where('uuid',$exam_uuid)->first();
        if(!$examDetail){
            return redirect('stats/test-results')->with('error', 'Report not found');
        }
        $exam_id = $examDetail->id;
        // echo "string $exam_id"; die;

        $data = Exam::from('exams as e')
        ->select(
            'e.uuid as exam_uuid','e.test_number','e.title','e.total_questions',
            'eq.id as question_id','eq.question','eq.question_type','eq.option_one','eq.option_two','eq.option_three','eq.options_four','eq.correct_option','eq.short_answer_one',
            \DB::raw('SUM(CASE WHEN sr.question_result = 1 THEN 1 ELSE 0 END) AS total_correct'),
            \DB::raw('SUM(CASE WHEN sr.id > 1 THEN 1 ELSE 0 END) AS total_attempted')
        )
        ->leftJoin('exam_questions as eq', 'eq.exam_id', '=', 'e.id')
        ->leftJoin('stats_results as sr', 'sr.question_id', '=', 'eq.id')
        ->leftJoin('stats_session as ss', function($join) use ($teacherCode) {
            $join->on('ss.id', '=', 'sr.sid')
                ->where('ss.teacher_id', '=', $teacherCode);
        })
        ->where('ss.exam_id',$exam_id)
        ->groupBy('eq.id', 'e.uuid', 'e.test_number', 'e.title', 'e.total_questions', 'eq.question', 'eq.question_type', 'eq.option_one', 'eq.option_two', 'eq.option_three', 'eq.options_four', 'eq.correct_option', 'eq.short_answer_one')
        ->get();

        $resultsCount = 0;
        if(!empty($data->toArray())){
            $resultsCount = count($data);
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('test_number', function($row) {
                        $detailLink = url('/').'/exams/edit/'.$row->exam_uuid;
                        
                        $test_number = '
                                    <a href="'.$detailLink.'">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead"><em class="icon ni ni-table-view"></em> '.$row->test_number.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                ';
                        return $test_number;
                    })
                    ->addColumn('test_result', function($row) {

                        $detailLink = url('/').'/stats/student-exam-result/'.$row->uuid;

                        $total_correct = (int) $row->total_correct;
                        $total_attempted = (int) $row->total_attempted;

                        // Avoid division by zero
                        $percentage = $total_attempted > 0 
                            ? round(($total_correct / $total_attempted) * 100) 
                            : 0;

                        $test_result = "({$total_correct}/{$total_attempted}) {$percentage}%";

                        return $test_result;
                    })
                    ->addColumn('correct_option', function($row) {
                        $correct_option = '';
                        if($row->question_type == 'mc'){
                            if($row->correct_option == 1){
                                $correct_option = $row->option_one;
                            }
                            if($row->correct_option == 2){
                                $correct_option = $row->option_two;
                            }
                            if($row->correct_option == 3){
                                $correct_option = $row->option_three;
                            }
                            if($row->correct_option == 4){
                                $correct_option = $row->options_four;
                            }
                        }else{
                            $correct_option = $row->short_answer_one;
                        }

                        return $correct_option;
                    })
                    ->rawColumns(['correct_option','test_number','test_result'])
                    ->make(true);
        }

        return view('stats::test-question-result')->with(compact('teacherCode','resultsCount','exam_uuid'));
    }

    public function testReport(Request $request, $exam_uuid)
    {

        $authUser = \Auth::user();

        $teacher = Tracking::where('user_id',$authUser->id)->first();
        $teacherCode = ''; 
        if($teacher){
            $teacherCode = $teacher->teacher_id;
        }else{
            return redirect('stats');
        }

        $data = Exam::from('exams as e')
        ->select(
            'e.uuid as exam_uuid','e.test_number','e.title','e.total_questions',
            'eq.id as question_id','eq.question','eq.question_type','eq.option_one','eq.option_two','eq.option_three','eq.options_four','eq.correct_option','eq.short_answer_one',
            \DB::raw('SUM(CASE WHEN sr.question_result = 1 THEN 1 ELSE 0 END) AS total_correct'),
            \DB::raw('SUM(CASE WHEN sr.id > 1 THEN 1 ELSE 0 END) AS total_attempted'),
        )
        ->leftJoin('exam_questions as eq', 'eq.exam_id', '=', 'e.id')
        ->leftJoin('stats_results as sr', 'sr.question_id', '=', 'eq.id')
        ->where('e.uuid', $exam_uuid)
        ->groupBy('eq.id')
        ->get();

        $resultsCount = 0;
        if(!empty($data->toArray())){
            $resultsCount = count($data);
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('test_number', function($row) {
                        $detailLink = url('/').'/exams/edit/'.$row->exam_uuid;
                        
                        $test_number = '
                                    <a href="'.$detailLink.'">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead"><em class="icon ni ni-table-view"></em> '.$row->test_number.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                ';
                        return $test_number;
                    })
                    ->addColumn('correct_option', function($row) {
                        $correct_option = '';
                        if($row->question_type == 'mc'){
                            if($row->correct_option == 1){
                                $correct_option = $row->option_one;
                            }
                            if($row->correct_option == 2){
                                $correct_option = $row->option_two;
                            }
                            if($row->correct_option == 3){
                                $correct_option = $row->option_three;
                            }
                            if($row->correct_option == 4){
                                $correct_option = $row->options_four;
                            }
                        }else{
                            $correct_option = $row->short_answer_one;
                        }

                        return $correct_option;
                    })
                    ->rawColumns(['correct_option','test_number'])
                    ->make(true);
        }

        return view('stats::test-report')->with(compact('teacherCode','resultsCount','exam_uuid'));
    }

    public function questionReport(Request $request)
    {

        $authUser = \Auth::user();

        $teacher = Tracking::where('user_id',$authUser->id)->first();
        $teacherCode = ''; 
        if($teacher){
            $teacherCode = $teacher->teacher_id;
        }else{
            return redirect('stats');
        }

        $data = Exam::from('exams as e')
        ->select(
            'e.uuid as exam_uuid','e.test_number','e.title','e.total_questions',
            'eq.id as question_id','eq.question','eq.question_type','eq.option_one','eq.option_two','eq.option_three','eq.options_four','eq.correct_option','eq.short_answer_one',
            \DB::raw('SUM(CASE WHEN sr.question_result = 1 THEN 1 ELSE 0 END) AS total_correct'),
            \DB::raw('SUM(CASE WHEN sr.id > 1 THEN 1 ELSE 0 END) AS total_attempted')
        )
        ->leftJoin('exam_questions as eq', 'eq.exam_id', '=', 'e.id')
        ->leftJoin('stats_results as sr', 'sr.question_id', '=', 'eq.id')
        ->leftJoin('stats_session as ss', function($join) use ($teacherCode) {
            $join->on('ss.id', '=', 'sr.sid')
                 ->where('ss.teacher_id', '=', $teacherCode);
        })
        ->groupBy('eq.id', 'e.uuid', 'e.test_number', 'e.title', 'e.total_questions', 'eq.question', 'eq.question_type', 'eq.option_one', 'eq.option_two', 'eq.option_three', 'eq.options_four', 'eq.correct_option', 'eq.short_answer_one')
        ->get();


        $resultsCount = 0;
        if(!empty($data->toArray())){
            $resultsCount = count($data);
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('test_number', function($row) {
                        $detailLink = url('/').'/exams/edit/'.$row->exam_uuid;
                        
                        $test_number = '
                                    <a href="'.$detailLink.'">
                                        <div class="user-card">
                                            <div class="user-info">
                                                <span class="tb-lead"><em class="icon ni ni-table-view"></em> '.$row->test_number.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                            </div>
                                        </div>
                                    </a>
                                ';
                        return $test_number;
                    })
                    ->addColumn('correct_option', function($row) {
                        $correct_option = '';
                        if($row->question_type == 'mc'){
                            if($row->correct_option == 1){
                                $correct_option = $row->option_one;
                            }
                            if($row->correct_option == 2){
                                $correct_option = $row->option_two;
                            }
                            if($row->correct_option == 3){
                                $correct_option = $row->option_three;
                            }
                            if($row->correct_option == 4){
                                $correct_option = $row->options_four;
                            }
                        }else{
                            $correct_option = $row->short_answer_one;
                        }

                        return $correct_option;
                    })
                    ->rawColumns(['correct_option','test_number'])
                    ->make(true);
        }

        return view('stats::qurestion-result')->with(compact('teacherCode','resultsCount'));
    }

    public function createTeacherId()
    {
        do {
            $randomNumber = sprintf('%06d', mt_rand(100000, 999999));
        } while (Tracking::where('teacher_id', $randomNumber)->exists());
        
        return $randomNumber;
    }

}
