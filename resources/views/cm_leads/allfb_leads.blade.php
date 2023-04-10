@extends('layouts.cm_app')
 @section('title')
     All FaceBook Lead  
@endsection
@section('content')	
@php 
//dd('working');
@endphp
<style>
    #popup {
/*    display: none;*/
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

#popup:target {
    display: block;
}

.popup-content {
    position: relative;
    top: 30%;
    left: 50%;
    width: 300px;
    height: 207px;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
}

.close-buttor {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    color: black;
    text-decoration: none;
}

</style>
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Facbook Leads</h3>
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
											<!--<option value="">-- SELECT SOURCE --</option>-->
											<!--@if(count($sources)>0)-->
											<!--	@foreach($sources as $source)-->
											<!--		@if(isset($search['source']) && $search['source']==$source->id)-->
											<!--			<option value="{{ $source->id }}" selected>{{ $source->name }}</option>-->
											<!--		@else-->
											<!--			<option value="{{ $source->id }}">{{ $source->name }}</option>-->
											<!--		@endif-->
											<!--	@endforeach-->
											<!--@endif-->
											<option value="">FaceBook</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Follow Up Date From</label>
										<input type="text" class="form-control expdf" name="search[expdf]" value="{{ $search['expdf'] or "" }}" autocomplete="off" placeholder="Follow Date From">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Follow Up Date To</label>
										<input type="text" class="form-control expdt" name="search[expdt]" value="{{ $search['expdt'] or "" }}" placeholder="Follow Date From">
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
										<label>Call Date From</label>
										<input type="text" class="form-control calldf" name="search[calldf]" value="{{ $search['calldf'] or "" }}" placeholder="Call Date From">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Call Date To</label>
										<input type="text" class="form-control calldt" name="search[calldt]" value="{{ $search['calldt'] or "" }}" placeholder="Call Date To">
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
								<div class="form-group" style="display:none1;">
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
										 
									</div>
								</div>
								@endif
								
									<div class="form-group" style="display:none;">
									<div class="col-md-3">
										<label>Category</label>
										<select class="form-control" name="search[category]">
											<option value="">-- SELECT CATEGORY --</option>
											@if(count($categorys)>0)
												@foreach($categorys as $category)
													@if(isset($search['category']) && $search['category']==$category->id)
														<option value="{{ $category->id }}" selected>{{ $category->category }}</option>
													@else
														<option value="{{ $category->id }}">{{ $category->category }}</option>
													@endif
												@endforeach
											@endif
										</select>
									</div>
								</div>
									<div class="form-group">
									<div class="col-md-3">
								 <label></label>

									 
										<button type="submit" class="btn btn-block btn-info" style="margin-top: 4px;">Filter</button>
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
						<div class="x_content" id="employee_table" style="pointer-events: auto;">
							<table id="datatable-fblead" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th><input type="checkbox" id="check-all" class="check-box"></th>
										<th>Name</th>
										<!--<th>Mobile</th>-->
										<th>Source</th>
										<th>Technology</th>
										<!--<th>Source</th>-->
										<th>Owner</th>
										<!--th>Sub Tech.</th-->
										<th>Status</th>
										<th>Entry Date</th>
										<th>Call Date</th>
										<th>FollowUp Date</th>
										<!--th>Remark</th-->
										<th>Action</th>
									</tr>
								</thead>
							</table>
							 
						 

						</div>
					</div>
					
					<div class="row"> 
							 <div class="col-md-12"> 
							 
							<div class="col-md-1">
							<!--<button type="button" style="padding-left: 2px;" class="btn btn-success btn-block bulk-sms" onclick="javascript:leadController.bulkSms()">Bulk SMS</button>-->
							</div>
							
							@if (Auth::user()->role =='super_admin')									 
								<div class="col-md-1" style="">	 
								<button type="button" class="btn btn-danger  btn-block" onclick="javascript:leadController.selectDelete()" >Delete</button> 
								</div>
								 
							@endif
							
							<div class="col-md-1">
							<form id="export-leads" method="POST" onsubmit="return exportLeads()" action="{{ url('/fblead/getleadsexcel') }}">
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
							@if (Auth::user()->role =='super_admin')							 
							<button type="submit" class="btn btn-success  btn-block export-leads">Export</button>		
							@endif						
							</form>
							</div>
							
							<div class="col-md-2">		
							<form id="export-sales-leads" method="POST" onsubmit="return exportSalesLeads()" action="{{ url('/lead/getleadsSalesTeamexcel') }}">
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
								@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<!--<button type="submit" class="btn btn-success export-leads">Course Export</button>	-->
								 
								@endif
							</form>
							</div>
							<div class="col-md-2">	<!--//action="{{ url('/lead/getleadsSalesCounsellorTeamexcel') }}"  -->
							<form id="export-sales-CounsellorLeads-leads" method="POST" onsubmit="return exportSalesCounsellorLeads(this)" >
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
								@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
							
							<div class="form-group">
									
										<!--<button type="submit" class="btn btn-success export-leads">Counsellor Export</button>					-->
									</div>
								
								@endif
							</form>
							</div>
							
							@if (Auth::user()->role =='super_admin' )	
							<!--<div class="col-md-2">-->
							<!--<button type="button" class="btn btn-success  btn-block move-not-interested" onclick="javascript:leadController.moveNotInterested()" >Move to Not Int.</button>-->
							<!--</div>-->
							@endif
								 
							<!--<div class="col-md-2">-->
							<!--<button type="button" class="btn btn-success  btn-block move-not-interested" onclick="javascript:leadController.moveToExptLead()" >Move to Expt Visit.</button>-->
							<!--</div>-->
								 
						 

						 
							
							 </div>
							 </div> 
							 
							  <div class="row"> 
							 <div class="col-md-12"> 
							 	@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator' )							 
							<!--<div class="col-md-2">-->
							<!--<button type="button" class="btn btn-success  btn-block" onclick="javascript:leadController.selectToNewLeads()" title="Delete followup and fresh new lead">Create New Leads</button>-->
							<!--</div>-->
						 
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
				
				</div>

			</div>
		</div>
	</div>
	
<div id="popup" style="display:none;">
    <div class="popup-content">
        <!-- <a href="#" class="close-buttor" id="closeex">X</a> -->
        <p>Do you want to assign the lead :</p>
        @php 
        $id=Session::get('leadID');
        @endphp
        <div class="row">
        	<div class="col-md-12">
        		<form method="get" action="facbook-lead-manual">

        			<input type="hidden" name="leadID" id="leadID" value="">
        			<input type="hidden" name="courseID" id="courseID" value="">
        			<input type="hidden" name="remark" id="remark" value="">
        			<div class="form-group">
        				<label>Automatic</label>
        				<input type="radio" name="assign" value="1" checked>
        			</div>
        			<div class="form-group">
        				<label>Manual</label>
        				<input type="radio" name="assign" value="0">
        			</div>

        			<input type="submit" value="Ok" class="btn btn-info">
        			<!--<a href="" class="btn btn-warning cancel">Cancel</a>-->
        		</form>
        	</div>
        </div>
    </div>
</div>

@endsection