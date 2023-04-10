<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use Auth;
use Hash;
use Validator;
use DB;
use Session;
use Mail;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Input;
use Image; 
use App\Course;
use App\Courseassignment;
use App\User;
use App\Lead;
use App\Source;
use App\Helpers;
use App\Permission;
use App\Inquiry;
 

//use App\Capability;
class EnterLeadsController extends Controller
{
	
	public function __construct()
    {
        //$this->middleware('auth');
    }
    /**
     * Display a listing of the resource list of cms.
     *
     * @return \Illuminate\Http\Response
     */
  
 
	 
	 public function getForm(Request $request)
	 {
		 	 $catCourse= Course::get();
		 	  $leadSource= Source::where('status',1)->orderBy('name','asc')->get();
		   
		 return view('enter_leads.enterLeadform',['catCourse'=>$catCourse,'leadSource'=>$leadSource]);
		 	 
	 }
	 
	 public function enterleadSave(Request $request){
//echo "<pre>";print_r($_POST);die;


		if($request->ajax()){
 
		$mobile= ltrim($request->input('phone'), '0');	
		$mobile= trim($mobile);	
		$newmobile=  preg_replace('/\s+/', '', $mobile);
	
		$currentdate = date('Y-m-d');
        $checklead = Inquiry::where('mobile',$newmobile)->where('course_id',$request->input('course'))->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
       // echo "<pre>";print_r($checklead);die;
          if(!empty($checklead) && $checklead >0){
        
			   $validator = Validator::make($request->all(),[							
			//	'name' 	=> 'required|regex:/^[\pL\s\-]+$/u|min:3|max:32',					
			//	'email' 	=> 'required|regex:/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i',					
				'code' 	=> 'required|numeric',
			 	'source'=>'required',				
			 //	'message'=>'required',				 	 	
				'from' 	=> 'required',					
				'participant' => 'required',
				'course'=>'required|unique:croma_enquiries,course',
				'phone'=>'required|unique:croma_enquiries,mobile',
								
		 		
			]); 
          }else{
              
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
              
              
          }
			
			
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
		
	//	echo "<pre>";print_r($_POST);die;
		$inquiry = New Inquiry;		 
		$inquiry->name=$name;
		$inquiry->email= $request->input('email');
		$inquiry->participant= $request->input('participant');
		if($request->input('message')){
			$inquiry->comment= $request->input('message');
		}
    	$inquiry->source_id= $request->input('source');
		$soursename= Source::findOrFail($request->input('source'))->name;
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
	
			$coursename= Course::where('name', 'like', '%' .$request->input('course') . '%')->first();
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
		
		$inquiry->form= $request->input('from');	
		 		 
		$inquiry->category="send_enquiry_lead";	
		$inquiry->from_name=$request->input('from_name');	
		$inquiry->sub_category=$request->input('from_title');	
	 
		 
         $currentdate = date('Y-m-d');
        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
        
        $currentdate = date('Y-m-d');
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
        
        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
         
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
					return response()->json(['status'=>1,],200);
				}else{						
				 $inquiry->save();					
				leadassignCounsellor($lead_course_id,$type_lead,$inquiry);				
				return response()->json(['status'=>1,],200);				
				}
				
				
				}else{
			     	$inquiry->duplicate=2;		
					$inquiry->save();
					return response()->json(['status'=>1,],200);
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
            
 //echo "<pre>";print_r($inquiry);die;
            
		if($inquiry->save())
		{
		    
		    	
			 leadassignCounsellor($lead_course_id,$type_lead,$inquiry);
			
		 return response()->json(['status'=>1,],200);
		
		}else{
			return response()->json(['status'=>0,],200);
			
			
		}
        } 
		}
		 
	  
}



	/** Select subcategory wise show of course module onlu type 1
     * the specified resource fetch from subcuisine according to cuisine id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getCoursellorCourse(Request $request)
    {		 
		$cid = $request->input('cid'); 			
		$counsellorlist= [];
		$assigncourse = Courseassignment::get();	
		foreach($assigncourse as $counsellor){
		if(in_array($request->input('cid'),unserialize($counsellor->assign_dom_course))){
		$counsellorlist[] = $counsellor->counsellors;

		}
		 
		
		
		}
	 
			$html="";
			if(!empty($counsellorlist)){
			$html ='<table class="table table-striped table-bordered"><thead>
                        <tr>
                          <th>Name</th>                          
                          <th>Mobile</th>                          
                                                  
                        </tr>
                      </thead>';
			foreach($counsellorlist as $key=>$val){
			 
			$user = User::findOrFail($val);		
		 
			$html .='<tr>';	
			$html .='<td>'.$user->name.'</td>';				
			$html .='<td>'.$user->mobile.'</td>';				
			$html .='</tr>';					
			}
			$html .='</table>';				
			}
			
	 
		
		echo $html;
		 
    } 
 
 
 /** Select subcategory wise show of course module onlu type 1
     * the specified resource fetch from subcuisine according to cuisine id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getinternationalcourse(Request $request)
    {		 
		$cid = $request->input('cid'); 			
		$counsellorlist= [];
		$assigncourse = Courseassignment::get();	
		foreach($assigncourse as $counsellor){
		if(in_array($request->input('cid'),unserialize($counsellor->assign_int_course))){
		$counsellorlist[] = $counsellor->counsellors;

		}
		 
		
		
		} 
		
		 
			$html="";
			if(!empty($counsellorlist)){
			$html ='<table class="table table-striped table-bordered"><thead>
                        <tr>
                          <th>Name</th>                          
                          <th>Mobile</th>         
                        </tr>
                      </thead>';
			foreach($counsellorlist as $key=>$val){
			$user = User::findOrFail($val);		
		 	$html .='<tr>';	
			$html .='<td>'.$user->name.'</td>';				
			$html .='<td>'.$user->mobile.'</td>';				
			$html .='</tr>';					
			}
			$html .='</table>';				
			}
			
		echo $html;
		 
    } 
 
 	 public function getSocialForm(Request $request)
	 {
	    // echo "tetse";die;
		 $catCourse= Course::get();
		 $leadSource= Source::where('social',1)->orderBy('name','asc')->get();
		 return view('enter_leads.enterSocialLeadform',['catCourse'=>$catCourse,'leadSource'=>$leadSource]);
		 	 
	 }
 


}
