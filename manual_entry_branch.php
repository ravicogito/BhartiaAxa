<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");include_once("new_functions.php");include_once("Excel/reader.php");

date_default_timezone_set('Asia/Calcutta');
$msg = '';
//$PREMIUM_TYPE = 'INITIAL PAYMENT';

$objDB = new DB();

$pageOwner = "'branch','admin11','superadmin'";
$role_id=$_SESSION[ROLE_ID];
chkPageAccess($role_id, $pageOwner); 

//echo 'SITE IS UNDER MAINTENANCE';
#exit;

if(isset($_SESSION['error_msg']) && $_SESSION['error_msg']!="")
{
	$msg=$_SESSION['error_msg'];
	unset($_SESSION['error_msg']);
}


#$selTenure = mysql_query("SELECT id, tenure FROM tenure_master WHERE status=1 ORDER BY tenure ASC "); //for Term dropdown
#$numTenure = mysql_num_rows($selTenure);
if(isset($_POST['submit']) && $_POST['submit']!='')
{

//$phase=trim($_POST['phase']);
$branch=trim($_POST['branch']);
//$agent_code=trim($_POST['agent_code']);

//$upload_xml=$_POST['upload_xml'];

/*if($phase=="")
{
$msg="Please Enter Phase.";
}
else if($branch=="")
{
$msg="Please Enter Branch.";
}
else if($agent_code=="")
{
$msg="Please Enter Agent Code.";
}*///print_r($_FILES);exit;
if($_FILES["upload_xls"]["name"]=="")
{
		$msg="Please Upload XLS File.";
}
else 
{
	
	$storagename = $_FILES["upload_xls"]["name"];		
	move_uploaded_file($_FILES["upload_xls"]["tmp_name"], "../upload/" .$storagename);
	$upload_file = "../upload/" . $storagename;	//echo $upload_file;exit;
}


//------------- start for get hub id -------------//

$set_hub = "SELECT hub_id FROM branch_hub_entry WHERE branch_id = '".$branch."' order by hub_since desc LIMIT 0,1";
$set_hub_data = mysql_query($set_hub);
$get_hub = mysql_fetch_array($set_hub_data);

$hub_id=$get_hub['hub_id'];

//------------- end for get hub id --------------//


if($msg=="")
{
	
			$c = 1;
			//$xml=simplexml_load_file($_FILES["upload_xml"]["tmp_name"]);
			//ini_set('max_execution_time', 4800); //80 minutes			
			$data = new Spreadsheet_Excel_Reader();			
			$data->setOutputEncoding("UTF-8");			
			$data->read($upload_file);
			$total_data = $data->sheets[0][numRows]-1;
			//echo"<pre>";print_r($data);echo"</pre>";exit;

			//if($_REQUEST['csv']=="read") {
			
			//for ($j = 1; $j <= $data->sheets[0][numRows]; $j++){
			$duplicate_application_no_list=array();
			for ($i = 1; $i <= $data->sheets[0][numRows]-1; $i++) {
				
				if (array_key_exists("7",$data->sheets[0]['cells'][$i+1]))
			{
			 $application_no =  $data->sheets[0]['cells'][$i+1][7];
			 if(empty($application_no)){	
			 $application_no 				= '';
			 }else{	
				$application_no 					= $application_no;
				if(empty($duplicate_application_no_list))
				{
					$duplicate_application_no_list[] = $application_no;
					
				}
				else{
					if(in_array($application_no, $duplicate_application_no_list))
					{
							
					?>						
						<script>
								
								var res;
								var x = confirm("Duplicate application <?php echo $application_no; ?> do you want to enter it?");
								alert(x);
								if(x==true){
									alert("condition works");		
									$.post("<?php echo URL .'webadmin/includes/ajax_insert_uploaded_file.php'?>",{okay:1,file:'<?php echo $upload_file; ?>'},function(data){
										
										console.log(data);
										//alert(data);
										//alert("Successfully uploaded the data");
										//window.location.href= "<?php echo URL.'webadmin/index.php?p=transaction_list_branch'?>";
									});
								} else{
									alert("As you click cancel ,no data uploaded");	
									window.location.href= "<?php echo URL.'webadmin/index.php?p=manual_entry_branch'?>";
								}

						</script>			
					<?php 						
					}
					else{
						$duplicate_application_no_list[] = $application_no;						
						
					}
					
				}
				$c++;
			 }
			}
			else{
			$application_no 				=	'';  
			}	
				
			}
			if($total_data == $c){
				
				for ($j = 1; $j <= $data->sheets[0][numRows]-1; $j++) 
				{
				 $BRANCH_CODE 						=	$data->sheets[0]['cells'][$j+1][1];
				 if (array_key_exists("1",$data->sheets[0]['cells'][$j+1]))
				  {
					 $BRANCH_CODE =  $data->sheets[0]['cells'][$j+1][1];
					 if(empty($BRANCH_CODE)){	
					 $BRANCH_CODE				= '';
					 }else{	
						$BRANCH_CODE  				= $BRANCH_CODE;	
						$sql_bnch					= mysql_query("select id from admin where branch_code = '" .$BRANCH_CODE."'");
						$row=mysql_fetch_array($sql_bnch);
						$branch						= $row['id'];
					 }	
				  }
				  else{
					$BRANCH_CODE 				=	'';  
				  }
					 
				 
				 if (array_key_exists("2",$data->sheets[0]['cells'][$j+1]))
				  {
					 $ASTHA_BRANCH_NAME =  $data->sheets[0]['cells'][$j+1][2];
					 if(empty($ASTHA_BRANCH_NAME)){	
					 $ASTHA_BRANCH_NAME 				= '';
					 }else{	
					 $ASTHA_BRANCH_NAME 				= $ASTHA_BRANCH_NAME;	
					 }	
				  }
				  else{
					$ASTHA_BRANCH_NAME 				=	'';  
				  }
				 
				  if (array_key_exists("3",$data->sheets[0]['cells'][$j+1]))
				  {
					 $BRANCH_SUB_CODE =  $data->sheets[0]['cells'][$j+1][3];
					 if(empty($BRANCH_SUB_CODE)){	
					 $BRANCH_SUB_CODE 				= '0';
					 }else{	
					 $BRANCH_SUB_CODE 				= $BRANCH_SUB_CODE;
					 }	
				  }
				  else{
					$BRANCH_SUB_CODE 				=	'0';  
				  }
				  
				  if (array_key_exists("4",$data->sheets[0]['cells'][$j+1]))
				  {
					 $business_date =  $data->sheets[0]['cells'][$j+1][4];
					 if(empty($business_date)){	
					 $business_date 				= '0000-00-00';
					 }else{	
					 $business_date 				= $business_date;	
					 }	
				  }
				  else{
					$business_date 				=	'0000-00-00';  
				  }
				  
				  if (array_key_exists("5",$data->sheets[0]['cells'][$j+1]))
				  {
					 $POLICY_NO =  $data->sheets[0]['cells'][$j+1][5];
					 if(empty($POLICY_NO)){	
					 $POLICY_NO 				= '0';
					 }else{	
					 $POLICY_NO 				= $POLICY_NO;	
					 }	
				  }
				  else{
					$POLICY_NO 				=	'0';  
				  }
				  
				  if (array_key_exists("6",$data->sheets[0]['cells'][$j+1]))
				  {
					 $QUOTE_NO =  $data->sheets[0]['cells'][$j+1][6];
					 if(empty($QUOTE_NO)){	
					 $QUOTE_NO 				= '0';
					 }else{	
					 $QUOTE_NO 				= $QUOTE_NO;	
					 }	
				  }
				  else{
					$QUOTE_NO 				=	'0';  
				  }
				 
				 if (array_key_exists("7",$data->sheets[0]['cells'][$j+1]))
				  {
					 $application_no =  $data->sheets[0]['cells'][$j+1][7];
					 if(empty($application_no)){	
					 $application_no 				= '';
					 }else{	
						$application_no 					= $application_no;
						if(empty($duplicate_application_no_list))
						{
							$duplicate_application_no_list[] = $application_no;
						}
						else{
								
						}
					 }	
				  }
				  else{
					$application_no 				=	'';  
				  }
				   
				  if (array_key_exists("8",$data->sheets[0]['cells'][$j+1]))
				  {
					 $type_of_business =  $data->sheets[0]['cells'][$j+1][8];
					 if(empty($type_of_business)){	
					 $type_of_business 				= '';
					 }else{	
					 $type_of_business 				= $type_of_business;	
					 }	
				  }
				  else{
					$type_of_business 				=	'';  
				  }
				  
				  if (array_key_exists("9",$data->sheets[0]['cells'][$j+1]))
				  {
					 $PROPOSER_NAME =  $data->sheets[0]['cells'][$j+1][9];
					 if(empty($PROPOSER_NAME)){	
					 $PROPOSER_NAME 				= '';
					 }else{	
					 $PROPOSER_NAME 				= $PROPOSER_NAME;	
					 }	
				  }
				  else{
					$PROPOSER_NAME 				=	'';  
				  }
				  
				  if (array_key_exists("10",$data->sheets[0]['cells'][$j+1]))
				  {
					 $PROPOSER_DOB =  $data->sheets[0]['cells'][$j+1][10];
					 if(empty($PROPOSER_DOB)){	
					 $PROPOSER_DOB 				= '0000-00-00';
					 }else{	
					 $PROPOSER_DOB 				=$PROPOSER_DOB;	
					 }	
				  }
				  else{
					$PROPOSER_DOB 				=	'0000-00-00';  
				  }
				  
				  if (array_key_exists("11",$data->sheets[0]['cells'][$j+1]))
				  {
					 $PROPOSER_GENDER =  $data->sheets[0]['cells'][$j+1][11];
					 if(empty($PROPOSER_GENDER)){	
					 $PROPOSER_GENDER 				= '';
					 }else{	
					 $PROPOSER_GENDER 				= $PROPOSER_GENDER;	
					 }	
				  }
				  else{
					$PROPOSER_GENDER 				='';  
				  }
				  
				  if (array_key_exists("12",$data->sheets[0]['cells'][$j+1]))
				  {
					 $PROPOSER_AGE_PROOF =  $data->sheets[0]['cells'][$j+1][12];
					 if(empty($PROPOSER_AGE_PROOF)){	
					 $PROPOSER_AGE_PROOF 				= '';
					 }else{	
					 $PROPOSER_AGE_PROOF 				= $PROPOSER_AGE_PROOF;	
					 }	
				  }
				  else{
					$PROPOSER_AGE_PROOF 				='';  
				  }
				  
				   if (array_key_exists("13",$data->sheets[0]['cells'][$j+1]))
				  {
					 $insured_name =  $data->sheets[0]['cells'][$j+1][13];
					 if(empty($insured_name)){	
					 $insured_name 				= '';
					 }else{	
					 $insured_name 				= $insured_name;	
					 }	
				  }
				  else{
					$insured_name 				='';  
				  }
				  
				   if (array_key_exists("14",$data->sheets[0]['cells'][$j+1]))
				  {
					 $insured_dob =  $data->sheets[0]['cells'][$j+1][14];
					 if(empty($insured_dob)){	
					 $insured_dob 				= '0000-00-00';
					 }else{	
					 $insured_dob 				= $insured_dob;	
					 }	
				  }
				  else{
					$insured_dob 				='0000-00-00';  
				  }
				  
				  
				  
				  if (array_key_exists("15",$data->sheets[0]['cells'][$j+1]))
				  {
					 $insured_gender =  $data->sheets[0]['cells'][$j+1][15];
					 if(empty($insured_gender)){	
					 $insured_gender 				= '';
					 }else{	
					 $insured_gender 				= $insured_gender;	
					 }	
				  }
				  else{
					$insured_gender 				='';  
				  }
				  
				  if (array_key_exists("16",$data->sheets[0]['cells'][$j+1]))
				  {
					 $insured_age_proof =  $data->sheets[0]['cells'][$j+1][16];
					 if(empty($insured_age_proof)){	
					 $insured_age_proof 				= '';
					 }else{	
					 $insured_age_proof 				= $insured_age_proof;	
					 }	
				  }
				  else{
					$insured_age_proof 				='';  
				  }
				  
				  if (array_key_exists("17",$data->sheets[0]['cells'][$j+1]))
				  {
					 $plan_name =  $data->sheets[0]['cells'][$j+1][17];
					 if(empty($plan_name)){	
					 $plan_name 				= '';
					 }else{	
					$plan_name 				= $plan_name;	
					 }	
				  }
				  else{
					$plan_name 				='';  
				  }
				  
				  if (array_key_exists("18",$data->sheets[0]['cells'][$j+1]))
				  {
					 $term =  $data->sheets[0]['cells'][$j+1][18];
					 if(empty($term)){	
					 $term				= '';
					 }else{	
					$term 				= $term;	
					 }	
				  }
				  else{
					$term 				='';  
				  }
				  
				  if (array_key_exists("19",$data->sheets[0]['cells'][$j+1]))
				  {
					 $premium_paying_term =  $data->sheets[0]['cells'][$j+1][19];
					 if(empty($premium_paying_term)){	
					 $premium_paying_term				= '';
					 }else{	
					$premium_paying_term				=$premium_paying_term;	
					 }	
				  }
				  else{
					$premium_paying_term 				='';  
				  }
				  
				   if (array_key_exists("20",$data->sheets[0]['cells'][$j+1]))
				  {
					 $frequency =  $data->sheets[0]['cells'][$j+1][20];
					 if(empty($frequency)){	
					 $frequency				= '';
					 }else{	
					$frequency				=$frequency;	
					 }	
				  }
				  else{
					$frequency 				='';  
				  }
				  
				   if (array_key_exists("21",$data->sheets[0]['cells'][$j+1]))
				  {
					 $sum_asured =  $data->sheets[0]['cells'][$j+1][21];
					 if(empty($sum_asured)){	
					 $sum_asured				= '';
					 }else{	
					$sum_asured				=$sum_asured;	
					 }	
				  }
				  else{
					$sum_asured 				='';  
				  }
				  
				  if (array_key_exists("22",$data->sheets[0]['cells'][$j+1]))
				  {
					 $PAYMENT_MODE =  $data->sheets[0]['cells'][$j+1][22];
					 if(empty($PAYMENT_MODE)){	
					 $PAYMENT_MODE				= '';
					 }else{	
					$PAYMENT_MODE				=$PAYMENT_MODE;	
					 }	
				  }
				  else{
					$PAYMENT_MODE 				='';  
				  }
				  
				  if (array_key_exists("23",$data->sheets[0]['cells'][$j+1]))
				  {
					 $cash_money_receipt =  $data->sheets[0]['cells'][$j+1][23];
					 if(empty($cash_money_receipt)|| $cash_money_receipt==0){	
					 $cash_money_receipt				= '0.00';
					 }else{	
					 $cash_money_receipt				=$cash_money_receipt;	
					 }	
				  }
				  else{
					$cash_money_receipt 				='0.00';  
				  }
				  
				  if (array_key_exists("24",$data->sheets[0]['cells'][$j+1]))
				  {
					 $cheque_money_receipt =  $data->sheets[0]['cells'][$j+1][24];
					 if(empty($cheque_money_receipt)|| $cheque_money_receipt==0){	
					 $cheque_money_receipt				= '0.00';
					 }else{	
					 $cheque_money_receipt				=$cheque_money_receipt;	
					 }	
				  }
				  else{
					$cheque_money_receipt 				='0.00';  
				  }
				  
				  if (array_key_exists("25",$data->sheets[0]['cells'][$j+1]))
				  {
					 $receive_cash =  $data->sheets[0]['cells'][$j+1][25];
					 if(empty($receive_cash)){	
					 $receive_cash				= '0.00';
					 }else{	
					 $receive_cash				=$receive_cash;	
					 }	
				  }
				  else{
					$receive_cash 				='0.00';  
				  }
				  
				  if (array_key_exists("26",$data->sheets[0]['cells'][$j+1]))
				  {
					 $receive_cheque =  $data->sheets[0]['cells'][$j+1][26];
					 if(empty($receive_cheque)){	
					 $receive_cheque			= '0.00';
					 }else{	
					 $receive_cheque				=$receive_cheque;	
					 }	
				  }
				  else{
					$receive_cheque 				='0.00';  
				  }
				  
				   if (array_key_exists("27",$data->sheets[0]['cells'][$j+1]))
				  {
					 $cheque_no =  $data->sheets[0]['cells'][$j+1][27];
					 if(empty($cheque_no)){	
					 $cheque_no			= '0';
					 }else{	
					 $cheque_no			=$cheque_no;	
					 }	
				  }
				  else{
					$cheque_no 			='0';  
				  }
				  
				  if (array_key_exists("28",$data->sheets[0]['cells'][$j+1]))
				  {
					 $cheque_date =  $data->sheets[0]['cells'][$j+1][28];
					 if(empty($cheque_date)){	
					 $cheque_date			= '0000-00-00';
					 }else{	
					 $cheque_date		=$cheque_date;	
					 }	
				  }
				  else{
					$cheque_date 	   ='0000-00-00';  
				  }
				 
				 if (array_key_exists("29",$data->sheets[0]['cells'][$j+1]))
				  {
					 $cheque_bank_name =  $data->sheets[0]['cells'][$j+1][29];
					 if(empty($cheque_bank_name)){	
					 $cheque_bank_name			= '';
					 }else{	
					 $cheque_bank_name		=$cheque_bank_name;	
					 }	
				  }
				  else{
					$cheque_bank_name 	   ='';  
				  }
				  
				  if (array_key_exists("30",$data->sheets[0]['cells'][$j+1]))
				  {
					 $cheque_branch_name =  $data->sheets[0]['cells'][$j+1][30];
					 if(empty($cheque_branch_name)){	
					 $cheque_branch_name			= '';
					 }else{	
					 $cheque_branch_name		=$cheque_branch_name;	
					 }	
				  }
				  else{
					$cheque_branch_name 	   ='';  
				  }
				  
				  if (array_key_exists("38",$data->sheets[0]['cells'][$j+1]))
				  {
					 $FATHER_NAME =  $data->sheets[0]['cells'][$j+1][38];
					 if(empty($FATHER_NAME)){	
					 $FATHER_NAME			= '';
					 }else{	
					 $FATHER_NAME		=$FATHER_NAME;	
					 }	
				  }
				  else{
					$FATHER_NAME 	   ='';  
				  }
				  
				  if (array_key_exists("39",$data->sheets[0]['cells'][$j+1]))
				  {
					 $nominee_name =  $data->sheets[0]['cells'][$j+1][39];
					 if(empty($nominee_name)){	
					 $nominee_name			= '';
					 }else{	
					 $nominee_name		=$nominee_name;	
					 }	
				  }
				  else{
					$nominee_name 	   ='';  
				  }
				  
				  if (array_key_exists("40",$data->sheets[0]['cells'][$j+1]))
				  {
					 $nominee_dob =  $data->sheets[0]['cells'][$j+1][40];
					 if(empty($nominee_dob)){	
					 $nominee_dob			= '0000-00-00';
					 }else{	
					 $nominee_dob		=$nominee_dob;	
					 }	
				  }
				  else{
					$nominee_dob 	   ='0000-00-00';  
				  }
				  
				  if (array_key_exists("41",$data->sheets[0]['cells'][$j+1]))
				  {
					 $nominee_relationship =  $data->sheets[0]['cells'][$j+1][41];
					 if(empty($nominee_relationship)){	
					 $nominee_relationship			= '';
					 }else{	
					 $nominee_relationship		=$nominee_relationship;	
					 }	
				  }
				  else{
					$nominee_relationship 	   ='';  
				  }
				  
				  if (array_key_exists("42",$data->sheets[0]['cells'][$j+1]))
				  {
					 $appointee_name =  $data->sheets[0]['cells'][$j+1][42];
					 if(empty($appointee_name)){	
					 $appointee_name			= '';
					 }else{	
					 $appointee_name		=$appointee_name;	
					 }	
				  }
				  else{
					$appointee_name 	   ='';  
				  }
				  
				  if (array_key_exists("43",$data->sheets[0]['cells'][$j+1]))
				  {
					 $appointee_dob =  $data->sheets[0]['cells'][$j+1][43];
					 if(empty($appointee_dob)){	
					 $appointee_dob			= '0000-00-00';
					 }else{	
					 $appointee_dob		=$appointee_dob;	
					 }	
				  }
				  else{
					$appointee_dob 	   ='0000-00-00';  
				  }
				  
				  if (array_key_exists("44",$data->sheets[0]['cells'][$j+1]))
				  {
					 $appointee_relationship =  $data->sheets[0]['cells'][$j+1][44];
					 if(empty($appointee_relationship)){	
					 $appointee_relationship			= '';
					 }else{	
					 $appointee_relationship		=$appointee_relationship;	
					 }	
				  }
				  else{
					$appointee_relationship 	   ='';  
				  }
				  
				  if (array_key_exists("45",$data->sheets[0]['cells'][$j+1]))
				  {
					 $PROPOSER_ID_PROOF =  $data->sheets[0]['cells'][$j+1][45];
					 if(empty($PROPOSER_ID_PROOF)){	
					 $PROPOSER_ID_PROOF			= '';
					 }else{	
					 $PROPOSER_ID_PROOF		=$PROPOSER_ID_PROOF;	
					 }	
				  }
				  else{
					$PROPOSER_ID_PROOF 	   ='';  
				  }
				  
				  
				  if (array_key_exists("46",$data->sheets[0]['cells'][$j+1]))
				  {
					 $PROPOSER_ADDRESS_PROOF =  $data->sheets[0]['cells'][$j+1][46];
					 if(empty($PROPOSER_ADDRESS_PROOF)){	
					 $PROPOSER_ADDRESS_PROOF			= '';
					 }else{	
					 $PROPOSER_ADDRESS_PROOF		=$PROPOSER_ADDRESS_PROOF;	
					 }	
				  }
				  else{
					$PROPOSER_ADDRESS_PROOF 	   ='';  
				  }
				  
				  if (array_key_exists("47",$data->sheets[0]['cells'][$j+1]))
				  {
					 $insured_address =  $data->sheets[0]['cells'][$j+1][47];
					 if(empty($insured_address)){	
					 $insured_address			= '';
					 }else{	
					 $insured_address		=$insured_address;	
					 }	
				  }
				  else{
					$insured_address 	   ='';  
				  }
				  
				  if (array_key_exists("48",$data->sheets[0]['cells'][$j+1]))
				  {
					 $state_id =  $data->sheets[0]['cells'][$j+1][48];
					 if(empty($state_id)){	
					 $state_id			= '';
					 }else{	
					 $state_id		=$state_id;	
					 }	
				  }
				  else{
					$state_id 	   ='';  
				  }
				  
				  if (array_key_exists("49",$data->sheets[0]['cells'][$j+1]))
				  {
					 $pin =  $data->sheets[0]['cells'][$j+1][49];
					 if(empty($pin)){	
					 $pin			= '';
					 }else{	
					 $pin		=$pin;	
					 }	
				  }
				  else{
					$pin 	   ='';  
				  }
				  
				  if (array_key_exists("50",$data->sheets[0]['cells'][$j+1]))
				  {
					 $telephone_no =  $data->sheets[0]['cells'][$j+1][50];
					 if(empty($telephone_no)){	
					 $telephone_no			= '';
					 }else{	
					 $telephone_no		=$telephone_no;	
					 }	
				  }
				  else{
					$telephone_no 	   ='';  
				  }
				  
				  if (array_key_exists("51",$data->sheets[0]['cells'][$j+1]))
				  {
					 $education_qualification =  $data->sheets[0]['cells'][$j+1][51];
					 if(empty($education_qualification)){	
					$education_qualification			= '';
					 }else{	
					$education_qualification		=$education_qualification;	
					 }	
				  }
				  else{
					$education_qualification 	   ='';  
				  }
				  
				  
				  
				  if (array_key_exists("52",$data->sheets[0]['cells'][$j+1]))
				  {
					 $occupation =  $data->sheets[0]['cells'][$j+1][52];
					 if(empty($occupation)){	
					$occupation			= '';
					 }else{	
					$occupation		=$occupation;	
					 }	
				  }
				  else{
					$occupation 	   ='';  
				  }
				  
				  if (array_key_exists("53",$data->sheets[0]['cells'][$j+1]))
				  {
					
					 $anual_income =  $data->sheets[0]['cells'][$j+1][53];
					 if(empty($anual_income)){	
					$anual_income			= '';
					 }else{	
					$anual_income		=$anual_income;	
					 }	
				  }
				  else{
					$anual_income 	   ='';  
				  }
				  
				  if (array_key_exists("54",$data->sheets[0]['cells'][$j+1]))
				  {
					 $insured_height =  $data->sheets[0]['cells'][$j+1][54];
					 if(empty($insured_height)){	
					$insured_height			= '';
					 }else{	
					$insured_height		=$insured_height;	
					 }	
				  }
				  else{
					$insured_height 	   ='';  
				  }
				  
				   if (array_key_exists("55",$data->sheets[0]['cells'][$j+1]))
				  {
					 $insured_weight =  $data->sheets[0]['cells'][$j+1][55];
					 if(empty($insured_weight)){	
					$insured_weight			= '';
					 }else{	
					$insured_weight		=$insured_weight;	
					 }	
				  }
				  else{
					$insured_weight 	   ='';  
				  }
				  
				  if (array_key_exists("56",$data->sheets[0]['cells'][$j+1]))
				  {
					 $pan_no =  $data->sheets[0]['cells'][$j+1][56];
					 if(empty($pan_no)){	
					$pan_no		= '';
					 }else{	
					$pan_no		=$pan_no;	
					 }	
				  }
				  else{
					$pan_no 	   ='';  
				  }
				  
				  if (array_key_exists("57",$data->sheets[0]['cells'][$j+1]))
				  {
					 $AADHAAR_NO =  $data->sheets[0]['cells'][$j+1][57];
					 if(empty($AADHAAR_NO)){	
					$AADHAAR_NO		= '';
					 }else{	
					$AADHAAR_NO		=$AADHAAR_NO;	
					 }	
				  }
				  else{
					$AADHAAR_NO	   ='';  
				  }
					$sql="INSERT INTO installment_master_branch SET
									business_date='$business_date',
									type_of_business='$type_of_business',
									collection_date='0000-00-00',
									phase_id='NULL',
									hub_id='$hub_id',
									branch_id='$branch',
									application_no='$application_no',
									agent_code='',
									pre_printed_receipt_no='',
									cash_money_receipt='$cash_money_receipt',
									cheque_money_receipt='$cheque_money_receipt',
									draft_money_receipt='',
									applicant_name='',
									applicant_dob='0000-00-00',
									applicant_age='',
									insured_name='$insured_name',
									insured_dob='$insured_dob',
									insured_gender='$insured_gender',
									insured_age_proof='$insured_age_proof',
									insured_age='0',
									nominee_name='$nominee_name',
									nominee_relationship='$nominee_relationship',
									nominee_dob='$nominee_dob',
									nominee_age='',
									appointee_name='$appointee_name',
									appointee_relationship='$appointee_relationship',
									appointee_dob='$appointee_dob',
									appointee_age='',
									plan_name='$plan_name',
									term='$term',
									premium_paying_term='$premium_paying_term',
									frequency='$frequency',
									sum_asured='$sum_asured',
									pay_mode='$pay_mode',
									receive_cash='$receive_cash',
									receive_cheque='$receive_cheque',
									receive_draft='0.00',
									premium='0.00',
									cash_pis_id='0',
									cheque_pis_id='0',
									draft_pis_id='0',
									cheque_no='$cheque_no',
									cheque_date='$cheque_date',
									cheque_bank_name='$cheque_bank_name',
									cheque_branch_name='$cheque_branch_name',
									dd_no='',
									dd_date='0000-00-00',
									dd_bank_name='',
									dd_branch_name='',
									identity_proof='',
									address_proof='',
									insured_address1='',
									insured_address2='',
									insured_address3='',
									state_id='$state_id',
									pin='$pin',
									telephone_no='$telephone_no',
									office_name='',
									office_address1='',
									office_address2='',
									office_address3='',
									gender='',
									education_qualification='$education_qualification',
									occupation='$occupation',
									office_teephone_no='',
									nature_of_duty='',
									anual_income='$anual_income',
									insured_height='".mysql_real_escape_string($insured_height)."',
									insured_weight='$insured_weight',
									cancellation_reason='',
									is_deleted='0',
									is_edited='0',
									campaign_id='0',
									branch_despatched='0',
									branch_despatch_date='0000-00-00',
									sp_tag_despatched='0',
									sp_despatched_date='0000-00-00',
									admin_received='0',
									admin_receive_date='0000-00-00',
									BRANCH_CODE='$BRANCH_CODE',
									ASTHA_BRANCH_NAME='$ASTHA_BRANCH_NAME',
									BRANCH_SUB_CODE='$BRANCH_SUB_CODE',
									QUOTE_NO='$QUOTE_NO',
									PROPOSER_NAME='$PROPOSER_NAME',
									PROPOSER_DOB='$PROPOSER_DOB',
									PROPOSER_GENDER='$PROPOSER_GENDER',
									PROPOSER_AGE_PROOF='$PROPOSER_AGE_PROOF',
									FATHER_NAME='$FATHER_NAME',
									AADHAAR_NO='$AADHAAR_NO',
									POLICY_NO='$POLICY_NO',
									PAYMENT_MODE='$PAYMENT_MODE',
									pan_no='$pan_no',
									ANNUAL_INCOME='$anual_income',
									PROPOSER_ID_PROOF='$PROPOSER_ID_PROOF',
									PROPOSER_ADDRESS_PROOF='$PROPOSER_ADDRESS_PROOF',
									insured_address='$insured_address'";



					
					//echo $sql;exit;
					
									$qry	=	mysql_query($sql);
									if($qry){
										$msg = "Successfully Inserted the records...";
									}
									else{
										
										$duplicate_application_no_list[]=$application_no;
										$duplicate= implode(',',$duplicate_application_no_list);
										 $errmsg1 = "Please check data type for <br/>".$duplicate;
										}
					
				}	
				
			}
 					
			//header("location: ".URL.'webadmin/index.php?p=transaction_list_branch');
}

}

if(isset($_POST['branch_name']) && $_POST['branch_name'] != '')
{
	//echo '<pre>';
	//print_r($_POST);
	//echo '</pre>';
	extract($_POST);
	if(!(isset($micr_code))) {$micr_code = '';}
	if(!(isset($dd_date))) {$dd_date = '';}

	$product_name = find_product_name($plan);

	$dd_date = date('Y-m-d', strtotime($dd_date));

	//$premium_multiple = find_premium_multiple($plan);
	$min_amount = find_min_amount($plan);
	$max_amount = find_max_amount($plan);

	
	
	if(trim($micr_code) != '')
	{
		$micr_id = find_micr_id($micr_code);
	}

	//if($amount % $comitted_amount != 0)
	//{
		//$msg = 'Amount must be a multiple of Monthly Commited Amount';
	//}
	//else if($amount > $max_amount) // $max_amount is considered the maximum amount for a particular transaction
	//{
		//$msg = 'Amount should not exceed '.$max_amount;
	//}
	//else if($amount < $min_amount) // $min_amount is considered the minimum amount for a particular transaction
	//{
		//$msg = 'Minimum amount must be '.$min_amount;
	//}
	//else if($comitted_amount % $premium_multiple != 0)
	//{
		//$msg = 'Monthly Commited Amount must be a multiple of '.$premium_multiple;
	//}
	else if(isset($micr_id) && intval($micr_id) == 0)
	{
		$msg = 'Invalid MICR Code';
	}
	
	if($msg == '')
	{
				//echo 'Hello';
				

			$branch_code = find_branch_code($branch_name);
			

			$branch_state = find_state_id_through_branch_id($branch_name);
			
			
		
			// INSERTING DATA INTO installment_master TABLE
			
			if($msg == '') // authenic entry
			{
				if($total_value_manual == '')
				{
				$other_amount = $total_value - $main_amount;
				}
				else
				{
				$other_amount = $total_value_manual - $main_amount;
				}
				if($other_amount<0)
				{
				$other_amount = 0;
				}

				$derived_dob = date('Y-m-d', strtotime($dob));
				if(!isset($bank_ac)) {$bank_ac = 0;}
				if($other_payment_mode == '')
				{
				$other_payment_mode = 'NULL';
				}

				$set_hub = "SELECT hub_id FROM branch_hub_entry WHERE branch_id = '".$branch_name."' order by hub_since desc LIMIT 0,1";
				
				$set_hub_data = mysql_query($set_hub);
				$get_hub = mysql_fetch_array($set_hub_data);
				
				if($dob_manual!='')
				{
				$dob = $dob_manual;
				}
				if($plan_manual!='')
				{
				$plan = $plan_manual;
				}
				if($tenure_manual!='')
				{
				$tenure = $tenure_manual;
				}
				if($frequency_manual!='')
				{
				$frequency = $frequency_manual;
				}
				if($sum_assured_manual!='')
				{
				$sum_assured = $sum_assured_manual;
				}
				if($age_proof_manual!='')
				{
				$age_proof = $age_proof_manual;
				}
				if($amount_manual!='')
				{
				$amount = $amount_manual;
				}
				if($service_tax_manual!='')
				{
				$service_tax = $service_tax_manual;
				}
				if($total_value_manual!='')
				{
				$total_value = $total_value_manual;
				}
				


				$insert_installment = "INSERT INTO installment_master SET 
										bank_ac = '".realTrim($bank_ac)."',
										ifs_code_main = '".realTrim($ifs_code_main)."',
										micr_code_main = '".realTrim($micr_code_main)."',
										bank_name_main = '".realTrim($bank_name_main)."',
										branch_name_main = '".realTrim($branch_name_main)."',
										account_no_main = '".realTrim($account_no_main)."',
										plan = '".realTrim($plan)."',
										tenure = '".realTrim($tenure)."',
										first_name = '".realTrim($first_name)."',
										middle_name = '".realTrim($middle_name)."',
										last_name = '".realTrim($last_name)."',	
										applicant_dob = '".realTrim($derived_dob)."',

										accidental_benefit = '".realTrim($accidental_benefit)."',
										age_proof_type = '".realTrim($age_proof)."',

										application_no = '".$application_no."',
										branch_id = '".$branch_name."',
										hub_id = '".$get_hub['hub_id']."',
										deposit_date = '".date('Y-m-d')."',
										agent_code = '".realTrim($agent_code)."',
										agent_name = '".realTrim($agent_name)."',
										payment_mode = '".$payment_mode."',
										other_payment_mode = '".realTrim($other_payment_mode)."',
										sum_assured = '".realTrim($sum_assured)."',
										main_amount = '".floatval($main_amount)."',
										other_amount = '".floatval($other_amount)."',
										frequency = '".realTrim($frequency)."',
										amount = '".floatval($amount)."',
										service_tax = '".floatval($service_tax)."',
										total_value = '".floatval($total_value)."',
										dd_number = '".realTrim($dd_number)."',
										dd_date = '".realTrim($dd_date)."',
										dd_bank_name = '".realTrim($dd_bank_name)."',
										dd_bank_branch = '".realTrim($dd_branch_name)."',
										ifs_code = '".realTrim($ifs_code)."',
										micr_code = '".realTrim($micr_code)."',
										account_no = '".realTrim($account_no)."',
										in_favour = '".realTrim($in_favour)."',
										serial_no = '".realTrim($serial_no)."'
				";
				//echo '<br />'.$insert_installment;

				//exit;
				//mysql_query($insert_installment);

				//$branch_transaction = mysql_insert_id();
				//echo $branch_transaction;
				
				//$branch_transaction = find_branch_transaction($branch_name);
				//$transaction_id = $branch_name.'/'.date('m/Y').'/'.str_pad($branch_transaction,7,'0',STR_PAD_LEFT);
				//echo $transaction_id;

				$updt_transaction_id = "UPDATE installment_master SET transaction_id = '".$transaction_id."' WHERE id =".$branch_transaction;
				//mysql_query($updt_transaction_id);
				//exit;
				
				//$total_premium_after_transaction = intval($preimum_given + $NOPFTT); 

				//mysql_query("UPDATE customer_folio_no SET total_premium_given='".$total_premium_after_transaction."' WHERE id = '".$lastFolioNo."'"); // UPDATE TOTAL PREMIUM
				//header("location: ".URL.'webadmin/index.php?p=transaction_list_branch');
			}
	}

	//exit;
}

###### initialization of the variables start #######


if(!isset($ifs_code_main)) { $ifs_code_main = ''; } 
if(!isset($micr_code_main)) { $micr_code_main = ''; } 
if(!isset($bank_name_main)) { $bank_name_main = ''; } 
if(!isset($branch_name_main)) { $branch_name_main = ''; } 
if(!isset($account_no_main)) { $account_no_main = ''; } 

if(!isset($sum_assured)) { $sum_assured = ''; } 
if(!isset($short_premium)) { $short_premium = ''; } 
if(!isset($account_no)) { $account_no = ''; } 
if(!isset($in_favour)) { $in_favour = ''; } 



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
if(!isset($amount_manual)) { $amount_manual = ''; }

if(!isset($service_tax)) { $service_tax = ''; }

if(!isset($total_value)) { $total_value = ''; } 
if(!isset($total_value_manual)) { $total_value_manual = ''; } 
if(!isset($payment_mode)) { $payment_mode = ''; } 
if(!isset($other_payment_mode)) { $other_payment_mode = ''; }
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
if(!isset($dob_manual)) { $dob_manual = ''; }
if(!isset($insurance)) { $insurance = '0'; }
if(!isset($age_proof)) { $age_proof = ''; }
if(!isset($id_proof)) { $id_proof = ''; }
if(!isset($fathers_name)) { $fathers_name = ''; }
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
if(!isset($occupation)) { $occupation = ''; }
if(!isset($annual_income)) { $annual_income = ''; }
if(!isset($ino_address_proof)) { $ino_address_proof = ''; }
if(!isset($ino_id_proof)) { $ino_id_proof = ''; }
if(!isset($ino_age_proof)) { $ino_age_proof = ''; }
if(!isset($mothers_maiden_name)) { $mothers_maiden_name = ''; }
if(!isset($ifs_code)) { $ifs_code = ''; }
if(!isset($micr_code)) { $micr_code = ''; }
if(!isset($occupation_name)) { $occupation_name = ''; }
if(!isset($dd_branch_name)) { $dd_branch_name = ''; }
if(!isset($plan)) { $plan = ''; } 
if(!isset($main_amount)) { $main_amount = ''; } 
if(!isset($frequency)) { $frequency = ''; } 
if(!isset($accidental_benefit)) { $accidental_benefit = ''; }
if(!isset($age)) { $age = ''; }
if(!isset($age_proof_manual)) { $age_proof_manual = ''; }
if(!isset($sum_assured_manual)) { $sum_assured_manual = ''; } 
if(!isset($serial_no)) { $serial_no = ''; }
if(!isset($service_tax_manual)) { $service_tax_manual = ''; }
if(!isset($serial_no_manual)) { $serial_no_manual = ''; }


###### initialization of the variables end #######





//$a=loadVariable('a','');

$id = $_SESSION[ADMIN_SESSION_VAR];

/*
$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,3,5,6) AND branch_user_id = 0 ORDER BY branch_name ASC");

*/
/*
echo "<pre>";
print_r($_POST);
die();
*/



?>
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
	
		if(document.addForm.branch.value.search(/\S/) == -1)
		{
			alert("Please Enter Branch.");
			document.addForm.branch.focus();
			return false;
		}
		else if(document.addForm.upload_xls.value.search(/\S/) == -1)
		{
			alert("Please Upload XLS File.");
			document.addForm.branch.focus();
			return false;
		}
		else if(document.addForm.upload_xls.value.substring(document.addForm.upload_xls.value.lastIndexOf('.') + 1).toLowerCase()!='xls')
		{
			alert("Please Upload only Xls File.");
			document.addForm.branch.focus();
			return false;
		}
		else
		{
			return true;
		}
		}



function adalt_or_minor_div()
{
if(document.getElementById("is_adult").checked)
		{
		//alert('Hi');
		document.getElementById("for_minor").style.display="";
		document.getElementById("for_adult").style.display="none";
		}
		else
		{
		document.getElementById("for_minor").style.display="none";
		document.getElementById("for_adult").style.display="";
		}
return false;
}
</script>

<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>

<form name="addForm" id="addForm" enctype="multipart/form-data" action="" method="post" onsubmit="return dochk()">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Manual Entry (New Business)</td>
    </tr>
		
    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
        * marked fields are mandatory</font></b></td>
    </tr>

		

		
    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Preliminary Entry</font></b></td>
    </tr>
	
	<?php if($msg!=""){?>
	<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#green"><?php echo $msg; ?></font></b></td>
    </tr>
	<?php }?>
	
	<?php if($errmsg1!=""){?>
	<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000"><?php echo $errmsg1; ?></font></b></td>
    </tr>
	<?php }?>
	<?php if($errmsg2!=""){?>
	<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000"><?php echo $errmsg2; ?></font></b></td>
    </tr>
	<?php }?>

	

	
	<!--<tr> 
      <td class="tbllogin" valign="top" align="right">Phase<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><select name="phase" id="phase" class="inplogin">
				<option value="">Select</option>
				<?php 
				
					/*$selPhase = mysql_query("select id, phase from phase_master order by phase");   //for Plan dropdown
					$numPhase = mysql_num_rows($selPhase);
					if($numPhase > 0)
					{
						while($getPhase = mysql_fetch_array($selPhase))
						{	*/
												
				?>
					<option value="<?php //echo $getPhase['id']; ?>" <?php //echo ($getPhase['id'] == $tenure ? 'selected' : ''); ?>><?php echo $getPhase['phase']; ?></option>
				<?php
						//}
					//}
				?>
			</select></td>
    </tr>-->
	<?php //if($role_id!='4'): ?>
	<!--<tr> 
      <td class="tbllogin" valign="top" align="right">Branch Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><select name="branch" id="branch" class="inplogin">
				<option value="">Select</option>
				<?php 
				
					/*$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,3,5,6) AND branch_user_id = 0 ORDER BY branch_name ASC");
					$numBranch = mysql_num_rows($selBranch);
					if($numBranch > 0)
					{
						while($getBranch = mysql_fetch_array($selBranch))
						{	
												
				?>
					<option value="<?php echo $getBranch['id']; ?>" <?php echo ($getBranch['branch_name'] == $tenure ? 'selected' : ''); ?>><?php echo $getBranch['branch_name']; ?></option>
				<?php
						}
					}*/
				?>
			</select></td>
    </tr>	
	<?php //else:?>
	<input type="hidden" id="branch" name="branch" value="<?php echo $id; ?>">
	<?php //endif; ?>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Code <font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="agent_code" id="agent_code" type="text" /></td>
	</tr>-->
	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Upload XLS File<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="upload_xls" id="upload_xls" type="file" /></td>
    </tr>

		<tr>
		<td colspan="3">
		<div id="for_minor" style="display:none;margin-right: 266px;">
		<table cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
		
		<tr> 
      <td class="tbllogin" valign="top" align="right">Applicant DOB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dob_manual" id="dob_manual" type="text" class="inplogin"  value="<?php echo $dob_manual; ?>" maxlength="20" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.dob_manual,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Plan<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	  <!--<input type="hidden" name="plan" id="plan" value="2" >-->
	  
	  <select name="plan_manual" id="plan_manual" class="inplogin">
				<option value="">Select</option>
				<?php 
				
					$selPlan = mysql_query("select id, plan_name from insurance_plan WHERE status=1 AND is_new=1 ORDER BY plan_name ASC ");   //for Plan dropdown
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
			</select>			</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Term<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<select name="tenure_manual" id="tenure_manual" class="inplogin">
				<option value="">Select</option>
				<?php 
				$selTenure = mysql_query("SELECT id, tenure FROM tenure_master WHERE status=1 ORDER BY tenure ASC "); //for Term dropdown
$numTenure = mysql_num_rows($selTenure);
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
			</select></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Payment Frequency<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
					
				
				<select name="frequency_manual" id="frequency_manual" class="inplogin_select" onchange="javascript:showHide(this.value)">
				<option value="">Select</option>
				<?php 
				
					$selFrequency = mysql_query("select id, frequency from frequency_master WHERE status=1 ORDER BY frequency ASC ");   //for Plan dropdown
					$numFrequency = mysql_num_rows($selFrequency);
					if($numFrequency > 0)
					{
						while($getFrequency = mysql_fetch_array($selFrequency))
						{	
												
				?>
					<option value="<?php echo $getFrequency['id']; ?>" <?php echo ($getFrequency['id'] == $tenure ? 'selected' : ''); ?>><?php echo $getFrequency['frequency']; ?></option>
				<?php
						}
					}
				?>
			</select>					</td>
    </tr>
	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Sum Assured<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="sum_assured_manual" id="sum_assured_manual" type="text" class="inplogin"  value="<?php echo $sum_assured_manual; ?>" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')">	  </td>
    </tr>
	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Age Proof<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="age_proof_manual" id="age_proof_manual" class="inplogin_select" >
					<option value="">Select</option>
					<option value="STANDARD" >STANDARD</option>
					<option value="NSAP1" >NSAP1</option>
					<option value="NSAP23" >NSAP2/NASP3</option>
				</select>					</td>
    </tr>
<script type="text/javascript">
function get_tax_and_total(amunt)
{
//alert (amunt);
document.getElementById('service_tax_manual').value = Math.floor(parseInt(amunt)*(.03090));
document.getElementById('total_value_manual').value = parseInt(amunt) + Math.floor(parseInt(amunt)*(.03090));
}
</script>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Premium Amount<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="amount_manual" id="amount_manual" type="text" class="inplogin"  value="<?php echo $amount_manual; ?>" onKeyPress="return keyRestrict(event, '0123456789.')" onblur="get_tax_and_total(this.value);" >	  </td>
    </tr>	
	
	
	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Service Tax<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="service_tax_manual" id="service_tax_manual" type="text" class="inplogin"  value="<?php echo $service_tax_manual; ?>" onKeyPress="return keyRestrict(event, '0123456789.')" readonly="readonly">	  </td>
    </tr>	
		
	<tr> 
      <td class="tbllogin" valign="top" align="right">Total Premium Amount<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="total_value_manual" id="total_value_manual" type="text" class="inplogin"  value="<?php echo $total_value_manual;?>" readonly="readonly">	  </td>
    </tr>	
	</table>
		</div>		</td>
		</tr>
		


	
	 
	
	
	
	
	
	
	

	
	
	
			
	


    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Add" name="submit" class="inplogin" type="submit"> 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
    </tr>

  </tbody>
</table>
</form>

<?php //$objDB->close(); ?>

