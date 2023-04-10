@extends('layouts.cm_app')
@section('title')
    Search Lead And Demo
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h4>Search Lead</h4>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						 
						<div class="x_content">
							<br />
							<!--form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" onsubmit="return leadController.submit(this)"-->
							<form id="searchLeadAndDemo-form" class="form-horizontal form-label-left">
								{{ csrf_field() }}
								<input type="hidden" name="id" value="">
								<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="mobile" class="form-control col-md-6 col-xs-12" value="{{ old('mobile') }}" placeholder="Search Mobile">
										@if ($errors->has('mobile'))
											<span class="help-block">
												<strong>{{ $errors->first('mobile') }}</strong>
											</span>
										@endif
										<a class="btn btn-info searchLeadAndDemo" style="position:absolute;right:5px;">Search</a>
									</div>
								</div>
								  
								 
							</form>
								<div class="x_title">
							 
							<h4>Lead Data </h4>
							 
							<div class="clearfix"></div>
						</div> 
				<div class="table-responsive" id="leadsPayloadModal">
					<table class="table table-bordered" id="datatable-leadjoined">
						<thead>
							<tr>
								 
								<th>Date</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Course</th>
								<th>Trainer</th>
								<th>Owner</th>
								<th>Lead Type</th>
								<th>Other</th>
								
							</tr>
						</thead>
						<tbody>
							 
						</tbody>
					</table>
					 
				</div>	
				<div class="x_title">
							 
							<h4>Demo Data </h4>
							 
							<div class="clearfix"></div>
						</div> 
				<div class="table-responsive" id="demosPayloadModal">
					<table class="table table-bordered" id="datatable-demojoined">
						<thead>
							<tr>
							 
								<th>Date</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Course</th>
								<th>Trainer</th>
								<th>Owner</th>
								<th>Lead Type</th>
								<th>Other</th>
								
							</tr>
						</thead>
						<tbody>
							 
						</tbody>
					</table>
					 
				</div>
				 
								
								 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<div id="leadDemoJoined" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						 
					</div>
					<div class="modal-body" style="padding-top:0">
					</div>
					 
				</div>

			</div>
		</div>	
	<!--<div id="leadsPayloadModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Name</th>
								<th>Mobile</th>
								<th>Course</th>
								<th>Owner</th>
								<th>Select One</th>
							</tr>
						</thead>
						<tbody>
							 
						</tbody>
					</table>
					<div class="pull-right">
					<button type="button" class="btn btn-default choke">Submit</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
				 
			</div>
		</div>
	</div>-->
	<!-- /page content -->
@endsection