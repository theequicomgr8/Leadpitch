@extends('layouts.cm_app')
@section('title')
     Update Email  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update - {{ $edit_email->name }}</h3>
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
							 
							<h2>Update Form <small>(Update Email)</small></h2>
							 
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" method="POST" action="">
								 
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										{{ csrf_field() }}
										<input type="text" name="name" required="required" class="form-control col-md-7 col-xs-12" value="{{ old('name',(isset($edit_email)) ? $edit_email->name:"") }}" placeholder="Enter Name">
										@if ($errors->has('name'))
										<small class="error alert-danger">{{ $errors->first('name') }}</small>
										@endif
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Email <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input name="email" class="form-control col-md-7 col-xs-12" value="{{ old('email',(isset($edit_email)) ? $edit_email->email:"") }}" placeholder="Enter Email">
										@if ($errors->has('email'))
										<small class="error alert-danger">{{ $errors->first('email') }}</small>
										@endif
									</div>
								</div>
								 
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button type="submit" name="submit" value="update" class="btn btn-success">Submit</button>
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