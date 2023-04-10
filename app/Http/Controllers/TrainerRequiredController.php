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
use App\Trainerrequired;
 
use Auth;
use Session;

class TrainerRequiredController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        return view('cm_trainerrequired.all_trainerrequired');
    } 
	
	
	  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
		
		$data['button'] = "Save";		
		$data['message'] = Message::where('name','LIKE','%Welcome%')->first();		 
		$data['sources'] = Source::all();
		$data['courses'] = Course::all();
		$data['statuses'] = Status::all();
		if($request->isMethod('post') && $request->input('submit')=="Save"){
			 
			  $this->validate($request, [		
				'technology'=>'required',
				'workday'=>'required',
				'training'=>'required',
				'skills'=>'required',	
				 
				 
			]);
			//$check = Trainer::where('mobile',trim($request->input('mobile')))->where('course',$request->input('course'))->first();
			
			$trainer =  new Trainerrequired;			
			$trainer->technology = $request->input('technology');
			$trainer->workday = $request->input('workday');
			$trainer->training = trim($request->input('training'));
			$trainer->skills = $request->input('skills'); 
			$trainer->coursecontent = $request->input('coursecontent');			 
			$trainer->remarks = $request->input('remarks');			 
			$trainer->created_by = $request->user()->id;			 
			 
			if($trainer->save()){
				

			 $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		    $headers .= 'From: <info@leads.cromacampus.com>' . "\r\n";
			$to="brijeshchauhan68@gmail.com,brijesh.chauhan@cromacampus.com";
			$subject="Trainer Required- ";
			$message='<tr>
			<td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have received an enquiry from our trainer required. Here are the details:</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Technology:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('technology').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Training:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('training').' </span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Work Days:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('workday').' </span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Skills:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('skills').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Remarks:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('remarks').'</span><u></u><u></u></p>
			</td>
			</tr>



			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Course Content:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->input('coursecontent').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">From:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> '.$request->user()->name.'  </span><u></u><u></u></p>
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
            Mail::send('emails.send_trainer_required_to_email', ['msg'=>$message], function ($m) use ($message,$request) {
				$m->from('info@cromacampus.com', 'Requirement Trainer');
				$m->to("hr@cromacampus.com", "");
				$m->to("info@cromacampus.com", "")->subject("Trainer Required-".$request->input('technology'));
			});		
				
				
				
				 return redirect('/trainerrequired/all-trainer')->with('success','Trainer Required Add Successfully');
					 
				}else{
					$trainer->delete();
					 return redirect('/trainerrequired/all-trainer')->with('failed','Trainer Required Not Add Successfully!');
				}
			
			 
		}
        return view('cm_trainerrequired.all_trainerrequired',$data);
    }

    
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    { 
		$data['button'] = "Update";		
		$data['message'] = Message::where('name','LIKE','%Welcome%')->first();		 
		$data['sources'] = Source::all();
		$data['courses'] = Course::all();
		$data['statuses'] = Status::all();		
		$data['edit_data'] = Trainerrequired::find($id);	 
	 	if($request->isMethod('post') && $request->input('submit')=="Update"){
			
		   $this->validate($request, [		
				'technology'=>'required',
				'workday'=>'required',
				'training'=>'required',
				'skills'=>'required',	
				 
				 
			]);
			
			$trainer = Trainerrequired::find($id);		
			$trainer->technology = $request->input('technology');
			$trainer->workday = $request->input('workday');
			$trainer->training = trim($request->input('training'));
			$trainer->skills = $request->input('skills'); 
			$trainer->coursecontent = $request->input('coursecontent');			 
			$trainer->remarks = $request->input('remarks');			 
			 if($trainer->save()){
				 return redirect('/trainerrequired/all-trainer')->with('success','Trainer Required Update Successfully');
					 
				}else{
					$trainer->delete();
					 return redirect('/trainerrequired/all-trainer')->with('failed','Trainer Required Not Update Successfully!');
				}
		 
		}
        return view('cm_trainerrequired.all_trainerrequired',$data);
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
		 
			$trainer = Trainerrequired::findorFail($id);	
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
			 
			
			$trainers = DB::table('croma_trainerrequireds as trainers'); 
			 
			$trainers = $trainers->orderBy('trainers.id','desc');		 
			 
				 
			if($request->input('search.value')!==''){
				$trainers = $trainers->where(function($query) use($request){
					$query->orWhere('trainers.technology','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('trainers.skills','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.training','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.workday','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 
			if($request->input('search.leaddf')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
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
					 
					
				 						 	
    				 
    						$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($trainer->remarks==NULL)?"Remark Not Available":$trainer->remarks).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
    						$separator = ' | ';
    					 
    						/* $action .= $separator.'<a href="javascript:trainerController.getfollowUps('.$trainer->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
    						$separator = ' | '; */
    				 
    						$action .= $separator.'<a href="/trainerrequired/edit/'.$trainer->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
    						$separator = ' | ';
    					 
					
					 
					
					 
					 
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$trainer->id'></th>",
						$trainer->technology,
						$trainer->training,
						$trainer->workday,
						$trainer->skills,						 
						(new Carbon($trainer->created_at))->format('d-m-Y h:i:s'),					 
						$action
					];
					$returntrainers['recordCollection'][] = $trainer->id;
				 
			}
			
			$returntrainers['data'] = $data;
			return response()->json($returntrainers);
			 
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
     * Get leads excel.
     *
     * @param  Request $request
     * @return ExcelSheet
     */
    public function getTrainerExcel(Request $request)
    {  	 
	
		//if($request->ajax()){
			$trainers = DB::table('croma_trainerrequireds as trainers'); 
			 
			  
			if($request->input('search.value')!==''){
				$trainers = $trainers->where(function($query) use($request){
					$query->orWhere('trainers.technology','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('trainers.skills','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.training','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('trainers.workday','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 

			if($request->input('search.leaddf')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$trainers = $trainers->whereDate('trainers.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			 
			//$trainers = $trainers->paginate($request->input('length'));
			$trainers = $trainers->get();
			  
			$returntrainers = [];
			$data = [];
			 
			$returntrainers['recordCollection'] = [];
			//echo "<pre>";print_r($trainers);
			$totremarks='';
			foreach($trainers as $trainer){
				 
								
					$arr[] = [
						"Technology"=>$trainer->technology,
						"Training"=>$trainer->training,
						"Work Day"=>$trainer->workday,
						"Skills"=>$trainer->skills,						 
						"Course Content"=>$trainer->coursecontent,
						"Remark"=>$trainer->remarks,						 
						"Date"=>(new Carbon($trainer->created_at))->format('d-m-Y h:i:s'),
						 
					];
					$returntrainers['recordCollection'][] = $trainer->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('Trainer_Required'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		 
    }
	
	
}
