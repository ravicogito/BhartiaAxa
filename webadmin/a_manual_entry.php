<?php
/*
//include_once("../utility/config.php");
//include_once("../utility/dbclass.php");
//include_once("../utility/functions.php");

if(!isset($_SESSION[ADMIN_SESSION_VAR]))
{
	header("location: index.php");
	exit();
}

$objDB = new DB();

$a=loadVariable('a','');

$id = $_SESSION[ADMIN_SESSION_VAR];
$old_password = mysql_real_escape_string(loadVariable('old_password',''));
$new_password = mysql_real_escape_string(loadVariable('new_password',''));
$confirm_password = mysql_real_escape_string(loadVariable('confirm_password',''));

if($new_password =="" || $old_password == "" || $confirm_password == "")
{
	$_SESSION[ERROR_MSG] = "Please Enter Data for All Fields...";
	$objDB->close();
	header("location: index.php?p=changepass");
	exit();
}

if($new_password <> $confirm_password )
{
	$_SESSION[ERROR_MSG] = "New and Confirm Password does not match...";
	$objDB->close();
	header("location: index.php?p=changepass");
	exit();
}

$Query  = "select * from admin where id ='".$id."'";
$objDB->setQuery($Query);
$rs = $objDB->select();

if($rs[0]['password'] <> $old_password)
{
	$_SESSION[ERROR_MSG] = "Invalid Old Password Given...";
	$objDB->close();
	header("location: index.php?p=changepass");
	exit();
}

/*
echo "<pre>";
print_r($_POST);
die();
*/

/*if($a=='change_pass')
{
	$Query  = "UPDATE admin set password='".$new_password."' WHERE id='".$id."'";
	$objDB->setQuery($Query);
	$rs = $objDB->update();
	
	$_SESSION[SUCCESS_MSG] = "Password Changed Successfully....";
	$objDB->close();
	header("location: index.php?p=changepass");
	exit();
}

$objDB->close();
header("location: index.php?p=changepass");
exit();
*/
?>