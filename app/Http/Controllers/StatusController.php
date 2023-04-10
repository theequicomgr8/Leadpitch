<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
//models
use App\Status;
use App\Lead;
use Session;
class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('view_status')){
			return view('cm_status.status');
		}else{
			return "Unh Cheatin`";
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
        if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('add_status'))){
			$validator = Validator::make($request->all(),[
				'name'=>'required|unique:'.Session::get('company_id').'_status',
			]);
			
			if($validator->fails()){
				$errorsBag = $validator->getMessageBag()->toArray();
				$errors = [];
				foreach($errorsBag as $error){
					$errors[] = implode("<br/>",$error);
				}
				$errors = implode("<br/>",$errors);
				return response()->json(['status'=>0,'errors'=>$errors],200);
			}
			
			$status = new Status;
			$status->name = ucwords($request->input('name'));
			$status->show_exp_date = (($request->input('show_exp_date'))?1:0);
			$status->lead_filter = (($request->input('lead_filter'))?1:0);
			$status->lead_follow_up = (($request->input('lead_follow_up'))?1:0);
			$status->add_demo = (($request->input('add_demo'))?1:0);
			$status->demo_filter = (($request->input('demo_filter'))?1:0);
			$status->demo_follow_up = (($request->input('demo_follow_up'))?1:0);
			$status->abgyan_follow_up = (($request->input('abgyan_follow_up'))?1:0);
			 
			if($status->save()){
				return response()->json(['status'=>1],200);
			}else{
				return response()->json(['status'=>0,'errors'=>'Status not added'],400);
			}
		}else{
			return "Unh Cheatin`";
		}
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
    public function edit(Request $request, $id)
    {
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('edit_status')){
			$status = Status::find($id);
			return view('cm_status.status_update',['status'=>$status]);			
		}else{
			return "Unh Cheatin`";
		}
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
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('edit_status')){
			$validator = Validator::make($request->all(),[
				'name'=>'required|unique:'.Session::get('company_id').'_status,name,'.$id,
			]);
			if($validator->fails()){
				return redirect('status/update/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$status = Status::find($id);
			$status->name = ucwords($request->input('name'));
			$status->show_exp_date = (($request->input('show_exp_date'))?1:0);
			$status->lead_filter = (($request->input('lead_filter'))?1:0);
			$status->lead_follow_up = (($request->input('lead_follow_up'))?1:0);
			$status->add_demo = (($request->input('add_demo'))?1:0);
			$status->demo_filter = (($request->input('demo_filter'))?1:0);
			$status->demo_follow_up = (($request->input('demo_follow_up'))?1:0);
			$status->abgyan_follow_up = (($request->input('abgyan_follow_up'))?1:0);
			if($status->save()){
				DB::table(Session::get('company_id').'_leads')->where('status',$status->id)->update(array('status_name'=>$status->name));
				$request->session()->flash('alert-success', 'Status successful updated !!');
				return redirect(url('/status'));
			}else{
				$request->session()->flash('alert-danger', 'Status not updated !!');
				return redirect(url('/status/update/'.$id));
			}
		}else{
			return "Unh Cheatin`";
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
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('delete_status'))){
			try{
				$status = Status::findorFail($id);
				if($status->delete()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Status not found'],200);
			}
		}else{
			return "Unh Cheatin`";
		}
    }
	
    /**
     * Get paginated status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedStatus(Request $request)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('view_status'))){
			$status = Status::orderBy('id','desc')->paginate($request->input('length'));
			$returnStatus = [];
			$data = [];
			$returnStatus['draw'] = $request->input('draw');
			$returnStatus['recordsTotal'] = $status->total();
			$returnStatus['recordsFiltered'] = $status->total();
			foreach($status as $source){
				$data[] = [
					$source->name,
					(($source->show_exp_date)?"<strong><i aria-hidden='true' class='fa fa-check'></i></strong>":"<strong><i aria-hidden='true' class='fa fa-times'></i></strong>"),
					(($source->lead_filter)?"<strong><i aria-hidden='true' class='fa fa-check'></i></strong>":"<strong><i aria-hidden='true' class='fa fa-times'></i></strong>"),
					(($source->lead_follow_up)?"<strong><i aria-hidden='true' class='fa fa-check'></i></strong>":"<strong><i aria-hidden='true' class='fa fa-times'></i></strong>"),
					(($source->add_demo)?"<strong><i aria-hidden='true' class='fa fa-check'></i></strong>":"<strong><i aria-hidden='true' class='fa fa-times'></i></strong>"),
					(($source->demo_filter)?"<strong><i aria-hidden='true' class='fa fa-check'></i></strong>":"<strong><i aria-hidden='true' class='fa fa-times'></i></strong>"),
					(($source->demo_follow_up)?"<strong><i aria-hidden='true' class='fa fa-check'></i></strong>":"<strong><i aria-hidden='true' class='fa fa-times'></i></strong>"),
					(($source->abgyan_follow_up)?"<strong><i aria-hidden='true' class='fa fa-check'></i></strong>":"<strong><i aria-hidden='true' class='fa fa-times'></i></strong>"),
					'<a href="/status/update/'.$source->id.'" title="Update"><i class="fa fa-refresh" aria-hidden="true"></i></a>'." | ".'<a href="javascript:statusController.delete('.$source->id.')" title="Update"><i class="fa fa-trash" aria-hidden="true"></i></a>'
				];
			}
			$returnStatus['data'] = $data;
			return response()->json($returnStatus);
		}else{
			return "Unh Cheatin`";
		}
    }
}
