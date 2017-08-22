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

$pageOwner = "'superadmin','admin','branch','hub','subadmin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

if((intval($_SESSION[ROLE_ID]) == 3) || (intval($_SESSION[ROLE_ID]) == 4))
{
	$_SESSION['branch_name'] = $_SESSION[ADMIN_SESSION_VAR]; // For branch and hub
}

$where = "WHERE is_deleted=0 ";
$OrderBY = " ORDER BY id DESC ";




if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
{
	$where.= ' AND deposit_date ="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
}

if(isset($_SESSION['pis_mode']) && $_SESSION['pis_mode'] != '') 
{
	$where.= ' AND pis_mode ="'.$_SESSION['pis_mode'].'"';
}

$Query = "SELECT * FROM direct_renewal_premium_gtfs ".$where.$OrderBY.$Limit;
//echo $Query;
//exit;

$selTransaction = mysql_query($Query);
//echo "SELECT * FROM installment_master WHERE is_deleted=0 ".$where." ORDER BY id DESC";
$numTransaction = mysql_num_rows($selTransaction);

		
		//$header .= "Transaction Id"."\t";
		$header .= "Policy Number"."\t";
		$header .= "Insured Name"."\t";
		$header .= "Agent Name"."\t";
		$header .= "Agent Code"."\t";
		
		$header .= "Branch Name"."\t";
		
		
		$header .= "Plan"."\t";
		$header .= "Term"."\t";

		$header .= "Phase"."\t";
		$header .= "No. of Installment "."\t";

		
		
		$header .= "Amount"."\t";
		$header .= "Due Date"."\t";
		$header .= "Deposit Date"."\t";
		$header .= "LIC Receipt Date"."\t"; #####
		
		$header .= "\n";
	
		if($numTransaction > 0)
		{
			while($getTransaction = mysql_fetch_assoc($selTransaction))
			{
				$mysql_id = $getTransaction['id'];
				$branch_name = find_branch_name($getTransaction['branch_id']);
				$plan = find_plan($getTransaction['plan']);
			$line = '';		
			
			//$line .= '\''.str_pad($mysql_id, 7, "0", STR_PAD_LEFT)." \t";
			//$line .= $getTransaction['transaction_id']."\t";
			$line .= $getTransaction['application_no']."\t";
			$line .= $getTransaction['applicant_name']."\t";
			$line .= $getTransaction['agent_name']."\t";
			$agent_code = $getTransaction['agent_code'];
			$agent_code = "'".$agent_code;
			//exit;
			$line .= $agent_code."\t";

			
			$line .= $branch_name." \t";
			
			
			$line .= $plan." \t";
			$line .= $getTransaction['term']." Years\t";
			

			$line .= find_phase_name($getTransaction['phase'])."\t";
			
			$line .= find_installment_name($getTransaction['installment_no'])."\t";

			
			$line .= $getTransaction['amount']."\t";
			
			
			$due_date = '';
			if((isset($getTransaction['due_date'])) && ($getTransaction['due_date'] != '0000-00-00') && ($getTransaction['due_date'] != '1970-01-01'))
				{
				$due_date = date('d/m/Y',strtotime($getTransaction['due_date']));
				}
			$line .= $due_date."\t";
			
			$deposit_date = '';
			if((isset($getTransaction['deposit_date'])) && ($getTransaction['deposit_date'] != '0000-00-00') && ($getTransaction['deposit_date'] != '1970-01-01'))
				{
				$deposit_date = date('d/m/Y',strtotime($getTransaction['deposit_date']));
				}
			$line .= $deposit_date."\t";
			
			
			
			$sp_dd_date = '';
			if((isset($getTransaction['sp_dd_date'])) && ($getTransaction['sp_dd_date'] != '0000-00-00') && ($getTransaction['sp_dd_date'] != '1970-01-01'))
				{
				$sp_dd_date = date('d/m/Y',strtotime($getTransaction['sp_dd_date']));
				}
			$line .= $sp_dd_date."\t";
			;
			
			$data .= trim($line)."\n";
		}
	}
	$data = str_replace("\r", "", $data);
	if ($data == "")
	{
		$data = "\n(0) Records Found!\n";						
	}
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=Short_Premium_excel_SICl_New".'_'.date('m-d-Y_H:i').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";

?>