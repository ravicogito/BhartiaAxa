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
		// Preliminary Edit variables
		if(!isset($hub_name_id)) { $hub_name_id = ''; }
		if(!isset($campaign_id)) { $campaign_id = ''; }
		
		if(!isset($branch_name)) { $branch_name = ''; }
		if($business_date!='') {  $business_date=date('Y-m-d',strtotime(str_replace('/','-',$business_date))); }
		if(!isset($applicant_name)) { $applicant_name = ''; }  else  { $applicant_name = addslashes($applicant_name); }
		if(!isset($plan_name)) { $plan_name = ''; }  else  { $plan_name = addslashes($plan_name); }
		if(!isset($receive_cash)) { $receive_cash = ''; }  else  { $receive_cash = addslashes($receive_cash); }
		if(!isset($receive_cheque)) { $receive_cheque = ''; }  else  { $receive_cheque = addslashes($receive_cheque); }
		if(!isset($receive_draft)) { $receive_draft = ''; }  else  { $receive_draft = addslashes($receive_draft); }
		if(!isset($premium)) { $premium = ''; }  else  { $premium = addslashes($premium); }
		// Preliminary Edit variables end
		
		// Secondary Entry variables
                                if(!isset($business_type)) { $business_type = ''; }   else  { $business_type = addslashes($business_type); }
				if(!isset($agent_code)) { $agent_code = ''; } else  { $agent_code = addslashes($agent_code); }
				if(!isset($pre_printed_receipt_no)) { $pre_printed_receipt_no = ''; } else  { $pre_printed_receipt_no = addslashes($pre_printed_receipt_no); }
				if($due_date!='') {  $due_date=date('Y-m-d',strtotime(str_replace('/','-',$due_date))); }
				if(!isset($pay_mode)) { $pay_mode = ''; } else  { $pay_mode = addslashes($pay_mode); }
				if(!isset($health)) { $health = ''; } 
				
		if($due_date!="")
		{
			$due_date=date('Y-m-d',strtotime(str_replace('/','-',$due_date)));
		}

		$update_installment = "UPDATE installment_master_ge_short_premium_renewal SET ";
					if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '1')) // Superadmin
		{
			$update_installment.= "hub_id='$hub_name_id',";
			$update_installment.= "branch_id='$branch_name',";
			$update_installment.= "business_date='$business_date',";
			$update_installment.= "applicant_name='$applicant_name',";
			$update_installment.= "plan_name='$plan_name',";
			$update_installment.= "receive_cash='$receive_cash',";
			$update_installment.= "receive_cheque='$receive_cheque',";
			$update_installment.= "receive_draft='$receive_draft',";
			$update_installment.= "premium='$premium',";
		}
								$update_installment.="agent_code = '$agent_code',
                                                                                business_type = '".realTrim($business_type)."',
										pre_printed_receipt_no='$pre_printed_receipt_no',
										due_date = '$due_date',
										reason='$reason',
										is_edited = '1',
										campaign_id='".realTrim($campaign_id)."'
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
	$selTransaction = mysql_query("SELECT * FROM installment_master_ge_short_premium_renewal WHERE id='".$invoice_id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
			$getTransaction = mysql_fetch_assoc($selTransaction);
			
			
			
			$business_date = $getTransaction['business_date'];
			$type_of_business = $getTransaction['type_of_business'];
			
			//$phase_id = $getTransaction['phase_id'];
			//$phase_name=find_phase_name($phase_id);
			
			$hub_id = $getTransaction['hub_id'];
			
			$branch_id = $getTransaction['branch_id'];
			$branch_name = find_branch_name($branch_id);
			$campaign_id = $getTransaction['campaign_id'];
			$policy_no = stripslashes($getTransaction['policy_no']);
			$agent_code = stripslashes($getTransaction['agent_code']);
			$pre_printed_receipt_no = stripslashes($getTransaction['pre_printed_receipt_no']);
			$cash_money_receipt = stripslashes($getTransaction['cash_money_receipt']);
			$cheque_money_receipt = stripslashes($getTransaction['cheque_money_receipt']);
			$draft_money_receipt = stripslashes($getTransaction['draft_money_receipt']);
			$applicant_name = stripslashes($getTransaction['applicant_name']);
			$due_date = $getTransaction['due_date'];
			$plan_name = stripslashes($getTransaction['plan_name']);
			
			//$pay_mode = $getTransaction['pay_mode'];
			$receive_cash = $getTransaction['receive_cash'];
			$receive_cheque = $getTransaction['receive_cheque'];
			$receive_draft = $getTransaction['receive_draft'];
			$premium = $getTransaction['premium'];
			$cheque_no = $getTransaction['cheque_no'];
			$cheque_date = $getTransaction['cheque_date'];
			$cheque_bank_name = stripslashes($getTransaction['cheque_bank_name']);
			$cheque_branch_name = stripslashes($getTransaction['cheque_branch_name']);
			$dd_no = stripslashes($getTransaction['dd_no']);
			$dd_date = $getTransaction['dd_date'];
			$dd_bank_name = stripslashes($getTransaction['dd_bank_name']);
			$dd_branch_name = stripslashes($getTransaction['dd_branch_name']);
			$reason = stripslashes($getTransaction['reason']);
			
	
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
                if(document.getElementById('business_type').value == '' || document.getElementById('business_type').value == 'Select')
		{
			alert("Please Enter Business Type.");
			document.addForm.business_type.focus();
			return false;
		}
		if(document.addForm.agent_code.value.search(/\S/) == -1)
		{
			alert("Please Enter Agent Code.");
			document.addForm.agent_code.focus();
			return false;
		}
		
		<?php if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '1')) // Superadmin

					{
			?>
			
			
			
			else if(document.addForm.hub_name_id.value.search(/\S/) == -1){
				alert("Please Select Hub");
				document.addForm.hub_name_id.focus();
				return false;
			}
			else if(document.addForm.branch_name.value.search(/\S/) == -1){
				alert("Please Select Branch");
				document.addForm.branch_name.focus();
				return false;
			}
			
			
			else if(document.addForm.business_date.value.search(/\S/) == -1){
				alert("Please Enter Business Date");
				document.addForm.business_date.focus();
				return false;
			}
			
			else if(document.addForm.applicant_name.value.search(/\S/) == -1){
				alert("Please Enter Applicant Name");
				document.addForm.applicant_name.focus();
				return false;
			}
			
			
			else if((document.addForm.receive_cash.value > 0) && ((document.addForm.receive_draft.value  > 0) || (document.addForm.receive_cheque.value > 0))){
				alert("Please enter any one Receive Cash or Receive Draft or Receive cheque");
				return false;
			}
			
			else if((document.addForm.receive_draft.value > 0) && ((document.addForm.receive_cash.value > 0) || (document.addForm.receive_cheque.value > 0))){
				alert("Please enter any one Receive Cash or Receive Draft or Receive cheque");
				return false;
			}
			
			else if((document.addForm.receive_cheque.value > 0) && ((document.addForm.receive_cash.value > 0) || (document.addForm.receive_draft.value > 0))){
				alert("Please enter any one Receive Cash or Receive Draft or Receive cheque");
				return false;
			}
			
			else if((document.addForm.receive_cash.value < 1) && (document.addForm.receive_draft.value < 1) && (document.addForm.receive_cheque.value < 1)){
				alert("Please enter any one Receive Cash or Receive Draft or Receive cheque");
				return false;
			}
			
			
			<?php
			}
			?>
			else if(document.addForm.due_date.value.search(/\S/) == -1)
		{
				alert("Please Enter Due Date");
				document.addForm.due_date.focus();
				return false;
		}	
		else if(document.addForm.pre_printed_receipt_no.value.search(/\S/) == -1)
		{
				alert("Please Enter Printed Receipt No");
				document.addForm.pre_printed_receipt_no.focus();
				return false;
		}	
		
				else if(document.addForm.pre_printed_receipt_no.value.length !=7)
		{	
		alert("Please Enter Correct Receipt No.");
		document.addForm.pre_printed_receipt_no.focus();	
		return false;
		}			
		
		
		else
		{
			return true;
		}
		
		
	}
	
		
		// function for getting the braNCH FROM HUB ID	
function getbranch(hub)
{
//alert(hub);
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
	//alert(hub);
  if (xmlhttp.readyState==4)
    {
	 //alert(xmlhttp.responseText);
	document.getElementById("branch1").innerHTML=xmlhttp.responseText;
   }
  }
xmlhttp.open("GET","getbranch.php?hub="+hub,true);
xmlhttp.send();
}

	var alphaNumOnly = /[A-Za-z0-9\.\,]/g;
	var numOnly = /[0-9]/g;

function restrictCharacters(myfield, e, restrictionType) {
	if (!e) var e = window.event
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	var character = String.fromCharCode(code);

	// if they pressed esc... remove focus from field...
	if (code==27) { this.blur(); return false; }
	
	// ignore if they are press other keys
	// strange because code: 39 is the down key AND ' key...
	// and DEL also equals .
	if (!e.ctrlKey && code!=9 && code!=8 && code!=36 && code!=37 && code!=38 && (code!=39 || (code==39 && 

character=="'")) && code!=40) {
		if (character.match(restrictionType)) {
			return true;
		} else {
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

	<?php if($due_date != '0000-00-00') { ?>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Due Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
	  <td valign="top" align="left"><?php echo date('d/m/Y',strtotime($due_date)); ?></td>
    </tr>
	<?php } ?>
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Plan Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $plan_name; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Receive Mode</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	  <?php 
		$mode = '';
		if($receive_cash!=0.00){
			$mode = "CASH";
		}if($receive_cheque!=0.00){
			if(!empty($mode)){
				$mode .= ", CHAQUE";
			}else{
				$mode .= "CHAQUE";
			}
		}if($receive_draft!=0.00){
			if(!empty($mode)){
				$mode .= ", DRAFT";
			}else{
				$mode .= "DRAFT";
			}
		} 
		echo $mode;
	  ?>
	  </td>
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
		<?php if($receive_cheque>0): ?>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque/DD Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $cheque_no; ?></td>

    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque/DD Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
   	  
	  
	  <td valign="top" align="left"><?php if($cheque_date!='0000-00-00'){echo date("d/m/Y",strtotime($cheque_date)); } ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque/DD Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
     
	  <td valign="top" align="left"><?php echo $cheque_bank_name; ?></td>
	  
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque/DD Branch Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
     
	  <td valign="top" align="left"><?php echo $cheque_branch_name; ?></td>
	  
    </tr>
	<?php else: ?>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque/DD Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $dd_no; ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque/DD Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php if($dd_date!='0000-00-00'){echo date("d/m/Y",strtotime($dd_date)); } ?></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque/DD Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $dd_bank_name; ?></td>
    </tr>

	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Cheque/DD Branch Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $dd_branch_name; ?></td>
    </tr>	
	<?php endif; ?>
	<?php if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '1')) // Superadmin Preliminary Edit

		{
	?>
			<tr> 
			  <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Preliminary Edit</font></b></td>
			</tr>
            
             <tr>
              <td class="tbllogin" valign="top" align="right">Campaign </td>
              <td class="tbllogin" valign="top" align="center">:</td>
              <td valign="top" align="left">
              <select name="campaign_id" id="campaign_id" class="inplogin"  style="width:200px;">
            <option value="1">NONE</option>
                
                <?php 
                           $campaign_hub_id=$_SESSION[HUB_ID]; 
									$campaign_branch_id=$_SESSION[ADMIN_SESSION_VAR];
									$query = "select campaign_id, campaign from t_99_campaign WHERE (active_status=1)"; 	
																		
									/*if($campaign_branch_id!=2 && $campaign_hub_id!=2 && $campaign_branch_id!=384)
									{
										$query.=" AND (branch_id='$campaign_branch_id')";
									}
									elseif($campaign_branch_id!=2 && $campaign_branch_id!=384)
									{
										if($campaign_hub_id==2)
										{
											$query.=" AND (hub_id='$campaign_branch_id')";
										}
									}*/
									$query.=" AND (branch_id='$branch_id')";
									$query.=" ORDER BY campaign ASC ";
									
									$selCampaign=mysql_query($query);
                            $numCampaign = mysql_num_rows($selCampaign);
                            
                                while($getCampaign = mysql_fetch_array($selCampaign))
                                {	
                                                        
                        ?>
                <option value="<?php echo $getCampaign['campaign_id']; ?>" <?php echo ($getCampaign['campaign_id']==$campaign_id) ? 'selected' : ''; ?> ><?php echo $getCampaign['campaign']; ?></option>
                <?php
                                }
                            
                        ?>
              </select></td>
            </tr>
			
			<tr>
					<td class="tbllogin" valign="top" align="right"><strong>Hub<font color="#ff0000">*</font></strong></td>

						<td class="tbllogin" valign="top" align="center"><strong>:</strong></td>

						<td valign="top" align="left">

							<select name="hub_name_id" id="hub_name_id" class="inplogin_select" style="width:140px;" onchange="getbranch(this.value);">

								<option value="">--Select--</option>

							<?php 

								$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,5,6) AND role_id = 3 AND branch_user_id=0 ORDER BY branch_name ASC");

								while($getBranch = mysql_fetch_array($selBranch))

								{					

							?>

								<option value="<?php echo $getBranch['id'];?>" <?php echo ($getBranch['id']==$hub_id) ? 'selected' : ''; ?>><?php echo $getBranch['branch_name'];?></option>

							<?php } ?>

							</select>

						</td>
					</tr>
					
					
					<tr>
					<td class="tbllogin" valign="top" align="right"><strong>Branch<font color="#ff0000">*</font></strong></td>

						<td class="tbllogin" valign="top" align="center"><strong>:</strong></td>

						<td valign="top" align="left" id="branch1">

							
						<?php 

								 $branch_where="";

								 

								 if($_SESSION[ROLE_ID]=="3")

								 {

								 	$branch_where=" and hub_id='$hub_id'";

								 }

								$branch_sql = 'select * from admin where  role_id = 4'.$branch_where;

								//echo $branch_sql;

								$branch_query = mysql_query($branch_sql);

								$branch_num_row = mysql_num_rows($branch_query);

								//$brancharr = mysql_fetch_array($branch_query)

								//echo $_SESSION['branch_name'];

								?>

							<select name="branch_name" id="branch_name" class="inplogin_select" style="width:140px;">

								<option value="">--Select--</option>

							<?php  while($brancharr = mysql_fetch_array($branch_query))

							

									{?>

								<option value="<?php echo $brancharr['id']; ?>" <?php echo ($brancharr['id']==$branch_id) ? 'selected' : ''; ?>><?php echo $brancharr['branch_name']; ?></option> <?php }?>

							

							</select>

						</td>
					</tr>
	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Business Date<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="business_date" id="business_date" type="text" class="inplogin"  value="<?php if($business_date!="") { echo date("d/m/Y",strtotime($business_date)); } ?>" maxlength="20" readonly /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.business_date,'dd/mm/yyyy',this)" width="20" height="18"><br><a onClick="document.addForm.business_date.value='';" style="cursor:pointer">Clear</a></td>
    </tr>
	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Applicant Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="applicant_name" id="applicant_name" type="text" class="inplogin"  value="<?php echo $applicant_name; ?>" /></td>
    </tr>
	


	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Plan Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="plan_name" id="plan_name" type="text" class="inplogin"  value="<?php echo $plan_name; ?>" /></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Receive Cash</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="receive_cash" id="receive_cash" type="text" class="inplogin"  value="<?php echo $receive_cash; ?>" /></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Receive Cheque</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="receive_cheque" id="receive_cheque" type="text" class="inplogin"  value="<?php echo $receive_cheque; ?>" /></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Reveive DD</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="receive_draft" id="receive_draft" type="text" class="inplogin"  value="<?php echo $receive_draft; ?>" /></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Premium<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="premium" id="premium" type="text" class="inplogin"  value="<?php echo $premium; ?>" /></td>
    </tr>
	
	<?php
	}
	
	// Superadmin Preliminary Edit end
	?>
	
		
		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Secondary Entry</font></b></td>
    </tr>	
	<input type="hidden" name="app_dob" id="app_dob" value="<?php echo date('d-m-Y',strtotime($getTransaction['applicant_dob']));?>">
	
	<tr>
	<td align="center"><!--<input name="copy_data" id="copy_data" type="button" onClick="copy_datas();" value="Copy"  >--></td></tr>
	
        <tr> 
      <td class="tbllogin" valign="top" align="right">Business Type<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
<!--	  <input type="hidden" name="business_type" id="business_type" value="2" >-->
	  
	  <select name="business_type" id="business_type" class="inplogin">
				<option value="">Select</option>
				<?php 
				
					$selPlan = mysql_query("select * from business_type WHERE status='1' AND is_new='1' ORDER BY business_type ASC ");   //for Plan dropdown
					$numPlan = mysql_num_rows($selPlan);
					if($numPlan > 0)
					{
						while($getPlan = mysql_fetch_array($selPlan))
						{	
												
				?>
					<option value="<?php echo $getPlan['id']; ?>" <?php echo ($getPlan['id'] == $getTransaction['business_type'] ? 'selected' : ''); ?>><?php echo $getPlan['business_type']; ?></option>
				<?php
						}
					}
				?>
			</select>
			</td>
    </tr>
        
        <tr> 
      <td class="tbllogin" valign="top" align="right">Agent Code <font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="agent_code" id="agent_code" type="text" class="inplogin"  value="<?php echo $agent_code; ?>" /></td>
	</tr>
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Due Date<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="due_date" id="due_date" type="text" class="inplogin"  value="<?php if($due_date!="") { echo date("d/m/Y",strtotime($due_date)); } ?>" maxlength="20" readonly /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.due_date,'dd/mm/yyyy',this)" width="20" height="18"><br><a onClick="document.addForm.due_date.value='';" style="cursor:pointer">Clear</a></td>
    </tr>
	
	<tr> 
	<tr> 
      <td class="tbllogin" valign="top" align="right">Printed Receipt<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="pre_printed_receipt_no" id="pre_printed_receipt_no" type="text" class="inplogin"  value="<?php echo $pre_printed_receipt_no; ?>" maxlength="7" onKeyPress="return restrictCharacters(this,event,numOnly);" ></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Reason</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
        <textarea name="reason" id="reason"><?php echo $reason; ?></textarea>
      </td>
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