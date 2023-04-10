@extends('layouts.cm_app')
 @section('title')
     Add Lead  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>New Trainer</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<h2>Trainer Form <small>(Add New Trainer)</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							<!--form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" onsubmit="return leadController.submit(this)"-->
							<form class="form-horizontal form-label-left" onsubmit="return trainerController.submit(this)">
								{{ csrf_field() }}
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="name" class="form-control col-md-7 col-xs-12" placeholder="Enter Name" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="email" class="form-control col-md-7 col-xs-12" placeholder="Enter Email" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="mobile" class="form-control col-md-7 col-xs-12" placeholder="Enter Mobile" autocomplete="off">
									</div>
								</div>
							 
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Course <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_course form-control sms-control" name="course" tabindex="-1">
										</select>
									</div>
								</div>
								<!--<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Message</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control sms-control-target" name="message" tabindex="-1">
										<option value="{{ isset($message->id)?$message->id:"" }}"><?php echo isset($message->name)?$message->name:""?></option>
										</select>
									</div>
								</div>-->
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sub-course">Skills</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea type="text" name="sub-course" class="form-control col-md-7 col-xs-12" placeholder="Enter Skills"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Work Day <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="workday" tabindex="-1">
											 
													<option value="">Select Work Day</option>
													<option value="Week Day (Part Time)">Week Day (Part Time)</option>
													<option value="Week End (Part Time)">Week End (Part Time)</option>
													<option value="Week Day + Week End (Part Time)">Week Day + Week End (Part Time)</option>
													<option value="Full Time">Full Time</option>
												 
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Training<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="training" tabindex="-1">
											 
													<option value="">Select Training</option>
													<option value="Online Training">Online Training</option>
													<option value="Class Room Training">Class Room Training</option>
													<option value="Online + Class Room Training">Online + Class Room Training</option>
													<option value="Corporate Training">Corporate Training</option>
													 
												 
										</select>
									</div>
								</div>
								
								<!--<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control" name="status" tabindex="-1" disabled>
											@if(count($statuses)>0)
												@foreach($statuses as $status)
													<?php
														$selected = '';
														if(!strcasecmp($status->name,'new lead'))
															$selected = 'selected';
													?>
													<option value="{{ $status->id }}" <?php echo $selected ?>>{{ $status->name }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>-->
							 
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Remark </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="counsellor_remark" rows="1" class="form-control col-md-7 col-xs-12" placeholder="Enter remark" autocomplete="off"></textarea>
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button class="btn btn-primary" type="reset">Reset</button>
										<button type="submit" name="submit" class="btn btn-success">Submit</button>
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