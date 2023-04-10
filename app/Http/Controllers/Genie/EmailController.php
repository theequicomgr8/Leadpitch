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
use Session;
class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		if(Session::get('client.id')){

		$email = DB::table('email')->orderBy('id','desc')->get();

		return view('genie.cm_email.email',['email'=>$email]);

		}else{
			return redirect('/genie');
		}
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
			$email = $request->input('email');			 
			 
			 $this->validate($request, [
				'name'=>'required|unique:email',
				'email'=>'required|unique:email',

				]);   
			$add_data = array(
			'name'=>$name,
			'email'=>$email,
			'status'=>1,
			);
			 		
		 
			$add  = DB::table('email')->insert($add_data);
			if($add){
				$request->session()->flash('alert-success', 'Emial successfully added !');
				return redirect(url('genie/email'));
			}else{
				$request->session()->flash('alert-danger', 'Email not added !');
				return redirect(url('genie/email'));
			}
		}
			return redirect(url('genie/email'));
		}else{
			return redirect('/genie');
		}
		 
    }
 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {		 
		 
			$edit_email = DB::table('email')->where('id',$id)->first();
			  
			return view('genie.cm_email.email_update',compact('edit_email'));
		 
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
			$name =  trim(ucwords($request->input('name')));
			$email =  trim($request->input('email'));
			$this->validate($request, [
				 'name'	=> 'required|max:32|unique:email,name,'.$id.',id',		
				'email'=>'required|unique:email,email,'.$id.',id',

				]);  
			$update_date = array(
			'name'=>$name,
			'email'=>$email,
			); 
			 
			$update = DB::table('email')->where('id',$id)->update($update_date);			 
			$request->session()->flash('alert-success', 'Email successfully updated !');
			return redirect(url('genie/email'));
			 
		}
		return redirect(url('genie/email'));
		
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
			$delete = DB::table('email')->where('id',$id)->delete();
			
				if($delete){
					$request->session()->flash('alert-success', 'Email successfully deleted !!');
					return redirect(url('genie/email'));
				}else{
					$request->session()->flash('alert-danger', 'Email not deleted!');
					return redirect('genie/email');
				}
		 }else{
			return redirect('/genie');
	 
		}
			 
		 
    }
	
     
}
