<script>
 function autoRefresh_div()
 {  
    $("#notification_auto").load(location.href + " #notification_auto");
 }
 setInterval('autoRefresh_div()', 5000);    // refresh div after 5 secs
  </script>
   <div id="notification_auto">
   <?php 
   	 
		$query = DB::table('lead_follow_ups')
		->select('lead_follow_ups.*','leads.name','leads.mobile','course_name')
		->join('leads','lead_follow_ups.lead_id','=','leads.id')  
		//->where('lead_follow_ups.status','3')
		->where('leads.created_by',Auth::user()->id)
		->where('lead_follow_ups.expected_date_time','<=',date('Y-m-d'))
		//->limit(5)
		->orderBy('lead_follow_ups.id','desc')
		->get();
 //echo "<pre>";print_r($query);die;
		foreach ($query as $follow){
	 
	   
   ?>
   
   <script>
        /*  document.addEventListener('DOMContentLoaded', function () {
        if (!Notification) {
       alert('Desktop notifications not available in your browser. Try Chromium.'); 
       return;
        }

        if (Notification.permission !== "granted")
       Notification.requestPermission();
      }); */

      $(document).ready(function(){
      
        if (Notification.permission !== "granted")
       Notification.requestPermission();
        else {
       var notification = new Notification('Name: <?php echo $follow->name; ?>', {
         icon: '{{URL::asset('assets/images/logoleads.png')}}',
         body: "Mobile: <?php echo $follow->mobile.", Course:".$follow->course_name.", Remark: ".$follow->remark; ?>",
        
          
       });
       notification.onclick = function () {	
	window.location.href = "javascript:leadController.getfollowUps('<?php echo $follow->lead_id; ?>')";  
		 
       };
   /*     var follow_id = "<?php echo $follow->id; ?>";
        $.ajax({
         type: "post",
         url: "/lead/update_job_notification",
         data: {follow_id:follow_id},
         cache: false,
         success: function(data){
			 window.open = 'lead/all-leads'; 	   
	        }
        });  */
     //setTimeout(notification.close.bind(notification), 900000);
        }
      });
       </script>
   <?php  }  
   
   ?>
  </div>