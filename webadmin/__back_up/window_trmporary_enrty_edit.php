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
$pageOwner = "'superadmin','admin','hub','branch','subadmin'";

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


$selIDProof = mysql_query("SELECT id, document_name FROM id_proof ORDER BY document_name ASC ");
$numIDProof = mysql_num_rows($selIDProof);



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
	if(isset($_POST['agent_code']) && $_POST['agent_code'] != '')
	{
		//echo '<pre>';
		//print_r($_POST);
		
		//echo '</pre>';
		extract($_POST);
		#if(trim($employee_code) != ''){ $transaction_charges = 0.00; }
		if($_POST['appointee_relationship_type'] != "")
		{
		$appointee_relationship_type_name = $_POST['appointee_relationship_type'];
		}
		else
		{
		$appointee_relationship_type_name = $_POST['appointee_relationship_display'];
		}



		if($_POST['relationship_type'] != "")
		{
		$relationship_type_name = $_POST['relationship_type'];
		}
		else
		{
		$relationship_type_name = $_POST['relationship_type_name'];
		}

		if($_POST['occupation'] != "")
		{
		$occupation_name = $_POST['occupation'];
		}
		else
		{
		$occupation_name = $_POST['occupation_name'];
		}



		$update_installment = "UPDATE temporary_installment_master SET 	
										agent_code = '".realTrim($agent_code)."',
										agent_name = '".realTrim($agent_name)."',
										insured_name = '".realTrim($insured_name)."',
										dd_bank_name = '".realTrim($dd_bank_name)."',
										dd_number =  '".realTrim($dd_number)."',
										dd_date = '".date('Y-m-d',strtotime($dd_date))."',

										insured_dob = '".date('Y-m-d',strtotime($insured_dob))."',
										insured_father = '".realTrim($insured_father)."',
										insured_address = '".realTrim($insured_address)."',
										state = '".realTrim($state)."',
										zip = '".realTrim($zip)."',
										phone = '".realTrim($phone)."',
										occupation = '".realTrim($occupation_name)."',

										nature_of_business = '".realTrim($nature_of_business)."',
										annual_income = '".realTrim($annual_income)."',
										nominee_name = '".realTrim($nominee_name)."',
										nominee_relationship = '".realTrim($relationship_type_name)."',
										appointee_name = '".realTrim($appointee_name)."',
										appointee_relationship = '".realTrim($appointee_relationship_type_name)."',
										nominee_dob = '".date('Y-m-d',strtotime($nominee_dob))."',
										nominee_address = '".realTrim($nominee_address)."',
										insured_height = '".realTrim($insured_height)."',
										insured_weight = '".realTrim($insured_weight)."',
										gender = '".realTrim($gender)."',
										educational_qualification = '".realTrim($educational_qualification)."',
										marital_status = '".realTrim($marital_status)."',
										employers_name = '".realTrim($employers_name)."',
										employers_address = '".realTrim($employers_address)."',
										employers_state = '".realTrim($employers_state)."',
										employers_pin = '".realTrim($employers_pin)."',
										employers_phone = '".realTrim($employers_phone)."',
										husbands_name = '".realTrim($husbands_name)."',
										husbands_sum_assured = '".realTrim($husbands_sum_assured)."',
										age_proof = '".realTrim($age_proof)."',
										id_proof = '".realTrim($id_proof)."',
										income_proof = '".realTrim($income_proof)."',
										address_proof = '".realTrim($address_proof)."',
										is_edited = '1'
										
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
	$selTransaction = mysql_query("SELECT * FROM temporary_installment_master WHERE id='".$invoice_id."'");
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
			$other_amount = $getTransaction['other_amount'];
			$sum_assured = $getTransaction['sum_assured'];
			$account_no = $getTransaction['account_no'];
			$in_favour = $getTransaction['in_favour'];

			$receipt_number = $getTransaction['receipt_number'];
			$transaction_id = $getTransaction['transaction_id'];
			$amount = $getTransaction['amount'];
			$payment_mode = $getTransaction['payment_mode'];
			$other_payment_mode = $getTransaction['other_payment_mode'];

			$dd_number = $getTransaction['dd_number'];
			$dd_bank_name = $getTransaction['dd_bank_name'];
			$dd_date = '';
			//echo "hi:".$getTransaction['dd_date'];
			if(isset($getTransaction['dd_date']) && ($getTransaction['dd_date'] != '1970-01-01')){
			$dd_date = date('d-m-Y', strtotime($getTransaction['dd_date']));
			}
			$ifs_code = $getTransaction['ifs_code'];
			$micr_code = $getTransaction['micr_code'];
			if($dd_date == '0000-00-00' || $dd_date =='1970-01-01'){ $dd_date=''; }
			$first_name = $getTransaction['first_name'];
			$middle_name = $getTransaction['middle_name'];
			$last_name = $getTransaction['last_name'];
			$gender = $getTransaction['gender'];
			
			$nature_of_business = $getTransaction['nature_of_business'];
			$nominee_dob = $getTransaction['nominee_dob'] != '0000-00-00' ? date('d-m-Y',strtotime($getTransaction['nominee_dob'])) : '';
			

			$age_proof = $getTransaction['age_proof'];
			$income_proof = $getTransaction['income_proof'];
			$address_proof = $getTransaction['address_proof'];
			$id_proof = $getTransaction['id_proof'];

			$insured_name = $getTransaction['insured_name'];
			$insured_dob = $getTransaction['insured_dob'] != '0000-00-00' ? date('d-m-Y',strtotime($getTransaction['insured_dob'])) : '';
			$insured_father = $getTransaction['insured_father'];
			$insured_address = $getTransaction['insured_address'];
			$insured_height = $getTransaction['insured_height'];
			$insured_weight = $getTransaction['insured_weight'];
			$educational_qualification = $getTransaction['educational_qualification'];
			$marital_status = $getTransaction['marital_status'];

			$husbands_name = $getTransaction['husbands_name'];
			
			$nominee_address = $getTransaction['nominee_address'];
			$employers_name = $getTransaction['employers_name'];
			$employers_address = $getTransaction['employers_address'];
			$employers_pin = $getTransaction['employers_pin'];
			$employers_phone = $getTransaction['employers_phone'];
			$husbands_sum_assured = $getTransaction['husbands_sum_assured'];

			$state = $getTransaction['state'];
			//$city = $getTransaction['city'];
			$phone = $getTransaction['phone'];
			$zip = $getTransaction['zip'];
			$annual_income = $getTransaction['annual_income'];
			$occupation = $getTransaction['occupation'];
			$nominee_name = $getTransaction['nominee_name'];
			$nominee_address = $getTransaction['nominee_address'];
			$relationship_type = $getTransaction['nominee_relationship'];
			
			$appointee_name = $getTransaction['appointee_name'];
			$appointee_relationship = $getTransaction['appointee_relationship'];

			$employers_state = $getTransaction['employers_state'];
			$serial_no = $getTransaction['serial_no'];

			


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
if(!isset($dob)) { $dob = ''; }
if(!isset($age_proof)) { $age_proof = ''; }
if(!isset($id_proof)) { $id_proof = ''; }
if(!isset($insurance)) { $insurance = ''; }
if(!isset($income_proof)) { $income_proof = ''; }
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
if(!isset($appointee_relationship_type)) { $appointee_relationship_type = ''; }
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


	<script type="text/javascript">
	function insuranceEligible(dob)
	{
		//alert('123');
		//return false;
		//var dob = '22-11-1982'; // dd--mm-yyyy
		var splitted = dob.split("-");
		//alert(splitted[0]);
		//alert(splitted[1]);
		//alert(splitted[2]);
		var birthDate = new Date(splitted[2],splitted[1],splitted[0]);
		var today = new Date();
		if ((today >= new Date(birthDate.getFullYear() + 18, birthDate.getMonth() - 1, birthDate.getDate())) && (today <= new Date(birthDate.getFullYear() + 46, birthDate.getMonth() - 1, birthDate.getDate()))) 
		{
		  // Allow access
		  //alert("Eligible");
			
			return true;
		} 
		else 
		{
		  // Deny access
		  alert("Not Eligible");
		  return false;
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

	function copy_datas()
	{
	//alert("Hi");
	//alert(dob);
	//return false;
	dob=document.getElementById('app_dob').value;
	//alert(dob);
	document.getElementById('insured_name').value=document.getElementById('app_name').innerHTML;
	document.getElementById('insured_dob').value=dob;

	}

	function copy_adds()
	{
	//alert("Hi");
	//return false;
	document.getElementById('nominee_address').value=document.getElementById('insured_address').value;
	}
	</script>
	
	<script type="text/javascript">
<!--
	function update_trans_id()
	{
		document.getElementById("transaction_id").value = document.getElementById("receipt_number").value;
	}
	
	
	
	
	function dochk()
	{	
			
			

			
		if(document.addForm.insured_name.value.search(/\S/) == -1)
		{
			alert("Please Enter Insured Name");
			document.addForm.insured_name.focus();
			return false;
		}	
		//if(document.addForm.dd_date.value.search(/\S/) == -1)
		//{
		//	alert("Please Enter Chaque/DD Date");
		//	document.addForm.dd_date.focus();
		//	return false;
		//}
		if(document.addForm.insured_dob.value.search(/\S/) == -1)
		{
			alert("Please Enter Insured DOB");
			document.addForm.insured_dob.focus();
			return false;
		}

		if(document.addForm.insured_father.value.search(/\S/) == -1)
		{
			alert("Please Enter Father's Name");
			document.addForm.insured_father.focus();
			return false;
		}
		
		if(document.addForm.insured_address.value.search(/\S/) == -1)
		{
			alert("Please Enter Insured Address");
			document.addForm.insured_address.focus();
			return false;
		}	
		

		if(document.addForm.zip.value.search(/\S/) == -1)
		{
			alert("Please Enter PIN");
			document.addForm.zip.focus();
			return false;
		}	

		if(document.addForm.phone.value.search(/\S/) == -1)
		{
			alert("Please Enter Phone");
			document.addForm.phone.focus();
			return false;
		}	
		
		
		if(document.addForm.occupation.value.search(/\S/) == -1)
		{
			alert("Please Enter Occupation");
			document.addForm.occupation.focus();
			return false;
		}	

		if(document.addForm.nominee_name.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee Name");
			document.addForm.nominee_name.focus();
			return false;
		}		
		

		
		relation=document.getElementById("relationship_type").value;
		relationship_display = document.getElementById("relationship_display").value;
		//alert (relation);
		//alert (relationship_display);
		if((relation == "") && (relationship_display == "")) 
		{
			alert("Please Select Relationship with Nomninee");
			document.addForm.relationship_type.focus();
			return false;
		}		


		if(document.addForm.nominee_address.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee Address");
			document.addForm.nominee_address.focus();
			return false;
		}	

		
		if(document.addForm.insured_height.value.search(/\S/) == -1)
		{
			alert('Please Enter Insured Height');
			document.addForm.insured_height.focus();
			return false;
		}	

		if(document.addForm.insured_weight.value.search(/\S/) == -1)
		{
			alert('Please Enter Insured Weight');
			document.addForm.insured_weight.focus();
			return false;
		}	
		

		

		if(document.addForm.gender.value.search(/\S/) == -1)
		{
			alert('Please Enter Gender');
			document.addForm.gender.focus();
			return false;
		}

		if(document.addForm.educational_qualification.value.search(/\S/) == -1)
		{
			alert('Please Enter Educational Qualification');
			document.addForm.educational_qualification.focus();
			return false;
		}
		
		if(document.addForm.age_proof.value.search(/\S/) == -1)
		{
			alert('Please Enter Age Proof');
			document.addForm.age_proof.focus();
			return false;
		}

		if(document.addForm.id_proof.value.search(/\S/) == -1)
		{
			alert('Please Enter ID Proof');
			document.addForm.id_proof.focus();
			return false;
		}


		if(document.addForm.nominee_dob.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee DOB");
			document.addForm.nominee_dob.focus();
			return false;
		}else
		{
			ndob=document.addForm.nominee_dob.value;
			//alert(ndob);
			var splitted = ndob.split("-");
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
		  
		if(document.addForm.appointee_name.value.search(/\S/) == -1)
		{
			alert("Please enter Appointee Name");
			document.addForm.appointee_name.focus();
			return false;
		}		

		a_relation=document.getElementById("appointee_relationship_type").value;
		a_relationship_display = document.getElementById("appointee_relationship_display").value;
		//alert (relation);
		//alert (relationship_display);
		if((a_relation== "") && (a_relationship_display == "")) 
		{
			alert("Please Select Relationship with Appointee");
			document.addForm.appointee_relationship_type.focus();
			return false;
		}		





		}
			//return false;
		}

		
		
		gender=document.getElementById("gender").value;
		maritalstatus=document.getElementById("marital_status").value;
		//alert(gender);
		//alert(maritalstatus);
		//return false;
		if((gender == 'F') && (maritalstatus == 'MARRIED'))
		{
			if(document.addForm.husbands_name.value.search(/\S/) == -1)
			{
				alert('Please Enter Husband Name');
				document.addForm.husbands_name.focus();
				return false;
			}
			
		}
		
		
	}
	
//-->
</script>
<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>


<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>

<script type="text/javascript">
function removeEnter(data)
	{
	alert "Hi";
	return false;
	$('data').keypress(function(event) {
			if (event.keyCode == 13) {
			 return false;
				}
			});


			
	}
	</script>
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
      <td width="68%" align="left" valign="top"><?php echo stripslashes($branch_name_main); ?></td>
    </tr>
	<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Account No.</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo stripslashes($account_no_main); ?></td>
    </tr>

	<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Branch Name</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo stripslashes($branch_name); ?></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Application No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo stripslashes($application_no); ?></td>
    </tr>
    
    <tr> 
      <td class="tbllogin" valign="top" align="right">Deposit Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo stripslashes($deposit_date); ?></td>
    </tr>

		

		<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Code<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
		
		<?php 
		$readonly = '';
		//echo $_SESSION[ROLE_ID]; 
				if($_SESSION[ROLE_ID] == 4)
				{
					if(($agent_code != '') && ($agent_name != ''))
						{
						$readonly = 'readonly="readonly"'; 
						}
				}
						
						?>

      <td valign="top" align="left"><input type="text" class="inplogin" name="agent_code" id="agent_code" value="<?php echo $agent_code; ?>" onKeyUp="this.value = this.value.toUpperCase();" <?php echo $readonly;?>></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" class="inplogin" name="agent_name" id="agent_name" value="<?php echo $agent_name; ?>" <?php echo $readonly;?>></td>
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
      <td class="tbllogin" valign="top" align="right">Cheque / DD Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
     <!-- <td valign="top" align="left"><?php echo $dd_number; ?></td>-->
	 
	  <?php if($payment_mode != 'CASH'){?>
	  <td valign="top" align="left"><input type="text" class="inplogin" name="dd_number" id="dd_number" value="<?php echo $dd_number; ?>" onKeyUp="this.value = this.value.toUpperCase();" ></td><?php }?>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque / DD Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
   	  <td valign="top" align="left"><input name="dd_date" id="dd_date" type="text" class="inplogin"  value="<?php echo str_replace('/', '-', $dd_date ); ?>" maxlength="20" readonly /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.dd_date,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque / DD Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <!--<td valign="top" align="left"><?php echo $dd_bank_name; ?></td>-->
	<?php if($payment_mode != 'CASH'){?>
	  <td valign="top" align="left"><input type="text" class="inplogin" name="dd_bank_name" id="dd_bank_name" value="<?php echo $dd_bank_name; ?>" onKeyUp="this.value = this.value.toUpperCase();" ></td><?php }?>
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
      <td class="tbllogin" valign="top" align="right">Account No</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $account_no; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">In Favour</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $in_favour; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Other Payment Mode</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $other_payment_mode; ?></td>
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
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Secondary Entry</font></b></td>
    </tr>	
	
	<!--<tr> 
      <td class="tbllogin" valign="top" align="right">Receipt Serial No.<font color="#ff0000">*</font><br /><font style="font-size:10px;">( Pre printed value on the receipt)</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="serial_no" id="serial_no" type="text" class="inplogin"  value="<?php echo $serial_no; ?>" maxlength="7" onKeyPress="return keyRestrict(event, '0123456789')" ></td>
    </tr>-->
	<?php //$dob = date('d-m-Y',strtotime($getTransaction['applicant_dob'])); ?>
	<input type="hidden" name="app_dob" id="app_dob" value="<?php echo date('d-m-Y',strtotime($getTransaction['applicant_dob']));?>">
	
	<tr>
	<td align="center"><input name="copy_data" id="copy_data" type="button" onClick="copy_datas();" value="Copy"  ></td></tr>
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Name<font color="#ff0000">*</font><br /><font style="font-size:10px;"></font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td align="left"><input name="insured_name" id="insured_name" type="text" class="inplogin"  value="<?php echo stripslashes($insured_name); ?>" onKeyUp="this.value = this.value.toUpperCase();" style="width:240px;">
	<strong> Application No.:</strong>
     
      <?php echo $application_no; ?>
	</td>


    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured DOB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="insured_dob" id="insured_dob" type="text" class="inplogin"  value="<?php echo str_replace('/', '-', $insured_dob ); ?>" maxlength="20" readonly /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.insured_dob,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>

	<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Insured Age</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"></td>
    </tr>
 -->
	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Father's Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="insured_father" id="insured_father" type="text" class="inplogin"  value="<?php echo stripslashes($insured_father); ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Address <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="insured_address" id="insured_address" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});"  style="width:200px;"><?php echo $insured_address; ?></textarea></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">State <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">	

	  <select name="state" id="state" class="inplogin" >
				<option value="">Select</option>
				<?php 
					if(count($rsplace) > 0)
					{
						for($i=0; $i < count($rsplace); $i++)
						{
				?>
				<option value="<?php echo $rsplace[$i]['id']?>" <?php echo ($state == $rsplace[$i]['id'] ? 'selected' : '') ?>><?php echo $rsplace[$i]['place']?></option>
				<?php
						}
					}
				?>
			</select>
	  
		</td>
    </tr>

	<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">City</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="city" id="city" type="text" class="inplogin"  value="<?php echo $city; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr> -->

		<tr> 
      <td class="tbllogin" valign="top" align="right">PIN<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="zip" id="zip" type="text" class="inplogin"  value="<?php echo $zip; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Phone<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="phone" id="phone" type="text" class="inplogin"  value="<?php echo $phone; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Occupation <font color="#ff0000">*</font> </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<select name="occupation" id="occupation" class="inplogin" onchange="document.addForm.occupation_name.value = this.value; document.addForm.occupation_display.value = '';">
				<option value="">Select</option>
				<?php 
					if($numOccupation > 0)
					{
						while($getOccupation = mysql_fetch_array($selOccupation))
						{
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
      <td class="tbllogin" valign="top" align="right">Nature Of Business<!-- <font color="#ff0000">*</font> --></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="nature_of_business" id="nature_of_business" type="text" class="inplogin"  value="<?php echo $nature_of_business; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Annual Income (INR)<!-- <font color="#ff0000">*</font> --></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="annual_income" id="annual_income" type="text" class="inplogin"  value="<?php echo $annual_income; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')"></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="nominee_name" id="nominee_name" type="text" class="inplogin"  value="<?php echo $nominee_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" style="width:220px;"></td>
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
				?>
				<option value="<?php echo $getRelationship['relationship']?>" <?php echo ($relationship_type == $getRelationship['relationship'] ? 'selected' : '') ?>><?php echo $getRelationship['relationship']?></option>
				<?php
						}
					}
				?>
			</select>
			
			<?php $relationship_id = find_relationship_id($relationship_type); //echo $relationship_id; ?>
			&nbsp;<strong>Others</strong>&nbsp;
			
			<input name="relationship_type_name" id="relationship_type_name" type="hidden" class="inplogin"  value="<?php echo $relationship_type; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"> 
			
			
			<input name="relationship_display" id="relationship_display" type="text" class="inplogin"  value="<?php echo (intval($relationship_id) != 0 ? '' : $relationship_type); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.relationship_type_name.value = this.value">
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee DOB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="nominee_dob" id="nominee_dob" type="text" class="inplogin"  value="<?php echo $nominee_dob; ?>" maxlength="20" readonly/> <font color="#ff0000"></font>&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.nominee_dob,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>
	

<tr> 
      <td class="tbllogin" valign="top" align="right">Appointee Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="appointee_name" id="appointee_name" type="text" class="inplogin"  value="<?php echo $appointee_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" style="width:220px;"></td>
    </tr>

	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Relationship<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	 <?php $selRelationship = mysql_query("SELECT id, relationship FROM relationship_master ORDER BY relationship ASC ");
$numRelationship = mysql_num_rows($selRelationship);
?>  
			<select name="appointee_relationship_type" id="appointee_relationship_type" class="inplogin" onchange="document.addForm.appointee_relationship_type_name.value = this.value; document.addForm.appointee_relationship_display.value = '';">
				<option value="">Select</option>
				<?php 
					if($numRelationship > 0)
					{
						while($getRelationship = mysql_fetch_array($selRelationship))
						{							
				?>
				<option value="<?php echo $getRelationship['relationship']?>" <?php echo ($appointee_relationship == $getRelationship['relationship'] ? 'selected' : '') ?>><?php echo $getRelationship['relationship']?></option>
				<?php
						}
					}
				?>
			</select>
			
			<?php $appointee_relationship_id = find_relationship_id($appointee_relationship); 
			//echo $appointee_relationship_id; ?>
			&nbsp;<strong>Others</strong>&nbsp;
			
			 
			
			<input name="appointee_relationship_display" id="appointee_relationship_display" type="text" class="inplogin"  value="<?php echo (intval($appointee_relationship_id) != 0 ? '' : $appointee_relationship); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.appointee_relationship_type_name.value = this.value">

			<input name="appointee_relationship_type_name" id="appointee_relationship_type_name" type="hidden" class="inplogin"  value="<?php echo $appointee_relationship; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();">



			</td>

			</td>
    </tr>



		<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee Address<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="nominee_address" id="nominee_address" class="inplogin" onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});" ><?php echo $nominee_address; ?></textarea></td>
	  <td align="left"><input name="copy_addrs" id="copy_addrs" type="button" onClick="copy_adds();" value="Copy Insured Address"  ></td>


    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Height<font color="#ff0000">*</font> </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="insured_height" id="insured_height" type="text" class="inplogin"  value="<?php echo $insured_height; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"> CM</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Weight<font color="#ff0000">*</font> </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="insured_weight" id="insured_weight" type="text" class="inplogin"  value="<?php echo $insured_weight; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"> KG</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Gender<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="gender" id="gender" class="inplogin_select">
					<option value="">Select</option>
					<option value="M" <?php echo ($gender == 'M' ? 'selected' : ''); ?>>Male</option>
					<option value="F" <?php echo ($gender == 'F' ? 'selected' : ''); ?>>Female</option>
				</select>
			</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Educational Qualification<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="educational_qualification" id="educational_qualification" class="inplogin_select" style="width:200px;">
					<option value="">Select</option>
					<option value="ILLITERATE" <?php echo ($educational_qualification == 'ILLITERATE' ? 'selected' : ''); ?>>ILLITERATE</option>

					<option value="UNDER 10TH STANDARD" <?php echo ($educational_qualification == 'UNDER 10TH STANDARD' ? 'selected' : ''); ?>>UNDER 10TH STANDARD</option>
					<option value="SECONDARY" <?php echo ($educational_qualification == 'SECONDARY' ? 'selected' : ''); ?>>SECONDARY</option>
					<option value="HIGHER SECONDARY" <?php echo ($educational_qualification == 'HIGHER SECONDARY' ? 'selected' : ''); ?>>HIGHER SECONDARY</option>
					<option value="GRADUATE" <?php echo ($educational_qualification == 'GRADUATE' ? 'selected' : ''); ?>>GRADUATE</option>
					<option value="MASTERS" <?php echo ($educational_qualification == 'MASTERS' ? 'selected' : ''); ?>>MASTERS</option>
					<option value="RESEARCH SCHOLAR" <?php echo ($educational_qualification == 'RESEARCH SCHOLAR' ? 'selected' : ''); ?>>RESEARCH SCHOLAR</option>
				</select>
			</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Marital Status</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="marital_status" id="marital_status" class="inplogin_select" style="width:200px;">
					<option value="">Select</option>
					<option value="UNMARRIED" <?php echo ($marital_status == 'UNMARRIED' ? 'selected' : ''); ?>>UNMARRIED</option>
					<option value="MARRIED" <?php echo ($marital_status == 'MARRIED' ? 'selected' : ''); ?>>MARRIED</option>
					<option value="DIVORCED" <?php echo ($marital_status == 'DIVORCED' ? 'selected' : ''); ?>>DIVORCED</option>
					<option value="WIDOWED" <?php echo ($marital_status == 'WIDOWED' ? 'selected' : ''); ?>>WIDOWED</option>
				</select>
			</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Employer's Name </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="employers_name" id="employers_name" type="text" class="inplogin"  value="<?php echo $employers_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" style="width:250px;"></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Employer's Address </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="employers_address" id="employers_address" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});"><?php echo $employers_address; ?></textarea></td>
    </tr>

	<!--  -->

	<tr> 
      <td class="tbllogin" valign="top" align="right">Employer's State</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
		<select name="employers_state" id="employers_state" class="inplogin" >
				<option value="">Select</option>
				<?php 
					if(count($rsplace) > 0)
					{
						for($i=0; $i < count($rsplace); $i++)
						{
				?>
				<option value="<?php echo $rsplace[$i]['id']?>" <?php echo ($employers_state == $rsplace[$i]['id'] ? 'selected' : '') ?>><?php echo $rsplace[$i]['place']?></option>
				<?php
						}
					}
				?>
			</select>
		</td>
    </tr>

	
		<tr> 
      <td class="tbllogin" valign="top" align="right">Employer's PIN</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="employers_pin" id="employers_pin" type="text" class="inplogin"  value="<?php echo $employers_pin; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Employer's Phone</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="employers_phone" id="employers_phone" type="text" class="inplogin"  value="<?php echo $employers_phone; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
    </tr>

	<!--  -->

	<tr> 
      <td class="tbllogin" valign="top" align="right">Husband's Name </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="husbands_name" id="husbands_name" type="text" class="inplogin"  value="<?php echo $husbands_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Husband's Sum Assured </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="husbands_sum_assured" id="husbands_sum_assured" type="text" class="inplogin"  value="<?php echo $husbands_sum_assured; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="return keyRestrict(event, '0123456789.')"></td>
    </tr>


	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Age Proof
	  <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<div style="overflow:auto;width:250px;" align="left">
				<select name="age_proof" id="age_proof" align="left">
					<option value="" >Select Document</option>
					<?php
						if($numAgeProof > 0)
						{
							while($getAgeProof = mysql_fetch_assoc($selAgeProof))
							{
					?>
						<option value="<?php echo $getAgeProof['id']; ?>" <?php echo ($getAgeProof['id'] == $age_proof ? 'selected' : ''); ?> ><?php echo $getAgeProof['document_name']; ?></option>
					<?php		
							}						
						}
					?>
					
				</select>
				</div>
			</td>
    </tr>




	
	<tr> 
      <td class="tbllogin" valign="top" align="right">ID Proof
	  <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<div style="overflow:auto;width:250px;" align="left">
				<select name="id_proof" id="id_proof" align="left">
					<option value="" >Select Document</option>
					<?php
						if($numIDProof > 0)
						{
							while($getIDProof = mysql_fetch_assoc($selIDProof))
							{
					?>
						<option value="<?php echo $getIDProof['id']; ?>" <?php echo ($getIDProof['id'] == $id_proof ? 'selected' : ''); ?> ><?php echo $getIDProof['document_name']; ?></option>
					<?php		
							}						
						}
					?>
					
				</select>
				</div>
			</td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Income Proof<!-- <font color="#ff0000">*</font> --></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="income_proof" id="income_proof" class="inplogin_select" style="width:250px;">
					<option value="">Select Document</option>
					<?php
						if($numIncomeProof > 0)
						{
							while($getIncomeProof = mysql_fetch_assoc($selIncomeProof))
							{
					?>
						<option value="<?php echo $getIncomeProof['id']; ?>" <?php echo ($getIncomeProof['id'] == $income_proof ? 'selected' : ''); ?>><?php echo $getIncomeProof['document_name']; ?></option>
					<?php		
							}						
						}
					?>					
				</select>
			</td>
    </tr>

		

		<tr> 
      <td class="tbllogin" valign="top" align="right">Address Proof<!-- <font color="#ff0000">*</font> --></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<div style="overflow:auto;width:250px;" align="left"> 
				<!--<select name="address_proof" id="address_proof" class="inplogin_select" style="width:250px;">-->
				<select name="address_proof" id="address_proof" align="left">
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
				</div>
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