@extends('layouts.cm_app')
 @section('title')
      Absent assign course view
@endsection
@section('content')	
  
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Absent assign course view</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-danger hide"></div>
					<div class="alert alert-success hide"></div>
					 @foreach (['danger', 'warning', 'success', 'info'] as $msg)
								@if(Session::has('alert-' . $msg))
									<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
								@endif
							@endforeach
				</div>
				
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Absent Assign Course</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							<form class="form-horizontal form-label-left" action="#" method="POST" onsubmit="return absentAssignController.submit(this)">
								
								<div class="form-group">
									<label class="col-xs-12">Counsellor<span class="required">*</span></label>
									<div class="col-xs-6">
									
										<select class="select2_assincounsellor form-control" name="counsellors" tabindex="-1">
										 <option value="">Please Counsellor</option>   
										@if(!empty($users))
												@foreach($users as $user)
													 
														<option value="{{ $user->id }}" @if ($user->name== old('user'))
									 selected="selected"	
									@else
									{{ (isset($edit_data) && $edit_data->user ==$user->name   ) ? "selected":"" }} @endif>{{ $user->name }}</option>
													 
												@endforeach
											@endif
										</select>
									</div>
									 
									<div class="col-xs-6">									
										<select class="select2_assignuser form-control" name="to_counsellor" tabindex="-1">
										 <option value="">Please Counsellor</option>   
										@if(!empty($users))
												@foreach($users as $user)												 
														<option value="{{ $user->id }}" @if ($user->name== old('user'))
									 selected="selected"	
									@else
									{{ (isset($edit_data) && $edit_data->user ==$user->name   ) ? "selected":"" }} @endif>{{ $user->name }}</option>													 
												@endforeach
											@endif
										</select>
									</div>									
								</div>
								
							 
								
								<div class="form-group">
									<label class="col-xs-12">Domestic Course<span class="required">*</span></label>
									<div class="col-xs-5">
										<select id="source" class="form-control sow_absent_domestice" multiple style="height:200px;">
											<?php // echo $sourceCourses; ?>
										</select>
									</div>
									<div class="col-xs-2 text-center" style="margin:10px 0;">
										<input type='button' id='btnAllRight' value='>>'>
										<input type='button' id='btnRight' value='>'>
										<input type='button' id='btnLeft' value='<'>
										<input type='button' id='btnAllLeft' value='<<'>
									</div>
									<div class="col-xs-5">
										<select id="destination" name="absent_assign_dom_course[]" class="form-control" multiple style="height:200px;">
										</select>
									</div>
								</div>	
								
							 
								
								<div class="form-group">
									<label class="col-xs-12">Internation Course<span class="required">*</span></label>
									<div class="col-xs-5">
										<select id="intsource" class="form-control sow_absent_international" multiple style="height:200px;">
											<?php //echo $sourceIntCourses; ?>
										</select>
									</div>
									<div class="col-xs-2 text-center" style="margin:10px 0;">
										<input type='button' id='intbtnAllRight' value='>>'>
										<input type='button' id='intbtnRight' value='>'>
										<input type='button' id='intbtnLeft' value='<'>
										<input type='button' id='intbtnAllLeft' value='<<'>
									</div>
									<div class="col-xs-5">
										<select id="intdestination" name="absent_assign_int_course[]" class="form-control" multiple style="height:200px;">
										</select>
									</div>
								</div>
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-xs-12 text-right">
										<button type="reset" class="btn btn-primary">Reset</button>
										<button type="submit" class="btn btn-success">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			 <div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Create Assign Course</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							<form class="form-horizontal form-label-left" action="" method="get" >
								
								<div class="form-group">
									<label class="col-xs-12">Counsellor<span class="required">*</span></label>
									<div class="col-xs-6">
									
										<select class="form-control" name="search[counsellors]" tabindex="-1">
										 <option value="">Please Counsellor</option>   
										 @foreach($users as $user)
													@if(isset($search['counsellors']) && $search['counsellors']==$user->id)
														<option value="{{ $user->id }}" selected>{{ $user->name }}</option>
													@else
														<option value="{{ $user->id }}">{{ $user->name }}</option>
													@endif
												@endforeach
										</select>
									</div>
									 <div class="col-xs-3">
									 <button type="submit" name="submit" class="btn btn-block btn-info">Filter</button>
									</div>
									 									
								</div>
								 
								 
							</form>
						</div>
					</div>
				</div> 
				
				
				
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Assign Courses List</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content table-responsive">
							<table id="datatable-absent-assign-view" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Counsellors</th>										 
										<th>To Counsellor</th>
										<th>Dom Course</th>
							            <th>Int Course</th> 
									 
										<th>Action</th>
									</tr>
									</thead>
									 
								
							</table>
						</div>
					</div>
					 
				</div>
			</div>
		</div>
	</div>
 <script>
 
 </script>
	<!-- /page content -->
@endsection