@extends('layouts.cm_app')
 @section('title')
      Course Assigment
@endsection
@section('content')	
  
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Absent Course Assigment</h3>
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
				
				
				
			<!--	<div class="col-md-12 col-sm-12 col-xs-12">
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
										<th>Dom Course</th>
										<th>Int Course</th>
							            <th>Assign Lead</th> 
										<th>Status</th> 
										<th>Action</th>
									</tr>
									</thead>
									<tbody >
									
									 
									@if(!empty($assigncourse))
										@foreach($assigncourse as $course)
									<tr>
										<td><?php $usersname= APP\User::where('id',$course->counsellors)->first();
									// echo "<pre>";print_r($usersname);
										if(!empty($usersname)){
											
											echo $usersname->name;
										}
										?></td>
										 
										<td><?php if($course->assign_dom_course !== NULL){
										$assigncourse = unserialize($course->assign_dom_course);
										foreach($courses as $coursev){
										if(in_array($coursev->id,$assigncourse)){
										echo "<span class=\"label label-default\">".substr($coursev->name,0,15)."</span> ";
										}
										}
										} ?></td>
										
											 
										<td><?php if($course->assign_int_course !== NULL){
										$assignIntcourse = unserialize($course->assign_int_course);
										foreach($courses as $coursesv){
										if(in_array($coursesv->id,$assignIntcourse)){
										echo "<span class=\"label label-default\">".substr($coursesv->name,0,15)."</span> ";
										}
										}
										} ?></td>
										
										
									<td>
										<?php echo $course->leadcount; ?>
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assignlead_<?php echo $course->id; ?>" style="padding: 2px;"><i class="fa fa-edit"></i></button>
										
										<div class="modal fade" id="assignlead_<?php echo $course->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
										<div class="modal-header" style="padding: 1px 15px 0">

										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
										<h4>Lead Count add</h4>
										</div>

										<div class="modal-body">
										<form  onsubmit="return leadmanagmentController.saveleadcount(this)" autocomplate="off">
										 

										<div class="form-group" style="display: flex;align-items: center;">
										<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Lead Count </label>

										<div class="col-md-6 col-sm-4 col-xs-12">
										<input type="hidden" name="id" value="<?php echo $course->id; ?>">
										<input type="text" name="leadcount" class="form-control col-md-6 col-xs-12" value="{{ $course->leadcount }}" placeholder="Enter lead count" style="width: 100%;">
										@if ($errors->has('leadcount'))
										<span class="help-block">
										<strong>{{ $errors->first('leadcount') }}</strong>
										</span>
										@endif

										</div>
										</div>
										<div style=" text-align: center; margin: 15px 0;">
										<button type="submit" name="submit" class="btn btn-primary" >Save</button>
										</div>


										</form>



										</div>

										</div>
										</div>
										</div>
										 	
										</td> 	
									 	<td>
										<?php if($course->status=='1'){  ?>
										<a href="javascript:leadmanagmentController.assigncoursestatus({{$course->id}},'0')"><span style="color:green">Active</span></a>
										
										<?php }else{ ?>
										<a href="javascript:leadmanagmentController.assigncoursestatus({{$course->id}},'1')"><span style="color:red">Inactive</span></a>
										
										<?php } ?>
										</td> 
										<td><a href="/lead/editassigncourse/{{$course->id}}"><i class="fa fa-refresh" aria-hidden="true"></i></a> | <a href="javascript:leadmanagmentController.assigncoursedelete({{$course->id}})"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
									</tr>
									
									@endforeach
									@endif
									</tbody >
								
							</table>
						</div>
					</div>
					
					<div class="row"> 
							 <div class="col-md-12"> 
					<div class="col-md-3">
							<form  method="POST" action="{{ url('/lead/getCourseAssignExcel') }}">
							{{ csrf_field() }}
							 
							<input type="hidden" name="search[value]" value="">
							@if (Auth::user()->role =='super_admin')							 
							<button type="submit" class="btn btn-success  btn-block export-leads">Export</button>		
							@endif						
							</form>
							</div>
							</div>
							</div>
				</div>-->
			</div>
		</div>
	</div>
 <script>
 
 </script>
	<!-- /page content -->
@endsection