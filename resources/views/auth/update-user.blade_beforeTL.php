@extends('layouts.cm_app')
 @section('title')
     Update  
@endsection
@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update User - {{ $user->name }}</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="alert alert-danger hide"></div>
						@if(Session::has('success'))
						<div class="alert alert-success">{{Session::get('success')}}</div>
						@endif
						 
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" role="form" method="POST" action="">
								{{ csrf_field() }}
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="name" type="text" name="name" class="form-control col-md-7 col-xs-12" value="{{ $user->name }}">
										
										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email<span class="required">*</span> </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="email" type="email" class="form-control col-md-7 col-xs-12" name="email" value="{{ $user->email }}">
										
										@if ($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password" type="password" class="form-control col-md-7 col-xs-12" name="password">
										
										@if ($errors->has('password'))
											<span class="help-block">
												<strong>{{ $errors->first('password') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password-confirm">Confirm Password </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password-confirm" type="password" class="form-control col-md-7 col-xs-12" name="password_confirmation">
										
										@if ($errors->has('password_confirmation'))
											<span class="help-block">
												<strong>{{ $errors->first('password_confirmation') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group {{ $errors->has('role') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Role<span class="required">*</span> </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select id="role" class="form-control col-md-7 col-xs-12" <?php echo (Auth::user()->current_user_can('super_admin')||Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('manager') )?"name=\"role\"":"disabled"; ?>>
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator'))
											<?php
											$roles = [		
											 										
												"administrator"=>"Administrator",
												"manager"=>"Manager",
												"user"=>"User"
											];
											?>
											@else
												<?php
											$roles = [		
												 
												"manager"=>"Manager",
												"user"=>"User"
											];
											?>
											
											
											@endif
											<option value="">Select Role</option>
											@foreach($roles as $key=>$value)
												<option value="{{ $key }}" <?php echo ($key==$user->role)?"selected":""; ?>>{{ $value }}</option>
											@endforeach
										</select>
										 
									</div>
								</div>
								<div class="form-group{{ $errors->has('capabilities') ? ' has-error' : '' }}"> 
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="capabilities">Capabilities<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<?php
										
											$capabilities = getCapabilities();
									
									
										$html = "";
										foreach($permissions as $permission){
											$selected = "";
											if(in_array($permission->permission,$userCaps,TRUE)){
												$selected = "selected";
											}
											$html.="<option value='$permission->permission' $selected>$permission->permission</option>";
										}
										?>
										<select id="capabilities" class="form-control chosen-select col-md-7 col-xs-12" multiple <?php echo (Auth::user()->current_user_can('super_admin')||Auth::user()->current_user_can('administrator') ||Auth::user()->current_user_can('manager'))?"name=\"capabilities[]\"":"disabled"; ?>>
										<?php echo $html; ?>
										</select>
										@if ($errors->has('capabilities'))
											<span class="help-block">
												<strong>{{ $errors->first('capabilities') }}</strong>
											</span>
										@endif
									</div>
								</div>
							 
								<div class="form-group manager" style="<?php echo ((isset($user->role) && ($user->role=="user" || $user->role=="manager"))?"":"display:none") ?>">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="manager">Manager</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
											 
									<select class="form-control col-md-7 col-xs-12" <?php echo (Auth::user()->current_user_can('super_admin')||Auth::user()->current_user_can('administrator') ||Auth::user()->current_user_can('manager'))?"name=\"manager\"":"disabled"; ?>>
											<option value="">Select Manager</option>	
										<?php
									
										  //$managers = DB::table(Session::get('company_id').'_users')->where('role','manager')->get();
									 $managers = DB::table(Session::get('company_id').'_users')->where('role','!=','user')->get();
										  if(!empty($managers)){
											  foreach($managers as $manager){ ?>
											  
												  <option value='<?php echo $manager->id ?>' <?php if(isset($edit_data) && $edit_data->manager== $manager->id){ echo "selected"; }?>><?php echo $manager->name.' ('.$manager->role.')';  ?></option>
											<?php  }
										  }
										?>
										
									 
										</select>
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button type="submit" class="btn btn-success"><i class="fa fa-btn fa-user"></i> Submit</button>
									</div>
								</div>
								
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection
