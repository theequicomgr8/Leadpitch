@extends('layouts.cm_app')
@section('title')
     Dashboard  
@endsection
@section('content')
<?php echo "dashboard"; ?>
	<!-- page content -->
	<div class="right_col" role="main" ng-controller="counsellorDashboard">
		<!-- top tiles -->
		<div class="row tile_count">
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
				<span class="count_top"><i class="fa fa-users"></i> Total Leads</span>
				<div class="count">
					<!--<%total_leads%>-->
				</div>
				<span class="count_bottom"><i class="green">4%</i> From last Week</span>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
				<span class="count_top"><i class="fa fa-tachometer"></i> Interested</span>
				<div class="count green">
					<%total_interested%>
				</div>
				<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
				<span class="count_top"><i class="fa fa-tty"></i> Follow Up</span>
				<div class="count red">
					<%total_follow_up%>
				</div>
				<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
				<span class="count_top"><i class="fa fa-handshake-o"></i> Calling Visits</span>
				<div class="count">
					<%calling_visits%>
				</div>
				<span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
				<span class="count_top"><i class="fa fa-paw"></i> Direct Visits</span>
				<div class="count">
					<%direct_visits%>
				</div>
				<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
				<span class="count_top"><i class="fa fa-graduation-cap"></i> Joined</span>
				<div class="count">
					<%total_joined%>
				</div>
				<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
			</div>
		</div>
		<!-- /top tiles -->

		<div class="row top_tiles">
			<div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-users"></i></div>
					<div class="count">
						<%total_leads_tm%>
					</div>
					<h3>Total Leads <small style="font-size:12px;">(<?php echo "in ".date('M`y') ?>)</small></h3>
					 
				</div>
			</div>
			<div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-tachometer"></i></div>
					<div class="count green">
						<%total_interested_tm%>
					</div>
					<h3>Interested <small style="font-size:12px;">(<?php echo "in ".date('M`y') ?>)</small></h3>
					 
				</div>
			</div>
			<div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-tty"></i></div>
					<div class="count red">
						<%total_follow_up_tm%>
					</div>
					<h3>Follow Up <small style="font-size:12px;">(<?php echo "in ".date('M`y') ?>)</small></h3>
					 
				</div>
			</div>
			<div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-handshake-o"></i></div>
					<div class="count">
						<%calling_visits_tm%>
					</div>
					<h3>Calling Visits <small style="font-size:12px;">(<?php echo "in ".date('M`y') ?>)</small></h3>
					 
				</div>
			</div>
			<div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-paw"></i></div>
					<div class="count">
						<%direct_visits_tm%>
					</div>
					<h3>Direct Visits <small style="font-size:12px;">(<?php echo "in ".date('M`y') ?>)</small></h3>
					 
				</div>
			</div>
			<div class="animated flipInY col-lg-2 col-md-2 col-sm-6 col-xs-12">
				<div class="tile-stats">
					<div class="icon"><i class="fa fa-graduation-cap"></i></div>
					<div class="count">
						<%total_joined_tm%>
					</div>
					<h3>Joined <small style="font-size:12px;">(<?php echo "in ".date('M`y') ?>)</small></h3>
					 
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="x_panel tile fixed_height_320" >				 
					<div class="x_title calling_status">
						<h2>Daily Calling Status (<small><%fromDate%> - <%toDate%></small>)</h2>	
						<style>
						.cancelBtn{
							display:inherit;
							width:-webkit-fill-available;
						}
						#reportrange span{
							display:none;
						}
						</style>						
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
							<li id="reportrange_test" >
								<a href="javascript:void(0)" ng-click="showDCS_DateRangePicker()">
									<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
									<span></span> <b class="caret"></b>
								</a>
								<div ng-show="dcs_DateRangePicker" class="well" style="position:absolute;top:40px;left:0;right:auto;display:block;max-width:none;z-index:3001;
								direction:ltr;text-align:left;float:left;width:300px;margin-left:-253px;">
									<div class="col-xs-6"><label>From:</label><input type="text" class="form-control fromDate" value="<?php echo date('Y-m-d')?>" /></div>
									<div class="col-xs-6"><label>To:</label><input type="text" class="form-control toDate" value="<?php echo date('Y-m-d')?>" /></div>
									<div class="col-xs-6 col-xs-offset-6" style="padding-top:10px;"><input type="submit" value="submit" class="btn btn-success btn-block" ng-click="getCallingStatus()" /></div>
								</div>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>New Leads</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-blue" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_new_lead_percent%>%;">
										<span class="sr-only"><%dcs_new_lead_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="blue"><%dcs_new_lead%></span>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Interested</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_interested_percent%>%;">
										<span class="sr-only"><%dcs_interested_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="green"><%dcs_interested%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Pending</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-red" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_pending_percent%>%;">
										<span class="sr-only"><%dcs_pending_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="red"><%dcs_pending%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Not Intr...</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-light-red" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_not_interested_percent%>%;">
										<span class="sr-only"><%dcs_not_interested_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="light-red"><%dcs_not_interested%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Visits</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-purple" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_visits_percent%>%;">
										<span class="sr-only"><%dcs_visits_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="purple"><%dcs_visits%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Joined</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-dark-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%dcs_joined_percent%>%;">
										<span class="sr-only"><%dcs_joined_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="dark-green"><%dcs_joined%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<h5>Total Call Count: <span style="float:right"><%dcs_total_call_count_leads%>[L] / <%dcs_total_call_count_demos%>[D]</span> </h5>
					</div>
				</div>
			</div>
			
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="x_panel tile fixed_height_320">
					<div class="x_title calling_status">
						<h2>Weekly Calling Status</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>New Leads</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-blue" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%wcs_new_lead_percent%>%;">
										<span class="sr-only"><%wcs_new_lead_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="blue"><%wcs_new_lead%></span>
							</div>
							<div class="clearfix"></div>
						</div>

						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Interested</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%wcs_interested_percent%>%;">
										<span class="sr-only"><%wcs_interested_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="green"><%wcs_interested%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Pending</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-red" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%wcs_pending_percent%>%;">
										<span class="sr-only"><%wcs_pending_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="red"><%wcs_pending%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Not Intr...</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-light-red" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%wcs_not_interested_percent%>%;">
										<span class="sr-only"><%wcs_not_interested_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="light-red"><%wcs_not_interested%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Visits</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-purple" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%wcs_visits_percent%>%;">
										<span class="sr-only"><%wcs_visits_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="purple"><%wcs_visits%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget_summary">
							<div class="w_left w_25">
								<span>Joined</span>
							</div>
							<div class="w_center w_55">
								<div class="progress">
									<div class="progress-bar bg-dark-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width:<%wcs_joined_percent%>%;">
										<span class="sr-only"><%wcs_joined_percent%>% Complete</span>
									</div>
								</div>
							</div>
							<div class="w_right w_20">
								<span class="dark-green"><%wcs_joined%></span>
							</div>
							<div class="clearfix"></div>
						</div>
						<h5>Total Call Count: <span style="float:right"><%wcs_total_call_count_leads%>[L] / <%wcs_total_call_count_demos%>[D]</span> </h5>
					</div>
				</div>
			</div>

			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="x_panel tile fixed_height_320 overflow_hidden">
					<div class="x_title calling_status">
						<h2>Lead Status</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
							</li>
						</ul>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<table class="" style="width:100%">
							<tr>
								<th style="width:37%;">
									<p>Top 5</p>
								</th>
								<th>
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
										<p class="">Device</p>
									</div>
									<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
										<p class="">Progress</p>
									</div>
								</th>
							</tr>
							<tr>
								<td>
									<canvas class="canvasDoughnut" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
								</td>
								<td>
									<table class="tile_info">
										<tr>
											<td>
												<p><i class="fa fa-square blue"></i>IOS </p>
											</td>
											<td>30%</td>
										</tr>
										<tr>
											<td>
												<p><i class="fa fa-square green"></i>Android </p>
											</td>
											<td>10%</td>
										</tr>
										<tr>
											<td>
												<p><i class="fa fa-square purple"></i>Blackberry </p>
											</td>
											<td>20%</td>
										</tr>
										<tr>
											<td>
												<p><i class="fa fa-square aero"></i>Symbian </p>
											</td>
											<td>15%</td>
										</tr>
										<tr>
											<td>
												<p><i class="fa fa-square red"></i>Others </p>
											</td>
											<td>30%</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>	 
		</div>
		
		
		 
		  
		
		
		
	</div>
 
<script>
var messageBody = document.querySelector('#chatshow');
	messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
	
</script>
 
	
	<!-- /page content -->
@endsection