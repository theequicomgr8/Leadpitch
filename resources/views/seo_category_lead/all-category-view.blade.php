@extends('layouts.cm_app')
@section('title')
     All Counsllor View
@endsection
@section('content')		

	<div class="right_col" role="main">
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
				margin-bottom: 5px !important;
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
			 
			 .xp .panel_toolbox{
				min-width: 0px;
			 }
			 .xp .x_title {
    			/* border-bottom: 1px solid #2fa4cd; */
    			border-bottom: none;
				margin-bottom: 0px !important;
			 }
			 .xp .x_content {
				padding: 0px 0px !important;
				float: left;
				clear: both;
				margin-top: -6px;
			 }
				.xp .table {
				width: 100%;
				max-width: 100%;
				margin-bottom: 0px; 
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
			 .lms-accordian .paccordion.aps a{
				float:right;
				padding:5px;	
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
			
			.x_title h2 {   
				cursor: pointer;
			}
			.x_title{
				padding: 0px !important;
			}
		 </style>	
<style>
					tr th{
						    width: 101px
;
					}
					.x_title h2 {
					margin: 0px 0 0px;
					float: left;
					display: block;
					}
					.card-header ul{
						display: flex;
						flex-direction: row;
						margin-left: -40px;
						width: 100%;
						height: auto;					 
						z-index: 10;
						align-items: center;
						margin-right: 0px;
						margin-top: 0px;
						white-space: break-spaces;
					}
					.card-header li{
						display: flex;
					  justify-content: center;
					align-items: center;
					 flex: 1;    
					color: #4c4c4c !important;
					transition: all 0.5s ease;
					font-size: 12px;
					font-weight: 600;					 
					margin-right: 0px;
					text-decoration: none !important;
					border: 1px solid #ddd;
					width: 100%;
					width: 101px;
					max-width: 100%;					 
					padding: 5px 2px;
					     height: 35px;
					 
					}
					
					.card-body ul{
					display: flex;
						flex-direction: row;
						margin-left: -40px;
						width: 100%;
						height: auto;					 
						z-index: 10;
						 
						margin-right: 0px;
						margin-top: 0px;
					}
					.card-body ul li{ 
					
					display: flex;
					  justify-content: center;
					align-items: center;
					 flex: 1;    
					color: #4c4c4cd4 !important;
					transition: all 0.5s ease;
					font-size: 12px;
					font-weight: 600;					 
					margin-right: 0px;
					text-decoration: none !important;
					border: 1px solid #ddd;
					width: 100%;
					width: 110.7px;
					max-width: 100%;					 
					padding: 5px 2px;
					margin-top: -11px;
					height: 35px;
					min-width: 97.4px;
					 }
					 
	th {
    text-align: center;
}
td {
    text-align: center;
}

 
td:first-child { text-align: left };
 
					
					</style>		 
		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>Seo Lead Analysis</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				   <div class="col-md-12 col-sm-12">
				   <div class="row">
                   
					<div class="x_panel xp">
						<div class="x_title">

					 

						<table id="seo-data-table-category-view" class="table table-striped table-bordered">
						<thead>
						<tr>					 

						<th style="width:300px;">Name</th>
						<th><?php $current =  date('d-m-Y');						
						echo  date('M', strtotime($current));
						?></th>
						<th><?php 					
						echo  date('M', strtotime($current. ' - 1 month'));
						?></th>
						<th><?php  				
						echo date('M', strtotime($current. ' - 2 month'));
						  ?></th>
						<th><?php  					
						echo date('M', strtotime($current. ' - 3 month'));
						  ?></th>
						<th><?php  				
						echo date('M', strtotime($current. ' - 4 month'));
						  ?></th>
						<th><?php  						
						 echo date('M', strtotime($current. ' - 5 month'));
						  ?></th>
						<th><?php  						
						echo date('M', strtotime($current. ' - 6 month'));
						  ?></th>
						<th><?php  					
						 echo  date('M', strtotime($current. ' - 7 month'));
						 ?></th>
						<th><?php  					
						 echo  date('M', strtotime($current. ' -8 month'));
							?></th>
						<th><?php  						
						 echo date('M', strtotime($current. ' - 9 month'));
						  ?></th>
						  <th><?php  						
						 echo date('M', strtotime($current. ' - 10 month'));
						  ?></th>
						  <th><?php  						
						 echo date('M', strtotime($current. ' - 11 month'));
						  ?></th>
						  
					 

						</tr>
						
						</thead>
						
					 
						
						
						
						</table>



 





						 
						</div>
					</div>
					
					
							         
							         
						
            </div>
		 
				</div>
				 
				
				 
				
				
				
				
				
				
			</div>
		</div>
		<style>
			#followUpModal .form-control{
				margin-bottom:10px;
			}
		</style>
		<div id="viewCourseWiseLead" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content" style="width:1300px; margin-left: 10px; margin-left: -177px;">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title" id="category-name">View Course Lead</h4>
					</div>
					<div class="modal-body" style="padding-top:0">
					</div>
				 
				</div>

			</div>
		</div>
 
		<div id="followUpModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Follow Up</h4>
					</div>
					<div class="modal-body" style="padding-top:0">
					</div>
					<!--div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div-->
				</div>

			</div>
		</div>
		<div id="bulkSmsModal" class="modal fade" role="dialog">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Bulk SMS</h4>
					</div>
					<div class="modal-body">
						<textarea placeholder="Enter your custom message here..." id="bulkSmsControl" rows="10" class="form-control"></textarea>
						<div class="form-group" style="padding-top:20px;margin-bottom:0">
							<div class="col-md-3" style="margin:0 auto;float:none;">
								<button type="button" style="background-color:#169f85;color:#fff;" onclick="javascript:leadController.sendBulkSms()" class="btn btn-block">Submit</button>
							</div>
							<div class="clearfix"></div>
						</div>
						
					</div>
					<!--div class="modal-footer">
						<button type="button" class="btn btn-default" >Submit</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div-->
				</div>

			</div>
		</div>
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
		
		
		var acc = document.getElementsByClassName("accordion-lms");
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