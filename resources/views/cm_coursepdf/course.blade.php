@extends('layouts.cm_app')
@section('title')
     Course Content  
@endsection
@section('content')	
<style>
		 	.sp div h3 a{
				 font-weight:600;
			 }
			 .sp .add_right h3 a{
				background: #2bd366;
    			border-color: #2bd366;
			 }
			 .sp{				 
				position:relative;
			 }
			 .sp::before{
				 content:'';
				 width:80%;
				 height:2px;
				 background:#2fa4cd;
				 position:absolute;
				 bottom:0;
				 left:0;
			 }
			 .sp::after{
				content: '';
				width: 100px;
				height: 6px;
				background: #2fa4cd;
				position: absolute;
				bottom: -2px;
				left: 0;
			 }
			 .ta{
				 width:100%;
			 }
			 .lo{
				 display:flex;
				 justify-content:space-between;
				 align-items:center;
			 }
			 .lo p{
				 margin:5px 0;
			 }
			 .x_panel{
				 /* background:#fafafa !important; */
			 }
			 .ha{
				color: #2b5e95;
    			font-weight: 600;
			 }
			 .pa{
				/* padding: 0px 0px 15px 20px !important; */
				background: transparent !important;
				padding:0 !important;
			 }
			 .pa ul{
				 margin:0;
			 }
			 .aps{
				background-color: transparent !important;
				padding:0px 10px !important;
			 }
			 .aps:after{
    			margin-top: 2px;
			 }
			 .aps span{
				 color:#2b5e95;
				 line-height: 0;
			 }
			 .ip a{
				 margin-left:10px;
			 }
			 .dt{
				background: #fff;
				width: auto;
				padding: 0 7px !important;
				border: 1px solid #ddd;
				box-shadow: 0px 0px 6px 0px #ddd;
				margin: 3px;
			 }
			 .dt label{
				 margin:0;
			 }
			 .fdt::placeholder{
				 font-size:12px;
				 color:#464646;
			 }
			 .dal{
				border: none !important;
				box-shadow: none !important;
				padding: 0;
				width: 43px !important;
				margin-left: 15px;
			 }
			 .searchiconaz label{
				display: flex;
    			justify-content: center;
    			align-items: center;
			 }
			 .searchiconaz label input{
				 margin:0;
			 }
			 .searchiconaz label button{
				padding: 4px 9px;
				margin: 0;
				background: #004586;
				border: 0;
				color: #fff;
				font-size: 17px;
			 }
			 .xp{
				 border:none;
				 margin-bottom: 0;
				 padding:5px 17px 0;
			 }
			 .xp .x_title {
    			border-bottom: 1px solid #2fa4cd;
			 }
			 .xp .x_title ul li a{
				color: #2b5e95;
				background: #fff;
				box-shadow: 0px 0 7px 3px #0000001f;
				padding: 5px 7px;
			 }
			 .ta tr td div button{
				color: #606c78 !important;
			 }
			 .ta tr td div button:after{
				padding: 1px 3px;
    			line-height: 1;
				border:1px solid;
			 }
			 .pa ul li{
				color: #454b51;
    			font-size: 14px;
			 }
			 .lms-approved{
				color:##2a3f54;
			 }
			 .lms-pdf{
				 color:#2990f1;
			 }
			 .lms-edit{
				 color:#26c008;
			 }
			 .lms-delete{
				 color:#ff0000;
			 }
			 .raised{
				 display:flex;
				 justify-content:flex-end;
			 }
			 .raised button{
				background: #2b5e95;
				border: none;
				color: #fff;
				padding: 7px 15px;
				font-weight: 600;
			 }
			 .cltab{
				background: #fff;
				padding: 25px;
				margin: 15px;
				box-shadow: 0px 0px 6px 1px #dadada;
			 }



			 .lms-accordian .paccordion {
				background-color: transparent;
				color: #444;
				cursor: pointer;
				padding: 8px;
				width: 100%;
				border: none;
				margin: 0;
				text-align: left;
				outline: none;
				font-size: 15px;
				transition: 0.4s;
			}

			.lms-accordian .paccordion:hover {
				background-color: #ccc;
			}
			.lms-accordian .paccordion:after {
				content: '\002B';
				color: #777;
				font-weight: bold;
				float: left;
				margin-right: 5px;
			}
			.paccordion.active:after {
				content: "\2212";
			}
			.panel {
				padding: 0 18px;
				background-color: white;
				max-height: 0;
				overflow: hidden;
				transition: max-height 0.2s ease-out;
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
				<div class="page-title sp">
				<div class="title_left">
				<h3><a href="category/all-category" style="color: #002632;">Course Content</a></h3>

				</div>
					@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator'))
				<div class="page-title">
				<div class="add_right">
					<h3><a href="coursepdf/add" class="btn btn-info"> + Add</a></h3> 				
				</div>
				</div>
				@endif
				</div>				
				</div>
				
				</div>
			 
			
			<div class="clearfix"></div>
			<!--<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">				 
					<div class="">	
						<div class="">
							<div  class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<div class="row">
									<div class="col-sm-6">
										<div class="dataTables_length dt" >
											<label>Show Entries
												<select name="datatable-allcoursecontentpdf_length" aria-controls="datatable-allcoursecontentpdf" class="form-control input-sm dal">
													<option value="10">10</option>
													<option value="25">25</option>
													<option value="50">50</option>
													<option value="100">100</option>
													<option value="250">250</option>
													<option value="500">500</option>
												</select> 
											</label>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="dataTables_filter searchiconaz">
											<label>
												<input type="text" placeholder="Search Course,Content etc." name="search" class="dt fdt form-control input-md" aria-controls="datatable-allcoursecontentpdf">
												<button type="submit"><i class="fa fa-search"></i>
											</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
								<table class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable-allcoursecontentpdf_info">								
							<tbody></tbody></table><div   class="dataTables_processing panel panel-default" style="display: none;">Processing...</div></div></div><div class="row"><div class="col-sm-5"><div class="dataTables_info" role="status" aria-live="polite"></div></div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" ></div></div></div></div>
						</div>
					</div>
				</div>
			</div>-->
			<div class="clealfix"></div>
			<div class="row cltab">
			
			<?php // echo "<pre>";print_r($category);
			if(!empty($categorytype)){ 
				foreach($categorytype as $cattype){
			?>
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel xp">
						<div class="x_title">
							<h2 class="ha"><i class="fa fa-align-left "></i> <?php echo $cattype->categorytype; ?>  </h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content" style="display:none">
			 				<div class="row">
							 	<div class="col-sm-12">
			 						<div class="card-box table-responsive">
			 							<table class="ta">
										<?php 													
											$category =APP\Category::where('categorytype',$cattype->id)->get();													 
											if($category){
												foreach($category as $cat){ ?> 
			 								<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														 <?php echo $cat->category; ?> <span></span>
														</button>
														<div class="panel pa">
															<ul>
															<?php 
														 
															$coursecontent = App\Coursecontent::where('category_id',$cat->id)->get();
															if(!empty($coursecontent)){
																foreach($coursecontent as $content){
																  $coursePdflist = App\CoursePdf::where('categorycontent_id',$content->id)->get();
																	if(!empty($coursePdflist)){
																		foreach($coursePdflist as $coursePdf){
															?>
																<li>
																	<div class="lo">
																		<p><?php  
																		if(!empty($coursePdf)){
																			
																			echo $coursePdf->coursepdf;
																		}
																		?></p>
																		<div class="ip">

																	<?php	
																	 if(Auth::user()->current_user_can('super_admin')){
																	if($coursePdf->status=='1'){  ?>
																	<a href="javascript:courseController.coursepdfstatus('<?php echo $coursePdf->id ?>',0)" title="Active" ><span style="color:green"><i class="fa fa-thumbs-up" aria-hidden="true"></i></span></a>																<?php 		
																	}else{ ?>
																	 <a href="javascript:courseController.coursepdfstatus('<?php echo $coursePdf->id; ?>',1)" title="Inactive"><span style="color:red"><i class="fa fa-thumbs-down" aria-hidden="true"></i></span></a> 
																	 <?php } }
																	if(Auth::user()->current_user_can('super_admin')){
																	 ?>	
																			 																			
																	<a href="/upload/<?php echo $coursePdf->coursepdf; ?>" target="_blank" class="download-curriculumss lms-pdf" title="<?php echo ucwords(str_replace('-',' ',$coursePdf->coursepdf)); ?>" data-stud_id="<?php echo $coursePdf->coursepdf; ?>"><span class="course-pdf"> <i class="fa fa-file-pdf-o"></i> </span></a>
																	<?php }else if($coursePdf->status=='1'){ ?>									
																				 																			
																	<a href="/upload/<?php echo $coursePdf->coursepdf; ?>" target="_blank" class="download-curriculumss lms-pdf" title="<?php echo ucwords(str_replace('-',' ',$coursePdf->coursepdf)); ?>" data-stud_id="<?php echo $coursePdf->coursepdf; ?>"><span class="course-pdf"> <i class="fa fa-file-pdf-o"></i> </span></a>
																		<?php } ?>
																		 
																			<a href="/coursepdf/edit/<?php echo $content->id; ?>" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			
																			<a href="coursepdf/deleted-icon-table/<?php echo $coursePdf->categorycontent_id.'/'.$coursePdf->id;  ?>" title="PDF deleted" class="deleteIcon lms-delete" onclick="return ConfirmDelete()" ><i class="fa fa-trash" style="color:red"></i></a>
																			 
																		</div>
																	</div>
																</li>
															<?php } } } } ?>
																
																<!--<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>-->
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<?php } } ?>
											
											<!--<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Microsoft Azure
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Google Cloud
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Salesforce
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>-->
										</table>
									</div>
								</div>
							</div>
							

						</div>
					</div>
				</div>
				
				<?php } } ?>
				
				<!--<div class="col-md-12 col-sm-12">
					<div class="x_panel xp">
						<div class="x_title">
							<h2 class="ha"><i class="fa fa-align-left"></i> Programming Languages</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content" style="display:none">
			 				<div class="row">
							 	<div class="col-sm-12">
			 						<div class="card-box table-responsive">
			 							<table class="ta">
			 								<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														AWS (Amazon Web Services) <span>[6]</span>
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Microsoft Azure
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Google Cloud
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Salesforce
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							

						</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel xp">
						<div class="x_title">
							<h2 class="ha"><i class="fa fa-align-left"></i> Data Science & AI </h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content" style="display:none">
			 				<div class="row">
							 	<div class="col-sm-12">
			 						<div class="card-box table-responsive">
			 							<table class="ta">
			 								<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														AWS (Amazon Web Services) <span>[6]</span>
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Microsoft Azure
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Google Cloud
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Salesforce
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
							

						</div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12  ">
					<div class="x_panel xp">
						<div class="x_title">
							<h2 class="ha"><i class="fa fa-align-left"></i> Data Analytics & Visualization</h2>
							<ul class="nav navbar-right panel_toolbox">
								<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
								</li>
							</ul>
							<div class="clearfix"></div>
						</div>
						<div class="x_content" style="display:none">
			 				<div class="row">
							 	<div class="col-sm-12">
			 						<div class="card-box table-responsive">
			 							<table class="ta">
			 								<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														AWS (Amazon Web Services) <span>[6]</span>
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Microsoft Azure
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Google Cloud
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
											<tr>
											 	<td>						 
													<div class="lms-accordian">
														<button class="paccordion aps" style="font-weight: 700;font-size: 13px;">
														Salesforce
														</button>
														<div class="panel pa">
															<ul>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
																<li>
																	<div class="lo">
																		<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh</p>
																		<div class="ip">
																			<a href="#0" class="lms-approved"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-edit"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
																			<a href="#0" class="lms-delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		</div>
																	</div>
																</li>
															</ul>
														</div>											
													</div>											
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			
				
				-->
			<!--	<div class="col-md-12 col-sm-12">
					<div class="raised">
						<p>Click to Update Course <button>Raised Request</button></p>
					</div>
				</div>-->
				
			</div>	
		 
		 <script>
		var acc = document.getElementsByClassName("paccordion");
		var i;

		for (i = 0; i < acc.length; i++) {
		acc[i].addEventListener("click", function() {
		this.classList.toggle("active");
		var panel = this.nextElementSibling;
		if (panel.style.maxHeight) {
		panel.style.maxHeight = null;
		} else {
		panel.style.maxHeight = panel.scrollHeight + "px";
		} 
		});
		}
		</script>

	 

	<!-- /page content -->
@endsection