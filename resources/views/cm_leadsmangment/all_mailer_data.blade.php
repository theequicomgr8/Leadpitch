@extends('layouts.cm_app')
@section('title')
     All Mailer  
@endsection
@section('content')		
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>All Mailer</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					 
					<div class="x_panel">
						 
						<div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
							<form method="GET" action="" novalidate autocomplete="off">
								 
								<div class="form-group">
									<div class="col-md-3">
										<label>Date From</label>
										<input type="text" class="form-control expdf" name="search[expdf]" value="{{ $search['expdf'] or "" }}" autocomplete="off" placeholder="Follow Date From">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Date To</label>
										<input type="text" class="form-control expdt" name="search[expdt]" value="{{ $search['expdt'] or "" }}" placeholder="Follow Date From">
									</div>
								</div>
								   
								 
							  
								<div class="form-group">
									<div class="col-md-3">
								 <label></label>

									 
										<button type="submit" class="btn btn-block btn-info">Filter</button>
									</div>
								</div>
							</form>
						</div>
						 
						<div class="x_content" id="employee_table">
							<table id="datatable-mailer_data" class="table table-striped table-bordered">
								<thead>
									<tr>	
										<th><input type="checkbox" id="check-all" class="check-box"></th>									
										<th>Date</th>
										<th>Name</th>
										<th>Mobile</th>
										<th>Email</th>
										<th>Technology</th>										 
										 
									</tr>
								</thead>
							</table>
							 @if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator' )
								<div class="form-group">
								<div class="col-md-1">
								
								 
								<button type="button" class="btn btn-success" onclick="javascript:leadController.selectMailerDelete()" >Delete</button>
								

								</div>
								</div>
								@endif
						

						</div>
					</div>
				</div>
			</div>
		</div>
		 
		
 
		</div>

	<!-- /page content -->
@endsection