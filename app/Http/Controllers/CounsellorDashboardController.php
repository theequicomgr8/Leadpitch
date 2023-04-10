<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Carbon\Carbon;
use Schema;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
// models
use App\Lead;
use App\Source;
use App\Course;
use App\Status;
use App\LeadFollowUp;
use App\Message;
use App\Demo;
use App\DemoFollowUp;
use App\Chating;
use App\Capability;
use App\FeesGetTrainer;
use App\FeesCourse;
use App\User;
use Excel;
use Auth;
use Session;
class CounsellorDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id=null)
    { 
        
        
		if($request->wantsJson()){				
			 				
			$user_id = $request->user()->id;	
            
			$viewAll = false;
			$viewManage=false;
			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
				$viewAll = true;
			}
			
			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('manager') )){
				$viewManage = true;
				 
			}

			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('TL') )){
				$viewManage = true;
				 
			}

			if(!is_null($id) || !empty($id)){				 
				$user_id = $id;
			}
			// FIND TOTAL LEADS OF A COUNSELLOR
			 $response= []; 
			 
			  if(!empty($viewAll)){
			  
            $todays = strtotime("now");
            $leadlast = DB::table('croma_leads as leads');
            $rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status  AND DATE(m1.created_at)='".date('Y-m-d', $todays)."'";
            $leadlast = $leadlast->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
            $leadlast = $leadlast->where('leads.created_by','=',$user_id)->first();
            
            $demolast = DB::table('croma_demos as demos');
            $rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status AND DATE(m1.created_at)='".date('Y-m-d', $todays)."'";
            $demolast = $demolast->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
            $demolast = $demolast->where('demos.created_by','=',$user_id)->first();
                $response['leadlast']= (isset($leadlast->created_at)?$leadlast->created_at:"");   
				$response['demolast'] = (isset($demolast->created_at)?$demolast->created_at:""); 
            
			}else{
			   $todays = strtotime("now");
            $leadlast = DB::table('croma_leads as leads');
            $rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status  AND DATE(m1.created_at)='".date('Y-m-d', $todays)."'";
            $leadlast = $leadlast->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
            $leadlast = $leadlast->where('leads.created_by','=',$user_id)->first();
            
            $demolast = DB::table('croma_demos as demos');
            $rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status AND DATE(m1.created_at)='".date('Y-m-d', $todays)."'";
            $demolast = $demolast->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
            $demolast = $demolast->where('demos.created_by','=',$user_id)->first(); 
          
                $response['leadlast']= (isset($leadlast->created_at)?$leadlast->created_at:"");  
				$response['demolast'] = (isset($demolast->created_at)?$demolast->created_at:""); 
				
			}
			
			if(!empty($viewAll)){
				$response['total_leads'] = Lead::count();
			}else{
				$response['total_leads'] = Lead::where('created_by','=',$user_id)->count();
			}
			  
			if(!empty($viewManage)){
			/*	$capability = new Capability;
				$capability = $capability->where('manager',$user_id)->get();
				foreach($capability as $manage){					
				$response['total_leads'] += Lead::where('created_by','=',$manage->user_id)->count();	
				
				}*/
				
				$response['total_leads'] = Lead::where('created_by','=',$user_id)->count();	
			}
		 

			// FIND WHOSE LAST FOLLOW UP IS INTERESTED
		
			$leads = DB::table('croma_leads as leads');
			$rawQuery = "SELECT m1.* FROM `croma_lead_follow_ups` m1 LEFT JOIN `croma_lead_follow_ups` m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested')";
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			 
			if($viewAll){				 
				$response['total_interested'] = $leads->count();				
			}else{				
				$leads = $leads->where('leads.created_by','=',$user_id);
				$response['total_interested'] = $leads->count();
			}
			
			if(!empty($viewManage)){			 
				/* $capability = new Capability;
				$capability= $capability->select('user_id')->where('manager',$user_id)->get();	 			
				foreach($capability as $manage){	
				$leads = DB::table('croma_leads as leads');
				$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested')";
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$leads = $leads->where('leads.created_by','=',$manage->user_id);	 	 
				$response['total_interested'] += $leads->count();
				 
				} */
				
				
			 	
				$leads = DB::table('croma_leads as leads');
				$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested')";
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$leads = $leads->where('leads.created_by','=',$user_id);	 	 
				$response['total_interested'] = $leads->count();
				 
				 				
			}
			

			 
			 
			 	//	echo "";print_r($response['total_interested']);die;
			// FIND FOLLOW UPS EXCLUDING INTERESTED,NOT INTERESTED,LOC ISSUE,JOINED,ATTENDED DEMO
			$leads = DB::table('croma_leads as leads');
			$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status NOT IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close' || `name` LIKE 'attended demo')";
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			 
			if($viewAll){				 
				$response['total_follow_up'] = $leads->count();
				
			}else{				
				$leads = $leads->where('leads.created_by','=',$user_id);
				$response['total_follow_up'] = $leads->count();
			}
			if(!empty($viewManage)){	
				/* $capability = new Capability;
				$capability= $capability->select('user_id')->where('manager',$user_id)->get();	 			
				 	 			
				foreach($capability as $manage){	
				$leads = DB::table('croma_leads as leads');
				$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status NOT IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close' || `name` LIKE 'attended demo')";
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				
				$leads = $leads->where('leads.created_by','=',$manage->user_id);	 	 
				$response['total_follow_up'] += $leads->count();
				 
				} */
				
			 	
				$leads = DB::table('croma_leads as leads');
				$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status NOT IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close' || `name` LIKE 'attended demo')";
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				
				$leads = $leads->where('leads.created_by','=',$user_id);	 	 
				$response['total_follow_up'] = $leads->count();
				 
							
			}
			 //	echo "";print_r($response['total_follow_up']);die;
			// FIND THE CALLING VISITS
			if($viewAll){				 
				$response['calling_visits'] = Demo::where('demo_type','LIKE','calling')->count();
				
			}else{				
				$response['calling_visits'] = Demo::where('demo_type','LIKE','calling')->where('created_by','=',$user_id)->count();
			}
			if(!empty($viewManage)){
 				
				/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();
			  					
				foreach($capability as $manage){					
				$leads= Demo::where('demo_type','LIKE','calling')->where('created_by','=',$manage->user_id);	 	 
				$response['calling_visits'] += $leads->count();
				 
				}			
				*/
			$leads= Demo::where('demo_type','LIKE','calling')->where('created_by','=',$user_id);	 	 
				$response['calling_visits'] = $leads->count();				
			}
			
			
			// FIND THE DIRECT VISITS
			if($viewAll){				 
				$response['direct_visits'] = Demo::where('demo_type','NOT LIKE','calling')->count();
				
			}else{				
				$response['direct_visits'] = Demo::where('demo_type','NOT LIKE','calling')->where('created_by','=',$user_id)->count();

			}
			if(!empty($viewManage)){
 				
				/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();
				 			
				foreach($capability as $manage){	 				
				$leads = Demo::where('demo_type','NOT LIKE','calling')->where('created_by','=',$manage->user_id);	 	 
				$response['direct_visits'] += $leads->count();
				 
				}	 */
			 	 				
				$leads = Demo::where('demo_type','NOT LIKE','calling')->where('created_by','=',$user_id);	 	 
				$response['direct_visits'] = $leads->count();
				 
				 

				
			} 
			
			if($viewAll){				 
				$response['total_demos'] = Demo::count();
				
			}else{				
				$response['total_demos'] = Demo::where('created_by','=',$user_id)->count();

			}
			if(!empty($viewManage)){
 				
				/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();
				 			
				foreach($capability as $manage){	 				
				$leads = Demo::where('created_by','=',$manage->user_id);	 	 
				$response['total_demos'] += $leads->count();
				 
				}	 */
				 	 				
				$leads = Demo::where('created_by','=',$user_id);	 	 
				$response['total_demos'] = $leads->count();
				 
					

				
			} 
			// FIND TOTAL JOINED
			$leads = DB::table('croma_demos as demos');
			$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'joined' || `name` LIKE 'Close')";
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			
			if($viewAll){
				$response['total_joined'] = $leads->count();				
			}else{				
				$leads = $leads->where('demos.created_by','=',$user_id);
				$response['total_joined'] = $leads->count();
			}
			if(!empty($viewManage)){ 				
				/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();	 			
				foreach($capability as $manage){	 	
					$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'joined' || `name` LIKE 'Close')";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					$leads= $leads->where('demos.created_by','=',$manage->user_id);	 	 
					$response['total_joined'] += $leads->count();
				 
				}	 */
				
								 	 	
					$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'joined' || `name` LIKE 'Close')";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					$leads= $leads->where('demos.created_by','=',$user_id);	 	 
					$response['total_joined'] = $leads->count();
				 
								
			}
			
			//	echo "";print_r($response['total_joined']);die;
			
			// FIND TOTAL LEADS OF A COUNSELLOR THIS MONTH
			if($viewAll){
				$response['total_leads_tm'] = Lead::whereMonth('created_at','=',date('m'))->count();
				
			}else{				
				$response['total_leads_tm'] = Lead::where('created_by','=',$user_id)->whereMonth('created_at','=',date('m'))->count();
			}
			if(!empty($viewManage)){ 	
			
				/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();		
				 			
				foreach($capability as $manage){	 
				$leads = Lead::where('created_by','=',$manage->user_id)->whereMonth('created_at','=',date('m'));					  
				$response['total_leads_tm'] += $leads->count();
				 
				} */

 
				$leads = Lead::where('created_by','=',$user_id)->whereMonth('created_at','=',date('m'));					  
				$response['total_leads_tm'] = $leads->count();
				 
						
			}
			
			// FIND WHOSE LAST FOLLOW UP IS INTERESTED THIS MONTH
			$leads = DB::table('croma_leads as leads');
			$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` = 'interested') AND MONTH(m1.created_at)=".date('m');
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			
			if($viewAll){				 
				$response['total_interested_tm'] = $leads->count();
			}else{				
				$leads = $leads->where('leads.created_by','=',$user_id);
				$response['total_interested_tm'] = $leads->count();
			}
			
			if(!empty($viewManage)){ 
			
				/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
				foreach($Capability as $manage){	
				$leads = DB::table('croma_leads as leads');
				$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` = 'interested') AND MONTH(m1.created_at)=".date('m');
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));				
				$leads = $leads->where('leads.created_by','=',$manage->user_id);				  
				$response['total_interested_tm'] += $leads->count();
				 
				} */
				
							 	
				$leads = DB::table('croma_leads as leads');
				$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` = 'interested') AND MONTH(m1.created_at)=".date('m');
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));				
				$leads = $leads->where('leads.created_by','=',$user_id);				  
				$response['total_interested_tm'] = $leads->count();
				 
								
			}
			// FIND FOLLOW UPS EXCLUDING INTERESTED,NOT INTERESTED,LOC ISSUE,JOINED,ATTENDED DEMO THIS MONTH
			$leads = DB::table('croma_leads as leads');
			$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status NOT IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close' || `name` LIKE 'attended demo') AND MONTH(m1.created_at)=".date('m');
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				
			if($viewAll){				 
				 
				$response['total_follow_up_tm'] = $leads->count();		
			}else{				
				$leads = $leads->where('leads.created_by','=',$user_id);
				$response['total_follow_up_tm'] = $leads->count();
			}
			
			if(!empty($viewManage)){ 				
				/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
				foreach($Capability as $manage){	 
				$leads = DB::table('croma_leads as leads');
				$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status NOT IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close' || `name` LIKE 'attended demo') AND MONTH(m1.created_at)=".date('m');
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$leads = $leads->where('leads.created_by','=',$manage->user_id);				  
				$response['total_follow_up_tm'] += $leads->count();
				 
				} */
				
			  
				$leads = DB::table('croma_leads as leads');
				$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status NOT IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close' || `name` LIKE 'attended demo') AND MONTH(m1.created_at)=".date('m');
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$leads = $leads->where('leads.created_by','=',$user_id);				  
				$response['total_follow_up_tm'] = $leads->count();
				 
								
			}
			// FIND THE CALLING VISITS THIS MONTH
			
			if($viewAll){				 
				 
				$response['calling_visits_tm'] = Demo::where('demo_type','LIKE','calling')->whereMonth('created_at','=',date('m'))->count();	
			}else{				
				$response['calling_visits_tm'] = Demo::where('demo_type','LIKE','calling')->where('created_by','=',$user_id)->whereMonth('created_at','=',date('m'))->count();
			}
			if(!empty($viewManage)){
 				
				/*$Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
				foreach($Capability as $manage){	 				
				$response['calling_visits_tm'] += Demo::where('demo_type','LIKE','calling')->where('created_by','=',$manage->user_id)->whereMonth('created_at','=',date('m'))->count();	 
				 
				}*/
						
				 	 				
				$response['calling_visits_tm'] = Demo::where('demo_type','LIKE','calling')->where('created_by','=',$user_id)->whereMonth('created_at','=',date('m'))->count();	 
				 
								
			}
			// FIND THE DIRECT VISITS THIS MONTH
			if($viewAll){				 
				 
				$response['direct_visits_tm'] = Demo::where('demo_type','NOT LIKE','calling')->whereMonth('created_at','=',date('m'))->count();
			}else{				
				$response['direct_visits_tm'] = Demo::where('demo_type','NOT LIKE','calling')->where('created_by','=',$user_id)->whereMonth('created_at','=',date('m'))->count();

			}			
			if(!empty($viewManage)){
 				
				/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
				foreach($Capability as $manage){	 				
				$response['direct_visits_tm'] += Demo::where('demo_type','NOT LIKE','calling')->where('created_by','=',$manage->user_id)->whereMonth('created_at','=',date('m'))->count(); 			 
				 
				} */
				
			 	 				
				$response['direct_visits_tm'] = Demo::where('demo_type','NOT LIKE','calling')->where('created_by','=',$user_id)->whereMonth('created_at','=',date('m'))->count(); 			 
				 
								
			}
			// FIND TOTAL JOINED THIS MONTH
			// ****************************
			$leads = DB::table('croma_demos as demos');
			$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'joined' || `name` LIKE 'Close') AND MONTH(m1.created_at)=".date('m');
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			
			if($viewAll){	
				$response['total_joined_tm'] = $leads->count();
			}else{				
				$leads = $leads->where('demos.created_by','=',$user_id);
				$response['total_joined_tm'] = $leads->count();

			}			
			if(!empty($viewManage)){ 				
				/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	 			
				foreach($Capability as $manage){	 	
				$leads = DB::table('croma_demos as demos');
				$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'joined' || `name` LIKE 'Close') AND MONTH(m1.created_at)=".date('m');
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));				
				$leads = $leads->where('demos.created_by','=',$manage->user_id);
				$response['total_joined_tm'] += $leads->count();				 			 
				} */
  	
				$leads = DB::table('croma_demos as demos');
				$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 LEFT JOIN croma_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'joined' || `name` LIKE 'Close') AND MONTH(m1.created_at)=".date('m');
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));				
				$leads = $leads->where('demos.created_by','=',$user_id);
				$response['total_joined_tm'] = $leads->count();				 			 
								
			}
			// FIND DAILY CALLING STATUS
			// *************************
			$baseValue = 150;
			$from_unix_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
			$yesterday = strtotime("now");
		
				// NEW LEADS
			if($viewAll){	
				$leads = DB::table('croma_leads as leads');
				$leads = $leads->whereDate('leads.created_at','=',date('Y-m-d', $yesterday));
				 
				$leads = $leads->whereNull('leads.deleted_at');
				$response['daily_calling_status']['new_lead'] = $leads->count();
				$response['daily_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);
			}else{				
				$leads = DB::table('croma_leads as leads');
				$leads = $leads->whereDate('leads.created_at','=',date('Y-m-d', $yesterday));
				$leads = $leads->where('leads.created_by','=',$user_id);
				$leads = $leads->whereNull('leads.deleted_at');
				$response['daily_calling_status']['new_lead'] = $leads->count();
				$response['daily_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);

			}	
			
			if(!empty($viewManage)){ 				
				/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
				foreach($capability as $manage){
					
				$leads = DB::table('croma_leads as leads');
				$leads = $leads->whereDate('leads.created_at','=',date('Y-m-d', $yesterday));
				$leads = $leads->where('leads.created_by','=',$manage->user_id);
				$leads = $leads->whereNull('leads.deleted_at');
				$response['daily_calling_status']['new_lead'] += $leads->count();
				$response['daily_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);			 			 
				}	 */
				
				
					
				$leads = DB::table('croma_leads as leads');
				$leads = $leads->whereDate('leads.created_at','=',date('Y-m-d', $yesterday));
				$leads = $leads->where('leads.created_by','=',$user_id);
				$leads = $leads->whereNull('leads.deleted_at');
				$response['daily_calling_status']['new_lead'] = $leads->count();
				$response['daily_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);			 			 
					

				
			}
			
				// INTERESTED
				// **********
			
				if($viewAll){	
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'Favorite') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));					 
					$response['daily_calling_status']['interested'] = $leads->count();
					$response['daily_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{				
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'Favorite') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				//	$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['interested'] = $leads->count();
					$response['daily_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);

				}	
				if(!empty($viewManage)){ 				
					/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($capability as $manage){

					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					$leads = $leads->where('leads.created_by','=',$manage->user_id);
					$response['daily_calling_status']['interested'] += $leads->count();
					$response['daily_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);			 			 
					} */


					 

					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'Favorite') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['interested'] = $leads->count();
					$response['daily_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);			 			 
					

					
				}
				
				// PENDING
				// *******
				
				if($viewAll){	
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));				 
					$response['daily_calling_status']['pending'] = $leads->count();
					$response['daily_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['pending'] = $leads->count();
					$response['daily_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);				
					
				}
				if(!empty($viewManage)){ 				
					/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($capability as $manage){
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
						$leads = $leads->where('leads.created_by','=',$manage->user_id);
						$response['daily_calling_status']['pending'] += $leads->count();
						$response['daily_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);		 			 
					}
					 */
				 
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
						//$leads = $leads->where('leads.created_by','=',$user_id);
						$leads = $leads->where('fu.followby','=',$user_id);
						$response['daily_calling_status']['pending'] = $leads->count();
						$response['daily_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);		 			 
									
				}
				
				// NOT INTERESTED
				// **************
				
				if($viewAll){	
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));					 
					$response['daily_calling_status']['not_interested'] = $leads->count();
					$response['daily_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
						
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['not_interested'] = $leads->count();
					$response['daily_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
					
				}
				if(!empty($viewManage)){ 				
					/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($capability as $manage){
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
						$leads = $leads->where('leads.created_by','=',$manage->user_id);
						$response['daily_calling_status']['not_interested'] += $leads->count();
						$response['daily_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);	 			 
					}	 */
					
				 
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
						//$leads = $leads->where('leads.created_by','=',$user_id);
						$leads = $leads->where('fu.followby','=',$user_id);
						$response['daily_calling_status']['not_interested'] = $leads->count();
						$response['daily_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);	 			 
									
				}
				
				// Visits
				// ******
				
				if($viewAll){	
					$leads = DB::table('croma_demos as demos');
					$leads = $leads->whereDate('demos.created_at','=',date('Y-m-d', $yesterday));					
					$leads = $leads->whereNull('demos.deleted_at');
					$response['daily_calling_status']['visits'] = $leads->count();
					$response['daily_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{					
						$leads = DB::table('croma_demos as demos');
					$leads = $leads->whereDate('demos.created_at','=',date('Y-m-d', $yesterday));
					$leads = $leads->where('demos.created_by','=',$user_id);
					
					$leads = $leads->whereNull('demos.deleted_at');
					$response['daily_calling_status']['visits'] = $leads->count();
					$response['daily_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){ 				
					/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($capability as $manage){					
						$leads = DB::table('croma_demos as demos');
						$leads = $leads->whereDate('demos.created_at','=',date('Y-m-d', $yesterday));
						$leads = $leads->where('demos.created_by','=',$manage->user_id);
						$leads = $leads->whereNull('demos.deleted_at');
						$response['daily_calling_status']['visits'] += $leads->count();
						$response['daily_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);		 			 
					} */
					
					
					 					
						$leads = DB::table('croma_demos as demos');
						$leads = $leads->whereDate('demos.created_at','=',date('Y-m-d', $yesterday));
						$leads = $leads->where('demos.created_by','=',$user_id);
						$leads = $leads->whereNull('demos.deleted_at');
						$response['daily_calling_status']['visits'] = $leads->count();
						$response['daily_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);		 			 
									
				}
				
				// JOINED 
				// ******
				 
				if($viewAll){	
					$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));					 
					$response['daily_calling_status']['joined'] = $leads->count();
					$response['daily_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					
					$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					//$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['joined'] = $leads->count();
					$response['daily_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){ 				
					/* $capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($capability as $manage){					
						$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					$leads = $leads->where('demos.created_by','=',$manage->user_id);
					$response['daily_calling_status']['joined'] += $leads->count();
					$response['daily_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);	 			 
					} */	
					
					 					
					$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					//$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['joined'] = $leads->count();
					$response['daily_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);	 			 
						

					
				}
				
				// TOTAL CALL COUNT
				// ****************
					// LEADS
					// *****
					
				/*	
					if($viewAll){	
					
					$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Favorite' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));		
						$response['daily_calling_status']['total_call_count_leads'] = $leads->count();
					
					 
					
					
				}else{


					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Favorite' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				 
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['total_call_count_leads'] = $leads->count();
						
					 
					
				}
				if(!empty($viewManage)){ 	
				$leads = DB::table('croma_leads as leads');
				$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Favorite' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.lead_id";
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			 
				$leads = $leads->where('fu.followby','=',$user_id);	
				$response['daily_calling_status']['total_call_count_leads'] = $leads->count();
					
					
				} */
					
					$response['daily_calling_status']['total_call_count_leads'] = $response['daily_calling_status']['interested']+$response['daily_calling_status']['pending']+$response['daily_calling_status']['not_interested'];
				
				
				
					// DEMOS
					// *****
					
					if($viewAll){
							$leads = DB::table('croma_demos as demos');
						$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.demo_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));						 
						$response['daily_calling_status']['total_call_count_demos'] = $leads->count();
					}else{
						$leads = DB::table('croma_demos as demos');
						$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.demo_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
						//$leads = $leads->where('demos.created_by','=',$user_id);
					    $leads = $leads->where('fu.followby','=',$user_id);	$response['daily_calling_status']['total_call_count_demos'] = $leads->count();
						
					}
					if(!empty($viewManage)){ 				
						/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
						foreach($Capability as $manage){					
							$leads = DB::table('croma_demos as demos');
							$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.demo_id";
							$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
							$leads = $leads->where('demos.created_by','=',$manage->user_id);
							$response['daily_calling_status']['total_call_count_demos'] += $leads->count();	 			 
						} */
						
						 				
							$leads = DB::table('croma_demos as demos');
							$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)='".date('Y-m-d', $yesterday)."' GROUP BY m1.demo_id";
							$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
							//$leads = $leads->where('demos.created_by','=',$user_id);
						$leads = $leads->where('fu.followby','=',$user_id);	$response['daily_calling_status']['total_call_count_demos'] = $leads->count();	 			 
							

						
					}

			// FIND WEEKLY CALLING STATUS
			$baseValue = 150;
			$monday = strtotime("last monday midnight");
			$now = strtotime("now");
			$sunday = strtotime("next sunday", $monday);
			$diff = date_diff(date_create(date('Y-m-d',$monday)),date_create(date('Y-m-d',$now)));
			$baseValue *= ($diff->days+1);
				// NEW LEADS
				// *********
				
				if($viewAll){
				$leads = DB::table('croma_leads as leads');
				$leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d', $monday));
				$leads = $leads->whereDate('leads.created_at','<=',date('Y-m-d', $sunday));
				$leads = $leads->whereNull('leads.deleted_at');
				$response['weekly_calling_status']['new_lead'] = $leads->count();
				$response['weekly_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
			
				$leads = DB::table('croma_leads as leads');
				$leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d', $monday));
				$leads = $leads->whereDate('leads.created_at','<=',date('Y-m-d', $sunday));
				$leads = $leads->where('leads.created_by','=',$user_id);
				$leads = $leads->whereNull('leads.deleted_at');
				$response['weekly_calling_status']['new_lead'] = $leads->count();
				$response['weekly_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);
					
				}
				if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){					
						$leads = DB::table('croma_leads as leads');
						$leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d', $monday));
						$leads = $leads->whereDate('leads.created_at','<=',date('Y-m-d', $sunday));
						$leads = $leads->where('leads.created_by','=',$manage->user_id);
						$leads = $leads->whereNull('leads.deleted_at');
						$response['weekly_calling_status']['new_lead'] += $leads->count();
						$response['weekly_calling_status']['new_lead_percent'] += round(($leads->count()/$baseValue)*100, 2);			 
					} */
					
					
				 					
						$leads = DB::table('croma_leads as leads');
						$leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d', $monday));
						$leads = $leads->whereDate('leads.created_at','<=',date('Y-m-d', $sunday));
						$leads = $leads->where('leads.created_by','=',$user_id);
						$leads = $leads->whereNull('leads.deleted_at');
						$response['weekly_calling_status']['new_lead'] = $leads->count();
						$response['weekly_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);			 
						

					
				}

				// INTERESTED
				// **********
				
				if($viewAll){
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'Favorite') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					$response['weekly_calling_status']['interested'] = $leads->count();
					$response['weekly_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'Favorite') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['weekly_calling_status']['interested'] = $leads->count();
					$response['weekly_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);					
				}
				if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){					
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					$leads = $leads->where('leads.created_by','=',$manage->user_id);
					$response['weekly_calling_status']['interested'] += $leads->count();
					$response['weekly_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);				 
					}	 */
					
				 					
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'interested' || `name` LIKE 'Favorite') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['weekly_calling_status']['interested'] = $leads->count();
					$response['weekly_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);				 
						

					
				}
				// PENDING
				// *******
				
					if($viewAll){
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
						
						$response['weekly_calling_status']['pending'] = $leads->count();
						$response['weekly_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);
					}else{
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
						//$leads = $leads->where('leads.created_by','=',$user_id);
						$leads = $leads->where('fu.followby','=',$user_id);
						$response['weekly_calling_status']['pending'] = $leads->count();
						$response['weekly_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);
						
					}
					if(!empty($viewManage)){ 				
						/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
						foreach($Capability as $manage){				
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
						$leads = $leads->where('leads.created_by','=',$manage->user_id);
						$response['weekly_calling_status']['pending'] += $leads->count();
						$response['weekly_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);			 
						}	 */
						
									
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
						//$leads = $leads->where('leads.created_by','=',$user_id);
						$leads = $leads->where('fu.followby','=',$user_id);
						$response['weekly_calling_status']['pending'] = $leads->count();
						$response['weekly_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);			 
							

						
					}
				// NOT INTERESTED
				// **************
				
				if($viewAll){
					
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));				
					$response['weekly_calling_status']['not_interested'] = $leads->count();
					$response['weekly_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{						
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['weekly_calling_status']['not_interested'] = $leads->count();
					$response['weekly_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){				
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					$leads = $leads->where('leads.created_by','=',$manage->user_id);
					$response['weekly_calling_status']['not_interested'] += $leads->count();
					$response['weekly_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);			 
					} */
					
				 				
					$leads = DB::table('croma_leads as leads');
					$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['weekly_calling_status']['not_interested'] = $leads->count();
					$response['weekly_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);			 
					

					
				}
				// Visits
				// ******
				
				if($viewAll){
						$leads = DB::table('croma_demos as demos');
					$leads = $leads->whereDate('demos.created_at','>=',date('Y-m-d', $monday));
					$leads = $leads->whereDate('demos.created_at','<=',date('Y-m-d', $sunday));			
					$leads = $leads->whereNull('demos.deleted_at');
					$response['weekly_calling_status']['visits'] = $leads->count();
					$response['weekly_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					$leads = DB::table('croma_demos as demos');
					$leads = $leads->whereDate('demos.created_at','>=',date('Y-m-d', $monday));
					$leads = $leads->whereDate('demos.created_at','<=',date('Y-m-d', $sunday));
					$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->whereNull('demos.deleted_at');
					$response['weekly_calling_status']['visits'] = $leads->count();
					$response['weekly_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){				
					$leads = DB::table('croma_demos as demos');
					$leads = $leads->whereDate('demos.created_at','>=',date('Y-m-d', $monday));
					$leads = $leads->whereDate('demos.created_at','<=',date('Y-m-d', $sunday));
					$leads = $leads->where('demos.created_by','=',$manage->user_id);
					$leads = $leads->whereNull('demos.deleted_at');
					$response['weekly_calling_status']['visits'] += $leads->count();
					$response['weekly_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);		 
					} */
					
					 			
					$leads = DB::table('croma_demos as demos');
					$leads = $leads->whereDate('demos.created_at','>=',date('Y-m-d', $monday));
					$leads = $leads->whereDate('demos.created_at','<=',date('Y-m-d', $sunday));
					$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->whereNull('demos.deleted_at');
					$response['weekly_calling_status']['visits'] = $leads->count();
					$response['weekly_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);		 
									
				}
				
				// JOINED
				// ******
				
				if($viewAll){
					$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));				 
					$response['weekly_calling_status']['joined'] = $leads->count();
					$response['weekly_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					
					$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					//$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['weekly_calling_status']['joined'] = $leads->count();
					$response['weekly_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){				
					$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					$leads = $leads->where('demos.created_by','=',$manage->user_id);
					$response['weekly_calling_status']['joined'] += $leads->count();
					$response['weekly_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);	 
					} */
					
					
				 				
					$leads = DB::table('croma_demos as demos');
					$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					//$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['weekly_calling_status']['joined'] = $leads->count();
					$response['weekly_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);	 
					

					
				}
				// TOTAL CALL COUNT
				// ****************
					// LEADS
					// *****
					
				/*	if($viewAll){
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					 
						$response['weekly_calling_status']['total_call_count_leads'] = $leads->count();
					}else{
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM croma_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					 
					    $leads = $leads->where('fu.followby','=',$user_id);
					   $response['weekly_calling_status']['total_call_count_leads'] = $leads->count();
					}

					if(!empty($viewManage)){ 	
					 
						$leads = DB::table('croma_leads as leads');
						$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.lead_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				    	$leads = $leads->where('fu.followby','=',$user_id);	$response['weekly_calling_status']['total_call_count_leads'] = $leads->count(); 
							
						
					}
					
					*/
					$response['weekly_calling_status']['total_call_count_leads'] = $response['weekly_calling_status']['interested']+$response['weekly_calling_status']['pending']+$response['weekly_calling_status']['not_interested'];
					// DEMOS
					// *****
					
					if($viewAll){
						$leads = DB::table('croma_demos as demos');
						$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.demo_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
						//$leads = $leads->where('demos.created_by','=',$user_id);
						$response['weekly_calling_status']['total_call_count_demos'] = $leads->count();
					}else{
						$leads = DB::table('croma_demos as demos');
						$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.demo_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
						//$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);	$response['weekly_calling_status']['total_call_count_demos'] = $leads->count();
					}

					if(!empty($viewManage)){ 				
						/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
						foreach($Capability as $manage){				
						$leads = DB::table('croma_demos as demos');
						$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.demo_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
						$leads = $leads->where('demos.created_by','=',$manage->user_id);
						$response['weekly_calling_status']['total_call_count_demos'] += $leads->count(); 
						} */
						
						 				
						$leads = DB::table('croma_demos as demos');
						$rawQuery = "SELECT m1.* FROM croma_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `croma_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".date('Y-m-d', $monday)."' AND DATE(m1.created_at)<='".date('Y-m-d', $sunday)."' GROUP BY m1.demo_id";
						$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
						//$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);	$response['weekly_calling_status']['total_call_count_demos'] = $leads->count(); 
						

						
					}
			// RETURNING RESULT SET
			// ********************
			return response()->json(compact('response'),200);
			
			
			
		}
		
		
		
		
		$courses = Course::all();
		
		
			$FeesCourse = FeesCourse::all();
		$feesGetTrainer = FeesGetTrainer::all();
		if(Auth::user()->current_user_can('abgyan_follow_up') ){
		$statuses = Status::where('abgyan_follow_up',1)->get();
		}else{
		    	if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
		            $statuses = Status::where('lead_filter',1)->where('name','!=','Not Interested')->orWhere('abgyan_follow_up',1)->get(); 
		          
		    	}else{
		    	    
		    	    
		    	      $statuses = Status::where('lead_filter',1)->where('name','!=','Not Interested')->get();  
		    	}
		    
		}
		$counsellors = [];
		$counsellor_data= [];
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator') || $request->user()->current_user_can('lead_demo_all')){
			$counsellors = User::orderBy('name')->get();
			
			$counsellor_data = User::orderBy('name')->get();
			  
			foreach($counsellor_data as $key=>$coun)
			{
			$totalfollow = DB::table('croma_leads as leads');
			$rawQuery = "SELECT m1.* FROM `croma_lead_follow_ups` m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status NOT IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'interested' || `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close' || `name` LIKE 'attended demo')";
			$totalfollow = $totalfollow->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			$totalfollow = $totalfollow->where('leads.created_by','=',$coun->id);
			$totalfollow = $totalfollow->count();
			
			$counsellor_list[$key] = array(
			
			'id'=>$coun->id,
			'name'=>$coun->name,
			'totalfollow'=>$totalfollow
			
			);
			
			}  
			$counsellor_data = $counsellor_list;
		}
		 
		 
		if($request->user()->current_user_can('manager')){
			
					
			$capabilitys = Capability::select('user_id')->where('manager',$request->user()->id)->get();		
			$user_lists=[$request->user()->id];
			foreach($capabilitys as $manage){
				array_push($user_lists,$manage->user_id);		 
			}
			 $counsellors = User::select('id','name')->whereIn('id',$user_lists)->orderBy('name','ASC')->get();
		}
		if($request->user()->current_user_can('TL')){
			
					
			$capabilitys = Capability::select('user_id')->where('TL',$request->user()->id)->get();		
			$user_lists=[$request->user()->id];
			foreach($capabilitys as $manage){
				array_push($user_lists,$manage->user_id);		 
			}
			 $counsellors = User::select('id','name')->whereIn('id',$user_lists)->orderBy('name','ASC')->get();
		}
		
	  $users = User::get();
		$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
		 
		 
	 
		
        return view('cm_counsellor_dashboard.index',['courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'counsellors'=>$counsellors,'counsellor_data'=>$counsellor_data,'feesGetTrainer'=>$feesGetTrainer,'FeesCourse'=>$FeesCourse,'users'=>$users]);
    }

    /**
     * Get paginated leads and demos at one place.
     *
     * @param Request $request
     * @return ajax response with payload
     */
    public function getPaginatedLeadsDemos(Request $request)
    {  
		if($request->ajax()){
		 
			$user_id = $request->user()->id;
			$id = $request->input('search.counsellor');
			 
			$viewAll = false;
			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
				$viewAll = true;
			}
			if(!is_null($id) && !empty($id)){
				$user_id = $id;
			}
			 
			$data = [];				
			 
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id','left');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_demo_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
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
			}else{
				$rawQuery .= " AND m1.status NOT IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'Close')";
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			if($request->input('search.expdf')=='' && $request->input('search.expdt')==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d')."'";
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('demos.course',$courseList);
			}
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('demos.id','desc');
		//	$leads = $leads->orderby(DB::raw('(CASE `leads`.`status` WHEN \'18\' THEN 3 WHEN \'3\' THEN 2 WHEN \'2\' THEN 2  END)'),'DESC');
			$leads = $leads->where('demos.created_by','=',$user_id);
			$leads = $leads->whereNull('demos.deleted_at');
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
 
			// Code to insert into the 'cm_temp_leads' table
			$leads = $leads->get();
			
			if($leads){
				//$data = [];
				foreach($leads as $lead){
					$data[] = [
						'target_id'=>$lead->id,
						'expected_date_time'=>($lead->expected_date_time==NULL)?"":$lead->expected_date_time,
					
						'name'=>$lead->name,
					//	'mobile'=>$lead->mobile,
						'course_name'=>$lead->course_name,
						'target_type'=>'demo',
						'status_name'=>$lead->status_name,
						'status'=>$lead->status,
						'remark'=>$lead->remark
					];
				}
				//DB::table('temp_demos')->insert($data);
			}
			// Code to insert into the 'cm_temp_leads' table
			
			usort($data,function($a,$b){
				$t1 = strtotime($a['expected_date_time']);
				$t2 = strtotime($b['expected_date_time']);
				//return $t1 - $t2; //ascending
				return $t2 - $t1; //descending
			});
		
			
			$currentPage = Paginator::resolveCurrentPage();
			$collection = new Collection($data);
			$perPage = $request->input('length');
			$currentPageSearchResults = $collection->slice($currentPage*$perPage-$perPage, $perPage)->all();
			$leads = new Paginator($currentPageSearchResults,count($collection),$perPage,$currentPage);
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['demoRecordCollection'] = []; 
			 
			foreach($leads->items() as $lead){
				$action = '';
				$separator = '';
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
					$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead['remark']==NULL)?"Remark Not Available":$lead['remark']).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
					$separator = ' | ';
				}
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
					$action .= $separator.'<a href="javascript:demoController.getfollowUps('.$lead['target_id'].')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
					$separator = ' | ';
				}
				$data[] = [
				
					"<th><input type='checkbox' class='check-box' value='".$lead['target_id']."'></th>",
					($lead['expected_date_time']=="")?"":(new Carbon($lead['expected_date_time']))->format('d-m-Y h:i A'),
					$lead['name'],
				//	$lead['mobile'],
					$lead['course_name'],
					$lead['target_type'],
					$lead['status_name'],
					$action
				];
				 
				$returnLeads['demoRecordCollection'][] = $lead['target_id'];
			}
			 
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
		}
    }
	
	  
    	
    /**
     * Get excel leads and demos at one place.
     *
     * @param Request $request
     * @return ajax response with payload
     */
    public function getLeadsDemosExcel(Request $request)
    { 		
		 
			$user_id = $request->user()->id;
			$id = $request->input('search.counsellor');
		 
			$viewAll = false;
			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
				$viewAll = true;
			}
			if(!is_null($id) && !empty($id)){
				$user_id = $id;
			}
			$leads = DB::table('croma_demos as demos');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id','left');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_demo_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('search.statuss')!==''){
				 
				 
				$rawQuery .= " AND m1.status=".$request->input('search.statuss');
			}else{
				$rawQuery .= " AND m1.status NOT IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close')";
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			if($request->input('search.expdf')!=='' && $request->input('search.expdt')==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d')."'";
			}
			if($request->input('search.courses')!==''){
				 
				$leads = $leads->where('demos.course',$request->input('search.courses'));
			}
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('demos.id','desc');
			$leads = $leads->where('demos.created_by','=',$user_id);
			$leads = $leads->whereNull('demos.deleted_at');
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%');
				});
			}
			

			// Code to insert into the 'cm_temp_leads' table
			$leads = $leads->get();
			 
			if($leads){
				//$data = [];
				foreach($leads as $lead){
					$data[] = [
						'target_id'=>$lead->id,
						'expected_date_time'=>($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-Y h:i A'),
						'name'=>$lead->name,
						'mobile'=>$lead->mobile,
						'course_name'=>$lead->course_name,
						'target_type'=>'demo',
						'status_name'=>$lead->status_name,
						'status'=>$lead->status,
						'remark'=>$lead->remark
					];
				}
				 
			}
			// Code to insert into the 'cm_temp_leads' table
			
			$excel = \App::make('excel');
			Excel::create('dashboard_demos_'.date('Y-m-d H:i A'), function($excel) use($data) {
				$excel->sheet('Sheet 1', function($sheet) use($data) {
					$sheet->fromArray($data);
				});
			})->export('xls');
		
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
			 
			$user_id = $request->user()->id;
			$id = $request->input('search.counsellor');
			 
			$viewAll = false;
			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
				$viewAll = true;
			}
			if(!is_null($id) && !empty($id)){
				$user_id = $id;
			}
			 
			$data = [];
			$leads = DB::table(Session::get('company_id').'_leads as leads');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_lead_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";

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
			}else{
				$rawQuery .= " AND m1.status NOT IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'attended demo')";
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			if($request->input('search.expdf')=='' && $request->input('search.expdt')==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d')."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
		 
			$leads = $leads->orderByRaw("FIELD(fu.status, '18') DESC");
			$leads = $leads->orderBy("fu.expected_date_time",'DESC');
	  		$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$leads = $leads->where('leads.created_by','=',$user_id);
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.course')!==''){
				$courses = $request->input('search.course');
				foreach($courses as $course){
					$courseList[] = $course;
				}
				$leads = $leads->whereIn('leads.course',$courseList);
			}
			
		
			$leads = $leads->get();
			
		 
			if($leads){
			
				foreach($leads as $lead){
					$data[] = [
						'target_id'=>$lead->id,
						'expected_date_time'=>($lead->expected_date_time==NULL)?"":$lead->expected_date_time,
					
						'name'=>$lead->name,
					//	'mobile'=>$lead->mobile,
						'course_name'=>$lead->course_name,
						'target_type'=>'lead',
						'status_name'=>$lead->status_name,
						'status'=>$lead->status,
						'remark'=>$lead->remark
					];
				}
				
			}
			
			
		/*	
			usort($data,function($a,$b){
				$t1 = strtotime($a['expected_date_time']);
				$t2 = strtotime($b['expected_date_time']);
				//return $t1 - $t2; //ascending
				return $t2 - $t1; //descending
			});*/
		
			$currentPage = Paginator::resolveCurrentPage();
			$collection = new Collection($data);
			$perPage = $request->input('length');
			$currentPageSearchResults = $collection->slice($currentPage*$perPage-$perPage, $perPage)->all();
			$leads = new Paginator($currentPageSearchResults,count($collection),$perPage,$currentPage);
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['recordCollection'] = []; 
			
			foreach($leads->items() as $lead){
				$action = '';
				$separator = '';
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
					$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead['remark']==NULL)?"Remark Not Available":$lead['remark']).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
					$separator = ' | ';
				}
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('manager') || Auth::user()->current_user_can('TL') || Auth::user()->current_user_can('all_lead_follow_up')){
					$action .= $separator.'<a href="javascript:'.$lead['target_type'].'Controller.getfollowUps('.$lead['target_id'].')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
					$separator = ' | ';
				}
				$data[] = [
				
					"<th><input type='checkbox' class='check-box' value='".$lead['target_id']."'></th>",
					($lead['expected_date_time']=="")?"":(new Carbon($lead['expected_date_time']))->format('d-m-Y h:i A'),
					$lead['name'],
				//	$lead['mobile'],
					$lead['course_name'],
					$lead['target_type'],
					$lead['status_name'],
					$action
				];
				 
				$returnLeads['recordCollection'][] = $lead['target_id'];
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
    public function getPaginatedCounsellorlist(Request $request)
    {  
		if($request->ajax()){	
			
			//if($request->user()->current_user_can('super_admin')){		
			$user = User::orderBy('name');		
			if($request->input('search.value')!==''){
			$user = $user->where(function($query) use($request){
			$query->orWhere('name','LIKE','%'.$request->input('search.value').'%');

			});
			}
			if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){

			$getID= [];
			$capability = Capability::all();	 
			 
			foreach($capability as $key=>$manage){	 				 
				array_push($getID,$manage->user_id);
			}
			$user = User::whereIn('id',$getID);

			} 
			
			
			if(Auth::user()->current_user_can('manager')){

			$getID= [];
			$capability = Capability::select('user_id')->where('manager',$request->user()->id)->get();	 
			foreach($capability as $key=>$manage){	 				 
				array_push($getID,$manage->user_id);
			}
	    	$user = User::whereIn('id',$getID);

			} 

			if(Auth::user()->current_user_can('TL')){
			$getID= [];
			$capability = Capability::select('user_id')->where('TL',$request->user()->id)->get();	 
			foreach($capability as $key=>$manage){	 				 
				array_push($getID,$manage->user_id);
			}
			$user = User::whereIn('id',$getID);

			} 

			$user = $user->paginate($request->input('length'));
			 
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $user->total();
			$returnLeads['recordsFiltered'] = $user->total();
			$returnLeads['recordCollection'] = [];

			foreach($user as $counsellor)
			{
				$totalfollow = DB::table(Session::get('company_id').'_leads as leads');
				$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.status NOT IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'interested' || `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close' || `name` LIKE 'attended demo')";
				$totalfollow = $totalfollow->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				$totalfollow = $totalfollow->where('leads.created_by','=',$counsellor->id);
				$totalfollow = $totalfollow->count();

				$action = '';
				$separator = '';
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_remark')){
				$action .= $separator.'<a href="/dashboard/counsellor/'.$counsellor->id.'" class="counsellor_control"><input type="hidden" id="getCounsel" value="'.$counsellor->id.'" ><i aria-hidden="true" class="fa fa-comment"></i></a>';
			 
				}
				 
				$data[] = [					 
					$counsellor->name,
					$totalfollow,					 
					$action
				];
				$returnLeads['recordCollection'][] = $counsellor->id;
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
    /**
     * Get Leads Excel.
     *
     * @param  Request $request
     */
    public function getLeadsExcel(Request $request)
    { 
		 
			 
			
			$user_id = $request->user()->id;
			$id = $request->input('search.counsellor');
			 
			$viewAll = false;
			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
				$viewAll = true;
			}
			if(!is_null($id) && !empty($id)){
				$user_id = $id;
			}
			 
			 

			$leads = DB::table(Session::get('company_id').'_leads as leads');
			$leads = $leads->join('croma_cat_course as courses','leads.course','=','courses.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_lead_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";

			if($request->input('search.status')!==''){
				 
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}else{
				$rawQuery .= " AND m1.status NOT IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'attended demo')";
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			if($request->input('search.expdf')=='' && $request->input('search.expdt')==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d')."'";
			}
			if($request->input('search.course')!==''){
				 
				$leads = $leads->where('leads.course',$request->input('search.course'));
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('leads.*','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('leads.id','desc');
			$leads = $leads->whereNull('leads.deleted_at');
			$leads = $leads->where('leads.demo_attended','=','0');
			$leads = $leads->where('leads.created_by','=',$user_id);
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('leads.name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('leads.mobile','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			// Code to insert into the 'cm_temp_leads' table
			$leads = $leads->get();
		 
			$returnLeads = [];
		 $data=[];
			 $returnLeads['draw'] = $request->input('draw');
		
			$returnLeads['recordCollection'] = [];
			foreach($leads as $lead){
				$action = '';
				$separator = '';
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_lead_follow_up')){
					$action .= $separator.'<a href="javascript:leadController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
					$separator = ' | ';
				}
				$data[] = [
					"Expected Date"=>($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-Y h:i A'),
					"Name"=>$lead->name,
					"Mobile"=>$lead->mobile,
					"Technology"=>$lead->course_name,
					"Status"=>$lead->status_name
				];
				$returnLeads['recordCollection'][] = $lead->id;
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			Excel::create('Dashboard_leads_'.date('Y-m-d H:i a'), function($excel) use($data) {
				$excel->sheet('Sheet 1', function($sheet) use($data) {
					$sheet->fromArray($data);
				});
			})->export('xls');
			//return $leads->links();
		//}
    }
	
	
    
	
    /**
     * Get paginated demos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedDemos(Request $request)
    {  
		if($request->ajax()){
		
			$leads = DB::table(Session::get('company_id').'_demos as demos');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_demo_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}else{
				$rawQuery .= " AND m1.status NOT IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'Close')";
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			if($request->input('search.expdf')=='' && $request->input('search.expdt')==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d')."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'));
			$leads = $leads->orderBy('demos.id','desc');
			$leads = $leads->where('demos.created_by','=',$request->user()->id);
			$leads = $leads->whereNull('demos.deleted_at');
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.course')!==''){
				$leads = $leads->where('demos.course',$request->input('search.course'));
			}
			$leads = $leads->paginate($request->input('length'));
			//dd($leads->toSql());
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $leads->total();
			$returnLeads['recordsFiltered'] = $leads->total();
			$returnLeads['recordCollection'] = [];
			foreach($leads as $lead){
				$action = '';
				$separator = '';
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_demo_remark')){
					$action .= $separator.'<a href="javascript:void(0)" data-toggle="popover" title="Counsellor Remark" data-content="'.(($lead->remark==NULL)?"Remark Not Available":$lead->remark).'" data-trigger="hover" data-placement="left"><i aria-hidden="true" class="fa fa-comment"></i></a>';
					$separator = ' | ';
				}
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('add_demo_follow_up')){
					$action .= $separator.'<a href="javascript:demoController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
					$separator = ' | ';
				}
				$data[] = [
					($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-Y h:i A'),
					$lead->name,
					$lead->mobile,
					$lead->course_name,
					$lead->status_name,
					$action
				];
				$returnLeads['recordCollection'][] = $lead->id;
			}
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);
			//return $leads->links();
		}
    }
	
    /**
     * Get paginated demos.
     *
     * @param  Request $request
     */
    public function getDemosExcel(Request $request)
    {
		//if($request->ajax()){
			$leads = DB::table(Session::get('company_id').'_demos as demos');
			$leads = $leads->join('croma_cat_course as courses','demos.course','=','courses.id');
			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*,m3.name as status_name FROM ".Session::get('company_id')."_demo_follow_ups m1 LEFT JOIN ".Session::get('company_id')."_demo_follow_ups m2 ON (m1.demo_id = m2.demo_id AND m1.id < m2.id) INNER JOIN ".Session::get('company_id')."_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";
			
			if($request->input('search.status')!==''){
				$rawQuery .= " AND m1.status=".$request->input('search.status');
			}else{
				$rawQuery .= " AND m1.status NOT IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'not interested' || `name` LIKE 'location issue' || `name` LIKE 'joined' || `name` LIKE 'Close')";
			}
			
			if($request->input('search.expdf')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)>='".date('Y-m-d',strtotime($request->input('search.expdf')))."'";
			}
			
			if($request->input('search.expdt')!==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d',strtotime($request->input('search.expdt')))."'";
			}
			
			if($request->input('search.expdf')=='' && $request->input('search.expdt')==''){
				$rawQuery .= " AND DATE(m1.expected_date_time)<='".date('Y-m-d')."'";
			}
			
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
			// generating raw query to make join
			
			$leads = $leads->select('demos.*','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'));
			$leads = $leads->orderBy('demos.id','desc');
			$leads = $leads->where('demos.created_by','=',$request->user()->id);
			$leads = $leads->whereNull('demos.deleted_at');
			if($request->input('search.value')!==''){
				$leads = $leads->where(function($query) use($request){
					$query->orWhere('demos.name','LIKE','%'.$request->input('search.value').'%')
						  ->orWhere('demos.mobile','LIKE','%'.$request->input('search.value').'%');
				});
			}
			if($request->input('search.course')!==''){
				$leads = $leads->where('demos.course',$request->input('search.course'));
			}
			$leads = $leads->get();
		
			$returnLeads = [];
			$data = [];
			
			$returnLeads['recordCollection'] = [];
			foreach($leads as $lead){
				$action = '';
				$separator = '';
				if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('add_demo_follow_up')){
					$action .= $separator.'<a href="javascript:demoController.getfollowUps('.$lead->id.')" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a>';
					$separator = ' | ';
				}
				$data[] = [
					"Expected Date"=>($lead->expected_date_time==NULL)?"":(new Carbon($lead->expected_date_time))->format('d-m-Y h:i A'),
					"Name"=>$lead->name,
					"Mobile"=>$lead->mobile,
					"Technology"=>$lead->course_name,
					"Status"=>$lead->status_name
				];
				$returnLeads['recordCollection'][] = $lead->id;
			}
			
			$excel = \App::make('excel');
			Excel::create('demos_pending', function($excel) use($data) {
				$excel->sheet('Sheet 1', function($sheet) use($data) {
					$sheet->fromArray($data);
				});
			})->export('xls');
		//}
    }
	
	/**
     * Return data to draw the graph.
     *
     * @param  Request $request
     */
	public function drawGraph(Request $request){
		if($request->ajax()){
			$leads = DB::table(Session::get('company_id').'_leads as leads');
			$leads = $leads->select(DB::raw('month(created_at) as month'),DB::raw('count(*) as total_count'));
			$leads = $leads->where(DB::raw('month(created_at)'),'<',date('m'));
			$leads = $leads->where('leads.created_by',$request->user()->id);
			$leads = $leads->groupBy(DB::raw('month(created_at)'));
			$total_leads = $leads->get();
			
			$leads = DB::table(DB::raw(Session::get('company_id').'_demo_follow_ups as m1'));
			$leads = $leads->leftJoin(DB::raw(Session::get('company_id').'_demo_follow_ups as m2'),function($join){
				$join->on(DB::raw('m1.demo_id'),'=',DB::raw('m2.demo_id'))
					->on(DB::raw('m1.id'),'<',DB::raw('m2.id'));
			});
			$leads = $leads->join(DB::raw(Session::get('company_id').'_status as m3'),function($join){
				$join->on(DB::raw('m1.status'),'=',DB::raw('m3.id'))
					->where(DB::raw('m3.name'),'LIKE','attended demo');
			});
			$leads = $leads->whereNull(DB::raw('m2.id'));
			$leads = $leads->groupBy(DB::raw('month(m1.created_at)'));
			$leads = $leads->having('month','<',date('m'));
			$leads = $leads->select(DB::raw('month(m1.created_at) as month'),DB::raw('count(*) as total_count'));
			$total_joined = $leads->get();
			
			return response()->json(['total_leads'=>$total_leads,'total_joined'=>$total_joined],200);
		}
	}
	
    /**
     * Return daily calling status based on received date in post request.
     *
	 * @param user id, from and to date.
     * @return JSON Object.
     */
    public function getCallingStatus(Request $request, $id=null)
    {
		if($request->wantsJson()){
			$user_id = $request->user()->id;
			 
			$viewAll = false;
			$viewManage=false;
			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))){
				$viewAll = true;
			}
			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('manager') )){
				$viewManage = true;
			}
			if((is_null($id) || empty($id)) && ($request->user()->current_user_can('TL') )){
				$viewManage = true;
			}
			if(!is_null($id) && !empty($id)){
				$user_id = $id;
			}

			
			
			// FIND DAILY CALLING STATUS
			// *************************
			$baseValue = 150;
			$response= []; 
		
			$fromDate = $request->input('fromDate');
			$toDate = $request->input('toDate');
			$diff = date_diff(date_create($fromDate),date_create($toDate));
			$baseValue *= ($diff->days+1);
				// NEW LEADS
				// *********
				if(!empty($viewAll)){	
				
				$leads = DB::table(Session::get('company_id').'_leads as leads');
				if(!empty($fromDate))
					$leads = $leads->whereDate('leads.created_at','>=',$fromDate);
				if(!empty($toDate))
					$leads = $leads->whereDate('leads.created_at','<=',$toDate);
				 
				$leads = $leads->whereNull('leads.deleted_at');
				$leads = $leads->where('leads.demo_attended','=','0');
				$response['daily_calling_status']['new_lead'] = $leads->count();
				$response['daily_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					$leads = DB::table(Session::get('company_id').'_leads as leads');
					if(!empty($fromDate))
						$leads = $leads->whereDate('leads.created_at','>=',$fromDate);
					if(!empty($toDate))
						$leads = $leads->whereDate('leads.created_at','<=',$toDate);
					$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->whereNull('leads.deleted_at');
					$response['daily_calling_status']['new_lead'] = $leads->count();
					$response['daily_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				
				  			
				if(!empty($viewManage)){
 					/* 
					$Capability = Capability::select('user_id')->where('manager',$user_id)->get();	 
					foreach($Capability as $manage){
					$leads = DB::table(Session::get('company_id').'_leads as leads');
					if(!empty($fromDate))
						$leads = $leads->whereDate('leads.created_at','>=',$fromDate);
					if(!empty($toDate))
						$leads = $leads->whereDate('leads.created_at','<=',$toDate);
					$leads = $leads->where('leads.created_by','=',$manage->user_id);
					$leads = $leads->whereNull('leads.deleted_at');
					$response['daily_calling_status']['new_lead'] += $leads->count();
					$response['daily_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);			 			 
					} */
					
					
			 
					$leads = DB::table(Session::get('company_id').'_leads as leads');
					if(!empty($fromDate))
						$leads = $leads->whereDate('leads.created_at','>=',$fromDate);
					if(!empty($toDate))
						$leads = $leads->whereDate('leads.created_at','<=',$toDate);
					$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->whereNull('leads.deleted_at');
					$response['daily_calling_status']['new_lead'] = $leads->count();
					$response['daily_calling_status']['new_lead_percent'] = round(($leads->count()/$baseValue)*100, 2);			 			 
					

					
				} 
				// INTERESTED
				// **********
				
				if(!empty($viewAll)){
				$leads = DB::table(Session::get('company_id').'_leads as leads');
				$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'interested' || `name` LIKE 'Favorite') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				
				$response['daily_calling_status']['interested'] = $leads->count();
				$response['daily_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'interested' || `name` LIKE 'Favorite') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['interested'] = $leads->count();
					$response['daily_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){

					$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					$leads = $leads->where('leads.created_by','=',$manage->user_id);
					$response['daily_calling_status']['interested'] += $leads->count();
					$response['daily_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);			 			 
					} */
					
					
				

					$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'interested' || `name` LIKE 'Favorite') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['interested'] = $leads->count();
					$response['daily_calling_status']['interested_percent'] = round(($leads->count()/$baseValue)*100, 2);			 			 
					

					
				}
				// PENDING
				// *******
				
				if(!empty($viewAll)){
				$leads = DB::table(Session::get('company_id').'_leads as leads');
				$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
				 
				$response['daily_calling_status']['pending'] = $leads->count();
				$response['daily_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['pending'] = $leads->count();
					$response['daily_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){
					
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){
						$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					$leads = $leads->where('leads.created_by','=',$manage->user_id);
					$response['daily_calling_status']['pending'] += $leads->count();
					$response['daily_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);
						
					} */
				
			 
					$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['pending'] = $leads->count();
					$response['daily_calling_status']['pending_percent'] = round(($leads->count()/$baseValue)*100, 2);
						
				
				
				}
				
				// NOT INTERESTED
				// **************
				
				if(!empty($viewAll)){
				$leads = DB::table(Session::get('company_id').'_leads as leads');
				$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));			 
				$response['daily_calling_status']['not_interested'] = $leads->count();
				$response['daily_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['not_interested'] = $leads->count();
					$response['daily_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){
						
						$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					$leads = $leads->where('leads.created_by','=',$manage->user_id);
					$response['daily_calling_status']['not_interested'] += $leads->count();
					$response['daily_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);				
						
					} */
					
					
					 
						
					$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
					//$leads = $leads->where('leads.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['not_interested'] = $leads->count();
					$response['daily_calling_status']['not_interested_percent'] = round(($leads->count()/$baseValue)*100, 2);				
						
				
					
					
				}
				// Visits
				// ******
				
				if(!empty($viewAll)){
				$leads = DB::table(Session::get('company_id').'_demos as demos');
				if(!empty($fromDate))
					$leads = $leads->whereDate('demos.created_at','>=',$fromDate);
				if(!empty($toDate))
					$leads = $leads->whereDate('demos.created_at','<=',$toDate);
				 
				$leads = $leads->whereNull('demos.deleted_at');
				$response['daily_calling_status']['visits'] = $leads->count();
				$response['daily_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					$leads = DB::table(Session::get('company_id').'_demos as demos');
					if(!empty($fromDate))
						$leads = $leads->whereDate('demos.created_at','>=',$fromDate);
					if(!empty($toDate))
						$leads = $leads->whereDate('demos.created_at','<=',$toDate);
					$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->whereNull('demos.deleted_at');
					$response['daily_calling_status']['visits'] = $leads->count();
					$response['daily_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){ 				
				/* 	$Capability = Capability::select('user_id')->where('manager',$user_id)->get();	 
 				
					foreach($Capability as $manage){
						$leads = DB::table(Session::get('company_id').'_demos as demos');
					if(!empty($fromDate)){
						$leads = $leads->whereDate('demos.created_at','>=',$fromDate);
					}
					if(!empty($toDate)){
						$leads = $leads->whereDate('demos.created_at','<=',$toDate);
					}
					$leads = $leads->where('demos.created_by','=',$manage->user_id);
					$leads = $leads->whereNull('demos.deleted_at');
					$response['daily_calling_status']['visits'] += $leads->count();
					$response['daily_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);
						
					} */
					
					
				 
					$leads = DB::table(Session::get('company_id').'_demos as demos');
					if(!empty($fromDate)){
						$leads = $leads->whereDate('demos.created_at','>=',$fromDate);
					}
					if(!empty($toDate)){
						$leads = $leads->whereDate('demos.created_at','<=',$toDate);
					}
					$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->whereNull('demos.deleted_at');
					$response['daily_calling_status']['visits'] = $leads->count();
					$response['daily_calling_status']['visits_percent'] = round(($leads->count()/$baseValue)*100, 2);
						
					
					
					
				}
				// JOINED
				// ******
				
				if(!empty($viewAll)){
				 $leads = DB::table(Session::get('company_id').'_demos as demos');
				$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."'";
				$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));			 
				$response['daily_calling_status']['joined'] = $leads->count();
				$response['daily_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}else{
					$leads = DB::table(Session::get('company_id').'_demos as demos');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					//$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['joined'] = $leads->count();
					$response['daily_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);
				}
				if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){
						
						$leads = DB::table(Session::get('company_id').'_demos as demos');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					$leads = $leads->where('demos.created_by','=',$manage->user_id);
					$response['daily_calling_status']['joined'] += $leads->count();
					$response['daily_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);
					} */
					
					
				 
						
					$leads = DB::table(Session::get('company_id').'_demos as demos');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Joined' || `name` LIKE 'Close') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."'";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
					//$leads = $leads->where('demos.created_by','=',$user_id);
					$leads = $leads->where('fu.followby','=',$user_id);
					$response['daily_calling_status']['joined'] = $leads->count();
					$response['daily_calling_status']['joined_percent'] = round(($leads->count()/$baseValue)*100, 2);
					
					
				}
				// TOTAL CALL COUNT
				// ****************
					// LEADS
					// *****
					
						if(!empty($viewAll)){
					$leads = DB::table(Session::get('company_id').'_leads as leads');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));		 
					$response['daily_calling_status']['total_call_count_leads'] = $leads->count();
					}else{
						$leads = DB::table(Session::get('company_id').'_leads as leads');
							$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
							$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
							//$leads = $leads->where('leads.created_by','=',$user_id);
							$leads = $leads->where('fu.followby','=',$user_id);
							$response['daily_calling_status']['total_call_count_leads'] = $leads->count();
					}
					
					if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){
						$leads = DB::table(Session::get('company_id').'_leads as leads');
							$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
							$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
							$leads = $leads->where('leads.created_by','=',$manage->user_id);
							$response['daily_calling_status']['total_call_count_leads'] += $leads->count();
					} */
					
				 
						$leads = DB::table(Session::get('company_id').'_leads as leads');
							$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_lead_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested' || `name` LIKE 'Not Connected') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.lead_id";
							$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
							//$leads = $leads->where('leads.created_by','=',$user_id);
						$leads = $leads->where('fu.followby','=',$user_id);	$response['daily_calling_status']['total_call_count_leads'] = $leads->count();
				 
					
					}
					
					//$response['daily_calling_status']['total_call_count_leads'] = $response['daily_calling_status']['interested']+$response['daily_calling_status']['pending']+$response['daily_calling_status']['not_interested'];
					// DEMOS
					// *****
					
					if(!empty($viewAll)){
					$leads = DB::table(Session::get('company_id').'_demos as demos');
					$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.demo_id";
					$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));		 
					$response['daily_calling_status']['total_call_count_demos'] = $leads->count();
					}else{
						$leads = DB::table(Session::get('company_id').'_demos as demos');
							$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.demo_id";
							$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
							//$leads = $leads->where('demos.created_by','=',$user_id);
						$leads = $leads->where('fu.followby','=',$user_id);	$response['daily_calling_status']['total_call_count_demos'] = $leads->count();
					}
					
					if(!empty($viewManage)){ 				
					/* $Capability = Capability::select('user_id')->where('manager',$user_id)->get();	  			
					foreach($Capability as $manage){
						$leads = DB::table(Session::get('company_id').'_demos as demos');
							$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.demo_id";
							$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
							$leads = $leads->where('demos.created_by','=',$manage->user_id);
							$response['daily_calling_status']['total_call_count_demos'] += $leads->count();
					} */
					
					 
						$leads = DB::table(Session::get('company_id').'_demos as demos');
							$rawQuery = "SELECT m1.* FROM ".Session::get('company_id')."_demo_follow_ups m1 WHERE m1.status IN (SELECT id FROM `".Session::get('company_id')."_status` WHERE `name` LIKE 'Interested' || `name` LIKE 'NPUP' || `name` LIKE 'Not Reachable' || `name` LIKE 'Call Later' || `name` LIKE 'Switched Off' || `name` LIKE 'Location Issue' || `name` LIKE 'Not Interested') AND DATE(m1.created_at)>='".$fromDate."' AND DATE(m1.created_at)<='".$toDate."' GROUP BY m1.demo_id";
							$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'demos.id','=',DB::raw('`fu`.`demo_id`'));
							//$leads = $leads->where('demos.created_by','=',$user_id);
						$leads = $leads->where('fu.followby','=',$user_id);	$response['daily_calling_status']['total_call_count_demos'] = $leads->count();
					
					
					}
					
			// RETURNING RESULT SET
			// ********************
			return response()->json(compact('response'),200);
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
				$user_id = $request->user()->id;
			$leads = DB::table(Session::get('company_id').'_lead_follow_ups as lead_follow_ups')
							->join(Session::get('company_id').'_status as status','status.id','=','lead_follow_ups.status')
							->join(Session::get('company_id').'_leads as leads','lead_follow_ups.lead_id','=','leads.created_by')
							->where('lead_follow_ups.lead_id','=',$id)
							->where('leads.created_by','=',$user_id)
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
					(new Carbon($lead->created_at))->addMinutes(330)->format('d-m-Y h:i:s'),
					$lead->remark,
					$lead->status_name,
					(new Carbon($lead->expected_date_time))->format('d-m-Y h:i A')
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
    public function followUp(Request $request, $id)
    {  
		if($request->ajax()){
			$lead = Lead::findOrFail($id);
		
			$user = DB::table(Session::get('company_id').'_users')->where('id',$lead->created_by)->first();
			$leadLastFollowUp = DB::table(Session::get('company_id').'_lead_follow_ups as lead_follow_ups')
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
			$messages = Message::where('name','LIKE','%welcome%')->orWhere('course',$lead->course)->select('id','name')->get();
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
                $statuses = Status::where('lead_follow_up',1)->orWhere('abgyan_follow_up',1)->get();
                }else{
                $statuses = Status::where('lead_follow_up',1)->get();
                
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
					$html.= '<form class="form-label-left" onsubmit="return dashboardController.storeFollowUp('.$id.',this)">
								<div class="form-group">
									<div class="col-md-4">
										<label for="name">Name rr<span class="required">:</span></label>
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
										<!--input type="text" name="mobile" class="form-control col-md-7 col-xs-12" value="'.$lead->mobile.'"-->
										<p class="form-control-static" style="display:inline">'.$lead->mobile.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label>Source <span class="required">:</span></label>
										<!--select class="select2_single form-control" name="source" tabindex="-1">
											<option value="">-- SELECT SOURCE --</option>
											'.$sourceHtml.'
										</select-->
										<p class="form-control-static" style="display:inline">'.$sourceObj->name.'</p>
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
										<label for="message">Message</label>
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
							</form>';
				}else{
					$html.= '<p style="font-size: 24px;font-weight: 700;padding-top: 20px;text-align: center;">This lead found in attended demo...</p>';
				}
			$html.=		'</div>
					</div>
					<p style="margin-top:10px;margin-bottom:-5px;"><strong>Follow Up Status</strong>  <select onchange="javascript:dashboardController.getAllFollowUps()" class="follow-up-count"><option value="5">Last 5</option><option value="all">All</option></select></p>
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
    public function demofollowUp(Request $request, $id)
    {  
		if($request->ajax()){
			$lead = Demo::findOrFail($id);
		
			$user = DB::table(Session::get('company_id').'_users')->where('id',$lead->created_by)->first();
			$leadLastFollowUp = DB::table(Session::get('company_id').'_lead_follow_ups as lead_follow_ups')
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
			$messages = Message::where('name','LIKE','%welcome%')->orWhere('course',$lead->course)->select('id','name')->get();
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
                $statuses = Status::where('lead_follow_up',1)->orWhere('abgyan_follow_up',1)->get();
                }else{
                $statuses = Status::where('lead_follow_up',1)->get();
                
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
					$html.= '<form class="form-label-left" onsubmit="return dashboardController.storeFollowUp('.$id.',this)">
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
										<!--input type="text" name="mobile" class="form-control col-md-7 col-xs-12" value="'.$lead->mobile.'"-->
										<p class="form-control-static" style="display:inline">'.$lead->mobile.'</p>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4">
										<label>Source <span class="required">:</span></label>
										<!--select class="select2_single form-control" name="source" tabindex="-1">
											<option value="">-- SELECT SOURCE --</option>
											'.$sourceHtml.'
										</select-->
										<p class="form-control-static" style="display:inline">'.$sourceObj->name.'</p>
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
										<label for="message">Message</label>
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
							</form>';
				}else{
					$html.= '<p style="font-size: 24px;font-weight: 700;padding-top: 20px;text-align: center;">This lead found in attended demo...</p>';
				}
			$html.=		'</div>
					</div>
					<p style="margin-top:10px;margin-bottom:-5px;"><strong>Follow Up Status</strong>  <select onchange="javascript:dashboardController.getAllFollowUps()" class="follow-up-count"><option value="5">Last 5</option><option value="all">All</option></select></p>
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
			$statusObj = Status::find($request->input('status'));
			if(!strcasecmp($statusObj->name,'attended demo')){
				$lead->demo_attended = 1;
			}
			 
			$lead->course = $request->input('course');
			$lead->status = $request->input('status');
			$lead->status_name=	 Status::find($request->input('status'))->name;
			if($lead->save()){
				$leadFollowUp = new LeadFollowUp;
				$status = Status::findorFail($request->input('status'));
				if(!strcasecmp($status->name,'npup')){
					$npupCount = LeadFollowUp::where('lead_id',$id)->where('status',$status->id)->count();
					if($npupCount>=9){
						$status = Status::where('name','LIKE','Not Interested')->first();
						$leadFollowUp->status = $status->id;
					}else{
						$leadFollowUp->status = $request->input('status');
					}
				}else{
					$leadFollowUp->status = $request->input('status');
				}
				//$statusObj = Status::find($request->input('status'));
				$leadFollowUp->remark = $request->input('remark');
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
						$msg = preg_replace('/{{name}}/i',$request->input('name'),$msg);
						$msg = preg_replace('/{{course}}/i',($request->has('course'))?Course::find($request->input('course'))->name:"",$msg);
						$msg = preg_replace('/\\r/i','%0D',$msg);
						$msg = preg_replace('/\\n/i','%0A',$msg);
						sendSMS($lead->mobile,$msg);						
						//sendSMS($request->input('mobile'),$msg);						
					}
					
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
					
					return response()->json(['status'=>1],200);
				}
			}
		}		
	}
	
	 
	
		public function getnotification(Request $request){
    
	    $data=array();

		$query = DB::table('croma_lead_follow_ups as lead_follow_ups')
		->select('lead_follow_ups.*','leads.name','leads.mobile','course_name')
		->join('croma_leads as leads','lead_follow_ups.lead_id','=','leads.id')   
		->where('leads.created_by','=',Auth::user()->id)
		->where('lead_follow_ups.follow_status','0')
		->whereDate('lead_follow_ups.expected_date_time','=',date('Y-m-d', strtotime("now")))	
		->whereNull('leads.deleted_at')
		->limit(20)
		->orderBy('lead_follow_ups.id','desc')
		->get();
 
 
		foreach ($query as $follow){
		$timestamp = date("Y-m-d H:i",strtotime("now"));
		$followdate = date('Y-m-d H:i',strtotime($follow->expected_date_time));

		if($timestamp === $followdate ){
	  	
	  	$follows = array(
	  	    
	  	    'Name'=>$follow->name,
	  	    'Mobile'=>$follow->mobile,
	  	    'Course'=>$follow->course_name,
	  	    'Lead_id'=>$follow->lead_id
	  	    );
	  
	  
	  	return response()->json([
				"statusCode"=>1,
				"success"=>[
					"responseCode"=>200,
					"follow_data"=>$follows,
					"message"=>""
				]
			],200);	
		}
		 
		}
		
		
   
		
	}
	
	
		public function getnotificationdemo(){
      
	   
	
		$querydemo = DB::table('croma_demo_follow_ups as demo_follow_ups')
		->select('demo_follow_ups.*','demos.name','demos.mobile','demos.course_name')
		->join('croma_demos as demos','demo_follow_ups.demo_id','=','demos.id')   
		->where('demos.created_by','=',Auth::user()->id)
		->where('demo_follow_ups.follow_status','0')
		->whereDate('demo_follow_ups.expected_date_time','=',date('Y-m-d', strtotime("now")))	
		->whereNull('demos.deleted_at')
		->limit(20)
		->orderBy('demo_follow_ups.id','desc')
		->get();
	 
		foreach ($querydemo as $follows){
		$timestamps = date("Y-m-d H:i",strtotime("now"));
		$followdates = date('Y-m-d H:i',strtotime($follows->expected_date_time));

		if($timestamps === $followdates ){
	  	
	  	$followdemo = array(  
	  	    
	  	    'Name'=>$follows->name,
	  	    'Mobile'=>$follows->mobile,
	  	    'Course'=>$follows->course_name,
	  	    'Demo_id'=>$follows->demo_id
	  	    ); 
	  
	   
	  	return response()->json([
				"statusCode"=>1,
				"success"=>[
					"responseCode"=>200,
					"follow_data"=>$followdemo,
					"message"=>""
				]
			],200);	
		}
		 
		}
		
	}
	
	
	
	
	
}
