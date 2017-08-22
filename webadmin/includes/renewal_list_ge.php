<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
//print_r($_POST);
set_time_limit(0); 

unset($_SESSION['business_type']);
unset($_SESSION['campaign_id']);

$pageOwner = "'branch','superadmin','admin','hub','subsuperadmin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); // $USER_TYPE is coming from index.php
#print_r($_POST);
extract($_POST);
if(isset($mode) && $mode == 'del') // delete transaction
{	
	
	mysql_query("UPDATE installment_master SET is_deleted=1 WHERE id=".$transaction_id);
	
	$_SESSION[SUCCESS_MSG] = "Record deleted successfully...";
	header("location: index.php?p=".$_REQUEST['p']."");
	exit();
}
$today = date('Y-m-d');
if(isset($branch_scan) && count($branch_scan) > 0)
{
	$receiveStr = implode(',',$branch_scan);
	$receivedUpdate = mysql_query("UPDATE installment_master SET branch_scan=1,  branch_scan_date='".$today."' WHERE id IN (".$receiveStr.") ");
}
// Write functions here
$objDB = new DB();
$where = "WHERE is_deleted=0 ";
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
	$branch_name = $_SESSION[ADMIN_SESSION_VAR];
	if(isset($branch_name))
	{
		$_SESSION['branch_name'] = realTrim($branch_name);
	}
	if(isset($from_date))
	{
		$_SESSION['from_date'] = $from_date;
	}
        if(isset($business_type))
	{
		$_SESSION['business_type'] = realTrim($business_type);
	}
	if(isset($to_date))
	{
		$_SESSION['to_date'] = $to_date;
	}
	if(isset($campaign_id))
	{
		$_SESSION['campaign_id'] = $campaign_id;
	}

/*
	if(isset($receipt_number))
	{
		$_SESSION['receipt_number'] = realTrim($receipt_number);
	}
*/	
	if(isset($receipt_serial_no))
	{
		$_SESSION['receipt_serial_no'] = realTrim($receipt_serial_no);
	}
	if(isset($application_no))
	{
		$_SESSION['application_no'] = realTrim($application_no);
	}
	if(isset($hub_name_id))
	{
		$_SESSION['hub_name_id'] = realTrim($hub_name_id);
	}
	if(isset($branch_name_id))
	{
		$_SESSION['branch_name_id'] = realTrim($branch_name_id);
	}
/*
	if(isset($folio_no))
	{
		$_SESSION['folio_no'] = realTrim($folio_no);
	}
	if(isset($customer_id))
	{
		$_SESSION['customer_id'] = realTrim($customer_id);
	}
*/	
	if(isset($pis_generated))
	{
		$_SESSION['pis_generated'] = $pis_generated;
	}
/*
	if(isset($first_name))
	{
		$_SESSION['first_name'] = realTrim($first_name);
	}
	if(isset($last_name))
	{
		$_SESSION['last_name'] = realTrim($last_name);
	}
*/
/* 
   ########
	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '') // this is actually branch id
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name'].'"';
	}
	
	########
	
*/
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
		
	if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
	{
		$where.= ' AND business_date >="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
	}
	if(isset($_SESSION['to_date']) && $_SESSION['to_date'] != '') 
	{
		$where.= ' AND business_date <="'.date('Y-m-d', strtotime($_SESSION['to_date'])).'"';
	}
        
    if(isset($_SESSION['business_type']) && $_SESSION['business_type'] != '' && $_SESSION['business_type'] != 'Select All') 
	{
		$where.= ' AND business_type = "'.$_SESSION['business_type'].'"';
	}
	if(isset($_SESSION['campaign_id']) && $_SESSION['campaign_id'] != '' && $_SESSION['campaign_id'] != '0') 
	{
		$where.= ' AND campaign_id = "'.$_SESSION['campaign_id'].'"';
	}
	
	
/*
	if(isset($_SESSION['receipt_number']) && $_SESSION['receipt_number'] != '') 
	{
		$where.= ' AND receipt_number LIKE "%'.$_SESSION['receipt_number'].'%"';
	}
*/
if(isset($_SESSION['hub_name_id']) && $_SESSION['hub_name_id'] != '') 
	{
		$where.= ' AND hub_id="'.$_SESSION['hub_name_id'].'"';
	}
	if(isset($_SESSION['branch_name_id']) && $_SESSION['branch_name_id'] != '') 
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name_id'].'"';
	}
	
	if(isset($_SESSION['application_no']) && $_SESSION['application_no'] != '') 
	{
		$where.= ' AND policy_no LIKE "%'.$_SESSION['application_no'].'%"';
	}
	if(isset($_SESSION['receipt_serial_no']) && $_SESSION['receipt_serial_no'] != '') 
	{
		$where.= ' AND pre_printed_receipt_no = "'.$_SESSION['receipt_serial_no'].'"';
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
	##### CODE FOR SEARCHING 
$Query = "select count(id) as CNT from installment_master_ge_renewal  ".$where;
$Query;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];
$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;
include_once("../utility/pagination.php");
$Query = "SELECT * FROM installment_master_ge_renewal ".$where.$OrderBY.$Limit;
#echo $Query;
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
      <td colspan="25">
        <? showMessage(); ?>
      </td>
    </tr>
		<tr> 
      <td colspan="25" align="right">
        <a title=" Export to Excel " href="<?=URL?>transaction_excel_renewal_ge.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr>
	<tr> 
      <td colspan="25" align="left">
				<table>
				<?php if($_SESSION[ROLE_ID]=="1" || $_SESSION[ROLE_ID]=="2"): ?>
					<tr>
						<td width="150"><strong>Hub</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="hub_name_id" id="hub_name_id" class="inplogin_select" style="width:140px;" onchange="getbranch(this.value);">
								<option value="">Select All</option>
							<?php 
								$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,5,6) AND role_id = 3 AND branch_user_id=0 ORDER BY branch_name ASC");
								while($getBranch = mysql_fetch_array($selBranch))
								{					
							?>
								<option value="<?php echo $getBranch['id'];?>" <?php echo (isset($_SESSION['hub_name_id']) && ($_SESSION['hub_name_id']  == $getBranch['id']) ? 'selected' : ''); ?>><?php echo $getBranch['branch_name'];?></option>
							<?php } ?>
							</select>
						</td>
					</tr>
					<?php endif; ?>
					<?php if($_SESSION[ROLE_ID]=="1" || $_SESSION[ROLE_ID]=="2" || $_SESSION[ROLE_ID]=="3"): ?>
					<tr>
						<td width="150"><strong>Branch</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
						<div id="branch1">
						
						<?php 
								 $branch_where="";
								 
								 if($_SESSION[ROLE_ID]=="3")
								 {
								 	$branch_where=" and hub_id='$hub_id'";
								 }
								$branch_sql = 'select * from admin where  role_id = 4'.$branch_where.' ORDER BY branch_name ASC';
								//echo $branch_sql;
								$branch_query = mysql_query($branch_sql);
								$branch_num_row = mysql_num_rows($branch_query);
								//$brancharr = mysql_fetch_array($branch_query)
								//echo $_SESSION['branch_name'];
								?>
							<select name="branch_name_id" id="branch_name_id" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
							<?php  while($brancharr = mysql_fetch_array($branch_query))
							
									{?>
								<option value="<?php echo $brancharr['id']; ?>" <?php echo (isset($_SESSION['branch_name_id']) && ($_SESSION['branch_name_id']  == $brancharr['id']) ? 'selected' : ''); ?>><?php echo $brancharr['branch_name']; ?></option> <?php }?>
							
							</select></div>
						</td>
					</tr>
					<?php endif; ?>
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
						<td width="150"><strong>Policy Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="application_no" id="application_no" type="text" class="inplogin" value="<?php echo (isset($_SESSION['application_no']) ? $_SESSION['application_no'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Pre Printed Receipt No</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="receipt_serial_no" id="receipt_serial_no" type="text" class="inplogin" value="<?php echo (isset($_SESSION['receipt_serial_no']) ? $_SESSION['receipt_serial_no'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>PIS Generated</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="pis_generated" id="pis_generated" class="inplogin_select" style="width:140px;">
								<option>Select All</option>
								<option value=0 <?php if((isset($_SESSION['pis_generated'])) && ($_SESSION['pis_generated'] == '0')){?>selected="selecetd"<?php }?>>No</option>
								<option value=1 <?php if((isset($_SESSION['pis_generated'])) && ($_SESSION['pis_generated'] == '1')){?>selected="selecetd"<?php }?>>Yes</option>
							</select>
						</td>
					</tr>
                                        
                                        <tr>
						<td width="150"><strong>Business Type</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="business_type" id="business_type" class="inplogin_select" style="width:140px;">
								<option>Select All</option>
                                                                <?php
                                                                $Query = "SELECT * FROM business_type";
                                                                $objDB->setQuery($Query);
                                                                $rsp = $objDB->select();
                                                                for($i=0;$i<count($rsp);$i++){
                                                                ?>
								<option value='<?php echo $rsp[$i]['id'];?>' <?php if((isset($_SESSION['business_type'])) && ($_SESSION['business_type'] == $rsp[$i]['id'])){?>selected="selecetd"<?php }?>><?php echo $rsp[$i]['business_type'];?></option>
                                                                <?php
                                                                }
                                                                ?>
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
										if($branch_id!=2 && $hub_id!=189 && $hub_id!=2 && $branch_id!=384)
										{
											$query.=" AND (branch_id='$branch_id')";
										}
										/*elseif($branch_id!=2 && $branch_id!=384)
										{
											if($hub_id==189)
											{
												$query.=" AND (hub_id='$branch_id')";
											}
										}*/
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
						<td colspan="3" align="center"><input type="submit" name="btnSubmit" value="Search">&nbsp;<?php if(isset($_POST) && (count($_POST)) > 0){ ?><!-- <a href="xml_download.php" style="text-decoration:none;" ><input type="button" name="btnXML" value="Download XML"> --></a><?php } ?></td>
						
					</tr>
				</table>
        
      </td>
    </tr>
		<tr> 
      <td colspan="25" align="right">
        <? include_once("../utility/pagination_display.php");?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="25">Transaction Listing</td>
    </tr>
    <!--
	<tr>
			<td colspan="28"><input type="submit" name="btnUpdate" value="Update" class="inplogin"></td>
		</tr>
		-->
    <tr> 
		
			<td width="8%" align="center" valign="top" class="tbllogin">Policy Number</td>
			<td width="8%" align="center" valign="top" class="tbllogin">PIS Generated</td> 
			<td width="8%" align="center" valign="top" class="tbllogin">Pre Printed Receipt No </td> 
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Name</td>
            <td width="8%" align="center" valign="top" class="tbllogin">Campaign</td>
			       
			<td width="8%" align="center" valign="top" class="tbllogin">Plan Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Business Date</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Business Type</td>
			<td width="16%" align="center" valign="top" class="tbllogin">Applicant Name</td>
			
			<td width="8%" align="center" valign="top" class="tbllogin">Receive Cash</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Receive Cheque</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Receive Draft</td>
			
			<td width="8%" align="center" valign="top" class="tbllogin">Premium Amount</td>
			
			<td width="8%" align="center" valign="top" class="tbllogin">Health</td>
			<?php if($_SESSION[ROLE_ID] == '1' || $_SESSION[ROLE_ID] == '4'){ ?>			
			<td width="8%" align="center" valign="top" class="tbllogin">Edit</td>
			<?php }
			 if($_SESSION[ROLE_ID] == '1'){ ?>
			<td width="8%" align="center" valign="top" class="tbllogin">Cancel</td>
			<?php } ?>
			
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
			$mysql_id = $rs[$i]['id'];
			
			$branch_name = find_branch_name($rs[$i]['branch_id']);

$campaign_id=$rs[$i]['campaign_id'];
			$Query = "select campaign from t_99_campaign WHERE campaign_id=$campaign_id";
			//echo $Query;
			$objDB->setQuery($Query);
			$rsCampaign = $objDB->select();
			
?>
	<tr> 
				<td width="8%" align="center" valign="top" >
			<?php echo $rs[$i]['policy_no']; ?><br />
						
			</td>
			<td width="8%" align="center" valign="top" >
			<?php if($rs[$i]['cash_pis_id']=='0' && $rs[$i]['cheque_pis_id']=='0' && $rs[$i]['draft_pis_id']=='0')
				{
					echo "No";
				}
				else
				{
					echo "Yes";
				}
			
			 ?><br />
						
			</td>
					<td width="8%" align="center" valign="top" >
			<?php echo $rs[$i]['pre_printed_receipt_no']; ?><br />
						
			</td>
			
			<td width="8%" align="center" valign="top" ><?php echo $branch_name; ?></td>
            <td width="8%" align="center" valign="top" ><?php echo $rsCampaign[0]['campaign'] ?></td>
			<td width="8%" align="center" valign="top" ><?php echo stripslashes($rs[$i]['plan_name']); ?></td>
			<td width="8%" align="center" valign="top" ><?php echo date('d/m/Y', strtotime($rs[$i]['business_date'])); ?></td>
                        <td width="8%" align="center" valign="top" ><?php echo find_business_type($rs[$i]['business_type']); ?></td>
			<td width="16%" align="center" valign="top" ><?php echo stripslashes($rs[$i]['applicant_name']); ?></td>
			
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['receive_cash']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['receive_cheque']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['receive_draft']; ?></td>
			
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['premium']; ?></td>
			
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['health']; ?></td>
			
			
			<?php if($_SESSION[ROLE_ID] == '1' || $_SESSION[ROLE_ID] == '4'){
                            
                            ?>
			
                            <td width="8%" align="center" valign="top" > 
                                <?php if($rs[$i]['cash_pis_id'] == '0' && $rs[$i]['cheque_pis_id'] == '0' && $rs[$i]['draft_pis_id'] == '0' && $_SESSION[ROLE_ID] == '4'){ ?>
                                <a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_edit_renewal_ge.php?id=<?php echo base64_encode($mysql_id); ?>')">Edit</a>
                                <?php }elseif ($_SESSION[ROLE_ID] == '1') { ?>
                                <a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_edit_renewal_ge.php?id=<?php echo base64_encode($mysql_id); ?>')">Edit</a>                
                                <?php  } ?>
                            </td>
			<?php
                            
                            }
			if($_SESSION[ROLE_ID] == '1'){ ?>
			<td width="8%" align="center" valign="top" >
			<?php if($rs[$i]['cash_pis_id'] == '0' && $rs[$i]['cheque_pis_id'] == '0' && $rs[$i]['draft_pis_id'] == '0'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/renewal_ge_cancel.php?id=<?php echo base64_encode($mysql_id); ?>')">Cancel</a>
                        <?php } ?>
			</td>
			<?php } ?>
			
    </tr>
<?php		
		}
	}
?>
    <!--
	<tr>
			<td colspan="28" style="border:0px solid red"><input type="submit" name="btnUpdate" value="Update" class="inplogin"></td>
		</tr>
		-->
		
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
<!-- Idea by:  Nic Wolfe -->
<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=1,width=800,height=600,left = 112,top = 84');");
}
// End -->
</script>
<?php //$objDB->close(); ?>
