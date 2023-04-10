<?php
/**
 * CONTAINS HELPER FUNCTIONS
 */
 
 use App\AbsentCourseAssignment;
  use App\BucketCourseAssignment;
 use App\LeadForward;
 use App\Courseassignment;
 use App\Course;
 use App\Lead;
 use App\User;
 use App\LeadFollowUp;
 use App\Status;
 use App\Inquiry;

 
 
 
// SENDING SMS AND IT'S CONFIGURATION
// **********************************
function sendSMS($sendto, $message,$tempid=null){
	$username = 't1cromacampussms';
	$password = '42308595';
	$sender = 'CCAMPS';
	$sendto = $sendto;
		$tempid = $tempid;
	//$templateId = '1707161786775524106';
	$message = str_replace(' ', '%20', $message);
//	$url = 'http://nimbusit.co.in/api/swsendSingle.asp';
	$url = 'http://nimbusit.co.in/api/swsend.asp';
//	$data = "username=$username&password=$password&sender=$sender&sendto=$sendto&message=$message&entityID=1701160344973814570";
 
		$data = "username=$username&password=$password&sender=$sender&sendto=$sendto&entityID=1701160344973814570&templateID=$tempid&message=$message";
//http://nimbusit.co.in/api/swsendSingle.asp?username=t1cromacampussms&password=42308595&sender=CCAMPS&sendto=9205323836&entityID=1701160344973814570&templateID=1707161786775524106&message=%201234%20is%20Lead%20Portal%20Verification%20Code%20for%20Brijesh%20CROMA%20CAMPUS

//http://nimbusit.co.in/api/swsendSingle.asp?username=t1cromacampussms&password=42308595&sender=CCAMPS&sendto=9205323836&entityID=1701160344973814570&templateID=1707161786775524106&message=v
//http://nimbusit.co.in/api/swsendSingle.asp?username=xxxx&password=xxxx&sender=senderId&sendto=919xxxx&entityID=170134xxxxxxxxx&templateID=158777xxxxxxxxxxx&message=hello  

	$objURL = curl_init($url);
	curl_setopt($objURL, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($objURL, CURLOPT_POST, 1);
	curl_setopt($objURL, CURLOPT_POSTFIELDS, $data);
	$retval = trim(curl_exec($objURL));
	curl_close($objURL);
	
	return $retval;

	
	
	
	
}

 

function bucketLeadCounsellor($val,$lead_course_id,$type_lead,$inquiry,$remark=''){
	
			$absentassigncourse = BucketCourseAssignment::where('counsellors',$val)->get();	
			
		//	echo "<pre>";print_r($absentassigncourse);die;
			if($absentassigncourse->count()>0){
			if($type_lead=='Domestic'){				
			$catCourse = Course::findOrFail($lead_course_id);	
			$bucketIndex = $catCourse->bucket;

			$max = $mCount = 15;
			$i=0;
			$totalClients = count($absentassigncourse);
			$absentbuckets = [];
			$newbucket = [];
			$newbucketmerger = [];
			foreach($absentassigncourse as $counsellor){
			$absentcounsellors= BucketCourseAssignment::where('counsellors',$counsellor->counsellors)->get();
			foreach($absentcounsellors as $absentcounsellor){
			if($mCount == 0){
			$j = $i;
			$absentbuckets[++$j] = $absentbuckets[$i++];
			$absentbuckets[$j]['bucket_assign_dom_course'] = [];
			$mCount = $max-(count($absentbuckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($absentcounsellor->bucket_assign_dom_course))){
			$absentbuckets[$i]['bucket_assign_dom_course'][] = $absentcounsellor->tocounsellor;
			}   
		
			--$mCount;
			}
			
			}
			$j=0;
			
			 if(count($absentbuckets)>0){
				foreach($absentbuckets as $bucket){
				foreach($bucket as $position=>$counsellors){
				foreach($counsellors as $assign){			 
				array_push($newbucket, $assign);
				}
				}
				}
			 } 
			
		 
			$bucketCount = count($newbucket);
				if(!empty($bucketCount)){
			foreach($newbucket as $key=>$valc){
			    
			    $user= 	Courseassignment::where('status',1)->where('counsellors',$valc)->first();			
			if($user->leadassign+1<=$user->leadcount){
			 
		 	$lead =  new Lead;			
			$lead->name = $inquiry->name;
			$lead->email = $inquiry->email;		
			$lead->code = $inquiry->code;			
			$lead->mobile =$inquiry->mobile;			
			$lead->type =1;			
			$lead->source = $inquiry->source_id;
			$lead->source_name = $inquiry->source;
			$lead->course = $inquiry->course_id;
			$lead->course_name = $inquiry->course;
			$lead->status = 1;
			$lead->status_name = "New Lead";			 
			$lead->created_by = $valc;
			$lead->croma_id = $inquiry->id;
			$inquiry= Inquiry::findOrFail($inquiry->id);
			$inquiry->assigned_to=	$valc;
			$inquiry->save(); 
			if($lead->save()){
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $lead->created_by;
			if(!empty($inquiry->comment)){
			$followUp->remark = $inquiry->comment; 
			}else{
			    $followUp->remark=$remark;//""; for facebook remark
			}
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 
			$usercunsl= Courseassignment::where('status',1)->where('counsellors',$valc)->first();	
			$usercunsl->leadassign = $usercunsl->leadassign+1;
			$usercunsl->save();
			}			
		//	$kw = Course::find($lead_course_id);
		//	$kw->bucket = $i+1;
		//	$kw->save();
			

		
			
			
			 
			}else{
			   $inquiry= Inquiry::findOrFail($inquiry->id);
			    $inquiry->reason="Bucket Full";
					$inquiry->save(); 
			    
			}
            }
                }else{
                
                $inquiry= Inquiry::findOrFail($inquiry->id);
                $inquiry->reason="Counsellor-NF";
                $inquiry->save();
                }
		 
			 }else{
				 
				 
			$catCourse = Course::findOrFail($lead_course_id);	
			$bucketIndex = $catCourse->bucketinter;				

			$max = $mCount = 15;
			$i=0;
			$totalClients = count($absentassigncourse);
			$absentbuckets = [];
			$newbucket = [];
			$newbucketmerger = [];
			foreach($absentassigncourse as $counsellor){
		 	$absentcounsellors= BucketCourseAssignment::where('counsellors',$counsellor->counsellors)->get();
			foreach($absentcounsellors as $absentcounsellor){
			if($mCount == 0){
			$j = $i;
			$absentbuckets[++$j] = $absentbuckets[$i++];
			$buckets[$j]['bucket_assign_int_course'] = [];
			$mCount = $max-(count($absentbuckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($absentcounsellor->bucket_assign_int_course))){
			$absentbuckets[$i]['bucket_assign_int_course'][] = $absentcounsellor->tocounsellor;
			}

			--$mCount;
			}
			}
			$j=0;
			if(count($absentbuckets)>0){
			foreach($absentbuckets as $bucket){
				foreach($bucket as $position=>$counsellors){
						foreach($counsellors as $assign){			 
						array_push($newbucket, $assign);
						}
				}
				}
			 } 
			
		 
			$bucketCount = count($newbucket);
			if(!empty($bucketCount)){
			foreach($newbucket as $key=>$valc){
			 	
		 $user= 	Courseassignment::where('counsellors',$valc)->first();			
			if($user->leadassign+1<=$user->leadcount){
 
		 	$lead =  new Lead;			
			$lead->name = $inquiry->name;
			$lead->email = $inquiry->email;		
			$lead->code = $inquiry->code;			
			$lead->mobile =$inquiry->mobile;			
			$lead->type =1;			
			$lead->source = $inquiry->source_id;
			$lead->source_name = $inquiry->source;
			$lead->course = $inquiry->course_id;
			$lead->course_name = $inquiry->course;
			$lead->status = 1;
			$lead->status_name = "New Lead";			 
			$lead->created_by = $valc;
			$lead->croma_id = $inquiry->id;
			$inquiry= Inquiry::findOrFail($inquiry->id);
			$inquiry->assigned_to=	$valc;	
			$inquiry->save();
			
			$usercunsl= Courseassignment::where('counsellors',$valc)->first();	
			$usercunsl->leadassign = $usercunsl->leadassign+1;
			$usercunsl->save();
			if($lead->save()){
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $lead->created_by;
			if(!empty($inquiry->comment)){
			$followUp->remark = $inquiry->comment; 
			}else{
			    $followUp->remark=$remark;//""; for facebook remark
			}
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 

			}  
			  
			
		 
		 
			}else{
			    
			    $inquiry= Inquiry::findOrFail($inquiry->id);
		    	$inquiry->reason="Bucket Full";
		    	$inquiry->save();
			}
        }  
        
			 }else{
			    $inquiry= Inquiry::findOrFail($inquiry->id);
				$inquiry->reason="Counsellor-NF";
				$inquiry->save();
			 }
			 } 
		 }
}

function absentleadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark=''){
		$absentassigncourse = Courseassignment::where('status',0)->get();	
			if($absentassigncourse->count()>0){
				
			if($type_lead=='Domestic'){				
			$catCourse = Course::findOrFail($lead_course_id);	
			$bucketIndex = $catCourse->bucket;
			$max = $mCount = 15;
			$i=0;
			$assignbuckets = [];	
			$newconsellor = [];	
			$newbucket = [];  
			
			foreach($absentassigncourse as $counsellor){
			    
			if($mCount == 0){			
			$j = $i;
			$assignbuckets[++$j] = $assignbuckets[$i++];
			$assignbuckets[$j]['assign_dom_course'] = [];
			$mCount = $max-(count($assignbuckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($counsellor->assign_dom_course))){
			$assignbuckets[$i]['assign_dom_course'][] = $counsellor->counsellors;
			}   

			--$mCount;
			 

			}
			
	 
			foreach($assignbuckets as $bucket){
			foreach($bucket as $position=>$counsellors){
			foreach($counsellors as $assign){			 
			array_push($newconsellor, $assign);
			}
			}
			}

			$bucketCount = count($newconsellor);
				$maxn = $mCountn = 15;
				$in=0;
			foreach($newconsellor as $key=>$val){
			$absentcounsellors= AbsentCourseassignment::where('counsellors',$val)->get();
			foreach($absentcounsellors as $absentcounsellor){
			 if($mCount == 0){			
			$jn = $in;
			$absentbuckets[++$jn] = $absentbuckets[$in++];
			$absentbuckets[$jn]['absent_assign_dom_course'] = [];
			$mCountn = $maxn-(count($absentbuckets[$jn],1)-1);
			 }
			if(in_array($lead_course_id,unserialize($absentcounsellor->absent_assign_dom_course))){
			$absentbuckets[$in]['absent_assign_dom_course'][] = $absentcounsellor->tocounsellor;
			}   
		
			--$mCountn;
			}

			}
		 
			 
			
			 if(count($absentbuckets)>0){
				foreach($absentbuckets as $bucket){
				foreach($bucket as $position=>$counsellors){
				foreach($counsellors as $assign){			 
				array_push($newbucket, $assign);
				}
				}
				}
				
				
			 }else{			 
				 
			$maxnot = $mCountnot = 15;
			$ino=0;
				   $notabsentassigncourse= 	Courseassignment::where('status',0)->get();						  
					foreach($notabsentassigncourse as $notcounsellor){					 				 
					 if($mCountnot == 0){		
					$jno = $ino;
					$notabsentbuckets[++$jno] = $notabsentbuckets[$ino++];
					$notabsentbuckets[$jno]['assign_dom_course'] = [];
					$mCountnot = $maxnot-(count($notabsentbuckets[$jno],1)-1);
					 }
					if(in_array($lead_course_id,unserialize($notcounsellor->assign_dom_course))){
					$notabsentbuckets[$ino]['assign_dom_course'][] = $notcounsellor->counsellors;
					}  
				
					--$mCountnot;	 

					}
					foreach($notabsentbuckets as $notbucket){
					foreach($notbucket as $position=>$notcounsellors){
					foreach($notcounsellors as $notassign){			 
					array_push($newbucket, $notassign);
					}
					}
					}
				 
			 }
			
			
		 
			 
			$bucketCount = count($newbucket);
			if(!empty($bucketCount)){
			foreach($newbucket as $key=>$val){			    
			$user= 	Courseassignment::where('counsellors',$val)->first();			
			if($user->leadassign+1<=$user->leadcount){
			 
		 	$lead =  new Lead;			
			$lead->name = $inquiry->name;
			$lead->email = $inquiry->email;		
			$lead->code = $inquiry->code;			
			$lead->mobile =$inquiry->mobile;			
			$lead->type =1;			
			$lead->source = $inquiry->source_id;
			$lead->source_name = $inquiry->source;
			$lead->course = $inquiry->course_id;
			$lead->course_name = $inquiry->course;
			$lead->status = 1;
			$lead->status_name = "New Lead";			 
			$lead->created_by = $val;
			$lead->croma_id = $inquiry->id; 
			 
			$inquiry= Inquiry::findOrFail($inquiry->id);
			$inquiry->assigned_to=	$val;
			$inquiry->save(); 
			if($lead->save()){
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $lead->created_by;
			if(!empty($inquiry->comment)){
			$followUp->remark = $inquiry->comment; 
			}else{
			    $followUp->remark=$remark;//""; //For facebook remark
			}
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 
			$usercunsl= Courseassignment::where('counsellors',$val)->first();	
			$usercunsl->leadassign = $usercunsl->leadassign+1;
			$usercunsl->save();
			}			
						
			 
			}else{
				
				bucketLeadCounsellor($val,$lead_course_id,$type_lead,$inquiry,$remark='');
                $inquiry= Inquiry::findOrFail($inquiry->id);
                $inquiry->reason="Bucket Full";
                $inquiry->save();
			}
            }
			
			}else{
			      
		     $inquiry= Inquiry::findOrFail($inquiry->id);
             $inquiry->reason="Counsellor-NF";
             $inquiry->save();
			}
			 
		 
			 }else{
				 
				 //inernational
				 				
			$catCourse = Course::findOrFail($lead_course_id);	
			$bucketIndex = $catCourse->bucket;
			$max = $mCount = 15;
			$i=0;
			$assignbuckets = [];	
			$newconsellor = [];		
			$newbucket = [];		
			foreach($absentassigncourse as $counsellor){
			if($mCount == 0){					
				$j = $i;
				$assignbuckets[++$j] = $assignbuckets[$i++];
				$assignbuckets[$j]['assign_int_course'] = [];
				$mCount = $max-(count($assignbuckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($counsellor->assign_int_course))){
			$assignbuckets[$i]['assign_int_course'][] = $counsellor->counsellors;
			}   

			--$mCount;
			 

			}
			
			
			foreach($assignbuckets as $bucket){
			foreach($bucket as $position=>$counsellors){
			foreach($counsellors as $assign){			 
			array_push($newconsellor, $assign);
			}
			}
			}
			$maxn = $mCountn = 15;
			$in=0;
			$bucketCount = count($newconsellor);
			foreach($newconsellor as $key=>$val){
			$absentcounsellors= AbsentCourseassignment::where('counsellors',$val)->get();
			foreach($absentcounsellors as $absentcounsellor){
			 if($mCountn == 0){	
			$jn = $in;
			$absentbuckets[++$jn] = $absentbuckets[$in++];
			$absentbuckets[$jn]['absent_assign_int_course'] = [];
			$mCountn = $maxn-(count($absentbuckets[$jn],1)-1);
			 }
			if(in_array($lead_course_id,unserialize($absentcounsellor->absent_assign_int_course))){
			$absentbuckets[$in]['absent_assign_int_course'][] = $absentcounsellor->tocounsellor;
			}  
			--$mCountn;
			}

			}

			 
			
			 if(count($absentbuckets)>0){
				foreach($absentbuckets as $bucket){
				foreach($bucket as $position=>$counsellors){
				foreach($counsellors as $assign){			 
				array_push($newbucket, $assign);
				}
				}
				}
			 }else{			 
					$maxnot = $mCountnot = 15;
					$inot=0;
					$notabsentassigncourse= 	Courseassignment::where('status',0)->get();						  
					foreach($notabsentassigncourse as $notcounsellor){					 				 
					if($mCountnot == 0){	
					$jnot = $inot;
					$notabsentbuckets[++$jnot] = $notabsentbuckets[$inot++];
					$notabsentbuckets[$jnot]['assign_int_course'] = [];
					$mCountnot = $maxnot-(count($notabsentbuckets[$jnot],1)-1);
					}
					if(in_array($lead_course_id,unserialize($notcounsellor->assign_int_course))){
					$notabsentbuckets[$inot]['assign_int_course'][] = $notcounsellor->counsellors;
					}  
				
					--$mCountnot;	 

					}
					foreach($notabsentbuckets as $notbucket){
					foreach($notbucket as $position=>$notcounsellors){
					foreach($notcounsellors as $notassign){			 
					array_push($newbucket, $notassign);
					}
					}
					}
				 
			 }
			
			 
			$bucketCount = count($newbucket);
			if(!empty($bucketCount)){
			foreach($newbucket as $key=>$val){			    
			    $user= 	Courseassignment::where('counsellors',$val)->first();			
			if($user->leadassign+1<=$user->leadcount){
			    
			if($bucketCount<=$bucketIndex || $bucketIndex==0){
			$bucketIndex = 0;
			}
			 
			 
		 	$lead =  new Lead;			
			$lead->name = $inquiry->name;
			$lead->email = $inquiry->email;		
			$lead->code = $inquiry->code;			
			$lead->mobile =$inquiry->mobile;			
			$lead->type =1;			
			$lead->source = $inquiry->source_id;
			$lead->source_name = $inquiry->source;
			$lead->course = $inquiry->course_id;
			$lead->course_name = $inquiry->course;
			$lead->status = 1;
			$lead->status_name = "New Lead";			 
			$lead->created_by = $val;
			$lead->croma_id = $inquiry->id;
			 
			 
			$inquiry= Inquiry::findOrFail($inquiry->id);
			$inquiry->assigned_to=	$val;
			$inquiry->save(); 
			if($lead->save()){
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $lead->created_by;
			if(!empty($inquiry->comment)){
			$followUp->remark = $inquiry->comment; 
			}else{
			    $followUp->remark=$remark;//""; for facebook remark
			}
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 
			$usercunsl= Courseassignment::where('counsellors',$val)->first();	
			$usercunsl->leadassign = $usercunsl->leadassign+1;
			$usercunsl->save();
			}	
			 
			}else{
				bucketLeadCounsellor($val,$lead_course_id,$type_lead,$inquiry,$remark='');
                $inquiry= Inquiry::findOrFail($inquiry->id);
                $inquiry->reason="Bucket Full";
                $inquiry->save();
			}
            }

			 }else{
					$inquiry= Inquiry::findOrFail($inquiry->id);
					$inquiry->reason="Counsellor-NF";
					$inquiry->save(); 
			 }
   	


		
			 } 
		 }
		 
	
}



function leadassignCounsellor($lead_course_id,$type_lead,$inquiry){
	
	 if(is_numeric($lead_course_id) && !empty($lead_course_id)){
				
		$assigncourse = Courseassignment::where('status',1)->get();	
		 if($assigncourse->count()>0){
			if($type_lead=='Domestic'){				
			$catCourse = Course::findOrFail($lead_course_id);	
			$bucketIndex = $catCourse->bucket;

			$max = $mCount = 15;
			$i=0;
			$totalClients = count($assigncourse);
			$buckets = [];
			$newbucket = [];
			$newbucketmerger = [];
			foreach($assigncourse as $counsellor){
			
			if($mCount == 0){
			$j = $i;
			$buckets[++$j] = $buckets[$i++];
			$buckets[$j]['assign_dom_course'] = [];
			$mCount = $max-(count($buckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($counsellor->assign_dup_dom_course))){
			$buckets[$i]['assign_dup_dom_course'][] = $counsellor->counsellors;
			}  
			if(in_array($lead_course_id,unserialize($counsellor->assign_dom_course))){
			$buckets[$i]['assign_dom_course'][] = $counsellor->counsellors;
			}
			--$mCount;
			}
			$j=0;
			foreach($buckets as $bucket){
				foreach($bucket as $position=>$counsellors){
						foreach($counsellors as $assign){			 
						array_push($newbucket, $assign);
						}
				}
			}
			
			$i = 0;
			$bucketCount = count($newbucket);
			if(!empty($bucketCount)){
			foreach($newbucket as $key=>$val){
			    
			    $user= 	Courseassignment::where('counsellors',$val)->where('status',1)->first();			
			if($user->leadassign+1<=$user->leadcount){
			    
			if($bucketCount<=$bucketIndex || $bucketIndex==0){
			$bucketIndex = 0;
			}
			if($bucketIndex==$i){
			
			$mobile= ltrim($inquiry->mobile, '0');	
	    	$mobile= trim($mobile);	
			$newmobile=  preg_replace('/\s+/', '', $mobile);
			 $leadcheck = Lead::where('mobile',$inquiry->mobile)->where('course',$inquiry->course_id)->orderBy('id','desc')->get();	
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
			
			
		 
		     
		 	$lead =  new Lead;			
			$lead->name = $inquiry->name;
			$lead->email = $inquiry->email;		
			$lead->code = $inquiry->code;			
			$lead->mobile =$inquiry->mobile;			
			$lead->type =1;			
			$lead->source = $inquiry->source_id;
			$lead->source_name = $inquiry->source;
			$lead->course = $inquiry->course_id;
			$lead->course_name = $inquiry->course;
			$lead->status = 1;
			$lead->status_name = "New Lead";			 
			$lead->created_by = $val;
			$lead->croma_id = $inquiry->id;
			$lead->status=1;	
			 
			$inquiry= Inquiry::findOrFail($inquiry->id);
			$inquiry->assigned_to=	$val;
			$inquiry->save(); 
			if($lead->save()){
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $lead->created_by;
			if(!empty($inquiry->comment)){
			$followUp->remark = $inquiry->comment; 
			}else{
			    $followUp->remark="";
			}
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 
			$usercunsl= Courseassignment::where('counsellors',$val)->first();	
			$usercunsl->leadassign = $usercunsl->leadassign+1;
			$usercunsl->save();
			}			
			$kw = Course::find($lead_course_id);
			$kw->bucket = $i+1;
			$kw->save();
			 

			}
			
			
			$i++;
			}else{
		   bucketLeadCounsellor($val,$lead_course_id,$type_lead,$inquiry,$remark='');
		   
		    $inquiry= Inquiry::findOrFail($inquiry->id);
			$inquiry->reason="Bucket Full";
			$inquiry->save();
			    
			}
            }
            }else{
              absentleadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark='');
            $inquiry= Inquiry::findOrFail($inquiry->id);
            $inquiry->reason="Counsellor-NF";
            $inquiry->save(); 
            
            }
		 
			 }else{
				 
				 
			$catCourse = Course::findOrFail($lead_course_id);	
			$bucketIndex = $catCourse->bucketinter;				

			$max = $mCount = 15;
			$i=0;
			$totalClients = count($assigncourse);
			$buckets = [];
			$newbucket = [];
			$newbucketmerger = [];
			foreach($assigncourse as $counsellor){
		 	
			if($mCount == 0){
			$j = $i;
			$buckets[++$j] = $buckets[$i++];
			//$buckets[$j]['assign_dom_course'] = [];
			$mCount = $max-(count($buckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($counsellor->assign_int_course))){
			$buckets[$i]['assign_int_course'][] = $counsellor->counsellors;
			}  
			if(in_array($lead_course_id,unserialize($counsellor->assign_dup_int_course))){
			$buckets[$i]['assign_dup_int_course'][] = $counsellor->counsellors;
			}

			--$mCount;
			 
			}
			$j=0;
			foreach($buckets as $bucket){
				foreach($bucket as $position=>$counsellors){
						foreach($counsellors as $assign){			 
						array_push($newbucket, $assign);
						}
				}
			}
			
			$i = 0;
			$bucketCount = count($newbucket);
			if($bucketCount){
			foreach($newbucket as $key=>$val){
			$user= 	Courseassignment::where('counsellors',$val)->where('status',1)->first();			
			if($user->leadassign+1<=$user->leadcount){
			    
			if($bucketCount<=$bucketIndex || $bucketIndex==0){
			$bucketIndex = 0;
			}
			if($bucketIndex==$i){
			$mobile= ltrim($inquiry->mobile, '0');	
	    	$mobile= trim($mobile);	
			$newmobile=  preg_replace('/\s+/', '', $mobile);
			 $leadcheck = Lead::where('mobile',$inquiry->mobile)->where('course',$inquiry->course_id)->orderBy('id','desc')->get();	
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
			
			
		  
 
		 	$lead =  new Lead;			
			$lead->name = $inquiry->name;
			$lead->email = $inquiry->email;		
			$lead->code = $inquiry->code;			
			$lead->mobile =$inquiry->mobile;			
			$lead->type =1;			
			$lead->source = $inquiry->source_id;
			$lead->source_name = $inquiry->source;
			$lead->course = $inquiry->course_id;
			$lead->course_name = $inquiry->course;
			$lead->status = 1;
			$lead->status_name = "New Lead";			 
			$lead->created_by = $val;
			$lead->croma_id = $inquiry->id;
			$lead->status=1;	
			$inquiry= Inquiry::findOrFail($inquiry->id);
			$inquiry->assigned_to=	$val;	
			$inquiry->save();
			
			$usercunsl= Courseassignment::where('counsellors',$val)->first();	
			$usercunsl->leadassign = $usercunsl->leadassign+1;
			$usercunsl->save();
			if($lead->save()){
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $lead->created_by;
			if(!empty($inquiry->comment)){
			$followUp->remark = $inquiry->comment; 
			}else{
			    $followUp->remark="";
			}
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 

			}  
			 
			
			$kw = Course::find($lead_course_id);
			$kw->bucketinter = $i+1;
			$kw->save();
			 
			}
			$i++;
			}else{
			    
			    bucketLeadCounsellor($val,$lead_course_id,$type_lead,$inquiry,$remark='');
			    $inquiry= Inquiry::findOrFail($inquiry->id);
		    	$inquiry->reason="Bucket Full";
		    	$inquiry->save(); 
			    
			    
			}
			
			
        }

			 }else{
			      absentleadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark='');
                    $inquiry= Inquiry::findOrFail($inquiry->id);
                    $inquiry->reason="Counsellor-NF";
                    $inquiry->save();  
			 }
				 
				 
			 }
			 
			 
		 }else{
		     
		    absentleadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark='');
            $inquiry= Inquiry::findOrFail($inquiry->id);
            $inquiry->reason="Counsellor-NF";
            $inquiry->save();  
		     
		 }
		 
		   
		 
		 
			 }
	
	
}









function leadForwardCounsellor($lead_course_id,$type_lead,$lead,$leadForward){
	
	 if(is_numeric($lead_course_id) && !empty($lead_course_id)){
				 
			$assigncourse = Courseassignment::where('status',1)->get();	
		 if($assigncourse->count()>0){
			if($type_lead=='Domestic'){				
			$course = Course::findOrFail($lead_course_id);	
			$bucketIndex = $course->bucket;

			$max = $mCount = 15;
			$i=0;
			$totalClients = count($assigncourse);
			$buckets = [];
			$newbucket = [];
			$newbucketmerger = [];
			foreach($assigncourse as $counsellor){
			
			if($mCount == 0){
			$j = $i;
			$buckets[++$j] = $buckets[$i++];
			$buckets[$j]['assign_dom_course'] = [];
			$mCount = $max-(count($buckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($counsellor->assign_dup_dom_course))){
			$buckets[$i]['assign_dup_dom_course'][] = $counsellor->counsellors;
			}  
			if(in_array($lead_course_id,unserialize($counsellor->assign_dom_course))){
			$buckets[$i]['assign_dom_course'][] = $counsellor->counsellors;
			}
			--$mCount;
		 
			
			}
			$j=0;
			
			 
			foreach($buckets as $bucket){
				foreach($bucket as $position=>$counsellors){
						foreach($counsellors as $assign){			 
						array_push($newbucket, $assign);
						}
				}
			}
			
			$i = 0;
			$bucketCount = count($newbucket);
			if($bucketCount){
			foreach($newbucket as $key=>$val){
			    
			$courseassignment= 	Courseassignment::where('counsellors',$val)->where('status',1)->first();			
		 
			    
			if($bucketCount<=$bucketIndex || $bucketIndex==0){
			$bucketIndex = 0;
			}
			if($bucketIndex==$i){
			
			  
			$leadForwards= LeadForward::findOrFail($leadForward->id);
		    $leadForwards->forward_to = $val;
			$leadForwards->save();

			$lead= Lead::findOrFail($lead->id);
		    $lead->created_by = $val;
			$lead->created_at =date('Y-m-d H:i:s');
			if($lead->save()){	
			    $followUp =LeadFollowUp::where('lead_id',$lead->id)->delete();
			    
			    
			$user = User::findOrFail($leadForward->created_by);
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $val;
			//$followUp->remark = $user->name." has forwarded lead to you because candidate wants training on ".$lead->course_name;
			$followUp->remark = "";
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 
			}			 			
			$kw = Course::find($lead_course_id);
			$kw->bucket = $i+1;
			$kw->save();			 

			}
			
			
			$i++;
			 
			
			
            }
			} 

		 
			 }else{
				 
				 
			$catCourse = Course::findOrFail($lead_course_id);	
			$bucketIndex = $catCourse->bucketinter;				

			$max = $mCount = 15;
			$i=0;
			$totalClients = count($assigncourse);
			$buckets = [];
			$newbucket = [];
			$newbucketmerger = [];
			foreach($assigncourse as $counsellor){
		 	
			if($mCount == 0){
			$j = $i;
			$buckets[++$j] = $buckets[$i++];
			//$buckets[$j]['assign_dom_course'] = [];
			$mCount = $max-(count($buckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($counsellor->assign_int_course))){
			$buckets[$i]['assign_int_course'][] = $counsellor->counsellors;
			}  
			if(in_array($lead_course_id,unserialize($counsellor->assign_dup_int_course))){
			$buckets[$i]['assign_dup_int_course'][] = $counsellor->counsellors;
			}

			--$mCount;
			 
			}
			$j=0;
			foreach($buckets as $bucket){
				foreach($bucket as $position=>$counsellors){
						foreach($counsellors as $assign){			 
						array_push($newbucket, $assign);
						}
				}
			}
			
			$i = 0;
			$bucketCount = count($newbucket);
			foreach($newbucket as $key=>$val){
			$courseassignment= 	Courseassignment::where('counsellors',$val)->where('status',1)->first();			
			 
			    
			if($bucketCount<=$bucketIndex || $bucketIndex==0){
			$bucketIndex = 0;
			}
			if($bucketIndex==$i){
				
			 	 
			
	 
			$leadForwards= LeadForward::findOrFail($leadForward->id);
		    $leadForwards->forward_to = $val;
			$leadForwards->save();

			$lead= Lead::findOrFail($lead->id);
		    $lead->created_by = $val;
			 
			if($lead->save()){
				
				$followUp =LeadFollowUp::where('lead_id',$lead->id)->delete();
				
			$user = User::findOrFail($leadForward->created_by);
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $val;
			//$followUp->remark = $user->name." has forwarded lead to you because candidate wants training on ".$lead->course_name;
			$followUp->remark = "";
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 
			}
			$kw = Course::find($lead_course_id);
			$kw->bucketinter = $i+1;
			$kw->save();
			 
			}
			$i++;
		 
        }

				 
				 
				 
			 }
			 
			 
		 }
		 
			 
			 // counsellor absent  case 
			 
			$absentassigncourse = Courseassignment::where('status',0)->get();	
		 if($absentassigncourse->count()>0){
			if($type_lead=='Domestic'){				
			$catCourse = Course::findOrFail($lead_course_id);	
			$bucketIndex = $catCourse->bucket;

			$max = $mCount = 15;
			$i=0;
			$totalClients = count($absentassigncourse);
			$absentbuckets = [];
			$newbucket = [];
			$newbucketmerger = [];
			foreach($absentassigncourse as $counsellor){
			$absentcounsellors= AbsentCourseAssignment::where('counsellors',$counsellor->counsellors)->get();
			foreach($absentcounsellors as $absentcounsellor){
			if($mCount == 0){
			$j = $i;
			$absentbuckets[++$j] = $absentbuckets[$i++];
			$absentbuckets[$j]['absent_assign_dom_course'] = [];
			$mCount = $max-(count($absentbuckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($absentcounsellor->absent_assign_dom_course))){
			$absentbuckets[$i]['absent_assign_dom_course'][] = $absentcounsellor->tocounsellor;
			}  
			if(in_array($lead_course_id,unserialize($absentcounsellor->absent_assign_int_course))){
			$absentbuckets[$i]['absent_assign_int_course'][] = $absentcounsellor->tocounsellor;
			}
			--$mCount;
			}
			
			}
			$j=0;
			
			 
			foreach($absentbuckets as $bucket){
				foreach($bucket as $position=>$counsellors){
						foreach($counsellors as $assign){			 
						array_push($newbucket, $assign);
						}
				}
			}
			
			$i = 0;
			$bucketCount = count($newbucket);
			foreach($newbucket as $key=>$val){
			    
			    $courseassignment= 	Courseassignment::where('counsellors',$val)->where('status',1)->first();			
			 
			    
			if($bucketCount<=$bucketIndex || $bucketIndex==0){
			$bucketIndex = 0;
			}
			if($bucketIndex==$i){
			  
	 
			$leadForwards= LeadForward::findOrFail($leadForward->id);
		    $leadForwards->forward_to = $val;
			$leadForwards->save();

			$lead= Lead::findOrFail($lead->id);
		    $lead->created_by = $val;
			 
			if($lead->save()){
				$followUp =LeadFollowUp::where('lead_id',$lead->id)->delete();
				
				
			$user = User::findOrFail($leadForward->created_by);
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $val;
		    //$followUp->remark = $user->name." has forwarded lead to you because candidate wants training on ".$lead->course_name;
		    
		    $followUp->remark = "";
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 
			}
			
			$kw = Course::find($lead_course_id);
			$kw->bucket = $i+1;
			$kw->save();
			 

			}
			
			
			$i++;
		 
            }

		 
			 }else{
				 
				 
			$catCourse = Course::findOrFail($lead_course_id);	
			$bucketIndex = $catCourse->bucketinter;				

			$max = $mCount = 15;
			$i=0;
			$totalClients = count($absentassigncourse);
			$absentbuckets = [];
			$newbucket = [];
			$newbucketmerger = [];
			foreach($absentassigncourse as $counsellor){
		 	$absentcounsellors= AbsentCourseAssignment::where('counsellors',$counsellor->counsellors)->get();
			foreach($absentcounsellors as $absentcounsellor){
			if($mCount == 0){
			$j = $i;
			$absentbuckets[++$j] = $absentbuckets[$i++];
			//$buckets[$j]['absent_assign_dom_course'] = [];
			$mCount = $max-(count($absentbuckets[$j],1)-1);
			}
			if(in_array($lead_course_id,unserialize($absentcounsellor->absent_assign_dom_course))){
			$absentbuckets[$i]['absent_assign_dom_course'][] = $absentcounsellor->tocounsellor;
			}  
			if(in_array($lead_course_id,unserialize($absentcounsellor->absent_assign_int_course))){
			$absentbuckets[$i]['absent_assign_int_course'][] = $absentcounsellor->tocounsellor;
			}

			--$mCount;
			}
			}
			$j=0;
			foreach($absentbuckets as $bucket){
				foreach($bucket as $position=>$counsellors){
						foreach($counsellors as $assign){			 
						array_push($newbucket, $assign);
						}
				}
			}
			
			$i = 0;
			$bucketCount = count($newbucket);
			foreach($newbucket as $key=>$val){
			$courseassignment= 	Courseassignment::where('counsellors',$val)->where('status',1)->first();			
		 
			    
			if($bucketCount<=$bucketIndex || $bucketIndex==0){
			$bucketIndex = 0;
			}
			if($bucketIndex==$i){
				 $leadForwards= LeadForward::findOrFail($leadForward->id);
		    $leadForwards->forward_to = $val;
			$leadForwards->save();

			$lead= Lead::findOrFail($lead->id);
		    $lead->created_by = $val;
			 
			if($lead->save()){
				
				$followUp =LeadFollowUp::where('lead_id',$lead->id)->delete();
				
			$user = User::findOrFail($leadForward->created_by);
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;							
			$followUp->followby = $val;
			//$followUp->remark =$user->name." has forwarded lead to you because candidate wants training on ".$lead->course_name;
			$followUp->remark ="";
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $lead->id;				 
			$followUp->save(); 
			}
			 
			
			$kw = Course::find($lead_course_id);
			$kw->bucketinter = $i+1;
			$kw->save();
			}
			 
			$i++;
			}
        }
			 }		 
			 
		 }
			 
}


// SLUG GENERATOR FOR CLIENTS
// **************************
function generate_slug($slug=null){
	if(is_null($slug)){
		return null;
	}
	$slug = explode(" ",$slug);
	$slug = array_map('trim',$slug);
	$slug = array_map('remove_splchars',$slug);
	$slug = array_map('strtolower',$slug);
	$slug = implode("-",$slug);
	return $slug;
}

// SPECIAL CHARACTERS REMOVER
// **************************
function remove_splchars($str){
	return preg_replace("/[^a-zA-Z0-9]/", "", $str);
}

// FOLDER STRUCTURE GENERATOR
// **************************
function getFolderStructure(){
	try{
		$partial_str = '';
		$day = date('j');
		$week = '';
		if($day<11){
			$week = 'week_1';
		}
		else if($day>=11&&$day<21){
			$week = 'week_2';
		}
		else if($day>=21){
			$week = 'week_3';
		}
		$partial_str = 'uploads/images/'.date('Y').'/'.date('m').'/'.$week;
		$structure = public_path($partial_str);
		if(file_exists($structure)){
			return $partial_str;
		}else{
			if(mkdir($structure, 0755, true)){
				return $partial_str;
			}else{
				throw new Exception("Folder structure not found.\nUnable to create folder structure.");
			}
		}
	}catch(Exception $e){
		return $e->getMessage();
	}
}

// SUBSTRING GETTER
// ****************
function getAddress($arr,$len){
	$response = [];
	$response['fullstr'] = $response['substr'] = '';
	$response['isfullstr'] = $response['issubstr'] = 0;
	$response['ispositiveresponse'] = 0;
	$str = '';
	if(count($arr)>0){
		$str = implode(", ",$arr);
		$response['ispositiveresponse'] = 1;
		if(strlen($str)>$len){
			$response['fullstr'] = $str;
			$response['isfullstr'] = 1;
			$response['substr'] = substr($str,0,($len-1))."...";
			$response['issubstr'] = 1;
		}else{
			$response['fullstr'] = $str;
			$response['isfullstr'] = 1;
		}
	}
	// returning response object not an array
	return json_decode(json_encode($response), FALSE);
}

// STAR CODED STRING GETTER
// ************************
function getStarCodedStr($str,$type=NULL){
	if(empty($str))
		return NULL;
	if($type=='number'){
		$strArr = str_split($str,1);
		$strLen = count($strArr);
		$strToReturn = [];
		for($i=0;$i<$strLen;++$i){
			if($i<2){
				$strToReturn[] = $strArr[$i];
			}
			else if($i>=2 && $i<=($strLen-3)){
				$strToReturn[] = '*';
			}
			else if($i>($strLen-3)){
				$strToReturn[] = $strArr[$i];
			}
		}
		$strToReturn = implode($strToReturn);
	}
	else if($type=='email'){
		$strExpl = explode('@',$str);
		$strArr = str_split($strExpl[0],1);
		$strLen = count($strArr);
		$strToReturn = [];
		for($i=0;$i<$strLen;++$i){
			if($i<1){
				$strToReturn[] = $strArr[$i];
			}
			else if($i>=1 && $i<=($strLen-2)){
				$strToReturn[] = '*';
			}
			else if($i>($strLen-2)){
				$strToReturn[] = $strArr[$i];
			}
		}
		$strToReturn = implode($strToReturn);
		if(preg_match("/@/", $str)){
			$strToReturn .= "@".$strExpl[1];	
		}
	}
	return $strToReturn;
}

// RETURN STATE/UNION TERROTERIES LIST
// ***********************************
function getStates(){
	return ['-- Select State --','Andhra Pradesh','Arunachal Pradesh','Assam','Andaman and Nicobar Islands','Bihar','Chandigarh','Chhattisgarh','Dadra and Nagar Haveli','Daman and Diu','Delhi (NCR)','Goa','Gujarat','Haryana','Himachal Pradesh','Jammu & Kashmir','Jharkhand','Karnataka','Kerala','Lakshadweep','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Orissa','Pondicherry','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal'];
}

// RETURN CLIENTS TYPE
// *******************
function getClientsType(){
	return [
		'general'=>'General',
		'lead_based'=>'Lead Based',
		'yearly_subscription'=>'Yearly Subscription',
		'free_subscription'=>'Free Subscription (1 Month)'
	];
}

// RETURN CAPABILITIES ARRAY
// *************************
function getCapabilities(){
	return ['all_lead_remark'=>'All Lead Remark',
			'view_lead'=>'View Lead',
			'add_lead_follow_up'=>'Add Lead Follow Up',
			'edit_lead'=>'Edit Lead',
			'delete_lead'=>'Delete Lead',
			'add_lead'=>'Add Lead',
			'restore_lead'=>'Restore Lead',
			'all_demo_remark'=>'All Demo Remark',
			'view_demo'=>'View Demo',
			'add_demo_follow_up'=>'Add Demo Follow Up',
			'edit_demo'=>'Edit Demo',
			'delete_demo'=>'Delete Demo',
			'add_demo'=>'Add Demo',
			'restore_demo'=>'Restore Demo',
			'view_source'=>'View Source',
			'edit_source'=>'Edit Source',
			'add_source'=>'Add Source',
			'delete_source'=>'Delete Source',
			'view_course'=>'View Course',
			'edit_course'=>'Edit Course',
			'add_course'=>'Add Course',
			'delete_course'=>'Delete Course',
			'view_status'=>'View Status',
			'edit_status'=>'Edit Status',
			'add_status'=>'Add Status',
			'delete_status'=>'Delete Status',
			'view_message'=>'View Message',
			'edit_message'=>'Edit Message',
			'add_message'=>'Add Message',
			'delete_message'=>'Delete Message',
			'send_mail'=>'Send Mail',
		];
}