<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
//print_r($_POST);

set_time_limit(0); 

$pageOwner = "'superadmin','admin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); // $USER_TYPE is coming from index.php
$objDB = new DB();

#print_r($_POST);
//extract($_POST);
if(isset($_POST) && count($_POST) > 0)
{
	$updateQry = '';
	$wrong_address = '';
	$selData = mysql_query("SELECT id, address FROM admin WHERE 1");
	$numData = mysql_num_rows($selData); 
	if($numData > 0)
	{
		while($getData = mysql_fetch_assoc($selData))
		{
			$address = $getData['address'];
			$updateQry .= "UPDATE admin SET address = '".mysql_real_escape_string($address)."' WHERE id='".$getData['id']."';<br />";
			//mysql_query($updateQry);
			$wrong_address .= $getData['address'].'<br />';
		}
	}
	echo $wrong_address.'<br /><br /><br /><br />';
	echo $updateQry;
	//mysql_query($updateQry);
}


?>

<form method="post" action="" name="updatefrm">

Put date in yyyy-mm-dd format


<input type="submit" id="" name="btnSubmit" value="update"/>
	
</form>



<?php //$objDB->close(); ?>