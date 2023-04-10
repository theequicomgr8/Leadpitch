<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
//models
use App\Course;
use App\Assigncourse;
use App\Courseassignment;
use App\Lead;
use App\Source;
use App\CromaCourse;
use App\Status;
use App\Capability;
use App\User;
use App\Message;
use Session;
use Auth;
use Excel;
class CourseAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseassignmentindex(Request $request)
    {   
		 
		
		 $users = User::orderBy('name','ASC')->get();
		 
		 
		 
		$assigncourse = Courseassignment::get();		 
		$assign_dom_course=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){		
			if($assignc->assign_dom_course !=NULL){
		$assign_dom_course = array_merge($assign_dom_course,unserialize($assignc->assign_dom_course));
		}
		}
		}	
 
		$coursesnotexit  = CromaCourse::whereNotIn('id',$assign_dom_course)->orderBy('name','asc')->get();	 
		$sourceCourses="";
		foreach($coursesnotexit as $courseNot){
		$sourceCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";
		}
	 
			//echo "<pre>";print_r($coursesnotexit); 
	
		 
		 	 
		$assign_int_course=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){		
		if($assignc->assign_int_course !=''){	
		
	 
		$assign_int_course = array_merge($assign_int_course,unserialize($assignc->assign_int_course));
		}
		}
		}	
		 
 
		$coursesIntnotexit  = CromaCourse::whereNotIn('id',$assign_int_course)->orderBy('name','asc')->get();	 
		//echo "<pre>";print_r($coursesIntnotexit);die;
		$sourceIntCourses="";
		foreach($coursesIntnotexit as $courseIntNot){
		$sourceIntCourses .= "<option value=\"$courseIntNot->id\">$courseIntNot->name</option>";
		}
	 
	
	
	
	
	
	
	
	
	
		$courses = CromaCourse::get();
	 
	//echo "<pre>";print_r($sourceCourses);die;
		
		
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_assigncourse.all-course-assignment',['courses'=>$courses,'search'=>$search,'users'=>$users,'sourceCourses'=>$sourceCourses,'assigncourse'=>$assigncourse,'courses'=>$courses,'sourceIntCourses'=>$sourceIntCourses]);
    } 
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function duplicatecourseindex(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::all();
		 
			$courses = CromaCourse::select('id','name')->get();	
		$statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->get();
		 $users='';
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			 			
			$capability = Capability::select('user_id')->get();	 		 
			 $user_list=[];
			foreach($capability as $manage){
				array_push($user_list,$manage->user_id);		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_list)->orderBy('name','ASC')->get();
		}else{
			$users = User::select('id','name')->where('id',$request->user()->id)->orderBy('name','ASC')->get();
		}
		 
		
		if(Auth::user()->current_user_can('manager')){
			$capabilitys = Capability::select('user_id')->where('manager',$request->user()->id)->get();		
		    $user_lists=[$request->user()->id];
			foreach($capabilitys as $manage){
				array_push($user_lists,$manage->user_id);		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_lists)->orderBy('name','ASC')->get();
			
			
		}  
		
		
			$assigncourse = Courseassignment::get();
 //echo "<pre>";print_r($assigncourse);die;
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_assigncourse.duplicate-course-assignment',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users,'assigncourse'=>$assigncourse]);
    } 
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function courseAssignmentSave(Request $request)
    { 
	
	//echo "<pre>";print_r($_POST);die;
        if($request->ajax()){
			
			
			 if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required|unique:'.Session::get('company_id').'_courseassignment',
				'assign_dom_course'=>'required',
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
			
			$assigncourse = new Courseassignment;
			$assigncourse->counsellors = $request->input('counsellors');
			if($request->has('assign_dom_course')){
				$assigncourse->assign_dom_course = serialize($request->input('assign_dom_course'));
			}
			
			if($request->has('assign_int_course')){
				$assigncourse->assign_int_course = serialize($request->input('assign_int_course'));
			}
			
			//echo "<pre>";print_r($assigncourse);die;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveleadcount(Request $request)
    { 
	
	//echo "<pre>";print_r($_POST);die;
        if($request->ajax()){
			
			
			 if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$validator = Validator::make($request->all(),[
				'leadcount'=>'required',
				 
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
			
				$id= 	$request->input('id');  
			$leadcount=$request->input('leadcount');
			$updatearray = array('leadcount'=>$leadcount);
			 $leadupdate = Courseassignment::where('id',$id)->update($updatearray);
			 
			if($leadupdate){
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function duplicatecourseassignment(Request $request)
    { 
	
	//echo "<pre>";print_r($_POST);die;
        if($request->ajax()){
			
			
			 if($request->user()->current_user_can('super_admin')|| $request->user()->current_user_can('administrator') ){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required|unique:'.Session::get('company_id').'_courseassignment',
				'duplicate_course'=>'required',
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
			
			$assigncourse = new Courseassignment;
			$assigncourse->counsellors = $request->input('counsellors');
			if($request->has('duplicate_course')){
				$assigncourse->duplicatecourse = serialize($request->input('duplicate_course'));
			}
			
			//echo "<pre>";print_r($assigncourse);die;
			if($assigncourse->save()){
				return response()->json(['status'=>1],200);
			}else{
				return response()->json(['status'=>0,'errors'=>'duplicate course not added'],400);
			}
		}else{
			return "Unh Cheatin`";
		}
			 
	 
		}
    }

	  /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editcourse(Request $request,$id)
    {
	 
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			
			
			
			
		$assigncourse = Courseassignment::get();
		$userCourses=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){
			 
			if($assignc->assign_dom_course !=NULL){
		$userCourses = 	array_merge($userCourses,unserialize($assignc->assign_dom_course));
			}
		}
		}	

		$coursesnotexit  = CromaCourse::whereNotIn('id',$userCourses)->orderBy('name','asc')->get();
 
		$sourceCourses="";
		foreach($coursesnotexit as $courseNot){

		$sourceCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";

		} 
			$edit_data = Courseassignment::find($id);
			//echo "<pre>";print_r($edit_data);die;
			$courses = CromaCourse::select('id','name')->get();		
			$users = User::select('id','name')->get();
			$destinationCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->assign_dom_course)){
				$assigncourseid = unserialize($edit_data->assign_dom_course);
				foreach($courses as $course){
					if(in_array($course->id,$assigncourseid)){
						$destinationCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					} 
				}
			} 
			
			
			
				
		$assigncourse = Courseassignment::get();
		$userIntCourses=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){
			 
			if($assignc->assign_int_course !=NULL){
		$userIntCourses = 	array_merge($userIntCourses,unserialize($assignc->assign_int_course));
			}
		}
		}	

		$coursesIntnotexit  = CromaCourse::whereNotIn('id',$userIntCourses)->orderBy('name','asc')->get();
 
		$sourceIntCourses="";
		foreach($coursesIntnotexit as $courseNot){
		$sourceIntCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";

		} 
			$edit_data = Courseassignment::find($id);
			//echo "<pre>";print_r($edit_data);die;
			$courses = CromaCourse::select('id','name')->get();		
			$users = User::select('id','name')->get();
			$destinationIntCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->assign_int_course)){
				$assigncourseid = unserialize($edit_data->assign_int_course);
				foreach($courses as $course){
					if(in_array($course->id,$assigncourseid)){
						$destinationIntCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					} 
				}
			} 
			
			
			
			
			
			
			//echo $edit_data->counsellors;
			if(!empty($users)){
			foreach($users as $user){
				if($user->id==$edit_data->counsellors){
					$sourceCounsellors = "<option value=\"$user->id\" selected>$user->name</option>";
				}else{
					$sourceCounsellors .= "<option value=\"$user->id\">$user->name</option>";
				}
				}
			}
			//echo "<pre>";print_r($sourceCounsellors);die;
			 
			return view('cm_assigncourse.update_course_assignment',['courses'=>$courses,'sourceCourses'=>$sourceCourses,'sourceCounsellors'=>$sourceCounsellors,'destinationCounsellors'=>$destinationCounsellors,'edit_data'=>$edit_data,'users'=>$users,'sourceIntCourses'=>$sourceIntCourses,'destinationIntCounsellors'=>$destinationIntCounsellors]);
		}else{
			return "Unh Cheatin`";
		}
       // return view('cm_courses.create_assign_course',['users'=>$users,'edit_data'=>$edit_data]);
    }

    public function editsavecourse(Request $request, $id){
		
		//echo "<pre>";print_r($_POST);die;
		
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required|unique:'.Session::get('company_id').'_courseassignment,counsellors,'.$id,
				'assign_dom_course'=>'required',
			]);
			if($validator->fails()){
				return redirect('lead/editassigncourse/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$assigncourse = Courseassignment::find($id);
			$assigncourse->counsellors = $request->input('counsellors');
			
			if($request->has('assign_dom_course')){
				$assigncourse->assign_dom_course = serialize($request->input('assign_dom_course'));
			}
			
			if($request->has('assign_int_course')){
				$assigncourse->assign_int_course = serialize($request->input('assign_int_course'));
			}else{
				$assigncourse->assign_int_course="";
			}
			
			
			
			
			//echo "<pre>";print_r($assigncourse);die;
			if($assigncourse->save()){
				 
				$request->session()->flash('alert-success', 'Course successful updated !!');
				return redirect(url('/lead/all-course-assignment'));	
			}else{
				$request->session()->flash('alert-danger', 'Assign Course not updated !!');
				return redirect(url('/lead/editassigncourse/'.$id));			
			}
		}else{
			return "Unh Cheatin`";
		}
		
	}
	
	
	 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editduplicatecourse(Request $request,$id)
    {
	 
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			
			 
			
			
			
			
			$edit_data = Courseassignment::find($id);
			//echo "<pre>";print_r($edit_data);die;
			$courses = CromaCourse::select('id','name')->get();		
			$users = User::select('id','name')->get();
			$destinationDomCounsellors = "";
			$destinationIntCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->assign_dup_dom_course)){
				$assigncourseid = unserialize($edit_data->assign_dup_dom_course);
				foreach($courses as $course){
					if(in_array($course->id,$assigncourseid)){
						$destinationDomCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					}else{
					$destinationDomCounsellors .= "<option value=\"$course->id\">$course->name</option>";

					}						
				}
			} 
			
			
			if(!is_null($edit_data->assign_dup_int_course)){
				$assigncourseid = unserialize($edit_data->assign_dup_int_course);
				foreach($courses as $course){
					if(in_array($course->id,$assigncourseid)){
						$destinationIntCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					}else{
					$destinationIntCounsellors .= "<option value=\"$course->id\">$course->name</option>";

					}						
				}
			} 
			
			
			
			//echo $edit_data->counsellors;
			if(!empty($users)){
			foreach($users as $user){
				if($user->id==$edit_data->counsellors){
					$sourceCounsellors = "<option value=\"$user->id\" selected>$user->name</option>";
				}else{
					$sourceCounsellors .= "<option value=\"$user->id\">$user->name</option>";
				}
				}
			}
			//echo "<pre>";print_r($sourceCounsellors);die;
			 
			return view('cm_assigncourse.update_duplicate_course_assignment',['courses'=>$courses,'sourceCounsellors'=>$sourceCounsellors,'destinationDomCounsellors'=>$destinationDomCounsellors,'destinationIntCounsellors'=>$destinationIntCounsellors,'edit_data'=>$edit_data,'users'=>$users]);
		}else{
			return "Unh Cheatin`";
		}
       // return view('cm_courses.create_assign_course',['users'=>$users,'edit_data'=>$edit_data]);
    }

    
	
	
	public function editsaveduplicatecourse(Request $request, $id){
		
	//	echo "<pre>";print_r($_POST);die;
		
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required|unique:'.Session::get('company_id').'_courseassignment,counsellors,'.$id,
				'assign_dup_dom_course'=>'required',
			]);
			if($validator->fails()){
				return redirect('lead/editduplicatecourse/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$assigncourse = Courseassignment::find($id);
			$assigncourse->counsellors = ucwords($request->input('counsellors'));
			if($request->has('assign_dup_dom_course')){
				$assigncourse->assign_dup_dom_course = serialize($request->input('assign_dup_dom_course'));
			}
			if($request->has('assign_dup_int_course')){
				$assigncourse->assign_dup_int_course = serialize($request->input('assign_dup_int_course'));
			}else{
				$assigncourse->assign_dup_int_course="";
			}
			
			 
			if($assigncourse->save()){
				 
				$request->session()->flash('alert-success', 'Course successful updated !!');
				return redirect(url('/lead/duplicate-course-assignment'));	
			}else{
				$request->session()->flash('alert-danger', 'Assign Course not updated !!');
				return redirect(url('/lead/editduplicatecourse/'.$id));			
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
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
			try{
				$assigncourse = Courseassignment::findorFail($id);
				
			//	echo "<pre>";print_r($assigncourse);die;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assigncoursestatus(Request $request, $id,$val)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
			try{
				$assigncourse = Courseassignment::findorFail($id);
				$assigncourse->status = $val;
			//	echo "<pre>";print_r($assigncourse);die;
				if($assigncourse->save()){
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
	
	
	
	
	
}
