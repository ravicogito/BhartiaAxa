<?php
/*echo 'hello';
die();*/
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");

$objDB = new DB();
$planid = $_POST['planid'];
/*echo $planid;
die();*/
$plan_sql = 'select * from product_master where id="'.$planid.'"';
$plan_query = mysql_query($plan_sql);
$plan_row = mysql_fetch_array($plan_query);
echo '&nbsp;&nbsp;&nbsp;&nbsp;Min Rs: '.$plan_row['min_amount'].'&nbsp;&nbsp;&nbsp;&nbsp;Max Rs: '.$plan_row['max_amount'];
