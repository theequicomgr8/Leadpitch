<?php

namespace App\Http\Controllers\Genie;
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
use App\Capability;
use DB;
use App\Permission;
use Mail;
use Excel;
use Schema;
 
class ClientController extends Controller
{
	
	public function __construct()
    {
        //$this->middleware('auth');
    }
    /**
     * Display a listing of the resource list of cms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
	
	if(Session::get('client.role') =='master'){
		
        $id= Session::get('client.id'); 
		$title = "Profile";
		$edit_data = DB::table('users')->where('id',$id)->first();	 
		 return view('genie/index',compact('edit_data','title'));
		 
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
    public function edit(Request $request)
    {	
		if(Session::get('client.role') =='master'){
		$id= Session::get('client.id'); 
		$title = "Profile edit";
		$button = "Update"; 
		if($request->isMethod('post') && $request->input('submit')=="Update")
		{		
				
		 $this->validate($request, [
									 
					'name' 		=> 'required',	
					'email' => 'required|email',
					'mobile' => 'required|numeric',					 
					
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
					$destinationPath = base_path() . '/public/upload/';
					$image =$photo->getClientOriginalName();
					$image = time().$image;
					$photo->move($destinationPath, $image);	
				 
					$updatedata['image'] =$image;
				}	
		 
				$insert =DB::table('users')->where('id',$id)->update($updatedata); 			 
				return redirect('genie/profile')->with('success','Profile successfully updated!');
				 
			
		}
	}else{
		 return redirect('/genie');
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
         if(Session::get('client.id')){
		$delet_data = DB::table('users')->where('id',$id)->first();		 
		if($delet_data->image!='')
		{		
			if (file_exists(base_path('public/upload/'. $delet_data->image)))
				{
                 unlink(base_path('public/upload/'. $delet_data->image));
				}
		 
		}
 
		$edit_data = array('image'  =>"",);	 
		$del = DB::table('users')->where('id',$id)->update($edit_data);		 		
		return redirect('client/profile')->with("success","Profile image deleted successfully.");
		
		}else{
		 return redirect('/genie');
	} 
		
    }
	 
	public function getPassword()
    {  
	
	if(Session::get('client.role') =='master'){
        $id=  Session::get('client.id');  
		$title = "Change password";
		$edit_data = DB::table('users')->where('id',$id)->first();
	 
		 return view('genie/passwords/changepassword',compact('edit_data','title'));
		 
		 
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
    public function changePassword(Request $request)
    {	
		if(Session::get('client.role') =='master'){
			
		$id= Session::get('client.id');  	 
		$title = "Change password";		 
		if($request->isMethod('post') && $request->input('submit')=="Update")
		{	 
				 $this->validate($request, [
					'oldpwd'=>'required',
					'newpwd'=>'required|min:8|max:255',
					'cpwd' => 'required|min:8|max:255',					 
					
					]); 
			 		
					$edit_data = DB::table('users')->where('id',$id)->first();						 
					$newpwd = bcrypt($request->input('newpwd')); 
			 
			if(Hash::check(trim($request->input('oldpwd')), $edit_data->password))
			{ 
					 $update_data = array(
					 'password' =>bcrypt($request->input('newpwd')),
					 ); 
					  
					$edit = DB::table('users')->where('id',$id)->update($update_data);
					  if($edit){
						  	return redirect('/genie/changepassword')->with('success','Password Changed Successfully');
						   	
					  }	
			}else{
				return redirect('/genie/changepassword')->with('failed','Old Password Not Matched!');					 
				}	
			
		}
		 return view('genie/passwords/changepassword');
		 
		}else{
		 return redirect('/genie');
		}
    }
	 
	 public function resetpassword(Request $request)
	 {  		 
		 return view('genie.reset');
		 
	 }
	  public function passwordReset(Request $request){
		  
		 
		if($request->isMethod('post') && $request->input('reset')=="resetPass")
		{	 
				 $this->validate($request, [
					'email'=>'required',
					'password'=>'required|min:8|max:255',
					'password_confirmation' => 'required|min:8|max:255',					 
					
					]); 
			 		
					
					$email = $request->input('email');
					
					$check_date = DB::table('users')->where('email',$email)->first();	
					// echo "<pre>";print_r($check_date);die;
					if(!empty($check_date)){				 
			 
					 $update_data = array(
					 'password' =>bcrypt($request->input('password')),
					 ); 
				//	echo "<pre>";print_r($update_data);die;
					$edit = DB::table('users')->where('email',$email)->update($update_data);
					  if($edit){
						  	return redirect('genie/reset')->with('success','Password Changed Successfully!');
						   	
					  }	
			 	
					}else{
					return redirect('genie/reset')->with('failed','Email does Not Matched!');	
					}
			
			
		}
		  return redirect('genie/reset');
		  
		  
		  
		  
	  }
	

	 public function logout(Request $request)
	 {
		  
		 $request->session()->forget('client');		
		 return redirect('/genie');
		 
	 }
	  
	  
	  public function updateClient(Request $request, $id){
		  
		 if(Session::get('client.role') =='master'){
		
			$user_id = base64_decode($id);
			$edit_data = DB::table('client')->where('client_id',$user_id)->first();
			return view('genie.update-client',['edit_data'=>$edit_data]);		
			$request->session()->flash('failed', "Not Permission other Super admin edit");
		}else{
		 return redirect('/genie');
		}
		 
		 
		 
		 
	}
	
	public function updateThisClient(Request $request, $id)
	{ 
		if(Session::get('client.role') =='master'){
		$user_id = base64_decode($id);
		if($request->input('client')=="clientUpdate"){ 
		   $this->validate($request, [					 
						 
						'name'=>'required',					 	
						'email' => 'required',
						'mobile' => 'required|numeric',					 
						'from' => 'required',					 
						'to' => 'required'					 
						//'password'=>'required|min:8',					 
						//'confirmpassword' => 'required_with:password|same:password|min:8'					
						
						]);
						
						
				 
				$name       = $request->input('name');
				$mobile     = $request->input('mobile');
				$email     	= $request->input('email');
				$from   	= $request->input('from');
				$to   		= $request->input('to');
				$password   = $request->input('password');
				
			 
				$update_client = array(
				
				'name'=>$name,
				'mobile'=>$mobile,
				'email'=>$email,
				'from'=>$from,
				'client_to'=>$to,
				);
				if($request->input('password') !=''){
					$update_client['password']=$request->input('password');				
				}
				
				 
				$checkupdate = DB::table('client')->where('client_id',$user_id)->update($update_client);
			 
			$this->success_msg = 'User successfully updated!';
			$request->session()->flash('success', $this->success_msg);		  
			return redirect("genie/clientList");
		}
		$edit_data = DB::table('client')->where('client_id',$user_id)->first();	 
		return redirect('genie.update-client'.$id,['edit_data'=>$edit_data]);
		}else{
			 return redirect('/genie');
			}
	}
	  
	  
	  
	  
	  
	  /* dowload excel formate add lead */
	  public function downloadExcelFormate(){
		  
		  $arr[] = [
						"Name"=>'Test',
						"Email"=>'test@gmail.com',
						"Mobile"=>'1234567891',
						"Source"=>'Croma',
						"Course"=>'PHP',
						"Sub-Course"=>'Java Script',						
						"Status"=>'New Lead',
						"Expected_Date_Time"=>'m/d/yyyy', 
						"Remarks"=>'Interested',
						 
						 
					];			 
				 
			
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('add_leads_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		  
	  } 
	  
	  
	  public function excelFormateDemo(){
		  
		  $arr[] = [
						"Name"=>'Test demo',
						"Email"=>'testdemo@gmail.com',
						"Mobile"=>'1234567891',
						"Source"=>'Croma',
						"Course"=>'PHP',
						"Sub-Course"=>'Java Script',						
						"Status"=>'Attended Demo',
						"Expected_Date_Time"=>'m/d/yyyy', 
						"Remarks"=>'Joined start',					 
						 
					];			 
				 
			
			date_default_timezone_set('Asia/Kolkata'); 
			$excel = \App::make('excel');
			 
			Excel::create('add_demos_'.date('Y-m-d H:i a'), function($excel) use($arr) {
				$excel->sheet('Sheet 1', function($sheet) use($arr) {
					$sheet->fromArray($arr);
				});
			})->export('xls');
		  
	  }
	  
	 public function clientDelte(Request $request, $id){
		 
		$client_id=  base64_decode($id);
	
		 $client = DB::table('client')->where('client_id',$client_id)->first();		 
		 $company_id= $client->company_id;
		 Schema::dropIfExists($company_id.'_leads');
		 Schema::dropIfExists($company_id.'_lead_follow_ups');
		 Schema::dropIfExists($company_id.'_demos');
		 Schema::dropIfExists($company_id.'_demo_follow_ups');
		 Schema::dropIfExists($company_id.'_capabilities');
		 Schema::dropIfExists($company_id.'_chating');
		 Schema::dropIfExists($company_id.'_courses');
		 Schema::dropIfExists($company_id.'_email');
		 Schema::dropIfExists($company_id.'_mobile');
		 Schema::dropIfExists($company_id.'_sources');
		 Schema::dropIfExists($company_id.'_messages');
		 Schema::dropIfExists($company_id.'_status');
		 Schema::dropIfExists($company_id.'_roles_permissions');
		 Schema::dropIfExists($company_id.'_users');
		 $clinet_delete = DB::table('client')->where('client_id',$client_id)->delete();
	 
		$request->session()->flash('success','User Deleted successfully!');
			return redirect("/genie/clientList");
		 
		 
		 
		 
	 }
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
}
