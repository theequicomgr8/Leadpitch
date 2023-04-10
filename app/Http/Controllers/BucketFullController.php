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
use App\BucketCourseAssignment;
use App\Message;
use Session;
use Auth;
use Excel;
class BucketFullController extends Controller
{
	
	
	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
	{  		
		$users = User::where('status',1)->orderBy('name','ASC')->get();		 
		$search = [];
		if($request->has('search')){
		$search = $request->input('search');
		}
		
		//echo "<pre>";print_r($users);die;
        return view('cm_bucket.all-bucket-course-assign',['search'=>$search,'users'=>$users]);
    } 
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addBucket(Request $request)
    {   
		 
		 
		 $users = User::where('status',1)->orderBy('name','ASC')->get();
		 
		 
		 
		$assigncourse = BucketCourseAssignment::get();		 
		$bucket_assign_dom_course=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){		
			if($assignc->bucket_assign_dom_course !=NULL){
		$bucket_assign_dom_course = array_merge($bucket_assign_dom_course,unserialize($assignc->bucket_assign_dom_course));
		}
		}
		}	
 
		$coursesnotexit  = CromaCourse::whereNotIn('id',$bucket_assign_dom_course)->orderBy('name','asc')->get();	 
		$sourceCourses="";
		foreach($coursesnotexit as $courseNot){
		$sourceCourses .= "<option value=\"$courseNot->id\">$courseNot->name</option>";
		}
	 
			//echo "<pre>";print_r($coursesnotexit); 
	
		 
		 	 
		$bucket_assign_int_course=array();
		$marg=[];
		if(!empty($assigncourse)){
		foreach($assigncourse as $assignc){		
		if($assignc->bucket_assign_int_course !=''){	 
		$bucket_assign_int_course = array_merge($bucket_assign_int_course,unserialize($assignc->bucket_assign_int_course));
		}
		}
		}	
		 
 
		$coursesIntnotexit  = CromaCourse::whereNotIn('id',$bucket_assign_int_course)->orderBy('name','asc')->get();	 
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

        return view('cm_bucket.add-bucket-course',['courses'=>$courses,'search'=>$search,'users'=>$users,'sourceCourses'=>$sourceCourses,'assigncourse'=>$assigncourse,'courses'=>$courses,'sourceIntCourses'=>$sourceIntCourses]);
    } 
	
	

 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeBucketAssign(Request $request)
    { 
	
	//echo "<pre>";print_r($_POST);die;
        if($request->ajax()){
			
			
			 if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$validator = Validator::make($request->all(),[
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
			
			$assigncourse = new BucketCourseAssignment;
			$assigncourse->counsellors = $request->input('counsellors');
			$assigncourse->tocounsellor = $request->input('to_counsellor');
			 
			if($request->has('bucket_assign_dom_course')){
				$assigncourse->bucket_assign_dom_course = serialize($request->input('bucket_assign_dom_course'));
			}
			
			if($request->has('bucket_assign_int_course')){
				$assigncourse->bucket_assign_int_course = serialize($request->input('bucket_assign_int_course'));
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

 
	
	
	
	
	
	public function getPaginationBucket(Request $request)
	{
		
		 
		   
		if($request->ajax()){	
		
		$bucketassigns = BucketCourseAssignment::orderBy('id','ASC');		 
		if($request->input('search.value')!==''){
				$bucketassigns = $bucketassigns->where(function($query) use($request){
					$query->orWhere('counsellors','LIKE','%'.$request->input('search.value').'%')				     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			//echo "<pre>";print_r($request->input('search'));die;
			if($request->input('search.counsellors')!==''){	 
			$bucketassigns = $bucketassigns->where('counsellors',$request->input('search.counsellors'));
			}
			$bucketassigns = $bucketassigns->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $bucketassigns->total();
			$returnLeads['recordsFiltered'] = $bucketassigns->total();
			$returnLeads['recordCollection'] = [];
 //echo "<pre>";print_r($users);die;
 
		$courses = CromaCourse::get();
			foreach($bucketassigns as $bucket){	
			
					$usersname=User::where('id',$bucket->counsellors)->first();					 
					if(!empty($usersname)){
					$username=  $usersname->name;
					}else{
					$username="";
					}
					
					
					$tousersname=User::where('id',$bucket->tocounsellor)->first();					 
					if(!empty($tousersname)){
					$tousername=  $tousersname->name;
					}else{
					$tousername="";
					}
					
					 
//echo "<pre>";print_r($bucket->absent_assign_dom_course);
					if($bucket->bucket_assign_dom_course !== NULL){
					$assigncourse = unserialize($bucket->bucket_assign_dom_course);
					$bucket_assign_dom_course = "";
					foreach($courses as $coursev){
					if(in_array($coursev->id,$assigncourse)){
					$bucket_assign_dom_course .= "<span class=\"label label-default\">".$coursev->name."</span> ";
					}
					}
					} 
					
				if($bucket->bucket_assign_int_course !== NULL){
				$assignIntcourse = unserialize($bucket->bucket_assign_int_course);
				$bucket_assign_int_course = "";
				foreach($courses as $coursesv){
				if(in_array($coursesv->id,$assignIntcourse)){
				$bucket_assign_int_course .= "<span class=\"label label-default\">".$coursesv->name."</span> ";
				}
				}
				}
				$action='<a href="/bucket/editbucketassigncourse/'.$bucket->id.'"><i class="fa fa-refresh" aria-hidden="true"></i></a> | <a href="javascript:bucketAssignController.bucketassigncoursedelete('.$bucket->id.')"><i class="fa fa-trash" aria-hidden="true"></i></a>';

					$data[] = [		 
						$username,						 				 		
						$tousername,						 				 		
						$bucket_assign_dom_course,						 		
						$bucket_assign_int_course,	
						$action,						
						  	 			  
						 
					];
					$returnLeads['recordCollection'][] = $bucket->id;				 
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
    public function editbucketcourse(Request $request,$id)
    {
	 
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			
			
			 
			
				$edit_data = BucketCourseAssignment::where('id',$id)->first(); 	
				$courses = CromaCourse::select('id','name')->get();		
				$users = User::where('status','1')->select('id','name')->orderBy('name','asc')->get();
			
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
				
				$bucketassigncourse = BucketCourseAssignment::where('counsellors',$edit_data->counsellors)->get(); 
			 
				$bucket_assign_dom_course_list=array();
				$marg=[];
				if(!empty($bucketassigncourse)){
				foreach($bucketassigncourse as $bucketassignc){	
					if($bucketassignc->bucket_assign_dom_course	!=NULL){
					$bucket_assign_dom_course_list = array_merge($bucket_assign_dom_course_list,unserialize($bucketassignc->bucket_assign_dom_course));
					}
				}
				}	
				 
				 if($assign_dom_course){
					$newdomarray = array();
				foreach($assign_dom_course as $key=>$val){
				if(!in_array($val ,$bucket_assign_dom_course_list)){
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
		
		$buckettoassigncourse = BucketCourseAssignment::where('tocounsellor',$edit_data->tocounsellor)->get(); 

		$bucket_assign_int_course_list=array();
		$marg=[];
		if(!empty($buckettoassigncourse)){
		foreach($buckettoassigncourse as $buckettoassignc){	
		if($buckettoassignc->bucket_assign_int_course	 !=NULL){
			$bucket_assign_int_course_list = array_merge($bucket_assign_int_course_list,unserialize($buckettoassignc->bucket_assign_int_course));
		}
		}
		}	
//echo "<pre>";print_r($userIntCourses);echo "sdf";print_r($absent_assign_int_course_list);	die;	
		
		if($userIntCourses){
			$newintarray = array();
			foreach($userIntCourses as $keys=>$vals){
			if(!in_array($vals ,$bucket_assign_int_course_list)){
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





 
		
		
	 
			$destinationDomCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->bucket_assign_dom_course)){
				$assigncourseid = unserialize($edit_data->bucket_assign_dom_course);
				foreach($courses as $course){
					if(in_array($course->id,$assigncourseid)){
						$destinationDomCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					} 
				}
			} 
			
			
			
				
	 
			 
			//echo "<pre>";print_r($edit_data);die;
			 
			 
			$destinationIntCounsellors = "";
			$sourceCounsellors ="";
			if(!is_null($edit_data->bucket_assign_int_course)){
				$absentassigncourseintid = unserialize($edit_data->bucket_assign_int_course);
				foreach($courses as $course){
					if(in_array($course->id,$absentassigncourseintid)){
						$destinationIntCounsellors .= "<option value=\"$course->id\" selected>$course->name</option>";
					} 
				}
			} 
			
			
			
			
			   
			 
			return view('cm_bucket.update_bucket_course_assignment',['courses'=>$courses,'sourceCourses'=>$sourceCourses,'sourceCounsellors'=>$sourceCounsellors,'destinationDomCounsellors'=>$destinationDomCounsellors,'edit_data'=>$edit_data,'users'=>$users,'sourceIntCourses'=>$sourceIntCourses,'destinationIntCounsellors'=>$destinationIntCounsellors]);
		}else{
			return "Unh Cheatin`";
		}
       // return view('cm_courses.create_assign_course',['users'=>$users,'edit_data'=>$edit_data]);
    }

    public function editbucketsavecourse(Request $request, $id){
		
	//	echo "<pre>";print_r($_POST); echo $request->input('counsellors'); die;
		
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$validator = Validator::make($request->all(),[				 
				 'counsellors'=>'required',
				 'tocounsellor'=>'required',
			]);
			if($validator->fails()){
				return redirect('bucket/editbucketassigncourse/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$assigncourse = BucketCourseAssignment::find($id);	
			$assigncourse->counsellors = $request->input('counsellors');
			$assigncourse->tocounsellor = $request->input('tocounsellor');
			//$request->input('counsellors');
			if($request->has('bucket_assign_dom_course')){				
				$assigncourse->bucket_assign_dom_course = serialize($request->input('bucket_assign_dom_course'));
			}
			
			if($request->has('bucket_assign_int_course')){
				$assigncourse->bucket_assign_int_course = serialize($request->input('bucket_assign_int_course'));
			} 
			
			
			
			
			//echo "<pre>";print_r($assigncourse);die;
			if($assigncourse->save()){				 
				$request->session()->flash('alert-success', 'Course successful updated !!');
				return redirect(url('/bucket/all-bucket-course'));	
			}else{
				$request->session()->flash('alert-danger', 'Assign Course not updated !!');
				return redirect(url('/bucket/editbucketssigncourse/'.$id));			
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
    public function bucketassigncoursedelete(Request $request, $id)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
			try{
				$assigncourse = BucketCourseAssignment::findorFail($id);
				
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
	
	
	 /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getbucketcourse(Request $request, $id)
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
				
				
				$bucketassigncourse = BucketCourseAssignment::where('counsellors',$id)->get(); 
				//echo "<pre>";print_r($bucketassigncourse);die;
				$bucket_assign_dom_course=array();
				$marg=[];
				if(!empty($bucketassigncourse)){
				foreach($bucketassigncourse as $bucketassignc){		
				
				 
					if($bucketassignc->bucket_assign_dom_course	 !=NULL){
					$bucket_assign_dom_course = array_merge($bucket_assign_dom_course,unserialize($bucketassignc->bucket_assign_dom_course));
					}
				}
				}	
			
				 if($assign_dom_course){
					$newdomarray = array();
				foreach($assign_dom_course as $key=>$val){
				if(!in_array($val ,$bucket_assign_dom_course)){
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
			 
			$bucket_assign_int_course_list=array();
			$marg=[];
			if(!empty($bucketassigncourse)){
			foreach($bucketassigncourse as $bucketassignc){		


			if($bucketassignc->bucket_assign_int_course	 !=NULL){
			$bucket_assign_int_course_list = array_merge($bucket_assign_int_course_list,unserialize($bucketassignc->bucket_assign_int_course));
			}
			}
			}
			
			
			 if($assign_int_course){
					$newintsarray = array();
				foreach($assign_int_course as $keys=>$vals){
				if(!in_array($vals ,$bucket_assign_int_course_list)){
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
				return response()->json(['status'=>0,'errors'=>'not found'],200);
			}
		 
    }
	
	
	
	
}
