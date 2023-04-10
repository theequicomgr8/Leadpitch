@extends('layouts.cm_app')
@section('title')
     Email  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Email</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-danger hide"></div>
					<div class="alert alert-success hide"></div>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							@foreach (['danger', 'warning', 'success', 'info'] as $msg)
								@if(Session::has('alert-' . $msg))
									<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
								@endif
							@endforeach
								 
							<h2>Add Email</small></h2>
						 
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal form-label-left" action="" method="POST" onsubmit="return MobileController.submit(this)">
							 
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Name<span class="required">*</span></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
									{{csrf_field()}}
										<input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Enter Name">
										@if ($errors->has('name'))
										<small class="error alert-danger">{{ $errors->first('name') }}</small>
										@endif
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Email<span class="required">*</span></label>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<input type="text" class="form-control" name="email" value="{{old('email')}}" placeholder="Enter Email">
										@if ($errors->has('email'))
										<small class="error alert-danger">{{ $errors->first('email') }}</small>
										@endif
									</div>
								</div>
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
										<button type="reset" class="btn btn-primary">Reset</button>
										<button type="submit" name="submit" value="Save" class="btn btn-success">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>Email List</h2>
							 
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<table id="datatable-mobile" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Name</th>
										<th>email</th>										
										<th>Action</th>
									</tr>
								</thead>
								<tfoot>
								@if(count($email))
									@foreach($email as $email_v)
									<tr>
										<th>{{$email_v->name}}</th>
										<th>{{$email_v->email}}</th>									 
										<th><a href="{{URL::asset('genie/email/edit/'.$email_v->id)}}" ><span class="glyphicon glyphicon-edit"></span></a>
										
										<a href="{{URL::asset('genie/email/delete/'.$email_v->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><span class="glyphicon glyphicon-trash glyphicon"></span></a>
										</th>
									</tr>
									@endforeach
									@else										
									<tr>
										<td colspan="3">No data available in table</td>
									</tr>
									@endif
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /page content -->
@endsection