@extends('layouts.cm_app')
@section('title')
     Batch
@endsection
@section('content')	
	<!-- page content -->
	 @if(Request::segment(3)=='addbatch'  || Request::segment(3)=='editbatch'  )
	 
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Add Create Batch</h3>
				 
				</div>
				
				<div class="title_right">
					<h3><a href="{{url('demo/batch/batch')}}" class="btn btn-primary" style="float: right;margin-right: 20px;">All Batch </a></h3>
				 
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<!--h2>Lead Form <small>(Add New Lead)</small></h2-->
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<!--form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" onsubmit="return leadController.submit(this)"-->
							<form class="form-horizontal form-label-left" onsubmit="return demoController.addbatchsubmit(this)">
								{{ csrf_field() }}
								 <input type="hidden" name="batchid" value="<?php echo  ((isset($edit_data)) ? $edit_data->id:""); ?>">
								<div class="form-group{{ $errors->has('batch') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="batch">Batch Name </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="batch" class="form-control col-md-6 col-xs-12" value="{{ old('batch',(isset($edit_data->batch)) ? $edit_data->batch:"")}}" placeholder="Enter Batch Neme">
										@if ($errors->has('batch'))
											<span class="help-block">
												<strong>{{ $errors->first('batch') }}</strong>
											</span>
										@endif
										 
									</div>
								</div>
								 	
						 

							<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Coordinator <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_user form-control" name="user" tabindex="-1">
										 <option value="">Please Coordinator</option>   
										@if(!empty($users))
												@foreach($users as $user)
													 
														<option value="{{ $user->name }}" @if ($user->name== old('user'))
									 selected="selected"	
									@else
									{{ (isset($edit_data) && $edit_data->user ==$user->name   ) ? "selected":"" }} @endif>{{ $user->name }}</option>
													 
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Trainer <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_trainer form-control" name="trainer" tabindex="-1">
										 <option value="">Please Trainer</option>   
										@if(!empty($trainers))
												@foreach($trainers as $trainer)
												 
														<option value="{{ $trainer->name }}" @if ( old('trainer')==$trainer->name)
									selected="selected"	
									@else
									{{ (isset($edit_data) && $edit_data->trainer == $trainer->name ) ? "selected":"" }} @endif>{{ $trainer->name }}</option>
													 
												@endforeach
											@endif
										</select>
									</div>
								</div>
								
							 
								 	 
							  
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button class="btn btn-primary" type="reset">Reset</button>
										<button type="submit" class="btn btn-success">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>
	</div>
		@else
			<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>All Batch </h3>
				</div>
				
				<div class="title_right">
					<h3><a href="{{url('demo/batch/addbatch')}}" class="btn btn-primary" style="float: right;margin-right: 20px;">Add Batch </a></h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="alert alert-danger hide"></div>
					<div class="alert alert-success hide"></div>
					@if(Session::has('alert-success'))
						<div class="alert alert-success">{{Session::get('alert-success')}}</div>
					@endif
					<div class="x_panel">
						 
						 <style>
						.check-box{
							height:18px;
							width:20px;
							cursor:pointer;
						}
						</style>
						<div class="x_content">
							<table id="datatable-batch" class="table table-striped table-bordered">
								<thead>
									<tr>
										 
										
										<th><input type="checkbox" id="check-all" class="check-box"></th>
										<th>Batch Name</th>
										<th>Coordinator</th>
										<th>Trainer</th>										 
										<th>Action</th>
									</tr>
								</thead>
							</table>
						 
						</div>
					</div>
				</div>
			</div>
		</div>
 
	 
	</div>
	
	
	@endif
	<!-- /page content -->
@endsection