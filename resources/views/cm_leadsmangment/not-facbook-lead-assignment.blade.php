@extends('layouts.cm_app')
@section('title')
     Unassigned Leads
@endsection
@section('content')		
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Unassigned Leads </h3>
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
										<label>Lead Date From</label>
										<input type="text" class="form-control leaddf" name="search[leaddf]" value="{{ $search['leaddf'] or "" }}" placeholder="Create Date From">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Lead Date To</label>
										<input type="text" class="form-control leaddt" name="search[leaddt]" value="{{ $search['leaddt'] or "" }}" placeholder="Create Date From">
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
								 
								@if(count($users)>0)
								<div class="form-group">
									<div class="col-md-3">
										<label>Counsellor</label>

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
										 
									</div>
								</div>
								@endif
								<div class="form-group">
									<div class="col-md-3">
								 <label></label>

									 
										<button type="submit" class="btn btn-block btn-info">Filter</button>
									</div>
								</div>
							</form>
						</div>
						<style>
						.check-box{
							height:18px;
							width:20px;
							cursor:pointer;
						}
						</style>
						<div class="x_content" id="employee_table">
							<table id="facbook-data-table-not-lead-assignment" class="table table-striped table-bordered">
								<thead>
									<tr role="row">
								 	  <th><input type="checkbox" id="check-all" class="check-box"></th>
									<th>Date</th>
									<th>Name</th>
									<th>Mobile</th>							 						 
									<th>Course</th>
									<!--<th>Form Field</th>		-->
									<!--<th>Page</th>	-->
									<!--<th>Reason</th>-->
									<th>Action</th>
									</tr>
								</thead>
								<tbody> 
								
								 </tbody>
							</table>
							
							

						</div>
					</div>
						<div class="row"> 
					<div class="col-md-12">
					    
					    	<div class="col-md-3">
					<form id="export-unassign-leads" method="POST" onsubmit="return exportReceivedUnassignLeads()" action="{{ url('/lead/getleadsreceivedunassignexcel') }}">
					{{ csrf_field() }}
					<input type="hidden" name="search[source]" value="">
					 
					<input type="hidden" name="search[leaddf]" value="">
					<input type="hidden" name="search[leaddt]" value="">
					<input type="hidden" name="search[course]" value=""> 
					<input type="hidden" name="search[user]" value=""> 
					<input type="hidden" name="search[value]" value="">
					@if (Auth::user()->role =='super_admin')							 
					<button type="submit" class="btn btn-success  btn-block export-leads">Export</button>		
					@endif						
					</form>
					</div>	
					@if (Auth::user()->role =='super_admin' || 'administrator')									 
					<div class="col-md-3">	 
					<button type="button" class="btn btn-success  btn-block" onclick="javascript:leadmanagmentController.selectReceivedSoftDelete()" >Soft Delete</button> 
					</div>
					@endif	
					
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
		
 
		<div id="followUpModal" class="modal fade" role="dialog">
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
		<div id="bulkSmsModal" class="modal fade" role="dialog">
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
								<button type="button" style="background-color:#169f85;color:#fff;" onclick="javascript:leadController.sendBulkSms()" class="btn btn-block">Submit</button>
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