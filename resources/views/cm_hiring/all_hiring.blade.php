@extends('layouts.cm_app')
@section('title')
     Hiring Employee 
@endsection
@section('content')	
<style>
#success{	
	color:green;font-size: 20px;
}
#failed{
	color:red;
	
}
</style>
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
				<div class="col-md-8 col-md-offset-1">
				<div id="success"> </div><div id="failed"> </div>
				</div>
				</div>
				
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">		
				<div class="page-title">
				<div class="title_left">
				<h3><a href="hiring/all-hiring">Hiring Employee</a></h3>

				</div>
				<div class="page-title">
				<div class="add_right">
					<h3><a href="hiring/add" class="btn btn-info"> + Add</a></h3>	
 
 				
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
							<h2>Hiring Form</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							 
					 <form action="" method="POST" enctype="multipart/form-data"  class="form-horizontal form-label-left" autocomplete="off">
						{{ csrf_field() }}
				 				  
					<div class="form-group row">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Hiring Type<span class="required">*</span></label>
					<div class="col-md-7 col-sm-7 col-xs-12">
					<select class="form-control" name="hiring_type" id="hiring_type" tabindex="-1">

					<option value="">Select Hiring</option>
					<option value="Employee"  @if ("Employee"== old('hiring_type'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->hiring_type =="Employee" ) ? "selected":"" }} @endif>Employee</option>
					<option value="Trainer"  @if ("Trainer"== old('hiring_type'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->hiring_type =="Trainer" ) ? "selected":"" }} @endif>Trainer</option>

					</select>
					</div>
					</div>
				 
				
				<div class="trainer">
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
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Skills</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<textarea class="form-control" name="skills" placeholder="Enter Skills">{{ old('skills',(isset($edit_data->skills)) ? $edit_data->skills:"")}}</textarea>
							 @if ($errors->has('skills'))
								<small class="error alert-danger">{{ $errors->first('skills') }}</small>
								@endif
						</div>
					</div>
					
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Training Mode<span class="required">*</span></label>
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
					</div>
					<div class="employee">
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Profile Postion<span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							 <input type="text" class="form-control" name="position" value="{{ old('position',(isset($edit_data->position)) ? $edit_data->position:"")}}" placeholder="Enter Profile Postion" >
							 @if ($errors->has('position'))
								<small class="error alert-danger">{{ $errors->first('position') }}</small>
								@endif
						</div>
					</div>
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Salary Package <span class="required">*</span></label>
						<div class="col-md-3 col-sm-3 col-xs-12">
							 
							<select class="form-control" name="salaryfrom" tabindex="-1">
							  <?php for($i=10000;$i<20000; $i+=5000){?>
							<option value="<?php echo $i; ?>"  @if ($i== old('salaryfrom'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->salaryfrom ==$i ) ? "selected":"" }} @endif><?php echo $i; ?></option>
							 <?php } ?>
							 </select>
						</div>
						
						<div class="col-md-1 col-sm-1 col-xs-12">To</div>
						<div class="col-md-3 col-sm-3 col-xs-12">							
							<select class="form-control" name="salaryto" tabindex="-1">
							  <?php for($i=10000;$i<=100000; $i+=5000){?>
							<option value="<?php echo $i; ?>"  @if ($i== old('salaryto'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->salaryto ==$i ) ? "selected":"" }} @endif><?php echo $i; ?></option>
							 <?php } ?>
							 </select>
						</div>
					</div>
					</div>
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Experience</label>
						<div class="col-md-3 col-sm-3 col-xs-12">
						<select class="form-control" name="experiencefrom" tabindex="-1">
							 <option value="">Select Experience</option>
							 <?php for($i=0;$i<20; $i++){?>
							<option value="<?php echo $i; ?>"  @if ($i== old('experiencefrom'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->experiencefrom ==$i ) ? "selected":"" }} @endif><?php echo $i; ?></option>
							 <?php } ?>
							</select>
						</div>
						
						<div class="col-md-1 col-sm-1 col-xs-12">To</div>
						<div class="col-md-3 col-sm-3 col-xs-12">
						<select class="form-control" name="experienceto" tabindex="-1">
							 <option value="">Select Experience</option>
							 <?php for($i=2;$i<20; $i++){?>
							<option value="<?php echo $i; ?>" @if ($i== old('experienceto'))
							selected="selected"	
							@else
							{{ (isset($edit_data) && $edit_data->experienceto ==$i ) ? "selected":"" }} @endif><?php echo $i; ?></option>
							 <?php } ?>
							</select>
						</div>
					</div>	
					 	
					
					  
					
					 
					 
				
					
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Type <span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<select class="form-control" name="workday" tabindex="-1">
								 
							<option value="">Select Type</option>
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
						<div class="x_content" id="hiring_table">
							<table id="datatable-hiring" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th><input type="checkbox" id="check-all" class="check-box"></th>
										<th>Date</th>	
										<th>Type </th>	
										<th>Profile/Technology </th>	
										<th>Experience</th>	
										<th>Work Days</th>	
										<th>Training Mode</th>	
										<th>Skills</th>	
										<th>Create By</th>																		 
										<th class="text-center">Status</th>							 						 
										<th>Action</th>
									</tr>
								</thead>
							</table>
							 
						

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
		
 
	
	
	</div>
  
	<!-- /page content -->
@endsection