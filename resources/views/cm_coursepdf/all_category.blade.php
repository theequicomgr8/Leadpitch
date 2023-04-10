@extends('layouts.cm_app')
@section('title')
{{$title}}
@endsection
@section('content')	
<style>
#success{	
	color:green;font-size: 20px;
}
#failed{
	color:red;
	
}
</style>
	<div class="right_col" role="main">
		<div class="">


			@if(Session::has('success')) 	
				<div class="row">
				<div class="col-md-8 col-md-offset-1">
				<div class="alert alert-success">
				<strong> {{Session::get('success')}}</strong>
				</div>
				</div>
				</div>
				@endif
				@if(Session::has('failed')) 	
				<div class="row">
				<div class="col-md-8 col-md-offset-1">
				<div class="alert alert-danger">
				<strong>{{Session::get('failed')}}</strong>
				</div>
				</div>
				</div>
				@endif
				<div class="row">
				<div class="col-md-8 col-md-offset-1">
				<div id="success"> </div><div id="failed"> </div>
				</div>
				</div>
				
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">		
				<div class="page-title">
				<div class="title_left">
				<h3><a href="category/all-category">Course Category</a></h3>

				</div>
				<div class="page-title">
				<div class="add_right">
					<h3><a href="category/add" class="btn btn-info"> + Add</a></h3>	
 
 				
				</div>
				</div>
				</div>				
				</div>
				
				</div>
		
			<div class="clearfix"></div>
			
			 
			@if(Request::segment(2)=='add'  || Request::segment(2)=='edit'  )
			
		<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<h2>Category Form</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							 
					 <form action="" method="POST" enctype="multipart/form-data"  class="form-horizontal form-label-left" autocomplete="off">
						{{ csrf_field() }}
				 				  
					 
				 
				
		 
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Category<span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							 <input type="text" class="form-control" name="category" value="{{ old('category',(isset($edit_data->category)) ? $edit_data->category:"")}}" placeholder="Enter category" >
							 @if ($errors->has('category'))
								<small class="error alert-danger">{{ $errors->first('category') }}</small>
								@endif
						</div>
					</div>	
					   
 
					<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
								 
								<button type="submit" class="btn btn-success" name="submit" value="<?php echo $button; ?>">Submit</button>
							</div>
						</div>
					</form>

								 
						</div>
					</div>
				</div>
			</div>
		
		@else
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="alert alert-danger hide"></div>
					<div class="alert alert-success hide"></div>
					@if(Session::has('alert-success'))
						<div class="alert alert-success">{{Session::get('alert-success')}}</div>
					@endif
					<div class="x_panel">
						 
						 
						<style>
						.check-box{
							height:18px;
							width:20px;
							cursor:pointer;
						}
						</style>
						<div class="x_content" id="hiring_table">
							<table id="datatable-category" class="table table-striped table-bordered">
								<thead>
									<tr>
									 
										<th>Category</th>											 					 						 
										<th>Action</th>
									</tr>
								</thead>
							</table>
							 
						

						</div>
					</div>
				</div>
			</div>
			@endif
			
			
		</div>
		<style>
			#followUpModal .form-control{
				margin-bottom:10px;
			}
		</style>
		
 
	
	
	</div>
  
	<!-- /page content -->
@endsection