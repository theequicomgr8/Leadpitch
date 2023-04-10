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
use App\Hiring;

 
use Auth;
use Session;

class HiringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        return view('cm_hiring.all_hiring');
    } 
	
	
	  

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
		
		$data['button'] = "Save";		
		 
		if($request->isMethod('post') && $request->input('submit')=="Save"){
			 //echo "<pre>";print_r($_POST);die;
			  $this->validate($request, [		
				'hiring_type'=>'required',
				'workday'=>'required',
			]);
			//$check = hiring::where('mobile',trim($request->input('mobile')))->where('course',$request->input('course'))->first();
			
			$hiring =  new Hiring;			
			$hiring->hiring_type = $request->input('hiring_type');
			$hiring->technology = $request->input('technology');
			$hiring->training = $request->input('training');
			$hiring->workday = $request->input('workday');
			$hiring->salaryfrom = $request->input('salaryfrom');
			$hiring->salaryto = $request->input('salaryto');
			$hiring->experiencefrom = $request->input('experiencefrom');
			$hiring->experienceto = $request->input('experienceto');
			$hiring->position = $request->input('position');
			$hiring->skills = $request->input('skills');		 		 
			$hiring->remarks = $request->input('remarks');		 		 
			$hiring->created_by = $request->user()->id;			 
			$hiring->status = 1;			 
			 	$users = User::orderBy('name','ASC')->get();
			$usr = [];
			foreach($users as $user){
				$usr[$user->id] = $user->name;
			}
			if($hiring->save()){

	    	$headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		    $headers .= 'From: <info@leads.cromacampus.com>' . "\r\n";
			$to="brijesh.chauhan@cromacampus.com";
			$subject="hiring Required- ";
			$newhtml='';
			if($request->input('hiring_type')=='Trainer'){
				$newhtml .='<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Technology:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('technology').'</span><u></u><u></u></p>
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
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Training Mode:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('training').'</span><u></u><u></u></p>
			</td>
			</tr>
				<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Work Day:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('workday').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Experience:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('experiencefrom').' To '.$request->input('experienceto').'</span><u></u><u></u></p>
			</td>
			</tr>';
				
			}else{
				
				$newhtml .='<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Technology:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('position').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Experience:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('experiencefrom').' To '.$request->input('experienceto').'</span><u></u><u></u></p>
			</td>
			</tr>
			 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Work Day:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('workday').'</span><u></u><u></u></p>
			</td>
			</tr>';
				
			}
			
			$message='<tr>
			<td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have received an enquiry from our hiring required. Here are the details:</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Post By:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$usr[$request->user()->id].'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Hiring Type:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('hiring_type').'</span><u></u><u></u></p>
			</td>
			</tr>
			'.$newhtml.'
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Remark:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('remarks').'</span><u></u><u></u></p>
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
				$m->from('leads@leadpitch.in', 'Requirement hiring');
				$m->to("brijesh.chauhan@cromacampus.com", "")->subject("Hiring Required-".$request->input('hiring_type'))->cc('pawan.dixit@cromacampus.com');
			});	 	
				
				
				
				 return redirect('/hiring/all-hiring')->with('success','Hiring Required Add Successfully');
					 
				}else{
					$hiring->delete();
					 return redirect('/hiring/all-hiring')->with('failed','Hiring Required Not Add Successfully!');
				}
			
			 
		}
        return view('cm_hiring.all_hiring',$data);
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
		
		$data['edit_data'] = Hiring::find($id);	 
	 	if($request->isMethod('post') && $request->input('submit')=="Update"){
			
		   $this->validate($request, [		
				'hiring_type'=>'required',
				'workday'=>'required',				 
			]);
			
			$hiring = Hiring::find($id);		
			$hiring->hiring_type = $request->input('hiring_type');
			$hiring->technology = $request->input('technology');
			$hiring->training = $request->input('training');
			$hiring->workday = $request->input('workday');
			$hiring->salaryfrom = $request->input('salaryfrom');
			$hiring->salaryto = $request->input('salaryto');
			$hiring->experiencefrom = $request->input('experiencefrom');
			$hiring->experienceto = $request->input('experienceto');
			$hiring->position = $request->input('position');
			$hiring->skills = $request->input('skills');			 	 
			$hiring->remarks = $request->input('remarks');	
//echo "<pre>";print_r($hiring);die;			
			 if($hiring->save()){
				 return redirect('/hiring/all-hiring')->with('success','Hiring Required Update Successfully');
					 
				}else{
					$hiring->delete();
					 return redirect('/hiring/all-hiring')->with('failed','hiring Required Not Update Successfully!');
				}
		 
		}
        return view('cm_hiring.all_hiring',$data);
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
		 
			$hiring = Hiring::findorFail($id);	
			$hiring->delete();			
			 
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
     * Get paginated hirings.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedHiring(Request $request)
    {  
		if($request->ajax()){
			 
			
			$hirings = DB::table('croma_hiring as hirings'); 
			 
			$hirings = $hirings->orderBy('hirings.id','desc');		 
			 
				 
			if($request->input('search.value')!==''){
				$hirings = $hirings->where(function($query) use($request){
					$query->orWhere('hirings.position','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('hirings.skills','LIKE','%'.$request->input('search.value').'%')						  
						  ->orWhere('hirings.status','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 
			if($request->input('search.leaddf')!==''){
				$hirings = $hirings->whereDate('hirings.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$hirings = $hirings->whereDate('hirings.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			 
			//dd($courseList);
			$hirings = $hirings->paginate($request->input('length'));
		 
			//dd($hirings->toSql());
			$users = User::orderBy('name','ASC')->get();
			$usr = [];
			foreach($users as $user){
				$usr[$user->id] = $user->name;
			}
			$returnhirings = [];
			$data = [];
			$returnhirings['draw'] = $request->input('draw');
			$returnhirings['recordsTotal'] = $hirings->total();
			$returnhirings['recordsFiltered'] = $hirings->total();
			$returnhirings['recordCollection'] = [];
 
			foreach($hirings as $hiring){
				 
				$action = '';
				$separator = '';	
				$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($hiring->remarks==NULL)?"Remark Not Available":$hiring->remarks).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
				$separator = ' | ';   					 

				$action .= $separator.'<a href="/hiring/edit/'.$hiring->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
				$separator = ' | ';
    					 
    					 
					if($hiring->status=='1'){
						$status='<span style="color:green;">Ative</span>';
					}elseif($hiring->status=='2'){
						$status='<span style="color:red;">Close</span>';						
					}else{
						$status='<span style="color:green;">Proccess</span>';
					}
					
					$drop= ' <ul class="breadcrumb-elements" style="list-style: none;">
							<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							  		'.$status.'
							</a>
							<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="javascript:hiringstatus('.$hiring->id.',1)" title="Active">Active</a></li> 
							<li><a href="javascript:hiringstatus('.$hiring->id.',2)" title="Close">Close</a></li> 
							<li><a href="javascript:hiringstatus('.$hiring->id.',0)" title="Proccess">Proccess</a></li> 
						 
							</ul>
							</li>
							</ul>';
					
					 
					 
					$data[] = [
						"<th><input type='checkbox' class='check-box' value='$hiring->id'></th>",
						(new Carbon($hiring->created_at))->format('d-M-Y'),	
						$hiring->hiring_type,
						$hiring->position.''.$hiring->technology,
						$hiring->experiencefrom.' To '.$hiring->experienceto,
						$hiring->workday,				 
						$hiring->training,				 
						$hiring->skills,				 
						//$hiring->salaryfrom.' To '.$hiring->salaryto,	
						$usr[$hiring->created_by],	
						$drop, 						 										 
						$action
					];
					$returnhirings['recordCollection'][] = $hiring->id;
				 
			}
			
			$returnhirings['data'] = $data;
			return response()->json($returnhirings);
			 
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
				$hiring = Hiring::where('id',$id)->whereNotNull('deleted_at')->first();
				if($hiring->forceDelete()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'hiring not found'],200);
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
    public function gethiringExcel(Request $request)
    {  	 
	
		//if($request->ajax()){
			$hirings = DB::table('croma_hiring as hirings'); 
			 
			  
			if($request->input('search.value')!==''){
				$hirings = $hirings->where(function($query) use($request){
					$query->orWhere('hirings.position','LIKE','%'.$request->input('search.value').'%')
					      ->orWhere('hirings.skills','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('hirings.descripton','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('hirings.workday','LIKE','%'.$request->input('search.value').'%');
				});
			}
			 

			if($request->input('search.leaddf')!==''){
				$hirings = $hirings->whereDate('hirings.created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!==''){
				$hirings = $hirings->whereDate('hirings.created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			 
			//$hirings = $hirings->paginate($request->input('length'));
			$hirings = $hirings->get();
			  
			$returnhirings = [];
			$data = [];
			 
			$returnhirings['recordCollection'] = [];
			//echo "<pre>";print_r($hirings);
			$totremarks='';
			foreach($hirings as $hiring){
				 
								
					$arr[] = [
						"Position"=>$hiring->position,
						"Skills"=>$hiring->skills,
						"Status"=>$hiring->status,
						"Skills"=>$hiring->skills,						 
						 				 
						"Date"=>(new Carbon($hiring->created_at))->format('d-m-Y h:i:s'),
						 
					];
					$returnhirings['recordCollection'][] = $hiring->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('hiring_Required'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		 
    }
	
	public function status(Request $request, $id,$val){
	 
		$hiring = Hiring::findorFail($id);	
			$hiring->status=$val;
			 
			if($hiring->save()){		
			 return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Status Successfully Change.'
			]
		],200);
		
			}else{
				
				
				return response()->json([
			'statusCode'=>0,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Not Status Successfully Change.'
			]
		],200);
		
			}
	
	
	}
		
}
