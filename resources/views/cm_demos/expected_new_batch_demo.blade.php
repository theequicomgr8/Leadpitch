@extends('layouts.cm_app')
 @section('title')
     Expected New Batch Demo  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>All Expected New Batch Demos</h3>
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
						 
						<div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
							<form method="GET" action="" novalidate autocomplete="off">
								<div class="form-group">
									<div class="col-md-3">
										<label>Source</label>
										<select class="form-control" name="search[source]">
											<option value="">-- SELECT SOURCE --</option>
											@if(count($sources)>0)
												@foreach($sources as $source)
													@if(isset($search['source']) && $search['source']==$source->id)
														<option value="{{ $source->id }}" selected>{{ $source->name }}</option>
													@else
														<option value="{{ $source->id }}">{{ $source->name }}</option>
													@endif
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Expected Date From</label>
										<input type="text" class="form-control expdf" name="search[expdf]" value="{{ $search['expdf'] or "" }}">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Expected Date To</label>
										<input type="text" class="form-control expdt" name="search[expdt]" value="{{ $search['expdt'] or "" }}">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Lead Date From</label>
										<input type="text" class="form-control leaddf" name="search[leaddf]" value="{{ $search['leaddf'] or "" }}">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Lead Date To</label>
										<input type="text" class="form-control leaddt" name="search[leaddt]" value="{{ $search['leaddt'] or "" }}">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Technology</label>
										<select class="form-control select2_course" name="search[course][]" multiple>
											<option value="">-- SELECT TECHNOLOGY --</option>
											@if(count($courses)>0)
												@if(isset($search['course']))
													@foreach($search['course'] as $value)
														{{--*/ $courseSelected[] = $value /*--}}
													@endforeach
												@endif
												@foreach($courses as $course)
													@if(isset($courseSelected) && in_array($course->id,$courseSelected))
														<option value="{{ $course->id }}" selected>{{ $course->name }}</option>
													@else
														<option value="{{ $course->id }}">{{ $course->name }}</option>
													@endif
												@endforeach
											@endif
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Status</label>
										<select class="form-control select2_status" name="search[status][]" multiple>
											<option value="">-- SELECT STATUS --</option>
											@if(count($statuses)>0)
												@if(isset($search['status']))
													@foreach($search['status'] as $value)
														{{--*/ $statusSelected[] = $value /*--}}
													@endforeach
												@endif
												@foreach($statuses as $status)
													@if(isset($statusSelected) && in_array($status->id,$statusSelected))
														<option value="{{ $status->id }}" selected>{{ $status->name }}</option>
													@else
														<option value="{{ $status->id }}">{{ $status->name }}</option>
													@endif
												@endforeach
											@endif
										</select>
									</div>
								</div>
								@if(count($users)>0)
								<div class="form-group">
									<div class="col-md-3">
										<label>User</label>
										<select class="form-control" name="search[user]">
											<option value="">-- SELECT USER --</option>
												@foreach($users as $user)
													@if(isset($search['user']) && $search['user']==$user->id)
														<option value="{{ $user->id }}" selected>{{ $user->name }}</option>
													@else
														<option value="{{ $user->id }}">{{ $user->name }}</option>
													@endif
												@endforeach
										</select>
										<button type="submit" class="btn btn-block btn-info">Filter</button>
									</div>
								</div>
								@endif
								<!--div class="form-group">
									<div class="col-md-3">
										<label style="visibility:hidden">Filter</label>
										<button type="submit" class="btn btn-block btn-info">Filter</button>
									</div>
								</div-->
							</form>
						</div>
						<style>
						.check-box{
							height:18px;
							width:20px;
							cursor:pointer;
						}
						</style>
						<div class="x_content">
							<table id="datatable-expected-new-batch-demo" class="table table-striped table-bordered">
								<thead>
									<tr>
										  
										<th><input type="checkbox" id="check-all" class="check-box"></th>
										<th>Demo_Date</th>
										<th>Name</th>
									<!--<th>Mobile</th>-->
										<th>Type</th>
										<!--th>Source</th-->
										<th>Technology</th>
										<th>Owner</th>
										<th>Status</th>
										<th>Call Date</th>
										<th>FollowUp_Date</th>
										<th>Action</th>
									</tr>
								</thead>
							</table>
							<form id="export-demos" method="POST" onsubmit="return exportDemos()" action="{{ url('/demo/getdemosexcel') }}">
								{{ csrf_field() }}
								<input type="hidden" name="search[source]" value="">
								<input type="hidden" name="search[expdf]" value="">
								<input type="hidden" name="search[expdt]" value="">
								<input type="hidden" name="search[leaddf]" value="">
								<input type="hidden" name="search[leaddt]" value="">
								<input type="hidden" name="search[course]" value="">
								<input type="hidden" name="search[status]" value="">
								<input type="hidden" name="search[user]" value="">
								<input type="hidden" name="search[value]" value="">
								<div class="form-group">
									<div class="col-md-2">
										<button type="button" class="btn btn-success btn-block bulk-sms" onclick="javascript:demoController.bulkSms()">Bulk SMS</button>
									</div>
								</div>
									@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator' )
								<div class="form-group">
									<div class="col-md-2">
										<div class="row"> 
											<button type="submit" class="btn btn-success btn-block export-leads">Export</button>
									
										</div>
									</div>
								</div>
									@endif
									@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<div class="form-group">
								<div class="col-md-2">
						    	
								<button type="button" class="btn btn-success btn-block move-not-interested" onclick="javascript:demoController.moveNotInterested()">Move to Not Int.</button>
								
								</div>

								</div>
								@endif
								
							<!--	<div class="form-group">
								<div class="col-md-2">
						    	@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<button type="button" class="btn btn-success  btn-block move-joined-demos" onclick="javascript:demoController.moveJoinedDemos()" >Move Joined Demo</button>

								@endif
								</div>
								</div>-->
								
									@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<div class="form-group">
								<div class="col-md-2">	
								<button type="button" class="btn btn-success btn-block" onclick="javascript:demoController.selectedDelete()">Delete</button>
							
								</div>
								</div>
								 	@endif
								
								<div class="form-group">
								<div class="col-md-2">
								<button type="button" class="btn btn-success  btn-block move-joined-demos" onclick="javascript:demoController.expectedNewBatchMoveToDemos()" >Batch Move To Demo</button>
								</div>
								</div>
								
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<style>
			#demoFollowUpModal .form-control{
				margin-bottom:10px;
			}
		</style>
		<div id="demoFollowUpModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Follow Up</h4>
					</div>
					<div class="modal-body" style="padding-top:0">
					</div>
					<!--div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div-->
				</div>

			</div>
		</div>
		<div id="demoBulkSmsModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Bulk SMS</h4>
					</div>
					<div class="modal-body">
						<textarea placeholder="Enter your custom message here..." id="bulkSmsControl" rows="10" class="form-control"></textarea>
						<div class="form-group" style="padding-top:20px;margin-bottom:0">
							<div class="col-md-3" style="margin:0 auto;float:none;">
								<button type="button" style="background-color:#169f85;color:#fff;" onclick="javascript:demoController.sendBulkSms()" class="btn btn-block">Submit</button>
							</div>
							<div class="clearfix"></div>
						</div>
						
					</div>
					<!--div class="modal-footer">
						<button type="button" class="btn btn-default" >Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div-->
				</div>

			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection