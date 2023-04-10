@extends('layouts.cm_app')
@section('title')
Source view
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

									 
										<button type="submit" class="btn btn-block btn-info">Filter</button>
									</div>
								</div>
								
								 
							</form>
								 
							 
						</div>
				   <div class="row">
                   
					<div class="x_panel xp">
						<div class="x_title">

					 

						<table id="data-table-source-count" class="table table-bordered table-hover table-striped">
						<thead>
						<tr>	
						<th>Date</th>					
						<th title="Website">Website</th>
						<th title="WhatsApp">WhatsApp</th>
						<th title="Ph. Enquiry">Ph_En</th>
						<th title="Devendra Phone">Dev_Ph</th>
						<th title="Devendra WhatsApp">Dev_WA</th>
						<th title="Saurabh WhatsApp">Sau_WA</th>
						<th title="Direct Visit">Dir_Visit</th>
						<th title="FaceBook">FB</th>
						<th title="Instagram">Insta</th>
						<th title="Linkedin">L_in</th>
						<th title="Just Dial">JD</th>
						<th title="Sulekha">Sulekha</th>
						<th title="Yet5">Yet5</th>
						<th title="Google Adword">G_Ad</th>
						<th title="Total Lead">Total_Lead</th>
						
						 <!--<th><?php $current =  date('d-m-Y');						
						echo  date('M d', strtotime($current));
						?></th>
						<th><?php $current =  date('d-m-Y');						
						echo  date('M d', strtotime($current. ' - 1 day'));
						?></th>
						<th><?php  				
						echo date('M d', strtotime($current. ' - 2 day'));
						  ?></th>
						<th><?php  					
						echo date('M d', strtotime($current. ' - 3 day'));
						  ?></th>
						<th><?php  				
						echo date('M d', strtotime($current. ' - 4 day'));
						  ?></th>
						<th><?php  						
						 echo date('M d', strtotime($current. ' - 5 day'));
						  ?></th>
						<th><?php  						
						echo date('M d', strtotime($current. ' - 6 day'));
						  ?></th>
						<th><?php  					
						 echo  date('M d', strtotime($current. ' - 7 day'));
						 ?></th>
						<th><?php  					
						 echo  date('M d', strtotime($current. ' -8 day'));
							?></th>
						<th><?php  						
						 echo date('M d', strtotime($current. ' - 9 day'));
						  ?></th> -->
					 

						</tr>
						
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
                   
					  <div id="chartContainer" style="height: 150px; width: 100%;"></div>	
                   
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