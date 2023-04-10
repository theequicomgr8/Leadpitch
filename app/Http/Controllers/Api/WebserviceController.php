<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
use App\Inquiry;
use App\SiteFeedback;

use Excel;
use App\User;
use App\LeadsedgeAutoSend;
use App\LeadsedgeKeyword;
use App\LeadsedgeAutoSendFollowup;
use Auth;
use Session;

class WebserviceController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        
	   
    }

    public function getValue(Request $request){
    echo "test";die;
    }

   

	public function saveEnquiry(Request $request){
		//echo "<pre>";print_r($_POST);die;
		 
			 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			//	'phone' 	=> 'required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im',				
				'course'=>'required',				
				'from' 	=> 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
	 
	 
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		
		
		
		if(!empty($request->input('demo_date'))){		
				$inquiry->demo_date_time= $request->input('demo_date');			
		}
		if(!empty($request->input('experience'))){		
				$inquiry->experience= $request->input('experience');			
		}
		
		$inquiry->form= $request->input('from');				 
		$inquiry->category="send_enquiry";	
		$inquiry->sub_category=$request->input('from_title');	
		$inquiry->source_id= 7;
		$inquiry->source= "Website";
         
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{  

	
		if($inquiry->save())
		{
			
			leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			 
	 
			 
			if(!empty($request->input('email'))){
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud=$request->input('name') .": Thanks for your Enquiry";	
 		    Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.in', 'Croma Campus');
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			}
			
		    //return redirect()->to('cromacampus.com/thanks');
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
			 
			 
		} 
		 
		
	}
  
	public function saveCoursePopup(Request $request){ 
 
		
	 
			 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			//	'phone' 	=> 'required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im',				
				'course'=>'required',				
				'from' 	=> 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	 
			
			
		 
	 
	 
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
		$mobile= trim($mobile);	
		$newmobile=  preg_replace('/\s+/', '', $mobile);
		$inquiry->mobile =$newmobile;
		$inquiry->title_name=$request->title_name ?? '';
		$inquiry->category_id=$request->category_id ?? '';
	    $inquiry->title_heading=$request->title_heading ?? '';
	    
	    
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		
		if(!empty($request->input('demo_date'))){		
				$inquiry->demo_date_time= $request->input('demo_date');			
		}
		if(!empty($request->input('experience'))){		
				$inquiry->experience= $request->input('experience');			
		}
		
		$inquiry->form= $request->input('from');			 	 
		$inquiry->category="send_enquiry";	
		$inquiry->sub_category=$request->input('from_title');	
    	$inquiry->source_id= 7;
		$inquiry->source= "Website";
    	  
    	   $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
      
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        	
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
			$leadcheck = Lead::where('mobile',$newmobile)->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				}			 
				
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{ 
	
		if($inquiry->save())
		{
		    
		    leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
		    
			 //Cookie::queue("popupform", $course_name, "180");
			 
		 /*
			
			if(!empty($request->input('email'))){
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud=$request->input('name') .": Thanks for your Enquiry ";
 		    Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.in', 'Croma Campus');
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			
			}
		*/
			
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'not submitted'],200);
			
			
		}
		}	  
	 
		
	}
  
	
	public function saveEnquiryDownload(Request $request){
		
	 
			 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			//	'phone' 	=> 'required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im',				
				'course'=>'required',				
				'from' 	=> 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			 
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
			
		if(!empty($request->input('demo_date'))){		
				$inquiry->demo_date_time= $request->input('demo_date');			
		}
		if(!empty($request->input('experience'))){		
				$inquiry->experience= $request->input('experience');			
		}
		
		$inquiry->form= $request->input('from');	
		 		 
		$inquiry->category="send_enquiry";	
		$inquiry->sub_category=$request->input('from_title');	
    	$inquiry->source_id= 7;
		$inquiry->source= "Website";
       
         $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();	$lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
       if(!empty($checkleadcourse) && $checkleadcourse>0){ 
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{	
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				 
				}			 
				
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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

				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{ 
			    
	    // SK  start(master course download otp not work)
	    $request->session()->put('user.mobile', $request->input('phone'));
		$otp = mt_rand(100000, 999999);
		$request->session()->put('user.otp', $otp);
		$inquiry->otp=$otp;
		// SK end (master course download otp not work)
		
		if($inquiry->save())
		{
		    
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			 
			//comment by SK  start(master course download otp not work)
            // $request->session()->put('user.mobile', $request->input('phone'));
            // $otp = mt_rand(100000, 999999);
            // $request->session()->put('user.otp', $otp);
            //comment by SK  end
            
			$message = "{$otp} is Lead Portal Verification Code for {$request->session()->get('user.name')} CROMA CAMPUS";
			$tempid='1707161786775524106';
			$send = sendSMS($request->session()->get('user.mobile'),$message,$tempid);
 
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";		 
			$headers .= 'From: CromaCampus <info@leadpitch.in>';			 
		//	$to="brijesh.chauhan@cromacampus.com";
			$toemail= $request->input('email');
			$subjects="OTP Croma Campus ".$request->input('name');
			$message='<tr>
			<td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have received  Forgot Password. Here are the password details:</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Password:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$otp.'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr><td style="padding:18pt 0in 0in 0in;"></td></tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal" style="text-decoration:underline"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Note:</span><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> This is a system generated email. Please do not reply.</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="border:none;border-bottom:dashed #cccccc 1.0pt;padding:0in 0in 5.0pt 0in"></td>
			</tr> 			 
			</tbody>
			</table>
			</td>
			</tr>
			';
			 
       //->cc('brijesh.chauhan@cromacampus.com');
		  
    	 Mail::send('mails.send_password', ['msg'=>$message], function ($m) use ($message,$request,$subjects,$toemail) {
				$m->from('info@leadpitch.in', 'OTP Croma Campus ');
				$m->to($toemail, "")->subject($subjects);
				
				
			});
	
 
 
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
	    	$subject="Student Enquiry - ".$course_name;
	    	if(!empty($request->input('demo_date'))){
				$demo_date ='<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Date:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('demo_date').'</span><u></u><u></u></p>
			</td>
			</tr>';
				
			}else{
				
				$demo_date="";
			}
			
			if(!empty($request->input('experience'))){
				$experience ='<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Experience:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('experience').'</span><u></u><u></u></p>
			</td>
			</tr>';
				
			}else{
				
				$experience="";
			}
			
	    	
			$message=' <tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('name').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('email').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Mobile:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">'.'+'.$request->input('code').'-'.ltrim($request->input('phone'), '0').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Course:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$course_name.'</span><u></u><u></u></p>
			</td>
			</tr>
			'.$demo_date.'
			'.$experience.'
			 
			 
				<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Type of Lead:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$type_lead.' </span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">From Session:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('from_title').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">From Page:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('from').'</span><u></u><u></u></p>
			</td>
			</tr>';
			
			 $stdemail="";
			 $codemail="";
			 $coordinator="";
           if(!empty($request->input('email'))){
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.com>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud=$request->input('name') .": Thanks for your Enquiry ";
 		    Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.com', 'Croma Campus');
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			}
			
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not Submitted'],200);
			
			
		}
        } 
	 
		
	}
  
	
	
  public function saveEnquirySide(Request $request){
		
	 
			 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',
			//	'phone' 	=> 'required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im',	
		    	'course'=>'required',				
				// 'from' 	=> 'required',					
						
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
			 
	 
		$code =$request->input('code') ?? '91';
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->code= $request->input('code') ?? '91';			
		$mobile= ltrim($request->input('phone'), '0');	
		$mobile= trim($mobile);	
		$newmobile=  preg_replace('/\s+/', '', $mobile);
		$inquiry->mobile =$newmobile;
		$inquiry->utm =$request->input('utm') ?? '';
		
		$inquiry->title_name=$request->input('title_name') ?? '';
		$inquiry->category_id=$request->input('category_id') ?? '';
	    $inquiry->title_heading=$request->title_heading ?? '';
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
        if(!empty($request->input('message'))){
        $inquiry->comment= $request->input('message');	
        }else{
        $inquiry->comment="";
        
        }
        
		$inquiry->form= $request->input('from');			 	 
		$inquiry->category="send_enquiry_sidebar";	
    	 $inquiry->sub_category=$request->input('from_title');	
    	
    	if(!empty($request->input('utm')) || $request->input('utm')!='')
    	{
    	    $source="website(Google Ads)";
    	    $source_id=37;
    	}
    	else
    	{
    	    $source="Website";
    	    $source_id=7;
    	}
    	
    	
    	$inquiry->source_id= $source_id;//7;
		$inquiry->source= $source;//"Website";
    	   $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count(); 	
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
       if(!empty($checkleadcourse) && $checkleadcourse>0){ 
                
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 	
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
				
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				 
				}			 
				
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{ 
	    //dd($inquiry);
		if($inquiry->save())
		{
		    
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			 
		
			if(!empty($request->input('email'))){
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";		 
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud=$request->input('name') .": Thanks for your Enquiry ";	 
		    Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.in', $request->input('name'));
				$m->to($stdemail, "")->subject($subject_stud);				
			});  		
			}

			return response()->json(['status'=>1,'msg'=>'successfully submitted111'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'successfully submitted'],200);
			
			
		}
        }	  
	 
		
	}
  
  
  public function saveWebinars(Request $request){
		
	 
			 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			//	'phone' 	=> 'required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im',				
				'course'=>'required',				
				'from' 	=> 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
	 
	 
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		
		if(!empty($request->input('demo_date'))){		
				$inquiry->demo_date_time= $request->input('demo_date');			
		}
		if(!empty($request->input('experience'))){		
				$inquiry->experience= $request->input('experience');			
		}
		
		$inquiry->form= $request->input('from');
		 		 
		$inquiry->category="send_enquiry";	
		$inquiry->sub_category=$request->input('from_title');	
    	$inquiry->source_id= 7;
		$inquiry->source= "Website";
          $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
         
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();	 
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
            
			$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				}			 
			
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',$newmobile)->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{ 
	
		if($inquiry->save())
		{
			
			leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			  
			 
		 	if(!empty($request->input('email'))){
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud=$request->input('name') .": Thanks for your Enquiry";	
 		    Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.in', 'Croma Campus');
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			
			}
		  
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
			 
			 
		} 
		 
		
	}
  
  
  	public function saveCalling(Request $request){
		
	 

			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				//'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			 	'course'=>'required',
				'from' 	=> 'required',					
				'participant' => 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
		 
	 
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		$inquiry->call_prefered_time= $request->input('time_of_call');
		$inquiry->skype_id= $request->input('skype_id');
		$inquiry->whatsapp_no= $request->input('whatsapp_no');
		$inquiry->country= $request->input('country');
		 
		$inquiry->source_id= 7;
		$inquiry->source= "Website";		
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');	
		$inquiry->sub_category=$request->input('from_title');	
		$inquiry->source_id= 7;
		$inquiry->source= "Website";
		  $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();	$lastdate= date('Y-m-d', strtotime($currentdate. ' - 5 day'));		
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
					
						
				}			 
				
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{
            
		if($inquiry->save())
		{
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			
		  
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
        }
			  
		 
		
	}

 
	public function saveFranchise(Request $request){
	
	 
			 
			   $validator = Validator::make($request->all(),[				 
				'state' 	=> 'required',					
				'city' 	=> 'required',	
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',					
			
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
			
				
			 
			
        
        
		$inquiry = New Inquiry;
		$statename =State::where('state_id',$request->input('state'))->first()->state_name;
		$cityname =City::where('city_id',$request->input('city'))->first()->city_name;	 
		$inquiry->city= $statename.' '.$cityname;
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->mobile= ltrim($request->input('phone'), '0');	
		 			
		$inquiry->form= "For Franchise";
		$inquiry->category="send_franchise";	
		$inquiry->sub_category="franchise";	
    	$inquiry->source_id= 7;
		$inquiry->source= "Website";
		 
		if($inquiry->save())
		{
		    
		    $headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$subject="Frenchise Enquiry";
			$message=' <tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('name').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('email').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Mobile:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('phone').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">State:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$statename.'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">City:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$cityname.'</span><u></u><u></u></p>
			</td>
			</tr>
		 			
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">From:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">For Franchise</span><u></u><u></u></p>
			</td>
			</tr>
			 ';
			 $stdemail="";
			 $codemail="";
			 $coordinator="";
            
	     $to = array( "devendra1784@hotmail.com");
 		     Mail::send('mails.send_franchise_mail', ['msg'=>$message], function ($m) use ($message,$request,$subject,$stdemail,$codemail,$to) {
				$m->from('info@leadpitch.in',$request->input('name'));
				$m->to($to, "")->subject($subject);				
			});   
			 
		 
	
			if(!empty($inquiry->email)){
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: CromaCampus <enquiry@cromacampus.in>';
			$to=$inquiry->email;
			$subject="Croma Campus- ".$request->input('iq_course');
			} 
			
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
			  
	 
		
	}
  
	public function saveScholarship(Request $request){
	
	 
			 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',					
				'degree' 	=> 'required',					
				'college' 	=> 'required',					
				'course' 	=> 'required',					
				'g-recaptcha-response' 	=> 'required',		
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
			
		 
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";
		}else{
		$type_lead= "International";
		}
		 
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->mobile= ltrim($request->input('phone'), '0');			 
		$inquiry->scholarship_exam= $request->input('demo_date');		 
		$inquiry->college= $request->input('college');		 
		$inquiry->degree= $request->input('degree');		 
	 
		
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		} 
		 			
		$inquiry->form= "For Scholarship";
		$inquiry->category="send_Scholarship";
		$inquiry->sub_category="Scholarship";
		$inquiry->source_id= 7;
		$inquiry->source= "Website";
		if($inquiry->save())
		{
				 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
		 
			 
			
		if(!empty($request->input('email'))){
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud=$request->input('name') .": Thanks for your Enquiry";	
 		    Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.in', 'Croma Campus');
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			
			}
		
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
			  
		 
		
	}
  
 
 public function saveHireCroma(Request $request){
	
	// echo "<pre>";print_r($_POST);die;
			 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',					
				'company' => 'required',					
				'experience' 	=> 'required',					
				'no_of_candidate' 	=> 'required',					
				'technology' 	=> 'required',					
				'remarks' 	=> 'required',			
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
			
				
		 
		
		 
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->mobile= ltrim($request->input('phone'), '0');			 
		$inquiry->comp_name= $request->input('company');		 
		$inquiry->experience= $request->input('experience');		 
		$inquiry->participant= $request->input('no_of_candidate');		 
		$inquiry->course= $request->input('technology');	
	 
		if(!empty($request->input('remarks'))){
        $inquiry->comment= $request->input('remarks');	
        }else{
        $inquiry->comment="";
        }
		 		
		$inquiry->form= "For Hire For Cromacampus";
		$inquiry->category="send_hire_cromacampus";	
		$inquiry->sub_category="footer";	 
		$inquiry->source_id= 7;
		$inquiry->source= "Website";
		
	//	echo "<pre>";print_r($inquiry);die;
		if($inquiry->save())
		{
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$subject="Hire For Croma Campus Enquiry";
			$message=' <tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('name').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('email').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Mobile:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('phone').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Company Name :</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('company').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Experience:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('experience').'</span><u></u><u></u></p>
			</td>
			</tr>	
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Technology:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('technology').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">No Of Candidate:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('no_of_candidate').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Remarks:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('remarks').'</span><u></u><u></u></p>
			</td>
			</tr>			 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
		<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">From:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">For hire</span><u></u><u></u></p>
			</td>
			</tr>';
			
			 $stdemail="";
			 $codemail="";
			 $coordinator="";
	 
	//	  $to = array( "hr@cromacampus.com");
		/* 
		   $to = array( "brijesh.chauhan@cromacampus.com");
 		     Mail::send('mails.send_hire_cromacampus_mail', ['msg'=>$message], function ($m) use ($message,$request,$subject,$stdemail,$codemail) {
				$m->from('info@leadpitch.in',$request->input('name'));
				$m->to($to, "")->subject($subject);				
			});   
	*/

			/*if(!empty($request->input('email'))){
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud="Thanks for connecting with Croma Campus ".$request->input('technology');	 
		    Mail::send('mails.send_mail_student_hire_cromacampus', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.in', $request->input('name'));
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			 
			}
	*/
			
			
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
			  
	 
		
	}
  
 
	public function saveCorporateEnquiry(Request $request){
		
	 
			 
			   $validator = Validator::make($request->all(),[							
				'first_name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'last_name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:2|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',					
				'technology' 	=> 'required',					
				'company' 	=> 'required',
				'message' 	=> 'required',					
				'from' 	=> 'required',					
			//	'participant' 	=> 'required',		
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
			 
		 
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('first_name').' '.$request->input('last_name');
		$inquiry->email= $request->input('email');
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
		$mobile= trim($mobile);	
		$newmobile=  preg_replace('/\s+/', '', $mobile);
		$inquiry->mobile =$newmobile;
		
        if(is_numeric($request->input('technology'))){
        $coursename=    Course::findOrFail($request->input('technology'));	
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
        
        $coursename=    Course::where('name', 'like', '%' .$request->input('technology') . '%')->first();
        if(!empty($coursename)){
        
        $lead_course_id =$coursename->id;					
        $inquiry->course_id= $lead_course_id;			
        $course_name= $coursename->name;	
        $inquiry->course= $course_name;	
        }else{
        $inquiry->course_id= 0;	
        $lead_course_id=0;
        $inquiry->reason= "Course-NF";
        $inquiry->course= $request->input('technology');	
        $course_name =$request->input('technology');
        }
        
        }
		
	 
		$inquiry->comp_name= $request->input('company');
		$inquiry->participant= $request->input('participant');
		$inquiry->proj_name= $request->input('designation');
	 
		if(!empty($request->input('message'))){
        $inquiry->comment= $request->input('message');	
        }else{
        $inquiry->comment="";
        }
		$inquiry->form= $request->input('from');			 	 
		$inquiry->category="send_enquiry_corporate";			 
		$inquiry->sub_category=$request->input('from_title');	
		$inquiry->source_id= 7;
		$inquiry->source= "Website";
		 
		if($inquiry->save())
		{
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			  
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
	    	$subject="Corporate Enquiry - ".$request->input('technology');
			$message=' <tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('first_name').' '.$request->input('last_name').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('email').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Mobile:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">'.'+'.$request->input('code').'-'.ltrim($request->input('phone'), '0').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Course:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('technology').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Company:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('company').'</span><u></u><u></u></p>
			</td>
			</tr>
			
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Designation:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('designation').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Type of Lead:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$type_lead.'</span><u></u><u></u></p>
			</td>
			</tr>
			 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">From Page:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('from').'</span><u></u><u></u></p>
			</td>
			</tr><tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Message:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('message').'</span><u></u><u></u></p>
			</td>
			</tr>';
			
			 $stdemail="";
			 $codemail="";
			 $coordinator="";
       
          
      /*
        $to = array( "cromacampuswebsite@gmail.com");
 		     Mail::send('mails.send_lead_inquiry', ['msg'=>$message], function ($m) use ($message,$request,$subject,$stdemail,$codemail,$to) {
				$m->from('info@leadpitch.in', $request->input('name'));
				$m->to($to, "")->subject($subject);				
			});   
				
			  */
			
			if(!empty($request->input('email'))){				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";		 
			$headers .= 'From: info@leadpitch.in' . "\r\n";			 
			$stdemail=$request->input('email');
	    	$std_message=$request->input('first_name');
			$subject_stud=$request->input('name') .": Thanks for connecting with Croma Campus ".$request->input('course');	  		   
 		    Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.in', $request->input('name'));
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			
			 
			
			}
		
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
			  
		 
		
	}
	
	
 
	
 
 
 
	public function saveNewsLetter(Request $request){
		
		 
			 
			   $validator = Validator::make($request->all(),[					 				
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
        
		
		 
		 
		$inquiry = New Inquiry;	 	 
		$inquiry->email= $request->input('email');		 
		$inquiry->form= "News Letter";			 		 
		$inquiry->category="send_News Letter subscribe";			 
		$inquiry->sub_category="News Letter subscribe";		 
    	 $inquiry->source_id= 7;
		$inquiry->source= "Website";
		if($inquiry->save())
		{
			 
			
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
			  
	 
		
	}
	
	
 
	public function saveNotifications(Request $request){
		
	 
			 
			   $validator = Validator::make($request->all(),[					 				
				'phone' 	=> 'required|numeric',						
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	    
				
 
     
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;	 	 
		$inquiry->mobile= $request->input('phone');		 
		$inquiry->form= "FOR NOTIFICATIONS";			 	 
		$inquiry->category="send_NOTIFICATIONS";			 
		$inquiry->sub_category="NOTIFICATIONS";		 
		 $inquiry->source_id= 7;
		$inquiry->source= "Website";
		if($inquiry->save())
		{
			
			 
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
			  
	 
		
	}
	
	
 
	
  
  
 
	
	public function faceAnIssue(Request $request){
		
	 
			 
			   $validator = Validator::make($request->all(),[					 				
				'name' 	=> 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',						
				'remark' 	=> 'required|max:300',						
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	    
				 
		$inquiry = New Inquiry;	 	 
		$inquiry->name= $request->input('name');		 
		$inquiry->email= $request->input('email');		 
		$inquiry->phone= $request->input('phone');		 
		$inquiry->remark= $request->input('remark');		 
		$inquiry->form= "Face An Issue";		 
		  
		$inquiry->category="Face An Issue";			 
		$inquiry->sub_category="Face An Issue";		 
    	 $inquiry->source_id= 7;
		$inquiry->source= "Website";
		if($inquiry->save())
		{
              
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		 
			  
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
		}
		
 
	
	}
	
  
	
	
	
	
 /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
      
public function saveApplyJob(Request $request)
    {	 
		$title="";
		$keyword="";
		$description="";
	 
	 
			 
			 
			 if($request->input('type')=='Trainer'){
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',		
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	 					
				'keyskills' 	=> 'required',	
				'mode' 	=> 'required',
				'slot' 	=> 'required',
				'availability'	=> 'required',
				'experience'	=> 'required',
				'secondaryskills'	=> 'required',
				//'resume' => 'required|max:1000',
		    	'resume' => 'required|max:2048|mimes:doc,docs,pdf', 
						
		 		
			]); 
			 }else{
			     
			       $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',		
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	 					
				'keyskills' 	=> 'required',	
				'experience' 	=> 'required',
				'remark' 	=> 'required|max:250',
			//	'resume' => 'required|max:1000',
				'resume' => 'required|max:2048|mimes:doc,docs,pdf',
						
		 		
			]); 
			     
			     
			 }
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			
		 
			
			$applyJob =new ApplyJob;
			$applyJob->job_title =$request->input('jobtitle');
			$applyJob->from =$request->input('from');
			$applyJob->name =$request->input('name');
			$applyJob->email =$request->input('email');
			$applyJob->phone =$request->input('phone');
			$applyJob->role =$request->input('type');
			$applyJob->keyskills =$request->input('keyskills');
            if($request->input('type')=='Trainer'){
            $applyJob->secondaryskills =$request->input('secondaryskills');
            $applyJob->tmode =$request->input('mode');
            $applyJob->tslot =$request->input('slot');
            $applyJob->availability =$request->input('availability');
            }
			$applyJob->experience =$request->input('experience');
        
            
			$image = [];
		 	
	 
			 if($request->hasFile('resume')){

			$filePath = getResumeFolderStructure();			 
			$file =  $request->file('resume');
			$filename =str_replace(' ', '_', $file->getClientOriginalName());			 
			$destinationPath = public_path($filePath);
			$nameArr = explode('.',$filename);
			$ext = array_pop($nameArr);
			$name = implode('_',$nameArr);
			if(file_exists($destinationPath.'/'.$filename)){
			$filename = $name."_".time().'.'.$ext;
			}
			$file->move($destinationPath,$filename);				 
			$image['resume'] = array(
			'name'=>$filename,
			'alt'=>$filename,						
			'src'=>$filePath."/".$filename
			);	
			$applyJob->resume=serialize($image);
			}   
			
			if($applyJob->save()){	
			
		//	$jobtitle=$request->input('jobtitle');			
		//	 Cookie::queue("showapply", $jobtitle, "60");
			 
			 $headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		  $headers .= "Content-Type: multipart/mixed; boundary=" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			  $to = array( "cromacampuswebsite@gmail.com");
	    	$subject="Regarding Job careers - ".$request->input('name')."- ".$request->input('jobtitle');
	     			  
			$message =' <tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('name').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('email').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Job Title - EXP:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('jobtitle').' Exp- '.$request->input('experience').' </span><u></u><u></u></p>
			</td>
			</tr>
				<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Skills:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('keyskills').' </span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Mobile:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">'.$request->input('phone').'</span><u></u><u></u></p>
			</td>
			</tr>		
			 
		 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">From Lead:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('from').'</span><u></u><u></u></p>
			</td>
			</tr>
			 ';
			//$to="cromacampusleads@gmail.com";
			
			 
			  $stdemail="";
			 $codemail="";
			 $coordinator="";
			 
			// echo "<pre>";print_r($_FILES);die;
	
 
		$to="brijesh.chauhan@cromacampus.com";
		Mail::send('mails.send_lead_inquiry', ['msg'=>$message], function ($m) use ($message,$request,$subject,$stdemail,$codemail) {
		$m->from('info@leadpitch.in', $request->input('name'));
		if($request->file('resume')){ 
		    $filename = $request->file('resume');
		$m->attach($filename->getRealPath(), [
		'as' => $filename->getClientOriginalName(), 
		'mime' => $filename->getMimeType()
		]);
		}
		$m->to('hr@cromacampus.com', "")->subject($subject);				
		});  
			 
		
			 
			 
          $to = array( "hr@cromacampus.com");
             // $to = array( "hr@cromacampus.com");
 		     Mail::send('mails.send_lead_inquiry', ['msg'=>$message], function ($m) use ($message,$request,$subject,$to) {
				$m->from('info@cromacampus.com', $request->input('name'));
				$m->to($to, "")->subject($subject);				
			});   
			 		
			 
			 
        
        if(!empty($request->input('email'))){
        
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: CromaCampus <info@leadpitch.in>';
        $stdemail=$request->input('email');
        $std_message=$request->input('name');
        $subject_stud=$request->input('name') .": Thanks for your Apply Job ";
        Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
        $m->from('info@leadpitch.in', 'Croma Campus');
        $m->to($stdemail, "")->subject($subject_stud);				
        });  
        
        } 
			 
			 
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
			}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);


			}


		
	  
    

	}	
	
      
   
	
	public function saveChat(Request $request){
		
	 

			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			 	'course'=>'required',				
			 	'source'=>'required',				
			 	'user'=>'required',				 	 	
				'from' 	=> 'required',					
				'participant' => 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			
			  
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');		
		$inquiry->source_id= 7;
		$inquiry->source= "Website";
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');			 		 
		$inquiry->category="send_enquiry_Calling";			 
		$inquiry->sub_category=$request->input('from_title');	
		$inquiry->source_id= 7;
		$inquiry->source= "Website";
		 
		
		  $currentdate = date('Y-m-d');
		$lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checklead = Inquiry::where('mobile',$newmobile)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checklead) && $checklead >0){
           
        	$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);		
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				
				
				}else{
			     	$inquiry->duplicate=2;	
					$inquiry->save();	
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}
        }else{
		if($inquiry->save())
		{
			 
			 
		  leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
		
        }
			  
	 
		
	}
	
	
	
	
	 
	
	
	public function saveInquiry(Request $request){
		
	 
 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				//'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			 	'course'=>'required',				
			 	//'source'=>'required',				
			 	//'city'=>'required',				 	 	
				'from' 	=> 'required',					
				'participant' => 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
		 
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		
	 
		
		$inquiry->source_id= 7;
		$inquiry->source= "Website";		
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');			 		 
		$inquiry->category="send_enquiry_calling";	
		 
		$inquiry->sub_category=$request->input('from_title');	
	     $inquiry->source_id= 7;
		$inquiry->source= "Website";
 	    $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();	$lastdate= date('Y-m-d', strtotime($currentdate. ' - 5 day'));		
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
			$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				
                if(!empty($check) && $check>0){
                $inquiry->duplicate=2;				
                $inquiry->save();					 
                return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
                }else{						
                $inquiry->save();					
                leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
                return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				}			 
			
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{ 
	 
		if($inquiry->save())
		{
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			
			 
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
		
        }
			  
	 
		
	}
	
	
	
	
	 
	
	
	public function saveLead(Request $request){
		
	 
 
	
	
	    $mobile= ltrim($request->input('phone'), '0');	
		$mobile= trim($mobile);	
		$newmobile=  preg_replace('/\s+/', '', $mobile);
		$currentdate = date('Y-m-d');
    
              
               $validator = Validator::make($request->all(),[							
			//	'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',		
			//	'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'code' 	=> 'required|numeric',
			 	'source'=>'required',				
			 //	'message'=>'required',				 	 	
				'from' 	=> 'required',					
				'participant' => 'required',
				'phone'=>'required',
				'course'=>'required',
								
		 		
			]); 
              
              
           
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
		  
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		if($request->input('name')){
		   $name= $request->input('name');
		}else{
		     $name="TBD";
		    
		}
		$inquiry = New Inquiry;		 
		$inquiry->name= $name;
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		
	 
		
		$inquiry->source_id= $request->input('source');
		$soursename=LeadSource::findOrFail($request->input('source'))->name;
		$inquiry->source= $soursename;  	
		
 
		
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->course= $request->input('course');	
		
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');	
		 		 
		$inquiry->category="send_enquiry_lead";	
		$inquiry->from_name=$request->input('from_name');	
		$inquiry->sub_category=$request->input('from_title');	
		if(!empty($request->input('message'))){
        $inquiry->comment= $request->input('message');	
        }else{
        $inquiry->comment="";
        }	
		 
		 
		
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
				
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{
            
		if($inquiry->save())
		{
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			 
			 
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
        } 
	 
		
	}
	
	
	 
 
	
	
	public function saveTeamLead(Request $request){
		  
	 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				//'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			 	'course'=>'required',				
			 	'user'=>'required',				
			 	//'city'=>'required',				 	 	
				'from' 	=> 'required',					
				'participant' => 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
		 
	 
	  
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		
	  
		$inquiry->source_id= 7;
		$inquiry->source= "Website";		
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');	
		 		 
		$inquiry->category="send_enquiry_team_lead";	
		 
		$inquiry->sub_category=$request->input('from_title');	
	 
		if($inquiry->save()){
		
			 
		  leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			 
			
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}
	 
		
	}
	
	 
	
		public function saveLeadSocial(Request $request){
		
	 
 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				//'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			 	'course'=>'required',				
			 	//'source'=>'required',				
			 	//'city'=>'required',				 	 	
				'from' 	=> 'required',					
				'participant' => 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
 
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		
	 
		$inquiry->source_id= $request->input('source');
		$soursename=LeadSource::findOrFail($request->input('source'))->name;
		$inquiry->source= $soursename;  
		
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');			 	 
		$inquiry->category="send_enquiry_social";	
		$inquiry->from_name=$request->input('from_name');	
		 
		$inquiry->sub_category=$request->input('from_title');	
		// echo "<pre>";print_r($inquiry);die;
		
		   $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();	$lastdate= date('Y-m-d', strtotime($currentdate. ' - 5 day'));		
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
		 
			
			
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				
                if(!empty($check) && $check>0){
                $inquiry->duplicate=2;				
                $inquiry->save();					 
                return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
                }else{						
                $inquiry->save();					
                leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
                return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				}			 
			
			
			
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{ 
            
		if($inquiry->save())
		{
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			
		 
			
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
        }
			  
	 
		
	}
	
	
	
	
		public function saveLeadSocialShruti(Request $request){
		
	 
 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				//'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			 	'course'=>'required',				
			 	//'source'=>'required',				
			 	//'city'=>'required',				 	 	
				'from' 	=> 'required',					
				'participant' => 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
	 
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		
		$inquiry->source_id= $request->input('source');
		$soursename=LeadSource::findOrFail($request->input('source'))->name;
		$inquiry->source= $soursename;
		
		
	 
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');	
		 	 
		$inquiry->category="send_enquiry_social";	
		$inquiry->from_name=$request->input('from_name');	
		 
		$inquiry->sub_category=$request->input('from_title');	
		// echo "<pre>";print_r($inquiry);die;
		
	   $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();	$lastdate= date('Y-m-d', strtotime($currentdate. ' - 5 day'));		
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				}			 
				
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{ 
		if($inquiry->save())
		{
		    
		    
		    
			leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			
			  
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
        }  
	 
		
	}
	
	
	
	 
		public function saveLeadSocialAnmol(Request $request){
		
	 
 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				//'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			 	'course'=>'required',				
			 	//'source'=>'required',				
			 	//'city'=>'required',				 	 	
				'from' 	=> 'required',					
				'participant' => 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
	 
	  
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		
      	$inquiry->source_id= $request->input('source');
		$soursename=LeadSource::findOrFail($request->input('source'))->name;
		$inquiry->source= $soursename;  
		
			
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');		 		 
		$inquiry->category="send_enquiry_social";	
		$inquiry->from_name=$request->input('from_name');	
		 
		$inquiry->sub_category=$request->input('from_title');	
		// echo "<pre>";print_r($inquiry);die;
		
		  $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();	$lastdate= date('Y-m-d', strtotime($currentdate. ' - 5 day'));		
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
			$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}	
				}			 
				
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{ 
            
		if($inquiry->save())
		{
			 
			leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			
			 
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
		
        }
			  
		 
		
	}
	
	
	
	
		public function saveLeadSocialSachin(Request $request){
		
	 
 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				//'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			 	'course'=>'required',				
			 	//'source'=>'required',				
			 	//'city'=>'required',				 	 	
				'from' 	=> 'required',					
				'participant' => 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
	 
	  
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		
		$inquiry->source_id= $request->input('source');
		$soursename=LeadSource::findOrFail($request->input('source'))->name;
		$inquiry->source= $soursename;
		
 	
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');			 		 
		$inquiry->category="send_enquiry_social";	
		$inquiry->from_name=$request->input('from_name');			 
		$inquiry->sub_category=$request->input('from_title');	
		
    	$currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
         
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();	$lastdate= date('Y-m-d', strtotime($currentdate. ' - 5 day'));		
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();	 		
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
				
					if(!empty($check) && $check>0){
					$inquiry->duplicate=2;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
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
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
				}			 
			
			
			}else if(!empty($checkleadday) && $checkleadday>0){
				
				$leadcheck = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
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


				}
				 if(!empty($check) && $check>0){
					$inquiry->duplicate=1;				
					$inquiry->save();					 
					return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
				}else{
						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);			
				return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);				
				}
			}else{ 
            
		if($inquiry->save())
		{
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
		 
			
			return response()->json(['status'=>1,'msg'=>'successfully submitted'],200);
		}else{
			return response()->json(['status'=>0,'msg'=>'Not submitted'],200);
			
			
		}
        }
			  
	 
		
	}
	
	
	
	
	public function saveLeadBulkEmail(Request $request){
		
	 
 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
					'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',						
				'phone' 	=> 'required|numeric',	
			 	'course'=>'required',
				'from' 	=> 'required',					
				'participant' => 'required',
					'slot' => 'required',
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
	 
	  
		
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		$inquiry->time_slot= $request->input('slot');
		$inquiry->source_id= 7;
		$inquiry->source= "Website";		
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		$inquiry->reason= "Course-NF";
		$inquiry->course= $request->input('course');	
		$course_name =$request->input('course');
		}
		 		
		}
		
		
		 
		
		$inquiry->form= $request->input('from');			 	 
		$inquiry->category="send_enquiry_Bulk_Email";	
		$inquiry->from_name=$request->input('from_name');	
		 
		$inquiry->sub_category=$request->input('from_title');	
		 
		
	     $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();	$lastdate= date('Y-m-d', strtotime($currentdate. ' - 5 day'));		
         
        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        if(!empty($checklead) && $checklead >0){				
        
        $inquiry->duplicate=1;	
        $inquiry->save();
        return response()->json(['status'=>1,],200);			
        }else if(!empty($checkleadday) && $checkleadday>0){
        
        $inquiry->duplicate=1;	
        $inquiry->save();
        return response()->json(['status'=>1,],200);				
        
        }else if(!empty($checkleadcourse) && $checkleadcourse>0){ 
        $inquiry->duplicate=2;
        $inquiry->save();
        return response()->json(['status'=>1,],200);
        
        }else{ 
		if($inquiry->save())
		{
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
	    	 
	     
			if(!empty($request->input('email'))){
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud=$request->input('name') .": Thanks for your Enquiry";	
 		    Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.in', 'Croma Campus');
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			
			}
		
		
			return response()->json(['status'=>1,],200);
		}else{
			return response()->json(['status'=>0,],200);
			
			
		}
        }
			  
	 
		
	}
	
	
	 

		public function saveRequestZone(Request $request){
		 
//	echo "<pre>";print_r($_POST);die;
			 
			  if($request->input('rz_form') ==='TF'){
				 
			 
			   $validator = Validator::make($request->all(),[							
			'name' 	=> 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',					
				'course' 	=> 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:50',					
				'trainer' 	=> 'required',					
			//	'feedback' 	=> 'required',					
				'rating' 	=> 'required',					
				'rating1' 	=> 'required',	
				'rating2' 	=> 'required',					
				'rating4' 	=> 'required',					
				'rating3' 	=> 'required',					
				'rating5'	=> 'required',					
				'rating6' => 'required',	
				'studentId' 	=> 'required',				
				 
			]); 			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			
			
			$inquiry = New SiteFeedback;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->phone= $request->input('phone');			 	 
		$inquiry->course= $request->input('course');	
		$inquiry->studentId= $request->input('studentId');	
	    $inquiry->Q1= $request->input('rating');	
		$inquiry->Q2= $request->input('rating1');	
		$inquiry->Q3= $request->input('rating2');	
		$inquiry->Q4= $request->input('rating3');	
		$inquiry->Q5= $request->input('rating4');	
		$inquiry->Q6= $request->input('rating5');	
		$inquiry->Q7= $request->input('rating6');	
		$inquiry->comment= $request->input('feedback');	
	 
	    $inquiry->save();
	
			
			
			 }
			 
			
			 
			 
			 if($request->input('rz_form') ==='RCT'){
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',				 					
				'campany_name' 	=> 'required',					
				'course' 	=> 'required',					
				'remark' 	=> 'required',					
				'code' 	=> 'required|numeric',	
			]); 			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			 }
			 
			 
			 
			
			 if($request->input('rz_form') ==='SR'){
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
				'candidate_phone' 	=> 'required|numeric',		
				'course' 	=> 'required',					
				'remark' 	=> 'required',					
				'candidate_name' => 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:50',				
				 				
				 
			]); 			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			 }
			 
			 
			 
			  if($request->input('rz_form') ==='FC'){
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',					
				'course' 	=> 'required',					
				'query' 	=> 'required',					
				 			
				 				
				 
			]); 			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			 } 
			
			
			if($request->input('rz_form') ==='NBE' || $request->input('rz_form') ==='RFOT'|| $request->input('rz_form') ==='CTM'|| $request->input('rz_form') ==='ROT' ){
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',					
				'course' 	=> 'required',					
				'remark' 	=> 'required',		 			
				 				
				 
			]); 			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			
			 }
			 
			 if($request->input('rz_form') ==='PQ'){
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',					
				'course' 	=> 'required',					
				'query' 	=> 'required',					
				 			
				 				
				 
			]); 			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			 }
			

			 if($request->input('rz_form') ==='JPA'){
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|string|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',					
				'course' 	=> 'required',					
				'remark' 	=> 'required',					
				 			
				 				
				 
			]); 			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
			 }
			 
			 
	 
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		
        $code =$request->input('code');
        if($code=='91'){
        $type_lead= "Domestic";
        }else{
        $type_lead= "International";
        }
        
		if($request->input('code')){
		$inquiry->code=$request->input('code');
		}
		$inquiry->mobile= ltrim($request->input('phone'), '0');	
		
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
		$course_name =$request->input('course');
		}
		 		
		}
		 
		$inquiry->source_id= 7;
		$inquiry->source= "Website";
		$inquiry->form= $request->input('from_title');	
		$inquiry->category="send_request_zone";	
		$inquiry->sub_category=$request->input('from_title');	
		
//	echo $lead_course_id.'=='.$type_lead.'--';
//	echo "<pre>";print_r($inquiry);die;
		if($inquiry->save())
		{
		    
		     if($request->input('rz_form') ==='SR' || $request->input('rz_form') ==='RCT' || $request->input('rz_form') ==='RFOT' ){
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
				
		     }
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		//	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			// Additional headers
			//	$headers .= 'From: inquiry@cromacampus.com' . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$to="cromacampuswebsite@gmail.com";
		 
	    	$subject="Student Enquiry - ".$request->input('name');
	    	
	    	  if($request->input('rz_form') ==='RCT'){
				  $coursehtml="";
				  	$to ="devendra1784@hotmail.com";
			  }else{
				$coursehtml='<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Course:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('course').'</span><u></u><u></u></p>
			</td>
			</tr>';
				$to ="devendra1784@hotmail.com";
			  }
	    		
	    		if($request->input('code')){
					$htmlmobile='<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Mobile:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">'.'+'.$request->input('code').'-'.ltrim($request->input('phone'), '0').'</span><u></u><u></u></p>
			</td>
			</tr>';
					
				}else{
					$htmlmobile='<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Mobile:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">'.ltrim($request->input('phone'), '0').'</span><u></u><u></u></p>
			</td>
			</tr>';
				}	
	    		
	    		
	    		
	    		
	    					  
			$message =' <tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('name').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('email').'</span><u></u><u></u></p>
			</td>
			</tr>
			'.$htmlmobile.'
			'.$coursehtml.'
			
			 
		 
			 ';
			//$to="cromacampusleads@gmail.com";
			
			 
				  
			 
			 
			 
			  if($request->input('rz_form') ==='TF'){
			$message .='  
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Student Id:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('studentId').'</span><u></u><u></u></p>
			</td>
			</tr>
			 
			 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Trainer Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('trainer').'</span><u></u><u></u></p>
			</td>
			</tr>
			 
			
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Quality of the content consistent throughout the course?   Rating:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('rating').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">How would you rate your trainer’s expertise?  Rating:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('rating1').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">How would you rate your trainer’s delivery skills?  Rating:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('rating2').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Knowledge or skills have improved by taking the course?  Rating:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('rating3').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Rate your counsellor’s guidance during the course?  Rating:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('rating4').'</span><u></u><u></u></p>
			</td>
			</tr><tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">HR/Placement team helpful during/after the course? Rating:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('rating5').'</span><u></u><u></u></p>
			</td>
			</tr><tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">How would you rate your overall learning experience?  Rating:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('rating6').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Feedback Comments:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('feedback').'</span><u></u><u></u></p>
			</td>
			</tr>';
			
			$to="feedback@cromacampus.com";
			
			  }else if($request->input('rz_form') ==='RCT'){
			
				$message .='<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Campany Name :</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('campany_name').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Technology Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('course').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Remark:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('remark').'</span><u></u><u></u></p>
			</td>
			</tr>';
			$to="cromacampuswebsite@gmail.com";
			
			  }else  if($request->input('rz_form') ==='SR'){			
		    	$message .=' <tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Candidate Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('candidate_name').'</span><u></u><u></u></p>
			</td>
			</tr>	<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Candidate Phone:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('candidate_phone').'</span><u></u><u></u></p>
			</td>
			</tr>			 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Remark:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('remark').'</span><u></u><u></u></p>
			</td>
			</tr>';
				$to ="devendra1784@hotmail.com";
			$to="cromacampuswebsite@gmail.com";
			  }else if($request->input('rz_form') ==='FC'){			
				$message .='	 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Remark:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('query').'</span><u></u><u></u></p>
			</td>
			</tr>';
			 
			$to="team@cromacampus.com";	
			  }else if($request->input('rz_form') ==='PQ'){			
				$message .='	 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Remark:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('query').'</span><u></u><u></u></p>
			</td>
			</tr>';
				$to ="devendra1784@hotmail.com";
			  }else if($request->input('rz_form') ==='JPA'){			
				$message .='	 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Remark:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('remark').'</span><u></u><u></u></p>
			</td>
			</tr>';
				$to ="devendra1784@hotmail.com";
			  }else if($request->input('rz_form') ==='NBE'){			
				$message .='	 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Batch :</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('batch').'</span><u></u><u></u></p>
			</td>
			</tr>';
			$to ="devendra1784@hotmail.com";
			  }
				
		 
			
			 $stdemail="";
			 $codemail="";
			 $coordinator="";
         //  echo "<pre>";print_r($request->file('fees_file')->getRealPath()); die;
          
	 
		/*Mail::send('mails.send_lead_inquiry', ['msg'=>$message], function ($m) use ($message,$request,$subject,$stdemail,$codemail) {
		$m->from('inquiry@cromacampus.com', $request->input('name'));
		if($request->file('choose_file')){

		$m->attach($request->file('choose_file')->getRealPath(), [
		'as' => $request->file('choose_file')->getClientOriginalName(), 
		'mime' => $request->file('choose_file')->getMimeType()
		]); 
		}
		$m->to('devendra1784@hotmail.com', "")->subject($subject);				
		});   
				*/
			 
			//Mail::send($to,$subject,$message,$headers);
		//	mail($to,$subject,$message,$headers);
			
			
			  
            // $to = array( "brijeshchauhan68@gmail.com");
 		    /* Mail::send('mails.send_lead_inquiry', ['msg'=>$message], function ($m) use ($message,$request,$subject,$stdemail,$codemail,$to) {
				$m->from('info@leadpitch.in', $request->input('name'));
				$m->to($to, "")->subject($subject);	
				
				//$m->to($to, "")->subject($subject)->cc('brijesh.chauhan@cromacampus.com');	
			});   
				
			*/
		/*	if(!empty($request->input('email'))){
				
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			// Additional headers
			//	$headers .= 'From: inquiry@cromacampus.com' . "\r\n";
			$headers .= 'From: CromaCampus <info@leadpitch.in>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud="Thanks for connecting with Croma Campus ".$request->input('course');	 
		//	$to="brijesh.chauhan@cromacampus.com";
 		    Mail::send('mails.mailer', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@leadpitch.in', $request->input('name'));
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			
			
			
			}
			*/
			
			
			return response()->json(['status'=>1,],200);
		}else{
			return response()->json(['status'=>0,],200);
			
			
		}
			  
		 
		
	}
  

	
	
	
	
	
	
	
	
	
	
	
	
	
	
 

	public function saveEnquiryContact(Request $request){
	//	echo "<pre>";print_r($_POST);die;
		 
			 
			   $validator = Validator::make($request->all(),[							
				'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
				'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'phone' 	=> 'required|numeric',	
			//	'phone' 	=> 'required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im',				
				'course'=>'required',				
				'from' 	=> 'required',					
				'code' 	=> 'required|numeric',				
		 		
			]); 
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	  
				 
	 
	 
		$code =$request->input('code');
		if($code=='91'){
		$type_lead= "Domestic";

		}else{
		$type_lead= "International";
		}
		
		
		$inquiry = New Inquiry;		 
		$inquiry->name= $request->input('name');
		$inquiry->email= $request->input('email');
		$inquiry->code= $request->input('code');			
		$mobile= ltrim($request->input('phone'), '0');	
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
		
		
		
		if(!empty($request->input('demo_date'))){		
				$inquiry->demo_date_time= $request->input('demo_date');			
		}
		if(!empty($request->input('experience'))){		
				$inquiry->experience= $request->input('experience');			
		}
		
		$inquiry->form= $request->input('from');				 
		$inquiry->category="send_enquiry";	
		$inquiry->sub_category=$request->input('from_title');	
		$inquiry->source_id= 7;
		$inquiry->source= "Website";
         
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
					return response()->json(['status'=>1,],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,],200);				
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
					return response()->json(['status'=>1,],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,],200);				
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
					return response()->json(['status'=>1,],200);
				}else{
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,],200);				
				}
			}else{  

	
		if($inquiry->save())
		{
			
			leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			 
	 
			 
			if(!empty($request->input('email'))){
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: CromaCampus <info@cromacampus.com>';
			$stdemail=$request->input('email');
	    	$std_message=$request->input('name');
			$subject_stud=$request->input('name') .": 2nd Unity Marathon Certificate ";	
 		    Mail::send('mails.mailer_croma_abgyan', ['name'=>$std_message], function ($m) use ($std_message,$request,$subject_stud,$stdemail) {
				$m->from('info@cromacampus.com', 'Croma Campus');
				$m->to($stdemail, "")->subject($subject_stud);				
			});  
			}
		
			
			return response()->json(['status'=>1,],200);
		}else{
			return response()->json(['status'=>0,],200);
			
			
		}
			 
			 
		} 
		 
		
	}
  



	 
public function getCounsellor(request $request){
//	echo "teste";die;
	$userlist= User::get();
	
	 if(!empty($userlist)){
		foreach($userlist as $key=>$val){
				$data_list[$key]=array(
				'id'		=>$val['id'],
				'name'		=>$val['name'],
				'mobile'	=>$val['mobile'],
				'email'		=>$val['email'],
				 
				); 
			
		}
		$data['status']=true;
		$data['response']=$data_list;
	}else{
	$data['status']=false;
	$data['response']['user_list'] =$data_list;

	}		
	
	
	echo json_encode($data);
	
}
	


	 
public function getCounsellorName(request $request,$id){
	
	//echo "teste";die;
	$userName= User::find($id);
	
	 if(!empty($userName)){
		 
				$data_name=array(
				'id'		=>$userName->id,
				'name'		=>$userName->name,
				'mobile'	=>$userName->mobile,
				'email'		=>$userName->email,
				 
				); 
			
		 
		$data['status']=true;
		$data['response']=$data_name;
	}else{
	$data['status']=false;
	$data['response']['user_list'] =$data_list;

	}		
	
	
	echo json_encode($data);
	
}
	

















}
