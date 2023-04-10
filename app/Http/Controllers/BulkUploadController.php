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

class BulkUploadController extends Controller
{
    public function createBulkUpload(Request $request){
		 
		/* if(!$request->user()->current_user_can('super_admin') || !$request->user()->current_user_can('view_course')){
			return view('errors.unauthorised');
		} */		
		return view('cm_bulkupload.bulk_upload');
	}
	
	
	public function createBulkUploadDemo(Request $request){		 
		 		
		return view('cm_bulkupload.bulk_upload_demo');
	}
	
    public function storeBulkUpload(Request $request){
		 
		 
		$file = Input::file('upload_file');	
		$results = Excel::load($file)->get();		
	//	echo "<pre>";print_r($results);die;
		$arr = [];
		foreach($results as $result){
			
			/*$arrayToPass = [];
			foreach($result as $key=>$value){
				$arrayToPass[$key] = $value;
			} 
			
			*/
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
				
				$lead = new lead;
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
				$lead->created_by =  '18';
			 
			 
				if($lead->save()){
				$followUp = new LeadFollowUp;
				$followUp->status = ($result->status)?Status::where('name',$result->status)->first()->id:"";			 
				$followUp->remark = $result->remarks;
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->lead_id = $lead->id;
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
			 
				$request->session()->flash('alert-success','Lead upload successfully!!');
				  return redirect('bulkupload/leads');
			
			
		// response uploaded keywords
	/* 	$excel = \App::make('excel');
		Excel::create('Uploaded_KWDS', function($excel) use($arr) {
			$excel->sheet('Sheet1', function($sheet) use($arr) {
				$sheet->fromArray($arr);
			});
		})->export('xls'); */
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





}
