@extends('layouts.cm_app')
@section('title')
     Trainer Required 
@endsection
@section('content')		
	<div class="right_col" role="main">
		<div class="">


			@if(Session::has('success')) 	
				<div class="row">
				<div class="col-md-8 col-md-offset-1">
				<div class="alert alert-success">
				<strong> {{Session::get('success')}}</strong>
				</div>
				</div>
				</div>
				@endif
				@if(Session::has('failed')) 	
				<div class="row">
				<div class="col-md-8 col-md-offset-1">
				<div class="alert alert-danger">
				<strong>{{Session::get('failed')}}</strong>
				</div>
				</div>
				</div>
				@endif
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">		
				<div class="page-title">
				<div class="title_left">
				<h3><a href="trainerrequired/all-trainer">Required Trainer</a></h3>

				</div>
				<div class="page-title">
				<div class="add_right">
					<h3><a href="trainerrequired/add" class="btn btn-info"> + Add</a></h3>	
 
 				
				</div>
				</div>
				</div>				
				</div>
				
				</div>
		
			<div class="clearfix"></div>
			
			 
			@if(Request::segment(2)=='add'  || Request::segment(2)=='edit'  )
			
		<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<h2>Trainer Required Form</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							 
					 <form action="" method="POST" enctype="multipart/form-data"  class="form-horizontal form-label-left" autocomplete="off">
						{{ csrf_field() }}
				 				  
			 
				 
				<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Technology<span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							 <input type="text" class="form-control" name="technology" value="{{ old('technology',(isset($edit_data->technology)) ? $edit_data->technology:"")}}" placeholder="Enter Technology" >
							 @if ($errors->has('technology'))
								<small class="error alert-danger">{{ $errors->first('technology') }}</small>
								@endif
						</div>
					</div>	
					
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Work Day <span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<select class="form-control" name="workday" tabindex="-1">
								 
							<option value="">Select Work Day</option>
							<option value="Week Day (Part Time)" @if ("Week Day (Part Time)"== old('workday'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->workday =="Week Day (Part Time)" ) ? "selected":"" }} @endif>Week Day (Part Time)</option>
							<option value="Week End (Part Time)" @if ("Week End (Part Time)"== old('workday'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->workday =="Week End (Part Time)" ) ? "selected":"" }} @endif>Week End (Part Time)</option>

							<option value="Week Day + Week End (Part Time)" @if ("Week Day + Week End (Part Time)"== old('workday'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->workday =="Week Day + Week End (Part Time)" ) ? "selected":"" }} @endif>Week Day + Week End (Part Time)</option>
						
							<option value="Full Time" @if ("Full Time"== old('workday'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->workday =="Full Time" ) ? "selected":"" }} @endif>Full Time</option>
									 
							</select>
							 @if ($errors->has('workday'))
								<small class="error alert-danger">{{ $errors->first('workday') }}</small>
								@endif
						</div>
					</div>	
					
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Training <span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<select class="form-control" name="training" tabindex="-1">
								 
							<option value="">Select Training</option>
							<option value="Online Training"  @if ("Online Training"== old('training'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->training =="Online Training" ) ? "selected":"" }} @endif>Online Training</option>
							<option value="Class Room Training"  @if ("Class Room Training"== old('training'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->training =="Class Room Training" ) ? "selected":"" }} @endif>Class Room Training</option>
						
							<option value="Online + Class Room Training"  @if ("Online + Class Room Training"== old('training'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->training =="Online + Class Room Training" ) ? "selected":"" }} @endif>Online + Class Room Training</option>	

							<option value="Corporate Training"  @if ("Corporate Training"== old('training'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->training =="Corporate Training" ) ? "selected":"" }} @endif>Corporate Training</option>										 
									 
							</select>
							 @if ($errors->has('training'))
								<small class="error alert-danger">{{ $errors->first('training') }}</small>
								@endif
						</div>
					</div>	
					
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Skills <span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<textarea class="form-control" name="skills" placeholder="Enter Skills">{{ old('skills',(isset($edit_data->skills)) ? $edit_data->skills:"")}}</textarea>
							 @if ($errors->has('skills'))
								<small class="error alert-danger">{{ $errors->first('skills') }}</small>
								@endif
						</div>
					</div>
					
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Coures Content</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<textarea class="form-control" name="coursecontent" placeholder="Enter Coures Content"> {{ old('coursecontent',(isset($edit_data->coursecontent)) ? $edit_data->coursecontent:"")}}</textarea>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Remark</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<textarea class="form-control" name="remarks" placeholder="Enter Remark"> {{ old('remarks',(isset($edit_data->remarks)) ? $edit_data->remarks:"")}}</textarea>
						</div>
					</div>
					 
					 
 
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								 
								<button type="submit" class="btn btn-success" name="submit" value="<?php echo $button; ?>">Submit</button>
							</div>
						</div>
					</form>

								 
						</div>
					</div>
				</div>
			</div>
		
		@else
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="alert alert-danger hide"></div>
					<div class="alert alert-success hide"></div>
					@if(Session::has('alert-success'))
						<div class="alert alert-success">{{Session::get('alert-success')}}</div>
					@endif
					<div class="x_panel">
						 
						<div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
							<form method="GET" action="" novalidate autocomplete="off">
						 
							  
								<div class="form-group">
									<div class="col-md-3">
										<label>Date From</label>
										<input type="text" class="form-control leaddf" name="search[leaddf]" value="{{ $search['leaddf'] or "" }}" placeholder="Date From">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Date To</label>
										<input type="text" class="form-control leaddt" name="search[leaddt]" value="{{ $search['leaddt'] or "" }}" placeholder="Date To">
									</div>
								</div>
						    	<div class="form-group">
									<div class="col-md-3">
									<label></label>
								 <button type="submit" class="btn btn-block btn-info">Filter</button>
								</div>
								</div>
								 
								
							</form>
						</div>
						<style>
						.check-box{
							height:18px;
							width:20px;
							cursor:pointer;
						}
						</style>
						<div class="x_content" id="employee_table">
							<table id="datatable-trainerrequired" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th><input type="checkbox" id="check-all" class="check-box"></th>
										<th>Technology</th>
										<th>Training</th>
										<th>Work Day</th>
										<th>Skills</th>										 
										<th>Date</th>										 
										<th>Action</th>
									</tr>
								</thead>
							</table>
							 
							<form id="export-trainerrequired" method="POST" onsubmit="return exporttrainerrequired()" action="{{ url('/trainerrequired/gettrainerrequiredexcel') }}">
								{{ csrf_field() }}
								 
								<input type="hidden" name="search[leaddf]" value="">
								<input type="hidden" name="search[leaddt]" value="">								 
								<input type="hidden" name="search[value]" value="">
								<!--<div class="form-group">
									<div class="col-md-2">
										<button type="button" class="btn btn-success btn-block bulk-sms" onclick="javascript:leadController.bulkSms()" title="Send Bulk SMS">Bulk SMS</button>
									</div>
								</div>-->
								@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<div class="form-group">
									<div class="col-md-2">									 
										<button type="submit" class="btn btn-success  btn-block export-leads">Export</button>							
										
									</div>
								</div>
									@endif
									
									 
								@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator' )
								<div class="form-group">
								<div class="col-md-2">							
								 
								<button type="button" class="btn btn-success  btn-block" onclick="javascript:trainerRequiredController.selectDelete()" >Delete</button>
								

								</div>
								</div>
								@endif
								 
								 
								 
							</form>

						</div>
					</div>
				</div>
			</div>
			@endif
			
			
		</div>
		<style>
			#followUpModal .form-control{
				margin-bottom:10px;
			}
		</style>
		
 
		<div id="followUpModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Follow Up</h4>
					</div>
					<div class="modal-body" style="padding-top:0">
					</div>
					<!--div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div-->
				</div>

			</div>
		</div>
		<div id="bulkSmsModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Bulk SMS</h4>
					</div>
					<div class="modal-body">
						<textarea placeholder="Enter your custom message here..." id="bulkSmsControl" rows="10" class="form-control"></textarea>
						<div class="form-group" style="padding-top:20px;margin-bottom:0">
							<div class="col-md-3" style="margin:0 auto;float:none;">
								<button type="button" style="background-color:#169f85;color:#fff;" onclick="javascript:leadController.sendBulkSms()" class="btn btn-block">Submit</button>
							</div>
							<div class="clearfix"></div>
						</div>
						
					</div>
					<!--div class="modal-footer">
						<button type="button" class="btn btn-default" >Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div-->
				</div>

			</div>
		</div>
	</div>
 
	<!-- /page content -->
@endsection