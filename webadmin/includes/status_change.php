<?php  
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");






$id=$_GET['id'];
$table=$_GET['table'];
$returning_page=$_GET['returning_page'];

function status_changes($id,$table,$returning_page){
	$sql_query=mysql_query("SELECT * FROM '".$table."' WHERE id='".$id."'");
	$rows=mysql_fetch_array($sql_query);
	$status=$rows['status'];
	if($status==1){
		$status1=0;
	}
	else{
		$status1=1;
	}
	mysql_query("UPDATE gold_rate SET status='".$status1."'");
	header("Location:index.php?p=gold_rate_add");
}
if($table=='commodity_master'){
	function status_changess($id,$table,$returning_page){
	$sql_query=mysql_query("SELECT * FROM commodity_master WHERE commodity_id='".$id."'");
	$rows=mysql_fetch_array($sql_query);
	$status=$rows['status'];
	if($status==1){
		$status1=0;
	}
	else{
		$status1=1;
	}
	mysql_query("UPDATE commodity_master SET status='".$status1."' WHERE commodity_id='".$id."'");
	$_SESSION[SUCCESS_MSG] = "Status Changed Successfully...";
		header("Location:index.php?p=commodity");
	//$status_changed='Status Changed';
}
status_changess($id,$table,$returning_page);
}
else{
	function status_changess1($id,$table,$returning_page){
	$sql_query=mysql_query("SELECT * FROM '".$table."' WHERE id='".$id."'");
	$rows=mysql_fetch_array($sql_query);
	$status=$rows['status'];
	if($status==1){
		$status1=0;
	}
	else{
		$status1=1;
	}
	mysql_query("UPDATE gold_rate SET status='".$status1."' WHERE id='".$id."'");
	header("Location:index.php?p=gold_rate_add");
	}
}

?>