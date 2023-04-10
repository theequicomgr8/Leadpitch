@extends('layouts.cm_app')
 @section('title')
     Update Status  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update - {{ $status->name }}</h3>
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
							<h2>Update Form <small>(Update Status)</small></h2>
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
							<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										{{ csrf_field() }}
										<input type="text" name="name" required="required" class="form-control col-md-7 col-xs-12" value="{{ $status->name }}">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label" for="show_exp_date"></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="show_exp_date" <?php echo (($status->show_exp_date)?"checked":""); ?>> Show Expected Date</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label" for="lead_filter"></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="lead_filter" <?php echo (($status->lead_filter)?"checked":""); ?>> Show in Lead Filter</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label" for="lead_follow_up"></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="lead_follow_up" <?php echo (($status->lead_follow_up)?"checked":""); ?>> Show in Lead Follow Up</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label" for="add_demo"></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="add_demo" <?php echo (($status->add_demo)?"checked":""); ?>> Show in Add Demo Form</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label" for="demo_filter"></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="demo_filter" <?php echo (($status->demo_filter)?"checked":""); ?>> Show in Demo Filter</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label" for="demo_follow_up"></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="demo_follow_up" <?php echo (($status->demo_follow_up)?"checked":""); ?>> Show in Demo Follow Up</label>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label" for="demo_follow_up"></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<div class="checkbox">
											<label><input type="checkbox" value="1" name="abgyan_follow_up" <?php echo (($status->abgyan_follow_up)?"checked":""); ?>> Show in Abgyan Follow Up</label>
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