@extends('layouts.cm_app')
 @section('title')
     Update Lead  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update  Trainer</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							@foreach (['danger', 'warning', 'success', 'info'] as $msg)
								@if(Session::has('alert-' . $msg))
									<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
								@endif
							@endforeach
							@if (count($errors) > 0)
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
							<h2>Update Form <small>(Update Trainer)</small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form action="/trainer/update/<?php echo $edit_data->id ?>" class="form-horizontal form-label-left" method="POST">
									{{ csrf_field() }}
								 <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="name" class="form-control col-md-7 col-xs-12" value="{{ old('name',(isset($edit_data->name)) ? $edit_data->name:"")}}" placeholder="Enter Name" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="email" class="form-control col-md-7 col-xs-12"  value="{{ old('email',(isset($edit_data->email)) ? $edit_data->email:"")}}" placeholder="Enter Email" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="mobile" class="form-control col-md-7 col-xs-12"  value="{{ old('mobile',(isset($edit_data->mobile)) ? $edit_data->mobile:"")}}" placeholder="Enter Mobile" autocomplete="off">
									</div>
								</div>
							 
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Course <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_course form-control sms-control" name="course" tabindex="-1">
											<option>-- SELECT COURSE --</option>
											@if(count($courses)>0)
												@foreach($courses as $course)
													@if($course->id == $edit_data->course)
														<option value="{{ $course->id }}" selected>{{ $course->name }}</option>
													@else
														<option value="{{ $course->id }}">{{ $course->name }}</option>
													@endif
												@endforeach
											@endif
										</select>
									</div>
								</div>
								 
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sub-course">Skills</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea type="text" name="sub-course" class="form-control col-md-7 col-xs-12" placeholder="Enter Skills">{{ old('sub-course', (isset($edit_data->sub_courses)) ? $edit_data->sub_courses:"")}}</textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Work Day <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
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
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Training<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
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
									</div>
								</div>
								
								 
							 
							 
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
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
	<!-- /page content -->
@endsection