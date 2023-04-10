<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Excel;
//Models
use App\Lead;
use App\Demo;
use App\Source;
use App\Course;
use App\Status;
use App\LeadFollowUp;
use App\DemoFollowUp;
use session;
use DB;
use App\User;
use App\Inquiry;

class FacbookLeadController extends Controller
{
    public function createBulkUpload(Request $request){
		 
		/* if(!$request->user()->current_user_can('super_admin') || !$request->user()->current_user_can('view_course')){
			return view('errors.unauthorised');
		} */		
		return view('cm_bulkupload.facbook_lead_upload');
	}
	
	
	public function createBulkUploadDemo(Request $request){		 
		 		
		return view('cm_bulkupload.bulk_upload_demo');
	}
	
    



		//excel upload of facbook
		public function storeBulkUpload(Request $request){
		  		 
		$file = Input::file('upload_file');	
		$results = Excel::load($file)->get();		
		$arr = [];
	 
		$notuploadary=[];
		foreach($results as $result){
		    $checkdata=DB::table('croma_leads')->where('name',$result->name)->where('mobile',$result->mobile)->whereNull('deleted_at')->first();
		    if(empty($checkdata))
		    {
		        $sources = Source::where('name',$result->source)->first();		 
    			if(!empty($sources)){
    				$sourse_id = ($result->source)?Source::where('name',$result->source)->first()->id:"";
    				$sourse_name = $result->source;
    			}else{	
    			     $sourse_id= 16;
					 $sourse_name = "FaceBook";
    			}
    			
				$coursename=    Course::where('name', 'like', '%' .$result->course . '%')->first();
				if(!empty($coursename)){

				$lead_course_id =$coursename->id;					
				$course_id= $lead_course_id;			
				$course_name= $coursename->name;	
				 
				}else{			 
				$course_id=0;			 
				$course_name =$result->course;
				}
    			  
    			
    			$statuss = Status::where('name',$result->status)->first();		 
    			if(!empty($statuss)){
    				$status_id = ($result->status)?Status::where('name',$result->status)->first()->id:"";
    				$status_name = $result->status;
    			}else{	
    			   $status_id = 1;
				   $status_name="New Lead";
    			}
     
    				$users=DB::table('croma_users')->where('name',$result->owner)->first();
    				if(!empty($users))
    				{
						$owner_id=$users->id;
    				}
    				else{
    					$owner_id=18;
    				}
    			 
    				
    				$getmobile=DB::table('croma_leads')->where('mobile',$result->mobile)->first();
    				if(empty($getmobile)){
    					$mobile=$result->mobile;
    				}else{
    					 
    					$mobile=$result->mobile;
    				}
    				
    				// $user_id=Auth::user()->id;
    				
    				date_default_timezone_set("Asia/Calcutta");   
                    $dat= date('Y-m-d H:i:s');
    
    				$lead = new lead;
    				$lead->name = $result->name?? '';
    				$lead->email = $result->email ?? '';
    				$lead->mobile = $mobile;//$result->mobile ?? '';
    				$lead->source = $sourse_id ?? 0;
    				$lead->source_name = $sourse_name ?? 'FaceBook';	
    				$lead->course = $course_id ?? 0;		 
    				$lead->course_name = $course_name ?? '';						 
    				 			
    				$lead->status= '1';//$status_id;	
    				$lead->status_name = 'New Lead';//$status_name ?? 'New Lead';
    				$lead->created_by =  $owner_id;
    				$lead->fb_lead =  1;
    				$lead->fbcourses=$result->course ?? '';
    				$lead->facbook_to_lead=1;
    				$lead->facebook_owner=$owner_id;
    				$lead->fb_lead_status=1; //add 8 feb
    				$lead->fb_created_at=$dat;
    				$lead->code='91';    			 		  
    				if($lead->save()){
    					 
    				$followUp = new LeadFollowUp;
    				$followUp->status = 1; 			 
    				$followUp->remark = $result->remarks ?? ''; //Interested
    				$followUp->expected_date_time = $dat;//date('Y-m-d H:i:s');
    				$followUp->lead_id = $lead->id;
    				$followUp->save();				
    					
    				}
    			
    					 
		    }
		    else{
		        $notuploadary[]=$result->mobile;
		         		        
		    }
		}
			    $notuploadary=implode(",", $notuploadary);
			    
				$request->session()->flash('alert-success','Lead upload successfully!!');
				  return redirect('bulkupload/facbook')->with('not_upload',$notuploadary);;
			
		}



















	public function storeBulkUploadDemo(Request $request){
		 
		 
		$file = Input::file('upload_file');	
		$results = Excel::load($file)->get();	
 		
		$arr = [];
		foreach($results as $result){
			$arrayToPass = [];
			foreach($result as $key=>$value){
				$arrayToPass[$key] = $value;
			}
		/*$validator = Validator::make($arrayToPass,[
				'name' => 'required',
				'email' => 'required',
				'course' => 'required',
				'status' => 'required',
				 
			]); */
			 
			$sources = Source::where('name',$result->source)->first();		 
			if(!empty($sources)){
				$sourse_id = ($result->source)?Source::where('name',$result->source)->first()->id:"";
				$sourse_name = $result->source;
			}else{	
			    if($result->source){
				$source = new source;
				$source->name = $result->source;
				if($source->save()){
				$sourse_id = ($result->source)?Source::where('name',$result->source)->first()->id:"";
				$sourse_name = $result->source;
				}
			    }
			}
			
			$courses = Course::where('name',$result->course)->first();		 
			if(!empty($courses)){
				$course_id = ($result->course)?Course::where('name',$result->course)->first()->id:"";
				$course_name = $result->course;
			}else{	
			    if($result->course){
    				$course = new course;
    				$course->name = $result->course;
    				$course->counsellors = serialize(array('18'));
    				if($course->save()){
    				$course_id = ($result->course)?Course::where('name',$result->course)->first()->id:"";
    				$course_name = $result->course;
    				}
			    }
			}
			
			$statuss = Status::where('name',$result->status)->first();		 
			if(!empty($statuss)){
				$status_id = ($result->status)?Status::where('name',$result->status)->first()->id:"";
				$status_name = $result->status;
			}else{	
			    if($result->status){
    				$status = new status;
    				$status->name = $result->status;
    				if($status->save()){
    				$status_id = ($result->status)?Status::where('name',$result->status)->first()->id:"";
    				$status_name = $result->status;
    				}
			    }
			}
			 
		//	if(!$validator->fails()){
				
				$lead = new Demo;
				$lead->name = $result->name;
				$lead->email = $result->email;
				$lead->mobile = $result->mobile;
				$lead->source = $sourse_id;
				$lead->source_name = $sourse_name;	
				$lead->course = $course_id;		 
				$lead->course_name = $course_name;						 
				$lead->sub_courses = $result->sub_course;				
				$lead->status= $status_id;	
				$lead->status_name = $status_name;
				$lead->created_by ='18';
			 
			 
				if($lead->save()){
				$followUp = new DemoFollowUp;
				$followUp->status = ($result->status)?Status::where('name',$result->status)->first()->id:"";			 
				$followUp->remark = $result->remarks;
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->demo_id = $lead->id;
				$followUp->save();				
					
				}
			
					/* $arr[] = [
						'name'=>$result->name,
						'email'=>$result->email,
						'mobile'=>$result->mobile,
						'source'=>$result->source,
						'course'=>$result->course,
						'sub_course'=>$result->sub_course,
						'status'=>$result->status,
						'remarks'=>$result->remarks,
					]; */
				}
			 
				$request->session()->flash('alert-success','Demo upload successfully!!');
				  return redirect('bulkupload/demos');
			
			
		// response uploaded keywords
	/* 	$excel = \App::make('excel');
		Excel::create('Uploaded_KWDS', function($excel) use($arr) {
			$excel->sheet('Sheet1', function($sheet) use($arr) {
				$sheet->fromArray($arr);
			});
		})->export('xls'); */
}




public function downloadExcelFormate(){
  //echo "sdfsd";die;
  $arr[] = [
				"Name"=>'Test',
				"Email"=>'testsks@gmail.com',
				"Mobile"=>'1234567875',
				"Source"=>'Croma',
				"Course"=>'PHP',
				'Owner'=> 'Suman',
			];			 
		 
	
	date_default_timezone_set('Asia/Kolkata'); 
	$excel = \App::make('excel');
	 
	Excel::create('add_facbook_leads_'.date('Y-m-d H:i a'), function($excel) use($arr) {
		$excel->sheet('Sheet 1', function($sheet) use($arr) {
			$sheet->fromArray($arr);
		});
	})->export('xls');
  
}



}
