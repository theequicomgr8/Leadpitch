@extends('layouts.cm_app')
 @section('title')
     Update Message  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update - {{ $message->name }}</h3>
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
							<h2>Update Form <small>(Update Message Template)</small></h2>
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
							<form class="form-horizontal form-label-left" method="POST" action="">
							<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Local</label>
									<div class="col-md-3 col-sm-3 col-xs-12">
										 <input type="radio" name="permission" value="L" class="local" {{ (isset($message->permission) && $message->permission == "L" ) ? "checked":"" }} >
									</div>
									
									<label class="control-label col-md-1 col-sm-1 col-xs-12">Global</label>
									<div class="col-md-3 col-sm-3 col-xs-12">
										 <input type="radio" name="permission" value="G" class="global" {{ (isset($message->permission) && $message->permission == "G" ) ? "checked":"" }}>
									</div>
								</div>
								
								<div class="form-group technology">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="course">Technology</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="course" class="form-control">
											<option value="">-- Select Technology --</option>
											@if(count($courses)>0)
												@foreach($courses as $course)
													<?php
														$selected = '';
														if($message->course==$course->id)
															$selected = 'selected';
													?>
													<option value="{{$course->id}}" <?php echo $selected; ?>>{{$course->name}}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										{{ csrf_field() }}
										<input type="text" name="name" required="required" class="form-control col-md-7 col-xs-12" placeholder="Enter Meassage Name" value="{{ $message->name }}">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Message template <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="message" class="form-control col-md-7 col-xs-12" placeholder="Enter Meassage Content">{{ $message->message }}</textarea>
									</div>
								</div>
							<div id="showPermission">
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="all_lead" <?php echo (($message->all_lead)?"checked":""); ?>>All Lead</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="all_demo" <?php echo (($message->all_demo)?"checked":""); ?>> All Demo</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="add_lead" <?php echo (($message->add_lead)?"checked":""); ?>> Add Lead</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="add_demo" <?php echo (($message->add_demo)?"checked":""); ?>> Add Demo</label>
										</div>
									</div>
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