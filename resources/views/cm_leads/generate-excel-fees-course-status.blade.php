<?php
  


	header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0'); 
	header('Pragma: no-cache'); 
	header('Content-Type: application/x-msexcel; format=attachment;');  
	header('Content-Disposition: attachment; filename=team_status_'.date('d-m-Y H:i:s').'.xls'); 
	 
	 
  
?>

 
    <table border="1" cellspacing="10" cellpadding="2" style="font-family:Garmond;font-size:9pt;">

    <thead>

      <tr>

       
        <th style="color:#fff;background-color:#333333;">Course</th>        
			<?php 
			foreach( $leadsmonth as $feesmont ) {
			?>
			<th style="color:#fff;background-color:#333333;"><?php  echo date('Y-M',strtotime($feesmont->leadcreated)); ?></th>												

			<?php } ?>	

      </tr>

    </thead>

    <tbody>

    <?php

 

	

      if( count($leads) > 0  ){
          $count = 0;

            foreach( $leads as $lead ) {				



            ++$count;

            ?>

            <tr>

              <td style="text-align:center;"> {{$lead->course_name}}</td>
             
			<?php 		
		 
			foreach( $leadsmonth as $monthss ) {
			?>
			 <td style="text-align:center;">  
			 <?php 	$monthname =date('M-Y',strtotime($monthss->leadcreated));
				$month = date('m',strtotime($monthss->leadcreated));
				$year = date('Y',strtotime($monthss->leadcreated));		
				$leadsmonthcount = App\Lead::whereMonth('created_at','=',$month)->whereYear('created_at','=',$year)->where('course',$lead->course);
				if($created_by){
				$leadsmonthcount= $leadsmonthcount->where('created_by',$created_by);
				}
				$leadsmonthcount=$leadsmonthcount->count();
				echo $leadsmonthcount;
				
				
				?>
			 
			 </td>

			<?php  } ?>

	  
 

           

            </tr>

            <?php

          }
		  
	  }
                                                                
                       
    ?>                

    </tbody>

    </table>
	
 

 