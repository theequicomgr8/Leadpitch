<!DOCTYPE html>
<html lang="en" ng-app="app">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!--Developed by brijesh chauhan-->
    <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="canonical" href="{{ URL::current() }}"/>
    <title>@yield('title')</title>
	 <link rel="shortcut icon" href="{{ URL::asset('/assets/images/favicon.png') }}" type="image/x-icon">
	 <base href="{{URL::asset('')}}" >
    <script src="<?php echo asset('/node_modules/angular/angular.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/spinner/spin.min.js') ?>"></script>
  
    <link href="<?php echo asset('/vendors/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/vendors/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/vendors/nprogress/nprogress.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/vendors/iCheck/skins/flat/green.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') ?>" rel="stylesheet">
  
    <link href="<?php echo asset('/vendors/google-code-prettify/bin/prettify.min.css') ?>" rel="stylesheet">
   
    <link href="<?php echo asset('/vendors/select2/dist/css/select2.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/vendors/select2/dist/css/select2-bootstrap.css') ?>" rel="stylesheet">
 
    <link href="<?php echo asset('/vendors/switchery/dist/switchery.min.css') ?>" rel="stylesheet">
  
    <link href="<?php echo asset('/vendors/starrr/dist/starrr.css') ?>" rel="stylesheet">
  
    <link href="<?php echo asset('/vendors/bootstrap-daterangepicker/daterangepicker.css') ?>" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('/vendors/chosen-js/chosen.css') }}">
    <link href="<?php echo asset('/build/css/custom.min.css') ?>" rel="stylesheet">
    <link href="<?php echo asset('/build/css/style.css') ?>" rel="stylesheet">
  <style>
  .required{
      color:red;
      
  }
  
  </style>
</head>

<body class="nav-md" >

    <!-- SPINNER -->
    <div id="brijeshBkgd"></div>
    <div id="brijeshCntr"></div>
    <!-- SPINNER -->

    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="brijesh_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        @if(Session::get('client.role')=="master")
                        <a href="{{ url('/genie/clientDashboard') }}" class="site_title"><i class="fa fa-paw"></i> <span>Leads</span></a>
					@else
						
				 <a href="{{ url('/dashboard/counsellor') }}" class="site_title"><img src="<?php echo asset('/assets/images/logo.png') ?>" style="width:20%"><span>Leads</span></a>
					
					@endif
                    </div>
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
					<?php
					if(Session::get('client.role')=="master"){ ?>
                    <div class="profile clearfix">
                        <div class="profile_pic">
                           	<?php	
						$userimage = DB::table('users')->where('id',Session::get('client.id'))->first();
						if($userimage->image){
						?>
                            <img src="{{ URL::asset('/upload/'.$userimage->image)}}" alt="..." class="img-circle profile_img">
						<?php }else{ ?>
						<img src="<?php echo asset('/assets/images/user.png') ?>" alt="..." class="img-circle profile_img">
						<?php } ?>
                        </div>
                        <div class="profile_info">
                            <span>Welcome</span>
                            <h2><?php echo Session::get('client.name'); ?></h2>
                        </div>
                    </div>
					<?php }else{ ?>
					
					 <div class="profile_pic">
                           	<?php
							
						$id= Auth::user()->id;
						  
						$userimage = DB::table(Session::get('company_id').'_users')->where('id',$id)->first();
						 
						if($userimage->image){
						?>
                            <img src="{{ URL::asset('/upload/'.$userimage->image)}}" alt="..." class="img-circle profile_img" style="height: 50px;">
						<?php }else{ ?>
						<img src="<?php echo asset('/assets/images/user.png') ?>" alt="..." class="img-circle profile_img">
						<?php } ?>
                        </div>
                        <div class="profile_info">
                            <span>Welcome</span>
                            <h2>{{ Auth::user()->name }}</h2>
                        </div>
					<?php  } ?>
                    <!-- /menu profile quick info -->
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        
                        <div class="menu_section">
                            <h3>&nbsp;</h3>
							
                            <ul class="nav side-menu">							
						 
								<?php if(Session::get('client.role')=='master') { ?>
									
								<li><a href="{{url::asset('genie/clientDashboard')}}"><i class="fa fa-home"></i>Dashboard</a></li>			
							 
						    	<li><a href="javascript:void(0)"><i class="fa fa-users"></i>Client <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{url::asset('/genie/clientList')}}">All Client</a></li> 
									 
										<li><a href="{{url::asset('/genie/clientAdd')}}">Create Client</a></li>
										 
                                    </ul>
                                </li>
							
							<li><a href="javascript:void(0)"><i class="fa fa-plus-square"></i>Roles Permissions <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="{{url::asset('/genie/permission')}}">Permissions</a></li>
											<!--<li><a href="{{url::asset('/role-permission')}}">Role Based Permission</a></li>-->
										</ul>
								</li>
								<li><a href="javascript:void(0)"><i class="fa fa-key"></i>OTP <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">										
                                        <li><a href="{{URL::asset('/genie/mobile')}}">Mobile</a></li>
                                        <li><a href="{{URL::asset('/genie/email')}}">Email</a></li>	
                                    </ul>
                                </li>
								<?php  }else{ ?>
								
									@if(Auth::user()->current_user_can('show_add_demo_students'))
										<li><a href="{{url::asset('/demo/add-lead-students')}}">Add Demo Students</a></li>
										@endif
								
								
								@if(!Auth::user()->current_user_can('show_add_demo_students') ||  Auth::user()->current_user_can('administrator') )	
								<li><a href="javascript:void(0)"><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="{{url::asset('/dashboard/counsellor')}}">Dashboard</a></li>
									
									</ul>
								</li>
								@endif
								@if(!Auth::user()->current_user_can('show_seo_lead'))	
                                <li><a href="javascript:void(0)"><i class="fa fa-users"></i>Users <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{url::asset('/users')}}">All Users</a></li>
										@if(Auth::user()->current_user_can('administrator')||Auth::user()->current_user_can('super_admin') ||Auth::user()->current_user_can('manager'))
										<li><a href="{{url::asset('/register')}}">Create User</a></li>
										@endif
									
                                    </ul>
                                </li>
                                @endif
                                
									@if(Auth::user()->current_user_can('super_admin') )
									<li><a href="javascript:void(0)"><i class="fa fa-key"></i>OTP <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{URL::asset('/mobile')}}">Mobile</a></li>
                                        <li><a href="{{URL::asset('/email')}}">Email</a></li>
                                    </ul>
                                </li>
									@endif
								    	
									
									@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('leads_assign') || Auth::user()->current_user_can('administrator'))	
								<li><a href="javascript:void(0)"><i class="fa fa-database"></i>Leads Assignment <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
									    @if(Auth::user()->current_user_can('super_admin') ||  Auth::user()->current_user_can('leads_assign') || Auth::user()->current_user_can('administrator'))
										<li><a href="{{url('/enterleads')}}">Enter Lead</a></li>
										<li><a href="{{url('/enterSocailleads')}}">Enter Social Lead</a></li>
										
                                    <li><a href="{{url('genie/lead')}}">All Lead</a></li>                       
                                    <li><a href="{{url('genie/lead-analysis')}}">Daily Lead Analysis</a></li>                      
                                    <li><a href="{{url('genie/monthly-lead-analysis')}}">Monthly Lead Analysis</a></li>                   
                                    <li><a href="{{url('genie/course-assignment')}}">Course Assignment</a></li>  
									<li><a href="{{url('dashboard/categorylead-analysis')}}">Category Lead Analysis</a></li>
									<li><a href="{{url('dashboard/seo-categorylead-analysis')}}">Organic Leads Analysis</a></li>
									<!--<li><a href="{{url('dashboard/facbook-lead')}}">Facbook Lead</a></li>-->
										@endif
										 
										</ul>
								</li>
						    	@endif
						    	
						    	<!-- For Only Arun Sharma  -->
						    	@if(Auth::user()->current_user_can('Organic_lead'))
						    	<li><a href="{{url('dashboard/seo-categorylead-analysis')}}">Organic Leads Analysis</a></li>
						    	@endif
						    	
						    	
						    	
									
									@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('leads_managment'))	
								<li><a href="javascript:void(0)"><i class="fa fa-database"></i>Leads Managment <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
									    @if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('leads_managment'))
										<li><a href="{{url::asset('/enterleads')}}">Enter leads</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('leads_managment'))
										<li><a href="{{url::asset('/lead/all-lead-managment')}}">All Received Leads</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator'))
									
										<li><a href="{{url::asset('/lead/all-counsllor-view')}}">Counsllor View</a></li>
												<li><a href="{{url::asset('/lead/all-counsllor')}}">All Counsllor</a></li>
										<li><a href="{{url::asset('/lead/all-course-assignment')}}">Course Assignment</a></li>
										<li><a href="{{url::asset('/lead/duplicate-course-assignment')}}">Duplicate Course Assignment</a></li>
										
										<!--<li><a href="{{url::asset('/absent/all-absent-course')}}">Absent Course Assignment</a></li>-->
										<li><a href="{{url::asset('/absent/absent-assign-course-view')}}">Absent Assign Course</a></li>
										@endif
										 
									<!--	<li><a href="{{url::asset('/bucket/add-bucket-course')}}">Add Bucket Full Course </a></li>-->
										<li><a href="{{url::asset('/bucket/all-bucket-course')}}">Bucket Course Assignment</a></li>
										 
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('leads_managment'))
										<li><a href="{{url::asset('/lead/lead-assignment')}}">Lead Assignment</a></li>
											<li><a href="{{url::asset('/lead/all-duplicate-lead')}}">Duplicate Lead</a></li>
										<li><a href="{{url::asset('/lead/all-dual-lead')}}">Dual Lead</a></li>
										<li><a href="{{url::asset('/lead/not-lead-assignment')}}">Unassigned Leads</a></li>
										<!--<li><a href="{{url::asset('/fblead/not-lead-assignment')}}">Facbook Unassigned Leads</a></li>-->
										<li><a href="{{url::asset('/lead/all-delete-lead')}}">Delete croma lead </a></li>
																			 
										@endif
										</ul>
								</li>
						    	@endif
						    	
                                @if(Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('show_seo_lead'))
                                <li><a href="{{url::asset('/lead/show-seo-lead')}}"> <i class="fa fa-database"></i>Paid Campaign Analytis</a></li>
                                @endif
									
									@if(!Auth::user()->current_user_can('show_seo_lead') ||  Auth::user()->current_user_can('administrator') )	
								<li><a href="javascript:void(0)"><i class="fa fa-database"></i>Leads <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_lead'))
										<li><a href="{{url::asset('/lead/all-leads')}}">All Leads</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('add_lead'))
										<li><a href="{{url::asset('/lead/add-lead')}}">Add Lead</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') ||  Auth::user()->current_user_can('administrator')  ||  Auth::user()->current_user_can('search_leadDemo') )
										<li><a href="{{url::asset('/lead/search-lead')}}">Search Lead</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin'))
										<li><a href="{{url::asset('/course/assignment/assigncourse')}}">NI To Fresh Lead</a></li>
										 
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_deleted_leads'))
										<li><a href="{{url::asset('/lead/deleted-leads')}}">Deleted Leads</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_lead'))
										<li><a href="{{url::asset('/lead/not-interested')}}">Not Interested Leads</a></li>
										@endif
										
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_lead'))
										<li><a href="{{url::asset('/lead/expected-lead-demo')}}">Expected Demo</a></li>
										@endif
										
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('view_lead'))
										<li><a href="{{url::asset('/lead/lead-forward')}}">Lead Forward</a></li>
										@endif
										
										
									</ul>
								</li>
							@endif
							
							@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('facebook'))
								<li><a href="javascript:void(0)"><i class="fa fa-plus-square"></i>Facbook Leads <span class="fa fa-chevron-down"></span></a>
                                	<ul class="nav child_menu">
                                		<li><a href="{{url('dashboard/facbook-lead')}}">All Facebook Lead</a></li>
                                		<li><a href="{{url::asset('/fblead/not-lead-assignment')}}">Facebook Unassigned Leads</a></li>
                                		<li><a href="{{url::asset('/notInter/facbook')}}">Not Interested Facebook Lead</a></li>
                                		<li><a href="{{url::asset('/fblead/all-duplicate-lead')}}">Duplicate Facebook Lead </a></li>
                                		<li><a href="{{url::asset('/bulkupload/facbook')}}">Upload Facebook Leads </a></li>
                                		
                                	</ul>
                                <li>
								@endif
							
							
							
							@if(!Auth::user()->current_user_can('show_seo_lead') ||  Auth::user()->current_user_can('administrator') )	
								<li><a href="javascript:void(0)"><i class="fa fa-plus-square"></i>Demos <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_demo'))
										<li><a href="{{url::asset('/demo/all-leads')}}">All Demos</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('add_demo'))
										<li><a href="{{url::asset('/demo/add-lead')}}">Add Demo</a></li>
										@endif
										
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_demo'))
										<li><a href="{{url::asset('/demo/batch/batch')}}">Create Batch</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') ||  Auth::user()->current_user_can('administrator')||  Auth::user()->current_user_can('joined_demos'))
										<li><a href="{{url::asset('/demo/leadjoinded')}}">Take Admission</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_deleted_demos'))
										<li><a href="{{url::asset('/demo/deleted-leads')}}">Deleted Demos</a></li>
										@endif
                                        @if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_demo'))
										<li><a href="{{url::asset('/demo/not-interested')}}">Not Interested Demos</a></li>
										@endif
										
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('joined_demos') || Auth::user()->current_user_can('admission'))
										<li><a href="/demo/joined-demos">Admissions</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('joined_demos'))
										<li><a href="/demo/trainer-status">Trainer Status</a></li>
										@endif
									</ul>
								</li>
								@endif
								@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')|| Auth::user()->current_user_can('view_lead'))	
								<li><a href="javascript:void(0)"><i class="fa fa-money"></i>Fees Management <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										@if(Auth::user()->current_user_can('super_admin') )
										<li><a href="{{url::asset('/fees/add-fees')}}">Add Fees </a></li>
										@endif
										
										<!--@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_lead'))
										<li><a href="{{url::asset('/lead/expected-new-batch-lead')}}">Expected New Batch Leads</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('add_lead'))
										<li><a href="{{url::asset('/demo/expected-demo')}}">Expected Demos</a></li>
										@endif-->
										@if(Auth::user()->current_user_can('super_admin') )
										<li><a href="{{url::asset('/fees/all-fees')}}">All Fees</a></li>										
										@endif
										
									<!--	@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_lead'))
										<li><a href="{{url::asset('/lead/counsellor-payment-mode')}}">Counsellor Payment URL</a></li>
										@endif-->
										 
									</ul>
								</li>
							@endif
									
							@if(!Auth::user()->current_user_can('show_seo_lead') )
								<li><a href="javascript:void(0)"><i class="fa fa-asterisk"></i>Expected Demo Batch <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
									
										<li><a href="{{url::asset('/lead/expected-lead')}}">Expected Visit</a></li>
									
										
									<!--	@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_lead'))
										<li><a href="{{url::asset('/lead/expected-new-batch-lead')}}">Expected New Batch Leads</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('add_lead'))
										<li><a href="{{url::asset('/demo/expected-demo')}}">Expected Demos</a></li>
										@endif
										-->
									
										<!--<li><a href="{{url::asset('/demo/expected-new-batch-demo')}}">Expected Batch</a></li>-->
										
										<li><a href="{{url::asset('/demo/batch/lead-batch')}}">Leads Batch </a></li>
									
										 
									</ul>
								</li>
								@endif
							@if(!Auth::user()->current_user_can('show_seo_lead') || Auth::user()->current_user_can('super_admin') ||  Auth::user()->current_user_can('view_hiring') ||  Auth::user()->current_user_can('administrator') )										 
							<li><a href="javascript:void(0)"><i class="fa fa-handshake-o"></i>Hiring <span class="fa fa-chevron-down"></span></a>
							<ul class="nav child_menu">

							<li><a href="{{url::asset('/hiring/add')}}">New Hiring </a></li>

							<li><a href="{{url::asset('/hiring/all-hiring')}}">All Hiring </a></li>

                            <!--<li><a href="{{url::asset('/trainerrequired/all-trainer')}}">Trainer Required</a></li>-->

							</ul>
							</li>
							@endif

						 
					 
					 
							@if(Auth::user()->current_user_can('super_admin')|| Auth::user()->current_user_can('all_view_trainer') ||  Auth::user()->current_user_can('administrator') )	
								<li><a href="javascript:void(0)"><i class="fa fa-graduation-cap"></i>Trainer <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('all_view_trainer'))
										<li><a href="{{url::asset('/trainer/all-trainer')}}">All Trainer</a></li>
										@endif
										@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('add_trainer'))
										<li><a href="{{url::asset('/trainer/add-trainer')}}">Add Trainer</a></li>
										@endif	

																				
									</ul>
								</li>
							@endif
				
						@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('view_demo') )		
								<li><a href="javascript:void(0)"><i class="fa fa-list"></i>Course Content<span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
								 		@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') || Auth::user()->current_user_can('view_demo'))	
										 <?php $catype= App\CategoryType::get(); 
									 if(!empty($catype)){
										 foreach($catype as $cate){	 
									 ?>
										<li><a href="{{url::asset('/coursepdf/course-pdf/'.base64_encode($cate->id))}}">{{$cate->categorytype}}</a></li> 
								<?php } } ?>
										 
										@endif
										 
									 
									  <li><a href="{{url::asset('/coursepdf/pending')}}">PENDING</a></li> 
										 
									</ul>
								</li>
								@endif
								
								
								@if(Auth::user()->current_user_can('super_admin') ||  Auth::user()->current_user_can('administrator') )		
								<li><a href="javascript:void(0)"><i class="fa fa-list"></i>Course Generated<span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
									
									
								 		@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator'))	
										 
										<li><a href="{{url::asset('/categoryType/all-category-type')}}">Category Type</a></li> 
										<li><a href="{{url::asset('/category/all-category')}}">Category</a></li> 
										<li><a href="{{url::asset('/subcategory/all-sub-category')}}">Sub Category</a></li> 
									<!--	<li><a href="{{url::asset('/coursepdf/add')}}">Add Course PDF</a></li>-->
										@endif
									 
									 
									 
										 
									</ul>
								</li>
								@endif
								
								@if(Auth::user()->current_user_can('super_admin')|| Auth::user()->current_user_can('administrator') )		
								<li><a href="javascript:void(0)"><i class="fa fa-list"></i>Mailer & Feedback<span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
																	
								 		@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator'))	
										 
										<li><a href="{{url::asset('/lead/mailer')}}">Mailer Data</a></li> 
										<li><a href="{{url::asset('/lead/website-feedback')}}">Website Feedback</a></li> 
										 	<li><a href="{{url::asset('/lead/counsellor-feedback')}}">Counsellor Feedback</a></li> 
										@endif
										 
									
									 
										 
									</ul>
								</li>
								@endif
							
					        	<!--<li><a href="javascript:void(0)"><i class="fa fa-list"></i>Calling Details<span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
									@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator')|| Auth::user()->current_user_can('manager'))	
										<li><a href="{{url::asset('/content/add-content')}}">Add Content</a></li>
										@endif
										
										 
										<li><a href="{{url::asset('/content/all-content')}}">Content Details</a></li>
									 
									 
										 
									</ul>
								</li>-->
        					 
								@if(Auth::user()->current_user_can('super_admin'))
								<li><a href="{{url::asset('/permanent-transfer')}}"><i class="fa fa-arrow-right"></i>Transfer</a></li>
								@endif
								@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_source'))
								<li><a href="{{url::asset('/source')}}"><i class="fa fa-code-fork"></i>Source</a></li>
								@endif
								<!--@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_course'))
								<li><a href="{{url::asset('/course')}}"><i class="fa fa-language"></i>Course</a></li>
								@endif-->
								@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_status'))
								<li><a href="{{url::asset('/status')}}"><i class="fa fa-check"></i>Status</a></li>
								@endif
								
								@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('view_message'))
								<li><a href="{{url::asset('/message')}}"><i class="fa fa-envelope"></i>Message</a></li>
								@endif
								 
								
								@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('bulkupload'))
								<li><a href="javascript:void(0)"><i class="fa fa-upload"></i>Bulk Upload<span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
									<li><a href="{{url::asset('/bulkupload/leads')}}">Lead Upload</a></li>
									<li><a href="{{url::asset('/bulkupload/demos')}}">Demo Upload</a></li>
									<!--<li><a href="{{url::asset('/bulkupload/facbook')}}">Facbook Lead Upload</a></li>-->
									</ul>
								</li>
								@endif	
								
								
								@if(Auth::user()->current_user_can('super_admin') ||  Auth::user()->current_user_can('administrator') )
								<li><a href="javascript:void(0)"><i class="fa fa-plus-square"></i>Permissions <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
									   <li><a href="{{url::asset('/permission')}}">Permissions</a></li>
										<li><a href="{{url::asset('/role-permission')}}">Role Based Permission</a></li>
									</ul>
								</li>
								@endif
								
								<?php 
								//dd(Auth::user()->id);
								?>
                                
								<li><a href="javascript:void(0)"></a>
									<ul class="nav child_menu">
										<li></li>
									</ul>
								</li>
							<!-- @if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator'))
								<li><a href="javascript:void(0)"><i class="fa fa-plus-square"></i>Cache Remove <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="{{url::asset('/clear-cache')}}">Cache Clean</a></li>
										<li><a href="{{url::asset('/optimize')}}">Optimize</a></li>
										<li><a href="{{url::asset('/view-clear')}}">View Clean</a></li>
									</ul>
								</li>
								@endif  --> 
								 
								<?php } ?>
                            </ul>
							
							
                        </div>

                    </div>
                    <!-- /sidebar menu -->
                   <!-- <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ url('/logout') }}">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>-->
                  
                </div>
            </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">	
						<?php if(Session::get('client.role')=="master"){ 
					 
						?>
                            <li class="user-profile-list">
                                <a href="javascript:void(0);" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?php if($userimage->image) { ?>
									<img src="{{ URL::asset('/upload/'.$userimage->image)}}" alt="">
								<?php }else{ ?>
								<img src="<?php echo asset('/assets/images/user.png') ?>" alt="...">
								<?php } echo Session::get('client.name'); ?>
									<span class=" fa fa-angle-down"></span>
								</a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                     <li><a href="{{url::asset('genie/profile')}}"> Profile</a></li>
                                    <li>
                                        <a href="{{URL::asset('genie/changepassword')}}">
                                            
                                            <span>Change Password</span>
                                        </a>
                                    </li>
                                    <li><a href="{{ url('/genie/logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>
							<?php }else{ ?>
							<li class="user-profile-list">
                                <a href="javascript:void(0);" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?php if($userimage->image) { ?>
									<img src="{{ URL::asset('/upload/'.$userimage->image)}}" alt="">
								<?php }else{ ?>
								<img src="<?php echo asset('/assets/images/user.png') ?>" alt="...">
								<?php } ?>{{ Auth::user()->name }}
									<span class=" fa fa-angle-down"></span>
								</a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                     <li><a href="{{url::asset('profile')}}"> Profile</a></li>
                                    <li>
                                        <a href="{{URL::asset('changepassword')}}">
                                            
                                            <span>Change Password</span>
                                        </a>
                                    </li>
                                    <li><a href="{{ url('/logout') }}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                                </ul>
                            </li>
							
							<?php  } 
							
							  if(Session::get('client.role') !=="master"){ 
							?>
							@if(Auth::user()->current_user_can('super_admin') || Auth::user()->current_user_can('administrator') ||  Auth::user()->current_user_can('lead_demo_all') )
							
							<?php
							$route = Route::current();
							if($route->getName()=='dashboard'):
							?>
							<li class="list-separator">
								<span> | </span>
							</li>
							<li class="counsellor-list">
								<select class="form-control counsellor-control select-box">
									<option value="">User Dashboard</option>
									@if(isset($counsellors))
										@foreach($counsellors as $counsellor)
											<option value="{{$counsellor->id}}" <?php if(Request::segment(3)==$counsellor->id) { echo "selected"; } ?> >{{$counsellor->name}}</option>
										@endforeach
									@endif
								</select>
							</li>
							<?php endif; ?>
							
							@endif
							
							@if(Auth::user()->current_user_can('manager'))
							
							<?php
							$route = Route::current();
							if($route->getName()=='dashboard'):
							?>
							<li class="list-separator">
								<span> | </span>
							</li>
							<li class="counsellor-list">
								<select class="form-control counsellor-control select-box">
									<option value="">User Dashboard</option>
									@if(isset($counsellors))
										@foreach($counsellors as $counsellor)
											<option value="{{$counsellor->id}}" <?php if(Request::segment(3)==$counsellor->id) { echo "selected"; } ?> >{{$counsellor->name}}</option>
										@endforeach
									@endif
								</select>
							</li>
							<?php endif; ?>
							@endif
							<?php } ?>
							
					
                         </ul>
                    </nav>
                </div>
            </div>
          <!--  
        	 
		 
            <!-- /top navigation -->

			@yield('content')
<div id="success" class="modal fade" role="dialog">						
<div class="modal-dialog" style="max-width: 200px !important;margin: 15.75rem auto;">
<div class="modal-content">					 
<div class="modal-body">
<h6>Mobile No Copied</h6>				 
</div>
</div>
</div>
</div>	


<div id="messagemodel" class="modal fade" role="dialog" data-backdrop="static"><div class="modal-dialog"><div class="modal-content"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span></button><div class="modal-body" style="margin-top: 15px;"><div class="imgclass"></div><div class="successhtml"></div><div class="failedhtml"></div></div></div></div></div>

            <!-- footer content -->
            <footer>
                <div class="pull-right">
                   Developed By Croma Campus 
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->				
        </div>
    <!-- jQuery -->
    <script src="<?php echo asset('/vendors/jquery/dist/jquery.min.js') ?>"></script>
    <!-- Bootstrap -->
    <script src="<?php echo asset('/vendors/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    <!-- FastClick -->
    <script src="<?php echo asset('/vendors/fastclick/lib/fastclick.js') ?>"></script>
    <!-- NProgress -->
    <script src="<?php echo asset('/vendors/nprogress/nprogress.js') ?>"></script>
    <!-- Chart.js -->
	<script src="<?php echo asset('/vendors/Chart.js/dist/Chart.min.js') ?>"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?php echo asset('/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') ?>"></script>
    <!-- iCheck -->
    <script src="<?php echo asset('/vendors/iCheck/icheck.min.js') ?>"></script>
    <!-- Flot -->
	<script src="<?php echo asset('/vendors/Flot/jquery.flot.js') ?>"></script>
	<script src="<?php echo asset('/vendors/Flot/jquery.flot.pie.js') ?>"></script>
	<script src="<?php echo asset('/vendors/Flot/jquery.flot.time.js') ?>"></script>
	<script src="<?php echo asset('/vendors/Flot/jquery.flot.stack.js') ?>"></script>
	<script src="<?php echo asset('/vendors/Flot/jquery.flot.resize.js') ?>"></script>
    <!-- Flot plugins -->
	<script src="<?php echo asset('/vendors/flot.orderbars/js/jquery.flot.orderBars.js') ?>"></script>
	<script src="<?php echo asset('/vendors/flot-spline/js/jquery.flot.spline.min.js') ?>"></script>
	<script src="<?php echo asset('/vendors/flot.curvedlines/curvedLines.js') ?>"></script>
    <!-- DateJS -->
	<script src="<?php echo asset('/vendors/DateJS/build/date.js') ?>"></script>
    <!-- Datatables -->
    <script src="<?php echo asset('/vendors/datatables.net/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-buttons/js/buttons.flash.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-buttons/js/buttons.html5.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-buttons/js/buttons.print.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') ?>"></script>
    <script src="<?php echo asset('/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/jszip/dist/jszip.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/pdfmake/build/pdfmake.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/pdfmake/build/vfs_fonts.js') ?>"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?php echo asset('/vendors/moment/min/moment.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/bootstrap-daterangepicker/daterangepicker.js') ?>"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="<?php echo asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js') ?>"></script>
    <script src="<?php echo asset('/vendors/jquery.hotkeys/jquery.hotkeys.js') ?>"></script>
    <script src="<?php echo asset('/vendors/google-code-prettify/src/prettify.js') ?>"></script>
    <!-- jQuery Tags Input -->
    <script src="<?php echo asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js') ?>"></script>
    <!-- Switchery -->
    <script src="<?php echo asset('/vendors/switchery/dist/switchery.min.js') ?>"></script>
    <!-- Select2 -->
    <script src="<?php echo asset('/vendors/select2/dist/js/select2.full.min.js') ?>"></script>
    <!-- Parsley -->
    <script src="<?php echo asset('/vendors/parsleyjs/dist/parsley.min.js') ?>"></script>
    <!-- Autosize -->
    <script src="<?php echo asset('/vendors/autosize/dist/autosize.min.js') ?>"></script>
    <!-- jQuery autocomplete -->
    <script src="<?php echo asset('/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js') ?>"></script>
    <!-- starrr -->
    <script src="<?php echo asset('/vendors/starrr/dist/starrr.js') ?>"></script>
	<!-- Chosen -->
	<script src="{{ asset('/vendors/chosen-js/chosen.jquery.js') }}"></script>
	<!-- Picklist -->
	<script src="{{ asset('/vendors/picklist/jquery.selectlistactions.js') }}"></script>
    <!-- Custom Theme Scripts -->
    <script src="<?php echo asset('/build/app/app.js') ?>"></script>
    <script src="<?php echo asset('/build/app/counsellorDashboardController.js') ?>"></script>
    <script src="<?php echo asset('/build/app/userController.js') ?>"></script>
    <script src="<?php echo asset('/build/js/custom.js') ?>"></script>
    <script src="<?php echo asset('/build/js/script.js') ?>"></script>
	<script>
		$(".chosen-select").chosen();
		 $("body").on("contextmenu",function(e){
        return true;
    });
    
    // $(document).ready(function(){
    //     $("body").on("click",function(e){
    //     return true;
    //     });
    // });
	</script>
	
</body>

</html>