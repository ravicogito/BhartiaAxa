<?php 
include_once("utility/config.php");
include_once("utility/dbclass.php");
include_once("utility/functions.php");
include_once("webadmin/includes/new_functions.php");
date_default_timezone_set('Asia/Calcutta');
error_reporting(0);
set_time_limit(0); 
$objDB = new DB();
$pageOwner = "'branch','superadmin','admin','hub','subsuperadmin','admin11'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); // $USER_TYPE is coming from index.php
function findLastPayDate($folio_id)
{
	$objDB = new DB();
	$Query = "SELECT DATE_FORMAT(deposit_date, '%d-%m-%Y') AS last_date FROM installment_master WHERE folio_no_id = '".$folio_id."' AND is_deleted=0 ORDER BY id DESC LIMIT 0,1 ";
	$objDB->setQuery($Query);
	$rsu = $objDB->select();
	if(count($rsu) > 0 )
	{
		return $rsu[0]['last_date'];
	}
}
function findCumulativeInstallment($folio_id,$id)
{
	$objDB = new DB();
	$Query = "SELECT SUM(installment) AS cumulative_installment FROM installment_master WHERE folio_no_id = '".$folio_id."' AND is_deleted=0 AND id <= '".$id."'";
	$objDB->setQuery($Query);
	$rsu = $objDB->select();
	if(count($rsu) > 0 )
	{
		return $rsu[0]['cumulative_installment'];
	}
}
$where = '';
#$_SESSION['branch_name'] = isset($_GET['branch_name']) ? $_GET['branch_name'] : '';
#$_SESSION['from_date'] = isset($_GET['from_date']) ? $_GET['from_date'] : '';
#$_SESSION['to_date'] = isset($_GET['to_date']) ? $_GET['to_date'] : '';
#$_SESSION['first_name'] = isset($_GET['first_name']) ? $_GET['first_name'] : '';
if(isset($_SESSION['hub_name_id']) && $_SESSION['hub_name_id'] != '') 
	{
		$where.= ' AND hub_id="'.$_SESSION['hub_name_id'].'"';
	}
	if(isset($_SESSION['branch_name_id']) && $_SESSION['branch_name_id'] != '') 
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name_id'].'"';
	}
	if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
	{
		$where.= ' AND business_date >="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
	}
	if(isset($_SESSION['to_date']) && $_SESSION['to_date'] != '') 
	{
		$where.= ' AND business_date <="'.date('Y-m-d', strtotime($_SESSION['to_date'])).'"';
	}
	if(isset($_SESSION['receipt_number']) && $_SESSION['receipt_number'] != '') 
	{
		$where.= ' AND receipt_number LIKE "%'.$_SESSION['receipt_number'].'%"';
	}
	if(isset($_SESSION['application_no']) && $_SESSION['application_no'] != '') 
	{
		$where.= ' AND application_no LIKE "%'.$_SESSION['application_no'].'%"';
	}
	
	
	if(isset($_SESSION['hub_name_id']) && $_SESSION['hub_name_id'] != '') 
	{
		$where.= ' AND hub_id="'.$_SESSION['hub_name_id'].'"';
	}
	
	if(isset($_SESSION['branch_name_id']) && $_SESSION['branch_name_id'] != '') 
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name_id'].'"';
	}
	
	if($_SESSION[ROLE_ID] == '3')
		{
			$branchStr = '';
			$branch_user_id = find_branch_user_id($_SESSION[ADMIN_SESSION_VAR]);
			$hub_id = intval($branch_user_id) == 0 ? $_SESSION[ADMIN_SESSION_VAR] : $branch_user_id ;
	
			$selBranches = mysql_query("SELECT id FROM admin WHERE hub_id=".$hub_id."");
			if(mysql_num_rows($selBranches) > 0)
			{
								
				$where.=" AND hub_id='$hub_id'";
			}
	}
else if($_SESSION[ROLE_ID] == '4')
{
	$where.= ' AND branch_id="'.$_SESSION['branch_name'].'"';
}
	if(isset($_SESSION['pis_generated']) && ($_SESSION['pis_generated'] != ''))
	{
		if($_SESSION['pis_generated'] == '0')
		{
		$where.= " AND cash_pis_id='0' AND cheque_pis_id='0' AND draft_pis_id='0'";
		}
		else if($_SESSION['pis_generated'] == '1')
		{
		$where.= " AND (cash_pis_id!='0' || cheque_pis_id!='0' || draft_pis_id!='0')";
		}
		else
		{
		$where.= '';
		}
	}
$sql="SELECT * FROM installment_master_short_premium WHERE is_deleted=0 ".$where." ORDER BY id DESC";
 //echo $sql;
// exit;
$selTransaction = mysql_query($sql);
//echo "SELECT * FROM installment_master WHERE is_deleted=0 ".$where." ORDER BY id DESC";
$numTransaction = mysql_num_rows($selTransaction);
	//$tbname = $_POST['select1'];
	//$objDB = new DB();
	//$Query = $_SESSION['branch_report_query'];
	//$objDB->setQuery($Query);
	//$rs = $objDB->select();
		
		
		$header .= "Business Date."."\t";
		$header .= "Business Type."."\t";
		$header .= "Branch Name"."\t";
		$header .= "Application No"."\t";
		$header .= "Policy No"."\t";
		$header .= "Agent Code"."\t";
		$header .= "Pre Printed Recipt No"."\t";
		$header .= "Cash Money Recipt."."\t";
		$header .= "Chaque Money Recipt"."\t";
		$header .= "Draft Money Recipt"."\t";
		$header .= "Applicant Name."."\t";
		
		$header .= "Plan Name."."\t";
				
		
		
		//$header .= "Payment MODE."."\t";
		$header .= "Payment in Cash."."\t";
		$header .= "Payment in Cheque."."\t";
		$header .= "Payment in draft."."\t";
		$header .= "Premium Amount."."\t";
		$header .= "Cheque/DD No."."\t";
		$header .= "Cheque/DD Date."."\t";
		$header .= "Cheque/DD Bank Name."."\t";
		$header .= "Cheque/DD Branch Name."."\t";
		
		
		$header .= "Reason."."\t";
		
		$header .= "\n";
	
		$search = "/[ \r\n]/";
		if($numTransaction > 0)
		{
			while($getTransaction = mysql_fetch_assoc($selTransaction))
			{
		
				$line = '';
				$deposit_date = '';
				if($getTransaction['business_date'] != '0000-00-00'){
					$deposit_date = date('d/m/Y',strtotime($getTransaction['business_date']));
				}
				$line .= $deposit_date."\t";
				$line .= stripslashes($getTransaction['type_of_business'])."\t";
				$line .= find_branch_name($getTransaction['branch_id'])."\t";
				$line .= "'".stripslashes($getTransaction['application_no'])."\t";
				$line .= "'".stripslashes($getTransaction['proposal_no'])."\t";
				$line .= "'".stripslashes($getTransaction['agent_code'])."\t";
				$line .= "'".stripslashes($getTransaction['pre_printed_receipt_no'])."\t";
				$line .= "'".stripslashes($getTransaction['cash_money_receipt'])."\t";
				$line .= "'".stripslashes($getTransaction['cheque_money_receipt'])."\t";
				$line .= "'".stripslashes($getTransaction['draft_money_receipt'])."\t";
				$line .= stripslashes($getTransaction['applicant_name'])."\t";
				
				$line .= stripslashes($getTransaction['plan_name'])."\t";
				
				
				//$line .= $getTransaction['pay_mode']."\t";
				$line .= $getTransaction['receive_cash']."\t";
				$line .= $getTransaction['receive_cheque']."\t";
				$line .= $getTransaction['receive_draft']."\t";
				$line .= $getTransaction['premium']."\t";
				if($getTransaction['receive_cheque']>0)
				{
					$line .= "'".$getTransaction['cheque_no']."\t";
					$cheque_date = '';
					if($getTransaction['cheque_date'] != '0000-00-00'){
						$cheque_date = date('d/m/Y',strtotime($getTransaction['cheque_date']));
					}
					$line .= $cheque_date."\t";
					$line .= $getTransaction['cheque_bank_name']."\t";
					$line .= $getTransaction['cheque_branch_name']."\t";
				}
				else
				{
					$line .= "'".$getTransaction['dd_no']."\t";
					$dd_date = '';
					if($getTransaction['dd_date'] != '0000-00-00'){
						$dd_date = date('d/m/Y',strtotime($getTransaction['dd_date']));
					}
					$line .= $dd_date."\t";
					$line .= stripslashes($getTransaction['dd_bank_name'])."\t";
					$line .= stripslashes($getTransaction['dd_branch_name'])."\t";
				}
				$line .= stripslashes($getTransaction['reason'])."\t";
				
				$data .= trim($line)."\n";
				
			}
				
			//echo "<pre>";
			//print_r($$data);
			
			//exit;
	}
	$data = str_replace("\r", "", $data);
	if ($data == "")
	{
		$data = "\n(0) Records Found!\n";						
	}
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=fresh_sz".'_'.date('m-d-Y_H:i').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";
?>