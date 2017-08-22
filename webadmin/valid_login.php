<?php
//echo 'test2';
//print_r($_POST);EXIT;
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");

$objDB = new DB();
$username=loadVariable('username','');
$password=loadVariable('password','');
$password2=loadVariable('password2','');



$Query = "SELECT * from admin where username='".mysql_real_escape_string($username)."' and password ='".mysql_real_escape_string($password)."' and status = 1";
//echo  $Query;exit;
$objDB->setQuery($Query);

$rs = $objDB->select();


//$query = mysql_query($Query);
//$rs=mysql_num_rows($query);
//print_r($rs);exit;




if(count($rs) == 1 )

{


//echo 'final';
//exit;
	
 
	$_SESSION[ADMIN_SESSION_VAR] = $rs[0]['id'];
	$_SESSION[ROLE_ID] = $rs[0]['role_id'];
	$_SESSION[BRANCH_USER_ID] = $rs[0]['branch_user_id'];
	$_SESSION[HUB_ID] = $rs[0]['hub_id'];
	$_SESSION[SITE_NAME] = SITE_NAME_VAL;
	$_SESSION[SUCCESS_MSG] = "You have successfully logged in...";
	//echo "<pre>";print_r($_SESSION);EXIT;
	//header("location:index.php?p=home");
	header('Location: index.php?p=home');
	
	$objDB->close();

	//exit();

}

else

{

	$objDB->close();

	$_SESSION[ERROR_MSG] = "Invalid Username or Password, Try again...";

	header("location: index.php?p=login");

	exit();

}





?>