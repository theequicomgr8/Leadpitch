@extends('layouts.cm_app')
 @section('title')
     Register  
@endsection
@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>New User</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
								@if(Session::has('success'))
								<div class="alert alert-success">{{Session::get('success')}}</div>
								@endif
						 
							<h2>User Form <small>(Add New User)</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" role="form" method="POST" action="{{ url('/register') }}">
								{{ csrf_field() }}
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="name" type="text" name="name" class="form-control col-md-7 col-xs-12" placeholder="Enter Name" value="{{ old('name') }}" autocomplete="off">
										
										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="email" type="email" class="form-control col-md-7 col-xs-12" name="email" placeholder="Enter Email" value="{{ old('email') }}" autocomplete="off">
										
										@if ($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password" type="password" class="form-control col-md-7 col-xs-12" placeholder="*******" name="password" autocomplete="off">
										
										@if ($errors->has('password'))
											<span class="help-block">
												<strong>{{ $errors->first('password') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password-confirm">Confirm Password <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password-confirm" type="password" class="form-control col-md-7 col-xs-12" placeholder="******" name="password_confirmation" autocomplete="off">
										
										@if ($errors->has('password_confirmation'))
											<span class="help-block">
												<strong>{{ $errors->first('password_confirmation') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Role <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select id="role" class="form-control col-md-7 col-xs-12 rolemanage" name="role">
										<option value="">Select Role</option>  
												@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator'))
											<option value="administrator" @if ("administrator"== old('role')) selected="selected" @endif>Administrator</option>											 
											<option value="manager" @if ("manager"== old('role')) selected="selected" @endif>Manager</option>
											<option value="user" @if ("user"== old('role')) selected="selected" @endif>User</option>
											@else												
											<option value="manager" @if ("manager"== old('role')) selected="selected" @endif>Manager</option>
											<option value="user" @if ("user"== old('role')) selected="selected" @endif>User</option>
											
											@endif
										</select>
										
										@if ($errors->has('role'))
											<span class="help-block">
												<strong>{{ $errors->first('role') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('capabilities') ? ' has-error' : '' }}">
									  
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="capabilities">Capabilities <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select id="capabilities" class="form-control chosen-select col-md-7 col-xs-12" name="capabilities[]" multiple>
										 
										</select>
										@if ($errors->has('capabilities'))
											<span class="help-block">
												<strong>{{ $errors->first('capabilities') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group manager" style="display:none">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="manager">Manager</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
								
									<select class="form-control col-md-7 col-xs-12" name="manager">
									<option value="">Select Manager1</option>
										<?php 
										$managers = DB::table(Session::get('company_id').'_capabilities')->select('user_id')->get();
										 
											  if(!empty($managers)){
												  
												foreach($managers as $manage){					
												$userName= DB::table(Session::get('company_id').'_users')->where('id',$manage->user_id)->where('role','manager')->first(); 
										 if(!empty($userName)){
										   
											  ?>
												  <option value="<?php echo $userName->id ?>" @if ($userName->id== old('manager')) selected="selected"	@endif><?php echo $userName->name; ?></option>
											<?php  }
											
											  }
										  }
										?>
										
									 
										</select>
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button class="btn btn-primary" type="reset">Reset</button>
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
