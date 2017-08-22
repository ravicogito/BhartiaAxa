<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
$msg = '';
if(!isset($_SESSION[ADMIN_SESSION_VAR]))
{
	//echo 'Hi';
	header("location: index.php");
	exit();
}

$objDB = new DB();
$pageOwner = "'superadmin','admin','hub','branch'";

//exit;
chkPageAccess($_SESSION[ROLE_ID], $pageOwner);

$Query = "select * from place_master where 1 ";
$objDB->setQuery($Query);
$rsplace = $objDB->select();

$branch_readonly = intval($_SESSION[ROLE_ID]) == 4 ? 'readonly' : '';
	

// Write functions here


$selIncomeProof = mysql_query("SELECT id, document_name FROM income_proof ORDER BY document_name ASC ");
$numIncomeProof = mysql_num_rows($selIncomeProof);

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
	//$mysql_customer_id = find_customer_id_through_installment_id($invoice_id);
	//$folio_no_id = find_folio_id_through_transaction_id($invoice_id);
	if(isset($_POST['short_premium_payment_mode']) && $_POST['short_premium_payment_mode'] != '')
	{
		//echo '<pre>';
		//print_r($_POST);
		//echo '</pre>';
		//exit;
		extract($_POST);
		#if(trim($employee_code) != ''){ $transaction_charges = 0.00; }

		$update_installment = "UPDATE installment_master SET 	
										short_premium_payment_mode = '".realTrim($_POST['short_premium_payment_mode'])."',
										short_premium_amount = '".realTrim($_POST['short_premium_amount'])."',
										
										sp_chk_dd_date = '".date('Y-m-d',strtotime($_POST['sp_chk_dd_date']))."',
										sp_chk_dd_bankname = '".realTrim($_POST['sp_chk_dd_bankname'])."',
										sp_chk_dd_no = '".realTrim($_POST['sp_chk_dd_no'])."'
										
										WHERE 
										id='".$invoice_id."'
				";
				//echo '<br />'.$update_installment;
				//exit;
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
			//echo "<pre>";
			//print_r($getTransaction);
			//exit;
					

			$bank_ac = $getTransaction['bank_ac'];
			$ifs_code_main = $getTransaction['ifs_code_main'];

			$branch_name_main = $getTransaction['branch_name_main'];
			$bank_name_main = $getTransaction['bank_name_main'];
			$account_no_main = $getTransaction['account_no_main'];
			$micr_code_main = $getTransaction['micr_code_main'];
			



			$branch_name = find_branch_name($getTransaction['branch_id']);
			$application_no = $getTransaction['application_no'];
			$deposit_date = date('d-m-Y', strtotime($getTransaction['deposit_date']));
			$agent_code = $getTransaction['agent_code'];
			$agent_name = $getTransaction['agent_name'];
			$tenure = $getTransaction['tenure'];
			$plan = $getTransaction['plan'];

			$main_amount = $getTransaction['main_amount'];
			$short_premium_amount = $getTransaction['short_premium_amount'];
			$other_amount = $getTransaction['other_amount'];
			$sum_assured = $getTransaction['sum_assured'];
			$account_no = $getTransaction['account_no'];
			$in_favour = $getTransaction['in_favour'];

			$receipt_number = $getTransaction['receipt_number'];
			$transaction_id = $getTransaction['transaction_id'];
			
			
			
			
			
			
			
			#---------------------------------------------------------#
			
			$short_premium_payment_mode = $getTransaction['short_premium_payment_mode'];
			$amount = $getTransaction['amount'];
			$short_premium_amount = $getTransaction['short_premium_amount'];

			$payment_mode = $getTransaction['payment_mode'];
			$other_payment_mode = $getTransaction['other_payment_mode'];

			$sp_chk_dd_no = $getTransaction['sp_chk_dd_no'];
			$sp_chk_dd_bankname = $getTransaction['sp_chk_dd_bankname'];
			$sp_chk_dd_bankname = $getTransaction['sp_chk_dd_bankname'];
			$sp_chk_dd_date = '';
			
			if(isset($getTransaction['sp_chk_dd_date']) && ($getTransaction['sp_chk_dd_date'] != '1970-01-01')){
			$sp_chk_dd_date = date('d-m-Y', strtotime($getTransaction['sp_chk_dd_date']));
			}
			$middle_name = $getTransaction['middle_name'];
		#	----------------------------------------------------------- #
			
			
			#$ifs_code = $getTransaction['ifs_code'];
			#$micr_code = $getTransaction['micr_code'];
			#if($dd_date == '0000-00-00' || $dd_date =='1970-01-01'){ $dd_date=''; }
			$first_name = $getTransaction['first_name'];
			$middle_name = $getTransaction['middle_name'];
			$last_name = $getTransaction['last_name'];
			#$gender = $getTransaction['gender'];
			
			#$nature_of_business = $getTransaction['nature_of_business'];
			#$nominee_dob = $getTransaction['nominee_dob'] != '0000-00-00' ? date('d-m-Y',strtotime($getTransaction['nominee_dob'])) : '';
			

			#$age_proof = $getTransaction['age_proof'];
			#$income_proof = $getTransaction['income_proof'];
			#$address_proof = $getTransaction['address_proof'];

			#$insured_name = $getTransaction['insured_name'];
			#$insured_dob = $getTransaction['insured_dob'] != '0000-00-00' ? date('d-m-Y',strtotime($getTransaction['insured_dob'])) : '';
			#$insured_father = $getTransaction['insured_father'];
			#$insured_address = $getTransaction['insured_address'];
			#$insured_height = $getTransaction['insured_height'];
			#$insured_weight = $getTransaction['insured_weight'];
			#$educational_qualification = $getTransaction['educational_qualification'];
			#$marital_status = $getTransaction['marital_status'];

			#$husbands_name = $getTransaction['husbands_name'];
			
			#$nominee_address = $getTransaction['nominee_address'];
			#$employers_name = $getTransaction['employers_name'];
			#$employers_address = $getTransaction['employers_address'];
			#$employers_pin = $getTransaction['employers_pin'];
			#$employers_phone = $getTransaction['employers_phone'];
			#$husbands_sum_assured = $getTransaction['husbands_sum_assured'];

			#$state = $getTransaction['state'];
			//$city = $getTransaction['city'];
			#$phone = $getTransaction['phone'];
			#$zip = $getTransaction['zip'];
			#$annual_income = $getTransaction['annual_income'];
			#$occupation = $getTransaction['occupation'];
			#$nominee_name = $getTransaction['nominee_name'];
			#$nominee_address = $getTransaction['nominee_address'];
			#$relationship_type = $getTransaction['nominee_relationship'];
			
			#$nominee_address = $getTransaction['nominee_address'];

			#$employers_state = $getTransaction['employers_state'];
			#$serial_no = $getTransaction['serial_no'];

			


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


if(!isset($short_premium_amount)) { $short_premium_amount = ''; } 
if(!isset($short_premium_payment_mode)) { $short_premium_payment_mode = ''; } 

if(!isset($sp_chk_dd_no)) { $sp_chk_dd_no = ''; } 
//if(!isset($sp_chk_dd_bankname)) { $sp_chk_dd_bankname = ''; }
if(!isset($sp_chk_dd_bankname)) { $sp_chk_dd_bankname = ''; } 


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

	
	function dochk()
	{	
			
			

			
		if(document.addForm.short_premium_payment_mode.value.search(/\S/) == -1)
		{
			alert("Please Enter Payment Mode");
			document.addForm.short_premium_payment_mode.focus();
			return false;
		}	

		if(document.addForm.short_premium_amount.value.search(/\S/) == -1)
		{
			alert("Please Enter Short premiun Amount");
			document.addForm.short_premium_amount.focus();
			return false;
		}
		paymentMode=document.getElementById("short_premium_payment_mode").value;
		if((paymentMode=='CHEQUE') || (paymentMode=='DD'))
		{
			if(document.addForm.sp_chk_dd_date.value.search(/\S/) == -1)
			{
				alert("Please Enter Cheque/DD Date");
				document.addForm.insured_address.focus();
				return false;
			}
		}
		

		if((paymentMode=='CHEQUE') || (paymentMode=='DD'))
		{
			if(document.addForm.sp_chk_dd_no.value.search(/\S/) == -1)
			{
				alert("Please Enter Cheque/DD No.");
				document.addForm.sp_chk_dd_no.focus();
				return false;
			}
			if(document.addForm.sp_chk_dd_bankname.value.search(/\S/) == -1)
			{
				alert("Please Enter Bank Name");
				document.addForm.sp_chk_dd_bankname.focus();
				return false;
			}
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
	 <!-- <input type="hidden" name="transaction_charges" value="<?php echo $transaction_charges; ?>"> -->
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
      <td width="27%" align="right" valign="top" class="tbllogin">Bank Account</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo ($bank_ac == '1' ? 'Yes' : 'No'); ?></td>
    </tr>
	<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">IFS Code</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo $ifs_code_main; ?></td>
    </tr>
	<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">MICR Code</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo $micr_code_main; ?></td>
    </tr>
	<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Bank Name</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo $bank_name_main; ?></td>
    </tr>
	<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Branch Name</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo $branch_name_main; ?></td>
    </tr>
	<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Account No.</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo $account_no_main; ?></td>
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
      <td class="tbllogin" valign="top" align="right">Deposit Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $deposit_date; ?></td>
    </tr>

		

		

	<tr> 
      <td class="tbllogin" valign="top" align="right">Applicant Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><div id="app_name"><?php echo $first_name.' '.$middle_name.' '.$last_name; ?></div></td>
    </tr>	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Plan</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo find_product_name($plan); ?></td>
    </tr>


	<tr> 
      <td class="tbllogin" valign="top" align="right">Term</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $tenure; ?> Years</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Payment Mode</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $payment_mode; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Payment Amount</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $main_amount; ?></td>
    </tr>

	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Total Amount</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $amount; ?></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Sum Assured</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $sum_assured; ?></td>
    </tr>	


	
		
		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Short Premium</font></b></td>
    </tr>	
	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Short premium Payment Mode<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	<select name="short_premium_payment_mode" id="short_premium_payment_mode" class="inplogin_select" onchange="otherpaydropdown(this.value);">
					<option value="">Select</option>
					<option value="CASH" <?php echo ($short_premium_payment_mode == 'CASH' ? 'selected' : ''); ?>>Cash</option>
					<option value="CHEQUE" <?php echo ($short_premium_payment_mode == 'CHEQUE' ? 'selected' : ''); ?>>Cheque</option>
					<option value="DD" <?php echo ($short_premium_payment_mode == 'DD' ? 'selected' : ''); ?>>DD</option>
					<!-- <option value="ECS" <?php echo ($payment_mode == 'ECS' ? 'selected' : ''); ?>>ECS</option> -->
				</select>&nbsp;&nbsp; Amount<font color="#ff0000">*</font>&nbsp;&nbsp;<input name="short_premium_amount" id="short_premium_amount" type="text" class="inplogin"  value="<?php echo $short_premium_amount; ?>" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')">			
				
		</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Short premium DD / Cheque Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="sp_chk_dd_date" id="sp_chk_dd_date" type="text" class="inplogin"  value="<?php echo $sp_chk_dd_date; ?>" maxlength="100" readonly /> &nbsp;<img src="images/cal.gif" id="calChq" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.sp_chk_dd_date,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Short premium DD / Cheque Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="sp_chk_dd_no" id="sp_chk_dd_no" type="text" class="inplogin"  value="<?php echo $sp_chk_dd_no; ?>" maxlength="100" ></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Short premium Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="sp_chk_dd_bankname" id="sp_chk_dd_bankname" type="text" class="inplogin"  value="<?php echo $sp_chk_dd_bankname; ?>" maxlength="100" ></td>
    </tr>
<?php if($short_premium_amount == ""){?>
 <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Update" class="inplogin" type="submit" onclick="return dochk()"> <!-- 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset">--> </td>
    </tr>
	<?php }?>
  </tbody>
	 </table>
	 </form>
 </div>
 
 </center>
 </body>
</html>