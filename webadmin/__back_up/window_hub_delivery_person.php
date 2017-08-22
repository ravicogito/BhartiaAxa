<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
$msg = '';
$otherReadonly = 'readonly';
if((intval($_SESSION[ROLE_ID]) == 1) || (intval($_SESSION[ROLE_ID]) == 2)) { $otherReadonly = ''; }

$readonly = ''; 
$objDB = new DB();
$pageOwner = "'superadmin','admin','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

	
// Write functions here

if(isset($_GET['id']) && !empty($_GET['id']))
{
	

	$folio_id = base64_decode($_GET['id']);

	if(isset($_POST['hub_delivery_person_name']) && ($_POST['hub_delivery_person_name'] != '') && isset($_POST['hub_delivery_person_emp_code']) && ($_POST['hub_delivery_person_emp_code'] != ''))
	{
		#echo '<pre>';
		#print_r($_POST);
		#echo '</pre>';
		#exit;
		extract($_POST);
		
				$folio_query = "UPDATE customer_folio_no SET hub_delivery_person_name = '".$hub_delivery_person_name."', hub_delivery_person_emp_code = '".$hub_delivery_person_emp_code."', hub_delivery_date = '".date('Y-m-d')."' WHERE id='".$folio_id."'";
				$update_folio = mysql_query($folio_query);

				$msg = "Record Updated Successfully...";

				#echo $folio_query.'<br />';

				
?>
<script type="text/javascript">
<!--
	//window.opener.document.addForm.submit();
	//window.close();
//-->
</script>

<?php
	}

	$getFolioRecord = findFolioData($folio_id); // THIS ARRAY CONTAIN ALL RECORDS FOR A PARICULAR ID
	$getMasterRecord = findCustomerData($getFolioRecord['customer_id']); // THIS ARRAY CONTAIN ALL RECORDS FOR A PARICULAR ID

	if($getFolioRecord['redemption_date'] != '0000-00-00')
	{
		$readonly = 'readonly';
		$selRedemptioData = mysql_query("SELECT * FROM redemption_master WHERE folio_no_id ='".$folio_id."'");
		if(mysql_num_rows($selRedemptioData) > 0)
		{
			$getRedemptionData = mysql_fetch_array($selRedemptioData);
		}
	}

	$selSumData = mysql_query("SELECT SUM(amount) AS AMOUNT, SUM(transaction_charges) AS TRANSACTION_CHARGES, SUM(gold_gram) AS GOLD_GRAM, SUM(installment) AS INSTALLMENT FROM installment_master WHERE is_deleted=0 AND folio_no_id='".$folio_id."'");

	$getSumData = mysql_fetch_array($selSumData);
	
	
			$product_name = $getFolioRecord['product_name'];
			$commodity_id = $getFolioRecord['commodity_name']; 
			//echo $commodity_id.'kkk';
			$commodity_name = find_commodity_name($commodity_id);
			$application_no = $getFolioRecord['application_no'];
			$folio_no = $getFolioRecord['folio_no'];
			$redemption_reason = $getFolioRecord['redemption_reason'];
			$trusted_id = $getMasterRecord['customer_id'];
			$tenure = $getFolioRecord['tenure'];
			$comitted_amount = $getFolioRecord['committed_amount'];

			$delivery_person_name = $getFolioRecord['delivery_person_name']; 
			$delivery_person_emp_code = $getFolioRecord['delivery_person_emp_code']; 
			$hub_delivery_person_name = $getFolioRecord['hub_delivery_person_name']; 
			$hub_delivery_person_emp_code = $getFolioRecord['hub_delivery_person_emp_code']; 

			$first_name = $getMasterRecord['first_name'];
			$middle_name = $getMasterRecord['middle_name'];
			$last_name = $getMasterRecord['last_name'];
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
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Add/View Delivery Person for Hub/Division </title>
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
		if(document.addForm.delivery_person_name.value.search(/\S/) == -1)
		{
			alert("Please Enter Delivery Person Name");
			document.addForm.delivery_person_name.focus();
			return false;
		}	
		if(document.addForm.delivery_person_emp_code.value.search(/\S/) == -1)
		{
			alert("Please Enter Delivery Person EMP Code");
			document.addForm.delivery_person_emp_code.focus();
			return false;
		}	
		if(document.addForm.hub_delivery_person_name.value.search(/\S/) == -1)
		{
			alert("Please Enter Hub Delivery Person Name");
			document.addForm.hub_delivery_person_name.focus();
			return false;
		}	
		if(document.addForm.hub_delivery_person_emp_code.value.search(/\S/) == -1)
		{
			alert("Please Enter Hub Delivery Person EMP Code");
			document.addForm.hub_delivery_person_emp_code.focus();
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
	 
	 <table width="750" style="border:0px solid red;">
	 
		 <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Add/View Delivery Person for Hub/Division</td>
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
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#009900"><?php echo $msg; ?></font></b></td>
    </tr>
		<?php
			}
		?>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Product Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $product_name; ?></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Commodity Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $commodity_name; ?></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Application No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $application_no; ?></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Folio No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $folio_no; ?></td>
    </tr>
    <tr> 
      <td class="tbllogin" valign="top" align="right">Customer ID</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<?php echo $trusted_id; ?>
			</td>
    </tr>
   
		<tr> 
      <td class="tbllogin" valign="top" align="right">Tenure</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $tenure; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Monthly Commited Amount</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $comitted_amount; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Advanced Amount</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $getSumData['AMOUNT']; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Tax</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $getSumData['TRANSACTION_CHARGES']; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Grams Accumulated</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $getSumData['GOLD_GRAM']; ?></td>
    </tr>

		<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Extra Grams Purchased <br .><font size="" color="" style="font-weight:normal;">(For Proper Denomination)</font> </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<?php 
				$floorVal = floor($getSumData['GOLD_GRAM']);
				//echo $floorVal.'floor';
				$extraGram = $getSumData['GOLD_GRAM'] - $floorVal;
				
				$gmsToBePurchased = 0.00;
				if($extraGram > .5)
				{
					$gmsToBePurchased = 1.0000 - $extraGram;
				}
				if($extraGram < .5)
				{
					$gmsToBePurchased = .5 - $extraGram;
				}
				if(($extraGram == .5) || ($extraGram == 0.00))
				{ $gmsToBePurchased = 0.0000; }
				if(isset($getRedemptionData['extra_gram']))
				{
					echo $getRedemptionData['extra_gram'];
				}
				else
				{
					echo $gmsToBePurchased;
				}
				?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Extra Price</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php 
			if(isset($getRedemptionData['extra_price']))
				{
					echo $getRedemptionData['extra_price'];
				}
				else
				{
					echo number_format(ceil($gmsToBePurchased * $rate),2, '.', '');
				}
				?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Extra Tax</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<?php 
				$state_id = find_state_id_through_branch_id($_SESSION[ADMIN_SESSION_VAR]);
				//echo $state_id.' '.$commodity_id;
				$tax_percentage = find_tax_percentage($state_id, $commodity_id);
				if(isset($getRedemptionData['extra_tax']))
				{
					echo $getRedemptionData['extra_tax'];
				}
				else
				{					
					echo number_format(ceil($gmsToBePurchased * $rate * $tax_percentage * .01), 2, '.','');
				}
				?>
			</td>
    </tr> -->


		
		<tr> 
      <td class="tbllogin" valign="top" align="right">First Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $first_name; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Middle Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $middle_name; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Last Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $last_name; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $nominee_name; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee DOB</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $nominee_dob; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee Address</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $nominee_address; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Nominee Relationship</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $relationship_type; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Appointee Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $appointee_name; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Appointee Relationship</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $appointee_relationship_type; ?></td>
    </tr>		

		<tr> 
      <td class="tbllogin" valign="top" align="right">Reason For Redemption</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $redemption_reason; ?></td>
    </tr>
		<?php if($readonly == 'readonly'){ ?>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Redemption Application Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo date('d-m-Y',strtotime($getFolioRecord['redemption_date'])); ?></td>
    </tr>	
		<?php } ?>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Delivery Person Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" class="inplogin" name="delivery_person_name" <?php echo $otherReadonly; ?> value="<?php echo $delivery_person_name; ?>" onblur="this.value = this.value.toUpperCase();"></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Delivery Person Emp Code<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" class="inplogin" name="delivery_person_emp_code" <?php echo $otherReadonly; ?> value="<?php echo $delivery_person_emp_code; ?>" onblur="this.value = this.value.toUpperCase();"></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Delivery Person Name for HUB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" class="inplogin" name="hub_delivery_person_name" value="<?php echo $hub_delivery_person_name; ?>" onblur="this.value = this.value.toUpperCase();"></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">Delivery Person Emp Code for HUB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="text" class="inplogin" name="hub_delivery_person_emp_code" value="<?php echo $hub_delivery_person_emp_code; ?>" onblur="this.value = this.value.toUpperCase();"></td>
    </tr>

		<input type="hidden" name="extra_price" value="<?php echo number_format(ceil($gmsToBePurchased * $rate),2, '.', ''); ?>">
		<input type="hidden" name="extra_tax" value="<?php echo number_format(ceil($gmsToBePurchased * $rate * $tax_percentage * .01), 2, '.',''); ?>">
		<input type="hidden" name="extra_gram" value="<?php echo $gmsToBePurchased; ?>">
		<input type="hidden" name="amount" value="<?php echo $getSumData['AMOUNT']; ?>">
		<input type="hidden" name="tax" value="<?php echo $getSumData['TRANSACTION_CHARGES']; ?>">
		<input type="hidden" name="grams_accumulated" value="<?php echo $getSumData['GOLD_GRAM']; ?>">
		
    <?php if(($hub_delivery_person_name == '') && ($hub_delivery_person_emp_code == '')) { ?>
		<tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
			<?php if(intval($_SESSION[ROLE_ID]) == 3){ ?>
        <input value="Update" class="inplogin" type="submit" onclick="return dochk()"> 
				<?php } ?>
				<input type="button" class="inplogin" name="close" value="Close" onclick="window.close()">
			</td>
    </tr>
		<?php } ?>
  </tbody>
	 </table>
	 </form>
 </div>
 
 </center>
 </body>
</html>