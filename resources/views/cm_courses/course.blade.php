@extends('layouts.cm_app')
 @section('title')
     Course  
@endsection
@section('content')	
  
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Courses</h3>
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
							<h2>Add Course</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							<form class="form-horizontal form-label-left" action="#" method="POST" onsubmit="return courseController.submit(this)">
								<div class="form-group">
									<label class="col-xs-12">Name<span class="required">*</span></label>
									<div class="col-xs-12">
										<input type="text" class="form-control" name="name" placeholder="Enter Course Name" autocomplete="off">
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-xs-12">Counsellors<span class="required">*</span></label>
									<div class="col-xs-5">
										<select id="source" class="form-control" multiple style="height:200px;">
											@if($users)
												@foreach($users as $user)
													<option value="{{ $user->id }}">{{ $user->name }}</option>
												@endforeach
											@endif
										</select>
									</div>
									<div class="col-xs-2 text-center" style="margin:10px 0;">
										<input type='button' id='btnAllRight' value='>>'>
										<input type='button' id='btnRight' value='>'>
										<input type='button' id='btnLeft' value='<'>
										<input type='button' id='btnAllLeft' value='<<'>
									</div>
									<div class="col-xs-5">
										<select id="destination" name="counsellors[]" class="form-control" multiple style="height:200px;">
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
							<h2>Courses List</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content table-responsive">
							<table id="datatable-courselist" class="table table-striped table-bordered">
								<thead>
									<tr>
									    	<th>ID</th>
										<th>Name</th>
										<th>Messages</th>
										<th>Counsellors</th>
										<th>Action</th>
									</tr>
									</thead>
									<tbody >
									@if(!empty($courses))
										@foreach($courses as $course)
									<tr>
									    	<td>{{$course->id}}</td>
										<td>{{$course->name}}</td>
										<td><?php 	
										if($course->id){										
										$messages = DB::table(Session::get('company_id').'_messages')->select('name')->where('course',$course->id)->get(); 
										foreach($messages as $message){
												echo "<span class=\"label label-default\">$message->name</span> ";
									
										} }
										
										?></td>
										<td><?php if($course->counsellors !== NULL){
										$counsellors = unserialize($course->counsellors);
										foreach($users as $user){
										if(in_array($user->id,$counsellors)){
										echo "<span class=\"label label-default\">$user->name</span> ";
										}
										}
										} ?></td>
										<td><a href="/course/update/{{$course->id}}"><i class="fa fa-refresh" aria-hidden="true"></i></a> | <a href="javascript:courseController.delete({{$course->id}})"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
									</tr>
									
									@endforeach
									@endif
									</tbody >
								
							</table>
							
								
							<form id="export-leads" method="POST" onsubmit="return exportLeads()" action="{{ url('/course/getCourseexcel') }}">
								{{ csrf_field() }}
								 
								 
								@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<div class="form-group">
									<div class="col-md-1">

								
										 
										<button type="submit" class="btn btn-success export-leads">Export</button>
								
										
									</div>
								</div>
									@endif  
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
 
	<!-- /page content -->
@endsection