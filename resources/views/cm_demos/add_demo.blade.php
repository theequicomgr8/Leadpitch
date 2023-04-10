@extends('layouts.cm_app')
 @section('title')
     Add Demo  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>New Demo</h3>
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
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<h2><small>(Add New Demo)</small></h2>
							<ul class="nav navbar-right panel_toolbox">
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
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<!--form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" onsubmit="return leadController.submit(this)"-->
							<form id="add-demo-form" method="POST" action="" class="form-horizontal form-label-left">
								{{ csrf_field() }}
								<input type="hidden" name="id" value="">
								<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="mobile" class="form-control col-md-6 col-xs-12" value="{{ old('mobile') }}">
										@if ($errors->has('mobile'))
											<span class="help-block">
												<strong>{{ $errors->first('mobile') }}</strong>
											</span>
										@endif
										<a class="btn btn-info verify-demo" style="position:absolute;right:5px;">Verify</a>
									</div>
								</div>
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="name" class="form-control col-md-7 col-xs-12" value="{{old('name')}}">
										@if($errors->has('name'))
											<span class="help-block">
												<strong>{{$errors->first('name')}}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('email')?' has-error':'' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="email" class="form-control col-md-7 col-xs-12" value="{{old('email')}}">
										@if($errors->has('email'))
											<span class="help-block">
												<strong>{{$errors->first('email')}}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('course')?' has-error':'' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Course <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control sms-control" name="course" tabindex="-1">
											<option value="">-- SELECT TECHNOLOGY --</option>
											@if(count($courses)>0)
												@foreach($courses as $course)
													<?php
														$selected = '';
														if(old('course')==$course->id):
															$selected = 'selected';
														endif;
													?>
													<option value="{{ $course->id }}" <?php echo $selected; ?>>{{ $course->name }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="form-group{{ $errors->has('message')?' has-error':'' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Message</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control sms-control-target" name="message" tabindex="-1">
										</select>
									</div>
								</div>
								<div class="form-group{{ $errors->has('sub-course')?' has-error':'' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sub-course">Sub Technology </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sub-course" class="form-control col-md-7 col-xs-12" value="{{old('sub-course')}}">
									</div>
								</div>
								<div class="form-group{{ $errors->has('status')?' has-error':'' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control" name="status" tabindex="-1">
											<option value="">-- SELECT STATUS --</option>
											@if(count($statuses)>0)
												@foreach($statuses as $status)
													<?php
														$selected = '';
														if(old('status')==$status->id):
															$selected = 'selected';
														endif;
													?>											
													<option value="{{ $status->id }}">{{ $status->name }}</option>
												@endforeach
											@endif
										</select>
										@if($errors->has('status'))
											<span class="help-block">
												<strong>{{$errors->first('status')}}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('exec_call')?' has-error':'' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Executive Call <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control" name="exec_call" tabindex="-1">
											<option value="">-- SELECT --</option>
											<option value="yes" <?php echo (old('exec_call')=='yes')?'selected':''; ?>>Yes</option>
											<option value="no" <?php echo (old('exec_call')=='no')?'selected':''; ?>>No</option>
										</select>
									</div>
								</div>
								<!--div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Expected Date &amp; Time </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="expected_date_time" id="expected_date_time" class="form-control col-md-7 col-xs-12" value="{{old('expected_date_time')}}">
									</div>
								</div-->
								<div class="form-group{{ $errors->has('remark')?' has-error':'' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Student Remark </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="remark" rows="5" class="form-control col-md-7 col-xs-12">{{old('remark')}}</textarea>
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
	<!-- /page content -->
@endsection