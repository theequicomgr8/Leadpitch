@extends('layouts.cm_app')
 @section('title')
      Assign Course
@endsection
@section('content')	
  
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>For Not Interested Assign Course</h3>
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
							<h2>Create Assign Course</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							<form class="form-horizontal form-label-left" action="#" method="POST" onsubmit="return courseController.assignCoursesubmit(this)">
								<div class="form-group">
									<label class="col-xs-12">Counsellor<span class="required">*</span></label>
									<div class="col-xs-12">
									
										<select class="select2_assignuser form-control" name="counsellors" tabindex="-1">
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
										<select id="destination" name="assigncourse[]" class="form-control" multiple style="height:200px;">
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
							<h2>Assign Courses List</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content table-responsive">
							<table id="datatable-assign-course" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Counsellors</th>				 
										<th>Course</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
									</thead>
									<tbody >
									@if(!empty($assigncourse))
										@foreach($assigncourse as $course)
									<tr>
										<td><?php $usersname= APP\User::where('id',$course->counsellors)->first();
									 
										if(!empty($usersname)){
											
											echo $usersname->name;
										}
										?></td>
										 
										<td><?php if($course->assigncourse !== NULL){
										$assigncourse = unserialize($course->assigncourse);
										foreach($courses as $coursev){
										if(in_array($coursev->id,$assigncourse)){
										echo "<span class=\"label label-default\">$coursev->name</span> ";
										}
										}
										} ?></td>
										<td>
										<?php if($course->status=='1'){?>
										<a href="javascript:courseController.assigncoursestatus({{$course->id}},'0')"><span style="color:green">Active</span></a>
										
										<?php }else{ ?>
										<a href="javascript:courseController.assigncoursestatus({{$course->id}},'1')"><span style="color:red">Inactive</span></a>
										
										<?php } ?>
										</td>
										<td><a href="/course/assignment/editassigncourse/{{$course->id}}"><i class="fa fa-refresh" aria-hidden="true"></i></a> | <a href="javascript:courseController.assigncoursedelete({{$course->id}})"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
									</tr>
									
									@endforeach
									@endif
									</tbody >
								
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