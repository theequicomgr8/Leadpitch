@extends('layouts.cm_app')
@section('title')
     Client register  
@endsection
@section('content')
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>New Client</h3>
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
						 
							<h2>Client Form <small>(Add New User)</small></h2>
							 
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" role="form" method="POST" action="{{ url('/genie/client') }}" autocomplete="off">
								{{ csrf_field() }}
								<div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company Id<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="company_id" class="form-control col-md-7 col-xs-12" value="{{ old('company_id') }}" placeholder="Company Id" autocomplete="off">
										
										@if ($errors->has('company_id'))
											<span class="help-block">
												<strong>{{ $errors->first('company_id') }}</strong>
											</span>
										@endif
										<span class="alert-danger">
												Not allow space,underscore and numeric value, only character min:2,max:8 
										</span>
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Company Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="name" class="form-control col-md-7 col-xs-12" value="{{ old('name') }}" placeholder="Company Name" autocomplete="off">
										
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
										<input type="text" name="mobile" class="form-control col-md-7 col-xs-12" value="{{ old('mobile') }}" placeholder="Mobile" autocomplete="off">
										
										@if ($errors->has('mobile'))
											<span class="help-block">
												<strong>{{ $errors->first('mobile') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="email" class="form-control col-md-7 col-xs-12" name="email" value="{{ old('email') }}" placeholder="Email" autocomplete="off">
										
										@if ($errors->has('email'))
											<span class="help-block">
												<strong>{{ $errors->first('email') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('from') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="from">From Date<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input autocomplete="off" type="text" class="form-control col-md-7 col-xs-12 fromDate" name="from" value="{{ old('from') }}" placeholder="From date" >
										
										@if ($errors->has('from'))
											<span class="help-block">
												<strong>{{ $errors->first('from') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('to') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">To Date<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
						<input autocomplete="off" type="text" class="form-control col-md-7 col-xs-12 toDate" dir="auto" name="to" value="{{ old('to') }}" placeholder="To date" onfocus="this.placeholder = ''">
										
										@if ($errors->has('to'))
											<span class="help-block">
												<strong>{{ $errors->first('to') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="password" class="form-control col-md-7 col-xs-12" dir="auto" name="password"  autocomplete="off">
										
										@if ($errors->has('password'))
											<span class="help-block">
												<strong>{{ $errors->first('password') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('confirmpassword') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="password-confirm">Confirm Password<span class="required">*</span> </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input id="password-confirm" type="password" class="form-control col-md-7 col-xs-12" name="confirmpassword" autocomplete="off">
										
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
										<button type="submit" class="btn btn-success" value="client" name="client"><i class="fa fa-btn fa-user"></i> Submit</button>
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
