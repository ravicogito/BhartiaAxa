<?php

function find_business_type($business_id)
{
//        echo "============";
//        die();
	$business = '';
	$selbusinessData = mysql_query("SELECT business_type FROM business_type WHERE id='".$business_id."'");
	$numbusinessData = mysql_num_rows($selbusinessData);
	if($numbusinessData > 0)
	{
		$getbusinessData = mysql_fetch_array($selbusinessData);
		$business = $getbusinessData['business_type'];
	}
	return $business;
}
function find_branch_code($branch_id)
{
	$branch_code = '';
	$selBranchData = mysql_query("SELECT branch_code FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_code = $getBranchData['branch_code'];
	}
	return $branch_code;
}

function find_plan($plan_id)
{
	$plan = '';
	$selPlanData = mysql_query("SELECT plan_name FROM insurance_plan WHERE id='".$plan_id."'");
	$numPlanData = mysql_num_rows($selPlanData);
	if($numPlanData > 0)
	{
		$getPlanData = mysql_fetch_array($selPlanData);
		$plan = $getPlanData['plan_name'];
	}
	return $plan;
}


function find_age($plan_id)
{
	$plan = '';
	$selPlanData = mysql_query("SELECT age FROM age_master WHERE id='".$plan_id."'");
	$numPlanData = mysql_num_rows($selPlanData);
	if($numPlanData > 0)
	{
		$getPlanData = mysql_fetch_array($selPlanData);
		$plan = $getPlanData['age'];
	}
	return $plan;
}


function find_sum_assured($plan_id)
{
	$plan = '';
	$selPlanData = mysql_query("SELECT sum_assured FROM sum_assured_master WHERE id='".$plan_id."'");
	$numPlanData = mysql_num_rows($selPlanData);
	if($numPlanData > 0)
	{
		$getPlanData = mysql_fetch_array($selPlanData);
		$plan = $getPlanData['sum_assured'];
	}
	return $plan;
}

function find_payment_frequency($plan_id)
{
	$plan = '';
	$selPlanData = mysql_query("SELECT frequency FROM frequency_master WHERE id='".$plan_id."'");
	$numPlanData = mysql_num_rows($selPlanData);
	if($numPlanData > 0)
	{
		$getPlanData = mysql_fetch_array($selPlanData);
		$plan = $getPlanData['frequency'];
	}
	return $plan;
}

function find_branch_user_id($branch_id)
{
	$branch_code = 0;
	$selBranchData = mysql_query("SELECT branch_user_id FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_code = $getBranchData['branch_user_id'];
	}
	return $branch_code;
}

function find_branch_user_name($branch_id)
{
	$branch_code = '';
	$selBranchData = mysql_query("SELECT name FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_code = $getBranchData['name'];
	}
	return $branch_code;
}

function find_branch_user_string($branch_admin_id)
{
	//$branch_code = '';
	$user_array = array();
	$selBranchData = mysql_query("SELECT id FROM admin WHERE branch_user_id='".$branch_admin_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		while($getBranchData = mysql_fetch_array($selBranchData))
		{		
			$user_array[] = $getBranchData['id'];
		}
	}
	$branch_code = trim(implode(',', $user_array),',');
	return $branch_code;
}

function find_branch_from_hub($branch_admin_id)
{
	//$branch_code = '';
	$user_array = array();
	$selBranchData = mysql_query("SELECT id FROM admin WHERE hub_id='".$branch_admin_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		while($getBranchData = mysql_fetch_array($selBranchData))
		{		
			$user_array[] = $getBranchData['id'];
		}
	}
	$branch_code = trim(implode(',', $user_array),',');
	return $branch_code;
}

function find_state_id_through_branch_id($branch_id)
{
	$branch_code = '';
	$selBranchData = mysql_query("SELECT state FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_code = $getBranchData['state'];
	}
	return $branch_code;
}

function find_branch_address($branch_id)
{
	$branch_code = '';
	$selBranchData = mysql_query("SELECT address FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_code = $getBranchData['address'];
	}
	return $branch_code;
}

function find_branch_id_through_code($branch_code)
{
	$branch_id = '';
	$selBranchData = mysql_query("SELECT id FROM admin WHERE branch_code='".$branch_code."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_id = $getBranchData['id'];
	}
	return $branch_id;
}


function find_premium_number($folio_id)
{
	$premium_number = 0;
	$selPremiumNumber = mysql_query("SELECT total_premium_given FROM customer_folio_no WHERE id='".$folio_id."'");
	$numPremiumNumber = mysql_num_rows($selPremiumNumber);
	if($numPremiumNumber > 0)
	{
		$getPremiumNumber = mysql_fetch_array($selPremiumNumber);
		$premium_number = $getPremiumNumber['total_premium_given'];
	}
	return intval($premium_number);
}



function find_id_through_customer_id($customer_id)
{
	$cust_id = '';
	#echo $customer_id.'<br />';
	$selCustomerID = mysql_query("SELECT id FROM customer_master WHERE customer_id='".mysql_real_escape_string($customer_id)."'");
	$numCustomerID = mysql_num_rows($selCustomerID);
	if($numCustomerID > 0)
	{
		$getCustomerID = mysql_fetch_array($selCustomerID);
		$cust_id = $getCustomerID['id'];
	}
	#echo $cust_id.'<br />';
	return $cust_id;
}

function find_name_through_id($id) // extracts name through mysql id
{
	$cust_id = '';
	$selCustomerID = mysql_query("SELECT first_name FROM customer_master WHERE id='".$id."'");
	$numCustomerID = mysql_num_rows($selCustomerID);
	if($numCustomerID > 0)
	{
		$getCustomerID = mysql_fetch_array($selCustomerID);
		$cust_id = $getCustomerID['first_name'];
	}
	return $cust_id;
}

function find_customer_id_through_id($id)
{
	$cust_id = '';
	$selCustomerID = mysql_query("SELECT customer_id FROM customer_master WHERE id='".$id."'");
	$numCustomerID = mysql_num_rows($selCustomerID);
	if($numCustomerID > 0)
	{
		$getCustomerID = mysql_fetch_array($selCustomerID);
		$cust_id = $getCustomerID['customer_id'];
	}
	return $cust_id;
}

function find_branch_name($branch_id)
{
	$branch_name = '';
	$selBranchData = mysql_query("SELECT branch_name FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_name = $getBranchData['branch_name'];
	}
	return $branch_name;
}

function realTrim($string)
{
	return mysql_real_escape_string(trim($string));
}

function totalServiceChargeGiven($application_no)
{
	$selPremium = mysql_query("SELECT SUM(transaction_charges) AS charge_given FROM installment_master WHERE application_no='".$application_no."' AND is_deleted=0");
	$getPremium = mysql_fetch_assoc($selPremium);
	return $getPremium['charge_given'];
}

function calculateServiceCharge($comittedAmount, $tenure)
{
	$service_charge_percentage = $tenure == '36' ? SERVICE_CHARGE_PERCENTAGE_36 : SERVICE_CHARGE_PERCENTAGE_OTHER;	
	$service_charge_amount = floatval($comittedAmount) * $tenure * ($service_charge_percentage / 100);
	return $service_charge_amount;
}

function service_status($service_id)
{
	$status = 1;
	$selStatus = mysql_query("SELECT status FROM entry_settings WHERE id='".$service_id."'");
	$numStatus = mysql_num_rows($selStatus);
	if($numStatus > 0)
	{
		$getStatus = mysql_fetch_array($selStatus);
		$status = $getStatus['status'];
	}
	return intval($status);
}

/*function find_hub_name($hub_id)
{
	$hub_name='';
	$sel_hub_id = mysql_query("SELECT hub_id FROM branch_hub_entry WHERE branch_id=".$hub_id." ORDER BY id DESC LIMIT 0,1");

	if(mysql_num_rows($sel_hub_id) > 0)
	{
		$get_hub_id = mysql_fetch_array($sel_hub_id);
		$hubID = $get_hub_id['hub_id'];
		$sel_branch = mysql_query("SELECT branch_name FROM admin WHERE id='".$hubID."'");

		if(mysql_num_rows($sel_branch) > 0)
		{
			$get_branch = mysql_fetch_array($sel_branch);
			$hub_name = $get_branch['branch_name'] != '' ? $get_branch['branch_name'] : 'HO';
		}
	}
	return $hub_name;		
}*/

function find_hub_name($hub_id)
{
	$hub_name='';
	
		$sel_branch = mysql_query("SELECT branch_name FROM admin WHERE id='".$hub_id."'");

		if(mysql_num_rows($sel_branch) > 0)
		{
			$get_branch = mysql_fetch_array($sel_branch);
			$hub_name = $get_branch['branch_name'];
		}

	return $hub_name;		
}
	
	
function new_find_hub_name($hub_id)
	{
		$hub_name='';
		
		$sel_branch = mysql_query("SELECT branch_name FROM admin WHERE id='".$hub_id."'");

			if(mysql_num_rows($sel_branch) > 0)
			{
				$get_branch = mysql_fetch_array($sel_branch);
				$hub_name = $get_branch['branch_name'] != '' ? $get_branch['branch_name'] : 'HO';
			}
		return $hub_name;		
	}

	function find_hub_id($hubID)
	{
		$hub_id='';
		$sel_hub_id = mysql_query("SELECT hub_id FROM admin WHERE id=".$hubID." ORDER BY id DESC LIMIT 0,1");
		#echo "SELECT hub_id FROM branch_hub_entry WHERE branch_id=".$hubID." ORDER BY id DESC LIMIT 0,1";

		if(mysql_num_rows($sel_hub_id) > 0)
		{
			$get_hub_id = mysql_fetch_array($sel_hub_id);
			$hub_id = $get_hub_id['hub_id'];			
		}
		return $hub_id;		
	}

	function find_hub_status($id)
	{
		$hub_status='4'; // 4 for branch
		$sel_hub_status = mysql_query("SELECT role_id FROM admin WHERE id=".$id."");
		#echo "SELECT hub_status FROM admin WHERE id=".$id."";

		if(mysql_num_rows($sel_hub_status) > 0)
		{
			$get_hub_status = mysql_fetch_array($sel_hub_status);
			$hub_status = $get_hub_status['role_id'];			
		}
		return $hub_status;		
	}

	function find_place_name($id)
	{
		if(empty($id)){ $id=0; }
		$hub_status=''; // 4 for branch
		$sel_hub_status = mysql_query("SELECT place FROM place_master WHERE id=".$id."");
		#echo "SELECT hub_status FROM admin WHERE id=".$id."";

		if(mysql_num_rows($sel_hub_status) > 0)
		{
			$get_hub_status = mysql_fetch_array($sel_hub_status);
			$hub_status = $get_hub_status['place'];			
		}
		return $hub_status;		
	}

	function find_tax_percentage($state_id, $commodity_id)
	{
		$hub_status=0.00; 
		$sel_hub_status = mysql_query("SELECT tax_percentage FROM tax_master WHERE place_id=".$state_id." AND commodity_id=".$commodity_id."");
		#echo "SELECT hub_status FROM admin WHERE id=".$id."";

		if(mysql_num_rows($sel_hub_status) > 0)
		{
			$get_hub_status = mysql_fetch_array($sel_hub_status);
			$hub_status = $get_hub_status['tax_percentage'];			
		}
		return $hub_status;		
	}

	function find_branch_customer($branch_id)
	{
		$hub_status=0; 
		$sel_hub_status = mysql_query("SELECT count(id) as mycount FROM customer_master WHERE branch_id=".$branch_id."");
		
		$get_hub_status = mysql_fetch_array($sel_hub_status);
		$hub_status = $get_hub_status['mycount'];		
		return $hub_status;		
	}

	function find_branch_transaction($branch_id) // This function is changed and currently it is picking up the number of transaction occured due to manual entry all over the INDIA for the current financial year // $branch_id BECOMES A DUMMY PARAMETER
	{
		date_default_timezone_set('Asia/Calcutta');

		$hub_status=0; 
		//$sel_hub_status = mysql_query("SELECT count(id) as mycount FROM installment_master WHERE branch_id=".$branch_id."");
		#$sel_hub_status = mysql_query("SELECT count(id) as mycount FROM installment_master WHERE 	deposit_date='".date('Y-m-d')."'");

		//$today = date('Y-m-d');
		//if($today >= date('Y-03-31')) // DATE BETWEEN JAN1 and MAR 31
		//{
			//$cutting_date = $today; // Last date date of a financial year
		//}

		//if($today < date('Y-03-31'))
		//{
			//$cutting_date = date('Y-03-31'); // date of the month JANUARY, FEBRUARY and MARCH 
		//}		

		$sel_hub_status = mysql_query("SELECT count(id) as mycount FROM installment_master WHERE migrated_from_dmspl = 0 AND is_deleted = 0 ");		
		
		$get_hub_status = mysql_fetch_array($sel_hub_status);
		$hub_status = $get_hub_status['mycount'];		
		return $hub_status;		
	}

	function find_product_name($product_id)
	{
		$hub_status=''; 
		$sel_hub_status = mysql_query("SELECT plan_name FROM insurance_plan WHERE id=".$product_id."");
		#echo "SELECT hub_status FROM admin WHERE id=".$id."";
		#echo "SELECT product_name FROM product_master WHERE id=".$product_id."";

		if(mysql_num_rows($sel_hub_status) > 0)
		{
			$get_hub_status = mysql_fetch_array($sel_hub_status);
			$hub_status = $get_hub_status['plan_name'];			
		}
		return $hub_status;		
	}

	function find_phase_name($product_id)
	{
		$hub_status=''; 
		$sel_hub_status = mysql_query("SELECT phase FROM phase_master WHERE id=".$product_id."");
		#echo "SELECT phase FROM phase_master WHERE id=".$product_id;
		#echo "SELECT product_name FROM product_master WHERE id=".$product_id."";

		if(mysql_num_rows($sel_hub_status) > 0)
		{
			$get_hub_status = mysql_fetch_array($sel_hub_status);
			$hub_status = $get_hub_status['phase'];			
		}
		return $hub_status;		
	}
	
	function find_installment_name($product_id)
	{
		$hub_status=''; 
		$sel_hub_status = mysql_query("SELECT installment_no FROM installment_no WHERE id=".$product_id."");
		#echo "SELECT hub_status FROM admin WHERE id=".$id."";
		#echo "SELECT product_name FROM product_master WHERE id=".$product_id."";

		if(mysql_num_rows($sel_hub_status) > 0)
		{
			$get_hub_status = mysql_fetch_array($sel_hub_status);
			$hub_status = $get_hub_status['installment_no'];			
		}
		return $hub_status;		
	}

	function find_premium_multiple($product_id)
	{
		$premium_multiple=0; 
		$sel_hub_status = mysql_query("SELECT premium_multiple FROM product_master WHERE id=".$product_id."");
		#echo "SELECT premium_multiple FROM product_master WHERE id=".$product_id."";

		if(mysql_num_rows($sel_hub_status) > 0)
		{
			$get_hub_status = mysql_fetch_array($sel_hub_status);
			$premium_multiple = $get_hub_status['premium_multiple'];			
		}
		return $premium_multiple;		
	}

	function find_min_amount($product_id)
	{
		$premium_multiple=0; 
		$sel_hub_status = mysql_query("SELECT min_amount FROM product_master WHERE id=".$product_id."");
		#echo "SELECT hub_status FROM admin WHERE id=".$id."";

		if(mysql_num_rows($sel_hub_status) > 0)
		{
			$get_hub_status = mysql_fetch_array($sel_hub_status);
			$premium_multiple = $get_hub_status['min_amount'];			
		}
		return $premium_multiple;		
	}

	function find_max_amount($product_id)
	{
		$premium_multiple=0; 
		$sel_hub_status = mysql_query("SELECT max_amount FROM product_master WHERE id=".$product_id."");
		#echo "SELECT hub_status FROM admin WHERE id=".$id."";

		if(mysql_num_rows($sel_hub_status) > 0)
		{
			$get_hub_status = mysql_fetch_array($sel_hub_status);
			$premium_multiple = $get_hub_status['max_amount'];			
		}
		return $premium_multiple;		
	}

	function find_first_name($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT first_name FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['first_name'];
		}
		return $cust_id;
	}

	function find_id_through_first_name($first_name)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT id FROM customer_master WHERE first_name LIKE '%".$first_name."%'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			while($getCustomerID = mysql_fetch_array($selCustomerID))
			{
				$cust_id_arr[] = $getCustomerID['id'];
			}
			if(count($cust_id_arr) > 0)
			{
				$cust_id = implode(',',$cust_id_arr);
			}
		}
		return trim($cust_id,',');
	}

	function find_id_through_last_name($last_name)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT id FROM customer_master WHERE last_name LIKE '%".$last_name."%'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			while($getCustomerID = mysql_fetch_array($selCustomerID))
			{
				$cust_id_arr[] = $getCustomerID['id'];
			}
			if(count($cust_id_arr) > 0)
			{
				$cust_id = implode(',',$cust_id_arr);
			}
		}
		return trim($cust_id,',');
	}

	function find_last_name($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT last_name FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['last_name'];
		}
		return $cust_id;
	}
	function find_middle_name($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT middle_name FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['middle_name'];
		}
		return $cust_id;
	}
	function find_phone($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT phone FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['phone'];
		}
		return $cust_id;
	}
	function find_email($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT email FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['email'];
		}
		return $cust_id;
	}

	function find_tenure($tenure_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT tenure FROM tenure_master WHERE id='".$tenure_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['tenure'];
		}
		return $cust_id;
	}

	function find_bonus_percentage($tenure_id)
	{
		$cust_id = 0.00;
		$selCustomerID = mysql_query("SELECT bonus_percentage FROM tenure_master WHERE id='".$tenure_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['bonus_percentage'];
		}
		return $cust_id;
	}

	function find_dob($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT dob_original FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['dob_original'];
		}
		return $cust_id;
	}

	function find_fathers_name($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT fathers_name FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['fathers_name'];
		}
		return $cust_id;
	}

	function find_guardian_name($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT guardian_name FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['guardian_name'];
		}
		return $cust_id;
	}

	function find_address($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT address1 FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['address1'];
		}
		return $cust_id;
	}

	function find_gender($customer_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT gender FROM customer_master WHERE id='".$customer_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['gender'];
		}
		return $cust_id;
	}

	function find_folio_id($folio_number)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT id FROM customer_folio_no WHERE 	folio_no='".$folio_number."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['id'];
		}
		return $cust_id;
	}

	
	
	function find_product_id_through_folio_id($folio_number)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT product_id FROM customer_folio_no WHERE 	id='".$folio_number."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['product_id'];
		}
		return $cust_id;
	}

	function find_customer_id_through_folio_id($folio_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT customer_id FROM customer_folio_no WHERE 	id='".$folio_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['customer_id'];
		}
		return $cust_id;
	}

	/*function find_customer_id_through_installment_id($transaction_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT customer_id FROM installment_master WHERE 	id='".$transaction_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['customer_id'];
		}
		return $cust_id;
	}*/	

	function kyc_ok($customer_id)
	{
		$status = 0;
		$selMasterRecord = mysql_query("SELECT age_proof, id_proof, address_proof FROM customer_master WHERE id='".$customer_id."'");

			if(mysql_num_rows($selMasterRecord) > 0)
			{	
				$getMasterRecord = mysql_fetch_array($selMasterRecord);
				if((intval($getMasterRecord['age_proof']) != 0) && (intval($getMasterRecord['id_proof']) != 0) && (intval($getMasterRecord['address_proof']) != 0))
				{
					$status = 1;
				}
			}
		return $status;
	}

	function find_commodity_name($commodity_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT commodity FROM commodity_master WHERE 	id='".$commodity_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['commodity'];
		}
		return $cust_id;
	}

	function find_age_proof($document_id)
	{
		$cust_id = '';
		#echo "SELECT document_name FROM age_proof WHERE 	id='".$document_id."'";
		$selCustomerID = mysql_query("SELECT document_name FROM age_proof WHERE 	id='".$document_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['document_name'];
		}
		return $cust_id;
	}

	function find_id_proof($document_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT document_name FROM id_proof WHERE 	id='".$document_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['document_name'];
		}
		return $cust_id;
	}

	function find_income_proof($document_id)
	{
		$cust_id = '';
		$selCustomerID = mysql_query("SELECT document_name FROM income_proof WHERE 	id='".$document_id."'");
		$numCustomerID = mysql_num_rows($selCustomerID);
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['document_name'];
		}
		return $cust_id;
	}

	function find_address_proof($document_id)
	{
		$cust_id = '';                
                $query = "SELECT document_name FROM address_proof WHERE 	id='".$document_id."'";      
                
		$selCustomerID = mysql_query($query);
		$numCustomerID = mysql_num_rows($selCustomerID);
           
		if($numCustomerID > 0)
		{
			$getCustomerID = mysql_fetch_array($selCustomerID);
			$cust_id = $getCustomerID['document_name'];
                        
		}
		return $cust_id;
	}

	function find_occupation_id($occupation)
	{
		$occupation_id = 0;
		$selOccupationData = mysql_query("SELECT id FROM occupation_master WHERE occupation='".realTrim($occupation)."'");
		$numOccupationData = mysql_num_rows($selOccupationData);
		if($numOccupationData > 0)
		{
			$getOccupationData = mysql_fetch_array($selOccupationData);
			$occupation_id = $getOccupationData['id'];
		}
		return $occupation_id;
	}

	function find_relationship_id($occupation)
	{
		$occupation_id = 0;
		$selOccupationData = mysql_query("SELECT id FROM relationship_master WHERE relationship='".realTrim($occupation)."'");
		$numOccupationData = mysql_num_rows($selOccupationData);
		if($numOccupationData > 0)
		{
			$getOccupationData = mysql_fetch_array($selOccupationData);
			$occupation_id = $getOccupationData['id'];
		}
		return $occupation_id;
	}

	function find_micr_id($occupation)
	{
		$occupation_id = 0;
		$selOccupationData = mysql_query("SELECT id FROM micr_master WHERE micr_code='".realTrim($occupation)."'");
		$numOccupationData = mysql_num_rows($selOccupationData);
		if($numOccupationData > 0)
		{
			$getOccupationData = mysql_fetch_array($selOccupationData);
			$occupation_id = $getOccupationData['id'];
		}
		return $occupation_id;
	}


	########### RETURNS ALL DATA FROM A TABLE FOR A ROW ######################

	function findFolioData($id)
	{
		$folioArray = array();
		$selFolioData = mysql_query("SELECT * FROM customer_folio_no WHERE id='".$id."'");
		$numFolioData = mysql_num_rows($selFolioData);
		if($numFolioData > 0)
		{
			$folioArray = mysql_fetch_array($selFolioData);
		}
		return $folioArray;
	}

	function findCustomerData($id)
	{
		$customerArray = array();
		$selCustomerData = mysql_query("SELECT * FROM customer_master WHERE id='".$id."'");
		$numCustomerData = mysql_num_rows($selCustomerData);
		if($numCustomerData > 0)
		{
			$customerArray = mysql_fetch_array($selCustomerData);
		}
		return $customerArray;
	}

	function findOldPisId($date, $pis_mode, $branch_id)
	{
		$pis_id = 0;
		$selPisData = mysql_query("SELECT id FROM pis_master WHERE branch_id='".$branch_id."' AND pis_date='".$date."' AND pis_mode='".$pis_mode."'");
		$numPisData = mysql_num_rows($selPisData);
		if($numPisData > 0)
		{
			$getPisData = mysql_fetch_array($selPisData);
			$pis_id = $getPisData['id'];
		}
		return $pis_id;
	}

	function findRedemptionData($id)
	{
		$customerArray = array();
		$selCustomerData = mysql_query("SELECT * FROM installment_master WHERE id='".$id."'");
		$numCustomerData = mysql_num_rows($selCustomerData);
		if($numCustomerData > 0)
		{
			$customerArray = mysql_fetch_array($selCustomerData);
		}
		return $customerArray;
	}
	
	function find_payment_number($application_no)
	{
		$selPremium = mysql_query("SELECT count(id) AS payment_counter FROM installment_master WHERE application_no='".$application_no."' AND is_deleted=0");
		$getPremium = mysql_fetch_assoc($selPremium);
		return intval($getPremium['payment_counter']); 
	}

function find_service_tax_percentage($place_id)
	{
		$occupation_id = 0.00;
		$selOccupationData = mysql_query("SELECT service_tax_percentage FROM service_tax_master WHERE  	place_id='".realTrim($place_id)."'");
		$numOccupationData = mysql_num_rows($selOccupationData);
		if($numOccupationData > 0)
		{
			$getOccupationData = mysql_fetch_array($selOccupationData);
			$occupation_id = $getOccupationData['service_tax_percentage'];
		}
		return $occupation_id;
	}

	function find_state_id_through_branch_code($branch_code)
{
	$state_id = '';
	//echo "SELECT state FROM admin WHERE branch_code='".$branch_code."'<br />";
	$selBranchData = mysql_query("SELECT state FROM admin WHERE branch_code='".$branch_code."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$state_id = $getBranchData['state'];
	}
	return $state_id;
}

function xmlEscape($string) 
	{
			return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
	}

function find_gap_status($folio_id) // This function will determine whether this is coming from GAP
	{
		$gap_status = 0;
		$selGap_status = mysql_query("SELECT migrated_from_dmspl FROM installment_master WHERE 	folio_no_id='".$folio_id."' AND migrated_from_dmspl=2");
		$numGap_status = mysql_num_rows($selGap_status);
		if($numGap_status > 0)
		{
			$getGap_status = mysql_fetch_array($selGap_status);
			$gap_status = $getGap_status['migrated_from_dmspl'];
		}
		return $gap_status;
	}

	###############


	function find_premium_id($plan_id, $age_id, $term, $rate,$age_proof,$extra_amount_rate)
	{
		$premium_id = '0';
		
		
		
		$premiumQuery = "SELECT id FROM new_plan_rate 
							WHERE plan_id='".realTrim($plan_id)."' 
							AND age_id='".realTrim($age_id)."' 
							AND term_id='".$term."' 
							AND rate='".$rate."'
							AND age_proof='".$age_proof."' 
							AND extra_amount_rate='".$extra_amount_rate;

		$selPremiumID = mysql_query($premiumQuery);

		echo $premiumQuery.'<br />';
		$numPremiumID = mysql_num_rows($selPremiumID);
		if($numPremiumID > 0)
		{
			$getPremiumID = mysql_fetch_array($selPremiumID);
			print_r($getPremiumID);
			$premium_id = $getPremiumID['id'];
		}
		#echo $cust_id.'<br />';
		return $premium_id;
	} 

	function find_plan_id($record)
	{
		$record_id = 0;
		$selData = mysql_query("SELECT id FROM insurance_plan WHERE plan_name='".realTrim($record)."'");
		$numData = mysql_num_rows($selData);
		if($numData > 0)
		{
			$getData = mysql_fetch_array($selData);
			$record_id = $getData['id'];
		}
		return $record_id;
	}

	function find_age_id($record)
	{
		$record_id = 0;
		$selData = mysql_query("SELECT id FROM age_master WHERE age='".realTrim($record)."'");
		$numData = mysql_num_rows($selData);
		if($numData > 0)
		{
			$getData = mysql_fetch_array($selData);
			$record_id = $getData['id'];
		}
		return $record_id;
	}

	function find_sum_assured_id($record)
	{
		$record_id = 0;
		$selData = mysql_query("SELECT id FROM sum_assured_master WHERE sum_assured='".realTrim($record)."'");
		$numData = mysql_num_rows($selData);
		if($numData > 0)
		{
			$getData = mysql_fetch_array($selData);
			$record_id = $getData['id'];
		}
		return $record_id;
	}

	function find_payment_frequency_id($record)
	{
		$record_id = 0;
		$selData = mysql_query("SELECT id FROM frequency_master WHERE frequency='".realTrim($record)."'");
		$numData = mysql_num_rows($selData);
		if($numData > 0)
		{
			$getData = mysql_fetch_array($selData);
			$record_id = $getData['id'];
		}
		return $record_id;
	}



?>