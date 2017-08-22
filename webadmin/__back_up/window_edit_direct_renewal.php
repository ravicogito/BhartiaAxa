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

//$Query = "select * from place_master where 1 ";
//$objDB->setQuery($Query);
//$rsplace = $objDB->select();



$Query = "select * from admin where role_id = '4' ";
$objDB->setQuery($Query);
$rsplace = $objDB->select();

$Query = "select * from insurance_plan where status = '1' ";
$objDB->setQuery($Query);
$rsplan = $objDB->select();


$Query = "select * from tenure_master where status = '1' ";
$objDB->setQuery($Query);
$rsterm = $objDB->select();

$Query = "select * from phase_master ";
$objDB->setQuery($Query);
$rsphase = $objDB->select();

$Query = "select * from installment_no ";
$objDB->setQuery($Query);
$rs_installment_no = $objDB->select();

$branch_readonly = intval($_SESSION[ROLE_ID]) == 4 ? 'readonly' : '';
	

// Write functions here




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
		




		


		$update_installment = "UPDATE direct_renewal_premium SET 	
										branch_id = '".$_POST['branch']."',
										agent_code = '".realTrim($agent_code)."',
										agent_name = '".realTrim($agent_name)."',
										plan = '".realTrim($plan)."',
										term = '".$_POST['term']."',
										phase = '".$_POST['phase']."',
										installment_no = '".$_POST['no_of_installment']."',
										amount = '".realTrim($amount)."'
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
	$selTransaction = mysql_query("SELECT * FROM direct_renewal_premium WHERE id='".$invoice_id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
			$getTransaction = mysql_fetch_assoc($selTransaction);
			
			$branch_name = find_branch_name($getTransaction['branch_id']);
			$application_no = $getTransaction['application_no'];
			$applicant_name = $getTransaction['applicant_name'];
			$deposit_date = date('d-m-Y', strtotime($getTransaction['deposit_date']));
			$agent_code = $getTransaction['agent_code'];
			$agent_name = $getTransaction['agent_name'];
			$tenure = $getTransaction['term'];
			$plan = $getTransaction['plan'];
			$transaction_id = $getTransaction['transaction_id'];
			$amount = $getTransaction['amount'];
			


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
if(!isset($applicant_name)) { $applicant_name = ''; } 
if(!isset($transaction_id)) { $transaction_id = ''; }


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

	
	
	function dochk()
	{	
			
		//alert('Hi');
		//return false;

			
		if(document.addForm.agent_code.value.search(/\S/) == -1)
		{
			alert("Please Enter Agent Code");
			document.addForm.agent_code.focus();
			return false;
		}	

		if(document.addForm.agent_name.value.search(/\S/) == -1)
		{
			alert("Please Enter Agent Name");
			document.addForm.agent_name.focus();
			return false;
		}
		if(document.addForm.plan.value.search(/\S/) == -1)
		{
			alert("Please Enter Plan");
			document.addForm.plan.focus();
			return false;
		}
		if(document.addForm.term.value.search(/\S/) == -1)
		{
			alert("Please Enter Term");
			document.addForm.term.focus();
			return false;
		}
		if(document.addForm.phase.value.search(/\S/) == -1)
		{
			alert("Please Enter Phase");
			document.addForm.phase.focus();
			return false;
		}
		if(document.addForm.no_of_installment.value.search(/\S/) == -1)
		{
			alert("Please Enter No Of Installment");
			document.addForm.no_of_installment.focus();
			return false;
		}
		if(document.addForm.amount.value.search(/\S/) == -1)
		{
			alert("Please Enter Amount");
			document.addForm.amount.focus();
			return false;
		}
		
		
		
	}
	

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
      <td colspan="3">Update Direct Renewal Entry</td>
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
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Direct Renewal Edit</font></b></td>
    </tr>
		
	

	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Branch Name <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">	

	  <select name="branch" id="branch" class="inplogin" >
				<option value="">Select</option>
				<?php 
					if(count($rsplace) > 0)
					{
						for($i=0; $i < count($rsplace); $i++)
						{
				?>
				<option value="<?php echo $rsplace[$i]['id']?>" <?php echo ($getTransaction['branch_id'] == $rsplace[$i]['id'] ? 'selected' : '') ?>><?php echo $rsplace[$i]['branch_name']?></option>
				<?php
						}
					}
				?>
			</select>
	  
		</td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Policy No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo stripslashes($application_no); ?></td>
    </tr>
    <tr> 
      <td class="tbllogin" valign="top" align="right">Insured Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><div id="app_name"><?php echo $applicant_name; ?></div></td>
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

      <td valign="top" align="left"><input type="text" class="inplogin" name="agent_code" id="agent_code" value="<?php echo $agent_code; ?>" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" class="inplogin" name="agent_name" id="agent_name" value="<?php echo $agent_name; ?>" ></td>
    </tr>

	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Plan <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">	

	  <select name="plan" id="plan" class="inplogin" >
				<option value="">Select</option>
				<?php 
					if(count($rsplan) > 0)
					{
						for($i=0; $i < count($rsplan); $i++)
						{
				?>
				<option value="<?php echo $rsplan[$i]['id']?>" <?php echo ($plan == $rsplan[$i]['id'] ? 'selected' : '') ?>><?php echo $rsplan[$i]['plan_name']?></option>
				<?php
						}
					}
				?>
			</select>
	  
		</td>
    </tr>
	
	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Term <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">	

	  <select name="term" id="term" class="inplogin" >
				<option value="">Select</option>
				<?php 
					if(count($rsterm) > 0)
					{
						for($i=0; $i < count($rsterm); $i++)
						{
				?>
				<option value="<?php echo $rsterm[$i]['tenure']?>" <?php echo ($tenure == $rsterm[$i]['tenure'] ? 'selected' : '') ?>><?php echo $rsterm[$i]['tenure']?></option>
				<?php
						}
					}
				?>
			</select>
	  
		</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Phase <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">	

	  <select name="phase" id="phase" class="inplogin" >
				<option value="">Select</option>
				<?php 
					if(count($rsphase) > 0)
					{
						for($i=0; $i < count($rsphase); $i++)
						{
				?>
				<option value="<?php echo $rsphase[$i]['id']?>" <?php echo ($getTransaction['phase'] == $rsphase[$i]['id'] ? 'selected' : '') ?>><?php echo $rsphase[$i]['phase']?></option>
				<?php
						}
					}
				?>
			</select>
	  
		</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">No. of Installment <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">	

	  <select name="no_of_installment" id="no_of_installment" class="inplogin" >
				<option value="">Select</option>
				<?php 
					if(count($rs_installment_no) > 0)
					{
						for($i=0; $i < count($rs_installment_no); $i++)
						{
				?>
				<option value="<?php echo $rs_installment_no[$i]['id']?>" <?php echo ($getTransaction['installment_no'] == $rs_installment_no[$i]['id'] ? 'selected' : '') ?>><?php echo $rs_installment_no[$i]['installment_no']?></option>
				<?php
						}
					}
				?>
			</select>
	  
		</td>
    </tr>
	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Amount</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      
	  <td valign="top" align="left"><input type="text" class="inplogin" name="amount" id="amount" value="<?php echo $amount; ?>" ></td>
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