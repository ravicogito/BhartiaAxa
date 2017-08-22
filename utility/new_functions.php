<?php
	// Write functions here
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

function find_premium_number($customer_id)
{
	$premium_number = 0;
	$selPremiumNumber = mysql_query("SELECT total_premium_given FROM customer_master WHERE id='".$customer_id."'");
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
	$selCustomerID = mysql_query("SELECT id FROM customer_master WHERE customer_id='".mysql_real_escape_string($customer_id)."'");
	$numCustomerID = mysql_num_rows($selCustomerID);
	if($numCustomerID > 0)
	{
		$getCustomerID = mysql_fetch_array($selCustomerID);
		$cust_id = $getCustomerID['id'];
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
function find_hub_name($hub_id)
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
}
?>