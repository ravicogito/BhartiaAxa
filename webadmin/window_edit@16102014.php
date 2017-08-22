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
	
	
	//exit;
	$invoice_id = base64_decode($_GET['id']);
	//echo $invoice_id;
	//$mysql_customer_id = find_customer_id_through_installment_id($invoice_id);
	//$folio_no_id = find_folio_id_through_transaction_id($invoice_id);
	if(isset($_POST['insured_name']) && $_POST['insured_name'] != '')
	{
		
		extract($_POST);
		
				if(!isset($phase)) { $phase = ''; } 
				if(!isset($agent_code)) { $agent_code = ''; } 
				if(!isset($pre_printed_receipt_no)) { $pre_printed_receipt_no = ''; } 
				if(!isset($term)) { $term = ''; } 
				if(!isset($sum_asured)) { $sum_asured = ''; } 
				if(!isset($pay_mode)) { $pay_mode = ''; } 
				if(!isset($insured_name)) { $insured_name = ''; } 
				if(!isset($insured_dob)) { $insured_dob = ''; }
				if($insured_dob!='') {  $insured_dob=date('Y-m-d', strtotime($insured_dob)); }
				 
				if(!isset($insured_age)) { $insured_age = ''; } 
				if(!isset($insured_address1)) { $insured_address1 = ''; } 
				if(!isset($insured_address2)) { $insured_address2 = ''; } 
				if(!isset($insured_address3)) { $insured_address3 = ''; } 
				if(!isset($state_id)) { $state_id = ''; } 
				if(!isset($pin)) { $pin = ''; } 
				if(!isset($telephone_no)) { $telephone_no = ''; } 
				
				if(!isset($occupation_name)) {$occupation_name = ''; } 
				if(!isset($anual_income)) { $anual_income = ''; } 
				if(!isset($nominee_name)) { $nominee_name = ''; }
				if(!isset($nominee_relationship_name)) { $nominee_relationship_name = ''; }
				if(!isset($nominee_dob)) { $nominee_dob = ''; }
				if($nominee_dob!='') {  $nominee_dob=date('Y-m-d', strtotime($nominee_dob)); }
				
				if(!isset($nominee_age)) { $nominee_age = ''; }
				if(!isset($appointee_name)) { $appointee_name = ''; }
				if(!isset($appointee_relationship_name)) { $appointee_relationship_name = ''; }
				if(!isset($appointee_dob)) { $appointee_dob = ''; }
				if($appointee_dob!='') {  $appointee_dob=date('Y-m-d', strtotime($appointee_dob)); }
				
				if(!isset($appointee_age)) { $appointee_age = ''; }
				if(!isset($insured_height)) { $insured_height = ''; }
				if(!isset($insured_weight)) { $insured_weight = ''; }
				if(!isset($gender)) { $gender = ''; }
				if(!isset($education_qualification)) { $education_qualification = ''; }
				if(!isset($office_name)) { $office_name = ''; }
				if(!isset($nature_of_duty)) { $nature_of_duty = ''; }
				if(!isset($office_address1)) { $office_address1 = ''; }
				if(!isset($office_address2)) { $office_address2 = ''; }
				if(!isset($office_address3)) { $office_address3 = ''; }
				if(!isset($office_teephone_no)) { $office_teephone_no = ''; }
				if(!isset($insured_age_proof)) { $insured_age_proof = ''; }
				if(!isset($identity_proof)) { $identity_proof = ''; }
				if(!isset($address_proof)) { $address_proof = ''; }


		$update_installment = "UPDATE installment_master_branch SET
										phase_id = '$phase',
										agent_code = '$agent_code',
										pre_printed_receipt_no='$pre_printed_receipt_no', 	
										term='$term',
										sum_asured='$sum_asured ',
										pay_mode='$pay_mode',
										insured_name='$insured_name',
										insured_dob='$insured_dob',
										insured_age='$insured_age',
										insured_address1='$insured_address1', 
										insured_address2='$insured_address2', 
										insured_address3='$insured_address3', 
										state_id='$state_id', 
										pin='$pin', 
										telephone_no='$telephone_no',
										occupation='$occupation_name',
										anual_income='$anual_income',
										nominee_name='$nominee_name',
										nominee_relationship='$nominee_relationship_name',
										nominee_dob='$nominee_dob',
										nominee_age='$nominee_age',
										appointee_name='$appointee_name',
										appointee_relationship='$appointee_relationship_name',
										appointee_dob='$appointee_dob',
										appointee_age='$appointee_age',
										insured_height='$insured_height',
										insured_weight='$insured_weight',
										gender='$gender',
										education_qualification='$education_qualification',
										office_name='$office_name',
										nature_of_duty='$nature_of_duty',
										office_address1='$office_address1',
										office_address2='$office_address2',
										office_address3='$office_address3',
										office_teephone_no='$office_teephone_no',
										insured_age_proof='$insured_age_proof',
										identity_proof='$identity_proof',
										address_proof='$address_proof',
										is_edited = '1'
										
										WHERE 
				
										id='$invoice_id'";
				
				/*
				echo '<br />'.$update_installment;
				exit;
				*/
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
	$selTransaction = mysql_query("SELECT * FROM installment_master_branch WHERE id='".$invoice_id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
			$getTransaction = mysql_fetch_assoc($selTransaction);
			
			
			
			$business_date = $getTransaction['business_date'];
			$type_of_business = $getTransaction['type_of_business'];
			
			$phase_id = $getTransaction['phase_id'];
			if(!empty($phase_id)){
				$phase_name=find_phase_name($phase_id);
			}else{
				$phase_name='';
			}
			$hub_id = $getTransaction['hub_id'];
			
			$branch_id = $getTransaction['branch_id'];
			$branch_name = find_branch_name($branch_id);
			
			$application_no = $getTransaction['application_no'];
			$agent_code = $getTransaction['agent_code'];
			$pre_printed_receipt_no = $getTransaction['pre_printed_receipt_no'];
			$cash_money_receipt = $getTransaction['cash_money_receipt'];
			$cheque_money_receipt = $getTransaction['cheque_money_receipt'];
			$draft_money_receipt = $getTransaction['draft_money_receipt'];
			$applicant_name = $getTransaction['applicant_name'];
			$applicant_dob = $getTransaction['applicant_dob'];
			$applicant_age = $getTransaction['applicant_age'];
			$insured_name = $getTransaction['insured_name'];
			$insured_dob = $getTransaction['insured_dob'];
			if($insured_dob=='0000-00-00') { $insured_dob=""; }
			
			$insured_age = $getTransaction['insured_age'];
			$nominee_name = $getTransaction['nominee_name'];
			$nominee_relationship = $getTransaction['nominee_relationship'];
			$nominee_dob = $getTransaction['nominee_dob'];
			if($nominee_dob=='0000-00-00') { $nominee_dob=""; }
			$nominee_age = $getTransaction['nominee_age'];
			$appointee_name = $getTransaction['appointee_name'];
			$appointee_relationship = $getTransaction['appointee_relationship'];
			$appointee_dob = $getTransaction['appointee_dob'];
			if($appointee_dob=='0000-00-00') { $appointee_dob=""; }
			
			$appointee_age = $getTransaction['appointee_age'];
			$insured_age_proof = $getTransaction['insured_age_proof'];
			$plan_name = $getTransaction['plan_name'];
			$sum_asured = $getTransaction['sum_asured'];
			$pay_mode = $getTransaction['pay_mode'];
			$receive_cash = $getTransaction['receive_cash'];
			$receive_cheque = $getTransaction['receive_cheque'];
			$receive_draft = $getTransaction['receive_draft'];
			$premium = $getTransaction['premium'];
			$cheque_no = $getTransaction['cheque_no'];
			$cheque_date = $getTransaction['cheque_date'];
			$cheque_bank_name = $getTransaction['cheque_bank_name'];
			$cheque_branch_name = $getTransaction['cheque_branch_name'];
			$dd_no = $getTransaction['dd_no'];
			$dd_date = $getTransaction['dd_date'];
			$dd_bank_name = $getTransaction['dd_bank_name'];
			$dd_branch_name = $getTransaction['dd_branch_name'];
			$identity_proof = $getTransaction['identity_proof'];
			$address_proof = $getTransaction['address_proof'];
			$insured_address1 = $getTransaction['insured_address1'];
			$insured_address2 = $getTransaction['insured_address2'];
			$insured_address3 = $getTransaction['insured_address3'];
			$state_id = $getTransaction['state_id'];
			$pin = $getTransaction['pin'];
			$telephone_no = $getTransaction['telephone_no'];
			$term = $getTransaction['term'];
			$office_name = $getTransaction['office_name'];
			$office_address1 = $getTransaction['office_address1'];
			$office_address2 = $getTransaction['office_address2'];
			$office_address3 = $getTransaction['office_address3'];
			$gender = $getTransaction['gender'];
			$education_qualification = $getTransaction['education_qualification'];
			$occupation = $getTransaction['occupation'];
			$office_teephone_no = $getTransaction['office_teephone_no'];
			$nature_of_duty = $getTransaction['nature_of_duty'];
			$anual_income = $getTransaction['anual_income'];
			$insured_height = $getTransaction['insured_height'];
			$insured_weight = $getTransaction['insured_weight'];
			
	
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
	
	<script type="text/javascript">
    function ageCount(field_type) {
	var input;
	var output;
	switch(field_type)
	{
		case "insured_dob":
		input="insured_dob";
		output="insured_age";
		break;
		
		case "nominee_dob":
		input="nominee_dob";
		output="nominee_age";
		break;
		
		case "appointee_dob":
		input="appointee_dob";
		output="appointee_age";
		break;
	
	}
        var date1 = new Date();
        var  dob= document.getElementById(input).value;
        var pattern = /^\d{1,2}\/\d{1,2}\/\d{4}$/; //Regex to validate date format (dd/mm/yyyy)
        if (pattern.test(dob)) {
            var year=Number(dob.substr(6,4));
			var month=Number(dob.substr(0,2))-1;
			var day=Number(dob.substr(3,2));
			var today=new Date();
			var age=today.getFullYear()-year;
			if(today.getMonth()<month || (today.getMonth()==month && today.getDate()<day)){
				age--;
			}
			document.getElementById(output).value=age;
        } else {
            alert("Invalid date format. Please Input in (dd/mm/yyyy) format!");
            return false;
        }

    }
	function age_Count1(){
		var year=Number(dob.substr(6,4));
		alert(year);
		var month=Number(dob.substr(0,2))-1;
		alert(month);
		var day=Number(dob.substr(3,2));
		alert(day);
		var today=new Date();
		var age=today.getFullYear()-year;
		if(today.getMonth()<month || (today.getMonth()==month && today.getDate()<day)){
			age--;
		}
		alert(age);
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
		if(document.addForm.phase.value.search(/\S/) == -1)
		{
			alert("Please Enter Phase.");
			document.addForm.phase.focus();
			return false;
		}
		else if(document.addForm.agent_code.value.search(/\S/) == -1)
		{
			alert("Please Enter Agent Code.");
			document.addForm.agent_code.focus();
			return false;
		}
		else if(document.addForm.pre_printed_receipt_no.value.search(/\S/) == -1)
		{
				alert("Please Enter Printed Receipt No");
				document.addForm.pre_printed_receipt_no.focus();
				return false;
		}			
		else if(document.addForm.term.value.search(/\S/) == -1)
		{
			alert("Please Enter Term");
			document.addForm.term.focus();
			return false;
		}	
		
		else if(document.addForm.sum_asured.value.search(/\S/) == -1)
		{
			alert("Please Enter Sum Assured");
			document.addForm.sum_asured.focus();
			return false;
		}

		else if(document.addForm.pay_mode.value.search(/\S/) == -1)
		{
			alert("Please Enter Pay Mode");
			document.addForm.pay_mode.focus();
			return false;
		}
		
		else if(document.addForm.insured_name.value.search(/\S/) == -1)
		{
			alert("Please Enter Insured Name");
			document.addForm.insured_name.focus();
			return false;
		}	
		

		else if(document.addForm.insured_dob.value.search(/\S/) == -1)
		{
			alert("Please Insured DOB");
			document.addForm.insured_dob.focus();
			return false;
		}	

		else if(document.addForm.insured_age.value.search(/\S/) == -1)
		{
			alert("Please Enter Insured Age");
			document.addForm.insured_age.focus();
			return false;
		}	
		
		
		else if(document.addForm.insured_address1.value.search(/\S/) == -1)
		{
			alert("Please Enter Insured Address");
			document.addForm.insured_address1.focus();
			return false;
		}	

		else if(document.addForm.state_id.value.search(/\S/) == -1)
		{
			alert("Please enter State");
			document.addForm.state_id.focus();
			return false;
		}
		
		else if(document.addForm.pin.value.search(/\S/) == -1)
		{
			alert("Please enter Pin");
			document.addForm.pin.focus();
			return false;
		}			
		
		else if(document.addForm.telephone_no.value.search(/\S/) == -1)
		{
			alert("Please enter Phone");
			document.addForm.telephone_no.focus();
			return false;
		}
		else if(document.addForm.occupation.value.search(/\S/) == -1 && document.addForm.occupation_display.value.search(/\S/) == -1)
		{
			alert("Please enter Occupation");
			document.addForm.occupation.focus();
			return false;
		}	
		else if(document.addForm.anual_income.value.search(/\S/) == -1)
		{
			alert("Please enter Annual Income");
			document.addForm.anual_income.focus();
			return false;
		}	
		else if(document.addForm.nominee_name.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee Name");
			document.addForm.nominee_name.focus();
			return false;
		}
		else if(document.addForm.nominee_relationship.value.search(/\S/) == -1 && document.addForm.nominee_relationship_display.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee Relationship");
			document.addForm.nominee_relationship.focus();
			return false;
		}
		else if(document.addForm.nominee_dob.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee DOB");
			document.addForm.nominee_dob.focus();
			return false;
		}
		else if(document.addForm.nominee_age.value.search(/\S/) == -1)
		{
			alert("Please enter Nominee Age");
			document.addForm.nominee_age.focus();
			return false;
		}
		else if(document.addForm.appointee_name.value.search(/\S/) == -1)
		{
			alert("Please enter Appointee Name");
			document.addForm.appointee_name.focus();
			return false;
		}
		else if(document.addForm.appointee_relationship.value.search(/\S/) == -1 && document.addForm.appointee_relationship_display.value.search(/\S/) == -1)
		{
			alert("Please enter Appointee Relationship");
			document.addForm.appointee_relationship.focus();
			return false;
		}
		else if(document.addForm.appointee_dob.value.search(/\S/) == -1)
		{
			alert("Please enter Appointee DOB");
			document.addForm.appointee_dob.focus();
			return false;
		}
		else if(document.addForm.appointee_age.value.search(/\S/) == -1)
		{
			alert("Please enter Appointee Age");
			document.addForm.appointee_age.focus();
			return false;
		}
		else if(document.addForm.insured_height.value.search(/\S/) == -1)
		{
			alert("Please enter Insured Height");
			document.addForm.insured_height.focus();
			return false;
		}
		else if(document.addForm.insured_weight.value.search(/\S/) == -1)
		{
			alert("Please enter Insured Weight");
			document.addForm.insured_weight.focus();
			return false;
		}
		else if(document.addForm.gender.value.search(/\S/) == -1)
		{
			alert("Please enter Gender");
			document.addForm.gender.focus();
			return false;
		}
		else if(document.addForm.education_qualification.value.search(/\S/) == -1)
		{
			alert("Please enter Educational Qualification");
			document.addForm.education_qualification.focus();
			return false;
		}
		else if(document.addForm.insured_age_proof.value.search(/\S/) == -1)
		{
			alert("Please enter Insured Age Proof");
			document.addForm.insured_age_proof.focus();
			return false;
		}
		else if(document.addForm.identity_proof.value.search(/\S/) == -1)
		{
			alert("Please enter ID Proof");
			document.addForm.identity_proof.focus();
			return false;
		}
		else if(document.addForm.address_proof.value.search(/\S/) == -1)
		{
			alert("Please enter Address Proof");
			document.addForm.address_proof.focus();
			return false;
		}
		
		else
		{
			return true;
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
	
	<script language="JavaScript" type="text/JavaScript">
function isNumber(field) {
        var re = /^[0-9-'.'-',']*$/;
        if (!re.test(field.value)) {
            alert('Agent Code should be Numeric');
            field.value = field.value.replace(/[^0-9-'.'-',']/g,"");
        }
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
        <? showMessage(); ?>      </td>
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
      <td width="28%" align="right" valign="top" class="tbllogin">Application No </td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $application_no; ?></td>
    </tr>
	<tr> 
      <td width="28%" align="right" valign="top" class="tbllogin">Business Date  </td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo date("d/m/Y",strtotime($business_date)); ?></td>
    </tr>
	<tr> 
      <td width="28%" align="right" valign="top" class="tbllogin">Type of Business </td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $type_of_business; ?></td>
    </tr>
	<!--<tr> 
      <td width="28%" align="right" valign="top" class="tbllogin">Phase</td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $phase_name; ?></td>
    </tr>-->
	<tr> 
      <td width="28%" align="right" valign="top" class="tbllogin">Branch Name </td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $branch_name; ?></td>
    </tr>
	<!--<tr> 
      <td width="28%" align="right" valign="top" class="tbllogin">Agent Code </td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $agent_code; ?></td>
    </tr>-->
	

	<tr> 
      <td width="28%" align="right" valign="top" class="tbllogin">Cash Money Receipt</td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $cash_money_receipt; ?></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque Money Receipt</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $cheque_money_receipt; ?></td>
    </tr>
    
    <tr> 
      <td class="tbllogin" valign="top" align="right">Draft Money Receipt</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left" id="deposit_date"><?php echo $draft_money_receipt; ?></td>
    </tr>

		

		<tr> 
      <td class="tbllogin" valign="top" align="right">Applicant Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
	  <td valign="top" align="left" id="deposit_date"><?php echo $applicant_name; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Applicant DOB</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      
	  <td valign="top" align="left" id="deposit_date"><?php echo date("d/m/Y",strtotime($applicant_dob)); ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Applicant Age</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><div id="app_name"><?php echo $applicant_age; ?></div></td>
    </tr>	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Plan Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $plan_name; ?></td>
    </tr>


	<tr> 
      <td class="tbllogin" valign="top" align="right">Receive Cash </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $receive_cash; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Receive Cheque </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $receive_cheque; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Reveive Draft </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $receive_draft; ?></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Premium</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $premium; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $cheque_no; ?></td>

    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
   	  
	  
	  <td valign="top" align="left"><?php if($cheque_date!='0000-00-00'){echo date("d/m/Y",strtotime($cheque_date)); } ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
     
	  <td valign="top" align="left"><?php echo $cheque_bank_name; ?></td>
	  
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque Branch Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
     
	  <td valign="top" align="left"><?php echo $cheque_branch_name; ?></td>
	  
    </tr>


	<tr> 
      <td class="tbllogin" valign="top" align="right">DD Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $dd_no; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">DD Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php if($dd_date!='0000-00-00'){echo date("d/m/Y",strtotime($dd_date)); } ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">DD Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $dd_bank_name; ?></td>
    </tr>

	
	<tr> 
      <td class="tbllogin" valign="top" align="right">DD Branch Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $dd_branch_name; ?></td>
    </tr>	


	
		
		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Secondary Entry</font></b></td>
    </tr>	
	<input type="hidden" name="app_dob" id="app_dob" value="<?php echo date('d-m-Y',strtotime($getTransaction['applicant_dob']));?>">
	
	<tr>
	<td align="center"><!--<input name="copy_data" id="copy_data" type="button" onClick="copy_datas();" value="Copy"  >--></td></tr>
	<tr> 
      <td width="tbllogin" align="right" valign="top" class="tbllogin">Phase<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td align="left" valign="top">
		<!--<input name="phase_name" id="phase_name" type="text" class="inplogin"  value="<?php //echo $phase_name; ?>" maxlength="255" >-->
		<select name="phase" id="phase" class="inplogin">
			<option value="">Select</option>
			<?php 
				$selPhase = mysql_query("select id, phase from phase_master order by phase");   //for Plan dropdown
				$numPhase = mysql_num_rows($selPhase);
				if($numPhase > 0)
				{
					while($getPhase = mysql_fetch_array($selPhase))
					{	
			?>
				<option value="<?php echo $getPhase['id']; ?>" <?php echo ($getPhase['id'] == $phase_id ? 'selected' : ''); ?>><?php echo $getPhase['phase']; ?></option>
			<?php
					}
				}
			?>
		</select>
	  </td>
    </tr>
	<tr> 
      <td width="tbllogin" align="right" valign="top" class="tbllogin">Agent Code<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td align="left" valign="top"><input name="agent_code" id="agent_code" type="text" class="inplogin"  value="<?php echo $agent_code; ?>" maxlength="255" ></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Printed Receipt<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="pre_printed_receipt_no" id="pre_printed_receipt_no" type="text" class="inplogin"  value="<?php echo $pre_printed_receipt_no; ?>" maxlength="255" ></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Term<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="term" id="term" type="text" class="inplogin"  value="<?php echo $term; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Sum Assured <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="sum_asured" id="sum_asured" type="text" class="inplogin"  value="<?php echo stripslashes($sum_asured); ?>" maxlength="255" onKeyPress="return keyRestrict(event, '0123456789.')"></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Pay Mode <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="pay_mode" id="pay_mode" type="text" class="inplogin"  value="<?php echo stripslashes($pay_mode); ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Name<font color="#ff0000">*</font><br /><font style="font-size:10px;"></font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td align="left"><input name="insured_name" id="insured_name" type="text" class="inplogin"  value="<?php echo stripslashes($insured_name); ?>" onKeyUp="this.value = this.value.toUpperCase();" style="width:240px;">
	</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured DOB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="insured_dob" id="insured_dob" type="text" class="inplogin"  value="<?php if($insured_dob!="") { echo date("d/m/Y",strtotime($insured_dob)); } ?>" maxlength="20" readonly onChange="ageCount('insured_dob')" /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.insured_dob,'dd/mm/yyyy',this)" width="20" height="18"></td>
    </tr>


	<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Insured Age</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"></td>
    </tr>
 -->
	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Age<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="insured_age" id="insured_age" type="text" class="inplogin"  value="<?php echo stripslashes($insured_age); ?>" maxlength="255" onKeyPress="return keyRestrict(event, '0123456789')"></td>
    </tr>

	<tr> 
      <td align="right" valign="top" class="tbllogin">Insured Address1 <strong><font color="#ff0000">*</font></strong></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="insured_address1" id="insured_address1" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});"  style="width:200px;"><?php echo $insured_address1; ?></textarea></td>
    </tr>
	<tr> 
      <td align="right" valign="top" class="tbllogin">Insured Address2</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="insured_address2" id="insured_address2" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});"  style="width:200px;"><?php echo $insured_address2; ?></textarea></td>
    </tr>
	<tr> 
      <td align="right" valign="top" class="tbllogin">Insured Address3</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="insured_address3" id="insured_address3" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});"  style="width:200px;"><?php echo $insured_address3; ?></textarea></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">State<strong> <font color="#ff0000">*</font></strong></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">	

	  <select name="state_id" id="state_id" class="inplogin" >
				<option value="">Select</option>
				<?php 
					if(count($rsplace) > 0)
					{
						for($i=0; $i < count($rsplace); $i++)
						{
				?>
				<option value="<?php echo $rsplace[$i]['id']?>" <?php echo ($state_id == $rsplace[$i]['id'] ? 'selected' : '') ?>><?php echo $rsplace[$i]['place']?></option>
				<?php
						}
					}
				?>
			</select>		</td>
    </tr>

	<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">City</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="city" id="city" type="text" class="inplogin"  value="<?php echo $city; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr> -->

		<tr> 
      <td class="tbllogin" valign="top" align="right">PIN<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="pin" id="pin" type="text" class="inplogin"  value="<?php echo $pin; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Phone<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="telephone_no" id="telephone_no" type="text" class="inplogin"  value="<?php echo $telephone_no; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
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
			&nbsp;<br><strong>Others</strong>&nbsp;<input name="occupation_name" id="occupation_name" type="hidden" class="inplogin"  value="<?php echo $occupation; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"> <input name="occupation_display" id="occupation_display" type="text" class="inplogin"  value="<?php echo (intval($occupation_id) != 0 ? '' : $occupation); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.occupation_name.value = this.value; document.addForm.occupation.value = '';"></td>
    </tr>

	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Annual Income (INR)<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="anual_income" id="anual_income" type="text" class="inplogin"  value="<?php echo $anual_income; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')"></td>
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
			<select name="nominee_relationship" id="nominee_relationship" class="inplogin" onchange="document.addForm.nominee_relationship_name.value = this.value; document.addForm.nominee_relationship_display.value = '';">
				<option value="">Select</option>
				<?php 
					if($numRelationship > 0)
					{
						while($getRelationship = mysql_fetch_array($selRelationship))
						{							
				?>
				<option value="<?php echo $getRelationship['relationship']?>" <?php echo ($nominee_relationship == $getRelationship['relationship'] ? 'selected' : '') ?>><?php echo $getRelationship['relationship']?></option>
				<?php
						}
					}
				?>
			</select>
			
			<?php $relationship_id = find_relationship_id($nominee_relationship); //echo $relationship_id; ?>
			&nbsp;<strong>Others</strong>&nbsp;
			
			<input name="nominee_relationship_name" id="nominee_relationship_name" type="hidden" class="inplogin"  value="<?php echo $nominee_relationship; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"> 
			
			
			<input name="nominee_relationship_display" id="nominee_relationship_display" type="text" class="inplogin"  value="<?php echo (intval($relationship_id) != 0 ? '' : $nominee_relationship); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.nominee_relationship_name.value = this.value; document.addForm.nominee_relationship.value = '';">			</td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee DOB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="nominee_dob" id="nominee_dob" type="text" class="inplogin"  value="<?php if($nominee_dob!="") { echo date("d/m/Y",strtotime($nominee_dob)); } ?>" maxlength="20" readonly onChange="ageCount('nominee_dob')"/> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.nominee_dob,'dd/mm/yyyy',this)" width="20" height="18"></td>
    </tr>
	<tr>
	  
	  <td class="tbllogin" valign="top" align="right">Nominee Age<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
	  <td valign="top" align="left"><input name="nominee_age" id="nominee_age" type="text" class="inplogin"  value="<?php echo $nominee_age; ?>" maxlength="2" onKeyPress="return keyRestrict(event, '0123456789')"/></td>
    </tr>
	

    <tr> 
      <td class="tbllogin" valign="top" align="right">Appointee Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="appointee_name" id="appointee_name" type="text" class="inplogin"  value="<?php echo $appointee_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" style="width:220px;"></td>
    </tr>

	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Appointee Relationship<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	 <?php $selRelationship = mysql_query("SELECT id, relationship FROM relationship_master ORDER BY relationship ASC ");
$numRelationship = mysql_num_rows($selRelationship);
?>  
			<select name="appointee_relationship" id="appointee_relationship" class="inplogin" onchange="document.addForm.appointee_relationship_name.value = this.value; document.addForm.appointee_relationship_display.value = '';">
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

			<input name="appointee_relationship_name" id="appointee_relationship_name" type="hidden" class="inplogin"  value="<?php echo $appointee_relationship; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.appointee_relationship_display.value = '';">		  </td>

			<td width="19%"></td>
    </tr>
<tr> 
      <td class="tbllogin" valign="top" align="right">Appointee DOB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="appointee_dob" id="appointee_dob" type="text" class="inplogin"  value="<?php if($appointee_dob!="") { echo date("d/m/Y",strtotime($appointee_dob)); } ?>" maxlength="20" readonly onChange="ageCount('appointee_dob')"/> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.appointee_dob,'dd/mm/yyyy',this)" width="20" height="18"></td>
    </tr>
	<tr>
	  
	  <td class="tbllogin" valign="top" align="right">Appointee Age<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
	  <td valign="top" align="left"><input name="appointee_age" id="appointee_age" type="text" class="inplogin"  value="<?php echo $appointee_age; ?>" maxlength="2" onKeyPress="return keyRestrict(event, '0123456789')"/></td>
    </tr>


		<!-- <tr> 
      <td align="right" valign="top" class="tbllogin"><strong>Nominee Address<font color="#ff0000">*</font></strong></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="nominee_address" id="nominee_address" class="inplogin" onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});" ><?php //echo $nominee_address; ?></textarea></td>
	  <td align="left"><input name="copy_addrs" id="copy_addrs" type="button" onClick="copy_adds();" value="Copy Insured Address"  ></td>
    </tr> -->

	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Height<font color="#ff0000">*</font> </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="insured_height" id="insured_height" type="text" class="inplogin"  value="<?php echo $insured_height; ?>" maxlength="255" onKeyPress="return keyRestrict(event, '0123456789.')"> CM</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Weight<font color="#ff0000">*</font> </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="insured_weight" id="insured_weight" type="text" class="inplogin"  value="<?php echo $insured_weight; ?>" maxlength="255" onKeyPress="return keyRestrict(event, '0123456789.')"> KG</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Gender<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="gender" id="gender" class="inplogin_select">
					<option value="">Select</option>
					<option value="M" <?php echo ($gender == 'M' ? 'selected' : ''); ?>>Male</option>
					<option value="F" <?php echo ($gender == 'F' ? 'selected' : ''); ?>>Female</option>
				</select>			</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Educational Qualification<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="education_qualification" id="education_qualification" class="inplogin_select" style="width:200px;">
					<option value="">Select</option>
					<option value="ILLITERATE" <?php echo ($education_qualification == 'ILLITERATE' ? 'selected' : ''); ?>>ILLITERATE</option>

					<option value="UNDER 10TH STANDARD" <?php echo ($education_qualification == 'UNDER 10TH STANDARD' ? 'selected' : ''); ?>>UNDER 10TH STANDARD</option>
					<option value="SECONDARY" <?php echo ($education_qualification == 'SECONDARY' ? 'selected' : ''); ?>>SECONDARY</option>
					<option value="HIGHER SECONDARY" <?php echo ($education_qualification == 'HIGHER SECONDARY' ? 'selected' : ''); ?>>HIGHER SECONDARY</option>
					<option value="GRADUATE" <?php echo ($education_qualification == 'GRADUATE' ? 'selected' : ''); ?>>GRADUATE</option>
					<option value="MASTERS" <?php echo ($education_qualification == 'MASTERS' ? 'selected' : ''); ?>>MASTERS</option>
					<option value="RESEARCH SCHOLAR" <?php echo ($education_qualification == 'RESEARCH SCHOLAR' ? 'selected' : ''); ?>>RESEARCH SCHOLAR</option>
				</select>			</td>
    </tr>

	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Office Name </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="office_name" id="office_name" type="text" class="inplogin"  value="<?php echo $office_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" style="width:250px;"></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Nature of Duty </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="nature_of_duty" id="nature_of_duty" type="text" class="inplogin"  value="<?php echo $nature_of_duty; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" style="width:250px;"></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Office Address1 </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="office_address1" id="office_address1" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});"><?php echo $office_address1; ?></textarea></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Office Address2 </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="office_address2" id="office_address2" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});"><?php echo $office_address2; ?></textarea></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Office Address3</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><textarea name="office_address3" id="office_address3" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});"><?php echo $office_address3; ?></textarea></td>
    </tr>

	<!--  -->

	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Office Phone</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="office_teephone_no" id="office_teephone_no" type="text" class="inplogin"  value="<?php echo $office_teephone_no; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
    </tr>

	<!--  -->

	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Insured Age Proof
	  <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<div style="overflow:auto;width:250px;" align="left">
				<select name="insured_age_proof" id="insured_age_proof" align="left">
					<option value="" >Select Document</option>
					<?php
						if($numAgeProof > 0)
						{
							while($getAgeProof = mysql_fetch_assoc($selAgeProof))
							{
					?>
						<option value="<?php echo $getAgeProof['id']; ?>" <?php echo ($getAgeProof['id'] == $insured_age_proof ? 'selected' : ''); ?> ><?php echo $getAgeProof['document_name']; ?></option>
					<?php		
							}						
						}
					?>
				</select>
				</div>			</td>
    </tr>




	
	<tr> 
      <td class="tbllogin" valign="top" align="right">ID Proof
	  <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<div style="overflow:auto;width:250px;" align="left">
				<select name="identity_proof" id="identity_proof" align="left">
					<option value="" >Select Document</option>
					<?php
						if($numIDProof > 0)
						{
							while($getIDProof = mysql_fetch_assoc($selIDProof))
							{
					?>
						<option value="<?php echo $getIDProof['id']; ?>" <?php echo ($getIDProof['id'] == $identity_proof ? 'selected' : ''); ?> ><?php echo $getIDProof['document_name']; ?></option>
					<?php		
							}						
						}
					?>
				</select>
				</div>			</td>
    </tr>

		

		

		<tr> 
      <td class="tbllogin" valign="top" align="right">Address Proof<font color="#ff0000">*</font></td>
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
				</div>			</td>
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