<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");

date_default_timezone_set('Asia/Calcutta');

$objDB = new DB();
$pageOwner = "'admin','superadmin','hub','branch','subadmin','division'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

$idstr = '';
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

$readonly = ''; // made the textboxes readonly if we get any previous entry
$previousID = 0; // ID of the previous PIS record

$transaction_charges = 0.00;
$amount_given = 0.00;


$objDB = new DB();

extract($_POST);

if(isset($_POST['phpSum']) && $_POST['phpSum'] != '')
{
	mysql_query("INSERT INTO pis_sp_renewal_master SET 
								branch_id='".$_SESSION[ADMIN_SESSION_VAR]."',
								pis_date='".date('Y-m-d', strtotime($_SESSION['from_date']))."',
								prepared_at='".date('Y-m-d H:i:s')."',
								pis_mode='".$_SESSION['short_premium_payment_mode']."',
								total='".$phpSum."',
								thousand='".$currThousand."',
								fiveHundred='".$currFiveHundred."',
								hundred='".$currHundred."',
								fifty='".$currFifty."',
								twenty='".$currTwenty."',
								ten='".$currTen."',
								five='".$currFive."',
								two='".$currTwo."',
								one='".$currOne."'
								
								");	
	
	$previousID = mysql_insert_id();
	
		$update_main_payment = "UPDATE renewal_short_premium SET pis_id='".$previousID."' WHERE id IN(".$_POST['idstr_alt_payment'].")";

		//echo $update_main_payment;
		//exit;
		mysql_query($update_main_payment);
	
	//echo $updateInstallment;
	//exit;



	?>
				<script type="text/javascript">
				<!--
					window.opener.document.addForm.submit();
					window.close();
				//-->
				</script>

	<?php
}

$where = "WHERE amount != '0' AND sp_payment_mode != '' AND is_deleted != 1";
$OrderBY = " ORDER BY id DESC ";

	$_SESSION['branch_name'] = $_SESSION[ADMIN_SESSION_VAR];
	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '') 
	{
		$where.= ' AND 	branch_id ="'.$_SESSION['branch_name'].'"';
	}


	if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
	{
		$where.= ' AND deposit_date >= "'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
	}
	
	if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
	{
		$where.= ' AND deposit_date <= "'.date('Y-m-d', strtotime($_SESSION['to_date'])).'"';
	}

	if(isset($_SESSION['short_premium_payment_mode']) && $_SESSION['short_premium_payment_mode'] != '') 
	{
		$where.= ' AND  sp_payment_mode = "'.$_SESSION['short_premium_payment_mode'].'" AND pis_id=0';
	}

$Query = "SELECT * FROM renewal_short_premium ".$where.$OrderBY;

#echo $Query;

$objDB->setQuery($Query);
$rs = $objDB->select();

$pageRecordCount = count($rs);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> PIS </title>
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
			<td colspan="3" align="center">
				<strong>LICI <br />
				PAY IN SLIP - <?php echo $_SESSION['short_premium_payment_mode']; ?><br/>
				Bank A/C No. : _________________<br />
				Branch Name : <?php $branch_name = find_branch_name($_SESSION[ADMIN_SESSION_VAR]); echo $branch_name; ?> <br />
				Deposited On : _________________
				</strong>
			</td>
		 </tr>
		 <tr>
			<td colspan="3" align="center">
				<strong>
					<div style="width:49%; float: left; text-align:left; ">Pay In Slip Number (New Entry) : <?php echo (intval($previousID) != 0 ? str_pad($previousID, 7, "0", STR_PAD_LEFT) : ''); ?></div>
					<div style="width:49%; float: left; text-align:right; ">Date : <?php echo date('d/m/Y', strtotime($_SESSION['from_date'])); ?></div>
				</strong>
			</td>
		 </tr>
		 		 
		 <tr>
			<td colspan="3">&nbsp;</td>
		 </tr>

		 <?php
			if($_SESSION['short_premium_payment_mode'] == 'CASH')
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
			$idstr_main = ''; // For main payment mode
			$idstr_alt = ''; // For alternative payment mode
			if($pageRecordCount > 0)	
			{
				
				for($i=0;$i<count($rs);$i++)
				{
					//$idstr.= $rs[$i]['id'].',';
		?>
		
					<tr>
						<td width="70" style="padding-top:5px;"><?php echo $i+1; ?>.</td>
						<td width="120" style="padding-top:5px;"><?php echo $rs[$i]['application_no']; ?></td>
						<!-- <td width="280" style="padding-top:5px;"><?php echo $rs[$i]['receipt_number']; ?></td> -->
						<td width="100" style="padding-top:5px;"><?php echo date('d/m/Y', strtotime($rs[$i]['deposit_date'])); ?></td>
						<td width="50" style="padding-top:5px;text-align:right;">
						
						<?php 
							//echo $rs[$i]['amount']; 
							$cash_amount = 0.00;
							
							if($rs[$i]['sp_payment_mode'] == 'CASH')
							{
								$cash_amount += $rs[$i]['amount'];
								$idstr_alt .= $rs[$i]['id'].',';
								//echo $idstr_alt;
								//exit;
							}

							echo $cash_amount;
							
						?>
						
						</td>
						<!-- <td width="50" style="padding-top:5px;text-align:right;"><?php echo $rs[$i]['transaction_charges']; ?></td> -->
						<td width="100" style="padding-top:5px; text-align:right"><?php echo find_branch_user_name($rs[$i]['branch_id']); ?></td>
					</tr>
				
				
			<?php
				//$transaction_charges += $rs[$i]['transaction_charges'];
				$amount_given += $cash_amount;
				}

				$idstr_main = trim($idstr_main, ',');
				$idstr_alt = trim($idstr_alt, ',');

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
						<!-- <td width="50" style="padding-top:5px;text-align:right;"><strong><?php echo number_format($transaction_charges,'2','.',''); ?></strong>
						</td> -->
						<td width="100" style="padding-top:5px; text-align:right">&nbsp;</td>
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
					<input type="hidden" name="idstr_main_payment" value="<?php echo $idstr_main; ?>" >
					<input type="hidden" name="idstr_alt_payment" value="<?php echo $idstr_alt; ?>" >
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
									<input type="hidden" value="<?php echo ($amount_given + $transaction_charges); ?>" name="phpSum" id="phpSum">
					<?php				
						$oldPisID = findOldPisId(date('Y-m-d',strtotime($_GET['pisdate'])), $_GET['pismode'], $_SESSION[ADMIN_SESSION_VAR]);
						
					?>
					<?php
						if(intval(($amount_given + $transaction_charges)) > 0) // transaction happens
						{
					?>
									<input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="validate()">
					<?php
						}
						if((intval($oldPisID) == 0) && ((intval($amount_given + $transaction_charges)) == 0))
						{
						
					?>
					<input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="validate()">
					<?php
						}
					?>
								</td>
							</tr>
					


						</table>
						
					</form>
				</td>
			</tr>
		 <?php
			}
			if($_SESSION['short_premium_payment_mode'] == 'DD')
			{				
		?>
			<tr>
			<td colspan="3" align="center" style="width:100%">
				<table cellpadding=0 cellspacing=0 width="670">
					<tr>
						<td width="30"><strong>SL. No.</strong></td>
						<!-- <td width="120" ><strong>Application No.</strong></td> -->
						<td width="280"><strong>Application No.</strong></td>
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
				$idstr_main = ''; // For main payment mode
				$idstr_alt = ''; // For alternative payment mode
				for($i=0;$i<count($rs);$i++)
				{
					$idstr.= $rs[$i]['id'].',';
				
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
								$idstr_alt .= $rs[$i]['id'].',';
							}

							

							echo $dd_amount;
							
						?>
						</td>
						<!-- <td width="50" style="padding-top:5px;text-align:right;"><?php echo $rs[$i]['transaction_charges']; ?></td> -->
						<td width="100" style="padding-top:5px; text-align:right"><?php echo find_branch_user_name($rs[$i]['branch_id']); ?></td>
					</tr>
				
				
			<?php
				//$transaction_charges += $rs[$i]['transaction_charges'];
				$amount_given += $dd_amount;
				}
				$idstr_main = trim($idstr_main, ',');
				$idstr_alt = trim($idstr_alt, ',');
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
						<td width="50" style="padding-top:5px;text-align:right;"><strong><?php echo number_format($transaction_charges,'2','.',''); ?></strong>
						<td width="100" style="padding-top:5px;text-align:right;"><strong>&nbsp;</strong>
						</td>
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
					<input type="hidden" name="idstr_main_payment" value="<?php echo $idstr_main; ?>" >
					<input type="hidden" name="idstr_alt_payment" value="<?php echo $idstr_alt; ?>" >
						<table cellpadding="0" cellspacing="0" width="500">
							
							<tr>
								<td align="center" colspan="3">
									<input type="hidden" value="<?php echo ($amount_given + $transaction_charges); ?>" name="phpSum" id="phpSum">
					
					<?php				
						$oldPisID = findOldPisId(date('Y-m-d',strtotime($_GET['pisdate'])), $_GET['pismode'], $_SESSION[ADMIN_SESSION_VAR]);
						
					?>
					<?php
						if(intval(($amount_given + $transaction_charges)) > 0) // transaction happens
						{
					?>
									<!-- <input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="validate()"> -->
									<input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="javascript:document.frm.submit();">
					<?php
						}
						if((intval($oldPisID) == 0) && ((intval($amount_given + $transaction_charges)) == 0))
						{
						
					?>
					<input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="javascript:document.frm.submit();">
					<?php
						}
					?>
								</td>
							</tr>
					


						</table>
						
					</form>
				</td>
			</tr>
		<?php
			}
			if($_SESSION['short_premium_payment_mode'] == 'CHEQUE')
			{				
		?>
			<tr>
			<td colspan="3" align="center" style="width:100%">
				<table cellpadding=0 cellspacing=0 width="670">
					<tr>
						<td width="30"><strong>SL. No.</strong></td>
						<!-- <td width="120" ><strong>Application No.</strong></td> -->
						<td width="280"><strong>Application  No.</strong></td>
						<td width="100"><strong>Chq. Date</strong></td>
						<td width="100"><strong>Chq. Number</strong></td>
						<td width="100"><strong>Drawee Bank</strong></td>
						<td width="50" style="text-align:right;"><strong>Chq. Amount</strong></td>
						<!-- <td width="50" style="text-align:right;"><strong>Tax</strong></td> -->
						<td width="100" align="right"><strong>Collector</strong></td>
					</tr>
				
				
		<?php 
			if($pageRecordCount > 0)	
			{
				$idstr_main = ''; // For main payment mode
				$idstr_alt = ''; // For alternative payment mode
				for($i=0;$i<count($rs);$i++)
				{
					//$idstr.= $rs[$i]['id'].',';
				
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
								$idstr_alt .= $rs[$i]['id'].',';
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
				$idstr_main = trim($idstr_main, ',');
				$idstr_alt = trim($idstr_alt, ',');
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
						<!-- <td width="50" style="padding-top:5px;text-align:right;"><strong><?php echo number_format($transaction_charges,'2','.',''); ?></strong></td> -->
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
					<input type="hidden" name="idstr_main_payment" value="<?php echo $idstr_main; ?>" >
					<input type="hidden" name="idstr_alt_payment" value="<?php echo $idstr_alt; ?>" >
						<table cellpadding="0" cellspacing="0" width="500">
							
							<tr>
								<td align="center" colspan="3">
									<input type="hidden" value="<?php echo ($amount_given + $transaction_charges); ?>" name="phpSum" id="phpSum">
					<?php				
						$oldPisID = findOldPisId(date('Y-m-d',strtotime($_GET['pisdate'])), $_GET['pismode'], $_SESSION[ADMIN_SESSION_VAR]);
						
					?>
					<?php
						if(intval(($amount_given + $transaction_charges)) > 0) // transaction happens
						{
					?>
									<!-- <input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="validate()"> -->
						<input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="javascript:document.frm.submit();">
					<?php
						}
						if((intval($oldPisID) == 0) && ((intval($amount_given + $transaction_charges)) == 0))
						{
						
					?>
					<input type="button" name="btnValidate" value="Validate Total" class="inplogin" onclick="javascript:document.frm.submit();">
					<?php
						}
					?>
								</td>
							</tr>
					


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
