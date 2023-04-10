<?php 
date_default_timezone_set('Asia/Calcutta'); 
header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0'); 
header('Pragma: no-cache'); 
header('Content-Type: application/x-msexcel; format=attachment;'); 
header('Content-Disposition: attachment; filename=course_about_'.date('d-m-Y H:i:s').'.xls'); 
?>
  <table border="1" cellspacing="10" cellpadding="2" style="font-family:Garmond;font-size:9pt;">
    <thead>
      <tr>
        <th>Heading</th>
        <th>Level1</th>
        <th>Level2</th>
        <th>Level3</th>
        <th>Level4</th>
        <th>Level5</th>  
		</tr>
    </thead>
    <tbody>
	<?php if(!empty($courseAboutHeading)){

foreach($courseAboutHeading as $vheading){ 

 
		?>
		<tr>
		<td><?php echo $vheading->heading.'-'.$vheading->course_id.'-'.$vheading->id; ?></td>	 
		<td></td> 
		<td></td> 
		<td></td> 
		<td></td> 
		<td></td> 	 
		</tr>   
	<?php    }  } 	?>					  
                     
				 
					 
<?php if(!empty($courseAboutHeading)){

foreach($courseAboutHeading as $vheading){ 

 $courseAboutLevel1 = App\CourseAboutExcel::where('heading_id',$vheading->id)->get();
//echo "<pre>";print_r($courseAboutLevel1);die;
if($courseAboutLevel1){
	
	foreach($courseAboutLevel1 as $level1){
 
		?>
		<tr>
		<td></td> 
		<td><?php  echo $level1->coursescontent.'-'.$level1->heading_id.'-'.$level1->id; ?></td> 
 
		<td></td>
		<td></td> 
		<td></td> 
		<td></td> 
		</tr>   
<?php    }  }  } }	?>					  
               				 
					 
<?php if(!empty($courseAboutHeading)){

foreach($courseAboutHeading as $vheading){ 

 $courseAboutLevel1 = App\CourseAboutExcel::where('heading_id',$vheading->id)->get();
 
if($courseAboutLevel1){
	
	foreach($courseAboutLevel1 as $level1){
$aboutLevel2 = App\CourseAboutExcel::where('content_id',$level1->id)->get();

	if($aboutLevel2){	
	
	foreach($aboutLevel2 as $level2) {
		?>
		<tr>
		<td></td> 
		<td></td> 
 
		<td><?php  echo $level2->subcontent.'-'.$level2->content_id.'-'.$level2->id; ?></td>
		<td></td> 
		<td></td> 
		<td></td> 
		</tr>   
 <?php  }}  }  }  } }	?>					  
                           				 
					 
<?php if(!empty($courseAboutHeading)){

foreach($courseAboutHeading as $vheading){ 

 $courseAboutLevel1 = App\CourseAboutExcel::where('heading_id',$vheading->id)->get();
//echo "<pre>";print_r($courseAboutLevel1);die;
if($courseAboutLevel1){
	
	foreach($courseAboutLevel1 as $level1){
$aboutLevel2 = App\CourseAboutExcel::where('content_id',$level1->id)->get();

	if($aboutLevel2){	
	
	foreach($aboutLevel2 as $level2) {
		
		$aboutLevel3 = App\CourseAboutExcel::where('subcontent_id',$level2->id)->get(); 
		if($aboutLevel3){	foreach($aboutLevel3 as $level3){
		?>
		<tr>
		<td></td> 
		<td></td> 
 
		<td></td>
		<td><?php  echo $level3->lastcontent.'-'.$level3->subcontent_id.'-'.$level3->id; ?></td> 
		<td></td> 
		<td></td> 
		</tr>   
		<?php   } } }}  }  }  } }	?>					  
                                    				 
					 
<?php if(!empty($courseAboutHeading)){

foreach($courseAboutHeading as $vheading){ 

 $courseAboutLevel1 = App\CourseAboutExcel::where('heading_id',$vheading->id)->get();
//echo "<pre>";print_r($courseAboutLevel1);die;
if($courseAboutLevel1){
	
	foreach($courseAboutLevel1 as $level1){
$aboutLevel2 = App\CourseAboutExcel::where('content_id',$level1->id)->get();

	if($aboutLevel2){	
	
	foreach($aboutLevel2 as $level2) {
		
		$aboutLevel3 = App\CourseAboutExcel::where('subcontent_id',$level2->id)->get(); 	
		if($aboutLevel3){	foreach($aboutLevel3 as $level3){
			
				$aboutLevel4 = App\CourseAboutExcel::where('endcontent_id',$level3->id)->get();
				if($aboutLevel4){	
				
				foreach($aboutLevel4 as $level4){
		?>
		<tr>
		<td></td> 
		<td></td> 
 
		<td></td>
		<td></td> 
		<td><?php  echo $level4->endcontent.'-'.$level4->endcontent_id.'-'.$level4->id; ?></td> 
		<td></td> 
		</tr>   
<?php   } } } }  }  }  } } } }	?>					  
                     

    </tbody>

    </table>  
	
 