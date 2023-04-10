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

<style>
	 .form-heading strong{
		display: block;
		text-align: center;
		color: #2b5f98;
		font-size: 20px;
		text-transform: uppercase;
		border-bottom: 1px solid #2fa4cd;
		padding: 10px;
		margin-bottom: 40px;
	 }
	 .gr .form-group label{
		text-align:left;
		font-size: 16px;
    	color: #505050;
	 }
	 .gr .form-group button{
		margin: 15px auto;
		display: block;
		background: #26c008;
		border: none;
		color: #fff;
		padding: 10px 35px;
		text-transform: uppercase;
		font-size: 20px;
		font-weight: 500;
		border-radius: 24px;
	}
	.gr .form-group{
		margin-bottom:20px;
	}
 </style>
 
 <style>
		.table-heading{
			display:flex;
			align-items:center;
			justify-content:space-between;
		}
		.table-heading h3{
			color: #2b5f98;
			font-size: 20px;
			text-transform: uppercase;
			font-weight: 600;
		}
		.raised_number span{
			font-size: 16px;
		}
		.raised_number span:last-child{
			color: #2b5f98;
    		font-weight: 600;
		}
		.fs{
			border:none
		}
		.fs tr{}
		.fs tr th{
			border:none !important;
			border-top:1px solid #2fa4cd !important;
			border-bottom:1px solid #2fa4cd !important;
			line-height: 2 !important;
			color:#000;
		}
		.fs tr th:last-child{
			text-align:center;
		}
		.fs tr td{
			border:none !important;
			border-bottom:1px solid #b9b9b9 !important;
			line-height: 2 !important;
			color: #626262;
		}
		.fs tr td:last-child{
			display: flex;
    		justify-content: space-around;
		}
		.fs tr td:last-child span:first-child{
			position:relative;
		}
		.fs tr td:last-child span:first-child::before{
			position:absolute;
			content:'';
			width:10px;
			height:10px;
			background:#1d9c00;
			top: 7px;
    		left: -15px;
		}
		.fs tr td:last-child span:last-child{
			position:relative;
		}
		.fs tr td:last-child span:last-child::before{
			position:absolute;
			content:'';
			width:10px;
			height:10px;
			background:#ff0000;
			top: 7px;
    		left: -15px;
		}
		.x_content{
			padding:15px 5px 35px !important;
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
				<h3><a href="subcategory/all-sub-category">Sub Category</a></h3>
			 
				</div>
				<div class="page-title">
				<div class="add_right">
					<h3><a href="subcategory/add" class="btn btn-info"> + Add</a></h3>	
 
 				
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
						<!--<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<h2>Category Form</h2>
							<div class="clearfix"></div>
						</div>-->
						<div class="x_content">
						 
							 
					 <form action="" method="POST" enctype="multipart/form-data"  class="form-horizontal form-label-left" autocomplete="off">
						{{ csrf_field() }}
				 				  
					 
				 
				
		 
						<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Type <span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">

						<select name="categorytype" class="form-control col-md-7 col-xs-12" id="categorytype">
						 
						<option value="">Select Type</option>
						@if(!empty($categorytype))
						@foreach($categorytype as $type)
						 
						<option value="<?php echo $type->id ?>" <?php echo (isset($edit_data->categorytype) && $edit_data->categorytype==$type->id)?"selected":"";?>><?php echo $type->categorytype; ?></option>
						 
						@endforeach
						@endif
						
						
						</select>
						@if ($errors->has('categorytype'))
						<small class="error alert-danger">{{ $errors->first('categorytype') }}</small>
						@endif
						</div>
						</div>
						
						
						<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Category <span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">

						<select name="category" class="form-control col-md-7 col-xs-12 categorylist">						 
						<option value="">Select Category</option>
						@if(!empty($categorylist))
						@foreach($categorylist as $category)
						 
						<option value="<?php echo $category->id ?>" <?php echo (isset($edit_data->category) && $edit_data->category==$category->id)?"selected":"";?>><?php echo $category->category; ?></option>
						 
						@endforeach
						@endif
						
						
						</select>
						@if ($errors->has('categorytype'))
						<small class="error alert-danger">{{ $errors->first('categorytype') }}</small>
						@endif
						</div>
						</div>
					
					<div class="form-group row">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Sub Category<span class="required">*</span></label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							 <input type="text" class="form-control" name="subcategory" value="{{ old('subcategory',(isset($edit_data->subcategory)) ? $edit_data->subcategory:"")}}" placeholder="Enter Sub category" >
							 @if ($errors->has('subcategory'))
								<small class="error alert-danger">{{ $errors->first('subcategory') }}</small>
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
							<table id="datatable-sub-category" class="table table-striped table-bordered fs">
								<thead>
									<tr>
									 
										<th>Type</th>											 					 						 
										<th>Category</th>											 					 						 
										<th>Sub Category</th>											 					 						 
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