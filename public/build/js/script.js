// ************
// X-CSRF-TOKEN
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
// X-CSRF-TOKEN
// ************
	 
// ***********
// DATA TABLES
var dataTableFollowUps;
var recordCollection;
var demoRecordCollection;
/*
function autoRefresh_div()
 {  
   
    
     $.ajax({
        url: "/getnotification",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        success:  
        function(data,textStatus,jqXHR){ 
            if(data.statusCode){
                var payload = data.success.follow_data;
            
          document.addEventListener("DOMContentLoaded", function () {
        if (!Notification) {
       alert('Desktop notifications not available in your browser. Try Chromium.'); 
       return;
        }

        if (Notification.permission !== "granted")
       Notification.requestPermission();
      });

     
      
        if (Notification.permission !== "granted")
       Notification.requestPermission();
        else {
       var notification = new Notification(" ", {
         icon: "assets/images/logoleads.png",
         body: "Name: "+payload.Name+"\nMobile: "+payload.Mobile+"\nCourse: "+payload.Course,
        
          
       });
       notification.onclick = function () {	
	window.location.href = "javascript:leadController.getfollowUps("+payload.Lead_id+")";  
      var follow_id = "+payload.Lead_id+";
        $.ajax({
         type: "post",
         url: "/lead/update_job_notification",
         data: {follow_id:follow_id},
         cache: false,
         success: function(data){
			 window.open = 'lead/all-leads'; 	   
	        }
        });  
     //setTimeout(notification.close.bind(notification), 50000);
	 
	  };
	 setTimeout ( "autoRefresh_div()", 50000 );
        }
           //var objn= JSON.parse(response);
          //  $('#notification_auto').html(data); //insert text of test.php into your div
          
            
        }else{
            
        }
        
        
        }
    });
 }
 setInterval('autoRefresh_div()', 30000);  
*/

/*
function autoRefresh_divdemo()
 {  
   
    //window.location = window.location.href;
    // $("#notification_auto").load(location.href + " #notification_auto");
      
     $.ajax({
        url: "/getnotificationdemo",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        success:  
        function(data,textStatus,jqXHR){ 
            if(data.statusCode){
                var loaddemo = data.success.follow_data;
          document.addEventListener("DOMContentLoaded", function () {
        if (!Notification) {
       alert('Desktop notifications not available in your browser. Try Chromium.'); 
       return;
        }

        if (Notification.permission !== "granted")
       Notification.requestPermission();
      });

     
      
        if (Notification.permission !== "granted")
       Notification.requestPermission();
        else {
      
         var notification = new Notification(" ", {
         icon: "assets/images/logodemo.png",
         body: "Name: "+loaddemo.Name+"\nMobile: "+loaddemo.Mobile+"\nCourse: "+loaddemo.Course,
          
       });
       notification.onclick = function () {	
	window.location.href = "javascript:demoController.getfollowUps("+loaddemo.Demo_id+")";  
      var demo_id = "+loaddemo.Demo_id+";
        $.ajax({
         type: "post", 
         url: "/demo/update_job_notification",
         data: {demo_id:demo_id},
         cache: false,
         success: function(data){
			 window.open = 'demo/all-leads'; 	   
	        }
        });  
    // setTimeout(notification.close.bind(notification), 5000);
	 
	  };
	 
        }
           //var objn= JSON.parse(response);
          //  $('#notification_auto').html(data); //insert text of test.php into your div
          
            
        }else{
            
        }
        
        
        }
    });
 }
 setInterval('autoRefresh_divdemo()', 30000);  
 
 */
var datatablecourselist = $('#datatable-courselist').DataTable({
	
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	
});


var datatableAssignCourse = $('#datatable-assign-course').DataTable({
	
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	
});
 
 
 
var dataTableBucketAssign = $('#datatable-bucket-assign')
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"ajax":{
		url:"/bucket/get-bucket-assign",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['counsellors']=$('*[name="search[counsellors]"]').val(); 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();



var dataTableCounsellorPaymentMode = $('#datatable-all-counsellorPaymentMode')
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"ajax":{
		url:"/lead/get-counsellor-payment-mode",
		data:function(d){
			d.page = (d.start/d.length)+1;
			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableAbsentAssignView = $('#datatable-absent-assign-view')
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"ajax":{
		url:"/absent/get-absent-assign-view",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['counsellors']=$('*[name="search[counsellors]"]').val(); 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();
var dataTableRolePermission = $('#datatable-role-permission')
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"ajax":{
		url:"/role-permission/getpermission",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTablePermission = $('#datatable-permission')
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"ajax":{
		url:"/permission/getpermission",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();


 
$('#data-table-source-counts').DataTable({			
columnDefs: [ { type: 'date', 'targets': [0] } ],
order: [[ 0, 'ASC' ]],
"lengthMenu": [
[10,150,250,500],
['10','150','250','500']
],
});

//daily analysis Batches 
var dataTablecSourceView = $('#data-table-source-count').on('draw.dt',function(e,settings){
	$('#data-table-source-count').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"ajax":{
		url:"/genie/lead/get-source-count",
		data:function(d){
			d.page = (d.start/d.length)+1; 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();	
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();
    
 var dataTableleadassign = $('#datatable-all-leads-assign')
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	 
	"ordering":false,
	"lengthMenu": [
            [20,25,50,100,250,500],
            ['20','25','50','100','250','500']
        ],
		  
	"ajax":{
		url:"/genie/get-lead",
		data:function(d){		 
			
			d.page = (d.start/d.length)+1;	
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();			 	
			d.search['user']=$('*[name="search[user]"]').val();			 	
			d.search['source']=$('*[name="search[source]"]').val();	
			d.search['courses']=$('*[name="search[courses]"]').val();	
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection; 
			
			return json.data;
		}
	}
}).api();

 var dataTableLeadAssignCourse = $('#datatable-lead-assign-course')
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	
	"ordering":false,
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
		  
	"ajax":{
		url:"/genie/get-assign-course",
		data:function(d){		 
			
			d.page = (d.start/d.length)+1;	
			 	 	
			d.search['user']=$('*[name="search[user]"]').val();			 	
		//	d.search['course']=$('*[name="search[course]"]').val();			 	
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection; 
			
			return json.data;
		}
	}
}).api();

	
//Upcoming Batches 
var dataTablecSourceView = $('#data-table-monthly-lead-analysis').on('draw.dt',function(e,settings){
	$('#data-table-monthly-lead-analysis').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,	
"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],	
	"ajax":{
		url:"/genie/lead/get-monthly-lead-analysis",
		data:function(d){
			d.page = (d.start/d.length)+1; 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();	
			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();




//category dashboard lead start SK
var dataTablecCategoryView = $('#data-table-category-view').on('draw.dt',function(e,settings){
	$('#data-table-category-view').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,	 
	"ajax":{
		url:"/lead/get-category-view",
		data:function(d){
			d.page = (d.start/d.length)+1; 
			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();

//https://leadpitch.in/dashboard/seo-categorylead-analysis
//seo category lead analysis start SK 13 march
var seodataTablecCategoryView = $('#seo-data-table-category-view').on('draw.dt',function(e,settings){
	$('#seo-data-table-category-view').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,	 
	"ajax":{
		url:"/lead/get-seo-category-view",
		data:function(d){
			d.page = (d.start/d.length)+1; 
			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();


//sk
var categorydashboardController = (function(){
		return {
			getCategoryWiseCountlead:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/getCategoryCourseWiseCountlead/"+id,
					type:"GET",
					success:function(response){
						 
						$('#viewCourseWiseLead .modal-body').html(response.html);
						datatableCourseLead = $('#datatable-course-lead').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":true,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/lead/getCategoryCourseLead/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									
								},
								dataSrc:function(json){
								recordCollection = json.recordCollection;
								$("#category-name").text(json.categoryname+"  ("+json.total_current_month+")"); //for category name
								return json.data;
								}
							}
						}).api();
						
						$('#viewCourseWiseLead').modal({keyboard:false,backdrop:'static'});
						$('#viewCourseWiseLead .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
		};
	})();
	
	
	
	//seo category pop start
	var seocategorydashboardController = (function(){
		return {
			getCategoryWiseCountlead:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/seo-getCategoryCourseWiseCountlead/"+id,
					type:"GET",
					success:function(response){
						 
						$('#viewCourseWiseLead .modal-body').html(response.html);
						datatableCourseLead = $('#seo-datatable-course-lead').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":true,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/lead/seo-getCategoryCourseLead/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									
								},
								dataSrc:function(json){
								recordCollection = json.recordCollection;
								$("#category-name").text(json.categoryname+"  ("+json.total_current_month+")"); //for category name
								return json.data;
								}
							}
						}).api();
						
						$('#viewCourseWiseLead').modal({keyboard:false,backdrop:'static'});
						$('#viewCourseWiseLead .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
		};
	})();
	//seo category pop end





//data-table-counsloor
var dataTableCunsloor= $('#data-table-counsloor').on('draw.dt',function(e,settings){
	$('#data-table-counsloor').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,	 
	"ajax":{
		url:"/lead/get-counsellor",
		data:function(d){
			d.page = (d.start/d.length)+1; 
			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();

//Upcoming Batches 
var dataTablecCunsloorView = $('#data-table-counsloor-view').on('draw.dt',function(e,settings){
	$('#data-table-counsloor-view').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,	 
	"ajax":{
		url:"/lead/get-counsellor-view",
		data:function(d){
			d.page = (d.start/d.length)+1; 
			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();

//Upcoming Batches 
var dataTableUpcomingBatches = $('#datatable-upcoming-batches').on('draw.dt',function(e,settings){
	$('#datatable-upcoming-batches').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,	 
	"ajax":{
		url:"/dashboard/counsellor/upcoming-batches/getbatches",
		data:function(d){
			d.page = (d.start/d.length)+1; 
			d.search['course']=$('*[name="search[course]"]').val();			  
			d.search['trainer']=$('*[name="search[trainer]"]').val(); 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();


var dataTablePendingLeadsDemos = $('#datatable-pending-leads-demos').on('draw.dt',function(e,settings){
	$('#datatable-pending-leads-demos').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	$('#datatable-pending-leads-demos').find('#check-all').on('change',function(){
		if(this.checked){
			$('.check-box').prop('checked',true);
		}else{
			$('.check-box').prop('checked',false);
		}
	});
})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,	 
	"ajax":{
		url:"/dashboard/counsellor/pending-leads-demos/getleadsdemos",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['course']=$('*[name="search[courses][]"]').val();			  
			d.search['status']=$('*[name="search[statuss][]"]').val();
			d.search['counsellor']=$('.counsellor-control').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
	
			return json.data;
		}
	}
}).api();

 

var dataTablePendingLeads = $('#datatable-pending-leads').on('draw.dt',function(e,settings){
	$('#datatable-pending-leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	$('#datatable-pending-leads').find('#check-all').on('change',function(){
		if(this.checked){
			$('.check-box').prop('checked',true);
		}else{
			$('.check-box').prop('checked',false);
		}
	});
})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"ajax":{
		url:"/dashboard/counsellor/pending-leads/getleads",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			var status = $('*[name="search[status][]"]').val();
			if(status!=null && status.length=='1' && status[0]==''){
				d.search['status']="";
			}else{
				d.search['status']=$('*[name="search[status][]"]').val();
			}
			//d.search['status']=$('*[name="search[status][]"]').val();
			d.search['counsellor']=$('.counsellor-control').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();
//

var dataTableCounsellors = $('#datatable-counsellors').on('draw.dt',function(e,settings){
	$('#datatable-counsellors').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	
})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,	
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],	
	"ajax":{
		url:"/dashboard/counsellorlist",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['name']=$('*[name="search[name][]"]').val();
			var status = $('*[name="search[status][]"]').val();
			if(status!=null && status.length=='1' && status[0]==''){
				d.search['status']="";
			}else{
				d.search['status']=$('*[name="search[status][]"]').val();
			}
			//d.search['status']=$('*[name="search[status][]"]').val();
			d.search['counsellor']=$('.counsellor-control').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();


 

var dataTablePendingDemos = $('#datatable-pending-demos').on('draw.dt',function(e,settings){
	$('#datatable-pending-demos').find('[data-toggle="popover"]').popover({html:true,container:'body'});
})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"ajax":{
		url:"/dashboard/counsellor/pending-demos/getdemos",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableLead = $('#datatable-lead').on('draw.dt',function(e,settings){
	$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-lead').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/getleads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['calldf']=$('*[name="search[calldf]"]').val();
			d.search['calldt']=$('*[name="search[calldt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['category']=$('*[name="search[category]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();



//for facbook start lead for listing
var dataTableFBLead = $('#datatable-fblead').on('draw.dt',function(e,settings){
	$('#datatable-fblead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-fblead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-fblead').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/getfbleads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['calldf']=$('*[name="search[calldf]"]').val();
			d.search['calldt']=$('*[name="search[calldt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['category']=$('*[name="search[category]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();
//facbook end




//facbook not interested start
var dataTableFBLead = $('#datatable-not_interestedfblead').on('draw.dt',function(e,settings){
	$('#datatable-not_interestedfblead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-not_interestedfblead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-not_interestedfblead').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/notIntergetfbleads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['calldf']=$('*[name="search[calldf]"]').val();
			d.search['calldt']=$('*[name="search[calldt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['category']=$('*[name="search[category]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();
//facbook not interested end






var dataTableShowSeoLead = $('#datatable-show-seo-lead').on('draw.dt',function(e,settings){
	$('#datatable-show-seo-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-show-seo-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-show-seo-lead').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-show-seo-leads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['calldf']=$('*[name="search[calldf]"]').val();
			d.search['calldt']=$('*[name="search[calldt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableLeadForward = $('#datatable-lead-forward').on('draw.dt',function(e,settings){
	$('#datatable-lead-forward').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-lead-forward').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-lead-forward').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-lead-forward",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();			 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();			 
			d.search['course']=$('*[name="search[course][]"]').val();
			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableAllFees = $('#datatable-all-fees').on('draw.dt',function(e,settings){
	$('#datatable-all-fees').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-all-fees').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-all-fees').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/fees/get-all-fees",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();				 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();


var dataTableAllReceivedLeads = $('#data-table-All-Received-Leads').on('draw.dt',function(e,settings){
	$('#data-table-All-Received-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#data-table-All-Received-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#data-table-All-Received-Leads').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-all-received-leads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();		 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();				 
			d.search['course']=$('*[name="search[course][]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();



var dataTableAllDual = $('#data-table-all-dual-Leads').on('draw.dt',function(e,settings){
	$('#data-table-all-dual-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#data-table-all-dual-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#data-table-all-dual-Leads').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-all-dual-leads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();		 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();				 
			d.search['course']=$('*[name="search[course][]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableAllDeleteLeads = $('#data-table-All-Delete-Leads').on('draw.dt',function(e,settings){
	$('#data-table-All-Delete-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#data-table-All-Delete-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#data-table-All-Delete-Leads').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-all-deleted-leads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();		 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();				 
			d.search['course']=$('*[name="search[course][]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();


var dataTableLeadAssignment= $('#data-table-lead-assignment').on('draw.dt',function(e,settings){
	$('#data-table-lead-assignment').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#data-table-lead-assignment').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#data-table-lead-assignment').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-all-lead-assignment",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();		 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();				 
			d.search['course']=$('*[name="search[course][]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableAllDuplicateLeads = $('#data-table-All-duplicate-Leads').on('draw.dt',function(e,settings){
	$('#data-table-All-duplicate-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#data-table-All-duplicate-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#data-table-All-duplicate-Leads').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-all-duplicate-leads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();		 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();				 
			d.search['course']=$('*[name="search[course][]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();



//all facebook duplicate start
var dataTableAllDuplicateLeads = $('#fbdata-table-All-duplicate-Leads').on('draw.dt',function(e,settings){
	$('#fbdata-table-All-duplicate-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#fbdata-table-All-duplicate-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#fbdata-table-All-duplicate-Leads').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/fblead/get-all-duplicate-leads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();		 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();				 
			d.search['course']=$('*[name="search[course][]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();
//all facebook duplicate end




var dataTableNotLeadAssignment= $('#data-table-not-lead-assignment').on('draw.dt',function(e,settings){
	$('#data-table-not-lead-assignment').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#data-table-not-lead-assignment').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#data-table-not-lead-assignment').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-all-not-lead-assignment",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();		 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();				 
			d.search['course']=$('*[name="search[course][]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableMailData = $('#datatable-mailer_data').on('draw.dt',function(e,settings){
	$('#datatable-mailer_data').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-mailer_data').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-mailer_data').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/mailer_data",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			 
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableFeedback = $('#datatable-feedback_data').on('draw.dt',function(e,settings){
	$('#datatable-feedback_data').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-feedback_data').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-feedback_data').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-website-feedback",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			 
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableCounsellorFeedback = $('#datatable-counsellor-feedback').on('draw.dt',function(e,settings){
	$('#datatable-counsellor-feedback').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-counsellor-feedback').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-counsellor-feedback').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/get-counsellor-feedback",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			 
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableTrainer = $('#datatable-trainer').on('draw.dt',function(e,settings){
	$('#datatable-trainer').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-trainer').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-trainer').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/trainer/gettrainer",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableTrainerRequired = $('#datatable-trainerrequired').on('draw.dt',function(e,settings){
	$('#datatable-trainerrequired').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-trainerrequired').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-trainerrequired').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/trainerrequired/gettrainer",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();


var dataTableHiring = $('#datatable-hiring').on('draw.dt',function(e,settings){
	$('#datatable-hiring').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-trainerrequired').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-trainerrequired').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/hiring/gethiring",		 
		data:function(d){
			$('body').scrollTop('0px');
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;			 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();


var dataTableCategory = $('#datatable-category').on('draw.dt',function(e,settings){
	$('#datatable-category').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
		"fnInitComplete":function(){
		$('#datatable-category').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		 
	},
	 
	"ajax":{
		url:"/category/get-category",		 
		data:function(d){			 
			d.page = (d.start/d.length)+1;		
		//	d.search['value']=$('*[name="search[value]"]').val();			
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();




var dataTableSubCategory = $('#datatable-sub-category').on('draw.dt',function(e,settings){
	$('#datatable-sub-category').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
		"fnInitComplete":function(){
		$('#datatable-sub-category').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		 
	},
	 
	"ajax":{
		url:"/subcategory/get-subcategory",		 
		data:function(d){			 
			d.page = (d.start/d.length)+1;		
			//d.search['value']=$('*[name="search[value]"]').val();			
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();



var dataTableCategoryType = $('#datatable-category_type').on('draw.dt',function(e,settings){
	$('#datatable-category_type').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
		"fnInitComplete":function(){
		$('#datatable-category_type').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		 
	},
	 
	"ajax":{
		url:"/categoryType/get-category-type",		 
		data:function(d){			 
			d.page = (d.start/d.length)+1;		
		//	d.search['value']=$('*[name="search[value]"]').val();			
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableallcoursecontentpdf = $('#datatable-allcoursecontentpdf').on('draw.dt',function(e,settings){
	$('#datatable-allcoursecontentpdf').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-content').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		 
	},
	"ajax":{
			url:"/coursepdf/get-course-pdf",		 
		data:function(d){
			 
			d.page = (d.start/d.length)+1;			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();



var dataTableallcoursecontentpdf = $('#datatable-coursepdfpending').on('draw.dt',function(e,settings){
	$('#datatable-coursepdfpending').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-content').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		 
	},
	"ajax":{
			url:"/coursepdf/get-coursepdfpending",		 
		data:function(d){
			 
			d.page = (d.start/d.length)+1;			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableContent = $('#datatable-content').on('draw.dt',function(e,settings){
	$('#datatable-content').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-content').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		 
	},
	"ajax":{
			url:"/content/getcontent",		 
		data:function(d){
			 
			d.page = (d.start/d.length)+1;			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();
var dataTableExpectedLead = $('#datatable-expected-lead').on('draw.dt',function(e,settings){
	$('#datatable-expected-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-expected-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-expected-lead').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/getexpectedleads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();


var dataTableExpectedLeadDemo = $('#datatable-expected-lead-demo').on('draw.dt',function(e,settings){
	$('#datatable-expected-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-expected-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-expected-lead').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/getexpectedleadsdemo",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['calldf']=$('*[name="search[calldf]"]').val();
			d.search['calldt']=$('*[name="search[calldt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableExpectedNewBatchLead = $('#datatable-expected-new-batch-lead').on('draw.dt',function(e,settings){
	$('#datatable-expected-new-batch-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-expected-new-batch-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-expected-new-batch-lead').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/getexpectednewbatchleads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableLeadNotInterested = $('#datatable-lead-not-interested').on('draw.dt',function(e,settings){
	$('#datatable-lead-not-interested').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500,1000],
            ['10','25','50','100','250','500','1000']
        ],
	"fnInitComplete":function(){
		$('#datatable-lead-not-interested').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-lead-not-interested').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/getleads",
	//	url:"/lead/getNotInterestedleads",	
		data:function(d){
		 
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['calldf']=$('*[name="search[calldf]"]').val();
			d.search['calldt']=$('*[name="search[calldt]"]').val();
			d.search['course']=$('*[name="search[course]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
		 
			d.search['not_interested']=new String("1").valueOf();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableDemo = $('#datatable-demo').on('draw.dt',function(e,settings){
	$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"fnInitComplete":function(){
		$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-demo').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
		url:"/demo/getleads",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['calldf']=$('*[name="search[calldf]"]').val();
			d.search['calldt']=$('*[name="search[calldt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();



var dataTableBatch = $('#datatable-batch').on('draw.dt',function(e,settings){
	$('#datatable-batch').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"fnInitComplete":function(){
		$('#datatable-batch').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-batch').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
		url:"/demo/batch/getbatch",
		data:function(d){
			d.page = (d.start/d.length)+1;			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			batchRecordCollection = json.batchRecordCollection;
			return json.data;
		}
	}
}).api();


var dataTableExpectedDemo = $('#datatable-expected-demo').on('draw.dt',function(e,settings){
	$('#datatable-expected-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"fnInitComplete":function(){
		$('#datatable-expected-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-expected-demo').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
		url:"/demo/getexpectedleads",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();
// expecte new batch demo
var dataTableExpectedNewBatchDemo = $('#datatable-expected-new-batch-demo').on('draw.dt',function(e,settings){
	$('#datatable-expected-new-batch-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"fnInitComplete":function(){
		$('#datatable-expected-new-batch-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-expected-new-batch-demo').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
		url:"/demo/getexpectednewbatchdemo",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();




// datatable-lead-batch
var dataTableExpectedNewBatchDemo = $('#datatable-lead-batch').on('draw.dt',function(e,settings){
	$('#datatable-lead-batch').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"fnInitComplete":function(){
		$('#datatable-lead-batch').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-lead-batch').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
		url:"/demo/batch/getleadbatch",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();

var dataTableDemoNotInterested = $('#datatable-demo-not-interested').on('draw.dt',function(e,settings){
	$('#datatable-demo-not-interested').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"fnInitComplete":function(){
		$('#datatable-demo-not-interested').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-demo-not-interested').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
		url:"/demo/getleads",
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['calldf']=$('*[name="search[calldf]"]').val();
			d.search['calldt']=$('*[name="search[calldt]"]').val();
			d.search['course']=$('*[name="search[course]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.search['not_interested']=new String("1").valueOf();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();

// demo/joined-demos

var dataTableDemoJoinedDemos = $('#datatable-demo-joined-demos').on('draw.dt',function(e,settings){
	$('#datatable-demo-joined-demos').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"fnInitComplete":function(){
		$('#datatable-demo-joined-demos').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-demo-joined-demos').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
		url:"/demo/get-joined-demos",
	//	url:"/demo/getleads",
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['joindf']=$('*[name="search[joindf]"]').val();
			d.search['joindt']=$('*[name="search[joindt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['calldf']=$('*[name="search[calldf]"]').val();
			d.search['calldt']=$('*[name="search[calldt]"]').val();
			d.search['course']=$('*[name="search[course]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.search['joined_demos']=new String("2").valueOf();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();


var dataTableTrainerStatus = $('#datatable-trainer_status').on('draw.dt',function(e,settings){
	$('#datatable-trainer_status').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"fnInitComplete":function(){
		$('#datatable-trainer_status').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-trainer_status').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
		url:"/demo/gettrainerstatus",
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['trainer']=$('*[name="search[trainer]"]').val();		 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();	
 		
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();
// end demo/joined-demos

var dataTableDeletedLead = $('#datatable-deleted-lead').on('draw.dt',function(e,settings){
	$('#datatable-deleted-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-deleted-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-deleted-lead').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/lead/getdeletedleads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();


var dataTableDeletedDemo = $('#datatable-deleted-demo').on('draw.dt',function(e,settings){
	$('#datatable-deleted-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#datatable-deleted-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#datatable-deleted-demo').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/demo/getdeletedleads",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();
			d.search['expdf']=$('*[name="search[expdf]"]').val();
			d.search['expdt']=$('*[name="search[expdt]"]').val();
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();
			d.search['course']=$('*[name="search[course][]"]').val();
			d.search['status']=$('*[name="search[status][]"]').val();
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();

var dataTableSource = $('#datatable-source').dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ajax":{
		url:"/source/getsources",
		data:function(d){
			d.page = (d.start/d.length)+1;
		}
	}
}).api();


 
 
             
			
			 
/* var dataTableCourse = $('#datatable-course').dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"responsive":true,	        	 
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"ajax":{
		url:"/course/getcourses",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.columns = null;
			d.order = null;
		}
	}
}).api(); */

var dataTableMessage = $('#datatable-message').dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"fnInitComplete":function(){$('#datatable-message').find('[data-toggle="popover"]').popover({html:true,container:'body'});},
	"ajax":{
		url:"/message",
		data:function(d){
			d.page = (d.start/d.length)+1;
			d.columns = null;
			d.order = null;
		}
	}
}).api();

var dataTableStatus = $('#datatable-status').dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ajax":{
		url:"/status/getstatus",
		data:function(d){
			d.page = (d.start/d.length)+1;
		}
	}
}).api();
// DATA TABLES
// ***********

// ****************
// CONFIRMATION BOX
	var confirmationBox = (function(){
		var $html = "\
			<div id=\"confirmModal\" class=\"modal fade\" role=\"dialog\">\
				<div class=\"modal-dialog\">\
					<div class=\"modal-content\">\
						<div class=\"modal-header\">\
							<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>\
							<h4 class=\"modal-title\"></h4>\
						</div>\
						<div class=\"modal-body\"></div>\
						<div class=\"modal-footer\"><a class=\"btn btn-primary btn-yes\" href=\"javascript:confirmationBox.the_action('yes')\" >Yes</a><a class=\"btn btn-primary btn-no\" href=\"javascript:confirmationBox.the_action('no')\" >No</a></div>\
					</div>\
				</div>\
			</div>\
		";
		$('body').append($($html));
		var opts = { status:0, target:null, callback:null, controller:null };
		return {
			modal_header:function(content){
				$("#confirmModal .modal-header").html(content);
			},
			modal_title:function(content){
				$("#confirmModal .modal-title").html(content);
			},
			modal_body:function(content){
				$("#confirmModal .modal-body").html(content);
			},
			modal_footer:function(content){
				$("#confirmModal .modal-footer").html(content);
			},
			setYLabel:function(label){
				$("#confirmModal .btn-yes").html(label);
			},
			setNLabel:function(label){
				$("#confirmModal .btn-no").html(label);
			},
			open:function(args){
				//alert('test');
				opts.status = 0;
				opts.target = args.target;
				opts.callback = args.callback;
				opts.controller = args.controller;
				this.modal_title(args.modal_title);
				this.modal_body(args.modal_body);
				this.setYLabel(args.yesLabel);
				this.setNLabel(args.noLabel);
				$("#confirmModal").modal({keyboard:false,backdrop:'static'});
			},
			close:function(){
				opts.status = 0;
				opts.target = null;
				opts.callback = null;
				$("#confirmModal").modal('hide');
			},
			the_action:function(status){
				//alert(status);
				opts.status = status;
				if(opts.status=="yes"){
					//courseController.deleteCourse(opts.target);
					window[opts.controller][opts.callback](opts.target);
					this.close();
				}
				if(opts.status=="no"){
					this.close();
				}
				return false;
			}
		}
	})();
// CONFIRMATION BOX
// ****************

// **************
// SPINNER OBJECT
	var mainSpinner = (function(){
		var opts = {
		lines: 13 // The number of lines to draw
		, length: 28 // The length of each line
		, width: 14 // The line thickness
		, radius: 42 // The radius of the inner circle
		, scale: 1 // Scales overall size of the spinner
		, corners: 1 // Corner roundness (0..1)
		, color: '#000' // #rgb or #rrggbb or array of colors
		, opacity: 0.25 // Opacity of the lines
		, rotate: 0 // The rotation offset
		, direction: 1 // 1: clockwise, -1: counterclockwise
		, speed: 1 // Rounds per second
		, trail: 60 // Afterglow percentage
		, fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
		, zIndex: 2e9 // The z-index (defaults to 2000000000)
		, className: 'spinner' // The CSS class to assign to the spinner
		, top: '50%' // Top position relative to parent
		, left: '50%' // Left position relative to parent
		, shadow: false // Whether to render a shadow
		, hwaccel: false // Whether to use hardware acceleration
		, position: 'absolute' // Element positioning
		};
		var brijeshBkgd = document.getElementById('brijeshBkgd');
		var target = document.getElementById('brijeshCntr');
		var spinner = new Spinner(opts);
		return {
			start:function(){
				spinner.spin(target);
				brijeshBkgd.style.display = 'block';
			},
			stop:function(){
				spinner.stop();
				brijeshBkgd.style.display = 'none';
			}
		}
	})();
// SPINNER OBJECT
// **************

jQuery(document).on('click', '.socialactive', function(event){ 		
		var THIS = jQuery(this);
		var source_id   = THIS.data('source_id');
		var val   = THIS.data('val');		 
		 if( confirm("Are You Sure Want to change ") ) {
		 	if(THIS.is(':checked')){			 
			var visib = 1;	
		THIS.prop('disabled', true);			
		}else{			 
			var visib = 0;
			THIS.prop('disabled', false);
		}
		
		jQuery.ajax({
			url:"/source/source-social",
			type: 'POST',
			data: {
				'action': 'updateSubContentstatus',
				'source_id': source_id,
				'val': val,				 
			},
			dataType: 'JSON',
			success: function(response){				
					if(response.status){					 		
						dataTableSource.ajax.reload(null,false);
					}
					else{
						THIS.prop('checked', false);						
						$('.errormessage').html("<div class='alert alert-danger'>"+response.msg+"</div>");	
					}			
				 
				THIS.prop('disabled', false);
			}
		});
		 }
			return false;
		 
	});


jQuery(document).on('click', '.dailystatusactive', function(event){ 		
		var THIS = jQuery(this);
		var source_id   = THIS.data('source_id');
		var val   = THIS.data('val');		 
		 if( confirm("Are You Sure Want to change ") ) {
		 	if(THIS.is(':checked')){			 
			var visib = 1;	
		THIS.prop('disabled', true);			
		}else{			 
			var visib = 0;
			THIS.prop('disabled', false);
		}
		
		jQuery.ajax({
			url:"/source/source-dailystatus",
			type: 'POST',
			data: {
				'action': 'updateSubContentstatus',
				'source_id': source_id,
				'val': val,				 
			},
			dataType: 'JSON',
			success: function(response){				
					if(response.status){					 		
						dataTableSource.ajax.reload(null,false);
					}
					else{
						THIS.prop('checked', false);						
						$('.errormessage').html("<div class='alert alert-danger'>"+response.msg+"</div>");	
					}			
				 
				THIS.prop('disabled', false);
			}
		});
		 }
			return false;
		 
	});



// **********************
// SHOW VALIDATION ERRORS
	function showValidationErrors($this,errors){
		$this.find('.form-group').removeClass('has-error');
		$this.find('.help-block').remove();
		for (var key in errors) {
			if(errors.hasOwnProperty(key)){
				var el = $this.find('*[name="'+key+'"]');
				$('<span class="help-block"><strong>'+errors[key][0]+'</strong></span>').insertAfter(el);
				el.closest('.form-group').addClass('has-error');
			}
		}
	}
// SHOW VALIDATION ERRORS
// **********************

// ******************
// getParameterByName
  function getParameterByName(name, url) {
		if (!url) url = window.location.href;
		name = name.replace(/[\[\]]/g, "\\$&");
		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);
		if (!results) return null;
		if (!results[2]) return '';
		return decodeURIComponent(results[2].replace(/\+/g, " "));
	}  
	
	 
// getParameterByName
// ******************
	 
// ********************
// EXPORT PENDING LEADS
function exportLeadsPending(){ 
	$('#export-leads-pending').find('input[name="search[expdf]"]').val($('*[name="search[expdf]"]').val());
	$('#export-leads-pending').find('input[name="search[expdt]"]').val($('*[name="search[expdt]"]').val());
	$('#export-leads-pending').find('input[name="search[course]"]').val($('*[name="search[course][]"]').val());
	$('#export-leads-pending').find('input[name="search[status]"]').val($('*[name="search[status][]"]').val());
	$('#export-leads-pending').find('input[name="search[value]"]').val($('input[type="search"]').val());
	$('#export-leads-pending').find('input[name="search[counsellor]"]').val($('.counsellor-control').val());	
	return true;
}
// EXPORT PENDING LEADS
// ********************

// ************
// EXPORT TRAiner
function exporttrainer(){
	$('#export-trainer').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));
	$('#export-trainer').find('input[name="search[expdf]"]').val(getParameterByName('search%5Bexpdf%5D'));
	$('#export-trainer').find('input[name="search[expdt]"]').val(getParameterByName('search%5Bexpdt%5D'));
	$('#export-trainer').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-trainer').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-trainer').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D%5B%5D'));
	$('#export-trainer').find('input[name="search[status]"]').val(getParameterByName('search%5Bstatus%5D%5B%5D'));	 
	$('#export-trainer').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}

// ************
// EXPORT trainerrequired
function exporttrainerrequired(){
	 
	$('#export-trainerrequired').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-trainerrequired').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));	 
	$('#export-trainerrequired').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}
// ******************************
// EXPORT PENDING LEADS AND DEMOS
function exportLeadsDemosPending(){
	$('#export-leads-demos-pending').find('input[name="search[expdf]"]').val($('*[name="search[expdf]"]').val());
	$('#export-leads-demos-pending').find('input[name="search[expdt]"]').val($('*[name="search[expdt]"]').val());
	$('#export-leads-demos-pending').find('input[name="search[courses]"]').val($('*[name="search[courses][]"]').val());
	$('#export-leads-demos-pending').find('input[name="search[statuss]"]').val($('*[name="search[statuss][]"]').val());
	$('#export-leads-demos-pending').find('input[name="search[value]"]').val($('input[type="search"]').val());
	$('#export-leads-demos-pending').find('input[name="search[counsellor]"]').val($('.counsellor-control').val());	
	return true;
}
// EXPORT PENDING LEADS AND DEMOS
// ******************************

// ********************
// EXPORT PENDING DEMOS
function exportDemosPending(){
	$('#export-demos-pending').find('input[name="search[expdf]"]').val(getParameterByName('search%5Bexpdf%5D'));
	$('#export-demos-pending').find('input[name="search[expdt]"]').val(getParameterByName('search%5Bexpdt%5D'));
	$('#export-demos-pending').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D'));
	$('#export-demos-pending').find('input[name="search[status]"]').val(getParameterByName('search%5Bstatus%5D'));
	$('#export-demos-pending').find('input[name="search[value]"]').val($('input[type="search"]').val());
	return true;
}
// EXPORT PENDING DEMOS
// ********************

// ************
// EXPORT LEADS
function exportLeads(){
	$('#export-leads').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));
	$('#export-leads').find('input[name="search[expdf]"]').val(getParameterByName('search%5Bexpdf%5D'));
	$('#export-leads').find('input[name="search[expdt]"]').val(getParameterByName('search%5Bexpdt%5D'));
	$('#export-leads').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-leads').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-leads').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D%5B%5D'));
	$('#export-leads').find('input[name="search[status]"]').val(getParameterByName('search%5Bstatus%5D%5B%5D'));
	$('#export-leads').find('input[name="search[user]"]').val(getParameterByName('search%5Buser%5D'));
	$('#export-leads').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}
// EXPORT LEADS
// ************

function exportReceivedLeads(){
	$('#export-received-leads').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));	 
	$('#export-received-leads').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-received-leads').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-received-leads').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D%5B%5D'));	 
	$('#export-received-leads').find('input[name="search[user]"]').val(getParameterByName('search%5Buser%5D'));
	$('#export-received-leads').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}

function exportReceivedUnassignLeads(){
	$('#export-unassign-leads').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));	 
	$('#export-unassign-leads').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-unassign-leads').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-unassign-leads').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D%5B%5D'));	
	$('#export-unassign-leads').find('input[name="search[user]"]').val(getParameterByName('search%5Buser%5D'));
	$('#export-unassign-leads').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}



// ************
// EXPORT LEADS
function exportSalesLeads(){
	$('#export-sales-leads').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));
	$('#export-sales-leads').find('input[name="search[expdf]"]').val(getParameterByName('search%5Bexpdf%5D'));
	$('#export-sales-leads').find('input[name="search[expdt]"]').val(getParameterByName('search%5Bexpdt%5D'));
	$('#export-sales-leads').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-sales-leads').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-sales-leads').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D%5B%5D'));
	$('#export-sales-leads').find('input[name="search[status]"]').val(getParameterByName('search%5Bstatus%5D%5B%5D'));
	$('#export-sales-leads').find('input[name="search[user]"]').val(getParameterByName('search%5Buser%5D'));
	$('#export-sales-leads').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}



// EXPORT LEADS
function exportSalesCounsellorLeads(THIS){
	$('#export-sales-CounsellorLeads-leads').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));
	$('#export-sales-CounsellorLeads-leads').find('input[name="search[expdf]"]').val(getParameterByName('search%5Bexpdf%5D'));
	$('#export-sales-CounsellorLeads-leads').find('input[name="search[expdt]"]').val(getParameterByName('search%5Bexpdt%5D'));
	$('#export-sales-CounsellorLeads-leads').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-sales-CounsellorLeads-leads').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-sales-CounsellorLeads-leads').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D%5B%5D'));
	$('#export-sales-CounsellorLeads-leads').find('input[name="search[status]"]').val(getParameterByName('search%5Bstatus%5D%5B%5D'));
	$('#export-sales-CounsellorLeads-leads').find('input[name="search[user]"]').val(getParameterByName('search%5Buser%5D'));
	$('#export-sales-CounsellorLeads-leads').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	 	//var THIS = $(this);
	 var $this = $(THIS),
					data  = $this.serialize();
		// alert(data);
			$.ajax({
			url:"/lead/getleadsSalesCounsellorTeamexcel",
			type:"POST",			
			data:data,
			 
			success: function(response) {        		
				//var printWindow = window.open('', '', 'width=700,height=500');
				//printWindow.document.write(response);
			//ssa=	window.open('data:application/vnd.ms-excel,' + encodeURIComponent(response));  
				// return (ssa);
				 
				 
				 var a = document.createElement('a');
				var data_type = 'data:application/vnd.ms-excel';
				a.href = data_type + ', ' + encodeURIComponent(response);				 
				a.download = 'Sales_Team_Monthly_Status_Report.xls';			 
				a.click();				 
				e.preventDefault();
				return (a);
				return false;
			}
			});	 
	 
	return true;
}
// ************
// EXPORT LEADS
function exportNotLeads(){
	$('#export-leads').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));
	$('#export-leads').find('input[name="search[expdf]"]').val(getParameterByName('search%5Bexpdf%5D'));
	$('#export-leads').find('input[name="search[expdt]"]').val(getParameterByName('search%5Bexpdt%5D'));
	$('#export-leads').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-leads').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-leads').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D'));
	$('#export-leads').find('input[name="search[status]"]').val(getParameterByName('search%5Bstatus%5D%5B%5D'));
	$('#export-leads').find('input[name="search[user]"]').val(getParameterByName('search%5Buser%5D'));
	$('#export-leads').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}
// EXPORT LEADS
// ************

// ************
// EXPORT DEMOS
function exportDemos(){
	$('#export-demos').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));
	$('#export-demos').find('input[name="search[expdf]"]').val(getParameterByName('search%5Bexpdf%5D'));
	$('#export-demos').find('input[name="search[expdt]"]').val(getParameterByName('search%5Bexpdt%5D'));
	$('#export-demos').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-demos').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-demos').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D%5B%5D'));
	$('#export-demos').find('input[name="search[status]"]').val(getParameterByName('search%5Bstatus%5D%5B%5D'));
	$('#export-demos').find('input[name="search[user]"]').val(getParameterByName('search%5Buser%5D'));
	$('#export-demos').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}
// EXPORT DEMOS
// ************


// ************
// EXPORT DEMOS
function exportNotDemos(){
	$('#export-demos').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));
	$('#export-demos').find('input[name="search[expdf]"]').val(getParameterByName('search%5Bexpdf%5D'));
	$('#export-demos').find('input[name="search[expdt]"]').val(getParameterByName('search%5Bexpdt%5D'));
	$('#export-demos').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-demos').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-demos').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D'));
	$('#export-demos').find('input[name="search[status]"]').val(getParameterByName('search%5Bstatus%5D%5B%5D'));
	$('#export-demos').find('input[name="search[user]"]').val(getParameterByName('search%5Buser%5D'));
	$('#export-demos').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}
// EXPORT DEMOS
// ************

// ************
// EXPORT JOIN DEMOS
function exportJoinDemos(){
	$('#export-demos').find('input[name="search[source]"]').val(getParameterByName('search%5Bsource%5D'));
	$('#export-demos').find('input[name="search[expdf]"]').val(getParameterByName('search%5Bexpdf%5D'));
	$('#export-demos').find('input[name="search[expdt]"]').val(getParameterByName('search%5Bexpdt%5D'));
	$('#export-demos').find('input[name="search[leaddf]"]').val(getParameterByName('search%5Bleaddf%5D'));
	$('#export-demos').find('input[name="search[leaddt]"]').val(getParameterByName('search%5Bleaddt%5D'));
	$('#export-demos').find('input[name="search[course]"]').val(getParameterByName('search%5Bcourse%5D'));
	$('#export-demos').find('input[name="search[status]"]').val(getParameterByName('search%5Bstatus%5D%5B%5D'));
	$('#export-demos').find('input[name="search[user]"]').val(getParameterByName('search%5Buser%5D'));
	$('#export-demos').find('input[name="search[value]"]').val($('input[type="search"]').val());
	 
	return true;
}
// EXPORT DEMOS
// ************

// ************************
// REMOVE VALIDATION ERRORS
	function removeValidationErrors($this){
		$this.find('.form-group').removeClass('has-error');
		$this.find('.help-block').remove();
	}
// REMOVE VALIDATION ERRORS
// **********************

// ***********
// VERIFY DEMO
	function verifyDemo(){
		var mobile = $('#add-demo-form').find('[name="mobile"]').val();
		if(null==mobile){
			return;
		}
		$.ajax({
			"url":"/demo/verify-demo/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
					if(payload.length>1){
						$('#leadsPayloadModal').modal();
						var $html = "";
						for(var i in payload){
							$html += "\
								<tr>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].mobile+"</td>\
									<td>"+payload[i].course_name+"</td>\
									<td>"+payload[i].owner_name+"</td>\
									<td class=\"text-center\"><label><input type=\"radio\" name=\"choke\" value=\""+payload[i].id+"\"></label></td>\
								</tr>";
						}
						$('#leadsPayloadModal tbody').html($html);
						$('#leadsPayloadModal .choke').off('click');
						$('#leadsPayloadModal .choke').on('click',function(){
							var target_id = $('#leadsPayloadModal input[name="choke"]').filter(':checked').val();
							for(var i in payload){
								if(payload[i].id == target_id){
									$('.alert-danger').html('').addClass('hide');
									$('#add-demo-form').find('[name="id"]').val(payload[i].id);
									$('#add-demo-form').find('[name="name"]').val(payload[i].name);
									$('#add-demo-form').find('[name="email"]').val(payload[i].email);
									$('#add-demo-form').find('[name="source"]').val(payload[i].source).change();
									$('#add-demo-form').find('[name="course"]').html('<option value="'+payload[i].course+'">'+payload[i].course_name+'</option>').val(payload[i].course).trigger('change');
									$('#add-demo-form').find('[name="sub-course"]').val(payload[i].sub_courses);
									$('#add-demo-form').find('[name="remark"]').val(payload[i].remarks);
									$('#add-demo-form').find('[name="exec_call"]').val('yes').trigger('change').prop('disabled',false);
									demo_owner = payload[i].created_by;									
								}							
							}
							$('#leadsPayloadModal').modal('hide');
						});
						return;
					}
					$('.alert-danger').html('').addClass('hide');
					$('#add-demo-form').find('[name="id"]').val(payload[0].id);
					$('#add-demo-form').find('[name="name"]').val(payload[0].name);
					$('#add-demo-form').find('[name="email"]').val(payload[0].email);
					$('#add-demo-form').find('[name="source"]').val(payload[0].source).change();
					$('#add-demo-form').find('[name="course"]').html('<option value="'+payload[0].course+'">'+payload[0].course_name+'</option>').val(payload[0].course).trigger('change');
					$('#add-demo-form').find('[name="sub-course"]').val(payload[0].sub_courses);
					$('#add-demo-form').find('[name="remark"]').val(payload[0].remarks);
					$('#add-demo-form').find('[name="exec_call"]').val('yes').trigger('change').prop('disabled',false);
					demo_owner = payload[0].created_by;					
				}else{
					$('.alert-danger').html(data.success.message).removeClass('hide');
					$('#add-demo-form').find('[name="id"]').val('');
					$('#add-demo-form').find('[name="name"]').val('');
					$('#add-demo-form').find('[name="email"]').val('');
					$('#add-demo-form').find('[name="source"]').val('').change();
					$('#add-demo-form').find('[name="course"]').html('').val('').trigger('change');
					$('#add-demo-form').find('[name="owner"]').html('<option value="">-- SELECT OWNER --</option>');
					$('#add-demo-form').find('[name="sub-course"]').val('');
					$('#add-demo-form').find('[name="remark"]').val('');
					$('#add-demo-form').find('[name="exec_call"]').val('no').trigger('change').prop('disabled','disabled');					
				}
				/* if(data!=''){
					$('.alert-danger').html('').addClass('hide');
					$('#add-demo-form').find('[name="id"]').val(data.id);
					$('#add-demo-form').find('[name="name"]').val(data.name);
					$('#add-demo-form').find('[name="email"]').val(data.email);
					$('#add-demo-form').find('[name="source"]').val(data.source).change();
					$('#add-demo-form').find('[name="course"]').html('<option value="'+data.course+'">'+data.course_name+'</option>').val(data.course).trigger('change');
					$('#add-demo-form').find('[name="sub-course"]').val(data.sub_courses);
					$('#add-demo-form').find('[name="remark"]').val(data.remarks);
					$('#add-demo-form').find('[name="exec_call"]').val('yes').trigger('change').prop('disabled',false);
					demo_owner = data.created_by;
					//$('.demo_owner option[value='+data.created_by+']').prop('selected', true);
					//$('.demo_owner').val(data.created_by);
					// if(data.status!=0){
						// $('#add-demo-form').find('[name="status"]').find('option').filter(function(i,e){
							// return $(e).text()=='attended demo'
						// });
					// }						
				}else{
					$('.alert-danger').html('Lead not found').removeClass('hide');
					$('#add-demo-form').find('[name="id"]').val('');
					$('#add-demo-form').find('[name="name"]').val('');
					$('#add-demo-form').find('[name="email"]').val('');
					$('#add-demo-form').find('[name="source"]').val('').change();
					$('#add-demo-form').find('[name="course"]').html('').val('').trigger('change');
					$('#add-demo-form').find('[name="owner"]').html('<option value="">-- SELECT OWNER --</option>');
					$('#add-demo-form').find('[name="sub-course"]').val('');
					$('#add-demo-form').find('[name="remark"]').val('');
					$('#add-demo-form').find('[name="exec_call"]').val('no').trigger('change').prop('disabled','disabled');
					// if(data.status!=0){
						// $('#add-demo-form').find('[name="status"]').find('option').filter(function(i,e){
							// return $(e).text()=='attended demo'
						// });
					// }
				} */
			}
		});
	}
// VERIFY DEMO
// ***********


function leadjoinded(){
		var mobile = $('#leadDemoJoined-form').find('[name="mobile"]').val();
		if(null==mobile){
			return;
		}
		$.ajax({
			"url":"/demo/leadDemoJoined-verify-lead/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
					 
					if(payload.length>0){
						//$('#leadsPayloadModal').modal();
						var $html = "";
						var $lead = "leads";
						for(var i in payload){
							$html += "\
								<tr>\
								<td class=\"text-center\"><label><input type=\"hidden\" class=\"check-lead-type\" name=\"check-type\" value=\"leads\" ><input type=\"radio\" class=\"check-lead-box\" name=\"choke\" value=\""+payload[i].id+"\"></label></td>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].mobile+"</td>\
									<td>"+payload[i].course_name+"</td>\
									<td>"+payload[i].trainer+"</td>\
									<td>"+payload[i].owner_name+"</td>\
									<td><a href=\"javascript:leadController.leadjoindededit("+payload[i].id+")\" title=\"followUp\"><i class=\"fa fa-edit\" aria-hidden=\"true\"></i></a></td>\
								</tr>";
						}
						 
						$('#leadsPayloadModal tbody').html($html);
						  
						return;
					}
					 				
				}else{
					 	$('#leadsPayloadModal tbody').html("<tr><td colspan='7'>No Records Founds</td></tr>");		
				}
				 
			}
		});
		$.ajax({
			"url":"/demo/leadDemoJoined-verify-demo/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
					 
					if(payload.length>0){
						//$('#leadsPayloadModal').modal();
						var $html = "";
						for(var i in payload){
							$html += "\
								<tr>\
								<td class=\"text-center\"><label><input type=\"hidden\" class=\"check-demo-type\" name=\"check-type\" value=\"demos\"><input type=\"radio\" class=\"check-box\" name=\"choke\" value=\""+payload[i].id+"\"></label></td>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].mobile+"</td>\
									<td>"+payload[i].course_name+"</td>\
									<td>"+payload[i].trainer+"</td>\
									<td>"+payload[i].owner_name+"</td>\
									<td><a href=\"javascript:demoController.demojoindededit("+payload[i].id+")\" title=\"followUp\"><i class=\"fa fa-edit\" aria-hidden=\"true\"></i></a></td>\
								</tr>";
						}
						 
						$('#demosPayloadModal tbody').html($html);
						  
						return;
					}
					 				
				}else{
					 	$('#demosPayloadModal tbody').html("<tr><td colspan='7'>No Records Founds</td></tr>");		
				}
				 
			}
		});
	}
	
	
	function searchLeadAndDemo(){
		var mobile = $('#searchLeadAndDemo-form').find('[name="mobile"]').val();
		if(null==mobile){
			return;
		}
		 
		$.ajax({
			"url":"/demo/searchLead/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
					 
					if(payload.length>0){
						//$('#leadsPayloadModal').modal();
						var $html = "";
						var $lead = "leads";
						for(var i in payload){
							if(payload[i].move_not_interested==1){
								var newstatus = "Not interested Tab";
							}else if(payload[i].move_not_interested==2){ 
							var newstatus = "Expect Lead";
							}else if(payload[i].move_not_interested==3){ 
							var newstatus = "Expect Demo";
							}else{
								var newstatus = "All Leads ";
							}
							
							if(payload[i].demo_attended=='1'){
								var newtype = "( Move to Demo Lead )";
							}else{
							    var newtype = "";
							} 
							
							if(payload[i].deleted_at==null){
								var newdelete = "";
							}else{
								var newdelete = "Delete Tab ";
							}
							$html += "\
								<tr>\<td>"+payload[i].leadcreated_at+"</td>\
								    \<td>"+payload[i].name+"</td>\
									<td>"+payload[i].mobile+"</td>\
									<td>"+payload[i].course_name+"</td>\
									<td>"+payload[i].trainer+"</td>\
									<td>"+payload[i].owner_name+"</td>\
									<td>"+newstatus+" "+newtype+"</td>\
									<td>"+newdelete+"</td>\
							</tr>";
						}
						 
						$('#leadsPayloadModal tbody').html($html);
						  
						return;
					}
					 				
				}else{
					 	$('#leadsPayloadModal tbody').html("<tr><td colspan='7'>No Records Founds</td></tr>");		
				}
				 
			}
		});
		$.ajax({
			"url":"/demo/searchDemo/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
					 
					if(payload.length>0){
						//$('#leadsPayloadModal').modal();
						var $html = "";
						for(var i in payload){
							if(payload[i].move_not_interested==1){
								var newstatus = "Not interested Tab";
							}else if(payload[i].move_not_interested==2){ 
							var newstatus = "Joined Demos";
							}else if(payload[i].move_not_interested==3){ 
							var newstatus = "Expect Demo";
							}else if(payload[i].move_not_interested==4){ 
							var newstatus = "new batch demo";
							}else{
								var newstatus = "All Demo";
							}
							if(payload[i].deleted_at==null){
								var newdelete = "";
							}else{
								var newdelete = "Delete Tab";
							}
							$html += "\
								<tr>\
									<td>"+payload[i].leadcreated_at+"</td>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].mobile+"</td>\
									<td>"+payload[i].course_name+"</td>\
									<td>"+payload[i].trainer+"</td>\
									<td>"+payload[i].owner_name+"</td>\
									<td>"+newstatus+"</td>\
									<td>"+newdelete+"</td>\
								</tr>";
						}
						 
						$('#demosPayloadModal tbody').html($html);
						  
						return;
					}
					 				
				}else{
					 	$('#demosPayloadModal tbody').html("<tr><td colspan='7'>No Records Founds</td></tr>");		
				}
				 
			}
		});
	}
	
	
		function searchPaidfees(){
		var mobile = $('#paidfees-form').find('[name="mobile"]').val();
		if(null==mobile){
			return;
		}
		  var fees_type = jQuery('#fees_type option:selected').val();		 
 
		if(fees_type=='newfees'){
	
	 
		$.ajax({
			"url":"/fees/searchfeesLeaddata/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
		 
				if(data.statusCode){
					var payload = data.success.payload;
				 
					if(payload.length>1){
						$('#leadsPayloadModal').modal();
						var $html = "";
						var $lead = "leads";
						for(var i in payload){
							 $html += "\
								<tr>\
								<td class=\"text-center\"><label><input type=\"hidden\" class=\"check-demo-type\" name=\"check-type\" value=\"newfees\"><input type=\"radio\" class=\"check-box\" name=\"choke\" value=\""+payload[i].id+"\"></label></td>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].mobile+"</td>\
									<td>"+payload[i].course_name+"</td>\
									<td>"+payload[i].owner_name+"</td>\
								</tr>";							 
						}
						 
						 
						
						$('#leadsPayloadModal tbody').html($html);
						$('#leadsPayloadModal .choke').off('click');
						$('#leadsPayloadModal .choke').on('click',function(){
							var target_id = $('#leadsPayloadModal input[name="choke"]').filter(':checked').val();
							for(var i in payload){
								if(payload[i].id == target_id){
									$('.alert-danger').html('').addClass('hide');
									$('#paidfees-form').find('[name="id"]').val(payload[i].id);
									$('#paidfees-form').find('[name="name"]').val(payload[i].name);
									$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
									$('#paidfees-form').find('[name="course"]').html('').val('').trigger('change');
																	
								}							
							}
							$('#leadsPayloadModal').modal('hide');
						});
						
						
						  
						return;
					}else{
						
						for(var i in payload){
							

							$('#paidfees-form').find('[name="id"]').val(payload[i].id);
							$('#paidfees-form').find('[name="name"]').val(payload[i].name);
							$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
							$('#paidfees-form').find('[name="course"]').html('<option value="'+payload[i].course_id+'">'+payload[i].course_name+'</option>').val(payload[i].course_id).trigger('change');
								
													
							}	
						
					}
					 				
				}else{
					 
					$('#paidfees-form').find('[name="id"]').val('');
					$('#paidfees-form').find('[name="name"]').val('');
					$('#paidfees-form').find('[name="email"]').val('');
					 
					$('#paidfees-form').find('[name="course"]').html('').val('').trigger('change');
					 
				}
				 
			}
		});
		
		}else if(fees_type=='newfeesExperience'){
			
		
		$.ajax({
			"url":"/fees/searchfeesExperiensedata/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
				 
					if(payload.length>1){
						$('#leadsPayloadModal').modal();
						var $html = "";
						for(var i in payload){							 
							$html += "\
								<tr>\
								<td class=\"text-center\"><label><input type=\"hidden\" class=\"check-demo-type\" name=\"check-type\" value=\"newfees\"><input type=\"radio\" class=\"check-box\" name=\"choke\" value=\""+payload[i].id+"\"></label></td>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].phone+"</td>\
									<td>"+payload[i].technology+"</td>\
									<td>"+payload[i].owner_name+"</td>\
								</tr>";
						}
						  
						$('#leadsPayloadModal tbody').html($html);
						$('#leadsPayloadModal .choke').off('click');
						$('#leadsPayloadModal .choke').on('click',function(){
						var target_id = $('#leadsPayloadModal input[name="choke"]').filter(':checked').val();
							for(var i in payload){
								if(payload[i].id == target_id){
								 
									$('#paidfees-form').find('[name="id"]').val(payload[i].id);
									$('#paidfees-form').find('[name="name"]').val(payload[i].name);
									$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
									$('#paidfees-form').find('[name="course"]').html('<option value="'+payload[i].technology+'">'+payload[i].technology+'</option>').val(payload[i].technology).trigger('change');
																	
								}							
							}
							$('#leadsPayloadModal').modal('hide');
						});
						
					}else{
						 
							for(var i in payload){
							

							$('#paidfees-form').find('[name="id"]').val(payload[i].id);
							$('#paidfees-form').find('[name="name"]').val(payload[i].name);
							$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
							$('#paidfees-form').find('[name="course"]').html('<option value="'+payload[i].technology+'">'+payload[i].technology+'</option>').val(payload[i].technology).trigger('change');
								
													
							}

					}						
				}else{
					$('#paidfees-form').find('[name="id"]').val('');
					$('#paidfees-form').find('[name="name"]').val('');
					$('#paidfees-form').find('[name="email"]').val('');					 
					$('#paidfees-form').find('[name="course"]').html('').val('').trigger('change');	
				}
				 
			}
		});
		
		}else if(fees_type=='newfeesCertificate'){
			
		$.ajax({
			"url":"/fees/searchFeesCertificateData/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
				 
					if(payload.length>1){
						$('#leadsPayloadModal').modal();
						var $html = "";
						for(var i in payload){							 
							$html += "\
								<tr>\
								<td class=\"text-center\"><label><input type=\"hidden\" class=\"check-demo-type\" name=\"check-type\" value=\"newfees\"><input type=\"radio\" class=\"check-box\" name=\"choke\" value=\""+payload[i].id+"\"></label></td>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].phone+"</td>\
									<td>"+payload[i].course+"</td>\
									<td>"+payload[i].owner_name+"</td>\
								</tr>";
						}
						  
						$('#leadsPayloadModal tbody').html($html);
						$('#leadsPayloadModal .choke').off('click');
						$('#leadsPayloadModal .choke').on('click',function(){
						var target_id = $('#leadsPayloadModal input[name="choke"]').filter(':checked').val();
							for(var i in payload){
								if(payload[i].id == target_id){
								 
									$('#paidfees-form').find('[name="id"]').val(payload[i].id);
									$('#paidfees-form').find('[name="name"]').val(payload[i].name);
									$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
									$('#paidfees-form').find('[name="course"]').html('<option value="'+payload[i].course+'">'+payload[i].course+'</option>').val(payload[i].course).trigger('change');
																	
								}							
							}
							$('#leadsPayloadModal').modal('hide');
						});
						
					}else{
						 
							for(var i in payload){
							

							$('#paidfees-form').find('[name="id"]').val(payload[i].id);
							$('#paidfees-form').find('[name="name"]').val(payload[i].name);
							$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
							$('#paidfees-form').find('[name="course"]').html('<option value="'+payload[i].course+'">'+payload[i].course+'</option>').val(payload[i].course).trigger('change');
								
													
							}

					}						
				}else{
					$('#paidfees-form').find('[name="id"]').val('');
					$('#paidfees-form').find('[name="name"]').val('');
					$('#paidfees-form').find('[name="email"]').val('');					 
					$('#paidfees-form').find('[name="course"]').html('').val('').trigger('change');	
				}
				 
			}
		});
			
		}else if(fees_type=='pendingfeesExperience'){
			
		
		$.ajax({
			"url":"/fees/searchPendingFeesExperienceData/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
				 
					if(payload.length>1){
						$('#leadsPayloadModal').modal();
						var $html = "";
						for(var i in payload){							 
							$html += "\
								<tr>\
								<td class=\"text-center\"><label><input type=\"hidden\" class=\"check-demo-type\" name=\"check-type\" value=\"newfees\"><input type=\"radio\" class=\"check-box\" name=\"choke\" value=\""+payload[i].id+"\"></label></td>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].mobile+"</td>\
									<td>"+payload[i].technology+"</td>\
									<td>"+payload[i].owner_name+"</td>\
								</tr>";
						}
						  
						$('#leadsPayloadModal tbody').html($html);
						$('#leadsPayloadModal .choke').off('click');
						$('#leadsPayloadModal .choke').on('click',function(){
						var target_id = $('#leadsPayloadModal input[name="choke"]').filter(':checked').val();
							for(var i in payload){
								if(payload[i].id == target_id){
								 
									$('#paidfees-form').find('[name="id"]').val(payload[i].id);
									$('#paidfees-form').find('[name="name"]').val(payload[i].name);
									$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
									$('#paidfees-form').find('[name="course"]').html('<option value="'+payload[i].technology+'">'+payload[i].technology+'</option>').val(payload[i].technology).trigger('change');
																	
								}							
							}
							$('#leadsPayloadModal').modal('hide');
						});
						
					}else{
					 
							for(var i in payload){
							

							$('#paidfees-form').find('[name="id"]').val(payload[i].id);
							$('#paidfees-form').find('[name="name"]').val(payload[i].name);
							$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
							 
								
													
							}

					}						
				}else{
					$('#paidfees-form').find('[name="id"]').val('');
					$('#paidfees-form').find('[name="name"]').val('');
					$('#paidfees-form').find('[name="email"]').val('');					 
					$('#paidfees-form').find('[name="course"]').html('').val('').trigger('change');	
				}
				 
			}
		});
		}else if(fees_type=='pendingfeesCertificate'){
			
		$.ajax({
			"url":"/fees/searchFeesPendingCertificateData/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
				 
					if(payload.length>1){
						$('#leadsPayloadModal').modal();
						var $html = "";
						for(var i in payload){							 
							$html += "\
								<tr>\
								<td class=\"text-center\"><label><input type=\"hidden\" class=\"check-demo-type\" name=\"check-type\" value=\"newfees\"><input type=\"radio\" class=\"check-box\" name=\"choke\" value=\""+payload[i].id+"\"></label></td>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].phone+"</td>\
									<td>"+payload[i].course+"</td>\
									<td>"+payload[i].owner_name+"</td>\
								</tr>";
						}
						  
						$('#leadsPayloadModal tbody').html($html);
						$('#leadsPayloadModal .choke').off('click');
						$('#leadsPayloadModal .choke').on('click',function(){
						var target_id = $('#leadsPayloadModal input[name="choke"]').filter(':checked').val();
							for(var i in payload){
								if(payload[i].id == target_id){
								 
									$('#paidfees-form').find('[name="id"]').val(payload[i].id);
									$('#paidfees-form').find('[name="name"]').val(payload[i].name);
									$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
									$('#paidfees-form').find('[name="course"]').html('<option value="'+payload[i].course+'">'+payload[i].course+'</option>').val(payload[i].course).trigger('change');
																	
								}							
							}
							$('#leadsPayloadModal').modal('hide');
						});
						
					}else{
						 
							for(var i in payload){						

							$('#paidfees-form').find('[name="id"]').val(payload[i].id);
							$('#paidfees-form').find('[name="name"]').val(payload[i].name);
							$('#paidfees-form').find('[name="email"]').val(payload[i].email); 
							}

					}						
				}else{
					$('#paidfees-form').find('[name="id"]').val('');
					$('#paidfees-form').find('[name="name"]').val('');
					$('#paidfees-form').find('[name="email"]').val('');					 
					$('#paidfees-form').find('[name="course"]').html('').val('').trigger('change');	
				}
				 
			}
		});
			
		}else if(fees_type=='pendingfees'){
			
		$.ajax({
			"url":"/fees/searchfeesPendingdata/"+mobile,
			"type":"GET",
			"success":function(data,textStatus,jqXHR){
				if(data.statusCode){
					var payload = data.success.payload;
				 
					if(payload.length>1){
						$('#leadsPayloadModal').modal();
						var $html = "";
						for(var i in payload){							 
							$html += "\
								<tr>\
								<td class=\"text-center\"><label><input type=\"hidden\" class=\"check-demo-type\" name=\"check-type\" value=\"newfees\"><input type=\"radio\" class=\"check-box\" name=\"choke\" value=\""+payload[i].id+"\"></label></td>\
									<td>"+payload[i].name+"</td>\
									<td>"+payload[i].phone+"</td>\
									<td>"+payload[i].course_name+"</td>\
									<td>"+payload[i].owner_name+"</td>\
								</tr>";
						}
						  
						$('#leadsPayloadModal tbody').html($html);
						$('#leadsPayloadModal .choke').off('click');
						$('#leadsPayloadModal .choke').on('click',function(){
						var target_id = $('#leadsPayloadModal input[name="choke"]').filter(':checked').val();
							for(var i in payload){
								if(payload[i].id == target_id){
								 
									$('#paidfees-form').find('[name="id"]').val(payload[i].id);
									$('#paidfees-form').find('[name="name"]').val(payload[i].name);
									$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
									$('#paidfees-form').find('[name="course"]').html('<option value="'+payload[i].course_id+'">'+payload[i].course_name+'</option>').val(payload[i].course_id).trigger('change');
																	
								}							
							}
							$('#leadsPayloadModal').modal('hide');
						});
						
					}else{
						 
							for(var i in payload){
							

							$('#paidfees-form').find('[name="id"]').val(payload[i].id);
							$('#paidfees-form').find('[name="name"]').val(payload[i].name);
							$('#paidfees-form').find('[name="email"]').val(payload[i].email);								
							$('#paidfees-form').find('[name="course"]').html('<option value="'+payload[i].course_id+'">'+payload[i].course_name+'</option>').val(payload[i].course_id).trigger('change');
								
													
							}

					}						
				}else{
					$('#paidfees-form').find('[name="id"]').val('');
					$('#paidfees-form').find('[name="name"]').val('');
					$('#paidfees-form').find('[name="email"]').val('');					 
					$('#paidfees-form').find('[name="course"]').html('').val('').trigger('change');	
				}
				 
			}
		});
			
			
			
		}
		
	 
	}
	
// **************************
// ROLE PERMISSION CONTROLLER
	var rolePermissionController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					
				$.ajax({
					url:"/role-permission",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Permission added successfully');
							$this.find('button[type="reset"]').click();
							dataTableRolePermission.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Permission not added');
					}
				});
				return false;
			},
			delete:function(id){
				if(confirm("Are you sure ??")){
					mainSpinner.start();
					$.ajax({
						url:"/role-permission/delete/"+id,
						type:"GET",
						success:function(response){
							if(response.status){
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-success').removeClass('hide').html('Permission deleted successfully');
								dataTableRolePermission.ajax.reload(null,false);
							}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html(response.errors);							
								dataTableRolePermission.ajax.reload(null,false);
							}
						},
						error:function(response){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Permission not deleted');
						}
					});
				}
				return false;
			}
		};
	})();
// ROLE PERMISSION CONTROLLER
// **************************

// *********************
// PERMISSION CONTROLLER
	var permissionController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/permission",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Permission added successfully');
							$this.find('button[type="reset"]').click();
							dataTablePermission.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Permission not added');
					}
				});
				return false;
			},
			delete:function(id){
				if(confirm("Are you sure ??")){
					mainSpinner.start();
					$.ajax({
						url:"/permission/delete/"+id,
						type:"GET",
						success:function(response){
							if(response.status){
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-success').removeClass('hide').html('Permission deleted successfully');
								dataTablePermission.ajax.reload(null,false);
							}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html(response.errors);							
								dataTablePermission.ajax.reload(null,false);
							}
						},
						error:function(response){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Permission not deleted');
						}
					});
				}
				return false;
			}
		};
	})();
// PERMISSION CONTROLLER
// *********************

	
	var leadAssignCromaController = (function(){
		return {
			 checked_Ids:[],
			selectLeads:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box-lead:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to delete lead!');
					return false;
				}
					 
					mainSpinner.start();
					
					$.ajax({
					url:"/genie/lead/selectTodeleteLeads",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
							mainSpinner.stop();
							var response = JSON.parse(jqXHR.responseText);
						//	alert(response.statusCode);
				    	if(response.statusCode){
							$('#messagemodel').modal();
								$('#messagemodel .modal-title').text("Lead Delete");	
								$('#messagemodel .modal-body').html("<div class='alert alert-success'>"+response.data.message+"</div>");			
								$('#messagemodel').modal({keyboard:false,backdrop:'static'});
								$('#messagemodel').css({'width':'100%'});
							dataTableleadassign.ajax.reload(null,false);						 
							 						 
						 
						 
							 
						}else{
							$('#messagemodel').modal();
							$('#messagemodel .modal-title').text("Permission Delete");	
							$('#messagemodel .modal-body').html("<div class='alert alert-danger'>"+response.data.message+"</div>");	
							$('#messagemodel').modal({keyboard:false,backdrop:'static'});
							$('#messagemodel').css({'width':'100%'});
							}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
					 
				return false;
			}
		};
	})();
  

// ***************
// LEAD CONTROLLER
	var leadController = (function(){
		return {
			checked_Ids:[],
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					//alert(data);
				$.ajax({
					url:"/lead/add-lead",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Lead added successfully');
							$this.find('button[type="reset"]').click();
							$this.find('[name="course"]').html('').val('').trigger('change');
						}else{
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Lead not added');
					}
				});
				return false;
			},
			delete:function(id){
				if(confirm("Are you sure ??")){
					mainSpinner.start();
					$.ajax({
						url:"/lead/delete/"+id,
						type:"GET",
						success:function(response){
							if(response.status){
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-success').removeClass('hide').html('Lead deleted softly and successfully');
								dataTableLeadNotInterested.ajax.reload(null,false);
								dataTableLead.ajax.reload(function(){
									$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
								},false);
							}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html(response.errors);							
								dataTableLead.ajax.reload(function(){
									$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
								},false);
							}
						},
						error:function(response){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Lead not deleted');
						}
					});
				}
				return false;
			},
			getfollowUps:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/follow-up/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						$('#expected_date_time').daterangepicker({
							singleDatePicker: true,
							autoUpdateInput: false,
							timePicker: true,
							minDate: new Date(),
							//autoUpdateInput: false,
							locale: {
								format: 'DD-MMMM-YYYY h:mm A'
							},
							singleClasses: "picker_2"
						}/* , function(start, end, label) {
							alert(JSON.stringify(start));
							$('#expected_date_time').val(start.format('DD-MMMM-YYYY h:mm A'));
						} */);
						$('#expected_date_time').on('apply.daterangepicker', function(ev, picker) {
							$('#expected_date_time').val(picker.startDate.format('DD-MMMM-YYYY h:mm A'));
						});
						select2_course();
						dataTableFollowUps = $('#datatable-followups').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":false,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/lead/getfollowups/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									d.count = $(".follow-up-count").val();
								}
							}
						}).api();
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadController.getfollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadController.getfollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadController.getfollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadController.getfollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				/* var dataTableFollowUps = $('#datatable-followups').dataTable({
					"fixedHeader": true,
					"processing":true,
					"serverSide":true,
					"paging":true,
					"ajax":{
						url:"/lead/getfollowups/",
						data:function(d){
							d.page = (d.start/d.length)+1;
						}
					}
				}).api(); */
			},
			storeFollowUp:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/lead/store-follow-up/'+id,
					type:"post",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							//$this.find('*[name="status"]').val('');
							//$this.find('*[name="expected_date_time"]').val('');
							$this.find('*[name="remark"]').val('');
							alert('Follow Up created successfully');
							dataTableFollowUps.ajax.reload( null, false );
							dataTableExpectedLead.ajax.reload( null, false );
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							dataTablePendingLeads.ajax.reload(function(){
								$('#datatable-pending-leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							removeValidationErrors($this);
						}
						if(response.demo_created){
							$this.closest('.x_content').html("<p style='font-size: 24px;font-weight:700;padding-top:20px;text-align:center;'>Lead successfully moved to Demo...</p>");
						}
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			sendMail:function(id){
				mainSpinner.start();
				$.ajax({
					url:'/lead/sendmail/'+id,
					type:"GET",
					dataType:'json',
					success:function(response){
						//console.log(response);
						if(response.status){
							alert(response.success.message);
							mainSpinner.stop();
						}
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						alert('Something went wrong');
						mainSpinner.stop();
					}
				});
				return false;
			},
			getAllFollowUps:function(){
				//mainSpinner.start();
				dataTableFollowUps.ajax.reload( null, false );
				return false;
			},
			bulkSms:function(){
				//var checked_Ids = [];
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					//alert($(this).val());
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    alert('Please select data for Bulk SMS!');
					return false;
				}
				$('#bulkSmsModal .alert').remove();
				$('#bulkSmsModal').modal({backdrop:"static",keyboard:false});
				return false;
			},
			sendBulkSms:function(){
				if($('#bulkSmsControl').val() == '')
					return false;
				var $this = this;
				$.ajax({
					url:"/lead/send-bulk-sms",
					type:"POST",
					dataType:"json",
					data:{
						message:$('#bulkSmsControl').val(),
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.status){
							alert(data.success.message);
							$('#bulkSmsModal .alert').remove();
							$('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							$('#bulkSmsControl').val('');
							setTimeout(function(){
								$('#bulkSmsModal').modal('hide');
							},2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;
			},
			moveNotInterested:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    	alert('Please select data to move Not Intereseted and Location Issue!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/move-not-interested",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							// $('#bulkSmsModal .alert').remove();
							// $('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							// $('#bulkSmsControl').val('');
							// setTimeout(function(){
								// $('#bulkSmsModal').modal('hide');
							// },2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			moveToExptLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    	alert('Please select data to move To Expected lead!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/move-to-expected-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLead.ajax.reload( null, false );							
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
			moveToNewbatchLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    	alert('Please select data To Move To New Batch lead!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/move-to-expected-new-batch-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedLead.ajax.reload(null,false);
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			moveToLeads:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select date to move to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLeadNotInterested.ajax.reload(null,false);
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			expectedMoveToLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to Expected move to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/expected-move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedLead.ajax.reload(null,false);
							dataTableExpectedLead.ajax.reload(function(){
								$('#datatable-expected-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},			
			expectedNewBatchMoveToLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to New batch Move to Lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/expected-new-batch-move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedNewBatchLead.ajax.reload(null,false);
							dataTableExpectedNewBatchLead.ajax.reload(function(){
								$('#datatable-expected-new-batch-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
			moveToExptLeadDemo:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    	alert('Please select data to move To Expected Demo!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/move-to-expected-lead-demo",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLead.ajax.reload( null, false );							
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},		
			 
			expectedDemoMoveToLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to Expected Demo move to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/expected-demo-move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedLeadDemo.ajax.reload(null,false);
							dataTableExpectedLeadDemo.ajax.reload(function(){
								$('#datatable-expected-lead-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},	
			deleteMoveToLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/delete-move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableDeletedLead.ajax.reload(null,false);
							dataTableDeletedLead.ajax.reload(function(){
								$('#datatable-deleted-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},	
			
			expectedDemoMoveToDemoLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to Expected Demo move to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/expected-demo-move-to-demo-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedLeadDemo.ajax.reload(null,false);
							dataTableExpectedLeadDemo.ajax.reload(function(){
								$('#datatable-expected-lead-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},	
			selectDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/selectDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							// $('#bulkSmsModal .alert').remove();
							// $('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							// $('#bulkSmsControl').val('');
							// setTimeout(function(){
								// $('#bulkSmsModal').modal('hide');
							// },2000);
							location.reload();
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
				selectForwardDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/selectForwardDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLeadForward.ajax.reload(function(){
								$('#datatable-lead-forward').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
			selectMailerDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/selectMailerDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
					   
						if(data.statusCode=='1'){
						    dataTableMailData.ajax.reload(null,false);
						    dataTableMailData.ajax.reload(function(){
								$('#datatable-mailer_data').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			selectFeedbackDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/selectFeedbackDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
						    dataTableFeedback.ajax.reload(null,false);
							 
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			counsellorFeedbackDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/counsellorFeedbackDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
						    dataTableCounsellorFeedback.ajax.reload(null,false);
						 
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			selectToNewLeads:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to New Leads!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/selectToNewLeads",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
				    	if(data.statusCode){
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Update New Leads successfully...');
							dataTableLead.ajax.reload(null,false);						 
							 						 
							mainSpinner.stop();
							alert(data.data.message);
							 
						}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html('Not update New Leads successfully...');							
								dataTableLead.ajax.reload(null,false);
							}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
			getExpectFollowUps:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/expect-follow-up/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						$('#expected_date_time').daterangepicker({
							singleDatePicker: true,
							autoUpdateInput: false,
							timePicker: true,
							minDate: new Date(),
							//autoUpdateInput: false,
							locale: {
								format: 'DD-MMMM-YYYY h:mm A'
							},
							singleClasses: "picker_2"
						}/* , function(start, end, label) {
							alert(JSON.stringify(start));
							$('#expected_date_time').val(start.format('DD-MMMM-YYYY h:mm A'));
						} */);
						$('#expected_date_time').on('apply.daterangepicker', function(ev, picker) {
							$('#expected_date_time').val(picker.startDate.format('DD-MMMM-YYYY h:mm A'));
						});
						select2_course();
						select2_trainer();
						select2_user();
						dataTableExpectFollowUps = $('#datatable-expect-followups').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":false,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/lead/getexpectfollowups/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									d.count = $(".follow-up-count").val();
								}
							}
						}).api();
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadController.getExpectFollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadController.getExpectFollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadController.getExpectFollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadController.getExpectFollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storeExpectFollowUp:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/lead/store-expect-follow-up/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							//$this.find('*[name="status"]').val('');
							//$this.find('*[name="expected_date_time"]').val('');
							$this.find('*[name="remark"]').val('');
							alert('Meeting Up created successfully');
							dataTableExpectedLeadDemo.ajax.reload( null, false );							 
							dataTableExpectFollowUps.ajax.reload( null, false );							 
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);								 
							removeValidationErrors($this);
							mainSpinner.stop();
						}
						if(response.demo_created){
							$this.closest('.x_content').html("<p style='font-size: 24px;font-weight:700;padding-top:20px;text-align:center;'>Lead successfully moved to Demo...</p>");
						}
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			
			leadjoindededit:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/leadjoindededit/"+id,
					type:"GET",
					success:function(response){
						 
						$('#leadDemoJoined .modal-body').html(response.html);						 
						select2_course();
						select2_trainer();
						select2_user();
						 
						 
						 
						$('#leadDemoJoined').modal({keyboard:false,backdrop:'static'});
						$('#leadDemoJoined .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storeleadjoind:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/lead/storeleadjoind/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							alert('Update successfully');
							 	$('.leadjoinded').click();					 
							 	 				 
						 						 
							removeValidationErrors($this);
							mainSpinner.stop();
						}
						 
						 
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			
			leadForwardForm:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/lead-forward-form/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						 				 
						select2_course();
						select2_assignuser();	
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadController.leadForwardForm('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadController.leadForwardForm('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadController.leadForwardForm('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadController.leadForwardForm('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storeLeadForward:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/lead/store-lead-forward/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							$this.find('*[name="owner"]').val('');
							alert('Forward successfully');
						dataTablePendingLeads.ajax.reload( null, false );
						dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);								 
							removeValidationErrors($this);
							mainSpinner.stop();
						}
						 
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			
			
			
			
		};
	})();
	
	
	// ***************
// Trainer CONTROLLER
	var trainerController = (function(){
		return {
			checked_Ids:[],
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					//alert(data);
				$.ajax({
					url:"/trainer/add-trainer",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Trainer added successfully');
							$this.find('button[type="reset"]').click();
							$this.find('[name="course"]').html('').val('').trigger('change');
						}else{
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Lead not added');
					}
				});
				return false;
			},
			 
			getfollowUps:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/trainer/follow-up/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						$('#expected_date_time').daterangepicker({
							singleDatePicker: true,
							autoUpdateInput: false,
							timePicker: true,
							minDate: new Date(),
							//autoUpdateInput: false,
							locale: {
								format: 'DD-MMMM-YYYY h:mm A'
							},
							singleClasses: "picker_2"
						} );
						$('#expected_date_time').on('apply.daterangepicker', function(ev, picker) {
							$('#expected_date_time').val(picker.startDate.format('DD-MMMM-YYYY h:mm A'));
						});
						select2_course();
						dataTableTrainerFollowUps = $('#datatable-trainerfollowups').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":false,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/trainer/getfollowups/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									d.count = $(".follow-up-count").val();
								}
							}
						}).api();
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:trainerController.getfollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:trainerController.getfollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:trainerController.getfollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:trainerController.getfollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storeFollowUp:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/trainer/store-follow-up/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							//$this.find('*[name="status"]').val('');
							//$this.find('*[name="expected_date_time"]').val('');
							$this.find('*[name="remark"]').val('');
							alert('Follow Up created successfully');
							dataTableTrainerFollowUps.ajax.reload( null, false );							 
							dataTableTrainer.ajax.reload(function(){
								$('#datatable-trainer').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);						
							removeValidationErrors($this);
						}
						if(response.demo_created){
							$this.closest('.x_content').html("<p style='font-size: 24px;font-weight:700;padding-top:20px;text-align:center;'>Lead successfully moved to Demo...</p>");
						}
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			sendMail:function(id){
				mainSpinner.start();
				$.ajax({
					url:'/trainer/sendmail/'+id,
					type:"GET",
					dataType:'json',
					success:function(response){
						//console.log(response);
						if(response.status){
							alert(response.success.message);
							mainSpinner.stop();
						}
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						alert('Something went wrong');
						mainSpinner.stop();
					}
				});
				return false;
			},
			getAllFollowUps:function(){
				//mainSpinner.start();
				dataTableTrainerFollowUps.ajax.reload( null, false );
				return false;
			},
			getAllExpectFollowUps:function(){
				//mainSpinner.start();
				dataTableExpectFollowUps.ajax.reload( null, false );
				return false;
			},
			bulkSms:function(){
				
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    alert('Please select data for Bulk SMS!');
					return false;
				}
				$('#bulkSmsModal .alert').remove();
				$('#bulkSmsModal').modal({backdrop:"static",keyboard:false});
				return false;
			},
			sendBulkSms:function(){
				if($('#bulkSmsControl').val() == '')
					return false;
				var $this = this;
				$.ajax({
					url:"/trainer/send-bulk-sms",
					type:"POST",
					dataType:"json",
					data:{
						message:$('#bulkSmsControl').val(),
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.status){
							alert(data.success.message);
							$('#bulkSmsModal .alert').remove();
							$('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							$('#bulkSmsControl').val('');
							setTimeout(function(){
								$('#bulkSmsModal').modal('hide');
							},2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;
			},		 				
			 	 
			selectDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/trainer/selectdelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableTrainer.ajax.reload(function(){
								$('#datatable-trainer').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			}
			 
		};
	})();
	
// ***************
// Trainer CONTROLLER
	var trainerRequiredController = (function(){
		return {
			checked_Ids:[],
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					//alert(data);
				$.ajax({
					url:"/trainerrequired/add-trainer",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Lead added successfully');
							$this.find('button[type="reset"]').click();
							$this.find('[name="course"]').html('').val('').trigger('change');
						}else{
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Lead not added');
					}
				});
				return false;
			},
			 
			getfollowUps:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/trainerrequired/follow-up/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						$('#expected_date_time').daterangepicker({
							singleDatePicker: true,
							autoUpdateInput: false,
							timePicker: true,
							minDate: new Date(),					
							locale: {
								format: 'DD-MMMM-YYYY h:mm A'
							},
							singleClasses: "picker_2"
						});
						$('#expected_date_time').on('apply.daterangepicker', function(ev, picker) {
							$('#expected_date_time').val(picker.startDate.format('DD-MMMM-YYYY h:mm A'));
						});
						select2_course();
						dataTableTrainerFollowUps = $('#datatable-trainerfollowups').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":false,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/trainerrequired/getfollowups/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									d.count = $(".follow-up-count").val();
								}
							}
						}).api();
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:trainerRequiredController.getfollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:trainerRequiredController.getfollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:trainerRequiredController.getfollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:trainerRequiredController.getfollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				
			},
			storeFollowUp:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/trainerrequired/store-follow-up/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							//$this.find('*[name="status"]').val('');
							//$this.find('*[name="expected_date_time"]').val('');
							$this.find('*[name="remark"]').val('');
							alert('Follow Up created successfully');
							dataTableTrainerFollowUps.ajax.reload( null, false );						 
							dataTableTrainerRequired.ajax.reload(function(){
								$('#datatable-trainerrequired').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							 
							removeValidationErrors($this);
						}
						if(response.demo_created){
							$this.closest('.x_content').html("<p style='font-size: 24px;font-weight:700;padding-top:20px;text-align:center;'>Lead successfully moved to Demo...</p>");
						}
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			sendMail:function(id){
				mainSpinner.start();
				$.ajax({
					url:'/trainerrequired/sendmail/'+id,
					type:"GET",
					dataType:'json',
					success:function(response){
						//console.log(response);
						if(response.status){
							alert(response.success.message);
							mainSpinner.stop();
						}
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						alert('Something went wrong');
						mainSpinner.stop();
					}
				});
				return false;
			},
			getAllFollowUps:function(){
				//mainSpinner.start();
				dataTableTrainerFollowUps.ajax.reload( null, false );
				return false;
			},
			bulkSms:function(){
				//var checked_Ids = [];
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					//alert($(this).val());
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    alert('Please select data for Bulk SMS!');
					return false;
				}
				$('#bulkSmsModal .alert').remove();
				$('#bulkSmsModal').modal({backdrop:"static",keyboard:false});
				return false;
			},
			sendBulkSms:function(){
				if($('#bulkSmsControl').val() == '')
					return false;
				var $this = this;
				$.ajax({
					url:"/trainerrequired/send-bulk-sms",
					type:"POST",
					dataType:"json",
					data:{
						message:$('#bulkSmsControl').val(),
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.status){
							alert(data.success.message);
							$('#bulkSmsModal .alert').remove();
							$('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							$('#bulkSmsControl').val('');
							setTimeout(function(){
								$('#bulkSmsModal').modal('hide');
							},2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;
			},
			 		 		
			 	 
			selectDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/trainerrequired/selectdelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableTrainerRequired.ajax.reload(function(){
								$('#datatable-trainerrequired').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			}
			 
		};
	})();

	
	
// LEAD CONTROLLER
// ***************
//Add students demo
  var demoStudentsController = (function(){
		return {
			checked_Ids:[],
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					//alert(data);
				$.ajax({
					url:"/demo/add-lead-students",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Lead added successfully');
							$this.find('button[type="reset"]').click();
							$this.find('[name="course"]').html('').val('').trigger('change');
							window.location=document.location.href; 
								window.location=document.location.href; 
						}else{
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Lead not added');
					}
				});
				return false;
			}
			
			
	};
	})();
  
  
  var feesManageController = (function(){
		return {
			checked_Ids:[],
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					//alert(data);
				$.ajax({
					url:"/fees/save-fees",
					type:"POST",
					data:data,
					success:function(response){
						 
						if(response.status){
							mainSpinner.stop();

							$(".resetform").click();
						 
							$("#messagemodel").modal();
							$('.imgclass').html('<img src="../Thanks_success.jpg" style="width: 100%;text-align: center;margin: auto;display: block;">');					
							$('.successhtml').html("<p class='text-center' style='font-weight: 600;'>We will get back to you soon.</p>");
							$('#messagemodel').modal({backdrop:"static",keyboard:false});
							removeValidationErrors($this);
							$this.find('.ans').removeClass('has-error');
							$this.find('.help-block').remove();
							$this.find('button[type="reset"]').click();
							$this.find('[name="course"]').html('').val('').trigger('change');

							
							
						}else{
							mainSpinner.stop();
							$("#messagemodel").modal();
							$("#sidemodalclass").modal('hide');
							$('.imgclass').html('<img src="../message_alert.png" style="width: 50%;text-align: center;margin: auto;display: block;">');				
							$('.failedhtml').html("<p class='text-center'>Some Errot Please Tray again.</p>");	
							$('#messagemodel').modal({backdrop:"static",keyboard:false});						
						}
					},
					error:function(jqXHR, textStatus, errorThrown){
						mainSpinner.stop();
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){						 
						var errors=response.errors;
						$this.find('.ans').removeClass('has-error');
						$this.find('.help-block').remove();
						for (var key in errors) {
						if(errors.hasOwnProperty(key)){
						var el = $this.find('*[name="'+key+'"]');
						$('<span class="help-block"><strong>'+errors[key][0]+'</strong></span>').insertAfter(el);
						el.closest('.ans').addClass('has-error');

						}
						}

						}else{
						alert('Something went wrong');
						}
						 
					}
				});
				return false;
			},
			selectFeesDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fees/selectdelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableAllFees.ajax.reload(function(){
								$('#datatable-all-fees').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			}
			
			
	};
	})();
  
  //end add students demo
// ***************
// DEMO CONTROLLER
	var demoController = (function(){
		return {
			checked_Ids:[],
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					//alert(data);
				$.ajax({
					url:"/demo/add-lead",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Lead added successfully');
							$this.find('button[type="reset"]').click();
							$this.find('[name="course"]').html('').val('').trigger('change');
						}else{
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Lead not added');
					}
				});
				return false;
			},
			
			addbatchsubmit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					 
				$.ajax({
					url:"/demo/batch/addbatch",
					type:"POST",
					data:data,
					success:function(response){
						 
						if(response.status){					 
							 
							 alert('Created Successfully ');							 
							 $('.alert-success').removeClass('hide').text(response.msg);
							 window.location.href = "demo/batch/batch";  
							 $this.find('button[type="reset"]').click();
							$this.find('[name="user"]').html('').val('').trigger('change');
							removeValidationErrors($this);
						}
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
/* 			delete:function(id){
				if(confirm("Are you sure ??")){
					mainSpinner.start();
					$.ajax({
						url:"/lead/delete/"+id,
						type:"GET",
						success:function(response){
							if(response.status){
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-success').removeClass('hide').html('Lead deleted softly and successfully');
								dataTableLead.ajax.reload(null,false);
							}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html(response.errors);							
								dataTableLead.ajax.reload(null,false);
							}
						},
						error:function(response){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Lead not deleted');
						}
					});
				}
				return false;
			}, */
			delete:function(id){
				var args = {
					target:id,
					controller:"demoController",
					callback:"deleteDemo",
					yesLabel:"Yes",
					noLabel:"No",
					modal_title:"Confirmation",
					modal_body:"Are you sure you want to delete it ??"
				}
				confirmationBox.open(args);
				return false;
			},
			deleteDemo:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/demo/delete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Demo deleted successfully');
							dataTableDemoNotInterested.ajax.reload( null, false );
							dataTableDemo.ajax.reload(function(){
								$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
							dataTableDemo.ajax.reload(function(){
								$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Demo not deleted');
					}
				});
				return false;
			},
			getfollowUps:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/demo/follow-up/"+id,
					type:"GET",
					success:function(response){						 
						$('#demoFollowUpModal .modal-body').html(response.html);
						$('#expected_date_time').daterangepicker({
							singleDatePicker: true,
							autoUpdateInput: false,
							timePicker: true,
							minDate: new Date(),
							//autoUpdateInput: false,
							locale: {
								format: 'DD-MMMM-YYYY h:mm A'
							},
							singleClasses: "picker_2"
						});
						$('#expected_date_time').on('apply.daterangepicker', function(ev, picker) {
							$('#expected_date_time').val(picker.startDate.format('DD-MMMM-YYYY h:mm A'));
						});
						select2_course();
						dataTableFollowUps = $('#datatable-demoFollowups').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":false,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/demo/getfollowups/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									d.count = $(".follow-up-count").val();
								}
							}
						}).api();						
						var prevNextHtml = '';	
						 
					 if(Array.isArray(demoRecordCollection)){
						for(var i=0;i<demoRecordCollection.length;i++){
							if(demoRecordCollection[i]==id && demoRecordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:demoController.getfollowUps('+demoRecordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(demoRecordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:demoController.getfollowUps('+demoRecordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:demoController.getfollowUps('+demoRecordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:demoController.getfollowUps('+demoRecordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
					 }
						$('#demoFollowUpModal .modal-title').html(prevNextHtml);
						$('#demoFollowUpModal').modal({keyboard:false,backdrop:'static'});
						$('#demoFollowUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				
			},
			storeFollowUp:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/demo/store-follow-up/'+id,
					type:"post",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							//$this.find('*[name="status"]').val('');
							//$this.find('*[name="expected_date_time"]').val('');
							$this.find('*[name="remark"]').val('');
							alert('Follow Up created successfully');
							dataTableFollowUps.ajax.reload( null, false );
							dataTableExpectedNewBatchDemo.ajax.reload( null, false );
							dataTableDemoNotInterested.ajax.reload( null, false );
							dataTableDemo.ajax.reload(function(){
								$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							dataTablePendingLeadsDemos.ajax.reload(function(){
								$('#datatable-pending-leads-demos').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							removeValidationErrors($this);
						}
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			sendMail:function(id){
				mainSpinner.start();
				$.ajax({
					url:'/demo/sendmail/'+id,
					type:"GET",
					dataType:'json',
					success:function(response){
						//console.log(response);
						if(response.status){
							alert(response.success.message);
							mainSpinner.stop();
						}
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						alert('Something went wrong');
						mainSpinner.stop();
					}
				});
				return false;
			},
			getAllFollowUps:function(){
				//mainSpinner.start();
				dataTableFollowUps.ajax.reload( null, false );
				return false;
			},
			bulkSms:function(){
				//var checked_Ids = [];
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					//alert($(this).val());
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    alert('Please select data for Bulk SMS !');
					return false;
				}
				$('#demoBulkSmsModal .alert').remove();
				$('#demoBulkSmsModal').modal({backdrop:"static",keyboard:false});
				return false;
			},
			sendBulkSms:function(){
				if($('#bulkSmsControl').val() == '')
					return false;
				var $this = this;
				$.ajax({
					url:"/demo/send-bulk-sms",
					type:"POST",
					dataType:"json",
					data:{
						message:$('#bulkSmsControl').val(),
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.status){
							//alert(data.success.message);
							$('#demoBulkSmsModal .alert').remove();
							$('#demoBulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							$('#bulkSmsControl').val('');
							setTimeout(function(){
								$('#demoBulkSmsModal').modal('hide');
							},2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;
			},
			selectedDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    
					alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/demo/selectDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableDemo.ajax.reload(function(){
								$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							// $('#bulkSmsModal .alert').remove();
							// $('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							// $('#bulkSmsControl').val('');
							// setTimeout(function(){
								// $('#bulkSmsModal').modal('hide');
							// },2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			moveNotInterested:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    alert('Please select data to move Not Intereseted and Location Issue!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/demo/move-not-interested",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableDemo.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
			
			moveToDemos:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0)
					//return false;
				
				mainSpinner.start();
				$.ajax({
					url:"/demo/move-to-demos",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){							 
							dataTableDemoNotInterested.ajax.reload( null, false );
							dataTableDemo.ajax.reload(function(){
								$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
			
			expectedMoveToDemos:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0)
				{					 
					alert('Please select data to expected move to demo!');
					return false;
				}
				
				mainSpinner.start();
				$.ajax({
					url:"/demo/expected-move-to-demos",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){	 
							 
							dataTableExpectedDemo.ajax.reload( null, false );
							dataTableExpectedDemo.ajax.reload(function(){
								$('#datatable-expected-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
			
			expectedNewBatchMoveToDemos:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0)
				{					 
					alert('Please select data to New Batch Move to Demo!');
					return false;
				}
				
				mainSpinner.start();
				$.ajax({
					url:"/demo/expected-new-batch-move-to-demos",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){	 
							 
							dataTableExpectedNewBatchDemo.ajax.reload( null, false );
							dataTableExpectedNewBatchDemo.ajax.reload(function(){
								$('#datatable-expected-new-batch-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
			moveToJoinDemos:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0)
				{					 
					alert('Please select data to move Join!');
					return false;
				}
				
				mainSpinner.start();
				$.ajax({
					url:"/demo/move-to-join-demos",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){	 
							 
							dataTableDemoJoinedDemos.ajax.reload( null, false );
							dataTableDemo.ajax.reload(function(){
								$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
			moveJoinedDemos:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    
				alert('please select data to Move Joined Demo !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/demo/move-joined-demos",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						  
						if(data.statusCode){
							dataTableDemo.ajax.reload(function(){
								$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},true);
							mainSpinner.stop();
							alert(data.data.message);
							// $('#bulkSmsModal .alert').remove();
							// $('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							// $('#bulkSmsControl').val('');
							// setTimeout(function(){
								// $('#bulkSmsModal').modal('hide');
							// },2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
			moveToExpectedDemos:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    
				alert('please select data to Move Expected Demo !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/demo/move-to-expected-demos",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						  
						if(data.statusCode){
							dataTableDemo.ajax.reload(function(){
								$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},true);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
			moveToExpectedNewBatchDemos:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    
				alert('please select data To Move Expected New Batch Demo !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/demo/move-to-expected-new-batch-demos",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						  
						if(data.statusCode){
							dataTableDemo.ajax.reload( null, false );
							dataTableDemo.ajax.reload(function(){
								$('#datatable-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},true);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
				moveToBatchModel:function(){
				 
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
				 
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    alert('Please select data for Lead to Batch!');
					return false;
				}
				$('#moveToBatchModel .alert').remove();
				$('#moveToBatchModel').modal({backdrop:"static",keyboard:false});
				return false;
			},
			leadAssignToBatch:function(){
				if($('#batch_id').val() == '')
					return false;
				var $this = this;
				mainSpinner.start();
				$.ajax({
					url:"/demo/batch/assignleadbatch",
					type:"POST",
					dataType:"json",
					data:{
						batch_id:$('#batch_id').val(),
						ids:$this.checked_Ids
					},
									
					
					success:function(data,textStatus,jqXHR){
						mainSpinner.stop();
						if(data.status){								
							 
						 alert('Successfully Assigned Leads to Batch');
						 dataTableDemo.ajax.reload(null,false);	
							$('#moveToBatchModel .alert').remove();
							$('.assignsuccess').text("Successfully Assigned Leads to batch");
							$('#batch_id').val('');
							setTimeout(function(){
								$('#moveToBatchModel').modal('hide');
							},2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;
			},
			 batchMoveToDemos:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0)
				{					 
					alert('Please select data to Batch Move to Demo!');
					return false;
				}
				
				mainSpinner.start();
				$.ajax({
					url:"/demo/batch/batch-move-to-demos",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){	 
							 mainSpinner.stop();
							alert(data.data.message);
							dataTableBatch.ajax.reload( null, false );	
								dataTableBatch.ajax.reload(function(){
								$('#datatable-batch').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},true);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
			selectToNewDemos:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to New Demos!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/demo/selectToNewDemos",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
				    	if(data.statusCode){
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Update New Demos successfully...');
							dataTableDemo.ajax.reload(null,false);						 
							 						 
							mainSpinner.stop();
							alert(data.data.message);
							 
						}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html('Not update New Demos successfully...');							
								dataTableDemo.ajax.reload(null,false);
							}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
			leadDemoJoined:function(){
				var $this = this;
				$this.checked_Ids = [];
		    	if ($(".check-lead-box:checked").length > 0)
				{
				 
				$('.check-lead-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
					 
						$this.checked_Ids.push($(this).val());
					}
				});	
				$this.check_type = [];
				$('.check-lead-type').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						 
						$this.check_type.push($(this).val());
					}
				});
				
				} else {
					
					$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
					 
						$this.checked_Ids.push($(this).val());
					}
				});	
				$this.check_type = [];
				$('.check-demo-type').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						 
						$this.check_type.push($(this).val());
					}
				});
				}
				 
				if($this.checked_Ids.length == 0){
				    
				alert('please select data to Move Joined Demo !');
					return false;
				}
				 
				mainSpinner.start();
				$.ajax({
					url:"/demo/leadDemoJoined",
					type:"POST",
					dataType:"json",
					data:{
			    		ids:$this.checked_Ids,checktype:$this.check_type,
					},
					success:function(data,textStatus,jqXHR){
						  
						if(data.statusCode){
						    mainSpinner.stop();		
							$('.leadjoinded').click();							
							alert(data.data.message);
							 
						}else{
								mainSpinner.stop();
							alert(data.data.message);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				//return false;				
			},
			
			 demojoindededit:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/demo/demojoindededit/"+id,
					type:"GET",
					success:function(response){
						 
						$('#leadDemoJoined .modal-body').html(response.html);						 
						select2_course();
						select2_trainer();
						select2_user();
						 
						 
						 
						$('#leadDemoJoined').modal({keyboard:false,backdrop:'static'});
						$('#leadDemoJoined .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storedemojoind:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/demo/storedemojoind/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							//$this.find('*[name="status"]').val('');
							//$this.find('*[name="expected_date_time"]').val('');
							$this.find('*[name="remark"]').val('');
							alert('Update successfully');
							 	$('.leadjoinded').click();					 
							 	 //window.location.reload();					 
						 						 
							removeValidationErrors($this);
							mainSpinner.stop();
						}
						 
						 
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
		};
	})();
// DEMO CONTROLLER
// ***************


// ***********************
// DELETED LEAD CONTROLLER
	var enterLeadController = (function(){
		return {
				enterleadSave:function(THIS){	
		 
				var $this = $(THIS),
					data  = $this.serialize();	
			 			mainSpinner.start();
				$.ajax({
					url:"/enterleadSave",
					type:"POST",
					data:data,
					success:function(response){
						 	mainSpinner.stop();	
						if(response.status){								 
							
							$(".resetform").click();
							$("#messagemodelrefresh").modal();
							$('.imgclass').html('<img src="/img/thankpopup.png" style="width: 100%;text-align: center;margin: auto;display: block;">');					
							$('.successhtml').html("<p class='text-center' style='font-weight: 600;'>Your Submission has been received. <br> Our experts will reach out to you in the next 24 hours.</p>");
							$('#messagemodelrefresh').modal({backdrop:"static",keyboard:false});
							removeValidationErrors($this);
							$this.find('.ans').removeClass('has-error');
							$this.find('.help-block').remove();
							 location.reload();
						}else{	
						    alert('Some Errot Please Tray again');
						//	$("#messagemodelrefresh").modal();
						//$('.imgclass').html('<img src="/public/img/message_alert.png" style="width: 50%;text-align: center;margin: auto;display: block;">');				
						//	$('.failedhtml').html("<p class='text-center'>Some Errot Please Tray again.</p>");	
						//	$('#messagemodelrefresh').modal({backdrop:"static",keyboard:false});							
						}
					},
					error:function(jqXHR, textStatus, errorThrown){
					    mainSpinner.stop();	
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){						 
						var errors=response.errors;
						$this.find('.ans').removeClass('has-error');
						$this.find('.help-block').remove();
						for (var key in errors) {
						if(errors.hasOwnProperty(key)){
						var el = $this.find('*[name="'+key+'"]');
						$('<span class="help-block"><strong>'+errors[key][0]+'</strong></span>').insertAfter(el);
						el.closest('.ans').addClass('has-error');

						}
						}

						}else{
						alert('Something went wrong');
						}
						 
					}
				});
				return false;
			} 
		};
	})();
	
	
 

// ***********************
// DELETED LEAD CONTROLLER
	var deletedLeadController = (function(){
		return {
			delete:function(id){
				if(confirm("Are you sure ??")){
					mainSpinner.start();
					$.ajax({
						url:"/lead/force-delete/"+id,
						type:"GET",
						success:function(response){
							if(response.status){
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-success').removeClass('hide').html('Lead deleted successfully');
								dataTableDeletedLead.ajax.reload(null,false);
							}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html('Lead not deleted!');							
								dataTableDeletedLead.ajax.reload(null,false);
							}
						},
						error:function(response){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Lead not deleted');
						}
					});
				}
				return false;
			},
			restore:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/restore/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Lead restored successfully');
							dataTableDeletedLead.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
							dataTableDeletedLead.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Lead not restored');
					}
				});
				return false;
			},
			selectDeleteParmanent:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to Delete Permanently!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/selectDeleteParmanent",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
				    	if(data.statusCode){
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Deleted Permanently successfully...');
							dataTableLeadNotInterested.ajax.reload(null,false);
							dataTableDeletedLead.ajax.reload(null,false);							 
							mainSpinner.stop();
							alert(data.data.message);
							 
						}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html('Not Deleted Permanently successfully...');					
								dataTableLeadNotInterested.ajax.reload(null,false);
							}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			}
		};
	})();
	
	
// DELETED LEAD CONTROLLER
// ***********************

var batchController = (function(){
		return {
			submit:function(THIS){
				//mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					//alert(data);
				$.ajax({
					url:"/upcomingBatch/save",
					type:"POST",
					data:data,
						dataType:'json',
					success:function(response){
						 
						if(response.status){
							mainSpinner.stop();
							$('#upcomingbatchModal').modal('hide');
							alert('batch added successfully');
							$this.find('button[type="reset"]').click();
							removeValidationErrors($this);
							dataTableUpcomingBatches.ajax.reload(null,false);
						} 
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
	    	delete:function(id){
				if(confirm("Are you sure want to deleted?")){
					mainSpinner.start();
					$.ajax({
						url:"/upcomingBatch/destroy/"+id,
						type:"GET",
						success:function(response){
							if(response.status){
								mainSpinner.stop();
							alert('deleted successfully');
								dataTableUpcomingBatches.ajax.reload(null,false);
							}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html('Demo not deleted!');							
								dataTableUpcomingBatches.ajax.reload(null,false);
							}
						},
						error:function(response){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Lead not deleted');
						}
					});
				}
				return false;
			},	   
		};
	})();

// ***********************
// DELETED DEMO CONTROLLER
	var deletedDemoController = (function(){
		return {
			delete:function(id){
				if(confirm("Are you sure ??")){
					mainSpinner.start();
					$.ajax({
						url:"/demo/force-delete/"+id,
						type:"GET",
						success:function(response){
							if(response.status){
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-success').removeClass('hide').html('Lead deleted successfully');
								dataTableDeletedDemo.ajax.reload(null,false);
							}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html('Demo not deleted!');							
								dataTableDeletedDemo.ajax.reload(null,false);
							}
						},
						error:function(response){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Lead not deleted');
						}
					});
				}
				return false;
			},
			selectDeleteParmanent:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to Delete Permanently!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/demo/selectDeleteParmanent",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
					    if(data.statusCode){							
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Deleted Permanently successfully...');
							dataTableDemoNotInterested.ajax.reload(null,false);	dataTableDeletedDemo.ajax.reload(null,false);	dataTableDemoJoinedDemos.ajax.reload(null,false);					 
							mainSpinner.stop();
							alert(data.data.message);
							 
						}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html('Not Deleted Permanently successfully...');							
								dataTableDemoNotInterested.ajax.reload(null,false);
							}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			restore:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/demo/restore/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Demo restored successfully');
							dataTableDeletedDemo.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
							dataTableDeletedDemo.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Demo not restored');
					}
				});
				return false;
			}
		};
	})();
// DELETED DEMO CONTROLLER
// ***********************

// *****************
// SOURCE CONTROLLER
	var sourceController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/source",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Source added successfully');
							$this.find('button[type="reset"]').click();
							dataTableSource.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Source not added');
					}
				});
				return false;
			},
			delete:function(id){
				var args = {
					target:id,
					controller:"sourceController",
					callback:"deleteSource",
					yesLabel:"Yes",
					noLabel:"No",
					modal_title:"Confirmation",
					modal_body:"Are you sure you want to delete it ??"
				}
				confirmationBox.open(args);
				return false;
			},
			deleteSource:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/source/delete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Source deleted successfully');
							dataTableSource.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
							dataTableSource.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Source not deleted');
					}
				});
				return false;
			},
			status:function(id,val){		 
			 	if(val==true){
				if(confirm("Are you sure you want to change the status to Active?")){				 
				$.ajax({
					url:"/source/status/"+id+"/"+val,
					type:"GET",					
					success:function(response){	
						mainSpinner.stop();						
					if(response.status){
						$('#messagemodel .modal-title').text("status successfully update");	
						$('#messagemodel .modal-body').html("<div class='alert alert-success'>"+response.msg+"</div>");		
						$('#messagemodel').modal({keyboard:false,backdrop:'static'});
						$('#messagemodel').css({'width':'100%'});
						dataTableSource.ajax.reload( null, false );   
					}else{
							$('#messagemodel .modal-title').text("Status successfully update");	
							$('#messagemodel .modal-body').html("<div class='alert alert-danger'>"+response.msg+"</div>");		
							$('#messagemodel').modal({keyboard:false,backdrop:'static'});
							$('#messagemodel').css({'width':'100%'});
					}						
					},
					error:function(response){
						mainSpinner.stop();	
						 alert('some error');
					}
				});
				}			
				
			}else{
				if(confirm("Are you sure you want to change the status to Inactive?")){
				 
				$.ajax({
					url:"/source/status/"+id+"/"+val,
					type:"GET",				
					success:function(response){		
						mainSpinner.stop();						
					if(response.status){
						$('#messagemodel .modal-title').text("status successfully update");	
						$('#messagemodel .modal-body').html("<div class='alert alert-success'>"+response.msg+"</div>");		
						$('#messagemodel').modal({keyboard:false,backdrop:'static'});
						$('#messagemodel').css({'width':'100%'});
						dataTableSource.ajax.reload( null, false );   
					}else{
							$('#messagemodel .modal-title').text("Status successfully update");	
							$('#messagemodel .modal-body').html("<div class='alert alert-danger'>"+response.msg+"</div>");		
							$('#messagemodel').modal({keyboard:false,backdrop:'static'});
							$('#messagemodel').css({'width':'100%'});
					}						
					},
					error:function(response){
						mainSpinner.stop();	
						 alert('some error');
					}
				});
				}				
			}			
			
			},
			
		};
	})();
// SOURCE CONTROLLER
// *****************

// *****************
// COURSE CONTROLLER
	var courseController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/course",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course added successfully');
							$this.find('button[type="reset"]').click();
							
							dataTableCourse.ajax.reload(null,false);
							
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			delete:function(id){
				var args = {
					target:id,
					controller:"courseController",
					callback:"deleteCourse",
					yesLabel:"Yes",
					noLabel:"No",
					modal_title:"Confirmation",
					modal_body:"Are you sure you want to delete it ??"
				}
				confirmationBox.open(args);
				return false;
			},
			deleteCourse:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/course/delete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course deleted successfully');
						 window.location = window.location.href;
							dataTableCourse.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			assignCoursesubmit:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/course/assignment/addcourse",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course added successfully');
							$this.find('button[type="reset"]').click();
							//datatableAssignCourse.ajax.reload(null,false);
							window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			
			assigncoursedelete:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/course/assignment/assigncoursedelete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('deleted successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course deleted successfully');
							//dataTableCourse.ajax.reload(null,false);
							 window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			assigncoursestatus:function(id,val){
				mainSpinner.start();
				$.ajax({
					url:"/course/assignment/assigncoursestatus/"+id+"/"+val,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('Status successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Status successfully');
							//dataTableCourse.ajax.reload(null,false);
							 window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Assign Course not Status!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Assign not Update');
					}
				});
				return false;
			},
				
		
			coursepdfstatus:function(id,val){
				 
				mainSpinner.start();
				$.ajax({
					url:"/coursepdf/coursepdfstatus/"+id+"/"+val,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('Status successfully');
							dataTableallcoursecontentpdf.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
								alert('Not Status successfully');							 					
							dataTableallcoursecontentpdf.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Assign not Update');
					}
				});
				return false;
			},
			
		
			
			
		};
	})();
// COURSE CONTROLLER
// *****************





// *****************
// COURSE CONTROLLER
	var leadmanagmentController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/course",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course added successfully');
							$this.find('button[type="reset"]').click();
							dataTableCourse.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			delete:function(id){
				var args = {
					target:id,
					controller:"courseController",
					callback:"deleteCourse",
					yesLabel:"Yes",
					noLabel:"No",
					modal_title:"Confirmation",
					modal_body:"Are you sure you want to delete it ??"
				}
				confirmationBox.open(args);
				return false;
			},
			deleteCourse:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/course/delete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course deleted successfully');
							//dataTableCourse.ajax.reload(null,false);
							 window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			
			duplicatecourseassignment:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/lead/duplicatecourseassignment",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course added successfully');
							$this.find('button[type="reset"]').click();
							//datatableAssignCourse.ajax.reload(null,false);
							window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			courseassignment:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/lead/courseassignmentadd",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course added successfully');
							$this.find('button[type="reset"]').click();
							//datatableAssignCourse.ajax.reload(null,false);
							window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			saveleadcount:function(THIS){			 
				 
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/lead/saveleadcount",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
						 alert('Updated successfully');
							window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			assigncoursedelete:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/assigncoursedelete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('deleted successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html(' deleted successfully');
							//dataTableCourse.ajax.reload(null,false);
							 window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			
			assigncoursestatus:function(id,val){
				mainSpinner.start();
				$.ajax({
					url:"/course/assignment/assigncoursestatus/"+id+"/"+val,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('Status successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Status successfully');
							//dataTableCourse.ajax.reload(null,false);
							 window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Assign Course not Status!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Assign not Update');
					}
				});
				return false;
			},
			
				statususer:function(id,val){
				mainSpinner.start();
				$.ajax({
					url:"/lead/statususer/"+id+"/"+val,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('Status successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Status successfully');
							dataTableCunsloor.ajax.reload(null,false);
							 
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Assign Course not Status!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Assign not Update');
					}
				});
				return false;
			},
			coursepdfstatus:function(id,val){
				 
				mainSpinner.start();
				$.ajax({
					url:"/coursepdf/coursepdfstatus/"+id+"/"+val,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('Status successfully');
						 window.location = window.location.href;
							dataTableallcoursecontentpdf.ajax.reload(null,false);
							 
						}else{
							mainSpinner.stop();
								alert('Not Status successfully');							 					
							dataTableallcoursecontentpdf.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Assign not Update');
					}
				});
				return false;
			},
				
			getCourseWiseCountlead:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/getCourseWiseCountlead/"+id,
					type:"GET",
					success:function(response){
						 
						$('#viewCourseWiseLead .modal-body').html(response.html);
						datatableCourseLead = $('#datatable-course-lead').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":true,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/lead/getCourseLead/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									
								},
								dataSrc:function(json){
								recordCollection = json.recordCollection;
								return json.data;
								}
							}
						}).api();
						
						$('#viewCourseWiseLead').modal({keyboard:false,backdrop:'static'});
						$('#viewCourseWiseLead .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			
			
			leadAssignForm:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/lead-assign-form/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						 				 
						select2_course();
						select2_assignuser();	
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadmanagmentController.leadAssignForm('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadmanagmentController.leadAssignForm('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadmanagmentController.leadAssignForm('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadmanagmentController.leadAssignForm('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storeLeadAssign:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/lead/store-lead-assign/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							$this.find('*[name="owner"]').val('');
							alert('Created successfully');
							dataTableNotLeadAssignment.ajax.reload(null,false);	
							dataTableAllDual.ajax.reload(null,false);
							dataTableNotLeadAssignment.ajax.reload(function(){
								$('#data-table-not-lead-assignment').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							dataTableAllDual.ajax.reload(function(){
								$('#data-table-all-dual-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							removeValidationErrors($this);
							mainSpinner.stop();
						}
						 
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			selectReceivedSoftDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/selectReceivedSoftDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
						    
						    dataTableAllDuplicateLeads.ajax.reload(null,false);
						    dataTableLeadAssignment.ajax.reload(null,false);
						    dataTableNotLeadAssignment.ajax.reload(null,false);
						    dataTableAllDual.ajax.reload(null,false);
						    dataTableAllReceivedLeads.ajax.reload(function(){
								$('#data-table-All-Received-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							
							
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			selectReceivedDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/selectReceivedDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
						    
						    dataTableAllDeleteLeads.ajax.reload(null,false);	
							
							
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			leaddelete:function(id){
			 
				mainSpinner.start();
				$.ajax({
					url:"/lead/leaddelete/"+id,
					type:"GET",
					success:function(response){
						 
						if(response.statusCode){
							 
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course deleted successfully');
							dataTableAllDual.ajax.reload(null,false);
							 
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							dataTableAllDual.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			
			
		};
	})();
// COURSE CONTROLLER
// *****************

//facebook duplicate assign start
	var fbleadmanagmentController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/course",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course added successfully');
							$this.find('button[type="reset"]').click();
							dataTableCourse.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			delete:function(id){
				var args = {
					target:id,
					controller:"courseController",
					callback:"deleteCourse",
					yesLabel:"Yes",
					noLabel:"No",
					modal_title:"Confirmation",
					modal_body:"Are you sure you want to delete it ??"
				}
				confirmationBox.open(args);
				return false;
			},
			deleteCourse:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/course/delete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course deleted successfully');
							//dataTableCourse.ajax.reload(null,false);
							 window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			
			duplicatecourseassignment:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/lead/duplicatecourseassignment",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course added successfully');
							$this.find('button[type="reset"]').click();
							//datatableAssignCourse.ajax.reload(null,false);
							window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			courseassignment:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/lead/courseassignmentadd",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course added successfully');
							$this.find('button[type="reset"]').click();
							//datatableAssignCourse.ajax.reload(null,false);
							window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			saveleadcount:function(THIS){			 
				 
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/lead/saveleadcount",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
						 alert('Updated successfully');
							window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not added');
					}
				});
				return false;
			},
			assigncoursedelete:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/assigncoursedelete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('deleted successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html(' deleted successfully');
							//dataTableCourse.ajax.reload(null,false);
							 window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			
			assigncoursestatus:function(id,val){
				mainSpinner.start();
				$.ajax({
					url:"/course/assignment/assigncoursestatus/"+id+"/"+val,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('Status successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Status successfully');
							//dataTableCourse.ajax.reload(null,false);
							 window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Assign Course not Status!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Assign not Update');
					}
				});
				return false;
			},
			
				statususer:function(id,val){
				mainSpinner.start();
				$.ajax({
					url:"/lead/statususer/"+id+"/"+val,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('Status successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Status successfully');
							dataTableCunsloor.ajax.reload(null,false);
							 
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Assign Course not Status!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Assign not Update');
					}
				});
				return false;
			},
			coursepdfstatus:function(id,val){
				 
				mainSpinner.start();
				$.ajax({
					url:"/coursepdf/coursepdfstatus/"+id+"/"+val,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('Status successfully');
						 window.location = window.location.href;
							dataTableallcoursecontentpdf.ajax.reload(null,false);
							 
						}else{
							mainSpinner.stop();
								alert('Not Status successfully');							 					
							dataTableallcoursecontentpdf.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Assign not Update');
					}
				});
				return false;
			},
				
			getCourseWiseCountlead:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/getCourseWiseCountlead/"+id,
					type:"GET",
					success:function(response){
						 
						$('#viewCourseWiseLead .modal-body').html(response.html);
						datatableCourseLead = $('#datatable-course-lead').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":true,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/lead/getCourseLead/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									
								},
								dataSrc:function(json){
								recordCollection = json.recordCollection;
								return json.data;
								}
							}
						}).api();
						
						$('#viewCourseWiseLead').modal({keyboard:false,backdrop:'static'});
						$('#viewCourseWiseLead .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			
			
			leadAssignForm:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/fblead/lead-assign-form/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						 				 
						select2_course();
						select2_assignuser();	
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadmanagmentController.leadAssignForm('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadmanagmentController.leadAssignForm('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadmanagmentController.leadAssignForm('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadmanagmentController.leadAssignForm('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storeLeadAssign:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/fblead/store-lead-assign/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							$this.find('*[name="owner"]').val('');
							alert('Created successfully');
							dataTableNotLeadAssignment.ajax.reload(null,false);	
							dataTableAllDual.ajax.reload(null,false);
							dataTableNotLeadAssignment.ajax.reload(function(){
								$('#data-table-not-lead-assignment').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							dataTableAllDual.ajax.reload(function(){
								$('#data-table-all-dual-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							removeValidationErrors($this);
							mainSpinner.stop();
						}
						 
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			selectReceivedSoftDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/selectReceivedSoftDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
						    
						    dataTableAllDuplicateLeads.ajax.reload(null,false);
						    dataTableLeadAssignment.ajax.reload(null,false);
						    dataTableNotLeadAssignment.ajax.reload(null,false);
						    dataTableAllDual.ajax.reload(null,false);
						    dataTableAllReceivedLeads.ajax.reload(function(){
								$('#data-table-All-Received-Leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							
							
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			selectReceivedDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/selectReceivedDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
						    
						    dataTableAllDeleteLeads.ajax.reload(null,false);	
							
							
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			leaddelete:function(id){
			 
				mainSpinner.start();
				$.ajax({
					url:"/lead/leaddelete/"+id,
					type:"GET",
					success:function(response){
						 
						if(response.statusCode){
							 
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Course deleted successfully');
							dataTableAllDual.ajax.reload(null,false);
							 
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							dataTableAllDual.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			
			
		};
	})();
	//facebook dulicate assign end


var absentAssignController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/absent/absentAssignCourse",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Absent Course added successfully');
							$this.find('button[type="reset"]').click();
					    	dataTableAbsentAssignView.ajax.reload(null,false);
							location.reload();
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Absent Course not added');
					}
				});
				return false;
			},
			absentassigncoursedelete:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/absent/absentassigncoursedelete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('deleted successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html(' deleted successfully');
							dataTableAbsentAssignView.ajax.reload(null,false);
							// window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			 
			 
			
			
		};
	})();
	
	
		
// *****************
// COURSE CONTROLLER
	var bucketAssignController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				$('#destination option').prop('selected',true);
				$('#intdestination option').prop('selected',true);
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/bucket/save-bucketAssignCourse",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Absent Course added successfully');
							$this.find('button[type="reset"]').click();
							dataTableBucketAssign.ajax.reload(null,false);
							location.reload();
							
							
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Absent Course not added');
					}
				});
				return false;
			},
			 
			 		 
			bucketassigncoursedelete:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/bucket/bucketassigncoursedelete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							alert('deleted successfully');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html(' deleted successfully');
							dataTableBucketAssign.ajax.reload(null,false);
							// window.location = window.location.href;
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Course not deleted!');							
							//dataTableCourse.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Course not deleted');
					}
				});
				return false;
			},
			 
			 
			
			
		};
	})();
	


// ******************
// MESSAGE CONTROLLER
	var messageController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/message",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Message Template Created Successfully');
							$this.find('button[type="reset"]').click();
							dataTableMessage.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Message template not added');
					}
				});
				return false;
			},
			delete:function(id){
				var args = {
					target:id,
					controller:"messageController",
					callback:"deleteMessage",
					yesLabel:"Yes",
					noLabel:"No",
					modal_title:"Confirmation",
					modal_body:"Are you sure you want to delete it ??"
				}
				confirmationBox.open(args);
				return false;
			},
			deleteMessage:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/message/delete/"+id,
					type:"GET",
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Message template deleted successfully');
							dataTableMessage.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
							dataTableMessage.ajax.reload(null,false);
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Message template not deleted');
					}
				});
				return false;
			}
		};
	})();
// MESSAGE CONTROLLER
// ******************

// *****************
// STATUS CONTROLLER
	var statusController = (function(){
		return {
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
				$.ajax({
					url:"/status",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Status added successfully');
							$this.find('button[type="reset"]').click();
							dataTableStatus.ajax.reload(null,false);
						}else{
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(response){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Status not added');
					}
				});
				return false;
			},
			delete:function(id){
				if(confirm("Are you sure ??")){
					mainSpinner.start();
					$.ajax({
						url:"/status/delete/"+id,
						type:"GET",
						success:function(response){
							if(response.status){
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-success').removeClass('hide').html('Status deleted successfully');
								dataTableStatus.ajax.reload(null,false);
							}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html(response.errors);							
								dataTableStatus.ajax.reload(null,false);
							}
						},
						error:function(response){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Status not deleted');
						}
					});
				}
				return false;
			}
		};
	})();
// STATUS CONTROLLER
// *****************

// DOCUMENT READY
// **************
$(document).ready(function(){
    
	$('.expdf')
	.daterangepicker({
		singleDatePicker:true,
		autoUpdateInput:false,
		locale:{format:'DD-MM-YYYY'},
		singleClasses:"picker_2"
	},function(start,end,label){$('.expdf').val(start.format('DD-MM-YYYY'));})
	.on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY'));
	});
	$('.expdt')
	.daterangepicker({
		singleDatePicker:true,
		autoUpdateInput:false,
		locale:{format:'DD-MM-YYYY'},
		singleClasses:"picker_2"
	},function(start,end,label){$('.expdt').val(start.format('DD-MM-YYYY'));})
	.on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY'));
	});
	$('.calldf')
	.daterangepicker({
		singleDatePicker:true,
		autoUpdateInput:false,
		locale:{format:'DD-MM-YYYY'},
		singleClasses:"picker_2"
	},function(start,end,label){$('.calldf').val(start.format('DD-MM-YYYY'));})
	.on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY'));
	});
	$('.calldt')
	.daterangepicker({
		singleDatePicker:true,
		autoUpdateInput:false,
		locale:{format:'DD-MM-YYYY'},
		singleClasses:"picker_2"
	},function(start,end,label){$('.calldt').val(start.format('DD-MM-YYYY'));})
	.on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY'));
	});
	$('.fromDate')
	.daterangepicker({
		singleDatePicker:true,
		autoUpdateInput:false,
		locale:{format:'YYYY-MM-DD'},
		singleClasses:"picker_2"
	},function(start,end,label){$('.fromDate').val(start.format('YYYY-MM-DD'));})
	.on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('YYYY-MM-DD'));
	});
	$('.toDate')
	.daterangepicker({
		singleDatePicker:true,
		autoUpdateInput:false,
		locale:{format:'YYYY-MM-DD'},
		singleClasses:"picker_2"
	},function(start,end,label){$('.toDate').val(start.format('YYYY-MM-DD'));})
	.on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('YYYY-MM-DD'));
	});
	$('.leaddf')
	.daterangepicker({
		singleDatePicker:true,
		autoUpdateInput:false,
		locale:{format:'DD-MM-YYYY'},
		singleClasses:"picker_2"
	},function(start,end,label){$('.leaddf').val(start.format('DD-MM-YYYY'));})
	.on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY'));
	});
	$('.leaddt').daterangepicker({
		singleDatePicker:true,
		autoUpdateInput:false,
		locale:{format:'DD-MM-YYYY'},
		singleClasses:"picker_2"
	},function(start,end,label){$('.leaddt').val(start.format('DD-MM-YYYY'));})
	.on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('DD-MM-YYYY'));
	});
	$(document).on('change','.counsellor-control',function(){
		dataTablePendingLeadsDemos.ajax.reload(null,false);
	});
	 $(document).on('change','.counsellor-control',function(){
		dataTablePendingLeads.ajax.reload(null,false);
	});
	 
/*	 
	 
 $('#start_date_time').daterangepicker({
		singleDatePicker: true,
		autoUpdateInput: false,
		timePicker: true,
	//	minDate: new Date(),
		locale:{format: 'h:mm A'},
		singleClasses: "picker_2"
	},function(start,end,label){$('#start_date_time').val(start.format('h:mm A'));}); */
	
 
	
	
 
	// ******************************
	// MESSAGE AND DEMO OWNER CONTROL
		$(document).on('change','.sms-control',function(e){
			e.preventDefault();
			$this = $(this);
			if($this.val()=='') return;
			mainSpinner.start();
			
			// ************
			// MESSAGE AJAX
				$.ajax({
					"url":"/message/getmessageslist/"+$this.val(),
					"type":"GET",
					"success":function(data,textStatus,jqXHR){
						//alert(JSON.stringify(data));
						if(data.length>0){
							var html = '<option value="">-- SELECT MESSAGE --</option>';
							html += '<option value="no">-- NO MESSAGE --</option>';
							for(var i in data){
								html+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
							}
							$('.sms-control-target').html(html);
						}
						//mainSpinner.stop();
					}
				});
			// MESSAGE AJAX
			// ************

			// ***************
			// DEMO OWNER AJAX
				$.ajax({
					"url":"/course/getcoursecounsellors/"+$this.val(),
					"type":"GET",
					"success":function(data,textStatus,jqXHR){
						//alert(JSON.stringify(data));
						if(data.length>0){
							if($('.demo_owner').length){
								$('.demo_owner').html(data);
								$('.demo_owner option[value='+demo_owner+']').prop('selected', true);
							}
						}
						mainSpinner.stop();
					}
				});
			// DEMO OWNER AJAX
			// ***************
			mainSpinner.stop();
		});
	// MESSAGE AND DEMO OWNER CONTROL
	// ******************************
	
	// ***********
	// VERIFY DEMO
		$(document).on("click",".verify-demo",function(e){
			e.preventDefault();
			verifyDemo();
		});
	// VERIFY DEMO
	// ***********
	// ***********
	// leadjoinded
		$(document).on("click",".leadjoinded",function(e){
			e.preventDefault();
			 
			leadjoinded();
			
		});
		
		$(document).on("click",".searchLeadAndDemo",function(e){
			e.preventDefault();
			 
			searchLeadAndDemo();
			
		});
		
		
		 $(document).on("click",".paidfees",function(e){
			e.preventDefault();
			  
			searchPaidfees();
			
		});
	// ***************************
	// COURSE SELECTION AJAX BASED
	/*	 $(".select2_trainer").select2({
			theme: "bootstrap",
			placeholder: "-- SELECT TRAINER --",
			maximumSelectionSize: 6,
			containerCssClass: ":all:",
			ajax: {
				url: "/trainer/get_fees_trainer_ajax",
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term
					}
				},
				processResults: function(data) {
					return {
						results: $.map(data, function(obj) {
							return {
								//id: obj.id,
								id: obj.name,
								text: obj.name
							};
						})
					}
				},
				cache: true
			}
		}); 
		
		*/
		select2_course();
		select2_trainer();
		select2_user();
        $('.select2_select').select2();
        $('.select_trainerb').select2();
        $('.select_trainer').select2();
        $('.select_courseb').select2();
			
		select2_assignuser();
	// COURSE SELECTION AJAX BASED
	// ***************************
	
	// *********
	// DEMO TYPE
		$(document).on('change','*[name="exec_call"]',function(e){
			var $this = $(this);
			if($this.val()=='no'){
				$('#demo_type').closest('.form-group').removeClass('hide');
			}else{
				$('#demo_type').closest('.form-group').addClass('hide');
			}
		});
	// DEMO TYPE
	// ********* 

    // **************************************************************************************
	// DISABLING EXPECTED DATE AND TIME WHEN SELECTED STATUS IS "NOT INTERESTED OR LOC ISSUE"
		$(document).on('change','*[name="status"]',function(e){
			var $this = $(this);
			//if($this.find("option:selected").text()=="Not Interested"||$this.find("option:selected").text()=="Location Issue"){
			if(!$this.find("option:selected").data('value')){
				$('*[name="expected_date_time"]').prop({'disabled':true}).val('');
			}else{
				$('*[name="expected_date_time"]').prop({'disabled':false});
			}
		});
	// DISABLING EXPECTED DATE AND TIME WHEN SELECTED STATUS IS "NOT INTERESTED OR LOC ISSUE"
	// **************************************************************************************
	

	
	// **********
	// FORM RESET
		$(document).on('click','*[type="reset"]',function(){
			$(this).closest('form').find('select').val('').trigger('change');
		});
	// FORM RESET
	// **********
//	$('.manager').hide();
	// *************************************
	// POPULATING CAPABILITIES BASED ON ROLE
		$(document).on('change','*[name="role"]',function(){
			var val = $(this).val();
			if(val=='')
				return;
			if(val =='user' || val =='TL' || val =='manager'){
				$('.manager').show();
			}else{
				$('.manager').hide();
			}
			mainSpinner.start();
			$.ajax({
				url:'/role-permission/'+val,
				type:"GET",
				dataType:'json',
				success:function(data,textStatus,jqXHR){
					if(data.status){
						$('#capabilities').html(data.html);
						$('#capabilities').trigger('chosen:updated');
					}
					mainSpinner.stop();
				},
				error:function(jqXHR, textStatus, errorThrown){}
			});
		});
	// POPULATING CAPABILITIES BASED ON ROLE
	// *************************************
	
	// ********
	// PICKLIST
		$('#btnRight').click(function (e) {
		  $('select').moveToListAndDelete('#source', '#destination');
		  e.preventDefault();
		});
		$('#btnAllRight').click(function (e) {
		  $('select').moveAllToListAndDelete('#source', '#destination');
		  e.preventDefault();
		});
		$('#btnLeft').click(function (e) {
		  $('select').moveToListAndDelete('#destination', '#source');
		  e.preventDefault();
		});
		$('#btnAllLeft').click(function (e) {
		  $('select').moveAllToListAndDelete('#destination', '#source');
		  e.preventDefault();
		});
	// PICKLIST
	// ********
	
	$('#intbtnRight').click(function (e) {
		  $('select').moveToListAndDelete('#intsource', '#intdestination');
		  e.preventDefault();
		});
		$('#intbtnAllRight').click(function (e) {
		  $('select').moveAllToListAndDelete('#intsource', '#intdestination');
		  e.preventDefault();
		});
		$('#intbtnLeft').click(function (e) {
		  $('select').moveToListAndDelete('#intdestination', '#intsource');
		  e.preventDefault();
		});
		$('#intbtnAllLeft').click(function (e) {
		  $('select').moveAllToListAndDelete('#intdestination', '#intsource');
		  e.preventDefault();
		});
	
	// ****************************
	// ON SUBMIT UPDATE COURSE FORM
		$('#update-course').submit(function(){
			$('#destination option').prop('selected',true);
			$('#intdestination option').prop('selected',true);
			return true;
		});
	// ON SUBMIT UPDATE COURSE FORM
	// ****************************
	
	// ****************************
	// ON SUBMIT UPDATE ROLE PERMISSION FORM
		$('#role-form2').submit(function(){
			$('#destination option').prop('selected',true);
			return true;
		});
	// ON SUBMIT UPDATE COURSE FORM
	// ****************************
	
	
	$(document).on('change','.get_coursellor_course',function(e){
				var cid = $('.get_coursellor_course').val();
				$.ajax({
					"url":"/genie/get_coursellor_course",
					"type":"GET",
					"data": {	'cid': cid	},
					"success":function(data,textStatus,jqXHR){					 
						if(data.length>0){							 
						//	$('.show-coursellor-table').replaceWith(data);
							$('.show-coursellor-table').html(data);
						}else{
					$('.show-coursellor-table').html(data);
						}							
					}
				});	
				
				$.ajax({
				"url":"/genie/get_international_course",
				"type":"GET",
				"data": {	'cid': cid	},
				"success":function(datas,textStatus,jqXHR){					 
				if(datas.length>0){							 
				 
				$('.show-internation-coursellor-table').html(datas);
				}else{
				$('.show-internation-coursellor-table').html(datas);
				}							
				}
				});	
				
		});
	
	
	$(document).on('change','#categorytype',function(){
			var val = $(this).val();
			if(val=='')
				return;
			 
			mainSpinner.start();
			$.ajax({
				url:'/coursepdf/get-category-ajax/'+val,
				type:"GET",
				dataType:'json',
				success:function(data,textStatus,jqXHR){
					if(data.status){
						$('.categorylist').html(data.html);
						$('.categorylist').trigger('chosen:updated');
					}
					mainSpinner.stop();
				},
				error:function(jqXHR, textStatus, errorThrown){}
			});
		});
		
		$(document).on('change','.categorylist',function(){
			var val = $(this).val();
			if(val=='')
				return;
			 
			mainSpinner.start();
			$.ajax({
				url:'/coursepdf/get-subcategory-ajax/'+val,
				type:"GET",
				dataType:'json',
				success:function(data,textStatus,jqXHR){
					if(data.status){
						$('.subcategorylist').html(data.html);
						$('.subcategorylist').trigger('chosen:updated');
					}
					mainSpinner.stop();
				},
				error:function(jqXHR, textStatus, errorThrown){}
			});
		});
		
		
		$(document).on('change','.select2_assincounsellor',function(){
			var val = $(this).val();
			if(val=='')
				return;			 
			mainSpinner.start();
			$.ajax({
				url:'/absent/get-assign-domestic-course-ajax/'+val,
				type:"GET",
				dataType:'json',
				success:function(data,textStatus,jqXHR){
					if(data.status){
						$('.sow_absent_domestice').html(data.html);
						$('.sow_absent_international').html(data.sourceIntCourses);
						$('.sow_absent_domestice').trigger('chosen:updated');
					}
					mainSpinner.stop();
				},
				error:function(jqXHR, textStatus, errorThrown){}
			});
		});
		
		
		$(document).on('change','.select2_bucketcounsellor',function(){
			var val = $(this).val();
			if(val=='')
				return;			 
			mainSpinner.start();
			$.ajax({
				url:'/bucket/get-bucket-domestic-course-ajax/'+val,
				type:"GET",
				dataType:'json',
				success:function(data,textStatus,jqXHR){
					if(data.status){
						$('.sow_bucket_domestice').html(data.html);
						$('.sow_bucket_international').html(data.sourceIntCourses);
						$('.sow_bucket_domestice').trigger('chosen:updated');
					}
					mainSpinner.stop();
				},
				error:function(jqXHR, textStatus, errorThrown){}
			});
		});
		
		
	// *****************
	// SELECT2 ON STATUS
		$(".select2_status").select2({
			theme: "bootstrap",
			placeholder: "-- SELECT STATUS --",
			allowClear: true,
			maximumSelectionSize: 6,
			containerCssClass: ":all:",
			width: 'resolve',
		});
	// SELECT2 ON STATUS
	// *****************
	
	 $('#count_code').select2();
});
// **************
// DOCUMENT READY

// *********
// FUNCTIONS
	function select2_course(){
		$(".select2_course").select2({
			theme: "bootstrap",
			placeholder: "-- SELECT TECHNOLOGY --",
			allowClear: true,
			maximumSelectionSize: 6,
			containerCssClass: ":all:",
			width: 'resolve',
			ajax: {
				url: "/course/get_c_ajax",
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term
					}
				},
				processResults: function(data) {
					return {
						results: $.map(data, function(obj) {
							return {
								id: obj.id,
								text: obj.name
							};
						})
					}
				},
				cache: true
			}
		});
	}
	
	
	 function select2_trainer(){
	  $(".select2_trainer").select2({
			theme: "bootstrap",
			placeholder: "-- SELECT TRAINER --",
			maximumSelectionSize: 6,
			containerCssClass: ":all:",
			ajax: {
				url: "/trainer/get_fees_trainer_ajax",
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term
					}
				},
				processResults: function(data) {
					return {
						results: $.map(data, function(obj) {
							return {
								id: obj.name,
								text: obj.name
							};
						})
					}
				},
				cache: true
			}
		}); 
	 }
	 
	 
	 function select2_user(){
	  $(".select2_user").select2({
			theme: "bootstrap",
			placeholder: "-- SELECT USER --",
			maximumSelectionSize: 6,
			containerCssClass: ":all:",
			ajax: {
				url: "/lead/get_user_ajax",
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term
					}
				},
				processResults: function(data) {
					return {
						results: $.map(data, function(obj) {
							return {
								id: obj.name,
								text: obj.name
							};
						})
					}
				},
				cache: true
			}
		}); 
	 }
 
 
  function select2_assignuser(){
	  $(".select2_assignuser").select2({
			theme: "bootstrap",
			placeholder: "-- SELECT USER --",
			maximumSelectionSize: 6,
			containerCssClass: ":all:",
			ajax: {
				url: "/lead/get_user_ajax",
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term
					}
				},
				processResults: function(data) {
					return {
						results: $.map(data, function(obj) {
							return {
								id: obj.id,
								text: obj.name
							};
						})
					}
				},
				cache: true
			}
		}); 
	 }
	// *********
// FUNCTIONS
 	function submitChating(){
		var chating_id = $('#chating_id').val();
		 var chating = $('#chating').val();	 	
		 var dataString = 'chating_id='+ chating_id + '&chating=' + chating;
		$.ajax({
           type: "POST",
           url: "/dashboard/chating",
           data:dataString,  
           success: function(response)
           { 
	 	  	$('#chatTempory').append(response.html);
			$('#chating').val('');	
			
           }
		   
         });
		return false;
		 
	} 
	
	
function hiringstatus(id,val){
	 
	  	//alert(id);
		 var dataString = 'id='+ id + '&val=' + val;
		 
		$.ajax({
           type: "POST",
           url: "/hiring/status/"+id+"/"+val,
          data:dataString,  
		//  dataType: 'json',
           success: function(response)
           { 
		  // alert(response.statusCode);
		   
		   if(response.statusCode){
	 	  	$('#success').text(response.data.message);
			 
			dataTableHiring.ajax.reload(null,false);
		   }else{
			   
			   $('#failed').text(response.message);
		   }
           }
		   
         });
	 
	 
	 
}

	/* 
	$('#btn-chat').click(function(){	
		mainSpinner.start();	
		 var chating_id = $('#chating_id').val();
		 var chating = $('#chating').val();		
		 var dataString = 'chating_id='+ chating_id + '&chating=' + chating;
		$.ajax({
           type: "POST",
           url: "/dashboard/chating",
           data:dataString,  
           success: function(data,textStatus,jqXHR)
           {
              
			   dataTableChating.ajax.reload(null,false);
			 
           }
		   
         });
		 return false;
	});
	 */
 	
	
	$('.download-curriculum').click(function(){	
		var THIS = jQuery(this);
		var pdfname   = THIS.data('stud_id');	
		 var dataString = 'coursename='+ pdfname;
		 
		  var url="https://leads.cromacampus.com/upload/"+pdfname;
            window.open(url,'_blank');  
		// alert(pdfname);
		$.ajax({
           type: "POST",
           url: "/coursepdf/download",
           data:dataString,  
           success: function(data,textStatus,jqXHR)
           {
              
			  // dataTableChating.ajax.reload(null,false);
			 
           }
		   
         });
		 return false;
	});
	
	
	$('.local').click(function(){	
		 $('.technology').show();
		  $('#showPermission').hide();
		 
	});

	$('.global').click(function(){	
		 $('.technology').hide();
		$('#showPermission').show();
		
	});
	 
		if($('.global').is(":checked")) {  
        $(".technology").hide();
		} else{
        $(".technology").show();
		}
	
	if($('.local').is(":checked"))  { 
        $("#showPermission").hide();
	} else{
        $("#showPermission").show();	
	}




	
		$('.pendingfees').hide();
		$('.feespay').hide();
		$('.additionalpay').hide();
		jQuery('#fees_type').change(function(){
		var mode = jQuery(this).val();
		switch(mode){	  
		case 'newfees':		 
		$('.pendingfees').show();
		$('.feespay').show();
		$('.additionalpay').hide();
		
		break;
		case 'newfeesExperience':		 
		$('.pendingfees').show();
		$('.feespay').hide();
		$('.additionalpay').show();
		break;
		case 'newfeesCertificate':		 
		$('.pendingfees').show();
		$('.feespay').hide();
		$('.additionalpay').show();
		break;	
		case 'pendingfeesExperience':		 
		$('.pendingfees').hide();
		$('.feespay').hide();
		$('.additionalpay').show();
		break;
		case 'pendingfeesCertificate':		 
		$('.pendingfees').hide();
		$('.feespay').hide();
		$('.additionalpay').show();
		break;
		case 'pendingfees':		 
		$('.pendingfees').hide();
		$('.feespay').show();
		$('.additionalpay').hide();
		break;			
		}
		});
		
		function isNumberKey(e){
		var keyCode = e.keyCode || e.charCode;
		if(keyCode>=48&&keyCode<=57)
		return true;
		else
		return false;
		}
	
	function filterAjaxUpcomingData(tableID,THIS){
		 
		var res = tableID.split("-");
		res = res.map(function($el){
			return $el.charAt(0).toUpperCase() + $el.slice(1);
		});
		res.shift();
		tableID = res.join('');
		tableID = "dataTable"+tableID;
		window[tableID].ajax.reload(null,false);
		//alert(test); 
		//dataTablePendingLeadsDemos.ajax.reload(null,false);
		return false;
	}
		
	function filterAjaxLeadData(tableID,THIS){
		 
		var res = tableID.split("-");
		res = res.map(function($el){
			return $el.charAt(0).toUpperCase() + $el.slice(1);
		});
		res.shift();
		tableID = res.join('');
		tableID = "dataTable"+tableID;
		window[tableID].ajax.reload(null,false);
	//	alert(test); 
		//dataTablePendingLeadsDemos.ajax.reload(null,false);
		return false;
	}
	
	function filterAjaxDemoData(tableID,THIS){
		 
		var res = tableID.split("-");
		res = res.map(function($el){
			return $el.charAt(0).toUpperCase() + $el.slice(1);
		});
		res.shift();
		tableID = res.join('');
		tableID = "dataTable"+tableID;
		window[tableID].ajax.reload(null,false);
		dataTablePendingLeadsDemos.ajax.reload(null,false);
		return false;
	}
// FUNCTIONS
// *********


		$('.employee').hide();
		$('.trainer').hide();		 
		jQuery('#hiring_type').change(function(){
		var type = jQuery(this).val();
		if(type=='Employee'){
		$('.employee').show();
		$('.trainer').hide();
		}else if(type=='Trainer'){
		 
		$('.trainer').show();
		$('.employee').hide();

		} 
		});
	var share = jQuery('#hiring_type option:selected').val();
	switch(share){	  
		case 'Employee':
			jQuery('.trainer').hide();
			jQuery('.employee').show();
			break;

		case 'Trainer':
			jQuery('.employee').hide();
			jQuery('.trainer').show();
			break;			 
		 
	}
	 
	 function ConfirmDelete()
{

if (confirm("Are you sure you want to delete?"))
return true;
else
return false;
}



function searchid(a){
	$('.submit-btns').prop("disabled",true);
	if(a.length >1)
	{ 		
		$('.loader').show();	
		$.ajax({
			url:"courses/ajax_view",
			type:'post',
			data:{id:a},
			success:function(data){
			
			 
				$('.cltab').html(data);			
				 
				$('.loader').hide();
				 
					
			}
		});
	}else{
		$('.result').hide();
	}
} 


function copyToClipboard(element) {        
var $temp = $("<input>");
$("body").append($temp);
$temp.val(element).select();
document.execCommand("copy");
$temp.remove();

$("#success").modal();
setTimeout(function(){        
$('#success').modal("hide");

}, 3000);        

}

   // document.cookie = "humans_21909=1"; document.location.reload(true)


//$(".side-menu").mouseover(function(){$(this).css("overflow-y","scroll");});
//$(".side-menu").mouseout(function(){$(this).css("overflow-y","hidden");});




//for facbook start
var leadFBController = (function(){
		return {
			checked_Ids:[],
			submit:function(THIS){
				mainSpinner.start();
				var $this = $(THIS),
					data  = $this.serialize();
					//alert(data);
				$.ajax({
					url:"/lead/fbadd-lead",
					type:"POST",
					data:data,
					success:function(response){
						if(response.status){
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Lead added successfully');
							$this.find('button[type="reset"]').click();
							$this.find('[name="course"]').html('').val('').trigger('change');
						}else{
							mainSpinner.stop();
							$('body').scrollTop('0px');
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html(response.errors);							
						}
					},
					error:function(){
						mainSpinner.stop();
						$('.alert').addClass('hide');
						$('.alert-danger').removeClass('hide').html('Lead not added');
					}
				});
				return false;
			},
			delete:function(id){
				if(confirm("Are you sure ??")){
					mainSpinner.start();
					$.ajax({
						url:"/lead/fbdelete/"+id,
						type:"GET",
						success:function(response){
							if(response.status){
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-success').removeClass('hide').html('Lead deleted softly and successfully');
								dataTableLeadNotInterested.ajax.reload(null,false);
								dataTableLead.ajax.reload(function(){
									$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
								},false);
							}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html(response.errors);							
								dataTableLead.ajax.reload(function(){
									$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
								},false);
							}
						},
						error:function(response){
							mainSpinner.stop();
							$('.alert').addClass('hide');
							$('.alert-danger').removeClass('hide').html('Lead not deleted');
						}
					});
				}
				return false;
			},
			getfollowUps:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/lead/fbfollow-up/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						$('#expected_date_time').daterangepicker({
							singleDatePicker: true,
							autoUpdateInput: false,
							timePicker: true,
							minDate: new Date(),
							//autoUpdateInput: false,
							locale: {
								format: 'DD-MMMM-YYYY h:mm A'
							},
							singleClasses: "picker_2"
						}/* , function(start, end, label) {
							alert(JSON.stringify(start));
							$('#expected_date_time').val(start.format('DD-MMMM-YYYY h:mm A'));
						} */);
						$('#expected_date_time').on('apply.daterangepicker', function(ev, picker) {
							$('#expected_date_time').val(picker.startDate.format('DD-MMMM-YYYY h:mm A'));
						});
						select2_course();
						dataTableFollowUps = $('#datatable-followups').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":false,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/lead/fbgetfollowups/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									d.count = $(".follow-up-count").val();
								}
							}
						}).api();
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadFBController.getfollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadFBController.getfollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadFBController.getfollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadFBController.getfollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();


					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				/* var dataTableFollowUps = $('#datatable-followups').dataTable({
					"fixedHeader": true,
					"processing":true,
					"serverSide":true,
					"paging":true,
					"ajax":{
						url:"/lead/getfollowups/",
						data:function(d){
							d.page = (d.start/d.length)+1;
						}
					}
				}).api(); */
			},
			storeFollowUp:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/lead/store-fbfollow-up/'+id,
					type:"post",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							//$this.find('*[name="status"]').val('');
							//$this.find('*[name="expected_date_time"]').val('');
							$this.find('*[name="remark"]').val('');
							alert('Follow Up created successfully');
							dataTableFollowUps.ajax.reload( null, false );
							dataTableExpectedLead.ajax.reload( null, false );
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							dataTablePendingLeads.ajax.reload(function(){
								$('#datatable-pending-leads').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							removeValidationErrors($this);
						}
						if(response.demo_created){
							$this.closest('.x_content').html("<p style='font-size: 24px;font-weight:700;padding-top:20px;text-align:center;'>Lead successfully moved to Demo...</p>");
						}
						mainSpinner.stop();
						if(response.pop=='show'){
							$('#followUpModal .modal-body').hide();
							$(".modal-dialog modal-lg").hide();
							$(".modal-content").hide();
							$("#followUpModal").hide();
							$(".modal-backdrop fade in").hide();
							$(".modal-backdrop").hide();

							// $("#followUpModal").hide();
							$("#popup").show();
						}
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			sendMail:function(id){
				mainSpinner.start();
				$.ajax({
					url:'/fblead/sendmail/'+id,
					type:"GET",
					dataType:'json',
					success:function(response){
						//console.log(response);
						if(response.status){
							alert(response.success.message);
							mainSpinner.stop();
						}
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						alert('Something went wrong');
						mainSpinner.stop();
					}
				});
				return false;
			},
			getAllFollowUps:function(){
				//mainSpinner.start();
				dataTableFollowUps.ajax.reload( null, false );
				return false;
			},
			bulkSms:function(){
				//var checked_Ids = [];
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					//alert($(this).val());
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    alert('Please select data for Bulk SMS!');
					return false;
				}
				$('#bulkSmsModal .alert').remove();
				$('#bulkSmsModal').modal({backdrop:"static",keyboard:false});
				return false;
			},
			sendBulkSms:function(){
				if($('#bulkSmsControl').val() == '')
					return false;
				var $this = this;
				$.ajax({
					url:"/lead/send-fbbulk-sms",
					type:"POST",
					dataType:"json",
					data:{
						message:$('#bulkSmsControl').val(),
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.status){
							alert(data.success.message);
							$('#bulkSmsModal .alert').remove();
							$('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							$('#bulkSmsControl').val('');
							setTimeout(function(){
								$('#bulkSmsModal').modal('hide');
							},2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;
			},
			moveNotInterested:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    	alert('Please select data to move Not Intereseted and Location Issue!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/move-not-interested",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							// $('#bulkSmsModal .alert').remove();
							// $('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							// $('#bulkSmsControl').val('');
							// setTimeout(function(){
								// $('#bulkSmsModal').modal('hide');
							// },2000);
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			moveToExptLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    	alert('Please select data to move To Expected lead!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/move-to-expected-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLead.ajax.reload( null, false );							
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
			moveToNewbatchLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    	alert('Please select data To Move To New Batch lead!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/lead/move-to-expected-new-batch-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedLead.ajax.reload(null,false);
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			moveToLeads:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select date to move to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLeadNotInterested.ajax.reload(null,false);
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			expectedMoveToLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to Expected move to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/expected-move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedLead.ajax.reload(null,false);
							dataTableExpectedLead.ajax.reload(function(){
								$('#datatable-expected-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},			
			expectedNewBatchMoveToLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to New batch Move to Lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/expected-new-batch-move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedNewBatchLead.ajax.reload(null,false);
							dataTableExpectedNewBatchLead.ajax.reload(function(){
								$('#datatable-expected-new-batch-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
			moveToExptLeadDemo:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
				    	alert('Please select data to move To Expected Demo!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/move-to-expected-lead-demo",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLead.ajax.reload( null, false );							
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},		
			 
			expectedDemoMoveToLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to Expected Demo move to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/expected-demo-move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedLeadDemo.ajax.reload(null,false);
							dataTableExpectedLeadDemo.ajax.reload(function(){
								$('#datatable-expected-lead-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},	
			deleteMoveToLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/delete-move-to-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableDeletedLead.ajax.reload(null,false);
							dataTableDeletedLead.ajax.reload(function(){
								$('#datatable-deleted-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},	
			
			expectedDemoMoveToDemoLead:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to Expected Demo move to lead !');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/expected-demo-move-to-demo-lead",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableExpectedLeadDemo.ajax.reload(null,false);
							dataTableExpectedLeadDemo.ajax.reload(function(){
								$('#datatable-expected-lead-demo').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
						 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},	
			selectDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/selectDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							// $('#bulkSmsModal .alert').remove();
							// $('#bulkSmsModal .modal-body').prepend("<div class='alert alert-success'>"+data.success.message+"</div>");
							// $('#bulkSmsControl').val('');
							// setTimeout(function(){
								// $('#bulkSmsModal').modal('hide');
							// },2000);

						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
				selectForwardDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/selectForwardDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
							dataTableLeadForward.ajax.reload(function(){
								$('#datatable-lead-forward').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
			selectMailerDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/selectMailerDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
					   
						if(data.statusCode=='1'){
						    dataTableMailData.ajax.reload(null,false);
						    dataTableMailData.ajax.reload(function(){
								$('#datatable-mailer_data').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			selectFeedbackDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/selectFeedbackDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
						    dataTableFeedback.ajax.reload(null,false);
							 
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			counsellorFeedbackDelete:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
		        	alert('Please select data to Delete!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/counsellorFeedbackDelete",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
						if(data.statusCode){
						    dataTableCounsellorFeedback.ajax.reload(null,false);
						 
							mainSpinner.stop();
							alert(data.data.message);
							 
						}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			selectToNewLeads:function(){
				var $this = this;
				$this.checked_Ids = [];
				$('.check-box:checked').each(function(){
					if(!(new String("on").valueOf() == $(this).val())){
						$this.checked_Ids.push($(this).val());
					}
				});
				if($this.checked_Ids.length == 0){
					alert('Please select data to New Leads!');
					return false;
				}
				mainSpinner.start();
				$.ajax({
					url:"/fblead/selectToNewLeads",
					type:"POST",
					dataType:"json",
					data:{
						ids:$this.checked_Ids
					},
					success:function(data,textStatus,jqXHR){
				    	if(data.statusCode){
							$('.alert').addClass('hide');
							$('.alert-success').removeClass('hide').html('Update New Leads successfully...');
							dataTableLead.ajax.reload(null,false);						 
							 						 
							mainSpinner.stop();
							alert(data.data.message);
							 
						}else{
								mainSpinner.stop();
								$('.alert').addClass('hide');
								$('.alert-danger').removeClass('hide').html('Not update New Leads successfully...');							
								dataTableLead.ajax.reload(null,false);
							}
					},
					error:function(jqXHR,textStatus,errorThrown){
						
					}
				});
				return false;				
			},
			
			getExpectFollowUps:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/fblead/expect-follow-up/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						$('#expected_date_time').daterangepicker({
							singleDatePicker: true,
							autoUpdateInput: false,
							timePicker: true,
							minDate: new Date(),
							//autoUpdateInput: false,
							locale: {
								format: 'DD-MMMM-YYYY h:mm A'
							},
							singleClasses: "picker_2"
						}/* , function(start, end, label) {
							alert(JSON.stringify(start));
							$('#expected_date_time').val(start.format('DD-MMMM-YYYY h:mm A'));
						} */);
						$('#expected_date_time').on('apply.daterangepicker', function(ev, picker) {
							$('#expected_date_time').val(picker.startDate.format('DD-MMMM-YYYY h:mm A'));
						});
						select2_course();
						select2_trainer();
						select2_user();
						dataTableExpectFollowUps = $('#datatable-expect-followups').dataTable({
							"fixedHeader": true,
							"processing":true,
							"serverSide":true,
							"paging":false,
							"ordering":false,
							"searching":false,
							"lengthChange":false,
							"info":false,
							"autoWidth":false,
							"ajax":{
								url:"/fblead/getexpectfollowups/"+id,
								data:function(d){
									d.page = (d.start/d.length)+1;
									d.columns = null;
									d.order = null;
									d.count = $(".follow-up-count").val();
								}
							}
						}).api();
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadFBController.getExpectFollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadFBController.getExpectFollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadFBController.getExpectFollowUps('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadFBController.getExpectFollowUps('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storeExpectFollowUp:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/fblead/store-expect-follow-up/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							//$this.find('*[name="status"]').val('');
							//$this.find('*[name="expected_date_time"]').val('');
							$this.find('*[name="remark"]').val('');
							alert('Meeting Up created successfully');
							dataTableExpectedLeadDemo.ajax.reload( null, false );							 
							dataTableExpectFollowUps.ajax.reload( null, false );							 
							dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);								 
							removeValidationErrors($this);
							mainSpinner.stop();
						}
						if(response.demo_created){
							$this.closest('.x_content').html("<p style='font-size: 24px;font-weight:700;padding-top:20px;text-align:center;'>Lead successfully moved to Demo...</p>");
						}
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			
			leadjoindededit:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/fblead/leadjoindededit/"+id,
					type:"GET",
					success:function(response){
						 
						$('#leadDemoJoined .modal-body').html(response.html);						 
						select2_course();
						select2_trainer();
						select2_user();
						 
						 
						 
						$('#leadDemoJoined').modal({keyboard:false,backdrop:'static'});
						$('#leadDemoJoined .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storeleadjoind:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/fblead/storeleadjoind/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							alert('Update successfully');
							 	$('.leadjoinded').click();					 
							 	 				 
						 						 
							removeValidationErrors($this);
							mainSpinner.stop();
						}
						 
						 
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			
			leadForwardForm:function(id){
				mainSpinner.start();
				$.ajax({
					url:"/fblead/lead-forward-form/"+id,
					type:"GET",
					success:function(response){
						 
						$('#followUpModal .modal-body').html(response.html);
						 				 
						select2_course();
						select2_assignuser();	
						var prevNextHtml = '';	 						
						for(var i=0;i<recordCollection.length;i++){
							if(recordCollection[i]==id && recordCollection.length != 1){
								if(i==0){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadFBController.leadForwardForm('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
								else if(i==(recordCollection.length-1)){
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadFBController.leadForwardForm('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a>';
								}
								else{
									prevNextHtml += '<a style="background:#2A3F54;color:#fff;" href="javascript:leadFBController.leadForwardForm('+recordCollection[i-1]+')" class="btn" title="followUp"><< Previous</a><a style="background:#2A3F54;color:#fff;padding:6px 25px;" href="javascript:leadFBController.leadForwardForm('+recordCollection[i+1]+')" class="btn" title="followUp">Next >></a>';
								}
							}
						}
						$('#followUpModal .modal-title').html(prevNextHtml);
						$('#followUpModal').modal({keyboard:false,backdrop:'static'});
						$('#followUpModal .select2-container').css({'width':'100%'});
						mainSpinner.stop();
					},
					error:function(response){
						mainSpinner.stop();
					}
				});
				 
			},
			storeLeadForward:function(id,THIS){
				mainSpinner.start();
				var $this = $(THIS);
				$.ajax({
					url:'/fblead/store-lead-forward/'+id,
					type:"GET",
					data:$this.serialize(),
					dataType:'json',
					success:function(response){
						if(response.status){
							$this.find('*[name="owner"]').val('');
							alert('Forward successfully');
						dataTablePendingLeads.ajax.reload( null, false );
						dataTableLead.ajax.reload(function(){
								$('#datatable-lead').find('[data-toggle="popover"]').popover({html:true,container:'body'});
							},false);								 
							removeValidationErrors($this);
							mainSpinner.stop();
						}
						 
						mainSpinner.stop();
					},
					error:function(jqXHR, textStatus, errorThrown){
						var response = JSON.parse(jqXHR.responseText);
						if(response.status){
							showValidationErrors($this,response.errors);
						}else{
							alert('Something went wrong');
						}
						mainSpinner.stop();
					}
				});
				return false;
			},
			
			
			
			
		};
	})();
//for facbook end	
//for facbook end


$(".cancel").click(function(){
	window.location.href="/dashboard/facbook-lead";
});




















//facbook unsigned
var dataTablecCunsloorView = $('#data-table-counsloor-view').on('draw.dt',function(e,settings){
	$('#data-table-counsloor-view').find('[data-toggle="popover"]').popover({html:true,container:'body'});
	})
.dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,	 
	"ajax":{
		url:"/facbooklead/get-counsellor-view",
		data:function(d){
			d.page = (d.start/d.length)+1; 
			 
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			demoRecordCollection = json.demoRecordCollection;
			return json.data;
		}
	}
}).api();

//Upcoming Batches 
// var dataTableUpcomingBatches = $('#datatable-upcoming-batches').on('draw.dt',function(e,settings){
// 	$('#datatable-upcoming-batches').find('[data-toggle="popover"]').popover({html:true,container:'body'});
// 	})
// .dataTable({
// 	"fixedHeader": true,
// 	"processing":true,
// 	"serverSide":true,
// 	"paging":true,
// 	"ordering":false,	 
// 	"ajax":{
// 		url:"/dashboard/counsellor/upcoming-batches/getbatches",
// 		data:function(d){
// 			d.page = (d.start/d.length)+1; 
// 			d.search['course']=$('*[name="search[course]"]').val();			  
// 			d.search['trainer']=$('*[name="search[trainer]"]').val(); 
// 			d.columns = null;
// 			d.order = null;
// 		},
// 		dataSrc:function(json){
// 			demoRecordCollection = json.demoRecordCollection;
// 			return json.data;
// 		}
// 	}
// }).api();



var dataTableNotLeadAssignment= $('#facbook-data-table-not-lead-assignment').on('draw.dt',function(e,settings){
	$('#facbook-data-table-not-lead-assignment').find('[data-toggle="popover"]').popover({html:true,container:'body'});
}).dataTable({
	"fixedHeader": true,
	"processing":true,
	"serverSide":true,
	"paging":true,
	"ordering":false,
	"columnDefs":[
		{
			orderable:false,
			targets:[0]
		}
	],
	"lengthMenu": [
            [10,25,50,100,250,500],
            ['10','25','50','100','250','500']
        ],
	"fnInitComplete":function(){
		$('#facbook-data-table-not-lead-assignment').find('[data-toggle="popover"]').popover({html:true,container:'body'});
		$('#facbook-data-table-not-lead-assignment').find('#check-all').on('change',function(){
			if(this.checked){
				$('.check-box').prop('checked',true);
			}else{
				$('.check-box').prop('checked',false);
			}
		});
	},
	"ajax":{
			url:"/facbooklead/get-all-not-lead-assignment",		 
		data:function(d){
			$('#check-all').prop('checked',false);
			d.page = (d.start/d.length)+1;
			d.search['source']=$('*[name="search[source]"]').val();		 
			d.search['leaddf']=$('*[name="search[leaddf]"]').val();
			d.search['leaddt']=$('*[name="search[leaddt]"]').val();				 
			d.search['course']=$('*[name="search[course][]"]').val();			 
			d.search['user']=$('*[name="search[user]"]').val();
			d.columns = null;
			d.order = null;
		},
		dataSrc:function(json){
			recordCollection = json.recordCollection;
			return json.data;
		}
	}
}).api();


$(document).on("click",".getid",function(){
	var lead_id=$(this).attr('set-id');
	var course_id=$(this).attr('set-course');
// 	alert('dfdf');
	$("#leadID").attr('value',lead_id);
	$("#courseID").attr('value',course_id);
});


$(document).on("click",".fillowbtn",function(){
	//alert(2);
	var lead_id=$(this).attr('set-id');
	var course_id=$(this).attr('set-course');
	$("#leadID").attr('value',lead_id);
	$("#courseID").attr('value',course_id);
});

$(document).on("change",".techn",function(){
	// alert(3);
	var lead_id=$(this).attr('set-id');
	var course_id=$(this).val();
	//alert(course_id);
	$("#leadID").attr('value',lead_id);
	$("#courseID").attr('value',course_id);
});

$(document).on("blur",".remark",function(){
	var remark=$(this).val();
	$("#remark").attr('value',remark);
});




$(document).on('change','.fbstatus',function(e){
	var $this = $(this);
	var id=$(this).val();
	if(id==3){
		$("select option[value='3']").attr("data-value","0");	
	}
	else if(id=='27'){
		$("select option[value='27']").attr("data-value","0");
	}
	else
	{
		$("select option[value='3']").attr("data-value","1");
		$("select option[value='27']").attr("data-value","1");
	}

});


$(document).on("click",".call",function(){
	var mobile=$(this).attr('data-mobile');
	var name=$(this).attr('data-name');
	$.ajax({
		url : '/call',
		type: 'POST',
		data: {mobile:mobile,name:name},
		// contentType:false,
		// cache:false,
		// processData:false,
		success:function(data)
		{
			console.log(data);
		}
	});
});
