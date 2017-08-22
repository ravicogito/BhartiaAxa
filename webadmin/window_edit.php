<?php
include_once("../utility/config.php");

include_once("../utility/dbclass.php");

include_once("../utility/functions.php");

include_once("includes/new_functions.php");

$msg = '';

if(!isset($_SESSION[ADMIN_SESSION_VAR])){
    //echo 'Hi';
    header("location: index.php");exit();
}


$objDB = new DB();

$pageOwner = "'superadmin','admin','hub','branch','subadmin'";

#####################################################
/*define('WEBSERVICE_API',"http://apps.tradingllp.com/agent/get_agent.php");
define('WEBPHASE_API',"http://apps.tradingllp.com/agent/get_phase.php");
define('WEBSERVICE_SECRETE_KEY',"abcd234567");
define('WEBSERVICE_PASSWORD',"123");*/
define('WEBSERVICE_API',"http://senabidemoportal.com/bharatiAXA/webadmin/get_agent.php");
define('WEBPHASE_API',"http://apps.tradingllp.com/agent/get_phase.php");
define('WEBSERVICE_SECRETE_KEY',"abcd234567");
define('WEBSERVICE_PASSWORD',"123");
/*$servername = "apps.tradingllp.com";
$username = "dmsplu";
$password = "dmsplPwD12";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";*/

/*$conn = mysql_connect("apps.tradingllp.com","abcd234567","123",true);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";

mysql_select_db("gtfsaep",$conn);
   
$query1 = "SELECT * FROM gtfsaep.AGENT_MST";
//$query2 = "SELECT * FROM db2.table";
$rs = mysql_query($query1);
  exit;
  //echo $rs."aaaaaaa";exit; 
  while($row = mysql_fetch_assoc($rs)) {
    $data1[] = $row;
    print'<pre>';print_r($data1);exit;
  }*/

######################################################

chkPageAccess($_SESSION[ROLE_ID], $pageOwner);
$Query = "select * from place_master where 1 ";

$objDB->setQuery($Query);

$rsplace = $objDB->select();

//print'<pre>';print_r($rsplace);

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

		if(!isset($branch_name)) { $branch_name = ''; }

		if($business_date!='') {  $business_date=date('Y-m-d',strtotime(str_replace('/','-',$business_date))); }

		if(!isset($applicant_name)) { $applicant_name = ''; }  else  { $applicant_name = addslashes($applicant_name); }

		if($applicant_dob!='') {  $applicant_dob=date('Y-m-d',strtotime(str_replace('/','-',$applicant_dob))); }

		if(!isset($applicant_age)) { $applicant_age = ''; }  else  { $applicant_age = addslashes($applicant_age); }

		if(!isset($plan_name)) { $plan_name = ''; }  else  { $plan_name = addslashes($plan_name); }

		if(!isset($receive_cash)) { $receive_cash = ''; }  else  { $receive_cash = addslashes($receive_cash); }

		if(!isset($receive_cheque)) { $receive_cheque = ''; }  else  { $receive_cheque = addslashes($receive_cheque); }

		if(!isset($receive_draft)) { $receive_draft = ''; }  else  { $receive_draft = addslashes($receive_draft); }

		if(!isset($premium)) { $premium = ''; }  else  { $premium = addslashes($premium); }

		// Preliminary Edit variables end

		

		// Secondary Entry variables

				if(!isset($campaign_id)) { $campaign_id = ''; } else { $campaign_id = addslashes($campaign_id); }

				if(!isset($business_type)) { $business_type = ''; } else { $business_type = addslashes($business_type); }

				if($collection_date!='') {  $collection_date=date('Y-m-d',strtotime(str_replace('/','-',$collection_date))); }

				if (!isset($phase)) {$phase = '';} else { $phase = addslashes($phase);}
				//if(!isset($phase_name )) { $phase_name  = ''; } else  { $phase_name = addslashes($phase_name); }

                if(!isset($agent_code)) { $agent_code = ''; } else  { $agent_code = addslashes($agent_code); }
                if(!isset($agent_name)) { $agent_name = ''; } else  { $agent_name = addslashes($agent_name); }
 



				if(!isset($pre_printed_receipt_no)) { $pre_printed_receipt_no = ''; } else  { $pre_printed_receipt_no = addslashes($pre_printed_receipt_no); }

				if(!isset($term)) { $term = ''; } else  { $term = addslashes($term); }

				if(!isset($premium_paying_term)) { $premium_paying_term = ''; } else  { $premium_paying_term = addslashes($premium_paying_term); }

				if(!isset($sum_asured)) { $sum_asured = ''; } 

				if(!isset($pay_mode)) { $pay_mode = ''; } 

				if(!isset($insured_name)) { $insured_name = ''; } else  { $insured_name = addslashes($insured_name); }

				if(!isset($insured_dob)) { $insured_dob = ''; }

				if($insured_dob!='') {  $insured_dob=date('Y-m-d',strtotime(str_replace('/','-',$insured_dob))); }

				 

				if(!isset($insured_age)) { $insured_age = ''; } 

				if(!isset($insured_address1)) { $insured_address1 = ''; } else  { $insured_address1 = addslashes($insured_address1); }

				if(!isset($insured_address2)) { $insured_address2 = ''; } else  { $insured_address2 = addslashes($insured_address2); }

				if(!isset($insured_address3)) { $insured_address3 = ''; } else  { $insured_address3 = addslashes($insured_address3); }

				if(!isset($state_id)) { $state_id = ''; } else  { $state_id = addslashes($state_id); }

				if(!isset($pin)) { $pin = ''; } 

				if(!isset($telephone_no)) { $telephone_no = ''; } 

				

				if(!isset($occupation_name)) {$occupation_name = ''; } else  { $occupation_name = addslashes($occupation_name); }

				if(!isset($anual_income)) { $anual_income = ''; } 

				if(!isset($nominee_name)) { $nominee_name = ''; } else  { $nominee_name = addslashes($nominee_name); }

				//if(!isset($nominee_relationship_name)) { $nominee_relationship_name = ''; } else  { $nominee_relationship_name = addslashes($nominee_relationship_name); }
				if(!isset($nominee_relationship)) { $nominee_relationship = ''; } else  { $nominee_relationship = addslashes($nominee_relationship); }

				if(!isset($nominee_dob)) { $nominee_dob = ''; }

				if($nominee_dob!='') {  $nominee_dob=date('Y-m-d',strtotime(str_replace('/','-',$nominee_dob))); }

				

				if(!isset($nominee_age)) { $nominee_age = ''; }

				if(!isset($appointee_name)) { $appointee_name = ''; } else  { $appointee_name = addslashes($appointee_name); }

				//if(!isset($appointee_relationship_name)) { $appointee_relationship_name = ''; } else  { $appointee_relationship_name = addslashes($appointee_relationship_name); }

				if(!isset($appointee_relationship)) { $appointee_relationship = ''; } else  { $appointee_relationship = addslashes($appointee_relationship); }

				if(!isset($appointee_dob)) { $appointee_dob = ''; }

				if($appointee_dob!='') {  $appointee_dob=date('Y-m-d',strtotime(str_replace('/','-',$appointee_dob))); }

				

				if(!isset($appointee_age)) { $appointee_age = ''; }

				if(!isset($insured_height)) { $insured_height = ''; } else  { $insured_height = addslashes($insured_height); }

				if(!isset($insured_weight)) { $insured_weight = ''; } else  { $insured_weight = addslashes($insured_weight); }

				if(!isset($gender)) { $gender = ''; } else  { $gender = addslashes($gender); }

				if(!isset($education_qualification)) { $education_qualification = ''; } else  { $education_qualification = addslashes($education_qualification); }

				if(!isset($office_name)) { $office_name = ''; } else  { $office_name = addslashes($office_name); }

				if(!isset($nature_of_duty)) { $nature_of_duty = ''; } else  { $nature_of_duty = addslashes($nature_of_duty); }

				if(!isset($office_address1)) { $office_address1 = ''; } else  { $office_address1 = addslashes($office_address1); }

				if(!isset($office_address2)) { $office_address2 = ''; } else  { $office_address2 = addslashes($office_address2); }

				if(!isset($office_address3)) { $office_address3 = ''; } else  { $office_address3 = addslashes($office_address3); }

				if(!isset($office_teephone_no)) { $office_teephone_no = ''; }

				if(!isset($insured_age_proof)) { $insured_age_proof = ''; } else  { $insured_age_proof = addslashes($insured_age_proof); }

				if(!isset($identity_proof)) { $identity_proof = ''; } else  { $identity_proof = addslashes($identity_proof); }

				if(!isset($address_proof)) { $address_proof = ''; } else  { $address_proof = addslashes($address_proof); }

	// Secondary Entry variables end

        $new_phase = array();
		$new_phase = explode("~", $phase);

		$update_installment = "UPDATE installment_master_branch SET ";

		if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '1')) // Superadmin
		{
            $update_installment.= "hub_id='$hub_name_id',";

			$update_installment.= "branch_id='$branch_name',";

			$update_installment.= "business_date='$business_date',";

			$update_installment.= "applicant_name='$applicant_name',";

			$update_installment.= "applicant_dob='$applicant_dob',";

			$update_installment.= "applicant_age='$applicant_age',";

			$update_installment.= "plan_name='$plan_name',";

			$update_installment.= "receive_cash='$receive_cash',";

			$update_installment.= "receive_cheque='$receive_cheque',";

			$update_installment.= "receive_draft='$receive_draft',";

			$update_installment.= "premium='$premium',";

		}

								$update_installment.="phase_id = '".realTrim($new_phase[0]) ."',								
                                        phase_name = '".realTrim($new_phase[1])."',   
										type_of_business = '$business_type',

										collection_date = '$collection_date',

										agent_code = '$agent_code',
										agent_name = '$agent_name',

										pre_printed_receipt_no='$pre_printed_receipt_no', 	

										term='$term',

										premium_paying_term='$premium_paying_term',

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

										nominee_relationship='$nominee_relationship',

										nominee_dob='$nominee_dob',

										nominee_age='$nominee_age',

										appointee_name='$appointee_name',

										appointee_relationship='$appointee_relationship',

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

										is_edited = '1',

										campaign_id='$campaign_id'

										WHERE 

				

										id='$invoice_id'";

				

				//echo '<br />'.$update_installment;

				//exit;

				mysql_query($update_installment);

				if(mysql_affected_rows() >0){
                  $_SESSION[SUCCESS_MSG] = "Successfully Updated";

				}

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

			$type_of_business = stripslashes($getTransaction['type_of_business']);

			

			$phase_id = $getTransaction['phase_id'];

			if(!empty($phase_id)){

				$phase_name=find_phase_name($phase_id);

			}else{

				$phase_name='';

			}

			$hub_id = $getTransaction['hub_id'];

			

			$branch_id = $getTransaction['branch_id'];

			$collection_date = $getTransaction['collection_date'];

			$branch_name = find_branch_name($branch_id);

			

			$application_no = stripslashes($getTransaction['application_no']);

			$branch_code = stripslashes($getTransaction['BRANCH_CODE']);

			$astha_branch_name = stripslashes($getTransaction['ASTHA_BRANCH_NAME']);

			$branch_sub_code = stripslashes($getTransaction['BRANCH_SUB_CODE']);

			$quote_no = stripslashes($getTransaction['QUOTE_NO']);

			//$quote_no = stripslashes($getTransaction['QUOTE_NO']);

			$business_type = stripslashes($getTransaction['type_of_business']);

			$proposer_name = stripslashes($getTransaction['PROPOSER_NAME']);

			$proposer_dob = stripslashes($getTransaction['PROPOSER_DOB']);

			$proposer_gender = stripslashes($getTransaction['PROPOSER_GENDER']);

			$proposer_age_proof = stripslashes($getTransaction['PROPOSER_AGE_PROOF']);

			$insured_name = stripslashes($getTransaction['insured_name']);

			$insured_dob = stripslashes($getTransaction['insured_dob']);

			$insured_gender = stripslashes($getTransaction['insured_gender']);

			$insured_age = stripslashes($getTransaction['insured_age']);

			$plan_name = stripslashes($getTransaction['plan_name']);

			$term = stripslashes($getTransaction['term']);

			$premium_paying_term = stripslashes($getTransaction['premium_paying_term']);

			$frequency = stripslashes($getTransaction['pay_mode']);

			$sum_asured = stripslashes($getTransaction['sum_asured']);

			$payment_mode = stripslashes($getTransaction['PAYMENT_MODE']);

			$cash_receipt_no = stripslashes($getTransaction['receive_cash']);

            $cheqe_draft_rcpt_no = stripslashes($getTransaction['cheque_money_receipt']);

            $cash_amount = stripslashes($getTransaction['receive_cash']);

            $chq_draft_amount = stripslashes($getTransaction['draft_money_receipt']);

            $chq_draft_no = stripslashes($getTransaction['cheque_no']);

            $cheque_date = stripslashes($getTransaction['cheque_date']);

            $cheque_drft_bank_name = stripslashes($getTransaction['cheque_bank_name']);

            $cheque_branch_name = stripslashes($getTransaction['cheque_branch_name']);

            $father_name = stripslashes($getTransaction['FATHER_NAME']);

            $nominee_name = stripslashes($getTransaction['nominee_name']);

            $nominee_dob = stripslashes($getTransaction['nominee_dob']);

            $nominee_relationship = stripslashes($getTransaction['nominee_relationship']);

            $appointee_name = stripslashes($getTransaction['appointee_name']);

            $appointee_dob = stripslashes($getTransaction['appointee_dob']);

            $appointee_relationship = stripslashes($getTransaction['appointee_relationship']);

            $proposer_identity_proof = stripslashes($getTransaction['identity_proof']);

            $proposer_address_proof = stripslashes($getTransaction['address_proof']);

            $address = stripslashes($getTransaction['insured_address']);

            $state = stripslashes($getTransaction['state_id']);

            $pin = stripslashes($getTransaction['pin']);

            $telephone_no = stripslashes($getTransaction['telephone_no']);

            $education_qualification = stripslashes($getTransaction['education_qualification']);

            $occupation = stripslashes($getTransaction['occupation']);

            $anual_income = stripslashes($getTransaction['anual_income']);

            $insured_height = stripslashes($getTransaction['insured_height']);

            $insured_weight = stripslashes($getTransaction['insured_weight']);

            $pan_no = stripslashes($getTransaction['pan_no']);

            $AADHAAR_NO = stripslashes($getTransaction['AADHAAR_NO']);
           
            if($business_type == "FRESH"){
                $POLICY_NO = "";
            }else{
            	$POLICY_NO = stripslashes($getTransaction['POLICY_NO']);
            }

            $business_date = stripslashes($getTransaction['business_date']);

            $agent_code = stripslashes($getTransaction['agent_code']);

            $agent_name = stripslashes($getTransaction['agent_name']);

            $phase = $getTransaction['phase_id'];

			$pre_printed_receipt_no = stripslashes($getTransaction['pre_printed_receipt_no']);

			$cash_money_receipt = $getTransaction['cash_money_receipt'];

			$cheque_money_receipt = $getTransaction['cheque_money_receipt'];

			$draft_money_receipt = $getTransaction['draft_money_receipt'];

			$applicant_name = stripslashes($getTransaction['applicant_name']);

			$applicant_dob = $getTransaction['applicant_dob'];
            //echo $applicant_dob;exit;
            if($proposer_dob!="0000-00-00"){
			  // $applicant_age = $getTransaction['applicant_age'];
			   $date1 = strtotime($business_date);
			   $date2 = strtotime($proposer_dob);

			   $time_difference = $date1 - $date2;
               $seconds_per_year = 60*60*24*365;
			   $years = round($time_difference / $seconds_per_year);
			   $applicant_age = $years;
               //echo $years; 
		    }else{
		       $applicant_age =  $applicant_age;
		    }

		   
           $insured_name = stripslashes($getTransaction['insured_name']);

		    $insured_dob = $getTransaction['insured_dob'];

			if($insured_dob=='0000-00-00') { $insured_dob=""; }

			$insured_age = $getTransaction['insured_age'];

			if($insured_dob!="0000-00-00"){
			  // $applicant_age = $getTransaction['applicant_age'];
			   $date1 = strtotime($business_date);
			   $date2 = strtotime($insured_dob);

			   $time_difference = $date1 - $date2;
               $seconds_per_year = 60*60*24*365;
			   $years = round($time_difference / $seconds_per_year);
			   $insured_age = $years;
               //echo $years; 
		    }else{
		       $insured_age =  $insured_age;
		    }

			$nominee_name = stripslashes($getTransaction['nominee_name']);

			$nominee_relationship = stripslashes($getTransaction['nominee_relationship']);

			$nominee_dob = $getTransaction['nominee_dob'];

			if($nominee_dob=='0000-00-00') { $nominee_dob=""; }

			$nominee_age = $getTransaction['nominee_age'];

            if($nominee_dob!="0000-00-00"){
			  // $applicant_age = $getTransaction['applicant_age'];
			   $date1 = strtotime($business_date);
			   $date2 = strtotime($nominee_dob);

			   $time_difference = $date1 - $date2;
               $seconds_per_year = 60*60*24*365;
			   $years = round($time_difference / $seconds_per_year);
			   $nominee_age = $years;
               //echo $years; 
		    }else{
		       $nominee_age =  $nominee_age;
		    }

            $appointee_name = stripslashes($getTransaction['appointee_name']);

			$appointee_relationship = stripslashes($getTransaction['appointee_relationship']);

			$appointee_dob = $getTransaction['appointee_dob'];

			//if($appointee_dob=='0000-00-00') { $appointee_dob=""; }else{$appointee_dob = $appointee_dob;}
            $appointee_age = $getTransaction['appointee_age'];

            if($appointee_dob!="0000-00-00"){
			  // $applicant_age = $getTransaction['applicant_age'];
			   $date1 = strtotime($business_date);
			   $date2 = strtotime($appointee_dob);

			   $time_difference = $date1 - $date2;
               $seconds_per_year = 60*60*24*365;
			   $years = round($time_difference / $seconds_per_year);
			   $appointee_age = $years;
               //echo $years; 
		    }else{
		       $appointee_age =  $appointee_age;
		    }


            $proposer_age_proof = stripslashes($getTransaction['PROPOSER_AGE_PROOF']);

            $insured_age_proof = stripslashes($getTransaction['insured_age_proof']);

			$plan_name = stripslashes($getTransaction['plan_name']);

			$sum_asured = $getTransaction['sum_asured'];

			$pay_mode = $getTransaction['pay_mode'];

			$payment_mode = $getTransaction['PAYMENT_MODE'];

			$receive_cash = $getTransaction['receive_cash'];

			$receive_cheque = $getTransaction['receive_cheque'];

			$receive_draft = $getTransaction['receive_draft'];

			$premium = stripslashes($getTransaction['premium']);

			$cheque_no = $getTransaction['cheque_no'];

			$cheque_date = $getTransaction['cheque_date'];

			$cheque_bank_name = stripslashes($getTransaction['cheque_bank_name']);

			$cheque_branch_name = stripslashes($getTransaction['cheque_branch_name']);

			$dd_no = $getTransaction['dd_no'];

			$dd_date = $getTransaction['dd_date'];

			$dd_bank_name = stripslashes($getTransaction['dd_bank_name']);

			$dd_branch_name = stripslashes($getTransaction['dd_branch_name']);

			$identity_proof = stripslashes($getTransaction['PROPOSER_ID_PROOF']);

			$address_proof = stripslashes($getTransaction['PROPOSER_ADDRESS_PROOF']);

			$insured_address1 = stripslashes($getTransaction['insured_address1']);

			$insured_address2 = stripslashes($getTransaction['insured_address2']);

			$insured_address3 = stripslashes($getTransaction['insured_address3']);

			$state_id = stripslashes($getTransaction['state_id']);

			$pin = $getTransaction['pin'];

			$telephone_no = $getTransaction['telephone_no'];

			$term = stripslashes($getTransaction['term']);

			$premium_paying_term = stripslashes($getTransaction['premium_paying_term']);

			$office_name = stripslashes($getTransaction['office_name']);

			$office_address1 = stripslashes($getTransaction['office_address1']);

			$office_address2 = stripslashes($getTransaction['office_address2']);

			$office_address3 = stripslashes($getTransaction['office_address3']);

			$gender = stripslashes($getTransaction['gender']);

			$education_qualification = stripslashes($getTransaction['education_qualification']);

			$occupation = stripslashes($getTransaction['occupation']);

			$office_teephone_no = $getTransaction['office_teephone_no'];

			$nature_of_duty = stripslashes($getTransaction['nature_of_duty']);

			$anual_income = $getTransaction['anual_income'];

			$insured_height = stripslashes($getTransaction['insured_height']);

			$insured_weight = stripslashes($getTransaction['insured_weight']);

			

	    }else{

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

	function clear_input(attr){

		//alert(attr);
        document.getElementById(attr).value="";
    }

	

	function insuranceEligible(dob){

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

	if (!e.ctrlKey && code!=9 && code!=8 && code!=36 && code!=37 && code!=38 && (code!=39 || (code==39 && character=="'")) && code!=40) {

		if (character.match(restrictionType)) {

			return true;

		} else {

			return false;

		}

		

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

		

		case "applicant_dob":

		input="applicant_dob";

		output="applicant_age";

		break;

	

	}

        var date1 = new Date();

        var  dob= document.getElementById(input).value;

        var pattern = /^\d{1,2}\/\d{1,2}\/\d{4}$/; //Regex to validate date format (dd/mm/yyyy)

        if (pattern.test(dob)) {

            var year=Number(dob.substr(6,4));

			var month=Number(dob.substr(3,2))-1;

			var day=Number(dob.substr(0,2));

			var today=new Date();

			var age=today.getFullYear()-year;

			if(today.getMonth()<month || (today.getMonth()==month && today.getDate()<day)){

				age--;

			}

			if(age<0){

				age='';

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


   function deleteagent(){
        $("#chk_deleteagent").hide();   // Off validation button
        $("#chk_loadagent").show();     // On validation button
        $('#status_flag').val(''); // for hidden field null
        $('#agent_code').val(''); // for Agent Code field null
        $('#agent_name').val(''); // for Agent Name field null
        $('#status_msg').text(''); // Delete msg from hidden field
        $("#stat_msg").hide();
        document.getElementById("agent_code").readOnly=false; // agent code readonly false
    }

    function loadphase(){
        $("#chk_loadagent").show(); // Off validation button
        $("#chk_deleteagent").hide(); // On DELETE button
        $('#agent_code').val(''); // for Ref Code field null
        $('#agent_name').val(''); // for Ref Name field null
        $("#stat_msg").hide();
        document.getElementById("agent_code").readOnly=false; // agent code readonly false
    }


 function loadagent(agent,phase){
   //alert(agent+"======================"+phase);
        var a = "<?php echo WEBSERVICE_SECRETE_KEY; ?>";
        var datastring = 'password=' +<?php echo WEBSERVICE_PASSWORD; ?>+'&secret_key='+a+'&agent_code='+agent+'&phase_id='+phase;
        var URL = "<?php echo WEBSERVICE_API; ?>";
       // alert(URL); alert(datastring);

        if (agent == ""){
            alert("Please enter Agent Code.");
            $("#agent_code").focus();
        }else if(phase == ""){
            alert("Please enter Phase.");
            $("#phase").focus();
        }else{
            $.ajax({
                    type: "POST",
                    url: URL,
                    data:  datastring,
                    dataType: 'JSON',
                    cache: false,
//                    async:true,
//                    crossDomain: true,
//                    contentType: "application/json; charset=utf-8",
                        success: function(data){
                        var obj = JSON.parse(data);
                        //alert("AAAAAAAAAAAA");

                        console.log(obj);
                        //alert(obj.result);

                        if(obj.result == '0'){
                           //alert(obj.msg);
                            $('#status_msg').text(obj.msg);  // error msg display
                            $('#status_msg').css('color', '#ff0000');
                            $('#status_flag').val(obj.result); // for hidden field 0
                            $("#stat_msg").show();     // For tr display
                        }
                         if(obj.result == '1'){
                            //alert(obj.AG_NAME);
                            $('#status_flag').val(obj.result); // for hidden field 1
                            $("#chk_loadagent").hide(); // Off validation button
                            $("#chk_deleteagent").show(); // On DELETE button
                             document.getElementById("agent_code").readOnly=true; // agent code readonly true
                             $('#agent_name').val(obj.AG_NAME);         // displaying agent name
                             //=========== For Message ==============//
                             $('#status_msg').text(obj.msg);  // success msg display
                             $('#status_msg').css('color', '#319620');
                             $("#stat_msg").show();     // For tr display
                             //=========== For Message ==============//
                         }
                        return false;

                    }
            });
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

	

	

		if(document.addForm.phase.value.search(/\S/) == -1)

		{

			alert("Please Enter Phase.");

			document.addForm.phase.focus();

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

			

			/*else if((document.addForm.receive_cash.value > 0) && ((document.addForm.receive_draft.value  > 0) || (document.addForm.receive_cheque.value > 0))){

				alert("Please enter any one Receive Cash or Receive Draft or Receive cheque");

				return false;

			}*/

			

			/*else if((document.addForm.receive_draft.value > 0) && ((document.addForm.receive_cash.value > 0) || (document.addForm.receive_cheque.value > 0))){

				alert("Please enter any one Receive Cash or Receive Draft or Receive cheque");

				return false;

			}*/

			

			/*else if((document.addForm.receive_cheque.value > 0) && ((document.addForm.receive_cash.value > 0) || (document.addForm.receive_draft.value > 0))){

				alert("Please enter any one Receive Cash or Receive Draft or Receive cheque");

				return false;

			}*/

			

			/*else if((document.addForm.receive_cash.value < 1) && (document.addForm.receive_draft.value < 1) && (document.addForm.receive_cheque.value < 1)){

				alert("Please enter any one Receive Cash or Receive Draft or Receive cheque");

				return false;

			}*/

			

			

			<?php

			}

			?>

		

		else if(document.addForm.agent_code.value.search(/\S/) == -1)

		{

			alert("Please Enter Agent Code.");

			document.addForm.agent_code.focus();

			return false;

		}	

		

		

		else if(document.addForm.pre_printed_receipt_no.value.search(/\S/) == -1)

		{

			alert("Please Enter Receipt No.");

			document.addForm.pre_printed_receipt_no.focus();

			return false;

		}

		

		

		else if(document.addForm.pre_printed_receipt_no.value.length !=7)

		{	

		alert("Please Enter Correct Receipt No.");

		document.addForm.pre_printed_receipt_no.focus();	

		return false;

		}

			

				

		else if(document.addForm.term.value.search(/\S/) == -1)

		{

			alert("Please Enter Term");

			document.addForm.term.focus();

			return false;

		}	

		else if(document.addForm.premium_paying_term.value.search(/\S/) == -1)

		{

			alert("Please Enter Premium Paying Term");

			document.addForm.premium_paying_term.focus();

			return false;

		}	

		else if(document.addForm.sum_asured.value < 1 )

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

		

		else if(document.addForm.telephone_no.value.length !=10)

		{	

		alert("Please Enter Correct Phone No.");

		document.addForm.telephone_no.focus();	

		return false;

		}

		

		

		

		else if(document.addForm.occupation.value.search(/\S/) == -1 && document.addForm.occupation_display.value.search(/\S/) == -1)

		{

			alert("Please enter Occupation");

			document.addForm.occupation.focus();

			return false;

		}	

		else if(document.addForm.anual_income.value  < 1)

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

		else if(document.addForm.appointee_name.value.search(/\S/) == -1 && document.addForm.nominee_age.value < 18)

		{

			alert("Please enter Appointee Name");

			document.addForm.appointee_name.focus();

			return false;

		}

		/*

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

		}*/

		/*

		else if(document.addForm.appointee_age.value < 18)

		{

			alert("Appointee age should be more than 18 years");

			document.addForm.appointee_age.focus();

			return false;

		}*/

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

		else if(document.addForm.nature_of_duty.value.search(/\S/) == -1)

		{

			alert("Please enter Nature of Duty");

			document.addForm.nature_of_duty.focus();

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

	

//-->

</script>

<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>

<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>





<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>



<script type="text/javascript">

/*function removeEnter(data)

	{

	alert "Hi";

	return false;

	$('data').keypress(function(event) {

			if (event.keyCode == 13) {

			 return false;

				}

			});





			

	}*/

	</script>

	

	<script language="JavaScript" type="text/JavaScript">





function isNumber(field) {

        var re = /^[0-9-'.'-',']*$/;

        if (!re.test(field.value)) {

            alert('Agent Code should be Numeric');

            field.value = field.value.replace(/[^0-9-'.'-',']/g,"");

        }

    }

	function copy_details(){

		var chk = $('#copy_name').is(':checked');

		if(chk == true){

			$('#insured_name').val('<?php echo $insured_name; ?>');

			$('#insured_name').attr('readonly', true);

			$('#insured_dob').val('<?php echo date("d/m/Y",strtotime($insured_dob)); ?>');

			$('#insured_dob').attr('readonly', true);

			$('#insured_age').val('<?php echo $insured_age; ?>');

			$('#insured_age').attr('readonly', true);

			$('#insured_address1').val('<?php echo $address; ?>');

			$('#insured_address1').val('<?php echo $address; ?>');

		}else{

			$('#insured_name').val('');

			$('#insured_name').removeAttr('readonly');

			$('#insured_dob').val('');

			$('#insured_dob').removeAttr('readonly');

			$('#insured_age').val('');

			$('#insured_age').removeAttr('readonly');

		}

	}

	
function ageCountNew(val){
	//alert(val);
	var bsndate = document.getElementById('business_date').value;  
	var bresult = bsndate.split('/');
	var bdate = bresult[2]+'-'+bresult[1]+'-'+bresult[0];

	if(val== 'applicant_dob'){
     //alert(bdate);
     var cdate = document.getElementById('applicant_dob').value;
     var gresult = cdate.split('/');
     var gdate = gresult[2]+'-'+gresult[1]+'-'+gresult[0];
     //alert(gresult[0]);alert(gresult[1]);alert(gresult[2]);
     //alert(gdate);
     var busnYear = new Date(bdate);
     var givenYear = new Date(gdate);
     var busnYear = busnYear.getFullYear();
     var givenYear = givenYear.getFullYear();
     var age = busnYear - givenYear;
    // alert(bdate);alert(gdate);alert(ages);
     $('#applicant_age').val(age);
    // document.getElementById('applicant_age123').innerHTML = ages;
    }
	if(val== 'insured_dob'){
	 var cdate = document.getElementById('insured_dob').value;	
	 var gresult = cdate.split('/');
	 gdate = gresult[2]+'-'+gresult[1]+'-'+gresult[0];
      //alert(gresult[0]);alert(gresult[1]);alert(gresult[2]);
     var busnYear = new Date(bdate);
     var givenYear = new Date(gdate); 
     var busnYear = busnYear.getFullYear();
     var givenYear = givenYear.getFullYear();
     var age = busnYear - givenYear;
    // alert(bdate);alert(gdate);alert(age);
     //document.getElementById('insured_age').innerHTML= age;
      $('#insured_age').val(age);
	}
	if(val== 'nominee_dob'){
      var cdate = document.getElementById('nominee_dob').value;
	  var gresult = cdate.split('/');
	  gdate = gresult[2]+'-'+gresult[1]+'-'+gresult[0];
     // alert(gresult[0]);alert(gresult[1]);alert(gresult[2]);
     var busnYear = new Date(bdate);
     var givenYear = new Date(gdate); 
     var busnYear = busnYear.getFullYear();
     var givenYear = givenYear.getFullYear();
     var age = busnYear - givenYear;
     //document.getElementById('nominee_age').innerHTML= age;
     $('#nominee_age').val(age);
	}
	if(val== 'appointee_dob'){
       var cdate = document.getElementById('appointee_dob').value;
       var gresult = cdate.split('/');
       gdate = gresult[2]+'-'+gresult[1]+'-'+gresult[0];
       //alert(gresult[0]);alert(gresult[1]);alert(gresult[2]);
       var busnYear = new Date(bdate);
       var givenYear = new Date(gdate); 
       var busnYear = busnYear.getFullYear();
       var givenYear = givenYear.getFullYear();
       var age = busnYear - givenYear;
      // document.getElementById('appointee_age').innerHTML= age;
       $('#appointee_age').val(age);
	}
	
//alert(cdate);
}

function calculatedPremium(val){
  // alert(val);
  var total = 0;
  if(val=='receive_cash') {
     var receive_cash = $('#receive_cash').val();
  }else{
  	var receive_cash = $('#receive_cash').val();
  } 
  if(val=='receive_cheque') {
  	 var receive_cheque = $('#receive_cheque').val();
  }else{
  	  var receive_cheque = $('#receive_cheque').val(); 
  } 

  if(val=='receive_draft') {
  	 var receive_draft = $('#receive_draft').val();
  }else{
  	  var receive_draft = $('#receive_draft').val(); 
  } 
  //alert(receive_cash);
  //alert(parseInt(receive_cheque));
  //alert(parseInt(receive_draft));
  gtotal = parseInt(total)+parseInt(receive_cash)+parseInt(receive_cheque)+parseInt(receive_draft);
  //alert(gtotal);
  $('#premium').val(gtotal);

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

      <td width="28%" align="right" valign="top" class="tbllogin">BRANCH CODE </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $branch_code; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">ASTHA BRANCH NAME </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $astha_branch_name; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">BRANCH SUB CODE </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $branch_sub_code; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">QUOTE NO </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $quote_no; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">E-APP NO </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $application_no; ?></td>

    </tr>

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">BUSINESS TYPE </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $business_type; ?></td>

    </tr>-->

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PROPOSER NAME </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $proposer_name; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PROPOSER DOB </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $proposer_dob; ?></td>

    </tr>-->

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PROPOSER GENDER </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $proposer_gender; ?></td>

    </tr>-->

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PROPOSER AGE PROOF </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $proposer_age_proof; ?></td>

    </tr>

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">INSURED NAME </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $insured_name; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">INSURED DOB </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $insured_dob; ?></td>

    </tr>-->

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">INSURED GENDER </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $insured_gender; ?></td>

    </tr>

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">INSURED AGE PROOF </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $insured_age; ?></td>

    </tr>-->

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PLAN </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $plan_name; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">TERM </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $term; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PREMIUM PAYING TERM </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $premium_paying_term; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">FREQUENCY </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $frequency; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">SUM ASSURED </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $sum_asured; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PAYMENT MODE </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $payment_mode; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">CASH RECEIPT NO </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $cash_receipt_no; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">CHEQUE / DRAFT RECEIPT NO </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $cheqe_draft_rcpt_no; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">CASH AMOUNT </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $cash_amount; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">CHEQUE/DRAFT AMOUNT </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $chq_draft_amount; ?></td>

    </tr>

		<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">CHEQUE / DRAFT NO </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $chq_draft_no; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">CHEQUE / DRAFT DATE </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $cheque_date; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">CHEQUE / DRAFT BANK NAME :</td>

      <td width="3%" align="center" valign="top" class="tbllogin"></td>

      <td width="50%" align="left" valign="top"><?php echo $cheque_drft_bank_name; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">CHEQUE / DRAFT BRANCH NAME :</td>

      <td width="3%" align="center" valign="top" class="tbllogin"></td>

      <td width="50%" align="left" valign="top"><?php echo $cheque_branch_name; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">FATHER'S NAME </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $father_name; ?></td>

    </tr>

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">NOMINEE NAME </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $nominee_name; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">NOMINEE D.O.B. </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $nominee_dob; ?></td>

    </tr>-->

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">NOMINEE RELATION </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $nominee_relationship; ?></td>

    </tr>-->

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">APPOINTEE NAME </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $appointee_name; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">APPOINTEE D.O.B. </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $appointee_dob; ?></td>

    </tr>-->

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">APPOINTEE RELATION </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $appointee_relationship; ?></td>

    </tr>-->

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PROPOSER ID PROOF </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $proposer_identity_proof; ?></td>

    </tr>-->

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PROPOSER ADDRESS PROOF </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $proposer_address_proof; ?></td>

    </tr>-->

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">ADDRESS </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $address; ?></td>

    </tr>

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">STATE  </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $state; ?></td>

    </tr>-->

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PIN </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $pin; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">MOBILE NO </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $telephone_no; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">EDUCATION QUALIFICATION </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $education_qualification; ?></td>

    </tr>

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">OCCUPATION </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $occupation; ?></td>

    </tr>-->

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">ANNUAL INCOME </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $anual_income; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">HEIGHT </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $insured_height; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">WEIGHT </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $insured_weight; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">PAN NO </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $pan_no; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">AADHAAR NO </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $AADHAAR_NO; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">POLICY NO </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo $POLICY_NO; ?></td>

    </tr>

	<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">Business Date  </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php echo date("d/m/Y",strtotime($business_date)); ?></td>

    </tr>

	

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">Phase</td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $phase_name; ?></td>

    </tr>-->

	

	<!--<tr> 

      <td width="28%" align="right" valign="top" class="tbllogin">Agent Code </td>

      <td width="3%" align="center" valign="top" class="tbllogin">:</td>

      <td width="50%" align="left" valign="top"><?php //echo $agent_code; ?></td>

    </tr>-->	



<?php if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '1')) // Superadmin Preliminary Edit



		{

	?>

			<tr> 

			  <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Preliminary Edit</font></b></td>

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

      <td valign="top" align="left"><input name="business_date" id="business_date" type="text" class="inplogin"  value="<?php if($business_date!="") { echo date("d/m/Y",strtotime($business_date)); } ?>" maxlength="20" readonly /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.business_date,'dd/mm/yyyy',this)" width="20" height="18"><br><a onClick="clear_input('business_date');" style="cursor:pointer">Clear</a></td>

    </tr>

	

	

	<tr> 

      <td class="tbllogin" valign="top" align="right">Applicant Name<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="applicant_name" id="applicant_name" type="text" class="inplogin"  value="<?php echo $proposer_name; ?>" /></td>

    </tr>

		

	<tr> 

      <td class="tbllogin" valign="top" align="right">Applicant DOB<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="applicant_dob" id="applicant_dob" type="text" class="inplogin"  value="<?php if($proposer_dob!="0000-00-00") { echo date("d/m/Y",strtotime($proposer_dob)); } ?>" maxlength="20" onchange="ageCountNew('applicant_dob');" readonly /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.applicant_dob,'dd/mm/yyyy',this)" width="20" height="18"><br><a onClick="clear_input('applicant_dob');" style="cursor:pointer">Clear</a></td>

    </tr>

	

	<tr> 

      <td class="tbllogin" valign="top" align="right">Applicant Age<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="applicant_age" id="applicant_age" type="text" class="inplogin"  value="<?php echo $applicant_age; ?>" readonly /></td>

    </tr>

	

	

	<tr> 

      <td class="tbllogin" valign="top" align="right">Plan Name</td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="plan_name" id="plan_name" type="text" class="inplogin"  value="<?php echo $plan_name; ?>" /></td>

    </tr>

	<tr> 

      <td class="tbllogin" valign="top" align="right">Receive Cash</td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="receive_cash" id="receive_cash" type="text" onblur="calculatedPremium('receive_cash');"  class="inplogin"   value="<?php echo $receive_cash; ?>" /></td>

    </tr>

	<tr> 

      <td class="tbllogin" valign="top" align="right">Receive Cheque</td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="receive_cheque" id="receive_cheque" type="text" class="inplogin" onblur="calculatedPremium('receive_cheque');"  value="<?php echo $receive_cheque; ?>" /></td>

    </tr>

	<tr> 

      <td class="tbllogin" valign="top" align="right">Reveive DD</td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="receive_draft" id="receive_draft" onblur="calculatedPremium('receive_draft');"  type="text" class="inplogin"  value="<?php echo $receive_draft; ?>" /></td>

    </tr>
    <?php $premium_amount = $receive_cash+$receive_cheque+$receive_draft; ?>
	<tr> 

      <td class="tbllogin" valign="top" align="right">Premium<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

     <td valign="top" align="left"><input name="premium" id="premium" type="text" class="inplogin"  value="<?php echo $premium_amount; ?>" /></td>

    </tr>

	

	<?php

	}

	

	// Superadmin Preliminary Edit end

	?>



	<tr> 

      <td width="tbllogin" class="tbllogin" align="center" valign="top"><b><font color="#ff0000">Secondary Entry</font></b></td>

	  <td class="tbllogin" valign="top" align="center">&nbsp;</td>

	  <td align="left" valign="top"><input name="copy_name" id="copy_name" type="checkbox" class="inplogin" onclick="copy_details();"  <?php if($proposer_name == $insured_name){echo 'checked';}?>>Copy name from primary</td>

    </tr>	

	<input type="hidden" name="app_dob" id="app_dob" value="<?php echo date('d-m-Y',strtotime($getTransaction['applicant_dob']));?>">

	

	<tr>

	<td align="center"><!--<input name="copy_data" id="copy_data" type="button" onClick="copy_datas();" value="Copy"  >--></td></tr>

	<tr>

                      <td class="tbllogin" valign="top" align="right"><strong>Campaign</strong> </td>

                      <td class="tbllogin" valign="top" align="center">:</td>

                      <td valign="top" align="left">

                      <select name="campaign_id" id="campaign_id" class="inplogin"  style="width:200px;">

                        <option value="1">NONE</option>

                            

                            <?php

										$campaign_hub_id=$_SESSION[HUB_ID]; 

										$campaign_branch_id=$_SESSION[ADMIN_SESSION_VAR];

										

										$query = "select campaign_id, campaign from t_99_campaign WHERE active_status=1 AND branch_id='$campaign_branch_id' ORDER BY campaign ASC"; 	

																			

										/*if($campaign_branch_id!=1 && $campaign_hub_id!=2 && $campaign_branch_id!=2)

										{

											$query.=" AND (branch_id='$campaign_branch_id')";

										}

										elseif($campaign_branch_id!=1 && $campaign_branch_id!=2)

										{

											if($campaign_hub_id==2)

											{

												$query.=" AND (hub_id='$campaign_hub_id')";

											}

										}*/

										

										$selCampaign=mysql_query($query);

										

                                        $numCampaign = mysql_num_rows($selCampaign);

                                        

                                            while($getCampaign = mysql_fetch_array($selCampaign))

                                            {	

                                                                    

                                    ?>

                            <option value="<?php echo $getCampaign['campaign_id']; ?>" <?php echo ($getCampaign['campaign_id'] == $getTransaction['campaign_id'] ? 'selected' : '');?>><?php echo $getCampaign['campaign']; ?></option>

                            <?php

                                            }

                                        

                                    ?>

                          </select></td>

                                        </tr>

	<input type="hidden" name="app_dob" id="app_dob" value="<?php echo date('d-m-Y',strtotime($getTransaction['applicant_dob']));?>">

	

	<tr>

		<td align="center"></td>

	</tr>

        

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

                                                              if($numPlan > 0){

                                                              while($getPlan = mysql_fetch_array($selPlan)) {	
                                                        ?>

                                                              <option value="<?php echo $getPlan['business_type']; ?>" <?php echo ($getPlan['business_type'] == $type_of_business ? 'selected' : ''); ?>><?php echo $getPlan['business_type']; ?></option>

                                                      <?php

                                                                      }

                                                              }

                                                      ?>

                                              </select>

                                              </td>

                          </tr>

      <tr> 

      <td class="tbllogin" valign="top" align="right">Collection Date</td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="collection_date" id="collection_date" type="text" class="inplogin"  value="<?php if($collection_date!="0000-00-00") { echo date("d/m/Y",strtotime($collection_date)); } ?>" maxlength="20" readonly /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.collection_date,'dd/mm/yyyy',this)" width="20" height="18"><br><a onClick="clear_input('collection_date');" style="cursor:pointer">Clear</a></td>

    </tr>

<?php
define('WEBPHASE_API',"http://apps.tradingllp.com/agent/get_phase.php");
$val = file_get_contents(WEBPHASE_API);
$val = json_decode($val);
$data = $val->data;
//echo "<pre>";
//print_r($data);//exit;
//echo WEBPHASE_API."abcccccccccccccc"

//echo $data[0]->PHASE_ID;
//echo $data[0]->PHASE_NAME;
?>

	<tr> 

      <td width="tbllogin" align="right" valign="top" class="tbllogin">Phase<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td align="left" valign="top">

		<!--<input name="phase_name" id="phase_name" type="text" class="inplogin"  value="<?php //echo $phase_name; ?>" maxlength="255" >-->
		 <select style="width: 157px;" name="phase" id="phase" class="inplogin">
                        <option value=""> -- Please Select -- </option>
                        <?php for ($i = 0; $i < count($data); $i++) {?>
                            <option value="<?php echo $data[$i]->PHASE_ID . "~" . $data[$i]->PHASE_NAME; ?>" 
                            <?php if ($phase == $data[$i]->PHASE_ID) {echo "selected";}?> ><?php echo $data[$i]->PHASE_NAME; ?></option>
                        <?php }?>
        </select>

		<!--<select name="phase" id="phase" class="inplogin">

			<option value="">Select</option>

			<?php 

				//$selPhase = mysql_query("select id, phase from phase_master order by phase");   //for Plan dropdown

				//$numPhase = mysql_num_rows($selPhase);

				//if($numPhase > 0)

				{

					//while($getPhase = mysql_fetch_array($selPhase))

					{	

			?>

				<option value="<?php //echo $getPhase['id']; ?>" <?php //echo ($getPhase['id'] == $phase_id ? 'selected' : ''); ?>><?php //echo $getPhase['phase']; ?></option>

			<?php

					}

				}

			?>

		</select>-->

	  </td>

    </tr>

	<!--<tr> 

      <td width="tbllogin" align="right" valign="top" class="tbllogin">Agent Code<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td align="left" valign="top"><input name="agent_code" id="agent_code" type="text" class="inplogin"  value="<?php //echo $agent_code; ?>" maxlength="255" ></td>
   </tr>-->
    <tr>
        <td class="tbllogin" valign="top" align="right">Agent Code<font color="#ff0000">*</font></td>
        <td class="tbllogin" valign="top" align="center">:</td>
        <td valign="top" align="left">
            <input <?php if (isset($agent_code) && $agent_code != '') {?>readonly<?php }?> name="agent_code" id="agent_code" type="text" class="inplogin"  value="<?php echo $agent_code; ?>" maxlength="100" onkeyPress="return restrictCharacters(this, event, numOnly)">
            <span class="tbllogin">
                <input <?php if (isset($agent_code) && $agent_code != '') {?>style="display: none;"<?php }?> type="button" name="chk_loadagent" id="chk_loadagent" value="Validate Agent Code" 
                onclick="javascript:loadagent(agent_code.value, phase.value);">
                <input <?php if (!isset($agent_code) || $agent_code == '') {?>style="display: none;" <?php }?> type="button" name="chk_deleteagent" id="chk_deleteagent" value="Delete Agent Code" onclick="javascript:deleteagent();">
            </span>
        </td>
    </tr>

     <tr id="stat_msg" style="display: none;">
            <td class="tbllogin" valign="top" align="right"></td>
            <td class="tbllogin" valign="top" align="center"></td>
            <td valign="top" align="left">
                <div name="status_msg" id="status_msg"></div>
            </td>
     </tr>

     <input type="hidden" name="status_flag" id="status_flag" value="<?php if (isset($agent_code) && $agent_code != '') {echo "1";}?>">

    <tr>
      <td class="tbllogin" valign="top" align="right">Agent  Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input <?php if (isset($agent_code) && $agent_code != '') {?>readonly<?php }?> name="agent_name" id="agent_name" type="text" class="inplogin"  value="<?php echo $agent_name; ?>" maxlength="50" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Printed Receipt<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="pre_printed_receipt_no" id="pre_printed_receipt_no" type="text" class="inplogin"  value="<?php echo $pre_printed_receipt_no; ?>" maxlength="7" onKeyPress="return restrictCharacters(this,event,numOnly);" ></td>
   </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Term<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="term" id="term" type="text" class="inplogin"  value="<?php echo $term; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
   </tr>

	<tr> 

      <td class="tbllogin" valign="top" align="right">Premium Paying Term<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="premium_paying_term" id="premium_paying_term" type="text" class="inplogin"  value="<?php echo $premium_paying_term; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>

    </tr>

	<tr> 

      <td class="tbllogin" valign="top" align="right">Sum Assured <font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="sum_asured" id="sum_asured" type="text" class="inplogin"  value="<?php echo stripslashes($sum_asured); ?>" maxlength="255" onKeyPress="return keyRestrict(event, '0123456789.')"></td>

    </tr>

	<!--<tr> 

      <td class="tbllogin" valign="top" align="right">Pay Mode <font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="pay_mode" id="pay_mode" type="text" class="inplogin"  value="<?php echo stripslashes($pay_mode); ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>

    </tr>-->

	<tr> 

      <td class="tbllogin" valign="top" align="right">Pay Mode <font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left">

		<select name="pay_mode" id="pay_mode" class="inplogin" >

			<option value="">Select Mode</option>

			<?php 

				$Query = "select  *  from frequency_master ";

				$objDB->setQuery($Query);

				$rs = $objDB->select();

				foreach($rs as $data){

			?>

			<option value="<?php echo $data['id']?>" <?php if($data['id'] == $pay_mode){echo "selected";}else{echo "";}?>><?php echo $data['frequency']?></option>

			<?php } ?>

		</select>

	  </td>

    </tr>

    <tr> 

      <td class="tbllogin" valign="top" align="right">Payment Mode <font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="payment_mode" id="payment_mode" type="text" class="inplogin"  value="<?php echo  $payment_mode; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>

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

      <td valign="top" align="left"><input name="insured_dob" id="insured_dob" type="text" class="inplogin"  value="<?php if($insured_dob!="") { echo date("d/m/Y",strtotime($insured_dob)); } ?>" maxlength="20" readonly onChange="ageCountNew('insured_dob')" /> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.insured_dob,'dd/mm/yyyy',this)" width="20" height="18"></td>

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

      <td valign="top" align="left"><input name="insured_age" id="insured_age" type="text" class="inplogin"  
      value="<?php echo stripslashes($insured_age); ?>" maxlength="255" onKeyPress="return keyRestrict(event, '0123456789')"></td>

    </tr>



	<tr> 

      <td align="right" valign="top" class="tbllogin">Insured Address1 <strong><font color="#ff0000">*</font></strong></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><textarea name="insured_address1" id="insured_address1" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" maxlength="40" onKeyPress="$('form').keypress(function(e){

    if(e.which === 13){

        return false;

    }

});"  style="width:200px;"><?php echo $insured_address1; ?></textarea></td>

    </tr>

	<tr> 

      <td align="right" valign="top" class="tbllogin">Insured Address2</td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><textarea name="insured_address2" id="insured_address2" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" maxlength="40" onKeyPress="$('form').keypress(function(e){

    if(e.which === 13){

        return false;

    }

});"  style="width:200px;"><?php echo $insured_address2; ?></textarea></td>

    </tr>

	<tr> 

      <td align="right" valign="top" class="tbllogin">Insured Address3</td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><textarea name="insured_address3" id="insured_address3" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" maxlength="40" onKeyPress="$('form').keypress(function(e){

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
                <?php if(count($rsplace) > 0){
                       for($i=0; $i < count($rsplace); $i++){
                ?>
                <option value="<?php echo $rsplace[$i]['place']?>" <?php echo ($state_id == $rsplace[$i]['place'] ? 'selected' : '') ?>><?php echo $rsplace[$i]['place']?></option>

				<?php  }}?>
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

      <td valign="top" align="left"><input name="pin" id="pin" type="text" class="inplogin"  value="<?php echo $pin; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="return keyRestrict(event, '0123456789.')" ></td>

    </tr>



	<tr> 

      <td class="tbllogin" valign="top" align="right">Phone<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="telephone_no" id="telephone_no" type="text" class="inplogin"  value="<?php echo $telephone_no; ?>" maxlength="10" onKeyPress="return restrictCharacters(this,event,numOnly);" ></td>

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

			&nbsp;<br>
			<!--<strong>Others</strong>&nbsp;<input name="occupation_name" id="occupation_name" type="hidden" class="inplogin"  value="<?php //echo $occupation; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"> 
			<input name="occupation_display" id="occupation_display" type="text" class="inplogin"  value="<?php //echo (intval($occupation_id) != 0 ? '' : $occupation); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.occupation_name.value = this.value; document.addForm.occupation.value = '';">-->

			</td>

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

			<!--&nbsp;<strong>Others</strong>&nbsp;

			

			<input name="nominee_relationship_name" id="nominee_relationship_name" type="hidden" class="inplogin"  value="<?php //echo $nominee_relationship; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"> 

			

			

			<input name="nominee_relationship_display" id="nominee_relationship_display" type="text" class="inplogin"  value="<?php //echo (intval($relationship_id) != 0 ? '' : $nominee_relationship); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.nominee_relationship_name.value = this.value; document.addForm.nominee_relationship.value = '';">-->	


					</td>

    </tr>

	<tr> 

      <td class="tbllogin" valign="top" align="right">Nominee DOB<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="nominee_dob" id="nominee_dob" type="text" class="inplogin"  value="<?php if($nominee_dob!="0000-00-00") { echo date("d/m/Y",strtotime($nominee_dob)); } ?>" maxlength="20" readonly onChange="ageCountNew('nominee_dob')"/> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.nominee_dob,'dd/mm/yyyy',this)" width="20" height="18"></td>

    </tr>

	<tr>

	  

	  <td class="tbllogin" valign="top" align="right">Nominee Age<font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

	  <td valign="top" align="left"><input name="nominee_age" id="nominee_age" type="text" class="inplogin"  value="<?php echo $nominee_age; ?>" maxlength="2" onKeyPress="return keyRestrict(event, '0123456789')"/></td>

    </tr>

	



    <tr> 

      <td class="tbllogin" valign="top" align="right">Appointee Name<!--<font color="#ff0000">*</font>--></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="appointee_name" id="appointee_name" type="text" class="inplogin"  value="<?php echo $appointee_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" style="width:220px;"></td>

    </tr>

    
    <tr> 

      <td class="tbllogin" valign="top" align="right">Appointee Relationship<!--<font color="#ff0000">*</font>--></td>

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

			<!--&nbsp;<strong>Others</strong>&nbsp;

			<input name="appointee_relationship_display" id="appointee_relationship_display" type="text" class="inplogin"  value="<?php //echo (intval($appointee_relationship_id) != 0 ? '' : $appointee_relationship); ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.appointee_relationship_type_name.value = this.value">



			<input name="appointee_relationship_name" id="appointee_relationship_name" type="hidden" class="inplogin"  value="<?php //echo $appointee_relationship; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase(); document.addForm.appointee_relationship_display.value = '';">	-->	
           </td>
          <td width="19%"></td>
      </tr>

<tr> 

      <td class="tbllogin" valign="top" align="right">Appointee DOB<!--<font color="#ff0000">*</font>--></td>

      <td class="tbllogin" valign="top" align="center">:</td>
      
      <td valign="top" align="left"><input name="appointee_dob" id="appointee_dob" type="text" class="inplogin"  
      value="<?php if($appointee_dob!="0000-00-00") { echo date("d/m/Y",strtotime($appointee_dob)); }else{echo $appointee_dob="";} ?>" maxlength="20" readonly onChange="ageCountNew('appointee_dob')"/> <!-- <font color="#ff0000">(DD-MM-YYYY)</font> -->&nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.appointee_dob,'dd/mm/yyyy',this)" width="20" height="18"><br><a onClick="clear_input('appointee_dob');" style="cursor:pointer">Clear</a></td>

    </tr>

	<tr>

	  

	  <td class="tbllogin" valign="top" align="right">Appointee Age<!--<font color="#ff0000">*</font>--></td>

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

					<option value="M" <?php echo ($proposer_gender == 'M' ? 'selected' : ''); ?>>Male</option>

					<option value="F" <?php echo ($proposer_gender == 'F' ? 'selected' : ''); ?>>Female</option>

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



	



	<!--<tr> 

      <td class="tbllogin" valign="top" align="right">Office Name </td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="office_name" id="office_name" type="text" class="inplogin"  value="<?php //echo $office_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" style="width:250px;"></td>

    </tr>-->

	<tr> 

      <td class="tbllogin" valign="top" align="right">Nature of Duty <font color="#ff0000">*</font></td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="nature_of_duty" id="nature_of_duty" type="text" class="inplogin"  value="<?php echo $nature_of_duty; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();" style="width:250px;"></td>

    </tr>



	<!--<tr> 

      <td class="tbllogin" valign="top" align="right">Office Address1 </td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><textarea name="office_address1" id="office_address1" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" maxlength="40" onKeyPress="$('form').keypress(function(e){

    if(e.which === 13){

        return false;

    }

});"><?php //echo $office_address1; ?></textarea></td>

    </tr>

	<tr> 

      <td class="tbllogin" valign="top" align="right">Office Address2 </td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><textarea name="office_address2" id="office_address2" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" maxlength="40" onKeyPress="$('form').keypress(function(e){

    if(e.which === 13){

        return false;

    }

});"><?php //echo $office_address2; ?></textarea></td>

    </tr>

	<tr> 

      <td class="tbllogin" valign="top" align="right">Office Address3</td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><textarea name="office_address3" id="office_address3" class="inplogin"  onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){

    if(e.which === 13){

        return false;

    }

});"><?php //echo $office_address3; ?></textarea></td>

    </tr>
  <tr> 

      <td class="tbllogin" valign="top" align="right">Office Phone</td>

      <td class="tbllogin" valign="top" align="center">:</td>

      <td valign="top" align="left"><input name="office_teephone_no" id="office_teephone_no" type="text" class="inplogin"  value="<?php //echo $office_teephone_no; ?>" maxlength="10" onKeyPress="return restrictCharacters(this,event,numOnly);" ></td>

    </tr>-->

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

      <td class="tbllogin" valign="top" align="right">PROPOSER ID Proof<?php  echo $identity_proof;?>

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

						<option value="<?php echo $getIDProof['document_name']; ?>" <?php echo ($getIDProof['document_name'] == $identity_proof ? 'selected' : ''); ?> ><?php echo $getIDProof['document_name']; ?></option>

					<?php		

							}						

						}

					?>

				</select>

				</div>			</td>

    </tr>

    <tr> 

      <td class="tbllogin" valign="top" align="right">PROPOSER Address Proof<font color="#ff0000">*</font></td>

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

						<option value="<?php echo $getAddressProof['document_name']; ?>" <?php echo ($address_proof == $getAddressProof['document_name'] ? 'selected' : ''); ?>><?php echo $getAddressProof['document_name']; ?></option>

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