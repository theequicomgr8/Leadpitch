<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\User;
use App\Lead;
use App\LeadFollowUp;
use App\Assigncourse;
use DB;
class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
		 
		$assigncourse = DB::table('croma_assigncourse')->get();		 
			$userCourses=array();
			if(!empty($assigncourse)){
				foreach($assigncourse as $assignc){
					 $userCourses = 	array_merge($userCourses,unserialize($assignc->assigncourse));
					
			$leads = DB::table('croma_leads as leads');			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_courses as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*, m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";		
			$rawQuery .= " AND m1.status=4";			   
			$calldf =date('Y-m-d');
			$startDate = time();
	       // $calldf = date('Y-m-d', strtotime('-1 day', $startDate));
	        //$calldt = date('Y-m-d', strtotime('-1 day', $startDate));
			$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($calldf))."'";
			$leads = $leads->orderBy('follow_up_date','ASC');			
			$calldt =date('Y-m-d');
			$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($calldt))."'";			 
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');	 
			
		 		 
			$leads = $leads->whereIn('leads.course',$userCourses);	
			$leads = $leads->where('leads.created_by','!=',$assignc->counsellors);	
			$leads = $leads->get();	 
		// echo "<pre>";print_r($leads);		 
				if(!empty($leads)){			 
				foreach($leads as $lead){		 

				$data=array('name'=>$lead->name,"email"=>$lead->email,"mobile"=>$lead->mobile,"source"=>17,"source_name"=>"Linkedin","course"=>$lead->course,"course_name"=>$lead->course_name,"sub_courses"=>$lead->sub_courses,"status"=>1,"status_name"=>"New Lead","created_by"=>$assignc->counsellors,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'));

				//echo "<pre>";print_r($data);
				$last_id= DB::table('croma_leads')->insertGetId($data);	 
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
}
