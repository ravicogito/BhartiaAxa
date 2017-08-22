<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");

date_default_timezone_set('Asia/Calcutta');

$objDB = new DB();
$pageOwner = "'admin','superadmin','hub','branch','subadmin','division'";
if(!isset($_SESSION[ROLE_ID])) { $_SESSION[ROLE_ID] = 0; } // To stop error receipting
//echo '123';
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

$transaction_charges = 0.00;
$amount_given = 0.00;
$currThousand = 0;
$currFiveHundred = 0;
$currHundred = 0;
$currFifty = 0;
$currTwenty = 0;
$currTen = 0;
$currFive = 0;
$currTwo = 0;
$currOne = 0;
$valTotal = 0;

$branch_id=0;
$pis_date = '';
$pis_mode='';

$readonly = 'readonly'; // made the textboxes readonly if we get any previous entry
$previousID = 0; // ID of the previous PIS record


$objDB = new DB();
//$pageOwner = "'admin','superadmin','branch','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 
#print_r($_POST);

extract($_GET);

##### FETCHING RECORDS FOR PREVIOUS PIS ##########

$previousID = base64_decode($id);

if(isset($_POST['cms_no']) && trim($_POST['cms_no']) != '') // Updating cms no and deposit date
{
	extract($_POST);
	mysql_query("UPDATE pis_sp_master_gtfs SET cms_no='".$cms_no."', deposit_date='".date('Y-m-d', strtotime($deposit_date))."' WHERE id='".$previousID."'");

?>
<script type="text/javascript">
<!--
	window.opener.document.addForm.submit();
	window.close();
//-->
</script>

<?php
}

if(intval($previousID) != 0)
{
	$selOldRec = mysql_query("SELECT * FROM pis_sp_master_gtfs WHERE id='".$previousID."'");
	if(mysql_num_rows($selOldRec) > 0)
	{
		$readonly = 'readonly';
		$getOldRec = mysql_fetch_array($selOldRec);

		$currThousand = $getOldRec['thousand'];
		$currFiveHundred = $getOldRec['fiveHundred']; 
		$currHundred = $getOldRec['hundred'];
		$currFifty = $getOldRec['fifty'];
		$currTwenty = $getOldRec['twenty'];
		$currTen = $getOldRec['ten'];
		$currFive = $getOldRec['five'];
		$currTwo = $getOldRec['two'];
		$currOne = $getOldRec['one'];
		$valTotal = $getOldRec['total'];

		$prepared_at = $getOldRec['prepared_at'];

		$branch_id=$getOldRec['branch_id'];
		$pis_date = $getOldRec['pis_date'];
		$pis_mode=$getOldRec['pis_mode'];
		$deposit_date= $getOldRec['deposit_date'] != '0000-00-00' ? date('d-m-Y', strtotime($getOldRec['deposit_date'])) : '';
		$cms_no=$getOldRec['cms_no'];

	}
}

if(intval($previousID) == 0)
{
	echo 'Invalid PIS';
	exit;
}

$where = "WHERE  pis_id='".base64_decode($_GET['id'])."'";
$OrderBY = " ORDER BY id DESC ";



$Query = "SELECT * FROM insurance_short_premium_gtfs ".$where.$OrderBY;

//echo $Query;
#exit;

$objDB->setQuery($Query);
$rs = $objDB->select();

$pageRecordCount = count($rs);
#echo $pageRecordCount;





##### FETCHING RECORDS FOR PREVIOUS PIS ##########

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> PIS </title>
  <meta name="Generator" content="">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
	<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
	<script type="text/javascript">
	<!--
		function pisCheck()
		{
			if(document.cms_frm.deposit_date.value.search(/\S/) == -1)
			{
				alert("Please Select Deposit Date");
				return false;
			}
			if(document.cms_frm.cms_no.value.search(/\S/) == -1)
			{
				alert("Please Select CMS No.");
				return false;
			}
		}
	//-->
	</script>
 </head>

 <body>
 <center>
 <div>
	 <table style="width:770px;">		 
		 <tr>
			<td colspan="3" align="center">
				<form method="post" action="" name="cms_frm" onsubmit="return pisCheck()">
					
				
					<strong>GOLDEN TRUST FINANCIAL SERVICES<br />
					PAY IN SLIP - <?php echo $getOldRec['pis_mode']; ?><br/>
					Bank A/C No. : _________________<br />
					Branch Name : <?php $branch_name = find_branch_name($getOldRec['branch_id']); echo $branch_name; ?> <br />
					Deposited On : <input type="text" name="deposit_date" id="deposit_date" value="<?php echo ($deposit_date) ;?>" readonly > &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.cms_frm.deposit_date,'dd-mm-yyyy',this)" width="20" height="18">
					<br />
					CMS No. : <input type="text" name="cms_no" id="cms_no" value="<?php echo $cms_no;?>" <?php if(intval($_SESSION[ROLE_ID]) == 4) { 
						if($cms_no != ''){echo "readonly"; }}?>>&nbsp;&nbsp;
					<?php if($deposit_date == '') { ?>
						<input type="submit" name="btnSubmit" value="Submit">
					<?php }
					if((intval($_SESSION[ROLE_ID]) == 1) || (intval($_SESSION[ROLE_ID]) == 2)) { 
						if($deposit_date != ''){
						?>
						<input type="submit" name="btnSubmit" value="Submit"><?php }} ?>
					</strong>
				</form>
			</td>
		 </tr>
		 <tr>
			<td colspan="3" align="center">
				<strong>
					<div style="width:49%; float: left; text-align:left; ">Pay In Slip Number : <?php echo (intval($previousID) != 0 ? str_pad($previousID, 7, "0", STR_PAD_LEFT) : ''); ?></div>
					<div style="width:49%; float: left; text-align:right; ">Date : <?php echo date('d/m/Y', strtotime($getOldRec['pis_date'])); ?></div>
				</strong>
			</td>
		 </tr>
		 		 
		 <tr>
			<td colspan="3">&nbsp;</td>
		 </tr>

		 <?php
			if($getOldRec['pis_mode'] == 'CASH')
			{
		 ?>

		<tr>
			<td colspan="3" align="center" style="width:100%">
				<table cellpadding=0 cellspacing=0 width="770">
					<tr>
						<td width="70"><strong>SL. No.</strong></td>
						<td width="120" ><strong>Application No.</strong></td>
						<!-- <td width="280"><strong>Receipt No.</strong></td> -->
						<td width="100"><strong>Deposit Date</strong></td>
						<td width="50" style="text-align:right;"><strong>Cash Amount</strong></td>
						<!-- <td width="50" style="text-align:right;"><strong>Tax</strong></td> -->
						<td width="100" align="right"><strong>Collector</strong></td>
					</tr>
				
				
		<?php 
			if($pageRecordCount > 0)	
			{
				#$transaction_charges = 0.00;
				#$amount_given = 0.00;
				for($i=0;$i<count($rs);$i++)
				{
				
		?>
		
					<tr>
						<td width="70" style="padding-top:5px;"><?php echo $i+1; ?>.</td>
						<td width="120" style="padding-top:5px;"><?php echo $rs[$i]['application_no']; ?></td>
						<!-- <td width="280" style="padding-top:5px;"><?php echo $rs[$i]['receipt_number']; ?></td> -->
						<td width="100" style="padding-top:5px;"><?php echo date('d/m/Y', strtotime($rs[$i]['deposit_date'])); ?>&nbsp;&nbsp;</td>
						<td width="50" style="padding-top:5px;text-align:right;">
						<?php 
							//echo $rs[$i]['amount']; 
							$cash_amount = 0.00;
							if($rs[$i]['sp_payment_mode'] == 'CASH')
							{
								$cash_amount += $rs[$i]['amount'];
								//$idstr_main .= $rs[$i]['id'].',';
							}

							

							echo $cash_amount;
							
						?>
						</td>
						<!-- <td width="50" style="padding-top:5px;text-align:right;"><?php echo $rs[$i]['transaction_charges']; ?></td> -->
						<td width="100" style="padding-top:5px; text-align:right"><?php echo find_branch_user_name($rs[$i]['branch_id']); ?></td>
					</tr>
				
				
			<?php
				$transaction_charges += $rs[$i]['transaction_charges'];
				$amount_given += $cash_amount;
				}
			}	
			?>
					<tr>
						<td colspan="7"><hr /></td>						
					</tr>
					<tr>
						<td width="70" style="padding-top:5px;">&nbsp;</td>
						<td width="120" style="padding-top:5px;">&nbsp;</td>
						<td width="280" style="padding-top:5px;">&nbsp;</td>
						<td width="100" style="padding-top:5px;"><strong>Total (<?php echo number_format(($amount_given + $transaction_charges),'2','.','');?>)</strong></td>
						<td width="50" style="padding-top:5px;text-align:right;"><strong><?php echo number_format($amount_given,'2','.',''); ?></strong>
						</td>
						<td width="50" style="padding-top:5px;text-align:right;"><strong><?php echo number_format($transaction_charges,'2','.',''); ?></strong>
						</td>
						<td width="100" style="padding-top:5px;text-align:right;"><strong>&nbsp;</strong>
						</td>
					</tr>
					<tr>
						<td colspan="7"><hr /></td>						
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" width="100%" align="center">
					<form name="frm" method="post" action="">
						<table cellpadding="0" cellspacing="0" width="500">
							<tr>
								<td width="200" align="left"><strong>Denomination</strong></td>
								<td width="150" align="center"><strong>No. Of Currencies</strong></td>
								<td width="150" align="left"><strong>Total Amount</strong></td>
							</tr>
							<tr>
								<td width="200" align="left">Rs.1000/-</td>
								<td width="150" align="center"><input type="text" name="currThousand" id="currThousand" value="<?php echo $currThousand; ?>" class="inplogin" style="width:50px;" <?php echo $readonly; ?>></td>
								<td width="150" align="left"><input type="text" name="valThousand" id="valThousand" value="<?php echo $currThousand * 1000; ?>" class="inplogin" style="width:100px;" readonly></td>
							</tr>
							<tr>
								<td width="200" align="left">Rs.500/-</td>
								<td width="150" align="center">
								<input type="text" name="currFiveHundred" id="currFiveHundred" value="<?php echo $currFiveHundred; ?>" class="inplogin" style="width:50px;" <?php echo $readonly; ?>></td>
								<td width="150" align="left">
								<input type="text" name="valFiveHundred" id="valFiveHundred" class="inplogin" value="<?php echo $currFiveHundred * 500; ?>" style="width:100px;" readonly>
								</td>
							</tr>
							<tr>
								<td width="200" align="left">Rs.100/-</td>
								<td width="150" align="center">
								<input type="text" name="currHundred" id="currHundred" value="<?php echo $currHundred; ?>" class="inplogin" style="width:50px;" <?php echo $readonly; ?>>
								</td>
								<td width="150" align="left">
									<input type="text" name="valHundred" id="valHundred" value="<?php echo $currHundred * 100; ?>" class="inplogin" style="width:100px;" readonly>
								</td>
							</tr>
							<tr>
								<td width="200" align="left">Rs.50/-</td>
								<td width="150" align="center">
									<input type="text" name="currFifty" id="currFifty" value="<?php echo $currFifty; ?>" class="inplogin" style="width:50px;" <?php echo $readonly; ?>>
								</td>
								<td width="150" align="left">
									<input type="text" name="valFifty" id="valFifty" value="<?php echo $currFifty * 50; ?>" class="inplogin" style="width:100px;" readonly>
								</td>
							</tr>
							<tr>
								<td width="200" align="left">Rs.20/-</td>
								<td width="150" align="center">
									<input type="text" name="currTwenty" id="currTwenty" value="<?php echo $currTwenty; ?>" class="inplogin" style="width:50px;" <?php echo $readonly; ?>>
								</td>
								<td width="150" align="left">
									<input type="text" name="valTwenty" id="valTwenty" value="<?php echo $currTwenty * 20; ?>" class="inplogin" style="width:100px;" readonly>
								</td>
							</tr>
							<tr>
								<td width="200" align="left">Rs.10/-</td>
								<td width="150" align="center">
									<input type="text" name="currTen" id="currTen" value="<?php echo $currTen; ?>" class="inplogin" style="width:50px;" <?php echo $readonly; ?>>
								</td>
								<td width="150" align="left">
									<input type="text" name="valTen" id="valTen" value="<?php echo $currTen * 10; ?>" class="inplogin" style="width:100px;" readonly>
								</td>
							</tr>
							<tr>
								<td width="200" align="left">Rs.5/-</td>
								<td width="150" align="center">
									<input type="text" name="currFive" id="currFive" value="<?php echo $currFive; ?>" class="inplogin" style="width:50px;" <?php echo $readonly; ?>>
								</td>
								<td width="150" align="left">
									<input type="text" name="valFive" id="valFive" value="<?php echo $currFive * 5; ?>" class="inplogin" style="width:100px;" readonly>
								</td>
							</tr>
							<tr>
								<td width="200" align="left">Rs.2/-</td>
								<td width="150" align="center">
									<input type="text" name="currTwo" id="currTwo" value="<?php echo $currTwo; ?>" class="inplogin" style="width:50px;" <?php echo $readonly; ?>>
								</td>
								<td width="150" align="left">
									<input type="text" name="valTwo" id="valTwo" value="<?php echo $currTwo * 2; ?>" class="inplogin" style="width:100px;" readonly>
								</td>
							</tr>
							<tr>
								<td width="200" align="left">Re.1/-</td>
								<td width="150" align="center">
									<input type="text" name="currOne" id="currOne" value="<?php echo $currOne; ?>" class="inplogin" style="width:50px;" <?php echo $readonly; ?>>
								</td>
								<td width="150" align="left">
									<input type="text" name="valOne" id="valOne" value="<?php echo $currOne * 1; ?>" class="inplogin" style="width:100px;" readonly>
								</td>
							</tr>
							<tr>
								<td width="200" align="left"><strong>Total</strong></td>
								<td width="150" align="center">&nbsp;</td>
								<td width="150" align="left">
									<input type="text" name="valTotal" id="valTotal" value="<?php echo $valTotal; ?>" class="inplogin" style="width:100px;" readonly>
								</td>
							</tr>
							<tr>
								<td align="center" colspan="3">
									<input type="hidden" value="<?php echo $transaction_charges; ?>" name="phpSum" id="phpSum">
					<?php
				#echo '<br />Previous ID is : '.$previousID.'<br />';
						if(intval($previousID) == 0)
						{
					?>
									<input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="validate()">
					<?php
						}	
					?>
								</td>
							</tr>
					<?php
						if(intval($previousID) != 0)
						{
					?>
							<tr>
								<td colspan="3" align="center" style="padding-bottom:20px;padding-top:20px;">
									<strong>Certification</strong><br />
									I hereby certify that the physical cash has been verified by me with this pay in slip and the same is found to be in order.								
								</td>
							</tr>							
							
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Prepared by (Name) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Emp Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Prepared by (Signature) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Prepared at (Date &amp; Time)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo date('d/m/Y H:i:s', strtotime($prepared_at)); ?>
								</td>
							</tr>
							<tr>
								<td colspan="3" align="center" style="padding-top:20px;">
									Verified the Pay in Slip Control Statement &amp; tallied with the instruments on Hand.					
								</td>
							</tr>	
					<?php
						}	
					?>


						</table>
						
					</form>
				</td>
			</tr>
		 <?php
			}
			if($getOldRec['pis_mode'] == 'DD')
			{
		?>
			<tr>
			<td colspan="3" align="center" style="width:100%">
				<table cellpadding=0 cellspacing=0 width="770">
					<tr>
						<td width="30"><strong>SL. No.</strong></td>
						<!-- <td width="120" ><strong>Application No.</strong></td> -->
						<td width="280"><strong>Receipt No.</strong></td>
						<td width="100"><strong>DD Date</strong></td>
						<td width="100"><strong>DD Number</strong></td>
						<td width="100"><strong>Drawee Bank</strong></td>
						<td width="50" style="text-align:right;"><strong>DD Amount</strong></td>
						<!-- <td width="50" style="text-align:right;"><strong>Tax</strong></td> -->
						<td width="100" align="right"><strong>Collector</strong></td>
					</tr>
				
				
		<?php 
			if($pageRecordCount > 0)	
			{
				#$transaction_charges = 0.00;
				#$amount_given = 0.00;
				for($i=0;$i<count($rs);$i++)
				{
				
		?>
		
					<tr>
						<td width="30" style="padding-top:5px;"><?php echo $i+1; ?>.</td>
						<!-- <td width="120" style="padding-top:5px;"><?php echo $rs[$i]['application_no']; ?></td> -->
						<td width="280" style="padding-top:5px;"><?php echo $rs[$i]['application_no']; ?></td>
						<td width="100" style="padding-top:5px;"><?php echo date('d/m/Y', strtotime($rs[$i]['sp_dd_date'])); ?></td>
						<td width="100" style="padding-top:5px;"><?php echo $rs[$i]['sp_dd_no']; ?></td>
						<td width="100" style="padding-top:5px;"><?php echo $rs[$i]['sp_dd_bank']; ?></td>
						<td width="50" style="padding-top:5px;text-align:right;">
						<?php 
							//echo $rs[$i]['amount']; 
							$dd_amount = 0.00;
							

							if($rs[$i]['sp_payment_mode'] == 'DD')
							{
								$dd_amount += $rs[$i]['amount'];
								//$idstr_alt .= $rs[$i]['id'].',';
							}

							echo $dd_amount;
							
						?>
						</td>
						<!-- <td width="50" style="padding-top:5px;text-align:right;"><?php echo $rs[$i]['transaction_charges']; ?></td> -->
						<td width="100" style="padding-top:5px; text-align:right"><?php echo find_branch_user_name($rs[$i]['branch_id']); ?></td>
					</tr>
				
				
			<?php
				$transaction_charges += $rs[$i]['transaction_charges'];
				$amount_given += $dd_amount;
				}
			}	
			?>
					<tr>
						<td colspan="8"><hr /></td>						
					</tr>
					<tr>
						<td width="30" style="padding-top:5px;">&nbsp;</td>
						<td width="280" style="padding-top:5px;">&nbsp;</td>
						<td width="100" style="padding-top:5px;">&nbsp;</td>
						<td width="100" style="padding-top:5px;">&nbsp;</td>
						<td width="100" style="padding-top:5px;"><strong>Total (<?php echo number_format(($amount_given + $transaction_charges),'2','.','');?>)</strong></td>
						<td width="50" style="padding-top:5px;text-align:right;"><strong><?php echo number_format($amount_given,'2','.',''); ?></strong>
						</td>
						<!-- <td width="50" style="padding-top:5px;text-align:right;"><strong><?php echo number_format($transaction_charges,'2','.',''); ?></strong>
						</td> -->
						<td width="100" style="padding-top:5px;text-align:right;"><strong>&nbsp;</strong>
						</td>
					</tr>
					<tr>
						<td colspan="8"><hr /></td>						
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" width="100%" align="center">
					<form name="frm" method="post" action="">
						<table cellpadding="0" cellspacing="0" width="500">
							
							<tr>
								<td align="center" colspan="3">
									<input type="hidden" value="<?php echo $transaction_charges; ?>" name="phpSum" id="phpSum">
					<?php
				#echo '<br />Previous ID is : '.$previousID.'<br />';
						if(intval($previousID) == 0)
						{
					?>
									<input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="javascript:document.frm.submit();">
					<?php
						}	
					?>
								</td>
							</tr>
					<?php
						if(intval($previousID) != 0)
						{
					?>
							<tr>
								<td colspan="3" align="center" style="padding-bottom:20px;padding-top:20px;">
									<strong>Certification</strong><br />
														
								</td>
							</tr>	
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Number of cheques on hand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ___________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Prepared by (Name) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Emp Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Prepared by (Signature) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Prepared at (Date &amp; Time) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo date('d/m/Y H:i:s', strtotime($prepared_at)); ?>
								</td>
							</tr>
							<tr>
								<td colspan="3" align="center" style="padding-top:20px;">
									Verified the Pay in Slip Control Statement &amp; tallied with the instruments on Hand.					
								</td>
							</tr>	

							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Verified by (Name) 
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Emp Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Verified by (Signature) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Verified at (Date &amp; Time) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Pick up person's signature &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Bank Acknowledgement &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Bank reference Number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
					<?php
						}	
					?>


						</table>
						
					</form>
				</td>
			</tr>
		<?php
			}
			if($getOldRec['pis_mode'] == 'CHEQUE')
			{
		?>
			<tr>
			<td colspan="3" align="center" style="width:100%">
				<table cellpadding=0 cellspacing=0 width="770">
					<tr>
						<td width="30"><strong>SL. No.</strong></td>
						<!-- <td width="120" ><strong>Application No.</strong></td> -->
						<td width="280"><strong>Application No.</strong></td>
						<td width="100"><strong>Chq. Date</strong></td>
						<td width="100"><strong>Chq. Number</strong></td>
						<td width="100"><strong>Drawee Bank</strong></td>
						<td width="50" style="text-align:right;"><strong>Cheque Amount</strong></td>
						<!-- <td width="50" style="text-align:right;"><strong>Tax</strong></td> -->
						<td width="100" align="right"><strong>Collector</strong></td>
					</tr>
				
				
		<?php 
			if($pageRecordCount > 0)	
			{
				#$transaction_charges = 0.00;
				#$amount_given = 0.00;
				for($i=0;$i<count($rs);$i++)
				{
				
		?>
		
					
					
					
					
					<tr>
						<td width="30" style="padding-top:5px;"><?php echo $i+1; ?>.</td>
						<!-- <td width="120" style="padding-top:5px;"><?php echo $rs[$i]['application_no']; ?></td> -->
						<td width="280" style="padding-top:5px;"><?php echo $rs[$i]['application_no']; ?></td>
						<td width="100" style="padding-top:5px;"><?php echo date('d/m/Y', strtotime($rs[$i]['sp_dd_date'])); ?></td>
						<td width="100" style="padding-top:5px;"><?php echo $rs[$i]['sp_dd_no']; ?></td>
						<td width="100" style="padding-top:5px;"><?php echo $rs[$i]['sp_dd_bank']; ?></td>
						<td width="50" style="padding-top:5px;text-align:right;">
						<?php 
							//echo $rs[$i]['amount']; 
							$cheque_amount = 0.00;
							if($rs[$i]['sp_payment_mode'] == 'CHEQUE')
							{
								$cheque_amount += $rs[$i]['amount'];
								//$idstr_main .= $rs[$i]['id'].',';
							}

							
							echo $cheque_amount;
							
						?>
						</td>
						<!-- <td width="50" style="padding-top:5px;text-align:right;"><?php echo $rs[$i]['transaction_charges']; ?></td> -->
						<td width="100" style="padding-top:5px; text-align:right"><?php echo find_branch_user_name($rs[$i]['branch_id']); ?></td>
					</tr>
				
				
			<?php
				//$transaction_charges += $rs[$i]['transaction_charges'];
			$amount_given += $cheque_amount;
				}
			}	
			?>
					<tr>
						<td colspan="8"><hr /></td>						
					</tr>
					<tr>
						<td width="30" style="padding-top:5px;">&nbsp;</td>
						<td width="280" style="padding-top:5px;">&nbsp;</td>
						<td width="100" style="padding-top:5px;">&nbsp;</td>
						<td width="100" style="padding-top:5px;">&nbsp;</td>
						<td width="100" style="padding-top:5px;"><strong>Total (<?php echo number_format(($amount_given + $transaction_charges),'2','.','');?>)</strong></td>
						<td width="50" style="padding-top:5px;text-align:right;"><strong><?php echo number_format($amount_given,'2','.',''); ?></strong>
						</td>
						<!-- <td width="50" style="padding-top:5px;text-align:right;"><strong><?php echo number_format($transaction_charges,'2','.',''); ?></strong> -->
						</td>
						<td width="100" style="padding-top:5px;text-align:right;"><strong>&nbsp;</strong>
						</td>
					</tr>
					<tr>
						<td colspan="8"><hr /></td>						
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" width="100%" align="center">
					<form name="frm" method="post" action="">
						<table cellpadding="0" cellspacing="0" width="500">
							
							<tr>
								<td align="center" colspan="3">
									<input type="hidden" value="<?php echo $transaction_charges; ?>" name="phpSum" id="phpSum">
					<?php
				#echo '<br />Previous ID is : '.$previousID.'<br />';
						if(intval($previousID) == 0)
						{
					?>
									<input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="javascript:document.frm.submit();">
					<?php
						}	
					?>
								</td>
							</tr>
					<?php
						if(intval($previousID) != 0)
						{
					?>
							<tr>
								<td colspan="3" align="center" style="padding-bottom:20px;padding-top:20px;">
									<strong>Certification</strong><br />
														
								</td>
							</tr>	
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Number of cheques on hand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ___________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Prepared by (Name) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Emp Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Prepared by (Signature) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Prepared at (Date &amp; Time) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo date('d/m/Y H:i:s', strtotime($prepared_at)); ?>
								</td>
							</tr>
							<tr>
								<td colspan="3" align="center" style="padding-top:20px;">
									Verified the Pay in Slip Control Statement &amp; tallied with the instruments on Hand.					
								</td>
							</tr>	

							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Verified by (Name) 
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Emp Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Verified by (Signature) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Verified at (Date &amp; Time) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Pick up person's signature &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Bank Acknowledgement &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
							<tr>
								<td colspan="3" align="left" style="padding-bottom:10px;">
									Bank reference Number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________________________
								</td>
							</tr>
					<?php
						}	
					?>


						</table>
						
					</form>
				</td>
			</tr>
		<?php
			}
		 ?>
		 
	 </table>
 </div>
 <div style="height:50px;">&nbsp;</div>
 
 </center>
 <script type="text/javascript">
 <!--
	function validate()
	{
		var valThousand = parseInt(document.getElementById("currThousand").value * 1000);
		var valFiveHundred = parseInt(document.getElementById("currFiveHundred").value * 500);
		var valHundred = parseInt(document.getElementById("currHundred").value * 100);
		var valFifty = parseInt(document.getElementById("currFifty").value * 50);
		var valTwenty = parseInt(document.getElementById("currTwenty").value *20);
		var valTen = parseInt(document.getElementById("currTen").value * 10);
		var valFive = parseInt(document.getElementById("currFive").value * 5);
		var valTwo = parseInt(document.getElementById("currTwo").value * 2);
		var valOne = parseInt(document.getElementById("currOne").value * 1);
		var valTotal = valThousand + valFiveHundred + valHundred + valFifty + valTwenty + valTen + valFive + valTwo + valOne;
		var phpSum = parseInt(document.getElementById("phpSum").value);
		//alert(valTotal);
		//alert(phpSum);

		document.getElementById("valThousand").value = valThousand;
		document.getElementById("valFiveHundred").value = valFiveHundred;
		document.getElementById("valHundred").value = valHundred;
		document.getElementById("valFifty").value = valFifty;
		document.getElementById("valTwenty").value = valTwenty;
		document.getElementById("valTen").value = valTen;
		document.getElementById("valFive").value = valFive;
		document.getElementById("valTwo").value = valTwo;
		document.getElementById("valOne").value = valOne;
		document.getElementById("valTotal").value = valTotal;

		if(phpSum != valTotal)
		{
			alert("Invalid Denomination");
		}
		else
		{
			//alert ("success");
			document.frm.submit();
		}

		
	}
 //-->
 </script>
 </body>
</html>
