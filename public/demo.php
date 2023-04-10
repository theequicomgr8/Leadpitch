<?php 
$con=mysqli_connect("162.241.65.30","cromavps_website","cromavps_website#&123","cromavps_website");
$qry=mysqli_query($con,"select * from croma_category");
$row=mysqli_fetch_array($qry);
$category=$row['category'];
echo $category;

?>