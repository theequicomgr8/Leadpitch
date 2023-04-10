<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
//models
use App\Course;
use App\Assigncourse;
use App\Lead;
use App\User;
use App\Message;
use Session;
use Auth;
use Excel;
class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('view_course')){
			 
			$managers = DB::table(Session::get('company_id').'_capabilities')->select('user_id')->where('user_id','<>',$request->user()->id)->get();
			 
			  			$user_list=[];
			if(!empty($managers)){

			foreach($managers as $manage){						 
			array_push($user_list,$manage->user_id);			
			}
			}
			$users = User::select('id','name')->whereIn('id',$user_list)->orderBy('name','ASC')->get();


			$courses = Course::orderBy('id','desc')->get();
			
			return view('cm_courses.course',compact('users','courses'));			
		}else{
			return "Unh Cheatin`";
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
        if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('add_course'))){
			$validator = Validator::make($request->all(),[
				'name'=>'required|unique:'.Session::get('company_id').'_courses',
				'counsellors'=>'required',
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
			
			$course = new Course;
			$course->name = ucwords($request->input('name'));
			if($request->has('counsellors')){
				$course->counsellors = serialize($request->input('counsellors'));
			}
			if($course->save()){
				return response()->json(['status'=>1],200);
			}else{
				return response()->json(['status'=>0,'errors'=>'Course not added'],400);
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
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('edit_course')){
			$course = Course::find($id);
			$users = User::select('id','name')->get();
			$sourceCounsellors = $destinationCounsellors = "";
			if(!is_null($course->counsellors)){
				$counsellors = unserialize($course->counsellors);
				foreach($users as $user){
					if(in_array($user->id,$counsellors)){
						$destinationCounsellors .= "<option value=\"$user->id\" selected>$user->name</option>";
					}else{
						$sourceCounsellors .= "<option value=\"$user->id\">$user->name</option>";
					}
				}
			}else{
				foreach($users as $user){
					$sourceCounsellors .= "<option value=\"$user->id\">$user->name</option>";
				}
			}
			return view('cm_courses.course_update',['course'=>$course,'users'=>['sourceCounsellors'=>$sourceCounsellors,'destinationCounsellors'=>$destinationCounsellors]]);
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
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('edit_course')){
			$validator = Validator::make($request->all(),[
				'name'=>'required|unique:'.Session::get('company_id').'_courses,name,'.$id,
				'counsellors'=>'required',
			]);
			if($validator->fails()){
				return redirect('course/update/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$course = Course::find($id);
			$course->name = ucwords($request->input('name'));
			if($request->has('counsellors')){
				$course->counsellors = serialize($request->input('counsellors'));
			}
			if($course->save()){
				DB::table(Session::get('company_id').'_leads')->where('course',$course->id)->update(array('course_name'=>$course->name));
				$request->session()->flash('alert-success', 'Course successful updated !!');
				return redirect(url('/course'));
			}else{
				$request->session()->flash('alert-danger', 'Course not updated !!');
				return redirect(url('/course/update/'.$id));			
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
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('delete_course'))){
			try{
				$course = Course::findorFail($id);
				if($course->delete()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Course not found'],200);
			}
		}else{
			return "Unh Cheatin`";
		}
    }
	
    /**
     * Get paginated courses.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function getPaginatedCourses(Request $request)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('view_course'))){
			$courses = Course::orderBy('id','desc');
			if($request->input('search.value')!==''){
				$courses = $courses->where(function($query) use($request){
				    
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')
					    ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			$courses = $courses->paginate($request->input('length'));
			$returnCourses = [];
			$data = [];
			$returnCourses['draw'] = $request->input('draw');
			$returnCourses['recordsTotal'] = $courses->total();
			$returnCourses['recordsFiltered'] = $courses->total();
			$users = User::select('id','name')->get();
			foreach($courses as $course){
				$counsellor_names = '';
				if($course->counsellors !== NULL){
					$counsellors = unserialize($course->counsellors);
					foreach($users as $user){
						if(in_array($user->id,$counsellors)){
							$counsellor_names .= "<span class=\"label label-default\">$user->name</span> ";
						}
					}
				}
				$message_names = "";
				$messages = Message::where('course',$course->id)->select('name')->get();
				if($messages){
					foreach($messages as $message){
						$message_names .= "<span class=\"label label-default\">{$message->name}</span> ";
					}
				}
				$data[] = [
					$course->name,
					$message_names,
					$counsellor_names,
					'<a href="/course/update/'.$course->id.'"><i class="fa fa-refresh" aria-hidden="true"></i></a> | <a href="javascript:courseController.delete('.$course->id.')"><i class="fa fa-trash" aria-hidden="true"></i></a>'
				];
			}
			$returnCourses['data'] = $data;
			return response()->json($returnCourses);
		}else{
			return "Unh Cheatin`";
		}
    }
	
    /**
     * Get matches courses based on ajax.
     *
     * @param  string
     * @return JSON Object having matched course details
     */
    public function getCourseAjax(Request $request)
    {
		if($request->ajax()){
			if(null==$request->input('q')){
				$courses = Course::take(6)->get();
			}else{
				$courses = Course::where('name','LIKE',"%".$request->input('q')."%")->get();
			}
			return response()->json($courses,200);
		}
	}
	
    /**
     * Return the specified resource from storage.
     *
     * @param  obj  Request object
     * @param  int  $id
     * @return Json Response
     */
	public function getCourseCounsellors(Request $request, $id){
		if($request->ajax()){
			/*$course = Course::findorFail($id);
			$counsellor_names = "<option value=''>-- SELECT COURSE COUNSELLOR --</option>";
			if($course){
				$users = User::select('id','name')->orderBy('name','ASC')->get();
				if(!is_null($course->counsellors)){
					$counsellors = unserialize($course->counsellors);
					foreach($users as $user){
						if($request->user()->current_user_can('super_admin')||$request->user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
							$boldStyle = '';
							if(in_array($user->id,$counsellors))
								$boldStyle = "style='font-weight:bold'";
							$counsellor_names .= "<option value='$user->id' $boldStyle>$user->name</option>";
						}else{
							if(in_array($user->id,$counsellors)){
								$counsellor_names .= "<option value='$user->id' style='font-weight:bold' selected>$user->name</option>";
							}
						}
					}
				}
			}
			
			*/
			
			
			$users = User::select('id','name')->orderBy('name','ASC')->get();
				if(!empty($users)){
					$counsellor_names = "<option value=''>-- SELECT COURSE COUNSELLOR --</option>";
					foreach($users as $user){
					    	$counsellor_names .= "<option value='$user->id'>$user->name</option>";
					} 
				}
			return response()->json($counsellor_names,200);
		} 
	}
	
	
	  /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assignCourse(Request $request)
    {
	 
		$users = User::select('id','name')->orderBy('name','ASC')->get();		 
		$assigncourse = Assigncourse::get();
		$userCourses=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){
		$userCourses = 	array_merge($userCourses,unserialize($assignc->assigncourse));
		}
		}	

		$coursesnotexit  = Course::whereNotIn('id',$userCourses)->orderBy('name','asc')->get();

		$sourceCourses="";
		foreach($coursesnotexit as $courseNot){

		$sourceCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";

		}
		$courses = Course::get();
        return view('cm_courses.create_assign_course',['users'=>$users,'sourceCourses'=>$sourceCourses,'assigncourse'=>$assigncourse,'courses'=>$courses]);
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAssignCourse(Request $request)
    { 
	
 
        if($request->ajax()){
			
			
			 if($request->user()->current_user_can('super_admin') ){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required|unique:'.Session::get('company_id').'_assigncourse',
				'assigncourse'=>'required',
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
			
			$assigncourse = new Assigncourse;
			$assigncourse->counsellors = $request->input('counsellors');
			if($request->has('assigncourse')){
				$assigncourse->assigncourse = serialize($request->input('assigncourse'));
			}
			if($assigncourse->save()){
				return response()->json(['status'=>1],200);
			}else{
				return response()->json(['status'=>0,'errors'=>'Assigncourse not added'],400);
			}
		}else{
			return "Unh Cheatin`";
		}
			 
	 
		}
    }
	
	 /**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAssignCourse(Request $request)
    {   
		if($request->ajax()){
			$batchs =New Batch;		  

			if($request->input('search.value')!==''){
				$batchs = $batchs->where(function($query) use($request){
					$query->orWhere('batch','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('user','LIKE','%'.$request->input('search.value').'%')						 
						  ->orWhere('trainer','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 
			$batchs = $batchs->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $batchs->total();
			$returnLeads['recordsFiltered'] = $batchs->total();
			$returnLeads['batchRecordCollection'] = [];
			foreach($batchs as $batch){
				 
					$action = '';
					$separator = '';
			    	 
				 
					$action .= $separator.'<a href="/demo/batch/editbatch/'.$batch->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
					$separator = ' | ';	
					
					$action .= $separator.'<a href="/demo/batch/editbatch/'.$batch->id.'" title="Edit"><i class="fa fa-trash" aria-hidden="true"></i></a>';
					$separator = ' | ';
					  
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$batch->id'></th>",
						 
						$batch->batch,
						$batch->user,
						$batch->trainer,	 
						$action
					];
					$returnLeads['batchRecordCollection'][] = $batch->id;
				 
			}
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
	
	 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editcourse(Request $request,$id)
    {
	 
		if($request->user()->current_user_can('super_admin') ){
			
			
			
			
		$assigncourse = Assigncourse::get();
		$userCourses=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){
		$userCourses = 	array_merge($userCourses,unserialize($assignc->assigncourse));
		}
		}	

		$coursesnotexit  = Course::whereNotIn('id',$userCourses)->orderBy('name','asc')->get();

		$sourceCourses="";
		foreach($coursesnotexit as $courseNot){

		$sourceCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";

		}
			 
			
			
			$edit_data = Assigncourse::find($id);
		 
			$courses = Course::select('id','name')->get();		
			$users = User::select('id','name')->get();
			$destinationCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->assigncourse)){
				$assigncourseid = unserialize($edit_data->assigncourse);
				foreach($courses as $course){
					if(in_array($course->id,$assigncourseid)){
						$destinationCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					} 
				}
			} 
		 
			if(!empty($users)){
			foreach($users as $user){
				if($user->id==$edit_data->counsellors){
					$sourceCounsellors = "<option value=\"$user->id\" selected>$user->name</option>";
				}else{
					$sourceCounsellors .= "<option value=\"$user->id\">$user->name</option>";
				}
				}
			}
		 
			 
			return view('cm_courses.update_assign_course',['courses'=>$courses,'sourceCourses'=>$sourceCourses,'sourceCounsellors'=>$sourceCounsellors,'destinationCounsellors'=>$destinationCounsellors,'edit_data'=>$edit_data,'users'=>$users]);
		}else{
			return "Unh Cheatin`";
		}
       
    }

    
	
	
	public function editsavecourse(Request $request, $id){
		
		 
		
		if($request->user()->current_user_can('super_admin')){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required|unique:'.Session::get('company_id').'_assigncourse,counsellors,'.$id,
				'assigncourse'=>'required',
			]);
			if($validator->fails()){
				return redirect('course/assignment/editassigncourse/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$assigncourse = Assigncourse::find($id);
			$assigncourse->counsellors = ucwords($request->input('counsellors'));
			if($request->has('assigncourse')){
				$assigncourse->assigncourse = serialize($request->input('assigncourse'));
			}
			if($assigncourse->save()){
				 
				$request->session()->flash('alert-success', 'Course successful updated !!');
				return redirect(url('/course/assignment/assigncourse'));
			}else{
				$request->session()->flash('alert-danger', 'Assign Course not updated !!');
				return redirect(url('/course/assignment/editassigncourse/'.$id));			
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
    public function assigncoursedelete(Request $request, $id)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('delete_course'))){
			try{
				$assigncourse = Assigncourse::findorFail($id);
				if($assigncourse->delete()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Course not found'],200);
			}
		}else{
			return "Unh Cheatin`";
		}
    }
	
	
	
 
	  /**
     * Get leads excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
    public function getCourseExcel(Request $request)
    {  	 
	
		//if($request->ajax()){
			$courses = DB::table('croma_cat_course as course');			 
			$courses = $courses->select('*');
			$courses = $courses->orderBy('course.id','ASC');		 
			$courses = $courses->get();
			  
			$returnLeads = [];
			$data = [];
			 
			$returnLeads['recordCollection'] = [];
			 
			$totremarks='';
			foreach($courses as $course){
				 
								$msg="";
						$messages = DB::table('croma_messages')->select('name')->where('course',$course->id)->get(); 
						foreach($messages as $message){
						$msg =$message->name;

						} 
				 


								 

						
						if($course->counsellors !== NULL){
							$counsellors = unserialize($course->counsellors);
						$usermame="";
						foreach($counsellors as $user_id){
							$users = User::where('id',$user_id)->first();
						if($users){
				    	$usermame .= $users->name.' ,';
						}
						}
									 
						}else{
							$usermame="";
						}							
								
					$arr[] = [
						"ID"=>$course->id,
						"Course"=>$course->name,
						"Message"=>$msg,
						"Counsellor"=>$usermame,				 
						 
					];
					$returnLeads['recordCollection'][] = $course->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');			 
			Excel::create('lead_course_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		 
    }
	
    
	
	
	
}
