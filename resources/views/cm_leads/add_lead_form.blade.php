@extends('layouts.cm_app')
 @section('title')
     Add Lead  
@endsection
@section('content')	
 
	<div class="right_col" role="main">
	    <style> 
.select2-container--default .select2-selection--multiple, .select2-container--default .select2-selection--single{
	    min-height: 35px !important;
}
</style>
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>New Lead</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<h2>Lead Form <small>(Add New Lead)</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<form class="form-horizontal form-label-left" onsubmit="return leadController.submit(this)">
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
									<input type="text" name="mobile" class="form-control col-md-3 col-xs-12" placeholder="Enter Mobile" autocomplete="off">
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
										<input type="text" name="sub-course" class="form-control col-md-7 col-xs-12">
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
														if(!strcasecmp($status->name,'new lead'))
															$selected = 'selected';
													?>
													<option value="{{ $status->id }}" <?php echo $selected ?>>{{ $status->name }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
								
								 <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Counsellor<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control" name="user_id" tabindex="-1">
											@if(count($users)>0)
												@foreach($users as $user)
													<?php
														$selected = '';
														if($user->id == Auth()->user()->id)
															$selected = 'selected';
													?>
													<option value="{{ $user->id }}" <?php echo $selected ?>>{{ $user->name }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Student Remark </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="remark" rows="1" class="form-control col-md-7 col-xs-12" placeholder="Enter Remark" autocomplete="off"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Counsellor Remark </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="counsellor_remark" rows="1" class="form-control col-md-7 col-xs-12" placeholder="Enter counsellor remark" autocomplete="off"></textarea>
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

@endsection