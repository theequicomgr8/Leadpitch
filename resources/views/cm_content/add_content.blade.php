@extends('layouts.cm_app')
 @section('title')
     Add Content  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>New Content</h3>
						@if(Session::has('alert-danger'))
						<div class="alert alert-danger">{{Session::get('alert-danger')}}</div>
						@endif
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">					 
						<div class="x_content">						 
							<form class="form-horizontal form-label-left" action="{{url('content/add-content')}}" method="post">
								{{ csrf_field() }}
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Course <span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input type="text" name="course" value="{{old('course')}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Course" autocomplete="off">
										@if ($errors->has('course'))
										<small class="error alert-danger">{{ $errors->first('course') }}</small>
										@endif
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="email">Introduction  <span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea type="text" name="introduction" rows="4"  class="form-control col-md-7 col-xs-12 textarea" placeholder="Enter Introduction" autocomplete="off">{{old('introduction')}}</textarea>
										@if ($errors->has('introduction'))
										<small class="error alert-danger">{{ $errors->first('introduction') }}</small>
										@endif
									</div>
								</div>
							 
							 <div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="mobile">Module<span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea type="text" name="modulesdescription" class="form-control col-md-7 col-xs-12 textarea" placeholder="Enter Module Description" autocomplete="off">{{old('modulesdescription')}}</textarea>
										@if ($errors->has('modulesdescription'))
										<small class="error alert-danger">{{ $errors->first('modulesdescription') }}</small>
										@endif
									</div>
								</div>  								 
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Duration</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="duration" rows="1" value="{{old('duration')}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Duration" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Certification</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea name="certification" rows="4" class="form-control col-md-7 col-xs-12" placeholder="Enter Certification" autocomplete="off">{{old('certification')}}</textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Live Project</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea name="liveproject" rows="4" class="form-control col-md-7 col-xs-12 textarea" placeholder="Enter Live Project" autocomplete="off">{{old('liveproject')}}</textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Training Mode</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="trainingmode" rows="1" value="{{old('trainingmode')}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Training Mode" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Demo Timing</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="demotimig" rows="1" value="{{old('demotimig')}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Demo Timing" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">About Trainer</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<textarea name="abouttrainer" rows="1" class="form-control col-md-7 col-xs-12" placeholder="Enter About Trainer" autocomplete="off">{{old('abouttrainer')}}</textarea>
									</div>
								</div>
							 
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Placement Tie-Up</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="placementtieup" rows="1" value="{{old('placementtieup')}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Placement Tie-Up" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="remark">Placement Ratio</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										<input name="placementratio" rows="1" value="{{old('placementratio')}}" class="form-control col-md-7 col-xs-12" placeholder="Enter Placement Ratio" autocomplete="off">
									</div>
								</div>
								
								
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">										 
										<button type="submit" class="btn btn-success" name="submit">Submit</button>
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
  
 
  	<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
	
<script>tinymce.init({selector:'textarea' });</script>
@endsection