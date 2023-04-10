@extends('layouts.cm_app')
 @section('title')
     Delete Demo  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Deleted Leads</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="alert alert-danger hide"></div>
					<div class="alert alert-success hide"></div>
					<div class="x_panel">
						<div class="x_title">
							<h2>Deleted Leads <small>Listing</small></h2>
						 
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<table id="datatable-deleted-demo" class="table table-striped table-bordered">
								<thead>
									<tr>
									    <th><input type="checkbox" id="check-all" class="check-box"></th>
										<th>Name</th>
										<th>Email</th>
										<!--<th>Mobile</th>-->
										<th>Source</th>
										<th>Course</th>
										<th>Sub Courses</th>
										<th>Status</th>
										<th>Remark</th>
										<th>Created At</th>
										<th>Action</th>
										 
									</tr>
								</thead>
							</table>
							<form id="export-demos" method="POST" action="{{ url('/demo/getExcelDeletedDemo') }}">
								{{ csrf_field() }}
								 
								 
								<div class="form-group">
									<div class="col-md-2">
										<div class="row">	
									@if (Auth::user()->role =='super_admin')
											<button type="submit" class="btn btn-success btn-block export-leads" name="expert" value="Expert">Export</button>
										@endif
										</div>
									</div>
								</div>	
								
								<div class="form-group">
								<div class="col-md-2">							 
								@if (Auth::user()->role =='super_admin')
								<button type="button" class="btn btn-success btn-block" onclick="javascript:deletedDemoController.selectDeleteParmanent()">Delete</button>
								@endif
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