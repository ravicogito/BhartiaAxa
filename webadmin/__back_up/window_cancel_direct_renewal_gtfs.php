<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
$msg = '';

$objDB = new DB();
$pageOwner = "'superadmin','admin','subadmin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

$readonly = 'readonly';
if(!isset($cancellation_reason)) { $cancellation_reason = ''; }

/*
function find_premium_for_this_transaction($transaction_id)
		{
			$return_array = array();
			#echo "<br /> SELECT amount / comitted_amount as premium, customer_id FROM installment_master WHERE id='".$transaction_id."'";
			$selPremiumNumber = mysql_query("SELECT installment as premium, customer_id FROM installment_master WHERE id='".$transaction_id."'");
			$numPremiumNumber = mysql_num_rows($selPremiumNumber);
			if($numPremiumNumber > 0)
			{
				$getPremiumNumber = mysql_fetch_array($selPremiumNumber);
				$return_array['premium_number'] = $getPremiumNumber['premium'];
				$return_array['customer_id'] = $getPremiumNumber['customer_id'];
			}
			return $return_array;
		}

*/

if(isset($_GET['id']) && !empty($_GET['id']))
{
	

	$invoice_id = base64_decode($_GET['id']);
	//echo $invoice_id;
	
	if(isset($_POST['cancellation_reason']) && $_POST['cancellation_reason'] != '')
	{
		//echo '<pre>';
		//print_r($_POST);
		//echo '</pre>';
		extract($_POST);

		$update_installment = "UPDATE direct_renewal_premium_gtfs SET 	
										cancellation_reason = '".realTrim($cancellation_reason)."',
										is_deleted = 1
										
										WHERE 
										id='".$invoice_id."'
				";
				#echo '<br />'.$update_installment;
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
	$selTransaction = mysql_query("SELECT * FROM direct_renewal_premium_gtfs WHERE id='".$invoice_id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
			$getTransaction = mysql_fetch_assoc($selTransaction);
			//print_r($getTransaction);

			

			$branch_name = find_branch_name($getTransaction['branch_id']);
			$application_no = $getTransaction['application_no'];
			$deposit_date = date('d-m-Y', strtotime($getTransaction['deposit_date']));
			#$receipt_date = date('d-m-Y', strtotime($getTransaction['receipt_date']));
			$agent_code = $getTransaction['agent_code'];
			$agent_name = $getTransaction['agent_name'];
			$tenure = $getTransaction['term'];
			#$comitted_amount = $getTransaction['amount'];
			$amount = $getTransaction['amount'];
			$payment_mode = $getTransaction['sp_payment_mode'];
			#$payment_mode_service = $getTransaction['payment_mode_service'];

			$dd_number = $getTransaction['sp_dd_no'];
			$dd_bank_name = $getTransaction['sp_dd_bank'];
			$dd_date = date('d-m-Y', strtotime($getTransaction['sp_dd_date']));
			$receipt_serial_number = $getTransaction['receipt_number'];
			#$ifs_code = $getTransaction['ifs_code'];
			#$micr_code = $getTransaction['micr_code'];
			if($dd_date == '01-01-1970'){ $dd_date=''; }
			$first_name = $getTransaction['applicant_name'];
			$receipt_number = $getTransaction['transaction_id'];
			//$middle_name = $getTransaction['middle_name'];
			//$last_name = $getTransaction['last_name'];
			#$customer_type = $getTransaction['customer_type'];
			#$employee_code = $getTransaction['employee_code'];
			#$penalty = $getTransaction['penalty'];
			//$gender = $getMasterRecord['gender'];
			//$dob_original = $getTransaction['dob_original'];
			//$age_proof = $getMasterRecord['age_proof'];
			if(isset($getTransaction['cancellation_reason']))
			{
			$cancellation_reason = $getTransaction['cancellation_reason'];
			}
			else
			{
			$cancellation_reason = "";
			}


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
#if(!isset($receipt_date)) { $receipt_date = ''; } 
if(!isset($agent_code)) { $agent_code = ''; } 
if(!isset($agent_name)) { $agent_name = ''; } 
if(!isset($tenure)) { $tenure = ''; } 
if(!isset($receipt_number)) { $receipt_number = ''; } 
if(!isset($comitted_amount)) { $comitted_amount = ''; } 
if(!isset($amount)) { $amount = ''; } 
if(!isset($payment_mode)) { $payment_mode = ''; } 
#if(!isset($payment_mode_service)) { $payment_mode_service = ''; } 
if(!isset($dd_number)) { $dd_number = ''; } 
if(!isset($dd_bank_name)) { $dd_bank_name = ''; } 
if(!isset($ifs_code)) { $ifs_code = ''; }
if(!isset($micr_code)) { $micr_code = ''; }
if(!isset($dd_date)) { $dd_date = ''; }
if(!isset($first_name)) { $first_name = ''; }
#if(!isset($last_name)) { $last_name = ''; }
#if(!isset($transaction_id)) { $transaction_id = ''; }
#if(!isset($customer_type)) { $customer_type = ''; }
#if(!isset($employee_code)) { $employee_code = ''; }
#if(!isset($penalty)) { $penalty = ''; }
#if(!isset($gender)) { $gender = 'M'; }
if(!isset($dob)) { $dob = ''; }
if(!isset($receipt_serial_number)) { $receipt_serial_number = ''; }
if(!isset($age_proof)) { $age_proof = ''; }
if(!isset($insurance)) { $insurance = ''; }
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
if(!isset($appointee_name)) { $appointee_name = ''; }
if(!isset($relationship_type)) { $relationship_type = ''; }
if(!isset($husbands_name)) { $husbands_name = ''; }
if(!isset($nominee_dob)) { $nominee_dob = ''; }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Cancel Receipt </title>
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

	
	
	<script type="text/javascript">
<!--
	function update_trans_id()
	{
		document.getElementById("transaction_id").value = document.getElementById("receipt_number").value;
	}
	function dochk()
	{	
		if(document.addForm.cancellation_reason.value.search(/\S/) == -1)
		{
			alert("Please Enter Cancellation Reason");
			document.addForm.cancellation_reason.focus();
			return false;
		}	
	}

	function chq_bounce()
	{
		if(document.addForm.cheque_bounce.checked == true)
		{
			//alert('123');
			document.addForm.cancellation_reason.value = 'CHEQUE BOUNCE';
			document.getElementById('cancellation_reason').readOnly = true;
		}
		else
		{
			document.addForm.cancellation_reason.value = '';
			document.getElementById('cancellation_reason').readOnly = false;
		}
	}

//-->
</script>
<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
 </head>

 <body>
 <center>
 <div>
 <form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()">
	 <input type="hidden" name="transaction_charges" value="<?php echo $transaction_charges; ?>">
	 <table width="750" style="border:0px solid red;">
	 
		 <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Cancel Receipt</td>
    </tr>
    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
        * marked fields are mandatory</font></b></td>
    </tr>
		<?php
			if($msg != '')
			{
		?>
		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000"><?php echo $msg; ?></font></b></td>
    </tr>
		<?php
			}
		?>
    
		<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Branch Name</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo $branch_name; ?></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Policy No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $application_no; ?></td>
    </tr>
    
    <tr> 
      <td class="tbllogin" valign="top" align="right">Deposit Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $deposit_date; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $agent_code; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $agent_name; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Term</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $tenure; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Receipt No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $receipt_number; ?></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Monthly Commited Amount</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $comitted_amount; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Amount</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $amount; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Payment Mode<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $payment_mode; ?>
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
			<td class="tbllogin" valign="top" align="right">IFS Code</td>
			<td class="tbllogin" valign="top" align="center">:</td>
			<td valign="top" align="left"><?php echo $ifs_code; ?></td>
		</tr>
		<tr> 
			<td class="tbllogin" valign="top" align="right">MICR Code</td>
			<td class="tbllogin" valign="top" align="center">:</td>
			<td valign="top" align="left"><?php echo $micr_code; ?></td>
		</tr>	

		<tr> 
      <td class="tbllogin" valign="top" align="right">Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $first_name; ?></td>
    </tr>

		

		<tr> 
      <td class="tbllogin" valign="top" align="right">Receipt Serial No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $receipt_serial_number; ?></td>
    </tr>		

	<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Cheque Bounce</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="checkbox" name="cheque_bounce" id="cheque_bounce" value="1" onclick="javascript:chq_bounce()"></td>
    </tr> -->
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Reason For Cancellation<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="cancellation_reason" id="cancellation_reason" class="inplogin" onKeyUp="this.value = this.value.toUpperCase();"></textarea></td>
    </tr>
		
    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Cancel Receipt" class="inplogin" type="submit" onclick="return dochk()"> <!-- 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"> --></td>
    </tr>
  </tbody>
	 </table>
	 </form>
 </div>
 
 </center>
 </body>
</html>