<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
use Carbon\Carbon;
use Mail;
//models
use App\Lead;
use App\Demo;
use App\Source;
use App\Course;
use App\Status;
use App\LeadFollowUp;
use App\DemoFollowUp;
use App\Message;
use App\Capability;
use App\FeesGetTrainer;
use App\Courseassignment;
use App\CromaCourse;
use App\CromaLead;
use App\Inquiry;

use App\Assigncourse;
use Excel;
use App\User;
use App\MailerTable;
use App\Feedback;
use App\SiteFeedback;
use Auth;
use Session;

class LeadManagmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::all();
		$courses = Course::all();
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
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}



        return view('cm_leadsmangment.all-lead-managment',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    } 
    
    	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteindex(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::all();
		$courses = Course::all();
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
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}



        return view('cm_leadsmangment.all-delete-lead',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    } 
    
    
	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function duplicateleadindex(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::all();
		$courses = Course::all();
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
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_leadsmangment.all-duplicate-lead',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    } 
	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function counsllorviewindex(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
	
		$sources = Source::all();
		$courses = Course::all();
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
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
//	echo "<pre>";print_r($users);die;
        return view('cm_leadsmangment.all-counsllor-view',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    } 
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseassignmentindex(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::all();
		 
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
		$userCourses=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){
		$userCourses = 	array_merge($userCourses,unserialize($assignc->assigncourse));
		}
		}	

		$coursesnotexit  = CromaCourse::whereNotIn('id',$userCourses)->orderBy('name','asc')->get();

		$sourceCourses="";
		foreach($coursesnotexit as $courseNot){

		$sourceCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";

		}
	 
	
		$courses = CromaCourse::get();
	 
	//echo "<pre>";print_r($assigncourse);die;
		
		
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_leadsmangment.all-course-assignment',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users,'sourceCourses'=>$sourceCourses,'assigncourse'=>$assigncourse,'courses'=>$courses]);
    } 
	
	
	public function getPaginationCounsellorView(Request $request)
	{
		   
		if($request->ajax()){			 
		$users = User::where('status',1)->orderBy('name','ASC');		 
		if($request->input('search.value')!==''){
				$users = $users->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')					     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			$users = $users->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $users->total();
			$returnLeads['recordsFiltered'] = $users->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($users as $user){				 
				$name = '<a href="javascript:leadmanagmentController.getCourseWiseCountlead('.$user->id.')" title="View Course wise Lead"><i class="fa fa-list fa-fw" style="cursor: pointer;"></i></a>'.$user->name;
				$current =  date('d-m-Y');						
		    	$oneday=  date('d-m-Y', strtotime($current));	
				$onelead =Inquiry::whereDate('created_at','=',date_format(date_create($oneday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();
				$firstday=  date('d-m-Y', strtotime($current. ' - 1 day'));	
				$firstlead =Inquiry::whereDate('created_at','=',date_format(date_create($firstday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();
				$secongday=  date('d-m-Y', strtotime($current. ' - 2 day'));	
				$secondlead =Inquiry::whereDate('created_at','=',date_format(date_create($secongday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();
			
				$thirdday=  date('d-m-Y', strtotime($current. ' - 3 day'));	
				$thirdlead = Inquiry::whereDate('created_at','=',date_format(date_create($thirdday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();							
							
				$fourday=  date('d-m-Y', strtotime($current. ' - 4 day'));	
				$fourlead = Inquiry::whereDate('created_at','=',date_format(date_create($fourday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();
				
				
				$fiveday=  date('d-m-Y', strtotime($current. ' - 5 day'));	
				 $fivelead = Inquiry::whereDate('created_at','=',date_format(date_create($fiveday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();
				
		    	$sixday=  date('d-m-Y', strtotime($current. ' - 6 day'));	
		    	$sixlead = Inquiry::whereDate('created_at','=',date_format(date_create($sixday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();	
			
			    $sevenday=  date('d-m-Y', strtotime($current. ' - 7 day'));	
				$sevenlead = Inquiry::whereDate('created_at','=',date_format(date_create($sevenday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();
			
			    $eightday=  date('d-m-Y', strtotime($current. ' - 8 day'));	
				$eightlead = Inquiry::whereDate('created_at','=',date_format(date_create($eightday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();
				
				$nineday=  date('d-m-Y', strtotime($current. ' - 9 day'));	
				$ninelead = Inquiry::whereDate('created_at','=',date_format(date_create($nineday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();
			 
				
					$data[] = [		 
						$name,	
						$onelead,	 
						$firstlead,	 			
						$secondlead,
						$thirdlead,	 			
						$fourlead,
						$fivelead,	 			
						$sixlead,
						$sevenlead,	 			
						$eightlead,	
						$ninelead,	 			
					 			
						 		 			
						 				 			
						 					  
						 
					];
					$returnLeads['recordCollection'][] = $user->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
    public function getCourseWiseCountlead(Request $request, $id)
	 {
	
		if($request->ajax()){
			
		$current =  date('d-m-Y');
		$html = '  
		<table id="datatable-course-lead" class="table table-striped table-bordered">
		<thead>
		<tr>					 

		<th>Course</th>
		<th>'.date('M d', strtotime($current)).'</th>
		<th>'.date('M d', strtotime($current. ' - 1 day')).'</th>
		<th>'.date('M d', strtotime($current. ' - 2 day')).'</th>
		<th>'.date('M d', strtotime($current. ' - 3 day')).'</th>
		<th>'.date('M d', strtotime($current. ' - 4 day')).'</th>
		<th>'.date('M d', strtotime($current. ' - 5 day')).'</th>
		<th>'.date('M d', strtotime($current. ' - 6 day')).'</th>
		<th>'.date('M d', strtotime($current. ' - 7 day')).'</th>
		<th>'.date('M d', strtotime($current. ' -8 day')).'</th>
		<th>'.date('M d', strtotime($current. ' - 9 day')).'</th>
	 

		</tr>

		</thead>

		<tbody>'; 

		$html .='</tbody></table>';
		return response()->json(['status'=>1,'html'=>$html],200);
	 }
							
}
	 public function getCourseLead(Request $request, $id)
	 {
	
		if($request->ajax()){
		
		$courselist = CromaLead::where('assigned_to',$id);

		$courselist= $courselist->groupby('course');		
		$courselist = $courselist->paginate($request->input('length'));
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $courselist->total();
			$returnLeads['recordsFiltered'] = $courselist->total();
			foreach($courselist as $course){
				$current =  date('d-m-Y');	
				$dayone=  date('d-m-Y', strtotime($current));	
				$leadone = Inquiry::whereDate('created_at','=',date_format(date_create($dayone),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();
				$dayfirst=  date('d-m-Y', strtotime($current. ' - 1 day'));	
				$leadfirst = Inquiry::whereDate('created_at','=',date_format(date_create($dayfirst),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();
		
				$daysecond=  date('d-m-Y', strtotime($current. ' - 2 day'));	
				$leadsecond = Inquiry::whereDate('created_at','=',date_format(date_create($daysecond),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();	
		
				$daythird=  date('d-m-Y', strtotime($current. ' - 3 day'));	
				$leadthird = Inquiry::whereDate('created_at','=',date_format(date_create($daythird),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();	
		
		
				$dayfour=  date('d-m-Y', strtotime($current. ' - 4 day'));	
				$leadfour = Inquiry::whereDate('created_at','=',date_format(date_create($dayfour),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();
		
				$dayfive=  date('d-m-Y', strtotime($current. ' - 5 day'));	
				$leadfive = Inquiry::whereDate('created_at','=',date_format(date_create($dayfive),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();	
		
				$daysix=  date('d-m-Y', strtotime($current. ' - 6 day'));	
				$leadsix = Inquiry::whereDate('created_at','=',date_format(date_create($daysix),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();	
		
				$dayseven=  date('d-m-Y', strtotime($current. ' - 7 day'));	
				$leadseven = Inquiry::whereDate('created_at','=',date_format(date_create($dayseven),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();	
		
				$dayeight=  date('d-m-Y', strtotime($current. ' - 8 day'));	
				$leadeight = Inquiry::whereDate('created_at','=',date_format(date_create($dayeight),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();							  
		 
				$daynine=  date('d-m-Y', strtotime($current. ' - 9 day'));	
				$leadnine = Inquiry::whereDate('created_at','=',date_format(date_create($daynine),'Y-m-d'))->where('course',$course->course)->where('assigned_to',$course->assigned_to)->get()->count();		
		
			 
		
				$data[] = [
					substr($course->course,0,16),
					$leadone,
					$leadfirst,
					$leadsecond,
					$leadthird,
					$leadfour,
					$leadfive,
					$leadsix,
					$leadseven,
					$leadeight,
					$leadnine,
					 
				];
			}
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
		 
		 
		 
		  
 
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
			
			
			
			
		$assigncourse = Courseassignment::get();
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
			
			
			$edit_data = Courseassignment::find($id);
		
			$courses = Course::select('id','course')->get();		
			$users = User::select('id','name')->get();
			$destinationCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->assigncourse)){
				$assigncourseid = unserialize($edit_data->assigncourse);
				foreach($courses as $course){
					if(in_array($course->id,$assigncourseid)){
						$destinationCounsellors .= "<option value=\"$course->id\" selected>$course->course</option>";
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
		
			return view('cm_leadsmangment.update_course_assignment',['courses'=>$courses,'sourceCourses'=>$sourceCourses,'sourceCounsellors'=>$sourceCounsellors,'destinationCounsellors'=>$destinationCounsellors,'edit_data'=>$edit_data,'users'=>$users]);
		}else{
			return "Unh Cheatin`";
		}
      
    }

    
	
	
	public function editsavecourse(Request $request, $id){
		

		
		if($request->user()->current_user_can('super_admin')){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required|unique:'.Session::get('company_id').'_courseassignment,counsellors,'.$id,
				'assigncourse'=>'required',
			]);
			if($validator->fails()){
				return redirect('lead/editassigncourse/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$assigncourse = Courseassignment::find($id);
			$assigncourse->counsellors = ucwords($request->input('counsellors'));
			if($request->has('assigncourse')){
				$assigncourse->assigncourse = serialize($request->input('assigncourse'));
			}
			
	
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
	 
		if($request->user()->current_user_can('super_admin') ){
			
			
			
			
		$assigncourse = Courseassignment::get();
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
			
			
		
			
			
			$edit_data = Courseassignment::find($id);
		
			$courses = Course::select('id','name')->get();		
			$users = User::select('id','name')->get();
			$destinationCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->duplicatecourse)){
				$assigncourseid = unserialize($edit_data->duplicatecourse);
				foreach($courses as $course){
					if(in_array($course->id,$assigncourseid)){
						$destinationCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					}else{
					$destinationCounsellors .= "<option value=\"$course->id\">$course->name</option>";

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
	
			 
			return view('cm_leadsmangment.update_duplicate_course_assignment',['courses'=>$courses,'sourceCourses'=>$sourceCourses,'sourceCounsellors'=>$sourceCounsellors,'destinationCounsellors'=>$destinationCounsellors,'edit_data'=>$edit_data,'users'=>$users]);
		}else{
			return "Unh Cheatin`";
		}
      
    }

    
	
	
	public function editsaveduplicatecourse(Request $request, $id){
		

		
		if($request->user()->current_user_can('super_admin')){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required|unique:'.Session::get('company_id').'_courseassignment,counsellors,'.$id,
				'duplicate_course'=>'required',
			]);
			if($validator->fails()){
				return redirect('lead/editduplicatecourse/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$assigncourse = Courseassignment::find($id);
			$assigncourse->counsellors = ucwords($request->input('counsellors'));
			if($request->has('duplicate_course')){
				$assigncourse->duplicatecourse = serialize($request->input('duplicate_course'));
			}
			
			//echo "<pre>";print_r($assigncourse);die;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function duplicatecourseindex(Request $request)
    {   
	
		$sources = Source::all();
		 
			$courses = Course::select('id','name')->get();	
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

		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_leadsmangment.duplicate-course-assignment',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users,'assigncourse'=>$assigncourse]);
    } 
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leadassignmentindex(Request $request)
    {   
	
		$sources = Source::all();
		$courses = Course::all();
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
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_leadsmangment.lead-assignment',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    } 
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notleadassignmentindex(Request $request)
    {   
		
		$sources = Source::all();
		$courses = Course::all();
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
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_leadsmangment.not-lead-assignment',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    } 
	
	
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function courseAssignmentSave(Request $request)
    { 
	

        if($request->ajax()){
			
			
			 if($request->user()->current_user_can('super_admin') ){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required|unique:'.Session::get('company_id').'_courseassignment',
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
			
			$assigncourse = new Courseassignment;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function duplicatecourseassignment(Request $request)
    { 
	

        if($request->ajax()){
			
			
			 if($request->user()->current_user_can('super_admin') ){
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

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function getLeadPagination(Request $request)
	{
		   
		if($request->ajax()){			 
		$inquires = Inquiry::orderBy('id','DESC');		 
		if($request->input('search.value')!==''){
				$inquires = $inquires->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('sub_category','LIKE','%'.$request->input('search.value').'%')	
				    	    ->orWhere('form','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('course','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('from_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.source')!==''){
				$inquires = $inquires->where('source_id',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$inquires = $inquires->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$inquires = $inquires->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$inquires = $inquires->where('course_id',$request->input('search.course'));
			}
			
			
			if($request->input('search.user')!==''){
				$inquires = $inquires->where('assigned_to',$request->input('search.user'));
			}
			$inquires = $inquires->where('deleted',0);
			$inquires = $inquires->where('duplicate',0);
			$inquires = $inquires->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $inquires->total();
			$returnLeads['recordsFiltered'] = $inquires->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($inquires as $inquiry){				 
				 
				 if($inquiry->course){
				     $coursename = '<span title="'.$inquiry->course.'">'.substr($inquiry->course,0,25).'</span>';
				     
				 }else{
				      $coursename ="";
				     
				 }
				 
				 
				  if($inquiry->sub_category){
				     $sub_category = '<span title="'.$inquiry->sub_category.'">'.substr($inquiry->sub_category,0,25).'</span>';
				     
				 }else{
				      $sub_category ="";
				     
				 }
				 if($inquiry->mobile){
				     
		     
		    		$mobile= '+'.$inquiry->code.'-<span contenteditable>'.$inquiry->mobile.'</span>';
		    	
				 }
				  if(!empty($inquiry->assigned_to)){
					$username =  User::findOrFail($inquiry->assigned_to)->name;
				 }else{
					$username=""; 
				 }
				 if($inquiry->from_name){
				     
				    $from_name='('.$inquiry->from_name.')';
				 }else{
				     $from_name="";
				     
				 }
				 
				 //for source not show 
			    if(Auth::user()->current_user_can('user')  ||  Auth::user()->current_user_can('manager')){
    			     $sub_category="";  
    			}
				 
					$data[] = [		 
		 		 "<th><input type='checkbox' class='check-box' value='$inquiry->id'></th>",
						date('d-m-Y H:i:s',strtotime($inquiry->created_at)),						 		 			
						$inquiry->name,				 			
						$mobile,				 			
					//	$inquiry->email,
						$coursename,
						$sub_category.''.$from_name,							
				    	$username,	 					
						 	 			
						 		 			
						 				 			
						 					  
						 
					];
					$returnLeads['recordCollection'][] = $inquiry->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	
	public function getDeletedLeadPagination(Request $request)
	{
		   
		if($request->ajax()){			 
		$inquires = Inquiry::orderBy('id','DESC');		 
		if($request->input('search.value')!==''){
				$inquires = $inquires->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('sub_category','LIKE','%'.$request->input('search.value').'%')	
				    	    ->orWhere('form','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('course','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('from_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.source')!==''){
				$inquires = $inquires->where('source_id',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$inquires = $inquires->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$inquires = $inquires->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$inquires = $inquires->where('course_id',$request->input('search.course'));
			}
			
			$inquires = $inquires->where('deleted',1);
			$inquires = $inquires->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $inquires->total();
			$returnLeads['recordsFiltered'] = $inquires->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($inquires as $inquiry){				 
				 
				 if($inquiry->course){
				     $coursename = '<span title="'.$inquiry->course.'">'.substr($inquiry->course,0,25).'</span>';
				     
				 }else{
				      $coursename ="";
				     
				 }
				 
				 
				  if($inquiry->sub_category){
				     $sub_category = '<span title="'.$inquiry->sub_category.'">'.substr($inquiry->sub_category,0,25).'</span>';
				     
				 }else{
				      $sub_category ="";
				     
				 }
				 if($inquiry->mobile){
				     
		     
		    		$mobile= '+'.$inquiry->code.'-<span contenteditable>'.$inquiry->mobile.'</span>';
		    	
				 }
				  if(!empty($inquiry->assigned_to)){
					$username =  User::findOrFail($inquiry->assigned_to)->name;
				 }else{
					$username=""; 
				 }
				  if($inquiry->from_name){
				     
				    $from_name='('.$inquiry->from_name.')';
				 }else{
				     $from_name="";
				     
				 }
				 
					$data[] = [		 
		 		 "<th><input type='checkbox' class='check-box' value='$inquiry->id'></th>",
						date('d-m-Y H:i:s',strtotime($inquiry->created_at)),						 		 			
						$inquiry->name,				 			
						$mobile,
						$coursename,
						$sub_category.''.$from_name,							
				    	$username,	 					
						 	 			
						 		 			
						 				 			
						 					  
						 
					];
					$returnLeads['recordCollection'][] = $inquiry->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	
	
	
	
	
	
	
	public function getDuplicateLeadPagination(Request $request)
	{
		   
		if($request->ajax()){			 
		$inquires = Inquiry::orderBy('id','DESC');		 
		if($request->input('search.value')!==''){
				$inquires = $inquires->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('sub_category','LIKE','%'.$request->input('search.value').'%')	
				    	    ->orWhere('form','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('course','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('from_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.source')!==''){
				$inquires = $inquires->where('source_id',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$inquires = $inquires->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$inquires = $inquires->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$inquires = $inquires->where('course_id',$request->input('search.course'));
			} 
			
			if($request->input('search.user')!==''){
				$inquires = $inquires->where('assigned_to',$request->input('search.user'));
			}
			
		//	$inquires = $inquires->select('.*', DB::raw('COUNT(*) as `count`'));
	
		//	$inquires = $inquires->groupBy('mobile','course')->having('COUNT(*)>1');
		//	$inquires = $inquires->groupBy('mobile','course')->havingRaw('COUNT(id) > 0');
			$inquires = $inquires->where('assigned_to',0);
			$inquires = $inquires->where('duplicate',1);
			$inquires = $inquires->where('deleted',0);
		//	$inquires = $inquires->orderBy('id','ASC');
			$inquires = $inquires->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $inquires->total();
			$returnLeads['recordsFiltered'] = $inquires->total();
			$returnLeads['recordCollection'] = [];
 //echo "<pre>";print_r($request->input('search'));
			foreach($inquires as $inquiry){				 
				 
				 if($inquiry->course){
				     $coursename = '<span title="'.$inquiry->course.'">'.substr($inquiry->course,0,25).'</span>';
				     
				 }else{
				      $coursename ="";
				     
				 }
				 
				 
				  if($inquiry->sub_category){
				     $sub_category = '<span title="'.$inquiry->sub_category.'">'.substr($inquiry->sub_category,0,25).'</span>';
				     
				 }else{
				      $sub_category ="";
				     
				 }
				 
				 
				 //for source not show 
			    if(Auth::user()->current_user_can('user')  ||  Auth::user()->current_user_can('manager')){
    			     $sub_category="";  
    			}
    			
				 if($inquiry->mobile){
		    		$mobile= '+'.$inquiry->code.'-<span contenteditable>'.$inquiry->mobile.'</span>';
				 }else{
				     	$mobile="";
				     
				 }
				  if(!empty($inquiry->assigned_to)){
					$username =  User::findOrFail($inquiry->assigned_to)->name;
				 }else{
					$username=""; 
				 }
				 
				  if($inquiry->from_name){
				     
				    $from_name='('.$inquiry->from_name.')';
				 }else{
				     $from_name="";
				     
				 }
				 
				 	 $action ='<a href="javascript:leadmanagmentController.leadAssignForm('.$inquiry->id.')" title="Lead Assign"><i class="fa fa-send" aria-hidden="true"></i></a>';
					$data[] = [		 
		 		 "<th><input type='checkbox' class='check-box' value='$inquiry->id'></th>",
						date('d-m-Y H:i:s',strtotime($inquiry->created_at)),						 		 			
						$inquiry->name,				 			
						$mobile,
						$coursename,
						$sub_category.''.$from_name,							
				    	$username,
				    	$action
						 	 			
						 		 			
						 				 			
						 					  
						 
					];
					$returnLeads['recordCollection'][] = $inquiry->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	
	
	
	
	
	
	
	
	public function getLeadAssignmentPagination(Request $request)
	{
		   
		if($request->ajax()){			 
		$inquires = Inquiry::orderBy('id','DESC');		 
		if($request->input('search.value')!==''){
				$inquires = $inquires->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')	
					        ->orWhere('sub_category','LIKE','%'.$request->input('search.value').'%')	
				    	    ->orWhere('form','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('course','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('from_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.source')!==''){
				$inquires = $inquires->where('source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$inquires = $inquires->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$inquires = $inquires->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$inquires = $inquires->where('course_id',$request->input('search.course'));
			}
			if($request->input('search.user')!==''){
				$inquires = $inquires->where('assigned_to',$request->input('search.user'));
			}
			$inquires = $inquires->where('deleted',0);
			$inquires = $inquires->where('assigned_to','!=','0');
			$inquires = $inquires->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $inquires->total();
			$returnLeads['recordsFiltered'] = $inquires->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($inquires as $inquiry){				 
				 
				 if($inquiry->course){
				     $coursename = '<span title="'.$inquiry->course.'">'.substr($inquiry->course,0,25).'</span>';
				     
				 }else{
				      $coursename ="";
				     
				 }
				 
				 
				  if($inquiry->sub_category){
				     $sub_category = '<span title="'.$inquiry->sub_category.'">'.substr($inquiry->sub_category,0,25).'</span>';
				     
				 }else{
				      $sub_category ="";
				     
				 }
				 
				 
				 //for source not show 
			    if(Auth::user()->current_user_can('user')  ||  Auth::user()->current_user_can('manager')){
    			     $sub_category="";  
    			}
				 
				 if($inquiry->mobile){
		    	$mobile= '+'.$inquiry->code.'-<span contenteditable>'.$inquiry->mobile.'</span>';
				 }else{
				     	$mobile="";
				     
				 }
				 if(!empty($inquiry->assigned_to)){
					$username =  User::findOrFail($inquiry->assigned_to)->name;
				 }else{
					$username=""; 
				 }
				 
				 if($inquiry->from_name){
				     
				    $from_name='('.$inquiry->from_name.')';
				 }else{
				     $from_name="";
				     
				 }
					$data[] = [		 
		 		 "<th><input type='checkbox' class='check-box' value='$inquiry->id'></th>",
						date('d-m-Y',strtotime($inquiry->created_at)),						 		 			
						$inquiry->name,				 			
						$mobile,				 			
				 		$coursename,
						$sub_category.''.$from_name,							
						$username,	 			
						 			
						 		 			
						 				 			
						 					  
						 
					];
					$returnLeads['recordCollection'][] = $inquiry->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	
	public function getNotLeadAssignmentPagination(Request $request)
	{
		   
		if($request->ajax()){			 
		$inquires = Inquiry::orderBy('id','DESC');		 
		if($request->input('search.value')!==''){
				$inquires = $inquires->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')	
					 ->orWhere('sub_category','LIKE','%'.$request->input('search.value').'%')	
				    	    ->orWhere('form','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('course','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('from_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.source')!==''){
				$inquires = $inquires->where('source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$inquires = $inquires->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$inquires = $inquires->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$inquires = $inquires->where('course_id',$request->input('search.course'));
			}
			
			if($request->input('search.user')!==''){
				$inquires = $inquires->where('assigned_to',$request->input('search.user'));
			}
			$inquires = $inquires->where('deleted',0);
			$inquires = $inquires->where('assigned_to','=','0');
			//$inquires = $inquires->where('reason','Bucket Full');
			$inquires = $inquires->where('duplicate','=','0');
			$inquires = $inquires->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $inquires->total();
			$returnLeads['recordsFiltered'] = $inquires->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($inquires as $inquiry){				 
				 
				 if($inquiry->course){
				     $coursename = '<span title="'.$inquiry->course.'" contenteditable>'.$inquiry->course.'</span>';
				     
				 }else{
				      $coursename ="";
				     
				 }
				 if($inquiry->form){
				     $form = '<span title="'.$inquiry->form.'" contenteditable>'.$inquiry->form.'</span>';
				     
				 }else{
				      $form ="";
				     
				 }
				 
				 
				  if($inquiry->sub_category){
				     $sub_category = '<span title="'.$inquiry->sub_category.'">'.substr($inquiry->sub_category,0,25).'</span>';
				     
				 }else{
				      $sub_category ="";
				     
				 }
				 
				 //for source not show 
			    if(Auth::user()->current_user_can('user')  ||  Auth::user()->current_user_can('manager')){
    			     $sub_category="";  
    			}
    			 //for source not show 
			    if(Auth::user()->current_user_can('user')  ||  Auth::user()->current_user_can('manager')){
    			     $form="";  
    			}
				 
				 if($inquiry->mobile){
				     
		    	$mobile= '+'.$inquiry->code.'-<span contenteditable>'.$inquiry->mobile.'</span>';
		    	
				 }
				 if(!empty($inquiry->assigned_to)){
					$username =  User::findOrFail($inquiry->assigned_to)->name;
				 }else{
					$username=""; 
				 }
				 
				 
				  if($inquiry->from_name){
				     
				    $from_name='('.$inquiry->from_name.')';
				 }else{
				     $from_name="";
				     
				 }
				 $action ='<a href="javascript:leadmanagmentController.leadAssignForm('.$inquiry->id.')" title="Lead Assign"><i class="fa fa-send" aria-hidden="true"></i></a>';
					$data[] = [		 
					"<th><input type='checkbox' class='check-box' value='$inquiry->id'></th>",
						date('d-m-Y H:i:s',strtotime($inquiry->created_at)),						 		 			
						$inquiry->name,				 			
						$mobile, 
						$coursename,
						$sub_category.''.$from_name,	
						$form,	
						$inquiry->reason,
				    	$action,  			
						 		 			
						 				 			
						 					  
						 
					];
					$returnLeads['recordCollection'][] = $inquiry->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leadAssignForm(Request $request, $id)
    {  
		if($request->ajax()){
			$lead = Inquiry::findOrFail($id);		 
			 
			$sources = Source::all();
			$sourceHtml = '';
			$sourceObj = null;
			if(count($sources)>0){
				foreach($sources as $source){
					if($source->id == $lead->source){
						$sourceHtml .= '<option value="'.$source->id.'" selected>'.$source->name.'</option>';
						$sourceObj = $source;
					}else{
						$sourceHtml .= '<option value="'.$source->id.'">'.$source->name.'</option>';
					}
				}
			}
			
			$courses = Course::all();			 
			$courseHtml = '';
			if(count($courses)>0){
				foreach($courses as $course){
					if($course->id == $lead->course_id){
						$courseHtml .= '<option value="'.$course->id.'" selected>'.$course->name.'</option>';
					}else{
						$courseHtml .= '<option value="'.$course->id.'">'.$course->name.'</option>';
					}
				}
			}
			$coursename =Course::where('id',$lead->course_id)->first();
			 
			
			
		 
			
			
			$users = User::all();			 
			$usersHtml = '';
			if(!empty($users)){
				foreach($users as $user){
					if($user->id == $lead->assigned_to){
						$usersHtml .= '<option value="'.$user->id.'" selected>'.$user->name.'</option>';
					}else{
						$usersHtml .= '<option value="'.$user->id.'">'.$user->name.'</option>';
					}
				}
			}
			
			$code =$lead->code;
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
			 
	 
			 
			
			 
			 
			$html = '<div class="row">
						<div class="x_content" style="padding:0">';
				 
					$html.= '<form class="form-label-left" onsubmit="return leadmanagmentController.storeLeadAssign('.$id.',this)">
								 <div class="form-group">
									<div class="col-md-3">
										<label for="name">Name<span class="required">:</span></label>										 
										<p class="form-control-static" style="display:inline">'.$lead->name.'</p>
									</div>
								</div>
							
								<div class="form-group">
									<div class="col-md-5">
									<label for="email">Email <span class="required">:</span></label>
										 
										<p class="form-control-static" style="display:inline">'.$lead->email.'</p>
									</div>
								</div>
							
								 	<div class="form-group">
									<div class="col-md-4">
										<label for="mobile">Mobile <span class="required">:</span></label>									 
										<p class="form-control-static" style="display:inline" contenteditable>'.$lead->code.'-'.$lead->mobile.'</p>
										<input type="hidden" name="mobile" value="'.$lead->mobile.'">
									</div>
								</div>
								
							 
								<div class="form-group">
									<div class="col-md-6">
										<label>Course <span class="required">*</span></label>
										<p class="form-control-static" style="display:inline">'.$lead->course.'</p>
										 
									</div>
								</div>
							 	<div class="form-group">
									<div class="col-md-6">
										<label>Lead Type <span class="required">*</span></label>
										<p class="form-control-static" style="display:inline">'.$type_lead.'</p>
										 
									</div>
								</div>
							 
								   
								
									<div class="form-group">
									<div class="col-md-4">
										<label>Technology <span class="required">*</span></label>
										<select class="select2_single form-control sms-control select2_course" name="course" tabindex="-1">
											<option value="">-- SELECT TECHNOLOGY --</option>
											'.$courseHtml.'
										</select>
									</div>
								</div>
							  	<div class="form-group">
									<div class="col-md-4">
										<label>Counsellor <span class="required">*</span></label>
										<select class="select2_assignuser form-control" name="counsellor" tabindex="-1">
											<option value="">-- Select counsellor --</option>
											'.$usersHtml.'
										</select>
									</div>
								</div>
							 
							 
								<div class="form-group">
									<div class="col-md-4">
										<label style="visibility:hidden">Submit</label>
										<button type="submit" class="btn btn-success btn-block">Submit</button>
									</div>
								</div>
							</form>';
				 
			$html.=	'</div>
					</div>';
			
			return response()->json(['status'=>1,'html'=>$html],200);
		}
    }
	
	
	
	
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeLeadAssign(Request $request, $id)
    {
		if($request->ajax()){

        $mobile= ltrim($request->input('mobile'), '0');	
        $mobile= trim($mobile);	
        $newmobile=  preg_replace('/\s+/', '', $mobile);
        $leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$request->input('course'))->orderBy('id','desc')->get();	
        if(!empty($leadcheck) && count($leadcheck)>0){				 
        foreach($leadcheck as $checkv){					  
        if($checkv->status !=4 && $checkv->deleted_at ==''){				  
        $var =1;	 			  
        }else{
        $var =0;
        }
        if($var==1){				  
        $check=1;											  
        break;
        }else{						  
        $check=0;	
        
        }
        }
        }else{
             $check=0; 
        }

        if(!empty($check) && $check>0){
        $validator = Validator::make($request->all(),[
        'counsellor'=>'required',
        // 'mobile'=>'required|unique:croma_leads,mobile',	
        // 'course'=>'required|unique:croma_leads,course',
        
        'mobile'=>'required',	
        'course'=>'required',
        
        ]);
        }else{
        
        $validator = Validator::make($request->all(),[
        'counsellor'=>'required',
        'course'=>'required',
         'mobile'=>'required',
        ]);
        
        }


		 
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
		// echo "<pre>";print_r($_GET);die;
			
			$cromaLead = CromaLead::find($id);
			
		
			$lead =  new Lead;		
			$lead->croma_id = $cromaLead->id;
			$lead->name = $cromaLead->name;
			$lead->email = $cromaLead->email;
			$lead->code = trim($cromaLead->code);
            $mobile= ltrim($cromaLead->mobile, '0');	
	    	$mobile= trim($mobile);	
            $newmobile=  preg_replace('/\s+/', '', $mobile);
            $lead->mobile =$newmobile;	
			$lead->source = $cromaLead->source_id;
			$lead->source_name = $cromaLead->source;
			$coursesname = Course::findOrFail($request->input('course'));	
			$lead->course = $coursesname->id;
			$lead->course_name = $coursesname->name;
			$lead->status = 1;
			$lead->status_name = "New Lead";			 
			$lead->created_by = $request->input('counsellor');
			$lead->type =1;
			 $cromaLead->assigned_to=$request->input('counsellor');
			 $cromaLead->save();
			 //	echo "<pre>";print_r($lead); die;
			if($lead->save()){
				$followUp = new LeadFollowUp;
				$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;				 
				$followUp->followby = $lead->created_by;				 
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->lead_id = $lead->id;
				 
				if($followUp->save()){
					 
					return response()->json(['status'=>1],200);
				}else{
					$lead->delete();
					return response()->json(['status'=>0,'errors'=>'Lead not added'],400);
				}
			}else{
				return response()->json(['status'=>0,'errors'=>'Lead not added'],400);
			}
			 
		}		
	}
	
	
	
    /**
     * Get leads excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
    public function getleadsreceivedexcel(Request $request)
    {  	 
	
		//if($request->ajax()){
			 
			$leads = Inquiry::orderBy('id','DESC');		

			 
			
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('form','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 

			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$leads = $leads->where('course_id',$request->input('search.course'));
			}
				$leads = $leads->where('deleted',0);
				$leads = $leads->where('duplicate',0);
				$leads = $leads->where('assigned_to','!=',0);
			//$leads = $leads->paginate($request->input('length'));
			$leads = $leads->get();
			  
			$returnLeads = [];
			$data = [];
			 
			$returnLeads['recordCollection'] = [];
			foreach($leads as $lead){
				 $mobilecode= $lead->code.'-'.$lead->mobile;
					$arr[] = [
					    "Created"=>(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						"Name"=>$lead->name,
						"Mobile"=>$mobilecode,
						"Email"=>$lead->email,
						"Technology"=>$lead->course,	
						"Page"=>$lead->form,
						"From"=>$lead->sub_category,
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('all_received_leads_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		 
    }
	
	
	
	
	
	
	
	
	
	/**
     * Select Delete.
     *
     * @param lead id(s) to send.
     */	
	public function selectReceivedDelete(Request $request){
		$ids = $request->input('ids');	 
 
		if(!empty($ids)){
		foreach($ids as $id){			
			$lead = Inquiry::findorFail($id);					 
			$lead->delete();			 
			
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Deleted successfully...'
			]
		],200);
	}else{
		
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Please select check box.'
			]
		],200);
		
	}
	
	}
	
	
    /**
     * Get leads excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
    public function getleadsreceivedDeleteexcel(Request $request)
    {  	 
	
 
			 
			$leads = Inquiry::orderBy('id','DESC');		

			 
			
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('form','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 

			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$leads = $leads->where('course_id',$request->input('search.course'));
			}
			
			if($request->input('search.user')!==''){
				$leads = $leads->where('assigned_to',$request->input('search.user'));
			}
			$leads = $leads->where('deleted',1);
			$leads = $leads->get();
			  
			$returnLeads = [];
			$data = [];
			 
			$returnLeads['recordCollection'] = [];
			foreach($leads as $lead){
				 $mobilecode= $lead->code.'-'.$lead->mobile;
					$arr[] = [
						"Created"=>(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						"Name"=>$lead->name,
						"Mobile"=>$mobilecode,
						"Email"=>$lead->email,
						"Technology"=>$lead->course,	
						"Page"=>$lead->form,
						"From"=>$lead->sub_category,
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('all_received_leads_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		 
    }
	
	
	
    /**
     * Get leads excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
    public function getleadsreceivedunassignexcel(Request $request)
    {  	 
	
 
			 
			$leads = Inquiry::orderBy('id','DESC');		

			 
			
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('form','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 

			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$leads = $leads->where('course_id',$request->input('search.course'));
			}
			
			if($request->input('search.user')!==''){
				$leads = $leads->where('assigned_to',$request->input('search.user'));
			}
	
			$leads = $leads->where('deleted',0);
			$leads = $leads->where('assigned_to','=','0');
			$leads = $leads->where('duplicate','=','0');
			$leads = $leads->get();
			  
			$returnLeads = [];
			$data = [];
			 
			$returnLeads['recordCollection'] = [];
			foreach($leads as $lead){
				 $mobilecode= $lead->code.'-'.$lead->mobile;
					$arr[] = [
						"Created"=>(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						"Name"=>$lead->name,
						"Mobile"=>$mobilecode,
						"Email"=>$lead->email,
						"Technology"=>$lead->course,	
						"Page"=>$lead->form,
						"From"=>$lead->sub_category,
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('all_unassign_leads_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		 
    }
	
	
	/**
     * Select Delete.
     *
     * @param lead id(s) to send.
     */	
	public function leaddelete(Request $request,$id){
	 
 
		if(!empty($id)){
		 		
			$lead = Inquiry::findorFail($id);					 
	 		 
			if($lead->delete()){
		 
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Deleted successfully...'
			]
		],200);
	}else{
		
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Please select check box.'
			]
		],200);
		
	}
	
	}else{
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Please select check.'
			]
		],200);
		
	}
	}
	
	
	/**
     * soft Delete.
     *
     * @param lead id(s) to send.
     */	
	public function selectReceivedSoftDelete(Request $request){
		$ids = $request->input('ids');	 
 
		if(!empty($ids)){
		foreach($ids as $id){			
			$lead = Inquiry::findorFail($id);					 
			$lead->deleted=1;			 
			$lead->deleted_by=$request->user()->id;			 
			$lead->save();
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Soft delete successfully...'
			]
		],200);
	}else{
		
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Please select check box.'
			]
		],200);
		
	}
	
	}
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allCounsllor(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
	
		$sources = Source::all();
		$courses = Course::all();
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
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
//	echo "<pre>";print_r($users);die;
        return view('cm_leadsmangment.all-counsllor',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    } 
	
	
	public function getPaginationCounsellor(Request $request)
	{
		   
		if($request->ajax()){			 
		$users = User::orderBy('name','ASC');		 
		if($request->input('search.value')!==''){
				$users = $users->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')					     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			$users = $users->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $users->total();
			$returnLeads['recordsFiltered'] = $users->total();
			$returnLeads['recordCollection'] = [];
 //echo "<pre>";print_r($users);die;
			foreach($users as $user){	
			if($user->status==1){
				$status ='<a href="javascript:leadmanagmentController.statususer('.$user->id.',0)" title="View Course wise Lead"><span style="color:green">Active</span></a>';
			}else{
				$status ='<a href="javascript:leadmanagmentController.statususer('.$user->id.',1)" title="View Course wise Lead"><span style="color:red">In-Active</span></a>';
			}
					$data[] = [		 
						$user->name,						 		
						  	 $status,					  
						 
					];
					$returnLeads['recordCollection'][] = $user->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	

		
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function statususer(Request $request, $id,$val)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
			try{
				$users = User::findorFail($id);
				$users->status = $val;
			//	echo "<pre>";print_r($assigncourse);die;
				if($users->save()){
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function duallead(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::all();
		$courses = Course::all();
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
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}



        return view('cm_leadsmangment.all-dual-lead',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }

		
	public function getDualLeadPagination(Request $request)
	{
		   
		if($request->ajax()){			 
		$inquires = Inquiry::orderBy('id','DESC');		 
		if($request->input('search.value')!==''){
				$inquires = $inquires->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('sub_category','LIKE','%'.$request->input('search.value').'%')	
				    	    ->orWhere('form','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('course','LIKE','%'.$request->input('search.value').'%')
				    	    ->orWhere('from_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.source')!==''){
				$inquires = $inquires->where('source_id',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$inquires = $inquires->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$inquires = $inquires->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$inquires = $inquires->where('course_id',$request->input('search.course'));
			}
			
			
			if($request->input('search.user')!==''){
				$inquires = $inquires->where('assigned_to',$request->input('search.user'));
			}
			$inquires = $inquires->where('duplicate',2);
			$inquires = $inquires->where('deleted',0);
			$inquires = $inquires->where('assigned_to','=','0');
			$inquires = $inquires->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $inquires->total();
			$returnLeads['recordsFiltered'] = $inquires->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($inquires as $inquiry){				 
				 
				 if($inquiry->course){
				     $coursename = '<span title="'.$inquiry->course.'">'.substr($inquiry->course,0,25).'</span>';
				     
				 }else{
				      $coursename ="";
				     
				 }
				 
				 
				  if($inquiry->sub_category){
				     $sub_category = '<span title="'.$inquiry->sub_category.'">'.substr($inquiry->sub_category,0,25).'</span>';
				     
				 }else{
				      $sub_category ="";
				     
				 }
				 
				 //for source not show 
			    if(Auth::user()->current_user_can('user')  ||  Auth::user()->current_user_can('manager')){
    			     $sub_category="";  
    			}
				 
				 if($inquiry->mobile){
				     
		     
		    		$mobile= '+'.$inquiry->code.'-<span contenteditable>'.$inquiry->mobile.'</span>';
		    	
				 }
				  if(!empty($inquiry->assigned_to)){
					$username =  User::findOrFail($inquiry->assigned_to)->name;
				 }else{
					$username=""; 
				 }
				 if($inquiry->from_name){
				     
				    $from_name='('.$inquiry->from_name.')';
				 }else{
				     $from_name="";
				     
				 }
				 
				 
				   $action ='<a href="javascript:leadmanagmentController.leadAssignForm('.$inquiry->id.')" title="Lead Assign"><i class="fa fa-send" aria-hidden="true"></i></a>';
				  
				  
				  $action .=' | <a href="javascript:leadmanagmentController.leaddelete('.$inquiry->id.')" title="Lead Assign"><i class="fa fa-trash" aria-hidden="true"></i></a>';
					$data[] = [		 
		 		 "<th><input type='checkbox' class='check-box' value='$inquiry->id'></th>",
						date('d-m-Y H:i:s',strtotime($inquiry->created_at)),						 		 			
						$inquiry->name,				 			
						$mobile,				 			
					//	$inquiry->email,
						$coursename,
						$sub_category.''.$from_name,							
				    	$inquiry->comment, 					
						$action,		
						 		 			
						 				 			
						 					  
						 
					];
					$returnLeads['recordCollection'][] = $inquiry->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	  /**
     * Get leads excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
   public function getCourseAssignExcel(Request $request)
    {  	 
	
		//if($request->ajax()){
		 
			$leads = Courseassignment::orderBy('id','DESC');
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('counsellors','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('counsellors','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leadcount','LIKE','%'.$request->input('search.value').'%')						  
						  ->orWhere('leadcount','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 
			
 
			 
			//$leads = $leads->paginate($request->input('length'));
			$leads = $leads->get();
			 // echo"<pre>";print_r($leads);die;
			$returnLeads = [];
			$data = [];
			 	$courses = Course::get();
			$returnLeads['recordCollection'] = [];
			foreach($leads as $lead){
					$usersname= User::where('id',$lead->counsellors)->first();
					// echo "<pre>";print_r($usersname);
					 
					
					
$domestic="";
$international="";
$coursenamelist=array();
$coursenameinter=array();
			if($lead->assign_dom_course !== NULL){
			$assigncourse = unserialize($lead->assign_dom_course);
			foreach($courses as $coursev){
			if(in_array($coursev->id,$assigncourse)){
			//$domestic .=$coursev->name;
			$domestic .=$coursev->name;
			array_push($coursenamelist,$coursev->name);
			} 
			}
			} 
			
			$coursenamelistnew =implode(', ',$coursenamelist);
		 
				if($lead->assign_int_course !== NULL){
				$assignIntcourse = unserialize($lead->assign_int_course);
				foreach($courses as $coursesv){
				if(in_array($coursesv->id,$assignIntcourse)){
			//	$international .= $coursesv->name;
				array_push($coursenameinter,$coursesv->name);
				} 
				}
				} 					
			 	
						$coursenamelistinter =implode(', ',$coursenameinter);
						
					$arr[] = [
						"Name"=>$usersname->name,	
						"Domestic"=>$coursenamelistnew,			
						"International"=>$coursenamelistinter,		
						
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('assign_course_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				
				$excel->sheet('Sheet 1', function($sheet) use($arr,$coursenamelistnew,$lead) {
					$sheet->fromArray($arr);
 
				});
			})->export('xls');
		 
    }
	
	
		
	 
	
	
}
