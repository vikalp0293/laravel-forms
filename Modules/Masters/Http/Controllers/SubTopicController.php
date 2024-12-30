<?php

namespace Modules\Masters\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Masters\Entities\Subtopic;
use Modules\Masters\Entities\Topic;
use Auth;
use DataTables;
use DB;
use URL;
use Illuminate\Support\Str;
use Image;

class SubTopicController extends Controller
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
        $user = Auth::user();

        $topics = Topic::where('status','active')->orderBy('name','ASC')->get();

        $data = subtopic::select('s.name as topic','m_sub_topics.*')
                ->leftjoin('m_topics as s','s.id','=','m_sub_topics.topic_id')
                ->where(function ($query) use ($request) {
                if (!empty($request->toArray())) {
                        if(isset($request->status) && (!empty($request->status) ) ){
                            $query->where('m_sub_topics.status',$request->input('status'));
                        }

                        if(isset($request->name) && (!empty($request->name) || $request->name != 0)){
                            $query->where('m_sub_topics.name',$request->input('name'));
                        }
                    }
                })
                ->orderBy('m_sub_topics.id','DESC')
                ->get();      
        if ($request->ajax()) {
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) use ($userPermission){
                        $btn = '';
                        $btn .= "<ul class='nk-tb-actions gx-1'><li><div class='drodown mr-n1'><a href='#' class='dropdown-toggle btn btn-icon btn-trigger' data-toggle='dropdown'><em class='icon ni ni-more-h'></em></a><div class='dropdown-menu dropdown-menu-right'><ul class='link-list-opt no-bdr'>";
                        $btn .= "<li>
                                    <a href='#' data-target='addDrawer' data-id='".$row->id."' class='editItem toggle'>
                                        <em class='icon ni ni-edit'></em> <span>Edit</span>
                                    </a>
                                </li>";
                        $confirmMsg = 'Are you sure, you want to delete it?';
                        $btn .= "<li>
                                    <a href='#' data-id='".$row->id."' class='eg-swal-av3'>
                                        <em class='icon ni ni-trash'></em> <span>Delete</span>
                                    </a>
                                </li>";
                        $btn .= "</ul></div></div></li></ul>";

                        return $btn;
                    })
                    ->addColumn('created_at', function ($row) {
                        return date(\Config::get('constants.DATE.DATE_FORMAT') , strtotime($row->created_at));
                    })

                    ->addColumn('updated_at', function ($row) {
                        return date(\Config::get('constants.DATE.DATE_FORMAT') , strtotime($row->updated_at));
                    })

                    ->addColumn('is_featured', function ($row) {
                        return $value = ($row->is_featured == '1') ? 'Yes' : 'No';
                    })

                    ->addColumn('status', function ($row) {
                        $value = ($row->status == 'active') ? 'badge badge-success' : 'badge badge-danger';
                        $status = '
                            <span class="tb-sub">
                                <span class="'.$value.'">
                                    '.ucfirst($row->status).'
                                </span>
                            </span>
                        ';
                        return $status;
                    })
                    ->rawColumns(['action','created_at','updated_at','status'])
                    ->make(true);
        }
        return view('masters::subtopic/index',['subtopics'=>$data,'topics' => $topics]);

    }

    public function getSubTopic(Request $request)
    {
        $id = $request->input("id");
        $subtopic   =   Subtopic::where('id',$id)->first();

        if(!empty($subtopic->toArray())){
            return array('subtopic' => $subtopic,'success'=>true);
        }else{
            return array('success'=>false,'subtopic'=>array());
        }
    }

    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {

            $rules = array(
                'name' => 'required',
            );
            $user = Auth::user();
            
            $validator = \Validator::make($request->all(), $rules);


            if ($validator->fails()) {
                $messages = $validator->messages();
                return redirect('masters/subtopic')->with('error', $messages);
            } else {
                

                if($request->input("id") && $request->input("id")!='0' && $request->input("id")!=''){
                    $subtopic = Subtopic::find($request->input("id"));
                    $msg = trans('messages.UNIT_UPDATED');
                }else{

                    $isExists = Subtopic::where('name',$request->input("name"))->get()->toArray();
                    
                    if(!empty($isExists)){
                        return redirect('masters/subtopic')->with('error', trans('messages.UNIT_TITLE'));
                    }
                
                    $subtopic = new Subtopic();
                    $msg = trans('messages.UNIT_ADDED');
                }

                $subtopic->name = $request->input("name");
                $subtopic->topic_id = $request->input("topic_id");
                $subtopic->status = $request->input("status")=='1' ? 'active' : "inactive";
                
                
                if($subtopic->save()){
                    return redirect('masters/subtopic')->with('message', $msg);
                }else{
                    return redirect('masters/subtopic')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
                }
            }
            
        } catch (Exception $e) {
            return redirect('masters/subtopic')->with('error', trans('messages.SOMETHING_WENT_WRONG'));
        }
    }

    public function massUpdate(Request $request)
    {
        try {
            if( empty($request->input("ids"))){
                return array('success'=>false,'item'=>array(),'msg'=>trans('messages.SELECT_AN_ITEM'));
                
            }

            if($request->input("status") == '0' ){
                return array('success'=>false,'item'=>array(),'msg'=>trans('messages.SELECT_BULK_STATUS'));
            }
            
            $i=0;    
            foreach($request->input("ids") as $key=>$value ){
                $subtopic = Subtopic::find($value);
                $subtopic->status = $request->input("status");
                $subtopic->save();
                $i++;
            }


            if($i>0){
                return array('success'=>true,'item' => array(),'msg'=>'true');
            }else{
                return array('success'=>false,'item'=>array(),'msg'=>trans('messages.NO_UPDATE_REQUIRED'));
            }
                            
        } catch (Exception $e) {
            
            return array('success'=>false,'item'=>array(),'msg'=>trans('messages.SOMETHING_WENT_WRONG'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $id = $request->input("id");
        $item = Subtopic::findOrfail($id);

        if ($item->delete()) {
            return array('subtopic' =>array(),'success'=>true,'msg'=>'success');
        }
        else{
            return array('success'=>false,'subtopic'=>array(),'msg'=>'fails');
        }
    }
}
