<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Course;
use Auth;
use DB;
use Validator;
use App\Lead;
use App\Demo;
use Excel;
use Carbon\Carbon;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		$courses = Course::all();
		$users = [];
		if(Auth::user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$users = DB::table('users')->select('users.id','users.name')->get();
		}else{
			$users = DB::table('users')->select('users.id','users.name')->where('users.id',$request->user()->id)->get();
		}
		return view('cm_transfer.transfer',['courses'=>$courses,'users'=>$users]);
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
			'course'=>'required',
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
			case 'leads_in_dr':
				$transfer='leads';
				$datewise = true;
			break;
			case 'all_demos':
				$transfer='demos';
			break;
			case 'demos_in_dr':
				$transfer='demos';
				$datewise = true;
			break;
		}
		if(isset($datewise) && !!$datewise){
			$validator = Validator::make($request->all(),[
				'transfer'=>'required',
				'course'=>'required',
				'transfer_from'=>'required',
				'transfer_to'=>'required',
				'leaddf'=>'required',
				'leaddt'=>'required',
			],[
				'leaddf.required'=>'The from date field is required.',
				'leaddt.required'=>'The to date field is required.',
			]);
			if($validator->fails()){
				return redirect('permanent-transfer')
							->withErrors($validator)
							->withInput();
			}			
		}
		if(isset($transfer) && $transfer=='leads'){
			$leads = DB::table('leads');
			$leads = $leads->where('course',$request->course);
			$leads = $leads->where('created_by',$request->transfer_from);
			if(isset($datewise) && !!$datewise){
				$leads = $leads->whereDate('leads.created_at','>=',date_format(@date_create($request->input('leaddf')),'Y-m-d'));
				$leads = $leads->whereDate('leads.created_at','<=',date_format(@date_create($request->input('leaddt')),'Y-m-d'));
			}
			$leads = $leads->get();
			if(count($leads)){
				$users = DB::table('users')->select('id','name')->get();
				$usr=[];
				foreach($users as $user){
					$usr[$user->id]=$user->name;
				}
				$leadsUpdate = DB::table('leads');
				$leadsUpdate = $leadsUpdate->where('course', $request->course);
				$leadsUpdate = $leadsUpdate->where('created_by', $request->transfer_from);
				if(isset($datewise) && !!$datewise){
					$leadsUpdate = $leadsUpdate->whereDate('leads.created_at','>=',date_format(date_create($request->input('leaddf')),'Y-m-d'));
					$leadsUpdate = $leadsUpdate->whereDate('leads.created_at','<=',date_format(date_create($request->input('leaddt')),'Y-m-d'));
				}				
				$leadsUpdate = $leadsUpdate->update(['created_by' => $request->transfer_to]);
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
				$excel = \App::make('excel');
				Excel::create('leads_transfered_'.date('d_m_Y'), function($excel) use($arr) {
					$excel->sheet('Sheet 1', function($sheet) use($arr) {
						$sheet->fromArray($arr);
					});
				})->export('xls');
			}else{
				return redirect('permanent-transfer')
							->withErrors(['No lead(s) found for transfer'])
							->withInput();				
			}
		}
		else if(isset($transfer) && $transfer=='demos'){
			$leads = DB::table('demos');
			$leads = $leads->where('course',$request->course);
			$leads = $leads->where('created_by',$request->transfer_from);
			if(isset($datewise) && !!$datewise){
				$leads = $leads->whereDate('demos.created_at','>=',date_format(@date_create($request->input('leaddf')),'Y-m-d'));
				$leads = $leads->whereDate('demos.created_at','<=',date_format(@date_create($request->input('leaddt')),'Y-m-d'));
			}
			$leads = $leads->get();
			if(count($leads)){
				$users = DB::table('users')->select('id','name')->get();
				$usr=[];
				foreach($users as $user){
					$usr[$user->id]=$user->name;
				}
				/* DB::table('demos')
					->where('course', $request->course)
					->where('created_by', $request->transfer_from)
					->update(['created_by' => $request->transfer_to,'owner' => $request->transfer_to]); */
				$leadsUpdate = DB::table('demos');
				$leadsUpdate = $leadsUpdate->where('course', $request->course);
				$leadsUpdate = $leadsUpdate->where('created_by', $request->transfer_from);
				if(isset($datewise) && !!$datewise){
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
				$excel = \App::make('excel');
				Excel::create('demos_transfered_'.date('d_m_Y'), function($excel) use($arr) {
					$excel->sheet('Sheet 1', function($sheet) use($arr) {
						$sheet->fromArray($arr);
					});
				})->export('xls');
			}else{
				return redirect('permanent-transfer')
							->withErrors(['No demo(s) found for transfer'])
							->withInput();
			}			
		}
    }
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
