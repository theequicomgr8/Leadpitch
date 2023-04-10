<?php
namespace App\Http\Controllers;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Schema;
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
use Session;
use App\Demo;
use App\Email;
use App\Mobile;
use App\Client;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {     
		 
		 return view('admin/login');
    }

	/**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    { 
        return Client::create([
            'company_id' => strtolower($data['company_id']),
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'mobile' => trim($data['mobile']),
            'from' => trim($data['from']),
            'client_to' => trim($data['to']),
            'password' => trim(bcrypt($data['password'])),
			 
        ]);
    }
	
	 
	
	
   /**
     * Handle an authentication attempt
     *
     * @return Response
     */
	public function authenticate(Request $request)
	{
		
		 
         
			 
		if($request->has('email') && $request->has('password')&& $request->has('lgn')){
			$user = DB::table('users')->where('email',$request->input('email'))->where('role','master')->first();
			// echo"<pre>";print_r($user);die;
			$email = DB::table('email')->get();
			$mobile = DB::table('mobile')->get();
			if($user){
				if (Hash::check(trim($request->input('password')), $user->password)) {
					   
					$request->session()->put('user.email', $request->input('email'));
					$request->session()->put('user.password', $request->input('password'));
					$request->session()->put('user.remember', $request->input('remember'));
					$request->session()->put('user.name', $user->name);		
					$request->session()->put('user.id', $user->id);		
					$request->session()->put('user.role', $user->role);		

					$user = $request->session()->get('user');
					//echo "<pre>";print_r($user);die;
					
					
					/* if (Auth::attempt(['email' => $user['email'], 'password' => $user['password']], $user['remember'])) {
						 
					 $request->session()->forget('user');
						 return redirect()->intended('/genie/client');
					 } 	 */

					 
					//return redirect()->intended('/genie/client');					 
					return view('admin.mobile',compact('email','mobile'));
					return $request->session()->all();
				}else{
					return redirect('/genie')->withErrors(['password'=>'Incorrect Password'])->withInput();
				}
			}else{
				//return 'email not found';
				return redirect('/genie')->withErrors(['generic_err'=>'Email ID/Password is incorrect'])->withInput();
			}
		}
		if($request->has('mobile') && $request->input('mobile') != ''){
			$request->session()->put('user.mobile', $request->input('mobile'));
			$otp = mt_rand(100000, 999999);
			$request->session()->put('user.otp', $otp);
			$message = "{$otp} is Lead Portal Verification Code for {$request->session()->get('user.name')}.";
	//	echo $request->session()->get('user.mobile');	 echo "<pre>";print_r($message);
			//$this->sendUandP($message);
			$send = sendSMS($request->session()->get('user.mobile'),$message);
 
			return redirect('/genie/otp');
			//return view('auth.otp',['otp'=>$otp]);
			//return $request->session()->all();
		}
		else if($request->has('otp_to_email') && $request->input('otp_to_email') != ''){
			$request->session()->put('user.otp_to_email', $request->input('otp_to_email'));
			$otp = mt_rand(100000, 999999);
			$request->session()->put('user.otp', $otp);
			$message = "{$otp} is Lead Portal Verification Code for {$request->session()->get('user.name')}.";
			
			Mail::send('emails.sendotp_to_email', ['msg'=>$message], function ($m) use ($message,$request) {
				$m->from('otp@leadsedge.com', 'OTP');
				//$m->to($request->input('otp_to_email'), "")->subject('OTP - Lead Portal');
				$m->to($request->input('otp_to_email'), "")->subject($message);
			});			
			return redirect('/genie/otp');
		}
		
		if($request->has('otp')){
//echo "iiner";echo $request->session()->get('user.otp').'==';echo $request->input('otp');die;
			if($request->session()->get('user.otp')==$request->input('otp')){
				$user = $request->session()->get('user'); 
					return redirect('/genie/clientDashboard');
				
			}else{
				return redirect('/genie/otp')->withErrors(['otp'=>'Invalid OTP'])->withInput();
			}
		}
		if($request->has('email')&&!$request->has('password')&&$request->has('lgn')){
			return redirect('/genie')->withErrors(['password'=>'Password required'])->withInput();
		}
		if($request->has('password')&&!$request->has('email')&&$request->has('lgn')){
			return redirect('/genie')->withErrors(['email'=>'Email required'])->withInput();
		}
		if(!$request->has('password')&&!$request->has('email')&&$request->has('lgn')){
			return redirect('/genie')->withErrors(['email'=>'Email required','password'=>'Password required'])->withInput();
		}
		//return $request->input('email')."=>".$request->input('password')."=>".$request->input('remember');
		
		 
	}
    
    /**
     * Handle an authentication attempt
     *
     * @return Response
     */
	public function getOTP(Request $request){
		 if(Session::get('user.id')){
		if($request->session()->has('user.email') && $request->session()->has('user.password') && ($request->session()->has('user.mobile') || $request->session()->has('user.otp_to_email')))
			return response()->view('admin.otp');
		else
			return redirect()->intended('/genie');
		}else{
			return redirect('/genie');
		}
	}
	
	public function getRegister(){
		
		if(Session::get('user.id')){ 
			 
		return response()->view('client.register');
			 
		}else{
			return redirect('/genie');
		}
	}
	public function clientDashboard()
	{
	 if(Session::get('user.id')){
		return view('admin/client_dashboard');
		}else{
			return redirect('/genie');
		}
	}
	
	public function postRegister(Request $request)
	{
		 
		 if(Session::get('user.id')){
			
		 
			if($request->input('client')=="client")
			{
				 
			$this->validate($request, [
									 
					'company_id' => 'required|min:2|max:8|unique:client',	
					'name' => 'required',
					'name'=>'required|unique:client',
					'email' => 'required|email|unique:client',
					'mobile' => 'required|numeric|unique:client',					 
					'from' => 'required',					 
					'to' => 'required',					 
					'password'=>'required|min:8',					 
					'confirmpassword' => 'required_with:password|same:password|min:8'					
					
					]);
					
					
			$company_id =strtolower($request->input('company_id'));
			$name       = $request->input('name');
			$mobile     = $request->input('mobile');
			$email     	= $request->input('email');
			$from   	= $request->input('from');
			$to   		= $request->input('to');
			$password   = $request->input('password');
		
		 /* create client leads table dynamic  */
			Schema::create($company_id.'_leads', function (Blueprint $table) {
			$table->increments('id');
			$table->char('name',255);
			$table->char('email',255)->nullable();
			$table->string('mobile')->nullable();
			$table->bigInteger('source');
			$table->string('source_name');
			$table->bigInteger('course');
			$table->char('course_name');
			$table->char('sub_courses')->nullable();
			$table->integer('status');
			$table->string('status_name');
			$table->tinyInteger('demo_attended');
			$table->tinyInteger('move_not_interested');
			$table->text('remarks');
			$table->timestamp('deleted_at')->nullable();
			$table->bigInteger('deleted_by');
			$table->bigInteger('created_by');
			$table->timestamps();
			});
			
			 /* create client lead follow ups table dynamic  */
			Schema::create($company_id.'_lead_follow_ups', function (Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('status');
			$table->enum('follow_status',['0', '1'])->default(0);
			$table->dateTime('expected_date_time')->nullable();
			$table->text('remark')->nullable();
			$table->bigInteger('lead_id');			 
			$table->timestamps();
			});   
			
			 /* create client demos table dynamic  */
			Schema::create($company_id.'_demos', function (Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('lead_id');
			$table->char('name',255);
			$table->char('email',255)->nullable();
			$table->string('mobile')->nullable();
			$table->bigInteger('source');
			$table->string('source_name');
			$table->bigInteger('course');
			$table->char('course_name');
			$table->char('sub_courses')->nullable();
			$table->integer('status');
			$table->string('status_name');
			$table->tinyInteger('demo_attended');
			$table->tinyInteger('move_not_interested');
			$table->char('executive_call');
			$table->char('demo_type');
			$table->integer('owner');
			$table->text('remarks');
			$table->timestamp('deleted_at')->nullable();
			$table->bigInteger('deleted_by');
			$table->bigInteger('created_by');
			$table->timestamps();
			});
			
			 /* create client lead follow ups table dynamic  */
			Schema::create($company_id.'_demo_follow_ups', function (Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('status');
			$table->enum('follow_status',['0', '1'])->default(0);
			$table->dateTime('expected_date_time')->nullable();
			$table->text('remark')->nullable();
			$table->bigInteger('demo_id');			 
			$table->timestamps();
			}); 
			
			
				/* create client leads table dynamic  */
			Schema::create($company_id.'_capabilities', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->longText('capabilities');
			$table->integer('manager');
			$table->integer('administrator');			 
			$table->timestamps();
			});   
				/* create client chating table dynamic  */
			Schema::create($company_id.'_chating', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('chating_id');
			$table->longText('chating');		 		 
		    $table->dateTime('chating_date');
			}); 

			/* create client courses table dynamic  */
			Schema::create($company_id.'_courses', function (Blueprint $table) {
			$table->increments('id');
			$table->char('name')->nullable();
			$table->longText('counsellors');		 		 
			$table->timestamps();
			});  
			
			
			/* create client Email table dynamic  */
			Schema::create($company_id.'_email', function (Blueprint $table) {
			$table->increments('id');
			$table->char('name');
			$table->char('email');		 		 
			$table->integer('status');		 		 
			$table->timestamps();
			}); 
			
			/* create client mobile table dynamic  */
			Schema::create($company_id.'_mobile', function (Blueprint $table) {
			$table->increments('id');
			$table->char('name');
			$table->char('mobile');		 		 
			$table->integer('status');		 		 
			$table->timestamps();
			}); 
			
			$messages =DB::table($company_id.'_email')->insert(array(
			array('name'=>$name,
			"email"=>$email,
			"status"=>"1") 
			
			));
			
			$messages =DB::table($company_id.'_mobile')->insert(array(
			array('name'=>$name,
			"mobile"=>$mobile,
			"status"=>"1") 
			
			));

			/* create client mobile table dynamic  */
			Schema::create($company_id.'_sources', function (Blueprint $table) {
			$table->increments('id');
			$table->char('name')->nullable();		 	 		 
			$table->timestamps();
			});   
				//$saurses =DB::table($company_id.'_sources')->insert(array('name'=>"GrewBox"));
			
				/* create client messages table dynamic  */
			Schema::create($company_id.'_messages', function (Blueprint $table) {
			$table->increments('id');
			$table->char('name');		 	 		 
			$table->text('message');		 	 		 
			$table->char('permission');		 	 		 
			$table->char('description');		 	 		 
			$table->char('course')->nullable();		 	 		 
			$table->enum('all_lead',['0', '1'])->nullable()->default(1);		 	 		 
			$table->enum('all_demo',['0', '1'])->nullable()->default(1);		 	 		 
			$table->enum('add_lead',['0', '1'])->nullable()->default(1);		 	 		 
			$table->enum('add_demo',['0', '1'])->nullable()->default(1);		 	 		 
			$table->timestamps();
			});   
			
		$messages =DB::table($company_id.'_messages')->insert(array(
			array('name'=>"Welcome Message",
			"message"=>"Hi {{name}},
			Thanks for showing your interest in {{course}} Training.
			Our experts will get back to you soon.
			
			Croma Campus
			E-20, Sec-3, Noida
			https://goo.gl/FWel0I","permission"=>"G","all_lead"=>"1","all_demo"=>"1","add_lead"=>"1","add_demo"=>"1"),
			
			array('name'=>"SMS Counselor",
			"message"=>"Hi {{name}},
				Thanks for showing your interest in {{course}} Training.
				Our address is:
				Croma Campus
				E-20, Sec-3, Noida 
				Near Sec-16 Metro Station 
				http://www.cromacampus.com Thanks
				{{counsellor}}
				{{mobile}}","permission"=>"G","all_lead"=>"1","all_demo"=>"1","add_lead"=>"1","add_demo"=>"1")
			
			
			));
			
			
				/* create client status table dynamic  */
			Schema::create($company_id.'_status', function (Blueprint $table) {
			$table->increments('id');
			$table->char('name');		 	 		 
			$table->enum('show_exp_date',['0', '1'])->nullable()->default(1);		 	 		 
			$table->enum('lead_filter',['0', '1'])->nullable()->default(1);			 	 		 
			$table->enum('lead_follow_up',['0', '1'])->nullable()->default(1);			 	 		 
			$table->enum('add_demo',['0', '1'])->nullable()->default(1);			 	 		 
			$table->enum('demo_filter',['0', '1'])->nullable()->default(1);		 	 		 
			$table->enum('demo_follow_up',['0', '1'])->nullable()->default(1);			 	 	 		 
			$table->timestamps();
			});   
			
	    	$status	=DB::table($company_id.'_status')->insert(array(
				array("name"=>"New Lead","show_exp_date"=>"1","lead_filter"=>"1","lead_follow_up"=>"1","add_demo"=>"0","demo_filter"=>"0","demo_follow_up"=>"0"),
				array("name"=>"NPUP","show_exp_date"=>"1","lead_filter"=>"1","lead_follow_up"=>"1","add_demo"=>"1","demo_filter"=>"1","demo_follow_up"=>"1"),
				array("name"=>"Interested","show_exp_date"=>"1","lead_filter"=>"1","lead_follow_up"=>"1","add_demo"=>"1","demo_filter"=>"1","demo_follow_up"=>"1"),
				array("name"=>"Not Interested","show_exp_date"=>"0","lead_filter"=>"1","lead_follow_up"=>"1","add_demo"=>"1","demo_filter"=>"1","demo_follow_up"=>"1"),
				array("name"=>"Call Later","show_exp_date"=>"1","lead_filter"=>"1","lead_follow_up"=>"1","add_demo"=>"1","demo_filter"=>"1","demo_follow_up"=>"1"),
				array("name"=>"Switched Off","show_exp_date"=>"1","lead_filter"=>"1","lead_follow_up"=>"1","add_demo"=>"1","demo_filter"=>"1","demo_follow_up"=>"1"),
				array("name"=>"Not Reachable","show_exp_date"=>"1","lead_filter"=>"1","lead_follow_up"=>"1","add_demo"=>"1","demo_filter"=>"1","demo_follow_up"=>"1"),
				array("name"=>"Location Issue","show_exp_date"=>"0","lead_filter"=>"1","lead_follow_up"=>"1","add_demo"=>"1","demo_filter"=>"1","demo_follow_up"=>"1"),
				array("name"=>"Joined","show_exp_date"=>"1","lead_filter"=>"0","lead_follow_up"=>"0","add_demo"=>"0","demo_filter"=>"1","demo_follow_up"=>"1"),
				array("name"=>"Attended Demo","show_exp_date"=>"1","lead_filter"=>"0","lead_follow_up"=>"0","add_demo"=>"1","demo_filter"=>"1","demo_follow_up"=>"1"),
				array("name"=>"Close","show_exp_date"=>"0","lead_filter"=>"0","lead_follow_up"=>"0","add_demo"=>"0","demo_filter"=>"1","demo_follow_up"=>"1"),
				 
				));
				
				
				
			
					/* create table role permissions */
			
			Schema::create($company_id.'_roles_permissions', function (Blueprint $table) {
			$table->increments('id');
			$table->char('role');
			$table->longText('permissions')->nullable();			 
			$table->rememberToken();
			$table->timestamps();
			});  
			$role_permissions = DB::table('roles_permissions')->get();
			if(!empty($role_permissions)){
				foreach($role_permissions as $role_permission){					
					$rolepermission =DB::table($company_id.'_roles_permissions')->insert(array(
					array('role'=>$role_permission->role,
					"permissions"=> $role_permission->permissions,
					)				 			
				));
				
				}
			
			}
			
			
			/* end role permossion */
			
			Schema::create($company_id.'_users', function (Blueprint $table) {
			$table->increments('id');
			$table->char('name');
			$table->string('company_id')->nullable();
			$table->string('email')->nullable();
			$table->string('mobile')->nullable();
			$table->string('password')->nullable();
			$table->string('role');
			$table->text('image');
			$table->date('from');
			$table->date('to');
			$table->rememberToken();
			$table->timestamps();
			});  
			
			 
			
			
			$client = $this->create($request->all());				 
		 
			if($client){

			 
				$userData = array(
				'name' => trim($name),
				'company_id' => strtolower(trim($company_id)),
				'email' => trim($email),
				'mobile' => trim($mobile),
				'role' => 'super_admin',
				'from' => $from,
				'to' => $to,
				'password' => trim(bcrypt($password)),
				);
				$clientuser = DB::table($company_id.'_users')->insertGetId($userData);
				$permissions = Permission::select('id','permission')->get();
				$permission_list=[];
				foreach($permissions as $permission){
					array_push($permission_list,$permission->permission);
				}
			 		 
					 $add_capbilities = array(
					 'user_id'=>$clientuser,
					 'capabilities'=>serialize($permission_list),
					 
					 );
				$capability = DB::table($company_id.'_capabilities')->insert($add_capbilities);


			/* orphan user create */
				$orphanData = array(
				'name' => trim('Orphan'),
				'company_id' => trim($company_id),				 
				'role' => 'user',				 
				 
				);
				$orphanUser = DB::table($company_id.'_users')->insertGetId($orphanData);
				$permissions = Permission::select('id','permission')->get();
				$permission_list=[];
				foreach($permissions as $permission){
					array_push($permission_list,$permission->permission);
				}
			 		 
					 $orphan_capbilities = array(
					 'user_id'=>$orphanUser,
					 'capabilities'=>serialize($permission_list),
					 
					 );
				$capability = DB::table($company_id.'_capabilities')->insert($orphan_capbilities);				 
				 
				
				
			}
			 
			$request->session()->flash('success','User register successfully!');
			return redirect("/genie/clientList");
			
		}
		
		}else{
			return redirect('/genie');
		}
		 
	} 
	
	
	/* list of clinet */
	
	public function getClient()
	{
		if(Session::get('user.id')){
		$clients =DB::table('client')->get();

		return view('admin.list-client',compact('clients'));
		}else{
		return redirect('/genie');
		}
	}

}
