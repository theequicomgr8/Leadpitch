<?php

namespace App\Http\Controllers\Genie;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;

//models
use App\Permission;
use App\RolePermission;
use Session;
use DB;
class ClientRolesPermissionsController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	/* public function __construct(){
		$this->middleware(function ($request, $next) {
			if($request->user()->current_user_can('super_admin') || $request->user()->current_user_can('administrator')){
				return $next($request);
			}else{
				return "Unh Cheatin`";
			}
		});
	} */
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function permissionIndex(Request $request){
	 if(Session::get('client.id')){
			return view('genie.roles_permissions.permission');
		}else{
				return redirect('/genie');
		}
	}
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function permissionStore(Request $request)
    {
       if(Session::get('client.id')){
			$validator = Validator::make($request->all(),[
				'permission'=>'required|unique:permissions',
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
			
			$permission = new Permission;
			$permission->permission = $request->input('permission');
			if($permission->save()){
				return response()->json(['status'=>1],200);
			}else{
				return response()->json(['status'=>0,'errors'=>'Permission not added'],400);
			}
		}else{
				return redirect('/genie');
		}
    }
	
    /**
     * Get paginated permissions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedPermissions(Request $request)
    {
		 if(Session::get('client.id')){
			$permissions = Permission::orderBy('id','desc');
			if($request->input('search.value')!==''){
				$permissions = $permissions->where(function($query) use($request){
					$query->orWhere('permission','LIKE','%'.$request->input('search.value').'%');
				});
			}
			$permissions= $permissions->paginate($request->input('length'));
			$returnPermissions = [];
			$data = [];
			$returnPermissions['draw'] = $request->input('draw');
			$returnPermissions['recordsTotal'] = $permissions->total();
			$returnPermissions['recordsFiltered'] = $permissions->total();
			foreach($permissions as $permission){
				$data[] = [
					$permission->permission,
					'<a href="genie/permission/update/'.$permission->id.'" title="Update"><i class="fa fa-refresh" aria-hidden="true"></i></a>'." | ".'<a href="javascript:permissionController.delete('.$permission->id.')" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>',
					
					 
				];
			}
			$returnPermissions['data'] = $data;
			return response()->json($returnPermissions);
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
    public function editPermission(Request $request, $id)
    {
		 if(Session::get('client.id')){
			$permission = Permission::find($id);
			 
			return view('genie.roles_permissions.permission_update',['permission'=>$permission,'id'=>$id]);			
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
    public function updatePermission(Request $request, $id)
    {  
		 if(Session::get('client.id')){
			$validator = Validator::make($request->all(),[
				'permission'=>'required|unique:permissions,permission,'.$id
			]);
			if($validator->fails()){
				return redirect('genie.permission/update/'.$id)
							->withErrors($validator)
							->withInput();
			}
			$permission = Permission::find($id);
			$permission->permission = $request->input('permission');
			if($permission->save()){
				$request->session()->flash('alert-success', 'Permission successful updated !!');
				 
				return redirect(url('genie/permission'));
			}else{
				$request->session()->flash('alert-danger', 'Permission not updated !!');
				return redirect(url('genie/permission/update/'.$id));			
			}			
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
    public function destroyPermission(Request $request, $id)
    {
		 if(Session::get('client.id')){
			try{
				$permission = Permission::findorFail($id);
				if($permission->delete()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Permission not found'],200);
			}
		}else{
				return redirect('/genie');
		}
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function rolePermissionIndex(Request $request){
		
		 if(Session::get('client.id')){
			$permissions = Permission::all();
			$roles = [
				'super_admin'=>'Super Admin',
				'administrator'=>'Administrator',
				'manager'=>'Manager',
				'user'=>'User',
			];
			
			return view('genie.roles_permissions.role_permission',['permissions'=>$permissions,'roles'=>$roles]);
		}else{
				return redirect('/genie');
		}
	}
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function rolePermissionStore(Request $request)
    {  
         if(Session::get('client.id')){
			$validator = Validator::make($request->all(),[
				'role'=>'required|unique:roles_permissions',
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
			
			$permission = DB::table('roles_permissions');
			$permission->role = $request->input('role');
			$permission->permissions = serialize($request->input('permission'));
			if($permission->save()){
				return response()->json(['status'=>1],200);
			}else{
				return response()->json(['status'=>0,'errors'=>'Permission not added'],400);
			}
		}else{
				return redirect('/genie');
		}
    }
	
    /**
     * Get paginated permissions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPaginatedRolesPermissions(Request $request)
    {
		 if(Session::get('client.id')){
		//	$permissions = RolePermission::orderBy('id','desc')->paginate($request->input('length'));
			$permissions = DB::table('roles_permissions')->orderBy('id','desc')->paginate($request->input('length'));
			$returnPermissions = [];
			$data = [];
			$returnPermissions['draw'] = $request->input('draw');
			$returnPermissions['recordsTotal'] = $permissions->total();
			$returnPermissions['recordsFiltered'] = $permissions->total();
	 
			foreach($permissions as $permission){
				$html = '';
				$permissionss = unserialize($permission->permissions);
				$i=1;
				foreach($permissionss as $p){
					$br = "";
					if($i%6==0)
						$br .= "<br>";
					$html .= "<span class='label label-default'>$p</span>&nbsp;&nbsp;".$br;
					++$i;
				}
				$data[] = [
					$permission->role,
					$html,
					'<a href="genie/role-permission/update/'.$permission->id.'" title="Update"><i class="fa fa-refresh" aria-hidden="true"></i></a>'." | ".'<a href="javascript:clientRolePermissionController.delete('.$permission->id.')" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a>'
				];
			}
			$returnPermissions['data'] = $data;
			return response()->json($returnPermissions);
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
    public function editRolePermission(Request $request, $id)
    {  
		 if(Session::get('client.id')){
			$permissions = Permission::all();
			$edit_permission = DB::table('roles_permissions')->find($id);
			 
			$roles = [
				'super_admin'=>'Super Admin',
				'administrator'=>'Administrator',
				'manager'=>'Manager',
				'user'=>'User',
			];
			 
			$sourcePermissions = "";
			$destinationPermissions = "";
			if(!is_null($edit_permission->permissions)){
				$rolePermissions = unserialize($edit_permission->permissions);
				 
					foreach($permissions as $permission){
						if(isset($rolePermissions) && in_array($permission->permission,$rolePermissions)){
							$destinationPermissions .="<option value=\"$permission->permission\" selected>$permission->permission</option>";
						}else{
							$sourcePermissions .="<option value=\"$permission->permission\">$permission->permission</option>";
							
						}
					}
			}else{
				foreach($permissions as $permission){
					$sourcePermissions .= "<option value=\"$permission->permission\">$permission->permission</option>";
				}
			}
			
			 
			return view('genie.roles_permissions.role_permission_update',['id'=>$id,'roles'=>$roles,'edit_permission'=>$edit_permission, 'users'=>['sourcePermissions'=>$sourcePermissions,'destinationPermissions'=>$destinationPermissions]]);
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
    public function updateRolePermission(Request $request, $id)
    { 
	
		//echo $id;echo "<pre>";print_r($_POST);die;
		
		 if(Session::get('client.id')){
			$validator = Validator::make($request->all(),[
				'role'=>'required|unique:roles_permissions,role,'.$id,
			]);
			if($validator->fails()){
				return redirect(url('genie/role-permission/update/'.$id))
							->withErrors($validator)
							->withInput();
			}
			$permission = DB::table('roles_permissions');
		 
			$permission->role = $request->input('role');
			$permission->permissions = serialize($request->input('permission'));
			
			$update = array(
			'role'=>$request->input('role'),
			'permissions'=>serialize($request->input('permission'))
			);
			if($permission->save()){
				$request->session()->flash('alert-success', 'Permission successful updated !!');
				return redirect(url('genie/role-permission'));
			}else{
				$request->session()->flash('alert-danger', 'Permission not updated !!');
				return redirect(url('genie/role-permission/update/'.$id));			
			}
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
    public function destroyRolePermission(Request $request, $id)
    {
		 if(Session::get('client.id')){
			try{
				$permission = DB::table('roles_permissions')->findorFail($id);
				if($permission->delete()){
					return response()->json(['status'=>1],200);
				}else{
					return response()->json(['status'=>0],400);
				}
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Permission not found'],200);
			}
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
    public function getRolePermissions(Request $request, $id)
    {   
		  
			try{
				$rolePermission = DB::table('roles_permissions')->where('role',$id)->first();
				$permissions = Permission::all();
				$rolePermissions = unserialize($rolePermission->permissions);
				$html = "";
			 
				foreach($permissions as $permission){
					if(in_array($permission->permission,$rolePermissions)){
						$html .= "<option value='$permission->permission' selected>$permission->permission</option>";
					}else{
						$html .= "<option value='$permission->permission'>$permission->permission</option>";
					}
				}
				return response()->json(['status'=>1,'html'=>$html],200);
			}catch(\Exception $e){
				return response()->json(['status'=>0,'errors'=>'Permission not found'],200);
			}
		 
    }
}
