@extends('layouts.cm_app')
@section('title')
     List Client  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main" ng-controller="userController">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>All Users</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					 
					<div class="alert alert-success hide"></div>
					<div class="alert alert-danger hide"></div>
						@if(Session::has('success'))
						<div class="alert alert-success">{{Session::get('success')}}</div>
						@endif
						
						@if(Session::has('failed'))
						<div class="alert alert-danger">{{Session::get('failed')}}</div>
						@endif
					<div class="x_panel">
						<div class="x_content">
							<table id="datatable-client" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Company ID</th>
										<th>Name</th>
										<th>Email</th>
										<th>Mobile</th>										
										<th>From</th>
										<th>To</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								 
								@if(count($clients)>0)
									@foreach($clients as $user)
										<tr>
											<td>{{ $user->company_id }}</td>
											<td>{{ $user->name }}</td>
											<td>{{ $user->email }}</td>
											<td>{{ $user->mobile }}</td>
											<td>{{ $user->from }}</td>
											<td>{{ $user->client_to }}</td>
											<td><a href="/genie/update/{{ base64_encode($user->client_id) }}"><i aria-hidden="true" class="fa fa-refresh"></i></a> | <a href="/genie/delete/{{ base64_encode($user->client_id) }}" onclick="return confirm('Are you sure you want to delete?');"><i aria-hidden="true" class="fa fa-trash"></i></a></td>
										</tr>
									@endforeach
								@endif
								 
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection