@extends('layouts.cm_app')
@section('title')
     Dashboard  
@endsection
@section('content')
<style>
.widget_summary{
	margin-bottom: -8px;
}
</style>
 

	<div class="right_col" role="main" ng-controller="counsellorDashboarddd">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">	 
				<div class="x_panel">
					<div class="x_title">
						<h2>Upcoming Batches</h2>
								<div class="col-md-8">
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Create New Batches</button>
								</div>
						<div class="clearfix"></div>
					</div>
					<div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
						<form method="GET" action="" novalidate onsubmit="return filterAjaxUpcomingData('datatable-upcoming-batches',this)">
							<div class="form-group">
								<div class="col-md-3">
									<label>Trainer</label>
									<select class="form-control select_trainerb" name="search[trainer]" >
									<option value="">Select Trainer</option>
									@if($feesGetTrainer) 
										@foreach($feesGetTrainer as $trainer)
											@if(isset($trainer->id) && ($trainer->id==$search['trainer']))
												<option value="{{ $trainer->id }}" selected>{{ $trainer->name }}</option>
											@else
												<option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
											@endif
										@endforeach
									@endif
									</select>
								
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<label>Course</label>
									<select class="form-control select_courseb" name="search[course]" >
									<option value="">Select Courses</option>
									@if($courses) 
										@foreach($courses as $course)
											@if(isset($course->id) && ($course->id==$search['course']))
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
									<button type="submit" class="btn btn-block btn-info" style="margin-top:23px">Filter</button>								
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
					<div class="x_content">
						 
						<table id="datatable-upcoming-batches" class="table table-striped table-bordered">
							<thead>
								<tr> 
									<th>Trainer</th>
									<th>Course</th>
									<th>Training Slot</th>
									<th>Start Batch Date</th>
									<th>Batches Time</th>
									<th>Owner</th>
									<th>Training Mode</th>
									<th>Counsellor</th>
								</tr>
							</thead>
						</table>
						 
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Create Upcoming Batches</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">
					 <form action="" method="post" onsubmit="return batchController.submit(this)">
					 <div> 
					 
						<div class="form-group">
							<label for="recipient-name" class="col-form-label">Trainer Name</label>
							<select class="form-control select_trainerb" name="trainer" style="width:100%;">
								<option value="">Select Trainer</option>
								@if($feesGetTrainer)
								@foreach($feesGetTrainer as $trainer)
								<option value="<?php echo $trainer->id ?>"><?php  echo $trainer->name; ?></option>
								@endforeach
								@endif		  
							</select>	
						</div>
					
						<div class="form-group">
							<label for="recipient-name" class="col-form-label">Course:</label>
							<select class="form-control select_courseb" name="course" style="width:100%;">
									<option value="">Select course</option>
									@if($courses)
									@foreach($courses as $course)
									<option value="<?php echo $course->id ?>"><?php  echo $course->name; ?></option>
									@endforeach
									@endif
							</select>
						</div>
					
						<div class="form-group">
							<label for="recipient-name" class="col-form-label">Slot:</label>
							<select class="form-control" name="slot">
							  <option value="Weekday">Weekday</option>
							  <option value="Weekend">Weekend</option>
							  <option value="Both/Weekday/Weekend">Both/Weekday/Weekend</option>
							</select>
						</div>
						<div class="form-group">
							<label for="recipient-name" class="col-form-label">Start Date:</label>
							<!--<input type="date" class="form-control" id="recipient-name" name="batch_starts_on">-->
							<input type="datetime" class="form-control expdf"  name="batch_starts_on" placeholder="Select batch Start date">
						</div>
						<div class="form-group">
							<label for="recipient-name" class="col-form-label">Start Time:</label>
								<input type="text" class="form-control "  name="start_time" placeholder="Select batch Time">
						</div>
						<div class="form-group">
							<label for="recipient-name" class="col-form-label">Counsellor:</label>
							 
							<select class="form-control select_courseb" name="counsellor" style="width:100%;">
							@if($counsellors)
								@foreach($counsellors as $counsellor)
								<option value="<?php echo $counsellor->id ?>"><?php  echo $counsellor->name; ?></option>
									@endforeach
									@endif
							</select>
							
						</div>
						 
						<div class="form-group">
							<label for="recipient-name" class="col-form-label">Mode (Online/Offline/Both):</label>
							<select class="form-control" name="mode">
							  <option value="online">Online</option>
							  <option value="offline">Offline</option>
							  <option value="both">Both/Online/Offline</option>
							</select>
						</div>
						<div class="modal-footer">
							<!--<input type="hidden" name="action" value="batchesSave"/>-->
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" name="submit" value="submit" class="btn btn-primary">Save Batches </button>
						</div>
						</div>
				 </form>
				</div>
			</div>
		</div>
	</div>
		
		
		
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				 
				<div class="x_panel">
					<div class="x_title">
						<h2>Pending Leads</h2>
						<div class="clearfix"></div>
					</div>
					<div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
						<form method="GET" action="" novalidate onsubmit="return filterAjaxLeadData('datatable-pending-leads',this)">
							<div class="form-group">
								<div class="col-md-3">
									<label>Follow Up Date From</label>
									<input type="text" class="form-control expdf" name="search[expdf]" value="{{ $search['expdf'] or "" }}" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<label>Follow Up Date To</label>
									<input type="text" class="form-control expdt" name="search[expdt]" value="{{ $search['expdt'] or "" }}" autocomplete="off">
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
					<div class="x_content">
						 
						<table id="datatable-pending-leads" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th><input type="checkbox" id="check-all" class="check-box"></th>
									<th>Date</th>
									<th>Name</th>
									<!--<th>Mobile</th>-->
									<th>Technology</th>
									<th>Type</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
						<form id="export-leads-pending" method="POST" onsubmit="return exportLeadsPending()" action="{{ url('/dashboard/counsellor/pending-leads/getleadsexcel') }}">
							{{ csrf_field() }}
							<input type="hidden" name="search[expdf]" value="">
							<input type="hidden" name="search[expdt]" value="">
							<input type="hidden" name="search[course]" value="">
							<input type="hidden" name="search[status]" value="">
							<input type="hidden" name="search[value]" value="">
							<input type="hidden" name="search[counsellor]" value="">
							@if (Auth::user()->role =='super_admin')
							<div class="form-group">
								<div class="col-md-2">
									<div class="row">
									    
										<button type="submit" class="btn btn-success btn-block export-leads">Export</button>
									</div>
								</div>
							</div>
								@endif
							<div class="form-group">
								<div class="col-md-2">
									<button type="button" class="btn btn-success btn-block bulk-sms" onclick="javascript:leadController.bulkSms()">Bulk SMS</button>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-2"> 
								<button type="button" class="btn btn-success  btn-block move-not-interested" onclick="javascript:leadController.moveToExptLead()" >Move to Expt Visit.</button>
							

								</div>
								</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="alert alert-danger hide"></div>
				<div class="alert alert-success hide"></div>
				@if(Session::has('success_msg'))
					<div class="alert alert-success">{{Session::get('success_msg')}}</div>
				@endif
				<div class="x_panel">
					<div class="x_title">
						<h2>Pending Demo</h2>
						<div class="clearfix"></div>
					</div>
					<div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
						<form method="GET" action="" novalidate onsubmit="return filterAjaxDemoData('datatable-pending-leads-demos',this)">
							<div class="form-group">
								<div class="col-md-3">
									<label>Follow Up Date From</label>
									<input type="text" class="form-control expdf" name="search[expdf]" value="{{ $search['expdf'] or "" }}" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<label>Follow Up Date To</label>
									<input type="text" class="form-control expdt" name="search[expdt]" value="{{ $search['expdt'] or "" }}" autocomplete="off">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-3">
									<label>Technology</label>
									<select class="form-control select2_course" name="search[courses][]" multiple>
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
									<select class="form-control select2_status" name="search[statuss][]" multiple>
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
					<div class="x_content">
						 
						<table id="datatable-pending-leads-demos" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th><input type="checkbox" id="check-all" class="check-box"></th>
									<th>Date</th>
									<th>Name</th>
									<!--<th>Mobile</th>-->
									<th>Technology</th>
									<th>Type</th>
									<th>Status</th>
									<th>Action</th>
								</tr> 
							</thead>
						</table>
						<form id="export-leads-demos-pending" method="POST" onsubmit="return exportLeadsDemosPending()" action="{{ url('/dashboard/counsellor/pending-leads-demos/getleadsdemosexcel') }}">
							{{ csrf_field() }}
							<input type="hidden" name="search[expdf]" value="">
							<input type="hidden" name="search[expdt]" value="">
							<input type="hidden" name="search[courses]" value="">
							<input type="hidden" name="search[statuss]" value="">
							<input type="hidden" name="search[value]" value="">
							<input type="hidden" name="search[counsellor]" value="">
								@if (Auth::user()->role =='super_admin')
							<div class="form-group">
								<div class="col-md-2">
									<div class="row">
										<button type="submit" class="btn btn-success btn-block export-leads">Export</button>
									</div>
								</div>
							</div>
							@endif
							<div class="form-group">
								<div class="col-md-2">
									<button type="button" class="btn btn-success btn-block bulk-sms" onclick="javascript:demoController.bulkSms()">Bulk SMS</button>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-2">						    
								<button type="button" class="btn btn-success  btn-block move-joined-demos" onclick="javascript:demoController.moveToExpectedNewBatchDemos()" >Move To New Batch</button>					 
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div> 
		 
		<div class="row">
			<div class="col-md-8 col-sm-8 col-xs-8">
				  @if(Auth::user()->current_user_can('super_admin')  || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('manager'))
				<div class="x_panel">
					<div class="x_title">
						<h2>Counsellor Status </h2>	
						<div class="clearfix"></div>	
					</div>	
					<style>
					.check-box{
						height:18px;
						width:20px;
						cursor:pointer;
					}
					</style>					
					<div class="x_content">						 
						<table id="datatable-counsellors" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr> 
									<th>Name</th>
									<th>Follow Up</th>
									 
									<th>Action</th>
								</tr>
								  
							</thead>
						</table>
						  
					</div>
				</div>
				@endif
			</div>
			
			<div class="col-md-4 col-sm-4 col-xs-4">
			
			<div class="chat-panel panel panel-default">
                        <!--<div class="panel-heading">
                            <i class="fa fa-comments fa-fw"></i>
                            Chat
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu slidedown">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-refresh fa-fw"></i> Refresh
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-check-circle fa-fw"></i> Available
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-times fa-fw"></i> Busy
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-clock-o fa-fw"></i> Away
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-sign-out fa-fw"></i> Sign Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
							
                        </div>-->
							
			<div class="x_panel tile fixed_height_320" >				 
				<div class="x_title calling_status">
					<h2>Daily Calling Status (<small><%fromDate%> - <%toDate%></small>)</h2>	
					<style>
					.cancelBtn{
					display:inherit;
					width:-webkit-fill-available;
					}
					#reportrange span{
					display:none;
					}
					</style>						
					<ul class="nav navbar-right panel_toolbox">
						<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
						<li id="reportrange_test" >
							<a href="javascript:void(0)" ng-click="showDCS_DateRangePicker()">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
								<span></span> <b class="caret"></b>
							</a>
							<div ng-show="dcs_DateRangePicker" class="well" style="position:absolute;top:40px;left:0;right:auto;display:block;max-width:none;z-index:3001;
							direction:ltr;text-align:left;float:left;width:300px;margin-left:-253px;">
								<div class="col-xs-6"><label>From:</label><input type="text" class="form-control fromDate" value="<?php //echo date('Y-m-d')?>" /></div>
								<div class="col-xs-6"><label>To:</label><input type="text" class="form-control toDate" value="<?php //echo date('Y-m-d')?>" /></div>
								<div class="col-xs-6 col-xs-offset-6" style="padding-top:10px;"><input type="submit" value="submit" class="btn btn-success btn-block" ng-click="getCallingStatus()" /></div>
							</div>
						</li>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="widget_summary">
						<div class="w_left w_25">
							<span>New Leads</span>
						</div>
						<div class="w_center w_55">
							<div class="progress">
								<div class="progress-bar bg-blue" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_new_lead_percent%>%;">
									<span class="sr-only"><%dcs_new_lead_percent%>% Complete</span>
								</div>
							</div>
						</div>
						<div class="w_right w_20">
							<span class="blue"><%dcs_new_lead%></span>
						</div>
						<div class="clearfix"></div>
					</div>

					<div class="widget_summary">
					<div class="w_left w_25">
					<span>Interested</span>
					</div>
						<div class="w_center w_55">
							<div class="progress">
								<div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_interested_percent%>%;">
									<span class="sr-only"><%dcs_interested_percent%>% Complete</span>
								</div>
							</div>
						</div>
						<div class="w_right w_20">
							<span class="green"><%dcs_interested%></span>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="widget_summary">
						<div class="w_left w_25">
							<span>Pending</span>
						</div>
						<div class="w_center w_55">
							<div class="progress">
								<div class="progress-bar bg-red" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_pending_percent%>%;">
									<span class="sr-only"><%dcs_pending_percent%>% Complete</span>
								</div>
							</div>
						</div>
						<div class="w_right w_20">
							<span class="red"><%dcs_pending%></span>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="widget_summary">
						<div class="w_left w_25">
							<span>Not Intr...</span>
						</div>
						<div class="w_center w_55">
							<div class="progress">
								<div class="progress-bar bg-light-red" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_not_interested_percent%>%;">
									<span class="sr-only"><%dcs_not_interested_percent%>% Complete</span>
								</div>
							</div>
						</div>
						<div class="w_right w_20">
							<span class="light-red"><%dcs_not_interested%></span>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="widget_summary">
						<div class="w_left w_25">
							<span>Visits</span>
						</div>
						<div class="w_center w_55">
							<div class="progress">
								<div class="progress-bar bg-purple" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_visits_percent%>%;">
									<span class="sr-only"><%dcs_visits_percent%>% Complete</span>
								</div>
							</div>
						</div>
						<div class="w_right w_20">
							<span class="purple"><%dcs_visits%></span>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="widget_summary">
						<div class="w_left w_25">
							<span>Joined</span>
						</div>
						<div class="w_center w_55">
							<div class="progress">
								<div class="progress-bar bg-dark-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_joined_percent%>%;">
									<span class="sr-only"><%dcs_joined_percent%>% Complete</span>
								</div>
							</div>
						</div>
						<div class="w_right w_20">
							<span class="dark-green"><%dcs_joined%></span>
						</div>
						<div class="clearfix"></div>
					</div>
				@if(Auth::user()->current_user_can('super_admin')  || Auth::user()->current_user_can('administrator')) 
				Lead:<%leadlast%> Demo:<%demolast%>
				@endif
				<h5>Total Call Count: <span style="float:right"><%dcs_total_call_count_leads%>[L] / <%dcs_total_call_count_demos%>[D]</span> </h5>
				</div>
				</div>
				<div class="panel-body" id="chatshow" style="overflow:scroll; height: 472px;"></div>
				<div class="panel-footer">
					<div id="chatingshow"></div>
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
				</div>

			</div>
		</div>
		
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
					 
				</div>

			</div>
		</div>
	</div>
 
<script>
var messageBody = document.querySelector('#chatshow');
	messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
	
</script>
<script>
    $(document).ready(function () {
      $('#myTable').DataTable();
    });
  </script>
 

@endsection