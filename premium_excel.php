<?php 
include_once("utility/config.php");

include_once("utility/dbclass.php");

include_once("utility/functions.php");

//include_once("includes/other_functions.php");

include_once("webadmin/includes/new_functions.php");

date_default_timezone_set('Asia/Calcutta');
error_reporting(0);
set_time_limit(0); 

$objDB = new DB();

$pageOwner = "'superadmin','admin','branch','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

if((intval($_SESSION[ROLE_ID]) == 3) || (intval($_SESSION[ROLE_ID]) == 4))
{
	$_SESSION['branch_name'] = $_SESSION[ADMIN_SESSION_VAR]; // For branch and hub
}

$where = "WHERE 1 ";
$OrderBY = " ORDER BY id ";

/*if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '')
{	
	$where.=" AND branch_id='".$_SESSION['branch_name']."'";
}


if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
{
	$where.= ' AND deposit_date ="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
}

if(isset($_SESSION['pis_mode']) && $_SESSION['pis_mode'] != '') 
{
	$where.= ' AND pis_mode ="'.$_SESSION['pis_mode'].'"';
}*/

$Query = "SELECT * FROM new_plan_rate ".$where.$OrderBY.$Limit;
//echo $Query;
//exit;

$selTransaction = mysql_query($Query);
//echo "SELECT * FROM installment_master WHERE is_deleted=0 ".$where." ORDER BY id DESC";
$numTransaction = mysql_num_rows($selTransaction);

		
		//$header .= "Transaction Id"."\t";
		$header .= "Plan"."\t";
		$header .= "Age"."\t";
		$header .= "Term"."\t";
		$header .= "Premium Amount Rate"."\t";
		$header .= "Age Proof"."\t";
		$header .= "Extra Premium Amount Rate"."\t";
		
		
		
		
		$header .= "\n";
	
		if($numTransaction > 0)
		{
			while($getTransaction = mysql_fetch_assoc($selTransaction))
			{
				$mysql_id = $getTransaction['id'];
				//$branch_name = find_branch_name($getTransaction['branch_id']);
				$plan_id = find_plan($getTransaction['plan_id']);
				$age_id = find_age($getTransaction['age_id']);
				
			$line = '';		
			
			//$line .= '\''.str_pad($mysql_id, 7, "0", STR_PAD_LEFT)." \t";
			//$line .= $getTransaction['transaction_id']."\t";
			
			$line .= $plan_id."\t";
			$line .= $age_id."\t";
			$line .= $getTransaction['term_id']."\t";
			$line .= $getTransaction['rate']."\t";
			
			$line .= $getTransaction['age_proof']."\t";
			
			$line .= $getTransaction['extra_amount_rate']."\t";
			
			$data .= trim($line)."\n";
		}
	}
	$data = str_replace("\r", "", $data);
	if ($data == "")
	{
		$data = "\n(0) Records Found!\n";						
	}
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Premium_excel".'_'.date('m-d-Y_H:i').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";

?>