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
	$selTransaction = mysql_query("SELECT receipt_generated FROM renewal_master WHERE id='".$receipt_id."'");
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
	$selTransaction = mysql_query("SELECT * FROM renewal_master WHERE id='".$invoice_id."'");
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
	if($getTransaction['payment_type'] != 'REDEMPTION') // For NON REDEMPTION payment
	{
?>

<?php 
		$sql= "select * FROM insurance_plan WHERE id = ".$getTransaction['plan'];
		
		$query= mysql_query($sql);
		$data=mysql_fetch_array($query);


		$sql2= "select * FROM frequency_master WHERE id = ".$getTransaction['frequency'];
		//echo $sql2;
		
		$query2= mysql_query($sql2);
		$data2=mysql_fetch_array($query2);
		
		
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

<table width="700" border="0" cellpadding="0" cellspacing="0" align="center" style="padding:25px;" style = "padding-top:10px;" >
		
		<tr>
			<td align="center" class="font9px" style="line-height:3px;">
				<table>
					<tr>
						<td width="125" align="center" ><img src="images/sarada-logo.jpg"  /></td>

					  <td width="552" align="center">
						
						<p style="font-size: 16px;" align="center"><strong>SARADA INSURANCE CONSULTANCY LIMITED</strong></p><p style="font-size: 11px;" align="center">Corporate Agent of Life <strong>Insurance Corporation of India</strong></p>
						<p style="font-size: 13px;" align="center">License No. - 5389734</p>

						
						<p style="font-size: 11px;" align="center">REGD. OFFICE : 103 A, S.N.Banerjee Road, Kolkata - 700014</p>
						<p style="font-size: 11px;" align="center"><strong>PROVISIONAL RECEIPT (CUSTOMER COPY)</strong><?php 
						if(isset($getTransaction['receipt_generated']) && ($getTransaction['receipt_generated'] == 1))
						{
						  echo "[Duplicate]";
						}
						?></p></td>

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
		<td width="30%" align="left" valign="top" style="padding-top:3px;">
		<Strong>Policy No. : <?php echo $getTransaction['policy_no']; ?></strong>
		</td>
		</tr>



	</table>
	<br/><br/>
	  <div style="width:700px; margin:0 auto; text-align:left; padding-top:1px;">Received with thanks from<strong> <?php 
				echo $getTransaction['insured_name'].' '.$getTransaction['middle_name'].''.$getTransaction['last_name']; ?></strong>a sum of Rs <strong><?php echo $getTransaction['main_amount']; ?></strong>/- by 
				
				<?php if($getTransaction['payment_mode'] == 'CASH'){?> Cash <?php }?>
				
		<?php if(($getTransaction['payment_mode'] == 'CHEQUE') || ($getTransaction['payment_mode'] == 'DD')){?>Cheque/Demand Draft No <?php echo $getTransaction['dd_number']; ?> dated <?php echo date('d-m-Y',strtotime($getTransaction['dd_date'])); ?> drawn on <?php echo $getTransaction['dd_bank_name']; ?><?php } 
		
		if($getTransaction['other_payment_mode'] == 'CASH'){?>
			 & Rs <strong><?php echo $getTransaction['other_amount'];?></strong> /-By Cash

		<?php
		}
		?>
			<!--towards FP ( <strong><?php echo $data2['frequency']; ?></strong> Premium) in relation to above Proposal for  & on behalf of Life Insurance Corporation of India under<strong> <?php echo $data['plan_name'];?> </strong> and term<strong> <?php echo $getTransaction['tenure']; ?></strong> Years.
			
			-->



			being renewal premium against <strong><?php echo $data['plan_name'];?></strong> and term<strong> <?php echo $getTransaction['tenure']; ?></strong> Years policy for <strong><?php echo $data2['frequency']; ?> </strong> premium of 
			"LIFE INSURANCE CORPORATION OF INDIA"
			</div><br/>
	<br/><br/>
	
	
	
	<table width="700" border="0" style="padding:0 10px 0 0;">
		<tr>
			<td width="449" align="left">
			Code No:<?php echo $getTransaction['agent_code'];?>			</td>
	  <td width="251" align="center" class="font9px" height="70px;" valign="middle" rowspan="2">
				_____________________________________ <br />
  <strong>AUTHORISED SIGNATORY</strong><br /><strong>WITH SEAL</strong>
		  </td>
		  </tr>
		  </table>

<br/>
	<br/>
	<table width="700" border="0">
		<tr>
			<td colspan="7" align="left" style="border:1px solid #000000; font-size:10px; padding:5px;">
				<strong>This is only a provisional recept. the recept will remain valid till the issuance of Renewal Premium receipt by Life insurance Corporation of India and/ or revival of policy coverage in case of lapsation either by submission of Declaration of Good Health or otherwise. In any of the above situation Sarada insurance Consultancy Limited will not be held responsible/liable if unfortunate death of Life Assured occurs in the interening period</strong>		
		</tr>
	</table>


<!-- #################### CUSTOMER COPY ENDS INITIAL AND SUBSEQUENT PAYMENT ######################## -->
	
<br><br><br>

<table width="700" border="0" cellpadding="0" cellspacing="0" align="center" style="padding:25px;" >
		
		<tr>
			<td align="center" class="font9px" style="line-height:3px;">
				<table>
					<tr>
						<td width="125" align="center" ><img src="images/sarada-logo.jpg"  /></td>

					  <td width="552" align="center">
						
						<p style="font-size: 16px;" align="center"><strong>SARADA INSURANCE CONSULTANCY LIMITED</strong></p><p style="font-size: 11px;" align="center">Corporate Agent of Life <strong>Insurance Corporation of India</strong></p>
						<p style="font-size: 13px;" align="center">License No. - 5389734 </p>
						
						<p style="font-size: 11px;" align="center">REGD. OFFICE : 103 A, S.N.Banerjee Road, Kolkata - 700014</p>
						<p style="font-size: 11px;" align="center"><strong>PROVISIONAL RECEIPT (OFFICE COPY)</strong><?php 
						if(isset($getTransaction['receipt_generated']) && ($getTransaction['receipt_generated'] == 1))
						{
						  echo "[Duplicate]";
						}
						?></p></td>

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
		<td width="30%" align="left" valign="top" style="padding-top:3px;">
		<Strong>Policy No. : <?php echo $getTransaction['policy_no']; ?></strong>
		</td>
		</tr>



	</table>
	<br/><br/>
	  <div style="width:700px; margin:0 auto; text-align:left; padding-top:1px;">Received with thanks from<strong> <?php 
				echo $getTransaction['insured_name'].' '.$getTransaction['middle_name'].''.$getTransaction['last_name']; ?></strong>a sum of Rs <strong><?php echo $getTransaction['main_amount']; ?></strong>/- by 
				
				<?php if($getTransaction['payment_mode'] == 'CASH'){?> Cash <?php }?>
				
		<?php if(($getTransaction['payment_mode'] == 'CHEQUE') || ($getTransaction['payment_mode'] == 'DD')){?>Cheque/Demand Draft No <?php echo $getTransaction['dd_number']; ?> dated <?php echo date('d-m-Y',strtotime($getTransaction['dd_date'])); ?> drawn on <?php echo $getTransaction['dd_bank_name']; ?><?php } 
		
		if($getTransaction['other_payment_mode'] == 'CASH'){?>
			 & Rs <strong><?php echo $getTransaction['other_amount'];?></strong> /-By Cash

		<?php
		}
		?>
			<!--towards FP ( <strong><?php echo $data2['frequency']; ?></strong> Premium) in relation to above Proposal for  & on behalf of Life Insurance Corporation of India under<strong> <?php echo $data['plan_name'];?> </strong> and term<strong> <?php echo $getTransaction['tenure']; ?></strong> Years.
			
			-->



			being renewal premium against <strong><?php echo $data['plan_name'];?></strong> and term<strong> <?php echo $getTransaction['tenure']; ?></strong> Years policy for <strong><?php echo $data2['frequency']; ?> </strong> premium of 
			"LIFE INSURANCE CORPORATION OF INDIA"
			</div><br/>
	<br/><br/>
	
	
	
	<table width="700" border="0" style="padding:0 10px 0 0;">
		<tr>
			<td width="449" align="left">
			Code No:<?php echo $getTransaction['agent_code'];?>			</td>
	  <td width="251" align="center" class="font9px" height="70px;" valign="middle" rowspan="2">
				_____________________________________ <br />
  <strong>AUTHORISED SIGNATORY</strong><br /><strong>WITH SEAL</strong>
		  </td>
		  </tr>
		  </table>

<br/>
	<br/>
	<table width="700" border="0">
		<tr>
			<td colspan="7" align="left" style="border:1px solid #000000; font-size:10px; padding:5px;">
				<strong>This is only a provisional recept. the recept will remain valid till the issuance of Renewal Premium receipt by Life insurance Corporation of India and/ or revival of policy coverage in case of lapsation either by submission of Declaration of Good Health or otherwise. In any of the above situation Sarada insurance Consultancy Limited will not be held responsible/liable if unfortunate death of Life Assured occurs in the interening period</strong>		
		</tr>
	</table>
<!-- #################### OFFICE COPY ENDS INITIAL AND SUBSEQUENT PAYMENT ######################## -->

</center>

</body>
</html>









<?php
	}
			
?>

<?php
	mysql_query("UPDATE renewal_master SET receipt_generated=1 WHERE id='".base64_decode($_GET['id'])."'");

?>
