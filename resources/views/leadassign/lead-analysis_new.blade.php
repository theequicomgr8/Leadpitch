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

					 

						<table id="data-table-source-counts" class="table table-striped table-bordered">
						<thead>
						<tr>					 
                        <th>Date</th>		
						<?php 						 
						$sourcelists = App\Source::whereIn('name',['WhatsApp', 'Ph Enquiry','Website'])->where('dailystatus',1)->get();
					 
						if($sourcelists){ foreach($sourcelists as $list){ ?>
						<th><?php  echo $list->name; 	 ?></th>
						<?php } } ?>
							<th title="Total">Total</th>
							<?php 						 
						$sourcelists = App\Source::whereNotIn('name',['WhatsApp', 'Ph Enquiry','Website'])->where('dailystatus',1)->get();
						if($sourcelists){ foreach($sourcelists as $list){ ?>
						<th><?php  echo $list->name; 	 ?></th>
						<?php } } ?>
						 <th title="Other Total">Total</th>				
						 <th title="Total Lead">Total_Lead</th>				
						 
						 

						</tr>
						
						</thead>
						
					 
						<tbody>
						<?php  $sources = App\Inquiry::select('created_at')->orderBy('created_at','DESC');	
								$sources = $sources->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get();	
$sum=0;
if(!empty($sources)){ foreach($sources as $source){
								?>
						<tr>
						<td><?php  echo date('d-M-y',strtotime($source->created_at));		 ?></td>
						<?php 
							$sourcelist = App\Source::whereIn('name',['WhatsApp', 'Ph Enquiry','Website'])->where('dailystatus',1)->get();
						
						$countno=0;
						$total_lead=0;
						if($sourcelist){ foreach($sourcelist as $list){ ?>
						<td><?php   
						$countno =App\Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',$list->id)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
						
						echo $countno;
						
						$total_lead +=$countno;
						?></td>
						
						
						<?php } } ?>
						<td><?php   					 
						echo $total_lead;
						?></td>
						<?php 
							$sourcelistnt = App\Source::whereNotIn('name',['WhatsApp', 'Ph Enquiry','Website'])->where('dailystatus',1)->get();
						
						$counttotal=0;
						$total_lead_t=0;
						if($sourcelistnt){ foreach($sourcelistnt as $list){ ?>
						<td><?php   
						$counttotal =App\Inquiry::whereDate('created_at','=',date_format(date_create($source->created_at),'Y-m-d'))->where('source_id',$list->id)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
						
						echo $counttotal;
						
						$total_lead_t +=$counttotal;
						?></td>
						<?php } } ?>
						<td><?php    echo $total_lead_t; ?></td>
						<td><?php    echo $total_lead+$total_lead_t; ?></td>
						
						</tr>
<?php }  } ?>
						</tbody>
						
						
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