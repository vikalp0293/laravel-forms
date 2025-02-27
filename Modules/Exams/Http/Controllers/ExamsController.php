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
use Modules\Masters\Entities\Standard;

use Modules\Exams\Entities\Exam;
use Modules\Exams\Entities\ExamBackground;
use Modules\Exams\Entities\ExamQuestion;

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

        $authUser = \Auth::user();

        $role = $authUser->getRoleNames()->toArray();

        $data = Exam::from('exams as e')
                 ->select('e.id','e.uuid','e.test_number','e.title','e.total_questions','e.created_at','e.status','ms.name as subject','mt.name as topic','mst.name as subtopic','st.name as state','g.name as grade')
                ->leftJoin('m_subjects as ms','ms.id','=','e.subject')
                ->leftJoin('m_topics as mt','mt.id','=','e.topic')
                ->leftJoin('m_sub_topics as mst','mst.id','=','e.subtopic')
                ->leftJoin('m_states as st','st.id','=','e.state')
                ->leftJoin('m_grades as g','g.id','=','e.grade')
                ->orderby('e.id','desc')
                ->get();
        $usersCount = 0;
        if(!empty($data->toArray())){
            $usersCount = count($data);
        }

        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('title', function($row) {
                            $detailLink = url('/').'/exams/edit/'.$row->uuid;
                            
                            $title = '
                                        <a href="'.$detailLink.'">
                                            <div class="user-card">
                                                <div class="user-info">
                                                    <span class="tb-lead">'.$row->title.' <span class="dot dot-success d-md-none ml-1"></span></span>
                                                </div>
                                            </div>
                                        </a>
                                    ';
                            return $title;
                    })
                    ->addColumn('status', function ($row) {
                        if($row->status == 'active'){
                            $statusValue = 'Active';
                        }else{
                            $statusValue = 'Inactive';
                        }

                        $value = ($row->status == 'active') ? 'badge badge-success' : 'badge badge-danger';
                        $status = '
                            <span class="tb-sub">
                                <span class="'.$value.'">
                                    '.$statusValue.'
                                </span>
                            </span>
                        ';
                        return $status;
                    })
                    ->addColumn('action', function($row) {
                           $edit = url('/').'/exams/edit/'.$row->uuid;
                           $delete = url('/').'/exams/delete/'.$row->id;
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

                            $btn = '';
                            $btn .= '<ul class="nk-tb-actio ns gx-1">
                                        <li>
                                            <div class="drodown mr-n1">
                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                        ';

                           $btn .=       $editBtn."
                                        ".$deleteBtn;

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
                    ->rawColumns(['action','created_at','title','status',])
                    ->make(true);
        }


        return view('exams::index')->with(compact('usersCount'));
    }

    public function create(Request $request)
    {
        try {
            $authUser = \Auth::user();
            $maxQuestions = env('MAX_QUESTIONS'); // Your secret key from the .env file

            $subjects = Subject::where('status','active')->orderBy('name','asc')->get();
            $grades = Grade::where('status','active')->orderBy('name','asc')->get();
            $states = State::where('status','active')->orderBy('name','asc')->get();

            

            return view('exams::create',[
                'subjects' => $subjects,
                'grades' => $grades,
                'states' => $states,
                'maxQuestions' => $maxQuestions,
            ]);

        } catch (Exception $e) {
            return redirect('user')->with('error', $exception->getMessage());           
        }
    }

    
    public function searchQuestions(Request $request,$searchTxt){


        $questions = ExamQuestion::where('question', 'like', "%$searchTxt%")->get();

        

        if(!empty($questions->toArray())){
            
            $searchResult = '';
            foreach ($questions as $key => $question) {

                if($question->question_type == 'sa'){
                    $badgeClass = 'badge-dark';
                }else{
                    $badgeClass = 'badge-gray';
                }

                $searchResult .= '
                    <div class="question-radio-item">
                        <label for="customRadio'.$key.'"><span class="badge '.$badgeClass.'">'.strtoupper($question->question_type).' :</span> '.$question->question.'</label>
                        <div class="custom-control custom-control-sm custom-radio">
                            <input data-examId="'.$question->exam_id.'" value="'.$question->id.'" type="radio" id="customRadio'.$key.'" name="customRadio" class="custom-control-input searchedQuestion">
                            <label class="custom-control-label" for="customRadio'.$key.'">&nbsp;</label>
                        </div>
                    </div>    
                ';
            }

            return array('rows' =>$searchResult,'success'=>true,'msg'=>'success');
        }else{
            return array('rows' =>'','success'=>false,'msg'=>'success');
        }


    }

    
    public function questionDetails(Request $request,$questionId,$examId){
        $question = ExamQuestion::select('exam_questions.*','eb.id as background_id','eb.background_number', 'eb.exam_id', 'eb.instruction', 'eb.instruction_two', 'eb.image_one', 'eb.instruction_three', 'eb.image_two')
        ->leftJoin('exam_backgrounds as eb','eb.id','=','exam_questions.background_number')
        ->where('exam_questions.id',$questionId)
        ->where('exam_questions.exam_id',$examId)
        ->first();

        if($question){
            return array('question' =>$question,'success'=>true,'msg'=>'success');
        }else{
            return array('question' =>'','success'=>false,'msg'=>'success');
        }
    }


    public function store(Request $request){
        
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $backgrounds = $request->input("backgrounds");
            $questions = $request->input("questions");

            $exam = new Exam();
            $exam->uuid = \Str::uuid();
            $exam->test_number = $this->createTestNumber();
            $exam->title  = $request->exists("title") ? $request->input("title") : "";
            $exam->description  = $request->exists("description") ? $request->input("description") : "";
            $exam->subject  = $request->exists("subject") ? $request->input("subject") : "";
            $exam->topic  = $request->exists("topic") ? $request->input("topic") : "";
            $exam->subtopic  = $request->exists("subtopic") ? $request->input("subtopic") : "";
            $exam->state  = $request->exists("state") ? $request->input("state") : "";
            $exam->grade  = $request->exists("grade") ? $request->input("grade") : "";
            $exam->total_questions  = count($questions);
            $exam->created_by = $user->id;

            if($exam->save()){
                if(!empty($backgrounds)){
                    
                    foreach($request->backgrounds as $key => $bg){


                        if(isset($bg['background']) && $bg['background'] != ''){

                            $background = new ExamBackground();
                            $background->exam_id = $exam->id;
                            $background->background_number = $bg['background'];
                            $background->instruction = $bg['instructions'];
                            $background->instruction_two = $bg['instructions_2'];
                            $background->instruction_three = $bg['instructions_3'];

                            if (isset($bg['image_upload_1']) && count((array)$bg['image_upload_1'])) {
                                $bgImageOne = $bg['image_upload_1'];
                                $bgImageOneNameWithExt = $bgImageOne->getClientOriginalName();
                                list($bgImageOne_width,$bgImageOne_height)=getimagesize($bgImageOne);
                                // Get file path
                                $originalName = pathinfo($bgImageOneNameWithExt, PATHINFO_FILENAME);
                                $bgImageOneName = pathinfo($bgImageOneNameWithExt, PATHINFO_FILENAME);
                                // Remove unwanted characters
                                $bgImageOneName = preg_replace("/[^A-Za-z0-9 ]/", '', $bgImageOneName);
                                $bgImageOneName = preg_replace("/\s+/", '-', $bgImageOneName);

                                // Get the original image extension
                                $extension = $bgImageOne->getClientOriginalExtension();
                                if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                                    return redirect('exams/create')->with('error', trans('messages.INVALID_IMAGE'));
                                }
                                $bgImageOneName = 'bgimg_image_upload_1_'.$key.'_'.$bgImageOneName.'_'.time().'.'.$extension;
                                
                                $destinationPath = public_path('uploads/questions/backgrounds');
                                if($bgImageOne_width > 800){
                                    $bgImageOne_canvas = Image::create(800, 800);
                                    $bgImageOne_image = Image::read($bgImageOne->getRealPath())->resize(800, 800, function ($constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    });
                                    $bgImageOne_canvas->place($bgImageOne_image, 'center');
                                    $bgImageOne_canvas->save($destinationPath.'/'.$bgImageOneName,80);
                                }else{
                                    $bgImageOne->move($destinationPath, $bgImageOneName);
                                }
                                $bgImageOne_file = public_path('uploads/questions/backgrounds/'. $bgImageOneName);

                                $background->image_one = $bgImageOneName;
                            }

                            if (isset($bg['image_upload_2']) && count((array)$bg['image_upload_2'])) {
                                $bgImageTwo = $bg['image_upload_2'];
                                $bgImageTwoNameWithExt = $bgImageTwo->getClientOriginalName();
                                list($bgImageTwo_width,$bgImageTwo_height)=getimagesize($bgImageTwo);
                                // Get file path
                                $originalName = pathinfo($bgImageTwoNameWithExt, PATHINFO_FILENAME);
                                $bgImageTwoName = pathinfo($bgImageTwoNameWithExt, PATHINFO_FILENAME);
                                // Remove unwanted characters
                                $bgImageTwoName = preg_replace("/[^A-Za-z0-9 ]/", '', $bgImageTwoName);
                                $bgImageTwoName = preg_replace("/\s+/", '-', $bgImageTwoName);

                                // Get the original image extension
                                $extension = $bgImageTwo->getClientOriginalExtension();
                                if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                                    return redirect('exams/create')->with('error', trans('messages.INVALID_IMAGE'));
                                }
                                $bgImageTwoName = 'bgimg_image_upload_2_'.$key.'_'.$bgImageTwoName.'_'.time().'.'.$extension;
                                
                                $destinationPath = public_path('uploads/questions/backgrounds');
                                if($bgImageTwo_width > 800){
                                    $bgImageTwo_canvas = Image::create(800, 800);
                                    $bgImageTwo_image = Image::read($bgImageTwo->getRealPath())->resize(800, 800, function ($constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    });
                                    $bgImageTwo_canvas->place($bgImageTwo_image, 'center');
                                    $bgImageTwo_canvas->save($destinationPath.'/'.$bgImageTwoName,80);
                                }else{
                                    $bgImageTwo->move($destinationPath, $bgImageTwoName);
                                }
                                $bgImageTwo_file = public_path('uploads/questions/backgrounds/'. $bgImageTwoName);

                                $background->image_two = $bgImageTwoName;
                            }

                            $background->save();
                        }
                    }
                }

                if(!empty($questions)){
                    foreach($request->questions as $key => $examQuestion){
                        $question = new ExamQuestion();
                        $question->exam_id = $exam->id;
                        $question->background_number = $examQuestion['background'];
                        $question->question = $examQuestion['question'];
                        $question->question_text_two = $examQuestion['question_text_2'];
                        $question->question_type = $examQuestion['question_type'];

                        if (isset($examQuestion['standard']) && $examQuestion['standard'] !== null) {
                            $question->standard = $examQuestion['standard'];
                        }

                        if($examQuestion['question_type'] == 'mc'){
                            $question->option_one = $examQuestion['option1'];
                            $question->option_two = $examQuestion['option2'];
                            $question->option_three = $examQuestion['option3'];
                            $question->options_four = $examQuestion['option4'];
                            $question->correct_option = $examQuestion['correct_option'];
                            $question->explanation = $examQuestion['mc_explanation_text'];
                        }

                        if($examQuestion['question_type'] == 'sa'){
                            $question->short_answer_one = $examQuestion['sa_answer_1'];
                            $question->short_answer_two = $examQuestion['sa_answer_2'];
                            $question->explanation = $examQuestion['sa_explanation_text'];
                        }

                        if (isset($examQuestion['question_image_1']) && count((array)$examQuestion['question_image_1'])) {
                            $questionImageOne = $examQuestion['question_image_1'];
                            $questionImageOneNameWithExt = $questionImageOne->getClientOriginalName();
                            list($questionImageOne_width,$questionImageOne_height)=getimagesize($questionImageOne);
                            // Get file path
                            $originalName = pathinfo($questionImageOneNameWithExt, PATHINFO_FILENAME);
                            $questionImageOneName = pathinfo($questionImageOneNameWithExt, PATHINFO_FILENAME);
                            // Remove unwanted characters
                            $questionImageOneName = preg_replace("/[^A-Za-z0-9 ]/", '', $questionImageOneName);
                            $questionImageOneName = preg_replace("/\s+/", '-', $questionImageOneName);

                            // Get the original image extension
                            $extension = $questionImageOne->getClientOriginalExtension();
                            if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                                return redirect('exams/create')->with('error', trans('messages.INVALID_IMAGE'));
                            }
                            $questionImageOneName = 'qimg_question_image_1_'.$key.'_'.$questionImageOneName.'_'.time().'.'.$extension;
                            
                            $destinationPath = public_path('uploads/questions');
                            if($questionImageOne_width > 800){
                                $questionImageOne_canvas = Image::create(800, 800);
                                $questionImageOne_image = Image::read($questionImageOne->getRealPath())->resize(800, 800, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });
                                $questionImageOne_canvas->place($questionImageOne_image, 'center');
                                $questionImageOne_canvas->save($destinationPath.'/'.$questionImageOneName,80);
                            }else{
                                $questionImageOne->move($destinationPath, $questionImageOneName);
                            }
                            $questionImageOne_file = public_path('uploads/questions/'. $questionImageOneName);

                            $question->question_image_one = $questionImageOneName;
                        }

                        if (isset($examQuestion['question_image_2']) && count((array)$examQuestion['question_image_2'])) {
                            $questionImageTwo = $examQuestion['question_image_2'];
                            $questionImageTwoNameWithExt = $questionImageTwo->getClientOriginalName();
                            list($questionImageTwo_width,$questionImageTwo_height)=getimagesize($questionImageTwo);
                            // Get file path
                            $originalName = pathinfo($questionImageTwoNameWithExt, PATHINFO_FILENAME);
                            $questionImageTwoName = pathinfo($questionImageTwoNameWithExt, PATHINFO_FILENAME);
                            // Remove unwanted characters
                            $questionImageTwoName = preg_replace("/[^A-Za-z0-9 ]/", '', $questionImageTwoName);
                            $questionImageTwoName = preg_replace("/\s+/", '-', $questionImageTwoName);

                            // Get the original image extension
                            $extension = $questionImageTwo->getClientOriginalExtension();
                            if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                                return redirect('exams/create')->with('error', trans('messages.INVALID_IMAGE'));
                            }
                            $questionImageTwoName = 'qimg_question_image_2_'.$key.'_'.$questionImageTwoName.'_'.time().'.'.$extension;
                            
                            $destinationPath = public_path('uploads/questions');
                            if($questionImageTwo_width > 800){
                                $questionImageTwo_canvas = Image::create(800, 800);
                                $questionImageTwo_image = Image::read($questionImageTwo->getRealPath())->resize(800, 800, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });
                                $questionImageTwo_canvas->place($questionImageTwo_image, 'center');
                                $questionImageTwo_canvas->save($destinationPath.'/'.$questionImageTwoName,80);
                            }else{
                                $questionImageTwo->move($destinationPath, $questionImageTwoName);
                            }
                            $questionImageTwo_file = public_path('uploads/questions/'. $questionImageTwoName);

                            $question->question_image_two = $questionImageTwoName;
                        }

                        $question->save();
                        
                    }
                }

                DB::commit();
            }

            return redirect('exams')->with('message', 'Exam created successfully');

        } catch (\Exception $e) {
            return redirect('exams/create')->with('error', 'Exception- '.$e->getMessage());

        }
    }

    public function edit($uuid)
    {
        try {
            $authUser = \Auth::user();
            $exam = Exam::from('exams as e')->where('e.uuid',$uuid)->first();

            if(!$exam){
                return redirect('exams')->with('error', 'Exam not found');
            }

            $examQuestions = ExamQuestion::where('exam_id',$exam->id)->get();
            $examBackgrounds = ExamBackground::where('exam_id',$exam->id)->get();

            $mergedData = [];
            $backgrounds = [];

            // Organize backgrounds by background_number for quick lookup
            foreach ($examBackgrounds->toArray() as $background) {
                $backgrounds[$background['background_number']] = $background;
            }

            // Track grouped questions inline to maintain order
            $groupedQuestions = [];

            foreach ($examQuestions->toArray() as $question) {
                $backgroundNumber = $question['background_number'];

                if ($backgroundNumber == 0) {
                    // Keep background_number = 0 questions separate, preserving order
                    $mergedData[] = [
                        'background' => [],
                        'questions' => [$question]
                    ];
                } else {
                    // If already added, append to existing group
                    if (!isset($groupedQuestions[$backgroundNumber])) {
                        $groupedQuestions[$backgroundNumber] = [
                            'background' => $backgrounds[$backgroundNumber] ?? [],
                            'questions' => []
                        ];
                        $mergedData[] = &$groupedQuestions[$backgroundNumber]; // Maintain insertion order
                    }
                    $groupedQuestions[$backgroundNumber]['questions'][] = $question;
                }
            }

            // Ensure backgrounds with no questions are also included
            foreach ($backgrounds as $backgroundNumber => $background) {
                $hasQuestions = array_filter($mergedData, function ($item) use ($backgroundNumber) {
                    return !empty($item['questions']) && $item['questions'][0]['background_number'] == $backgroundNumber;
                });

                if (!$hasQuestions) {
                    $mergedData[] = [
                        'background' => $background,
                        'questions' => []
                    ];
                }
            }

            // echo "<pre>";
            // echo "<br>----------------------------------------------<br>";
            // print_r($mergedData);
            // die;

            $maxQuestions = env('MAX_QUESTIONS'); // Your secret key from the .env file
            $subjects = Subject::where('status','active')->orderBy('name','asc')->get();
            $grades = Grade::where('status','active')->orderBy('name','asc')->get();
            $states = State::where('status','active')->orderBy('name','asc')->get();
            $topics = Topic::where('status','active')->orderBy('name','asc')->get();
            $subtopics = Subtopic::where('status','active')->orderBy('name','asc')->get();
            $standards = Standard::where('sub_topic_id',$exam->subtopic)->where('status','active')->orderBy('name','asc')->get();

            return view('exams::edit',[
                'subjects' => $subjects,
                'grades' => $grades,
                'states' => $states,
                'topics' => $topics,
                'subtopics' => $subtopics,
                'standards' => $standards,
                'maxQuestions' => $maxQuestions,
                'exam' => $exam,
                'mergedData' => $mergedData,
            ]);

        } catch (Exception $e) {
            return redirect('user')->with('error', $exception->getMessage());           
        }
    }


    public function update(Request $request){
        
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $backgrounds = $request->input("backgrounds");
            $questions = $request->input("questions");

            $exam = Exam::where('uuid',$request->uuid)->first();
            $exam->title  = $request->exists("title") ? $request->input("title") : "";
            $exam->description  = $request->exists("description") ? $request->input("description") : "";
            $exam->subject  = $request->exists("subject") ? $request->input("subject") : "";
            $exam->topic  = $request->exists("topic") ? $request->input("topic") : "";
            $exam->subtopic  = $request->exists("subtopic") ? $request->input("subtopic") : "";
            $exam->state  = $request->exists("state") ? $request->input("state") : "";
            $exam->grade  = $request->exists("grade") ? $request->input("grade") : "";
            $exam->total_questions  = count($questions);
            $exam->created_by = $user->id;

            if($exam->save()){
                if(!empty($backgrounds)){
                    
                    foreach($request->backgrounds as $key => $bg){


                        if(isset($bg['background']) && $bg['background'] != ''){

                            if(isset($bg['id']) && $bg['id'] != ''){
                                $background = ExamBackground::find($bg['id']);
                            }else{
                                $background = new ExamBackground();
                            }

                            
                            $background->exam_id = $exam->id;
                            $background->background_number = $bg['background'];
                            $background->instruction = $bg['instructions'];
                            $background->instruction_two = $bg['instructions_2'];
                            $background->instruction_three = $bg['instructions_3'];

                            if (isset($bg['image_upload_1']) && count((array)$bg['image_upload_1'])) {
                                $bgImageOne = $bg['image_upload_1'];
                                $bgImageOneNameWithExt = $bgImageOne->getClientOriginalName();
                                list($bgImageOne_width,$bgImageOne_height)=getimagesize($bgImageOne);
                                // Get file path
                                $originalName = pathinfo($bgImageOneNameWithExt, PATHINFO_FILENAME);
                                $bgImageOneName = pathinfo($bgImageOneNameWithExt, PATHINFO_FILENAME);
                                // Remove unwanted characters
                                $bgImageOneName = preg_replace("/[^A-Za-z0-9 ]/", '', $bgImageOneName);
                                $bgImageOneName = preg_replace("/\s+/", '-', $bgImageOneName);

                                // Get the original image extension
                                $extension = $bgImageOne->getClientOriginalExtension();
                                if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                                    return redirect('exams/create')->with('error', trans('messages.INVALID_IMAGE'));
                                }
                                $bgImageOneName = 'bgimg_image_upload_1_'.$key.'_'.$bgImageOneName.'_'.time().'.'.$extension;
                                
                                $destinationPath = public_path('uploads/questions/backgrounds');
                                if($bgImageOne_width > 800){
                                    $bgImageOne_canvas = Image::create(800, 800);
                                    $bgImageOne_image = Image::read($bgImageOne->getRealPath())->resize(800, 800, function ($constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    });
                                    $bgImageOne_canvas->place($bgImageOne_image, 'center');
                                    $bgImageOne_canvas->save($destinationPath.'/'.$bgImageOneName,80);
                                }else{
                                    $bgImageOne->move($destinationPath, $bgImageOneName);
                                }
                                $bgImageOne_file = public_path('uploads/questions/backgrounds/'. $bgImageOneName);

                                $background->image_one = $bgImageOneName;
                            }

                            if (isset($bg['image_upload_2']) && count((array)$bg['image_upload_2'])) {
                                $bgImageTwo = $bg['image_upload_2'];
                                $bgImageTwoNameWithExt = $bgImageTwo->getClientOriginalName();
                                list($bgImageTwo_width,$bgImageTwo_height)=getimagesize($bgImageTwo);
                                // Get file path
                                $originalName = pathinfo($bgImageTwoNameWithExt, PATHINFO_FILENAME);
                                $bgImageTwoName = pathinfo($bgImageTwoNameWithExt, PATHINFO_FILENAME);
                                // Remove unwanted characters
                                $bgImageTwoName = preg_replace("/[^A-Za-z0-9 ]/", '', $bgImageTwoName);
                                $bgImageTwoName = preg_replace("/\s+/", '-', $bgImageTwoName);

                                // Get the original image extension
                                $extension = $bgImageTwo->getClientOriginalExtension();
                                if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                                    return redirect('exams/create')->with('error', trans('messages.INVALID_IMAGE'));
                                }
                                $bgImageTwoName = 'bgimg_image_upload_2_'.$key.'_'.$bgImageTwoName.'_'.time().'.'.$extension;
                                
                                $destinationPath = public_path('uploads/questions/backgrounds');
                                if($bgImageTwo_width > 800){
                                    $bgImageTwo_canvas = Image::create(800, 800);
                                    $bgImageTwo_image = Image::read($bgImageTwo->getRealPath())->resize(800, 800, function ($constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    });
                                    $bgImageTwo_canvas->place($bgImageTwo_image, 'center');
                                    $bgImageTwo_canvas->save($destinationPath.'/'.$bgImageTwoName,80);
                                }else{
                                    $bgImageTwo->move($destinationPath, $bgImageTwoName);
                                }
                                $bgImageTwo_file = public_path('uploads/questions/backgrounds/'. $bgImageTwoName);

                                $background->image_two = $bgImageTwoName;
                            }

                            $background->save();
                        }
                    }
                }

                if(!empty($questions)){
                    foreach($request->questions as $key => $examQuestion){
                        $question = new ExamQuestion();

                        if(isset($examQuestion['id']) && $examQuestion['id'] != ''){
                            $question = ExamQuestion::find($examQuestion['id']);
                        }else{
                            $question = new ExamQuestion();
                        }

                        $question->exam_id = $exam->id;
                        $question->background_number = $examQuestion['background'];
                        $question->question = $examQuestion['question'];
                        $question->question_text_two = $examQuestion['question_text_2'];
                        $question->question_type = $examQuestion['question_type'];

                        if (isset($examQuestion['standard']) && $examQuestion['standard'] !== null) {
                            $question->standard = $examQuestion['standard'];
                        }

                        if($examQuestion['question_type'] == 'mc'){
                            $question->option_one = $examQuestion['option1'];
                            $question->option_two = $examQuestion['option2'];
                            $question->option_three = $examQuestion['option3'];
                            $question->options_four = $examQuestion['option4'];
                            $question->correct_option = $examQuestion['correct_option'];
                            $question->explanation = $examQuestion['mc_explanation_text'];
                        }

                        if($examQuestion['question_type'] == 'sa'){
                            $question->short_answer_one = $examQuestion['sa_answer_1'];
                            $question->short_answer_two = $examQuestion['sa_answer_2'];
                            $question->explanation = $examQuestion['sa_explanation_text'];
                        }

                        if (isset($examQuestion['question_image_1']) && count((array)$examQuestion['question_image_1'])) {
                            $questionImageOne = $examQuestion['question_image_1'];
                            $questionImageOneNameWithExt = $questionImageOne->getClientOriginalName();
                            list($questionImageOne_width,$questionImageOne_height)=getimagesize($questionImageOne);
                            // Get file path
                            $originalName = pathinfo($questionImageOneNameWithExt, PATHINFO_FILENAME);
                            $questionImageOneName = pathinfo($questionImageOneNameWithExt, PATHINFO_FILENAME);
                            // Remove unwanted characters
                            $questionImageOneName = preg_replace("/[^A-Za-z0-9 ]/", '', $questionImageOneName);
                            $questionImageOneName = preg_replace("/\s+/", '-', $questionImageOneName);

                            // Get the original image extension
                            $extension = $questionImageOne->getClientOriginalExtension();
                            if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                                return redirect('exams/create')->with('error', trans('messages.INVALID_IMAGE'));
                            }
                            $questionImageOneName = 'qimg_question_image_1_'.$key.'_'.$questionImageOneName.'_'.time().'.'.$extension;
                            
                            $destinationPath = public_path('uploads/questions');
                            if($questionImageOne_width > 800){
                                $questionImageOne_canvas = Image::create(800, 800);
                                $questionImageOne_image = Image::read($questionImageOne->getRealPath())->resize(800, 800, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });
                                $questionImageOne_canvas->place($questionImageOne_image, 'center');
                                $questionImageOne_canvas->save($destinationPath.'/'.$questionImageOneName,80);
                            }else{
                                $questionImageOne->move($destinationPath, $questionImageOneName);
                            }
                            $questionImageOne_file = public_path('uploads/questions/'. $questionImageOneName);

                            $question->question_image_one = $questionImageOneName;
                        }

                        if (isset($examQuestion['question_image_2']) && count((array)$examQuestion['question_image_2'])) {
                            $questionImageTwo = $examQuestion['question_image_2'];
                            $questionImageTwoNameWithExt = $questionImageTwo->getClientOriginalName();
                            list($questionImageTwo_width,$questionImageTwo_height)=getimagesize($questionImageTwo);
                            // Get file path
                            $originalName = pathinfo($questionImageTwoNameWithExt, PATHINFO_FILENAME);
                            $questionImageTwoName = pathinfo($questionImageTwoNameWithExt, PATHINFO_FILENAME);
                            // Remove unwanted characters
                            $questionImageTwoName = preg_replace("/[^A-Za-z0-9 ]/", '', $questionImageTwoName);
                            $questionImageTwoName = preg_replace("/\s+/", '-', $questionImageTwoName);

                            // Get the original image extension
                            $extension = $questionImageTwo->getClientOriginalExtension();
                            if($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png'){
                                return redirect('exams/create')->with('error', trans('messages.INVALID_IMAGE'));
                            }
                            $questionImageTwoName = 'qimg_question_image_2_'.$key.'_'.$questionImageTwoName.'_'.time().'.'.$extension;
                            
                            $destinationPath = public_path('uploads/questions');
                            if($questionImageTwo_width > 800){
                                $questionImageTwo_canvas = Image::create(800, 800);
                                $questionImageTwo_image = Image::read($questionImageTwo->getRealPath())->resize(800, 800, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });
                                $questionImageTwo_canvas->place($questionImageTwo_image, 'center');
                                $questionImageTwo_canvas->save($destinationPath.'/'.$questionImageTwoName,80);
                            }else{
                                $questionImageTwo->move($destinationPath, $questionImageTwoName);
                            }
                            $questionImageTwo_file = public_path('uploads/questions/'. $questionImageTwoName);

                            $question->question_image_two = $questionImageTwoName;
                        }

                        $question->save();
                        
                    }
                }

                DB::commit();
            }

            return redirect('exams')->with('message', 'Exam updated successfully');

        } catch (\Exception $e) {
            return redirect('exams/create')->with('error', 'Exception- '.$e->getMessage());

        }
    }

    public function removeImage(Request $request,$id,$type,$name)
    {
        if($type == 'background'){
            $item = ExamBackground::findOrfail($id);
            if($name == 'image_one'){
                $item->image_one = null;   
            }else{
                $item->image_two = null;   
            }
            
        }else{
            $item = ExamQuestion::findOrfail($id);
            if($name == 'question_image_1'){
                $item->question_image_1 = null;   
            }else{
                $item->question_image_2 = null;   
            }
        }


        if ($item->save()) {
            return array('subject' =>array(),'success'=>true,'msg'=>'success');
        }
        else{
            return array('success'=>false,'subject'=>array(),'msg'=>'fails');
        }
    }

    public function createTestNumber()
    {
        // Get the last created order
        $lastExam = Exam::orderBy('id', 'desc')->first();
        $number = 0;
        if ($lastExam) {
            $number = substr($lastExam->test_number, 5);
        }
        return 'EXAM-' . sprintf('%06d', intval($number) + 1);
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

    public function getStandardBySubTopic($sub_topic_id)
    {
        $standards   =   Standard::where('sub_topic_id',$sub_topic_id)->orderBy('name','asc')->get();  
        if(!empty($standards->toArray())){
            return $arrayName = array('standards' => $standards);
        }else{
            return false;
        }
    }

}
