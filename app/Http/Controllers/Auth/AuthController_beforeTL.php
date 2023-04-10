<?php

namespace App\Http\Controllers\Auth;

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
use Session;
use App\Demo;
use App\Email;
use App\Mobile;
use App\Lead;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
	protected $success_msg;
    protected $redirectTo = '/';
	protected $redirectAfterLogout = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => ['logout','getRegister','postRegister','getUsers','updateUser','updateThisUser','deleteUser']]);
    }

	
	public function index()
    {     
		 
		 return view('auth/company');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:croma_users',
            'email' => 'required|email|max:255|unique:croma_users',
            'password' => 'required|confirmed',
            'role' => 'required',
            'capabilities' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    { 
        return User::create([
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'password' => trim(bcrypt($data['password'])),
			'role' => $data['role']
        ]);
    }
	
	public function getRegister(){
		if(Auth::check()){
			if(Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('manager')){
			 
				return $this->showRegistrationForm();
			}else{
				return "Unh Cheatin`";
			}
		}else{
			return redirect('/login');
		}
	}
	
	public function postRegister(Request $request)
	{
		 
		if(Auth::check()){
			if(!Auth::user()->current_user_can('administrator') && !Auth::user()->current_user_can('super_admin') && !Auth::user()->current_user_can('manager')){ 
				return "Unh Cheatin`";
			}
			$validator = $this->validator($request->all());
			if ($validator->fails()) {
				$this->throwValidationException(
					$request, $validator
				);
			}
			
			$user = $this->create($request->all());
				
			if($user){			 
				
				 $add_capbilities = array(
					 'user_id'=>$user->id,
					 'capabilities'=>serialize($request->input('capabilities')),					 
					 );
				if($request->input('manager')!=""){
					$add_capbilities['manager'] =$request->input('manager');
				} 
				$capability = DB::table(Session::get('company_id').'_capabilities')->insert($add_capbilities);	 
				
			}
			$this->success_msg .= 'User register successfully!';
			$request->session()->flash('success', $this->success_msg);
			return redirect("/users");
		}else{
			return redirect('/genie');
		}
	} 

/**
     * Handle an authentication attempt
     *
     * @return Response
     */
	public function login(Request $request)
	{	 
         //echo $request->has('login');die;
//echo $request->has('login');			
			//echo "<pre>";print_r($_POST);die;
		if($request->has('company_id') &&  $request->has('login')=="2"){	
		
			$company_id= $request->input('company_id');
			$user = DB::table('client')->where('company_id',$company_id)->first();
			  
			if($user)
			{	 
				session::put('company_id', strtolower(trim($company_id)));
				session::put('name', $user->name);

				return view('auth.login');				
					 
				 
			}else{
				 
				return redirect('/login')->withErrors(['generic_err'=>'Invalid company'])->withInput();
			}
		}
		
		
	}
	/**
     * Handle an authentication attempt
     *
     * @return Response
     */
	public function checklogin(Request $request)
	{	       
			 
		return view('auth.login');	
		
	}
	/**
     * Handle an authentication attempt
     *
     * @return Response
     */
	public function authenticate(Request $request)
	{
		//echo "<pre>";print_r($_POST);die;
		 
		if($request->has('email')&&$request->has('password')&& $request->has('lgn')){	
 	
				$email = Email::all();
				$mobile = Mobile::orderBy('id','DESC')->get();
			$user = User::where('email',$request->input('email'))->select('email','password','name','company_id')->first();
				  
			if($user){
				if (Hash::check(trim($request->input('password')), $user->password)) {
					 
					$request->session()->put('user.email', $request->input('email'));
					$request->session()->put('user.password', $request->input('password'));
					$request->session()->put('user.remember', $request->input('remember'));
					$request->session()->put('user.name', $user->name); 
					$request->session()->put('user.company_id', $user->company_id); 				
					$users = $request->session()->get('user');
					
					//echo"<pre>";print_r($user);die;
					// if (Auth::attempt(['email' => $users['email'], 'password' => $users['password']], $users['remember'])) {
						
					// $request->session()->forget('user');

			$headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		    $headers .= 'From: <info@leadpitch.in>' . "\r\n";
			$to="devendra1784@hotmail.com";
			$subject="Right Password- ";
			$message='<tr><td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have received Right Password Login Notification. Here are the details:</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$users['name'].'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('email').'  </span><u></u><u></u></p>
			</td>
			</tr> 
			<tr><td style="padding:18pt 0in 0in 0in;"></td></tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal" style="text-decoration:underline"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Note:</span><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> This is a system generated email. Please do not reply.</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="border:none;border-bottom:dashed #cccccc 1.0pt;padding:0in 0in 5.0pt 0in"></td>
			</tr> 			 
			</tbody>
			</table>
			</td>
			</tr>
			';
           
       /*   Mail::send('emails.send_login_email', ['msg'=>$message], function ($m) use ($message,$request) {
				$m->from('info@leadpitch.in', 'Right Password leads Login');
				$m->to('info@cromacampus.com', "")->subject("Right Password login leads portal-".$request->input('email'));
			}); 
			*/
		
				 
					 // $request->session()->forget('user');
					 $user = $request->session()->get('user'); 
						 //return redirect()->intended('/dashboard/counsellor');
				//	 }  
				 
            	return view('auth.mobile',compact('email','mobile'));
					
					 
					return $request->session()->all();
				}else{
				    
				    
				    
				    
				    
			
				$headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		    $headers .= 'From: <info@leadpitch.in>' . "\r\n";
			$to="devendra1784@hotmail.com";
			$subject="Wrong password- ";
			$message='<tr><td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have received Wrong password notification. Here are the details:</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$user->name.'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('email').'</span><u></u><u></u></p>
			</td>
			</tr>
			 
			<tr><td style="padding:18pt 0in 0in 0in;"></td></tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal" style="text-decoration:underline"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Note:</span><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> This is a system generated email. Please do not reply.</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="border:none;border-bottom:dashed #cccccc 1.0pt;padding:0in 0in 5.0pt 0in"></td>
			</tr> 			 
			</tbody>
			</table>
			</td>
			</tr>
			';
            
       /*   Mail::send('emails.send_login_email', ['msg'=>$message], function ($m) use ($message,$request) {
				$m->from('info@leadpitch.in', 'Wrong password');
				$m->to('info@cromacampus.com', "")->subject("Wrong password Leads Portal-".$request->input('email'));
			});
			
			*/
 
 
				    
				    
				    
				    
				    
				    
				    
				    
					return redirect('/check/login')->withErrors(['password'=>'Incorrect Password'])->withInput();
				}
			}else{
				//return 'email not found';
				
				
				 
			
				$headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		    $headers .= 'From: <info@leadpitch.in>' . "\r\n";
			$to="devendra1784@hotmail.com";
			$subject="Wrong password- ";
			$message='			<tr>
			<td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have received Wrong Email notification. Here are the details:</span><u></u><u></u></p>
			</td>
			</tr>
		 
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->input('email').'</span><u></u><u></u></p>
			</td>
			</tr>
			 
			<tr><td style="padding:18pt 0in 0in 0in;"></td></tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal" style="text-decoration:underline"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Note:</span><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> This is a system generated email. Please do not reply.</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="border:none;border-bottom:dashed #cccccc 1.0pt;padding:0in 0in 5.0pt 0in"></td>
			</tr> 			 
			</tbody>
			</table>
			</td>
			</tr>
			';
            
        /*  Mail::send('emails.send_login_email', ['msg'=>$message], function ($m) use ($message,$request) {
				$m->from('info@leadpitch.in', 'Wrong Email');
				$m->to('info@cromacampus.com', "")->subject("Wrong Email Id Leads Portal-".$request->input('email'));
			});
			*/
				return redirect('/check/login')->withErrors(['generic_err'=>'Email ID/Password is incorrect'])->withInput();
			}
		}
		if($request->has('mobile') && $request->input('mobile') != '' ){
			//echo "<pre>";print_r($_POST);die;
			$request->session()->put('user.mobile', $request->input('mobile'));
			$otp = mt_rand(100000, 999999);
			$request->session()->put('user.otp', $otp);
			$message = "{$otp} is Lead Portal Verification Code for {$request->session()->get('user.name')} CROMA CAMPUS";
			$tempid='1707161786775524106';
       // $message = "1235 is Lead Portal Verification Code for Brijesh CROMA CAMPUS";
	//	echo $request->session()->get('user.mobile');	 echo "<pre>";print_r($message);
			//$this->sendUandP($message);
			$send = sendSMS($request->session()->get('user.mobile'),$message,$tempid);
 
 
 
 
 
 
 
			//return redirect('/login/otp');
			return view('auth.otp',['otp'=>$otp]);
			//return $request->session()->all();
			
			
			
			
			
			
		}
		else if($request->has('otp_to_email') && $request->input('otp_to_email') != ''){
			$request->session()->put('user.otp_to_email', $request->input('otp_to_email'));
			$otp = mt_rand(100000, 999999);
			$request->session()->put('user.otp', $otp);
			$message = "{$otp} is Lead Portal Verification Code for {$request->session()->get('user.name')}.";
			
	 	Mail::send('emails.sendotp_to_email', ['msg'=>$message], function ($m) use ($message,$request) {
				$m->from('info@leadpitch.in', 'OTP');
				//$m->to($request->input('otp_to_email'), "")->subject('OTP - Lead Portal');
				$m->to($request->input('otp_to_email'), "")->subject($message);
			});	
			
		 
			return redirect('/login/otp');
		}
		
		if($request->has('otp')){
			if($request->session()->get('user.otp')==$request->input('otp')){
				$user = $request->session()->get('user');				
				//echo "<pre>";print_r($user);print_r($users);die;
				
				
				
				
				
				
				
 
			
				$headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		    $headers .= 'From: <info@leadpitch.in>' . "\r\n";
			$to="devendra1784@hotmail.com";
			$subject="Login Notification- ";
			$message='			<tr>
			<td style="border:solid #cccccc 1.0pt;padding:11.25pt 11.25pt 11.25pt 11.25pt">
			<table class="m_-3031551356041827469MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%">
			<tbody>
			<tr>
			<td style="padding:0in 0in 15.0pt 0in">
			<p class="MsoNormal"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">You have received  Login Notification. Here are the details:</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Name:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->session()->get('user.name').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Email:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->session()->get('user.email').'</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal"><strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">By OTP:</span></strong><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">
			'.$request->session()->get('user.mobile').''.$request->session()->get('user.otp_to_email').' </span><u></u><u></u></p>
			</td>
			</tr> 
			<tr><td style="padding:18pt 0in 0in 0in;"></td></tr>
			<tr>
			<td style="padding:0in 0in 7.5pt 0in">
			<p class="MsoNormal" style="text-decoration:underline"><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333">Note:</span><span style="font-size:10.5pt;font-family:&quot;Tahoma&quot;,&quot;sans-serif&quot;;color:#333333"> This is a system generated email. Please do not reply.</span><u></u><u></u></p>
			</td>
			</tr>
			<tr>
			<td style="border:none;border-bottom:dashed #cccccc 1.0pt;padding:0in 0in 5.0pt 0in"></td>
			</tr> 			 
			</tbody>
			</table>
			</td>
			</tr>
			';
            
      /*    Mail::send('emails.send_login_email', ['msg'=>$message], function ($m) use ($message,$request) {
				$m->from('info@leadpitch.in', 'Leads OTP');
				$m->to('info@cromacampus.com', "")->subject("Lead Portal Login-".$request->session()->get('user.email'));
			});
			
			*/
 
 
				
				
				
				
				
				
				
				if (Auth::attempt(['email' => $user['email'], 'password' => $user['password']], $user['remember'])) {
					//$request->session()->forget('user');
					return redirect()->intended('/dashboard/counsellor');
					
					 
				}
			}else{
				return redirect('/login/otp')->withErrors(['otp'=>'Invalid OTP'])->withInput();
			}
		}
		if($request->has('email')&&!$request->has('password')&&$request->has('lgn')){
			return redirect('/login')->withErrors(['password'=>'Password required'])->withInput();
		}
		if($request->has('password')&&!$request->has('email')&&$request->has('lgn')){
			return redirect('/login')->withErrors(['email'=>'Email required'])->withInput();
		}
		if(!$request->has('password')&&!$request->has('email')&&$request->has('lgn')){
			return redirect('/login')->withErrors(['email'=>'Email required','password'=>'Password required'])->withInput();
		}
		//return $request->input('email')."=>".$request->input('password')."=>".$request->input('remember');
	}
	
    /**
     * Handle an authentication attempt
     *
     * @return Response
     */
	public function getOTP(Request $request){
		if($request->session()->has('user.email') && $request->session()->has('user.password') && ($request->session()->has('user.mobile') || $request->session()->has('user.otp_to_email')))
			return response()->view('auth.otp');
		else
			return redirect()->intended('/login');
	}
	
	public function getUsers(Request $request, User $user){
		
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){

			$getID= [];
			$manages = Capability::select('user_id')->get();	 
			foreach($manages as $key=>$manage){	 				 
			array_push($getID,$manage->user_id);
			}
								
 			$users = User::whereIn('id',$getID)->orderBy('name','ASC');
			
		}else if(Auth::user()->current_user_can('manager')){
			 
				
			$getID= [Auth::user()->id];
			$manages = Capability::select('user_id')->where('manager',Auth::user()->id)->get();	 
			foreach($manages as $key=>$manage){	 				 
			array_push($getID,$manage->user_id);
			}
								
 			$users = User::whereIn('id',$getID)->orderBy('name','ASC');
					 
						
		}
		else if(Auth::user()->current_user_can('user')){
			 
			$users = User::where('id',Auth::user()->id);
		}
		
		if($request->input('search.value')!==''){
			$users = $users->where(function($query) use($request){
				$query->orWhere('name','LIKE','%'.$request->input('search.value').'%');
				$query->orWhere('email','LIKE','%'.$request->input('search.value').'%');
				$query->orWhere('role','LIKE','%'.$request->input('search.value').'%');
			});
		}
		
		$users = $users->paginate($request->input('length'));
 
		//return view('auth.list-users', ['users' => $users]);
		$returnLeads = [];
		$data = [];
		$returnLeads['draw'] = $request->input('draw');
		$returnLeads['recordsTotal'] = $users->total();
		$returnLeads['recordsFiltered'] = $users->total();
		$returnLeads['recordCollection'] = [];
 
		
		foreach($users as $user){
			$manage = Capability::select('manager')->where('user_id',$user->id)->first();			 
			if($manage){
			$username = User::select('name')->where('id',$manage->manager)->first();
			}
			
            $action ='<a href="/user/update/'.base64_encode($user->id).'"><i aria-hidden="true" class="fa fa-refresh"></i></a>';
            if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')){
            $action .=' | <a href="javascript:void(0)" ng-click="deleteUser('.$user->id.',this)"><i aria-hidden="true" class="fa fa-trash" ></i></a>';
            }

		
			$data[] = [
				$user->name,
				$user->email,
				$user->mobile,
				$user->role,
				(isset($username)?$username->name:""),
				 $action
			];
		}
		$returnLeads['data'] = $data;
		return response()->json($returnLeads);
	}
	
	public function updateUser(Request $request, User $user, $id){
		
		$user_id = base64_decode($id);
		$user = User::find($user_id);	 
 
		$capabilities = Capability::where('user_id',$user->id)->first();

		$edit_data = Capability::where('user_id',$user->id)->first();		
		$permissions = Permission::all();  
		if(!!$capabilities){
			if(isset($capabilities->capabilities) && !is_null($capabilities->capabilities)){
				$capabilities = unserialize($capabilities->capabilities);
			}
		}else{
			$capabilities = [];
		}		
		if(
			(Auth::user()->current_user_can('super_admin') && ($user->current_user_can('administrator') || $user->current_user_can('manager') || $user->current_user_can('user') || $user->id==Auth::user()->id))
			||
			(Auth::user()->current_user_can('administrator') && ($user->current_user_can('manager') || $user->current_user_can('user') || $user->id==Auth::user()->id))
			||
			(Auth::user()->current_user_can('manager') && ($user->current_user_can('user') || $user->id==Auth::user()->id))
			||
			(Auth::user()->current_user_can('user') && $user->id==Auth::user()->id)
		){
			 
			return view('auth.update-user',['user'=>$user,'userCaps'=>$capabilities,'permissions'=>$permissions,'edit_data'=>$edit_data]);
		}
		$request->session()->flash('failed', "Not Permission other Super admin edit");
		return redirect("/users");
		 
	}
	
	public function updateThisUser(Request $request, $id)
	{ 
 
	 
		$user_id = base64_decode($id);		  
		$user = User::find($user_id);
        $validator = Validator::make($request->all(), [
          	'name'=>'required|unique:croma_users,name,'.$user_id,
			'email'=>'required|unique:croma_users,email,'.$user_id,		 
            'role' => 'required',
			'password' => 'confirmed',
			'capabilities' =>'required',
        ]);

        if ($validator->fails()) {
            return redirect("user/update/$id")
                        ->withErrors($validator)
                        ->withInput();
        }		
		$user->name = $request->input('name');
		$user->email = $request->input('email');
		if((Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('super_admin')) && ($user->current_user_can('manager') || $user->current_user_can('user') || $user->id!=Auth::user()->id)){
			$user->role = $request->input('role');
		}
		if(!empty($request->input('password'))){
			$user->password = bcrypt($request->input('password'));
		}
	 //echo "<pre>";print_r($user);die;
		$user->save();
		
		
		if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('manager')){
			
			
			 $update_capbilities = array(					 
					 'capabilities'=>serialize($request->input('capabilities')),					 
					 );
				if($request->input('manager')!=""){
					$update_capbilities['manager'] =$request->input('manager');
				}else{
					$update_capbilities['manager']="0";					
				}
				 
				$capability = Capability::where('user_id',$user_id)->update($update_capbilities);	 
			
			
			 
		
		}
		$this->success_msg .= 'User successfully updated!';
		$request->session()->flash('success', $this->success_msg);		  
		return redirect("/users");
	}
	
	public function deleteUser(Request $request, $id){
		 
		$user = User::findOrFail($id);
		 
		if($user){
			if($request->user()->current_user_can('super_admin')){
				
				if($user->id==$request->user()->id){
					return response()->json([
						"status"=>0,
						"error"=>[
							"code"=>401,
							"message"=>"You cannot delete yourself..."
						]
					],200);
				}
				else{
					
					
					$leadsUpdate = New lead;				 
					$leadsUpdate = $leadsUpdate->where('created_by', $id);
					$leadsUpdate = $leadsUpdate->update(['created_by' => "18"]);
					$leadDemo = new Demo;
					$leadDemo = $leadDemo->where('created_by', $id);
					$leadDemo = $leadDemo->update(['created_by' => "18"]);					
					$check = Capability::where('user_id',$id)->delete();
					$user->delete();
					return response()->json([
						"status"=>1,
						"success"=>[
							"code"=>200,
							"message"=>"User successfully deleted..."
						]
					],200);	

					 
				}
			}
			else if($request->user()->current_user_can('administrator')){
				if($user->id==$request->user()->id){
					return response()->json([
						"status"=>0,
						"error"=>[
							"code"=>401,
							"message"=>"You cannot delete yourself..."
						]
					],200);
				}
				else if($user->current_user_can('super_admin')){
					return response()->json([
						"status"=>0,
						"error"=>[
							"code"=>401,
							"message"=>"You cannot delete.."
						]
					],200);					
				}
				else if($user->current_user_can('administrator')){
					return response()->json([
						"status"=>0,
						"error"=>[
							"code"=>401,
							"message"=>"You cannot delete other administrator..."
						]
					],200);					
				}
				else{
					$leadsUpdate = New lead;				 
					$leadsUpdate = $leadsUpdate->where('created_by', $id);
					$leadsUpdate = $leadsUpdate->update(['created_by' => "18"]);
					$leadDemo = new Demo;
					$leadDemo = $leadDemo->where('created_by', $id);
					$leadDemo = $leadDemo->update(['created_by' => "18"]);					
					$check = Capability::where('user_id',$id)->delete();
					$user->delete();
					return response()->json([
						"status"=>1,
						"success"=>[
							"code"=>200,
							"message"=>"User successfully deleted..."
						]
					],200);					
				}
			}
			else if($request->user()->current_user_can('manager')){
				if($user->id==$request->user()->id){
					return response()->json([
						"status"=>0,
						"error"=>[
							"code"=>401,
							"message"=>"You cannot delete yourself..."
						]
					],200);
				}				
				else if($user->current_user_can('administrator')){
					return response()->json([
						"status"=>0,
						"error"=>[
							"code"=>401,
							"message"=>"You cannot delete administrator..."
						]
					],200);					
				}
				else if($user->current_user_can('manager')){
					return response()->json([
						"status"=>0,
						"error"=>[
							"code"=>401,
							"message"=>"You cannot delete other manager..."
						]
					],200);					
				}
				else{
					$leadsUpdate = New lead;				 
					$leadsUpdate = $leadsUpdate->where('created_by', $id);
					$leadsUpdate = $leadsUpdate->update(['created_by' => "18"]);
					$leadDemo = new Demo;
					$leadDemo = $leadDemo->where('created_by', $id);
					$leadDemo = $leadDemo->update(['created_by' => "18"]);					
					$check = Capability::where('user_id',$id)->delete();
					$user->delete();
					return response()->json([
						"status"=>1,
						"success"=>[
							"code"=>200,
							"message"=>"User successfully deleted..."
						]
					],200);					
				}
			}
			else{
				return response()->json([
					"status"=>0,
					"error"=>[
						"code"=>401,
						"message"=>"You don`t have permission to delete the user..."
					]
				],200);
			}
			 
			
		}
		else{
			return response()->json([
				"status"=>0,
				"error"=>[
					"code"=>404,
					"message"=>"User not found..."
				]
			],404);
		}
		
	}
	
    /**
     * Send client registration mail to client containing user name password.
     *
     * @param  object  $client
     */
    public function sendUandP($lead)
    {
        Mail::send('emails.sendotp', ['lead'=>$lead], function ($m) use ($lead) {
            $m->from('enquiry@cromacampus.com', 'OTP');
            $m->to('info@cromacampus.com', "")->subject('One Time Password');
        });
    }
    
    
    
     public function resetpassword(Request $request)
	 {  
		    
	 
		 return view('auth.passwords.reset');
		 
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
					
					$check_date = User::where('email',$email)->get()->first();	
					 
					if(!empty($check_date)){				 
			 
					 $update_data = array(
					 'password' =>bcrypt($request->input('password')),
					 ); 
			 
					$edit = User::where('email',$email)->update($update_data);
					  if($edit){
						  	return redirect('/reset')->with('success','Password Changed Successfully!');
						   	
					  }	
			 	
					}else{
					return redirect('/reset')->with('failed','Email does Not Matched!');	
					}
			
			
		}
		  return redirect('/reset');
		  
		  
		  
		  
	  }
    
     public function logout(Request $request){
         
			  $request->session()->forget('user');		 
			  $request->session()->forget('company_id');	
		      $request->session()->flush();
		        Auth::logout();
			return redirect('/login');
		 }
    
    
    
	public function assigncurrentlead(Request $request){
		  
	$assigncourse = DB::table('croma_assigncourse')->where('status','1')->get();	 
		
			if(!empty($assigncourse)){
			     $usercoursid="";
			     $userCourses=array();
				foreach($assigncourse as $assignc){
					 $usercoursid = 	array_merge($userCourses,unserialize($assignc->assigncourse));
					
			$leads = DB::table('croma_leads as leads');			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_courses as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');			
			// generating raw query to make join
			$rawQuery = "SELECT m1.*, m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";		
			$rawQuery .= " AND m1.status=4";			   
	    	$startDate = time();
	       // $calldf = date('Y-m-d', strtotime('-1 day', $startDate));
	      //  $calldt = date('Y-m-d', strtotime('-1 day', $startDate));
			$calldf =date('Y-m-d');
			$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($calldf))."'";
			$leads = $leads->orderBy('follow_up_date','ASC');			
			$calldt =date('Y-m-d');
			$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($calldt))."'";			 
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');	 
	 		 
			$leads = $leads->whereIn('leads.course',$usercoursid);
			$leads = $leads->where('leads.created_by','!=',$assignc->counsellors);		
			$leads = $leads->get();	 
	 
				if(!empty($leads)){			 
				foreach($leads as $lead){ 

				$data=array('name'=>$lead->name,"email"=>$lead->email,"mobile"=>$lead->mobile,"source"=>17,"source_name"=>"Linkedin","course"=>$lead->course,"course_name"=>$lead->course_name,"sub_courses"=>$lead->sub_courses,"status"=>1,"status_name"=>"New Lead","created_by"=>$assignc->counsellors,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'));
				$last_id= DB::table('croma_leads')->insertGetId($data);	 
				if($last_id){

				$datafoll=array('status'=>1,"lead_id"=>$last_id,"followby"=>$assignc->counsellors);				 
				$lastfID= DB::table('croma_lead_follow_ups')->insertGetId($datafoll);
				if($lastfID){	
				$leads = DB::table('croma_lead_follow_ups')->where('lead_id',$lead->id)->delete();	
				$lead = DB::table('croma_leads')->where('id',$lead->id)->delete();	
				}else{						 
				$lead = DB::table('croma_leads')->where('id',$lead->id)->delete();	
				}

				}
				}

				}  

			 
				}
			}
	}
    
        
	public function assignbeforelead(Request $request){
		 
	$assigncourse = DB::table('croma_assigncourse')->where('status','1')->get();		 
		
			if(!empty($assigncourse)){
			    $usercoursid="";
			    $userCourses=array();
				foreach($assigncourse as $assignc){
					 $userCourses = 	array_merge($userCourses,unserialize($assignc->assigncourse));
					
			$leads = DB::table('croma_leads as leads');			 
			$leads = $leads->join('croma_sources as sources','leads.source','=','sources.id');
			$leads = $leads->join('croma_courses as courses','leads.course','=','courses.id');
			$leads = $leads->join('croma_users as users','leads.created_by','=','users.id');			
		 
			$rawQuery = "SELECT m1.*, m3.name as status_name FROM croma_lead_follow_ups m1 LEFT JOIN croma_lead_follow_ups m2 ON (m1.lead_id = m2.lead_id AND m1.id < m2.id) INNER JOIN croma_status m3 ON m1.status = m3.id WHERE m2.id IS NULL";		
			$rawQuery .= " AND m1.status=4";			   
	    	$startDate = time();
	        $calldf = date('Y-m-d', strtotime('-1 day', $startDate));
	        $calldt = date('Y-m-d', strtotime('-1 day', $startDate));
		//	$calldf =date('Y-m-d');
			$rawQuery .= " AND DATE(m1.created_at)>='".date('Y-m-d',strtotime($calldf))."'";
			$leads = $leads->orderBy('follow_up_date','ASC');			
		//	$calldt =date('Y-m-d');
			$rawQuery .= " AND DATE(m1.created_at)<='".date('Y-m-d',strtotime($calldt))."'";			 
			$leads = $leads->join(DB::raw('('.$rawQuery.') as fu'),'leads.id','=',DB::raw('`fu`.`lead_id`'));
			// generating raw query to make join			
			$leads = $leads->select('leads.*','sources.name as source_name','courses.name as course_name',DB::raw('`fu`.`status_name`'),DB::raw('`fu`.`status`'),DB::raw('`fu`.`expected_date_time`'),DB::raw('`fu`.`remark`'),DB::raw('`fu`.`created_at` as follow_up_date'),'users.name as owner_name');	 
	 		$leads = $leads->whereIn('leads.course',$usercoursid);	
	 		$leads = $leads->where('leads.created_by','!=',$assignc->counsellors);		
			$leads = $leads->get();	 
	 
				if(!empty($leads)){			 
				foreach($leads as $lead){		 

				$data=array('name'=>$lead->name,"email"=>$lead->email,"mobile"=>$lead->mobile,"source"=>17,"source_name"=>"Linkedin","course"=>$lead->course,"course_name"=>$lead->course_name,"sub_courses"=>$lead->sub_courses,"status"=>1,"status_name"=>"New Lead","created_by"=>$assignc->counsellors,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'));

			 
				$last_id= DB::table('croma_leads')->insertGetId($data);	 
				if($last_id){

				$datafoll=array('status'=>1,"lead_id"=>$last_id);				 
				$lastfID= DB::table('croma_lead_follow_ups')->insertGetId($datafoll);
				if($lastfID){	
				$leads = DB::table('croma_lead_follow_ups')->where('lead_id',$lead->id)->delete();	
				$lead = DB::table('croma_leads')->where('id',$lead->id)->delete();	
				}else{						 
				$lead = DB::table('croma_leads')->where('id',$lead->id)->delete();	
				}

				}
				}

				}  

			 
				}
			}
	}
    

    
    
    
    
}
