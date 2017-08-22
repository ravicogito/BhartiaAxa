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

$pageOwner = "'branch','admin','superadmin','hub','subsuperadmin'";

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
    
	if(isset($_SESSION['hub_id']) && $_SESSION['hub_id'] != '0') 
	{
		$where.= ' AND t_99_campaign.hub_id = "'.$_SESSION['hub_id'].'"';
	}
	if(isset($_SESSION['branch_id']) && $_SESSION['branch_id'] != '') 
	{
		$where.= ' AND t_99_campaign.branch_id = "'.$_SESSION['branch_id'].'"';
	}
	if(isset($_SESSION['campaign_code']) && $_SESSION['campaign_code'] != '') 
	{
		$where.= ' AND t_99_campaign.campaign_code = "'.$_SESSION['campaign_code'].'"';
	}
	if(isset($_SESSION['campaign_name']) && $_SESSION['campaign_name'] != '') 
	{
		$where.= ' AND  t_99_campaign.campaign ="'.$_SESSION['campaign_name'].'"';	
	}
//$sql="SELECT * FROM installment_master WHERE is_deleted='0' ".$where." ORDER BY id DESC";
$Query = "SELECT t_99_campaign.*,admin.name,admin.branch_code FROM t_99_campaign INNER JOIN admin ON t_99_campaign.branch_id=admin.id WHERE t_99_campaign.deleted_by_id=0 ".$where.$OrderBY.$Limit;
//  echo $sql;
//  exit;
$selTransaction = mysql_query($Query);
//echo "SELECT * FROM installment_master WHERE is_deleted=0 ".$where." ORDER BY id DESC";
$numTransaction = mysql_num_rows($selTransaction);
		
		header("Content-Type: text/plain");
		 		echo "<table>";
				echo '<thead>';
				echo "<tr>";
				echo "<th> HUB </th>";
				echo "<th> Branch Name</th>";
				echo "<th> Branch Code</th>";
				echo "<th> Campaign</th>";
				echo "<th> Campaign Code</th>";
				echo "</tr>";
				echo "</thead>";
				
				
		
		//$header .= "\n";
		
		//echo $header;
		//die;
	
		//$search = "/[ \r\n]/";
		if($numTransaction > 0)
		{
			echo "<tbody>";
			while($getTransaction = mysql_fetch_assoc($selTransaction))
			{
				echo "<tr>";
				echo "<td> ".new_find_hub_name($getTransaction['hub_id'])."</td>";
				echo "<td> ".$getTransaction['name']."</td>";
				echo "<td> ".$getTransaction['branch_code']."</td>";
				echo "<td> ".$getTransaction['campaign']."</td>";			
				echo "<td> ".$getTransaction['campaign_code']."</td>";
				echo "</tr>";
				//$data .= trim($line)."\n";
			}
			echo "</tbody>";
				
		}
		echo "</table>";
	/* $data = str_replace("\r", "", $data);
	if ($data == "")
	{
		$data = "\n(0) Records Found!\n";						
	} */
	
	//header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=SICPL_new".'_'.date('m-d-Y_H:i').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	//print "$header\n$data";
	
?>