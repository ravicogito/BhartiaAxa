<?php
/*echo 'hello';
die();*/
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");

$objDB = new DB();
$micr = trim($_POST['micr']);
/*echo $planid;
die();*/
$micr_sql = 'select * from micr_master where ifs_code="'.$micr.'"';
$micr_query = mysql_query($micr_sql);
$micr_num_row = mysql_num_rows($micr_query);
if($micr_num_row < 1){
	echo '0';
}else{
$micr_row = mysql_fetch_array($micr_query);
$micrarr[] = $micr_row['bank_name'];
$micrarr[] = $micr_row['branch'];
$micrarr[] = $micr_row['micr_code'];
echo json_encode($micrarr);
//echo 'Bank='.$micr_row['dd_bank_name'].'Branch='.$micr_row['dd_bank_branch'].'IFS='.$micr_row['ifs_code'];	
}