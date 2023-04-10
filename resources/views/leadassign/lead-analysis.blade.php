@extends('layouts.cm_app')
@section('title')
Lead Analysis
@endsection
@section('content')	
 
  <div class="right_col" role="main">
  	 
          <div class="">    
			<div class="page-title">
			<div class="title_left">
			<h3>Lead Analysis</h3>
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
				   <div class="col-md-12 col-sm-12">
				       
				       <div id="leads_filter" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;">
							<form method="GET" action="" novalidate autocomplete="off">
							  <div class="form-group">
									<div class="col-md-2">
										<label>Date From</label>
										<input type="text" class="form-control leaddf" name="search[leaddf]" value="{{ $search['leaddf']}}" placeholder="Create Date From">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-2">
										<label>Date To</label>
										<input type="text" class="form-control leaddt" name="search[leaddt]" value="{{ $search['leaddt']}}" placeholder="Create Date From">
									</div>
								</div>
								 
								
								 
								<div class="form-group">
									<div class="col-md-2">
								 <label></label>

									 
										<button type="submit" class="btn btn-block btn-info" style="margin-top: 4px;">Filter</button>
									</div>
								</div>
								
								 
							</form>
								 
							 
						</div>
				   <div class="row">
                   
					<div class="x_panel xp">
						<div class="x_title">

					 

						<table id="data-table-source-count" class="table table-striped table-bordered">
						<thead>
						<tr>					 
                        <th>Date</th>	
						<?php 						 
						$sourcelists = App\Source::whereIn('name',['WhatsApp', 'Ph Enquiry','Website','Chat'])->where('dailystatus',1)->get();
					 
						if($sourcelists){ foreach($sourcelists as $list){ ?>
										<th><?php if($list->name=='WhatsApp'){ echo "WA"; }else if($list->name=='Ph Enquiry'){ echo "P_No"; }else if($list->name=='Website'){
							echo "WS"; }else{ echo $list->name; } ?></th>

						<?php } } ?>
							<th title="Total">Total</th>
								<th title="Direct Visit">DR</th>
							<?php 						 
						$sourcelists = App\Source::whereNotIn('name',['WhatsApp', 'Ph Enquiry','Website','Chat','Direct Visit'])->where('dailystatus',1)->get();
						if($sourcelists){ foreach($sourcelists as $list){ ?>
					<th><?php  
							if($list->name=='Direct Visit'){ 
							echo "DR"; 
							}else if($list->name=='FaceBook'){ 
							echo "FB";
							}else if($list->name=='Google_Adword'){ 
							echo "GA";
							}else if($list->name=='Instagram'){ 
							echo "IG";
							}else if($list->name=='Sulekha'){ 
							echo "SU";
							}else if($list->name=='Yet5'){ 
							echo "Y5";						
							}else if($list->name=='Linkedin'){ 
							echo "LI";

							}else if($list->name=='JustDial'){ 
							echo "JD";

							}else if($list->name=='Urban Pro'){ 
							echo "UP";

							}else if($list->name=='SMO_Leads'){ 
							echo "SM";							
							}else if($list->name=='College Workshop'){ 
							echo "CW";							
							}else if($list->name=='College_Visit'){ 
							echo "CV";							
							}else if($list->name=='College_Duniya'){ 
							echo "CD";							
							}else if($list->name=='Saurabh WhatsApp'){ 
							echo "SW";							
							}else if($list->name=='Dev Phone'){ 
							echo "DP";							
							}else if($list->name=='Dev WhatsApp'){ 
							echo "DW";							
							}else if($list->name=='website(google Ads)'){ 
							echo "WGA";							
							}else{
							echo $list->name; 
							} 	
							?></th>
						<?php } } ?>
						 <th title="Other Total">Total</th>				
						 <th title="Total Lead">G.Total</th>	
						</tr>
					<!--<th title="Website">Website</th>
						<th title="WhatsApp">WhatsApp</th>
						<th title="Ph. Enquiry">Ph_En</th>
						<th title="Devendra Phone">Dev_Ph</th>
						<th title="Devendra WhatsApp">Dev_WA</th>
						<th title="Saurabh WhatsApp">Sau_WA</th>
						<th title="Saurabh WhatsApp">Chat</th>
						<th title="Direct Visit">Dir_Visit</th>
						<th title="FaceBook">FB</th>
						<th title="Instagram">Insta</th>
						<th title="Linkedin">L_in</th>
						<th title="Just Dial">JD</th>
						<th title="Sulekha">Sulekha</th>
						<th title="Yet5">Yet5</th>
						<th title="Google Adword">G_Ad</th>
						<th title="Total Lead">Total_Lead</th>-->	

					 
						
						</thead>
						
					 
						
						
						
						</table>



 





						 
						</div>
					</div>
					
					
							         
							         
						
            </div>
		 
				</div>
				 
				
				 
				
				
				
				
				
				
			</div>
          
            <div class="row">
               
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                
                        <div class="col-md-12 col-sm-12 col-xs-12">
					  <div id="chartContainer" style="height: 250px;"></div>	
                   </div>
                  
                </div>
              </div>

             
               
              
            </div>
			 
          </div>
        </div>
		
		
        <!-- /page content -->
<script>	 
 window.onload = function () {
	
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	 
	axisY:{
		includeZero: true
	},
	legend:{
		cursor: "pointer",
		verticalAlign: "center",
		horizontalAlign: "right",
		itemclick: toggleDataSeries
	},
	data: [{
		type: "column",
		name: "",
		indexLabel: "{y}",
		yValueFormatString: "#0.##",
		showInLegend: true,
		dataPoints: <?php echo $dataPoints; ?>,
	}]
});
chart.render();
 
function toggleDataSeries(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	}
	else{
		e.dataSeries.visible = true;
	}
	chart.render();
}
 
 }
</script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
		 
		@endsection