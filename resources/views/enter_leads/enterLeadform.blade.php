@extends('layouts.cm_app')
@section('title')
Enter Leads
@endsection
@section('content')	
 <style>.help-block{color:red;}</style>
  <div class="right_col" role="main">
          <div class="">    
			<div class="page-title">
			<div class="title_left">
			<h3>Enter Leads Form</h3>
			<div class="succesmessage"></div><div class="errormessage"></div>
			</div>

			<div class="title_right">
			<div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
			
			</div>
			</div>
			</div>
            <div class="clearfix"></div>
				
			 <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">                    
                    <form class="form-horizontal form-label-left" method="POST" onsubmit="return enterLeadController.enterleadSave(this)" autocomplete="off">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name </label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="hidden" name="from_title" value="Website" >
								<input type="hidden" name="from" value="Website">
								<input type="hidden" name="from_name" value="<?php if(Auth::user()->name){ echo  Auth::user()->name; } ?>">
								<input id="name" type="text" name="name" class="form-control col-md-7 col-xs-12" placeholder="Enter Name" value="" autocomplete="off">
										
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input id="email" type="email" class="form-control col-md-7 col-xs-12" name="email" placeholder="Enter Email" value="" autocomplete="off">		
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Enter Phone <span class="required">*</span></label>
							<div class="col-md-2 col-sm-2 col-xs-12"> 
								<?php 

									$countrylist = App\CountryCode::get();								 
									?>
								<select name="code" class="choosecode form-control col-md-3 col-xs-4" id="count_code">
								    	<?php if(!empty($countrylist)){ 
									foreach($countrylist as $country){	?>
									<option value="<?php echo $country->phonecode; ?>" <?php echo (isset($country->phonecode) && $country->phonecode=='91')?"selected":"";  ?>><?php echo '+'.$country->phonecode.' '.$country->country_name;  ?></option>
									<?php } } ?>
								</select>
										
							</div>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<input id="mobile" type="tel" class="form-control col-md-3 col-xs-4" placeholder="Enter Phone" name="phone" autocomplete="off">
													
						</div>
									
					</div>
									
								
								
								<div class="form-group">
									  
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="course">Select Course <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									 
										<select name="course" class="form-control select_courseb col-md-7 col-xs-12">
										<option value="">Select Course*</option>
									<?php										 
									if(!empty($catCourse)){
									foreach($catCourse as $course){
									?>
									<option value="<?php echo $course->id; ?>"><?php echo $course->name; ?></option>

									<?php } } ?>
								</select>
										
									</div>
								</div>
							<div class="form-group">
									  
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="training">Select Training Mode <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select id="training" class="form-control chosen-select col-md-7 col-xs-12" name="participant">
											<option value="">Select Training Mode</option>
											<option value="offline">Class Training </option>
											<option value = "online" selected>Online Training</option>
										 
										</select>
										
									</div>
								</div>
							<div class="form-group">
									  
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="training">Select Lead Source <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select id="training" class="form-control chosen-select col-md-7 col-xs-12" name="source">
											<option value="">Select Lead Source</option>
											<?php										 
												if(!empty($leadSource)){
												foreach($leadSource as $source){
											?>
												<option value="<?php echo $source->id; ?>" <?php  if($source->id==24){ echo "selected"; } ?>><?php echo $source->name; ?></option>

												<?php } } ?>	
										
										</select>
										
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="message">Remark </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="message" class="form-control col-md-7 col-xs-12" placeholder="Remarks"></textarea>
										
									</div>
								</div>
								
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button class="btn btn-primary" type="reset">Reset</button>
										<button type="submit" class="btn btn-success" name="submit"><i class="fa fa-btn fa-user"></i> Submit</button>
									</div>
								</div>
								
						</form>
                  </div>
                </div>
              </div>
            </div>
			
           
            
			
          </div>
        </div>
        
        
        <div id="messagemodelrefresh" class="modal fade" role="dialog" data-backdrop="static">
            <div class="modal-dialog"><div class="modal-content"><div class="modal-body">
                <div class="imgclass"></div><div class="successhtml"></div><div class="failedhtml">
                    <button type="button" class="refresh btn btn-default btn-info" onclick="refreshfunction();" style="margin-left: 42%;">OK</button></div>
                    </div></div></div></div>

        
		
		@endsection