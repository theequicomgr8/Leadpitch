@extends('layouts.cm_app')
@section('title')
     Client List  
@endsection
@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Profile Edit</h3>
					
				</div>
			</div>
		
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<h2>User Form <small>(Edit Profile)</small></h2>	
							@if(Session::has('success')) 	
									<div class="row">
									<div class="col-md-4 col-md-offset-1">
									<div class="alert alert-success">
									<strong>{{Session::get('success')}}.</strong> 
									</div>
									</div>
									</div>
									@endif
									@if(Session::has('failed')) 	
									<div class="row">
									<div class="col-md-4 col-md-offset-1">
									<div class="alert alert-danger">
									<strong>{{Session::get('failed')}}.</strong> 
									</div>
									</div>
									</div>
							@endif							
							<div class="clearfix"></div>
						</div>
						 
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" role="form" method="POST" action="{{ url('/genie/profile') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="name" type="text" name="name" class="form-control col-md-7 col-xs-12" value="{{ old('name',(isset($edit_data)) ? $edit_data->name:"")}}">
										
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
										<input id="email" type="email" class="form-control col-md-7 col-xs-12" name="email" value="{{ old('email',(isset($edit_data)) ? $edit_data->email:"")}}">
										
										@if ($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
										@endif
									</div>
								</div>
								 <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control col-md-7 col-xs-12" name="mobile" value="{{ old('mobile',(isset($edit_data)) ? $edit_data->mobile:"")}}">
										
										@if ($errors->has('mobile'))
											<span class="help-block">
												<strong>{{ $errors->first('mobile') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Profile Pic</label>

								<div class="col-lg-6 col-sm-6 col-xs-12">	 
 
								<div class="image">

								@if(isset($edit_data) && $edit_data->image !='')									 

								<div >
								<img src="{{ URL::asset('/upload/'.$edit_data->image)}}" style="max-width:100px;" height="100" width="100">	
								<a href="client/profile/del_icon/{{$edit_data->id}}" class="btn btn-inverse btn-circle m-b-5 deleteIcon"><i class="glyphicon glyphicon-trash"></i></a>

								</div>
								@else											 
								<input type="file" dir="auto" name="image" accept="image/*">


								@endif

								@if ($errors->has('image'))
								<small class="error alert-danger">{{ $errors->first('image') }}</small>
								@endif
								</div>
								</div>

								</div>

							 
							 
							 
								<div class="ln_solid"></div>
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
