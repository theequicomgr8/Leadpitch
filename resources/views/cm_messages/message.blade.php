@extends('layouts.cm_app')
 @section('title')
     Message  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Messages</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-danger hide"></div>
					<div class="alert alert-success hide"></div>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
						@foreach (['danger', 'warning', 'success', 'info'] as $msg)
								@if(Session::has('alert-' . $msg))
									<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
								@endif
							@endforeach
							<h2>Add Message</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							<form class="form-horizontal form-label-left" action="#" method="POST" onsubmit="return messageController.submit(this)">
								<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12">Meassage Permission<span class="required">*</span></label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Local</label>
									<div class="col-md-3 col-sm-3 col-xs-12">
										 <input type="radio" name="permission" value="L" class="local">
									</div>
									
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Global</label>
									<div class="col-md-3 col-sm-3 col-xs-12">
										 <input type="radio" name="permission" value="G" class="global">
									</div>
								</div>
								</div>
								
								<div class="form-group technology">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Technology</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<select name="course" class="select2_course form-control">
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Name<span class="required">*</span></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<input type="text" class="form-control" placeholder="Enter Meassage Name" name="name">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Message<span class="required">*</span></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<textarea class="form-control" placeholder="Enter Meassage Content" name="message"></textarea>
									</div>
								</div>
								<div id="showPermission">
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="all_lead" >All Lead</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="all_demo" > All Demo</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="add_lead" > Add Lead</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="add_demo" > Add Demo</label>
										</div>
									</div>
								</div>
								
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<button type="reset" class="btn btn-primary">Reset</button>
										<button type="submit" class="btn btn-success">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Messages List</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<table id="datatable-message" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Name</th>
										<th>Technology</th>
										<th>Action</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Name</th>
										<th>Technology</th>
										<th>Action</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection