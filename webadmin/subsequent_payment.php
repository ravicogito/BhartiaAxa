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

date_default_timezone_set('Asia/Calcutta');
$msg = '';
$PREMIUM_TYPE = 'SUBSEQUENT PAYMENT';

$pageOwner = "'superadmin','admin','branch','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

//echo 'SITE IS UNDER MAINTENANCE';
//exit;

$goldRate = 'Not Added to the database';
$goldUnit = 'Gram';
$selGoldRate = mysql_query("SELECT price, unit FROM gold_rate_master WHERE price_wef = '".date('Y-m-d')."' AND commodity_id='".GOLD_ID."'  AND verified_by != 0");
$numGoldRate = mysql_num_rows($selGoldRate);
if($numGoldRate > 0) 
{
	$getGoldRate = mysql_fetch_array($selGoldRate);
	$goldRate = $getGoldRate['price'];
	$goldUnit = $getGoldRate['unit'];
}

$silverRate = 'Not Added to the database';
$silverUnit = 'Gram';
$selSilverRate = mysql_query("SELECT price, unit FROM gold_rate_master WHERE price_wef = '".date('Y-m-d')."' AND commodity_id='".SILVER_ID."' AND verified_by != 0");
$numSilverRate = mysql_num_rows($selSilverRate);
if($numSilverRate > 0) 
{
	$getSilverRate = mysql_fetch_array($selSilverRate);
	$silverRate = $getSilverRate['price'];
	$silverUnit = $getSilverRate['unit'];
}


#################

if(isset($_GET['id']) && !empty($_GET['id']))
{
	

	$folio_no_id = base64_decode($_GET['id']);
	//echo $invoice_id;
	if(isset($_POST['branch_name']) && $_POST['branch_name'] != '') // insertion
{
	//echo '<pre>';
	//print_r($_POST);
	//echo '</pre>';
	extract($_POST);
	//echo $plan;
	if(!(isset($micr_code))) {$micr_code = '';}
	if(!(isset($dd_date))) {$dd_date = '';}

	$product_name = $plan; // this is product name
	$plan_id = find_product_id_through_folio_id($folio_no_id);

	#$deposit_date = date('Y-m-d', strtotime($deposit_date));
	#$receipt_date = date('Y-m-d', strtotime($receipt_date));
	#$dob = date('Y-m-d', strtotime($dob));
	$dd_date = date('Y-m-d', strtotime($dd_date));

	$premium_multiple = find_premium_multiple($plan_id);
	$min_amount = find_min_amount($plan_id);
	$max_amount = find_max_amount($plan_id);
	
	if(trim($micr_code) != '')
	{
		$micr_id = find_micr_id($micr_code);
	}

	if($amount % $comitted_amount != 0)
	{
		$msg = 'Amount must be a multiple of Monthly Commited Amount';
	}
	else if($amount > $max_amount) // $max_amount is considered the maximum amount for a particular transaction
	{
		$msg = 'Amount should not exceed '.$max_amount;
	}
	else if($amount < $min_amount) // $min_amount is considered the minimum amount for a particular transaction
	{
		$msg = 'Minimum amount must be '.$min_amount;
	}
	else if($comitted_amount % $premium_multiple != 0)
	{
		$msg = 'Monthly Commited Amount must be a multiple of '.$premium_multiple;
	}
	else if(isset($micr_id) && intval($micr_id) == 0)
	{
		$msg = 'Invalid MICR Code';
	}
	
	else
	{
		
			
			
			//$taxPercentage = find_tax_percentage($state);
			//$taxAmount = floatval(($taxPercentage / 100) * $amount);
			
			#$service_charge_percentage = $tenure == '36' ? SERVICE_CHARGE_PERCENTAGE_36 : SERVICE_CHARGE_PERCENTAGE_OTHER;		
			
			#$lastCustID = find_id_through_customer_id($customer_id);

			
			###### NEW CODE ###########
			#if($lastCustID == '')
			#{
				$guardian_name = (strtotime(date('Y-m-d')) - strtotime($dob)) < (18 * 365.25 * 24 * 60 * 60) ? $fathers_name : ''; 
				
				#$final_occupation = $occupation;
				
				
				// INSERTING DATA INTO customer_master TABLE
			//$firstInsert = "INSERT INTO customer_master SET
					//customer_id = '',
					//branch_id = '".$branch_name."',
					////first_name = '".$first_name."',
					//last_name = '".$last_name."',
					//total_premium_given = '0',
					//is_active = 'Y',
					//account_created_date = '".date('Y-m-d')."',
					//dob = '".date('Y-m-d', strtotime($dob))."',
					//dob_original = '".$dob."',
					//fathers_name = '".$fathers_name."',
					//guardian_name = '".$guardian_name."',
					//pan = '".$pan."',
				//	state = '".$state."',
					//occupation = '".$final_occupation."',
					//annual_income = '".$annual_income."'

			//";
			#echo $firstInsert;
			//mysql_query($firstInsert);
			#$lastCustID = mysql_insert_id();
			#$PREMIUM_TYPE = 'INITIAL PAYMENT';
			

			$selFolio = mysql_query("SELECT * FROM customer_folio_no WHERE id='".$folio_no_id."'");
			$numFolio = mysql_num_rows($selFolio);

			if($numFolio > 0)
			{
				$getFolioRecord = mysql_fetch_assoc($selFolio);
				$lastCustID = $getFolioRecord['customer_id'];
				$commodity_name = $getFolioRecord['commodity_name'];
			}

			if($commodity_name == SILVER_ID)
			{
				$goldRate = $silverRate; // Transaction of silver 
			}

			$branch_state = find_state_id_through_branch_id($branch_name);
			
			$taxPercentage = find_tax_percentage($branch_state, $commodity_name);
			$taxAmount = floatval(($taxPercentage / 100) * $amount);

			$branch_code = find_branch_code($branch_name);
			$branch_customer_count = find_branch_customer($branch_name);

			#$customer_id = $product_name.'/'.$branch_code.'/'.str_pad($branch_customer_count,5,'0',STR_PAD_LEFT);

			#echo "UPDATE customer_master SET customer_id='".$customer_id."' WHERE id='".$lastCustID."'";
			
			#mysql_query("UPDATE customer_master SET customer_id='".$customer_id."' WHERE id='".$lastCustID."'");

			##### INSERTING DATA INTO THE customer_folio_no table
			$selFolioNo = mysql_query("SELECT COUNT(id) as folio_counter FROM customer_folio_no WHERE customer_id = '".$lastCustID."'");
			$getFolioNo = mysql_fetch_array($selFolioNo);		

			#$FCounterForThisTransaction = $getFolioNo['folio_counter'] + 1;
			#$folioNumber = $customer_id.'/'.str_pad($FCounterForThisTransaction,3,'0',STR_PAD_LEFT);
			#$tenureVal = find_tenure($tenure);
			#$bonusPercentage = find_bonus_percentage($tenure);
			#$end_date = date('Y-m-d',strtotime(date('Y-m-d').'+'.$tenureVal.' months'));

			#$insertFolio = mysql_query("INSERT INTO customer_folio_no SET 
				#customer_id = '".$lastCustID."', 
				#folio_no = '".$folioNumber."',
				#committed_amount = '".$comitted_amount."',
				#tenure = '".$tenureVal."',
				#bonus_percentage = '".$bonusPercentage."',
				#product_name = '".$plan."',
				#start_date = '".date('Y-m-d')."',
				#end_date = '".$end_date."'

			#");

			$lastFolioNo = $folio_no_id;

			##### INSERTING DATA INTO THE customer_folio_no table
			
			#}

			###### DUPLICATE CHECKING FOR CUSTOMER ID and first name starts

			#$first_name_from_database = find_name_through_id($lastCustID);
			
			/*if((trim($first_name_from_database) != trim($first_name)) && ($PREMIUM_TYPE == 'SUBSEQUENT PAYMENT'))
			{
				$msg = 'First Name for '.$customer_id.' should be '.$first_name_from_database.'. '.$first_name.' is given by mistake.';
			}*/
		
			###### DUPLICATE CHECKING FOR CUSTOMER ID and first name ends



			###### NEW CODE ############

			// FIND TRANSACTION CHARGES

			$preimum_given = find_premium_number($folio_no_id);

			#echo $preimum_given.'<br />';
			

			$NOPFTT = $amount / $comitted_amount; // $NOPFTT = number of premium for this transaction
			//echo $NOPFTT.'<br />';

			#$service_charge_premium = $NOPFTT; // For this no of premium service charge will be given
			#if(intval($preimum_given + $NOPFTT) >= intval(SERVICE_CHARGE_QUANTITY) )
			#{
				#$service_charge_premium = intval(SERVICE_CHARGE_QUANTITY) - $preimum_given;
			#}

			#if(intval($service_charge_premium) < 0) // to avoid negetive service charge when all the service chrgeable premium has been made 
			#{
				#$service_charge_premium = 0;
			#}
			//echo $service_charge_premium; exit;

			//$service_charge_amount = floatval($comitted_amount) * $service_charge_premium * ($service_charge_percentage / 100);

			// SERVICE CHARGE PER CHARGEABLE PREMIUM IS (floatval($comitted_amount) * $tenure * ($service_charge_percentage / 100)) / SERVICE_CHARGE_QUANTITY
			#$service_charge_amount = floatval($comitted_amount) * $service_charge_premium * ($service_charge_percentage / 100) * ($tenure / SERVICE_CHARGE_QUANTITY);
						
			// INSERTING DATA INTO installment_master TABLE

			$gap_status = find_gap_status($folio_no_id);
			
			if($msg == '') // authenic entry
			{
				mysql_query("INSERT INTO receipt_generator SET
					transaction_date = '".date('Y-m-d')."'
				");

				$branch_transaction = mysql_insert_id();
				
				//$branch_transaction = find_branch_transaction($branch_name);

				$transaction_id = $product_name.'/'.date('m/Y').'/'.str_pad($branch_transaction,6,'0',STR_PAD_LEFT);
				#$installment = $preimum_given + (floatval($amount) / floatval($comitted_amount));
				$installment = floatval($amount) / floatval($comitted_amount);
				$insert_installment = "INSERT INTO installment_master SET 
										application_no = '".$application_no."',
										branch_id = '".$branch_name."',
										customer_id = '".mysql_real_escape_string($lastCustID)."',
										folio_no_id = '".mysql_real_escape_string($lastFolioNo)."',
										deposit_date = '".date('Y-m-d')."',
										migrated_from_dmspl = '".mysql_real_escape_string($gap_status)."',
										agent_code = '".mysql_real_escape_string($agent_code)."',
										agent_name = '".mysql_real_escape_string($agent_name)."',
										receipt_number = '".$transaction_id."',
										transaction_id = '".$transaction_id."',
										payment_mode = '".$payment_mode."',
										amount = '".floatval($amount)."',
										transaction_charges = '".floatval($taxAmount)."',
										gold_gram = '".number_format(($amount / $goldRate),4,'.','')."',
										gold_rate = '".floatval($goldRate)."',
										payment_type = '".$PREMIUM_TYPE."',
										category = 'INDIVIDUAL',
										dd_number = '".mysql_real_escape_string($dd_number)."',
										dd_date = '".mysql_real_escape_string($dd_date)."',
										dd_bank_name = '".mysql_real_escape_string($dd_bank_name)."',
										ifs_code = '".realTrim($ifs_code)."',
										micr_code = '".realTrim($micr_code)."',
										installment = '".intval($installment)."'
				";
				//echo '<br />'.$insert_installment; //exit;
				mysql_query($insert_installment);
				
				$total_premium_after_transaction = intval($preimum_given + $NOPFTT); 

				#echo '<br />'."UPDATE customer_folio_no SET total_premium_given='".$total_premium_after_transaction."' WHERE id = '".$lastFolioNo."'";
				mysql_query("UPDATE customer_folio_no SET total_premium_given='".$total_premium_after_transaction."' WHERE id = '".$lastFolioNo."'"); // UPDATE TOTAL PREMIUM
				#header("location: ".URL.'webadmin/index.php?p=transaction_list');

				?>
				<script type="text/javascript">
				<!--
					window.opener.document.addForm.submit();
					window.close();
				//-->
				</script>

				<?php
			}
	}

	//exit;
}
	

	$selFolio = mysql_query("SELECT * FROM customer_folio_no WHERE id='".$folio_no_id."'");
	$numFolio = mysql_num_rows($selFolio);
	if($numFolio > 0)
		{
			$getFolioRecord = mysql_fetch_assoc($selFolio);
			//print_r($getTransaction);
			$application_no = $getFolioRecord['application_no'];

			$selMasterRecord = mysql_query("SELECT * FROM customer_master WHERE id='".$getFolioRecord['customer_id']."'");

			if(mysql_num_rows($selMasterRecord) > 0)
			{	
				$getMasterRecord = mysql_fetch_assoc($selMasterRecord);
				#echo '<pre>';
				#print_r($getMasterRecord);
				#echo '</pre>';
			}	

			$trusted_id = $getMasterRecord['customer_id'];
			#$application_no = $getTransaction['application_no']; // to be changed
			$deposit_date = date('d-m-Y');
			#$receipt_date = date('d-m-Y', strtotime($getTransaction['receipt_date']));
			#$agent_code = $getTransaction['agent_code'];
			$tenure = $getFolioRecord['tenure'];
			#$receipt_number = $getTransaction['receipt_number'];
			#$transaction_id = $getTransaction['transaction_id'];
			$comitted_amount = $getFolioRecord['committed_amount'];
			$plan = $getFolioRecord['product_name'];
			#$amount = $getTransaction['amount'];
			#$payment_mode = $getTransaction['payment_mode'];
			#$payment_mode_service = $getTransaction['payment_mode_service'];

			#$dd_number = $getTransaction['dd_number'];
			#$dd_bank_name = $getTransaction['dd_bank_name'];
			#$dd_date = date('d-m-Y', strtotime($getTransaction['dd_date']));
			#if($dd_date == '01-01-1970'){ $dd_date=''; }
			$first_name = $getMasterRecord['first_name'];
			$middle_name = $getMasterRecord['middle_name'];
			$last_name = $getMasterRecord['last_name'];
			#$customer_type = $getTransaction['customer_type'];
			#$employee_code = $getTransaction['employee_code'];
			#$penalty = $getTransaction['penalty'];
			$gender = $getMasterRecord['gender'];
			$dob_original = $getMasterRecord['dob_original'];
			$dob = $getMasterRecord['dob_original'];
			$insurance = $getMasterRecord['insurance'];
			$age_proof = $getMasterRecord['age_proof'];
			#$id_proof = $getMasterRecord['id_proof'];
			$fathers_name = $getMasterRecord['fathers_name'];
			$mothers_maiden_name = $getMasterRecord['mothers_maiden_name'];
			$guardian_name = $getMasterRecord['guardian_name'];
			$address1 = $getMasterRecord['address1'];
			#$address_proof = $getMasterRecord['address_proof'];
			#$address2 = $getTransaction['address2'];
			$state = $getMasterRecord['state'];
			$city = $getMasterRecord['city'];
			$phone = $getMasterRecord['phone'];
			$email = $getMasterRecord['email'];
			$pan = $getMasterRecord['pan'];
			$zip = $getMasterRecord['zip'];
			#$annual_income = $getMasterRecord['annual_income'];
			#$occupation = $getMasterRecord['occupation'];
			$nominee_name = $getFolioRecord['nominee_name'];
			$relationship_type = $getFolioRecord['nominee_relationship'];
			#$transaction_charges = $getTransaction['transaction_charges'];

		}
		else
		{
			echo 'No record found';
			exit;
		}
}


#################



###### initialization of the variables start #######

if(!isset($branch_name)) { $branch_name = ''; } 
if(!isset($application_no)) { $application_no = ''; } 
if(!isset($customer_id)) { $customer_id = ''; } 
if(!isset($deposit_date)) { $deposit_date = date('Y-m-d'); } 
#if(!isset($receipt_date)) { $receipt_date = ''; } 
if(!isset($agent_code)) { $agent_code = ''; } 
if(!isset($agent_name)) { $agent_name = ''; } 
if(!isset($tenure)) { $tenure = ''; } 
if(!isset($receipt_number)) { $receipt_number = ''; } 
if(!isset($comitted_amount)) { $comitted_amount = ''; } 
if(!isset($amount)) { $amount = ''; } 
if(!isset($payment_mode)) { $payment_mode = ''; } 
if(!isset($payment_mode_service)) { $payment_mode_service = ''; } 
if(!isset($dd_number)) { $dd_number = ''; } 
if(!isset($dd_bank_name)) { $dd_bank_name = ''; } 
if(!isset($dd_date)) { $dd_date = ''; }
if(!isset($first_name)) { $first_name = ''; }
if(!isset($last_name)) { $last_name = ''; }
if(!isset($transaction_id)) { $transaction_id = ''; }
if(!isset($penalty)) { $penalty = ''; }
if(!isset($gender)) { $gender = 'M'; }
if(!isset($dob)) { $dob = ''; }
if(!isset($age_proof)) { $age_proof = ''; }
if(!isset($id_proof)) { $id_proof = ''; }
if(!isset($fathers_name)) { $fathers_name = ''; }
if(!isset($mothers_maiden_name)) { $mothers_maiden_name = ''; }
if(!isset($guardian_name)) { $guardian_name = ''; }
if(!isset($address1)) { $address1 = ''; }
if(!isset($address_proof)) { $address_proof = ''; }
#if(!isset($address2)) { $address2 = ''; }
if(!isset($state)) { $state = ''; }
if(!isset($city)) { $city = ''; }
if(!isset($phone)) { $phone = ''; }
if(!isset($email)) { $email = ''; }
if(!isset($pan)) {$pan = ''; }
if(!isset($nominee_name)) { $nominee_name = ''; }
if(!isset($relationship_type)) { $relationship_type = ''; }
if(!isset($middle_name)) { $middle_name = ''; }
#if(!isset($occupation)) { $occupation = ''; }
#if(!isset($annual_income)) { $annual_income = ''; }
if(!isset($plan)) { $plan = ''; }
if(!isset($ino_address_proof)) { $ino_address_proof = ''; }
if(!isset($ino_id_proof)) { $ino_id_proof = ''; }
if(!isset($ino_age_proof)) { $ino_age_proof = ''; }
if(!isset($ifs_code)) { $ifs_code = ''; }
if(!isset($micr_code)) { $micr_code = ''; }
if(!isset($occupation_name)) { $occupation_name = ''; }



###### initialization of the variables end #######





//$a=loadVariable('a','');

$id = $_SESSION[ADMIN_SESSION_VAR];

$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,6) AND branch_user_id = 0 ORDER BY branch_name ASC");


/*
echo "<pre>";
print_r($_POST);
die();
*/



?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Subsequent Payment </title>
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
	<!--
		function showHide(paymentType)
		{	
			//alert(paymentType);
			if(paymentType == 'CASH')
			{
				document.getElementById("dd_number").value='';			
				document.getElementById("dd_bank_name").value='';
				document.getElementById("dd_date").value=''; 
				document.getElementById("micr_code").value=''; 
				document.getElementById("ifs_code").value=''; 

				document.getElementById("dd_number").disabled=true;
				document.getElementById("dd_bank_name").disabled=true;
				document.getElementById("dd_date").disabled=true;
				document.getElementById("micr_code").disabled=true;
				document.getElementById("ifs_code").disabled=true;
				document.getElementById("calChq").disabled=true;
			}
			else
			{
				document.getElementById("dd_number").disabled=false;
				document.getElementById("dd_bank_name").disabled=false;
				document.getElementById("dd_date").disabled=false;
				document.getElementById("micr_code").disabled=false;
				document.getElementById("ifs_code").disabled=false;
				document.getElementById("calChq").disabled=false;
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
		//alert('aa');
		if(document.addForm.branch_name.value.search(/\S/) == -1)
		{
			alert("Please select Branch Name");
			document.addForm.branch_name.focus();
			return false;
		}

		if(document.addForm.application_no.value.search(/\S/) == -1)
		{
			alert("Please enter Application No.");
			document.addForm.application_no.focus();
			return false;
		}

		if(document.addForm.first_name.value.search(/\S/) == -1)
		{
			alert("Please enter First Name");
			document.addForm.first_name.focus();
			return false;
		}

		/*if(document.addForm.fathers_name.value.search(/\S/) == -1)
		{
			alert("Please enter Father's Name");
			document.addForm.fathers_name.focus();
			return false;
		}*/
		/*if(document.addForm.mothers_maiden_name.value.search(/\S/) == -1)
		{
			alert("Please enter Mother's Maiden Name");
			document.addForm.mothers_maiden_name.focus();
			return false;
		}*/
		if(document.addForm.dob.value.search(/\S/) == -1)
		{
			alert("Please enter Customer DOB");
			document.addForm.dob.focus();
			return false;
		}

		/*if(document.addForm.customer_id.value.search(/\S/) == -1)
		{
			alert("Please enter Customer ID");
			document.addForm.customer_id.focus();
			return false;
		}*/		

		if(document.addForm.agent_code.value.search(/\S/) == -1)
		{
			alert("Please enter Agent Code");
			document.addForm.agent_code.focus();
			return false;
		}

		if(document.addForm.agent_name.value.search(/\S/) == -1)
		{
			alert("Please enter Agent Name");
			document.addForm.agent_name.focus();
			return false;
		}


		if(document.addForm.tenure.value.search(/\S/) == -1)
		{
			alert("Please select Tenure");
			document.addForm.tenure.focus();
			return false;
		}
		
		if(document.addForm.comitted_amount.value.search(/\S/) == -1)
		{
			alert("Please enter Monthly Committed Amount");
			document.addForm.comitted_amount.focus();
			return false;
		}

		if(parseInt(document.addForm.comitted_amount.value) < 500)
		{
			alert("Minimum limit for Monthly Committed Amount is Rs. 500/-");
			document.addForm.comitted_amount.focus();
			return false;
		}

		if(document.addForm.amount.value.search(/\S/) == -1)
		{
			alert("Please enter Amount");
			document.addForm.amount.focus();
			return false;
		}

		if(parseInt(document.addForm.comitted_amount.value) > parseInt(document.addForm.amount.value))
		{
			alert("Amount must be greater than or equal to the Monthly Committed Amount");
			document.addForm.amount.focus();
			return false;
		}

		if(document.addForm.payment_mode.value == '')
		{
			alert("Please select Payment Mode");
			document.addForm.payment_mode.focus();
			return false;
		}

		if((parseInt(document.addForm.amount.value) > 999999) && (document.addForm.payment_mode.value == 'CASH'))
		{
			alert("Rs 1000000 or more can not be paid in cash in a single transaction");
			document.addForm.payment_mode.focus();
			return false;
		}
		
		if(document.addForm.payment_mode.value == 'DD' || document.addForm.payment_mode.value == 'CHEQUE')
		{
			if(document.addForm.dd_number.value.search(/\S/) == -1)
			{
				alert("Please enter DD/Cheque Number");
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
				alert("Please enter DD/Cheque Date");
				document.addForm.dd_date.focus();
				return false;
			}
			if(document.addForm.ifs_code.value.search(/\S/) == -1)
			{
				alert("Please enter IFS Code");
				document.addForm.ifs_code.focus();
				return false;
			}
			if(document.addForm.micr_code.value.search(/\S/) == -1)
			{
				alert("Please enter MICR Code");
				document.addForm.micr_code.focus();
				return false;
			}
		}
		
		/*if(document.addForm.state.value == '')
		{
			alert("Please select State");
			document.addForm.state.focus();
			return false;
		}*/

		if((parseInt(document.addForm.comitted_amount.value) > 4000) && document.addForm.pan.value.search(/\S/) == -1)
		{
			alert("Please enter PAN. PAN is must for Monthly Commited Amount greater than Rs. 4000/-");
			document.addForm.pan.focus();
			return false;
		}

		if((parseInt(document.addForm.amount.value) > 49999) && document.addForm.pan.value.search(/\S/) == -1)
		{
			alert("Please enter PAN");
			document.addForm.pan.focus();
			return false;
		}
		if((document.addForm.pan.value.search(/\S/) != -1) && (parseInt(document.addForm.pan.value.length) != 10))
		{
			alert("Invalid PAN");
			document.addForm.pan.focus();
			return false;
		}
	}
//-->
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
	function update_trans_id()
	{
		document.getElementById("transaction_id").value = document.getElementById("receipt_number").value;
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
		<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
			<tbody>
				<tr> 
					<td colspan="3">
						<? showMessage(); ?>
					</td>
				</tr>
				<tr class="TDHEAD"> 
					<td colspan="3">Subsequent Payment</td>
				</tr>
				<tr> 
					<td colspan="3" style="padding-left: 70px;" align="left">
						<b><font color="#009900">Gold Rate : <?php echo ($goldRate != 'Not Added to the database' ? 'Rs. '.$goldRate.' per '.$goldUnit : $goldRate)?>
						<br />
						<font color="#009900">Silver Rate : <?php echo ($silverRate != 'Not Added to the database' ? 'Rs. '.$silverRate.' per '.$silverUnit : $silverRate)?>
						</font>
						
						</b>
					</td>
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

				<?php 
					if(intval($_SESSION[ROLE_ID]) == 1 || intval($_SESSION[ROLE_ID]) == 2)
					{
				?>
				
				<tr> 
					<td width="27%" align="right" valign="top" class="tbllogin">Branch Name<font color="#ff0000">*</font></td>
					<td width="5%" align="center" valign="top" class="tbllogin">:</td>
					<td width="68%" align="left" valign="top">
						<select name="branch_name" id="" class="inplogin_select">
							<option value="">Select Branch</option>
						<?php 
							while($getBranch = mysql_fetch_array($selBranch))
							{					
						?>
							<option value="<?php echo $getBranch['id'];?>" <?php echo ($branch_name == $getBranch['id'] ? 'selected' : ''); ?>><?php echo $getBranch['branch_name'];?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<?php
					}			
				?>

				<?php 
					if(intval($_SESSION[ROLE_ID]) == 3 || intval($_SESSION[ROLE_ID]) == 4)
					{
				?>

				<input type="hidden" name="branch_name" id="" class="inplogin_select" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
				
				
				<?php
					}			
				?>
				<tr> 
					<td class="tbllogin" valign="top" align="right">Application No.<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="application_no" id="application_no" type="text" class="inplogin"  value="<?php echo $application_no; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" readonly /></td>
				</tr>
				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Customer ID<font color="#ff0000">*</font><br />(Reliance Money System)</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left">
						<input name="customer_id" id="customer_id" type="text" class="inplogin"  value="<?php echo $customer_id; ?>" maxlength="255" >
					</td>
				</tr> -->
				<tr> 
					<td class="tbllogin" valign="top" align="right">First Name<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="first_name" id="first_name" type="text" class="inplogin"  value="<?php echo $first_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" readonly ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Middle Name</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="middle_name" id="middle_name" type="text" class="inplogin"  value="<?php echo $middle_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" readonly ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Last Name</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="last_name" id="last_name" type="text" class="inplogin"  value="<?php echo $last_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" readonly ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Father's Name<!-- <font color="#ff0000">*</font> --></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="fathers_name" id="fathers_name" type="text" class="inplogin"  value="<?php echo $fathers_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"  ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Mother's Maiden Name<!-- <font color="#ff0000">*</font> --></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="mothers_maiden_name" id="mothers_maiden_name" type="text" class="inplogin"  value="<?php echo $mothers_maiden_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Customer DOB<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="dob" id="dob" type="text" class="inplogin"  value="<?php echo $dob; ?>" maxlength="20" readonly /> <!-- <img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.dob,'dd-mm-yyyy',this)" width="20" height="18"> --></td>
				</tr>	

				<tr> 
					<td class="tbllogin" valign="top" align="right">Applied For Insurance</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><?php echo ($insurance == '1' ? 'Yes' : 'No'); ?></td>
				</tr>

				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Deposit Date<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="deposit_date" id="deposit_date" type="text" class="inplogin" value="<?php echo date('d-m-Y'); ?>" maxlength="20" readonly /><img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.deposit_date,'dd-mm-yyyy',this)" width="20" height="18"></td>
				</tr> -->

				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Receipt Date<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="receipt_date" id="receipt_date" type="text" class="inplogin"  value="<?php echo date('d-m-Y'); ?>" maxlength="20" readonly />&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.receipt_date,'dd-mm-yyyy',this)" width="20" height="18"></td>
				</tr> -->

				<tr> 
					<td class="tbllogin" valign="top" align="right">Agent Code<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="agent_code" id="agent_code" type="text" class="inplogin"  value="<?php echo $agent_code; ?>" maxlength="50" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Agent Name<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="agent_name" id="agent_name" type="text" class="inplogin"  value="<?php echo $agent_name; ?>" maxlength="50" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Tenure<!-- <font color="#ff0000">*</font> --></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left">
					<?php echo $tenure; ?> Months
					<input name="tenure" id="tenure" type="hidden" class="inplogin"  value="<?php echo $tenure; ?>" readonly />
					</td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Plan<!-- <font color="#ff0000">*</font> --></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><?php echo $plan;?><input type="hidden" name="plan" value="<?php echo $plan; ?>">
					</td>
				</tr>
				<!-- <tr> 
					<td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Secondary Entry</font></b></td>
				</tr> -->
				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Receipt No.<font color="#ff0000">*</font><br />(Reliance Money System)</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="receipt_number" id="receipt_number" type="text" class="inplogin"  value="<?php echo $receipt_number; ?>" maxlength="50" onKeyUp="javascript:update_trans_id()" onchange="javascript:update_trans_id()"></td>
				</tr> -->
				<tr> 
					<td class="tbllogin" valign="top" align="right">Monthly Commited Amount<font color="#ff0000">*</font><br /></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="comitted_amount" id="comitted_amount" type="text" class="inplogin"  value="<?php echo $comitted_amount; ?>" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')" readonly ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Amount Paid<font color="#ff0000">*</font><br /></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="amount" id="amount" type="text" class="inplogin"  value="<?php echo $amount; ?>" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')"></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">State</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><?php echo find_place_name($state); ?>
					<input type="hidden" name="state" id="state" value="<?php echo $state; ?>">
						
					</td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Payment Mode<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left">
						<select name="payment_mode" id="payment_mode" class="inplogin_select" onchange="javascript:showHide(this.value)">
							<option value="">Select</option>
							<option value="CASH" <?php echo ($payment_mode == 'CASH' ? 'selected' : ''); ?>>Cash</option>
							<option value="CHEQUE" <?php echo ($payment_mode == 'CHEQUE' ? 'selected' : ''); ?>>Cheque</option>
							<option value="DD" <?php echo ($payment_mode == 'DD' ? 'selected' : ''); ?>>DD</option>
							<option value="ECS" <?php echo ($payment_mode == 'ECS' ? 'selected' : ''); ?>>ECS</option>
						</select>
					</td>
				</tr>

				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Payment Mode (S. Charge)<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left">
						<select name="payment_mode_service" id="payment_mode_service" class="inplogin_select">
							<option value="">Select</option>
							<option value="CASH">Cash</option>
							<option value="DD">DD</option>
						</select>
					</td>
				</tr> -->

				<tr> 
					<td class="tbllogin" valign="top" align="right">DD / Cheque Number</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="dd_number" id="dd_number" type="text" class="inplogin"  value="<?php echo $dd_number; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Bank Name</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="dd_bank_name" id="dd_bank_name" type="text" class="inplogin"  value="<?php echo $dd_bank_name; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">DD / Cheque Date</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="dd_date" id="dd_date" type="text" class="inplogin"  value="" maxlength="100" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.dd_date,'dd-mm-yyyy',this)" width="20" height="18"></td>
				</tr>
				<tr> 
					<td class="tbllogin" valign="top" align="right">IFS Code</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="ifs_code" id="ifs_code" type="text" class="inplogin"  value="<?php echo $ifs_code; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>
				<tr> 
					<td class="tbllogin" valign="top" align="right">MICR Code</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="micr_code" id="micr_code" type="text" class="inplogin"  value="<?php echo $micr_code; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>		
				<tr> 
					<td class="tbllogin" valign="top" align="right">PAN </td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="pan" id="pan" type="text" class="inplogin"  value="<?php echo $pan; ?>" maxlength="10" ></td>
				</tr>

				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Occupation</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input type="text" name="occupation" class="inplogin" value="<?php echo $occupation; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" >
					</td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Annual Income (INR)</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="annual_income" id="annual_income" type="text" class="inplogin"  value="<?php echo $annual_income; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')"></td>
				</tr> -->

				

				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Transaction ID<font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="transaction_id" id="transaction_id" type="text" class="inplogin"  value="<?php echo $transaction_id; ?>" maxlength="100" ></td>
				</tr>	 -->

				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Payment Type <font color="#ff0000">*</font></td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left">
						<select name="payment_type" id="payment_type" class="inplogin_select">
							<option value="">Select</option>
							<option value="INITIAL PAYMENT">Initial Payment</option>
							<option value="SUBSEQUENT PAYMENT">Subsequent Payment</option>
						</select>
					</td>
				</tr> -->

				<!-- <tr> 
					<td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Secondary Entry</font></b></td>
				</tr> -->

				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Transaction Charges </td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="transaction_charges" id="transaction_charges" type="text" class="inplogin"  value="" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')"></td>
				</tr> -->

				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Penalty (Reliance Money System Cheque Bounce) </td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="penalty" id="penalty" type="text" class="inplogin"  value="<?php echo $penalty; ?>" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
				</tr> -->

				<!-- <tr> 
					<td class="tbllogin" valign="top" align="right">Gender</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left">
						<select name="gender" id="gender" class="inplogin_select">
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
					</td>
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
								<option value="<?php echo $getAgeProof['id']; ?>"><?php echo $getAgeProof['document_name']; ?></option>
							<?php		
									}						
								}
							?>
							
						</select>
					</td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Identification Number(Age Proof) </td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="ino_age_proof" id="ino_age_proof" type="text" class="inplogin"  value="<?php echo $ino_age_proof; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">ID Proof</td>
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
								<option value="<?php echo $getIDProof['id']; ?>"><?php echo $getIDProof['document_name']; ?></option>
							<?php		
									}						
								}
							?>					
						</select>
					</td>
				</tr>
				<tr> 
					<td class="tbllogin" valign="top" align="right">Identification Number(Id Proof) </td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="ino_id_proof" id="ino_id_proof" type="text" class="inplogin"  value="<?php echo $ino_id_proof; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>
				

				<tr> 
					<td class="tbllogin" valign="top" align="right">Guardian Name </td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="guardian_name" id="guardian_name" type="text" class="inplogin"  value="<?php echo $guardian_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Address 1</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><textarea name="address1" id="address1" class="inplogin" onKeyUp="this.value = this.value.toUpperCase();"><?php echo $address1; ?></textarea></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Address Proof</td>
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
								<option value="<?php echo $getAddressProof['id']; ?>"><?php echo $getAddressProof['document_name']; ?></option>
							<?php		
									}						
								}
							?>					
						</select>
					</td>
				</tr>
				<tr> 
					<td class="tbllogin" valign="top" align="right">Identification Number(Address Proof) </td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="ino_address_proof" id="ino_address_proof" type="text" class="inplogin"  value="<?php echo $ino_address_proof; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				

				<tr> 
					<td class="tbllogin" valign="top" align="right">City</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="city" id="city" type="text" class="inplogin"  value="<?php echo $city; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Phone</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="phone" id="phone" type="text" class="inplogin"  value="<?php echo $phone; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Email</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="email" id="email" type="text" class="inplogin"  value="<?php echo $email; ?>" maxlength="100" ></td>
				</tr>

				

				<tr> 
					<td class="tbllogin" valign="top" align="right">Occupation </td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="occupation" id="occupation" type="text" class="inplogin"  value="<?php echo $occupation; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Annual Income </td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="annual_income" id="annual_income" type="text" class="inplogin"  value="<?php echo $annual_income; ?>" maxlength="100" onKeyPress="return keyRestrict(event, '0123456789.')"></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Nominee Name</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="nominee_name" id="nominee_name" type="text" class="inplogin"  value="<?php echo $nominee_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr>

				<tr> 
					<td class="tbllogin" valign="top" align="right">Relationship Type</td>
					<td class="tbllogin" valign="top" align="center">:</td>
					<td valign="top" align="left"><input name="relationship_type" id="relationship_type" type="text" class="inplogin"  value="<?php echo $relationship_type; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" ></td>
				</tr> -->

				<?php
					if(($goldRate != 'Not Added to the database') && ($silverRate != 'Not Added to the database'))
					{
				?>

				<tr> 
					<td colspan="2">&nbsp;</td>
					<td> <input type="hidden" id="a" name="a" value="change_pass"> 
						<input value="Add" class="inplogin" type="submit" onclick="return dochk()"> 
						&nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
				</tr>
				<?php
					}	
				?>
			</tbody>
		</table>
		</form>
 </div>
 
 </center>
 </body>
</html>