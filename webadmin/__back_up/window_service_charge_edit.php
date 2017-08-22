<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");

$msg = '';
if(!isset($_SESSION[ADMIN_SESSION_VAR]) || $_SESSION[ADMIN_SESSION_VAR] != 2)
{
	//echo 'Hi';
	header("location: index.php");
	exit();
}

$objDB = new DB();
	

// Write functions here


if(isset($_GET['id']) && !empty($_GET['id']))
{
	

	$invoice_id = base64_decode($_GET['id']);
	//echo $invoice_id;
	if(isset($_POST['transaction_charges']) && $_POST['transaction_charges'] != '')
	{
		//echo '<pre>';
		//print_r($_POST);
		//echo '</pre>';
		extract($_POST);
		

		$update_installment = "UPDATE installment_master SET 										
										deposit_date = '".date('Y-m-d', strtotime($deposit_date))."',
										receipt_date = '".date('Y-m-d', strtotime($receipt_date))."',
										transaction_charges = '".floatval($transaction_charges)."',
										first_name = '".realTrim($first_name)."',
										last_name = '".realTrim($last_name)."',
										agent_code = '".realTrim($agent_code)."'
										
										WHERE 
										id='".$invoice_id."'
				";
				//echo $update_installment;
				mysql_query($update_installment);

?>
<script type="text/javascript">
<!--
	window.opener.document.addForm.submit();
	window.close();
//-->
</script>

<?php
	}
	$selTransaction = mysql_query("SELECT * FROM installment_master WHERE id='".$invoice_id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
			$getTransaction = mysql_fetch_assoc($selTransaction);
			//print_r($getTransaction);
			$branch_name = find_branch_name($getTransaction['branch_id']);
			$trusted_id = find_customer_id_through_id($getTransaction['customer_id']);
			$application_no = $getTransaction['application_no'];
			$deposit_date = date('d-m-Y', strtotime($getTransaction['deposit_date']));
			$receipt_date = date('d-m-Y', strtotime($getTransaction['receipt_date']));
			$agent_code = $getTransaction['agent_code'];
			$tenure = $getTransaction['tenure'];
			$receipt_number = $getTransaction['receipt_number'];
			$transaction_id = $getTransaction['transaction_id'];
			$comitted_amount = $getTransaction['comitted_amount'];
			$amount = $getTransaction['amount'];
			$payment_mode = $getTransaction['payment_mode'];
			$payment_mode_service = $getTransaction['payment_mode_service'];

			$dd_number = $getTransaction['dd_number'];
			$dd_bank_name = $getTransaction['dd_bank_name'];
			$dd_date = date('d-m-Y', strtotime($getTransaction['dd_date']));
			if($dd_date == '01-01-1970'){ $dd_date=''; }
			$first_name = $getTransaction['first_name'];
			$last_name = $getTransaction['last_name'];
			$customer_type = $getTransaction['customer_type'];
			$employee_code = $getTransaction['employee_code'];
			$penalty = $getTransaction['penalty'];
			$gender = $getTransaction['gender'];
			$dob_original = $getTransaction['dob_original'];
			$age_proof = $getTransaction['age_proof'];
			$id_proof = $getTransaction['id_proof'];
			$fathers_name = $getTransaction['fathers_name'];
			$guardian_name = $getTransaction['guardian_name'];
			$address1 = $getTransaction['address1'];
			$address_proof = $getTransaction['address_proof'];
			$address2 = $getTransaction['address2'];
			$state = $getTransaction['state'];
			$city = $getTransaction['city'];
			$phone = $getTransaction['phone'];
			$email = $getTransaction['email'];
			$pan = $getTransaction['pan'];
			$nominee_name = $getTransaction['nominee_name'];
			$relationship_type = $getTransaction['relationship_type'];
			$transaction_charges = $getTransaction['transaction_charges'];

		}
		else
		{
			echo 'No record found';
			exit;
		}
}


if(!isset($branch_name)) { $branch_name = ''; } 
if(!isset($application_no)) { $application_no = ''; } 
if(!isset($customer_id)) { $customer_id = ''; } 
if(!isset($deposit_date)) { $deposit_date = ''; } 
if(!isset($receipt_date)) { $receipt_date = ''; } 
if(!isset($agent_code)) { $agent_code = ''; } 
if(!isset($tenure)) { $tenure = ''; } 
if(!isset($receipt_number)) { $receipt_number = ''; } 
if(!isset($comitted_amount)) { $comitted_amount = ''; } 
if(!isset($amount)) { $amount = ''; } 
if(!isset($payment_mode)) { $payment_mode = ''; } 
if(!isset($payment_mode_service)) { $payment_mode_service = ''; } 
if(!isset($dd_number)) { $dd_number = ''; } 
if(!isset($dd_bank_name)) { $dd_bank_name = ''; } 
if(!isset($dd_date)) { $dd_date = ''; }
if(!isset($first_name)) { $first_name = ''; }
if(!isset($last_name)) { $last_name = ''; }
if(!isset($transaction_id)) { $transaction_id = ''; }
if(!isset($customer_type)) { $customer_type = ''; }
if(!isset($employee_code)) { $employee_code = ''; }
if(!isset($penalty)) { $penalty = ''; }
if(!isset($gender)) { $gender = 'M'; }
if(!isset($dob)) { $dob = ''; }
if(!isset($age_proof)) { $age_proof = ''; }
if(!isset($id_proof)) { $id_proof = ''; }
if(!isset($fathers_name)) { $fathers_name = ''; }
if(!isset($guardian_name)) { $guardian_name = ''; }
if(!isset($address1)) { $address1 = ''; }
if(!isset($address_proof)) { $address_proof = ''; }
if(!isset($address2)) { $address2 = ''; }
if(!isset($state)) { $state = ''; }
if(!isset($city)) { $city = ''; }
if(!isset($zip)) { $zip = ''; }
if(!isset($phone)) { $phone = ''; }
if(!isset($email)) { $email = ''; }
if(!isset($pan)) {$pan = ''; }
if(!isset($nominee_name)) { $nominee_name = ''; }
if(!isset($relationship_type)) { $relationship_type = ''; }
if(!isset($transaction_charges)) { $transaction_charges = 0.00; }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Edit Service Charge </title>
  <meta name="Generator" content="">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
	
	<link rel="shortcut icon" type="image/x-icon" href="<?=URL?>images/favicon.ico">
<link rel="stylesheet" href="<?=URL?>webadmin/css/default.css">
<link rel="stylesheet" href="<?=URL?>webadmin/css/dropdown.css">
<link rel="stylesheet" href="<?=URL?>css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?=URL?>css/template.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<script src="<?=URL?>js/jquery.min.js" type="text/javascript"></script>
<script src="<?=URL?>js/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="<?=URL?>js/jquery.validationEngine.js" type="text/javascript"></script>
<script>	
		$(document).ready(function() {
			$("#frmadminform").validationEngine()
		});
		
		// JUST AN EXAMPLE OF CUSTOM VALIDATI0N FUNCTIONS : funcCall[validate2fields]
		function validate2fields(){
			if($("#firstname").val() =="" ||  $("#lastname").val() == ""){
				return false;
			}else{
				return true;
			}
		}
	</script>

<style type="text/css">
body{
	font-family:Arial, Verdana, Helvetica, sans-serif; font-size:12px; font-weight:normal;
	color:#404040; text-decoration:none;
	text-align:justify;
	background:url(images/adminbg.gif) repeat-x 0 0; margin:0 auto;
	}
	
.insideBORDER{

	border: solid 1px #CCCCCC;

}
/*################ Style Css Use in hotelTabMenu ################*/
.hotelTabMenu a{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; color:#666666; text-decoration:none; height:24px; padding:0px 10px 0px 10px; background:#EFEFEF; display:block; line-height:24px; border:solid 1px #CBCBCB;}
.hotelTabMenu a:hover{ font-weight:bold; color:#000; text-decoration:none; background:#fff; border-bottom:0px;}

	 
.hotelTabSelect{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; background:#FFF; color:#000; height:24px; line-height:24px; text-decoration:none; padding:0px 10px 0px 10px; display:block; border-top:solid 1px #CBCBCB; border-left:solid 1px #CBCBCB; border-right:solid 1px #CBCBCB; border-bottom:0px;}

.hotelTabSelect a{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; background:#FFF; color:#000; height:24px; line-height:24px; text-decoration:none; padding:0px 10px 0px 10px; display:block;}
</style>	

	
<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
 </head>

 <body>
 <center>
 <div>
 <form name="addForm" id="addForm" action="" method="post" >
	 <input type="hidden" name="transaction_charges" value="<?php echo $transaction_charges; ?>">
	 <table width="750" style="border:0px solid red;">
	 
		 <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Update Entry</td>
    </tr>
		
		<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Branch Name</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo $branch_name; ?></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Application No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $application_no; ?></td>
    </tr>
    <tr> 
      <td class="tbllogin" valign="top" align="right">Customer ID<br />(Reliance Money System)</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<?php echo $trusted_id; ?>
			</td>
    </tr>
    <tr> 
      <td class="tbllogin" valign="top" align="right">Deposit Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<input name="deposit_date" id="deposit_date" type="text" class="inplogin"  value="<?php echo $deposit_date; ?>" maxlength="100" /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.deposit_date,'dd-mm-yyyy',this)" width="20" height="18">
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Receipt Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<input name="receipt_date" id="receipt_date" type="text" class="inplogin"  value="<?php echo $receipt_date; ?>" maxlength="100" /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.receipt_date,'dd-mm-yyyy',this)" width="20" height="18">
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="agent_code" id="agent_code" type="text" class="inplogin"  value="<?php echo $agent_code; ?>" /></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Tenure</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $tenure; ?></td>
    </tr>		
		<tr> 
      <td class="tbllogin" valign="top" align="right">Receipt No.<br />(Reliance Money System)</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $receipt_number; ?></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Monthly Commited Amount<br />(Reliance Money System)</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $comitted_amount; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Amount<br />(Reliance Money System)</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $amount; ?></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Service Charge</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<input type="text" name="transaction_charges" class="inplogin" value="<?php echo $transaction_charges; ?>" onKeyPress="return keyRestrict(event, '0123456789.')">
			</td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Payment Mode (S. Charge)</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $payment_mode_service; ?>
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">DD Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $dd_number; ?></td>
    </tr>
		

		<tr> 
      <td class="tbllogin" valign="top" align="right">Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $dd_bank_name; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">DD Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $dd_date; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">First Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="first_name" id="first_name" type="text" class="inplogin"  value="<?php echo $first_name; ?>" /></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Last Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="last_name" id="last_name" type="text" class="inplogin"  value="<?php echo $last_name; ?>" /></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Transaction ID</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $transaction_id; ?></td>
    </tr>	

		<tr> 
      <td class="tbllogin" valign="top" align="right">Customer Type</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $customer_type; ?>
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Employee Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $employee_code; ?></td>
    </tr>
		

    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Update" class="inplogin" type="submit" ></td>
    </tr>
  </tbody>
	 </table>
	 </form>
 </div>
 
 </center>
 </body>
</html>