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
					<h3>All Counsllor View</h3>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				   <div class="col-md-12 col-sm-12">
				   <div class="row">
                   
					<div class="x_panel xp">
						<div class="x_title">

					 

						<table id="" class="table table-striped table-bordered">
						<thead>
						<tr>					 

						<th>Name</th>
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
						  ?></th>
						<th><?php  				
						echo date('M d', strtotime($current. ' - 10 day'));
						  ?></th>

						</tr>
						
						</thead>
						
						<tbody>
						
						<?php //echo "<pre>";print_r($users); ?>
						
						@if(!empty($users))
							
						@foreach($users as $user)
								<tr>	
								<td>
								<a data-toggle="modal" data-target="#coursewiselead_<?php echo $user->id ?>"> <i class="fa fa-list fa-fw" style="cursor: pointer;"></i></a>
								{{$user->name}}
								
								
							<div id="coursewiselead_<?php echo $user->id ?>" class="modal fade" role="dialog">
							<div class="modal-dialog modal-lg" style="margin-left: 300px;">
							<div class="modal-content">
							<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title" style="float: left;">Follow Up</h4>
							</div>
							<div class="modal-body" style="padding-top:0">
							 
							 
						<table id="" class="table table-striped table-bordered">
						<thead>
						<tr>					 

						<th>Course</th>
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
						  ?></th>
						<th><?php  				
						echo date('M d', strtotime($current. ' - 10 day'));
						  ?></th>

						</tr>
						
						</thead>
						
						<tbody>
						<?php  
						$courselist = App\Lead::where('created_by',$user->id)->groupby('course')->get();					 
						if(!empty($courselist)){
						foreach($courselist as $course){
						?>
						<tr>
						<td><?php  echo substr($course->course_name,0,16); ?></td>
						<td><?php $current =  date('d-m-Y');						
							$dayfirst=  date('d-m-Y', strtotime($current. ' - 1 day'));	
							$leadfirst = App\Lead::whereDate('created_at','=',date_format(date_create($firstday),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();
							  
							echo $leadfirst;
							?></td>
						<td> <?php  				
							$daysecond=  date('d-m-Y', strtotime($current. ' - 2 day'));	
							$leadsecond = App\Lead::whereDate('created_at','=',date_format(date_create($daysecond),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();							  
							echo $leadsecond;
							?></td>
						<td><?php  				
							$daythird=  date('d-m-Y', strtotime($current. ' - 3 day'));	
							$leadthird = App\Lead::whereDate('created_at','=',date_format(date_create($daythird),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();							  
							echo $leadthird;
							?></td>
						<td> <?php  				
							$dayfour=  date('d-m-Y', strtotime($current. ' - 4 day'));	
							$leadfour = App\Lead::whereDate('created_at','=',date_format(date_create($dayfour),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();							  
							echo $leadfour;
							?></td>
						<td><?php  				
							$dayfive=  date('d-m-Y', strtotime($current. ' - 5 day'));	
							$leadfive = App\Lead::whereDate('created_at','=',date_format(date_create($dayfive),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();							  
							echo $leadfive;
							?></td>
						<td> <?php  				
							$daysix=  date('d-m-Y', strtotime($current. ' - 6 day'));	
							$leadsix = App\Lead::whereDate('created_at','=',date_format(date_create($daysix),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();							  
							echo $leadsix;
							?></td>
						<td> <?php  				
							$dayseven=  date('d-m-Y', strtotime($current. ' - 7 day'));	
							$leadseven = App\Lead::whereDate('created_at','=',date_format(date_create($dayseven),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();							  
							echo $leadseven;
							?></td>
						<td>  <?php  				
							$dayeight=  date('d-m-Y', strtotime($current. ' - 8 day'));	
							$leadeight = App\Lead::whereDate('created_at','=',date_format(date_create($dayeight),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();							  
							echo $leadeight;
							?></td>
						<td>  <?php  				
							$daynine=  date('d-m-Y', strtotime($current. ' - 9 day'));	
							$leadnine = App\Lead::whereDate('created_at','=',date_format(date_create($daynine),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();							  
							echo $leadnine;
							?></td>
						<td> <?php  				
							$dayten=  date('d-m-Y', strtotime($current. ' - 10 day'));	
							$leadten = App\Lead::whereDate('created_at','=',date_format(date_create($dayten),'Y-m-d'))->where('course',$course->course)->where('created_by',$course->created_by)->get()->count();							  
							echo $leadten;
							?></td>
						
						</tr>
						<?php  }  } ?>
						
						 
						
						</tbody>
						</table>
							 
							</div>
							</div>

							</div>
							</div>
								
								</td>
								 
								</tr>
								@endforeach
								@endif
								
							
								
								 </tbody>
						</table>
						</div>
					</div>
            </div>
		 
				</div>
			</div>
		</div>
 
		
 
	 
	 
	</div>
 
		  
	<!-- /page content -->
@endsection