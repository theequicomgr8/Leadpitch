@extends('layouts.cm_app')

@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Transfer (Leads or Demos)</h3>
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
					@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<div class="x_panel">
						<div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
							<form method="POST" action="" novalidate>
							    {{ csrf_field() }}
								<div class="form-group">
									<div class="col-md-3">
										<label>Transfer Parmanently</label>
										<select class="form-control" name="transfer">
											<option value="">Select Transfer</option>
											<option value="all_leads">All Leads</option>									 
											<option value="all_demos">All Demos</option>											 
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>From Date</label>
										<input type="text" class="form-control leaddf" name="leaddf">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>To Date</label>
										<input type="text" class="form-control leaddt" name="leaddt">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label>Technology</label>
										<select class="form-control select2_course" name="course" multiple>
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
								@if(count($users)>0)
								<div class="form-group">
									<div class="col-md-3">
										<label>From Owner</label>
										<select class="form-control" name="transfer_from">
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
								@if(count($users)>0)
								<div class="form-group">
									<div class="col-md-3">
										<label>To New Owner</label>
										<select class="form-control" name="transfer_to">
											<option value="">-- SELECT USER --</option>
												@foreach($users as $user)
													@if(isset($search['user']) && $search['user']==$user->id)
														<option value="{{ $user->id }}" selected>{{ $user->name }}</option>
													@else
														<option value="{{ $user->id }}">{{ $user->name }}</option>
													@endif
												@endforeach
										</select>
										<button type="submit" class="btn btn-block btn-info">Transfer</button>
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
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection