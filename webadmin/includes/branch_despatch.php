<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
//print_r($_POST);

set_time_limit(0); 
$objDB = new DB();
$today = date('Y-m-d');

$pageOwner = "'superadmin','admin','hub','branch'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); // $USER_TYPE is coming from index.php

#print_r($_POST);
extract($_POST);

unset($_SESSION['from_date']);
unset($_SESSION['to_date']);
unset($_SESSION['branch_despatch']);
unset($_SESSION['hub_receive']);
unset($_SESSION['ins_despatch']);
unset($_SESSION['receipt_number']);
unset($_SESSION['application_no']);
unset($_SESSION['campaign_id']);

if(isset($received) && count($received) > 0)
{
	$receiveStr = implode(',',$received);
	$receivedUpdate = mysql_query("UPDATE installment_master_branch SET hub_received=1, hub_id=".$_SESSION[ADMIN_SESSION_VAR].", hub_receive_date='".$today."' WHERE id IN (".$receiveStr.") AND hub_id = 0");
}
if(isset($scanned) && count($scanned) > 0)
{
	$scanningStr = implode(',',$scanned);
	$scannedUpdate = mysql_query("UPDATE installment_master_branch SET scanning_done=1, hub_id=".$_SESSION[ADMIN_SESSION_VAR].", scanning_date='".$today."' WHERE id IN (".$scanningStr.") ");
}

if(isset($despatched) && count($despatched) > 0)
{
	$despatchStr = implode(',',$despatched);
	$despatchedUpdate = mysql_query("UPDATE installment_master_branch SET hub_despatched=1, hub_despatch_date='".$today."' WHERE id IN (".$despatchStr.") AND hub_id=".$_SESSION[ADMIN_SESSION_VAR]."");
}

if(isset($branch_despatched) && count($branch_despatched) > 0)
{
	$branch_despatchedStr = implode(',',$branch_despatched);
	$sql= "UPDATE installment_master_branch SET branch_despatched=1, branch_despatch_date='".$today."' WHERE id IN (".$branch_despatchedStr.")";
	//echo $sql;
	//exit;
	$branch_despatchedUpdate = mysql_query($sql);
}

if(isset($sp_tag_despatched) && count($sp_tag_despatched) > 0)
{
	$sp_tag_despatchedStr = implode(',',$sp_tag_despatched);
	$sql= "UPDATE installment_master_branch SET sp_tag_despatched=1, sp_despatched_date='".$today."' WHERE id IN (".$sp_tag_despatchedStr.")";
	//echo $sql;
	//exit;
	$sp_tag_despatchedUpdate = mysql_query($sql);
}

if(isset($admin_received) && count($admin_received) > 0)
{
	$admin_receiveStr = implode(',',$admin_received);
	$sql = "UPDATE installment_master_branch SET admin_received=1, admin_receive_date='".$today."' WHERE id IN (".$admin_receiveStr.")";
	//echo $sql;
	//exit;
	$admin_receivedUpdate = mysql_query($sql);
}

/*if(isset($mode) && $mode == 'del') // delete transaction
{
	#echo '<br />'.$transaction_id;
	$premium_array = find_premium_for_this_transaction($transaction_id);
	#print_r($premium_array);

	$this_folio_id = find_folio_id_through_transaction_id($transaction_id);
	#echo '<br />'.$this_folio_id;

	mysql_query("UPDATE customer_folio_no SET total_premium_given= total_premium_given - '".$premium_array['premium_number']."' WHERE id='".$this_folio_id."'"); // roll back number of premiums
	#echo '<br />'."UPDATE customer_folio_no SET total_premium_given= total_premium_given - '".$premium_array['premium_number']."' WHERE id='".$this_folio_id."'";
	
	mysql_query("UPDATE installment_master_branch SET is_deleted=1 WHERE id=".$transaction_id);
	#echo "UPDATE installment_master_branch SET is_deleted=1 WHERE id=".$transaction_id;

	#exit;

	$_SESSION[SUCCESS_MSG] = "Record deleted successfully...";
	header("location: index.php?p=".$_REQUEST['p']."");
	exit();

} */

// Write functions here


function find_premium_for_this_transaction($transaction_id)
{
	$return_array = array();
	#echo "<br /> SELECT amount / comitted_amount as premium, customer_id FROM installment_master_branch WHERE id='".$transaction_id."'";
	$selPremiumNumber = mysql_query("SELECT installment as premium, customer_id FROM installment_master_branch WHERE id='".$transaction_id."'");
	$numPremiumNumber = mysql_num_rows($selPremiumNumber);
	if($numPremiumNumber > 0)
	{
		$getPremiumNumber = mysql_fetch_array($selPremiumNumber);
		$return_array['premium_number'] = $getPremiumNumber['premium'];
		$return_array['customer_id'] = $getPremiumNumber['customer_id'];
	}
	return $return_array;
}


//$where = "WHERE is_deleted=0 AND branch_scan = 1 ";
$where = "WHERE is_deleted=0 AND (cash_pis_id != '0' OR cheque_pis_id != '0' OR draft_pis_id != '0')";
$OrderBY = " ORDER BY id DESC ";


//=======================================================

#############
//print_r($_POST);

$pid 			= loadVariable('pid',0);
	$showAll 		= loadVariable('all',0);
	$searchField 	= loadVariable('searchField','');
	$searchString 	= outputEscapeString(loadVariable('search',''));
	$sortField 		= loadVariable('sf','name');
	$sortType 		= loadVariable('st','ASC');
	$dataPerPage 	= loadVariable('dpp',25);
	$mid 			= loadVariable('mid',0);
	$mode			= loadVariable('mode','');

	if($showAll == 0)
	{
		loadFromSession('LIST_PAGE','search',$searchString);
		loadFromSession('LIST_PAGE','sf',$sortField);
		loadFromSession('LIST_PAGE','st',$sortType);
		loadFromSession('LIST_PAGE','dpp',$dataPerPage);
		loadFromSession('LIST_PAGE','mid',$mid);
		loadFromSession('LIST_PAGE','pid',$pid);
	}
	
	$searchString 	= outputEscapeString($searchString);

	##############

	##### CODE FOR SEARCHING 

	$branch_user_id = find_branch_user_id($_SESSION[ADMIN_SESSION_VAR]);

	#echo $branch_user_id;

	$user_string = '';
	if(intval($branch_user_id) == 0)
	{
		$user_string = find_branch_user_string($_SESSION[ADMIN_SESSION_VAR]);
	}

	$complete_user_string = trim($_SESSION[ADMIN_SESSION_VAR].','.$user_string, ',');
	//echo 'Hi '.$complete_user_string;
	//echo $_SESSION[ADMIN_SESSION_VAR].','.$user_string;

	$_SESSION['branch_name_string'] = $complete_user_string;
	
	//if(isset($branch_name))
	//{
		$_SESSION['branch_name'] = $_SESSION[ADMIN_SESSION_VAR];
	//}

	//if(isset($branch_name))
	//{
		//$_SESSION['branch_name'] = realTrim($branch_name);
	//}

	if(isset($_SESSION['branch_name_string']) && $_SESSION['branch_name_string'] != '') 
	{
		$where.= ' AND branch_id IN ('.$_SESSION['branch_name_string'].')';
	}

	if(isset($from_date))
	{
		$_SESSION['from_date'] = $from_date;
	}
	if(isset($campaign_id))
	{
		$_SESSION['campaign_id'] = $campaign_id;
	}
	

	if(isset($to_date))
	{
		$_SESSION['to_date'] = $to_date;
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
        
        if(isset($branch_despatch))
	{
		$_SESSION['branch_despatch'] = $branch_despatch;
	}
        
        if(isset($hub_receive))
	{
		$_SESSION['hub_receive'] = $hub_receive;
	}
        
        if(isset($ins_despatch))
	{
		$_SESSION['ins_despatch'] = $ins_despatch;
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

	if(isset($_SESSION['application_no']) && $_SESSION['application_no'] != '') 
	{
		$where.= ' AND application_no = "'.$_SESSION['application_no'].'"';
	}
	if(isset($_SESSION['campaign_id']) && $_SESSION['campaign_id'] != '') 
	{
		$where.= ' AND campaign_id = "'.$_SESSION['campaign_id'].'"';
	}

	if(isset($_SESSION['folio_no']) && $_SESSION['folio_no'] != '') 
	{
		$derivedFolioNo = find_folio_id($_SESSION['folio_no']);
		$where.= ' AND folio_no_id = "'.$derivedFolioNo.'"';
	}

	if(isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != '') 
	{
		$derivedCustID = find_id_through_customer_id($_SESSION['customer_id']);
		$where.= ' AND customer_id = "'.$derivedCustID.'"';
	}
        
        if(isset($_SESSION['branch_despatch']) && ($_SESSION['branch_despatch'] != '') && ($_SESSION['branch_despatch'] != 'Select All'))
	{
		$where.= ' AND branch_despatched = "'.$_SESSION['branch_despatch'].'"';
	}
        
        if(isset($_SESSION['hub_receive']) && ($_SESSION['hub_receive'] != '') && ($_SESSION['hub_receive'] != 'Select All'))
	{
		$where.= ' AND hub_received = "'.$_SESSION['hub_receive'].'"';
	}
        
        if(isset($_SESSION['ins_despatch']) && ($_SESSION['ins_despatch'] != '') && ($_SESSION['ins_despatch'] != 'Select All'))
	{
		$where.= ' AND hub_despatched = "'.$_SESSION['ins_despatch'].'"';
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

	##### CODE FOR SEARCHING 

/*if($_SESSION[ROLE_ID] == '3')
{
	$branchStr = '';
	$selBranches = mysql_query("SELECT id FROM admin WHERE hub_id=".$_SESSION[ADMIN_SESSION_VAR]."");
	if(mysql_num_rows($selBranches) > 0)
	{
		while($getBranches = mysql_fetch_array($selBranches))
		{
			#print_r($getBranches);
			$branchStr.= $getBranches['id'].',';
		}
	}
	$branchStr = trim($branchStr, ',');

	$where.=" AND branch_id IN (".$branchStr.")";
}*/



$Query = "select count(id) as CNT from installment_master_branch  ".$where;
//echo $Query;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;
include_once("../utility/pagination.php");

$Query = "SELECT * FROM installment_master_branch ".$where.$OrderBY.$Limit;
//echo $Query;
#exit;
$objDB->setQuery($Query);
$rs = $objDB->select();

$pageRecordCount = count($rs);

//==========================================================

$_SESSION['LIST_PAGE'][$GLOBALS['p']] = array(
'pg' 		=> $currentPage,
'search' 	=> $searchString,
'st' 		=> $sortType,
'sf' 		=> $sortField,
'dpp' 		=> $dataPerPage,
'mid' 		=> $mid,
'pid' 		=> $pid
);

//$selTransaction = mysql_query("SELECT id, branch_id, customer_id, deposit_date, comitted_amount, amount, tenure, transaction_charges, first_name, last_name, phone, email FROM installment_master_branch WHERE is_deleted=0 ORDER BY id DESC");
//$numTransaction = mysql_num_rows($selTransaction);
	

/*
echo "<pre>";
print_r($_POST);
die();
*/



?>


<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
<script type="text/javascript">
<!--
	function confirmDelete(ID)
	{
			if(confirm('Transaction will be deleted.\nAre you sure ? '))
			{
				
				document.sortFrm.mode.value='del';
				document.sortFrm.transaction_id.value=ID;
				document.sortFrm.submit();
			}
	}
	function dppList(dpp)
	{
		//alert(dpp1);
		document.sortFrm.dpp.value = dpp;
		document.sortFrm.pg.value= 1;
		document.sortFrm.submit();
		return true;
	}
//-->
</script>

<form name="addForm" id="addForm" action="" method="post" style="border:0px solid red">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="29">
        <? showMessage(); ?>
      </td>
    </tr>
    <tr> 
      <td colspan="29" align="right">
        <a title=" Export to Excel " href="<?=URL?>despatch_excel.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr>

	<tr> 
      <td colspan="29" align="left">
				<table>
					<!-- <tr>
						<td width="150"><strong>Branch</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="branch_name" id="" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
							<?php 
								$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2) AND branch_user_id=0 ORDER BY branch_name ASC");
								while($getBranch = mysql_fetch_array($selBranch))
								{					
							?>
								<option value="<?php echo $getBranch['id'];?>" <?php echo (isset($_SESSION['branch_name']) && ($_SESSION['branch_name']  == $getBranch['id']) ? 'selected' : ''); ?>><?php echo $getBranch['branch_name'];?></option>
							<?php } ?>
							</select>
						</td>
					</tr> -->
					<tr>
						<td width="150"><strong>From Date</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="from_date" id="from_date" type="text" class="inplogin" value="<?php echo (isset($_SESSION['from_date']) ? $_SESSION['from_date'] : ''); ?>" maxlength="20" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.from_date,'dd-mm-yyyy',this)" width="20" height="18">&nbsp;&nbsp;<a href="javascript:void(0)" onclick="javascript:document.addForm.from_date.value=''">Clear</a>
						</td>
					</tr>
					<tr>
						<td width="150"><strong>To Date</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="to_date" id="to_date" type="text" class="inplogin" value="<?php echo (isset($_SESSION['to_date']) ? $_SESSION['to_date'] : ''); ?>" maxlength="20" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.to_date,'dd-mm-yyyy',this)" width="20" height="18">&nbsp;&nbsp;<a href="javascript:void(0)" onclick="javascript:document.addForm.to_date.value=''">Clear</a>
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Quote Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="application_no" id="application_no" type="text" class="inplogin" value="<?php echo (isset($_SESSION['application_no']) ? $_SESSION['application_no'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Pre-Printed RECEIPT NUMBER</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="receipt_number" id="receipt_number" type="text" class="inplogin" value="<?php echo (isset($_SESSION['receipt_number']) ? $_SESSION['receipt_number'] : ''); ?>" maxlength="50" /> 
						</td>
					</tr>
                                        
					<tr>
						<td width="150"><strong>Branch Despatch</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="branch_despatch" id="branch_despatch" class="inplogin_select" style="width:140px;">
								<option>Select All</option>
								<option value=0 <?php if((isset($_SESSION['branch_despatch'])) && ($_SESSION['branch_despatch'] == '0')){?>selected="selecetd"<?php }?>>No</option>
								<option value=1 <?php if((isset($_SESSION['branch_despatch'])) && ($_SESSION['branch_despatch'] == '1')){?>selected="selecetd"<?php }?>>Yes</option>
							</select>
						</td>
					</tr>                                        
				    <tr>
						<td width="150"><strong>Despatch To Ins Co</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="ins_despatch" id="ins_despatch" class="inplogin_select" style="width:140px;">
								<option>Select All</option>
								<option value=0 <?php if((isset($_SESSION['ins_despatch'])) && ($_SESSION['ins_despatch'] == '0')){?>selected="selecetd"<?php }?>>No</option>
								<option value=1 <?php if((isset($_SESSION['ins_despatch'])) && ($_SESSION['ins_despatch'] == '1')){?>selected="selecetd"<?php }?>>Yes</option>
							</select>
						</td>
					</tr>
                    
                     <tr>
                      <td class="tbllogin" valign="top" align="left"><strong>Campaign</strong> </td>
                      <td class="tbllogin" valign="top" align="center">:</td>
                      <td valign="top" align="left">
                      <select name="campaign_id" id="campaign_id" class="inplogin"  style="width:150px;">
                      <option value=""  <?php if((isset($_SESSION['campaign_id'])) && ($_SESSION['campaign_id'] == '')){?>selected="selecetd"<?php }?>>Select All</option>
                    <option value="1" <?php if((isset($_SESSION['campaign_id'])) && ($_SESSION['campaign_id'] == '1')){?>selected="selecetd"<?php }?>>NONE</option>
                        
                        <?php 
										$branch_id=$_SESSION[ADMIN_SESSION_VAR];
										$hub_id=$_SESSION[HUB_ID];
										
										$query="select campaign_id, campaign from t_99_campaign WHERE (active_status=1) ";
										if($branch_id!=1 && $hub_id!=2 && $branch_id!=2)
										{
											$query.=" AND (branch_id='$branch_id')";
										}
										elseif($branch_id!=1 && $branch_id!=2)
										{
											if($hub_id==2)
											{
												$query.=" AND (hub_id='$branch_id')";
											}
										}
										$query.=" ORDER BY campaign ASC ";
										$selCampaign = mysql_query($query);   //for Campaign dropdown
										$numCampaign = mysql_num_rows($selCampaign);
                                    
                                        while($getCampaign = mysql_fetch_array($selCampaign))
                                        {	
                                                                
                                ?>
                        <option value="<?php echo $getCampaign['campaign_id']; ?>"  <?php if((isset($_SESSION['campaign_id'])) && ($_SESSION['campaign_id'] == $getCampaign['campaign_id'])){?>selected="selecetd"<?php }?> ><?php echo $getCampaign['campaign']; ?></option>
                        <?php
                                        }
                                    
                                ?>
                      </select></td>
                    </tr>                                     


					<tr>
						<td colspan="3" align="center"><input type="submit" name="btnSubmit" value="Search">&nbsp;<?php if(isset($_POST) && (count($_POST)) > 0){ ?><!-- <a href="xml_download.php" style="text-decoration:none;" ><input type="button" name="btnXML" value="Download XML"></a> --><?php } ?></td>
						
					</tr>
				</table>
        
      </td>
    </tr>

		<tr> 
      <td colspan="29" align="right">
        <? include_once("../utility/pagination_display.php");?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="29">Transaction Listing Of Despatch</td>
    </tr>
		<tr>
			<td colspan="29"><input type="submit" name="btnUpdate" value="Update" class="inplogin"></td>
		</tr>
    
    <tr> 
		<?php
			if(($_SESSION[ROLE_ID] == '1') || ($_SESSION[ROLE_ID] == '2')) // FOR SUPERADMIN, ADMIN	
				{
		?>
			<td width="8%" align="center" valign="top" class="tbllogin">Admin Received</td>
		<?php
				}	
		?>
		
	<?php
		if(intval($_SESSION[ROLE_ID]) == 4) // NOT FOR HUB
			{ 
	?>
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Despatched</td>
			<td width="8%" align="center" valign="top" class="tbllogin">SP Tag Despatched</td>
            <td width="8%" align="center" valign="top" class="tbllogin">Despatched To INS.CO.</td>
	<?php
			}	
	?>      
			<td width="8%" align="center" valign="top" class="tbllogin">PIS Generated</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Quote Number</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Pre-printed Receipt No</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Business Date</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Branch Name</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Campaign</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Hub Name</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Applicant Name</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Premium Amount</td>
<!--                        <td width="8%" align="center" valign="top" class="tbllogin">Payment Mode</td>-->
                        
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Despatch Date</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Hub Receive Date</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Hub Despatch Date</td>
			<!--<td width="8%" align="center" valign="top" class="tbllogin">Admin Receive Date</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Edit</td>
			 <td width="8%" align="center" valign="top" class="tbllogin">Employee Code</td> -->
<!--			<td width="8%" align="center" valign="top" class="tbllogin">Phone</td>-->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Email</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Subsequent Payment</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Receipt</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">New Plan With Existing Customers</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Status</td>
			
    </tr>
<?php
	if($pageRecordCount > 0)
	{
		for($i=0;$i<count($rs);$i++) { 
		 if(($i % 2)==0)
		 {
			$cls="text5";
		 }
		 else
		 {
			$cls="text6";
		 }
                 
                 if($rs[$i]['cash_pis_id'] == '0' && $rs[$i]['cheque_pis_id'] == '0' && $rs[$i]['draft_pis_id'] == '0'){
                    $pis = 'No';
                }else{
                    $pis = 'Yes';
                }
			$mysql_id = $rs[$i]['id'];
			#echo '<br />'.$rs[$i]['customer_id']; // this is autoincrement id of the customer_master table

			//$selMasterRecord = mysql_query("SELECT customer_id, first_name, middle_name, last_name, phone, email FROM customer_master WHERE id='".$rs[$i]['customer_id']."'");

			//if(mysql_num_rows($selMasterRecord) > 0)
			//{	
			//	$getMasterRecord = mysql_fetch_array($selMasterRecord);
			//}

			//$selFolioRecord = mysql_query("SELECT folio_no, committed_amount, tenure, nominee_name //FROM customer_folio_no WHERE id = '".$rs[$i]['folio_no_id']."'");

			//if(mysql_num_rows($selFolioRecord) > 0)
			//{
			//	$getFolioRecord = mysql_fetch_array($selFolioRecord);
			//}

			$branch_name = find_branch_name($rs[$i]['branch_id']);
			
			$campaign_id=$rs[$i]['campaign_id'];
			$Query = "select campaign from t_99_campaign WHERE campaign_id=$campaign_id";
			#echo $Query;
			$objDB->setQuery($Query);
			$rsCampaign = $objDB->select();


//			$payment_mode_service = $rs[$i]['payment_mode'];

			//$kyc_ok = kyc_ok($rs[$i]['customer_id']);
?>
	<tr> 

		<td width="8%" align="center" valign="top" ><?php if((intval($rs[$i]['cash_pis_id']) != 0) || (intval($rs[$i]['cheque_pis_id']) != 0) || (intval($rs[$i]['draft_pis_id']) != 0)){ ?><input type="checkbox" name="branch_despatched[]" value="<?php echo $mysql_id; ?>"<?php if($rs[$i]['branch_despatched'] == '1'){ echo 'disabled="disabled"';}?> <?php echo ($rs[$i]['branch_despatched'] == '1' ? 'checked' : '')?>><?php } ?></td>
		<td width="8%" align="center" valign="top" ><?php if((intval($rs[$i]['cash_pis_id']) != 0) || (intval($rs[$i]['cheque_pis_id']) != 0) || (intval($rs[$i]['draft_pis_id']) != 0)){ ?><input type="checkbox" name="sp_tag_despatched[]" value="<?php echo $mysql_id; ?>"<?php if($rs[$i]['sp_tag_despatched'] == '1'){ echo 'disabled="disabled"';}?> <?php echo ($rs[$i]['sp_tag_despatched'] == '1' ? 'checked' : '')?>><?php } ?></td>
		<td width="8%" align="center" valign="top" ><?php if((intval($rs[$i]['cash_pis_id']) != 0) || (intval($rs[$i]['cheque_pis_id']) != 0) || (intval($rs[$i]['draft_pis_id']) != 0)){ ?><input type="checkbox" name="admin_received[]" value="<?php echo $mysql_id; ?>"<?php if($rs[$i]['admin_received'] == '1'){ echo 'disabled="disabled"';}?> <?php echo ($rs[$i]['admin_received'] == '1' ? 'checked' : '')?>><?php } ?></td>
		<td width="8%" align="center" valign="top" ><?php echo $pis; ?></td>
		<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['application_no']; ?></td>
		<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['pre_printed_receipt_no']; ?></td>
		<td width="8%" align="center" valign="top" ><?php echo date('d/m/Y',strtotime($rs[$i]['business_date'])); ?></td>
		<td width="8%" align="center" valign="top" ><?php echo find_branch_name($rs[$i]['branch_id']); ?></td>
		<td width="8%" align="center" valign="top" ><?php echo $rsCampaign[0]['campaign']; ?></td>
		<td width="8%" align="center" valign="top" ><?php echo find_hub_name($rs[$i]['hub_id']); ?></td>
		<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['applicant_name']; ?></td>
		<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['premium']; ?></td>                
		<td width="8%" align="center" valign="top" >
			<?php 
				$branchdespatchDate = $rs[$i]['branch_despatch_date'] == '0000-00-00' ? 'Not Despatched' : date('d/m/Y', strtotime($rs[$i]['branch_despatch_date']));
				echo $branchdespatchDate; 
			?>
		</td>
		
		<td width="8%" align="center" valign="top" >
			<?php 
				$receiveDate = $rs[$i]['hub_receive_date'] == '0000-00-00' ? 'Not Received' : date('d/m/Y', strtotime($rs[$i]['hub_receive_date']));
				echo $receiveDate; 
			?>
		</td>
		<td width="8%" align="center" valign="top" >
			<?php 
				$despatchDate = $rs[$i]['hub_despatch_date'] == '0000-00-00' ? 'Not Despatched' : date('d/m/Y', strtotime($rs[$i]['hub_despatch_date']));
				echo $despatchDate; 
			?>
		</td>
		<?php
		$status = "Inside Branch";
		if($rs[$i]['branch_despatched'] == 1) {$status="Despatched from Branch"; }
		if($rs[$i]['sp_tag_despatched'] == 1) {$status="Despatched from SP"; }
		if($rs[$i]['admin_received'] == 1) {$status="Despatched to ADMIN"; }
		if($rs[$i]['branch_despatched'] && $rs[$i]['sp_tag_despatched'] == 1 && $rs[$i]['admin_received'] == 1){$status="Despatched"; }
		?>
			<td width="8%" align="center" valign="top" ><?= $status;?></td>

    </tr>

<?php		
		}
?>
		<tr>
			<td colspan="29" style="border:0px solid red"><input type="submit" name="btnUpdate" value="Update" class="inplogin"></td>
		</tr>
<?php
	}
?>
    

  </tbody>
</table>
</form>
<form name="sortFrm" action="" method="post">
	<input type="hidden" name="transaction_id" value="">
	<input type="hidden" name="mode" value="">

	<input type="hidden" name="user_id" value="">
	<input type="hidden" name="st" id="st" value="<?=$sortType?>" >
	<input type="hidden" name="sf" id="sf" value="<?=$sortField?>" >
	<input type="hidden" name="pg" id="pg" value="<?=$currentPage?>" >
	<input type="hidden" name="p" id="p" value="<?=$GLOBALS['p']?>" >
	<input type="hidden" name="mid" id="mid" value="<?=$mid?>" >
	<input type="hidden" name="dpp" id="dpp" value="<?=$dataPerPage?>" >
	<INPUT type="hidden" name="search" id="search" value="<?=stripslashes($searchString)?>">  
	<INPUT type="hidden" name="searchField" id="searchField" value="<?=$searchField?>">

</form>
<form name="frm_opts" action="<?=$_SERVER['PHP_SELF'];?>?p=<?=$_REQUEST['p']?>" method="post" >
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="user_id" value="">
	<input type="hidden" name="st" id="st" value="<?=$sortType?>" >
	<input type="hidden" name="sf" id="sf" value="<?=$sortField?>" >
	<input type="hidden" name="pg" id="pg" value="<?=$currentPage?>" >
	<input type="hidden" name="p" id="p" value="<?=$GLOBALS['p']?>" >
	<input type="hidden" name="mid" id="mid" value="<?=$mid?>" >
	<input type="hidden" name="dpp" id="dpp" value="<?=$dataPerPage?>" >
	<INPUT type="hidden" name="search" id="search" value="<?=stripslashes($searchString)?>">
	<INPUT type="hidden" name="searchField" id="searchField" value="<?=$searchField?>"> 
</form>

<SCRIPT LANGUAGE="JavaScript">

<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=1,width=800,height=600,left = 112,top = 84');");
}
// End -->
</script>

<?php //$objDB->close(); ?>

