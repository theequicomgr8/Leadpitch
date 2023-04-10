<?php

namespace App\Http\Controllers\Api;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;  
use Auth;
use Hash;
use Validator;
use DB;
use Session;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Input;
use Image; 
use App\city;
use App\FAQs;
use App\Blog;
use App\Helpers;
use App\ContentWriter;
use App\Category;
use App\Course;
use App\Inquiry;
use App\Lead;
class SulekhaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct(Request $request)
    // {
    //      $this->middleware('auth');
	   
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function sulekhadata(Request $request)
    {
        
        //return $request->all();
		//dd($request->all());
		//die();
		$code=91;
		$type_lead= "Domestic";
		
		dd($request);
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('Name');
		$inquiry->email= $request->input('email');
		$inquiry->code= 91;			
		$mobile= ltrim($request->input('number'), '0');	
		$mobile= trim($mobile);	
		$newmobile=  preg_replace('/\s+/', '', $mobile);
		$inquiry->mobile =$newmobile; 
	    
    	if(is_numeric($request->input('course'))){
		$coursename=    Course::findOrFail($request->input('course'));	
		if(!empty($coursename)){
		$course_name =$coursename->name;
		$lead_course_id =$coursename->id;		
		$inquiry->course_id= $lead_course_id;	
		$inquiry->course= $course_name;	
		}else{
		$inquiry->course_id= 0;;	
		$inquiry->course= "";	
		
		}
		}else{
		 
	 
		$coursename=    Course::where('name', 'like', '%' .$request->input('course') . '%')->first();
		if(!empty($coursename)){
			
		$lead_course_id =$coursename->id;					
		$inquiry->course_id= $lead_course_id;			
		$course_name= $coursename->name;	
		$inquiry->course= $course_name;	
		}else{
		$inquiry->course_id= 0;	
		$lead_course_id=0;
		$inquiry->course= $request->input('course');	
		$inquiry->reason= "Course-NF";
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		
		
		
		$inquiry->form= 'Sulekha';				 
		$inquiry->category="sulekha_enquiry";	
		$inquiry->sub_category=$request->input('source');	
		$inquiry->source_id= 3;
		$inquiry->source= "Sulekha";
         
        $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checkleadcourse) && $checkleadcourse>0){ 

			$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
				if(!empty($leadcheck) && count($leadcheck)>0){				 
				foreach($leadcheck as $checkv){					  
				if($checkv->status !=4 && $checkv->deleted_at ==''){				  
				$var =1;	  //// interested				  
				}else{
				$var =0;  // not interested
				}
				if($var==1){				  
				$check=1;											  
				break;
				}else{						  
				$check=0;	
				}
				}
				
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					//return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				//return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				
				
				}else{
				    
				 $leadchecks = Lead::where('mobile',trim($newmobile))->where('course','!=',$lead_course_id)->orderBy('id','desc')->get();	 
				 foreach($leadchecks as $checkv){					  
				if($checkv->status !=4 && $checkv->deleted_at ==''){				  
				$var =1;	  //// interested				  
				}else{
				$var =0;  // not interested
				}
				if($var==1){				  
				$check=1;											  
				break;
				}else{						  
				$check=0;	
				}
				}
				
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					//return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				//return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				    
				    
				    
			      
				}			 
			
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				$leadchecks = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
				if(!empty($leadchecks) && count($leadchecks)>0){				 
				foreach($leadchecks as $checkv){					  
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					//return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				//return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{  

	
		if($inquiry->save())
		{
			
			leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			 
	 
			 
			
		    //return redirect()->to('cromacampus.com/thanks');
			
		}
        // $request->request->add(['Name' => 'Suman']);
        // $request->request->add(['email' => 'suman@gmail.com']);
        //return json_encode($request->all());

    }
    
    
    }
    
} 
    
  
 
 
 
 
 

