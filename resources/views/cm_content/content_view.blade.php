@extends('layouts.cm_app')
 @section('title')
     Content View  
@endsection
@section('content')	
<style>
strong {
    color: #000;
}
</style>
 <div class="right_col" role="main">
		<div class="">
			<div class="page-title">
				<div class="title_left">
					
						<div style="text-align:center;" >
						<a href='content/all-content' style="float:left;" class="btn btn-warning ">Back</a>
					</div>
				<h3>Content View</h3>					
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
				 
					<div class="x_panel">	
						<div class="x_content">
					 
							 
							 
							 
							 
							 
							 
							 
							<table class="table table-striped table-bordered">
								<thead>
									<tr><td><strong>Course</strong></td><td>{!! $view_data->course !!}</td>								 
									<tr><td><strong>Introduction</strong></td><td>
											
                        <!--<a id="alterPassword" class="add_or_edit collapsed" data-toggle="collapse" data-target="#p1" aria-expanded="false" style="font-size: 16px;color: #000;font-weight: 600;padding: 5px 3px;cursor: pointer;background: #EEEEEE;">Question <i class="fa fa-plus"></i></a>  					 
					<div class="settings_edit edit_form passwordh collapse" id="p1" aria-expanded="false" style="padding: 13px 0px 5px 20px;position: relative;list-style: none;font-size: 14px;">			 
				 s answer	</div>-->
									
									{!!$view_data->introduction !!}</td>								 
									<tr><td><strong>Modules</strong></td><td>{!! $view_data->modulesdescription !!}</td>								 
									 								 
									<tr><td><strong>Duration</strong></td><td>{!!$view_data->duration !!}</td>								 
									<tr><td><strong>Certification</strong></td><td>{!!$view_data->certification!!}</td>								 
									<tr><td><strong>Live Project</strong></td><td>{!!$view_data->liveproject!!}</td>								 
									<tr><td><strong>Training Mode</strong></td><td>{!!$view_data->trainingmode!!}</td>								 
									<tr><td><strong>Demo Timig</strong></td><td>{!!$view_data->demotimig!!}</td>								 
									<tr><td><strong>About Trainer</strong></td><td>{!!$view_data->abouttrainer!!}</td>								 
								 								 
									<tr><td><strong>Placement tieup</strong></td><td>{{$view_data->placementtieup}}</td>								 
									<tr><td><strong>Placement Ratio</strong></td><td>{{$view_data->placementratio}}</td>								 
									</tr>
								</thead>
							</table>
							 
							 

						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div style="text-align:center;" >
						<a href='content/all-content' style="float:left;" class="btn btn-warning ">Back</a>
					</div>
	</div>
						
	<!-- /page man body  container -->

 @endsection
 

 