<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
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

$selTenure = mysql_query("SELECT id, tenure FROM tenure_master WHERE status=1 ORDER BY tenure ASC "); //for Term dropdown
$numTenure = mysql_num_rows($selTenure);

//print_r($_POST);

//if(isset($_GET['id']) && !empty($_GET['id']))
//{
	

//	$invoice_id = base64_decode($_GET['id']);
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
		
		$insert_short_installment = "INSERT INTO insurance_short_premium SET
										application_no = '".realTrim($_POST['application_no'])."',
										applicant_name = '".realTrim($_POST['application_name'])."',
										agent_name = '".realTrim($_POST['agent_name'])."',
										agent_code = '".realTrim($_POST['agent_code'])."',
										receipt_number = '".realTrim($_POST['receipt_number'])."',
										deposit_date = '".date("Y-m-d")."',
										plan = '".realTrim($_POST['plan'])."',
										term = '".realTrim($_POST['tenure'])."',
										is_duplicate = '".realTrim($_POST['is_duplicate'])."',
										branch_id = '".$_POST['branch_name']."',
										sp_payment_mode = '".realTrim($_POST['short_premium_payment_mode'])."',
										amount = '".realTrim($_POST['short_premium_amount'])."',
										sp_dd_date = '".date('Y-m-d',strtotime($_POST['sp_chk_dd_date']))."',
										sp_dd_bank = '".realTrim($_POST['sp_chk_dd_bankname'])."',
										sp_dd_no = '".realTrim($_POST['sp_chk_dd_no'])."'";
										
										
				//echo '<br />'.$insert_short_installment;
				//exit;
				mysql_query($insert_short_installment);
				$branch_transaction = mysql_insert_id();

				$deposit_date = date("m/Y");
				$transaction_id = $_POST['branch_name']."/".$deposit_date."/".str_pad($branch_transaction,7,'0',STR_PAD_LEFT);
				$updt_transaction_id = "UPDATE insurance_short_premium SET transaction_id = '".$transaction_id."' WHERE id =".$branch_transaction;
				mysql_query($updt_transaction_id);

				header("location: ".URL.'webadmin/index.php?p=transaction_short_list_branch');

				
?>
<script type="text/javascript">
<!--
	window.opener.document.addForm.submit();
	window.close();
//-->
</script>

<?php
	}
	
if(!isset($branch_name)) { $branch_name = ''; } 
if(!isset($application_no)) { $application_no = ''; } 
if(!isset($application_name)) { $application_name = ''; } 
if(!isset($agent_code)) { $agent_code = '';} 
if(!isset($agent_name)) { $agent_name = ''; } 
if(!isset($receipt_number)) { $receipt_number = ''; } 

#if(!isset($serial_no)) { $serial_no = ''; } 
#if(!isset($customer_id)) { $customer_id = ''; } 
#if(!isset($deposit_date)) { $deposit_date = ''; } 
#if(!isset($receipt_date)) { $receipt_date = ''; } 
#if(!isset($agent_code)) { $agent_code = ''; } 
#if(!isset($agent_name)) { $agent_name = ''; } 
#if(!isset($tenure)) { $tenure = ''; } 
#if(!isset($receipt_number)) { $receipt_number = ''; } 
#if(!isset($comitted_amount)) { $comitted_amount = ''; } 


if(!isset($short_premium_amount)) { $short_premium_amount = ''; } 
if(!isset($short_premium_payment_mode)) { $short_premium_payment_mode = ''; } 

if(!isset($sp_chk_dd_no)) { $sp_chk_dd_no = ''; } 
if(!isset($plan)) { $plan = ''; }
if(!isset($tenure)) { $tenure = ''; }
if(!isset($is_duplicate)) { $is_duplicate = ''; }
if(!isset($sp_chk_dd_bankname)) { $sp_chk_dd_bankname = ''; }
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
	<script language="JavaScript" type="text/JavaScript">
function isNumber(field) {
        var re = /^[0-9-'.'-',']*$/;
        if (!re.test(field.value)) {
            alert('Agent Code should be Numeric');
            field.value = field.value.replace(/[^0-9-'.'-',']/g,"");
        }
    }
</script>
	
	<script type="text/javascript">

	
	function dochk()
	{	
			
			
		if(document.addForm.application_no.value.search(/\S/) == -1)
		{
			alert("Please Enter Application Number");
			document.addForm.application_no.focus();
			return false;
		}

		if(document.addForm.application_name.value.search(/\S/) == -1)
		{
			alert("Please Enter Insured Name");
			document.addForm.application_name.focus();
			return false;
		}

		if(document.addForm.agent_code.value.search(/\S/) == -1)
		{
			alert("Please Enter Agent Code");
			document.addForm.agent_code.focus();
			return false;
		}

		if(document.addForm.receipt_number.value.search(/\S/) == -1)
		{
			alert("Please Enter Receipt Serial No.");
			document.addForm.receipt_number.focus();
			return false;
		}


		if(document.addForm.plan.value.search(/\S/) == -1)
		{
			alert("Please Enter Plan");
			document.addForm.plan.focus();
			return false;
		}
		if(document.addForm.tenure.value.search(/\S/) == -1)
		{
			alert("Please Enter Term");
			document.addForm.tenure.focus();
			return false;
		}
		
			
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

		
		paymentMode=document.getElementById("short_premium_amount").value;
		if(document.getElementById("short_premium_amount").value < '1')
			{
			alert("Please Enter Proper Short premiun Amount");
			document.addForm.short_premium_amount.focus();
			return false;
			}

		paymentMode=document.getElementById("short_premium_payment_mode").value;
		date=document.getElementById("sp_chk_dd_date").value;
		//alert (date);
		dd_no=document.getElementById("sp_chk_dd_no").value;
		bank=document.getElementById("sp_chk_dd_bankname").value;
		if((paymentMode=='CHEQUE') || (paymentMode=='DD'))
		{
			if(date=='')
			{
				alert("Please Enter Cheque/DD Date");
				document.addForm.sp_chk_dd_date.focus();
				return false;
			}
		}
		if((paymentMode=='CHEQUE') || (paymentMode=='DD'))
		{
			if(dd_no == '')
			{
				alert("Please Enter Cheque/DD No.");
				document.addForm.sp_chk_dd_no.focus();
				return false;
			}
		}
		if((paymentMode=='CHEQUE') || (paymentMode=='DD'))
		{
			if(bank == '')
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
      <td class="tbllogin" valign="top" align="right">Application No.<font color="#ff0000">*</font></td>
	  
      <td width="5%" valign="top" align="center">:</td>
      <td width="68%" align="left"><input name="application_no" id="application_no" type="text" class="inplogin"  value="<?php echo $application_no; ?>" onKeyUp="this.value = this.value.toUpperCase();" maxlength="100" ></td>
    </tr>
    <tr> 
      
	  <td class="tbllogin" valign="top" align="right">Insured Name<font color="#ff0000">*</font></td>
      <td width="5%" valign="top" align="center">:</td>
      <td width="68%" align="left"><input name="application_name" id="application_name" type="text" class="inplogin"  value="<?php echo $application_name; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
     
	  <td class="tbllogin" valign="top" align="right">Agent Name</td>
      <td width="5%" valign="top" align="center">:</td>
      <td width="68%" align="left"><input name="agent_name" id="agent_name" type="text" class="inplogin"  value="<?php echo $agent_name; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
      
		<td class="tbllogin" valign="top" align="right">Agent Code<font color="#ff0000">*</font></td>
      <td width="5%" valign="top" align="center">:</td>
      <td width="68%" align="left"><input name="agent_code" id="agent_code" type="text" class="inplogin"  value="<?php echo $agent_code; ?>" maxlength="100" onKeyUp="isNumber(this);"></td>
    </tr>


<tr> 
      
		<td class="tbllogin" valign="top" align="right">Receipt Serial No.<font color="#ff0000">*</font></td>
      <td width="5%" valign="top" align="center">:</td>
      <td width="68%" align="left"><input name="receipt_number" id="receipt_number" type="text" class="inplogin"  value="<?php echo $receipt_number; ?>" onKeyUp="this.value = this.value.toUpperCase();" maxlength="7"></td>
    </tr>

   
	
	<tr> 
    
	  <td class="tbllogin" valign="top" align="right">Plan<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><select name="plan" id="plan" class="inplogin">
				
				
				<option value="">Select</option>
				<?php 
				
					$selPlan = mysql_query("select id, plan_name from insurance_plan WHERE status=1 ORDER BY plan_name ASC ");   //for Plan dropdown
					$numPlan = mysql_num_rows($selPlan);
					if($numPlan > 0)
					{
						while($getPlan = mysql_fetch_array($selPlan))
						{	
												
				?>
					<option value="<?php echo $getPlan['id']; ?>" <?php echo ($getPlan['id'] == $tenure ? 'selected' : ''); ?>><?php echo $getPlan['plan_name']; ?></option>
				<?php
						}
					}
				?>
			</select></td>
    </tr>
<input type="hidden" name="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">

	<tr> 
     
	  <td class="tbllogin" valign="top" align="right">Term<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><select name="tenure" id="tenure" class="inplogin">

				<option value="">Select</option>
				<?php 
					if($numTenure > 0)
					{
						while($getTenure = mysql_fetch_array($selTenure))
						{							
				?>
					<option value="<?php echo $getTenure['tenure']; ?>" <?php echo ($getTenure['tenure'] == $tenure ? 'selected' : ''); ?>><?php echo $getTenure['tenure']; ?></option>
				<?php
						}
					}
				?>
			</select> </td>
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
      <td valign="top" align="left"><input name="sp_chk_dd_date" id="sp_chk_dd_date" type="text" class="inplogin"  value="" maxlength="100" readonly /> &nbsp;<img src="images/cal.gif" id="calChq" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.sp_chk_dd_date,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Short premium DD / Cheque Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="sp_chk_dd_no" id="sp_chk_dd_no" type="text" class="inplogin"  value="<?php echo $sp_chk_dd_no; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Short premium Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="sp_chk_dd_bankname" id="sp_chk_dd_bankname" type="text" class="inplogin"  value="<?php echo $sp_chk_dd_bankname; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>
<?php if($short_premium_amount == ""){?>
 <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Add" class="inplogin" type="submit" onclick="return dochk()"> <!-- 
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