<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
$objDB = new DB();


$id=$_POST['id'];
//echo $id;
$date = date('Y-m-d');
//echo $date;
//exit;
$sql="UPDATE installment_master SET admin_checked = 1 , admin_checked_date = '".$date."' WHERE id = ".$id;
$query=mysql_query($sql);
$premiumarr[] = "Success";
?>