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
use App\AbsentCourseAssignment;
use App\Message;
use Session;
use Auth;
use Excel;
class AbsentAssignController extends Controller
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

        return view('cm_absent.all-absent-course',['courses'=>$courses,'search'=>$search,'users'=>$users,'sourceCourses'=>$sourceCourses,'assigncourse'=>$assigncourse,'courses'=>$courses,'sourceIntCourses'=>$sourceIntCourses]);
    } 
	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function absentassignview(Request $request)
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

        return view('cm_absent.absent-assign_view',['courses'=>$courses,'search'=>$search,'users'=>$users,'sourceCourses'=>$sourceCourses,'assigncourse'=>$assigncourse,'courses'=>$courses,'sourceIntCourses'=>$sourceIntCourses]);
    } 
	
	public function getpaginationAbsentAssignView(Request $request)
	{
		   
		if($request->ajax()){			 
		$absentassigns = AbsentCourseAssignment::orderBy('id','ASC');		 
		if($request->input('search.value')!==''){
				$absentassigns = $absentassigns->where(function($query) use($request){
					$query->orWhere('counsellors','LIKE','%'.$request->input('search.value').'%')					     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			//echo "<pre>";print_r($request->input('search'));die;
				if($request->input('search.counsellors')!==''){
					
					//echo $request->input('search.counsellors');die;
				$absentassigns = $absentassigns->where('counsellors',$request->input('search.counsellors'));
			}
			$absentassigns = $absentassigns->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $absentassigns->total();
			$returnLeads['recordsFiltered'] = $absentassigns->total();
			$returnLeads['recordCollection'] = [];
 //echo "<pre>";print_r($users);die;
 
		$courses = CromaCourse::get();
			foreach($absentassigns as $absent){	
					$usersname=User::where('id',$absent->counsellors)->first();					 
					if(!empty($usersname)){
					$username=  $usersname->name;
					}else{
					$username="";
					}
					
					$tousersname=User::where('id',$absent->tocounsellor)->first();					 
					if(!empty($tousersname)){
					$tousername=  $tousersname->name;
					}else{
					$tousername="";
					}
//echo "<pre>";print_r($absent->absent_assign_dom_course);
					if($absent->absent_assign_dom_course !== NULL){
					$assigncourse = unserialize($absent->absent_assign_dom_course);
					$absent_assign_dom_course = "";
					foreach($courses as $coursev){
					if(in_array($coursev->id,$assigncourse)){
					$absent_assign_dom_course .= "<span class=\"label label-default\">".$coursev->name."</span> ";
					}
					}
					} 
					
				if($absent->absent_assign_int_course !== NULL){
				$assignIntcourse = unserialize($absent->absent_assign_int_course);
				$absent_assign_int_course = "";
				foreach($courses as $coursesv){
				if(in_array($coursesv->id,$assignIntcourse)){
				$absent_assign_int_course .= "<span class=\"label label-default\">".$coursesv->name."</span> ";
				}
				}
				}
				$action='<a href="/absent/editabsentassigncourse/'.$absent->id.'"><i class="fa fa-refresh" aria-hidden="true"></i></a> | <a href="javascript:absentAssignController.absentassigncoursedelete('.$absent->id.')"><i class="fa fa-trash" aria-hidden="true"></i></a>';

					$data[] = [		 
						$username,						 		
						$tousername,						 		
						$absent_assign_dom_course,						 		
						$absent_assign_int_course,	
						$action,						
						  	 			  
						 
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
    public function getassigndomestinccourse(Request $request, $id)
    {  
		 
			try{
				$assigncourse = Courseassignment::where('counsellors',$id)->get(); 
				$assign_dom_course=array();
				$marg=[];
				if(!empty($assigncourse)){
				foreach($assigncourse as $assignc){		
				if($assignc->assign_dom_course !=NULL){
				$assign_dom_course = array_merge($assign_dom_course,unserialize($assignc->assign_dom_course));
				}
				}
				}	
				
				
				$absentassigncourse = AbsentCourseAssignment::where('counsellors',$id)->get(); 
				//echo "<pre>";print_r($absentassigncourse);die;
				$absent_assign_dom_course_list=array();
				$marg=[];
				if(!empty($absentassigncourse)){
				foreach($absentassigncourse as $absentassignc){		
				
				 
					if($absentassignc->absent_assign_dom_course	 !=NULL){
					$absent_assign_dom_course_list = array_merge($absent_assign_dom_course_list,unserialize($absentassignc->absent_assign_dom_course));
					}
				}
				}	
			
				 if($assign_dom_course){
					$newdomarray = array();
				foreach($assign_dom_course as $key=>$val){
				if(!in_array($val ,$absent_assign_dom_course_list)){
					array_push($newdomarray, $val);
				} 
				}
				}  
				//echo "<pre>";print_r($newarray);die;
		
			$coursesnotexit  = CromaCourse::whereIn('id',$newdomarray)->orderBy('name','asc')->get();	 
			$sourceCourses="";
			foreach($coursesnotexit as $courseNot){
			$sourceCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";
			}

			
 
//international coure
			
		$assign_int_course=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){		
		if($assignc->assign_int_course !=''){	 
		$assign_int_course = array_merge($assign_int_course,unserialize($assignc->assign_int_course));
		}
		}
		}	
			$absentassigncourse = AbsentCourseAssignment::where('counsellors',$id)->get(); 
			//echo "<pre>";print_r($absentassigncourse);die;
			$absent_assign_int_course_list=array();
			$marg=[];
			if(!empty($absentassigncourse)){
			foreach($absentassigncourse as $absentassignc){		


			if($absentassignc->absent_assign_int_course	 !=NULL){
			$absent_assign_int_course_list = array_merge($absent_assign_int_course_list,unserialize($absentassignc->absent_assign_int_course));
			}
			}
			}
			
			
			 if($assign_int_course){
					$newintsarray = array();
				foreach($assign_int_course as $keys=>$vals){
				if(!in_array($vals ,$absent_assign_int_course_list)){
					array_push($newintsarray, $vals);
				} 
				}
				} 

		$coursesIntnotexit  = CromaCourse::whereIn('id',$newintsarray)->orderBy('name','asc')->get();	 
	
		$sourceIntCourses="";
		foreach($coursesIntnotexit as $courseIntNot){
		$sourceIntCourses .= "<option value=\"$courseIntNot->id\">$courseIntNot->name</option>";
		}

		return response()->json(['status'=>1,'html'=>$sourceCourses,'sourceIntCourses'=>$sourceIntCourses],200);
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Sub Category not found'],200);
			}
		 
    }
	

 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAbsentAssign(Request $request)
    { 
	
	//echo "<pre>";print_r($_POST);die;
        if($request->ajax()){
			
			
			 if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required',
				'to_counsellor'=>'required',
				//'absent_assign_dom_course'=>'required',
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
			
			$assigncourse = new AbsentCourseAssignment;
			$assigncourse->counsellors = $request->input('counsellors');
			$assigncourse->tocounsellor = $request->input('to_counsellor');
			if($request->has('absent_assign_dom_course')){
				$assigncourse->absent_assign_dom_course = serialize($request->input('absent_assign_dom_course'));
			}
			
			if($request->has('absent_assign_int_course')){
				$assigncourse->absent_assign_int_course = serialize($request->input('absent_assign_int_course'));
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editabsentcourse(Request $request,$id)
    {
	 
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			
			
				$edit_data = AbsentCourseAssignment::where('id',$id)->first(); 	
				$courses = CromaCourse::select('id','name')->get();		
				$users = User::select('id','name')->get();
			
				$assigncourse = Courseassignment::where('counsellors',$edit_data->counsellors)->get(); 
				$assign_dom_course=array();
				$marg=[];
				if(!empty($assigncourse)){
				foreach($assigncourse as $assignc){		
				if($assignc->assign_dom_course !=NULL){
				$assign_dom_course = array_merge($assign_dom_course,unserialize($assignc->assign_dom_course));
				}
				}
				}				 
				$absentassigncourse = AbsentCourseAssignment::where('counsellors',$edit_data->counsellors)->get(); 
			 
				$absent_assign_dom_course_list=array();
				$marg=[];
				if(!empty($absentassigncourse)){
				foreach($absentassigncourse as $absentassignc){	
					if($absentassignc->absent_assign_dom_course	 !=NULL){
					$absent_assign_dom_course_list = array_merge($absent_assign_dom_course_list,unserialize($absentassignc->absent_assign_dom_course));
					}
				}
				}	
				 
				 if($assign_dom_course){
					$newdomarray = array();
				foreach($assign_dom_course as $key=>$val){
				if(!in_array($val ,$absent_assign_dom_course_list)){
					array_push($newdomarray, $val);
				} 
				}
				}  
			//	echo "<pre>";print_r($newdomarray);die;
		
			$coursesnotexit  = CromaCourse::whereIn('id',$newdomarray)->orderBy('name','asc')->get();	 
			$sourceCourses="";
			foreach($coursesnotexit as $courseNot){
			$sourceCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";
			}


		$userIntCourses=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $intassignc){

		if($intassignc->assign_int_course !=NULL){
		$userIntCourses = 	array_merge($userIntCourses,unserialize($intassignc->assign_int_course));
		}
		}
		}	
		
		$absenttoassigncourse = AbsentCourseAssignment::where('tocounsellor',$edit_data->tocounsellor)->get(); 

		$absent_assign_int_course_list=array();
		$marg=[];
		if(!empty($absenttoassigncourse)){
		foreach($absenttoassigncourse as $absenttoassignc){	
		if($absenttoassignc->absent_assign_int_course	 !=NULL){$absent_assign_int_course_list = array_merge($absent_assign_int_course_list,unserialize($absenttoassignc->absent_assign_int_course));
		}
		}
		}	
//echo "<pre>";print_r($userIntCourses);echo "sdf";print_r($absent_assign_int_course_list);	die;	
		
		if($userIntCourses){
			$newintarray = array();
			foreach($userIntCourses as $keys=>$vals){
			if(!in_array($vals ,$absent_assign_int_course_list)){
			array_push($newintarray, $vals);
			} 
			}
		} 


//echo "<pre>";print_r($newintarray);die;
		$coursesIntnotexit  = CromaCourse::whereIn('id',$newintarray)->orderBy('name','asc')->get();

		$sourceIntCourses="";
		foreach($coursesIntnotexit as $courseNot){
		$sourceIntCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";

		} 






		//echo "<pre>";print_r($sourceIntCourses);die;
			
			
		$assigncourse = AbsentCourseAssignment::get();
		 
		
		
		
		$edit_data = AbsentCourseAssignment::find($id);
			//echo "<pre>";print_r($edit_data);die;
			
			$destinationDomCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->absent_assign_dom_course)){
				$assigncourseid = unserialize($edit_data->absent_assign_dom_course);
				foreach($courses as $course){
					if(in_array($course->id,$assigncourseid)){
						$destinationDomCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					} 
				}
			} 
			
			
			
				
	 
			 
			//echo "<pre>";print_r($edit_data);die;
			 
			 
			$destinationIntCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->absent_assign_int_course)){
				$absentassigncourseintid = unserialize($edit_data->absent_assign_int_course);
				foreach($courses as $course){
					if(in_array($course->id,$absentassigncourseintid)){
						$destinationIntCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					} 
				}
			} 
			
		
			 
			return view('cm_absent.update_absent_course_assignment',['courses'=>$courses,'sourceCourses'=>$sourceCourses,'sourceCounsellors'=>$sourceCounsellors,'destinationDomCounsellors'=>$destinationDomCounsellors,'edit_data'=>$edit_data,'users'=>$users,'sourceIntCourses'=>$sourceIntCourses,'destinationIntCounsellors'=>$destinationIntCounsellors]);
		}else{
			return "Unh Cheatin`";
		}
       // return view('cm_courses.create_assign_course',['users'=>$users,'edit_data'=>$edit_data]);
    }

    public function editAbsentsavecourse(Request $request, $id){
		
	//	echo "<pre>";print_r($_POST);die;
		
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$validator = Validator::make($request->all(),[
				'counsellors'=>'required',
				'tocounsellor'=>'required',
				//'assign_dom_course'=>'required',
			]);
			if($validator->fails()){
				return redirect('absent/editabsentassigncourse/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$assigncourse = AbsentCourseAssignment::find($id);
			$assigncourse->counsellors = $request->input('counsellors');
			$assigncourse->tocounsellor = $request->input('tocounsellor');
			
			if($request->has('assign_dom_course')){
				$assigncourse->absent_assign_dom_course = serialize($request->input('assign_dom_course'));
			}
			
			if($request->has('assign_int_course')){
				$assigncourse->	absent_assign_int_course = serialize($request->input('assign_int_course'));
			} 
			
			
			
			
			//echo "<pre>";print_r($assigncourse);die;
			if($assigncourse->save()){				 
				$request->session()->flash('alert-success', 'Course successful updated !!');
				return redirect(url('/absent/absent-assign-course-view'));	
			}else{
				$request->session()->flash('alert-danger', 'Assign Course not updated !!');
				return redirect(url('/absent/editabsentassigncourse/'.$id));			
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
    public function absentassigncoursedelete(Request $request, $id)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
			try{
				$assigncourse = AbsentCourseAssignment::findorFail($id);
				
				//echo "<pre>";print_r($assigncourse);die;
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
	
	
	
	
	
	
}
