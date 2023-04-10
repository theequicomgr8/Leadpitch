<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
use Carbon\Carbon;
use Mail;
//models
use App\Feesmanage;
 
use App\Source;
use App\Course;
use App\Status;
 
use App\Message;
use App\Capability;
use App\FeesGetTrainer;
use App\Assigncourse;
use Excel;
use App\User;
use App\MailerTable;
use App\Feedback;
use App\SiteFeedback;
 
use App\CromaCourse;
use App\FeesCourse;
use App\CounsellorPaymentMode;
use App\EmploymentUser;
use Auth;
use Session;

class FeesManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
		 
	 
		$courses = FeesCourse::all();
		 
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

        return view('cm_fees.all_fees',['courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    } 
	
	
	 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
		 
		 
		 
		$courses = FeesCourse::all();
		 
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
		
		 
			 
        return view('cm_fees.add_fees',['courses'=>$courses,'statuses'=>$statuses,'users'=>$users]);
    }

    public function store(Request $request)
    {
		
			//echo $newmobile;
		//echo "<pre>";print_r($_POST);die;
        if($request->ajax()){			 
			  
			 	$fees_type =$request->input('fees_type');
				if($fees_type=='newfees'){
			$validator = Validator::make($request->all(),[
				'name'=>'required',
				'email'=>'email',
				'mobile'=>'required',	
				//'code'=>'required',	
				'fees'=>'required',				 
				'gst_status'=>'required',				 
				'total_gst'=>'required',				 
				'stud-to_be_paid'=>'required',				 
				'stud-fees_adjust'=>'required',				 
				'stud-final_fees'=>'required',				 
				'paid_amt'=>'required',				 
				'course'=>'required',				 
			]);
			 
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
				}else{
					
					
					$validator = Validator::make($request->all(),[
				'name'=>'required',
				'email'=>'email',
				'mobile'=>'required',							 
				'paid_amt'=>'required',				 
				'course'=>'required',				 
			]);
			 
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}	
					
					
					
					
					
					
					
					
				}
			
			 $feesmanage =  new Feesmanage;	
			 $feesmanage->fees_type = $request->input('fees_type');
			$feesmanage->name = $request->input('name');
			$feesmanage->email = $request->input('email');
			$mobile= trim($request->input('mobile'));	
			$newmobile=  preg_replace('/\s+/', '', $mobile);
			$feesmanage->mobile =$newmobile;
			$feesmanage->code = trim($request->input('code'));		 
			$feesmanage->course = $request->input('course');	 
			$feesmanage->fees = $request->input('fees');	 
			$feesmanage->gst_status = $request->input('gst_status');	 
			$feesmanage->total_gst = $request->input('total_gst');	 
			$feesmanage->total_fees = $request->input('stud-to_be_paid');						
			$feesmanage->adjust = $request->input('stud-fees_adjust');	 		 
			$feesmanage->to_be_paid = $request->input('stud-final_fees');			 
			$feesmanage->paid_amt = $request->input('paid_amt');			 
			$feesmanage->remarks = $request->input('counsellor_remark');
			$feesmanage->created_by =Auth::user()->id;
			 
		 
		 
			// echo "<pre>";print_r($lead);die;
			if($feesmanage->save()){				 
					return response()->json(['status'=>1,],200);		 
				 
			}else{
				return response()->json(['status'=>0,],200);
			}
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    { 
		$lead = Feesmanage::find($id);
		 
		$users = $courseCounsellors = [];
		  
		if(Auth::user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			
			$users = User::select('id','name')->get();
		}
		else{
			$users = User::select('id','name')->whereIn('id',$courseCounsellors)->get();
		}
		 
        return view('cm_leads.lead_update',['lead'=>$lead,'statuses'=>$statuses,'users'=>$users,'courseCounsellors'=>$courseCounsellors,'id'=>$id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  

 
			$validator = Validator::make($request->all(),[
				'name'=>'required',
				'email'=>'email',
				'mobile'=>'required|unique:croma_feesmanage,mobile',	
				'source'=>'required',
				'course'=>'required|unique:croma_feesmanage,course',
				//'status'=>'required',
			]);
			 
		 
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
		
		$lead = Feesmanage::find($id);
		$feesmanage->name = $request->input('name');
			$feesmanage->email = $request->input('email');
			$mobile= trim($request->input('mobile'));	
			$newmobile=  preg_replace('/\s+/', '', $mobile);
			$feesmanage->mobile =$newmobile;
			$feesmanage->code = trim($request->input('code'));		 
			$feesmanage->course = $request->input('course');	 
			$feesmanage->fees = $request->input('fees');	 
			$feesmanage->gst_status = $request->input('gst_status');	 
			$feesmanage->total_gst = $request->input('total_gst');	 
			$feesmanage->total_fees = $request->input('stud-to_be_paid');						
			$feesmanage->adjust = $request->input('stud-fees_adjust');	 		 
			$feesmanage->to_be_paid = $request->input('stud-final_fees');			 
			$feesmanage->paid_amt = $request->input('paid_amt');			 
			$feesmanage->remarks = $request->input('counsellor_remark');
			   
		
		if($lead->save()){
		return response()->json(['status'=>1,],200);		 
			 
		}else{
			return response()->json(['status'=>0,],200);		 		
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    { 
		$ids = $request->input('ids');		
		if(!empty($ids)){
		foreach($ids as $id){	
			$lead = Feesmanage::find($id);
			$lead->delete();			
			 
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Deleted Permanently successfully...'
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
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedAllFees(Request $request)
    {  
		if($request->ajax()){
			 
			
			$leads = DB::table('croma_feesmanage as fees');
			 
			  
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){		
				
			if($request->user()->current_user_can('manager')){
			    
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('fees.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('fees.created_by','=',$request->input('search.user'));
				}
								
				
			}else{
				 
				$leads = $leads->where('fees.created_by','=',$request->user()->id);
				}
				
				
			} else{
			
				if($request->input('search.user')!==''){
					$leads = $leads->where('fees.created_by','=',$request->input('search.user'));
				}
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('fees.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('fees.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('fees.mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('fees.course','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('fees.paid_amt','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('fees.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('fees.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			 $leads =$leads->orderBy('id','desc');
			//dd($courseList);
			$leads = $leads->paginate($request->input('length'));
		 
			//dd($leads->toSql());
			$users = User::orderBy('name','ASC')->get();
			$usr = [];
			foreach($users as $user){
				$usr[$user->id] = $user->name;
			}
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['recordCollection'] = [];
 //echo "<pre>";print_r($leads);die;
			foreach($leads as $lead){
				  
				  $course = DB::connection('mysql3')->table('wp_courses_details')->where('id',$lead->course)->first();		 	
				  
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						$lead->name,
						$lead->mobile,
						$lead->email,
						$course->course,						 
						$lead->to_be_paid,
						$lead->paid_amt,
						$lead->created_at
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			 
		}
    } 
	 
	  /**
     * Return model Lead.
     *
     * @param  int  $id
     * @return \app\Lead
     */
    public function searchfeesLeaddata(Request $request, $id)
    {
		$mobile = trim($id);
      // echo $mobile;die;
		$leads = DB::table('croma_leads as leads');
		$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
		$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');
		$leads = $leads->select('leads.*','courses.name as course_name','courses.id as course_id','users.name as owner_name');	
		$leads = $leads->where('leads.mobile','LIKE',$mobile);
		$leads = $leads->get();
		if($leads){
			return response()->json([
				"statusCode"=>1,
				"success"=>[
					"responseCode"=>200,
					"payload"=>$leads,
					"message"=>""
				]
			],200);			
		}else{
			return response()->json([
				"statusCode"=>0,
				"success"=>[
					"responseCode"=>200,
					"message"=>"Lead not found."
				]
			],200);				
		}
		 
    }
	  /**
     * Return model Lead.
     *
     * @param  int  $id
     * @return \app\Lead
     */
    public function searchfeesExperiensedata(Request $request, $id)
    {
		$mobile = trim($id);
      //echo $mobile;die;
		$leads = DB::connection('mysql3')->table('wp_associate_details as std');			 
		$leads = $leads->leftjoin('wp_users as user','std.counsellor','=','user.id');		 
		$leads = $leads->select('std.*','user.display_name as owner_name');	
		$leads = $leads->where('std.mobile','LIKE',$mobile);
		$leads = $leads->get();
		
		//echo "<pre>";print_r($leads);die;
		if($leads){
			return response()->json([
				"statusCode"=>1,
				"success"=>[
					"responseCode"=>200,
					"payload"=>$leads,
					"message"=>""
				]
			],200);			
		}else{
			return response()->json([
				"statusCode"=>0,
				"success"=>[
					"responseCode"=>200,
					"message"=>"Lead not found."
				]
			],200);				
		}
		 
    } 


	/**
     * Return model Lead.
     *
     * @param  int  $id
     * @return \app\Lead
     */
    public function searchFeesCertificateData(Request $request, $id)
    {
		$mobile = trim($id);
      //echo $mobile;die;
		$leads = DB::connection('mysql3')->table('wp_certification as std');			 
		$leads = $leads->leftjoin('wp_users as user','std.counsellor','=','user.id');		 
		$leads = $leads->select('std.*','user.display_name as owner_name');	
		$leads = $leads->where('std.mobile','LIKE',$mobile);
		$leads = $leads->get();
		
		//echo "<pre>";print_r($leads);die;
		if($leads){
			return response()->json([
				"statusCode"=>1,
				"success"=>[
					"responseCode"=>200,
					"payload"=>$leads,
					"message"=>""
				]
			],200);			
		}else{
			return response()->json([
				"statusCode"=>0,
				"success"=>[
					"responseCode"=>200,
					"message"=>"Lead not found."
				]
			],200);				
		}
		 
    }
	  /**
     * Return model Lead.
     *
     * @param  int  $id
     * @return \app\Lead
     */
    public function searchPendingFeesExperienceData(Request $request, $id)
    {
		$mobile = trim($id);
      //echo $mobile;die;
		$leads = DB::connection('mysql3')->table('wp_associate_details as std');			 
		$leads = $leads->leftjoin('wp_users as user','std.counsellor','=','user.id');		 
		$leads = $leads->select('std.*','user.display_name as owner_name');	
		$leads = $leads->where('std.mobile','LIKE',$mobile);
		$leads = $leads->get();
		
		//echo "<pre>";print_r($leads);die;
		if($leads){
			return response()->json([
				"statusCode"=>1,
				"success"=>[
					"responseCode"=>200,
					"payload"=>$leads,
					"message"=>""
				]
			],200);			
		}else{
			return response()->json([
				"statusCode"=>0,
				"success"=>[
					"responseCode"=>200,
					"message"=>"Lead not found."
				]
			],200);				
		}
		 
    } 
	
	/**
     * Return model Lead.
     *
     * @param  int  $id
     * @return \app\Lead
     */
    public function searchFeesPendingCertificateData(Request $request, $id)
    {
		$mobile = trim($id);
      //echo $mobile;die;
		$leads = DB::connection('mysql3')->table('wp_certification as std');			 
		$leads = $leads->leftjoin('wp_users as user','std.counsellor','=','user.id');		 
		$leads = $leads->select('std.*','user.display_name as owner_name');	
		$leads = $leads->where('std.mobile','LIKE',$mobile);
		$leads = $leads->get();
		
		//echo "<pre>";print_r($leads);die;
		if($leads){
			return response()->json([
				"statusCode"=>1,
				"success"=>[
					"responseCode"=>200,
					"payload"=>$leads,
					"message"=>""
				]
			],200);			
		}else{
			return response()->json([
				"statusCode"=>0,
				"success"=>[
					"responseCode"=>200,
					"message"=>"Lead not found."
				]
			],200);				
		}
		 
    } 
	
	
	  /**
     * Return model Lead.
     *
     * @param  int  $id
     * @return \app\Lead
     */
    public function searchfeesPendingdata(Request $request, $id)
    {
		$mobile = trim($id);
      // echo $mobile;die;
		$leads = DB::connection('mysql3')->table('wp_students_details as std');		 	 
		$leads = $leads->join('wp_courses_details as course','std.courses','=','course.id');		 
		$leads = $leads->join('wp_users as user','std.counsellor','=','user.id');		 
		$leads = $leads->select('std.*','course.course as course_name','course.id as course_id','user.display_name as owner_name');	
		$leads = $leads->where('std.phone','LIKE',$mobile);
		$leads = $leads->get();
		//echo "<pre>";print_r($leads);die;
		if($leads){
			return response()->json([
				"statusCode"=>1,
				"success"=>[
					"responseCode"=>200,
					"payload"=>$leads,
					"message"=>""
				]
			],200);			
		}else{
			return response()->json([
				"statusCode"=>0,
				"success"=>[
					"responseCode"=>200,
					"message"=>"Lead not found."
				]
			],200);				
		}
		 
    }
	
	
	
	
	
	
	
	public function counsellorPaymentModeIndex(){
 
		return view('cm_paymnet.index');
	}
	
	
	public function getcounsellorPaymentModePegination(Request $request)
	{
		   
		if($request->ajax()){		 
	 
        if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){		
        		$paymentMode = 	CounsellorPaymentMode::orderBy('id','ASC');		
        }else{	 
        $paymentMode = 	CounsellorPaymentMode::where('counsellor',$request->user()->id)->orderBy('id','ASC');		
        }	

		if($request->input('search.value')!==''){
				$paymentMode = $paymentMode->where(function($query) use($request){
					$query->orWhere('counsellor','LIKE','%'.$request->input('search.value').'%')				     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			$paymentMode = $paymentMode->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $paymentMode->total();
			$returnLeads['recordsFiltered'] = $paymentMode->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($paymentMode as $payment){				 
				$action="";
				$seperate="";
				$status="";			 					 
				 
				 
				  $ulhtml="<ul>";
				 if($payment->googlePay_emp){
					 $googlePayemp = EmploymentUser::where('id',$payment->googlePay_emp)->first();
					 $ulhtml .= "<li>".$googlePayemp->first_name.' '.$googlePayemp->last_name." | Google Pay | ".$payment->googlePay_mobile."</li>";
				 }
				 
				  if($payment->phonePay_emp){
					 $phonePayemp = EmploymentUser::where('id',$payment->phonePay_emp)->first();
					 $ulhtml .= "<li>".$phonePayemp->first_name.' '.$phonePayemp->last_name." | Phone Pay | ".$payment->phonePay_mobile. "</li>";
				 }

				 if($payment->paytm_emp){
					 $paytmemp = EmploymentUser::where('id',$payment->paytm_emp)->first();
					 $ulhtml .= "<li>".$paytmemp->first_name.' '.$paytmemp->last_name." | PayTm | ".$payment->paytm_mobile."</li>";
				 }
				  if($payment->UPI_emp){
					 $UPI_emp = EmploymentUser::where('id',$payment->UPI_emp)->first();
					 $ulhtml .= "<li>".$UPI_emp->first_name.' '.$UPI_emp->last_name." | UPI | ".$payment->upi."</li>";
				 }
				 
				  if($payment->googlePay_trainer){
					 $googlePaytrainer = FeesGetTrainer::where('id',$payment->googlePay_trainer)->first();
					 $ulhtml .= "<li>".$googlePaytrainer->name." | GooglePay | ".$payment->googlePay_mobile."</li>";
				 }
				 
				  if($payment->phonePay_trainer){
					 $phonePay_trainer = FeesGetTrainer::where('id',$payment->phonePay_trainer)->first();
					 $ulhtml .= "<li>".$phonePay_trainer->name." | PhonePay | ".$payment->phonePay_mobile."</li>";
				 }

				 if($payment->paytm_trainer){
					 $paytm_trainer = FeesGetTrainer::where('id',$payment->paytm_trainer)->first();
					 $ulhtml .= "<li>".$paytm_trainer->name." | Paytm | ".$payment->paytm_mobile."</li>";
				 }
				  if($payment->UPI_trainer){
					 $UPI_trainer = FeesGetTrainer::where('id',$payment->UPI_trainer)->first();
					 $ulhtml .= "<li>".$UPI_trainer->name." | UPI | ".$payment->upi."</li>";
				 }
				 
				 
				
				 
				  
			 
				
				 $ulhtml .="</ul>";
				
				
				$url ='<span contenteditable>'.('https://www.cromacampus.com/fees-pay').'/'.base64_encode($payment->id).'</span>';
				if($payment->counsellor){
					$user = User::where('id',$payment->counsellor)->first();
					$username = $user->name;
				}else{
					$username="";
				}
				 
					$data[] = [		 		 		 
						$username,							
						$url,						
					 					 						
						 				  
						 
					];
					$returnLeads['recordCollection'][] = $payment->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	
}
