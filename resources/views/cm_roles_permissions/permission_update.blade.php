@extends('layouts.cm_app')
 @section('title')
     Update Permission  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update - {{ $permission->permission }}</h3>
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
							<h2>Update Form hkjh<small>(Update Permission)</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="/permission/update/<?php echo $id;?>">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Permission <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										{{ csrf_field() }}
										<input type="text" name="permission" required="required" class="form-control col-md-7 col-xs-12" value="{{ $permission->permission }}">
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