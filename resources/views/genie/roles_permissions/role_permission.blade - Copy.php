@extends('layouts.cm_app')

@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Role Permission</h3>
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
							<h2>Add Role Permission<br/><small style="color:red">(Please use underscore inspite of space)</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" action="#" method="POST" onsubmit="return rolePermissionController.submit(this)">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Role</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<select name="role" class="form-control">
										@if($roles)
											@foreach($roles as $key=>$value)
												<option value="{{$key}}">{{$value}}</option>
											@endforeach
										@endif
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Permissions</label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<select name="permission[]" class="form-control" multiple>
										@if($permissions)
											@foreach($permissions as $permission)
												<option value="{{$permission->permission}}" selected>{{$permission->permission}}</option>
											@endforeach
										@endif
										</select>
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
							<h2>Permission List</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<table id="datatable-role-permission" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Role</th>
										<th>Permissions</th>
										<th>Action</th>
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