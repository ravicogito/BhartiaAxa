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
$floorAge = floor($age);

$fractionalYear = ((5*30.5)+14)/365;  //Fraction of an year for 5 month and 14 days for LIC
//echo $age;
$effectiveAge = $floorAge;
if(($age - $floorAge) >= $fractionalYear )
{
$effectiveAge = $floorAge + 1;
}
//echo $effectiveAge;
//exit;
//$age_id = find_age_id($effectiveAge);

$age_id='0';

$selData = mysql_query("SELECT id FROM age_master WHERE age='".$effectiveAge."'");
		$numData = mysql_num_rows($selData);
		if($numData > 0)
		{
			$getData = mysql_fetch_array($selData);
			$age_id = $getData['id'];
		}



//echo $age_id;
//exit;
$plan = $_POST['plan'];
//echo $plan;
//exit;
$sum_assured = $_POST['sum_assured'];
$term = $_POST['tenure'];
$frequency = $_POST['frequency'];
$age_proof = $_POST['age_proof'];
//exit;
$accidental_benefit = $_POST['accidental_benefit'];
//echo $accidental_benefit;
//exit;
//$sum_assured_id = find_sum_assured_id($sum_assured);
$premiumarr = array();

$where = '';

$selData = mysql_query("SELECT id FROM sum_assured_master WHERE sum_assured='".$sum_assured."'");
		$numData = mysql_num_rows($selData);
		if($numData > 0)
		{
			$getData = mysql_fetch_array($selData);
			$sum_assured_id = $getData['id'];
		}
		
		if((isset($accidental_benefit)) && ($accidental_benefit != ""))
		{
			$where.= " AND accidental_benefit='".$accidental_benefit."'";
		}

		//if(isset($age_proof) && ($age_proof != ""))
		//{
			//$where.= " AND age_proof='".$age_proof."'";
		//}

$sql = "select premium_value from premium_master where plan = '".$plan."' AND age = '".$age_id."' AND term ='".$term."' AND payment_frequency = '".$frequency."' AND sum_assured = ".$sum_assured_id." ".$where;
#echo $sql;
//exit;

if($plan != '5' && $plan != '10'){
$setPremiumamount = mysql_query($sql);
$numPremiumamount = mysql_num_rows($setPremiumamount);
if($numPremiumamount > 0)
{
	$getPremiumamount = mysql_fetch_array($setPremiumamount);
	//print_r($getPremiumamount);
	//exit;


if($age_proof == 'NSAP23')
	{

	//================*For plan-14*==========//
	if($plan == '1')
	{
	if($age_proof == 'NSAP23')
	{
		if($term == '20')
		{
			if($frequency == '3')
			{
				if($effectiveAge < '44')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0015));
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '44')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00155));
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '45')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0017));
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '46')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00185));
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '47')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00205));
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '48')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00225));
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '49')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0025));
				$premium_value = ceil($premium_value);
				}
				else
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0027));
				$premium_value = ceil($premium_value);
				}
			}




			if($frequency == '2')
			{
				if($effectiveAge < '44')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/2);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '44')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00155))/2);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '45')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0017))/2);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '46')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00185))/2);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '47')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00205))/2);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '48')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00225))/2);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '49')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0025))/2);
				$premium_value = ceil($premium_value);
				}
				else
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0027))/2);
				$premium_value = ceil($premium_value);
				}
			}


			if($frequency == '1')
			{
				if($effectiveAge < '44')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/4);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '44')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00155))/4);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '45')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0017))/4);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '46')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00185))/4);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '47')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00205))/4);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '48')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00225))/4);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '49')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0025))/4);
				$premium_value = ceil($premium_value);
				}
				else
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0027))/4);
				$premium_value = ceil($premium_value);
				}
			}

		}
		if($term == '15')
		{
			if($frequency == '3')
			{
				if($effectiveAge < '47')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0015));
				$premium_value = ceil($premium_value);
				}
				
				else if($effectiveAge == '47')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0016));
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '48')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00175));
				$premium_value = ceil($premium_value);
				}

				else if($effectiveAge == '49')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00195));
				$premium_value = ceil($premium_value);
				}
				else
				{
				$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00215));
				$premium_value = ceil($premium_value);
				}
			}

			if($frequency == '2')
			{
				if($effectiveAge < '47')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/2);
				$premium_value = ceil($premium_value);
				}
				
				else if($effectiveAge == '47')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/2);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '48')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/2);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '49')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00195))/2);
				$premium_value = ceil($premium_value);
				}
				else
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00215))/2);
				$premium_value = ceil($premium_value);
				}
			}

			if($frequency == '1')
			{
				if($effectiveAge < '47')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/4);
				$premium_value = ceil($premium_value);
				}
				
				else if($effectiveAge == '47')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/4);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '48')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/4);
				$premium_value = ceil($premium_value);
				}
				else if($effectiveAge == '49')
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00195))/4);
				$premium_value = ceil($premium_value);
				}
				else
				{
				$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00215))/4);
				$premium_value = ceil($premium_value);
				}
			}
		}
	}
	
	else
	{
	$premium_value = $getPremiumamount['premium_value'];
	}
	}

//================*end of plan-14*==========//



//================*For plan-165*==========//
	if($plan == '8')
	{
	if($age_proof == 'NSAP23')
	{
		if($term == '15')
		{
			if($sum_assured == '62500')
			{
				if($frequency == '3')
				{

					if($effectiveAge < '41')
					{
					$premium_value = $getPremiumamount['premium_value']+94 ;
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+101;
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+113;
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+125;
					}
					
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+137;
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+149;
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+168;
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+187;
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+207;
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+226;
					}
				}



				if($frequency == '2')
				{

					if($effectiveAge < '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(94/2) ;
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(101/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(113/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(125/2);
					$premium_value = ceil($premium_value);
					}
					
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(137/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(149/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(168/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(187/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(207/2);
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(226/2);
					$premium_value = ceil($premium_value);
					}
				}



				if($frequency == '1')
				{

					if($effectiveAge < '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(94/4) ;
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(101/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(113/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(125/4);
					$premium_value = ceil($premium_value);
					}
					
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(137/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(149/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(168/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(187/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(207/4);
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(226/4);
					$premium_value = ceil($premium_value);
					}
				}
		}

		if($sum_assured == '75000')
			{
			if($frequency == '3')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+112 ;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+122;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+136;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+150;
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+164;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+178;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+202;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+225;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+248;
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+271;
						$premium_value = ceil($premium_value);
						}
				}

				if($frequency == '2')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(112/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(122/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(136/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(150/2);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(164/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(178/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(202/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(225/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(248/2);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(271/2);
						$premium_value = ceil($premium_value);
						}
				}


				if($frequency == '1')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(112/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(122/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(136/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(150/4);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(164/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(178/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(202/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(225/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(248/4);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(271/4);
						$premium_value = ceil($premium_value);
						}
				}
		}

		if($sum_assured == '100000')
			{
			if($frequency == '3')
				{

						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+150 ;
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+162;
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+181;
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+200;
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+219;
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+238;
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+269;
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+300;
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+330;
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+361;
						}
				}


				if($frequency == '2')
				{

						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(150/2) ;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(162/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(181/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(200/2);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(219/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(238/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(269/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(300/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(330/2);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(361/2);
						$premium_value = ceil($premium_value);
						}
				}

				if($frequency == '1')
				{

						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(150/4) ;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(162/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(181/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(200/4);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(219/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(238/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(269/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(300/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(330/4);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(361/4);
						$premium_value = ceil($premium_value);
						}
				}
		}

		if($sum_assured == '150000')
			{
			if($frequency == '3')
				{

						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+225 ;
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+244;
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+272;
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+300;
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+329;
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+357;
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+403;
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+449;
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+496;
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+542;
						}
				}
				
				
				if($frequency == '2')
				{

						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(225/2) ;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(244/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(272/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(300/2);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(329/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(357/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(403/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(449/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(496/2);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(542/2);
						$premium_value = ceil($premium_value);
						}
				}


				if($frequency == '1')
				{

						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(225/4) ;
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(244/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(272/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(300/4);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(329/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(357/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(403/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(449/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(496/4);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(542/4);
						$premium_value = ceil($premium_value);
						}
				}
		}
		
		if($sum_assured == '200000')
			{
			if($frequency == '3')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+300;
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+325;
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+363;
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+400;
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+438;
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+476;
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+538;
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+599;
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+661;
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+723;
						}
				}
				if($frequency == '2')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(300/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(325/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(363/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(400/2);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(438/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(476/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(538/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(599/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(661/2);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(723/2);
						$premium_value = ceil($premium_value);
						}
				}

				if($frequency == '1')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(300/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(325/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(363/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(400/4);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(438/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(476/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(538/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(599/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(661/4);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(723/4);
						$premium_value = ceil($premium_value);
						}
				}
		}

		if($sum_assured == '250000')
			{
				if($frequency == '3')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+375;
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+406;
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+453;
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+500;
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+548;
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+595;
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+672;
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+749;
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+826;
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+903;
						}
				}

				if($frequency == '2')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(375/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(406/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(453/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(500/2);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(548/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(595/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(672/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(749/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(826/2);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(903/2);
						$premium_value = ceil($premium_value);
						}
				}

				if($frequency == '1')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(375/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(406/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(453/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(500/4);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(548/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(595/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(672/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(749/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(826/4);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(903/4);
						$premium_value = ceil($premium_value);
						}
				}
		}


		if($sum_assured == '300000')
			{
			if($frequency == '3')
				{

						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+450;
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+487;
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+544;
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+600;
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+657;
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+714;
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+806;
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+899;
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+991;
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+1084;
						}
				}


				if($frequency == '2')
				{

						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(450/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(487/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(544/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(600/2);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(657/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(714/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(806/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(899/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(991/2);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1084/2);
						$premium_value = ceil($premium_value);
						}
				}


				if($frequency == '1')
				{

						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(450/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(487/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(544/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(600/4);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(657/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(714/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(806/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(899/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(991/4);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1084/4);
						$premium_value = ceil($premium_value);
						}
				}
		}

		if($sum_assured == '400000')
			{
				if($frequency == '3')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+600;
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+649;
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+725;
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+801;
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+876;
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+952;
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+1075;
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+1199;
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+1322;
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+1445;
						}
				}
				if($frequency == '2')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(600/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(649/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(725/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(801/2);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(876/2);
						$premium_value = ceil($premium_value);

						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(952/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1075/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1199/2);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1322/2);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1445/2);
						$premium_value = ceil($premium_value);
						}
				}

				if($frequency == '1')
				{
						if($effectiveAge < '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(600/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '41')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(649/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '42')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(725/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '43')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(801/4);
						$premium_value = ceil($premium_value);
						}
						
						else if($effectiveAge == '44')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(876/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '45')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(952/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '46')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1075/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '47')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1199/4);
						$premium_value = ceil($premium_value);
						}
						else if($effectiveAge == '48')
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1322/4);
						$premium_value = ceil($premium_value);
						}
						
						else
						{
						$premium_value = $getPremiumamount['premium_value']+ceil(1445/4);
						$premium_value = ceil($premium_value);
						}
				}
		}



		}
		
	}
	
	else
	{
	$premium_value = $getPremiumamount['premium_value'];
	}
	}

//================*end of plan-165*==========//



//================for plan-149=========//

	if($plan == '7')
	{
	if($age_proof == 'NSAP23')
	{
		if($term == '21')
		{
			if($frequency == '3')
				{
					if($effectiveAge < '37')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.0015));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00151));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00156));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00162));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00159));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00164));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00169));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00174));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00183));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00192));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00201));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00206));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00224));
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00232));
					$premium_value = ceil($premium_value);
					}
				}

				if($frequency == '2')
				{
					if($effectiveAge < '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00151))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00156))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00162))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00159))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00164))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00169))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00174))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00183))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00192))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00201))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00206))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00224))/2);
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00232))/2);
					$premium_value = ceil($premium_value);
					}
				}


				if($frequency == '1')
				{
					if($effectiveAge < '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00151))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00156))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00162))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00159))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00164))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00169))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00174))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00183))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00192))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00201))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00206))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00224))/4);
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00232))/4);
					$premium_value = ceil($premium_value);
					}
				}
		}
		if($term == '16')
		{
			if($frequency == '3')
				{
					if($effectiveAge < '37')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.0015));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00151));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00156));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00162));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00168));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00173));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00182));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00191));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00201));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00207));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00217));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00226));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00242));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00247));
					$premium_value = ceil($premium_value);
					}
					else
					{
					$premium_value = $getPremiumamount['premium_value']+($sum_assured*(.00258));
					$premium_value = ceil($premium_value);
					}
				}


				if($frequency == '2')
				{
					if($effectiveAge < '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00151))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00156))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00162))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00168))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00173))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00182))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00191))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00201))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00207))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00217))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00226))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00242))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00247))/2);
					$premium_value = ceil($premium_value);
					}
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00258))/2);
					$premium_value = ceil($premium_value);
					}
				}
				if($frequency == '1')
				{
					if($effectiveAge < '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00151))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00156))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00162))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00168))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00173))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00182))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00191))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00201))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00207))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00217))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00226))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00242))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00247))/4);
					$premium_value = ceil($premium_value);
					}
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00258))/4);
					$premium_value = ceil($premium_value);
					}
				}
		}
	}
	
	else
	{
	$premium_value = $getPremiumamount['premium_value'];
	}
	}

//================*end of plan-149*==========//




//================for plan-178=========//



	if($plan == '2')
	{
	if($age_proof == 'NSAP23')
	{
		if($term == '20')
		{
			if($frequency == '3')
				{
					if($effectiveAge < '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0015));
					$premium_value = ceil($premium_value);
					}
					
					
					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00155));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0016));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0017));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0018));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0019));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.002));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0021));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0022));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00235));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0025));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00265));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00280));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00295));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00315));
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00335));
					$premium_value = ceil($premium_value);
					}
				}

				if($frequency == '2')
				{
					if($effectiveAge < '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/2);
					$premium_value = ceil($premium_value);
					}
					
					
					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00155))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0017))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0018))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0019))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.002))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0021))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0022))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00235))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0025))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00265))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00280))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00295))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00315))/2);
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00335))/2);
					$premium_value = ceil($premium_value);
					}
				}

				if($frequency == '1')
				{
					if($effectiveAge < '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/4);
					$premium_value = ceil($premium_value);
					}
					
					
					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00155))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0017))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0018))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0019))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.002))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0021))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0022))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00235))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0025))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00265))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00280))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00295))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00315))/4);
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00335))/4);
					$premium_value = ceil($premium_value);
					}
				}
		}
		if($term == '15')
		{
			if($frequency == '3')
				{
					if($effectiveAge < '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0015));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0016));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '35')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00165));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00175));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0018));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0019));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.002));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00205));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00215));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0023));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0024));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0025));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00265));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0028));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00295));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0031));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0033));
					$premium_value = ceil($premium_value);
					}
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00345));
					$premium_value = ceil($premium_value);
					}
				}


				if($frequency == '2')
				{
					if($effectiveAge < '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '35')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00165))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0018))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0019))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.002))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00205))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00215))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0023))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0024))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0025))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00265))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0028))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00295))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0031))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0033))/2);
					$premium_value = ceil($premium_value);
					}
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00345))/2);
					$premium_value = ceil($premium_value);
					}
				}


				if($frequency == '1')
				{
					if($effectiveAge < '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '35')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00165))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0018))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0019))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.002))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00205))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00215))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0023))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0024))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0025))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00265))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0028))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00295))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0031))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0033))/4);
					$premium_value = ceil($premium_value);
					}
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00345))/4);
					$premium_value = ceil($premium_value);
					}
				}
		}
	}
	
	else
	{
	$premium_value = $getPremiumamount['premium_value'];
	}
	}

	//================*end of plan-178*==========//








//================for plan-179=========//



	if($plan == '3')
	{
	if($age_proof == 'NSAP23')
	{
		if($term == '16')
		{
			if($frequency == '3')
				{
					if($effectiveAge < '28')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0015));
					$premium_value = ceil($premium_value);
					}
					
					else if($effectiveAge == '28')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0016));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '29')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00165));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '30')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00175));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '31')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00185));
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '32')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00195));
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '33')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0021));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00225));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '35')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0024));
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00255));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0027));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0029));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00305));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00325));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0034));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0036));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0038));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.004));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0042));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0044));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0046));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0048));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00495));
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0051));
					$premium_value = ceil($premium_value);
					}
				}

				if($frequency == '2')
				{
					if($effectiveAge < '28')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/2);
					$premium_value = ceil($premium_value);
					}
					
					else if($effectiveAge == '28')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '29')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00165))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '30')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '31')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00185))/2);
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '32')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00195))/2);
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '33')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0021))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00225))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '35')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0024))/2);
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00255))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0027))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0029))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00305))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00325))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0034))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0036))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0038))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.004))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0042))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0044))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0046))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0048))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00495))/2);
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0051))/2);
					$premium_value = ceil($premium_value);
					}
				}

				if($frequency == '1')
				{
					if($effectiveAge < '28')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0015))/4);
					$premium_value = ceil($premium_value);
					}
					
					else if($effectiveAge == '28')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '29')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00165))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '30')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '31')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00185))/4);
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '32')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00195))/4);
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '33')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0021))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00225))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '35')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0024))/4);
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00255))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0027))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0029))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00305))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00325))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0034))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0036))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0038))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.004))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0042))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0044))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0046))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0048))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00495))/4);
					$premium_value = ceil($premium_value);
					}
					
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0051))/4);
					$premium_value = ceil($premium_value);
					}
				}
		}
		if($term == '12')
		{
			if($frequency == '3')
				{
					if($effectiveAge < '20')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00155));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '20')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0016));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '21')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00165));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '22')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0017));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '23')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00175));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '24')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00175));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '25')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0018));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '26')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00185));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '27')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0019));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '28')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.002));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '29')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0025));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '30')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00215));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '31')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0023));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '32')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0024));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '33')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0026));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00275));
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '35')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00295));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00315));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0034));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00365));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0039));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00415));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0044));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00465));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0049));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0052));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0055));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0058));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0061));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.0064));
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.00675));
					$premium_value = ceil($premium_value);
					}
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil($sum_assured*(.007));
					$premium_value = ceil($premium_value);
					}
				}


				if($frequency == '2')
				{
					if($effectiveAge < '20')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00155))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '20')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '21')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00165))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '22')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0017))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '23')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '24')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '25')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0018))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '26')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00185))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '27')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0019))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '28')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.002))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '29')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0025))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '30')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00215))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '31')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0023))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '32')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0024))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '33')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0026))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00275))/2);
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '35')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00295))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00315))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0034))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00365))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0039))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00415))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0044))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00465))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0049))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0052))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0055))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0058))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0061))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0064))/2);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00675))/2);
					$premium_value = ceil($premium_value);
					}
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.007))/2);
					$premium_value = ceil($premium_value);
					}
				}


				if($frequency == '1')
				{
					if($effectiveAge < '20')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00155))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '20')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0016))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '21')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00165))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '22')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0017))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '23')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '24')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00175))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '25')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0018))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '26')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00185))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '27')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0019))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '28')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.002))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '29')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0025))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '30')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00215))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '31')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0023))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '32')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0024))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '33')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0026))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '34')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00275))/4);
					$premium_value = ceil($premium_value);
					}

					else if($effectiveAge == '35')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00295))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '36')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00315))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '37')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0034))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '38')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00365))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '39')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0039))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '40')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00415))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '41')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0044))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '42')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00465))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '43')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0049))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '44')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0052))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '45')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0055))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '46')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0058))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '47')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0061))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '48')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.0064))/4);
					$premium_value = ceil($premium_value);
					}
					else if($effectiveAge == '49')
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.00675))/4);
					$premium_value = ceil($premium_value);
					}
					else
					{
					$premium_value = $getPremiumamount['premium_value']+ceil(($sum_assured*(.007))/4);
					$premium_value = ceil($premium_value);
					}
				}
		}
	}
	
	else
	{
	$premium_value = $getPremiumamount['premium_value'];
	}
	}

	//================*end of plan-179*==========//




	if($plan == '6')
	{
		$premium_value = $getPremiumamount['premium_value'];
	}
	$message = "You need to pay Rs. ".$premium_value." /-";
}

else
	{
	$premium_value = $getPremiumamount['premium_value'];
	}
	$message = "You need to pay Rs. ".$premium_value." /-";

}
else
{
	$getPremiumamount = '';
	$premium_value = '';
	$message = "Invalid options chosen";
}

}
else
{
$premium_value = '';
$message	= '';
}
//echo $setPremiumamount;
//exit;


$premiumarr[] = $premium_value;
$premiumarr[] = $effectiveAge;
$premiumarr[] = $message;

echo json_encode($premiumarr);
?>