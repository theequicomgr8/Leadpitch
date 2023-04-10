<?php

namespace App\Http\Controllers;
use Session;
use App\Http\Controllers\Storage;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Capability;
use DB;
use App\Permission;
use Mail;
 
class ChangepasswordController extends Controller
{
	
	 public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource list of cms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        $id= Auth::user()->id ; 
		$title = "Change password";
		$edit_data = User::where('id',$id)->get()->first();
	 
		 return view('auth/passwords/changepassword',compact('edit_data','title'));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {	
		$id= Auth::user()->id ; 
		$title = "Change password";
		 
		if($request->isMethod('post') && $request->input('submit')=="Update")
		{	 
				 $this->validate($request, [
					'oldpwd'=>'required',
					'newpwd'=>'required|min:8|max:255',
					'cpwd' => 'required|min:8|max:255',					 
					
					]); 
			 		
					$edit_data = User::where('id',$id)->get()->first();	
					$newpwd = bcrypt($request->input('newpwd')); 
			 
			if(Hash::check(trim($request->input('oldpwd')), $edit_data->password))
			{ 
					 $update_data = array(
					 'password' =>bcrypt($request->input('newpwd')),
					 ); 
					 
					$edit = User::where('id',$id)->update($update_data);
					  if($edit){
						  	return redirect('/changepassword')->with('success','Password Changed Successfully!');
						   	
					  }	
			}else{
				return redirect('/changepassword')->with('failed','Old Password Not Matched!');					 
				}	
			
		}
		 return view('auth/passwords/changepassword');
    }
 
	 
}
