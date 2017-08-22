<?php

include_once("../utility/config.php");

include_once("../utility/dbclass.php");

include_once("../utility/functions.php");



$objDB = new DB();



$username=loadVariable('username','');

$password=loadVariable('password','');

$password2=loadVariable('password2','');



$Query = "SELECT * from admin where username='".mysql_real_escape_string($username)."' and password ='".mysql_real_escape_string($password)."' and status = 1";
//echo  $Query;
$objDB->setQuery($Query);

$rs = $objDB->select();



if(count($rs) == 1 )

{



	$objDB->close();

	$_SESSION[ADMIN_SESSION_VAR] = $rs[0]['id'];
	$_SESSION[ROLE_ID] = $rs[0]['role_id'];
	$_SESSION[BRANCH_USER_ID] = $rs[0]['branch_user_id'];
	$_SESSION[HUB_ID] = $rs[0]['hub_id'];
	$_SESSION[SITE_NAME] = SITE_NAME_VAL;

	$_SESSION[SUCCESS_MSG] = "You have successfully logged in...";

	header("location: index.php?p=home");

	exit();

}

else

{

	$objDB->close();

	$_SESSION[ERROR_MSG] = "Invalid Username or Password, Try again...";

	header("location: index.php?p=login");

	exit();

}





?>