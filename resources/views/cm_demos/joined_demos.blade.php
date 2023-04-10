@extends('layouts.cm_app')
 @section('title')
     Join Demo  
@endsection
@section('content')	
@php

@endphp
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Joined Demos/Admissions</h3>
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
						<!--div class="x_title">
							<h2>Leads <small>Listing</small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#">Settings 1</a>
										</li>
										<li><a href="#">Settings 2</a>
										</li>
									</ul>
								</li>
								<li><a class="close-link"><i class="fa fa-close"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div-->
						<div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
							<form method="GET" action="" novalidate autocomplete ="off">
								<div class="form-group">
									<div class="col-md-3">
										<label>Source</label>
										<select class="form-control" name="search[source]" id="source">
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
										<label>Join Date From</label>
										<input type="text" class="form-control expdf" name="search[joindf]" value="{{ $search['joindf'] or "" }}" id="joindf" autocomplete="off" placeholder="Enter join Date From">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Join Date To</label>
										<input type="text" class="form-control expdt" name="search[joindt]" value="{{ $search['joindt'] or "" }}" id="joindt" autocomplete="off" placeholder="Enter join Date To">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Lead Date From</label>
										<input type="text" class="form-control leaddf" name="search[leaddf]" value="{{ $search['leaddf'] or "" }}" id="leaddf" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Lead Date To</label>
										<input type="text" class="form-control leaddt" name="search[leaddt]" value="{{ $search['leaddt'] or "" }}" id="leaddt" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Call Date From</label>
										<input type="text" class="form-control calldf" name="search[calldf]" value="{{ $search['calldf'] or "" }}" id="calldf" placeholder="Call Date From">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Call Date To</label>
										<input type="text" class="form-control calldt" name="search[calldt]" value="{{ $search['calldt'] or "" }}" id="calldt" placeholder="Call Date To">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Technology</label>
										<select class="form-control select2_course" name="search[course]" multiple>
											<option value="">-- SELECT TECHNOLOGY --</option>
											@if(count($courses)>0)
												@foreach($courses as $course)
													@if(isset($search['course']) && $search['course']==$course->id)
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
										<select class="form-control" name="search[status][]" multiple>
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
														<option value="{{ $status->id }}" selected>{{ $status->name }}</option>
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
										<select class="form-control" name="search[user]" id="user">
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
										<label style="visibility:hidden">Filter</label>
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
							<table id="datatable-demo-joined-demos" class="table table-striped table-bordered">
								<thead>
									<tr>
										<!--th>Demo Date</th>
										<th>Name</th>
										<th>Mobile</th>
										<th>Demo Type</th>
										<th>Technology</th>
										<th>Sub Tech.</th>
										<th>Status</th>
										<th>Expected Date</th>
										<th>Remark</th>
										<th>Action</th-->
										
										<th><input type="checkbox" id="check-all" class="check-box"></th>
										<th>Reg_Date</th>
										<th>Join Date</th>
										<th>Name</th>
										<th>Source</th>
									<!--<th>Mobile</th>-->
										<th>Type</th>
								    
										<th>Technology</th>
										<th>Owner</th>
										<!--<th>Trainer</th>-->
										<th>Status</th>
										<th>Call Date</th>
										<th>FollowUp_Date</th>
										<!--<th>Action</th>-->
									</tr>
								</thead>
							</table>
							<form id="export-demos" method="POST" onsubmit="return exportJoinDemos()" action="{{ url('/demo/getdemosexcel') }}">
								{{ csrf_field() }}
								@php
								//dd($request->query('joindf');
								//dd(Request::get('search.joindf'));
								@endphp
								<input type="hidden" name="search[source]" id="hsource" value="">
								<input type="hidden" name="search[expdf]" id="hexpdf" value="">
								<input type="hidden" name="search[expdt]" id="hexpdt" value="">
								<input type="hidden" name="search[leaddf]" id="hleaddf" value="">
								<input type="hidden" name="search[leaddt]" id="hleaddt" value="">
								<input type="hidden" name="search[course]" id="hcourse" value="">
								<input type="hidden" name="search[status]" id="hstatus" value="">
								<input type="hidden" name="search[user]" id="huser" value="">
								<input type="hidden" name="search[value]" id="hvalue" value="">
								
								<input type="hidden" name="search[joindf]" id="hjoindf" value="">
								<input type="hidden" name="search[joindt]" id="hjoindt" value="">
								<input type="hidden" name="search[joined_demos]" value="2">
									@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<div class="form-group">
									<div class="col-md-2">
										<div class="row">
											<button type="submit" class="btn btn-success btn-block export-demos">Export</button>
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
								@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<button type="button" class="btn btn-success btn-block move-not-interested" onclick="javascript:demoController.moveToJoinDemos()">Move To Demo</button>
								@endif
								</div>
								</div>
								<div class="form-group">
								<div class="col-md-2">							 
								@if (Auth::user()->role =='super_admin' || Auth::user()->role =='administrator')
								<button type="button" class="btn btn-success btn-block" onclick="javascript:deletedDemoController.selectDeleteParmanent()">Delete Permt</button>
								@endif
								</div>
								</div>
							</form>
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
	<script src="https://code.jquery.com/jquery-3.6.3.js"></script>
	<script>
	    $(document).ready(function(){
	        $(".export-demos").click(function(){
	            var joindf=$("#joindf").val();
	            var joindt=$("#joindt").val();
	            var source=$("#source").val();
	            var leaddf=$("#leaddf").val();
	            var leaddt=$("#leaddt").val();
	            var calldf=$("#calldf").val();
	            var calldt=$("#calldt").val();
	            var user=$("#user").val();
	            
	            $("#hjoindf").attr('value',joindf);
	            $("#hjoindt").attr('value',joindt);
	            $("#hsource").attr('value',source);
	            $("#hleaddf").attr('value',leaddf);
	            $("#hleaddt").attr('value',leaddt);
	            $("#hcalldf").attr('value',calldf);
	            $("#hcalldt").attr('value',calldt);  
	            $("#huser").attr('value',user);
	        });
	    });
	</script>
@endsection