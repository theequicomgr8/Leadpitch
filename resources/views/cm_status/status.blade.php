@extends('layouts.cm_app')
 @section('title')
     Status  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Status</h3>
					@foreach (['danger', 'warning', 'success', 'info'] as $msg)
								@if(Session::has('alert-' . $msg))
									<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
								@endif
							@endforeach
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
							<h2>Add Status</small></h2>
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
							<form class="form-horizontal form-label-left" action="#" method="POST" onsubmit="return statusController.submit(this)">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<input type="text" class="form-control" name="name" placeholder="Enter Status">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="show_exp_date" checked> Show Expected Date</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="lead_filter" checked> Show in Lead Filter</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="lead_follow_up" checked> Show in Lead Follow Up</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="add_demo" checked> Show in Add Demo Form</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="demo_filter" checked> Show in Demo Filter</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="demo_follow_up" checked> Show in Demo Follow Up</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="abgyan_follow_up" checked> Show in Abgyan Follow Up</label>
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
							<h2>Status List</h2>
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
							<table id="datatable-status" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Name</th>
										<th>Show Expected Date</th>
										<th>Show in Lead Filter</th>
										<th>Show in Lead Follow Up</th>
										<th>Show in Add Demo Form</th>
										<th>Show in Demo Filter</th>
										<th>Show in Demo Follow Up</th>
										<th>Show in Abgyan Follow Up</th>
										<th>Action</th>
										<!--th>Delete</th-->
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection