@extends('layouts.cm_app')
 @section('title')
     Add Course Content  
@endsection
@section('content')	
  <!--  <link href="{{ asset('/vendors/dropzone/dist/min/dropzone.min.css')}}" rel="stylesheet">-->
		<link href="{{asset('build/drag_drop/main.css')}}" rel="stylesheet">
 <link rel="stylesheet" href="{{asset('build/drag_drop/jquery.ezdz.min.css')}}"> 
 <!-- page content -->
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
				@if(Session::has('alert-danger'))
						<div class="alert alert-danger">{{Session::get('alert-danger')}}</div>
						@endif
			 
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">					 
						<div class="x_content">						 
							<form class="form-horizontal form-label-left gr"  enctype="multipart/form-data" action="{{url('coursepdf/add')}}" method="post">
								{{ csrf_field() }}
								<?php //echo Request::segment(3); ?>
								<div class="form-heading">
									<strong>Add Course Content PDF</strong>
								</div>
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Type <span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										 
										<select name="categorytype" class="form-control col-md-7 col-xs-12" id="categorytype">
										<option value="">Select Type</option>
										@if(!empty($categorytype))
											@foreach($categorytype as $type)
										<option value="<?php echo $type->id ?>" <?php echo ((!is_null(Request::segment(3)) && Request::segment(3)==$type->id)?"selected":"");  ?>><?php echo $type->categorytype; ?></option>
										@endforeach
										@endif
										</select>
										@if ($errors->has('categorytype'))
										<small class="error alert-danger">{{ $errors->first('categorytype') }}</small>
										@endif
									</div>
								</div>
								
								
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Category <span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										 
										<select name="category" class="form-control col-md-7 col-xs-12 categorylist">
										<option value="">Select Category</option>
										@if(!empty($category))
											@foreach($category as $categ)
										<option value="<?php echo $categ->id ?>" <?php echo ((!is_null(Request::segment(4)) && Request::segment(4)==$categ->id)?"selected":"");  ?>><?php echo $categ->category; ?></option>
										@endforeach
										@endif
										</select>
										@if ($errors->has('category'))
										<small class="error alert-danger">{{ $errors->first('category') }}</small>
										@endif
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">Sub Category <span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12">
										 
										<select name="subcategory" class="form-control col-md-7 col-xs-12 subcategorylist">
										<option value="">Select Sub Category</option>
										@if(!empty($subcategory))
											@foreach($subcategory as $sub)
										<option value="<?php echo $sub->id ?>" <?php echo ((!is_null(Request::segment(5)) && Request::segment(5)==$sub->id)?"selected":"");  ?>><?php echo $sub->subcategory; ?></option>
										@endforeach
										@endif
										</select>
										@if ($errors->has('subcategory'))
										<small class="error alert-danger">{{ $errors->first('subcategory') }}</small>
										@endif
									</div>
								</div>
								 <div class="form-group image">
									<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">File <span class="required">*</span></label>
									<div class="col-md-8 col-sm-8 col-xs-12 ">
										 
										 <input type="file" name="course_image[]" multiple  accept=".pdf,.doc, .docx,">
										 @if ($errors->has('course_image'))
										<small class="error alert-danger">{{ $errors->first('course_image') }}</small>
										@endif
									</div>
								</div>
								 
						 
								
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">										 
										<button type="submit" class="btn btn-success"   name="submit">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	  <script type="text/javascript" src="{{asset('build/drag_drop/jquery-3.1.1.min.js')}}"></script>
	<script src="{{asset('build/drag_drop/jquery.ezdz.min.js')}}"></script>
	<!-- /page content -->
   <!--<script src="{{ asset('/vendors/dropzone/dist/min/dropzone.min.js')}}"></script>-->
   	<script>
 
		$('input[type="file"]').ezdz({
			text: 'Drag and drop',
			validators: {
				maxWidth:  4000,
				maxHeight: 4000
			},
			accept: function(e) 
			{
				console.log(e);
			},
			init	:   function(e) {console.log(e);},
			reject	: function(file, errors) {
				if (errors.mimeType) {
					alert(file.name + ' must be an PDF.');
				}

				if (errors.maxWidth) {
					alert(file.name + ' must be width:600px max.');
				}

				if (errors.maxHeight) {
					alert(file.name + ' must be height:400px max.');
				}
			}
		});
 
	 </script>
	    
 <!--
  	<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
	   <script src="{{asset('build/drag_drop/jquery.ezdz.min.js')}}"></script>
<script>tinymce.init({selector:'textarea' });</script>-->
@endsection