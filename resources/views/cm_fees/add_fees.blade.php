@extends('layouts.cm_app')
 @section('title')
     Add Fees  
@endsection
@section('content')	
	<!-- page content -->
	<div class="right_col" role="main">
	<style> 
.select2-container--default .select2-selection--multiple, .select2-container--default .select2-selection--single{
	    min-height: 35px !important;
}
</style>
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>New Fees Add</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_title">
							<div class="alert alert-danger hide"></div>
							<div class="alert alert-success hide"></div>
							<h2>Form <small>(Add Fees)</small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
						 
							<!--form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" onsubmit="return leadController.submit(this)"-->
							<form id="paidfees-form" class="form-horizontal form-label-left" onsubmit="return feesManageController.submit(this)">
								{{ csrf_field() }}
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Fees Type <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="hidden" name="id" value="">
								<select name="fees_type" id="fees_type" class="form-control col-md-7 col-xs-12">
								<option value="">Select Fees Type</option>
								<option value="newfees">New Fees Student</option>
								<option value="newfeesExperience">New Fees Experience</option>
								<option value="newfeesCertificate">New Fees Certificate</option>
								<option value="pendingfeesExperience">Pending Fees Experience   </option>
								<option value="pendingfeesCertificate">Pending Fees Certificate  </option>
								<option value="pendingfees">Pending Fees Student</option>
								</select >
									</div>
								</div>
								
								<div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">Mobile </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="mobile" class="form-control col-md-6 col-xs-12" value="{{ old('mobile') }}">
										@if ($errors->has('mobile'))
											<span class="help-block">
												<strong>{{ $errors->first('mobile') }}</strong>
											</span>
										@endif
										<a class="btn btn-info paidfees" style="position:absolute;right:5px;">Verify</a>
									</div>
								</div>
								
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
								
										<input type="text" name="name" class="form-control col-md-7 col-xs-12" placeholder="Enter Name" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="email" class="form-control col-md-7 col-xs-12" placeholder="Enter Email" autocomplete="off">
									</div>
								</div>
								 
								 
							 <div class="additionalpay">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Course <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="course" class="form-control" placeholder="Enter Course Name" value="">
										 
									</div>
								</div>
								</div>
								<div class="feespay">
								 <div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">Course <span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select class="form-control" name="course" tabindex="-1">
										<option value="">Select Course</option>
										<?php if(!empty($courses)){ foreach($courses as $course){ ?>
										<option value="<?php  echo $course->id ?>"><?php  echo $course->course ?></option>
										<?php  } } ?>
										</select>
									</div>
								</div>
								</div>
								  
								 <div class="pendingfees">
								
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Fees<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="tel" name="fees" id="stud_fees" class="form-control col-md-7 col-xs-12" placeholder="Enter Fees" onkeypress="return isNumberKey(event);" autocomplete="off"> 
									</div>
								</div>
								 
								
							<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="">GST<span class="required">*</span></label><br>
							<div class="col-md-3 col-sm-3 col-xs-12">
							<label class="radio-inline">
							<input type="radio" name="gst_status" value="Yes"  onchange="paidgst(this.value)">Yes
							</label>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-12">
							<label class="radio-inline">
							<input type="radio" name="gst_status" value="No"  onchange="nopaidgst(this.value)">No
							</label>

							</div>	
							</div>	

							<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  for="stud-service_tax">GST Amount</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" id="total_gst" name="total_gst" placeholder="GST" value="" readonly>
							</div>
							</div>										

							<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  for="stud-to_be_paid">Total Fees<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" id="stud-to_be_paid" name="stud-to_be_paid" placeholder="Total fees to be paid" value="" readonly>
							</div> 
							</div> 

							<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"  for="stud-to_be_paid">Fees Adjust<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="text" class="form-control" onblur="addFeesAdjust()" id="stud-fees_adjust" name="stud-fees_adjust" onkeypress="return isNumberKey(event);" placeholder="Fees Adjust" value="0" >
							
							</div>
							</div>
								
							
									
									<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12"   for="stud-final_fees">Final Fees<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" class="form-control" id="stud-final_fees" name="stud-final_fees" placeholder="Final Fees" value="" readonly>

									</div> 
									</div> 
								</div>
								
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Paid Amount<span class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="paid_amt"  class="form-control col-md-7 col-xs-12" onkeypress="return isNumberKey(event);" placeholder="Enter Paid Amount" autocomplete="off"> 
									</div>
								</div>
								
							 
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark">Counsellor Remark </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="counsellor_remark" rows="1" class="form-control col-md-7 col-xs-12" placeholder="Enter counsellor remark" autocomplete="off"></textarea>
									</div>
								</div>
								<div class="ln_solid"></div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button class="btn btn-primary" type="reset">Reset</button>
										<button type="submit" class="btn btn-success">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="leadsPayloadModal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
							<th>Select One</th>
								<th>Name</th>
								<th>Mobile</th>
								<th>Course</th>
								<th>Owner</th>
								
							</tr>
						</thead>
						<tbody>
							 
						</tbody>
					</table>
					<div class="pull-right">
					<button type="button" class="btn btn-default choke">Submit</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
				 
			</div>
		</div>
	</div>
	
		<script>
		function paidgst(gst){			
			 
			var fees = parseInt($('#stud_fees').val());		
			 var tot = parseInt(((fees*18)/100));			 
			 var gstamount = $('#total_gst').val(tot);			 
			 var tatol= fees + tot;			 
			 var tobe = $('#stud-to_be_paid').val(tatol);
				
		}
		
		function nopaidgst(gstno){	
 		
			var fees = parseInt($('#stud_fees').val());		
			 var tot = parseInt(0);			 
			 var gstamount = $('#total_gst').val(tot);			 
			 var tatol= fees + tot;			 
			 var tobe = $('#stud-to_be_paid').val(tatol);		
		}
				
		 function registrationAmount(valu){
			 var radios = ($('input:radio[name=gst_status]:checked').prop( "checked", true ).val());		
		 if(radios=='Yes'){
			var r_fees = Math.round((100*valu)/118);
			var regist_gst =$('#regist_fees').val(r_fees); 
			var paid_fees_tax =Math.round((((100*valu)/118)*18)/100);
			var gst =$('#stud-service_tax').val(paid_fees_tax); 
		 }else if(radios=='No'){			
			var regist_gst =$('#regist_fees').val(valu); 			
			var gst =$('#stud-service_tax').val(0); 
			 
		 }
			  
		 }		
		 
		 
		 

function addFeesAdjust() { 

	if( "undefined" == typeof window.jQuery ) {
		throw new Error("This code required jQuery");

	}
	//var total_fees = jQuery('#stud-update_total_fees');
	var stud_fees = jQuery('#stud-to_be_paid');
	var discount = jQuery('#stud-fees_adjust');
	var final_fees = jQuery('#stud-final_fees');
	var course_fees = jQuery('#stud-course-fees');
	var f = parseInt(stud_fees.val());
	var d = parseInt(discount.val());
	var t = parseInt(final_fees.val());
	var cf = parseInt(course_fees.val());
 
 
	if( f <= 0 ){

		alert("Total Fees Cannot be Empty");

		stud_fees.val("");

	}

	else if (d > f) {

		alert("Fees Adjustment cannot be more than total fee");

		discount.val("0");

	}
	else if(500<d){
		
		alert("Fees Adjustment cannot be more than 600 Rs.");

		discount.val("0");
	}else{

		jQuery('#stud-final_fees').val(stud_fees.val()-discount.val());		
			
		 
	}
	
	if(cf >= f){
		 
		$('#feesconfirm').modal();
		$('#feesconfirm .modal-body').html('<h3 style="color: red;">Final fees is less then course decided fees after submit please contact to admin!</h3>');
		
	}else if(cf==0){
		$('#feesconfirm').modal();
		$('#feesconfirm .modal-body').html('<h3 style="color: red;">Please update the course fees first!.</h3>');
	 
		
	}

}

		</script>
		 
	<!-- /page content -->
@endsection