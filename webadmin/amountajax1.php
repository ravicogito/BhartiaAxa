<?php
//print_r($_POST);
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
$objDB = new DB();

$dob = $_POST['dob'];
$dobArr=explode('-',$dob);
$formatted_dob = $dobArr[2].'-'.$dobArr[1].'-'.$dobArr[0];
$age = (strtotime(date('Y-m-d')) - strtotime($formatted_dob))/(60*60*24*365.25);

//if($age>'50')
//{
//$floorAge = '51';
//}
//else
//{
$floorAge = floor($age);
//}


//$fractionalYear = ((5*30.5)+14)/365;  //Fraction of an year for 5 month and 14 days for LIC
//echo $age;

$effectiveAge = $floorAge;





if(($effectiveAge<'18'))
{
$premium_value ='';
$service_tax ='';
$total_value ='';
$message = "Policy is not acceptable under 18 years";

}
if(($effectiveAge>'50'))
{
$premium_value ='';
$service_tax ='';
$total_value ='';
$message = "Policy is not acceptable above 50 years";

}


$fractionalYear = ((5*30.5)+16)/365;  //Fraction of an year for 5 month and 30 days for LIC
//echo $age;
$effectiveAge = $floorAge;
//echo $age;
//echo $floorAge;
//exit;

if($age<'18')
{
$effectiveAge = '17';
}
else if(($age - $floorAge) >= $fractionalYear )
{
$effectiveAge = $floorAge + 1;
}
else
{
$effectiveAge = $floorAge;
}


$age_id = find_age_id($effectiveAge);

$plan = $_POST['plan'];

$sum_assured = $_POST['sum_assured'];

$term = $_POST['tenure'];

$frequency = $_POST['frequency'];

$age_proof = $_POST['age_proof'];

//$effectiveAge;

//exit;
//echo "SELECT rate FROM new_plan_rate WHERE plan_id='".$plan."' AND age_id = '".$age_id."' AND term_id ='".$term."' AND age_proof = '".$age_proof."'";
//exit;
$selData = mysql_query("SELECT rate,extra_amount_rate FROM new_plan_rate WHERE plan_id='".$plan."' AND age_id = '".$age_id."' AND term_id ='".$term."' AND age_proof = '".$age_proof."'");
		$numData = mysql_num_rows($selData);
		if($numData > 0)
		{
			$getData = mysql_fetch_array($selData);
			$rate = $getData['rate'];
			$extra_amount_rate = $getData['extra_amount_rate'];
		}
		else
		{
		$premium_value ='0';
		$service_tax ='0';
		$total_value ='0';
		$message = "Please Enter Fields Properly";
		}
		

//echo "SELECT * FROM  `new_rate_calculation` WHERE plan_id='".$plan."' AND frequency_id = '".$frequency."' AND '".$sum_assured."' BETWEEN min_renge AND max_renge";
//exit;
$selDataCal = mysql_query("SELECT * FROM  `new_rate_calculation` WHERE plan_id='".$plan."' AND frequency_id = '".$frequency."' AND '".$sum_assured."' BETWEEN min_renge AND max_renge");
		$numDataCal = mysql_num_rows($selDataCal);
		if($numDataCal > 0)
		{
			$getDataCal = mysql_fetch_array($selDataCal);
			$mode_rebate = $getDataCal['mode_rebate'];
			$sum_assured_rebate = $getDataCal['sum_assured_rebate'];
			$accidental_benifit_add = $getDataCal['accidental_benifit_add'];
		}
		else
		{
		$premium_value ='0';
		$service_tax ='0';
		$total_value ='0';
		$message = "Please Enter Fields Properly";
		}


if(($term == '5')||($term == '6')||($term == '7')||($term == '10')||($term == '12')||($term == '20')){

$premium_value ='';
$service_tax ='';
$total_value ='';
$message = "Please Enter Fields Properly";
}

else if(($frequency == '1'))
{
$premium_value ='';
$service_tax ='';
$total_value ='';
$message = "Term quarterly is not acceprable";

}
else if(($sum_assured < '100000'))
{
$premium_value ='';
$service_tax ='';
$total_value ='';
$message = "Premium Amount should be minimum 100000";

}
else if(($sum_assured > '2000000'))
{
$premium_value ='';
$service_tax ='';
$total_value ='';
$message = "Premium Amount should be maximun 2000000";

}

else if(($effectiveAge<'18'))
{
$premium_value ='';
$service_tax ='';
$total_value ='';
$message = "Policy is not acceptable under 18 years";

}
else if(($effectiveAge>'50'))
{
$premium_value ='';
$service_tax ='';
$total_value ='';
$message = "Policy is not acceptable above 50 years";

}




else
	{
		if($frequency == '3')
{$premium_value=ceil(($rate-(($rate*$mode_rebate)/100)-$sum_assured_rebate+$accidental_benifit_add+$extra_amount_rate)*($sum_assured/1000));
}
else if($frequency == '2')
{
$premium_value = ceil((($rate-(($rate*$mode_rebate)/100)-$sum_assured_rebate+$accidental_benifit_add+$extra_amount_rate)/2)*($sum_assured/1000));
}
else
{
$premium_value = 0;
}
}
//exit;




if(($term == '5')||($term == '6')||($term == '7')||($term == '10')||($term == '12')||($term == '20'))
{
$premium_value ='';
$service_tax ='';
$total_value ='';
$message = "Please Enter Fields Properly";

}




if($premium_value != ""){
$service_tax = round($premium_value * (3.09/100)); 
$total_value = $premium_value + $service_tax;
$message = "You need to pay Rs. ".$total_value." /-";
}
else
{
$premium_value ='';
$service_tax ='';
$total_value ='';
if($message != "")
	{
$message = $message;
	}else
	{
$message = "Please Enter Fields Properly";
	}
}
$premiumarr[] = $premium_value;
$premiumarr[] = $effectiveAge;
$premiumarr[] = $service_tax;
$premiumarr[] = $total_value;
$premiumarr[] = $message;

echo json_encode($premiumarr);
?>