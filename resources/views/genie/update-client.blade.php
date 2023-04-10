@extends('layouts.cm_app')
@section('title')
     Client Update  
@endsection
@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update Client</h3>
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
						 
							<h2>Client Form <small>(Update Client)</small></h2>
							<!--<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#">Settings 1</a>
										</li>
										<li><a href="#">Settings 2</a>
										</li>
									</ul>
								</li>
								<li><a class="close-link"><i class="fa fa-close"></i></a>
								</li>
							</ul>-->
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" role="form" method="POST" action="genie/update/<?php echo base64_encode($edit_data->client_id) ?>">
								{{ csrf_field() }}
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company Id<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										 <label class="control-label">
										<?php echo (isset($edit_data)) ? $edit_data->company_id:""; ?>
										 </label>
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="name" class="form-control col-md-7 col-xs-12" value="{{ old('name',(isset($edit_data)) ? $edit_data->name:"")}}" placeholder="Company Name" autocomplete="off">
										
										@if ($errors->has('name'))
											<span class="help-block">
												<strong>{{ $errors->first('name') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Mobile<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="mobile" class="form-control col-md-7 col-xs-12" value="{{ old('mobile',(isset($edit_data)) ? $edit_data->mobile:"")}}" placeholder="Mobile" autocomplete="off">
										
										@if ($errors->has('mobile'))
											<span class="help-block">
												<strong>{{ $errors->first('mobile') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="email" class="form-control col-md-7 col-xs-12" name="email" value="{{ old('email',(isset($edit_data)) ? $edit_data->email:"")}}" placeholder="Email" autocomplete="off">
										
										@if ($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('from') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">From Date</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control col-md-7 col-xs-12 fromDate" name="from" value="{{ old('from',(isset($edit_data)) ? $edit_data->from:"")}}" placeholder="From date" autocomplete="off">
										
										@if ($errors->has('from'))
											<span class="help-block">
												<strong>{{ $errors->first('from') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('to') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">To Date</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" class="form-control col-md-7 col-xs-12 toDate" name="to" value="{{ old('to',(isset($edit_data)) ? $edit_data->client_to:"")}}" placeholder="To date" autocomplete="off">
										
										@if ($errors->has('to'))
											<span class="help-block">
												<strong>{{ $errors->first('to') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="password" class="form-control col-md-7 col-xs-12" dir="auto" name="password" placeholder="******" autocomplete="off">
										
										@if ($errors->has('password'))
											<span class="help-block">
												<strong>{{ $errors->first('password') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('confirmpassword') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password-confirm">Confirm Password </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password-confirm" type="password" class="form-control col-md-7 col-xs-12" name="confirmpassword" placeholder="*******" autocomplete="off">
										
										@if ($errors->has('confirmpassword'))
											<span class="help-block">
												<strong>{{ $errors->first('confirmpassword') }}</strong>
											</span>
										@endif
									</div>
								</div>
								  
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button class="btn btn-primary" type="reset">Reset</button>
										<button type="submit" class="btn btn-success" value="clientUpdate" name="client"><i class="fa fa-btn fa-user"></i> Submit</button>
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
