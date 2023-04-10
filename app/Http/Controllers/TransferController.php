<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Course;
use Auth;
use DB;
use Validator;
use App\User;
use App\Lead;
use App\Demo;
use App\Status;
use Excel;
use Carbon\Carbon;
use Session;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
		$courses = Course::all();
		$statuss = Status::all();
		$users = [];
		if(Auth::user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$users = User::select('id','name')->orderBy('name','ASC')->get();
		}else{
			$users = User::select('id','name')->where('id',$request->user()->id)->get();
		}
		return view('cm_transfer.transfer',['courses'=>$courses,'users'=>$users,'statuss'=>$statuss]);
    }

    /**
     * Transfer the resources
     *
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request)
    {
		
		
        $validator = Validator::make($request->all(),[
			'transfer'=>'required',
			'transfer_from'=>'required',
			'transfer_to'=>'required',
			
		]);
		if($validator->fails()){
            return redirect('permanent-transfer')
                        ->withErrors($validator)
                        ->withInput();
		}
		switch($request->input('transfer')){
			case 'all_leads':
				$transfer='leads';				 
			break;
			 
			case 'all_demos':
				$transfer='demos';
			break;
			case 'FaceBook':
				$transfer='FaceBook';
			break;
			 
		}
		 
		 
		if(isset($transfer) && $transfer=='leads'){
			 
			$leads = DB::table('croma_leads as leads');			 // generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_lead_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('status')!==''){
				$statuses = $request->input('status');
				$i=0;
				if(!empty($statuses)){
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
			}
			if($request->input('course')!=''){
				$courses = $request->input('course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
			
		/*	if($request->input('status')!=''){
				$status_data = $request->input('status');
				foreach($status_data as $status){
					$statusList[] = $status;
				}
				$leads = $leads->whereIn('leads.status',$statusList);
			}
			*/
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			$leads = $leads->where('created_by',$request->transfer_from);
			if($request->input('leaddf') !=''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(@date_create($request->input('leaddf')),'Y-m-d'));
				$leads = $leads->whereDate('leads.created_at','<=',date_format(@date_create($request->input('leaddt')),'Y-m-d'));
			}
			$leads = $leads->get();
		//	echo "<pre>";print_r($leads);die;
			if(count($leads)){
				$users = User::select('id','name')->get();
				$usr=[];
				foreach($users as $user){
					$usr[$user->id]=$user->name;
				} 
				$leadsUpdate = DB::table(Session::get('company_id').'_leads as leads');
				
				$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_lead_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('status')!==''){
				$statuses = $request->input('status');
				$i=0;
				if(!empty($statuses)){
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
			
			}
				if($request->input('course')!=''){
				$courses = $request->input('course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leadsUpdate = $leadsUpdate->whereIn('leads.course',$courseList);
				}
				
			 
				 
				 $leadsUpdate = $leadsUpdate->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$leadsUpdate = $leadsUpdate->where('created_by', $request->transfer_from);
				
				if($request->input('leaddf')  !=''){
					$leadsUpdate = $leadsUpdate->whereDate('leads.created_at','>=',date_format(date_create($request->input('leaddf')),'Y-m-d'));
					$leadsUpdate = $leadsUpdate->whereDate('leads.created_at','<=',date_format(date_create($request->input('leaddt')),'Y-m-d'));
				}				
			//	echo "<pre>";print_r($leadsUpdate);die;
				$leadsUpdate = $leadsUpdate->update(['created_by' => $request->transfer_to]);
				foreach($leads as $lead){
					$arr[] = [
						"Name"=>$lead->name,
						"Mobile"=>$lead->mobile,
						"Source"=>$lead->source_name,
						"Course"=>$lead->course_name,
						"Sub.Technology"=>$lead->sub_courses,
						"Remark"=>$lead->remarks,
						"Created"=>(new Carbon($lead->created_at))->addMinutes(330)->format('d-m-Y h:i:s'),
						"Transfered From"=>$usr[$request->transfer_from],
						"Transfered To"=>$usr[$request->transfer_to],
					];
				}
				
				if($leadsUpdate){
				$request->session()->flash('alert-success','Lead successfully transfer!');
				
				  return redirect('permanent-transfer')->with('alert-success','Lead successfully transfer!');
				   $excel = \App::make('excel');
				Excel::create('leads_transfered_'.date('d_m_Y H:i:s'), function($excel) use($arr) {
					$excel->sheet('Sheet 1', function($sheet) use($arr) {
						$sheet->fromArray($arr);
					});
				})->export('xls'); 
				}
			}else{
				return redirect('permanent-transfer')
							->withErrors(['No lead(s) found for transfer'])
							->withInput();				
			}
		}
		else if(isset($transfer) && $transfer=='demos'){
			$leads = DB::table(Session::get('company_id').'_demos as demos');
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_demo_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('status')!==''){
				$statuses = $request->input('status');
				$i=0;
					if(!empty($statuses)){
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
				 
			}
			if($request->input('course') !=''){
				$courses = $request->input('course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('course',$courseList);
			}
		  $leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			$leads = $leads->where('created_by',$request->transfer_from);
			if($request->input('leaddf') !=''){
				$leads = $leads->whereDate('demos.created_at','>=',date_format(@date_create($request->input('leaddf')),'Y-m-d'));
				$leads = $leads->whereDate('demos.created_at','<=',date_format(@date_create($request->input('leaddt')),'Y-m-d'));
			}
			$leads = $leads->get();
			 
			if(count($leads)){
				$users = User::select('id','name')->get();
				$usr=[];
				foreach($users as $user){
					$usr[$user->id]=$user->name;
				}
				 
				$leadsUpdate = DB::table(Session::get('company_id').'_demos as demos');
				$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_demo_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('status')!==''){
				$statuses = $request->input('status');
				$i=0;
					if(!empty($statuses)){
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
			}
				if($request->input('course')!=''){
				$courses = $request->input('course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				
				$leadsUpdate = $leadsUpdate->whereIn('course',$courseList);
				}
				
			 
				$leadsUpdate = $leadsUpdate->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
				$leadsUpdate = $leadsUpdate->where('created_by', $request->transfer_from);
				if($request->input('leaddf') !=''){
					$leadsUpdate = $leadsUpdate->whereDate('demos.created_at','>=',date_format(date_create($request->input('leaddf')),'Y-m-d'));
					$leadsUpdate = $leadsUpdate->whereDate('demos.created_at','<=',date_format(date_create($request->input('leaddt')),'Y-m-d'));
				}				
				$leadsUpdate = $leadsUpdate->update(['created_by' => $request->transfer_to,'owner' => $request->transfer_to]);
				foreach($leads as $lead){
					$arr[] = [
						"Name"=>$lead->name,
						"Mobile"=>$lead->mobile,
						"Source"=>$lead->source_name,
						"Course"=>$lead->course_name,
						"Sub. Technology"=>$lead->sub_courses,
						"Remark"=>$lead->remarks,
						"Created"=>(new Carbon($lead->created_at))->addMinutes(330)->format('d-m-Y h:i:s'),
						"Transfered From"=>$usr[$request->transfer_from],
						"Transfered To"=>$usr[$request->transfer_to],
					];
				}
				/* $excel = \App::make('excel');
				Excel::create('demos_transfered_'.date('d_m_Y H:i:s'), function($excel) use($arr) {
					$excel->sheet('Sheet 1', function($sheet) use($arr) {
						$sheet->fromArray($arr);
					});
				})->export('xls'); */
				$request->session()->flash('alert-success','Demo successfully transfer!');
				  return redirect('permanent-transfer');
			}else{
				return redirect('permanent-transfer')
							->withErrors(['No demo(s) found for transfer'])
							->withInput();
			}			
		}
		
		
		//facebook start
		else if(isset($transfer) && $transfer=='FaceBook'){
			 
			$leads = DB::table('croma_leads as leads');			 // generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_lead_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('status')!==''){
				$statuses = $request->input('status');
				$i=0;
				if(!empty($statuses)){
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
			}
			if($request->input('course')!=''){
				$courses = $request->input('course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
			
		/*	if($request->input('status')!=''){
				$status_data = $request->input('status');
				foreach($status_data as $status){
					$statusList[] = $status;
				}
				$leads = $leads->whereIn('leads.status',$statusList);
			}
			*/
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			$leads = $leads->where('created_by',$request->transfer_from);
			if($request->input('leaddf') !=''){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(@date_create($request->input('leaddf')),'Y-m-d'));
				$leads = $leads->whereDate('leads.created_at','<=',date_format(@date_create($request->input('leaddt')),'Y-m-d'));
			}
			$leads = $leads->get();
		//	echo "<pre>";print_r($leads);die;
			if(count($leads)){
				$users = User::select('id','name')->get();
				$usr=[];
				foreach($users as $user){
					$usr[$user->id]=$user->name;
				} 
				$leadsUpdate = DB::table(Session::get('company_id').'_leads as leads');
				
				$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_lead_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('status')!==''){
				$statuses = $request->input('status');
				$i=0;
				if(!empty($statuses)){
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
			
			}
				if($request->input('course')!=''){
				$courses = $request->input('course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leadsUpdate = $leadsUpdate->whereIn('leads.course',$courseList);
				}
				
			 
				 
				 $leadsUpdate = $leadsUpdate->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$leadsUpdate = $leadsUpdate->where('created_by', $request->transfer_from);
				
				if($request->input('leaddf')  !=''){
					$leadsUpdate = $leadsUpdate->whereDate('leads.created_at','>=',date_format(date_create($request->input('leaddf')),'Y-m-d'));
					$leadsUpdate = $leadsUpdate->whereDate('leads.created_at','<=',date_format(date_create($request->input('leaddt')),'Y-m-d'));
				}
				
				$leads=$leads->where('source_name','FaceBook');
				$leads=$leads->where('fb_lead','1');
				
				
			//	echo "<pre>";print_r($leadsUpdate);die;
				$leadsUpdate = $leadsUpdate->update(['created_by' => $request->transfer_to,"facebook_owner"=>$request->transfer_to]);
				foreach($leads as $lead){
					$arr[] = [
						"Name"=>$lead->name,
						"Mobile"=>$lead->mobile,
						"Source"=>$lead->source_name,
						"Course"=>$lead->course_name,
						"Sub.Technology"=>$lead->sub_courses,
						"Remark"=>$lead->remarks,
						"Created"=>(new Carbon($lead->created_at))->addMinutes(330)->format('d-m-Y h:i:s'),
						"Transfered From"=>$usr[$request->transfer_from],
						"Transfered To"=>$usr[$request->transfer_to],
					];
				}
				
				if($leadsUpdate){
				$request->session()->flash('alert-success','Lead successfully transfer!');
				
				  return redirect('permanent-transfer')->with('alert-success','Lead successfully transfer!');
				   $excel = \App::make('excel');
				Excel::create('leads_transfered_'.date('d_m_Y H:i:s'), function($excel) use($arr) {
					$excel->sheet('Sheet 1', function($sheet) use($arr) {
						$sheet->fromArray($arr);
					});
				})->export('xls'); 
				}
			}else{
				return redirect('permanent-transfer')
							->withErrors(['No lead(s) found for transfer'])
							->withInput();				
			}
		}
		//facebook end
		
		
		
		
    }
	
}
