<?php
//include("../utility/config.php");
//include("../utility/dbclass.php");
//include_once("../utility/functions.php");
//include_once("new_functions.php");
require("Excel/reader.php");
$conn = mysql_connect('localhost', 'senabide_bharati', 'Maakali2017');
mysql_select_db('senabide_bharatiAXA',$conn);

$branch_id					= $_POST['bn_id'];
$role_id					= $_POST['rid'];
$upload_file 				= $_POST['file'];



		$data = new Spreadsheet_Excel_Reader();
		
		$data->setOutputEncoding("CP1251");
		$data->read($upload_file);
		

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
						phase_name='NULL',
						hub_id='$hub_id',
						branch_id='$branch',
						application_no='$application_no',
						agent_code='',
						agent_name='',
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



		
		//echo $sql;//exit;
						if($role_id == 1)
						{
							$qry	=	mysql_query($sql);
							if($qry!=true){
							
							$duplicate_application_no_list[]=$application_no;
							$duplicate= implode(',',$duplicate_application_no_list);
							 echo "Please check data type for <br/>".$duplicate;
							}
							
						}
						else if($role_id ==4)
						{
							if($branch == $branch_id)
							{
								$qry	=	mysql_query($sql);
								if($qry!=true){
							
									$duplicate_application_no_list[]=$application_no;
									$duplicate= implode(',',$duplicate_application_no_list);
									 echo "Please check data type for <br/>".$duplicate;
								}
								
							}
							
						}
							
						/**/
		
	}

	
?>