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
function payment_mode_name($mode)
{
	$objDB = new DB();
	$Query = "select  *  from frequency_master  WHERE id = '".$mode."'";
	$objDB->setQuery($Query);
	$rsu = $objDB->select();
	if(count($rsu) > 0 )
	{
		return $rsu[0]['frequency'];
	}else{
		return "No Mode Selected";
	}
}
$where = '';
#$_SESSION['branch_name'] = isset($_GET['branch_name']) ? $_GET['branch_name'] : '';
#$_SESSION['from_date'] = isset($_GET['from_date']) ? $_GET['from_date'] : '';
#$_SESSION['to_date'] = isset($_GET['to_date']) ? $_GET['to_date'] : '';
#$_SESSION['first_name'] = isset($_GET['first_name']) ? $_GET['first_name'] : '';
if(isset($_SESSION['hub_name_id']) && $_SESSION['hub_name_id'] != '') 
	{
		$where.= ' AND im.hub_id="'.$_SESSION['hub_name_id'].'"';
	}
	if(isset($_SESSION['branch_name_id']) && $_SESSION['branch_name_id'] != '') 
	{
		$where.= ' AND im.branch_id="'.$_SESSION['branch_name_id'].'"';
	}
	if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
	{
		$where.= ' AND im.business_date >="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
	}
	if(isset($_SESSION['to_date']) && $_SESSION['to_date'] != '') 
	{
		$where.= ' AND im.business_date <="'.date('Y-m-d', strtotime($_SESSION['to_date'])).'"';
	}
	if(isset($_SESSION['receipt_number']) && $_SESSION['receipt_number'] != '') 
	{
		$where.= ' AND im.receipt_number LIKE "%'.$_SESSION['receipt_number'].'%"';
	}
	if(isset($_SESSION['application_no']) && $_SESSION['application_no'] != '') 
	{
		$where.= ' AND im.application_no LIKE "%'.$_SESSION['application_no'].'%"';
	}
	if(isset($_SESSION['folio_no']) && $_SESSION['folio_no'] != '') 
	{
		$derivedFolioNo = find_folio_id($_SESSION['folio_no']);
		$where.= ' AND im.folio_no_id = "'.$derivedFolioNo.'"';
	}
	if(isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != '') 
	{
		$derivedCustID = find_id_through_customer_id($_SESSION['customer_id']);
		$where.= ' AND im.customer_id = "'.$derivedCustID.'"';
	}
        
        
        if(isset($_SESSION['branch_despatch']) && $_SESSION['branch_despatch'] != '' && $_SESSION['branch_despatch'] != 'Select All') 
	{
		$where.= ' AND  im.branch_despatched ="'.$_SESSION['branch_despatch'].'"';	
	}
        
        if(isset($_SESSION['hub_receive']) && $_SESSION['hub_receive'] != '' && $_SESSION['hub_receive'] != 'Select All') 
	{
		$where.= ' AND  im.hub_received ="'.$_SESSION['hub_receive'].'"';	
	}
        
        if(isset($_SESSION['business_type']) && $_SESSION['business_type'] != '' && $_SESSION['business_type'] != 'Select All') 
	{
		$where.= ' AND  im.business_type ="'.$_SESSION['business_type'].'"';	
	}
        
        if(isset($_SESSION['ins_despatch']) && $_SESSION['ins_despatch'] != '' && $_SESSION['ins_despatch'] != 'Select All') 
	{
		$where.= ' AND  im.hub_despatched ="'.$_SESSION['ins_despatch'].'"';	
	}
        
	if(isset($_SESSION['first_name']) && $_SESSION['first_name'] != '') 
	{
		$firstNameString = find_id_through_first_name($_SESSION['first_name']);
		#echo $firstNameString;
		if($firstNameString != '')
		{
			$where.= ' AND im.customer_id IN ( '.$firstNameString.')';
		}
	}
	if(isset($_SESSION['last_name']) && $_SESSION['last_name'] != '') 
	{
		$lastNameString = find_id_through_last_name($_SESSION['last_name']);
		#echo $lastNameString;
		if($lastNameString != '')
		{
			$where.= ' AND im.customer_id IN ( '.$lastNameString.')';
		}
	}
	if($_SESSION[ROLE_ID] == '3')
		{
			$branchStr = '';
			$branch_user_id = find_branch_user_id($_SESSION[ADMIN_SESSION_VAR]);
			$hub_id = intval($branch_user_id) == 0 ? $_SESSION[ADMIN_SESSION_VAR] : $branch_user_id ;
	
			$selBranches = mysql_query("SELECT id FROM admin WHERE hub_id=".$hub_id."");
			if(mysql_num_rows($selBranches) > 0)
			{
								
				$where.=" AND im.hub_id='$hub_id'";
			}
	}
else if($_SESSION[ROLE_ID] == '4')
{
	$where.= ' AND im.branch_id="'.$_SESSION['branch_name'].'"';
}
	if(isset($_SESSION['pis_generated']) && ($_SESSION['pis_generated'] != ''))
	{
		if($_SESSION['pis_generated'] == '0')
		{
		$where.= " AND im.cash_pis_id='0' AND im.cheque_pis_id='0' AND im.draft_pis_id='0'";
		}
		else if($_SESSION['pis_generated'] == '1')
		{
		$where.= " AND (im.cash_pis_id!='0' || im.cheque_pis_id!='0' || im.draft_pis_id!='0')";
		}
		else
		{
		$where.= '';
		}
	}
$sql="SELECT im.*, ad.branch_code FROM installment_master_branch as im JOIN admin as ad ON im.branch_id=ad.id WHERE im.is_deleted=0 ".$where." ORDER BY im.id DESC";
//  echo $sql;
// exit;
$selTransaction = mysql_query($sql);
//echo "SELECT * FROM installment_master WHERE is_deleted=0 ".$where." ORDER BY id DESC";
$numTransaction = mysql_num_rows($selTransaction);
	//$tbname = $_POST['select1'];
	//$objDB = new DB();
	//$Query = $_SESSION['branch_report_query'];
	//$objDB->setQuery($Query);
	//$rs = $objDB->select();

//======================================================================
                $header .= "Application No"."\t";
                $header .= "Business Date."."\t";
                $header .= "HUB Name"."\t";
		$header .= "Branch Name"."\t";
                $header .= "Branch Code"."\t";
                $header .= "Applicant Name."."\t";
                $header .= "Insured Name."."\t";
                $header .= "Mobile Number."."\t";
                $header .= "Plan Name."."\t";
                $header .= "Premium Paying Term."."\t";
                $header .= "Term."."\t";
		$header .= "Sum Assured."."\t";
                $header .= "Payment Mode."."\t";
                $header .= "Premium Amount."."\t";
                $header .= "Agent Code"."\t";
                $header .= "State"."\t";
		$header .= "PIN"."\t";
                $header .= "PIC"."\t";
//======================================================================
		$header .= "\n";
		$search = "/[ \r\n]/";
		if($numTransaction > 0)
		{
			while($getTransaction = mysql_fetch_assoc($selTransaction))
			{  
                            $business_date = '';
				if(($getTransaction['business_date'] != '0000-00-00')&&($getTransaction['business_date'] != '1970-01-01')){
					$business_date = date('d/m/Y',strtotime($getTransaction['business_date']));
				}
                                
				$line = '';          
//======================================================================
                                $line .= "'".$getTransaction['application_no']."\t";
                                $line .= $business_date."\t";
                                $line .= new_find_hub_name($getTransaction['hub_id'])."\t";
				$line .= find_branch_name($getTransaction['branch_id'])."\t";
                                $line .= $getTransaction['branch_code']."\t";
                                $line .= stripslashes($getTransaction['applicant_name'])."\t";
                                $line .= stripslashes($getTransaction['insured_name'])."\t";
                                $line .= $getTransaction['mobile_no']."\t";
                                $line .= stripslashes($getTransaction['plan_name'])."\t";
                                //if($getTransaction['premium_paying_term'] != ''){
                                  $line .= $getTransaction['premium_paying_term']."\t";
                                //}else{
                                   // $line .= $getTransaction['term']."\t";
                                //}
                                $line .= $getTransaction['term']."\t";		
				$line .= $getTransaction['sum_asured']."\t";
                                $line .= $getTransaction['pay_mode']."\t";
                                $line .= $getTransaction['premium']."\t";
                                $line .= "'".$getTransaction['agent_code']."\t";
                                $line .= "'".$getTransaction['state_id']."\t";
				$line .= $getTransaction['pin']."\t";
                                $line .= "Reliance Life Insurance Co. Ltd.\t";
//======================================================================
				$data .= trim($line)."\n";
			}
	}
	$data = str_replace("\r", "", $data);
	if ($data == "")
	{
		$data = "\n(0) Records Found!\n";						
	}
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=SICL_new".'_'.date('m-d-Y_H:i').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";
?>