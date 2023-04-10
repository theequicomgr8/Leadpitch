@extends('layouts.cm_app')
@section('title')
     Change Password  
@endsection
@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Change Password</h3>
					
				</div>
			</div>
			@if(Session::has('success')) 	
				<div class="row">
				<div class="col-md-4 col-md-offset-4">
				<div class="alert alert-success">
				<strong>{{Session::get('success')}}.</strong> 
				</div>
				</div>
				</div>
				@endif
				@if(Session::has('failed')) 	
				<div class="row">
				<div class="col-md-4 col-md-offset-4">
				<div class="alert alert-danger">
				<strong>{{Session::get('failed')}}.</strong> 
				</div>
				</div>
				</div>
			@endif
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<h2>Change Password Form</h2>							 
							<div class="clearfix"></div>
						</div>
						 
						<div class="x_content">							 
						<form class="form-horizontal" action="{{url('/genie/changepassword')}}" method="post" enctype="multipart/form-data" >
				 					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Old Password</label>
						<div class="col-lg-6 col-sm-6 col-xs-12">
					 	{{ csrf_field()}}
							<input type="password" name="oldpwd" class="form-control" value="" placeholder="Old Password" autocomplete="off">
							 @if ($errors->has('oldpwd'))
								<small class="error alert-danger">{{ $errors->first('oldpwd') }}</small>
								@endif
						</div>
					</div>
 
					<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">New Password</label>
						
						   <div class="col-lg-6 col-sm-6 col-xs-12">	          
							<div class="image">
								 
								<input type="password" name="newpwd" class="form-control" value="" placeholder="New Password" >
								@if ($errors->has('newpwd'))
								<small class="error alert-danger">{{ $errors->first('newpwd') }}</small>
								@endif
							  </div>
							</div>

					</div>
					<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Confirm Password</label>
						
						   <div class="col-lg-6 col-sm-6 col-xs-12">	          
							<div class="image">
								 
								<input type="password" name="cpwd" class="form-control" value="" placeholder="Confirm Password">
								@if ($errors->has('cpwd'))
								<small class="error alert-danger">{{ $errors->first('cpwd') }}</small>
								@endif
							  </div>
							</div>

					</div>
						<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<button class="btn btn-primary" type="reset">Reset</button>
						<button type="submit" name="submit" class="btn btn-success" value="Update"><i class="fa fa-btn fa-user"></i> Submit</button>
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

		

 