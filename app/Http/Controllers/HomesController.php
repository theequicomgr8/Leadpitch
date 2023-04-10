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
use App\LeadForward;
use App\Demo;
use App\Source;
use App\Course;
use App\Status;
use App\LeadFollowUp;
use App\DemoFollowUp;
use App\Message;
use App\Capability;
use App\FeesGetTrainer;
use App\Inquiry;
use Excel;
use App\User;
use App\CromaCategory;
use App\MailerTable;
use App\Feedback;
use App\SiteFeedback;
use Auth;
use Session;
use App\AbsentCourseAssignment;
class HomesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::orderBy('name','asc')->get();
		$courses = Course::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->where('name','!=','Active')->where('name','!=','In Active')->get();
		}else{
		    	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
		    	$statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->orWhere('abgyan_follow_up',1)->get();
		    	}else{
		    	    
		    	   	$statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->get(); 
		    	    
		    	}
		}
		 $users='';
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			 			
			$capability = Capability::select('user_id')->get();	 		 
			 $user_list=[];
			foreach($capability as $manage){
				array_push($user_list,$manage->user_id);		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_list)->orderBy('name','ASC')->get();
		}else{
			//$users = User::select('id','name')->where('id',$request->user()->id)->orderBy('name','ASC')->get();
			$users = User::select('id','name')->orderBy('name','ASC')->get();
		}
		 
		
		if(Auth::user()->current_user_can('manager')){
			$capabilitys = Capability::select('user_id')->where('manager',$request->user()->id)->get();		
		    $user_lists=[$request->user()->id];
			foreach($capabilitys as $manage){
				array_push($user_lists,$manage->user_id);		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_lists)->orderBy('name','ASC')->get();
			
		} 
		if(Auth::user()->current_user_can('TL')){
			$capabilitys = Capability::select('user_id')->where('TL',$request->user()->id)->get();		
		    $user_lists=[$request->user()->id];
			foreach($capabilitys as $manage){
				array_push($user_lists,$manage->user_id);		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_lists)->orderBy('name','ASC')->get();
			
		}  
		
		//	$categorys =  CromaCategory::orderby('category','asc')->get();
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
		 

        return view('cm_leads.allfb_leads',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
    
    public function indexExpectedLead(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::orderBy('name','asc')->get();
		$courses = Course::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->get();
		}else{
		    
		    if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
		    	$statuses = Status::where('lead_filter',1)->orWhere('abgyan_follow_up',1)->get();
		    	}else{
		    	    
		    	   	$statuses = Status::where('lead_filter',1)->get(); 
		    	    
		    	}
		    
		}
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

        return view('cm_leads.expected_lead',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
	
	
	public function indexExpectedLeadDemo(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::orderBy('name','asc')->get();
		$courses = Course::all();
			if(Auth::user()->current_user_can('abgyan_follow_up') ){
	    	$statuses = Status::where('abgyan_follow_up',1)->where('name','!=','Active')->where('name','!=','In Active')->get();
			}else{
			   
			     if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
		    	 $statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->orWhere('abgyan_follow_up',1)->get();
		    	}else{
		    	   	 $statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->get();
		    	    
		    	}
			}
		
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

        return view('cm_leads.expected_lead_demo',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
	
	
	public function expectedNewBatchLead(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::orderBy('name','asc')->get();
		$courses = Course::all();
			if(Auth::user()->current_user_can('abgyan_follow_up') ){
	    	$statuses = Status::where('abgyan_follow_up',1)->get();
			}else{
			     
			    	
			    	 if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
		    	 $statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->orWhere('abgyan_follow_up',1)->get();
		    	}else{
		    	   	 $statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->get();
		    	    
		    	}
			    
			}
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

        return view('cm_leads.expected_new_batch_lead',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
	
	
	public function indexNotInterested(Request $request)
    {  
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::orderBy('name','asc')->get();
		$courses = Course::all();
		//$statuses = Status::where('lead_filter',1)->where('id','Not Interested')->where('name','Location Issue')->get();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->where('id',4)->orWhere('id',8)->get();
		}else{
		    	 
                
                if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
                $statuses = Status::where('lead_filter',1)->orWhere('abgyan_follow_up',1)->where('id',4)->orWhere('id',8)->get();
                }else{
                $statuses = Status::where('lead_filter',1)->where('id',4)->orWhere('id',8)->get();
                
                }
		    
		}
	 //echo "<pre>";print_r($statuses);
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
		    
		$capability = Capability::select('user_id')->get();	 		 
			 $user_list=[];
			foreach($capability as $manage){
				array_push($user_list,$manage->user_id);		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_list)->get();
		}else{
			$users = User::select('id','name')->where('id',$request->user()->id)->get();
		}
		 
		if(Auth::user()->current_user_can('manager')){
		    $capabilitys = Capability::select('user_id')->where('manager',$request->user()->id)->get();		
		    $userLists=[$request->user()->id];
			foreach($capabilitys as $manage){
				array_push($userLists,$manage->user_id);		 
			}
			 $users = User::select('id','name')->whereIn('id',$userLists)->orderBy('name','ASC')->get();
		} 

		if(Auth::user()->current_user_can('TL')){
		    $capabilitys = Capability::select('user_id')->where('TL',$request->user()->id)->get();		
		    $userLists=[$request->user()->id];
			foreach($capabilitys as $manage){
				array_push($userLists,$manage->user_id);		 
			}
			 $users = User::select('id','name')->whereIn('id',$userLists)->orderBy('name','ASC')->get();
		} 
		 
		
		

		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
		
	 
        return view('cm_leads.not_interested_leads',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$message = Message::where('name','LIKE','%Welcome%')->first();
	 
		 
		$sources = Source::orderBy('name','asc')->get();
		$courses = Course::all();
		$statuses = Status::all();
		
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
			 
        return view('cm_leads.add_lead_form',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'message'=>$message,'users'=>$users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
				 $democheck = Demo::where('mobile',trim($newmobile))->where('course',$request->input('course'))->orderBy('id','desc')->get();				 
			 if(!empty($democheck) && count($democheck)>0){				 
				 foreach($democheck as $checkdemo){					  
					  if($checkdemo->status !=4 && $checkdemo->deleted_at =='' ){				  
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
			 }
			 
			 
			 
	    	if(!empty($check) && $check>0){
			$validator = Validator::make($request->all(),[
				'name'=>'required',
				'email'=>'email',
				'mobile'=>'required|unique:croma_leads,mobile',	
				'source'=>'required',
				'course'=>'required|unique:croma_leads,course',
				 
			]);
			}else{
				
				$validator = Validator::make($request->all(),[
				'name'=>'required',
				'email'=>'email',
				'mobile'=>'required',					 
				'source'=>'required',
				'course'=>'required',
				 
			]);
				
			}
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				$errors = [];
				foreach($errorsBag as $error){
					$errors[] = implode("<br/>",$error);
				}
				$errors = implode("<br/>",$errors);
				return response()->json(['status'=>0,'errors'=>$errors],200);
			}
			
			 $lead =  new lead;			
			$lead->name = $request->input('name');
			$lead->email = $request->input('email');
			$lead->code = trim($request->input('stud-code'));
            $mobile= ltrim($request->input('mobile'), '0');	
	    	$mobile= trim($mobile);	
            $newmobile=  preg_replace('/\s+/', '', $mobile);
            $lead->mobile =$newmobile;	
			$lead->source = $request->input('source');
			$lead->source_name = ($request->has('source'))?Source::find($request->input('source'))->name:"";
			$lead->course = $request->input('course');
			$lead->course_name = ($request->has('course'))?Course::find($request->input('course'))->name:"";
			//$lead->status = $request->input('status');
		
			$lead->sub_courses = $request->input('sub-course');
			$lead->remarks = $request->input('remark');
			$lead->created_by = $request->input('user_id');
			$lead->status = Status::where('name','LIKE','New Lead')->first()->id;
			$lead->status_name = 'New Lead';
			//$lead->created_by = $request->user()->id;
			$user = User::where('id',$lead->created_by)->first();
			 
			if($lead->save()){
				$followUp = new LeadFollowUp;
				$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;
				//$followUp->remark = $lead->remarks;
				$followUp->followby = $lead->created_by;
				$followUp->remark = $request->input('counsellor_remark');
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->lead_id = $lead->id;
				 
				if($followUp->save()){
					if('no'!=$request->input('message')){
						$message = Message::find($request->input('message'));
						$msg = $message->message;
						$msg = preg_replace('/{{name}}/i',$request->input('name'),$msg);			
						$msg = preg_replace('/{{course}}/i',$lead->course_name,$msg);
						$msg = preg_replace('/{{counsellor}}/i',(isset($user->name))?$user->name:"",$msg);
						$msg = preg_replace('/{{mobile}}/i',(isset($user->mobile))?$user->mobile:"",$msg);
						$msg = preg_replace('/\\r/i','%0D',$msg);
						$msg = preg_replace('/\\n/i','%0A',$msg);
						$tempid='1707161786775524106';
						//sendSMS($request->input('mobile'),$msg,$tempid);
					}
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
		$lead = Lead::find($id);
		$sources = Source::all();
		$courses = Course::all();
		$statuses = Status::all();
		$users = $courseCounsellors = [];
		//$course = Course::findOrFail($lead->course);
	 
	/*	if($course){
			if(!is_null($course->counsellors)){
				$courseCounsellors = unserialize($course->counsellors);
			}	
		}
		if(Auth::user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			
			$users = User::select('id','name')->get();
		}
		else{
			$users = User::select('id','name')->whereIn('id',$courseCounsellors)->get();
		}
		 */
		 $users = User::select('id','name')->get();
        return view('cm_leads.fblead_update',['lead'=>$lead,'sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'users'=>$users,'courseCounsellors'=>$courseCounsellors,'id'=>$id]);
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
		 
			$check = Lead::where('mobile',trim($request->input('mobile')))->where('course',$request->input('course'))->where('id','<>',$id)->first();
		 
			if(count($check)>0){
			$validator = Validator::make($request->all(),[
				'name'=>'required',
				'email'=>'email',
				'mobile'=>'required|unique:croma_leads,mobile',	
				'source'=>'required',
				'course'=>'required|unique:croma_leads,course',
				//'status'=>'required',
			]);
			}else{
					$validator = Validator::make($request->all(),[
						'name'=>'required',
						'email'=>'email',
						'mobile'=>'required',
						'source'=>'required',
						'course'=>'required',
						'owner'=>'required',
					]);
		
			}
		if($validator->fails()){
            return redirect('lead/update/'.$id)
                        ->withErrors($validator)
                        ->withInput();
		}
		
		$lead = Lead::find($id);
		$lead->name = $request->input('name');
		$lead->email = $request->input('email');
		$lead->code = trim($request->input('stud-code'));
    	$mobile= trim($request->input('mobile'));	
		$newmobile=  preg_replace('/\s+/', '', $mobile);
		$lead->mobile =$newmobile;
		$lead->source = $request->input('source');
		$lead->source_name = Source::find($request->input('source'))->name;
		$lead->course = $request->input('course');
		$lead->course_name = Course::find($request->input('course'))->name;
		// $lead->status = $request->input('status');
		// $lead->status_name = Status::find($request->input('status'))->name;
		$lead->sub_courses = $request->input('sub-course');
		$lead->remarks = $request->input('remark');
		$lead->created_by = $request->input('owner');
		
		if($lead->save()){
			$request->session()->flash('alert-success', 'Lead successfully updated !!');
			//return redirect(url('/lead/all-leads'));
			return redirect(url('/dashboard/facbook-lead'));
		}else{
			$request->session()->flash('alert-danger', 'Lead not updated !!');
			//return redirect(url('/lead/update/'.$id));
			return redirect(url('/fblead/update/'.$id));
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('soft_delete_lead'))){
			try{
				 
				$lead = Lead::findorFail($id);	
 				
			//	$lead->delete();
				if($lead){					 
					DB::table('croma_leads')->where('id',$lead->id)->update(array('deleted_by'=>$request->user()->id,'deleted_at'=>date('Y-m-d H:i:s')));
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Lead not found'],200);
			}
		}else{
			return "Unh Cheatin`";
		}
    }
	
    /**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedLeads(Request $request)
    {  
		if($request->ajax()){
			 
		 
			$leads = DB::table('croma_leads as leads');
			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id','left');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id','left');
		//	$leads =$leads->join('cromag8l_web.croma_cat_course as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id','left');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";

            
            $this->demo3();
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			if($request->input('search.calldf')!==''){
				$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($request->input('search.calldf')))."'";
				
	        	$leads = $leads->orderBy('follow_up_date','ASC');
			}else{
					$leads = $leads->orderBy('created_at','desc');
				
			}
			
			if($request->input('search.calldt')!==''){
				$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($request->input('search.calldt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
		//	$leads = $leads->orderBy('leads.id','desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
			   
				$not_interested =1;
			}
			
		//echo $not_interested;
			$leads = $leads->where('leads.move_not_interested','=',$not_interested);
			
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){		
			
			if($request->user()->current_user_can('manager')){
			    
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}
			else if($request->user()->current_user_can('TL')){
			    
				$getID= [$request->user()->id]; 
				$tls = Capability::select('user_id')->where('TL',$request->user()->id)->get();	 
				foreach($tls as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}
			else{

				    if($request->input('search.user')==''){
				     //created_by
				     $leads = $leads->where('leads.facebook_owner','=',$request->user()->id);
				    }
				    if($request->input('search.user')!==''){
    					$leads = $leads->where('leads.facebook_owner','=',$request->input('search.user'));
    				}
    				
				}
				
				
			} else{
			
				// if($request->input('search.user')!==''){
				// 	$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				// }
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.facebook_owner','=',$request->input('search.user'));
				}
			}
			
			//new start 9 feb 2023 (default apna data show ho but agar filter me jiska bhi search karna ho kar sake)
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && $request->input('search.user')==''){
			    $leads = $leads->where('leads.facebook_owner','=',$request->user()->id);
			}
			//interested data not display
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator')){
			    $leads = $leads->where('leads.fb_lead_status','!=',3);
			}
			
			
			
			
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			
			
			
			
			if(!empty($request->input('search.status'))){
				$temp=[];
				foreach($request->input('search.status') as $value){
					$temp[$value]=$value;
				}
				$leads = $leads->whereIn('leads.fb_lead_status',$temp);
			}
			
			
			
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
			
	    	if($request->input('search.category')!==''){
				$categoryid = $request->input('search.category');		
				//echo	$categoryid;die;	 
				$courseslist = Course::where('category',$categoryid)->get();
				foreach($courseslist as $coursesid){
					$courseCategoryList[] = $coursesid->id;
				}				 
				$leads = $leads->whereIn('leads.course',$courseCategoryList);
			}
			
			$leads=$leads->where('fb_lead','1');
			$leads=$leads->orderBy('leads.created_at','DESC');
			
			//not show not interested data for user but show only admin
			if($request->user()->current_user_can('user') && $request->input('search.status')[0]!='1'){
			   $leads=$leads->whereNotIn('leads.fb_lead_status',['4']);
			}
            
			//dd($courseList);
			$leads = $leads->paginate($request->input('length'));
		 
			//dd($leads->toSql());
			$users = User::orderBy('name','ASC')->get();
			$usr = [];
			$sourceid = [];
			foreach($users as $user){
				$usr[$user->id] = $user->name;
			}
			$source =Source::where('social',1)->get();
			foreach($source as $sour){			
				array_push($sourceid,$sour->id);
			}
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['recordCollection'] = [];
			//echo "<pre>";print_r($leads);die;
			
			foreach($leads as $lead){
			 
					$action = '';
					$separator = '';
					$owners = '<span style="">'.$lead->owner_name.'</span>';//color:red
				 
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    				// 	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
    				// 		$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    				// 		$separator = ' | ';
    				// 	}
    				
    				
    				    if($lead->facbook_unassign=='1'){
					 		$color="red";
					 		$display='none';
					 	}
					 	else
					 	{
					 		$color="";
					 		$display='';
					 	}
					 	
					 	
					 	
    					//if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getfollowUps('.$lead->id.')" set-id="'.$lead->id.'" set-course="'.$lead->course.'"  class="getid" title="followUp" style="display:'.$display.';"><i class="fa fa-eye" aria-hidden="true"></i> | </a>  ';
    						// $separator = ' | ';
    					//}
    					
    					
    				// 	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    				// 		$action .= $separator.'<a href="javascript:leadFBController.getExpectFollowUps('.$lead->id.')" title="Expect Demo FollowUp"  data-toggle="popover" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i class="fa fa-meetup" aria-hidden="true"></i></a>';
    				// 		$separator = ' | ';
							 
    				// 	}
    					
    					//if(Auth::user()->current_user_can('edit_lead')){
    						$action .= $separator.'<a href="/fblead/update/'.$lead->id.'" title="Edit" style="display:'.$display.';"><i class="fa fa-pencil" aria-hidden="true"></i> |</a>';
    						//$separator = ' | ';
    					//}
					
				// 		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('view_lead')){
				// 	$action .= $separator.'<a href="javascript:leadFBController.leadForwardForm('.$lead->id.')" title="Lead Assign"><i class="fa fa-send" aria-hidden="true"></i></a>';
				// 		}
				
				       
    						$action .= $separator.'<a href="calllog/'.$lead->mobile.'" class="calllog" call-attr="'.$lead->mobile.'" target="_blank" style="display:'.$display.';"><i class="fa fa-headphones" aria-hidden="true"></i></a>';
    					
    					
    					
					 }
					
					 
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = LeadFollowUp::where('lead_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=15){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					
					if($lead->course_name){
				     $coursename = '<span title="'.$lead->course_name.'">'.substr($lead->course_name,0,25).'</span>';
				     
				 }else{
				      $coursename ="";
				     
				 }
				 
				 
				 $sourcename="";
				 if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('manager') ||  Auth::user()->current_user_can('administrator')){
					$sourcename= $lead->source_name;
					}else{
						
				     if(in_array($lead->source,$sourceid)){
							$sourcename =$lead->source_name;//"Website";							
						}else{							
							$sourcename= $lead->source_name;
						}
						
					}
					
					if($coursename=='')
					{
						$coursename=$lead->fbcourses;
					}
					else
					{
						$coursename=$coursename;
					}
					
					
					if(empty($lead->fb_lead_status)){
					    //dd('dsdsd');
						$leadstatus=$lead->status_name.$npupMark;
					}
					else{
						$statusdata=DB::table('croma_status')->where('id',$lead->fb_lead_status)->first();
						$leadstatus=$statusdata->name.$npupMark;
					}
					//$call="<span data-mobile='$lead->mobile' class='call' style='cursor: pointer;'><i class='fa fa-phone' aria-hidden='true'></i></span>";
					$getowner=DB::table('croma_users')->where('id',$lead->facebook_owner)->first();
					if(!empty($getowner)){
						$FacebookOwnerName=$getowner->name;
					}
					else{
						$FacebookOwnerName='';
					}
					
					if(empty($lead->fb_created_at)){
					    $entry_date="";//(new Carbon($lead->created_at))->format('d-m-y h:i:s');
					}else{
					    $entry_date=$lead->fb_created_at;
					}
					
					
					if(Auth::user()->email=='suman@gmail.com')
					{
					    $mob="(".$lead->mobile.")";
					}
					else
					{
					    $mob="";
					}
					
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						$lead->name.$mob,
					//	$lead->mobile,
						$sourcename,
						$coursename,
						//$lead->source_name,
						$FacebookOwnerName,//$owners,
						//$lead->sub_courses,
						$leadstatus,//$lead->status_name.$npupMark,
						$entry_date,//(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-y h:i A'),
						$action,
					];
					$returnLeads['recordCollection'][] = $lead->id;
				//}
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
	
	 /**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getNotInterestedPaginatedLeads(Request $request)
    {  
		if($request->ajax()){
			 
			
			$leads = DB::table('croma_leads as leads');
			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id','left');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id','left');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id','left');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*, m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('search.status')!==''){
				$statuses = $request->input('search.status');
				$i=0;
				foreach($statuses as $status){
					if(!$i){
						$rawQuery .= " AND (m1.status=".$status;
						$i=1;
					}else{
						$rawQuery .= " || m1.status=".$status;
					}
				}
				$rawQuery .= ")";
				//$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			if($request->input('search.calldf')!==''){
				$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($request->input('search.calldf')))."'";
				$leads = $leads->orderBy('follow_up_date','ASC');
			}else{
					$leads = $leads->orderBy('leads.id','desc');
				
			}
			
			if($request->input('search.calldt')!==''){
				$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($request->input('search.calldt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			
		
			//$leads = $leads->orderBy(DB::raw('(case when `fu`.`expected_date_time` is null then (select `fu`.`id`) else 0 end) desc, `fu`.`expected_date_time`')/*'leads.id'*/,'desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$not_interested = 1;
			 
		//	$leads = $leads->where('leads.move_not_interested','=',1);
			
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){		
				
			if($request->user()->current_user_can('manager')){
			    
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}else{
				 
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
				
				
			} else{
			
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
				 
			}
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
 
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';
					$owners = '<span style="">'.$lead->owner_name.'</span>';//color:red
					
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
    						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('edit_lead')){
    						$action .= $separator.'<a href="/lead/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
					
					 }
					
					 
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = LeadFollowUp::where('lead_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=15){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					
                    if($lead->course_name){
                    $coursename = '<span title="'.$lead->course_name.'">'.substr($lead->course_name,0,25).'</span>';
                    
                    }else{
                    $coursename ="";
                    
                    }
					
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						$lead->name,
						//$lead->mobile,
						$lead->source_name,
						$coursename,
						$owners,
						//$lead->sub_courses,
						$lead->status_name.$npupMark,
						(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-y h:i A'),
						 
						$action
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			 
		}
    } 
	
	/**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getexpectedPaginatedLeads(Request $request)
    {  
		if($request->ajax()){
			 
			
			$leads = DB::table('croma_leads as leads');
			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id','left');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id','left');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id','left');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('search.status')!==''){
				$statuses = $request->input('search.status');
				$i=0;
				foreach($statuses as $status){
					if(!$i){
						$rawQuery .= " AND (m1.status=".$status;
						$i=1;
					}else{
						$rawQuery .= " || m1.status=".$status;
					}
				}
				$rawQuery .= ")";
				//$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			$leads = $leads->orderBy('leads.id','desc');
			//$leads = $leads->orderBy(DB::raw('(case when `fu`.`expected_date_time` is null then (select `fu`.`id`) else 0 end) desc, `fu`.`expected_date_time`')/*'leads.id'*/,'desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$not_interested = 2;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			$leads = $leads->where('leads.move_not_interested','=',$not_interested);
			
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){		
				
			if($request->user()->current_user_can('manager')){
			    
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}else{
				 
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
				
				
			} else{
			
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
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
 
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';
					$owners = '<span style="">'.$lead->owner_name.'</span>';//color:red
					
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
    						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('edit_lead')){
    						$action .= $separator.'<a href="/lead/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
					
					 }
					
					 
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = LeadFollowUp::where('lead_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=15){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					  if($lead->course_name){
                    $coursename = '<span title="'.$lead->course_name.'">'.substr($lead->course_name,0,25).'</span>';
                    
                    }else{
                    $coursename ="";
                    
                    }
					
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						$lead->name,
					//	$lead->mobile,
						$lead->source_name,
						$coursename,
						$owners,
						//$lead->sub_courses,
						$lead->status_name.$npupMark,
						(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-y h:i A'),
						 
						$action
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			 
		}
    }
    
    
    
	
	/**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getexpectedPaginatedLeadsDemo(Request $request)
    {  
		if($request->ajax()){
			 
			 
			$leads = DB::table('croma_leads as leads');
			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('search.status')!==''){
				$statuses = $request->input('search.status');
				$i=0;
				foreach($statuses as $status){
					if(!$i){
						$rawQuery .= " AND (m1.status=".$status;
						$i=1;
					}else{
						$rawQuery .= " || m1.status=".$status;
					}
				}
				$rawQuery .= ")";
				//$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			
			if($request->input('search.calldf')!==''){
				$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($request->input('search.calldf')))."'";
				$leads = $leads->orderBy('follow_up_date','ASC');
			}else{
					$leads = $leads->orderBy('leads.id','desc');
				
			}
			
			if($request->input('search.calldt')!==''){
				$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($request->input('search.calldt')))."'";
			}
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			$leads = $leads->orderBy('leads.id','desc');
			//$leads = $leads->orderBy(DB::raw('(case when `fu`.`expected_date_time` is null then (select `fu`.`id`) else 0 end) desc, `fu`.`expected_date_time`')/*'leads.id'*/,'desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$not_interested = 3;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			$leads = $leads->where('leads.move_not_interested','=',$not_interested);
			
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){		
				
			if($request->user()->current_user_can('manager')){
			    
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}else{
				 
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
				
				
			} else{
			
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
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
 
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';
					$owners = '<span style="">'.$lead->owner_name.'</span>';//color:red
					
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:void(0)" title="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'"  ><i aria-hidden="true" class="fa fa-comment"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
    					
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getExpectFollowUps('.$lead->id.')" title="Expect Demo FollowUp"><i class="fa fa-meetup" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('edit_lead')){
    						$action .= $separator.'<a href="/lead/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
					
					 }
					 if($lead->meetingurl){
			        	$meeting= '<p contenteditable>'.$lead->meetingurl.'</p>';	
					 }else{
					     
					     $meeting="";
					     
					 }
					 
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = LeadFollowUp::where('lead_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=15){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					 if($lead->course_name){
                    $coursename = '<span title="'.$lead->course_name.'">'.substr($lead->course_name,0,25).'</span>';
                    
                    }else{
                    $coursename ="";
                    }
					
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
				    	$lead->name,						 
						$coursename,
						$owners,
						$lead->trainer,
						$lead->coordinator,
						$lead->status_name.$npupMark,
				    	((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),			
						$meeting,		
						($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-y h:i A'),
						 
						$action
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			 
		}
    }
	
	
	/**
     * Get paginated New Batch leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getexpectednewbatchdLeads(Request $request)
    {  
		if($request->ajax()){
			 
			
			$leads = DB::table('croma_leads as leads');
			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('search.status')!==''){
				$statuses = $request->input('search.status');
				$i=0;
				foreach($statuses as $status){
					if(!$i){
						$rawQuery .= " AND (m1.status=".$status;
						$i=1;
					}else{
						$rawQuery .= " || m1.status=".$status;
					}
				}
				$rawQuery .= ")";
				//$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			$leads = $leads->orderBy('leads.id','desc');
			//$leads = $leads->orderBy(DB::raw('(case when `fu`.`expected_date_time` is null then (select `fu`.`id`) else 0 end) desc, `fu`.`expected_date_time`')/*'leads.id'*/,'desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$not_interested = 3; //new batch only 3 expected lead 2
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			$leads = $leads->where('leads.move_not_interested','=',$not_interested);
			
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){		
				
			if($request->user()->current_user_can('manager')){
			    
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}else{
				 
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
				
				
			} else{
			
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
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
 
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';
					$owners = '<span style="">'.$lead->owner_name.'</span>';//color:red
					
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
    						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('edit_lead')){
    						$action .= $separator.'<a href="/lead/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
					
					 }
					
					 
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = LeadFollowUp::where('lead_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=15){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					 if($lead->course_name){
                    $coursename = '<span title="'.$lead->course_name.'">'.substr($lead->course_name,0,25).'</span>';
                    }else{
                    $coursename ="";
                    }
					
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						$lead->name,
						//$lead->mobile,
						$lead->source_name,
						$coursename,
						$owners,
						//$lead->sub_courses,
						$lead->status_name.$npupMark,
						(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-y h:i A'),
						 
						$action
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
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
    public function getLeadsExcel(Request $request)
    {  	 
	
		//if($request->ajax()){
			$leads = DB::table('croma_leads as leads');
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
			

			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			 
			/*if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}*/
			
			

			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			/*if($request->input('search.calldf')!==''){
				$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($request->input('search.calldf')))."'";
				
	        	$leads = $leads->orderBy('follow_up_date','ASC');
			}else{
					$leads = $leads->orderBy('leads.created_at','desc');
			}
			
			if($request->input('search.calldt')!==''){
				$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($request->input('search.calldt')))."'";
			}*/
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('leads.id','desc');
			$leads = $leads->whereNull('leads.deleted_at');
// 			$leads = $leads->where('leads.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
// 			$leads = $leads->where('leads.move_not_interested','=',$not_interested);
			
		
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') ){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}else{
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}
			if($request->input('search.status')!==''){
			    $leads = $leads->where('leads.fb_lead_status',$request->input('search.status'));
			}

            /*if(!empty($request->input('search.status'))){
				$temp=[];
				foreach($request->input('search.status') as $value){
					$temp[$value]=$value;
				}
				$leads = $leads->whereIn('leads.fb_lead_status',$temp);
			}*/

			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$leads = $leads->where('leads.course',$request->input('search.course'));
			}
			$leads = $leads->where('leads.deleted_by','0');
			$leads = $leads->where('leads.fb_lead','1');
			//$leads = $leads->paginate($request->input('length'));
			$leads = $leads->get();
			  
			$returnLeads = [];
			$data = [];
			 
			$returnLeads['recordCollection'] = [];
			foreach($leads as $lead){
				 
                $htmlremak='';
                $remarks =  LeadFollowUp::where('lead_id',$lead->id)->orderby('id','desc')->get();
                if($remarks){ 					 
                foreach($remarks as $remark){
                if($remark->remark){
                $htmlremak .=$remark->remark.'<br>';
                }
                }
                }
                
                    if($lead->fb_lead_status==3){
                        $status_name='Interested';
                    }else if($lead->fb_lead_status==1){
                        $status_name='New Lead';
                    }else if($lead->fb_lead_status==2){
                        $status_name='NPUP';
                    }else if($lead->fb_lead_status==4){
                        $status_name='Not Interested';
                    }else if($lead->fb_lead_status==5){
                        $status_name='Call Later';
                    }else if($lead->fb_lead_status==6){
                        $status_name='Switched Off';
                    }else if($lead->fb_lead_status==7){
                        $status_name='Not Reachable';
                    }else if($lead->fb_lead_status==8){
                        $status_name='Location Issue';
                    }else if($lead->fb_lead_status==27){
                        $status_name='Wrong number';
                    }
                    
                    
				 
					$arr[] = [
						"Name"=>$lead->name,
					//	"Mobile"=>$lead->code.''.str_replace(array( '(', ')','-','+' ), '',$lead->mobile),
						"Mobile"=>$lead->mobile,
						"Source"=>$lead->source_name,
						"Course"=>$lead->course_name,
						//"Sub. Technology"=>$lead->sub_courses,
						"Email"=>$lead->email,
						"Status"=>$status_name,//$lead->status_name,
						"Expected Date_Time"=>($lead->expected_date_time==NULL)?"":date_format(date_create($lead->expected_date_time),'m/d/Y'),
						"Remarks"=>$htmlremak,
						"Created"=>(new Carbon($lead->created_at))->format('m/d/Y'),
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('all_facebook_leads_'.date('Y-m-d H:i a'), function($excel) use($arr) {
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
    public function getleadsSalesTeamexcel(Request $request)
    {  	 
	//echo "sales";die;
	
	//echo "<pre>";print_r($request->input('search'));die;
		//if($request->ajax()){
			$leads = DB::table('croma_leads as leads');
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
			

			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			 
			if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			 

			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('leads.id','desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			/*$not_interested = 0;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			$leads = $leads->where('leads.move_not_interested','=',$not_interested);
			*/
			if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			/* if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') ){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}else{
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			} */
		/* 	if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			} */
			
			
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}


			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				//echo $request->input('search.course');die;
				$leads = $leads->where('leads.course',$request->input('search.course'));
				//$leads = $leads->groupby('leads.course');
			}
			$leads = $leads->groupby('leads.course');
			//$leads = $leads->paginate($request->input('length'));
			
			$leads = $leads->get();
			  
			$returnLeads = [];
			$data = [];
			 
			$returnLeads['recordCollection'] = [];
			//echo "<pre>";print_r($leads);
			$totremarks='';
			
			
			foreach($leads as $lead){
			    	$leadscount = DB::table('croma_leads as leads')->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'))->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'))->where('course',$lead->course);
				if($request->input('search.user')!==''){
					$leadscount = $leadscount->where('leads.created_by','=',$request->input('search.user'));
				}
				$leadscount=$leadscount->count();
				
				$interested = DB::table('croma_leads as leads');
				$interested = $interested->join('croma_sources as sources','leads.source','=','sources.id');
				$interested = $interested->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawintested = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawintested .= " AND m1.status=3";
				if($request->input('search.leaddf')!==''){
				$interested = $interested->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$interested = $interested->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$interested = $interested->where('leads.created_by','=',$request->input('search.user'));
				}
				$interested = $interested->join(DB::raw('('.$rawintested.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$interested = $interested->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$interested= $interested->where('leads.course',$lead->course); 
				$interested= $interested->count(); 
				
				//$intested = DB::table('croma_leads as leads')->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'))->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'))->where('course',$lead->course)->where('status',3)->count();
				
				
				$notinterested = DB::table('croma_leads as leads');
				$notinterested = $notinterested->join('croma_sources as sources','leads.source','=','sources.id');
				$notinterested = $notinterested->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawnotintested = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawnotintested .= " AND m1.status=4";
				if($request->input('search.leaddf')!==''){
				$notinterested = $notinterested->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$notinterested = $notinterested->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$notinterested = $notinterested->where('leads.created_by','=',$request->input('search.user'));
				}
				$notinterested = $notinterested->join(DB::raw('('.$rawnotintested.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$notinterested = $notinterested->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$notinterested= $notinterested->where('leads.course',$lead->course); 
				$notinterested= $notinterested->count();
				
				
				//$notintested = DB::table('croma_leads as leads')->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'))->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'))->where('course',$lead->course)->where('status',4)->count();
				
				//$joined = DB::table('croma_leads as leads')->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'))->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'))->where('course',$lead->course)->where('status',9)->count();
				$joined = DB::table('croma_leads as leads');
				$joined = $joined->join('croma_sources as sources','leads.source','=','sources.id');
				$joined = $joined->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawjoined = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawjoined .= " AND m1.status=9";
				if($request->input('search.leaddf')!==''){
				$joined = $joined->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$joined = $joined->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$joined = $joined->where('leads.created_by','=',$request->input('search.user'));
				}
				$joined = $joined->join(DB::raw('('.$rawjoined.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$joined = $joined->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$joined= $joined->where('leads.course',$lead->course); 
				$joined= $joined->count();
				
				//$NPUP = DB::table('croma_leads as leads')->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'))->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'))->where('course',$lead->course)->where('status',2)->count();
				
				$NPUP = DB::table('croma_leads as leads');
				$NPUP = $NPUP->join('croma_sources as sources','leads.source','=','sources.id');
				$NPUP = $NPUP->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawNPUP = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawNPUP .= " AND m1.status=2";
				if($request->input('search.leaddf')!==''){
				$NPUP = $NPUP->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$NPUP = $NPUP->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$NPUP = $NPUP->where('leads.created_by','=',$request->input('search.user'));
				}
				$NPUP = $NPUP->join(DB::raw('('.$rawNPUP.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$NPUP = $NPUP->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$NPUP= $NPUP->where('leads.course',$lead->course); 
				$NPUP= $NPUP->count();
				
				//$callLater = DB::table('croma_leads as leads')->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'))->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'))->where('course',$lead->course)->where('status',5)->count();
				$callLater = DB::table('croma_leads as leads');
				$callLater = $callLater->join('croma_sources as sources','leads.source','=','sources.id');
				$callLater = $callLater->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawcallLater = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawcallLater .= " AND m1.status=5";
				if($request->input('search.leaddf')!==''){
				$callLater = $callLater->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$callLater = $callLater->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$callLater = $callLater->where('leads.created_by','=',$request->input('search.user'));
				}
				$callLater = $callLater->join(DB::raw('('.$rawcallLater.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$callLater = $callLater->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$callLater= $callLater->where('leads.course',$lead->course); 
				$callLater= $callLater->count();
				
				
				//$switchedOff = DB::table('croma_leads as leads')->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'))->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'))->where('course',$lead->course)->where('status',6)->count();	
				
				$switchedOff = DB::table('croma_leads as leads');
				$switchedOff = $switchedOff->join('croma_sources as sources','leads.source','=','sources.id');
				$switchedOff = $switchedOff->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawswitchedOff = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawswitchedOff .= " AND m1.status=6";
				if($request->input('search.leaddf')!==''){
				$switchedOff = $switchedOff->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$switchedOff = $switchedOff->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$switchedOff = $switchedOff->where('leads.created_by','=',$request->input('search.user'));
				}
				$switchedOff = $switchedOff->join(DB::raw('('.$rawswitchedOff.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$switchedOff = $switchedOff->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$switchedOff= $switchedOff->where('leads.course',$lead->course); 
				$switchedOff= $switchedOff->count();
				
				
				//$notReachable = DB::table('croma_leads as leads')->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'))->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'))->where('course',$lead->course)->where('status',7)->count();
				
				
				$notReachable = DB::table('croma_leads as leads');
				$notReachable = $notReachable->join('croma_sources as sources','leads.source','=','sources.id');
				$notReachable = $notReachable->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawnotReachable = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawnotReachable .= " AND m1.status=7";
				if($request->input('search.leaddf')!==''){
				$notReachable = $notReachable->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$notReachable = $notReachable->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$notReachable = $notReachable->where('leads.created_by','=',$request->input('search.user'));
				}
				$notReachable = $notReachable->join(DB::raw('('.$rawnotReachable.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$notReachable = $notReachable->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$notReachable= $notReachable->where('leads.course',$lead->course); 
				$notReachable= $notReachable->count();
				
				//$locationIssue = DB::table('croma_leads as leads')->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'))->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'))->where('course',$lead->course)->where('status',8)->count();
				
				$locationIssue = DB::table('croma_leads as leads');
				$locationIssue = $locationIssue->join('croma_sources as sources','leads.source','=','sources.id');
				$locationIssue = $locationIssue->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawlocationIssue = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawlocationIssue .= " AND m1.status=8";
				if($request->input('search.leaddf')!==''){
				$locationIssue = $locationIssue->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$locationIssue = $locationIssue->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$locationIssue = $locationIssue->where('leads.created_by','=',$request->input('search.user'));
				}
				$locationIssue = $locationIssue->join(DB::raw('('.$rawlocationIssue.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$locationIssue = $locationIssue->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$locationIssue= $locationIssue->where('leads.course',$lead->course); 
				$locationIssue= $locationIssue->count();
				
				$notconnected = DB::table('croma_leads as leads');
				$notconnected = $notconnected->join('croma_sources as sources','leads.source','=','sources.id');
				$notconnected = $notconnected->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawnotconnected = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawnotconnected .= " AND m1.status=12";
				if($request->input('search.leaddf')!==''){
				$notconnected = $notconnected->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$notconnected = $notconnected->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$notconnected = $notconnected->where('leads.created_by','=',$request->input('search.user'));
				}
				$notconnected = $notconnected->join(DB::raw('('.$rawnotconnected.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$notconnected = $notconnected->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$notconnected= $notconnected->where('leads.course',$lead->course); 
				$notconnected= $notconnected->count();
				
				$favorite = DB::table('croma_leads as leads');
				$favorite = $favorite->join('croma_sources as sources','leads.source','=','sources.id');
				$favorite = $favorite->join('croma_cat_course as courses','leads.course','=','courses.id');				 
				$rawfavorite = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL ";
				 $rawfavorite .= " AND m1.status=18";
				if($request->input('search.leaddf')!==''){
				$favorite = $favorite->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$favorite = $favorite->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				if($request->input('search.user')!==''){
					$favorite = $favorite->where('leads.created_by','=',$request->input('search.user'));
				}
				$favorite = $favorite->join(DB::raw('('.$rawfavorite.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$favorite = $favorite->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
				$favorite= $favorite->where('leads.course',$lead->course); 
				$favorite= $favorite->count();
				
			 
								
								
					$arr[] = [
						"Course"=>$lead->course_name,
						//"Counsellor"=>$lead->course_name,
						"New Lead"=>$leadscount,
						"Interested"=>$interested,
						"Not Interested"=>$notinterested,
						"Joined"=>$joined,
						"NPUP"=>$NPUP,
						"Call Later"=>$callLater,
						"Switched Off"=>$switchedOff,
						"Not Reachable"=>$notReachable,
						"Location Issue"=>$locationIssue,
						"Favorite"=>$favorite,
						"Not Connected"=>$notconnected,
						 
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			 
			/* header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=test.csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			 */
			
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');			 
			Excel::create('Leads_Statics_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
			
			
		 
			 
		 
    }
	
	
	
	public function getleadsSalesCounsellorTeamexcel(Request $request)
    { 
	
	//echo "dsdasd";die;
	$leads = DB::table('croma_leads as leads');
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');	
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";			 
			if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('leads.id','desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			/* $not_interested = 0;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			$leads = $leads->where('leads.move_not_interested','=',$not_interested); */
			if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}			
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				//echo $request->input('search.course');die;
				$leads = $leads->where('leads.course',$request->input('search.course'));
				//$leads = $leads->groupby('leads.course');
			}	
			 			
			$leads = $leads->groupby('leads.course');	
				$leads = $leads->orderby('leads.created_at', 'ASC');
			$leads = $leads->get();
			
			
			
			$leadsmonth = DB::table('croma_leads as leads');
			$leadsmonth = $leadsmonth->join('croma_sources as sources','leads.source','=','sources.id');
			$leadsmonth = $leadsmonth->join('croma_cat_course as courses','leads.course','=','courses.id');	
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";			 
			if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}			
			$leadsmonth = $leadsmonth->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));			
			$leadsmonth = $leadsmonth->select('leads.*','sources.name as source_name','courses.name as course_name','leads.created_at as leadcreated',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
		//	$leadsmonth = $leadsmonth->orderBy('leads.id','desc');
			$leadsmonth = $leadsmonth->whereNull('leads.deleted_at');
			$leadsmonth = $leadsmonth->where('leads.demo_attended','=','0');
		 
			if($request->input('search.user')!==''){
					$leadsmonth = $leadsmonth->where('leads.created_by','=',$request->input('search.user'));
					$created_by=$request->input('search.user');
				}else{
			$created_by="";
				}					
			if($request->input('search.source')!==''){
				$leadsmonth = $leadsmonth->where('leads.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leadsmonth = $leadsmonth->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leadsmonth = $leadsmonth->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				//echo $request->input('search.course');die;
				$leadsmonth = $leadsmonth->where('leads.course',$request->input('search.course'));
				//$leads = $leads->groupby('leads.course');
			}
			
			$leadsmonth = $leadsmonth->groupby(DB::raw('month(leads.created_at)'));
			$leadsmonth = $leadsmonth->orderby('leads.created_at', 'ASC');

			//$leads = $leads->paginate($request->input('length'));			
			$leadsmonth = $leadsmonth->get();
			  
			  return response()->view('cm_leads.generate-excel-fees-course-status',['leadsmonth'=>$leadsmonth,'leads'=>$leads,'created_by'=>$created_by]);
			  die;
			   
	 
	}
  
	
    /**
     * Get Not interested leads excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
    public function getleadsexcelNotInterested(Request $request)
    {  	 
	
	  
			$leads = DB::table('croma_leads as leads');
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
			

			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			 
			if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			
			/* if($request->input('search.course')!==''){
				$rawQuery .= " AND m1.course_name=".$request->input('search.course');
			} */

			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('leads.id','desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			$leads = $leads->where('leads.move_not_interested','=',$not_interested);
			
			/*if($request->user()->id != 1 ){
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
			*/
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') ){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}else{
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}

			

			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$leads = $leads->where('leads.course',$request->input('search.course'));
			}
			 
			$leads = $leads->get();
			  
			$returnLeads = [];
			$data = [];
			 
			$returnLeads['recordCollection'] = [];
			
		 
			foreach($leads as $lead){
				 
					$arr[] = [
						"Name"=>$lead->name,
						"Mobile"=>$lead->mobile,
						"Source"=>$lead->source_name,
						"Technology"=>$lead->course_name,
						//"Sub. Technology"=>$lead->sub_courses,
						"Email"=>$lead->email,
						"Status"=>$lead->status_name,
						"Expected Date_Time"=>($lead->expected_date_time==NULL)?"":date_format(date_create($lead->expected_date_time),'d-m-y h:s A'),
						"Remark"=>$lead->remarks,
						"Created"=>(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						
					];
					$returnLeads['recordCollection'][] = $lead->id;
			
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('NI_leads_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		
    }
	
    /**
     * Display a listing of the deleted lead resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deletedLeads()
    {
		$sources = Source::all();
		$courses = Course::all();
		$statuses = Status::all();
        return view('cm_leads.deleted_leads',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses]);
    }
	
    /**
     * Get paginated deleted leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedDeletedLeads(Request $request)
    {
		if($request->ajax()){
			 
			$leads = DB::table('croma_leads as leads')
					   ->join('croma_sources as sources','leads.source','=','sources.id')
					   ->join('croma_cat_course as courses','leads.course','=','courses.id')
					   //->join('status','leads.status','=','status.id')
					   ->select('leads.*','sources.name as src_name','courses.name as crs_name')					
					   ->whereNotNull('leads.deleted_at')
					   ->orderBy('leads.id','desc');
						if($request->input('search.value')!==''){
						$leads = $leads->where(function($query) use($request){
						$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
						->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						 ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
						});
						}
				
					/*
					    if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('manager')){
						 
						  $leads=$leads->where('leads.created_by','=',$request->user()->id);
						} 
						*/
                        
                      if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') ){
                        if($request->user()->current_user_can('manager')){
                        $getID= [$request->user()->id];
                        $manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
                        foreach($manages as $key=>$manage){	 				 
                        array_push($getID,$manage->user_id);
                        }
                        $leads = $leads->whereIn('leads.created_by',$getID);
                        
                        }else{
                        $leads = $leads->where('leads.created_by','=',$request->user()->id);
                        }
                        } 

						
						
						
						
					   $leads= $leads->paginate($request->input('length'));
					   
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			foreach($leads as $lead){
				$lastStatus = DB::table('croma_lead_follow_ups as lead_follow_ups')
								->join('croma_status as status','status.id','=','lead_follow_ups.status')
								->where('lead_follow_ups.lead_id','=',$lead->id)
								->select('status.name as status_name')
								->orderBy('lead_follow_ups.id','desc')
								->first();
				/* $sources = $lead->source()->where('id',$lead->source)->get();
				foreach($sources as $source){
					$src = $source;
				}
				$courses = $lead->course()->where('id',$lead->course)->get();
				foreach($courses as $course){
					$crs = $course;
				}
				$statuses = $lead->status()->where('id',$lead->status)->get();
				foreach($statuses as $status){
					$sts = $status;
				} */
				$data[] = [
				    "<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
					$lead->name,
					$lead->email,
					//$lead->mobile,
					$lead->src_name,
					$lead->crs_name,
					$lead->sub_courses,
					$lastStatus->status_name,
					$lead->remarks,
					(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
			    	'<a href="javascript:deletedLeadController.restore('.$lead->id.')"><i class="fa fa-undo" aria-hidden="true" title="Restor"></i></a>'.' | '.'<a href="javascript:deletedLeadController.delete('.$lead->id.')"><i class="fa fa-trash" aria-hidden="true" title="Delete"></i></a>'
				];
			}
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
    /**
     * Restore lead.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
	{
		if($request->ajax()){
			try{
				$lead = Lead::where('id',$id)->whereNotNull('deleted_at')->first();
				if($lead){
			    	DB::table('croma_leads')->where('id',$id)->update(array('deleted_by'=>0,'deleted_at'=>null));
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Lead not found'],200);
			}
		}
	}
	
    /**
     * Remove the specified resource from storage permanently.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('hard_delete_lead'))){
			try{
				$lead = Lead::where('id',$id)->whereNotNull('deleted_at')->first();
				if($lead->forceDelete()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Lead not found'],200);
			}
		}else{
			return "Unh Cheatin`";
		}
    }
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function followUp(Request $request, $id)
    {  
		if($request->ajax()){
			$lead = Lead::findOrFail($id);		 
			$user = User::where('id',$lead->created_by)->first();
			$leadLastFollowUp = DB::table('croma_lead_follow_ups as lead_follow_ups')
							->where('lead_follow_ups.lead_id','=',$id)
							->select('lead_follow_ups.*')
							->orderBy('lead_follow_ups.id','desc')
							->first();
			$sources = Source::all();
			$sourceHtml = '';
			$sourceObj = null;
			if(count($sources)>0){
			foreach($sources as $source){		 
					if($source->id == $lead->source){						 
						$sourceObj = $source;
					} 
				}
			}
			
			$sourceid=[];
			$source =Source::where('social',1)->get();
			foreach($source as $sour){			
				array_push($sourceid,$sour->id);
			}
			 
			if(in_array($lead->source,$sourceid)){
			$sourcename ="Website";							
			}else{							
			$sourcename= $lead->source_name;
			}
			
			$courses = Course::all();
			 
			$courseHtml = '';
			if(count($courses)>0){
				foreach($courses as $course){
					if($course->id == $lead->course){
						$courseHtml .= '<option value="'.$course->id.'" selected>'.$course->name.'</option>';
					}else{
						$courseHtml .= '<option value="'.$course->id.'">'.$course->name.'</option>';
					}
				}
			}
			$messages = Message::where('permission','LIKE','%G%')->where('all_lead','1')->orWhere('course',$lead->course)->select('id','name')->get();
			$messageHtml = '';
			if(count($messages)>0){
				foreach($messages as $message){
					$messageHtml .= '<option value="'.$message->id.'">'.$message->name.'</option>';
				}
			}
			
			if(Auth::user()->current_user_can('abgyan_follow_up') ){
			$statuses = Status::where('abgyan_follow_up',1)->where('name','!=','Active')->where('name','!=','In Active')->where('name','!=','Rejected')->where('fb_status','1')->get();
			}else{
			   
			   if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
			       //->orWhere('abgyan_follow_up',1)
                $statuses = Status::where('lead_follow_up',1)->where('name','!=','Active')->where('name','!=','In Active')->where('name','!=','Rejected')->where('fb_status','1')->get();
                }else{
                $statuses = Status::where('lead_follow_up',1)->where('name','!=','Active')->where('name','!=','In Active')->where('name','!=','Rejected')->where('fb_status','1')->get();
                
                }
			   
			   
			}
			//dd($statuses);
			$statusHtml = '';
			$disabled = '';
			$dateValue = '';
			if(count($statuses)>0){
				foreach($statuses as $status){
					if(strcasecmp($status->name,'new lead')){
						$selected = '';
						if($leadLastFollowUp->status==$status->id){
							$selected = 'selected';
							//if(!strcasecmp($status->name,'not interested')||!strcasecmp($status->name,'location issue')){
							if(!$status->show_exp_date){
								$disabled = 'disabled';
								if($leadLastFollowUp->expected_date_time!=NULL){
									$dateValue = date_format(date_create($leadLastFollowUp->expected_date_time),'d-F-Y g:i A');
								}
							}
						}
						$statusHtml .= '<option data-value="'.$status->show_exp_date.'" value="'.$status->id.'" '.$selected.'>'.$status->name.'</option>';
					}
				}
			}
			
			
			if(!empty($lead->code)){
				$mobilecode='+'.$lead->code.' - ';
				$newcode ='+'.$lead->code;
			}else{
				$mobilecode="";
				$newcode="+91";
			}
			
			$demo = Demo::where('lead_id',$lead->id)->first();
			 
			$html = '<div class="row">
						<div class="x_content" style="padding:0">';
				if(!$demo){
				    if(Auth::user()->current_user_can('mask_number')){
						$number="********".substr($lead->mobile,8);
					}
					else{
						$number=$lead->mobile;
					}
					$html.= '<form class="form-label-left" onsubmit="return leadFBController.storeFollowUp('.$id.',this)">
								<div class="form-group">
									<div class="col-md-4">
										<label for="name">Name<span class="required">:</span></label>
										<!--input type="text" name="name" class="form-control-static col-md-7 col-xs-12" value="'.$lead->name.'"-->
										<p class="form-control-static" style="display:inline">'.$lead->name.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4" contenteditable>
									<label for="email">Email <span class="required">:</span></label>
										<!--input type="text" name="email" class="form-control col-md-7 col-xs-12" value="'.$lead->email.'"-->
										<p class="form-control-static" style="display:inline" >'.$lead->email.'</p>
									</div>
								</div>
								<div class="form-group" >
									<div class="col-md-4" >
										<label for="mobile">Mobile <span class="required">:</span></label>
										<!--input type="text" name="mobile" class="form-control col-md-7 col-xs-12" value="'.$lead->mobile.'"-->
										<span class="form-control-static" onclick="copyToClipboard('.$number.')">'.$mobilecode.''.$number.'  <a href="https://wa.me/'.$newcode.''.$lead->mobile.'" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true" style="color:#06e906;margin-left: 10px;font-size: 18px;"></i></a></span>
									    <span data-mobile="'.$lead->mobile.'" data-name="'.$lead->name.'" class="call" style="cursor: pointer;margin-left:10px;"><i class="fa fa-phone" aria-hidden="true"></i></span>
									    <a href="calllog/'.$lead->mobile.'" class="calllog" call-attr="'.$lead->mobile.'" target="_blank"><i class="fa fa-headphones" aria-hidden="true"></i></a>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4" >
										<label>Source <span class="required">:</span></label>
									 
										<p class="form-control-static" style="display:inline">'."FaceBook".'</p> <!-- $sourcename -->
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label for="sub-course">Sub Technologies <span class="required">:</span></label>
										<!--input type="text" name="sub-course" class="form-control col-md-7 col-xs-12" value="'.$lead->sub_courses.'" placeholder="Comma seperated courses"-->
										<p class="form-control-static" style="display:inline">'.$lead->sub_courses.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label for="remark">Student Remark <span class="required">:</span></label>
										<!--textarea name="remarks" rows="1" class="form-control col-md-7 col-xs-12">'."sks".$lead->remarks.'</textarea-->
										<p class="form-control-static" style="display:inline">'.$lead->remarks.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label>Type <span class="required">:</span></label>
										<p class="form-control-static" style="display:inline">'.ucfirst('lead').'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-8">
										<label>Owner <span class="required">:</span></label>
										<p class="form-control-static" style="display:inline">'.$user->name.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label>Technology <span class="required">*</span>('.$lead->fbcourses.')</label>
										<select class="select2_single form-control sms-control select2_course techn" name="course" tabindex="-1">
											<option value="">-- SELECT TECHNOLOGY --</option>
											'.$courseHtml.'
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label>Status <span class="required">*</span></label>
										<select class="select2_single form-control fbstatus" name="status" tabindex="-1">
											<option value="">-- SELECT STATUS --</option> 
											'.$statusHtml.'
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label for="expected_date_time">Expected Date &amp; Time <span class="required">*</span></label>
										<input type="text" id="expected_date_time" name="expected_date_time" class="form-control col-md-7 col-xs-12" value="'.$dateValue.'" placeholder="Expected Date &amp; Time" '.$disabled.' autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label for="remark">Counsellor Remark </label>
										<textarea name="remark" rows="1" class="form-control col-md-7 col-xs-12 remark"></textarea>
									</div>
								</div>
								<div class="form-group" style="display:none;">
									<div class="col-md-4">
										<label for="message">Message <span class="required">*</span></label>
										<select class="select2_single form-control sms-control-target" name="message" tabindex="-1">
											<option value="">-- SELECT MESSAGE --</option>
											<option value="no">No Message</option>
											'.$messageHtml.'
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label style="visibility:hidden">Submit</label>
										<button type="submit" class="btn btn-success btn-block fillowbtn" name="submit">Submit</button>
									</div>
								</div>
							</form>';
				}else{
					$html.= '<p style="font-size: 24px;font-weight: 700;padding-top: 20px;text-align: center;">This lead found in attended demo...</p>';
				}
			$html.=	'</div>
					</div> 
					<p style="margin-top:10px;margin-bottom:-5px;"><strong>Follow Up Status</strong>  <select onchange="javascript:leadFBController.getAllFollowUps()" class="follow-up-count"><option value="5">Last 5</option><option value="all">All</option></select></p>
					<div class="table-responsive">
						<table id="datatable-followups" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>Date</th>
									<th>Counsellor Remark</th>
									<th>Status</th>
									<th>Expected Date</th>
								</tr>
							</thead>
						</table>
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
    //
    public function storeFollowUp(Request $request, $id)
    {
		if($request->ajax()){
		    
			$validator = Validator::make($request->all(),[
				 
				'course'=>'required',
				'status'=>'required',			 
				//'remark'=>'required',
				// 'message'=>'required',
			]);
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
			
			// check now expected date and time if status is not - not interested/location issue
			$statusModel = Status::find($request->input('status'));
			//if($statusModel->name!='Not Interested' && $statusModel->name!='Location Issue'){
			
			if($request->input('status')!='3' && $request->input('status')!='27' ){
				if($statusModel->show_exp_date){
					$validator = Validator::make($request->all(),[
						'expected_date_time'=>'required',
					]);
					if($validator->fails()){
						$errorsBag = $validator->getMessageBag()->toArray();
						return response()->json(['status'=>1,'errors'=>$errorsBag],400);
					}				
				}
			}
			
			
    		
    		/*$data=DB::table('croma_leads')->where('fb_lead','1')->where('facbook_unassign','0')->where('fb_lead_status','3')->where('id',$id)->update([
    		     "fb_lead_status"=>1   
    		]);*/
    		//comment 13 march 2023
    		
    		 
    		
			
			$lead = Lead::find($id);
    
			$user = User::where('id',$lead->created_by)->first();
			 
			$statusObj = Status::find($request->input('status'));
			if(!strcasecmp($statusObj->name,'attended demo')){
				$lead->demo_attended = 1;
			}
			 
			//get course name
			$coursedetail=DB::table('croma_cat_course')->where('id',$request->input('course'))->first();
			$courseName=$coursedetail->name ?? '';


			$lead->course = $request->input('course');
			$lead->status = $request->input('status');
			
			
			
			
			$lead->fb_lead_status = $request->input('status');
			$lead->status_name=	 Status::find($request->input('status'))->name;
			if($lead->save()){
				$leadFollowUp = new LeadFollowUp;
				$status = Status::findorFail($request->input('status'));
				if(!strcasecmp($status->name,'npup')){
					$npupCount = LeadFollowUp::where('lead_id',$id)->where('status',$status->id)->count();
					if($npupCount>=15){
						$status = Status::where('name','LIKE','Not Interested')->first();
						$leadFollowUp->status = $status->id;
					}else{
						$leadFollowUp->status = $request->input('status');
					}
				}else{
					$leadFollowUp->status = $request->input('status');
				}
				$lead->fb_lead_status = $request->input('status'); //add 3 march
				$lead->status=$status->id;
				$lead->course_name=$courseName; //save course name sk
				$lead->save();
				
		
				
				//$statusObj = Status::find($request->input('status'));
				$leadFollowUp->remark = trim($request->input('remark'));
				$leadFollowUp->lead_id = $id;
				$leadFollowUp->followby = Auth()->user()->id;
				$leadFollowUp->expected_date_time = NULL;
				if($request->input('expected_date_time')!=''){
					$leadFollowUp->expected_date_time = date('Y-m-d H:i:s',strtotime($request->input('expected_date_time')));
				}
				
			
				
				
				
				
				if($leadFollowUp->save()){
					if('no'!=$request->input('message')){
						$message = Message::find($request->input('message'));
						$msg = $message->message;
						$msg = preg_replace('/{{name}}/i',$lead->name,$msg);
						$msg = preg_replace('/{{course}}/i',($request->has('course'))?Course::find($request->input('course'))->name:"",$msg);
						$msg = preg_replace('/{{counsellor}}/i',(isset($user->name))?$user->name:"",$msg);
						$msg = preg_replace('/{{mobile}}/i',(isset($user->mobile))?$user->mobile:"",$msg);
						$msg = preg_replace('/\\r/i','%0D',$msg);
						$msg = preg_replace('/\\n/i','%0A',$msg);
						$tempid = '1707161786873850571';
					//	sendSMS($lead->mobile,$msg,$tempid);						
						//sendSMS($request->input('mobile'),$msg);						
					}
					
					
			  $todays = strtotime("now");
			 $leadlast = DB::table('croma_lead_follow_ups as lead_follow_ups')
							->join('croma_leads as leads','leads.id','=','lead_follow_ups.lead_id')
							 ->orderBy('lead_follow_ups.created_at','desc')
							 ->limit(1,2)
							->where('leads.created_by','=',Auth::user()->id)
							->whereDate('lead_follow_ups.created_at', '=',date('Y-m-d', $todays))
							->select('lead_follow_ups.*','lead_follow_ups.created_at as followCraete','leads.*')							
							->skip(1)->first();
			if($leadlast){		
			//$current  =date('Y-m-d H:i:s'); 
			$current  =$leadFollowUp->created_at; 
			$diff = abs(strtotime($current) - strtotime($leadlast->followCraete));
			 
		//	$hours = floor($diff / (60*60));
			//$minutes = floor(($diff - ($hours*60*60)) / 60);
			//$seconds = floor(($diff - ($hours*60*60)) - ($minutes*60));
			
			$minutes = floor($diff / (60));
			$seconds = floor(($diff - ($minutes*60)));
			
			// status count
			
			$status = Status::findorFail($request->input('status'));
			$leads = DB::table('croma_leads as leads');
			$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE '".$status->name."') AND DATE(m1.created_at)='".date('Y-m-d', $todays)."' ";
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			$leads = $leads->where('leads.created_by','=',Auth::user()->id);
			$count= $leads->count();
			$subjects = 'Lead | '.$user->name.' | '.$minutes.' | '.$status->name.' ('.$count.')';
			
				 $oldtime= $leadlast->followCraete;
			}else{
				$subjects='Lead | '.$user->name.' | 0 - 0  | '.$status->name;
				$oldtime= "0 - 0";
			}
			
	    	$manages = Capability::select('manager')->where('user_id',Auth::user()->id)->first();	 
			if(!empty($manages->manager)){
				
			 
				$useremil = User::where('id',$manages->manager)->first();
				if(!empty($useremil)){
					
					$emailId= $useremil->email;
					$cc= 'cromacampus.leadsstatus@gmail.com';
				}else{
					$emailId= 'cromacampus.leadsstatus@gmail.com';
					$cc= '';
				}
				
			}else{
				$emailId= 'cromacampus.leadsstatus@gmail.com';
				$cc= '';
				
			}
		 	 
			$headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		    $headers .= 'From: <info@leads.cromacampus.com>' . "\r\n";
			$to="";
	 
			$message='			<tr>
			<td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have received  Lead Status Notification. Here are the details:</span><u></u><u></u></p>
			</td>
			</tr>
			
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Leads Status:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$subjects.'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">New Time :</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$leadFollowUp->created_at.'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Old Time :</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$oldtime.'</span><u></u><u></u></p>
			</td>
			</tr>	
			
			
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$lead->name.'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$lead->mobile.'  </span><u></u><u></u></p>
			</td>
			</tr> 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Course:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$lead->course_name.'  </span><u></u><u></u></p>
			</td>
			</tr> 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Remark:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('remark').'  </span><u></u><u></u></p>
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
            
 	/*Mail::send('emails.send_lead_status', ['msg'=>$message], function ($m) use ($message,$request,$subjects,$emailId,$cc) {
				$m->from('info@leads.cromacampus.com', 'Lead');
				if(!empty($cc)){
				$m->to($emailId, "")->subject($subjects)->cc('cromacampus.leadsstatus@gmail.com');
				}else{
				    
				    	$m->to($emailId, "")->subject($subjects);
				    
				}
				
			});*/
				
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					// ******************************************
					// CREATING DEMO BCOZ STATUS IS ATTENDED DEMO
					$lead_id_demo = $lead->id;
					if(!strcasecmp($statusObj->name,'attended demo')){
						$demo = new Demo;
						$demo->lead_id = $lead_id_demo;
						$demo->name = $lead->name;
						$demo->email = $lead->email;
						$demo->mobile = $lead->mobile;
						$demo->source = $lead->source;
						$demo->source_name = ($lead->source!='' && $lead->source!='0')?Source::find($lead->source)->name:"";
						$demo->course = $lead->course;
						$demo->course_name = ($lead->course!=''&&$lead->course!='0'&&Course::find($lead->course))?Course::find($lead->course)->name:"";
						$demo->sub_courses = $lead->sub_courses;
						$demo->remarks = $lead->remarks;
						$demo->executive_call = 'yes';
						/* $demo->executive_call = $request->input('exec_call');
						if($request->input('exec_call')=='no'){
							$demo->demo_type = $request->input('demo_type');
						} */
						$demo->created_by = $lead->created_by;
						$demo->owner = $lead->created_by;
						if($demo->save()){
							$followUp = new DemoFollowUp;
							$followUp->status = Status::where('name','LIKE','Attended Demo')->first()->id;
							$followUp->remark = $request->input('remark');
							$followUp->demo_id = $demo->id;
							$followUp->followby = Auth()->user()->id;
							if($followUp->save()){
								/* if('no'!=$request->input('message')){
									$message = Message::find($request->input('message'));
									$msg = $message->message;
									$msg = preg_replace('/{{name}}/i',$request->input('name'),$msg);			
									$msg = preg_replace('/{{course}}/i',$demo->course_name,$msg);
									$msg = preg_replace('/\\r/i','%0D',$msg);
									$msg = preg_replace('/\\n/i','%0A',$msg);
									sendSMS($request->input('mobile'),$msg);						
								} */
								return response()->json(['status'=>1,'demo_created'=>1],200);
							}else{
								$demo->delete();
								return response()->json(['status'=>0,'errors'=>'Demo not added as first follow up not created...'],400);
							}
						}else{
							return response()->json(['status'=>0,'errors'=>'Demo not added'],400);
						}
					}
					// CREATING DEMO BCOZ STATUS IS ATTENDED DEMO
					// ******************************************
					//$this->demoofollbyid($id); 
					
					$leads=DB::table('croma_leads')->where('fb_lead','1')->where('facbook_unassign','0')->where('id',$id)->get();
                    $temp=[];
                    foreach($leads as $lead)
                    {
                    	$leadid=$lead->id;
                    	$fb_lead_status=$lead->fb_lead_status;
                        
                    	$follow=DB::table('croma_lead_follow_ups')->where('lead_id',$leadid)->orderBy('id','desc')->first();
                    	$followid=$follow->id;
                        //$temp[]=$followid;
                    	$followupdate=DB::table('croma_lead_follow_ups')->where('id',$followid)->update(["status"=>$fb_lead_status]);
                    }
					if($request->input('status')=='3')
					{
						$pop="show";
					}
					else
					{
						$pop="hide";
					}
					return response()->json(['status'=>1,'pop'=>$pop],200);
				}
			}
		}		
	}
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getFollowUps(Request $request, $id)
    {   
		if($request->ajax()){
			
			$leads = DB::table('croma_lead_follow_ups as lead_follow_ups')
							->join('croma_status as status','status.id','=','lead_follow_ups.status')
							->where('lead_follow_ups.lead_id','=',$id)
							->select('lead_follow_ups.*','status.name as status_name')
							->orderBy('lead_follow_ups.id','desc');
			if($request->input('count')!='all'){
				$leads = $leads->take($request->input('count'));
			}else{
				$leads = $leads->take(100);
			}
			$leads = $leads->paginate($request->input('length'));
							//->take(5)
							//->paginate($request->input('length'));

			Session::put('leadID',$id);
			
							
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			foreach($leads as $lead){
				$data[] = [
					(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
					$lead->remark,
					$lead->status_name,
					(new Carbon($lead->expected_date_time))->format('d-m-y h:i A')
				];
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
    public function expectfollowUp(Request $request, $id)
    {  
		if($request->ajax()){
			$lead = Lead::findOrFail($id);		 
			$owner = User::where('id',$lead->created_by)->first();
			$leadLastFollowUp = DB::table('croma_lead_follow_ups as lead_follow_ups')
							->where('lead_follow_ups.lead_id','=',$id)
							->select('lead_follow_ups.*')
							->orderBy('lead_follow_ups.id','desc')
							->first();
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
					if($course->id == $lead->course){
						$courseHtml .= '<option value="'.$course->id.'" selected>'.$course->name.'</option>';
					}else{
						$courseHtml .= '<option value="'.$course->id.'">'.$course->name.'</option>';
					}
				}
			}
			$coursename =Course::where('id',$lead->course)->first();
			$statusname =Status::where('id',$leadLastFollowUp->status)->first();
			
			
			$trainers = FeesGetTrainer::all();			 
			$trainerHtml = '';
			if(!empty($trainers)){
				foreach($trainers as $trainer){
					if($trainer->id == $lead->trainer){
						$trainerHtml .= '<option value="'.$trainer->name.'" selected>'.$trainer->name.'</option>';
					}else{
						$trainerHtml .= '<option value="'.$trainer->name.'">'.$trainer->name.'</option>';
					}
				}
			}
			
			
			$users = User::all();			 
			$usersHtml = '';
			if(!empty($users)){
				foreach($users as $user){
					if($user->id == $lead->coorditioner){
						$usersHtml .= '<option value="'.$user->name.'" selected>'.$user->name.'</option>';
					}else{
						$usersHtml .= '<option value="'.$user->name.'">'.$user->name.'</option>';
					}
				}
			}
			
			
			
			$messages = Message::where('permission','LIKE','%G%')->where('all_lead','1')->orWhere('course',$lead->course)->select('id','name')->get();
			$messageHtml = '';
			if(count($messages)>0){
				foreach($messages as $message){
					$messageHtml .= '<option value="'.$message->id.'">'.$message->name.'</option>';
				}
			}
				if(Auth::user()->current_user_can('abgyan_follow_up') ){
		    	$statuses = Status::where('abgyan_follow_up',1)->where('name','!=','Active')->where('name','!=','In Active')->get();
				}else{
				  	$statuses = Status::where('lead_follow_up',1)->where('name','!=','Active')->where('name','!=','In Active')->get();  
				}
			$statusHtml = '';
			$disabled = '';
			$dateValue = '';
			if(count($statuses)>0){
				foreach($statuses as $status){
					if(strcasecmp($status->name,'new lead')){
						$selected = '';
						if($leadLastFollowUp->status==$status->id){
							$selected = 'selected';
							//if(!strcasecmp($status->name,'not interested')||!strcasecmp($status->name,'location issue')){
							if(!$status->show_exp_date){
								$disabled = 'disabled';
								if($leadLastFollowUp->expected_date_time!=NULL){
									$dateValue = date_format(date_create($leadLastFollowUp->expected_date_time),'d-F-Y g:i A');
								}
							}
						}
						$statusHtml .= '<option data-value="'.$status->show_exp_date.'" value="'.$status->id.'" '.$selected.'>'.$status->name.'</option>';
					}
				}
			}
			
			$demo = Demo::where('lead_id',$lead->id)->first();
			 
			$html = '<div class="row">
						<div class="x_content" style="padding:0">';
				if(!$demo){
					$html.= '<form class="form-label-left" onsubmit="return leadFBController.storeExpectFollowUp('.$id.',this)">
								 <div class="form-group">
									<div class="col-md-3">
										<label for="name">Name<span class="required">:</span></label>										 
										<p class="form-control-static" style="display:inline">'.$lead->name.'</p>
									</div>
								</div>
							
								<div class="form-group">
									<div class="col-md-5">
									<label for="email">Email <span class="required">:</span></label>
										<!--input type="text" name="email" class="form-control col-md-7 col-xs-12" value="'.$lead->email.'"-->
										<p class="form-control-static" style="display:inline">'.$lead->email.'</p>
									</div>
								</div>
							
								 	<div class="form-group">
									<div class="col-md-4">
										<label for="mobile">Mobile <span class="required">:</span></label>
										<!--input type="text" name="mobile" class="form-control col-md-7 col-xs-12" value="'.$lead->mobile.'"-->
										<p class="form-control-static" style="display:inline" contenteditable>'.$lead->mobile.'</p>
									</div>
								</div>
								 
							  
								<div class="form-group">
									<div class="col-md-3">
										<label>Owner <span class="required">:</span></label>
										<p class="form-control-static" style="display:inline">'.$owner->name.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-9">
										<label>Technology <span class="required">*</span></label>
										<p class="form-control-static" style="display:inline">'.$coursename->name.'</p>
										 
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Status <span class="required">*</span></label>
										<select class="select2_single form-control" name="status" tabindex="-1">
											<option value="">-- SELECT STATUS --</option> 
											'.$statusHtml.'
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-3">
										<label>Trainer <span class="required">*</span></label>
										<select class="select2_trainer select2_single form-control" name="trainer" tabindex="-1">
											<option value="">-- SELECT TRAINER --</option> 
											'.$trainerHtml.'
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label for="expected_date_time">Demo Date &amp; Time <span class="required">*</span></label>
										<input type="text" id="expected_date_time" name="expected_date_time" class="form-control col-md-7 col-xs-12" value="'.$dateValue.'" placeholder="Demo Date &amp; Time" '.$disabled.' autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Coordinator <span class="required">*</span></label>
										<select class="select2_user select2_single form-control" name="coordinator" tabindex="-1">
											<option value="">-- Select Coordinator --</option>
											'.$usersHtml.'
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-3">
										<label>Meeting URL <span class="required">*</span></label>
										<input type="text" class="form-control" name="meetingurl" placeholder="Meerting URL" tabindex="-1">
											 
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label for="remark">Counsellor Remark <span class="required">*</span></label>
										<textarea name="remark" rows="1" class="form-control col-md-7 col-xs-12"></textarea>
									</div>
								</div>
							 
								<div class="form-group">
									<div class="col-md-4">
										<label style="visibility:hidden">Submit</label>
										<button type="submit" class="btn btn-success btn-block">Submit</button>
									</div>
								</div>
							</form>';
				}else{
					$html.= '<p style="font-size: 24px;font-weight: 700;padding-top: 20px;text-align: center;">This lead found in attended demo...</p>';
				}
			$html.=	'</div>
					</div> 
					<p style="margin-top:10px;margin-bottom:-5px;"><strong>Follow Up Status</strong>  <select onchange="javascript:leadFBController.getAllExpectFollowUps()" class="follow-up-count"><option value="5">Last 5</option><option value="all">All</option></select></p>
					<div class="table-responsive">
						<table id="datatable-expect-followups" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>Date</th>
									<th>Counsellor Remark</th>
									<th>Status</th>
									<th>Expected Date</th>
								</tr>
							</thead>
						</table>
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
    public function storeExpectFollowUp(Request $request, $id)
    {
		if($request->ajax()){

			$validator = Validator::make($request->all(),[
				'coordinator'=>'required',
				'status'=>'required',			 
				'trainer'=>'required',			 
				'remark'=>'required',
				 
			]);
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
			$statusModel = Status::find($request->input('status'));
			if($statusModel->show_exp_date){
				$validator = Validator::make($request->all(),[
					'expected_date_time'=>'required',
				]);
				if($validator->fails()){
					$errorsBag = $validator->getMessageBag()->toArray();
					return response()->json(['status'=>1,'errors'=>$errorsBag],400);
				}				
			}
			
			$lead = Lead::find($id);
			$user = User::where('id',$lead->created_by)->first();
			$statusObj = Status::find($request->input('status'));
			if(!strcasecmp($statusObj->name,'attended demo')){
				$lead->demo_attended = 1;
			}
		 
			$lead->trainer = $request->input('trainer');
			$lead->coordinator = $request->input('coordinator');
			$coordinator= User::where('name',$request->input('coordinator'))->first();
			$lead->meetingurl = $request->input('meetingurl');
			$lead->move_not_interested = 3;
			$lead->status = $request->input('status');
			$lead->status_name=	 Status::find($request->input('status'))->name;
			if($lead->save()){
				$leadFollowUp = new LeadFollowUp;
				$leadFollowUp->status = $request->input('status');
				$leadFollowUp->remark = $request->input('remark');
				$leadFollowUp->lead_id = $id;
				$leadFollowUp->followby = $lead->created_by;
				if($request->input('expected_date_time')!=''){
					$leadFollowUp->expected_date_time = date('Y-m-d H:i:s',strtotime($request->input('expected_date_time')));
				}else{
				    	$leadFollowUp->expected_date_time = NULL;
				}
				if($leadFollowUp->save()){ 
					return response()->json(['status'=>1],200);
				}
			}
		}		
	}
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getExpectFollowUps(Request $request, $id)
    {   
		if($request->ajax()){
			
			$leads = DB::table('croma_lead_follow_ups as lead_follow_ups')
							->join('croma_status as status','status.id','=','lead_follow_ups.status')
							->where('lead_follow_ups.lead_id','=',$id)
							->select('lead_follow_ups.*','status.name as status_name')
							->orderBy('lead_follow_ups.id','desc');
			if($request->input('count')!='all'){
				$leads = $leads->take($request->input('count'));
			}else{
				$leads = $leads->take(100);
			}
			$leads = $leads->paginate($request->input('length'));
							//->take(5)
							//->paginate($request->input('length'));
							
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			foreach($leads as $lead){
				$data[] = [
					(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
					$lead->remark,
					$lead->status_name,
					(new Carbon($lead->expected_date_time))->format('d-m-y h:i A')
				];
			}
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
		}		
	}
	
    /**
     * Send client registration mail to client containing user name password.
     *
     * @param  object  $client
     */
    public function sendUandP($lead,$leadFollowUp)
    {
        /*Mail::send('emails.sendlead', ['lead'=>$lead,'leadFollowUp'=>$leadFollowUp], function ($m) use ($lead) {
            $m->from('leads@leadpitch.in', 'Leads with Location Issue');
            $m->to('locationissue@gmail.com', "")->subject('Leads with Location Issue');
        });
        
        */
    }
	
    /**
     * Send mail to counsellor.
     *
     * @param lead id
     */	
	public function sendMailToCounsellor(Request $request, $id){
		if(NULL==$id){
			return response()->json([
				"status"=>0,
				"error"=>[
					"code"=>400,
					"message"=>"Invalid Input or Bad Request"
				]
			],400);
		}
		$lead = DB::table('croma_leads as leads')
				->join('croma_users as users','users.id','=','leads.created_by')
				->select('leads.*','users.name as counsellor_name','users.email as counsellor_email','users.id as counsellor_id')
				->where('leads.id',$id)
				->first();
		// Write mailing logic here
		// Write mailing logic here
		return response()->json([
			"status"=>1,
			"success"=>[
				"code"=>200,
				"message"=>"Mailed Successfully..."
			]
		],200);
	}
	
    /**
     * Send bulk sms.
     *
     * @param lead id(s) and message to send.
     */	
	public function sendBulkSms(Request $request){
		$ids = $request->input('ids');
		$message = $request->input('message');
		 
		foreach($ids as $id){
			$lead = Lead::find($id);		 
			if($lead){
				$msg = $message;
				$msg = preg_replace('/{{name}}/i',$lead->name,$msg);
				$msg = preg_replace('/\\r/i','%0D',$msg);
				$msg = preg_replace('/\\n/i','%0A',$msg); 	
				
					$tempid = '1707161786873850571';
				//sendSMS($lead->mobile,$msg,$tempid);
			}
			
			
		}
		return response()->json([
			'status'=>1,
			'success'=>[
				'code'=>200,
				'message'=>'Bulk Sms send successfully...'
			]
		],200);
	}
	
    /**
     * Move not interested.
     *
     * @param lead id(s) to send.
     */	
	public function moveNotInterested(Request $request){
		$ids = $request->input('ids');
	 
		if(!empty($ids)){
		foreach($ids as $id){
			
			$lead = Lead::findorFail($id);
			
			if($lead){
				$leadFollowUp = DB::table('croma_lead_follow_ups as lead_follow_ups');
				$leadFollowUp = $leadFollowUp->join('croma_status as status','status.id','=','lead_follow_ups.status');
				$leadFollowUp = $leadFollowUp->where('lead_follow_ups.lead_id',$lead->id);
				$leadFollowUp = $leadFollowUp->select('lead_follow_ups.*','status.name');
				$leadFollowUp = $leadFollowUp->orderBy('lead_follow_ups.id','desc');
				$leadFollowUp = $leadFollowUp->first();
			 
				if(strcasecmp($leadFollowUp->name,'Not Interested')==0 || strcasecmp($leadFollowUp->name,'Location Issue')==0){
					$lead->move_not_interested = 1;
						 
					$lead->save();
				}
			}
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Moved successfully...'
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
     * Move to expected  lead.
     *
     * @param lead id(s) to send.
     */	
	public function moveToExpectedLead(Request $request){
		$ids = $request->input('ids');
	 
		if(!empty($ids)){
		foreach($ids as $id){
			
			$lead = Lead::findorFail($id);
			 
			if($lead){
				 
					$lead->move_not_interested = 2;						 
					$lead->save();
				 
			}
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Moved successfully...'
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
     * Move to expected  lead.
     *
     * @param lead id(s) to send.
     */	
	public function moveToExpectedLeadDemo(Request $request){
		$ids = $request->input('ids');
	 
		if(!empty($ids)){
		foreach($ids as $id){
			
			$lead = Lead::findorFail($id);
			 
			if($lead){
				 
					$lead->move_not_interested = 3;						 
					$lead->save();
				 
			}
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Moved successfully...'
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
     * Move not interested.
     *
     * @param lead id(s) to send. 
     */	
	public function update_job_notification(Request $request)
	{
		 
		$ids = $request->input('follow_id');	 
			 
			if($ids){
			 $update =array(
			 'follow_status'=>1,
			 );
			 $updated= DB::table('croma_lead_follow_ups')->where('id',$ids)->update($update);
 	

		}
			
		
		 
	}
	 /**
     * Move to lead.
     *
     * @param lead id(s) to send.
     */	
	public function moveToLeads(Request $request){
		$ids = $request->input('ids');
	 
		if(!empty($ids)){
		foreach($ids as $id){
			
			$lead = Lead::findorFail($id);
			
			if($lead){
				$leadFollowUp = DB::table('croma_lead_follow_ups as lead_follow_ups');
				$leadFollowUp = $leadFollowUp->join('croma_status as status','status.id','=','lead_follow_ups.status');
				$leadFollowUp = $leadFollowUp->where('lead_follow_ups.lead_id',$lead->id);
				$leadFollowUp = $leadFollowUp->select('lead_follow_ups.*','status.name');
				$leadFollowUp = $leadFollowUp->orderBy('lead_follow_ups.id','desc');
				$leadFollowUp = $leadFollowUp->first();
			 
				if(strcasecmp($leadFollowUp->name,'Not Interested')==0 || strcasecmp($leadFollowUp->name,'Location Issue')==0){
					 
					$lead->move_not_interested = 0;
						 
					$lead->save();
				}
			}
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Moved successfully...'
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
     * expected Move to lead.
     *
     * @param lead id(s) to send.
     */	
	public function expectedMoveToLeads(Request $request){
		$ids = $request->input('ids');
	 
		if(!empty($ids)){
		foreach($ids as $id){
			
			$lead = Lead::findorFail($id);
			
			if($lead){
				 
					$lead->move_not_interested = 0;						 
					$lead->save();
				 
			}
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Moved successfully...'
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
     * expected Demo Move to lead.
     *
     * @param lead id(s) to send.
     */	
	public function expectedDemoMoveToLeads(Request $request){
		$ids = $request->input('ids');
	 
		if(!empty($ids)){
		foreach($ids as $id){
			
			$lead = Lead::findorFail($id);
			
			if($lead){
				 
					$lead->move_not_interested = 0;						 
					$lead->save();
				 
			}
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Moved successfully...'
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
     * Delete Move to lead.
     *
     * @param lead id(s) to send.
     */	
	public function deleteMoveToLeads(Request $request){
		$ids = $request->input('ids');
//	 echo "<pre>";print_r($ids);die;
		if(!empty($ids)){
		foreach($ids as $id){
			
			$lead = Lead::findorFail($id);
			
			if($lead){
				 DB::table('croma_leads')->where('id',$id)->update(array('deleted_by'=>0,'deleted_at'=>null));
					 
				 
			}
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Moved successfully...'
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
     * expected Demo Move to Demo lead.
     *
     * @param lead id(s) to send.
     */	
	public function expectedDemoMoveToDemoLeads(Request $request){
		$ids = $request->input('ids');	 
		if(!empty($ids)){
		foreach($ids as $id){
			
			$lead = Lead::findorFail($id);
			
			if($lead){				 
			
					
			$demo = new Demo;
			 
			$demo->lead_id = $lead->id;
			 
			$demo->name = $lead->name;
			$demo->email = $lead->email;
			$demo->mobile = $lead->mobile;
			$demo->source = $lead->source;
			$demo->trainer = $lead->trainer;
			$demo->source_name = ($lead->source)?Source::find($lead->source)->name:"";
			$demo->course = $lead->course;
			$demo->course_name = ($lead->course)?Course::find($lead->course)->name:"";
			$demo->sub_courses = $lead->sub_course;		 	 
			$demo->executive_call = 'yes';			 
			$demo->demo_type = 'Calling';			 
			$demo->created_by = $lead->created_by;  
			$demo->owner = $lead->created_by;  	
 
			if($demo->save()){
				$followUp = new DemoFollowUp;
				$followUp->status = Status::where('name','LIKE','Attended Demo')->first()->id;				
				//$followUp->remark = $request->input('counsellor_remark');
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->demo_id = $demo->id;
				$followUp->followby = $demo->created_by;
				if($followUp->save()){			
						$leadFromLeadTable = Lead::findOrFail($lead->id);
						$leadFromLeadTable->demo_attended = 1;
						$leadFromLeadTable->save();
				 
					 
				}else{
					$demo->delete();
					 
				}
			}	
					
				  
			}
		}
		
	return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Moved to Demo successfully...'
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
     * expected Move to lead.
     *
     * @param lead id(s) to send.
     */	
	public function expectedNewBatchMoveToLeads(Request $request){
		$ids = $request->input('ids');
	 
		if(!empty($ids)){
		foreach($ids as $id){
			
			$lead = Lead::findorFail($id);
			
			if($lead){
				 
					$lead->move_not_interested = 0;						 
					$lead->save();
				 
			}
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Moved successfully...'
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
     * Select Delete.
     *
     * @param lead id(s) to send.
     */	
	public function selectDelete(Request $request){
		$ids = $request->input('ids');	 
		if(!empty($ids)){
		foreach($ids as $id){			
			$lead = Lead::findorFail($id);					 
			if($lead){
				 
				DB::table('croma_leads')->where('id',$id)->update(array('deleted_by'=>$request->user()->id,'deleted_at'=>date('Y-m-d H:i:s')));
				 
			}
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Deleted Soft successfully...'
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
     * Select Delete.
     *
     * @param lead id(s) to send.
     */	
	public function selectForwardDelete(Request $request){
		$ids = $request->input('ids');	 
	//	echo "<pre>";print_r($ids);die;
		if(!empty($ids)){
		foreach($ids as $id){			
			$lead = LeadForward::findorFail($id)->delete();					 
			 
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
     * Select selectDeleteParmanent.
     *
     * @param lead id(s) to send.
     */	
	public function selectDeleteParmanent(Request $request){
		$ids = $request->input('ids');	 
		 
		if(!empty($ids)){
		foreach($ids as $id){	
		    $leads = DB::table('croma_lead_follow_ups')->where('lead_id',$id)->delete();	
			$lead = Lead::findorFail($id);	
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
     * Select select to new leads.
     *
     * @param lead id(s) to send.
     */	
	public function selectToNewLeads(Request $request){
		$ids = $request->input('ids');	
		  
		if(!empty($ids)){
		foreach($ids as $id){	
			$leads = DB::table('croma_lead_follow_ups')->where('lead_id',$id)->delete();	
            $lead= Lead::findOrFail($id);
			if($leads){			 
			$followUp = new LeadFollowUp;
			$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;				 
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->lead_id = $id;
			$followUp->followby = $lead->created_by;
			$followUp->save();
			
			}			
			 
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Update New Leads successfully...'
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
     * Get Excel deleted leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getExcelDeletedLeads(Request $request)
    { 
	
	 if($request->input('expert')=="Expert")
	 {  
	 
			$leads = DB::table('croma_leads as leads')
					  ->join('croma_sources as sources','leads.source','=','sources.id')
					  ->join('croma_cat_course as courses','leads.course','=','courses.id')					     
					   ->select('leads.*')					
					   ->whereNotNull('leads.deleted_at');	
					  
                      /*  if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') ){
							if($request->user()->current_user_can('manager')){
							$getID= [$request->user()->id];
							$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
							foreach($manages as $key=>$manage){	 				 
							array_push($getID,$manage->user_id);
							}
							$leads = $leads->whereIn('leads.created_by',$getID);
						
							
							} 
							
							} */
                        
					   $leads= $leads->get();
					  // $leads= $leads->paginate($request->input('length'));
					  
			$returnLeads = [];
			$data = [];
			 
			foreach($leads as $lead){
				$lastStatus = DB::table('croma_lead_follow_ups as lead_follow_ups')
								->join('croma_status as status','status.id','=','lead_follow_ups.status')
								->where('lead_follow_ups.lead_id','=',$lead->id)
								->select('status.name','lead_follow_ups.expected_date_time')
								->orderBy('lead_follow_ups.id','desc')
								->first();
								
				 
				$arr[]  = [
					 
						"Name"=>$lead->name,
						"Mobile"=>$lead->mobile,
						"Source"=>$lead->source_name,
						"Technology"=>$lead->course_name,						 
						"Email"=>$lead->email,
						"Status"=>$lastStatus->name,
						"Expected Date_Time"=>($lastStatus->expected_date_time==NULL)?"":date_format(date_create($lastStatus->expected_date_time),'d-m-y h:s A'),
						"Remark"=>$lead->remarks,
						"Created"=>(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
				];
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('Deleted_leads_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
			 
	}
    }
	
	
	 /**
     * Get matches trainers based on ajax.
     *
     * @param  string
     * @return JSON Object having matched course details
     */
    public function getUserAjax(Request $request)
    {
		if($request->ajax()){
			if(null==$request->input('q')){
				$trainers = User::take(6)->get();
			}else{
				$trainers = User::where('name','LIKE',"%".$request->input('q')."%")->get();
			}
			return response()->json($trainers,200);
		}
	}
	
	
	
	
	
	public function assigncourselead(Request $request){
		
	 
		
	 
		 
		$assigncourse = DB::table('croma_assigncourse')->get();		 
			$userCourses=array();
			if(!empty($assigncourse)){
				foreach($assigncourse as $assignc){
					 $userCourses = 	array_merge($userCourses,unserialize($assignc->assigncourse));
					
			$leads = DB::table('croma_leads as leads');			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*, m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";		
			$rawQuery .= " AND m1.status=4";			   
	    	$startDate = time();
	       // $calldf = date('Y-m-d', strtotime('-1 day', $startDate));
	      //  $calldt = date('Y-m-d', strtotime('-1 day', $startDate));
			$calldf =date('Y-m-d');
			$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($calldf))."'";
			$leads = $leads->orderBy('follow_up_date','ASC');			
			$calldt =date('Y-m-d');
			$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($calldt))."'";			 
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');	 
		//	$leads = $leads->whereNull('leads.deleted_at');
		//	$leads = $leads->where('leads.demo_attended','=','0');
			
			//$leads = $leads->where('leads.move_not_interested','=',$not_interested);			 
			$leads = $leads->whereIn('leads.course',$userCourses);			 
			$leads = $leads->get();	 
	//	 echo "<pre>";print_r($leads); 
				if(!empty($leads)){			 
				foreach($leads as $lead){		 

				$data=array('name'=>$lead->name,"email"=>$lead->email,"mobile"=>$lead->mobile,"source"=>$lead->source,"source_name"=>$lead->source_name,"course"=>$lead->course,"course_name"=>$lead->course_name,"sub_courses"=>$lead->sub_courses,"status"=>1,"status_name"=>"New Lead","created_by"=>$assignc->counsellors);

			//	echo "<pre>";print_r($data);
			//	$last_id= DB::table('croma_leads')->insertGetId($data);	 
				if($last_id){

				$datafoll=array('status'=>1,"expected_date_time"=>date('Y-m-d H:i:s'),"lead_id"=>$last_id);				 
				$lastfID= DB::table('croma_lead_follow_ups')->insertGetId($datafoll);
				if($lastfID){	
				$leads = DB::table('croma_lead_follow_ups')->where('lead_id',$lead->id)->delete();	
				$lead = DB::table('croma_leads')->where('id',$lead->id)->delete();	
				}else{						 
				$lead = DB::table('croma_leads')->where('id',$lead->id)->delete();	
				}

				}
				}

				}  

			 
				}
			}
	}
	
	
	 /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leadjoindededit(Request $request, $id)
    {  
		if($request->ajax()){
			$lead = Lead::findOrFail($id);		 
			$user = User::where('id',$lead->created_by)->first();
			$leadLastFollowUp = DB::table('croma_lead_follow_ups as lead_follow_ups')
							->where('lead_follow_ups.lead_id','=',$id)
							->select('lead_follow_ups.*')
							->orderBy('lead_follow_ups.id','desc')
							->first();
			 
			$courses = Course::all();
			 
			$courseHtml = '';
			if(count($courses)>0){
				foreach($courses as $course){
					if($course->id == $lead->course){
						$courseHtml .= '<option value="'.$course->id.'" selected>'.$course->name.'</option>';
					}else{
						$courseHtml .= '<option value="'.$course->id.'">'.$course->name.'</option>';
					}
				}
			}
			
			$users = User::all();
			 
			$counsellorHtml = '';
			if(count($users)>0){
				foreach($users as $user){
					if($user->id == $lead->created_by){
						$counsellorHtml .= '<option value="'.$user->id.'" selected>'.$user->name.'</option>';
					}else{
						$counsellorHtml .= '<option value="'.$user->id.'">'.$user->name.'</option>';
					}
				}
			}
		 $trainers = FeesGetTrainer::all();			 
			$trainerHtml = '';
			if(!empty($trainers)){
				foreach($trainers as $trainer){
					if($trainer->name == $lead->trainer){
						$trainerHtml .= '<option value="'.$trainer->name.'" selected>'.$trainer->name.'</option>';
					}else{
						$trainerHtml .= '<option value="'.$trainer->name.'">'.$trainer->name.'</option>';
					}
				}
			}
			
			 
			
			 
			 
			$html = '<div class="row">
						<div class="x_content" style="padding:0">';
				 
					$html.= '<form class="form-label-left" onsubmit="return leadFBController.storeleadjoind('.$id.',this)">
								 
								<div class="form-group">
									<div class="col-md-4">
									<label for="email">Name <span class="required">:</span></label>
										<input type="text" name="name" class="form-control col-md-7 col-xs-12" value="'.$lead->name.'">
										 
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
									<label for="email">Email <span class="required">:</span></label>
										<input type="text" name="email" class="form-control col-md-7 col-xs-12" value="'.$lead->email.'">
										 
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label for="mobile">Mobile <span class="required">:</span></label>
										<input type="text" name="mobile" class="form-control col-md-7 col-xs-12" value="'.$lead->mobile.'">
										 
									</div>
								</div>
								 
								 
								<div class="form-group">
									<div class="col-md-4">
										<label>Owner <span class="required">*</span></label>
										<select class="select2_single form-control" name="counsellor" tabindex="-1">
											<option value="">--Counsellor --</option>
											'.$counsellorHtml.'
										</select>
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
										<label>Trainer <span class="required">*</span></label>
										<select class="select2_trainer select2_single form-control" name="trainer" tabindex="-1">
											<option value="">-- SELECT TRAINER --</option> 
											'.$trainerHtml.'
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
					</div> 
					 
					 ';
			
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
    public function storeleadjoind(Request $request, $id)
    { //echo "<pre>";print_r($_GET);die;
		if($request->ajax()){
			$validator = Validator::make($request->all(),[
				 
				'course'=>'required',
						 
				
			]);
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
			
			 
			
			$lead = Lead::find($id);
			$user = User::where('id',$lead->created_by)->first();		 
			 
			$lead->course = $request->input('course');
			$lead->course_name = ($request->input('course'))?Course::find($request->input('course'))->name:"";
			$lead->name = $request->input('name');
			$lead->email = $request->input('email');
			$lead->mobile = $request->input('mobile');
			$lead->created_by = $request->input('counsellor');
			$lead->course = $request->input('course');
			$lead->trainer = $request->input('trainer');
			 
			if($lead->save()){				 
					
					return response()->json(['status'=>1],200);
				}else{
					
					return response()->json(['status'=>0,'errors'=>'Demo not added as first follow up not created...'],400);
				}
			}
				
	}
	
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mailer(Request $request)
    {   
		 
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

        return view('cm_leads.all_mailer_data',['search'=>$search,'users'=>$users]);
    } 
	
	
	/**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginationMailerData(Request $request)
    {  
		if($request->ajax()){
			 
			
			$leads = DB::table('croma_mailer as leads');
			 	 
			$leads = $leads->select('leads.*');	
			$leads = $leads->orderBy('leads.id','DESC');
			
			if($request->input('search.expdf')!==''){
			$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.expdf')),'Y-m-d'));
			}
			if($request->input('search.expdt')!==''){
			$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.expdt')),'Y-m-d'));
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.phone','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
		 
			$leads = $leads->paginate($request->input('length'));
		 
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';					
					 
					$npupMark = '';
					
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						date('d-m-y',strtotime($lead->created_at)),
						$lead->name,
						$lead->phone,
						$lead->email,
						$lead->course,					 
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			 
		}
    } 
	
	
	/**
     * Select Delete.
     *
     * @param lead id(s) to send.
     */	
	public function selectMailerDelete(Request $request){
		$ids = $request->input('ids');	 
	//	echo "<pre>";print_r($ids);die;
		if(!empty($ids)){
		foreach($ids as $id){		
				$lead = MailerTable::findorFail($id);	
			//	echo "<pre>";print_r($lead);die;
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
			'statusCode'=>0,
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
    public function websitefeedback(Request $request)
    {   
		 
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

        return view('cm_leads.all_feedback_data',['search'=>$search,'users'=>$users]);
    } 
	
	
	/**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginationwebsitefeedback(Request $request)
    {  
		if($request->ajax()){
			 
			
			$leads = DB::table('croma_sitefeedback as leads');
			 	 
			$leads = $leads->select('leads.*');	
			$leads = $leads->orderBy('leads.id','DESC');
			
			if($request->input('search.expdf')!==''){
			$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.expdf')),'Y-m-d'));
			}
			if($request->input('search.expdt')!==''){
			$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.expdt')),'Y-m-d'));
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.phone','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
		 
			$leads = $leads->paginate($request->input('length'));
		 
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';					
					 
					$npupMark = '';
					
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						date('d-m-y',strtotime($lead->created_at)),
				    	$lead->name,
						$lead->phone,
						$lead->email,
						$lead->course,					 
						$lead->studentId,					 
						$lead->Q1,					 
						$lead->Q2,					 
						$lead->Q3,					 
						$lead->Q4,					 
						$lead->Q5,					 
						$lead->Q6,					 
						$lead->Q7,					 
						$lead->comment,					 
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			 
		}
    } 
	
	
	
	/**
     * Select Delete.
     *
     * @param lead id(s) to send.
     */	
	public function selectFeedbackDelete(Request $request){
		$ids = $request->input('ids');	 
		//echo "<pre>";print_r($ids);die;
		if(!empty($ids)){
		foreach($ids as $id){		
				$lead = SiteFeedback::findorFail($id);	
				//echo "<pre>";print_r($lead);die;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function counsellorfeedback(Request $request)
    {   
		 
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

        return view('cm_leads.counsellor-feedback',['search'=>$search,'users'=>$users]);
    } 
	
	
	/**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginationCounsellorfeedback(Request $request)
    {  
		if($request->ajax()){
			// echo "<pre>";print_r($request->input('search'));die;
			
			$leads = DB::table('croma_feedback as leads');
			 	 
			$leads = $leads->select('leads.*');	
			$leads = $leads->orderBy('leads.id','DESC');
			
			if($request->input('search.expdf')!==''){
			$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.expdf')),'Y-m-d'));
			}
			if($request->input('search.expdt')!==''){
			$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.expdt')),'Y-m-d'));
			}
			 if($request->input('search.user')!==''){
					$leads = $leads->where('leads.counsellor','=',$request->input('search.user'));
				}
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.phone','LIKE','%'.$request->input('search.value').'%');
				});
			}			
		
			$leads = $leads->paginate($request->input('length'));
		 
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';					
					 
					$npupMark = '';
					
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						date('d-m-y',strtotime($lead->created_at)),
						$lead->name,
						$lead->phone,
						$lead->email,
						$lead->counsellor,					 
						$lead->knowledge,					 
						$lead->optradio,					 
						$lead->consultant,					 
						$lead->comment,					 
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			 
		}
    } 
	
	
	/**
     * Select Delete.
     *
     * @param lead id(s) to send.
     */	
	public function counsellorFeedbackDelete(Request $request){
		$ids = $request->input('ids');	 
		//echo "<pre>";print_r($ids);die;
		if(!empty($ids)){
		foreach($ids as $id){		
				$lead = Feedback::findorFail($id);	
				//echo "<pre>";print_r($lead);die;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leadforward(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::all();
		$courses = Course::all();
		 
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

        return view('cm_leads.lead_forwarded',['sources'=>$sources,'courses'=>$courses,'search'=>$search,'users'=>$users]);
    }
	
	
	
	
	/**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedLeadForward(Request $request)
    {  
		if($request->ajax()){
			 
		 
			$leads = DB::table('croma_leads_forward as leads')->orderBy('id','desc');
			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id','left');
		//	$leads =$leads->join('cromag8l_web.croma_cat_course as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id','left');
			 
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name','users.name as owner_name');
		 
		 
			
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){		
				
			if($request->user()->current_user_can('manager')){
			    
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}else{
				 
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
				
				
			} else{
			
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
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
			 
                $leadstatus =  Demo::where('lead_id',$lead->croma_id)->count();
                if(!empty($leadstatus)){
                $admistatus ="Joined";
                }else{
                $admistatus="";
                }
			 
			 
			 
					$action = '';
					$separator = '';
					$owners = '<span style="">'.$lead->owner_name.'</span>';//color:red
				 
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
    						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getExpectFollowUps('.$lead->id.')" title="Expect Demo FollowUp"  data-toggle="popover" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i class="fa fa-meetup" aria-hidden="true"></i></a>';
    						$separator = ' | ';
							 
    					}
    					
    					if(Auth::user()->current_user_can('edit_lead')){
    						$action .= $separator.'<a href="/lead/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
					
					 }
					
				 $newowner = User::find($lead->forward_to);
				// echo "<pre>";print_r($newowner);
				 
				 if($newowner){
					 $newname =$newowner->name;
				 }else{ 
				 $newname= "";
				 }
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						$lead->name,						 
				    	'<span contenteditable>'.$lead->mobile.'</span>',								 
						substr($lead->old_course_name,0,26),	
						$owners,						
						substr($lead->course_name,0,26),
						$newname,	
						$admistatus,	
				
					];
					$returnLeads['recordCollection'][] = $lead->id;
				//}
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leadForwardForm(Request $request, $id)
    {  
	
	 
		if($request->ajax()){
			$lead = Lead::findOrFail($id);		 
			 
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
					if($course->id == $lead->course){
						$courseHtml .= '<option value="'.$course->id.'" selected>'.$course->name.'</option>';
					}else{
						$courseHtml .= '<option value="'.$course->id.'">'.$course->name.'</option>';
					}
				}
			}
			$coursename =Course::where('id',$lead->course)->first();
			  
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
				 
					$html.= '<form class="form-label-left" onsubmit="return leadFBController.storeLeadForward('.$id.',this)">
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
										<p class="form-control-static" style="display:inline" contenteditable>'.$lead->mobile.'</p>
										<input type="hidden" name="mobile" value="'.$lead->mobile.'">
									</div>
								</div>
								
							 
								<div class="form-group">
									<div class="col-md-6">
										<label>Course <span class="required">*</span></label>
										<p class="form-control-static" style="display:inline">'.$lead->course_name.'</p>
										 
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
							  	<!--<div class="form-group">
									<div class="col-md-4">
										<label>Counsellor <span class="required">*</span></label>
										<select class="select2_assignuser form-control" name="counsellor" tabindex="-1">
											<option value="">-- Select counsellor --</option>
											'.$usersHtml.'
										</select>
									</div>
								</div>-->
							 
							 
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
    public function storeLeadForward(Request $request, $id)
    {
		
	//	echo $id."saveforward";die;
		if($request->ajax()){
 
        
        $validator = Validator::make($request->all(),[       
        'course'=>'required',          
        ]);
             


		 
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
	 
			
				$lead = Lead::find($id);	
			
				$leadForward =  new LeadForward;			
				$leadForward->croma_id = $lead->id;
				$leadForward->name = $lead->name;
				$leadForward->email = $lead->email;		
				$leadForward->code = $lead->code;			
				$leadForward->mobile =$lead->mobile;	
				$leadForward->source = $lead->source;
				$leadForward->source_name = $lead->source_name;				
				$course = Course::where('id',$request->input('course'))->first();
				$leadForward->course = $course->id;
				$leadForward->course_name = $course->name;
				$leadForward->old_course = $lead->course;
				$leadForward->old_course_name = $lead->course_name;
				$leadForward->created_by = $lead->created_by;
				$leadForward->status = 1;
				$leadForward->status_name = "New Lead";				
				$leadForward->save();
				
			//echo "<pre>";print_r($lead);die;
			//$lead =  new Lead;		
			 
			$lead->course = $course->id;
			$lead->course_name = $course->name;
			$lead->status = 1;
			$lead->status_name = "New Lead";	
			$lead->forward_by = $lead->created_by;			 
			$code =$lead->code;
			if($code=='91'){
			$type_lead= "Domestic";
			}else{
			$type_lead= "International";
			}
		
			 //	echo "<pre>";print_r($lead); die;
			if($lead->save()){	
								
			//	$followUp =LeadFollowUp::where('lead_id',$lead->id)->delete();
				leadForwardCounsellor($request->input('course'),$type_lead,$lead,$leadForward);	
			 	$followUp=1;
				/* $user = User::findOrFail($leadForward->created_by);	
				$followUp = new LeadFollowUp;
				$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;	
				$followUp->remark = "old course ".$leadForward->old_course_name." counsellor ".$user->name."";
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->lead_id = $lead->id;				 
				$followUp->save(); */ 
				if($followUp){					 
					return response()->json(['status'=>1],200);
				}else{					 
					return response()->json(['status'=>0,'errors'=>'Lead not added'],400);
				}
			}else{
				return response()->json(['status'=>0,'errors'=>'Lead not added'],400);
			}
			 
		}		
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showseolead(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::where('social',1)->get();
		$courses = Course::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->where('name','!=','Active')->where('name','!=','In Active')->get();
		}else{
		    	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') ){
		    	$statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->orWhere('abgyan_follow_up',1)->get();
		    	}else{
		    	    
		    	   	$statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->get(); 
		    	    
		    	}
		}
		 $users='';
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || $request->user()->current_user_can('show_seo_lead')){
			 			
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

        return view('cm_leads.all_seo_leads',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
	
    /**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedShowSeoLead(Request $request)
    {  
		if($request->ajax()){
			 
		 
			$leads = DB::table('croma_leads as leads');
			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id','left');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id','left');
		//	$leads =$leads->join('cromag8l_web.croma_cat_course as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id','left');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('search.status')!==''){
				$statuses = $request->input('search.status');
				$i=0;
				foreach($statuses as $status){
					if(!$i){
						$rawQuery .= " AND (m1.status=".$status;
						$i=1;
					}else{
						$rawQuery .= " || m1.status=".$status;
					}
				}
				$rawQuery .= ")";
				//$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			if($request->input('search.calldf')!==''){
				$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($request->input('search.calldf')))."'";
				
	        	$leads = $leads->orderBy('follow_up_date','ASC');
			}else{
					$leads = $leads->orderBy('leads.id','desc');
				
			}
			
			if($request->input('search.calldt')!==''){
				$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($request->input('search.calldt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
		//	$leads = $leads->orderBy('leads.id','desc');
			//$leads = $leads->orderBy(DB::raw('(case when `fu`.`expected_date_time` is null then (select `fu`.`id`) else 0 end) desc, `fu`.`expected_date_time`')/*'leads.id'*/,'desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
			   
				$not_interested =1;
			}
			
		//echo $not_interested;
			$leads = $leads->where('leads.move_not_interested','=',$not_interested);
			
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('show_seo_lead')){		
				
			if($request->user()->current_user_can('manager')){
			    
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}else{
				 
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
				
				
			} else{
			
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source') !==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}else{
			    	$leads = $leads->where('leads.source',$request->input('search.source'));
			    
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
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
			 
					$action = '';
					$separator = '';
					$owners = '<span style="">'.$lead->owner_name.'</span>';//color:red
				 
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
    						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getExpectFollowUps('.$lead->id.')" title="Expect Demo FollowUp"  data-toggle="popover" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i class="fa fa-meetup" aria-hidden="true"></i></a>';
    						$separator = ' | ';
							 
    					}
    					
    					if(Auth::user()->current_user_can('edit_lead')){
    						$action .= $separator.'<a href="/lead/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
					
					$action .= $separator.'<a href="javascript:leadFBController.leadForwardForm('.$lead->id.')" title="Lead Assign"><i class="fa fa-send" aria-hidden="true"></i></a>';
					 
					 }
					
					 
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = LeadFollowUp::where('lead_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=15){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						$lead->name,
					//	$lead->mobile,
						$lead->source_name,
						substr($lead->course_name,0,26),
						$owners,
						//$lead->sub_courses,
						$lead->status_name.$npupMark,
						(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-y h:i A'),
						$action
					];
					$returnLeads['recordCollection'][] = $lead->id;
				//}
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
	
	
	public function leadassign(Request $request){
		$leadID=$request->input('leadID');
		$assign=$request->input('assign');
		$courseID=$request->input('courseID');
		$remark=$request->input('remark') ?? '';
		
		$sql=DB::table('croma_leads')->where('id',$leadID)->update(["assign"=>1]);
		
		if($assign==0){
// 			$data=DB::table('croma_leads')->where('id',$leadID)->first();
// 			$ary=[
// 				"name" =>$data->name ?? '',
// 				"email" =>$data->email ?? '',
// 				"code" =>91,
// 				"mobile"=>$data->mobile ?? '',
// 				"source_id"=>$data->source ?? 0,
// 				"source"=>$data->source_name ?? '',
// 				"course_id" =>$data->course ?? 0,
// 				"course" =>$data->course_name ?? '',
// 				"deleted" =>0,
// 				"assigned_to"=>0,
// 				"duplicate"=>0,
// 				"fb_enq" =>1,
// 				"created_at" => date('Y-m-d h:i:s')

// 			];
// 			//dd($data);
// 			$enq=DB::table('croma_enquiries')->insert($ary);
// 			$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1]);
// 			return back()->with('msg','Lead move to unsigned');
		}
		else
		{
		    
		    $sql=DB::table('croma_leads')->where('id',$leadID)->update(["assign"=>1]);
		    date_default_timezone_set("Asia/Calcutta");   
            $dat= date('Y-m-d H:i:s');
            
		    $data=DB::table('croma_leads')->where('id',$leadID)->where('fb_lead_status','3')->orderBy('id','desc')->first();
		    if(!empty($data) && count($data) > 0){
		        $mobile=$data->mobile;
		    $course=$data->course;
		    $participant="offline";
		    $code="91";
		    $name=$data->name;
		    $email=$data->email;
		    $message="";
		    $source=$data->source;//$data->source_name;//FaceBook
		    $from="";
		    $from_name="";
		    $from_title="";
		    $fb_status='1';
		    $owner=$data->facebook_owner;

		    
		    //new start
		    $mobile= ltrim($mobile, '0');	
			$mobile= trim($mobile);	
			$newmobile=  preg_replace('/\s+/', '', $mobile);
			
			$currentdate = date('Y-m-d');

		        // $checklead = Inquiry::where('mobile',$newmobile)->where('course_id',$course)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
		        
            $code ='91';
            if($code=='91'){
            $type_lead= "Domestic";
            
            }else{
            $type_lead= "International";
            }
				
			if($name){
			   $name= $name;
			}else{
			     $name="TBD";
			    
			}
				
			$inquiry = New Inquiry;	

			$inquiry->name=$name;
			$inquiry->email= $email;
			$inquiry->participant= $participant; //offline
			if($message){
				$inquiry->comment= $message;
			}
	    	$inquiry->source_id= $source;  //FaceBook id
	    	$inquiry->fb_status=$fb_status;
			$soursename= Source::findOrFail($source)->name;
			
			$inquiry->source= $soursename; 
			
		 	
			$inquiry->code= "91"; //91			
			$mobile= ltrim($mobile, '0');	
			$mobile= trim($mobile);	
			$newmobile=  preg_replace('/\s+/', '', $mobile);
			$inquiry->mobile =$newmobile;
				
				
				if(is_numeric($course)){
					$coursename=    Course::findOrFail($course);	
					if(!empty($coursename)){
						$course_name =$coursename->name;
						$lead_course_id =$coursename->id;		
						$inquiry->course_id= $lead_course_id;	
						$inquiry->course= $course_name;	
					}else{
						$inquiry->course_id= 0;;	
						$inquiry->course= $course;	
				
					}
					
				}else{
					
					$coursename= Course::where('name', 'like', '%' .$course . '%')->first();
					if(!empty($coursename)){
						
						$lead_course_id =$coursename->id;					
						$inquiry->course_id= $lead_course_id;			
						$course_name= $coursename->name;	
						$inquiry->course= $course_name;	
					}else{
						$inquiry->course_id= 0;	
						$lead_course_id=0;
						$inquiry->course= $course;	
						$course_name =$course;
					}
				 		
				}
				
				$inquiry->form= "FaceBook";	
				 		 
				$inquiry->category="send_enquiry_lead";	
				$inquiry->from_name="NA";//Auth::user()->name;//$request->input('from_name');	
				$inquiry->sub_category="FaceBook";//$request->input('from_title');	
			 
				 
		        $currentdate = date('Y-m-d');
		        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
		       
		        $currentdate = date('Y-m-d');
		        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
		        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
		        
		        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
		         
		        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
		        

		        
		        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
		            dd('1');
					$leadcheck = Lead::where('mobile',$newmobile)->where('course',$lead_course_id)->orderBy('id','desc')->get();	 
				 
						if(!empty($leadcheck) && count($leadcheck)>0)
						{				 
							foreach($leadcheck as $checkv)
							{					  
								if($checkv->status !=4 && $checkv->deleted_at =='')
								{				  
									$var =1;	 			  
								}
								else
								{
									$var =0;
								}
								if($var==1)
								{				  
									$check=1;											  
									break;
								}
								else
								{						  
									$check=0;	
								}
							}
							
							if(!empty($check) && $check>0){
								$inquiry->duplicate=2;				
								$inquiry->save();					 
								//return response()->json(['status'=>1,],200);
								
								//my code add start  "croma_id"=>$enq,
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    							
    							return back()->with('msg','Lead move to unsigned');
    							//my code add end
    							
							}else{						
							 $inquiry->save();					
							leadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark);				
							//return response()->json(['status'=>1,],200);
							
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    
    				            
    							return back()->with('msg','Lead move to unsigned');
    							//my code add end
							}
						
						
						}
						else{
					     	$inquiry->duplicate=2;		
							$inquiry->save();
							//return response()->json(['status'=>1,],200);
							
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    
    				            
    							return back()->with('msg','Lead move to unsigned');
    							
						}			 
					
					
					}
					else if(!empty($checkleadday) && $checkleadday>0)
					{
						//dd('2');
						$leadchecks = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
						if(!empty($leadchecks) && count($leadchecks)>0)
						{				 
							foreach($leadchecks as $checkv){					  
								if($checkv->status !=4 && $checkv->deleted_at =='')
								{				  
									$var =1;	 			  
								}
								else
								{
									$var =0;
								}
								if($var==1)
								{				  
									$check=1;											  
									break;
								}
								else
								{						  
									$check=0;	
								}
							}


						}
						if(!empty($check) && $check>0)
						{
								$inquiry->duplicate=1;				
								$inquiry->save();					 
								//return response()->json(['status'=>1,],200);
								
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    
    				            
    							return back()->with('msg','Lead move to unsigned');
    							
						}
						else
						{
							 $inquiry->save();					
							 leadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark);				
							 //return response()->json(['status'=>1,],200);	
							 
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    
    				            
    							return back()->with('msg','Lead move to unsigned');
    							
						}
					}
					else
					{
						if($inquiry->save())
						{	
							 leadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark);
							 
							$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
                            
							return back()->with('msg','Lead move to unsigned');
							
								 // return response()->json(['status'=>1,],200);
						
						}

		            }
		    }
		    return back()->with('msg','Lead move to unsigned');
		    //new end
		    
		    
		    

		}
	}
	
	
    
    
    
    
    //Facbook Not Interested Start
	public function notInterested(Request $request)
	{
		//dd($request->user()->current_user_can('administrator'));

		$sources = Source::orderBy('name','asc')->get();
		$courses = Course::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->where('name','!=','Active')->where('name','!=','In Active')->get();
		}else{
		    	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
		    	$statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->orWhere('abgyan_follow_up',1)->get();
		    	}else{
		    	    
		    	   	$statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->get(); 
		    	    
		    	}
		}
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
		if(Auth::user()->current_user_can('TL')){
			$capabilitys = Capability::select('user_id')->where('TL',$request->user()->id)->get();		
		    $user_lists=[$request->user()->id];
			foreach($capabilitys as $manage){
				array_push($user_lists,$manage->user_id);		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_lists)->orderBy('name','ASC')->get();
			
		}  
		
			$categorys =  CromaCategory::orderby('category','asc')->get();
 
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}

        return view('cm_leads.notInterestedfb_leads',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users,'categorys'=>$categorys]);
	}


	public function notInterestedPagination(Request $request)
	{
		if($request->ajax()){
			 
		 
			$leads = DB::table('croma_leads as leads');
			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id','left');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id','left');
		//	$leads =$leads->join('cromag8l_web.croma_cat_course as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id','left');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			// if($request->input('search.status')!==''){
			// 	$statuses = $request->input('search.status');
			// 	$i=0;
			// 	foreach($statuses as $status){
			// 		if(!$i){
			// 			$rawQuery .= " AND (m1.status=".$status;
			// 			$i=1;
			// 		}else{
			// 			$rawQuery .= " || m1.status=".$status;
			// 		}
			// 	}
			// 	$rawQuery .= ")";
			// }
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			if($request->input('search.calldf')!==''){
				$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($request->input('search.calldf')))."'";
				
	        	$leads = $leads->orderBy('follow_up_date','ASC');
			}else{
					$leads = $leads->orderBy('created_at','desc');
				
			}


			
			if($request->input('search.calldt')!==''){
				$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($request->input('search.calldt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
		//	$leads = $leads->orderBy('leads.id','desc');
			//$leads = $leads->orderBy(DB::raw('(case when `fu`.`expected_date_time` is null then (select `fu`.`id`) else 0 end) desc, `fu`.`expected_date_time`')/*'leads.id'*/,'desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
			   
				$not_interested =1;
			}
			
		//echo $not_interested;
			$leads = $leads->where('leads.move_not_interested','=',$not_interested);
			
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){		
				
			if($request->user()->current_user_can('manager')){
			    
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}
			else if($request->user()->current_user_can('TL')){
			    
				$getID= [$request->user()->id]; 
				$tls = Capability::select('user_id')->where('TL',$request->user()->id)->get();	 
				foreach($tls as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('leads.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
								
				
			}
			else{
				 
				$leads = $leads->where('leads.created_by','=',$request->user()->id);
				}
				
				
			} else{
			
				if($request->input('search.user')!==''){
					$leads = $leads->where('leads.created_by','=',$request->input('search.user'));
				}
			}
				 
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('leads.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('leads.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('leads.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}


			// dd($request->input('search.status'));


			if($request->input('search.status')!='' && $request->input('search.status')[0]!='1'){
				$leads = $leads->where('leads.fb_lead_status',$request->input('search.status'));
			}

			if($request->input('search.status')[0]=='1'){
				$leads = $leads->where('leads.status','=','1');
			}




			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
			
	    	if($request->input('search.category')!==''){
				$categoryid = $request->input('search.category');		
				//echo	$categoryid;die;	 
				$courseslist = Course::where('category',$categoryid)->get();
				foreach($courseslist as $coursesid){
					$courseCategoryList[] = $coursesid->id;
				}				 
				$leads = $leads->whereIn('leads.course',$courseCategoryList);
			}
			
			$leads=$leads->where('fb_lead','1');
			$leads=$leads->where('leads.fb_lead_status','4');
			//$leads=$leads->where('facbook_to_lead','0');
			
            
			//dd($courseList);
			$leads = $leads->paginate($request->input('length'));
		 
			//dd($leads->toSql());
			$users = User::orderBy('name','ASC')->get();
			$usr = [];
			$sourceid = [];
			foreach($users as $user){
				$usr[$user->id] = $user->name;
			}
			$source =Source::where('social',1)->get();
			foreach($source as $sour){			
				array_push($sourceid,$sour->id);
			}
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['recordCollection'] = [];
			//echo "<pre>";print_r($leads);die;
			
			foreach($leads as $lead){
			 
					$action = '';
					$separator = '';
					$owners = '<span style="">'.$lead->owner_name.'</span>';//color:red

				 
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    				// 	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
    				// 		$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    				// 		$separator = ' | ';
    				// 	}
    				
    				
    				    if($lead->facbook_unassign=='1'){
					 		$color="red";
					 		$display='none';
					 	}
					 	else
					 	{
					 		$color="";
					 		$display='';
					 	}
					 	
    					//if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    						$action .= $separator.'<a href="javascript:leadFBController.getfollowUps('.$lead->id.')" set-id="'.$lead->id.'" set-course="'.$lead->course.'"  class="getid" title="followUp" style="display:'.$display.';"><i class="fa fa-eye" aria-hidden="true"></i> | </a>  ';
    						// $separator = ' | ';
    					//}
    				// 	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
    				// 		$action .= $separator.'<a href="javascript:leadFBController.getExpectFollowUps('.$lead->id.')" title="Expect Demo FollowUp"  data-toggle="popover" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i class="fa fa-meetup" aria-hidden="true"></i></a>';
    				// 		$separator = ' | ';
							 
    				// 	}
    					
    					//if(Auth::user()->current_user_can('edit_lead')){
    						$action .= $separator.'<a href="/fblead/update/'.$lead->id.'" title="Edit" style="display:'.$display.';"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					//}
					
				// 		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('view_lead')){
				// 	$action .= $separator.'<a href="javascript:leadFBController.leadForwardForm('.$lead->id.')" title="Lead Assign"><i class="fa fa-send" aria-hidden="true"></i></a>';
				// 		}
					 }
					
					 
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = LeadFollowUp::where('lead_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=15){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					
					if($lead->course_name){
				     $coursename = '<span title="'.$lead->course_name.'">'.substr($lead->course_name,0,25).'</span>';
				     
				 }else{
				      $coursename ="";
				     
				 }
				 
				 
				 $sourcename="";
				 if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('manager') ||  Auth::user()->current_user_can('administrator')){
					$sourcename= $lead->source_name;
					}else{
						
				     if(in_array($lead->source,$sourceid)){
							$sourcename ="Website";							
						}else{							
							$sourcename= $lead->source_name;
						}
						
					}
					
					if($coursename=='')
					{
						$coursename=$lead->fbcourses;
					}
					else
					{
						$coursename=$coursename;
					}
					

					if(empty($lead->fb_lead_status) ){
						$leadstatus=$lead->status_name.$npupMark;
					}
					else{
						$statusdata=DB::table('croma_status')->where('id',$lead->fb_lead_status)->first();
						$leadstatus=$statusdata->name.$npupMark;
					}
					//$call="<span data-mobile='$lead->mobile' class='call'><i class='fa fa-phone' aria-hidden='true'></i></span>";

					$getowner=DB::table('croma_users')->where('id',$lead->facebook_owner)->first();
					if(!empty($getowner)){
						$FacebookOwnerName=$getowner->name;
					}
					else{
						$FacebookOwnerName='';
					}

					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						$lead->name,
					//	$lead->mobile,
						$sourcename,
						$coursename,
						//$lead->source_name,
						$FacebookOwnerName,//$owners,
						//$lead->sub_courses,
						$leadstatus,//$lead->status_name.$npupMark,
						(new Carbon($lead->created_at))->format('d-m-y h:i:s'),
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-y h:i A'),
						$action,
						
					];
					$returnLeads['recordCollection'][] = $lead->id;
				//}
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
	}


	//Facbook Not Interested End
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    	
	
	
	
	
	//calling start
	public function call(Request $request){
		$email=Auth()->user()->email;
		$mobile="91".$request->input('mobile');
		$name=$request->input('name');
		//"cromacampus.calling@gmail.com"
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.frejun.com/api/v1/integrations/create-call/',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
		    "user_email": "'.$email.'",
		    "transaction_id": "23e4567-e89b-12d3-a456",
		    "job_id": "123678-e89b-12d3-a456",
		    "candidate_id": "1",
		    "candidate_name": "'.$name.'",
		    "candidate_number": "'.$mobile.'"
		}',
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Api-Key BuWB2B7i.TaTyQLsPj7a7UdTBYKU1pwgGdGBgQkuD',
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}
	
	
	public function calllog($number)
	{
		$curl = curl_init();
        
        //$number='9340273433';
        
		curl_setopt_array($curl, array(

		  CURLOPT_URL => 'https://api.frejun.com/api/v1/integrations/calls?candidate_number='.$number.'&page_size=100',

		  CURLOPT_RETURNTRANSFER => true,

		  CURLOPT_ENCODING => '',

		  CURLOPT_MAXREDIRS => 10,
            
            
		  CURLOPT_TIMEOUT => 0,

		  CURLOPT_FOLLOWLOCATION => true,

		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

		  CURLOPT_CUSTOMREQUEST => 'GET',

// 		   CURLOPT_POSTFIELDS =>'{
// 			    "candidate_number": "9962346019",
// 			}',

		  CURLOPT_HTTPHEADER => array(

		    'Authorization: Api-Key BuWB2B7i.TaTyQLsPj7a7UdTBYKU1pwgGdGBgQkuD'

		  ),

		));


		$response = curl_exec($curl);


		curl_close($curl);



		//return $response;
		//$response=json_encode(json_decode($response, true));
		//return $response;
		// foreach(json_decode($response->results) as $member){
		// 	echo $member;
		// }

		$result=json_decode($response, true);
		$results=$result['results'];
		$next=$result['next'];

		return view('cm_leads.calllog',compact('results','next'));
	}
	
	
	
	
	
	
	public function demo3(){
	        date_default_timezone_set("Asia/Calcutta");   
            $dat= date('Y-m-d H:i:s');
            //->where('assign','1')
            $result=DB::table('croma_leads')->where('fb_lead','1')->where('fb_lead_status','3')->whereNull('deleted_at')->where('facbook_unassign','0')->get();
            foreach($result as $resu){
                $leadID=$resu->id;
        		$foll=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->orderBy('id','desc')->first();
        		$remark=$foll->remark;
        		
        		//assign code start
        		$data=DB::table('croma_leads')->where('id',$leadID)->orderBy('id','desc')->first();
		    $mobile=$data->mobile;
		    $course=$data->course;
		    $participant="offline";
		    $code="91";
		    $name=$data->name;
		    $email=$data->email;
		    $message="";
		    $source=$data->source;//$data->source_name;//FaceBook
		    $from="";
		    $from_name="";
		    $from_title="";
		    $fb_status="1";
		    
		    
		    //new start
		    $mobile= ltrim($mobile, '0');	
			$mobile= trim($mobile);	
			$newmobile=  preg_replace('/\s+/', '', $mobile);
			
			$currentdate = date('Y-m-d');

		        
		        
            $code ='91';
            if($code=='91'){
            $type_lead= "Domestic";
            
            }else{
            $type_lead= "International";
            }
				
			if($name){
			   $name= $name;
			}else{
			     $name="TBD";
			    
			}
				
			$inquiry = New Inquiry;	

			$inquiry->name=$name;
			$inquiry->email= $email;
			$inquiry->participant= $participant; //offline
			if($message){
				$inquiry->comment= $message;
			}
	    	$inquiry->source_id= $source;  //FaceBook id
	    	$inquiry->fb_status=$fb_status;
			$soursename= Source::findOrFail($source)->name;
			
			$inquiry->source= $soursename; 
			
		 	
			$inquiry->code= "91"; //91			
			$mobile= ltrim($mobile, '0');	
			$mobile= trim($mobile);	
			$newmobile=  preg_replace('/\s+/', '', $mobile);
			$inquiry->mobile =$newmobile;
				
				
				if(is_numeric($course)){
					$coursename=    Course::findOrFail($course);	
					if(!empty($coursename)){
						$course_name =$coursename->name;
						$lead_course_id =$coursename->id;		
						$inquiry->course_id= $lead_course_id;	
						$inquiry->course= $course_name;	
					}else{
						$inquiry->course_id= 0;;	
						$inquiry->course= $course;	
				
					}
					
				}else{
					
					$coursename= Course::where('name', 'like', '%' .$course . '%')->first();
					if(!empty($coursename)){
						
						$lead_course_id =$coursename->id;					
						$inquiry->course_id= $lead_course_id;			
						$course_name= $coursename->name;	
						$inquiry->course= $course_name;	
					}else{
						$inquiry->course_id= 0;	
						$lead_course_id=0;
						$inquiry->course= $course;	
						$course_name =$course;
					}
				 		
				}
				
				$inquiry->form= "FaceBook";	
				 		 
				$inquiry->category="send_enquiry_lead";	
				$inquiry->from_name="NA";//Auth::user()->name;//$request->input('from_name');	28 feb suman
				$inquiry->sub_category="FaceBook";//$request->input('from_title');	28 feb
			 
				 
		        $currentdate = date('Y-m-d');
		        $checklead = Inquiry::where('course_id',$lead_course_id)->where('mobile',$newmobile)->whereDate('created_at','=',date_format(date_create($currentdate),'Y-m-d'))->get()->count();
		       
		        $currentdate = date('Y-m-d');
		        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
		        $checkleadday = Inquiry::where('mobile',$newmobile)->where('course_id',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
		        
		        $lastdate= date('Y-m-d', strtotime($currentdate. ' - 4 day'));		
		         
		        $checkleadcourse = Inquiry::where('mobile',$newmobile)->where('course_id','!=',$lead_course_id)->whereDate('created_at','>',date_format(date_create($lastdate),'Y-m-d'))->get()->count();
		        

		        
		        if(!empty($checkleadcourse) && $checkleadcourse>0){ 
		            //dd('1');
					$leadcheck = Lead::where('mobile',$newmobile)->where('course',$lead_course_id)->orderBy('id','desc')->get();	 
				 
						if(!empty($leadcheck) && count($leadcheck)>0)
						{				 
							foreach($leadcheck as $checkv)
							{					  
								if($checkv->status !=4 && $checkv->deleted_at =='')
								{				  
									$var =1;	 			  
								}
								else
								{
									$var =0;
								}
								if($var==1)
								{				  
									$check=1;											  
									break;
								}
								else
								{						  
									$check=0;	
								}
							}
							
							if(!empty($check) && $check>0){
								$inquiry->duplicate=2;				
								$inquiry->save();					 
								
								
								//my code add start  ,"croma_id"=>$enq
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    
    				            
    							
    							return back()->with('msg','Lead move to unsigned');
    							//my code add end
    							
							}else{						
							 $inquiry->save();					
							leadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark);				
							//return response()->json(['status'=>1,],200);
							//my code add start  ,"croma_id"=>$enq
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    
    				            
    							return back()->with('msg','Lead move to unsigned');
    							//my code add end
							}
						
						
						}
						else{
					     	$inquiry->duplicate=2;		
							$inquiry->save();
							//return response()->json(['status'=>1,],200);
							//my code add start  ,"croma_id"=>$enq
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    
    				            
    							return back()->with('msg','Lead move to unsigned');
    							//my code add end
						}			 
					
					
					}
					else if(!empty($checkleadday) && $checkleadday>0)
					{
						//dd('2');
						$leadchecks = Lead::where('mobile',trim($newmobile))->where('course',$lead_course_id)->orderBy('id','desc')->get();					 
						if(!empty($leadchecks) && count($leadchecks)>0)
						{				 
							foreach($leadchecks as $checkv){					  
								if($checkv->status !=4 && $checkv->deleted_at =='')
								{				  
									$var =1;	 			  
								}
								else
								{
									$var =0;
								}
								if($var==1)
								{				  
									$check=1;											  
									break;
								}
								else
								{						  
									$check=0;	
								}
							}


						}
						if(!empty($check) && $check>0)
						{
								$inquiry->duplicate=1;				
								$inquiry->save();					 
								//return response()->json(['status'=>1,],200);
								//my code add start  ,"croma_id"=>$enq
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    
    				            
    							return back()->with('msg','Lead move to unsigned');
    							//my code add end
						}
						else
						{
							 $inquiry->save();					
							 leadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark);				
							 //return response()->json(['status'=>1,],200);	
							 //my code add start  "croma_id"=>$enq,
								$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

    							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);
    
    				            
    							return back()->with('msg','Lead move to unsigned');
    							//my code add end
						}
					}
					else
					{
						if($inquiry->save())
						{	
							 leadassignCounsellor($lead_course_id,$type_lead,$inquiry,$remark);
							 //my start ,"croma_id"=>$enq
							$lead=DB::table('croma_leads')->where('id',$leadID)->update(["facbook_unassign"=>1,'created_by'=>$user,"facbook_to_lead"=>1,"status_name"=>"New Lead","created_at"=>$dat]); //facbook_to_lead"=>0

							$follup=DB::table('croma_lead_follow_ups')->where('lead_id',$leadID)->update(["status"=>1,"created_at"=>$dat,"expected_date_time"=>$dat]);

				            
							return back()->with('msg','Lead move to unsigned');
							//my end
								 // return response()->json(['status'=>1,],200);
						
						}

		            }
        		
        		//assign cord end
        		
            }
            
		    
		            return back()->with('msg','Lead move to unsigned');
		    //new end

	}
	
	
	
	
	
	
	public function leadassign1(){
	    
       /* $temp=[];
	    $leads=DB::table('croma_enquiries')->where('source_id',16)->where('source','FaceBook')->where('assigned_to','!=','0')->orderBy('id','desc')->limit(300)->get();
	    foreach($leads as $lead){
	        $ary=[
        	   "mobile"=>$lead->mobile,
        	   "code"=>'91',
        	   "name"=>$lead->name,
        	   "email"=>$lead->email,
        	   "source"=>16,
        	   "source_name"=>'FaceBook',
        	   "course"=>$lead->course_id,
        	   "course_name"=>$lead->course,
        	   "status"=>1,
        	   "type"=>1,
        	   "status_name"=>'New Lead',
        	   "app_status"=>0,
        	   "demo_attended"=>0,
        	   "move_not_interested"=>0,
        	   "deleted_by"=>0,
        	   "created_by"=>$lead->assigned_to,
        	   "forward_by"=>0,
        	   "croma_id"=>$lead->id,
        	   ]; 
        	   
        	   
        	   $croma_id=$lead->id;
        	   $checks=DB::table('croma_leads')->where('croma_id',$croma_id)->first();
                
        	   
        	   if(empty($checks)){
        	       
        	       $leadsaveid=DB::table('croma_leads')->insertGetId($ary);
        	       date_default_timezone_set("Asia/Calcutta");
        	       $dat= date('Y-m-d H:i:s');
        	       DB::table('croma_lead_follow_ups')->insert([
        	        "status" =>1,
        	        "follow_status"=>0,
        	        "expected_date_time"=>$lead->created_at,
        	        "lead_id"=>$leadsaveid,
        	        "followby"=>$lead->assigned_to,
        	        "created_at"=>$lead->created_at,
        	        "updated_at"=>$lead->created_at
        	        ]);
        	       
        	       
        	   
        	   }
        DB::table('croma_leads')->where('fb_lead','0')->where('created_by','0')->get();
	    } */
	    
	    $temp=[];
	    $leads=DB::table('croma_enquiries')->where('source_id',16)->where('source','FaceBook')->where('assigned_to','!=','0')->where('reason','!=','Bucket Full')->where('sub_category','FaceBook')->where('form','FaceBook')->where('fb_status','1')->orderBy('id','desc')->limit(50)->get();
	    
	    foreach($leads as $lead){
	        $ary=[
        	   "mobile"=>$lead->mobile,
        	   "code"=>'91',
        	   "name"=>$lead->name,
        	   "email"=>$lead->email,
        	   "source"=>16,
        	   "source_name"=>'FaceBook',
        	   "course"=>$lead->course_id,
        	   "course_name"=>$lead->course,
        	   "status"=>1,
        	   "type"=>1,
        	   "status_name"=>'New Lead',
        	   "app_status"=>0,
        	   "demo_attended"=>0,
        	   "move_not_interested"=>0,
        	   "deleted_by"=>0,
        	   "created_by"=>$lead->assigned_to,
        	   "forward_by"=>0,
        	   "croma_id"=>$lead->id,
        	   ]; 
        	   
        	   
        	   $croma_id=$lead->id;
        	   //$temp[]=$lead->mobile;
        	   $data=DB::table('croma_leads')->where('mobile',$lead->mobile)->where('fb_lead','0')->first();
        	   if(empty($data) && count($data) == '0'){
            	   $checks=DB::table('croma_leads')->where('croma_id',$croma_id)->first();
                    
            	  
            	   if(empty($checks) && count($checks) == '0'){
            	       
            	       
            	       //$temp[]="<span style='color:green;'>empty</span>".$lead->mobile;
            	       $leadsaveid=DB::table('croma_leads')->insertGetId($ary);
            	       date_default_timezone_set("Asia/Calcutta");
            	       $dat= date('Y-m-d H:i:s');
            	       DB::table('croma_lead_follow_ups')->insert([
            	        "status" =>1,
            	        "follow_status"=>0,
            	        "expected_date_time"=>$lead->created_at,
            	        "lead_id"=>$leadsaveid,
            	        "followby"=>$lead->assigned_to,
            	        "created_at"=>$lead->created_at,
            	        "updated_at"=>$lead->created_at
            	        ]);
            	       
            	       
            	   
            	   }
            	   
        	   }
        	   
       
        
        //DB::table('croma_leads')->where('fb_lead','0')->where('created_by','0')->get();
	    }
	    
	    DB::table('croma_leads')->where("fb_lead",1)->where("facbook_unassign",1)->where('fb_lead_status','!=','3')->update(["facbook_unassign"=>0,"facbook_to_lead"=>1,"created_by"=>0]);
		DB::table('croma_leads')->where("fb_lead",1)->where('fb_lead_status','!=','3')->update(["facbook_unassign"=>0,"facbook_to_lead"=>1,"created_by"=>0]);
		
		
	    
	}
	
	
	
	
	
	
	
	
	
	public function leaddelete(){
	    $temp=[];
        $datas=DB::table('croma_leads')->where('fb_lead','1')->where('fb_lead_status','!=',3)->whereNull('deleted_at')->limit(100)->get();
        foreach($datas as $data){
            $mobile=$data->mobile;
            $email=$data->email;
            $course=$data->course; 
            
            
            $sql=DB::table('croma_leads')->where('fb_lead','0')->where('course',$course)->where('mobile',$mobile)->where('email',$email)->where('status','!=','3')->where('source','16')->delete();
            
            /*$sql=DB::table('croma_leads')->where('fb_lead','0')->where('course',$course)->where('mobile',$mobile)->where('email',$email)->where('status','!=','3')->where('source','16')->get();
            foreach($sql as $list){
            	$temp[]=$list->mobile;
            }*/
        }
	}
	
	
	
	
	public function statuschange(){
	    $leads=DB::table('croma_leads')->where('fb_lead','1')->where('facbook_unassign','0')->where('fb_lead_status','!=','3')->whereNull('deleted_at')->orderBy('id','desc')->limit(500)->get();
        $temp2=[];
        foreach($leads as $lead)
        {
        	$leadid=$lead->id;
        	$fb_lead_status=$lead->fb_lead_status;
            
        	$follow=DB::table('croma_lead_follow_ups')->where('lead_id',$leadid)->orderBy('id','desc')->first();
        	$followid=$follow->id;
            //$temp2[]=$followid;
        	$followupdate=DB::table('croma_lead_follow_ups')->where('id',$followid)->update(["status"=>$fb_lead_status]);
        }
	}
	
	
	
	
	
	public function website(){
	    $data=DB::connection('mysql4')->table('croma_category')->first();
	    dd($data);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
