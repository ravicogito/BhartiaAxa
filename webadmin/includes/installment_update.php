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
if(isset($transaction_date) && $transaction_date != '')
{
	//echo 'Hi';
	$qry = "select   i.id as intallment_id, i.receipt_number as receipt_number, i.amount as amount, 
f.committed_amount, i.amount / f.committed_amount AS 
installments FROM customer_folio_no f,  installment_master as i

WHERE i.`folio_no_id` = f.id
AND i.deposit_date = '".$transaction_date."' ";
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
	mysql_query("UPDATE installment_master SET installment='".$getData['installments']."' WHERE id='".$getData['intallment_id']."'" );
	}
}
}

!isset($transaction_date) ? $transaction_date = '' : $transaction_date;


?>

<form method="post" action="" name="updatefrm">

Put date in yyyy-mm-dd format
<input type="text" id="" name="transaction_date" value="<?php echo $transaction_date; ?>" />

<input type="submit" id="" name="btnSubmit" value="update"/>
	
</form>



<?php //$objDB->close(); ?>