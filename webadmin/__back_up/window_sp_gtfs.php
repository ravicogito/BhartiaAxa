<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");

if(!isset($_SESSION[ADMIN_SESSION_VAR]))
{
	//echo 'Hi';
	header("location: index.php");
	exit();
}

// Write functions here
function find_receipt_status($receipt_id)
{
	$generated = 0;
	//$invoice_id = base64_decode($_GET['id']);
	//echo $invoice_id;
	$selTransaction = mysql_query("SELECT receipt_generated FROM insurance_short_premium_gtfs WHERE id='".$receipt_id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
			$getTransaction = mysql_fetch_assoc($selTransaction);			
			$generated = $getTransaction['receipt_generated'];
		}
	return $generated;
}

if(isset($_GET['id']) && !empty($_GET['id']))
{
	$objDB = new DB();
	

	$invoice_id = base64_decode($_GET['id']);
	$receipt_status = find_receipt_status($invoice_id);
	//echo $invoice_id;
	$selTransaction = mysql_query("SELECT * FROM insurance_short_premium_gtfs WHERE id='".$invoice_id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
			$getTransaction = mysql_fetch_assoc($selTransaction);
			//print_r($getTransaction);
			$branch_name = find_branch_name($getTransaction['branch_id']);
			//$trusted_id = find_customer_id_through_id($getTransaction['customer_id']);

			

		}
		else
		{
			echo 'No record found';
			exit;
		}
}

?>


<?php 
		$sql= "select * FROM insurance_plan WHERE id = ".$getTransaction['plan'];
		
		$query= mysql_query($sql);
		$data=mysql_fetch_array($query);


		//$sql2= "select * FROM frequency_master WHERE id = ".$getTransaction['frequency'];
		//echo $sql2;
		
		//$query2= mysql_query($sql2);
		//$data2=mysql_fetch_array($query2);
		
		
		?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Receipt</title>
<style>
.td350 
{
	width:325px;	
	font-size:9px;
	line-height:10px;
}
.font9px 
{
	font-size:9px;
	line-height:10px;
}
</style>
</head>

<body style="font-family: Tahoma, Verdana, Arial; font-size:14px;">

<center>
<!-- #################### CUSTOMER COPY STARTS INITIAL AND SUBSEQUENT PAYMENT ######################## -->

	<table width="700" border="0" cellpadding="0" cellspacing="0" align="center" style = "padding-top:10px;">
		
		<tr>
			<td align="center" class="font9px" style="line-height:3px;">
				<table>
					<tr>
						<td width="80" align="center" ><img src="images/gtfs.bmp" width="60" /></td>

					  <td width="552" align="center">
						<p style="font-size: 11px;" align="center">Original</p>
						<p style="font-size: 16px;" align="center"><strong>GOLDEN TRUST FINANCIAL SERVICES</strong></p><p style="font-size: 11px;" align="center"><strong>U/S - 44</strong></p>
						
						
						<p style="font-size: 11px;" align="center">Regd. Office : S.N.Mansion, 16, R.N. Mukherjee Road, kolkata-700001</p>
						<p style="font-size: 11px;" align="center"><strong>PROVISIONAL RECEIPT (CUSTOMER COPY)</strong></p></td>

				  </tr>
					
				</table>
       </td>
		</tr>		
	</table>
<br/>

	
	<table width="700" border="0" cellpadding="0" cellspacing="0" style="font-size: 10px" align="center">    	
		
		<tr>
		<td width="36%" valign="top" align="left" style="padding-top:3px;">
		<strong>Date :</strong> <?php echo date('d-m-Y',strtotime($getTransaction['deposit_date'])); ?>
		</td>
		
		<td width="30%" valign="top" style="padding-top:3px;">
		<strong>Branch : <?php echo find_branch_name($getTransaction['branch_id']); ?></strong>
		</td>
		
		<td width="34%" valign="top" style="padding-top:3px;">
		<strong>Receipt No. :</strong> <?php echo $getTransaction['transaction_id'];?></td>
		</tr>
		<tr>
		<td width="36%" valign="top" align="left" style="padding-top:3px;"><strong>Application No. :</strong> <?php echo $getTransaction['application_no'];?></td><td width="30%" valign="top" style="padding-top:3px;"></td><td width="34%" valign="top" style="padding-top:3px;"></td>
		</tr>


	</table>
	<br/><br/>
	  <div style="width:700px; margin:0 auto; text-align:left; padding-top:1px;">Received with thanks from<strong> <?php 

	 if(isset($getTransaction['applicant_name'])) {
				 echo $getTransaction['applicant_name']; }else {echo '_______________________';}?></strong> a sum of Rs<strong><?php echo $getTransaction['amount']; ?></strong>/- by 
				
				<?php if($getTransaction['sp_payment_mode'] == 'CASH'){?> Cash <?php }?>
				
		<?php if(($getTransaction['sp_payment_mode'] == 'CHEQUE') || ($getTransaction['sp_payment_mode'] == 'DD')){?>Cheque/Demand Draft No <?php echo $getTransaction['sp_dd_no']; ?> dated <?php echo date('d-m-Y',strtotime($getTransaction['sp_dd_date'])); ?> drown on <?php echo $getTransaction['sp_dd_bank']; ?><?php }?>
			towards FP(Short Premium) in relation to above Proposal for  & on behalf of Life Insurance Corporation of India under<strong> <?php echo $data['plan_name'];?> </strong> and term<strong> <?php echo $getTransaction['term']; ?></strong> Years.</div>
	<br/>
<br/><br/>

	
	<table width="700" border="0" style="padding:0 10px 0 0;" >
		<tr>
			<td width="439">
			Code No:<?php echo $getTransaction['agent_code'];?>		</td>
	  <td width="251" align="center" class="font9px" height="70px;" valign="middle" rowspan="2">
				_____________________________________ <br />
  <strong>AUTHORISED SIGNATORY</strong><br /><strong>WITH SEAL</strong>
		  </td>

	  </tr>
	</table><br/>
<br/>

	<table width="700" border="0">
		<tr>
			<td colspan="7" align="center" style="border:1px solid #000000; font-size:10px; padding-bottom:2px;">
				<strong>Authorised Representative G.T.F.S. : 
(1) This is provisional receipt and valid till issue of receipt by the L.I.C.I.
(2) Risk will commence on acceptance of the proposal by the L.I.C.I.
(3) Money will be refunded by the L.I.C.I. in the event proposal is declined.
</strong>
			</td>
		</tr>
	</table>

<!-- #################### CUSTOMER COPY ENDS INITIAL AND SUBSEQUENT PAYMENT ######################## -->
	

<br/><br/><br/>
	<table width="700" border="0" cellpadding="0" cellspacing="0" align="center">
		
		<tr>
			<td align="center" class="font9px" style="line-height:3px;">
				<table>
					<tr>
						<td width="80" align="center" ><img src="images/gtfs.bmp" width="60" /></td>

					  <td width="552" align="center">
						
						<p style="font-size: 16px;" align="center"><strong>GOLDEN TRUST FINANCIAL SERVICES</strong></p><p style="font-size: 11px;" align="center"><strong>U/S - 44</strong></p>
						
						
						<p style="font-size: 11px;" align="center">Regd. Office : S.N.Mansion, 16, R.N. Mukherjee Road, kolkata-700001</p>
						<p style="font-size: 11px;" align="center"><strong>PROVISIONAL RECEIPT (OFFICE COPY)</strong></p></td>

				  </tr>
					
				</table>
       </td>
		</tr>		
	</table>
<br/>

	
	<table width="700" border="0" cellpadding="0" cellspacing="0" style="font-size: 10px" align="center">    	
		
		<tr>
		<td width="36%" valign="top" align="left" style="padding-top:3px;">
		<strong>Date :</strong> <?php echo date('d-m-Y',strtotime($getTransaction['deposit_date'])); ?>
		</td>
		
		<td width="30%" valign="top" style="padding-top:3px;">
		<strong>Branch : <?php echo find_branch_name($getTransaction['branch_id']); ?></strong>
		</td>
		
		<td width="34%" valign="top" style="padding-top:3px;">
		<strong>Receipt No. :</strong> <?php echo $getTransaction['transaction_id'];?></td>
		</tr>
		<tr>
		<td width="36%" valign="top" align="left" style="padding-top:3px;"><strong>Application No. :</strong> <?php echo $getTransaction['application_no'];?></td><td width="30%" valign="top" style="padding-top:3px;"></td><td width="34%" valign="top" style="padding-top:3px;"></td>
		</tr>


	</table>
	<br/><br/>
	  <div style="width:700px; margin:0 auto; text-align:left; padding-top:1px;">Received with thanks from<strong> <?php 

	 if(isset($getTransaction['applicant_name'])) {
				 echo $getTransaction['applicant_name']; }else {echo '_______________________';}?></strong> a sum of Rs<strong><?php echo $getTransaction['amount']; ?></strong>/- by 
				
				<?php if($getTransaction['sp_payment_mode'] == 'CASH'){?> Cash <?php }?>
				
		<?php if(($getTransaction['sp_payment_mode'] == 'CHEQUE') || ($getTransaction['sp_payment_mode'] == 'DD')){?>Cheque/Demand Draft No <?php echo $getTransaction['sp_dd_no']; ?> dated <?php echo date('d-m-Y',strtotime($getTransaction['sp_dd_date'])); ?> drown on <?php echo $getTransaction['sp_dd_bank']; ?><?php }?>
			towards FP(Short Premium) in relation to above Proposal for  & on behalf of Life Insurance Corporation of India under<strong> <?php echo $data['plan_name'];?> </strong> and term<strong> <?php echo $getTransaction['term']; ?></strong> Years.</div>
	<br/>
<br/><br/>

	<table width="700" border="0" style="padding:0 10px 0 0;">
		<tr>
			<td width="439">
			Code No:<?php echo $getTransaction['agent_code'];?>			</td>
	  <td width="251" align="center" class="font9px" height="70px;" valign="middle" rowspan="2">
				_____________________________________ <br />
  <strong>AUTHORISED SIGNATORY</strong><br /><strong>WITH SEAL</strong>
		  </td>

	  </tr>
	</table>

	<table width="700" border="0">
		<tr>
			<td colspan="7" align="center" style="border:1px solid #000000; font-size:10px; padding-bottom:2px;">
				<strong>Authorised Representative G.T.F.S. : 
(1) This is provisional receipt and valid till issue of receipt by the L.I.C.I.
(2) Risk will commence on acceptance of the proposal by the L.I.C.I.
(3) Money will be refunded by the L.I.C.I. in the event proposal is declined.
</strong>
			</td>
		</tr>
	</table>
<!-- #################### OFFICE COPY ENDS INITIAL AND SUBSEQUENT PAYMENT ######################## -->

</center>

</body>
</html>




<?php
	mysql_query("UPDATE insurance_short_premium_gtfs SET receipt_generated=1 WHERE id='".base64_decode($_GET['id'])."'");

?>
