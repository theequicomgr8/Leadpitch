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
 
class ProfileController extends Controller
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
		$title = "Profile";
		$edit_data = User::where('id',$id)->get()->first();
		// echo "<pre	>";print_r($profile_data);die;
		 return view('auth/profile/index',compact('edit_data','title'));
    }

    /**
     * Show the form for creating a new resource .
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
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
		$title = "Profile edit";
		$button = "Update"; 
		if($request->isMethod('post') && $request->input('submit')=="Update")
		{		
			 
	
		 $this->validate($request, [
									 
					'name' 	=> 'required|max:32',	
					'email' => 'required|email',
					'mobile' => 'required|numeric|digits:10',
					 
					
					]); 	
		 	 
			$name =  $request->input('name');				 		 		 
			$email =  $request->input('email');				 		 		 
			$mobile =  $request->input('mobile');	 
		 
			$photo = $request->file('image');			 
		 
				$updatedata= array(					
					'name'=>$name,  
					'email'=>$email,  
					'mobile'=>$mobile,  
					 
					
				 );	  
				if($photo)
				{   
				    $this->validate($request, [									 
					'image' 		=> 'required|mimes:jpeg,jpg,png'					
					]); 
					$destinationPath = base_path() . '/public/upload/';
					$image =$photo->getClientOriginalName();
					$image = time().$image;
					$photo->move($destinationPath, $image);	
				 
					$updatedata['image'] =$image;
				}	
		 
				$insert = User::where('id',$id)->update($updatedata);  
			 
				if($insert){
				return redirect('profile')->with('success','Profile successfully updated!');
				}else {
				return redirect('profile')->with('failed','Profile not updated!');
				}
			
		}
		 
		
    }
 
	
	/**
     * the specified resource from storage del_icon.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del_icon($id)
    {
         
		$delet_data = User::where('id',$id)->get()->first();		 
		if($delet_data->image!='')
		{		
			if (file_exists(base_path('public/upload/'. $delet_data->image)))
				{
                 unlink(base_path('public/upload/'. $delet_data->image));
				}
		 
		}
 
		$edit_data = array('image'  =>"",);	 
		$del = User::where('id',$id)->update($edit_data);		 		
		return redirect('profile')->with("success","Profile image deleted successfully.");
		
		 
		
    }
	 
}
