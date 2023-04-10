<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
use Mail;
use Carbon\Carbon;
//models
use App\Lead;
use App\Source;
use App\Course;
use App\Status;
use App\LeadFollowUp;
use App\Message;
use App\User;
use App\Demo;
use App\DemoFollowUp;
use App\FeesGetTrainer;
use App\FeesGetStudents;
use App\Batch;
use Excel;
use Auth;
use App\Capability;
use Session;
class DemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$sources = Source::all();
		$courses = Course::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->get();
		}else{
		    
		    if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
		    	$statuses = Status::where('demo_filter',1)->orWhere('abgyan_follow_up',1)->get();
		    	}else{
		    	   	$statuses = Status::where('demo_filter',1)->get(); 
		    	}
		    
		}
	 $batches = Batch::orderBy('batch','asc')->get();
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			 			
			$capability = Capability::select('user_id')->get();	 		 
			 $user_list=[];
			foreach($capability as $manage){

			array_push($user_list,$manage->user_id);
			 
		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_list)->orderBy('name','ASC')->get();
		}
		else{
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
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
		//echo "<pre>";print_r($users);die;
        return view('cm_demos.all_leads',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users,'batches'=>$batches]);
    }
    
    public function indexExpecteddemo(Request $request)
    {
		$sources = Source::all();
		$courses = Course::all();
			if(Auth::user()->current_user_can('abgyan_follow_up') ){
		    $statuses = Status::where('abgyan_follow_up',1)->get();
			}else{
			    
			    
			     if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
                $statuses = Status::where('demo_filter',1)->orWhere('abgyan_follow_up',1)->get();
                }else{
                $statuses = Status::where('demo_filter',1)->get();
                
                }
			}
	 
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			 			
			$capability = Capability::select('user_id')->get();	 		 
			 $user_list=[];
			foreach($capability as $manage){

			array_push($user_list,$manage->user_id);
			 
		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_list)->orderBy('name','ASC')->get();
		}
		else{
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
		
        return view('cm_demos.expected_demo',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
	
	
	public function expectedNewBatchDemo(Request $request)
    {
		$sources = Source::all();
		$courses = Course::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->get();
		}else{
		   
		   	
		   	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
                $statuses = Status::where('demo_filter',1)->orWhere('abgyan_follow_up',1)->get();
                }else{
                $statuses = Status::where('demo_filter',1)->get();
                
                }
		    
		}
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			 			
			$capability = Capability::select('user_id')->get();	 		 
			 $user_list=[];
			foreach($capability as $manage){

			array_push($user_list,$manage->user_id);
			 
		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_list)->orderBy('name','ASC')->get();
		}
		else{
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
		
        return view('cm_demos.expected_new_batch_demo',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
	
	public function indexNotInterested(Request $request)
    {
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::all();
		$courses = Course::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->where('name','Not Interested')->where('name','Location Issue')->get();
		}else{
		    	 
		    	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
                $statuses = Status::where('demo_filter',1)->where('name','Not Interested')->where('name','Location Issue')->orWhere('abgyan_follow_up',1)->get();
                }else{
                $statuses = Status::where('demo_filter',1)->where('name','Not Interested')->where('name','Location Issue')->get();
                
                }
		    
		}
		$users = [];
		if(Auth::user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			$users = User::select('id','name')->orderBy('name','ASC')->get();
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
        return view('cm_demos.not_interested_leads',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
	 list of demo join 
	 table demo
     */
	public function joinedDemos(Request $request)
    {
		 
		$sources = Source::all();
		$courses = Course::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->where('name','Close')->get();
		}else{
		    	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
                $statuses = Status::where('demo_filter',1)->where('name','Close')->orWhere('abgyan_follow_up',1)->get();
                }else{
                $statuses = Status::where('demo_filter',1)->where('name','Close')->get();
                }
		}
		$users = [];
		if(Auth::user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			$users = User::select('id','name')->orderBy('name','ASC')->get();
		}else{
			$users = User::select('id','name')->where('id',$request->user()->id)->get();
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
        return view('cm_demos.joined_demos',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$message = Message::where('name','LIKE','%welcome%')->first();
		$sources = Source::all();
		$courses = Course::all();
		$statuses = Status::where('add_demo',1)->get();
		$feesGetTrainer = FeesGetTrainer::orderBy('name','asc')->get();
		//echo "<pre>";print_r($feesGetTrainer);die;
        return view('cm_demos.add_lead_form',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'message'=>$message]);
    }


/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leadjoinded()
    {
		$message = Message::where('name','LIKE','%welcome%')->first();
		$sources = Source::all();
		$courses = Course::all();
		 
		$statuses = Status::where('add_demo',1)->get();
        return view('cm_demos.leadjoinded',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'message'=>$message]);
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
			$validator = Validator::make($request->all(),[
				'name'=>'required',
				'email'=>'email',
				'mobile'=>'required|unique:croma_demos,mobile,NULL,id,email,'.$request->input('email').',course,'.$request->input('course'),
				'source'=>'required',
				'course'=>'required',
				'owner'=>'required',
				'trainer'=>'required',
			],[
				'mobile.unique'=>'Record already exists...'
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
			
			$lead = new Demo;
			if($request->has('id') && $request->input('id') != ''){
				$lead->lead_id = $request->input('id');
			}
			$lead->name = $request->input('name');
			$lead->email = $request->input('email');
            $mobile= trim($request->input('mobile'));	
            $newmobile=  preg_replace('/\s+/', '', $mobile);
            $lead->mobile =$newmobile;	 
            $lead->code = trim($request->input('stud-code'));
			$lead->source = $request->input('source');
			$lead->trainer = $request->input('trainer');
			$lead->status=Status::where('name','LIKE','Attended Demo')->first()->id;
			$lead->source_name = ($request->has('source'))?Source::find($request->input('source'))->name:"";
			$lead->course = $request->input('course');
			$lead->course_name = ($request->has('course')&&Course::find($request->input('course')))?Course::find($request->input('course'))->name:"";
			// $lead->status = $request->input('status');
			// $lead->status_name = Status::find($request->input('status'))->name;
			$lead->sub_courses = $request->input('sub-course');
			$lead->remarks = $request->input('remark');
			if(!$request->has('exec_call')){
				$lead->executive_call = 'no';
			}
			if($lead->executive_call=='no'){
				$lead->demo_type = $request->input('demo_type');
			}
			if($request->input('owner')!=''){
				$lead->created_by = $request->input('owner'); //$request->user()->id;
				$lead->owner = $request->input('owner'); //$request->user()->id;				
			}
			$user = User::where('id',$lead->created_by)->first();
			if($lead->save()){
				$followUp = new DemoFollowUp;
				$followUp->status = Status::where('name','LIKE','Attended Demo')->first()->id;
				//$followUp->remark = $lead->remarks;
				$followUp->remark = $request->input('counsellor_remark');
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->demo_id = $lead->id;
				$followUp->followby = $lead->created_by;
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
 
						//sendSMS($request->input('mobile'),$msg);						
					}
					if($request->has('id') && $request->input('id') != ''){
						
						/*
						$lead = Lead::findorFail($request->input('id'));	 
						$lead->delete();
						$lead_follow_ups = LeadFollowUp::where('lead_id',$request->input('id'))->delete();*/
						
						$leadFromLeadTable = Lead::findOrFail($request->input('id'));
						$leadFromLeadTable->demo_attended = 1;
						$leadFromLeadTable->save();
					}
					return response()->json(['status'=>1],200);
				}else{
					$lead->delete();
					return response()->json(['status'=>0,'errors'=>'Demo not added as first follow up not created...'],400);
				}
			}else{
				return response()->json(['status'=>0,'errors'=>'Demo not added'],400);
			}
		}
    }

	
 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStudents()
    {
		$message = Message::where('name','LIKE','%welcome%')->first();
		$sources = Source::all();
		$courses = Course::all();
		$statuses = Status::where('add_demo',1)->get();
        return view('cm_demos.add_lead_form_students',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'message'=>$message]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStudents(Request $request)
    {
 
        if($request->ajax()){
			$validator = Validator::make($request->all(),[
				'name'=>'required',
				'email'=>'email',
				'mobile'=>'required|digits:15|unique:croma_demos,mobile,NULL,id,email,'.$request->input('email').',course,'.$request->input('course'),
			//	'source'=>'required',
				'course'=>'required',
				//'owner'=>'required',
				//'status'=>'required',
			],[
				'mobile.unique'=>'Record already exists...'
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
			
			$lead = new Demo;
			if($request->has('id') && $request->input('id') != ''){
				$lead->lead_id = $request->input('id');
			}
			$lead->name = $request->input('name');
			$lead->email = $request->input('email');
			$lead->mobile = $request->input('mobile');
			$source = $request->input('source');
			if(!empty($source)){
			$lead->source = $request->input('source');
			$lead->source_name = ($request->has('source'))?Source::find($request->input('source'))->name:"";
			}else{
			$lead->source = '11';
			$lead->source_name = ($request->has('source'))?Source::find('11')->name:"";
			} 
			
		 
			$lead->course = $request->input('course');
			$lead->course_name = ($request->has('course')&&Course::find($request->input('course')))?Course::find($request->input('course'))->name:"";
				$lead->status=Status::where('name','LIKE','Attended Demo')->first()->id;
			// $lead->status = $request->input('status');
			// $lead->status_name = Status::find($request->input('status'))->name;
			$lead->sub_courses = $request->input('sub-course');
			$lead->remarks = $request->input('remark');
			if(!$request->has('exec_call')){
				$lead->executive_call = 'no';
			}
			if($lead->executive_call=='no'){
				$lead->demo_type = $request->input('demo_type');
			}
			
			if($request->input('course')!=''){
				$course = Course::findorFail($request->input('course'));
				$counsellors = unserialize($course->counsellors);
				 
				 if($request->input('owner')!=''){				
				$lead->created_by = $request->input('owner'); //$request->user()->id;
				$lead->owner = $request->input('owner'); //$request->user()->id;				
			}else{
				if(count($counsellors)==1){					
				$lead->created_by = $counsellors[0];  
				$lead->owner =$counsellors[0];  
				}else{
					$lead->created_by = '72'; 
					$lead->owner ='72';  					

				}				
			}
			}
			
		 
			
			
			
			/*
			if($request->input('owner')!=''){
				$lead->created_by = $request->input('owner'); //$request->user()->id;
				$lead->owner = $request->input('owner'); //$request->user()->id;				
			}*/
			$user = User::where('id',$lead->created_by)->first();
			if($lead->save()){
				$followUp = new DemoFollowUp;
				$followUp->status = Status::where('name','LIKE','Attended Demo')->first()->id;
				//$followUp->remark = $lead->remarks;
				$followUp->remark = $request->input('counsellor_remark');
				$followUp->demo_id = $lead->id;
				$followUp->followby = $lead->created_by;
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
 
						//sendSMS($request->input('mobile'),$msg);						
					}
					if($request->has('id') && $request->input('id') != ''){
						
					 
						$leadFromLeadTable = Lead::findOrFail($request->input('id'));
						$leadFromLeadTable->demo_attended = 1;
						$leadFromLeadTable->save();
					}
					return response()->json(['status'=>1],200);
				}else{
					$lead->delete();
					return response()->json(['status'=>0,'errors'=>'Demo not added as first follow up not created...'],400);
				}
			}else{
				return response()->json(['status'=>0,'errors'=>'Demo not added'],400);
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
		$lead = Demo::find($id);
		$sources = Source::all();
		$courses = Course::all();
		$statuses = Status::all();
		$users = $courseCounsellors = [];
		$course = Course::findOrFail($lead->course);
		if($course){
			if(!is_null($course->counsellors)){
				$courseCounsellors = unserialize($course->counsellors);
			}	
		}
		if(Auth::user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator') || $request->user()->current_user_can('manager') || $request->user()->current_user_can('lead_demo_all')){
			$users = User::select('id','name')->get();
		}
		else{
			$users = User::select('id','name')->whereIn('id',$courseCounsellors)->get();
		}
        return view('cm_demos.lead_update',['lead'=>$lead,'sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'users'=>$users,'courseCounsellors'=>$courseCounsellors]);
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
			'mobile'=>'digits:15|unique:croma_demos,mobile,'.$id.',id,email,'.$request->input('email').',course,'.$request->input('course'),
			'source'=>'required',
			'course'=>'required',
			'owner'=>'required',
		],[
			'mobile.unique'=>'Already Exists...'
		]);
		if($validator->fails()){
            return redirect('demo/update/'.$id)
                        ->withErrors($validator)
                        ->withInput();
		}
		
		$lead = Demo::find($id);
		$lead->name = $request->input('name');
		$lead->email = $request->input('email');
		$mobile= trim($request->input('mobile'));	
		$newmobile=  preg_replace('/\s+/', '', $mobile);
		$lead->mobile =$newmobile;	 
		$lead->code = trim($request->input('stud-code'));
		$lead->source = $request->input('source');
		$lead->source_name = Source::find($request->input('source'))->name;
		$lead->course = $request->input('course');
		$lead->course_name = Course::find($request->input('course'))->name;
		// $lead->status = $request->input('status');
		// $lead->status_name = Status::find($request->input('status'))->name;
		$lead->sub_courses = $request->input('sub-course');
		$lead->executive_call = $request->input('exec_call');
		if($request->input('exec_call')=='no'){
			$lead->demo_type = $request->input('demo_type');
		}else{
			$lead->demo_type = 'Calling';
		}
		$lead->remarks = $request->input('remark');
		$lead->created_by = $request->input('owner');
		$lead->owner = $request->input('owner');
		
		if($lead->save()){
			$request->session()->flash('alert-success', 'Demo successfully updated !!');
			return redirect(url('/demo/all-leads'));
		}else{
			$request->session()->flash('alert-danger', 'Demo not updated !!');
			return redirect(url('/demo/update/'.$id));			
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
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('soft_delete_demo'))){
			try{
				
				$lead = Demo::findorFail($id);				 
				$lead->delete();
				if($lead->trashed()){
					$demo = Demo::where('id',$id)->update(array('deleted_by'=>$request->user()->id));
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Demo not found'],200);
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
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_sources as sources','demos.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			$leads = $leads->join('croma_users as users','demos.created_by','=','users.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
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
					$leads = $leads->orderBy('demos.id','desc');
				
			}			
			if($request->input('search.calldt')!==''){
				$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($request->input('search.calldt')))."'";
			}
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			$leads = $leads->orderBy('demos.id','desc');
			 
			$leads = $leads->whereNull('demos.deleted_at');
			$leads = $leads->where('demos.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			if($request->has('search.joined_demos')){
				$not_interested = 2;
			}
			$leads = $leads->where('demos.move_not_interested','=',$not_interested);
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('demos.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}
			else if($request->user()->current_user_can('TL')){
				$getID= [$request->user()->id];
			   $manages = Capability::select('user_id')->where('TL',$request->user()->id)->get();	 
			   foreach($manages as $key=>$manage){	 				 
				   array_push($getID,$manage->user_id);
			   }
				$leads = $leads->whereIn('demos.created_by',$getID);

			   if($request->input('search.user')!==''){
			   $leads = $leads->where('demos.created_by','=',$request->input('search.user'));
			   }
		   }
			else{
				$leads = $leads->where('demos.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}

			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('demos.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('demos.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('demos.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('demos.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('demos.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('demos.course',$courseList);
			}
			$leads = $leads->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['demoRecordCollection'] = [];
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';
			    	if($request->has('search.not_interested')){
					
					}elseif($request->has('search.joined_demos')){
					
					}
					else{
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_remark')){
						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_follow_up')){
						$action .= $separator.'<a href="javascript:demoController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('edit_demo')){
						$action .= $separator.'<a href="/demo/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_follow_up')){
						$action .= $separator.'<a href="calllog/'.$lead->mobile.'" class="calllog" call-attr="'.$lead->mobile.'" target="_blank" style="display:'.$display.';"><i class="fa fa-headphones" aria-hidden="true"></i></a>';
						
					}
					
					}
				
				/*	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('soft_delete_demo')){
						$action .= $separator.'<a href="javascript:demoController.delete('.$lead->id.')" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
						
					}*/
					
					/*$separator = ' | ';
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('send_mail')){
						$action .= $separator.'<a href="javascript:demoController.sendMail('.$lead->id.')" title="Send Mail"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>';
						$separator = ' | ';
					} */
					
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = DemoFollowUp::where('demo_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=9){
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
						(new Carbon($lead->created_at))->format('d-m-y'),
						$lead->name,
					//	$lead->mobile,
						'<strong>'.$lead->demo_type.'</strong>',
						//$lead->source_name,
						$coursename,
						$lead->owner_name,
						$lead->trainer,
						$lead->status_name.$npupMark,
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":date_format(date_create($lead->expected_date_time),'d-m-y h:s A'),
						 
						$action
					];
					$returnLeads['demoRecordCollection'][] = $lead->id;
				 
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
    public function getPaginatedJoinedDemos(Request $request)
    {   
		if($request->ajax()){
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_sources as sources','demos.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			$leads = $leads->join('croma_users as users','demos.created_by','=','users.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) left JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
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
			
		 
			if($request->input('search.calldf')!==''){
			     
				$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($request->input('search.calldf')))."'";
				$leads = $leads->orderBy('follow_up_date','ASC');
			}else{
					$leads = $leads->orderBy('demos.id','desc');
				
			}
			
			if($request->input('search.calldt')!==''){
				$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($request->input('search.calldt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			$leads = $leads->orderBy('demos.id','desc');
			 
			$leads = $leads->whereNull('demos.deleted_at');
			$leads = $leads->where('demos.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			if($request->has('search.joined_demos')){
				$not_interested = 2;
			}
			$leads = $leads->where('demos.move_not_interested','=',$not_interested);
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('demos.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}else{
				$leads = $leads->where('demos.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}

			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('demos.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%')
						   ->orWhere('demos.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('demos.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('demos.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('demos.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			
			
			if($request->input('search.joindf')!==''){
				$leads = $leads->whereDate('demos.join_date','>=',date_format(date_create($request->input('search.joindf')),'Y-m-d'));
			}
			if($request->input('search.joindt')!==''){
				$leads = $leads->whereDate('demos.join_date','<=',date_format(date_create($request->input('search.joindt')),'Y-m-d'));
			}
			
			 
			
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('demos.course',$courseList);
			}
			$leads = $leads->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['demoRecordCollection'] = [];
			//echo "<pre>";print_r($leads);die;
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';
			    	if($request->has('search.not_interested')){
					
					}elseif($request->has('search.joined_demos')){
					
					}
					else{
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_remark')){
						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_follow_up')){
						$action .= $separator.'<a href="javascript:demoController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('edit_demo')){
						$action .= $separator.'<a href="/demo/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					}
				
				/*	if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('soft_delete_demo')){
						$action .= $separator.'<a href="javascript:demoController.delete('.$lead->id.')" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>';
						
					}*/
					
					/*$separator = ' | ';
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('send_mail')){
						$action .= $separator.'<a href="javascript:demoController.sendMail('.$lead->id.')" title="Send Mail"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>';
						$separator = ' | ';
					} */
					
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = DemoFollowUp::where('demo_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=9){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					if(!empty($lead->join_date)){
					    
					    $joindate =date('d-M-y',strtotime($lead->join_date));
					}else{
					    
					    $joindate="";
					}
					
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						(new Carbon($lead->join_date))->format('d-m-y'),
						$joindate,
						$lead->name,
						$lead->source_name,
						$lead->demo_type,
						$lead->course_name,
						$lead->owner_name,
						//$lead->trainer,						
						$lead->status_name,	
				        ($lead->follow_up_date==NULL)?"":(new Carbon($lead->follow_up_date))->format('d-m-y h:i A'),
						($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-y h:i A'),
						 
					];
					$returnLeads['demoRecordCollection'][] = $lead->id;
				 
			}
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
	
	 /**
     * Get Expected paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getExpectedPaginatedLeads(Request $request)
    {   
		if($request->ajax()){
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_sources as sources','demos.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			$leads = $leads->join('croma_users as users','demos.created_by','=','users.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
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
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			$leads = $leads->orderBy('demos.id','desc');
			 
			$leads = $leads->whereNull('demos.deleted_at');
			$leads = $leads->where('demos.demo_attended','=','0');
			$not_interested = 3;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			if($request->has('search.joined_demos')){
				$not_interested = 2;
			}
			$leads = $leads->where('demos.move_not_interested','=',$not_interested);
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('demos.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}else{
				$leads = $leads->where('demos.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}

			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('demos.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('demos.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('demos.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('demos.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('demos.course',$courseList);
			}
			$leads = $leads->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['demoRecordCollection'] = [];
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';
			    	if($request->has('search.not_interested')){
					
					}elseif($request->has('search.joined_demos')){
					
					}
					else{
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_remark')){
						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_follow_up')){
						$action .= $separator.'<a href="javascript:demoController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('edit_demo')){
						$action .= $separator.'<a href="/demo/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					}
				
				  
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = DemoFollowUp::where('demo_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=9){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						(new Carbon($lead->created_at))->format('d-m-y'),
						$lead->name,
					//	$lead->mobile,
						'<strong>'.$lead->demo_type.'</strong>',
						//$lead->source_name,
						$lead->course_name,
						$lead->owner_name,
						//$lead->sub_courses,
						$lead->status_name.$npupMark,
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":date_format(date_create($lead->expected_date_time),'d-m-y h:s A'),
						 
						$action
					];
					$returnLeads['demoRecordCollection'][] = $lead->id;
				 
			}
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	  /**
     * Get Expected paginated new batch demo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getExpectednewbatchdemo(Request $request)
    {   
		if($request->ajax()){
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_sources as sources','demos.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			$leads = $leads->join('croma_users as users','demos.created_by','=','users.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
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
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			$leads = $leads->orderBy('demos.id','desc');
			 
			$leads = $leads->whereNull('demos.deleted_at');
			$leads = $leads->where('demos.demo_attended','=','0');
			$not_interested = 4; //expected demo 3, new batch demo 4, not interested 1 , joind demo 2
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			if($request->has('search.joined_demos')){
				$not_interested = 2;
			}
			$leads = $leads->where('demos.move_not_interested','=',$not_interested);
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('demos.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}else{
				$leads = $leads->where('demos.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}

			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('demos.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('demos.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('demos.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('demos.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('demos.course',$courseList);
			}
			$leads = $leads->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['demoRecordCollection'] = [];
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';
			    	if($request->has('search.not_interested')){
					
					}elseif($request->has('search.joined_demos')){
					
					}
					else{
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_remark')){
						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_follow_up')){
						$action .= $separator.'<a href="javascript:demoController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('edit_demo')){
						$action .= $separator.'<a href="/demo/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					}
				
									
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = DemoFollowUp::where('demo_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=9){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						(new Carbon($lead->created_at))->format('d-m-y'),
						$lead->name,
					//	$lead->mobile,
						'<strong>'.$lead->demo_type.'</strong>',
						//$lead->source_name,
						$lead->course_name,
						$lead->owner_name,
						//$lead->sub_courses,
						$lead->status_name.$npupMark,
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":date_format(date_create($lead->expected_date_time),'d-m-y h:s A'),
						 
						$action
					];
					$returnLeads['demoRecordCollection'][] = $lead->id;
				 
			}
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
	
    /**
     * Get demos excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
    public function getDemosExcel(Request $request)
    {    
        
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_sources as sources','demos.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			/*if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}*/
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('demos.id','desc');
			$leads = $leads->whereNull('demos.deleted_at');
			$leads = $leads->where('demos.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			if($request->has('search.joined_demos')){
				$not_interested = 2;
			}
			$leads = $leads->where('demos.move_not_interested','=',$not_interested);
		
			
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') ){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('demos.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}else{
				$leads = $leads->where('demos.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('demos.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('demos.source',$request->input('search.source'));
			}
			
			if($request->input('search.leaddf')!=''){
				$leads = $leads->whereDate('demos.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!=''){
			    
				$leads = $leads->whereDate('demos.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			//join date filter start 
			
			if($request->input('search.joindf')!=''){
			    //dd('sds');
				$leads = $leads->whereDate('demos.join_date','>=',date_format(date_create($request->input('search.joindf')),'Y-m-d'));
			}
			if($request->input('search.joindt')!=''){
			    
				$leads = $leads->whereDate('demos.join_date','<=',date_format(date_create($request->input('search.joindt')),'Y-m-d'));
			}
			//join date filter end 
			
			if($request->input('search.course')!==''){
				 
				$leads = $leads->where('demos.course',$request->input('search.course'));
			}
			//$leads = $leads->paginate($request->input('length'));
			$leads = $leads->get();
			 
			$returnLeads = [];
			$data = [];
			//$returnLeads['draw'] = $request->input('draw');
			//$returnLeads['recordsTotal'] = $leads->total();
			//$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['recordCollection'] = [];
	 
			foreach($leads as $lead){
				 
			  $htmlremak='';
                $remarks =  DemoFollowUp::where('demo_id',$lead->id)->orderby('id','desc')->get();
                if($remarks){ 					 
                foreach($remarks as $remark){
                if($remark->remark){
                $htmlremak .=$remark->remark.'<br>';
                }
                }
                }
					$arr[] = [
						
						"Name"=>$lead->name,
						"Mobile"=>$lead->mobile,
						"Source"=>$lead->source_name,
					//	"Demo Type"=>$lead->demo_type,
						"Course"=>$lead->course_name,					 
						"Email"=>$lead->email,
						"Status"=>$lead->status_name,
						"Expected_Date_Time"=>($lead->expected_date_time==NULL)?"":date_format(date_create($lead->expected_date_time),'m/d/Y'),
						"Remarks"=>$htmlremak,
				    	"Created"=>date_format(date_create($lead->created_at),'m/d/Y'),
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				
			}
			$excel = \App::make('excel');
			Excel::create('all_demos_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		//}
    }
	
    /**
     * Get getdemosexcelNotInterested excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
    public function getdemosexcelNotInterested(Request $request)
    {    
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_sources as sources','demos.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			/*if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}*/
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('demos.id','desc');
			$leads = $leads->whereNull('demos.deleted_at');
			$leads = $leads->where('demos.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			if($request->has('search.joined_demos')){
				$not_interested = 2;
			}
			$leads = $leads->where('demos.move_not_interested','=',$not_interested);
			 
			
	    	if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') ){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('demos.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}else{
				$leads = $leads->where('demos.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('demos.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('demos.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('demos.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('demos.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				 
				$leads = $leads->where('demos.course',$request->input('search.course'));
			}
		 
			$leads = $leads->get();
			 
			$returnLeads = [];
			$data = [];
	//	 echo "<pre>";print_r($request->input('search'));print_r($leads);die;
			$returnLeads['recordCollection'] = [];
			foreach($leads as $lead){
				 
					$arr[] = [
						"Demo Date"=>date_format(date_create($lead->created_at),'d-m-y'),
						"Name"=>$lead->name,
						"Mobile"=>$lead->mobile,
						"Demo Type"=>$lead->demo_type,
						"Technology"=>$lead->course_name,
						//"Sub. Technology"=>$lead->sub_courses,
						"Email"=>$lead->email,
						"Status"=>$lead->status_name,
						"Expected Date"=>($lead->expected_date_time)?"":date_format(date_create($lead->expected_date_time),'d-m-y'),
						"Remark"=>$lead->remarks,
						 
					];
					$returnLeads['recordCollection'][] = $lead->id;
				 
			}
			$excel = \App::make('excel');
			Excel::create('NI_demos_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		//}
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
        return view('cm_demos.deleted_leads',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses]);
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
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_sources as sources','demos.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			//$leads = $leads->join('status','leads.status','=','status.id');
			$leads = $leads->select('demos.*','sources.name as src_name','courses.name as crs_name');
			 
			if($request->input('search.value')!==''){
    			$leads = $leads->where(function($query) use($request){
    			$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
    			->orWhere('demos.email','LIKE','%'.$request->input('search.value').'%')
    			->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%')
    			->orWhere('demos.course_name','LIKE','%'.$request->input('search.value').'%')
    			->orWhere('demos.sub_courses','LIKE','%'.$request->input('search.value').'%');
    			});
			}
		
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') ){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('demos.created_by',$getID);

			 
			
			 
			}else{
				$leads = $leads->where('demos.created_by','=',$request->user()->id);
				}
			} 
		
			$leads = $leads->whereNotNull('demos.deleted_at');
			$leads = $leads->orderBy('demos.id','desc');
			$leads = $leads->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			foreach($leads as $lead){
				$lastStatus = DB::table('croma_demo_follow_ups as demo_follow_ups')
								->join('croma_status as status','status.id','=','demo_follow_ups.status')
								->where('demo_follow_ups.demo_id','=',$lead->id)
								->select('status.name as status_name')
								->orderBy('demo_follow_ups.id','desc')
								->first();
				$data[] = [
				    "<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
					$lead->name,
					$lead->email,
				//	$lead->mobile,
					$lead->src_name,
					$lead->crs_name,
					$lead->sub_courses,
					$lastStatus->status_name,
					$lead->remarks,
					date_format(date_create($lead->created_at),'d-m-y'),
					'<a href="javascript:deletedDemoController.restore('.$lead->id.')"><i class="fa fa-undo" aria-hidden="true" title="Restor"></i></a>'.' | '.
					'<a href="javascript:deletedDemoController.delete('.$lead->id.')"><i class="fa fa-trash" aria-hidden="true" title="Delete"></i></a>'
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
				$lead = Demo::where('id',$id)->whereNotNull('deleted_at');
				if($lead->restore()){
					$demo  = Demo::where('id',$id)->update(array('deleted_by'=>0));
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
				$lead = Demo::where('id',$id)->whereNotNull('deleted_at');
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
			$lead = Demo::find($id);			 
			$user = User::where('id',$lead->created_by)->first();
			$leadLastFollowUp = DB::table('croma_demo_follow_ups as demo_follow_ups')
							->where('demo_follow_ups.demo_id','=',$id)
							->select('demo_follow_ups.*')
							->orderBy('demo_follow_ups.id','desc')
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
			$messages = Message::where('permission','LIKE','%G%')->where('all_demo','1')->orWhere('course',$lead->course)->select('id','name')->get();
			$messageHtml = '';
			if(count($messages)>0){
				foreach($messages as $message){
					$messageHtml .= '<option value="'.$message->id.'">'.$message->name.'</option>';
				}
			}
				if(Auth::user()->current_user_can('abgyan_follow_up') ){
		        	$statuses = Status::where('abgyan_follow_up',1)->get();
				}else{
				    
                    if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
                    $statuses = Status::where('demo_follow_up',1)->orWhere('abgyan_follow_up',1)->get();
                    }else{
                    $statuses = Status::where('demo_follow_up',1)->get();
                    }
        				    	
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
			
		 
			
			if(!empty($lead->code)){
				$mobilecode='+'.$lead->code.' - ';
				
				$newcode ='+'.$lead->code;
			}else{
				$mobilecode="";
				$newcode="+91";
			}
			if(Auth::user()->current_user_can('mask_number')){
				$number="********".substr($lead->mobile,8);
			}
			else{
				$number=$lead->mobile;
			}
			if(Auth::user()->current_user_can('super_admin')  ||  Auth::user()->current_user_can('administrator')){
			     $display="inline";  
			}
			else{
			    $display="none";
			}
			$html = '<div class="row">
						<div class="x_content" style="padding:0">
							<form class="form-label-left" onsubmit="return demoController.storeFollowUp('.$id.',this)">
								<div class="form-group">
									<div class="col-md-4">
										<label for="name">Name<span class="required">:</span></label>
										<!--input type="text" name="name" class="form-control-static col-md-7 col-xs-12" value="'.$lead->name.'"-->
										<p class="form-control-static" style="display:inline">'.$lead->name.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
									<label for="email">Email <span class="required">:</span></label>
										<!--input type="text" name="email" class="form-control col-md-7 col-xs-12" value="'.$lead->email.'"-->
										<p class="form-control-static" style="display:inline">'.$lead->email.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label for="mobile">Mobile <span class="required">:</span></label>
										<!--input type="text" name="mobile" class="form-control col-md-7 col-xs-12" value="'.$number.'"-->
										<p class="form-control-static" style="display:inline" onclick="copyToClipboard('.$number.')">'.$mobilecode.''.$number.'</p>  <a href="https://wa.me/'.$newcode.''.$lead->mobile.'" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true" style="color:#06e906;margin-left: 10px;font-size: 18px;"></i></a>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label>Source <span class="required">:</span></label>
										<!--select class="select2_single form-control" name="source" tabindex="-1">
											<option value="">-- SELECT SOURCE --</option>
											'.$sourceHtml.'
										</select-->
										<p class="form-control-static" style="display:'.$display.'">'.$sourceObj->name.'</p>
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
										<!--textarea name="remarks" rows="1" class="form-control col-md-7 col-xs-12">'.$lead->remarks.'</textarea-->
										<p class="form-control-static" style="display:inline">'.$lead->remarks.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label>Type <span class="required">:</span></label>
										<p class="form-control-static" style="display:inline">'.ucfirst('demo').'</p>
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
										<label>Technology <span class="required">*</span></label>
										<select class="select2_single form-control sms-control select2_course" name="course" tabindex="-1">
											<option value="">-- SELECT TECHNOLOGY --</option>
											'.$courseHtml.'
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label>Status <span class="required">*</span></label>
										<select class="select2_single form-control" name="status" tabindex="-1">
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
										<label for="remark">Counsellor Remark <span class="required">*</span></label>
										<textarea name="remark" rows="1" class="form-control col-md-7 col-xs-12"></textarea>
									</div>
								</div>
								<div class="form-group">
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
										<button type="submit" class="btn btn-success btn-block">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<p style="margin-top:10px;margin-bottom:-5px;"><strong>Follow Up Status</strong>  <select onchange="javascript:demoController.getAllFollowUps()" class="follow-up-count"><option value="5">Last 5</option><option value="all">All</option></select></p>
					<div class="table-responsive">
						<table id="datatable-demoFollowups" class="table table-bordered table-striped table-hover">
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
    public function storeFollowUp(Request $request, $id)
    {
		if($request->ajax()){
			$validator = Validator::make($request->all(),[
				 
				'course'=>'required',
				'status'=>'required',
				//'expected_date_time'=>'required',
				'remark'=>'required',
				'message'=>'required',
			]);
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
			
			// check now expected date and time if status is not - not interested/location issue
			$statusModel = Status::find($request->input('status'));
			//if($statusModel->name!='Not Interested' && $statusModel->name!='Location Issue'){
			if($statusModel->show_exp_date){
				$validator = Validator::make($request->all(),[
					'expected_date_time'=>'required',
				]);
				if($validator->fails()){
					$errorsBag = $validator->getMessageBag()->toArray();
					return response()->json(['status'=>1,'errors'=>$errorsBag],400);
				}				
			}
			
			$lead = Demo::find($id);
			$user = User::where('id',$lead->created_by)->first();
			 
			$lead->course = $request->input('course');	
	    	$lead->status = $request->input('status');	
	     
			if($lead->save()){
				$leadFollowUp = new DemoFollowUp;
				$status = Status::findorFail($request->input('status'));
				if(!strcasecmp($status->name,'npup')){
					$npupCount = DemoFollowUp::where('demo_id',$id)->where('status',$status->id)->count();
					if($npupCount>=9){
						$status = Status::where('name','LIKE','Not Interested')->first();
						$leadFollowUp->status = $status->id;
					}else{
						$leadFollowUp->status = $request->input('status');
					}
				}else{
					$leadFollowUp->status = $request->input('status');
				}
				$leadFollowUp->remark = trim($request->input('remark'));
				$leadFollowUp->demo_id = $id;
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
				 
						//sendSMS($lead->mobile,$msg);
					}
					
					
							
			$todays = strtotime("now");
			 $leadlast = DB::table('croma_demo_follow_ups as lead_follow_ups')
							->join('croma_demos as leads','leads.id','=','lead_follow_ups.demo_id')
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
		//	$minutes = floor(($diff - ($hours*60*60)) / 60);
		//	$seconds = floor(($diff - ($hours*60*60)) - ($minutes*60));
			
			$minutes = floor($diff / (60));
			$seconds = floor(($diff - ($minutes*60)));
			
			// status count
			
			$status = Status::findorFail($request->input('status'));
			$leads = DB::table('croma_demos as leads');
			$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE '".$status->name."') AND DATE(m1.created_at)='".date('Y-m-d', $todays)."' ";
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`demo_id`'));
			$leads = $leads->where('leads.created_by','=',Auth::user()->id);
			$count= $leads->count();	
			 $subjects = 'Demo | '.$user->name.' | '.$minutes.' | '.$status->name.' ('.$count.')';
			$oldtime= $leadlast->followCraete;
			}else{
				$subjects= 'Demo | '.$user->name.' |   0 - 0  | '.$status->name;
				
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
			$to="brijeshchauhan68@gmail.com,brijesh.chauhan@cromacampus.com,devendra1784@hotmail.com";
			 
			$message='			<tr>
			<td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have received  Demo Status Notification. Here are the details:</span><u></u><u></u></p>
			</td>
			</tr>
			
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Demo Status:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$subjects.'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">New Time:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$leadFollowUp->created_at.'</span><u></u><u></u></p>
			</td>
			</tr>
				<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Old Time:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
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
			
			//info@leads.cromacampus.com
            
       /*     Mail::send('emails.send_lead_status', ['msg'=>$message], function ($m) use ($message,$request,$subjects,$emailId,$cc) {
				$m->from('info@leads.cromacampus.com', 'Demo');
				if(!empty($cc)){
				$m->to($emailId, "")->subject($subjects)->cc('cromacampus.leadsstatus@gmail.com');
				}else{
				    
				    	$m->to($emailId, "")->subject($subjects);
				    
				}
				
			});*/
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
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
    public function getFollowUps(Request $request, $id)
    { 
		if($request->ajax()){
			$leads = DB::table('croma_demo_follow_ups as demo_follow_ups')
							->join('croma_status as status','status.id','=','demo_follow_ups.status')
							->where('demo_follow_ups.demo_id','=',$id)
							->select('demo_follow_ups.*','status.name as status_name')
							->orderBy('demo_follow_ups.id','desc');
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
					$lead->created_at,
					$lead->remark,
					$lead->status_name,
					$lead->expected_date_time
				];
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
    public function verifyDemo(Request $request, $id)
    {
		$id = trim($id);
         
		$leads = DB::table('croma_leads as leads');
		$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
		$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');
		$leads = $leads->select('leads.*','courses.name as course_name','users.name as owner_name');
		$leads = $leads->whereNull('leads.deleted_at');
		$leads = $leads->where('leads.demo_attended','=','0');
		$leads = $leads->where('leads.mobile','LIKE',$id);
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
     * Send mail to counsellor.
     *
     * @param demo id
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
		$lead = DB::table('croma_demos as demos')
				->join('croma_users as users','users.id','=','demos.created_by')
				->select('demos.*','users.name as counsellor_name','users.email as counsellor_email','users.id as counsellor_id')
				->where('demos.id',$id)
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
     * Move not interested.
     *
     * @param lead id(s) to send.
     */	
	public function moveNotInterested(Request $request){
		 
		$ids = $request->input('ids');
		 
		if(!empty($ids))
		{
			
		foreach($ids as $id){
			$demo = Demo::findorFail($id);
			if($demo){
				$leadFollowUp = DB::table('croma_demo_follow_ups as demo_follow_ups');
				$leadFollowUp = $leadFollowUp->join('croma_status as status','status.id','=','demo_follow_ups.status');
				$leadFollowUp = $leadFollowUp->where('demo_follow_ups.demo_id',$demo->id);
				$leadFollowUp = $leadFollowUp->select('demo_follow_ups.*','status.name');
				$leadFollowUp = $leadFollowUp->orderBy('demo_follow_ups.id','desc');
				$leadFollowUp = $leadFollowUp->first();
				
				if(strcasecmp($leadFollowUp->name,'Not Interested')==0 || strcasecmp($leadFollowUp->name,'Location Issue')==0)
				{
					$demo->move_not_interested = 1;
					$demo->save();
					
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
		
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Please select check box.'
			]
		],200);
	}
	
	 /**
     * Move Joined Demos.
     *
     * @param lead id(s) to send.
     */	
	public function moveJoinedDemos(Request $request)
	{
		 
		$ids = $request->input('ids');
		if(!empty($ids))
		{			 
			 
			foreach($ids as $id){
				$demo = Demo::findorFail($id);
				if($demo){
					$leadFollowUp = DB::table('croma_demo_follow_ups as demo_follow_ups');
					$leadFollowUp = $leadFollowUp->join('croma_status as status','status.id','=','demo_follow_ups.status');
					$leadFollowUp = $leadFollowUp->where('demo_follow_ups.demo_id',$demo->id);
					$leadFollowUp = $leadFollowUp->select('demo_follow_ups.*','status.name');
					$leadFollowUp = $leadFollowUp->orderBy('demo_follow_ups.id','desc');
					$leadFollowUp = $leadFollowUp->first();
					 
					if(strcasecmp($leadFollowUp->name,'Close')==0)
					{
						$demo->move_not_interested = 2;
						$demo->save();
						
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
			
		}
	
		return response()->json([
			'statusCode'=>1,
			'data'=>[
			'responseCode'=>200,
			'payload'=>'',
			'message'=>'Please select check box.'
			]
			],200);

	}
	
	/**
     * Move to expected Demos.
     *
     * @param lead id(s) to send.
     */	
	public function moveToExpectedDemos(Request $request)
	{
		 
		$ids = $request->input('ids');
		 
		if(!empty($ids))
		{			 
			 
			foreach($ids as $id){
				$demo = Demo::findorFail($id);
				 
				if($demo){					 
						$demo->move_not_interested = 3;
						$demo->save();				 
						
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
			
		}
	
		return response()->json([
			'statusCode'=>1,
			'data'=>[
			'responseCode'=>200,
			'payload'=>'',
			'message'=>'Please select check box.'
			]
			],200);

	}
	
	
	/**
     * Move to expected Demos.
     *
     * @param lead id(s) to send.
     */	
	public function moveToExpectedNewBatchDemos(Request $request)
	{
		 
		$ids = $request->input('ids');
		 
		if(!empty($ids))
		{			 
			 
			foreach($ids as $id){
				$demo = Demo::findorFail($id);
				 
				if($demo){			
						// expected new batch demo 4, expected demo 3				
						$demo->move_not_interested = 4;
						$demo->save();				 
						
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
			
		}
	
		return response()->json([
			'statusCode'=>1,
			'data'=>[
			'responseCode'=>200,
			'payload'=>'',
			'message'=>'Please select check box.'
			]
			],200);

	}
	
	
	/**
     * Move Joined Demos.
     *
     * @param lead id(s) to send.
     */	
	public function moveToJoinDemos(Request $request)
	{
		 
		$ids = $request->input('ids');
		 
		if(!empty($ids))
		{			 
			 
			foreach($ids as $id){
				$demo = Demo::findorFail($id);
				if($demo){
					$leadFollowUp = DB::table('croma_demo_follow_ups as demo_follow_ups');
					$leadFollowUp = $leadFollowUp->join('croma_status as status','status.id','=','demo_follow_ups.status');
					$leadFollowUp = $leadFollowUp->where('demo_follow_ups.demo_id',$demo->id);
					$leadFollowUp = $leadFollowUp->select('demo_follow_ups.*','status.name');
					$leadFollowUp = $leadFollowUp->orderBy('demo_follow_ups.id','desc');
					$leadFollowUp = $leadFollowUp->first();
					 
					if(strcasecmp($leadFollowUp->name,'Close')==0)
					{
						$demo->move_not_interested = 0;
						$demo->save();
						
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
			
		}
	
		return response()->json([
			'statusCode'=>1,
			'data'=>[
			'responseCode'=>200,
			'payload'=>'',
			'message'=>'Please select check box.'
			]
			],200);

	}
	
	/**
     * Expected Move to Demos.
     *
     * @param lead id(s) to send.
     */	
	public function expectedMoveToDemos(Request $request)
	{
		 
		$ids = $request->input('ids');
		 
		if(!empty($ids))
		{			 
			 
			foreach($ids as $id){
				$demo = Demo::findorFail($id);
				if($demo){
					 
						$demo->move_not_interested = 0;
						$demo->save();
						
				 
					 
						
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
			
		}
	
		return response()->json([
			'statusCode'=>1,
			'data'=>[
			'responseCode'=>200,
			'payload'=>'',
			'message'=>'Please select check box.'
			]
			],200);

	}
	
	/**
     * Expected Move to Demos.
     *
     * @param lead id(s) to send.
     */	
	public function expectedNewBatchMoveToDemos(Request $request)
	{
		 
		$ids = $request->input('ids');
		 
		if(!empty($ids))
		{			 
			 
			foreach($ids as $id){
				$demo = Demo::findorFail($id);
				if($demo){
					 
						$demo->move_not_interested = 0;
						$demo->save();
						
				 
					 
						
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
			
		}
	
		return response()->json([
			'statusCode'=>1,
			'data'=>[
			'responseCode'=>200,
			'payload'=>'',
			'message'=>'Please select check box.'
			]
			],200);

	}
	
	
	
	/**
     * Send bulk sms.
     *
     * @param lead id(s) and message to send.
     */	
	public function sendBulkSms(Request $request)
	{ 
		$ids = $request->input('ids');
		$message = $request->input('message');		 
		foreach($ids as $id){
			$lead = Demo::find($id);			 
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
				'message'=>'Bulk Sms send ss successfully...'
			]
		],200);
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
			 $updated= DB::table('croma_demo_follow_ups')->where('id',$ids)->update($update);
 	

		}
			
		
		 
	}
	
	/**
     * Move to Demos.
     *
     * @param lead id(s) to send.
     */	
	public function moveToDemos(Request $request){
		 
		$ids = $request->input('ids');
		 
		if(!empty($ids))
		{
			
		foreach($ids as $id){
			$demo = Demo::findorFail($id);
			if($demo){
				$leadFollowUp = DB::table('croma_demo_follow_ups as demo_follow_ups');
				$leadFollowUp = $leadFollowUp->join('croma_status as status','status.id','=','demo_follow_ups.status');
				$leadFollowUp = $leadFollowUp->where('demo_follow_ups.demo_id',$demo->id);
				$leadFollowUp = $leadFollowUp->select('demo_follow_ups.*','status.name');
				$leadFollowUp = $leadFollowUp->orderBy('demo_follow_ups.id','desc');
				$leadFollowUp = $leadFollowUp->first();
				
				if(strcasecmp($leadFollowUp->name,'Not Interested')==0 || strcasecmp($leadFollowUp->name,'Location Issue')==0)
				{
					$demo->move_not_interested = 0;
					$demo->save();
					
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
		
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Please select check box.'
			]
		],200);
	} 
	
	/**
     * Select Delete.
     *
     * @param lead id(s) to send.
     */	
	public function selectDelete(Request $request){
		 
		$ids = $request->input('ids');
		 
		if(!empty($ids))
		{
			
		foreach($ids as $id){
			$demo = Demo::findorFail($id);
		 
			if($demo){
				 DB::table('croma_demos')->where('id',$id)->update(array('deleted_by'=>$request->user()->id,'deleted_at'=>date('Y-m-d H:i:s')));				 

							}
					
		}
		return response()->json([
					'statusCode'=>1,
					'data'=>[
					'responseCode'=>200,
					'payload'=>'',
					'message'=>'Lead Deleted successfully...'
					]
					],200);
		
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Please select check box.'
			]
		],200);
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
		    $lead_id= DB::table(Session::get('company_id').'_demos')->where('id',$id)->first();
			if(!empty($lead_id->lead_id)){				
			$leads = DB::table(Session::get('company_id').'_lead_follow_ups')->where('lead_id',$lead_id->lead_id)->delete();
			$demo = DB::table(Session::get('company_id').'_leads')->where('id',$lead_id->lead_id)->delete();
			}
 		$leads = DB::table('croma_demo_follow_ups')->where('demo_id',$id)->delete();
				
			$demo = DB::table('croma_demos')->where('id',$id)->delete();
		
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Lead Deleted Permanently...'
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
	public function selectToNewDemos(Request $request){
		$ids = $request->input('ids');	
		  
		if(!empty($ids)){
		foreach($ids as $id){	
			$leads = DB::table('croma_demo_follow_ups')->where('demo_id',$id)->delete();	
            $demo = Demo::findOrFail($id);
			if($leads){			 
			$followUp = new DemoFollowUp;
			$followUp->status = Status::where('name','LIKE','Attended Demo')->first()->id;			 
			$followUp->expected_date_time = date('Y-m-d H:i:s');
			$followUp->demo_id = $id;
			$followUp->followby = $demo->created_by;		
			$followUp->save();
			
			}			
			 
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Update New Demos successfully...'
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
     * Get Excel deleted Demo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getExcelDeletedDemo(Request $request)
    { 
	
	 if($request->input('expert')=="Expert")
	 {  
	 
			$leads = DB::table('croma_demos as demos')
					  ->join('croma_sources as sources','demos.source','=','sources.id')
					  ->join('croma_cat_course as courses','demos.course','=','courses.id')					     
					   ->select('demos.*')					
					   ->whereNotNull('demos.deleted_at');	
                        if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') ){
                        if($request->user()->current_user_can('manager')){
                        $getID= [$request->user()->id];
                        $manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
                        foreach($manages as $key=>$manage){	 				 
                        array_push($getID,$manage->user_id);
                        }
                        $leads = $leads->whereIn('demos.created_by',$getID);
                        
                        
                        
                        
                        }else{
                        $leads = $leads->where('demos.created_by','=',$request->user()->id);
                        }
                        } 
					   $leads= $leads->get();
					  // $leads= $leads->paginate($request->input('length'));
					 
			$returnLeads = [];
			$data = [];
			 
			foreach($leads as $lead){
				$lastStatus = DB::table('croma_demo_follow_ups as demo_follow_ups')
								->join('croma_status as status','status.id','=','demo_follow_ups.status')
								->where('demo_follow_ups.demo_id','=',$lead->id)
								->select('status.name','demo_follow_ups.expected_date_time')
								->orderBy('demo_follow_ups.id','desc')
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
			 
			Excel::create('Deleted_demo_'.date('Y-m-d H:i a'), function($excel) use($arr) {
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
    public function getFeesTrainerAjax(Request $request)
    {
		if($request->ajax()){
			if(null==$request->input('q')){
				$trainers = FeesGetTrainer::where('status',0)->take(6)->get();
			}else{
				$trainers = FeesGetTrainer::where('status',0)->where('name','LIKE',"%".$request->input('q')."%")->get();
			}
			return response()->json($trainers,200);
		}
	}
	
	 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function batch()
    {
	 
		$users = User::select('id','name')->orderBy('name','ASC')->get();
	 
        return view('cm_demos.create_batch',['users'=>$users]);
    }

 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addbatch()
    {
	 
		$users = User::select('id','name')->orderBy('name','ASC')->get();
		$trainers = FeesGetTrainer::orderBy('name','ASC')->get();
	 
        return view('cm_demos.create_batch',['users'=>$users,'trainers'=>$trainers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function savebatch(Request $request)
    { 
	
	//echo "<pre>";print_r($_POST);die;
        if($request->ajax()){
			
			$batchid= $request->input('batchid');
			if(!empty($batchid)){
				
			$validator = Validator::make($request->all(),[
				 
				'user'=>'required',
				'trainer'=>'required',		
				'batch'=>'required|unique:croma_batchs,batch, '.$batchid.',id'
			
			]);
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
			
			 
			
			$batch = Batch::findOrFail($batchid);				 
			$batch->user = $request->input('user');
			$batch->trainer = $request->input('trainer');
			$batch->batch = $request->input('batch');		
			//echo "<pre>";print_r($batch);die;
			if($batch->save()){	 		 
					 
					return response()->json(['status'=>1,'msg'=>'Batch Successfully Updated'],200);
				 
			}else{
				return response()->json(['status'=>0,'errors'=>'Batch not Updated'],400);
			}
			
			
			
			
			}else{
			$validator = Validator::make($request->all(),[
				 
				'user'=>'required',
				'trainer'=>'required',
				'batch'=>'required|unique:croma_batchs,batch',
			]);
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
			
			 
			
			$batch =  new Batch;			 
			$batch->user = $request->input('user');
			$batch->trainer = $request->input('trainer');
			$batch->batch = $request->input('batch');		
			//echo "<pre>";print_r($batch);die;
			if($batch->save()){	 		 
					 
					return response()->json(['status'=>1,'msg'=>'Batch Successfully Added'],200);
				 
			}else{
				return response()->json(['status'=>0,'errors'=>'Batch not added'],400);
			}
		}
		}
    }
	
	 /**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getbatch(Request $request)
    {   
		if($request->ajax()){
			$batchs =New Batch;		  

			if($request->input('search.value')!==''){
				$batchs = $batchs->where(function($query) use($request){
					$query->orWhere('batch','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('user','LIKE','%'.$request->input('search.value').'%')						 
						  ->orWhere('trainer','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 
			$batchs = $batchs->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $batchs->total();
			$returnLeads['recordsFiltered'] = $batchs->total();
			$returnLeads['batchRecordCollection'] = [];
			foreach($batchs as $batch){
				 
					$action = '';
					$separator = '';
			    	 
				 
					$action .= $separator.'<a href="/demo/batch/editbatch/'.$batch->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
					$separator = ' | ';	
					
					$action .= $separator.'<a href="/demo/batch/editbatch/'.$batch->id.'" title="Edit"><i class="fa fa-trash" aria-hidden="true"></i></a>';
					$separator = ' | ';
					  
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$batch->id'></th>",
						 
						$batch->batch,
						$batch->user,
						$batch->trainer,	 
						$action
					];
					$returnLeads['batchRecordCollection'][] = $batch->id;
				 
			}
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
	
	 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editbatch($id)
    {
	 
		$users = User::select('id','name')->orderBy('name','ASC')->get();
		$trainers = FeesGetTrainer::orderBy('name','ASC')->get();
		$edit_data= Batch::findOrFail($id);
		//echo "<pre>";print_r($edit_data);
        return view('cm_demos.create_batch',['users'=>$users,'trainers'=>$trainers,'edit_data'=>$edit_data]);
    }

    
	 
	
	
	public function assignLeadBatch(Request $request){
		

		
		if($request->ajax()){			 
				$batchID = $request->input('batch_id');
				$ids= $request->input('ids');
	 
				if(null==$ids){
				return response()->json([
				"statusCode"=>1,
				"data"=>[
				"responseCode"=>200,
				"payload"=>"",
				"message"=>"Lead id cannot be null !!"
				]
				],400);
				}
			if($batchID){				 

			if(!empty($ids)){					

			for($i=0;$i<count($ids); $i++){				
			$batch = Batch::findOrFail($batchID);
			if($batch){

			$lead = Demo::findOrFail($ids[$i]); 		 	
			$lead->move_not_interested=5;				
			$lead->batch=$batchID;
		 
			$lead->save();		 
		 
			 
			}
			}			
			
			}
			 


			}
				
			$resulsu = "Assigned Lead to Batch";	 
		}
		 
		return response()->json(['status'=>1,'data'=>$resulsu]);
		
		 
		
	}
	
	
	
	public function LeadBatch(Request $request)
    {
		$sources = Source::all();
		$courses = Course::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->get();
		}else{
		    
		    
		    
		     if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
                    $statuses = Status::where('demo_filter',1)->orWhere('abgyan_follow_up',1)->get();
                    }else{
                    $statuses = Status::where('demo_filter',1)->get();
                    }
		}
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			 			
			$capability = Capability::select('user_id')->get();	 		 
			 $user_list=[];
			foreach($capability as $manage){

			array_push($user_list,$manage->user_id);
			 
		 
			}
			 $users = User::select('id','name')->whereIn('id',$user_list)->orderBy('name','ASC')->get();
		}
		else{
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
		
        return view('cm_demos.lead_batch',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }
	
	
	 /**
     * Get Expected paginated new batch demo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getLeadBatch(Request $request)
    {   
		if($request->ajax()){
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_sources as sources','demos.source','=','sources.id');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			$leads = $leads->join('croma_users as users','demos.created_by','=','users.id');
			$leads = $leads->join('croma_batchs as batchs','demos.batch','=','batchs.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
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
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			$leads = $leads->orderBy('demos.id','desc');
			 
			$leads = $leads->whereNull('demos.deleted_at');
			$leads = $leads->where('demos.demo_attended','=','0');
			$not_interested = 5; //expected demo 3, new batch demo 4, not interested 1 , joind demo 2, lead Batch 5
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			if($request->has('search.joined_demos')){
				$not_interested = 2;
			}
			$leads = $leads->where('demos.move_not_interested','=',$not_interested);
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('lead_demo_all')){
				if($request->user()->current_user_can('manager')){
				 $getID= [$request->user()->id];
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}
 				$leads = $leads->whereIn('demos.created_by',$getID);

				if($request->input('search.user')!==''){
				$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}else{
				$leads = $leads->where('demos.created_by','=',$request->user()->id);
				}
			}else{
				if($request->input('search.user')!==''){
					$leads = $leads->where('demos.created_by','=',$request->input('search.user'));
				}
			}

			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('demos.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%')
						    ->orWhere('batchs.batch','LIKE','%'.$request->input('search.value').'%')
						    ->orWhere('demos.course_name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.source')!==''){
				$leads = $leads->where('demos.source',$request->input('search.source'));
			}
			if($request->input('search.leaddf')!==''){
				$leads = $leads->whereDate('demos.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$leads = $leads->whereDate('demos.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('demos.course',$courseList);
			}
			$leads = $leads->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['demoRecordCollection'] = [];
			foreach($leads as $lead){
				 
					$action = '';
					$separator = '';
			    	if($request->has('search.not_interested')){
					
					}elseif($request->has('search.joined_demos')){
					
					}
					else{
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_remark')){
						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_follow_up')){
						$action .= $separator.'<a href="javascript:demoController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					if(Auth::user()->current_user_can('edit_demo')){
						$action .= $separator.'<a href="/demo/update/'.$lead->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
						$separator = ' | ';
					}
					}
				if(!empty($lead->batch)){
						$batch = Batch::findOrFail($lead->batch);
						if(!empty($batch)){
							$batchname= $batch->batch;
						}else{
							
							$batchname= "N/A";
						}
						
					}else{
						
						$batchname= "N/A";
					}
									
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = DemoFollowUp::where('demo_id',$lead->id)->where('status',$status->id)->count();
						if($npupCount>=9){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$lead->id'></th>",
						(new Carbon($lead->created_at))->format('d-m-y'),
						$lead->name,
						$lead->mobile,
				    	$batchname,
						//$lead->source_name,
						$lead->course_name,
						$lead->owner_name,
						//$lead->sub_courses,
						$lead->status_name.$npupMark,
						((strcasecmp($lead->status_name,'new lead'))?((new Carbon($lead->follow_up_date))->format('d-m-y h:i:s')):""),
						($lead->expected_date_time==NULL)?"":date_format(date_create($lead->expected_date_time),'d-m-y h:s A'),
						 
						$action
					];
					$returnLeads['demoRecordCollection'][] = $lead->id;
				 
			}
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
    
	/**
     * Expected Move to Demos.
     *
     * @param lead id(s) to send.
     */	
	public function BatchMoveToDemos(Request $request)
	{
		 
		$ids = $request->input('ids');
		 
		if(!empty($ids))
		{			 
			 
			foreach($ids as $id){
				$demo = Demo::findorFail($id);
				if($demo){
					 
						$demo->move_not_interested = 0;
						$demo->batch = 0;						
						$demo->save();			 
						
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
			
		}
	
		return response()->json([
			'statusCode'=>1,
			'data'=>[
			'responseCode'=>200,
			'payload'=>'',
			'message'=>'Please select check box.'
			]
			],200);

	}
	
	 /**
     * Return model Lead.
     *
     * @param  int  $id
     * @return \app\Lead
     */
    public function searchLead(Request $request, $id)
    {
		$mobile = trim($id);
      // echo $mobile;die;
		$leads = DB::table('croma_leads as leads');
		$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
		$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');
		$leads = $leads->select('leads.*','courses.name as course_name','users.name as owner_name','leads.created_at as leadcreated_at' );		
		$leads = $leads->where('leads.demo_attended','=','0');
		$leads = $leads->where('leads.mobile','LIKE',$mobile);
		$leads = $leads->get();
		
//	echo "<pre>";print_r($leads);
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
    public function searchDemo(Request $request, $id)
    {
		$mobile = trim($id);
      // echo $mobile;die;
		$leads = DB::table('croma_demos as leads');
		$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
		$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');
		$leads = $leads->select('leads.*','courses.name as course_name','users.name as owner_name','leads.created_at as leadcreated_at');
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function leadSearchLead()
    {
		$message = Message::where('name','LIKE','%welcome%')->first();
		$sources = Source::all();
		$courses = Course::all();
	 
		$statuses = Status::where('add_demo',1)->get();
        return view('cm_demos.leadSearchLead',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'message'=>$message]);
    }
	 /**
     * Return model Lead.
     *
     * @param  int  $id
     * @return \app\Lead
     */
    public function leadDemoJoinedverifyLead(Request $request, $id)
    {
		$mobile = trim($id);
      // echo $mobile;die;
		$leads = DB::table('croma_leads as leads');
		$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
		$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');
		$leads = $leads->select('leads.*','courses.name as course_name','users.name as owner_name');
		$leads = $leads->whereNull('leads.deleted_at');
		$leads = $leads->where('leads.demo_attended','=','0');
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
    public function leadDemoJoinedverifyDemo(Request $request, $id)
    {
		$mobile = trim($id);
      // echo $mobile;die;
		$leads = DB::table('croma_demos as leads');
		$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
		$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');
		$leads = $leads->select('leads.*','courses.name as course_name','users.name as owner_name');
		$leads = $leads->whereNull('leads.deleted_at');
		//$leads = $leads->where('leads.demo_attended','=','0');
		$leads = $leads->where('leads.move_not_interested','!=','2');
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
     * Move Joined Demos.
     *
     * @param lead id(s) to send.
     */	
	public function leadDemoJoined(Request $request)
	{
		// echo "<pre>";print_r($_POST);die;
		$ids = $request->input('ids');
		$type = $request->input('checktype');
		 
		if($type[0]=="leads"){
		
		if(!empty($ids))
		{			 
			 
			foreach($ids as $id){
			$lead = lead::findorFail($id);
			$user = User::findorFail($lead->created_by);
			$demo = new Demo;		 
			$demo->lead_id = $lead->id;			 
			$demo->name = $lead->name;
			$demo->email = $lead->email;
			$demo->mobile = $lead->mobile;
			$demo->source = $lead->source;
			$demo->trainer = $lead->trainer;
			$demo->source_name = $lead->source_name;
			$demo->course = $lead->course;
			$demo->course_name = $lead->course_name;			
			$demo->sub_courses = $lead->sub_course; 			 
			$demo->created_by = $lead->created_by;  
			$demo->move_not_interested = 2;  
			$demo->join_date =date('Y-m-d');  	
			$demo->status = Status::where('name','LIKE','Close')->first()->id;
			// echo "<pre>";print_r($demo);die;
			if($demo->save()){
				$followUp = new DemoFollowUp;
				$followUp->status = Status::where('name','LIKE','Close')->first()->id;				 
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->demo_id = $demo->id;
				$followUp->followby =$lead->created_by;
				$followUp->save();				
				$lead->demo_attended=1;
				$lead->save();
				
				
				$students  = New FeesGetStudents;
				$students->name=$lead->name;
				$students->email=$lead->email;
				$students->mobile=$lead->mobile;
				$students->course=$lead->course_name;
				$students->trainer=$lead->trainer;
				$students->counsellor=$user->name;
				$students->save();
				
			}else{
				$demo->delete();
				
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
			
		}
		}else if($type[0]="demos"){
			
		if(!empty($ids))
		{			 
			 
			foreach($ids as $id){
				$demo = Demo::findorFail($id);
				//echo "<pre>";print_r($demo);die;
				$user = User::findorFail($demo->created_by);
				if($demo){
					$feesGetStudents = FeesGetStudents::where('mobile','=',$demo->mobile)->where('course','=',$demo->course_name)->get();
					 
					if(!empty($feesGetStudents) && count($feesGetStudents)>0){						
						 
						 
							return response()->json([
						'statusCode'=>0,
						'data'=>[
						'responseCode'=>200,
						'payload'=>'',
						'message'=>'Already Added Student In Pending Table.'
						]
						],200);
					}else{
						 
				$students  = New FeesGetStudents;
				$students->name=$demo->name;
				$students->email=$demo->email;
				$students->mobile=$demo->mobile;
				$students->course=$demo->course_name;
				$students->trainer=$demo->trainer;				 
				$students->counsellor=$user->name;
				$students->save();
				
				$demo->move_not_interested = 2;
				$demo->join_date =date('Y-m-d');  
				$demo->status = Status::where('name','LIKE','Close')->first()->id;	
			    if($demo->save()){
				$followUp = new DemoFollowUp;
				$followUp->status = Status::where('name','LIKE','Close')->first()->id;				 
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->demo_id = $demo->id;
				$followUp->followby =$demo->created_by;
			    if($followUp->save()){
					return response()->json([
						'statusCode'=>1,
						'data'=>[
						'responseCode'=>200,
						'payload'=>'',
						'message'=>'Add Lead To Pending Students successfully...'
						]
						],200);
				}
				}
				
				
					}
						
				}
			}
				
		return response()->json([
						'statusCode'=>1,
						'data'=>[
						'responseCode'=>200,
						'payload'=>'',
						'message'=>'Add Lead To Pending Students successfully...'
						]
						],200);
			
		}
			
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
			'responseCode'=>200,
			'payload'=>'',
			'message'=>'Please select check box.'
			]
			],200);

	}
	
	
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
	 list of demo join 
	 table demo
     */
	public function trainerStatus(Request $request)
    {
		 
	 
		$courses = Course::all();		 
		$trainers = FeesGetTrainer::orderBy('name','ASC')->get();
		$users = [];
		if(Auth::user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			$users = User::select('id','name')->get();
		}else{
			$users = User::select('id','name')->where('id',$request->user()->id)->get();
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
        return view('cm_demos.trainer_status',['trainers'=>$trainers,'courses'=>$courses,'search'=>$search,'users'=>$users]);
    }
	
	
	
	/**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function gettrainerstatus(Request $request)
    {   
		if($request->ajax()){	
			//echo "<pre>";print_r($request->input('search'));die;
		 	
			$trainers = FeesGetTrainer::orderBy('name','ASC');
			if($request->input('search.value')!==''){
			$trainers = $trainers->where(function($query) use($request){
			$query->orWhere('name','LIKE','%'.$request->input('search.value').'%');

			});
			}
			if($request->input('search.trainer')!==''){
			 
				$trainers =$trainers->where('name',$request->input('search.trainer'));
			}
			$trainers = $trainers->where('status','0');
			 
			$trainers = $trainers->paginate($request->input('length'));
			 
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $trainers->total();
			$returnLeads['recordsFiltered'] = $trainers->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($trainers as $trainer)
			{
				  $totaldemo = DB::table('croma_demos as demo');
				$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) WHERE m2.id IS NULL ";
				$totaldemo = $totaldemo->join(DB::raw('('.$rawQuery.') as fu'),'demo.id','=',DB::raw('`fu`.`demo_id`'));
				$totaldemo = $totaldemo->where('demo.trainer','=',$trainer->name);
				if($request->input('search.leaddf')!==''){
				$totaldemo = $totaldemo->whereDate('demo.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$totaldemo = $totaldemo->whereDate('demo.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				
				if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
				$courseList[] = $course;
				}
				$totaldemo = $totaldemo->whereIn('demo.course',$courseList);
				}
				$totaldemo = $totaldemo->count();
				
				
				$totaljoin = DB::table('croma_demos as leads');
				$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) WHERE m2.id IS NULL ";
				$totaljoin = $totaljoin->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`demo_id`'));
				$totaljoin = $totaljoin->where('leads.trainer','=',$trainer->name);
				if($request->input('search.leaddf')!==''){
				$totaljoin = $totaljoin->whereDate('leads.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!==''){
				$totaljoin = $totaljoin->whereDate('leads.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}		 
						
				if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
				$courseList[] = $course;
				}
				$totaljoin = $totaljoin->whereIn('leads.course',$courseList);
				}
				$totaljoin = $totaljoin->where('leads.move_not_interested','=','2');
				$totaljoin = $totaljoin->count();

			 
				   
				$data[] = [					 
					$trainer->name,
					"",					 
					$totaldemo,
					$totaljoin,
				];
				$returnLeads['recordCollection'][] = $trainer->id;
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
    public function demojoindededit(Request $request, $id)
    {  
		if($request->ajax()){
			$lead = Demo::findOrFail($id);		 
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
				 
					$html.= '<form class="form-label-left" onsubmit="return demoController.storedemojoind('.$id.',this)">
								 
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
    public function storedemojoind(Request $request, $id)
    { //echo "<pre>";print_r($_GET);die;
		if($request->ajax()){
			$validator = Validator::make($request->all(),[
				 
				'course'=>'required',
						 
				
			]);
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				return response()->json(['status'=>1,'errors'=>$errorsBag],400);
			}
			
			 
			
			$lead = Demo::find($id);
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
	
	
}