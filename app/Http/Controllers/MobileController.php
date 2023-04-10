<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use DB;
//models
use App\message;
use App\Course;
use App\Mobile;
 
use Session;
class MobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator'))
		{ 
	
			$mobile = Mobile::orderBy('id','desc')->get();
				 
			return view('cm_mobile.mobile',['mobile'=>$mobile]);
		}else{
			return "You have not permission";
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
	 
	if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
	
		if($request->isMethod('post') && $request->input('submit')=="Save")
		{ 	
			$name = $request->input('name');
			$mobile = $request->input('mobile');
		 
			 $this->validate($request, [
					'name'=>'required|unique:'.Session::get('company_id').'_mobile',
					'mobile'=>'required|unique:'.Session::get('company_id').'_mobile',

					]);   
			$add_data = array(
			'name'=>$name,
			'mobile'=>$mobile,
			'status'=>1,
			);
			  
			$add  = DB::table(Session::get('company_id').'_mobile')->insert($add_data);
			if($add){
				$request->session()->flash('alert-success', 'Mobile successfully added !');
				return redirect(url('/mobile'));
			}else{
				$request->session()->flash('alert-danger', 'Mobile not added !');
				return redirect(url('/mobile'));
			}
			
		}
			return redirect(url('/mobile'));
          }else{
			return "You have not permission";
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
		 if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
			$edit_mobile = DB::table(Session::get('company_id').'_mobile')->where('id',$id)->first();
			  
			return view('cm_mobile.mobile_update',compact('edit_mobile'));
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
		if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
	
		if($request->isMethod('post') && $request->input('submit')=="update")
		{ 
 
			$name =  ucwords($request->input('name'));
			$mobile =  $request->input('mobile');
			$this->validate($request, [
				'name'	=> 'required|max:32|unique:'.Session::get('company_id').'_mobile,name,'.$id.',id',		
				'mobile'=>'required|unique:'.Session::get('company_id').'_mobile,mobile,'.$id.',id',

			]); 
			$update_date = array(
			'name'=>$name,
			'mobile'=>$mobile
			); 
	 
		$update = DB::table(Session::get('company_id').'_mobile')->where('id',$id)->update($update_date);
		
		 
		$request->session()->flash('alert-success', 'Mobile successfully updated !');
		return redirect(url('/mobile'));
		} 
		$request->session()->flash('alert-danger', 'Mobile not updated!');		
		return redirect(url('/mobile/edit/'.$id));
		
		}else{
		return "not permisssion";
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
		 if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator') ){
			$delete = DB::table(Session::get('company_id').'_mobile')->where('id',$id)->delete();
			
				if($delete){
					$request->session()->flash('alert-success', 'Mobile successfully deleted !!');
					return redirect(url('/mobile'));
				}else{
					$request->session()->flash('alert-danger', 'Mobile not deleted!');
					return redirect('/mobile');
				}
		}else{
			return "Not permission";
		}
			 
		 
    }
	
    /**
     * Return the specified resource from storage.
     *
     * @param  obj  Request object
     * @param  int  $id
     * @return Json Response
     */
	public function getMessagesList(Request $request, $id){
		if($request->ajax()){
			$messages = Message::where('name','LIKE','%welcome%')->orWhere('course',$id)->select('id','name')->get();
			return response()->json($messages,200);
		} 
	}
}
