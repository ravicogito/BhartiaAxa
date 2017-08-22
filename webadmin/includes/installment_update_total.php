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
extract($_POST);
if(isset($start_date) && $start_date != '')
{
	//echo 'Hi';
	$qry = "select id FROM customer_folio_no WHERE start_date = '".mysql_real_escape_string($start_date)."' ";
echo $qry;
	$selData = mysql_query($qry);
	echo $selData;

$numData = mysql_num_rows($selData);
echo $numData ;
if($numData > 0)
{
	while($getData = mysql_fetch_assoc($selData))
	{
	echo '<pre>';
	print_r($getData);
	echo '</pre>';
	//mysql_query("UPDATE installment_master SET installment='".$getData['installments']."' WHERE id='".$getData['intallment_id']."'" );
	$selInsCnt = mysql_query("SELECT SUM(installment) AS mycount FROM installment_master WHERE  folio_no_id	='".$getData['id']."' AND is_deleted = 0" );
	echo "SELECT SUM(installment) AS mycount FROM installment_master WHERE  folio_no_id	='".$getData['id']."' AND is_deleted = 0".'<br />';
	if(mysql_num_rows($selInsCnt) > 0)
		{
			$getInsCnt = mysql_fetch_array($selInsCnt);
			echo $getInsCnt['mycount'];
			$updateQry = "UPDATE customer_folio_no SET total_premium_given='".$getInsCnt['mycount']."' WHERE id='".$getData['id']."'" ;
			echo '<br >'.$updateQry;
			mysql_query($updateQry);
		}
	}
}
}

!isset($start_date) ? $start_date = '' : $start_date;


?>

<form method="post" action="" name="updatefrm">

Put date in yyyy-mm-dd format
<input type="text" accesskey="q" id="" name="start_date" value="<?php echo $start_date; ?>"  />

<input type="submit" id="" name="btnSubmit" value="update"/>
	
</form>



<?php //$objDB->close(); ?>