@extends('layouts.cm_app')
 @section('title')
     Update Role Permission  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update - {{$edit_permission->role}}</h3>
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
							<h2>Update Form <small>(Update Permission)</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form id="role-form2" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="/role-permission/update/<?php echo $id;?>">
								{{ csrf_field() }}
								<div class="form-group">
									<label class="col-xs-12" for="role">Role <span class="required">*</span></label>
									<div class="col-xs-5">
									 <input type="text" name="role" required="required" class="form-control col-md-7 col-xs-12" value="{{ $edit_permission->role }}">
										 
									</div>
									
								</div>
								<div class="form-group">
									<label class="col-xs-12" for="name">Permission <span class="required"></span></label>
									<div class="col-xs-5">
									<select id="source" class="form-control" multiple style="height:200px;">
											<?php echo $users['sourcePermissions']; ?>
										</select>
									</div>
									<div class="col-xs-2 text-center" style="margin:10px 0;">
										<input type='button' id='btnAllRight' value='>>'>
										<input type='button' id='btnRight' value='>'>
										<input type='button' id='btnLeft' value='<'>
										<input type='button' id='btnAllLeft' value='<<'>
									</div>
									<div class="col-xs-5">
										<select id="destination" name="permission[]" class="form-control" multiple style="height:200px;">
											<?php echo $users['destinationPermissions']; ?>
										</select>
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