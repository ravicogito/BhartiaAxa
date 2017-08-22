<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");

date_default_timezone_set('Asia/Calcutta');
$msg = '';
//$PREMIUM_TYPE = 'INITIAL PAYMENT';

$objDB = new DB();

$pageOwner = "'branch'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

//echo 'SITE IS UNDER MAINTENANCE';
#exit;



$selTenure = mysql_query("SELECT id, tenure FROM tenure_master WHERE status=1 ORDER BY tenure ASC "); //for Term dropdown
$numTenure = mysql_num_rows($selTenure);


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
			
			
		
			// INSERTING DATA INTO temporary_installment_master TABLE
			
			if($msg == '') // authenic entry
			{
				
				//echo 'here';

				//$main_amount=$main_amount;

				$other_amount = $amount - $main_amount;

				//mysql_query("INSERT INTO receipt_generator SET
					//transaction_date = '".date('Y-m-d')."'
				//");

				
				//$installment = $preimum_given + (floatval($amount) / floatval($comitted_amount));
				
				$derived_dob = date('Y-m-d', strtotime($dob));
				if(!isset($bank_ac)) {$bank_ac = 0;}
				if($other_payment_mode == '')
				{
				$other_payment_mode = 'NULL';
				}

				$set_hub = "SELECT hub_id FROM branch_hub_entry WHERE branch_id = '".$branch_name."' order by hub_since desc LIMIT 0,1";
				//echo $set_hub;
				//exit;
				$set_hub_data = mysql_query($set_hub);
				$get_hub = mysql_fetch_array($set_hub_data);


				$insert_installment = "INSERT INTO temporary_installment_master SET 
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
										deposit_date = '2014-01-01',
										agent_code = '".realTrim($agent_code)."',
										agent_name = '".realTrim($agent_name)."',
										payment_mode = '".$payment_mode."',
										other_payment_mode = '".realTrim($other_payment_mode)."',
										sum_assured = '".realTrim($sum_assured)."',
										main_amount = '".floatval($main_amount)."',
										other_amount = '".floatval($other_amount)."',
										frequency = '".realTrim($frequency)."',
										amount = '".floatval($amount)."',
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
				mysql_query($insert_installment);

				$branch_transaction = mysql_insert_id();
				//echo $branch_transaction;
				
				//$branch_transaction = find_branch_transaction($branch_name);
				$transaction_id = $branch_name.'/'.date('m/Y').'/'.str_pad($branch_transaction,7,'0',STR_PAD_LEFT);
				//echo $transaction_id;

				$updt_transaction_id = "UPDATE temporary_installment_master SET transaction_id = '".$transaction_id."' WHERE id =".$branch_transaction;
				mysql_query($updt_transaction_id);
				//exit;
				
				//$total_premium_after_transaction = intval($preimum_given + $NOPFTT); 

				//mysql_query("UPDATE customer_folio_no SET total_premium_given='".$total_premium_after_transaction."' WHERE id = '".$lastFolioNo."'"); // UPDATE TOTAL PREMIUM
				header("location: ".URL.'webadmin/index.php?p=temporary_transaction_list_branch');
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
if(!isset($serial_no)) { $serial_no = ''; }




###### initialization of the variables end #######





//$a=loadVariable('a','');

$id = $_SESSION[ADMIN_SESSION_VAR];

$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,3,5,6) AND branch_user_id = 0 ORDER BY branch_name ASC");


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
<!--
	
	function showHide(paymentType)
	{	
		//alert(paymentType);
		if(paymentType == 'CASH')
		{
			document.getElementById("dd_number").value='';			
			document.getElementById("dd_bank_name").value='';
			document.getElementById("dd_branch_name").value='';
			document.getElementById("dd_date").value=''; 
			document.getElementById("micr_code").value=''; 
			document.getElementById("ifs_code").value=''; 

			document.getElementById("dd_number").disabled=true;
			document.getElementById("dd_bank_name").disabled=true;
			document.getElementById("dd_branch_name").disabled=true;
			document.getElementById("dd_date").disabled=true;
			document.getElementById("micr_code").disabled=true;
			document.getElementById("ifs_code").disabled=true;
			document.getElementById("calChq").disabled=true;
		}
		else
		{
			document.getElementById("dd_number").disabled=false;
			document.getElementById("dd_bank_name").disabled=false;
			document.getElementById("dd_branch_name").disabled=false;
			document.getElementById("dd_date").disabled=false;
			document.getElementById("micr_code").disabled=false;
			document.getElementById("ifs_code").disabled=false;
			document.getElementById("calChq").disabled=false;
		}
	}



	function bankdetails()
	{	
		//alert(paymentType);
		//var val = document.getElementById("bank_acy").value;

			

		if(document.getElementById("bank_acy").checked == false)
		{
			document.getElementById("ifs_code_main").value='';			
			document.getElementById("micr_code_main").value='';
			document.getElementById("bank_name_main").value='';
			document.getElementById("branch_name_main").value=''; 
			document.getElementById("account_no_main").value='';

			document.getElementById("ifs_code_main").disabled=true;
			document.getElementById("micr_code_main").disabled=true;
			document.getElementById("bank_name_main").disabled=true;
			document.getElementById("branch_name_main").disabled=true;
			document.getElementById("account_no_main").disabled=true;
		}
		else
		{
			document.getElementById("ifs_code_main").disabled=false;
			document.getElementById("micr_code_main").disabled=false;
			document.getElementById("bank_name_main").disabled=false;
			document.getElementById("branch_name_main").disabled=false;
			document.getElementById("account_no_main").disabled=false;
		}
	}
//-->
</script>

<script type="text/javascript">
	<!--
	function insuranceEligible(dob)
	{
		//alert('123');
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
		  //alert("Not Eligible");
		  return false;
		}
	}
	//-->
	</script>

<script type="text/javascript">
function showbank(micrval){
	var datastring = 'micr='+micrval;
	/*alert(datastring);
	die();*/
	if(micrval == ""){
		alert("please select IFS code");
		$("#ifs_code").focus();
	}else{
	$.ajax({
             type: "POST",
             url: "ddbankajax.php",
             data:  datastring,
			 dataType: 'json',
			 cache: false,
             success: function(data){
			 if(data == 0){
			 	alert("This is not a valid IFS Code");
				$('input[id=dd_bank_name]').val('');
				$('textarea[id=dd_branch_name]').val('');
				$('input[id=ifs_code]').val('');
				$('input[id=micr_code]').val('');
				$("#micr_code").focus();
			 }else{
				 $('input[id=dd_bank_name]').val(data[0]);
				 $('textarea[id=dd_branch_name]').val(data[1]);
				 $('input[id=micr_code]').val(data[2]);
				 
				 /*document.getElementById("dd_bank_name").disabled=true;
				 document.getElementById("dd_branch_name").disabled=true;*/
				 /*document.getElementById("ifs_code").disabled=true;*/
				 }
              }
          });
	}
}
</script>



<script type="text/javascript">
function showamount(sum_assured,plan,frequency,tenure,dob,age_proof,accidental_benefit,age_proof){
	if(age_proof != ""){
	var datastring = 'sum_assured='+sum_assured+'&frequency='+frequency+'&tenure='+tenure+'&dob='+dob+'&plan='+plan+'&age_proof='+age_proof+'&accidental_benefit='+accidental_benefit+'&age_proof='+age_proof;
	}else
	{
	var datastring = 'sum_assured='+sum_assured+'&frequency='+frequency+'&tenure='+tenure+'&dob='+dob+'&plan='+plan+'&age_proof='+age_proof+'&accidental_benefit='+accidental_benefit;
	}
	//alert(datastring);
	//die();
	if(dob == ""){
		alert("please select dob");
		$("#dob").focus();
	}
	
	else if(plan == ""){
		alert("please select plan");
		$("#plan").focus();
	}
	else if(tenure == ""){
		alert("please select terms");
		$("#tenure").focus();
	}
	else if(frequency == ""){
		alert("please select frequency");
		$("#frequency").focus();
	}
	
	else if(sum_assured == ""){
		alert("please select sum assured");
		$("#sum_assured").focus();
	}
	else if((plan == "1") && (accidental_benefit == "")){
		
			alert("please select Accidental Benefit");
			$("#accidental_benefit").focus();
		

	}
	else if((plan == "9") && (accidental_benefit == "")){
		
			alert("please select Accidental Benefit");
			$("#accidental_benefit").focus();
		

	}
	else if(age_proof == ""){
		alert("please select Age Proof");
		$("#age_proof").focus();
	}
	else{
	$.ajax({
             type: "POST",
             url: "amountajax.php",
             data:  datastring,
			 dataType: 'json',
			 cache: false,
             success: function(data){

				// alert(data);
				// return false;
				//alert('Hi');
				//var data1= JSON.parse(data);
				//alert (data1);
				
				//return false;
			 if(data == 0){
			 	alert("Invalid Options");
				$('input[id=amount]').val('');
				$('input[id=age]').val('');
				
			 }else{
				 $('input[id=amount]').val(data[0]);
				 $('input[id=age]').val(data[1]);
				 $('input[id=message]').val(data[2]);
				
				 }
              }
          });
	}
}
</script>


<script type="text/javascript">
function showbank_new(micrval){
	var datastring = 'micr='+micrval;
	/*alert(datastring);
	die();*/
	if(micrval == ""){
		alert("please select IFS code");
		$("#ifs_code_main").focus();
	}else{
	$.ajax({
             type: "POST",
             url: "ddbankajax_new.php",
             data:  datastring,
			 dataType: 'json',
			 cache: false,
             success: function(data){
			 if(data == 0){
			 	alert("This is not a valid IFS Code");
				$('input[id=bank_name_main]').val('');
				$('textarea[id=branch_name_main]').val('');
				$('input[id=ifs_code_main]').val('');
				$('input[id=micr_code_main]').val('');
				$("ifs_code_main").focus();
			 }else{
				 $('input[id=bank_name_main]').val(data[0]);
				 $('textarea[id=branch_name_main]').val(data[1]);
				 $('input[id=micr_code_main]').val(data[2]);
				 
				 /*document.getElementById("dd_bank_name").disabled=true;
				 document.getElementById("dd_branch_name").disabled=true;*/
				 /*document.getElementById("ifs_code").disabled=true;*/
				 }
              }
          });
	}
}
</script>


<script type="text/javascript">
function showminmax(planid){
	var datastring = 'planid='+planid;
	//alert(datastring);
	if(planid == ""){
		$("#plandesc").html('');
	}else{
	$.ajax({
             type: "POST",
             url: "planshow.php",
             data:  datastring,
             success: function(data){
			 //alert(data);
			 $("#plandesc").html(data);
              }
          });
	}
}

function otherpaydropdown(paymodeid)
{
//alert(paymodeid);
//var datastring = 'paymode='+paymodeid;
			if(paymodeid == 'CASH'){
			document.getElementById("other_payment_mode").value=''; 
			document.getElementById("other_payment_mode").disabled=true;
			document.getElementById("dd_date").value='';
			document.getElementById("dd_date").disabled=true;
			document.getElementById("dd_number").value='';
			document.getElementById("dd_number").disabled=true;
			document.getElementById("ifs_code").value='';
			document.getElementById("ifs_code").disabled=true;
			document.getElementById("micr_code").value='';
			document.getElementById("micr_code").disabled=true;
			document.getElementById("dd_bank_name").value='';
			document.getElementById("dd_bank_name").disabled=true;
			document.getElementById("account_no").value='';
			document.getElementById("account_no").disabled=true;
			document.getElementById("in_favour").value='';
			document.getElementById("in_favour").disabled=true;
			document.getElementById("dd_branch_name").value='';
			document.getElementById("dd_branch_name").disabled=true;
			}
			else
			{
			document.getElementById("other_payment_mode").disabled=false;
			document.getElementById("dd_date").disabled=false;
			document.getElementById("dd_number").disabled=false;
			document.getElementById("ifs_code").disabled=false;
			document.getElementById("micr_code").disabled=false;
			document.getElementById("dd_bank_name").disabled=false;
			document.getElementById("account_no").disabled=false;
			document.getElementById("account_no").disabled=false;
			document.getElementById("in_favour").disabled=false;
			document.getElementById("dd_branch_name").disabled=false;



			}
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
		if(document.addForm.serial_no.value.search(/\S/) == -1)
		{
			alert("Please Enter Serial No.");
			document.addForm.serial_no.focus();
			return false;
		}
		
		if(document.getElementById("bank_acy").checked == true)
		{
			if(document.addForm.ifs_code_main.value.search(/\S/) == -1)
			{
				alert("Please Enter IFS Code");
				document.addForm.ifs_code_main.focus();
				return false;
			}
			if(document.addForm.micr_code_main.value.search(/\S/) == -1)
			{
				alert("Please Enter MICR Code");
				document.addForm.micr_code_main.focus();
				return false;
			}
			if(document.addForm.bank_name_main.value.search(/\S/) == -1)
			{
				alert("Please Enter Bank Name");
				document.addForm.bank_name_main.focus();
				return false;
			}
			if(document.addForm.branch_name_main.value.search(/\S/) == -1)
			{
				alert("Please Enter Branch Name");
				document.addForm.branch_name_main.focus();
				return false;
			}
			if(document.addForm.account_no_main.value.search(/\S/) == -1)
			{
				alert("Please Enter Account No.");
				document.addForm.account_no_main.focus();
				return false;
			}
		}
		
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
		}
		if(document.addForm.mothers_maiden_name.value.search(/\S/) == -1)
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
		/*
		if((document.addForm.insurance.checked == true) && !insuranceEligible(document.addForm.dob.value))
		{
			alert('Insurance facility is not available for this customer');
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
			alert("Please select Term");
			document.addForm.tenure.focus();
			return false;
		}
		if(document.addForm.plan.value.search(/\S/) == -1)
		{
			alert("Please select Plan");
			document.addForm.plan.focus();
			return false;
		}

		/*if(document.addForm.comitted_amount.value.search(/\S/) == -1)
		{
			alert("Please enter Monthly Comitted Amount");
			document.addForm.comitted_amount.focus();
			return false;
		}*/		

		/*if(parseInt(document.addForm.comitted_amount.value) < 500)
		{
			alert("Minimum limit for Monthly Comitted Amount is Rs. 500/-");
			document.addForm.comitted_amount.focus();
			return false;
		}*/

		if(document.addForm.sum_assured.value.search(/\S/) == -1)
		{
			alert("Please enter Sum Assured");
			document.addForm.sum_assured.focus();
			return false;
		}
		if(document.addForm.main_amount.value.search(/\S/) == -1)
		{
			alert("Please enter Amount");
			document.addForm.main_amount.focus();
			return false;
		}

		
		//return false;

		/*if(parseInt(document.addForm.comitted_amount.value) > parseInt(document.addForm.amount.value))
		{
			alert("Amount must be greater than or equal to the Monthly Committed Amount");
			document.addForm.amount.focus();
			return false;
		}
		if(document.addForm.phone.value == '')
		{
			alert("Please Enter Phone No. ");
			document.addForm.phone.focus();
			return false;
		}*/

		if(document.addForm.payment_mode.value == '')
		{
			alert("Please select Payment Mode");
			document.addForm.payment_mode.focus();
			return false;
		}

		if(document.addForm.main_amount.value == '')
		{
			alert("Please select Amount");
			document.addForm.amount.focus();
			return false;
		}

		var premium_amount = document.getElementById("amount").value;
		var paid_amount = document.getElementById("main_amount").value;
		//alert(paid_amount);
		//alert(premium_amount);
		var other_amount = premium_amount - paid_amount;
		//alert(other_amount);
		if(document.getElementById("amount").value > document.getElementById("main_amount").value)
		{
		document.addForm.otheramount.value = "You need to pay Rs."+other_amount+"/- By Cash";
		}
		//return false;
		
		if(document.addForm.payment_mode.value == 'CASH'){
		if(document.getElementById("amount").value > document.getElementById("main_amount").value)
		{
			alert("Please enter Proper Amount");
			document.addForm.main_amount.focus();
			return false;
		}
		if(document.getElementById("amount").value < document.getElementById("main_amount").value)
		{
			alert("Please enter Proper Amount");
			document.addForm.main_amount.focus();
			return false;
		}
		}
		
		if(document.addForm.age_proof.value == '')
		{
			alert("Please select Age Proof");
			document.addForm.age_proof.focus();
			return false;
		}

		

		/*if((parseInt(document.addForm.main_amount.value) != parseInt(document.addForm.amount.value)) && (document.addForm.other_payment_mode.value.search(/\S/) == -1))
		{
			alert("Please select Other Payment Mode");
			document.addForm.other_payment_mode.focus();
			return false;
		}

		if((parseInt(document.addForm.amount.value) > 999999) && (document.addForm.payment_mode.value == 'CASH'))
		{
			alert("Rs 1000000 or more can not be paid in cash in a single transaction");
			document.addForm.payment_mode.focus();
			return false;
		}*/

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
			if(document.addForm.account_no.value.search(/\S/) == -1)
			{
				alert("Please enter Account Number");
				document.addForm.account_no.focus();
				return false;
			}
			if(document.addForm.in_favour.value.search(/\S/) == -1)
			{
				alert("Please enter In Favour");
				document.addForm.in_favour.focus();
				return false;
			}
		}

		if((document.addForm.payment_mode.value == 'CASH') && (document.addForm.other_payment_mode.value == 'CASH'))
		{
			alert("Please Deselect Other Payment Mode");
			document.addForm.other_payment_mode.focus();
			return false;

		}

		/*if(document.addForm.state.value == '')
		{
			alert("Please select State");
			document.addForm.state.focus();
			return false;
		}*/

		

		/*if((parseInt(document.addForm.comitted_amount.value) > 4000) && document.addForm.pan.value.search(/\S/) == -1)
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
		}*/

		

		/*if(document.addForm.annual_income.value.search(/\S/) == -1)
		{
			alert("Please Enter Annual Income");
			document.addForm.annual_income.focus();
			return false;
		}
		if(document.addForm.occupation_name.value.search(/\S/) == -1)
		{
			alert("Please Select Occupation");
			document.addForm.occupation_name.focus();
			return false;
		}*/
	}
//-->
</script>

<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>

<form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Temporary Manual Entry (SICL)</td>
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

	

	<!--  -->
	<tr> 
      <td class="tbllogin" valign="top" align="right">Receipt Serial No.<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="serial_no" id="serial_no" type="text" class="inplogin"  value="<?php echo $serial_no; ?>" maxlength="7" onKeyPress="return keyRestrict(event, '0123456789')" ></td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Bank A/C<!-- <font color="#ff0000">*</font> --></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	  <input name="bank_ac" id="bank_acy" type="radio" value="1" onclick="bankdetails()" > Yes <input name="bank_ac" id="bank_acn" type="radio" value="0"  onclick="bankdetails()"> No </td>
    </tr>	

	<tr> 
      <td class="tbllogin" valign="top" align="right">IFS Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="ifs_code_main" id="ifs_code_main" type="text" class="inplogin"  value="<?php echo $ifs_code_main; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" disabled>&nbsp;
        <span class="tbllogin">
        <input type="button" name="loadmicr_new" id="loadmicr_new" value="show" onclick="showbank_new(ifs_code_main.value);" />
        </span></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">MICR Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="micr_code_main" id="micr_code_main" type="text" class="inplogin"  value="<?php echo $micr_code_main; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" disabled></td>
	   <td class="tbllogin" valign="top" align="left">&nbsp;</td>
		</tr>
	
		<tr> 
      <td class="tbllogin" valign="top" align="right">Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="bank_name_main" id="bank_name_main" type="text" class="inplogin"  value="<?php echo $bank_name_main; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" disabled></td>
    </tr>
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Branch Name with Address</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	  <textarea name="branch_name_main" id="branch_name_main" onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});" rows="5" cols="20" class="inplogin" disabled><?php echo $branch_name_main; ?></textarea>
	  </td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Account No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="account_no_main" id="account_no_main" type="text" class="inplogin"  value="<?php echo $account_no_main; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" disabled ></td>
    </tr>
	<!--  -->
	

		
		<input type="hidden" name="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
	<tr> 
      <td class="tbllogin" valign="top" align="right">Application No.<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="application_no" id="application_no" type="text" class="inplogin"  value="<?php echo $application_no; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Code<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="agent_code" id="agent_code" type="text" class="inplogin"  value="<?php echo $agent_code; ?>" maxlength="50" onKeyUp="isNumber(this);" ></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="agent_name" id="agent_name" type="text" class="inplogin"  value="<?php echo $agent_name; ?>" maxlength="50" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

    
	<tr> 
      <td class="tbllogin" valign="top" align="right">Applicant Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="first_name" id="first_name" type="text" class="inplogin"  value="<?php echo $first_name; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr style="display:none;"> 
      <td class="tbllogin" valign="top" align="right">Middle Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="middle_name" id="middle_name" type="text" class="inplogin"  value="<?php echo $middle_name; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr style="display:none;"> 
      <td class="tbllogin" valign="top" align="right">Last Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="last_name" id="last_name" type="text" class="inplogin"  value="<?php echo $last_name; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr style="display:none;"> 
      <td class="tbllogin" valign="top" align="right">Father's Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="fathers_name" id="fathers_name" type="text" class="inplogin"  value="<?php echo $fathers_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>

	<tr style="display:none"> 
      <td class="tbllogin" valign="top" align="right">Mother's Maiden Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="mothers_maiden_name" id="mothers_maiden_name" type="text" class="inplogin"  value="<?php echo $mothers_maiden_name; ?>" maxlength="255" onKeyUp="this.value = this.value.toUpperCase();"></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Applicant DOB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dob" id="dob" type="text" class="inplogin"  value="<?php echo $dob; ?>" maxlength="20" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.dob,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>

	<!-- <tr> 
      <td class="tbllogin" valign="top" align="right">Plan<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<select name="plan" id="plan" class="inplogin" onchange="return showminmax(this.value);">
				<option value="">Select</option>
				<?php 
					if($numPlan > 0)
					{
						while($getPlan = mysql_fetch_array($selPlan))
						{
							
				?>
				<option value="<?php echo $getPlan['id']; ?>" <?php echo ($getPlan['id'] == $plan ? 'selected' : ''); ?>><?php echo $getPlan['product_name']; ?></option>
				<?php
						}
					}
				?>				
			</select>
			<span id="plandesc">			</span>			</td>
    </tr> -->

	<tr> 
      <td class="tbllogin" valign="top" align="right">Plan<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	  <!--<input type="hidden" name="plan" id="plan" value="2" >-->
	  
	  <select name="plan" id="plan" class="inplogin">
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
			</select>
			</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Term<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<select name="tenure" id="tenure" class="inplogin">
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
			</select></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Payment Frequency<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
					
				
				<select name="frequency" id="frequency" class="inplogin_select" onchange="javascript:showHide(this.value)">
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
			</select>
					</td>
    </tr>
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Accidental Benefit</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="accidental_benefit" id="accidental_benefit" class="inplogin_select" onchange="javascript:showHide(this.value)">
					<option value="">Select</option>
					<option value="WAB">WAB</option>
					<option value="NAB">NAB</option>
					
				</select>		
				
				
					</td>
    </tr>
	
	

	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Sum Assured<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="sum_assured" id="sum_assured" type="text" class="inplogin"  value="<?php echo $sum_assured; ?>" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')">
	  
	  

		
	  
	  </td>
    </tr>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Age<br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="age" id="age" type="text" class="inplogin" readonly="" >
	  
	 
	  </td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Age Proof<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="age_proof" id="age_proof" class="inplogin_select" >
					<option value="">Select</option>
					<option value="STANDARD" >STANDARD</option>
					<option value="NSAP1" >NSAP1</option>
					<option value="NSAP23" >NSAP2/NASP3</option>
				</select>		
				
				<span class="tbllogin">
        <input type="button" name="loadpremium" id="loadpremium" value="Load Premium Amount" onclick="showamount(sum_assured.value,plan.value,frequency.value,tenure.value,dob.value,age_proof.value,accidental_benefit.value,age_proof.value);" />
        </span>
					</td>
    </tr>


		<tr> 
      <td class="tbllogin" valign="top" align="right">Premium Amount<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="amount" id="amount" type="text" class="inplogin"  value="<?php echo $amount; ?>"  >
	  <span class="tbllogin" style="padding-left:20px;">
        <input type="text" name="message" id="message" value="" style="width:200px; border:none; " />
        </span>

	  </td>
    </tr>	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Payment Mode<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	<select name="payment_mode" id="payment_mode" class="inplogin_select" onchange="otherpaydropdown(this.value);">
					<option value="">Select</option>
					<option value="CASH" <?php echo ($payment_mode == 'CASH' ? 'selected' : ''); ?>>Cash</option>
					<option value="CHEQUE" <?php echo ($payment_mode == 'CHEQUE' ? 'selected' : ''); ?>>Cheque</option>
					<option value="DD" <?php echo ($payment_mode == 'DD' ? 'selected' : ''); ?>>DD</option>
					<!-- <option value="ECS" <?php echo ($payment_mode == 'ECS' ? 'selected' : ''); ?>>ECS</option> -->
				</select>&nbsp;&nbsp; Amount<font color="#ff0000">*</font>&nbsp;&nbsp;<input name="main_amount" id="main_amount" type="text" class="inplogin"  value="<?php echo $main_amount; ?>" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')">			
				
		</td>
    </tr>
	<span id="otherpaydesc">			</span>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Other Payment Mode</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="other_payment_mode" id="other_payment_mode" class="inplogin_select" >
					<option value="">Select</option>
					<option value="CASH" <?php echo ($other_payment_mode == 'CASH' ? 'selected' : ''); ?>>Cash</option>
					<!-- <option value="CHEQUE" <?php echo ($other_payment_mode == 'CHEQUE' ? 'selected' : ''); ?>>Cheque</option>
					<option value="DD" <?php echo ($other_payment_mode == 'DD' ? 'selected' : ''); ?>>DD</option> -->
					<!-- <option value="ECS" <?php echo ($payment_mode == 'ECS' ? 'selected' : ''); ?>>ECS</option> -->
				</select>			
				<span class="tbllogin" style="padding-left:20px;">
        
		<input type="text" name="otheramount" id="otheramount" value="" style="width:280px; border:none;" />
        </span>
		</td>
    </tr>
	
	 
	<tr> 
      <td class="tbllogin" valign="top" align="right">DD / Cheque Date</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dd_date" id="dd_date" type="text" class="inplogin"  value="" maxlength="100" readonly /> &nbsp;<img src="images/cal.gif" id="calChq" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.dd_date,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">DD / Cheque Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dd_number" id="dd_number" type="text" class="inplogin"  value="<?php echo $dd_number; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">IFS Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="ifs_code" id="ifs_code" type="text" class="inplogin"  value="<?php echo $ifs_code; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" >&nbsp;
        <span class="tbllogin">
        <input type="button" name="loadmicr" id="loadmicr" value="show" onclick="showbank(ifs_code.value);" />
        </span></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">MICR Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="micr_code" id="micr_code" type="text" class="inplogin"  value="<?php echo $micr_code; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();"></td>
	   <td class="tbllogin" valign="top" align="left">&nbsp;</td>
		</tr>
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dd_bank_name" id="dd_bank_name" type="text" class="inplogin"  value="<?php echo $dd_bank_name; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Account Number</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="account_no" id="account_no" type="text" class="inplogin"  value="<?php echo $account_no; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">In Favour</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	  <!-- <input name="in_favour" id="in_favour" type="text" class="inplogin"  value="<?php echo $in_favour; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" > -->

	  <select name="in_favour" id="in_favour" class="inplogin_select" >
		<option value="">Select</option>
		<option value="INSURANCE COMPANY" <?php echo ($in_favour == 'INSURANCE COMPANY' ? 'selected' : ''); ?>>INSURANCE COMPANY</option>
		<option value="OUR COMPANY" <?php echo ($in_favour == 'OUR COMPANY' ? 'selected' : ''); ?>>OUR COMPANY</option>
		
	  </select>
	  
	  </td>
    </tr>
	
	<tr > 
      <td class="tbllogin" valign="top" align="right">Branch Name with Address</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	  <textarea name="dd_branch_name" id="dd_branch_name" onKeyUp="this.value = this.value.toUpperCase();" onKeyPress="$('form').keypress(function(e){
    if(e.which === 13){
        return false;
    }
});" rows="5" cols="20"  class="inplogin"><?php echo $dd_branch_name; ?></textarea>
	  </td>
    </tr>	
	
			
	<tr style="display:none;"> 
      <td class="tbllogin" valign="top" align="right">PAN </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="pan" id="pan" type="text" class="inplogin"  value="<?php echo $pan; ?>" maxlength="10" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>		


    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Add" class="inplogin" type="submit" onclick="return dochk()"> 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
    </tr>

  </tbody>
</table>
</form>

<?php //$objDB->close(); ?>

