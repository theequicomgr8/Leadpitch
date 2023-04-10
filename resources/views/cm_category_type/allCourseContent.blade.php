@extends('layouts.cm_app')
@section('title')
     All Content  
@endsection
@section('content')		
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>All Content</h3>
					
			@if(Session::has('success')) 	
				<div class="row">
				<div class="col-md-8 col-md-offset-1">
				<div class="alert alert-success">
				<strong> {{Session::get('success')}}</strong>
				</div>
				</div>
				</div>
				@endif
				@if(Session::has('failed')) 	
				<div class="row">
				<div class="col-md-8 col-md-offset-1">
				<div class="alert alert-danger">
				<strong>{{Session::get('failed')}}</strong>
				</div>
				</div>
				</div>
				@endif
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="alert alert-danger hide"></div>
					<div class="alert alert-success hide"></div>
					@if(Session::has('alert-success'))
						<div class="alert alert-success">{{Session::get('alert-success')}}</div>
					@endif	
					@if(Session::has('alert-danger'))
						<div class="alert alert-danger">{{Session::get('alert-danger')}}</div>
					@endif
					<div class="x_panel">	
						<div class="x_content" id="employee_table">
							<table id="datatable-allcoursecontentpdf" class="table table-striped table-bordered">
								<thead>
									<tr>
										 
										<th>Category</th>
										<th>Course Content</th>
										 							 
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