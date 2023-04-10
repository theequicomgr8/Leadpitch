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
use App\Demo;
use App\Source;
use App\Course;
use App\Status;
use App\LeadFollowUp;
use App\DemoFollowUp;
use App\Message;
use App\Capability;
use Excel;
use App\User;
use App\Trainer;
use App\TrainerFollowUp;
use Auth;
use Session;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
		//dd($request->user()->current_user_can('administrator'));
		$sources = Source::all();
		$courses = Course::all();
		$statuses = Status::where('lead_filter',1)->where('name','!=','Location Issue')->where('name','!=','Close')->where('name','!=','Joined')->where('name','!=','Not Reachable')->where('name','!=','Switched Off')->where('name','!=','Call Later')->get();
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

        return view('cm_trainer.all_trainer',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    } 
	
	
	  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$message = Message::where('name','LIKE','%Welcome%')->first();
	 
		 
		$sources = Source::all();
		$courses = Course::all();
		$statuses = Status::all();
			 
        return view('cm_trainer.add_trainer_form',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'message'=>$message]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
		//echo "<pre>";print_r($_POST);die;
        if($request->ajax()){
			$check = Trainer::where('mobile',trim($request->input('mobile')))->where('course',$request->input('course'))->first();
			 
			if(count($check)>0){
			$validator = Validator::make($request->all(),[
				'name'=>'required',			 
				'mobile'=>'required|unique:croma_trainers,mobile',				 
				'course'=>'required|unique:croma_trainers,course',
				 
			]);
			}else{
				
				$validator = Validator::make($request->all(),[
				'name'=>'required',				 
				'mobile'=>'required',			 
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
			
			 $trainer =  new Trainer;			
			$trainer->name = $request->input('name');
			$trainer->email = $request->input('email');
			$trainer->mobile = trim($request->input('mobile'));			 
			$trainer->course = $request->input('course');
			$trainer->course_name = ($request->has('course'))?Course::find($request->input('course'))->name:"";			 
			$trainer->sub_courses = $request->input('sub-course');
			$trainer->training = $request->input('training');
			$trainer->workday = $request->input('workday');
			$trainer->created_by = $request->user()->id;
			$user = User::where('id',$trainer->created_by)->first();
			 
			if($trainer->save()){
				$followUp = new TrainerFollowUp;
				$followUp->status = Status::where('name','LIKE','New Lead')->first()->id;
				$followUp->remark = $request->input('counsellor_remark');
				$followUp->expected_date_time = date('Y-m-d H:i:s');
				$followUp->trainer_id = $trainer->id;
				 
				if($followUp->save()){
					 
					return response()->json(['status'=>1],200);
				}else{
					$trainer->delete();
					return response()->json(['status'=>0,'errors'=>'Trainer not added'],400);
				}
			}else{
				return response()->json(['status'=>0,'errors'=>'Trainer not added'],400);
			}
		}
    }

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    { 
		$data['edit_data'] = Trainer::find($id);
	 
		$data['courses'] = Course::all();
		$data['statuses'] = Status::all();
		 
		$course = Course::findOrFail($data['edit_data']->course);
	 
		if($course){
			if(!is_null($course->counsellors)){
				$courseCounsellors = unserialize($course->counsellors);
			}	
		}
		 
        return view('cm_trainer.trainer_update',$data);
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
		 
		//	$check = Trainer::where('mobile',trim($request->input('mobile')))->where('course',$request->input('course'))->where('id','<>',$id)->first();
		 		/*	$check = Trainer::where('id','<>',$id)->first();
echo "<pre>";print_r($chec);die;
			if(count($check)>0){
			$validator = Validator::make($request->all(),[
				'name'=>'required',				
				'mobile'=>'required|unique:croma_trainer,mobile',			
				'course'=>'required|unique:croma_trainer,course',
				
			]);
			}else{*/
					$validator = Validator::make($request->all(),[
						'name'=>'required',						
						'mobile'=>'required',						
						'course'=>'required',
						 
					]);
		
			 
		if($validator->fails()){
            return redirect('trainer/update/'.$id)
                        ->withErrors($validator)
                        ->withInput();
		}
		
		$trainer = Trainer::find($id);
		$trainer->name = $request->input('name');
		$trainer->email = $request->input('email');
		$trainer->mobile = trim($request->input('mobile'));		
		$trainer->course = $request->input('course');
		$trainer->course_name = Course::find($request->input('course'))->name;		 
		$trainer->sub_courses = $request->input('sub-course');
		$trainer->workday = $request->input('workday');
		$trainer->training = $request->input('training');
		 
	//	echo "<pre>";print_r($trainer);die;
		if($trainer->save()){
			$request->session()->flash('alert-success', 'Trainer Successfully Updated !!');
			return redirect(url('/trainer/all-trainer'));
		}else{
			$request->session()->flash('alert-danger', 'Trainer not Updated !!');
			return redirect(url('/trainer/update/'.$id));			
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
     public function admission($id){
	     array_map('unlink', glob("$id/*.*"));
	 }
     
    public function destroy(Request $request)
    {			
	
		$ids = $request->input('ids');		
		if(!empty($ids)){
		foreach($ids as $id){	
			 $leads = DB::table('croma_trainer_follow_ups')->where('trainer_id',$id)->delete();	
			$trainer = Trainer::findorFail($id);	
			$trainer->delete();			
			 
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
     * Get paginated trainers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedtrainer(Request $request)
    {  
		if($request->ajax()){
			 
			
			$trainers = DB::table('croma_trainers as trainers');		 
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_trainer_follow_ups m1 LEFT JOIN croma_trainer_follow_ups m2 ON (m1.trainer_id = m2.trainer_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
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
				 
			}
			
			 
			
			$trainers = $trainers->join(DB::raw('('.$rawQuery.') as fu'),'trainers.id','=',DB::raw('`fu`.`trainer_id`'));
			// generating raw query to make join
			
			$trainers = $trainers->select('trainers.*',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'));
			$trainers = $trainers->orderBy('trainers.id','desc');
		
		 
			if($request->input('search.value')!==''){
				$trainers = $trainers->where(function($query) use($request){
					$query->orWhere('trainers.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('trainers.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.course_name','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.leaddf')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$trainers = $trainers->whereIn('trainers.course',$courseList);
			}
			
		 
			$trainers = $trainers->paginate($request->input('length'));
		 
			//dd($trainers->toSql());
			$users = User::orderBy('name','ASC')->get();
			$usr = [];
			foreach($users as $user){
				$usr[$user->id] = $user->name;
			}
			$returntrainers = [];
			$data = [];
			$returntrainers['draw'] = $request->input('draw');
			$returntrainers['recordsTotal'] = $trainers->total();
			$returntrainers['recordsFiltered'] = $trainers->total();
			$returntrainers['recordCollection'] = [];

			$coursess = Course::orderBy('id','desc')->get();
			 
			   
 
			foreach($trainers as $trainer){
				 
					$action = '';
					$separator = '';
					 
					
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    					 
    						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Trainer Remark" data-content="'.(($trainer->remark==NULL)?"Remark Not Available":$trainer->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    						$separator = ' | ';
    					 
    		 
    						$action .= $separator.'<a href="javascript:trainerController.getfollowUps('.$trainer->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					 
    					if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('manager') ){
    						$action .= $separator.'<a href="/trainer/update/'.$trainer->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
					
					 }
					
					 
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = TrainerFollowUp::where('trainer_id',$trainer->id)->where('status',$status->id)->count();
						if($npupCount>=9){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					$whatsapp ='<a href="https://wa.me/91'.$trainer->mobile.'" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true" style="color:#06e906;margin-left:1px;font-size: 16px;"></i></a>';
				
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$trainer->id'></th>",
						'<span contenteditable>'.$trainer->name.'</span>',
						'<span contenteditable>'.$trainer->mobile.'</span> '.$whatsapp,						
						'<span contenteditable>'.$trainer->course_name.'</span>',
						$trainer->workday,
						(isset($usr[$trainer->	created_by])?$usr[$trainer->	created_by]:""),
						$trainer->training,						 
						$trainer->sub_courses,
						$trainer->status_name.$npupMark,
						(new Carbon($trainer->created_at))->format('d-m-Y h:i:s'),
						((strcasecmp($trainer->status_name,'new trainer'))?((new Carbon($trainer->follow_up_date))->format('d-m-Y h:i:s')):""),
						($trainer->expected_date_time==NULL)?"":(new Carbon($trainer->expected_date_time))->format('d-m-Y h:i A'),
						 
						$action
					];
					$returntrainers['recordCollection'][] = $trainer->id;
				 
			}
			
			$returntrainers['data'] = $data;
			return response()->json($returntrainers);
			 
		}
    } 
	
    /**
     * Get paginated trainers.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedtrainerold(Request $request)
    {  
		if($request->ajax()){
			 
			
			$trainers = DB::table('croma_trainers as trainers');
			 
		
			$trainers = $trainers->join('croma_courses as courses','trainers.course','=','courses.id');
			$trainers = $trainers->join('croma_users as users','trainers.created_by','=','users.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_trainer_follow_ups m1 LEFT JOIN croma_trainer_follow_ups m2 ON (m1.trainer_id = m2.trainer_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
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
			
			 
			
			$trainers = $trainers->join(DB::raw('('.$rawQuery.') as fu'),'trainers.id','=',DB::raw('`fu`.`trainer_id`'));
			// generating raw query to make join
			
			$trainers = $trainers->select('trainers.*','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');
			$trainers = $trainers->orderBy('trainers.id','desc');
		
			$trainers = $trainers->whereNull('trainers.deleted_at');
			$trainers = $trainers->where('trainers.demo_attended','=','0');
			$not_interested = 0;
			if($request->has('search.not_interested')){
				$not_interested = 1;
			}
			$trainers = $trainers->where('trainers.move_not_interested','=',$not_interested);
			
			 
				 
			if($request->input('search.value')!==''){
				$trainers = $trainers->where(function($query) use($request){
					$query->orWhere('trainers.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('trainers.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.leaddf')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$trainers = $trainers->whereIn('trainers.course',$courseList);
			}
			
			// course
			if(!$request->user()->current_user_can('super_admin') && !$request->user()->current_user_can('administrator') && !$request->user()->current_user_can('all_trainer_show')){		
				
			if($request->user()->current_user_can('manager')){	
				$getID= [$request->user()->id]; 
				$manages = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
 
				foreach($manages as $key=>$manage){	 				 
					array_push($getID,$manage->user_id);
				}


 				$coursess = Course::orderBy('id','desc')->get(); 				
				$courses_id=[];
 
				foreach($coursess as $course){
				$counsellors = unserialize($course->counsellors);	
	 

				foreach($getID as $key=>$val){

				if(in_array($val,$counsellors)){		

				array_push($courses_id,$course->id);					
				} 

				} 
				}	

 		
				$trainers = $trainers->whereIn('trainers.course',$courses_id);			 
					
					
				
			}else{
				
				$coursess = Course::orderBy('id','desc')->get();
				$courses_ids=[];
				foreach($coursess as $course){
				$counsellors = unserialize($course->counsellors);				
				if(in_array($request->user()->id,$counsellors)){		 
				array_push($courses_ids,$course->id);					
				}  
				}

				$trainers = $trainers->whereIn('trainers.course',$courses_ids);
				
				}
				
				
			}	 
			
			//dd($courseList);
			$trainers = $trainers->paginate($request->input('length'));
		 
			//dd($trainers->toSql());
			$users = User::orderBy('name','ASC')->get();
			$usr = [];
			foreach($users as $user){
				$usr[$user->id] = $user->name;
			}
			$returntrainers = [];
			$data = [];
			$returntrainers['draw'] = $request->input('draw');
			$returntrainers['recordsTotal'] = $trainers->total();
			$returntrainers['recordsFiltered'] = $trainers->total();
			$returntrainers['recordCollection'] = [];
 
			foreach($trainers as $trainer){
				 
					$action = '';
					$separator = '';
					$owners = '<span style="">'.$trainer->owner_name.'</span>';//color:red
					
					if($request->has('search.not_interested')){
					
					
					 }else{						 	
    				 
    						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Trainer Remark" data-content="'.(($trainer->remark==NULL)?"Remark Not Available":$trainer->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    						$separator = ' | ';
    					 
    				 
    						$action .= $separator.'<a href="javascript:trainerController.getfollowUps('.$trainer->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    				 
    					if(Auth::user()->current_user_can('super_admin')){
    						$action .= $separator.'<a href="/trainer/update/'.$trainer->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					}
					
					 }
					
					 
					$npupMark = '';
					$status = Status::where('name','LIKE','NPUP')->first();
					if($status){
						$npupCount = TrainerFollowUp::where('trainer_id',$trainer->id)->where('status',$status->id)->count();
						if($npupCount>=9){
							$npupMark .= ' <span class="light-red">*</span>';
						}
					}
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$trainer->id'></th>",
						$trainer->name,
						$trainer->mobile,						
						$trainer->course_name,
						$trainer->workday,
						$trainer->training,						 
						$trainer->sub_courses,
						$trainer->status_name.$npupMark,
						(new Carbon($trainer->created_at))->format('d-m-Y h:i:s'),
						((strcasecmp($trainer->status_name,'new trainer'))?((new Carbon($trainer->follow_up_date))->format('d-m-Y h:i:s')):""),
						($trainer->expected_date_time==NULL)?"":(new Carbon($trainer->expected_date_time))->format('d-m-Y h:i A'),
						 
						$action
					];
					$returntrainers['recordCollection'][] = $trainer->id;
				 
			}
			
			$returntrainers['data'] = $data;
			return response()->json($returntrainers);
			 
		}
    } 
	
	  
	
    /**
     * Get leads excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
    public function getTrainerExcel(Request $request)
    {  	 
	
		 
			$trainers = DB::table('croma_trainers as trainers');			 
			$trainers = $trainers->join('croma_courses as courses','trainers.course','=','courses.id');
			

			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM croma_trainer_follow_ups m1 LEFT JOIN croma_trainer_follow_ups m2 ON (m1.trainer_id = m2.trainer_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			 
			if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}
			 

			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			$trainers = $trainers->join(DB::raw('('.$rawQuery.') as fu'),'trainers.id','=',DB::raw('`fu`.`trainer_id`'));
			// generating raw query to make join
			
			$trainers = $trainers->select('trainers.*','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$trainers = $trainers->orderBy('trainers.id','desc');
			$trainers = $trainers->whereNull('trainers.deleted_at');
			 
		  
			if($request->input('search.value')!==''){
				$trainers = $trainers->where(function($query) use($request){
					$query->orWhere('trainers.name','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('trainers.email','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.mobile','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.sub_courses','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 		

			if($request->input('search.leaddf')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			if($request->input('search.course')!==''){
				$trainers = $trainers->where('trainers.course',$request->input('search.course'));
			}
			//$trainers = $trainers->paginate($request->input('length'));
			$trainers = $trainers->get();
			  
			$returntrainers = [];
			$data = [];
			 
			$returntrainers['recordCollection'] = [];
			//echo "<pre>";print_r($trainers);
			$totremarks='';
			foreach($trainers as $lead){
				 $allremarks = DB::table('croma_trainer_follow_ups as trainer_follow_ups')		 
								->where('trainer_follow_ups.trainer_id','=',$lead->id)			 
								->orderBy('trainer_follow_ups.id','desc')
								->get();
					foreach($allremarks as $all){
								$totremarks =$all->remark.'<br>';	
									
								}
								
								
					$arr[] = [
						"Name"=>$lead->name,
						"Mobile"=>$lead->mobile,						 
						"Technology"=>$lead->course_name,
						"Skills"=>$lead->sub_courses,
						"Email"=>$lead->email,					 
						"Expected Date_Time"=>($lead->expected_date_time==NULL)?"":date_format(date_create($lead->expected_date_time),'d-m-Y h:s A'),
						"Remark"=>$totremarks,
						"Created"=>(new Carbon($lead->created_at))->format('d-m-Y h:i:s'),
						 
					];
					$returntrainers['recordCollection'][] = $lead->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('all_trainers_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		 
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
			$trainer = Trainer::findOrFail($id);		 
			$user = User::where('id',$trainer->created_by)->first();
			$trainerLastFollowUp = DB::table('croma_trainer_follow_ups as trainer_follow_ups')
							->where('trainer_follow_ups.trainer_id','=',$id)
							->select('trainer_follow_ups.*')
							->orderBy('trainer_follow_ups.id','desc')
							->first();
			$sources = Source::all();
			$sourceHtml = '';
			$sourceObj = null;
			 
			$courses = Course::all();
			 
			$courseHtml = '';
			if(count($courses)>0){
				foreach($courses as $course){
					if($course->id == $trainer->course){
						$courseHtml .= '<option value="'.$course->id.'" selected>'.$course->name.'</option>';
					}else{
						$courseHtml .= '<option value="'.$course->id.'">'.$course->name.'</option>';
					}
				}
			}
			$messages = Message::where('permission','LIKE','%G%')->where('all_lead','1')->orWhere('course',$trainer->course)->select('id','name')->get();
			$messageHtml = '';
			if(count($messages)>0){
				foreach($messages as $message){
					$messageHtml .= '<option value="'.$message->id.'">'.$message->name.'</option>';
				}
			}
			$statuses = Status::where('lead_follow_up',1)->where('name','!=','Location Issue')->where('name','!=','Close')->where('name','!=','Joined')->where('name','!=','Not Reachable')->where('name','!=','Switched Off')->where('name','!=','Call Later')->get();
			$statusHtml = '';
			$disabled = '';
			$dateValue = '';
			if(count($statuses)>0){
				foreach($statuses as $status){
					if(strcasecmp($status->name,'new lead')){
						$selected = '';
						if($trainerLastFollowUp->status==$status->id){
							$selected = 'selected';
							//if(!strcasecmp($status->name,'not interested')||!strcasecmp($status->name,'location issue')){
							if(!$status->show_exp_date){
								$disabled = 'disabled';
								if($trainerLastFollowUp->expected_date_time!=NULL){
									$dateValue = date_format(date_create($trainerLastFollowUp->expected_date_time),'d-F-Y g:i A');
								}
							}
						}
						$statusHtml .= '<option data-value="'.$status->show_exp_date.'" value="'.$status->id.'" '.$selected.'>'.$status->name.'</option>';
					}
				}
			}
			
			 
			 
			$html = '<div class="row">
						<div class="x_content" style="padding:0">';
				 
					$html.= '<form class="form-label-left" onsubmit="return trainerController.storeFollowUp('.$id.',this)">
								<div class="form-group">
									<div class="col-md-4">
										<label for="name">Name<span class="required">:</span></label>
										<!--input type="text" name="name" class="form-control-static col-md-7 col-xs-12" value="'.$trainer->name.'"-->
										<p class="form-control-static" style="display:inline">'.$trainer->name.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
									<label for="email">Email <span class="required">:</span></label>
										<!--input type="text" name="email" class="form-control col-md-7 col-xs-12" value="'.$trainer->email.'"-->
										<p class="form-control-static" style="display:inline">'.$trainer->email.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label for="mobile">Mobile <span class="required">:</span></label>
										<!--input type="text" name="mobile" class="form-control col-md-7 col-xs-12" value="'.$trainer->mobile.'"-->
										<p class="form-control-static" style="display:inline" contenteditable>'.$trainer->mobile.'</p>
									</div>
								</div>								 
								<div class="form-group">
									<div class="col-md-4">
										<label for="sub-course">Sub Technologies <span class="required">:</span></label>
										<!--input type="text" name="sub-course" class="form-control col-md-7 col-xs-12" value="'.$trainer->sub_courses.'" placeholder="Comma seperated courses"-->
										<p class="form-control-static" style="display:inline">'.$trainer->sub_courses.'</p>
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
										<label for="remark">Trainer Remark <span class="required">*</span></label>
										<textarea name="remark" rows="1" class="form-control col-md-7 col-xs-12" placeholder="Trainer Remarks"></textarea>
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
					<p style="margin-top:10px;margin-bottom:-5px;"><strong>Follow Up Status</strong>  <select onchange="javascript:trainerController.getAllFollowUps()" class="follow-up-count"><option value="5">Last 5</option><option value="all">All</option></select></p>
					<div class="table-responsive">
						<table id="datatable-trainerfollowups" class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>Date</th>
									<th>Trainer Remark</th>
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
			
			$trainer = Trainer::find($id);
			$user = User::where('id',$trainer->created_by)->first();
			 
			$statusObj = Status::find($request->input('status'));
			 
			$trainer->course = $request->input('course');
			 
			if($trainer->save()){
				$trainerFollowUp = new TrainerFollowUp;
				$status = Status::findorFail($request->input('status'));
				if(!strcasecmp($status->name,'npup')){
					$npupCount = TrainerFollowUp::where('trainer_id',$id)->where('status',$status->id)->count();
					if($npupCount>=9){
						$status = Status::where('name','LIKE','Not Interested')->first();
						$trainerFollowUp->status = $status->id;
					}else{
						$trainerFollowUp->status = $request->input('status');
					}
				}else{
					$trainerFollowUp->status = $request->input('status');
				}
				 
				$trainerFollowUp->remark = $request->input('remark');
				$trainerFollowUp->trainer_id = $id;
				$trainerFollowUp->expected_date_time = NULL;
				if($request->input('expected_date_time')!=''){
					$trainerFollowUp->expected_date_time = date('Y-m-d H:i:s',strtotime($request->input('expected_date_time')));
				}
				if($trainerFollowUp->save()){	 
					
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
			
			$trainers = DB::table('croma_trainer_follow_ups as trainer_follow_ups')
							->join('croma_status as status','status.id','=','trainer_follow_ups.status')
							->where('trainer_follow_ups.trainer_id','=',$id)
							->select('trainer_follow_ups.*','status.name as status_name')
							->orderBy('trainer_follow_ups.id','desc');
			if($request->input('count')!='all'){
				$trainers = $trainers->take($request->input('count'));
			}else{
				$trainers = $trainers->take(100);
			}
			$trainers = $trainers->paginate($request->input('length'));
							//->take(5)
							//->paginate($request->input('length'));
							
			$returnTrainers = [];
			$data = [];
			$returnTrainers['draw'] = $request->input('draw');
			$returnTrainers['recordsTotal'] = $trainers->total();
			$returnTrainers['recordsFiltered'] = $trainers->total();
			foreach($trainers as $trainer){
				$data[] = [
					(new Carbon($trainer->created_at))->format('d-m-Y h:i:s'),
					$trainer->remark,
					$trainer->status_name,
					(new Carbon($trainer->expected_date_time))->format('d-m-Y h:i A')
				];
			}
			$returnTrainers['data'] = $data;
			return response()->json($returnTrainers);
		}		
	}
	
    /**
     * Send client registration mail to client containing user name password.
     *
     * @param  object  $client
     */
    public function sendUandP($lead,$leadFollowUp)
    {
        Mail::send('emails.sendlead', ['lead'=>$lead,'leadFollowUp'=>$leadFollowUp], function ($m) use ($lead) {
            $m->from('care@grewbox.com', 'Leads with Location Issue');
            $m->to('locationissue@gmail.com', "")->subject('Leads with Location Issue');
        });
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
				sendSMS($lead->mobile,$msg);
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
	
              
	
	
	
}
