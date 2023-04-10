@extends('layouts.cm_app')
@section('title')
      Lead Joined
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h4>Lead Add To Demo and Fees Students Pending</h4>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<!--h2>Lead Form <small>(Add New Lead)</small></h2-->
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<!--form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" onsubmit="return leadController.submit(this)"-->
							<form id="leadDemoJoined-form" class="form-horizontal form-label-left" onsubmit="return demoController.submit(this)">
								{{ csrf_field() }}
								<input type="hidden" name="id" value="">
								<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="mobile" class="form-control col-md-6 col-xs-12" value="{{ old('mobile') }}" placeholder="Enter Mobile">
										@if ($errors->has('mobile'))
											<span class="help-block">
												<strong>{{ $errors->first('mobile') }}</strong>
											</span>
										@endif
										<a class="btn btn-info leadjoinded" style="position:absolute;right:5px;">Verify</a>
									</div>
								</div>
								  
								 
							</form>
								<div class="x_title">
							 
							<h4>Lead Table Data </h4>
							 
							<div class="clearfix"></div>
						</div> 
				<div class="table-responsive" id="leadsPayloadModal">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Select One</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Course</th>
								<th>Trainer</th>
								<th>Owner</th>
								<th>Action</th>
								
							</tr>
						</thead>
						<tbody>
							 
						</tbody>
					</table>
					 
				</div>	
				<div class="x_title">
							 
							<h4>Demo Table Data </h4>
							 
							<div class="clearfix"></div>
						</div> 
				<div class="table-responsive" id="demosPayloadModal">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Select One</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Course</th>
								<th>Trainer</th>
								<th>Owner</th>
								<th>Action</th>
								
							</tr>
						</thead>
						<tbody>
							 
						</tbody>
					</table>
					 
				</div>
				 
								
								<div class="form-group">
								<div class="col-md-2">
						    	@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<button type="button" class="btn btn-success  btn-block move-joined-demos" onclick="javascript:demoController.leadDemoJoined()" >Joined</button>

								@endif
								</div>
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