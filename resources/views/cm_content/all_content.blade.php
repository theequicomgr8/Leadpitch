@extends('layouts.cm_app')
@section('title')
     All Content  
@endsection
@section('content')	
<style>
strong {
    color: #000;
}
</style>
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>All Content</h3>
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
							<table id="datatable-content" class="table table-striped table-bordered">
								<thead>
									<tr>
										 
										<th>Course</th>
										<th>Introduction</th>
										<th>Modules</th>								 
										<th>About Trainer</th>								 
										<th>Action</th>
										 
									</tr>
								</thead>
							</table>
							 
							 	<div class="form-group">
									<div class="col-md-2">	
									<a href="{{url('content/getcontentexcel')}}">	<button type="submit" class="btn btn-success  btn-block export-leads" >Export</button></a>								
										
									</div>
								</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		 
		
  
		 </div>

	<!-- /page content -->
@endsection