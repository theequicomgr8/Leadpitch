@extends('layouts.cm_app')
 @section('title')
     Update Lead  
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
					<h3>Update - {{ $lead->name }}</h3>
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
							<h2>Update Form <small>(Update Lead)</small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form action="/lead/update/<?php echo $id ?>" class="form-horizontal form-label-left" method="POST">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										{{ csrf_field() }}
										<input type="text" name="name" required="required" class="form-control col-md-7 col-xs-12" value="{{ $lead->name }}">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="email" class="form-control col-md-7 col-xs-12" value="{{ $lead->email }}">
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
									<option value="<?php echo $country->phonecode; ?>" <?php echo (isset($country->phonecode) && $country->phonecode==$lead->code)?"selected":"";  ?>><?php echo '+'.$country->phonecode.' '.$country->country_name;  ?></option>
									<?php } } ?>
									</select>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12">
									<input type="text" name="mobile" class="form-control col-md-3 col-xs-12" value="{{ $lead->mobile }}" placeholder="Enter Mobile" autocomplete="off">
									</div>
								</div>
								@php 
								    if(Auth::user()->current_user_can('super_admin')  ||  Auth::user()->current_user_can('administrator')){
                        			     $display="";  
                        			}
                        			else{
                        			    $display="none";
                        			}
								@endphp
								<div class="form-group" style="display:{{$display}}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Source <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control" required="required" name="source" tabindex="-1">
											<option>-- SELECT SOURCE --</option>
											@if(count($sources)>0)
												@foreach($sources as $source)
													@if($source->id == $lead->source)
														<option value="{{ $source->id }}" selected>
														    <!--{{ $source->name }}-->
														    @if($source->name=='FaceBook')
														        @if(Auth::user()->role=='user')
														        {{'Website'}}
														        @else
														        {{ $source->name }}
														        @endif
														    @else
														        {{ $source->name }}
														    @endif
														 </option>
													@else
														<option value="{{ $source->id }}">{{ $source->name }}</option>
													@endif
												@endforeach
											@endif
										  </select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Course <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control select2_course sms-control" required="required" name="course" tabindex="-1">
											<option>-- SELECT COURSE --</option>
											@if(count($courses)>0)
												@foreach($courses as $course)
													@if($course->id == $lead->course)
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
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Owner <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control demo_owner" name="owner" tabindex="-1">
											<option value="">-- SELECT OWNER --</option>
											@if(count($users)>0)
												@foreach($users as $user)
													@if($user->id == $lead->created_by)
														<option value="{{ $user->id }}" selected <?php echo (in_array($user->id,$courseCounsellors))?"style=\"font-weight:bold\"":""; ?>>{{ $user->name }}</option>
													@else
														<option value="{{ $user->id }}" <?php echo (in_array($user->id,$courseCounsellors))?"style=\"font-weight:bold\"":""; ?>>{{ $user->name }}</option>
													@endif
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="sub-course">Sub Course </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="sub-course" class="form-control col-md-7 col-xs-12" value="{{ $lead->sub_courses }}" placeholder="Comma seperated courses">
									</div>
								</div>
								<!--div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Status <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="select2_single form-control" name="status" tabindex="-1" disabled>
											@if(count($statuses)>0)
												@foreach($statuses as $status)
													@if($status->id == $lead->status)
														<option value="{{ $status->id }}" selected>{{ $status->name }}</option>
													@else
														<option value="{{ $status->id }}">{{ $status->name }}</option>
													@endif
												@endforeach
											@endif
										</select>
									</div>
								</div-->
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Remark </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="remark" rows="5" class="form-control col-md-7 col-xs-12">{{ $lead->remarks }}</textarea>
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