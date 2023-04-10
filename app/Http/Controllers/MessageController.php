<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
//models
use App\Message;
use App\Course;
use Session;
class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('view_message'))){
			
			$messages = DB::table(Session::get('company_id').'_messages as messages');
			$messages = $messages->orderBy('id','desc');
			$messages = $messages->leftJoin(Session::get('company_id').'_courses as courses','messages.course','=','courses.id');
			$messages = $messages->select('messages.*','courses.name as course_name');
			 
			
			if($request->input('search.value')!==''){
				$messages = $messages->where(function($query) use($request){
					$query->orWhere('messages.name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('courses.name','LIKE','%'.$request->input('search.value').'%');
				});
			}
			$messages = $messages->paginate($request->input('length'));
			$returnCourses = [];
			$data = [];
			$returnCourses['draw'] = $request->input('draw');
			$returnCourses['recordsTotal'] = $messages->total(); 
			$returnCourses['recordsFiltered'] = $messages->total();
			 
			foreach($messages as $course){
				$data[] = [
					$course->name.''.(isset($course->permission)?'('.$course->permission.')':""),
					$course->course_name,
						 
					'<a href="/message/update/'.$course->id.'" title="edit/update"><i class="fa fa-refresh" aria-hidden="true"></i></a>&nbsp;&nbsp;'.' | '.
					'&nbsp;&nbsp;<a href="javascript:messageController.delete('.$course->id.')" title="delete"><i class="fa fa-trash" aria-hidden="true"></i></a>&nbsp;&nbsp;'.'  |'.'<div class="btn" data-toggle="modal" data-target="#myModal'.$course->id.'"><i class="fa fa-eye" aria-hidden="true"></i></div><div id="myModal'.$course->id.'" class="modal fade" role="dialog">
					<div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><p><ul> '.nl2br($course->message).'</ul></p> </div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button> </div></div></div></div>'
				];
			}
			$returnCourses['data'] = $data;
			return response()->json($returnCourses);
		}else{
			$courses = Course::all();
			return view('cm_messages.message',['courses'=>$courses]);
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('add_message'))){
			$validator = Validator::make($request->all(),[
				'name'=>'required|unique:'.Session::get('company_id').'_messages',
				'message'=>'required',
				'permission'=>'required',
			]);
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				$errors = [];
				foreach($errorsBag as $error){
					$errors[] = implode("<br/>",$error);
				}
				$errors = implode("<br/>",$errors);
				return response()->json(['status'=>0,'errors'=>$errors],200);
			}
			
			$message = new Message;
			$message->name = ucwords($request->input('name'));
			$message->course = $request->input('course');
			$message->message = $request->input('message');
			$message->permission = $request->input('permission');
			$message->all_lead = $request->input('all_lead');
			$message->all_demo = $request->input('all_demo');
			$message->add_demo = $request->input('add_demo');
			$message->add_lead = $request->input('add_lead');
			//echo "<pre>";print_r($message);die;
			 
			if($message->save()){
				return response()->json(['status'=>1],200);
			}else{
				return response()->json(['status'=>0,'errors'=>'Message template not added'],400);
			}
		}else{
			return "Unh Cheatin`";
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('edit_message')){
			$message = Message::find($id);
			$courses = Course::all();
			return view('cm_messages.message_update',['message'=>$message,'courses'=>$courses]);
		}else{
			return "Unh Cheatin`";
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('edit_message')){
			$validator = Validator::make($request->all(),[
				'name'=>'required|unique:'.Session::get('company_id').'_messages,name,'.$id.',id',
				'message'=>'required',
				'permission'=>'required',
			]);
			if($validator->fails()){
				return redirect('message/update/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$message = Message::find($id);
			$message->name = ucwords($request->input('name'));
			$message->course = $request->input('course');
			$message->message = $request->input('message');
			$message->permission = $request->input('permission');
			$message->all_lead = $request->input('all_lead');
			$message->all_demo = $request->input('all_demo');
			$message->add_demo = $request->input('add_demo');
			$message->add_lead = $request->input('add_lead');
			if($message->save()){
				$request->session()->flash('alert-success', 'Message template successfully updated !!');
				return redirect(url('/message'));
			}else{
				$request->session()->flash('alert-danger', 'Message template not updated !!');
				return redirect(url('/message/update/'.$id));			
			}
		}else{
			return "Unh Cheatin`";
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('delete_message'))){
			try{
				$message = Message::findorFail($id);
				if($message->delete()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Message template not found'],200);
			}
		}else{
			return "Unh Cheatin`";
		}
    }
	
    /**
     * Return the specified resource from storage.
     *
     * @param  obj  Request object
     * @param  int  $id
     * @return Json Response
     */
	public function getMessagesList(Request $request, $id){
		if($request->ajax()){
			$messages = Message::where('permission','LIKE','%G%')->orWhere('course',$id)->select('id','name')->orderBy('id','DESC')->get();
			// echo "<pre>";print_r($messages);die;
			return response()->json($messages,200);
		} 
	}
}
