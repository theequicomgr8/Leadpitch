@extends('layouts.cm_app')
 @section('title')
     Update content  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update Content</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
					 
							@if(Session::has('alert-danger'))
						<div class="alert alert-danger">{{Session::get('alert-danger')}}</div>
						@endif
							 
					 
						<div class="x_content">
							<br />
							<form action="/content/update/<?php echo $edit_data->id ?>" class="form-horizontal form-label-left" method="POST">
								
									{{ csrf_field() }}
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Course <span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input type="text" name="course" value="{{ old('course',(isset($edit_data)) ? $edit_data->course:"")}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Course" autocomplete="off">
										@if ($errors->has('course'))
										<small class="error alert-danger">{{ $errors->first('course') }}</small>
										@endif
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="email">Introduction </label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea type="text" name="introduction" class="form-control col-md-7 col-xs-12 textarea" placeholder="Enter Introduction" autocomplete="off">{{ old('introduction',(isset($edit_data)) ? $edit_data->introduction:"")}}</textarea>
										@if ($errors->has('introduction'))
										<small class="error alert-danger">{{ $errors->first('introduction') }}</small>
										@endif
									</div>
								</div>
							 
							 <div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="mobile">Module Description </label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea type="text" name="modulesdescription" class="form-control col-md-7 col-xs-12" placeholder="Enter Module Description" autocomplete="off">{{ old('modulesdescription',(isset($edit_data)) ? $edit_data->modulesdescription:"")}}</textarea>
									</div>
								</div> 
						 
							 
							 
								 
								 
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Duration</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="duration" value="{{ old('duration',(isset($edit_data)) ? $edit_data->duration:"")}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Duration" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Certification</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea name="certification" rows="4" class="form-control col-md-7 col-xs-12" placeholder="Enter Certification" autocomplete="off">{{ old('certification',(isset($edit_data)) ? $edit_data->certification:"")}}</textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Live Project</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea name="liveproject" rows="1" class="form-control col-md-7 col-xs-12" placeholder="Enter Live Project" autocomplete="off">{{ old('liveproject',(isset($edit_data)) ? $edit_data->liveproject:"")}}</textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Training Mode</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="trainingmode" value="{{ old('trainingmode',(isset($edit_data)) ? $edit_data->trainingmode:"")}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Training Mode" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Demo Timing</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="demotimig" value="{{ old('demotimig',(isset($edit_data)) ? $edit_data->demotimig:"")}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Demo Timing" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">About Trainer</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea name="abouttrainer" class="form-control col-md-7 col-xs-12" placeholder="Enter About Trainer" autocomplete="off">{{ old('abouttrainer',(isset($edit_data)) ? $edit_data->abouttrainer:"")}}</textarea>
									</div>
								</div>							 
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Placement Tie-Up</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="placementtieup" value="{{ old('placementtieup',(isset($edit_data)) ? $edit_data->placementtieup:"")}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Placement Tie-Up" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Placement Ratio</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="placementratio" value="{{ old('placementratio',(isset($edit_data)) ? $edit_data->placementratio:"")}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Placement Ratio" autocomplete="off">
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
 
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>	
<script>tinymce.init({selector:'textarea' });</script>
@endsection