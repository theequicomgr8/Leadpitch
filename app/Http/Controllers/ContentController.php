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
use App\Content;
use Auth;
use Session;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
		return view('cm_content.all_content');
    } 
	 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {			 
        return view('cm_content.add_content');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
	
	$this->validate($request, [
				'introduction'=>'required',			 
				'course'=>'required',
				 
			]);
		$content =  new Content;			
		$content->course = trim($request->input('course'));
		$content->introduction = $request->input('introduction'); 
		$content->modulesdescription = $request->input('modulesdescription'); 
		$content->duration = $request->input('duration');
		$content->certification = $request->input('certification');
		$content->liveproject = $request->input('liveproject');
		$content->trainingmode =$request->input('trainingmode');
		$content->demotimig = $request->input('demotimig');
		$content->abouttrainer = $request->input('abouttrainer');		 
		$content->placementtieup = $request->input('placementtieup');
		$content->placementratio = $request->input('placementratio');
  
		if($content->save()){ 
			$request->session()->flash('alert-success', 'Content successful!');
				return redirect(url('/content/all-content'));
			}else{
				$request->session()->flash('alert-danger', 'Content not insert?');
				return redirect(url('/content/add-content'));
			}
		
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $data['view_data'] = Content::find($id); 	
        return view('cm_content.content_view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    { 
		$data['edit_data'] = Content::find($id);		 
        return view('cm_content.content_update',$data);
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
		 
		 $this->validate($request, [
				'introduction'=>'required',			 
				'course'=>'required',
				 
			]);
		
		$content = Content::find($id);
		$content->course = trim($request->input('course'));
		$content->introduction = $request->input('introduction');	 
		$content->modulesdescription = $request->input('modulesdescription');	 
		$content->duration = $request->input('duration');
		$content->certification = $request->input('certification');
		$content->liveproject = $request->input('liveproject');
		$content->trainingmode =$request->input('trainingmode');
		$content->demotimig = $request->input('demotimig');
		$content->abouttrainer = $request->input('abouttrainer');		 
		$content->placementtieup = $request->input('placementtieup');
		$content->placementratio = $request->input('placementratio');
		
		if($content->save()){
			$request->session()->flash('alert-success', 'Content successfully updated !!');
			return redirect(url('/content/all-content'));
		}else{
			$request->session()->flash('alert-danger', 'Content not updated !!');
			return redirect(url('/content/update/'.$id));			
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
	  $content = Content::findorFail($id); 
		if($content->delete()){					 
		 $request->session()->flash('alert-success', 'Content successfully Deleted !!');
			return redirect(url('/content/all-content'));
		}else{
			$request->session()->flash('alert-danger', 'Content not successfully Deleted !!');
			return redirect(url('/content/all-content'));
				}
			 
		 
    }
	
    /**
     * Get paginated leads.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedContent(Request $request)
    {  
		if($request->ajax()){		 
			
			$content = new Content;		 
			$content = $content->orderBy('id','desc');			 
				 
			if($request->input('search.value')!==''){
				$content = $content->where(function($query) use($request){
					$query->orWhere('course','LIKE','%'.$request->input('search.value').'%');
					     
				});
			}
			 
			 
			 
			$content = $content->paginate($request->input('length'));
		 
			 
			 
			$returnLeads = [];
			$data = [];
			$returnLeads['draw'] = $request->input('draw');
			$returnLeads['recordsTotal'] = $content->total();
			$returnLeads['recordsFiltered'] = $content->total();
			$returnLeads['recordCollection'] = [];
 //echo "<pre>";print_r($content);die;
			foreach($content as $contents){
				 
					 	$action = '';
					 if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') ){
						 $action .=' | <a href="/content/update/'.$contents->id.'"><i class="fa fa-refresh" aria-hidden="true"></i></a> | <a href="/content/delete/'.$contents->id.'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
					 }
					$data[] = [						 
						$contents->course,
						$contents->introduction,				 
						$contents->modulesdescription,
						$contents->abouttrainer,
						'<a href="/content/view/'.$contents->id.'" target="_blank" ><i class="fa fa-eye" aria-hidden="true"></i></a>  '.$action
					];
					$returnLeads['recordCollection'][] = $contents->id;
				 
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
    public function getContentExcel(Request $request)
    {  	 
			$content = new Content;		 
			$content = $content->orderBy('id','desc'); 
 
			$content = $content->get();
			  
			$returnLeads = [];
			$data = [];
			 
			$returnLeads['recordCollection'] = [];
			foreach($content as $contents){
				 
					$arr[] = [
						"Course"=>$contents->course,
						"introduction"=>$contents->introduction,				 
						"modulesdescription"=>$contents->modulesdescription,		 
						"duration"=>$contents->duration,
						"certification"=>$contents->certification,
						"liveproject"=>$contents->liveproject,
						"trainingmode"=>$contents->trainingmode,
						"demotimig"=>$contents->demotimig,
						"abouttrainer"=>$contents->abouttrainer,					 
						"placementtieup"=>$contents->placementtieup,
						"placementratio"=>$contents->placementratio,						 
					];
					$returnLeads['recordCollection'][] = $contents->id;
				 
			}
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create($contents->course.'_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		 
    }
	
   	 
	
	
}
