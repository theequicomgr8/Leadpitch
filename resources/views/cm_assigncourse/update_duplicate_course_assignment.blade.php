@extends('layouts.cm_app')
 @section('title')
     Update Duplicate Assign Course  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Update - Duplicate</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-8 col-sm-8 col-xs-8 col-xs-offset-2 ">
					<div class="x_panel">
						<div class="x_title">
							@foreach (['danger', 'warning', 'success', 'info'] as $msg)
								@if(Session::has('alert-' . $msg))
									<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
								@endif
							@endforeach
							@if (count($errors) > 0)
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
							<h2>Update Form <small>(Update Course)</small></h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form id="update-course" data-parsley-validate class="form-horizontal form-label-left" method="POST" action="">
								<div class="form-group">
									<label class="col-xs-12" for="name">Counsellors <span class="required">*</span></label>
									<div class="col-xs-12">
										{{ csrf_field() }}
										<select class="select2_assignuser form-control" name="counsellors" tabindex="-1">
										 <option value="">Please Counsellor</option>   
										@if(!empty($users))
												@foreach($users as $user)
													 
														<option value="{{ $user->id }}" @if ($user->name== old('user'))
									 selected="selected"	
									@else
									{{ (isset($edit_data) && $edit_data->counsellors ==$user->id   ) ? "selected":"" }} @endif>{{ $user->name }}</option>
													 
												@endforeach
											@endif
										</select>
									</div>
								</div>
								
								
								<div class="form-group">
									<label class="col-xs-12" for="name">Duplicate Dom Course <span class="required">*</span></label>
									<div class="col-xs-12">
										{{ csrf_field() }}
										<select class="select2_select form-control" name="assign_dup_dom_course[]" tabindex="-1" multiple>
										 <option value="">Please Course</option>   
										 <?php echo $destinationDomCounsellors;  ?>
											 
											
											
											
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-xs-12" for="name">Duplicate Int Course <span class="required">*</span></label>
									<div class="col-xs-12">
										{{ csrf_field() }}
										<select class="select2_select form-control" name="assign_dup_int_course[]" tabindex="-1" multiple>
										 <option value="">Please Course</option>   
										 <?php echo $destinationIntCounsellors;  ?>
											 
											
											
											
										</select>
									</div>
								</div>
								
								
								
								 
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-xs-4 col-xs-offset-8">
										<button type="submit" class="btn btn-success btn-block">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection