<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
$msg = '';

$readonly = ''; 
$objDB = new DB();
$pageOwner = "'superadmin','admin','branch','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 
	

// Write functions here

if(isset($_GET['id']) && !empty($_GET['id']))
{
	

	$folio_id = base64_decode($_GET['id']);
	//echo $folio_id;
	#$mysql_customer_id = find_customer_id_through_folio_id($folio_id);
	$getFolioRecord = findFolioData($folio_id); // THIS ARRAY CONTAIN ALL RECORDS FOR A PARICULAR ID
	//print_r($getFolioRecord);
	#exit;
	$getMasterRecord = findCustomerData($getFolioRecord['customer_id']); // THIS ARRAY CONTAIN ALL RECORDS FOR A PARICULAR ID

	$getRedemptionRecord = findRedemptionData($folio_id); // THIS ARRAY CONTAIN ALL REDEMPTION RECORD FROM INSTALLMENT_MASTER RECORDS FOR A PARICULAR ID

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
			//if(intval($commodity_id) == 1) {$rate = $goldRate; } 
			if(intval($commodity_id) == 2) {$rate = $silverRate; } 
			$commodity_name = find_commodity_name($commodity_id);
			$application_no = $getFolioRecord['application_no'];
			$folio_no = $getFolioRecord['folio_no'];
			$redemption_reason = $getFolioRecord['redemption_reason'];
			$trusted_id = $getMasterRecord['customer_id'];
			$tenure = $getFolioRecord['tenure'];
			$comitted_amount = $getFolioRecord['committed_amount'];
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
			$redemption_date = date('d/m/Y', strtotime($getFolioRecord['redemption_date']));
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Acknowledgement Receipt</title>
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
		if(document.addForm.redemption_reason.value.search(/\S/) == -1)
		{
			alert("Please Enter Redemption Reason");
			document.addForm.redemption_reason.focus();
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
 <form name="addForm" id="addForm" action="" method="post" >
	 
	 <table width="700" border="0">
		
		<tr>
			<td height="30" align="center" colspan="2" valign="top"><strong>Customer's Acknowledgement<br>
(Final delivery of Gold / Silver Items)
</strong></td>
		</tr>
		<tr>
			<td height="30" align="left" valign="top" style="padding-top:20px; padding-bottom:20px;"><strong>NAME OF THE HUB -</strong></td>
			<td height="30" align="left" valign="top" style="padding-top:20px; padding-bottom:20px;"><strong>DATE -</strong></td>
		</tr>
		
		<tr>
			<td height="30" align="left" valign="top" style="padding-top:20px; padding-bottom:20px;"><strong>Receipt No- (<?php echo $getRedemptionRecord['receipt_number']; ?>)</strong></td>
			
		</tr>
		
		<tr>
			<td height="30" align="left" colspan="2" valign="top" style="line-height:20px;">Delivered the gold / silver coins of ___________ grams to Mr. _______________________________
______________________________________________________ (Client / Nominee / Appointee)
delivered by Mr. ___________________________________________________________________
(Employee) with Employee Code __________________ against the following details

			</td>
		</tr>		
		
	</table>
	<p style="height:10px;">&nbsp;</p>

	<table width="700" style="border:0px solid red;">
	 
	<tbody> 
		<tr> 
      <td class="tbllogin" valign="top" align="left">Application No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $application_no; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="left">Folio No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $folio_no; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="left">Customer ID</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<?php echo $trusted_id; ?>
			</td>
    </tr>
		
		<tr> 
      <td class="tbllogin" valign="top" align="left">Reason For Redemption </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $redemption_reason; ?></td>
    </tr>		

		<tr> 
      <td class="tbllogin" valign="top" align="left">Redemption Date </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $redemption_date; ?></td>
    </tr>	
	
	<tr> 
      <td class="tbllogin" valign="top" align="left">Redemption Receipt No </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $getRedemptionRecord['receipt_number']; ?></td>
    </tr>
	
	  
  </tbody>
	 </table>
	  <p>&nbsp;</p>
	 <table width="700" border="0">
	 <tr>
	<td>
	<strong>TERMS</strong><br>
	Gold / Silver coins will not be taken back once the packet gets tampered.<br>
	Gold and Silver coins will be delivered to the self individual subject to submission of Original Identity Proof along with original redemption receipt in the counter.<br>
	A photo copy of identity proof and photo copy redemption receipt has to be provide <br><br>

	</td>
	</tr>	
	
	<tr><td>
	<strong>DECLARATION BY THE RECEIVER</strong><br>
I do hereby declare that I should abide by the terms as mentioned above. Moreover above gold / silver coins in grams received by me will be treated as full and final settlement of the scheme. I and nobody on behalf of me will not claim any further after receiving the same from the counter.
	</td></tr>  
	 </table>
	 
	 <p>&nbsp;</p>
	<table width="700" border="0">
		
		<tr>
			<td align="left" style="padding-bottom:20px; line-height:18px; "><strong>____________________________ <br/ > Signature of the Receiver<br />Emp Code : </strong></td>
			<td align="left" style="padding-bottom:20px; line-height:18px; "><strong>____________________________ <br/ > Signature of the Delivery Person<br />Emp Code : </strong></td>
		</tr>
	</table>
	 </form>
 </div>
 
 </center>
 </body>
</html>
<?php
	mysql_query("UPDATE customer_folio_no SET receipt_generated=1 WHERE id='".$folio_id."'");
?>