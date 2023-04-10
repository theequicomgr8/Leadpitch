@extends('layouts.cm_app')
@section('title')
All Inquiry
@endsection
@section('content')	
 <style>
 .form-control {
    display: block;
    width: 100%;
    height: 38px;
 }
 
 #leads_filters, table#datatable-lead > thead, table#datatable-demo > thead {
    background-color: #2A3F54;
    color: #FFF;
}
 </style>
  <div class="right_col" role="main">
          <div class="">    
			<div class="page-title">
			<div class="title_left">
			<h3>Inquiry</h3>
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
                  
                    <div id="leads_filters" class="col-md-12" style="border-bottom:2px solid #E6E9ED;margin-bottom:10px;padding-bottom:10px;padding-left:10px;padding-right:0px;">
							<div class="all-inquiry-form-av">
							<form method="GET" action="" novalidate autocomplete="off">
							       
							  <div class="form-group">
									<div class="">
										<label>Date From</label>
										<input type="text" class="form-control leaddf" name="search[leaddf]" value="{{ $search['leaddf']}}" placeholder="Create Date From">
									</div>
								</div>
								<div class="form-group">
									<div class="">
										<label>Date To</label>
										<input type="text" class="form-control leaddt" name="search[leaddt]" value="{{ $search['leaddt']}}" placeholder="Create Date From">
									</div>
								</div>
								
								<div class="form-group">
									<div class="">
										<label>Cousellor</label>
										<select class="form-control select_courseb" name="search[user]">
											<option value="">Select Cousellor</option>
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
								 
								<div class="form-group">
								    	<div class="">
										<label>Cources</label>
										<select class="form-control select_courseb" name="search[courses]">
											<option value="">Select Course</option>
										 
												@foreach($catCourse as $courses)
												@if(isset($search['courses']) && $search['courses']==$courses->id)
														<option value="{{ $courses->id }}" selected><?php echo  substr($courses->name,0,16); ?></option>
													@else
														<option value="{{ $courses->id }}"><?php echo substr($courses->name,0,16); ?></option>
													@endif
												@endforeach
										 
										</select>
								 </div>
								</div>
								 
								<div class="form-group">
									<div class="btn-width-av">
								 <label></label>

									 
										<button type="submit" class="btn btn-block btn-info" style="margin-top: 31px;height: 38px;">Filter</button>
									</div>
								</div>
								
								<div class="form-group circle-avd">
									<div style="margin-top:8px;">
								<div class="circle"><?php  
                                $lastdate =date('Y-m-d');
						        $m=date('m');
						        $d=date('d');
						   	$demo = App\Demo::whereDate('created_at','=',date_format(date_create($lastdate),'Y-m-d'))->where('source',11)->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
						   	$leacount = App\Inquiry::whereDate('created_at','=',date_format(date_create($lastdate),'Y-m-d'))->groupBy('mobile','course',DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->get()->count();
							echo $leacount+$demo;
							
					 
						   ?></div>
									</div>
								</div>
							</form>
							</div>
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
                        margin-left: 65px;
                        }
                        .all-inquiry-form-av form
                        {
                        display: flex;
                        justify-content: space-between;    flex-wrap: wrap;
                        
                        align-items: center;
                        text-align: center;
                        }
                        .all-inquiry-form-av .form-group label{
                        display: block;
                        text-align: start;
                        }
                        
                        .btn-width-av button{
                        width: 125px;
                        
                        }
                        
                        @media only screen and (max-width: 768px) {
                        .all-inquiry-form-av .form-group{
                        width:48%;
                        }
                        .btn-width-av button {
                        width: 100%;
                        }
                        .circle-avd
                        {
                        width:100% !important;
                        
                        }
                        .circle {
                        margin:auto;
                        }
                        }
                        </style>
								 
							</form>
						</div>
                  <div class="x_content">
                       
                    <table id="datatable-all-leads-assign" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                            <th><input type="checkbox" id="check-all" class="check-box-lead"></th>
						    <th>Date</th>                          
                            <th>Name</th>                          
                            <th class="" style="overflow:hidden;">Mobile</th>                          
                            <!-- <th>Email</th>  
						   <th>Page Name</th>	-->					  
                            <th>Course</th>                          
                            <th>Form Filed</th>  
                            <th>Counsellor</th>
                         
                           
                        </tr>
                      </thead>


                       </table>
                  </div>
                </div>
                 
                	@if(Auth::user()->current_user_can('super_admin')|| Auth::user()->current_user_can('delete_lead_assign') )
                <div class="col-lg-4">
				<button type="button" class="btn btn-success  btn-block move-not-interested" onclick="javascript:leadAssignCromaController.selectLeads()" >Delete</button>
				</div>	
				@endif
              </div>

             
               
              
            </div>
			 
          </div>
        </div>
		
 	<script>
		
	function autoRefresh_div()
	{    

		$.ajax({
		url: "/getleadcount",
		dataType: 'json',
		contentType: "application/json; charset=utf-8",
		success:  
		function(data,textStatus,jqXHR){ 
		if(data.statusCode){
		var payload = data.success.count_data;
		  
		$('.circle').html(payload);  
		} 
		}
		});
	}
	setInterval('autoRefresh_div()', 35000);
		</script> 
		@endsection