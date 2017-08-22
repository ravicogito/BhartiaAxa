<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");

if(!isset($_SESSION[ADMIN_SESSION_VAR]))
{
	//echo 'Hi';
	header("location: index.php");
	exit();
}

// Write functions here
function find_branch_code($branch_id)
{
	$branch_code = '';
	$selBranchData = mysql_query("SELECT branch_code FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_code = $getBranchData['branch_code'];
	}
	return $branch_code;
}

function find_branch_name($branch_id)
{
	$branch_name = '';
	$selBranchData = mysql_query("SELECT branch_name FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_name = $getBranchData['branch_name'];
	}
	return $branch_name;
}

function find_premium_number($customer_id)
{
	$premium_number = 0;
	$selPremiumNumber = mysql_query("SELECT total_premium_given FROM customer_master WHERE id='".$customer_id."'");
	$numPremiumNumber = mysql_num_rows($selPremiumNumber);
	if($numPremiumNumber > 0)
	{
		$getPremiumNumber = mysql_fetch_array($selPremiumNumber);
		$premium_number = $getPremiumNumber['total_premium_given'];
	}
	return intval($premium_number);
}

function find_id_through_customer_id($customer_id)
{
	$cust_id = '';
	$selCustomerID = mysql_query("SELECT id FROM customer_master WHERE customer_id='".mysql_real_escape_string($customer_id)."'");
	$numCustomerID = mysql_num_rows($selCustomerID);
	if($numCustomerID > 0)
	{
		$getCustomerID = mysql_fetch_array($selCustomerID);
		$cust_id = $getCustomerID['id'];
	}
	return $cust_id;
}

function find_customer_id_through_id($id)
{
	$cust_id = '';
	$selCustomerID = mysql_query("SELECT customer_id FROM customer_master WHERE id='".$id."'");
	$numCustomerID = mysql_num_rows($selCustomerID);
	if($numCustomerID > 0)
	{
		$getCustomerID = mysql_fetch_array($selCustomerID);
		$cust_id = $getCustomerID['customer_id'];
	}
	return $cust_id;
}

if(isset($_GET['id']) && !empty($_GET['id']))
{
	$objDB = new DB();

	$invoice_id = base64_decode($_GET['id']);
	//echo $invoice_id;
	$selTransaction = mysql_query("SELECT * FROM installment_master WHERE id='".$invoice_id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
			$getTransaction = mysql_fetch_assoc($selTransaction);
			//print_r($getTransaction);
			$branch_name = find_branch_name($getTransaction['branch_id']);
			$trusted_id = find_customer_id_through_id($getTransaction['customer_id']);

		}
		else
		{
			echo 'No record found';
			exit;
		}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Invoice </title>
  <meta name="Generator" content="">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
 </head>

 <body>
 <center>
 <div>
	 <table style="width:700px;">
		 <tr>
			<td colspan="3" style="text-align:center"><strong>DUPLICATE</strong></td>
		 </tr>
		 <tr>
			<td colspan="3" style="padding-left:128px;"><strong>DELIGHT MULTI SERVICES PVT. LIMITED (OFFICE COPY)</strong></td>
		 </tr>
		 <tr>
			<td></td>
			<td></td>
			<td></td>
		 </tr>
		 <tr>
			<td colspan="3" style="padding-left:150px;">
				24, Convent Road, Entally <br />
				Kolkata-700 014	
			</td>
		 </tr>
		 <tr>
			<td colspan="3" style="padding-left:128px;"><strong>RECEIPT NO. :</strong> <?php echo $getTransaction['receipt_number']; ?></td>
		 </tr>
		 <tr>
			<td colspan="3" style="padding-bottom:20px;padding-left:128px;"><strong>CUSTOMER ID :</strong> <?php echo find_customer_id_through_id($getTransaction['customer_id']); ?></td>
		 </tr>
		 <tr>
			<td style="line-height:18px;"><strong>REGION:-</strong> <?php echo $branch_name; ?></td>
			<td style="line-height:18px;"><strong>DATE:-</strong> <?php echo date('d/m/Y', strtotime($getTransaction['deposit_date'])); ?></td>
			<td style="line-height:18px;"><strong>MODE:-</strong> <?php echo $getTransaction['payment_mode_service']; ?></td>
		 </tr>
		 <tr>
			<td colspan="3" style="line-height:18px;">
			Received from Mr./Mrs.- <strong><?php echo $getTransaction['first_name'].' '.$getTransaction['last_name']; ?></strong> <br />
			a sum of <strong>Rs.- <?php echo $getTransaction['transaction_charges']; ?></strong> by <strong><?php echo $getTransaction['payment_mode_service']; ?></strong>
			<?php if($getTransaction['payment_mode_service'] == 'DD') {
				echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;<strong>DD Number :</strong> '.$getTransaction['dd_number'].'&nbsp;<strong>Bank Name :</strong> '.$getTransaction['dd_bank_name'].'&nbsp;<strong>Date :</strong> '.date('d-m-Y', strtotime($getTransaction['dd_date']));
			}?>
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;against <strong>Application no.- <?php echo $getTransaction['application_no']; ?></strong> towards investment of <br />
			Swarna Vriksh Service Charge including Service Tax
			<br />
			Total installment(s) given : <strong><?php echo find_premium_number($getTransaction['customer_id']); ?></strong>
			</td>	
		 </tr>
		 <tr>
			<td colspan="3" style="line-height:18px; padding-top:30px;">
				<div style="width:49%; float:left;"><strong>SERVICE TAX NO.:</strong> AACCD7901JST001<?php //echo $getTransaction['transaction_id']; ?><br />
				<strong>CODE:-</strong> <?php echo $getTransaction['agent_code']; ?>
				</div> 
				<div style="width:49%; float:left;">
				For Delight Multi Services Pvt.Ltd.<br /><br /><br />
				--------------------------- <br />
				(Authorized Signatory)
				
				</div></td>
			
		 </tr>
		 <tr>
			<td></td>
			<td></td>
			<td></td>
		 </tr>
	 </table>
 </div>
 <div style="height:50px;">&nbsp;</div>
 <div>
	 <table style="width:700px;">
		 <tr>
			<td colspan="3" style="text-align:center"><strong>DUPLICATE</strong></td>
		 </tr>
		 <tr>
			<td colspan="3" style="padding-left:128px;"><strong>DELIGHT MULTI SERVICES PVT. LIMITED (CUSTOMER COPY)</strong></td>
		 </tr>
		 <tr>
			<td></td>
			<td></td>
			<td></td>
		 </tr>
		 <tr>
			<td colspan="3" style="padding-left:150px;">
				24, Convent Road, Entally <br />
				Kolkata-700 014	
			</td>
		 </tr>
		 <tr>
			<td colspan="3" style="padding-left:128px;"><strong>RECEIPT NO. :</strong> <?php echo $getTransaction['receipt_number']; ?></td>
		 </tr>
		 <tr>
			<td colspan="3" style="padding-bottom:20px;padding-left:128px;"><strong>CUSTOMER ID :</strong> <?php echo find_customer_id_through_id($getTransaction['customer_id']); ?></td>
		 </tr>
		 <tr>
			<td style="line-height:18px;"><strong>REGION:-</strong> <?php echo $branch_name; ?></td>
			<td style="line-height:18px;"><strong>DATE:-</strong> <?php echo date('d/m/Y', strtotime($getTransaction['deposit_date'])); ?></td>
			<td style="line-height:18px;"><strong>MODE:-</strong> <?php echo $getTransaction['payment_mode_service']; ?></td>
		 </tr>
		 <tr>
			<td colspan="3" style="line-height:18px;">
			Received from Mr./Mrs.- <strong><?php echo $getTransaction['first_name'].' '.$getTransaction['last_name']; ?></strong> <br />
			a sum of <strong>Rs.- <?php echo $getTransaction['transaction_charges']; ?></strong> by <strong><?php echo $getTransaction['payment_mode_service']; ?></strong>
			<?php if($getTransaction['payment_mode_service'] == 'DD') {
				echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;<strong>DD Number :</strong> '.$getTransaction['dd_number'].'&nbsp;<strong>Bank Name :</strong> '.$getTransaction['dd_bank_name'].'&nbsp;<strong>Date :</strong> '.date('d-m-Y', strtotime($getTransaction['dd_date']));
			}?><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;against <strong>Application no.- <?php echo $getTransaction['application_no']; ?></strong> towards investment of <br />
			Swarna Vriksh Service Charge including Service Tax
			<br />
			Total installment(s) given : <strong><?php echo find_premium_number($getTransaction['customer_id']); ?></strong>
			</td>	
		 </tr>
		 <tr>
			<td colspan="3" style="line-height:18px; padding-top:30px;">
				<div style="width:49%; float:left;"><strong>SERVICE TAX NO.:</strong> AACCD7901JST001<?php //echo $getTransaction['transaction_id']; ?><br />
				<strong>CODE:-</strong> <?php echo $getTransaction['agent_code']; ?>
				</div> 
				<div style="width:49%; float:left;">
				For Delight Multi Services Pvt.Ltd.<br /><br /><br />
				--------------------------- <br />
				(Authorized Signatory)
				
				</div></td>
			
		 </tr>
		 <tr>
			<td></td>
			<td></td>
			<td></td>
		 </tr>
	 </table>
 </div>
 </center>
 </body>
</html>
<?php
	mysql_query("UPDATE installment_master SET receipt_generated=1 WHERE id='".base64_decode($_GET['id'])."'");

?>
