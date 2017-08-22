<?php
//print_r($_POST);
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
$objDB = new DB();

$policy_no = $_POST['policy_no'];

$applicant_name = '';

$selData = mysql_query("SELECT applicant_name FROM sicl_app_master WHERE application_no='".$policy_no."'");
		$numData = mysql_num_rows($selData);
		if($numData > 0)
		{
			$getData = mysql_fetch_array($selData);
			$applicant_name = $getData['applicant_name'];
		}


$premiumarr = array();

//echo $setPremiumamount;
//exit;


$premiumarr[] = $applicant_name;

echo json_encode($premiumarr);
?>