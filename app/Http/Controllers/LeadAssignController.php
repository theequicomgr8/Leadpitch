<?php
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;  
use Auth;
use Hash;
use Validator;
use DB;
use Session;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Input;
use Image;
use App\Inquiry; 
use App\User; 
use App\Source; 
use App\Demo;
use App\Course; 
use App\Status; 
use App\Courseassignment; 
 
class LeadAssignController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
         $this->middleware('auth');
	   
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	 
 
	$leadSource = Source::where('status',1)->orderby('name','asc')->get();
	$leadUser = User::orderby('name','asc')->get();
	$catCourse = Course::orderby('name','asc')->get();
	$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
        return view('leadassign.index',['search'=>$search,'leadUser'=>$leadUser,'leadSource'=>$leadSource,'catCourse'=>$catCourse]);
    } 
	
   
	 	
  
	// GET  Course pagination
	public function getLeadPagination(Request $request)
	{
		    date_default_timezone_set('Asia/Kolkata');
		    
		if($request->ajax()){			 
		$inquires = Inquiry::orderBy('created_at','DESC');		 
		if($request->input('search.value')!==''){
				$inquires = $inquires->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')					     		   
                                ->orWhere('form','LIKE','%'.$request->input('search.value').'%')
                                ->orWhere('name','LIKE','%'.$request->input('search.value').'%')
                                ->orWhere('course','LIKE','%'.$request->input('search.value').'%')
                                ->orWhere('source','LIKE','%'.$request->input('search.value').'%')
                                ->orWhere('mobile','LIKE','%'.$request->input('search.value').'%')
                                ->orWhere('sub_category','LIKE','%'.$request->input('search.value').'%')
                                ->orWhere('from_name','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
				if($request->input('search.leaddf')!=''){
				$inquires = $inquires->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
			}
			if($request->input('search.leaddt')!=''){
				$inquires = $inquires->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
			}
			
			if($request->input('search.user')!=''){
				$inquires = $inquires->where('assigned_to',$request->input('search.user'));
			}
			
			if($request->input('search.courses')!=''){
				$inquires = $inquires->where('course_id',$request->input('search.courses'));
			}
			
			//$inquires=$inquires->where('assigned_to','!=','0');
			// for duplicate value
	    	$inquires = $inquires->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"));
			$inquires = $inquires->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $inquires->total();
			$returnLeads['recordsFiltered'] = $inquires->total();
			$returnLeads['recordCollection'] = [];
          
			foreach($inquires as $inquiry){
			    $checkid = '<input type="checkbox" class="check-box-lead" value="'.$inquiry->id.'">';
			    if($inquiry->duplicate==2){
				 $mobilecode = '<span style="color:#A52A2A" >+'.$inquiry->code.'-<span contenteditable>'.$inquiry->mobile.'</span></span>';
			    }else{
			        $mobilecode ='+'.$inquiry->code.'-<span contenteditable>'.$inquiry->mobile.'</span>';
			        
			    }
				 if($inquiry->from_name){
				     $from_name= '('.$inquiry->from_name.')';
				 }else{
				     $from_name="";
				 }
				 
				 	if($inquiry->from_name){
				     $sub_category = '<span title="'.$inquiry->source.''.$from_name.'" >'.substr($inquiry->source,0,20).''.$from_name.'</span>';
				     
				 }else{
				      $sub_category ='<span title="'.$inquiry->sub_category.'" >'.substr($inquiry->sub_category,0,20).'</span>';
				     
				 }
				  if($inquiry->course){
				     $coursename = '<span title="'.$inquiry->course.'" style="    cursor: pointer;">'.substr($inquiry->course,0,25).'</span>';
				     
				     $shortcourse= explode(' ',trim($inquiry->course));
				     
				 }else{
				      $shortcourse ="";
				     
				 }
				 
				//for source not show 
			    if(Auth::user()->current_user_can('user')  ||  Auth::user()->current_user_can('manager')){
    			     $sub_category="";  
    			}
    			
    			
    			
								
                $modal ='
                <a data-toggle="modal" data-target="#selectcour_'.$inquiry->id.'" title="'.$inquiry->course.'">'.$shortcourse[0].''.$shortcourse[1].'</a>
                <div class="modal fade" id="selectcour_'.$inquiry->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Course: '.$inquiry->course.'<br>Page:'.$inquiry->form.'</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -46px;">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
                
                
                </div>
                </div>
                </div>';
			
			if(!empty($inquiry->assigned_to)){
					$username =  User::where('id',$inquiry->assigned_to)->first();
					if(!empty($username) && count($username)){
					$arrname = explode(' ',trim($username->name));
					}else{
					    
					    $arrname="";
					}
				 }else{
					$arrname=""; 
				 }
				 
        $laststatus = DB::table('croma_leads as leads')
        ->join('croma_lead_follow_ups as lead_follow_ups','leads.id','=','lead_follow_ups.lead_id')
        ->join('croma_status as status','status.id','=','lead_follow_ups.status')			
        ->orderBy('lead_follow_ups.created_at','desc')
        ->select('leads.mobile','lead_follow_ups.created_at as follow_create','status.name as statusname')
        ->where('leads.croma_id','=',$inquiry->id)->first();        
        if($laststatus){
        if($laststatus->statusname=='New Lead'){
        $statustime ='<span style="color:red">('.$laststatus->statusname.'| '.date('d-m | H:i',strtotime($laststatus->follow_create)).')</span> ';
        }else{
        $statustime ='('.$laststatus->statusname.'| '.date('d-m | H:i',strtotime($laststatus->follow_create)).')';    
        
        }
        
        }else{
        $statustime="";
        }  

					$data[] = [	
					    $checkid,
						date_format(date_create($inquiry->created_at),'d-M-y | H:i'),
						$inquiry->name,				 			
						$mobilecode,
						$modal,				 			
						$sub_category,		
						$arrname[0].' '.$statustime,
					];
					$returnLeads['recordCollection'][] = $inquiry->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	public function selectTodeleteLeads(Request $request){
		$ids = $request->input('ids');	
		   
		if(!empty($ids)){
		foreach($ids as $id){	
			$leads = DB::table('croma_enquiries')->where('id',$id)->delete();	
			 	
		}
		return response()->json([
			'statusCode'=>1,
			'data'=>[
				'responseCode'=>200,
				'payload'=>'',
				'message'=>'Successfully Deleted'
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
	 
 public function getleadcount(Request $request){
    
	    $data=array();
        $lastdate =date('Y-m-d');
		 $leacount = Inquiry::whereDate('created_at','=',date_format(date_create($lastdate),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
	  	    $demo = Demo::whereDate('created_at','=',date_format(date_create($lastdate),'Y-m-d'))->where('source',11)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
						   
	  	$follows = array(	  	    
	  	    'leacount'=>$leacount + $demo,	  	    
	  	    );
	  
	  
	  	return response()->json([
				"statusCode"=>1,
				"success"=>[
					"responseCode"=>200,
					"count_data"=>$leacount,
					"message"=>""
				]
			],200);	
		 
		 
		 
		
		
   
		
	}
 
	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
     public function leadanalysis(Request $request)
    {	 
	//echo "test";die;
			$current =  date('d-m-Y');						
			$oneday=  date('d-m-Y', strtotime($current));	
			$onelead =Inquiry::whereDate('created_at','=',date_format(date_create($oneday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisitonelead= Demo::whereDate('created_at','=',date_format(date_create($oneday),'Y-m-d'))->where('source',11)->get()->count();
			
			
			$firstday=  date('d-m-Y', strtotime($current. ' - 1 day'));	
			$firstlead =Inquiry::whereDate('created_at','=',date_format(date_create($firstday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisitfirstlead= Demo::whereDate('created_at','=',date_format(date_create($firstday),'Y-m-d'))->where('source',11)->get()->count();
			
			
			//echo $firstday; die;
			$secongday=  date('d-m-Y', strtotime($current. ' - 2 day'));	
			$secondlead =Inquiry::whereDate('created_at','=',date_format(date_create($secongday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisitsecondlead= Demo::whereDate('created_at','=',date_format(date_create($secongday),'Y-m-d'))->where('source',11)->get()->count();


			$thirdday=  date('d-m-Y', strtotime($current. ' - 3 day'));	
			$thirdlead = Inquiry::whereDate('created_at','=',date_format(date_create($thirdday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();							

			$directvisitthirdlead= Demo::whereDate('created_at','=',date_format(date_create($thirdday),'Y-m-d'))->where('source',11)->get()->count();

			$fourday=  date('d-m-Y', strtotime($current. ' - 4 day'));	
			$fourlead = Inquiry::whereDate('created_at','=',date_format(date_create($fourday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisitfourlead= Demo::whereDate('created_at','=',date_format(date_create($fourday),'Y-m-d'))->where('source',11)->get()->count();

			$fiveday=  date('d-m-Y', strtotime($current. ' - 5 day'));	
			$fivelead = Inquiry::whereDate('created_at','=',date_format(date_create($fiveday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisitfivelead= Demo::whereDate('created_at','=',date_format(date_create($fiveday),'Y-m-d'))->where('source',11)->get()->count();

			$sixday=  date('d-m-Y', strtotime($current. ' - 6 day'));	
			$sixlead = Inquiry::whereDate('created_at','=',date_format(date_create($sixday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	

			$directvisitsixlead= Demo::whereDate('created_at','=',date_format(date_create($sixday),'Y-m-d'))->where('source',11)->get()->count();


			$sevenday=  date('d-m-Y', strtotime($current. ' - 7 day'));	
			$sevenlead = Inquiry::whereDate('created_at','=',date_format(date_create($sevenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();

			$directvisitsevenlead= Demo::whereDate('created_at','=',date_format(date_create($sevenday),'Y-m-d'))->where('source',11)->get()->count();


			$eightday=  date('d-m-Y', strtotime($current. ' - 8 day'));	
			$eightlead = Inquiry::whereDate('created_at','=',date_format(date_create($eightday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();

			$directvisiteightlead= Demo::whereDate('created_at','=',date_format(date_create($eightday),'Y-m-d'))->where('source',11)->get()->count();


			$nineday=  date('d-m-Y', strtotime($current. ' - 9 day'));	
			$ninelead = Inquiry::whereDate('created_at','=',date_format(date_create($nineday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisitninelead= Demo::whereDate('created_at','=',date_format(date_create($nineday),'Y-m-d'))->where('source',11)->get()->count();
			
			
			$tenday=  date('d-m-Y', strtotime($current. ' - 10 day'));	
			$tenlead = Inquiry::whereDate('created_at','=',date_format(date_create($tenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisittenlead= Demo::whereDate('created_at','=',date_format(date_create($tenday),'Y-m-d'))->where('source',11)->get()->count();
			
			$elevenday=  date('d-m-Y', strtotime($current. ' - 11 day'));	
			$elevenlead = Inquiry::whereDate('created_at','=',date_format(date_create($elevenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisitelevenlead= Demo::whereDate('created_at','=',date_format(date_create($elevenday),'Y-m-d'))->where('source',11)->get()->count();
	
	
	        $twelveday=  date('d-m-Y', strtotime($current. ' - 12 day'));	
			$twelvelead = Inquiry::whereDate('created_at','=',date_format(date_create($twelveday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			$directvisittwelveleadlead= Demo::whereDate('created_at','=',date_format(date_create($twelveday),'Y-m-d'))->where('source',11)->get()->count();
				
				
			$thirteenday=  date('d-m-Y', strtotime($current. ' - 13 day'));	
			$thirteenlead = Inquiry::whereDate('created_at','=',date_format(date_create($thirteenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisitthirteenlead= Demo::whereDate('created_at','=',date_format(date_create($thirteenday),'Y-m-d'))->where('source',11)->get()->count();
			
			$fourteenday=  date('d-m-Y', strtotime($current. ' - 14 day'));	
			$fourteenlead = Inquiry::whereDate('created_at','=',date_format(date_create($fourteenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			$directvisitfourteenlead= Demo::whereDate('created_at','=',date_format(date_create($fourteenday),'Y-m-d'))->where('source',11)->get()->count();
			
			
			$fifteenday=  date('d-m-Y', strtotime($current. ' - 15 day'));	
			$fifteenlead = Inquiry::whereDate('created_at','=',date_format(date_create($fifteenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			$directvisitfifteenlead= Demo::whereDate('created_at','=',date_format(date_create($fifteenday),'Y-m-d'))->where('source',11)->get()->count();
			
			$sixteenday=  date('d-m-Y', strtotime($current. ' - 16 day'));	
			$sixteenlead = Inquiry::whereDate('created_at','=',date_format(date_create($sixteenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			$directvisitsixteenlead= Demo::whereDate('created_at','=',date_format(date_create($sixteenday),'Y-m-d'))->where('source',11)->get()->count();
			
			
			$seventeenday=  date('d-m-Y', strtotime($current. ' - 17 day'));	
			$seventeenlead = Inquiry::whereDate('created_at','=',date_format(date_create($seventeenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			$directvisitseventeenlead= Demo::whereDate('created_at','=',date_format(date_create($seventeenday),'Y-m-d'))->where('source',11)->get()->count();


		 
			$eighteenday=  date('d-m-Y', strtotime($current. ' - 18 day'));	
			$eighteenlead = Inquiry::whereDate('created_at','=',date_format(date_create($eighteenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	
			$directvisiteighteenlead= Demo::whereDate('created_at','=',date_format(date_create($eighteenday),'Y-m-d'))->where('source',11)->get()->count();
			
			
			
			$nineteenday=  date('d-m-Y', strtotime($current. ' - 19 day'));	
			$nineteenlead = Inquiry::whereDate('created_at','=',date_format(date_create($nineteenday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	
			
			$directvisitnineteenlead= Demo::whereDate('created_at','=',date_format(date_create($nineteenday),'Y-m-d'))->where('source',11)->get()->count();
			
			
			$twentyday=  date('d-m-Y', strtotime($current. ' - 20 day'));	
			$twentylead = Inquiry::whereDate('created_at','=',date_format(date_create($twentyday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	
			$directvisittwentylead= Demo::whereDate('created_at','=',date_format(date_create($twentyday),'Y-m-d'))->where('source',11)->get()->count();
			
	        $twentyoneday=  date('d-m-Y', strtotime($current. ' - 21 day'));	
			$twentyonelead = Inquiry::whereDate('created_at','=',date_format(date_create($twentyoneday),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	
			$directvisittwentyoneleadlead= Demo::whereDate('created_at','=',date_format(date_create($twentyoneday),'Y-m-d'))->where('source',11)->get()->count();
			
			 
			
	
	$dataPoints1 = array(
		//array("label"=> date('d-M',strtotime($oneday)), "y"=> ($onelead+$directvisitonelead)),
	array("label"=> date('d-M',strtotime($firstday)), "y"=> ($firstlead+$directvisitfirstlead)),
		array("label"=> date('d-M',strtotime($secongday)), "y"=> ($secondlead+$directvisitsecondlead)),
		array("label"=> date('d-M',strtotime($thirdday)), "y"=> ($thirdlead+$directvisitthirdlead)),
		array("label"=> date('d-M',strtotime($fourday)), "y"=> ($fourlead+$directvisitfourlead)),
		array("label"=> date('d-M',strtotime($fiveday)), "y"=> ($fivelead+$directvisitfivelead)),
		array("label"=> date('d-M',strtotime($sixday)), "y"=> ($sixlead+$directvisitsixlead)),
		array("label"=> date('d-M',strtotime($sevenday)), "y"=> ($sevenlead+$directvisitsevenlead)),
		array("label"=> date('d-M',strtotime($eightday)), "y"=> ($eightlead+$directvisiteightlead)),
		array("label"=> date('d-M',strtotime($nineday)), "y"=> ($ninelead+$directvisitninelead)),
		array("label"=> date('d-M',strtotime($tenday)), "y"=> ($tenlead+$directvisittenlead)),
		array("label"=> date('d-M',strtotime($elevenday)), "y"=> ($elevenlead+$directvisitelevenlead)),
		array("label"=> date('d-M',strtotime($twelveday)), "y"=> ($twelvelead+$directvisittwelveleadlead)),
		array("label"=> date('d-M',strtotime($thirteenday)), "y"=> ($thirteenlead+$directvisitthirteenlead)),
		array("label"=> date('d-M',strtotime($fourteenday)), "y"=> ($fourteenlead+$directvisitfourteenlead)),
		array("label"=> date('d-M',strtotime($sixteenday)), "y"=> ($sixteenlead+$directvisitfifteenlead)),
		array("label"=> date('d-M',strtotime($seventeenday)), "y"=> ($seventeenlead+$directvisitsixteenlead)),
		array("label"=> date('d-M',strtotime($eighteenday)), "y"=> ($eighteenlead+$directvisitseventeenlead)),
		array("label"=> date('d-M',strtotime($nineteenday)), "y"=> ($nineteenlead+$directvisitnineteenlead)),
		array("label"=> date('d-M',strtotime($twentyday)), "y"=> ($twentylead+$directvisittwentylead)),
			array("label"=> date('d-M',strtotime($twentyoneday)), "y"=> ($twentyonelead+$directvisittwentyoneleadlead)),

		);
		$dataPoints=json_encode($dataPoints1, JSON_NUMERIC_CHECK);

	$leadSource = Source::orderby('id','asc')->get();
	
	$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
        return view('leadassign.lead-analysis',['search'=>$search,'dataPoints'=>$dataPoints,'leadSource'=>$leadSource]);
    }
 
   
   
	 public function getPaginationSourcecount(Request $request)
	{
		   
		if($request->ajax()){			 
		$sources = Inquiry::orderBy('created_at','DESC');		 
		if($request->input('search.value')!==''){
				$sources = $sources->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')					     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
				if($request->input('search.leaddf')!=''){
 		
				$sources = $sources->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!=''){
				$sources = $sources->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				
			$sources = $sources->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"));
			$sources = $sources->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $sources->total();
			$returnLeads['recordsFiltered'] = $sources->total();
			$returnLeads['recordCollection'] = [];
 
 
			foreach($sources as $source){	
 
				$total_lead=0;
				$sourcelists = Source::whereIn('name',['WhatsApp', 'Ph Enquiry','Website','Chat'])->where('dailystatus',1)->get();		
			
				if($sourcelists){ 
				$array=[date('d-M-y',strtotime($source->created_at))];
					foreach($sourcelists as $key=>$val){  
					$countno =Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',$val->id)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();				 
			    	array_push($array,$countno);
					$total_lead +=$countno;
					
					}
				}  
				array_push($array,$total_lead);	  
			  	$directlead =Demo::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source',11)->where('deleted_by','0')->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	
				array_push($array,$directlead);
				$total_lead_t=0;
				$sourcelist = Source::whereNotIn('name',['WhatsApp', 'Ph Enquiry','Website','Chat','Direct Visit'])->where('dailystatus',1)->get();
				foreach($sourcelist as $keys=>$vals){  
					$counttotal =Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',$vals->id)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
					array_push($array,$counttotal);
					$total_lead_t +=$counttotal;
				}
				array_push($array,$total_lead_t+$directlead);	 
				array_push($array,($total_lead+$total_lead_t+$directlead));	 
				$data[] =  $array;
				$returnLeads['recordCollection'][] = $source->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
	
    public function getPaginationSourcecount_old(Request $request)
	{
		   
		if($request->ajax()){			 
		$sources = Inquiry::orderBy('created_at','DESC');		 
		if($request->input('search.value')!==''){
				$sources = $sources->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')					     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
				if($request->input('search.leaddf')!=''){
 		
				$sources = $sources->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!=''){
				$sources = $sources->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				
			$sources = $sources->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"));
			$sources = $sources->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $sources->total();
			$returnLeads['recordsFiltered'] = $sources->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($sources as $source){	

				//Website 7				 	
				$website =Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',7)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//WhatsApp 24 				 
				$whatsApp =Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',24)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//Ph. Enquiry 8				 
				$PhEnquiry =Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',8)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
								
				//Devendra Sir Phone  30					 
				$Devendraphone =Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',30)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			//Devendra Sir WhatsApp 31				 
				$DevendraWhatsApp = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',31)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();						
						
				//Saurabh Sir WhatsApp 29	
				 
				$saurabhwhatsApp = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',29)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				
					//Chat 34	
				 
				$chat = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',34)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//Direct Visit 11
				 
				 $directvisit = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',11)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				$directvisitLead = Demo::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source',11)->get()->count();
				//FaceBook 16		    	 	
		    	$faceBook = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',16)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	
			
				//Instagram 27
			   
				$instagram = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',27)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//Linkedin	17			  
				$linkedin = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',17)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				
				//JustDial 6				 
				$justDial = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',6)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//Sulekha 3				
				$sulekha = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',3)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
				//Yet5 2				
				$Yet5 = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',2)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				 
				//Google_Adword 19				
				$google_Adword = Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',19)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				 $total_lead = $website+$whatsApp+$PhEnquiry+$Devendraphone+$DevendraWhatsApp+$saurabhwhatsApp+$directvisit+$faceBook+$instagram+$linkedin+$justDial+$sulekha+$Yet5+$google_Adword+$chat+$directvisitLead;
				
					$data[] = [	
						date('d-M-y',strtotime($source->created_at)),											 		
						$website,	 			
						$whatsApp,	 			
						$PhEnquiry,
						$Devendraphone,	 			
						$DevendraWhatsApp,
						$saurabhwhatsApp,
						$chat,
						$directvisit+$directvisitLead,
						$faceBook,	 			
						$instagram,	
						$linkedin,	 	 					  
						$justDial,	 	 					  
						$sulekha,	 	 					  
						$Yet5,	 	 					  
						$google_Adword,	 	 					  
						$total_lead,	 	 					  
						 
					];
					$returnLeads['recordCollection'][] = $source->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
 	
 	
 	
	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function monthlyleadanalysis(Request $request)
    {	 
	   
	$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
        return view('leadassign.monthly-lead-analysis',['search'=>$search]);
    } 
		// GET  Course pagination
 
 public function getMonthlyPaginationLeadAnalysis(Request $request)
	{
		   
		if($request->ajax()){			 
		$sources = Inquiry::orderBy('created_at','DESC');		 
		if($request->input('search.value')!==''){
				$sources = $sources->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')					     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
				if($request->input('search.leaddf')!=''){
 		
				$sources = $sources->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!=''){
				$sources = $sources->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				
			$sources = $sources->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"));
			$sources = $sources->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $sources->total();
			$returnLeads['recordsFiltered'] = $sources->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($sources as $source){	
					$total_lead=0;
					$sourcelists = Source::whereIn('name',['WhatsApp', 'Ph Enquiry','Website','Chat'])->where('dailystatus',1)->get();		
				
						if($sourcelists){ 
						$array=[date('M-y',strtotime($source->created_at))];
						foreach($sourcelists as $key=>$val){   
						
				$countno =Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',$val->id)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();						
						array_push($array,$countno);
						$total_lead +=$countno;
						
						} }  
						  array_push($array,$total_lead);	  
					 
					  	$directlead =Demo::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source',11)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	
						array_push($array,$directlead);
					    
						$total_lead_t=0;
						$sourcelist = Source::whereNotIn('name',['WhatsApp', 'Ph Enquiry','Website','Chat','Direct Visit'])->where('dailystatus',1)->get();
					    
						foreach($sourcelist as $keys=>$vals){  
				 
						//$counttotal =Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',$vals->id)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	
						$counttotal =Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',$vals->id)->groupBy('mobile','course')->get()->count();
						array_push($array,$counttotal);
						$total_lead_t +=$counttotal;
						}
						array_push($array,$total_lead_t+$directlead); //$directlead add 9 march 2023	 
						array_push($array,($total_lead+$total_lead_t+$directlead));	 
						 
					$data[] =  $array;
					$returnLeads['recordCollection'][] = $source->id;				 
			}	
			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
 
	public function getMonthlyPaginationLeadAnalysis_oldd(Request $request)
	{
		   
		if($request->ajax()){			 
		$sources = Inquiry::orderBy('created_at','DESC');		 
		if($request->input('search.value')!==''){
				$sources = $sources->where(function($query) use($request){
					$query->orWhere('name','LIKE','%'.$request->input('search.value').'%')					     		   
						  ->orWhere('id','LIKE','%'.$request->input('search.value').'%');
				});
			}
				if($request->input('search.leaddf')!=''){
 		
				$sources = $sources->whereDate('created_at','>=',date_format(date_create($request->input('search.leaddf')),'Y-m-d'));
				}
				if($request->input('search.leaddt')!=''){
				$sources = $sources->whereDate('created_at','<=',date_format(date_create($request->input('search.leaddt')),'Y-m-d'));
				}
				
			$sources = $sources->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"));
			$sources = $sources->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $sources->total();
			$returnLeads['recordsFiltered'] = $sources->total();
			$returnLeads['recordCollection'] = [];
 
			foreach($sources as $source){	

				//Website 7				 	
				$website =Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',7)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//WhatsApp 24 				 
				$whatsApp =Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',24)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//Ph. Enquiry 8				 
				$PhEnquiry =Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',8)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
								
				//Devendra Sir Phone  30					 
				$Devendraphone =Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',30)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
			//Devendra Sir WhatsApp 31				 
				$DevendraWhatsApp = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',31)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();						
						
				//Saurabh Sir WhatsApp 29	
				 
				$saurabhwhatsApp = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',29)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				
					//Chat 34	
				 
				$chat = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',34)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//Direct Visit 11
				 
				 $directvisit = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',11)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				 
				$directvisitLead = Demo::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source',11)->get()->count();
				//FaceBook 16		    	 	
		    	$faceBook = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',16)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();	
			
				//Instagram 27
			   
				$instagram = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',27)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//Linkedin	17			  
				$linkedin = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',17)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				
				//JustDial 6				 
				$justDial = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',6)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				
				//Sulekha 3				
				$sulekha = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',3)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
			
				//Yet5 2				
				$Yet5 = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',2)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				 
				//Google_Adword 19				
				$google_Adword = Inquiry::whereMonth('created_at','=',date_format(date_create($source->created_at),'m'))->whereYear('created_at','=',date_format(date_create($source->created_at),'Y'))->where('source_id',19)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
				 $total_lead = $website+$whatsApp+$PhEnquiry+$Devendraphone+$DevendraWhatsApp+$saurabhwhatsApp+$directvisit+$faceBook+$instagram+$linkedin+$justDial+$sulekha+$Yet5+$google_Adword+$chat+$directvisitLead;
				
					$data[] = [	
						date('M-y',strtotime($source->created_at)),											 		
						$website,	 			
						$whatsApp,	 			
						$PhEnquiry,
						$Devendraphone,	 			
						$DevendraWhatsApp,
						$saurabhwhatsApp,
						$chat,
						$directvisit+$directvisitLead,
						$faceBook,	 			
						$instagram,	
						$linkedin,	 	 					  
						$justDial,	 	 					  
						$sulekha,	 	 					  
						$Yet5,	 	 					  
						$google_Adword,	 	 					  
						$total_lead,	 	 					  
						 
					];
					$returnLeads['recordCollection'][] = $source->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	
		     
	
		/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseassignment(Request $request)
    {	 
	//echo "test";die;
	$leadUser = User::orderby('name','asc')->get();
	$leadSource = Source::orderby('name','asc')->get();
	$leadStatus = Status::orderby('name','asc')->where('id','!=','13')->where('id','!=','14')->get();
	$courses = Course::orderBy('name','asc')->get();
	$search = [];
		if($request->has('search')){
			$search = $request->input('search');
		}
        return view('leadassign.course-assignment',['search'=>$search,'leadUser'=>$leadUser,'leadStatus'=>$leadStatus,'leadSource'=>$leadSource,'courses'=>$courses]);
    } 
	
	public function getCourseAssignmentPagination(Request $request)
	{
		    date_default_timezone_set('Asia/Kolkata');
		    
		if($request->ajax()){			 
		$inquires = Courseassignment::orderBy('id','DESC');
		
		if($request->input('search.value')!==''){
				$inquires = $inquires->where(function($query) use($request){
					$query->orWhere('counsellors','LIKE','%'.$request->input('search.value').'%')				     		   
                                ->orWhere('counsellors','LIKE','%'.$request->input('search.value').'%')                        
                                ->orWhere('counsellors','LIKE','%'.$request->input('search.value').'%');
				});
			}
			
			if($request->input('search.user')!==''){
				$inquires = $inquires->where('counsellors',$request->input('search.user'));
			}else{
			     	$inquires = $inquires->where('counsellors',$request->input('search.user')); 
			}
			
			
		if($request->input('search.course')!=''){
			$counsellorlist= [];
			$assigncourse = Courseassignment::get();	
			foreach($assigncourse as $counsellor){
			if(in_array($request->input('search.course'),unserialize($counsellor->assign_dom_course))){
			$counsellorlist[] = $counsellor->counsellors;

			}
			
			if(in_array($request->input('search.course'),unserialize($counsellor->assign_int_course))){
			$counsellorlist[] = $counsellor->counsellors;

			}
			}
			}
		
			if($request->input('search.course')!=''){				  
				$inquires = $inquires->whereIn('counsellors',$counsellorlist);
			 
				
			}
			
			
	 
			$inquires = $inquires->paginate($request->input('length'));
			
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $inquires->total();
			$returnLeads['recordsFiltered'] = $inquires->total();
			$returnLeads['recordCollection'] = [];
         // echo "<pre>";print_r($inquires);die;
		 $currentdate=date('Y-m-d');
		 
		$courses = Course::get();
			foreach($inquires as $course){
				$usersname= User::where('id',$course->counsellors)->first();
			 
				if(!empty($usersname)){

				$leadusersname = $usersname->name;
				}else{
					$leadusersname="";
				}
				$htmldom ="";
				if($course->assign_dom_course !== NULL){
				$assigncourse = unserialize($course->assign_dom_course);				
				foreach($courses as $coursev){
				if(in_array($coursev->id,$assigncourse)){
				$htmldom .= "<span class=\"label label-default\">".$coursev->name."</span> ";
				}
				}
				}
				
				$htmlint = "";
				
				if($course->assign_int_course !== NULL){
				$assignIntcourse = unserialize($course->assign_int_course);
				foreach($courses as $coursesv){
				if(in_array($coursesv->id,$assignIntcourse)){
				$htmlint .="<span class=\"label label-default\">".$coursesv->name."</span> ";
				}
				}
				}
				
				
					$data[] = [	
					   $leadusersname,
					   $htmldom,
					   $htmlint,
					];
					$returnLeads['recordCollection'][] = $course->id;				 
			}			
			$returnLeads['data'] = $data;
			return response()->json($returnLeads);			
			
		}  
		
	}
	    
	
	
 	
 
 
 
}
