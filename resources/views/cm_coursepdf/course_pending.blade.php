@extends('layouts.cm_app')
@section('title')
     All Course Content  
@endsection
@section('content')		
	<div class="right_col" role="main">
		<div class="">
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
				<div class="row">
				<div class="col-md-8 col-md-offset-1">
				<div id="success"> </div><div id="failed"> </div>
				</div>
				</div>
		<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">		
				<div class="page-title">
				<div class="title_left">
				<h3><a href="category/all-category">Course Content PDF</a></h3>

				</div>
					 
				</div>				
				</div>
				
				</div>
			 
			
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">				 
					<div class="x_panel">	
						<div class="x_content" >
							<table id="datatable-coursepdfpending" class="table table-striped table-bordered">
								<thead>
									<tr>									 
										<th>Course Content PDF</th>
										 							 
										 
										 
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