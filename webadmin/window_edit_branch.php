<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
$msg = '';
$branch_readonly = '';
$objDB = new DB();

$pageOwner = "'branch','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

// Write functions here


$selIDProof = mysql_query("SELECT id, document_name FROM id_proof ORDER BY document_name ASC ");
$numIDProof = mysql_num_rows($selIDProof);

$selAgeProof = mysql_query("SELECT id, document_name FROM age_proof ORDER BY document_name ASC ");
$numAgeProof = mysql_num_rows($selAgeProof);

$selAddressProof = mysql_query("SELECT id, document_name FROM address_proof ORDER BY document_name ASC ");
$numAddressProof = mysql_num_rows($selAddressProof);

$selOccupation = mysql_query("SELECT id, occupation FROM occupation_master ORDER BY occupation ASC ");
$numOccupation = mysql_num_rows($selOccupation);

$selRelationship = mysql_query("SELECT id, relationship FROM relationship_master ORDER BY relationship ASC ");
$numRelationship = mysql_num_rows($selRelationship);



if(isset($_GET['id']) && !empty($_GET['id']))
{
	

	$invoice_id = base64_decode($_GET['id']);
	//echo $invoice_id;
	$mysql_customer_id = find_customer_id_through_installment_id($invoice_id);
	$folio_no_id = find_folio_id_through_transaction_id($invoice_id);
	if(isset($_POST['agent_code']) && $_POST['agent_code'] != '')
	{
		//echo '<pre>';
		//print_r($_POST);
		//echo '</pre>';
		extract($_POST);
		#if(trim($employee_code) != ''){ $transaction_charges = 0.00; }

		$update_installment = "UPDATE installment_master SET 	
										agent_code = '".realTrim($agent_code)."',
										agent_name = '".realTrim($agent_name)."',
										payment_mode = '".$payment_mode."',
										
										dd_number = '".mysql_real_escape_string($dd_number)."',
										dd_date = '".mysql_real_escape_string(date('Y-m-d', strtotime($dd_date)))."',
										dd_bank_name = '".mysql_real_escape_string($dd_bank_name)."'
										
										WHERE 
										id='".$invoice_id."'
				";
				#echo '<br />'.$update_installment;
				mysql_query($update_installment);

				$update_folio = "UPDATE customer_folio_no SET 	
										nominee_name = '".realTrim($nominee_name)."',
										nominee_address = '".realTrim($nominee_address)."',
										nominee_dob = '".$nominee_dob."',
										
										nominee_relationship = '".realTrim($relationship_type_name)."',
										appointee_name = '".realTrim($appointee_name)."',
										appointee_relationship = '".mysql_real_escape_string($appointee_relationship_type_name)."'
										
										WHERE 
										id='".$folio_no_id."'
				";
				#echo '<br />'.$update_installment;
				mysql_query($update_folio);

				$update_secondary = "UPDATE customer_master SET
					dob = '".date('Y-m-d', strtotime($dob))."',
										dob_original = '".$dob."',
										age_proof = '".$age_proof."',
										
										id_proof = '".$id_proof."',
										first_name = '".mysql_real_escape_string($first_name)."',
										middle_name = '".mysql_real_escape_string($middle_name)."',
										last_name = '".mysql_real_escape_string($last_name)."',
										fathers_name = '".mysql_real_escape_string($fathers_name)."',
										husbands_name = '".mysql_real_escape_string($husbands_name)."',
										guardian_name = '".mysql_real_escape_string($guardian_name)."',
										address1 = '".mysql_real_escape_string($address1)."',
										address_proof = '".$address_proof."',
										gender = '".$gender."',
										occupation = '".$occupation_name."',
										annual_income = '".$annual_income."',
										
										city = '".mysql_real_escape_string($city)."',
										zip = '".mysql_real_escape_string($zip)."',
										phone = '".mysql_real_escape_string($phone)."',
										email = '".mysql_real_escape_string($email)."',
										pan = '".mysql_real_escape_string($pan)."'
										WHERE id='".$mysql_customer_id."'
				";

				#echo '<br />'.$update_secondary;
				#exit;
				mysql_query($update_secondary);
				#exit;

				
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
			
			if(intval($getTransaction['receipt_generated']) == 1) { $branch_readonly = 'readonly'; }

			$selMasterRecord = mysql_query("SELECT * FROM customer_master WHERE id='".$getTransaction['customer_id']."'");

			if(mysql_num_rows($selMasterRecord) > 0)
			{	
				$getMasterRecord = mysql_fetch_assoc($selMasterRecord);
				#echo '<pre>';
				#print_r($getMasterRecord);
				#echo '</pre>';
			}

			$selFolioRecord = mysql_query("SELECT * FROM customer_folio_no WHERE id = '".$getTransaction['folio_no_id']."'");

			if(mysql_num_rows($selFolioRecord) > 0)
			{
				$getFolioRecord = mysql_fetch_array($selFolioRecord);
			}

			$branch_name = find_branch_name($getTransaction['branch_id']);
			$trusted_id = $getMasterRecord['customer_id'];
			$application_no = $getTransaction['application_no'];
			$serial_no = $getTransaction['serial_no'];
			$deposit_date = date('d-m-Y', strtotime($getTransaction['deposit_date']));
			#$receipt_date = date('d-m-Y', strtotime($getTransaction['receipt_date']));
			$agent_code = $getTransaction['agent_code'];
			$agent_name = $getTransaction['agent_name'];
			$tenure = $getFolioRecord['tenure'];
			$receipt_number = $getTransaction['receipt_number'];
			$transaction_id = $getTransaction['transaction_id'];
			$comitted_amount = $getFolioRecord['committed_amount'];
			$amount = $getTransaction['amount'];
			$payment_mode = $getTransaction['payment_mode'];
			#$payment_mode_service = $getTransaction['payment_mode_service'];

			$dd_number = $getTransaction['dd_number'];
			$dd_bank_name = $getTransaction['dd_bank_name'];
			$dd_date = date('d-m-Y', strtotime($getTransaction['dd_date']));
			$ifs_code = $getTransaction['ifs_code'];
			$micr_code = $getTransaction['micr_code'];
			if($dd_date == '01-01-1970'){ $dd_date=''; }
			$first_name = $getMasterRecord['first_name'];
			$middle_name = $getMasterRecord['middle_name'];
			$last_name = $getMasterRecord['last_name'];
			#$customer_type = $getTransaction['customer_type'];
			#$employee_code = $getTransaction['employee_code'];
			#$penalty = $getTransaction['penalty'];
			$gender = $getMasterRecord['gender'];
			$dob_original = $getMasterRecord['dob_original'];
			$age_proof = $getMasterRecord['age_proof'];
			$insurance = $getMasterRecord['insurance'];
			$id_proof = $getMasterRecord['id_proof'];
			$fathers_name = $getMasterRecord['fathers_name'];
			$husbands_name = $getMasterRecord['husbands_name'];
			$guardian_name = $getMasterRecord['guardian_name'];
			$address1 = $getMasterRecord['address1'];
			$address_proof = $getMasterRecord['address_proof'];
			#$address2 = $getTransaction['address2'];
			$state = $getMasterRecord['state'];
			$city = $getMasterRecord['city'];
			$phone = $getMasterRecord['phone'];
			$email = $getMasterRecord['email'];
			$pan = $getMasterRecord['pan'];
			$zip = $getMasterRecord['zip'];
			$annual_income = $getMasterRecord['annual_income'];
			$occupation = $getMasterRecord['occupation'];
			$nominee_name = $getFolioRecord['nominee_name'];
			$nominee_address = $getFolioRecord['nominee_address'];
			$relationship_type = $getFolioRecord['nominee_relationship'];
			$nominee_dob = $getFolioRecord['nominee_dob'];
			$nominee_address = $getFolioRecord['nominee_address'];
			$appointee_name = $getFolioRecord['appointee_name'];
			$appointee_relationship_type = $getFolioRecord['appointee_relationship'];
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
if(!isset($serial_no)) { $serial_no = ''; }
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
if(!isset($last_name)) { $last_name = ''; }
if(!isset($transaction_id)) { $transaction_id = ''; }
#if(!isset($customer_type)) { $customer_type = ''; }
#if(!isset($employee_code)) { $employee_code = ''; }
#if(!isset($penalty)) { $penalty = ''; }
#if(!isset($gender)) { $gender = 'M'; }
if(!isset($dob)) { $dob = ''; }
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
  <title> Edit </title>
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
<!--forcalender-->
<!--<script src="<?=URL?>js/jscal2.js" type="text/javascript"></script>
<script src="<?=URL?>js/en.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?=URL?>css/jscal2.css">
<link rel="stylesheet" href="<?=URL?>css/border-radius.css" />
<link rel="stylesheet" href="<?=URL?>css/steel/steel.css" />-->
<!--forcalender-->
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
	function chkAdult(dob)
	{
		//alert('123');
		//var dob = '22-11-1982'; // dd--mm-yyyy
		var splitted = dob.split("-");
		//alert(splitted[0]);
		//alert(splitted[1]);
		//alert(splitted[2]);
		var birthDate = new Date(splitted[2],splitted[1],splitted[0]);
		var today = new Date();
		if (today >= new Date(birthDate.getFullYear() + 18, birthDate.getMonth() - 1, birthDate.getDate())) 
		{
		  // Allow access
		  //alert("Adult");
			return true;
		} 
		else 
		{
		  // Deny access
		  //alert("Child");
		  return false;
		}
	}
	//-->
	</script>
	
	<script type="text/javascript">
<!--
	function update_trans_id()
	{
		document.getElementById("transaction_id").value = document.getElementById("receipt_number").value;
	}
	function dochk()
	{	
		if(document.addForm.agent_code.value.search(/\S/) == -1)
		{
			alert("Please Enter Agent Code");
			document.addForm.serial_no.focus();
			return false;
		}	
		
		if(document.addForm.payment_mode.value.search(/\S/) == -1)
		{
			alert("Please select Payment Mode");
			document.addForm.payment_mode.focus();
			return false;
		}		

		if(document.addForm.serial_no.value.search(/\S/) == -1)
		{
			alert("Please Enter Receipt Serial No.");
			document.addForm.serial_no.focus();
			return false;
		}	

		if(document.addForm.serial_no.value.length != 7)
		{
			alert("Receipt Serial No. Must contain 7 characters");
			document.addForm.serial_no.focus();
			return false;
		}	

		/*if((document.addForm.insurance.checked == true) && (document.addForm.age_proof.value.search(/\S/) == -1))
		{
			alert("Age Proof is Required For Insurance");
			document.addForm.age_proof.focus();
			return false;
		}	*/

		/*if(document.addForm.id_proof.value.search(/\S/) == -1)
		{
			alert("Please Select ID Proof");
			document.addForm.id_proof.focus();
			return false;
		}	*/
		
		if(document.addForm.address1.value.search(/\S/) == -1)
		{
			alert("Please Enter Address");
			document.addForm.address1.focus();
			return false;
		}	

		/*if(document.addForm.address_proof.value.search(/\S/) == -1)
		{
			alert("Please Select Address Proof");
			document.addForm.address_proof.focus();
			return false;
		}	*/


		if(document.addForm.zip.value.search(/\S/) == -1)
		{
			alert("Please Enter PIN");
			document.addForm.zip.focus();
			return false;
		}	
		
		if(document.addForm.payment_mode.value == 'DD')
		{
			if(document.addForm.dd_number.value.search(/\S/) == -1)
			{
				alert("Please enter DD Number");
				document.addForm.dd_number.focus();
				return false;
			}
			if(document.addForm.dd_bank_name.value.search(/\S/) == -1)
			{
				alert("Please enter Bank Name");
				document.addForm.dd_bank_name.focus();
				return false;
			}
			if(document.addForm.dd_date.value.search(/\S/) == -1)
			{
				alert("Please enter DD Date");
				document.addForm.dd_date.focus();
				return false;
			}
		}
				
		if(document.addForm.email.value != '')
		{
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			var emailaddressVal = document.addForm.email.value;
			if(!emailReg.test(emailaddressVal))
			{
				alert('Invalid Email');
				document.addForm.email.focus();
				return false;
			}
		}

		/*if(document.addForm.annual_income.value.search(/\S/) == -1)
		{
			alert("Please Enter Annual Income");
			document.addForm.annual_income.focus();
			return false;
		}*/
		/*if(document.addForm.occupation_name.value.search(/\S/) == -1)
		{
			alert("Please Select Occupation");
			document.addForm.occupation_name.focus();
			return false;
		}*/

		if(document.addForm.nominee_name.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee Name");
			document.addForm.nominee_name.focus();
			return false;
		}		

		if(document.addForm.relationship_type_name.value.search(/\S/) == -1)
		{
			alert("Please Select Relationship with Nomninee");
			document.addForm.relationship_type.focus();
			return false;
		}		

		if(document.addForm.nominee_dob.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee DOB");
			document.addForm.nominee_dob.focus();
			return false;
		}	

		if(document.addForm.nominee_address.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee Address");
			document.addForm.nominee_address.focus();
			return false;
		}	

		if(!chkAdult(document.addForm.nominee_dob.value) && document.addForm.appointee_name.value.search(/\S/) == -1)
		{
			alert('Please Enter Guardian Name');
			return false;
		}		

		if(!chkAdult(document.addForm.nominee_dob.value) && document.addForm.appointee_relationship_type_name.value.search(/\S/) == -1)
		{
			alert('Please Select Guardian Relationship');
			return false;
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
      <td colspan="3">Update Entry</td>
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
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Preliminary Entry</font></b></td>
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
      <td class="tbllogin" valign="top" align="right">Customer ID</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<?php echo $trusted_id; ?>
			</td>
    </tr>
    <tr> 
      <td class="tbllogin" valign="top" align="right">Deposit Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $deposit_date; ?></td>
    </tr>

		<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Receipt Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php //echo $receipt_date; ?></td>
    </tr> -->

		<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Code<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" class="inplogin" name="agent_code" id="agent_code" value="<?php echo $agent_code; ?>" onKeyUp="this.value = this.value.toUpperCase();" <?= $branch_readonly; ?>></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" class="inplogin" name="agent_name" id="agent_name" value="<?php echo $agent_name; ?>" onKeyUp="this.value = this.value.toUpperCase();" <?= $branch_readonly; ?> ></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Tenure</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $tenure; ?></td>
    </tr>
		<!-- <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Secondary Entry</font></b></td>
    </tr> -->
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
      <td valign="top" align="left">
			<input type = "text" value="<?php echo $payment_mode; ?>" name="payment_mode" id="payment_mode" class="inplogin" readonly /> 
				<!-- <select name="payment_mode" id="payment_mode" class="inplogin_select">
					<option value="" >Select</option>
					<option value="CASH" <?php echo ($payment_mode == 'CASH' ? 'selected' : ''); ?>>Cash</option>
					<option value="CHEQUE" <?php echo ($payment_mode == 'CHEQUE' ? 'selected' : ''); ?>>Cheque</option>
					<option value="DD" <?php echo ($payment_mode == 'DD' ? 'selected' : ''); ?>>DD</option>
				</select> -->
			</td>
    </tr>

		<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Payment Mode (S. Charge)<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="payment_mode_service" id="payment_mode_service" class="inplogin_select">
					<option value="">Select</option>
					<option value="CASH" <?php echo ($payment_mode_service == 'CASH' ? 'selected' : ''); ?>>Cash</option>
					<option value="DD" <?php echo ($payment_mode_service == 'DD' ? 'selected' : ''); ?>>DD</option>
				</select>
			</td>
    </tr> -->

		<tr> 
      <td class="tbllogin" valign="top" align="right">DD Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dd_number" id="dd_number" type="text" class="inplogin"  value="<?php echo $dd_number; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" readonly /></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dd_bank_name" id="dd_bank_name" type="text" class="inplogin"  value="<?php echo $dd_bank_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" readonly /></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">DD Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dd_date" id="dd_date" type="text" class="inplogin"  value="<?php echo $dd_date; ?>" maxlength="100" readonly /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<!-- <img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.dd_date,'dd-mm-yyyy',this)" width="20" height="18"> --></td>
    </tr>

		<tr> 
			<td class="tbllogin" valign="top" align="right">IFS Code</td>
			<td class="tbllogin" valign="top" align="center">:</td>
			<td valign="top" align="left"><input name="ifs_code" id="ifs_code" type="text" class="inplogin"  value="<?php echo $ifs_code; ?>" maxlength="100" readonly onKeyUp="this.value = this.value.toUpperCase();" readonly ></td>
		</tr>
		<tr> 
			<td class="tbllogin" valign="top" align="right">MICR Code</td>
			<td class="tbllogin" valign="top" align="center">:</td>
			<td valign="top" align="left"><input name="micr_code" id="micr_code" type="text" class="inplogin"  value="<?php echo $micr_code; ?>" maxlength="100" readonly onKeyUp="this.value = this.value.toUpperCase();" readonly ></td>
		</tr>	

		<tr> 
      <td class="tbllogin" valign="top" align="right">First Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" name="first_name" id="first_name" readonly value="<?php echo $first_name; ?>" class="inplogin" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Middle Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" name="middle_name" id="middle_name" readonly value="<?php echo $middle_name; ?>" class="inplogin" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Last Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" name="last_name" id="last_name" readonly value="<?php echo $last_name; ?>" class="inplogin" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Transaction ID</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $receipt_number; ?></td>
    </tr>	

		<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Customer Type</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="customer_type" class="inplogin_select">
					<option value="NORMAL" <?php echo ($customer_type == 'NORMAL' ? 'selected' : '' ); ?>>Normal</option>
					<option value="EMPLOYEE" <?php echo ($customer_type == 'EMPLOYEE' ? 'selected' : '' ); ?>>Employee</option>
				</select>
			</td>
    </tr> -->

		<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Employee Code (Requires To Waive Service Charges) </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="employee_code" id="employee_code" type="text" class="inplogin"  value="<?php //echo $employee_code; ?>"  <?php //echo ($employee_code != '' ? 'readonly' : ''); ?>></td>
    </tr>
 -->
		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Secondary Entry</font></b></td>
    </tr>			

		<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Penalty (Cheque Bounce) </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="penalty" id="penalty" type="text" class="inplogin"  value="<?php echo $penalty; ?>" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
    </tr> 
		For secondary entry Address, ID Proof, Address Proof, Pin no is mandatory, Option for Insurance is required and if ticked yes then Age Proof should be mandatory. Nominee name and relationship is mandatory.
		-->

		<tr> 
      <td class="tbllogin" valign="top" align="right">Receipt Serial No.<font color="#ff0000">*</font><br /><font style="font-size:10px;">( Pre printed value on the receipt)</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="serial_no" id="serial_no" type="text" class="inplogin"  value="<?php echo $serial_no; ?>" maxlength="7" onKeyPress="return keyRestrict(event, '0123456789')" ></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Gender</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<!-- <input type="text" name="gender" class="inplogin" value="<?php echo $gender; ?>" readonly /> -->
				<select name="gender" id="gender" class="inplogin_select">
					<option value="">Select</option>
					<option value="M" <?php echo ($gender == 'M' ? 'selected' : ''); ?>>Male</option>
					<option value="F" <?php echo ($gender == 'F' ? 'selected' : ''); ?>>Female</option>
				</select>
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Husband's Name </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="husbands_name" id="husbands_name" type="text" class="inplogin"  value="<?php echo $husbands_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">DOB</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dob" id="dob" type="text" class="inplogin"  value="<?php echo str_replace('/', '-', $dob_original ); ?>" maxlength="20" readonly /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.dob,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>	

		<tr> 
      <td class="tbllogin" valign="top" align="right">Applied For Insurance</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo ($insurance == '1' ? 'Yes' : 'No'); ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Age Proof</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="age_proof" id="age_proof" class="inplogin_select">
					<option value="">Select Document</option>
					<?php
						if($numAgeProof > 0)
						{
							while($getAgeProof = mysql_fetch_assoc($selAgeProof))
							{
					?>
						<option value="<?php echo $getAgeProof['id']; ?>" <?php echo ($getAgeProof['id'] == $age_proof ? 'selected' : ''); ?>><?php echo $getAgeProof['document_name']; ?></option>
					<?php		
							}						
						}
					?>
					
				</select>
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">ID Proof<!-- <font color="#ff0000">*</font> --></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="id_proof" id="id_proof" class="inplogin_select">
					<option value="">Select Document</option>
					<?php
						if($numIDProof > 0)
						{
							while($getIDProof = mysql_fetch_assoc($selIDProof))
							{
					?>
						<option value="<?php echo $getIDProof['id']; ?>" <?php echo ($getIDProof['id'] == $id_proof ? 'selected' : ''); ?>><?php echo $getIDProof['document_name']; ?></option>
					<?php		
							}						
						}
					?>					
				</select>
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Father's Name </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="fathers_name" id="fathers_name" type="text" class="inplogin"  value="<?php echo $fathers_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" readonly /></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Guardian Name </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="guardian_name" id="guardian_name" type="text" class="inplogin"  value="<?php echo $guardian_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" readonly ></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Address<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="address1" id="address1" class="inplogin" onKeyUp="this.value = this.value.toUpperCase();" ><?php echo $address1; ?></textarea>
			&nbsp; <input type="button" name="btnCopy" id="btnCopy" value="Copy Address" class="inplogin" onclick="javascript:document.addForm.nominee_address.value = document.addForm.address1.value + ', ' + document.addForm.statename.value + ', ' + document.addForm.city.value + ', ' + document.addForm.zip.value">
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Address Proof<!-- <font color="#ff0000">*</font> --></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="address_proof" id="address_proof" class="inplogin_select">
					<option value="">Select Document</option>
					<?php
						if($numAddressProof > 0)
						{
							while($getAddressProof = mysql_fetch_assoc($selAddressProof))
							{
					?>
						<option value="<?php echo $getAddressProof['id']; ?>" <?php echo ($address_proof == $getAddressProof['id'] ? 'selected' : ''); ?>><?php echo $getAddressProof['document_name']; ?></option>
					<?php		
							}						
						}
					?>					
				</select>
			</td>
    </tr>

		<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Address 2 </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="address2" id="address2" class="inplogin"><?php echo $address2; ?></textarea></td>
    </tr> -->

		<tr> 
      <td class="tbllogin" valign="top" align="right">State</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo find_place_name($state); ?>	<input type="hidden" name="statename" value="<?php echo find_place_name($state); ?>">			
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">City</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="city" id="city" type="text" class="inplogin"  value="<?php echo $city; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" /></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">PIN<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="zip" id="zip" type="text" class="inplogin"  value="<?php echo $zip; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" /></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Phone<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="phone" id="phone" type="text" class="inplogin"  value="<?php echo $phone; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Email</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="email" id="email" type="text" class="inplogin"  value="<?php echo $email; ?>" maxlength="100" ></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">PAN </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="pan" id="pan" type="text" class="inplogin"  value="<?php echo $pan; ?>" maxlength="100" readonly /></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Occupation<!-- <font color="#ff0000">*</font> --></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<select name="occupation" id="occupation" class="inplogin" onchange="document.addForm.occupation_name.value = this.value; document.addForm.occupation_display.value = '';">
				<option value="">Select</option>
				<?php 
					if($numOccupation > 0)
					{
						while($getOccupation = mysql_fetch_array($selOccupation))
						{
							//echo '<option value="'.$getOccupation['occupation'].'" >'.$getOccupation['occupation'].'</option>';
				?>
				<option value="<?php echo $getOccupation['occupation']?>" <?php echo ($occupation == $getOccupation['occupation'] ? 'selected' : '') ?>><?php echo $getOccupation['occupation']?></option>
				<?php
						}
					}
				?>
			</select>

			<?php $occupation_id = find_occupation_id($occupation); //echo $occupation_id; ?>
			&nbsp;<strong>Others</strong>&nbsp;<input name="occupation_name" id="occupation_name" type="hidden" class="inplogin"  value="<?php echo $occupation; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"> <input name="occupation_display" id="occupation_display" type="text" class="inplogin"  value="<?php echo (intval($occupation_id) != 0 ? '' : $occupation); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.occupation_name.value = this.value">
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Annual Income (INR)<!-- <font color="#ff0000">*</font> --></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="annual_income" id="annual_income" type="text" class="inplogin"  value="<?php echo $annual_income; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')"></td>
    </tr>


		<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="nominee_name" id="nominee_name" type="text" class="inplogin"  value="<?php echo $nominee_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Relationship<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<select name="relationship_type" id="relationship_type" class="inplogin" onchange="document.addForm.relationship_type_name.value = this.value; document.addForm.relationship_display.value = '';">
				<option value="">Select</option>
				<?php 
					if($numRelationship > 0)
					{
						while($getRelationship = mysql_fetch_array($selRelationship))
						{
							//echo '<option value="'.$getOccupation['occupation'].'" >'.$getOccupation['occupation'].'</option>';
				?>
				<option value="<?php echo $getRelationship['relationship']?>" <?php echo ($relationship_type == $getRelationship['relationship'] ? 'selected' : '') ?>><?php echo $getRelationship['relationship']?></option>
				<?php
						}
					}
				?>
			</select>
			
			<?php $relationship_id = find_relationship_id($relationship_type); //echo $relationship_id; ?>
			&nbsp;<strong>Others</strong>&nbsp;<input name="relationship_type_name" id="relationship_type_name" type="hidden" class="inplogin"  value="<?php echo $relationship_type; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"> <input name="relationship_display" id="relationship_display" type="text" class="inplogin"  value="<?php echo (intval($relationship_id) != 0 ? '' : $relationship_type); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.relationship_type_name.value = this.value">
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee DOB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="nominee_dob" id="nominee_dob" type="text" class="inplogin"  value="<?php echo str_replace('/', '-', $nominee_dob ); ?>" maxlength="20" readonly /> <font color="#ff0000"></font>&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.nominee_dob,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>	
		<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee Address<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="nominee_address" id="nominee_address" class="inplogin" onKeyUp="this.value = this.value.toUpperCase();" ><?php echo $nominee_address; ?></textarea></td>
    </tr>


		<tr> 
      <td class="tbllogin" valign="top" align="right">Guardian Name <br /><font style="font-weight:normal;">(Required if nominee is minor)</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="appointee_name" id="appointee_name" type="text" class="inplogin"  value="<?php echo $appointee_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Guardian Relationship <br /><font style="font-weight:normal;">(Required if nominee is minor)</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<select name="appointee_relationship_type" id="appointee_relationship_type" class="inplogin" onchange="document.addForm.appointee_relationship_type_name.value = this.value; document.addForm.appointee_relationship_display.value = '';">
				<option value="">Select</option>
				<?php 
					$selRelationship = mysql_query("SELECT id, relationship FROM relationship_master ORDER BY relationship ASC ");
					$numRelationship = mysql_num_rows($selRelationship);

					if($numRelationship > 0)
					{
						while($getRelationship = mysql_fetch_array($selRelationship))
						{
							//echo '<option value="'.$getOccupation['occupation'].'" >'.$getOccupation['occupation'].'</option>';
				?>
				<option value="<?php echo $getRelationship['relationship']?>" <?php echo ($appointee_relationship_type == $getRelationship['relationship'] ? 'selected' : '') ?>><?php echo $getRelationship['relationship']?></option>
				<?php
						}
					}
				?>
			</select>
			
			<?php $relationship_id = find_relationship_id($relationship_type); //echo $relationship_id; ?>
			&nbsp;<strong>Others</strong>&nbsp;<input name="appointee_relationship_type_name" id="appointee_relationship_type_name" type="hidden" class="inplogin"  value="<?php echo $appointee_relationship_type; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"> <input name="appointee_relationship_display" id="appointee_relationship_display" type="text" class="inplogin"  value="<?php echo (intval($relationship_id) != 0 ? '' : $appointee_relationship_type); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.appointee_relationship_type_name.value = this.value">
			</td>
    </tr>

    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Update" class="inplogin" type="submit" onclick="return dochk()"> <!-- 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"> --></td>
    </tr>
  </tbody>
	 </table>
	 </form>
 </div>
 
 </center>
 </body>
</html>