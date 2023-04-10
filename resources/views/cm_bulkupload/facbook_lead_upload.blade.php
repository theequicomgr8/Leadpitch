@extends('layouts.cm_app')
 @section('title')
     Bulk Upload Lead  
@endsection
@section('content')	
  
	<!-- page content -->
	<div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
				<div class="row">
					<div class="col-md-7">
						<h3>Facbook Lead Upload</h3>
						</div>
						<div class="col-md-5">
						<!--<form  method="POST"  action="{{ url('/client/downloadexcelformate') }}">-->
						<form  method="POST"  action="{{ url('/facbook/downloadexcelformate') }}">
								{{ csrf_field() }}
								 
								<div class="form-group">						 

								<button type="submit" class="btn-success  btn-block download-excel-formate">Download Excel Format</button>
										
								 
								</div>
						</form>
						
					</div>
				</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12">
					 @foreach (['danger', 'warning', 'success', 'info'] as $msg)
								@if(Session::has('alert-' . $msg))
									<div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
								@endif
							@endforeach
				</div>
				
				
				<div class="col-md-12">
					@if(Session::has('not_upload'))
					@php
					$duplicate=Session::get('not_upload');
					$duplicateAry=explode(",",$duplicate);
					@endphp

						@if(count($duplicateAry)>1)
						<div class="alert alert-warning">Duplicate numbers
							<ol>  
							@foreach($duplicateAry as $value)
								<li>{{$value}}</li>
							@endforeach
							</ol>
							<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						</div>
						@endif
						
					@endif
				</div>
				
				
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							
							<h2>Add Facebook Lead</small></h2>
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
						</div>
						<div class="x_content">
							<br />
							<form class="form-horizontal" action="{{ url('/bulkupload/facbook') }}" method="POST" enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="form-group">
							<label class="control-label col-sm-2" for="">Upload Excel: <sup><i style="color:red" class="fa fa-asterisk fa-fw" aria-hidden="true"></i></sup></label>
							<div class="col-sm-4"> 
								<input type="file" class="form-control" value="" name="upload_file">
							</div>
						</div>
						<div class="form-group"> 
							<div class="col-sm-offset-2 col-sm-4 text-right">
								<input type="submit" name="upload_submit" value="Upload" class="btn btn-warning">
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