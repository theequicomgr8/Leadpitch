@extends('layouts.cm_app')
@section('title')
     All Lead  Managment
@endsection
@section('content')		
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>All Leads Managment</h3>
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
							<table id="" class="table table-striped table-bordered">
								<thead>
									<tr role="row">
									
									<th>									
									<input type="checkbox" id="check-all" class="check-box"></th>
									<th>Name</th>
									<th>Source</th>
									<th>Technology</th>
									<th>Owner</th>
									<th>Status</th>
									<th>Entry Date</th>
									<th>Call Date</th>
									<th>FollowUp Date</th>
									<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<tr>
								
								<td><input type="checkbox" class="check-box" value="80831"></td>								
								<td>Amit</td>
								<td>Website</td>								
								<td>Java Training</td>
								<td>Brijesh</td>
								<td>New Lead</td>
								<td>08-12-2021 03:46:49</td>
								<td></td>
								<td>08-12-2021 03:46 PM</td>
								<td></td>
								<!--<td><a href="javascript:void(0)" data-toggle="popover" title="" data-content="Remark Not Available" data-trigger="hover" data-placement="left" data-original-title="Counsellor Remark"><i aria-hidden="true" class="fa fa-comment"></i></a> | <a href="javascript:leadController.getfollowUps(80831)" title="followUp"><i class="fa fa-eye" aria-hidden="true"></i></a> | <a href="javascript:leadController.getExpectFollowUps(80831)" title="Expect Demo FollowUp"><i class="fa fa-meetup" aria-hidden="true"></i></a> | <a href="/lead/update/80831" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>-->
								
								
								</tr>
								
								<tr>								
								<td><input type="checkbox" class="check-box" value="80831"></td>								
								<td>Amit</td>
								<td>Website</td>								
								<td>Java Training</td>
								<td>Brijesh</td>
								<td>New Lead</td>
								<td>08-12-2021 03:46:49</td>
								<td></td>
								<td>08-12-2021 03:46 PM</td>
								<td></td>								
								</tr>
								
								 </tbody>
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