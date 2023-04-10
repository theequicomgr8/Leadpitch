@extends('layouts.cm_app')
@section('title')
     Payment URL
@endsection
@section('content')		
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Payment URL</h3>
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
					<div class="x_panel">
						 
					 
						<div class="x_content" id="employee_table">
							<table id="datatable-all-counsellorPaymentMode" class="table table-striped table-bordered">
								<thead>
									<tr>
									<th>Counsellor Name</th>                          
									<th>URL</th>                          
								 									 
										 
									</tr>
								</thead>
							</table>
							
							

						</div>
					</div>
					
						 	  
				</div>
			</div>
		</div>
		<style>
			#followUpModal .form-control{
				margin-bottom:10px;
			}
		</style>
		
 
		 
	 
	</div>

	<!-- /page content -->
@endsection