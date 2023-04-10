@extends('layouts.cm_app')
 @section('title')
     Update Assign Course  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update - </h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12  col-md-offset-0">
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
							<h2>Update Form <small>(Update Course)</small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form id="update-course" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="">
								<div class="form-group">
									<label class="col-xs-12" for="name">Counsellors <span class="required">*</span><span class="required">*</span></label>
									<div class="col-xs-6">
										{{ csrf_field() }}
										<select class="select2_assignuser form-control" name="counsellors" tabindex="-1">
										 <option value="">Please Counsellor</option>   
										@if(!empty($users))
												@foreach($users as $user)
													 
														<option value="{{ $user->id }}" @if ($user->name== old('user'))
									 selected="selected"	
									@else
									{{ (isset($edit_data) && $edit_data->counsellors ==$user->id   ) ? "selected":"" }} @endif>{{ $user->name }}</option>
													 
												@endforeach
											@endif
										</select>
									</div>
									 
									<div class="col-xs-6">
										{{ csrf_field() }}
										<select class="select2_assignuser form-control" name="tocounsellor" tabindex="-1">
										 <option value="">Please Counsellor</option>   
										@if(!empty($users))
												@foreach($users as $user)
													 
														<option value="{{ $user->id }}" @if ($user->name== old('user'))
									 selected="selected"	
									@else
									{{ (isset($edit_data) && $edit_data->tocounsellor ==$user->id   ) ? "selected":"" }} @endif>{{ $user->name }}</option>
													 
												@endforeach
											@endif
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-xs-12">Course<span class="required">*</span></label>
									<div class="col-xs-5">
										<select id="source" class="form-control" multiple style="height:200px;">
											<?php echo $sourceCourses; ?>
										</select>
									</div>
									<div class="col-xs-2 text-center" style="margin:10px 0;">
										<input type='button' id='btnAllRight' value='>>'>
										<input type='button' id='btnRight' value='>'>
										<input type='button' id='btnLeft' value='<'>
										<input type='button' id='btnAllLeft' value='<<'>
									</div>
									<div class="col-xs-5">
										<select id="destination" name="assign_dom_course[]" class="form-control" multiple style="height:200px;">
											<?php echo $destinationDomCounsellors; ?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-xs-12">International Course<span class="required">*</span></label>
									<div class="col-xs-5">
										<select id="intsource" class="form-control" multiple style="height:200px;">
											<?php echo $sourceIntCourses; ?>
										</select>
									</div>
									<div class="col-xs-2 text-center" style="margin:10px 0;">
										<input type='button' id='intbtnAllRight' value='>>'>
										<input type='button' id='intbtnRight' value='>'>
										<input type='button' id='intbtnLeft' value='<'>
										<input type='button' id='intbtnAllLeft' value='<<'>
									</div>
									<div class="col-xs-5">
										<select id="intdestination" name="assign_int_course[]" class="form-control" multiple style="height:200px;">
											<?php echo $destinationIntCounsellors; ?>
										</select>
									</div>
								</div>
								
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-xs-4 col-xs-offset-8">
										<button type="submit" class="btn btn-success btn-block">Submit</button>
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