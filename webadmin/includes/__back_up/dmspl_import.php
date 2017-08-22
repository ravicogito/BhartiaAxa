<?php
######################################################################################################
#
######### THIS PAGE IS USED FOR INSERTING THE DATA SUPPLIED BY RELIANCE TO GTFS_GOLD DATABASE #######
#					ONLY SUPERADMIN CAN ACCESS THIS PAGE FOR SECURITY PURPOSE
#
######################################################################################################
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");

date_default_timezone_set('Asia/Calcutta');

//print_r($_SESSION);

set_time_limit(0); 
ignore_user_abort(true);


$pageOwner = "'superadmin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

$msg = '';

$objDB = new DB();


function find_id_back_through_customer_id($customer_id)
{
	$cust_id = '';
	#echo $customer_id.'<br />';
	$selCustomerID = mysql_query("SELECT id FROM customer_master WHERE customer_id='".realTrim($customer_id)."'");
	echo "SELECT id FROM customer_master WHERE customer_id='".realTrim($customer_id)."'"."<br />";
	$numCustomerID = mysql_num_rows($selCustomerID);
	if($numCustomerID > 0)
	{
		$getCustomerID = mysql_fetch_array($selCustomerID);
		print_r($getCustomerID);
		$cust_id = $getCustomerID['id'];
	}
	#echo $cust_id.'<br />';
	return $cust_id;
} 

/*function clean($input)
{
   $input = trim($input);  
   $input = htmlentities($input, ENT_QUOTES);
   $input = mysql_escape_string($input);
   $input = EscapeShellCmd($input);
   return $input;  
}*/


if(!isset($_SESSION[ADMIN_SESSION_VAR]))
{
	header("location: index.php");
	exit();
}

if(isset($_FILES['zip']) && $_FILES['zip']['tmp_name'] != '')
{
	if(!is_dir('zips/'.$_SESSION[ADMIN_SESSION_VAR]))
	{
		mkdir('zips/'.$_SESSION[ADMIN_SESSION_VAR], 0777);
	}
	//echo 'zips/'.$_SESSION[ADMIN_SESSION_VAR].'HI1<br />';
	move_uploaded_file($_FILES['zip']['tmp_name'], 'zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$_FILES['zip']['name']); // file uploaded
	//echo 'Hi2<br />';
	chmod('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$_FILES['zip']['name'], 0777);

	//echo 'Hi3<br />';

	$filenameArr = explode('.', $_FILES['zip']['name']);
	$filenameWOExtension = $filenameArr[0];

	#### EXTRACTING THE FILE STARTS

	#$zip = new ZipArchive;
	#$res = $zip->open('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$_FILES['zip']['name']);
	#if ($res === TRUE) 
	#{
		#$zip->extractTo('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/');
		#$zip->close();
		$msg = 'Successfully Uploaded';
		if(file_exists('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$filenameWOExtension.'.xml'))
		{
			$filename = $filenameWOExtension.'.xml';
			chmod('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$filename, 0777);
		}
		if(file_exists('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$filenameWOExtension.'.XML'))
		{
			$filename = $filenameWOExtension.'.XML';
			chmod('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$filename, 0777);
		}

		######## DEALING WITH XML DATA STARTS #############

		
		
		//$service_charge_taken = service_status(3);
		//$service_tax_taken = service_status(4);

		
		$xml = simplexml_load_file('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$filename);

		//echo '<pre>';
		//print_r($xml);
		//echo '</pre>';
		//exit;

		$dob = '0000-00-00'; // taken outside of the loop since dob is served by dob_original
		$category = 'INDIVIDUAL';
		$commodity = '1'; // FOR RELIANCE ONLY GOLD WAS SOLD // // removed outside the loop

		$commodity_id = '1'; // FOR RELIANCE ONLY GOLD WAS SOLD // // removed outside the loop

		$product_code = 'AGP-1';
		$product_id = '1';
		$product_name = 'AGP';
		$discount_percentage = 0.00;
		
		$occupation = ''; // NOT FOUND IN XML
		$husbands_name = ''; // NOT FOUND IN XML
		$mothers_maiden_name = ''; // NOT FOUND IN XML
		$annual_income = ''; // NOT FOUND IN XML
		$age_proof = ''; // NOT FOUND IN XML
		$address_proof = ''; // NOT FOUND IN XML
		$id_proof = ''; // NOT FOUND IN XML
		$agent_code = ''; // NOT FOUND IN XML

		foreach($xml->xpath('//Payment') as $payment)

		{

			//echo '<pre>';
			//print_r($payment);
			//echo '</pre>';
			#echo $payment->APPLICATIONFORMNO.'<br />';
			$application_no = isset($payment->APPLICATIONFORMNO) ? realTrim($payment->APPLICATIONFORMNO) : '';

			#echo $payment->DATEOFFILLINGFORM.'<br />';
			#$deposit_date = isset($payment->RECODATE) ? realTrim($payment->RECODATE) : '0000-00-00';
			#$receipt_date = isset($payment->RECODATE) ? realTrim($payment->RECODATE) : '0000-00-00';
			$deposit_date = substr($payment->TRPAD_RECEIPT_DATE, 0, 10);
			//$recodate = isset($payment->RECODATE) ? date('Y-m-d', strtotime($payment->RECODATE)) : '0000-00-00';
			#$deposit_date = $recodate;
			$receipt_date = $deposit_date;
			$first_name = isset($payment->FIRSTNAME) ? realTrim($payment->FIRSTNAME) : '';
			#echo $payment->MIDDLENAME.'<br />';
			$middle_name = isset($payment->MIDDLENAME) ? realTrim($payment->MIDDLENAME) : ''; 
			#echo $payment->LASTNAME.'<br />';
			$last_name = isset($payment->LASTNAME) ? realTrim($payment->LASTNAME) : '';
			#echo $payment->FATHERNAME.'<br />';
			$fathers_name = isset($payment->FATHERNAME) ? realTrim($payment->FATHERNAME) : '';

			$dob_original = isset($payment->DOB) ? realTrim($payment->DOB) : '';
			#echo $payment->GENDER.'<br />';
			$gender = isset($payment->GENDER) ? realTrim($payment->GENDER) : '';
			#print_r($payment->GuardiansName); echo '<br />'; // object
			$guardian_name = isset($payment->GuardiansName) ? realTrim($payment->GuardiansName) : '';
			#echo $payment->AGENTNAME.'<br />';
			$agent_code = isset($payment->AGENTNAME) ? realTrim($payment->AGENTNAME) : '';
			$agent_name = isset($payment->AGENTNAME) ? realTrim($payment->AGENTNAME) : '';
			#echo $payment->ADDRESSLINE1.'<br />';
			$addressline1 = isset($payment->ADDRESSLINE1) ? realTrim($payment->ADDRESSLINE1) : '';
			$addressline2 = isset($payment->ADDRESSLINE2) ? realTrim($payment->ADDRESSLINE2) : '';
			$address1 = $addressline1.' '.$addressline2;
			#echo $payment->ADDRESSLINE2.'<br />';
			#$address2 = $payment->ADDRESSLINE2;
			#echo $payment->CITY.'<br />';
			$city = isset($payment->CITY) ? realTrim($payment->CITY) : '';

			#echo $payment->STATE.'<br />';
			#$state = isset($payment->STATE) ? realTrim($payment->STATE) : ''; 

			#echo $payment->PIN.'<br />';
			$zip = isset($payment->PIN) ? realTrim($payment->PIN) : ''; 
			#echo $payment->MOBILE.'<br />';
			$phone = isset($payment->MOBILE) ? realTrim($payment->MOBILE) : '';
			#print_r($payment->EMAIL); echo '<br />'; // object
			$email = isset($payment->EMAIL) ? realTrim($payment->EMAIL) : '';
			#print_r($payment->PAN); echo '<br />'; // object
			$pan = isset($payment->PAN) ? realTrim($payment->PAN) : '';
			#echo $payment->NOMINEENAME.'<br />';
			$nominee_name = isset($payment->NOMINEENAME) ? realTrim($payment->NOMINEENAME) : '';
			#echo $payment->RELEATIONSHIPTYPE.'<br />';
			$relationship_type = isset($payment->RELEATIONSHIPTYPE) ? realTrim($payment->RELEATIONSHIPTYPE) : '';
			$NOMINEEADDRESSLINE1 = isset($payment->NOMINEEADDRESSLINE1) ? realTrim($payment->NOMINEEADDRESSLINE1) : '';
			$NOMINEEADDRESSLINE2 = isset($payment->NOMINEEADDRESSLINE2) ? realTrim($payment->NOMINEEADDRESSLINE2) : '';
			
			$NOMINEECITY = isset($payment->NOMINEECITY) ? realTrim($payment->NOMINEECITY) : '';
			$NOMINEESTATE = isset($payment->NOMINEESTATE) ? realTrim($payment->NOMINEESTATE) : '';
			$NOMINEE_PIN = isset($payment->NOMINEE_PIN) ? realTrim($payment->NOMINEE_PIN) : '';
			$nominee_address = $NOMINEEADDRESSLINE1.' '.$NOMINEEADDRESSLINE2.' '.$NOMINEECITY.' '.$NOMINEESTATE.' '.$NOMINEE_PIN;
			#echo $payment->NOMINEEDOB.'<br />';
			$nominee_dob = isset($payment->NOMINEEDOB) ? date('Y-m-d', strtotime($payment->NOMINEEDOB)) : '0000-00-00'; // Field required varchar type
			#print_r($payment->NOMINEEGUARDIANNAME); echo '<br />'; // object
			$appointee_name = isset($payment->NOMINEEGUARDIANNAME) ? realTrim($payment->NOMINEEGUARDIANNAME) : ''; // Field required varchar type
			$appointee_relationship = isset($payment->APPOINTEERELATIONSHIP) ? realTrim($payment->APPOINTEERELATIONSHIP) : ''; // Field required varchar type
			#echo $payment->NOMINEEADDRESSLINE1.'<br />';
			$nominee_address1 = $nominee_address; 
			
			//$OUTLETCODE = str_replace('DMSPL','AJL',$payment->OUTLETCODE);
			//$payment->OUTLETCODE = $OUTLETCODE;

			$branch_code = isset($payment->OUTLETCODE) ? realTrim(str_replace('DMSPL','AJL',$payment->OUTLETCODE)) : ''; 	
			
			//echo '123';
			$state_id = find_state_id_through_branch_code($branch_code); ////////// ALTERATION REQUIRED // Calculate state through branch name and branch admin
			
			$branch_name = find_branch_id_through_code(realTrim($branch_code)); // this is branch id 
			
			//$category = isset($payment->CATEGORY) ? realTrim($payment->CATEGORY) : 'INDIVIDUAL'; // removed outside the loop

			$folio_no = $application_no; 
			#echo $payment->TENURE.'<br />';
			$tenure = isset($payment->TENURE) ? realTrim($payment->TENURE) : '';

			//$commodity_id = '1'; // FOR RELIANCE ONLY GOLD WAS SOLD // // removed outside the loop

			$bonus_percentage = '0.00'; // it is tenure dependent // ALTERATION REQUIRED // will be done later after uploading all the data

			#echo $payment->CommitedAdvance.'<br />';
			$committed_amount = isset($payment->CommitedAdvance) ? realTrim($payment->CommitedAdvance) : '';
			
			$payment_number = find_payment_number($application_no); // this variable contains how many times this customer paid some money to the company against an application number 

			#echo $payment->RECEIPT_NO.'<br />';
			$receipt_number = isset($payment->RECEIPT_NO) ? realTrim($payment->RECEIPT_NO) : ''; 
			$transaction_id = $folio_no.'/'.($payment_number + 1).'/'.date('d/m'); //////////

			
			#echo $payment->PaymentType.'<br />'; // cash, cheque etc
			$payment_mode = isset($payment->PaymentType) ? realTrim($payment->PaymentType) : ''; 
			#echo $payment->AMOUNT.'<br />';
			$amount = isset($payment->AMOUNT) ? realTrim($payment->AMOUNT) : 0.00;

			$payment_type = isset($payment->RECEIPT_TYPE) ? realTrim($payment->RECEIPT_TYPE) : ''; // if it is initial payment create new customer and new folio no 

			#echo $payment->CUSTOMER_ID.'<br />';
			$customer_id = 'AGP/'.$branch_code.'/'.$application_no; // ALTERATION REQUIRD customer ID -> PLAN NAME/AJL Code/application No
			#echo $payment->INSTALLMENT.'<br />';
			$installment = isset($payment->INSTALLMENT) ? realTrim($payment->INSTALLMENT) : '';
			//$bonus_percentage = isset($payment->BONUSPERCENTAGE) ? realTrim($payment->BONUSPERCENTAGE) : 0.00; // Moved outside to the loop
			$start_date = $deposit_date; // Required only for the FIRST PREMIUM
			$end_date = date('Y-m-d',strtotime($deposit_date.'+'.$tenure.' months')); ////////// ALTERATION REQUIRED .// calculate through the tenure

			$gold_gram = isset($payment->GOLDGRAMS) ? realTrim($payment->GOLDGRAMS) : '0.0000';
			$gold_rate = isset($payment->GOLDRATE) ? realTrim($payment->GOLDRATE) : '0.0000';
			
			

			###############################################

			// CHECKING WHETHER THIS customer_id ALREADY EXISTS OR NOT
			
			$lastCustID = find_id_back_through_customer_id($customer_id);

			echo 'last cust id = '.$lastCustID.'<br />';

			if($lastCustID == '')
			{
				// INSERTING DATA INTO customer_master TABLE
			$firstInsert = "INSERT INTO customer_master SET
					customer_id = '".$customer_id."',
					branch_id = '".$branch_name."',
					first_name = '".$first_name."',
					middle_name = '".$middle_name."',
					last_name = '".$last_name."',
					total_premium_given = '0',
					is_active = 'Y',
					account_created_date = '".$deposit_date."',
					gender = '".$gender."',
					dob_original = '".$dob_original."',
					fathers_name = '".$fathers_name."',
					husbands_name = '".$husbands_name."',
					mothers_maiden_name = '".$mothers_maiden_name."',
					guardian_name  = '".$guardian_name."',
					address1 = '".$address1."',
					state = '".$state_id."',
					city = '".$city."',
					zip = '".$zip."',
					phone = '".$phone."',
					email = '".$email."',
					pan = '".$pan."',
					annual_income = '".$annual_income."',
					occupation = '".$occupation."'
			";
			echo $firstInsert.'<br />';
			mysql_query($firstInsert);
			$lastCustID = mysql_insert_id();
			//$branch_code = find_branch_code($branch_name); // branch_name is actually branch_id
			}

			// CUSTOMER CREATED

			// CHECKING WHETHER THIS folio_number ALREADY EXISTS OR NOT

			$folio_id = find_folio_id($folio_no);
			if($folio_id == '')
			{
				$firstFolioInsert = "INSERT INTO customer_folio_no SET
					customer_id = '".$lastCustID."',
					folio_no = '".$folio_no."',
					application_no = '".$application_no."',
					commodity_name = '".$commodity_id."',
					committed_amount = '".$committed_amount."',
					tenure = '".$tenure."',
					bonus_percentage = '".$bonus_percentage."',
					start_date = '".$start_date."',
					end_date = '".$end_date."',
					product_id = '".$product_id."',
					product_code = '".$product_code."',
					product_name = '".$product_name."',
					discount_percentage = '".$discount_percentage."',
					total_premium_given = '".$installment."', 
					nominee_name  = '".$nominee_name."',
					nominee_address = '".$nominee_address."',
					nominee_dob = '".$nominee_dob."',
					nominee_relationship = '".$relationship_type."',
					appointee_name = '".$appointee_name."',
					appointee_relationship = '".$appointee_relationship."'
			";
			#echo $firstInsert.'<br />';
			mysql_query($firstFolioInsert);
			$folio_id = mysql_insert_id();
			}

			// FOLIO NO CREATED


			/////////////////////////////////////

			$preimum_given = find_premium_number($folio_id);
			
			//$NOPFTT = $amount / $committed_amount; // $NOPFTT = number of premium for this transaction
			//echo $NOPFTT.'<br />';

			//if(intval($service_charge_taken) == 1)
			//{
				//$serviceChargeInfoArray = findServiceChargeInfo($tenure);
				//$service_charge_percentage = $serviceChargeInfoArray['service_charge_percentage'];
				//$service_charge_installments = $serviceChargeInfoArray['service_charge_installments'];
			//}

			//if(intval($service_tax_taken) == 1)
			//{
				//$service_tax_percentage = find_service_tax_percentage($state_id);
			//}


			//$service_charge_premium = $NOPFTT; // For this no of premium service charge will be given

			//if(intval($preimum_given + $NOPFTT) >= intval($service_charge_installments) )
			//{
				//$service_charge_premium = intval($service_charge_installments) - $preimum_given;
			//}

			//if(intval($service_charge_premium) < 0) // to avoid negetive service charge when all the service chrgeable premium has been made 
			//{
				//$service_charge_premium = 0;
			//}
			
			//$service_charge_amount = floatval($committed_amount) * $service_charge_premium * ($service_charge_percentage / 100) * ($tenure / $service_charge_installments);

			//$service_tax_amount = $service_charge_amount * $service_tax_percentage * .01;

			################################################


			#echo '<br /><br /><br /><br /><br /><br />';

			$find_old_record = mysql_query("SELECT COUNT(id) AS mycount FROM installment_master WHERE receipt_number='".$receipt_number."' AND is_deleted=0");
			$num_old_record = mysql_fetch_array($find_old_record);
			$old_record = $num_old_record['mycount'];
			echo 'OLD RECORD IS '.$old_record.'<br />';

			if(intval($old_record) == 0)
			{
				// INSERTION OF CUSTOMER ID IN THE customer_master TABLE IS REQUIRED
				
				// insert query
				$insert_installment = "INSERT INTO installment_master SET 
										branch_id = '".$branch_name."',
										application_no = '".$application_no."',										
										customer_id = '".$lastCustID."',
										folio_no_id = '".$folio_id."',
										deposit_date = '".$deposit_date."',
										migrated_from_dmspl = '1',
										agent_code = '".$agent_code."',
										agent_name = '".$agent_name."',
										receipt_number = '".$receipt_number."',
										transaction_id = '".$transaction_id."',
										payment_mode = '".$payment_mode."',
										amount = '".floatval($amount)."',
										transaction_charges = '0.00',
										payment_type = '".$payment_type."',
										gold_gram	 = '".$gold_gram."',
										gold_rate = '".$gold_rate."',
										dd_number	='',
										dd_date = '0000-00-00',
										dd_bank_name = '',
										ifs_code = '',
										micr_code = '',
										installment = '".intval($amount / $tenure)."'
										
				";

				echo $insert_installment.'<br />';
				mysql_query($insert_installment);

				//$total_premium_after_transaction = intval($preimum_given + $installment); 

				mysql_query("UPDATE customer_folio_no SET total_premium_given='".$installment."' WHERE id = '".$folio_id."'");
				echo "UPDATE customer_folio_no SET total_premium_given='".$installment."' WHERE id = '".$folio_id."'".'<br />';
				
			}
			else
			{
				// DO NOTHING
				
				// update query
				//$insert_installment = "UPDATE installment_master SET 
										//branch_id = '".$branch_name."',
										//application_no = '".$application_no."',										
										//customer_id = '".$lastCustID."',
										//folio_no_id = '".$folio_id."',
										//deposit_date = '".$deposit_date."',
										//state = '".$state_id."',
										//payment_mode = '".$payment_mode."',
										//service_charge = '".$service_charge_amount."',
										
										
										//payment_type = '".$payment_type."',
										//category = '".$category."',
										
										
										//WHERE 
										//receipt_number='".$receipt_number."'
				//";

				#echo $insert_installment.'<br />';
				//mysql_query($insert_installment);



			}




			//$product_name = clean($products->product_name);

			//mysql_unbuffered_query("INSERT INTO mytable (onsaledate, onsaletime, eventdate, eventtime, buyat_short_deeplink_url, product_name, level1, level2, VenueName, VenueDMAID)VALUES (\"$products->OnsaleDate\",\"$products->OnsaleTime\",\"$products->EventDate\",\"$products->EventTime\",\"$products->buyat_short_deeplink_url\",\"$product_name\",\"$products->level1\",\"$products->level2\",\"$products->VenueName\",\"$products->VenueDMAID\")") or die(mysql_error());

			


		}


		######## DEALING WITH XML DATA ENDS ###############

		unlink('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$filename);
	#}
	#else
	#{
		#$msg = 'Failed to Extract the ZIP file';
	#}

	#### EXTRACTING THE FILE ENDS

	if(file_exists('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$_FILES['zip']['name']))
	{
		unlink('zips/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$_FILES['zip']['name']);
	}
}

// Write functions here


//$selIDProof = mysql_query("SELECT id, document_name FROM id_proof ORDER BY document_name ASC ");
//$numIDProof = mysql_num_rows($selIDProof);

//$selAgeProof = mysql_query("SELECT id, document_name FROM age_proof ORDER BY document_name ASC ");
//$numAgeProof = mysql_num_rows($selAgeProof);

//$selAddressProof = mysql_query("SELECT id, document_name FROM address_proof ORDER BY document_name ASC ");
//$numAddressProof = mysql_num_rows($selAddressProof);


//$id = $_SESSION[ADMIN_SESSION_VAR];


?>


<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
<script type="text/javascript">
<!--
		
	function dochk()
	{
		/*if(document.addForm.zip.value.search(/\S/) == -1)
		{
			alert("Please select the zip file");
			document.addForm.zip.focus();
			return false;
		}
		if(!chkzip(document.addForm.zip.value))
		{
			alert("Invalid file format");
			document.addForm.zip.focus();
			return false;
		}*/
		if(document.addForm.zip.value.search(/\S/) == -1)
		{
			alert("Please select the xml file");
			document.addForm.zip.focus();
			return false;
		}
		if(!chkxml(document.addForm.zip.value))
		{
			alert("Invalid file format");
			document.addForm.zip.focus();
			return false;
		}

	}
//-->
</script>

<form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()" enctype="multipart/form-data">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>
				<?= $msg;?>
      </td>
    </tr>
		<?php 
			$service_status = service_status(2);
			if($service_status == 0)
			{
		?>
		<tr> 
      <td colspan="3" >
       <marquee>
				<h1 style="color:#ff0000;">This facility is currently deactivated by the administrator. If you want to add any record manually please <a href="<?= URL; ?>webadmin/index.php?p=manual_entry_branch" style="font-size:18px;">click here</a>.
				</h1>
			 </marquee>
      </td>
    </tr>
		<?php 
			exit; 
			}
		?>
    <tr class="TDHEAD"> 
      <td colspan="3">Upload XML<!-- Upload ZIP of the XML --></td>
    </tr>

		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left">With the help of this page a branch can upload the XML file generated by the Reliance Money System. This is the substitution for the Manual Entry. <br />One can insert/update data for a whole day within a few seconds by using this page. <br /><br />
			<!-- <b>Steps required to upload the XML data : <br /><br /></b>
			1. Create ZIP of the xml file supplied by Reliance Money System.<br />
			2. Do not rename the ZIP file.<br />
			3. Upload the ZIP.<br />
			4. Click on the Add Button.<br />
			5. Wait for the success message.<br /> -->
			</td>
    </tr>

    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
        * marked fields are mandatory</font></b></td>
    </tr>
		
		<input type="hidden" name="branch_name" id="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
		<!-- <tr> 
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
    </tr> -->
		<tr> 
      <td class="tbllogin" valign="top" align="right">Upload XML <!-- Upload Zip (Reliance Money System) --><font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="zip" id="zip" type="file" class="inplogin" ></td>
    </tr>
    

    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Add" class="inplogin" type="submit" onclick="return dochk()"> 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
    </tr>
  </tbody>
</table>

<?php //$objDB->close(); ?>







<?php
		 //$zip = new ZipArchive;
     //$res = $zip->open('zips/payment_list.zip');
     //if ($res === TRUE) {
         //$zip->extractTo('zips/');
         //$zip->close();
        // echo 'ok';
    // } else {
        // echo 'failed';
    // }
?> 