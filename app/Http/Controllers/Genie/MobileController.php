<?php

namespace App\Http\Controllers\Genie;

use Illuminate\Http\Request;
 use App\Http\Controllers\Controller;
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
		if(Session::get('client.id')){
	
			$mobile = DB::table('mobile')->orderBy('id','desc')->get();
				 
			return view('genie.cm_mobile.mobile',['mobile'=>$mobile]);
		}else{
			return redirect('/genie');
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
	 
	if(Session::get('client.id')){
	
		if($request->isMethod('post') && $request->input('submit')=="Save")
		{ 	
			$name = $request->input('name');
			$mobile = $request->input('mobile');
		 
			 $this->validate($request, [
					'name'=>'required|unique:mobile',
					'mobile'=>'required|unique:mobile',

					]);   
			$add_data = array(
			'name'=>$name,
			'mobile'=>$mobile,
			'status'=>1,
			);
			  
			$add  = DB::table('mobile')->insert($add_data);
			if($add){
				$request->session()->flash('alert-success', 'Mobile successfully added !');
				return redirect(url('genie/mobile'));
			}else{
				$request->session()->flash('alert-danger', 'Mobile not added !');
				return redirect(url('genie/mobile'));
			}
			
		}
			return redirect(url('genie/mobile'));
          }else{
			return redirect('/genie');
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
		if(Session::get('client.id')){
			$edit_mobile = DB::table('mobile')->where('id',$id)->first();
			  
			return view('genie.cm_mobile.mobile_update',compact('edit_mobile'));
          }else{
			return redirect('/genie');
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
		if(Session::get('client.id')){
	
		if($request->isMethod('post') && $request->input('submit')=="update")
		{ 
 
			$name =  ucwords($request->input('name'));
			$mobile =  $request->input('mobile');
			$this->validate($request, [
				'name'	=> 'required|max:32|unique:mobile,name,'.$id.',id',		
				'mobile'=>'required|unique:mobile,mobile,'.$id.',id',

			]); 
			$update_date = array(
			'name'=>$name,
			'mobile'=>$mobile
			); 
	 
		$update = DB::table('mobile')->where('id',$id)->update($update_date);
		
		 
		$request->session()->flash('alert-success', 'Mobile successfully updated !');
		return redirect(url('genie/mobile'));
		} 
		$request->session()->flash('alert-danger', 'Mobile not updated!');		
		return redirect(url('genie/mobile/edit/'.$id));
		
		}else{
		return redirect('/genie');
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
		if(Session::get('client.id')){
			$delete = DB::table('mobile')->where('id',$id)->delete();
			
				if($delete){
					$request->session()->flash('alert-success', 'Mobile successfully deleted !!');
					return redirect(url('genie/mobile'));
				}else{
					$request->session()->flash('alert-danger', 'Mobile not deleted!');
					return redirect('genie/mobile');
				}
		}else{
			return redirect('/genie');
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
