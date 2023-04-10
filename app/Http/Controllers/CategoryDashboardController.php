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
use App\Inquiry;
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
class CategoryDashboardController extends Controller
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
		$statuses = Status::where('lead_filter',1)->where('name','!=','Active')->where('name','!=','In Active')->get();
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
//	echo "<pre>";print_r($users);die;
        return view('cm_categorymangment.all-category-view',['sources'=>$sources,'courses'=>$courses,'statuses'=>$statuses,'search'=>$search,'users'=>$users]);
    }



    public function getPaginationCategoryView(Request $request)
	{
		   
		if($request->ajax()){	

		// $users = User::where('status',1)->orderBy('name','ASC');	
		$categories=DB::connection('mysql4')->table('croma_category')->where('status',1)->where('show_menu','1')->orderBy('category','ASC');	 
		if($request->input('search.value')!==''){
				$categories = $categories->where(function($query) use($request){
					$query->orWhere('category','LIKE','%'.$request->input('search.value').'%')					     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
			$categories = $categories->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $categories->total();
			$returnLeads['recordsFiltered'] = $categories->total();
			$returnLeads['recordCollection'] = [];
 			
			foreach($categories as $user){				 
				$name = '<a href="javascript:categorydashboardController.getCategoryWiseCountlead('.$user->id.')" title="View Course wise Lead"><i class="fa fa-list fa-fw" style="cursor: pointer;"></i></a>'.$user->category;
				 //$name=$user->category;
				$current =  date('d-m-Y');						
		    	$oneday=  date('d-m-Y', strtotime($current));	
				// $onelead =Inquiry::whereDate('created_at','=',date_format(date_create($oneday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();

				$getcourseid=DB::table('croma_cat_course')->select('id as courseId')->where('category',$user->id)->get();
				$courseary=[];
				foreach($getcourseid as $gcourse){
					$courseary[]=$gcourse->courseId;
				}
				
				
				$onelead =Inquiry::whereMonth('created_at','=','01')->whereYear('created_at','=',date('Y'))->whereIn('course_id',$courseary)->where('deleted','0')->get()->count();  //jan



					
				$firstmonth=  date('m', strtotime($current. ' - 1 month'));	
				$firstlead =Inquiry::whereMonth('created_at','=',$firstmonth)->whereIn('course_id',$courseary)->get()->count(); //dec



				$secondmonth=  date('m', strtotime($current. ' - 2 month'));	
				// $secondlead =Inquiry::whereDate('created_at','=',date_format(date_create($secongday),'Y-m-d'))->where('assigned_to',$user->id)->get()->count();
				$secondlead =Inquiry::whereMonth('created_at','=',$secondmonth)->whereIn('course_id',$courseary)->get()->count();  //nov


			
				$thirdmonth=  date('m', strtotime($current. ' - 3 month'));	
				$thirdlead =Inquiry::whereMonth('created_at','=',$thirdmonth)->whereIn('course_id',$courseary)->get()->count();  //oct



							
				$fourmonth=  date('m', strtotime($current. ' - 4 month'));	
				$fourlead =Inquiry::whereMonth('created_at','=',$fourmonth)->whereIn('course_id',$courseary)->get()->count();  //sep
				
				
				$fivemonth=  date('m', strtotime($current. ' - 5 month'));	
				$fivelead =Inquiry::whereMonth('created_at','=',$fivemonth)->whereIn('course_id',$courseary)->get()->count();  //aug


				
		    	$sixmonth=  date('m', strtotime($current. ' - 6 month'));	
		    	$sixlead =Inquiry::whereMonth('created_at','=',$sixmonth)->whereIn('course_id',$courseary)->get()->count();  //july

			
			    $sevenmonth=  date('m', strtotime($current. ' - 7 month'));	
				$sevenlead =Inquiry::whereMonth('created_at','=',$sevenmonth)->whereIn('course_id',$courseary)->get()->count();  //june

			
			    $eightmonth=  date('m', strtotime($current. ' - 8 month'));	
				$eightlead =Inquiry::whereMonth('created_at','=',$eightmonth)->whereIn('course_id',$courseary)->get()->count();  //may

				
				$ninemonth=  date('m', strtotime($current. ' - 9 month'));	
				$ninelead =Inquiry::whereMonth('created_at','=',$ninemonth)->whereIn('course_id',$courseary)->get()->count();  //apr

				$tenmonth=  date('m', strtotime($current. ' - 10 month'));	
				$tenlead =Inquiry::whereMonth('created_at','=',$tenmonth)->whereIn('course_id',$courseary)->get()->count();  //march

				$elevenmonth=  date('m', strtotime($current. ' - 11 month'));	
				$elevenlead =Inquiry::whereMonth('created_at','=',$elevenmonth)->whereIn('course_id',$courseary)->get()->count();  //feb

				$twelvemonth=  date('m', strtotime($current. ' - 12 month'));	
				$twelvelead =Inquiry::whereMonth('created_at','=',$twelvemonth)->whereIn('course_id',$courseary)->get()->count();
			 
				
					$data[] = [		 
						$name,	
						$onelead,	 
						$firstlead,	 			
						$secondlead,
						$thirdlead,	 			
						$fourlead,
						$fivelead,	 			
						$sixlead,
						$sevenlead,	 			
						$eightlead,	
						$ninelead,
						$tenlead,
						$elevenlead,
						$twelvelead	 			
					 			
						 		 			
						 				 			
						 					  
						 
					];
					$returnLeads['recordCollection'][] = $user->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}



	
   


   public function getCourseWiseCountlead(Request $request, $id)
	 {
	
		if($request->ajax()){
			
		$current =  date('d-m-Y');
		$html = '  
		<table id="datatable-course-lead" class="table table-striped table-bordered">
		<thead>
		<tr>					 

		<th style="width:500px;">Course</th>
		<th>'.date('M', strtotime($current)).'</th>
		<th>'.date('M', strtotime($current. ' - 1 month')).'</th>
		<th>'.date('M', strtotime($current. ' - 2 month')).'</th>
		<th>'.date('M', strtotime($current. ' - 3 month')).'</th>
		<th>'.date('M', strtotime($current. ' - 4 month')).'</th>
		<th>'.date('M', strtotime($current. ' - 5 month')).'</th>
		<th>'.date('M', strtotime($current. ' - 6 month')).'</th>
		<th>'.date('M', strtotime($current. ' - 7 month')).'</th>
		<th>'.date('M', strtotime($current. ' -8 month')).'</th>
		<th>'.date('M', strtotime($current. ' - 9 month')).'</th>
		<th>'.date('M', strtotime($current. ' - 10 month')).'</th>
		<th>'.date('M', strtotime($current. ' - 11 month')).'</th>
	 

		</tr>

		</thead>

		<tbody>'; 

		$html .='</tbody></table>';
		return response()->json(['status'=>1,'html'=>$html],200);
	 }
							
}   








public function getCourseLead(Request $request, $id)
{

	if($request->ajax()){
    
    // get category name
    $getCategoryName=DB::connection('mysql4')->table('croma_category')->where('id',$id)->first();
	$categoryName=$getCategoryName->category;
	
	//get total categoy lead of current month
	$getcourseid=DB::table('croma_cat_course')->select('id as courseId')->where('category',$id)->get();
	$courseary=[];
	foreach($getcourseid as $gcourse){
		$courseary[]=$gcourse->courseId;
	}
	$totalcurrentmonth =Inquiry::whereMonth('created_at','=',date('m'))->whereIn('course_id',$courseary)->get()->count();
	

	// $courselist = CromaLead::where('assigned_to',$id);
	$courses=Course::where('category',$id);

	$courses= $courses->groupby('name');		
	$courses = $courses->paginate($request->input('length'));
		$returnLeads = [];
		$data = [];
		$returnLeads['draw'] = $request->input('draw');
		$returnLeads['recordsTotal'] = $courses->total();
		$returnLeads['recordsFiltered'] = $courses->total();
		
		foreach($courses as $course){
			$current =  date('d-m-Y');	
			$dayone=  date('d-m-Y', strtotime($current));

			$firstmonth = Inquiry::whereMonth('created_at','=',date('m'))->where('course_id',$course->id)->get()->count();

			$second=  date('m', strtotime($current. ' - 1 month'));	
			$secondmonth =Inquiry::whereMonth('created_at','=',$second)->where('course_id',$course->id)->get()->count();

			$third=  date('m', strtotime($current. ' - 2 month'));	
			$thirdmonth =Inquiry::whereMonth('created_at','=',$third)->where('course_id',$course->id)->get()->count();

			$four=  date('m', strtotime($current. ' - 3 month'));	
			$fourmonth =Inquiry::whereMonth('created_at','=',$four)->where('course_id',$course->id)->get()->count();

			$five=  date('m', strtotime($current. ' - 4 month'));	
			$fivemonth =Inquiry::whereMonth('created_at','=',$five)->where('course_id',$course->id)->get()->count();

			$six=  date('m', strtotime($current. ' - 5 month'));	
			$sixmonth =Inquiry::whereMonth('created_at','=',$six)->where('course_id',$course->id)->get()->count();

			$seven=  date('m', strtotime($current. ' - 6 month'));	
			$sevenmonth =Inquiry::whereMonth('created_at','=',$seven)->where('course_id',$course->id)->get()->count();

			$eight=  date('m', strtotime($current. ' - 7 month'));	
			$eightmonth =Inquiry::whereMonth('created_at','=',$eight)->where('course_id',$course->id)->get()->count();

			$nine=  date('m', strtotime($current. ' - 8 month'));	
			$ninemonth =Inquiry::whereMonth('created_at','=',$nine)->where('course_id',$course->id)->get()->count();

			$ten=  date('m', strtotime($current. ' - 9 month'));	
			$tenmonth =Inquiry::whereMonth('created_at','=',$ten)->where('course_id',$course->id)->get()->count();

			$eleven=  date('m', strtotime($current. ' - 10 month'));	
			$elevenmonth =Inquiry::whereMonth('created_at','=',$eleven)->where('course_id',$course->id)->get()->count();

			$twelve=  date('m', strtotime($current. ' - 11 month'));	
			$twelvemonth =Inquiry::whereMonth('created_at','=',$twelve)->where('course_id',$course->id)->get()->count();

			$data[] = [
				$course->name,
				$firstmonth,
				$secondmonth,
				$thirdmonth,
				$fourmonth,
				$fivemonth,
				$sixmonth,
				$sevenmonth,
				$eightmonth,
				$ninemonth,
				$tenmonth,
				$elevenmonth,
				$twelvemonth
			];

		}



		



		$returnLeads['data'] = $data;
		$returnLeads['categoryname'] = $categoryName;
		$returnLeads['total_current_month'] = $totalcurrentmonth;
		return response()->json($returnLeads);
	 
	 
	 
	  

	}
							
}





	
	
	
	
	
	
}
