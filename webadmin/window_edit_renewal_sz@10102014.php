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
$pageOwner = "'superadmin','admin','branch'";

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
	if(isset($_POST['submit']) && $_POST['submit'] != '')
	{
		
		extract($_POST);
		
				if(!isset($pre_printed_receipt_no)) { $pre_printed_receipt_no = ''; } 
				
				if(!isset($pay_mode)) { $pay_mode = ''; } 
				if(!isset($health)) { $health = ''; } 
				


		$update_installment = "UPDATE installment_master_sz_renewal SET
										pre_printed_receipt_no='$pre_printed_receipt_no', 	
										pay_mode='$pay_mode',
										health='$health',
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
	$selTransaction = mysql_query("SELECT * FROM installment_master_sz_renewal WHERE id='".$invoice_id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
			$getTransaction = mysql_fetch_assoc($selTransaction);
			
			
			
			$business_date = $getTransaction['business_date'];
			$type_of_business = $getTransaction['type_of_business'];
			
			$phase_id = $getTransaction['phase_id'];
			$phase_name=find_phase_name($phase_id);
			
			$hub_id = $getTransaction['hub_id'];
			
			$branch_id = $getTransaction['branch_id'];
			$branch_name = find_branch_name($branch_id);
			
			$policy_no = $getTransaction['policy_no'];
			$agent_code = $getTransaction['agent_code'];
			$pre_printed_receipt_no = $getTransaction['pre_printed_receipt_no'];
			$cash_money_receipt = $getTransaction['cash_money_receipt'];
			$cheque_money_receipt = $getTransaction['cheque_money_receipt'];
			$draft_money_receipt = $getTransaction['draft_money_receipt'];
			$applicant_name = $getTransaction['applicant_name'];
			
			$plan_name = $getTransaction['plan_name'];
			
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
			$health = $getTransaction['health'];
			
	
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
	switch(field_type)
	{
		case "insured_dob":
		var input="insured_dob";
		var output="insured_age";
		break;
		
		case "nominee_dob":
		var input="nominee_dob";
		var output="nominee_age";
		break;
		
		case "appointee_dob":
		var input="appointee_dob";
		var output="appointee_age";
		break;
	
	}
        var date1 = new Date();
        var  dob= document.getElementById(input).value;
        var date2=new Date(dob);
        var pattern = /^\d{1,2}\/\d{1,2}\/\d{4}$/; //Regex to validate date format (dd/mm/yyyy)
        if (pattern.test(dob)) {
            var y1 = date1.getFullYear(); //getting current year
            var y2 = date2.getFullYear(); //getting dob year
            var age = y1 - y2;           //calculating age 
           // document.write("Age : " + age);
           // return true;
		   //alert(age);
		   if(age<=0)
		   {
		   		age='';
		   }
		   document.getElementById(output).value=age;
        } else {
            alert("Invalid date format. Please Input in (dd/mm/yyyy) format!");
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
		if(document.addForm.pre_printed_receipt_no.value.search(/\S/) == -1)
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
      <td width="28%" align="right" valign="top" class="tbllogin">Policy No </td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $policy_no; ?></td>
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
	<tr> 
      <td width="28%" align="right" valign="top" class="tbllogin">Phase</td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $phase_name; ?></td>
    </tr>
	<tr> 
      <td width="28%" align="right" valign="top" class="tbllogin">Branch Name </td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $branch_name; ?></td>
    </tr>
	<tr> 
      <td width="28%" align="right" valign="top" class="tbllogin">Agent Code </td>
      <td width="3%" align="center" valign="top" class="tbllogin">:</td>
      <td width="50%" align="left" valign="top"><?php echo $agent_code; ?></td>
    </tr>
	

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
      <td class="tbllogin" valign="top" align="right">Printed Receipt<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="pre_printed_receipt_no" id="pre_printed_receipt_no" type="text" class="inplogin"  value="<?php echo $pre_printed_receipt_no; ?>" maxlength="255" ></td>
    </tr>
	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Pay Mode <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="pay_mode" id="pay_mode" type="text" class="inplogin"  value="<?php echo stripslashes($pay_mode); ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>
	


	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Health<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="health" id="health" class="inplogin_select">
					<option value="">Select</option>
					<option value="Y" <?php echo ($health == 'Y' ? 'selected' : ''); ?>>Yes</option>
					<option value="N" <?php echo ($health == 'N' ? 'selected' : ''); ?>>No</option>
				</select>			</td>
    </tr>

	
		

    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Update" class="inplogin" type="submit" name="submit" onclick="return dochk()"> <!-- 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"> --></td>
    </tr>
  </tbody>
	 </table>
    </form>
 </div>
 
 </center>
 </body>
</html>