<script>	



function getbranch(hub)
{
//alert(hub);
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
	//alert(hub);
  if (xmlhttp.readyState==4)
    {
	 //alert(xmlhttp.responseText);
	document.getElementById("branch1").innerHTML=xmlhttp.responseText;
   }
  }
xmlhttp.open("GET","getbranch.php?hub="+hub,true);
xmlhttp.send();
}
</script>

<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
//print_r($_POST);

set_time_limit(0); 

$pageOwner = "'superadmin','admin','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 


extract($_POST);




// Write functions here

$objDB = new DB();
$today = date('Y-m-d');

$where = "WHERE is_deleted=0 AND health = 4";
$OrderBY = " ORDER BY branch_id DESC ";


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

	
	if(isset($hub_name))
	{
		$_SESSION['hub_name'] = realTrim($hub_name);
	}

	
	
	if(isset($branch_name))
	{
		$_SESSION['branch_name'] = $branch_name;
	}

	if(isset($from_date))
	{
		$_SESSION['from_date'] = $from_date;
	}

	if(isset($to_date))
	{
		$_SESSION['to_date'] = $to_date;
	}



	/*if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '')
	{
		$branchStr = '';
		$selBranches = mysql_query("SELECT id FROM admin WHERE hub_id=".$_SESSION['branch_name']."");
		if(mysql_num_rows($selBranches) > 0)
		{
			while($getBranches = mysql_fetch_array($selBranches))
			{
				#print_r($getBranches);
				$branchStr.= $getBranches['id'].',';
			}
		}
		$branchStr = trim($branchStr, ',');

		if($branchStr != '')
		{
			$where.=" AND branch_id IN (".$branchStr.")";
		}
	}*/
	if(isset($_SESSION['hub_name']) && $_SESSION['hub_name'] != '') 
	{
		//$where.= ' AND  hub_id ="'.$_SESSION['hub_name'].'"';

		
		
		
		$branch_usr_str = find_branch_from_hub($_SESSION['hub_name']);
		$branch_usr_str_with_admin = $branch_usr_str.','.$_SESSION['hub_name'];
		$where.= " AND branch_id IN (".trim($branch_usr_str_with_admin,',').")";
	
	}

	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '') // this is actually branch id
	{
		
		//echo $_SESSION['branch_name'];
		//exit;
		$branch_usr_str = find_branch_user_string($_SESSION['branch_name']);
		$branch_usr_str_with_admin = $branch_usr_str.','.$_SESSION['branch_name'];
		$where.= " AND branch_id IN (".trim($branch_usr_str_with_admin,',').")";
	}


	if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
	{
		$where.= ' AND deposit_date >="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
	}

	
	if(isset($_SESSION['to_date']) && $_SESSION['to_date'] != '') 
	{
		$where.= ' AND deposit_date <="'.date('Y-m-d', strtotime($_SESSION['to_date'])).'"';
	}


	##### CODE FOR SEARCHING 


$Query = "select count(id) as CNT from renewal_master_gtfs  ".$where;
#echo $Query;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;
include_once("../utility/pagination.php");

$pageRecordCount = 0;



$Query = "SELECT * FROM renewal_master_gtfs ".$where.$OrderBY.$Limit;

#echo $Query;
#print_r($_POST);

if(isset($_SESSION['hub_name']) && $_SESSION['hub_name'] != '' && isset($_SESSION['from_date']) && $_SESSION['from_date'] != '' )
{
	//echo "hi";
	$objDB->setQuery($Query);
	$rs = $objDB->select();

	$pageRecordCount = count($rs);
	//echo $pageRecordCount;
	//exit;
}

if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '' && isset($_SESSION['from_date']) && $_SESSION['from_date'] != '' )
{
	//echo "hi";
	$objDB->setQuery($Query);
	$rs = $objDB->select();

	$pageRecordCount = count($rs);
	//echo $pageRecordCount;
	//exit;
}

#$objDB->setQuery($Query);
#$rs = $objDB->select();

#$pageRecordCount = count($rs);

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

//$selTransaction = mysql_query("SELECT id, branch_id, customer_id, deposit_date, comitted_amount, amount, tenure, transaction_charges, first_name, last_name, phone, email FROM renewal_master WHERE is_deleted=0 ORDER BY id DESC");
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

<script type="text/javascript">
<!--
	function dochk()
	{
		if(document.addForm.hub_name.value.search(/\S/) == -1)
		{
			alert("Please select Hub Name");
			document.addForm.branch_name.focus();
			return false;
		}		

		if(document.addForm.from_date.value.search(/\S/) == -1)
		{
			alert("Please select Date");
			document.addForm.from_date.focus();
			return false;
		}
	}

//-->
</script>

<form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="20">
        <? showMessage(); ?>
      </td>
    </tr>
		<?php
			if(isset($_SESSION['branch_name']) && isset($_SESSION['from_date']))
			{
		?>
		<tr> 
      <td colspan="20" align="right">
        <a title=" Export to Excel " href="<?=URL?>renewal_mis_excel_gtfs.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr>
		<?php
			}	
		?>

	<tr> 
      <td colspan="20" align="left">
				<table>
				<tr>
						<td colspan="3" align="center"><strong>MIS For Hub<br/><br/></strong></td>
						
					</tr>
					<tr> 
						<td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
							* marked fields are mandatory</font></b></td>
					</tr>
					
					<!-- <tr>
						<td width="150"><strong>Application Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="application_no" id="application_no" type="text" class="inplogin" value="<?php echo (isset($_SESSION['application_no']) ? $_SESSION['application_no'] : ''); ?>" /> 
						</td>
					</tr>

					<tr>
						<td width="150"><strong>Receipt Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="receipt_number" id="receipt_number" type="text" class="inplogin" value="<?php echo (isset($_SESSION['receipt_number']) ? $_SESSION['receipt_number'] : ''); ?>"  /> 
						</td>
					</tr>

					<tr>
						<td width="150"><strong>Customer ID</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="customerID" id="customerID" type="text" class="inplogin" value="<?php echo (isset($_SESSION['customerID']) ? $_SESSION['customerID'] : ''); ?>" /> 
						</td>
					</tr> -->
					
					<?php 
						if($_SESSION[ROLE_ID] == '3')
						{
					?>
					<input type="hidden" name="branch_name" id="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR];?>">
					<?php
						}
						else
						{
					?>
					<tr> 
      <td colspan="30" align="left">
				<table>
					<tr>
						<td width="150"><strong>Hub</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="hub_name" id="" class="inplogin_select" style="width:140px;" onchange="getbranch(this.value);">
								<option value="">Select All</option>
							<?php 
								$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,5,6) AND role_id = 3 AND branch_user_id=0 ORDER BY branch_name ASC");
								while($getBranch = mysql_fetch_array($selBranch))
								{					
							?>
								<option value="<?php echo $getBranch['id'];?>" <?php echo (isset($_SESSION['hub_name']) && ($_SESSION['hub_name']  == $getBranch['id']) ? 'selected' : ''); ?>><?php echo $getBranch['branch_name'];?></option>
							<?php } ?>
							</select>
						</td>
					</tr>
					
					<tr>
						<td width="150"><strong>Branch</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
						<div id="branch1">
						
						<?php if(isset($_SESSION['hub_name']))
								{
								$branch_sql = 'select * from admin where hub_id="'.$_SESSION['hub_name'].'" AND role_id != 3';
								//echo $branch_sql;
								$branch_query = mysql_query($branch_sql);
								$branch_num_row = mysql_num_rows($branch_query);
								//$brancharr = mysql_fetch_array($branch_query)
								//echo $_SESSION['branch_name'];
								?>
							<select name="branch_name" id="branch_name" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
							<?php  while($brancharr = mysql_fetch_array($branch_query))
							
									{?>
								<option value="<?php echo $brancharr['id']; ?>" <?php echo (isset($_SESSION['branch_name']) && ($_SESSION['branch_name']  == $brancharr['id']) ? 'selected' : ''); ?>><?php echo $brancharr['branch_name']; ?></option> <?php }?>
							
							</select><?php  }else{ ?>
							<select name="branch_name" id="branch_name" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
								</select>
							
							
							<?php } ?></div>
						</td>
					</tr>
					
					<?php 
						}			
					?>
				
					<?php 
						if($_SESSION[ROLE_ID] == '3')
						{
					?>

					<tr>
						<td width="150"><strong>Branch</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
						<div id="branch1">
						
						<?php if(isset($_SESSION[ADMIN_SESSION_VAR]))
								{
								$branch_sql = 'select * from admin where hub_id="'.$_SESSION[ADMIN_SESSION_VAR].'" AND role_id != 3';
								//echo $branch_sql;
								$branch_query = mysql_query($branch_sql);
								$branch_num_row = mysql_num_rows($branch_query);
								//$brancharr = mysql_fetch_array($branch_query)
								//echo $_SESSION['branch_name'];
								?>
							<select name="branch_name" id="branch_name" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
							<?php  while($brancharr = mysql_fetch_array($branch_query))
							
									{?>
								<option value="<?php echo $brancharr['id']; ?>" <?php echo (isset($_SESSION['branch_name']) && ($_SESSION['branch_name']  == $brancharr['id']) ? 'selected' : ''); ?>><?php echo $brancharr['branch_name']; ?></option> <?php }?>
							
							</select><?php  }else{ ?>
							<select name="branch_name" id="branch_name" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
								</select>
							
							
							<?php } ?></div>
						</td>
					</tr>
					<?php } ?>



					
					<tr>
						<td width="150"><strong>From Date<font color="#ff0000">*</font></strong></td>
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
					<!-- <tr>
						<td width="150"><strong>First Name</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="first_name" id="first_name" type="text" class="inplogin" value="<?php echo (isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''); ?>" /> 
						</td>
					</tr> -->
					<tr>
						<td colspan="3" align="center"><input type="submit" name="btnSubmit" value="Search" class="inplogin"></td>
						
					</tr>
				</table>
        
      </td>
    </tr>

		<tr> 
      <td colspan="20" align="right">
        <? include_once("../utility/pagination_display.php");?>
      </td>
    </tr>
		<?php
			if($pageRecordCount > 0)
			{
		?>
    <tr class="TDHEAD"> 
      <td colspan="20">Renewal Listing</td>
    </tr>
		<!-- <tr>
			<td colspan="20"><input type="submit" name="btnUpdate" value="Update"></td>
		</tr> -->
    
    <tr> 
      <!-- <td width="8%" align="center" valign="top" class="tbllogin">Received</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Despatched</td> -->
			
			<td width="8%" align="center" valign="top" class="tbllogin">Policy Number</td>
			
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">HUB Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Deposit Date</td>			
			<td width="8%" align="center" valign="top" class="tbllogin">Name</td>
			
			<td width="8%" align="center" valign="top" class="tbllogin">Committed Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Tenure (Years)</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Service Charges</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Payment Type</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Hub Receive Date</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Hub Despatch Date</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Employee Code</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Phone</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Email</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Edit</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Receipt</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Status</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Delete</td> -->
    </tr>
<?php
	
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
			//$trusted_id = find_customer_id_through_id($rs[$i]['customer_id']);
			$branch_name = find_branch_name($rs[$i]['branch_id']);
			//$employee_code = $rs[$i]['employee_code'];
			$payment_mode = $rs[$i]['payment_mode'];

			//$selMasterRecord = mysql_query("SELECT customer_id, first_name, middle_name, last_name, phone, email, nominee_name FROM customer_master WHERE id='".$rs[$i]['customer_id']."'");

			//if(mysql_num_rows($selMasterRecord) > 0)
			//{	
			//	$getMasterRecord = mysql_fetch_array($selMasterRecord);
			//}

			//$selFolioRecord = mysql_query("SELECT folio_no, committed_amount, tenure FROM customer_folio_no WHERE id = '".$rs[$i]['folio_no_id']."'");

			//if(mysql_num_rows($selFolioRecord) > 0)
		//	{
			//	$getFolioRecord = mysql_fetch_array($selFolioRecord);
			//}
?>
	
	<tr> 
      <!-- <td width="8%" align="center" valign="top" ><input type="checkbox" name="received[]" value="<?php echo $mysql_id; ?>" <?php echo ($rs[$i]['hub_received'] == '1' ? 'checked' : '')?>></td>
			<td width="8%" align="center" valign="top" ><input type="checkbox" name="despatched[]" value="<?php echo $mysql_id; ?>" <?php echo ($rs[$i]['hub_despatched'] == '1' ? 'checked' : '')?>></td> -->
			
			
			<?php 
			//echo "<pre>";
			//print_r($rs); ?>
			
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['policy_no']; ?></td>
			
			<td width="8%" align="center" valign="top" ><?php echo $branch_name; ?></td>
			<td width="8%" align="center" valign="top" >
				<?php echo find_branch_name($rs[$i]['hub_id']); ?>
			</td><!-- hub -->
			<td width="8%" align="center" valign="top" ><?php echo date('d-m-Y', strtotime($rs[$i]['deposit_date'])); ?></td>			
			<td width="8%" align="center" valign="top" ><?php echo ucwords($rs[$i]['insured_name']); ?></td>
			
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['sum_assured']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['tenure']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['amount']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['transaction_charges']; ?>
			</td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['payment_mode']; ?></td>
			
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
			<!-- <td width="8%" align="center" valign="top" ><?php echo $rs[$i]['email']; ?></td> --> 
			<!-- <td width="8%" align="center" valign="top" ><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_edit.php?id=<?php echo base64_encode($mysql_id); ?>')">Edit</a></td>
			<td width="8%" align="center" valign="top" >
			<?php if($employee_code == ''){ 
							if($payment_mode != ''){	
			?>
				<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window.php?id=<?php echo base64_encode($mysql_id); ?>')">Generate</a>
	<?php				}
						} else {
						echo $employee_code;
						}
	?>	
			</td> -->
	<?php
		$status = "Inside Branch";
		if($rs[$i]['hub_received'] == 1) {$status="Sent to Hub"; }
		if($rs[$i]['hub_despatched'] == 1) {$status="Despatched from Hub"; }
	?>
			<td width="8%" align="center" valign="top" ><?= $status;?></td>
			<!-- <td width="8%" align="center" valign="top" ><a title="Delete" href="#" onclick="javascript:confirmDelete(<?=$mysql_id; ?>)"><img src="images/delete_icon.gif" border="0" /></a></td> -->
    </tr>

<?php		
		}

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

