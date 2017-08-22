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

$where = "WHERE is_deleted=1 ";
$OrderBY = " ORDER BY pis_date, id DESC ";

/*
if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '')
{	
	$where.=" AND branch_id='".$_SESSION['branch_name']."'";
}
*/
if(isset($_SESSION['branch_name_id']) && $_SESSION['branch_name_id'] != '') 
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name_id'].'"';
	}
	
	if($_SESSION[ROLE_ID] == '4')
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name'].'"';
	}

if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
{
	$where.= ' AND pis_date >="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
}
if(isset($_SESSION['to_date']) && $_SESSION['to_date'] != '') 
{
	$where.= ' AND pis_date <="'.date('Y-m-d', strtotime($_SESSION['to_date'])).'"';
}

if(isset($_SESSION['pis_mode']) && $_SESSION['pis_mode'] != '') 
{
	$where.= ' AND pis_mode ="'.$_SESSION['pis_mode'].'"';
}

$Query = "SELECT * FROM  pis_master_sz_renewal ".$where.$OrderBY.$Limit;

$selTransaction = mysql_query($Query);
//echo "SELECT * FROM installment_master WHERE is_deleted=0 ".$where." ORDER BY id DESC";
$numTransaction = mysql_num_rows($selTransaction);

		
		$header .= "PIS Number"."\t";
		$header .= "Branch Name"."\t";
		$header .= "Business Date."."\t";
		$header .= "Prepared At"."\t";
		$header .= "Pis Mode"."\t"; #####
		$header .= "Amount"."\t"; ###
		$header .= "Thousand"."\t"; #####
		$header .= "Five Hundred"."\t";
		$header .= "Hundred"."\t";
		$header .= "Fifty"."\t";
		$header .= "Twenty"."\t";
		$header .= "Ten"."\t";
		$header .= "Five"."\t";
		$header .= "Two."."\t";
		$header .= "One"."\t";
		$header .= "Bank Deposit Date"."\t";
		$header .= "CMS No."."\t";
		$header .= "\n";
	
		if($numTransaction > 0)
		{
			while($getTransaction = mysql_fetch_assoc($selTransaction))
			{
				$mysql_id = $getTransaction['id'];
				$branch_name = find_branch_name($getTransaction['branch_id']);
		
			$line = '';		
			
			$line .= '\''.str_pad($mysql_id, 7, "0", STR_PAD_LEFT)." \t";
			$line .= $branch_name." \t";
			$line .= date('d/m/Y',strtotime($getTransaction['pis_date']))."\t";
			$line .= date('d/m/Y H:i:s',strtotime($getTransaction['prepared_at']))."\t";
			$line .= $getTransaction['pis_mode']."\t";
			$line .= $getTransaction['total']."\t";
			$line .= ($getTransaction['thousand'] * 1000)."\t";
			$line .= ($getTransaction['fiveHundred'] * 500)."\t";
			$line .= ($getTransaction['hundred'] * 100)."\t";
			$line .= ($getTransaction['fifty'] * 50)."\t";
			$line .= ($getTransaction['twenty'] * 20)."\t";
			$line .= ($getTransaction['ten'] * 10)."\t";
			$line .= ($getTransaction['five'] * 5)."\t";
			$line .= ($getTransaction['two'] * 2)."\t";
			$line .= ($getTransaction['one'] * 1)."\t";
			$deposit_date = $getTransaction['deposit_date'] == '0000-00-00' ? '' : date('d/m/Y',strtotime($getTransaction['deposit_date']));
			$line .= $deposit_date."\t";
			$line .= $getTransaction['cms_no']." \t";
			$data .= trim($line)."\n";
		}
	}
	$data = str_replace("\r", "", $data);
	if ($data == "")
	{
		$data = "\n(0) Records Found!\n";						
	}
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=PIS_excel_SICL_New".'_'.date('m-d-Y_H:i').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";

?>