@extends('layouts.cm_app')
@section('title')
     Add Demo  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
	    <style> 
.select2-container--default .select2-selection--multiple, .select2-container--default .select2-selection--single{
	    min-height: 35px !important;
}
</style>
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Add New Demo</h3>
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
							<form id="add-demo-form" class="form-horizontal form-label-left" onsubmit="return demoController.submit(this)">
								{{ csrf_field() }}
								<input type="hidden" name="id" value="">
								<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile </label>
									<div class="col-md-2 col-sm-2 col-xs-12">
									<?php 

									$countrylist = App\CountryCode::get();								 
									?>
									<select type="text" class="form-control" id="count_code" name="stud-code">
									<option value="">Select Code</option>
									<?php if(!empty($countrylist)){ 
									foreach($countrylist as $country){	?>
									<option value="<?php echo $country->phonecode; ?>" <?php echo (isset($country->phonecode) && $country->phonecode=='91')?"selected":"";  ?>><?php echo '+'.$country->phonecode.' '.$country->country_name;  ?></option>
									<?php } } ?>
									</select>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12">
										<input type="text" name="mobile" class="form-control col-md-6 col-xs-12" value="{{ old('mobile') }}" placeholder="Enter Mobile">
										@if ($errors->has('mobile'))
											<span class="help-block">
												<strong>{{ $errors->first('mobile') }}</strong>
											</span>
										@endif
										<a class="btn btn-info verify-demo" style="position:absolute;right:5px;">Verify</a>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="name" class="form-control col-md-7 col-xs-12" placeholder="Enter Name">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="email" class="form-control col-md-7 col-xs-12" placeholder="Enter Email">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Source <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control" name="source" tabindex="-1">
											<option value="">-- SELECT SOURCE --</option>
											@if(count($sources)>0)
												@foreach($sources as $source)
													<option value="{{ $source->id }}">{{ $source->name }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Course <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_course form-control sms-control" name="course" tabindex="-1">
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Owner <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control demo_owner" name="owner" tabindex="-1">
											<option value="">-- SELECT OWNER --</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Message</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control sms-control-target" name="message" tabindex="-1">
										<option value="{{ isset($message->id)?$message->id:"" }}"><?php echo isset($message->name)?$message->name:""?></option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sub-course">Sub Technology </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sub-course" class="form-control col-md-7 col-xs-12" placeholder="Enter Sub Technology">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Demo Trainer <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_trainer form-control" name="trainer" tabindex="-1">
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control" name="status" tabindex="-1" disabled>
											@if(count($statuses)>0)
												@foreach($statuses as $status)
													<?php
														$selected = '';
														if(!strcasecmp($status->name,'attended demo'))
															$selected = 'selected';
													?>
													<option value="{{ $status->id }}" <?php echo $selected ?>>{{ $status->name }}</option>
												@endforeach
											@endif
										</select>
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
								<div class="form-group hide">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Demo Type </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control" id="demo_type" name="demo_type" tabindex="-1">
											<option value="Reference" <?php echo (old('demo_type')=='Reference')?'selected':''; ?>>Reference</option>
											<option value="Walkin" <?php echo (old('demo_type')=='Walkin')?'selected':''; ?>>Walkin</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Student Remark </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="remark" rows="1" class="form-control col-md-7 col-xs-12" placeholder="Enter Student remark"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Counsellor Remark </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="counsellor_remark" rows="1" class="form-control col-md-7 col-xs-12" placeholder="Enter Counsellor Remark"></textarea>
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
	<div id="leadsPayloadModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Name</th>
								<th>Mobile</th>
								<th>Course</th>
								<th>Owner</th>
								<th>Select One</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>John</td>
								<td>9897523652</td>
								<td>john@example.com</td>
								<td>john@example.com</td>
								<td class="text-center"><label><input type="radio" name="optradio"></label></td>
							</tr>
							<tr>
								<td>John</td>
								<td>9897523652</td>
								<td>john@example.com</td>
								<td>john@example.com</td>
								<td class="text-center"><label><input type="radio" name="optradio"></label></td>
							</tr>
							<tr>
								<td>John</td>
								<td>9897523652</td>
								<td>john@example.com</td>
								<td>john@example.com</td>
								<td class="text-center"><label><input type="radio" name="optradio"></label></td>
							</tr>
						</tbody>
					</table>
					<div class="pull-right">
					<button type="button" class="btn btn-default choke">Submit</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!--div class="modal-footer">

				</div-->
			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection