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
$pageOwner = "'branch','admin','superadmin','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); // $USER_TYPE is coming from index.php
function findLastPayDate($folio_id)
{
	$objDB = new DB();
	$Query = "SELECT DATE_FORMAT(deposit_date, '%d-%m-%Y') AS last_date FROM installment_master WHERE folio_no_id = '".$folio_id."' AND is_deleted='0' ORDER BY id DESC LIMIT 0,1 ";
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
	$Query = "SELECT SUM(installment) AS cumulative_installment FROM installment_master WHERE folio_no_id = '".$folio_id."' AND is_deleted='0' AND id <= '".$id."'";
	$objDB->setQuery($Query);
	$rsu = $objDB->select();
	if(count($rsu) > 0 )
	{
		return $rsu[0]['cumulative_installment'];
	}
}
$where = "WHERE is_deleted='0' AND (cash_pis_id != '0' OR cheque_pis_id != '0' OR draft_pis_id != '0')";
$OrderBY = " ORDER BY im.id DESC ";
if(isset($_SESSION['branch_name_string']) && $_SESSION['branch_name_string'] != '') 
	{
		$where.= ' AND branch_id IN ('.$_SESSION['branch_name_string'].')';
	}
        

	if(isset($from_date))
	{
		$_SESSION['from_date'] = $from_date;
	}

	if(isset($to_date))
	{
		$_SESSION['to_date'] = $to_date;
	}
	if(isset($campaign_id))
	{
		$_SESSION['campaign_id'] = $to_date;
	}
	

	if(isset($receipt_number))
	{
		$_SESSION['receipt_number'] = realTrim($receipt_number);
	}

	if(isset($application_no))
	{
		$_SESSION['application_no'] = realTrim($application_no);
	}

	if(isset($folio_no))
	{
		$_SESSION['folio_no'] = realTrim($folio_no);
	}

	if(isset($customer_id))
	{
		$_SESSION['customer_id'] = realTrim($customer_id);
	}
	

	if(isset($first_name))
	{
		$_SESSION['first_name'] = realTrim($first_name);
	}
	if(isset($last_name))
	{
		$_SESSION['last_name'] = realTrim($last_name);
	}

	/*if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '') // this is actually branch id
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name'].'"';
	}*/

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
		$where.= ' AND pre_printed_receipt_no = "'.$_SESSION['receipt_number'].'"';
	}
		if(isset($_SESSION['campaign_id']) && $_SESSION['campaign_id'] != '') 
	{
		$where.= ' AND campaign_id="'.$_SESSION['campaign_id'].'"';
	}

	if(isset($_SESSION['application_no']) && $_SESSION['application_no'] != '') 
	{
		$where.= ' AND application_no LIKE "%'.$_SESSION['application_no'].'%"';
	}

	if(isset($_SESSION['folio_no']) && $_SESSION['folio_no'] != '') 
	{
		$derivedFolioNo = find_folio_id($_SESSION['folio_no']);
		$where.= ' AND folio_no_id = "'.$derivedFolioNo.'"';
	}
        
        if(isset($_SESSION['branch_despatch']) && $_SESSION['branch_despatch'] != '' && $_SESSION['branch_despatch'] != 'Select All') 
	{
		$where.= ' AND  branch_despatched ="'.$_SESSION['branch_despatch'].'"';	
	}
        
        if(isset($_SESSION['hub_receive']) && $_SESSION['hub_receive'] != '' && $_SESSION['hub_receive'] != 'Select All') 
	{
		$where.= ' AND  hub_received ="'.$_SESSION['hub_receive'].'"';	
	}
        
        if(isset($_SESSION['ins_despatch']) && $_SESSION['ins_despatch'] != '' && $_SESSION['ins_despatch'] != 'Select All') 
	{
		$where.= ' AND  hub_despatched ="'.$_SESSION['ins_despatch'].'"';	
	}

	if(isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != '') 
	{
		$derivedCustID = find_id_through_customer_id($_SESSION['customer_id']);
		$where.= ' AND customer_id = "'.$derivedCustID.'"';
	}

	if(isset($_SESSION['first_name']) && $_SESSION['first_name'] != '') 
	{
		$firstNameString = find_id_through_first_name($_SESSION['first_name']);
		#echo $firstNameString;
		if($firstNameString != '')
		{
			$where.= ' AND customer_id IN ( '.$firstNameString.')';
		}
	}

	if(isset($_SESSION['last_name']) && $_SESSION['last_name'] != '') 
	{
		$lastNameString = find_id_through_last_name($_SESSION['last_name']);
		#echo $lastNameString;
		if($lastNameString != '')
		{
			$where.= ' AND customer_id IN ( '.$lastNameString.')';
		}
	}

//$sql="SELECT * FROM installment_master WHERE is_deleted='0' ".$where." ORDER BY id DESC";
//$sql="SELECT im.*, ad.branch_name as branch_name, ad.branch_code as branch_code FROM installment_master as im JOIN admin as ad ON im.branch_id = ad.id WHERE is_deleted='0' ".$where." ORDER BY im.id DESC";
$sql = "SELECT im.*, ad.branch_code FROM installment_master_branch as im JOIN admin as ad ON im.branch_id = ad.id ".$where.$OrderBY.$Limit;
//  echo $sql;
//  exit;
$selTransaction = mysql_query($sql);
//echo "SELECT * FROM installment_master WHERE is_deleted=0 ".$where." ORDER BY id DESC";
$numTransaction = mysql_num_rows($selTransaction);
	//$tbname = $_POST['select1'];
	//$objDB = new DB();
	//$Query = $_SESSION['branch_report_query'];
	//$objDB->setQuery($Query);
	//$rs = $objDB->select();
		
		$header .= "PIS Generate"."\t";

		$header .= "Quote Number"."\t";
		$header .= "Branch Name"."\t";
                $header .= "Branch Code"."\t";
				
		$header .= "Campaign"."\t";
        $header .= "Campaign Code"."\t";
		
		$header .= "Business Date"."\t";
		$header .= "Pre-printed Receipt No"."\t";
		$header .= "Hub Name"."\t";
                $header .= "Business Type"."\t";
                $header .= "Agent Code"."\t";
                $header .= "Applicant Name"."\t";
                $header .= "Applicant Father Name"."\t";
                $header .= "Applicant DOB"."\t";
                $header .= "Applicant Address 1"."\t";
                $header .= "Applicant Address 2"."\t";
                $header .= "Applicant Address 3"."\t";
                $header .= "Mobile Number"."\t";
                $header .= "State"."\t";
                $header .= "Pin"."\t";
		$header .= "Plan"."\t";
		$header .= "Term"."\t";
		$header .= "Premium Paying Term"."\t";
		$header .= "Payment Frequency"."\t";
		$header .= "Sum Assured"."\t";
		$header .= "Premium Amount"."\t";
		$header .= "DD / Cheque No."."\t";
		$header .= "DD / Cheque Date"."\t";
                $header .= "Cheque Amount"."\t";
                $header .= "DD Amount"."\t";
                $header .= "Cash Amount"."\t";
                $header .= "Cheque Money Receipt No."."\t";
                $header .= "DD Money Receipt No."."\t";
                $header .= "Cash Money Receipt No."."\t";
		$header .= "Bank Name"."\t";
                $header .= "Insured Name"."\t";
//		$header .= "Insured DOB"."\t";
		$header .= "Nominee Name"."\t";
		$header .= "Nominee DOB"."\t";
		$header .= "Appointee Name"."\t";
                $header .= "Appointee Age"."\t";
                $header .= "Branch Scan"."\t";
		$header .= "Branch Scan Date"."\t";
                $header .= "Branch Despatch"."\t";
		$header .= "Branch Despatch Date"."\t";
                $header .= "Hub Despatch"."\t";
                $header .= "Hub Despatch Date"."\t";
                $header .= "Hub Receive"."\t";
                $header .= "Hub Receive Date"."\t";
		$header .= "\n";
	
		$search = "/[ \r\n]/";
		if($numTransaction > 0)
		{
			while($getTransaction = mysql_fetch_assoc($selTransaction))
			{
				
				$Query = "select campaign from t_99_campaign WHERE campaign_id=$getTransaction[campaign_id]";
				#echo $Query;
				$objDB->setQuery($Query);
				$rsCampaign = $objDB->select();

//                                echo "==========";
//                                echo "<br />";
//                                echo "+++++++++".$getTransaction['pin'];
//                                die();
				$mysql_id = $getTransaction['id'];
				$branch_name = find_branch_name($getTransaction['branch_id']);
				
				$insured_address = $getTransaction['insured_address'];
				$insured_address_len = strlen($insured_address);
				//echo $insured_address_len;
				//echo "<br>";
				//echo $insured_address;
				$insured_address1 = substr($getTransaction['insured_address'],'0','50');
				if(($insured_address_len > 50) && ($insured_address_len < 100))
				{
				$insured_address2 = substr($getTransaction['insured_address'],'51',$insured_address_len);
				}
				if($insured_address_len > 100)
				{
				$insured_address2 = substr($getTransaction['insured_address'],'51','100');
				$insured_address3 = substr($getTransaction['insured_address'],'101',$insured_address_len);
				}
				$nominee_address = $getTransaction['nominee_address'];
				$nominee_address_len = strlen($nominee_address);
				
				$nominee_address1 = substr($getTransaction['nominee_address'],'0','50');
				
				if(($nominee_address_len > 50) && ($nominee_address_len < 100))
				{
				$nominee_address2 = substr($getTransaction['nominee_address'],'51',$nominee_address_len);
				}
				if($nominee_address_len > 100)
				{
				$nominee_address2 = substr($getTransaction['nominee_address'],'51','100');
				$nominee_address3 = substr($getTransaction['nominee_address'],'101',$insured_address_len);
				}
				$employers_address = $getTransaction['employers_address'];
				$employers_address_len = strlen($employers_address);
				$employers_address1 = substr($getTransaction['employers_address'],'0','50');
				if(($employers_address_len > 50) && ($employers_address_len < 100))
				{
				$employers_address2 = substr($getTransaction['employers_address'],'51',$employers_address_len);
				}
				if($employers_address_len > 100)
				{
				$employers_address2 = substr($getTransaction['employers_address'],'51','100');
				$employers_address3 = substr($getTransaction['employers_address'],'101',$employers_address_len);
				}
                                if($getTransaction['cheque_date'] == '0000-00-00' || $getTransaction['cheque_date'] == '1970-01-01'){
                                    $cheque_date = "";
                                }else{
                                    $cheque_date = $getTransaction['cheque_date'];
                                }
				//echo "<br>";
				//echo $insured_address1;
				//echo "<br>";
				//echo 'l2'.$insured_address2;
				//echo "<pre>";
				//print_r($getTransaction);
				//exit;
                                if($getTransaction['cash_pis_id'] == '0' && $getTransaction['cheque_pis_id'] == '0' && $getTransaction['draft_pis_id'] == '0'){
                                    $pis = 'No';
                                }else{
                                    $pis = 'Yes';
                                }
                                
                                if($getTransaction['branch_scan'] == '1'){
                                    $branch_scan = 'Yes';
                                }else{
                                    $branch_scan = 'No';
                                }
                                
                                if($getTransaction['branch_scan_date'] != "0000-00-00"){
                                    $branch_scan_date = $getTransaction['branch_scan_date'];
                                }else{
                                    $branch_scan_date = '';
                                }
                                
                                if($getTransaction['branch_despatched'] == '1'){
                                    $branch_despatched = 'Yes';
                                }else{
                                    $branch_despatched = 'No';
                                }
                                
                                if($getTransaction['branch_despatch_date'] != "0000-00-00"){
                                    $branch_despatch_date = $getTransaction['branch_despatch_date'];
                                }else{
                                    $branch_despatch_date = '';
                                }
                                
                                if($getTransaction['hub_despatched'] == '1'){
                                    $hub_despatched = 'Yes';
                                }else{
                                    $hub_despatched = 'No';
                                }
                                
                                if($getTransaction['hub_despatch_date'] != "0000-00-00"){
                                    $hub_despatch_date = $getTransaction['hub_despatch_date'];
                                }else{
                                    $hub_despatch_date = '';
                                }
                                  
                                
                                if($getTransaction['hub_received'] == '1'){
                                    $hub_received = 'Yes';
                                }else{
                                    $hub_received = 'No';
                                }
                                
                                if($getTransaction['hub_receive_date'] != "0000-00-00"){
                                    $hub_receive_date = $getTransaction['hub_receive_date'];
                                }else{
                                    $hub_receive_date = '';
                                }
                                
			$line = '';
			
			$line .= $pis."\t";
			$line .= "'".$getTransaction['application_no']."\t";
			$line .= find_branch_name($getTransaction['branch_id'])."\t";
                        $line .= $getTransaction['branch_code']."\t";
			$line .= trim($rsCampaign[0]['campaign'])."\t";
			$line .= trim($rsCampaign[0]['campaign_code'])."\t";
			
			$line .= $getTransaction['business_date']."\t";
			$line .= "'".$getTransaction['pre_printed_receipt_no']."\t";
			$line .= find_hub_name($getTransaction['hub_name'])."\t";
                        $line .= find_business_type($getTransaction['business_type'])."\t";
                        $line .= "'".$getTransaction['agent_code']."\t";
                        $line .= $getTransaction['applicant_name']."\t";
                        $line .= $getTransaction['applicant_father_name']."\t";
                        $line .= $getTransaction['applicant_dob']."\t";
                        $line .= $getTransaction['applicant_address1']."\t";
                        $line .= $getTransaction['applicant_address2']."\t";
                        $line .= $getTransaction['applicant_address3']."\t";
                        $line .= "'".$getTransaction['mobile_no']."\t";
                        $line .= $getTransaction['state_id']."\t";
                        $line .= "'".$getTransaction['pin']."\t";
			$line .= $getTransaction['plan_name']."\t";
                        $line .= $getTransaction['term']."\t";
			$line .= $getTransaction['premium_paying_term']."\t";
			$line .= $getTransaction['pay_mode']."\t";
			$line .= $getTransaction['sum_asured']."\t";
                        $line .= $getTransaction['premium']."\t";
			$line .= "'".$getTransaction['cheque_no']."\t";
                        $line .= $cheque_date."\t";
                        $line .= $getTransaction['receive_cheque']."\t";
                        $line .= $getTransaction['receive_draft']."\t";
                        $line .= $getTransaction['receive_cash']."\t";
                        $line .= "'".$getTransaction['cheque_money_receipt ']."\t";
                        $line .= "'".$getTransaction['draft_money_receipt']."\t";
                        $line .= "'".$getTransaction['cash_money_receipt']."\t";
                        $line .= $getTransaction['bank']."\t";
                        $line .= $getTransaction['insured_name']."\t";
//                        $line .= $getTransaction['insured_dob']."\t";
                        $line .= $getTransaction['nominee_name']."\t";
			$line .= $getTransaction['nominee_dob']."\t";
                        $line .= $getTransaction['appointee_name']."\t";
                        $line .= $getTransaction['appointee_dob']."\t";
			$line .= $branch_scan."\t";
                        $line .= $branch_scan_date."\t";
                        $line .= $branch_despatched."\t";
                        $line .= $branch_despatch_date."\t";
                        $line .= $hub_despatched."\t";
                        $line .= $hub_despatch_date."\t";
                        $line .= $hub_received."\t";
                        $line .= $hub_receive_date."\t";
			$data .= trim($line)."\n";
			$nominee_address1 = '';
			$nominee_address2 = '';
			$nominee_address3 = '';
			$employers_address1 = '';
			$employers_address2 = '';
			$employers_address3 = '';
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
	header("Content-Disposition: attachment; filename=SICPL_new".'_'.date('m-d-Y_H:i').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";
?>