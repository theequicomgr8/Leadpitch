@extends('layouts.cm_app')
@section('title')
All Course assignment
@endsection
@section('content')	
 
  <div class="right_col" role="main">
          <div class="">    
			<div class="page-title">
			<div class="title_left">
			<h3>Course Assignment</h3>
			<div class="succesmessage"></div><div class="errormessage"></div>
			</div>

			<div class="title_right">
			<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
			<div class="input-group">
		 

			</div>
			</div>
			</div>
			</div>
            <div class="clearfix"></div>
				 <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">                    
                    <form class="form-horizontal form-label-left" autocomplete="off" action=""  onsubmit="return userController.registerSubmit(this)" > 

						<div class="form-group">
						<label class="control-label col-md-2 col-sm-3 col-xs-12" for="title"></label>
						
						<div class="col-md-8 col-sm-8 col-xs-12">
					 
						<select name="course" class="form-control select_courseb col-md-4 col-xs-12 get_coursellor_course">
						<option value="">Select Course</option>
						<?php										 
						if(!empty($courses)){
						foreach($courses as $course){
						?>
						<option value="<?php echo $course->id; ?>"><?php echo $course->name; ?></option>

						<?php } } ?>
						</select>

						</div>

						
						 
						</div>  					  
					   <div class="form-group">
                       <!-- <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Cousellor <span class="required">*</span>
                        </label>-->
                        <div class="col-md-2 col-sm-2 col-xs-12">
                            </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                         <div class="show-coursellor-table"></div>
						</div> 
					  <div class="col-md-4 col-sm-4 col-xs-12">
                         <div class="show-internation-coursellor-table"></div>
                      </div>   
					  
					    </div>
                    </form>
                  
                </div>
              </div>
            </div>
            </div> 
            <div class="row">
               
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
							<form method="GET" action="" novalidate autocomplete="off">
						 
								
								<div class="form-group">
									<div class="col-md-3">
									<!--	<label>Cousellor</label>-->
										<select class="form-control select_counsellor" name="search[user]">
											<option value="">Select Counsellor</option>
												@foreach($leadUser as $user)
													@if(isset($search['user']) && $search['user']==$user->id)
														<option value="{{ $user->id }}" selected>{{ $user->name }}</option>
													@else
														<option value="{{ $user->id }}">{{ $user->name }}</option>
													@endif
												@endforeach
										</select>										 
									</div>
								</div>								
								<!--<div class="form-group">
									<div class="col-md-3">
										<label>Course</label>
										<select class="form-control select_courses" name="search[course]">
											<option value="">Select Course</option>
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
								</div>-->
								 
								<div class="form-group">
									<div class="col-md-3">
							 

									 
										<button type="submit" class="btn btn-block btn-info">Filter</button>
									</div>
								</div>
								
								 
							</form>
								<style>
								.circle {
								border-radius: 50%;
								width: 75px;
								height: 75px;    
								background: #fff;
								border: 5px solid #000;
								color: #008000;
								text-align: center;
								font: 32px Arial, sans-serif;
								display: flex;
								align-items: center;
								justify-content: center;
								}
								</style>
						 
						</div>
						
                  <div class="x_content">
                      
                    <table id="datatable-lead-assign-course" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Counsellor</th>     
                          <th>Dom Course</th>                          
                          <th>Int Course</th>                            
                        </tr>
                      </thead>


                       </table>
                  </div>
                </div>
              </div>

             
               
              
            </div>
			 
          </div>
        </div>
	 
		@endsection