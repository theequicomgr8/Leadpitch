<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
//models
use App\Source;
use App\Lead;
use Session;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('view_source')){
			return view('cm_sources.source');
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
        if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('add_source'))){
			$validator = Validator::make($request->all(),[
			'name'=>'required|unique:'.Session::get('company_id').'_sources',
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
			
			$source = new Source;
			$source->name = ucwords($request->input('name'));
	 
			if($source->save()){
				return response()->json(['status'=>1],200);
			}else{
				return response()->json(['status'=>0,'errors'=>'Source not added'],400);
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
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('edit_source')){
			$source = Source::find($id);
			return view('cm_sources.source_update',['source'=>$source]);	
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
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('edit_source')){
			$validator = Validator::make($request->all(),[
	        	'name'=>'required|unique:'.Session::get('company_id').'_sources,name,'.$id
			]);
			if($validator->fails()){
				return redirect('source/update/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$source = Source::find($id);
			$source->name = ucwords($request->input('name'));
			if($source->save()){
			 
				$request->session()->flash('alert-success', 'Source successful updated !!');
				return redirect(url('/source'));
			}else{
				$request->session()->flash('alert-danger', 'Source not updated !!');
				return redirect(url('/source/update/'.$id));			
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
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('delete_source'))){
			try{
				$source = Source::findorFail($id);
				if($source->delete()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Source not found'],200);
			}
		}else{
			return "Unh Cheatin`";
		}
    }

    /**
     * Get paginated sources.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedSources(Request $request)
    {
		if($request->ajax() && ($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('view_source'))){
			$sources = Source::orderBy('id','desc')->paginate($request->input('length'));
			$returnSources = [];
			$data = [];
			$returnSources['draw'] = $request->input('draw');
			$returnSources['recordsTotal'] = $sources->total();
			$returnSources['recordsFiltered'] = $sources->total();
			foreach($sources as $source){
			    
			    if((isset($source->social) && $source->social==1)){
					  $check ="checked";
				  }else{
					  
					  $check="";
				  }
					  
			    
			    $action  ='<a href="/source/update/'.$source->id.'"><i class="fa fa-refresh" aria-hidden="true"></i></a> || <a href="javascript:sourceController.delete('.$source->id.')"><i class="fa fa-trash" aria-hidden="true"></i></a>'; 
				
				if($source->status=='1'){
				$status ='<a href="javascript:sourceController.status('.$source->id.',0)" title="Source status" class="btn btn-success">Active</a>';	
				}else{
				$status ='<a href="javascript:sourceController.status('.$source->id.',1)" title="Source status" class="btn btn-danger">Inactive</a>';	
				}
				
				if($source->social=='1'){		 
				$social ='<input type="checkbox" name="social" class="socialactive" data-source_id="'.$source->id.'"  data-val="0"  '.$check.' >' ;	
				}else{				 
				$social ='<input type="checkbox" name="social" class="socialactive" data-source_id="'.$source->id.'"  data-val="1">' ;	
				}
				
			    if($source->dailystatus=='1'){		 
				$dailystatus ='<input type="checkbox" name="dailystatus" class="dailystatusactive" data-source_id="'.$source->id.'"  data-val="0"  checked >' ;	
				}else{				 
				$dailystatus ='<input type="checkbox" name="dailystatus" class="dailystatusactive" data-source_id="'.$source->id.'"  data-val="1">' ;	
				}
				$data[] = [
					$source->name,
					$status,
					$social,
					$dailystatus,
					$action,
				];
			}
			$returnSources['data'] = $data;
			return response()->json($returnSources);
		}else{
			return "Unh Cheatin`";
		}
    }
    
     /**
     * Remove the specified resource from storage status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(request $request, $id,$val)
    {
       	 if($request->ajax()){			 
		$source = Source::findOrFail($id);	 
		$source->status=$val;
		 //echo "<pre>";print_r($source);die;
		if($source->save()){
			$status=1;							 
			$msg="Source status updated successfully!";					
			}else{
			$status=0;							 
			$msg="Source status could not be updated!";	
			}		
			return response()->json(['status'=>$status,'msg'=>$msg],200); 
		 }
    }
	
	
	
	/**
     * Remove the specified resource from storage status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function social(request $request)
    {
		//echo "<pre>";print_r($_POST);die;
       	 if($request->ajax()){		

		$source_id= $request->input('source_id');		 
		$val= $request->input('val');		 
		$source = Source::findOrFail($source_id);	 
		$source->social=$val;
	 
		if($source->save()){
			$status=1;							 
			$msg="Source status updated successfully!";					
			}else{
			$status=0;							 
			$msg="Source status could not be updated!";	
			}		
			return response()->json(['status'=>$status,'msg'=>$msg],200); 
		 }
    }
    
    
     /**
     * Remove the specified resource from storage status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dailystatus(request $request)
    {
		//echo "<pre>";print_r($_POST);die;
       	 if($request->ajax()){		

		$source_id= $request->input('source_id');		 
		$val= $request->input('val');		 
		$source = Source::findOrFail($source_id);	 
		$source->dailystatus=$val;
		 //echo "<pre>";print_r($source);die;
		if($source->save()){
			$status=1;							 
			$msg="Source status updated successfully!";					
			}else{
			$status=0;							 
			$msg="Source status could not be updated!";	
			}		
			return response()->json(['status'=>$status,'msg'=>$msg],200); 
		 }
    }
	
	
    
}
