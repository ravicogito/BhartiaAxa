<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
//print_r($_POST);

set_time_limit(0); 

$pageOwner = "'branch','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

$pis_mode = '';
extract($_POST);




// Write functions here

$objDB = new DB();
$today = date('Y-m-d');

$where = "WHERE is_deleted=0 ";
$OrderBY = " ORDER BY pis_date, pis_mode DESC ";


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

	
	//if(isset($branch_name))
	//{
		//$_SESSION['branch_name'] = $branch_name;
	//}
	$_SESSION['branch_name'] = $_SESSION[ADMIN_SESSION_VAR];

	if(isset($from_date))
	{
		$_SESSION['from_date'] = $from_date;
	}
	if(isset($to_date))
	{
		$_SESSION['to_date'] = $to_date;
	}
	if(isset($pis_mode))
	{
		$_SESSION['pis_mode'] = $pis_mode;
	}

	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '')
	{	
		$where.=" AND branch_id='".$_SESSION['branch_name']."'";
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


	##### CODE FOR SEARCHING 


$Query = "select count(id) as CNT from pis_master_sicl_renewal ".$where;
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



$Query = "SELECT * FROM pis_master_sicl_renewal ".$where.$OrderBY.$Limit;

#echo $Query;
#print_r($_POST);

//if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '' && isset($_SESSION['from_date']) && $_SESSION['from_date'] != '' && isset($_SESSION['pis_mode']) && $_SESSION['pis_mode'] != '')
//{
	$objDB->setQuery($Query);
	$rs = $objDB->select();

	$pageRecordCount = count($rs);
//}

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

//$selTransaction = mysql_query("SELECT id, branch_id, customer_id, deposit_date, comitted_amount, amount, tenure, transaction_charges, first_name, last_name, phone, email FROM installment_master WHERE is_deleted=0 ORDER BY id DESC");
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
	/*function dochk()
	{
		if(document.addForm.branch_name.value.search(/\S/) == -1)
		{
			alert("Please select Branch Name");
			document.addForm.branch_name.focus();
			return false;
		}		

		if(document.addForm.from_date.value.search(/\S/) == -1)
		{
			alert("Please select Date");
			document.addForm.from_date.focus();
			return false;
		}
	}*/

//-->
</script>

<form name="addForm" id="addForm" action="" method="post" >
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
        <a title=" Export to Excel " href="<?=URL?>pis_excel_sicl_renewal.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr>
		<?php
			}	
		?>

	<tr> 
      <td colspan="20" align="left">
				<table>
				<tr>
						<td colspan="3" align="center"><strong>Branch PIS (SICL RENEWAL)<br/><br/></strong></td>
						
					</tr>
					<tr> 
						<td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
							* marked fields are mandatory</font></b></td>
					</tr>
					
					<!-- 
					<tr>
						<td width="150"><strong>Receipt Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="receipt_number" id="receipt_number" type="text" class="inplogin" value="<?php echo (isset($_SESSION['receipt_number']) ? $_SESSION['receipt_number'] : ''); ?>"  /> 
						</td>
					</tr> -->
					
					<?php 
						if(($_SESSION[ROLE_ID] == '3') || ($_SESSION[ROLE_ID] == '4'))
						{
					?>
					<input type="hidden" name="branch_name" id="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR];?>">
					<?php
						}
						else
						{
					?>
					<tr>
						<td width="150"><strong>Branch<!-- <font color="#ff0000">*</font> --></strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="branch_name" id="" class="inplogin_select" style="width:140px;">
								<option value="">Select</option>
							<?php 
								$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id=3 OR role_id=4 ORDER BY branch_name ASC");
								while($getBranch = mysql_fetch_array($selBranch))
								{					
							?>
								<option value="<?php echo $getBranch['id'];?>" <?php echo (isset($_SESSION['branch_name']) && ($_SESSION['branch_name']  == $getBranch['id']) ? 'selected' : ''); ?>><?php echo $getBranch['branch_name'];?></option>
							<?php } ?>
							</select>
						</td>
					</tr>
					<?php 
						}			
					?>
					<tr>
						<td width="150"><strong>From Date<!-- <font color="#ff0000">*</font> --></strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="from_date" id="from_date" type="text" class="inplogin" value="<?php echo (isset($_SESSION['from_date']) ? $_SESSION['from_date'] : ''); ?>" maxlength="20" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.from_date,'dd-mm-yyyy',this)" width="20" height="18">&nbsp;&nbsp;<a href="javascript:void(0)" onclick="javascript:document.addForm.from_date.value=''">Clear</a>
						</td>
					</tr>


<tr>
						<td width="150"><strong>To Date<!-- <font color="#ff0000">*</font> --></strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="to_date" id="to_date" type="text" class="inplogin" value="<?php echo (isset($_SESSION['to_date']) ? $_SESSION['to_date'] : ''); ?>" maxlength="20" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.to_date,'dd-mm-yyyy',this)" width="20" height="18">&nbsp;&nbsp;<a href="javascript:void(0)" onclick="javascript:document.addForm.to_date.value=''">Clear</a>
						</td>
					</tr>

					<tr>
						<td width="150"><strong>PIS Mode</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select id="mode" name="pis_mode" class="inplogin_select">
								<option value="" >Select</option>
								<option value="CASH" <?php echo ($_SESSION['pis_mode'] == 'CASH' ? 'selected' : ''); ?>>CASH</option>
								<option value="CHEQUE" <?php echo ($_SESSION['pis_mode'] == 'CHEQUE' ? 'selected' : ''); ?>>CHEQUE</option>	
								<option value="DD" <?php echo ($_SESSION['pis_mode'] == 'DD' ? 'selected' : ''); ?>>DD</option>							
							</select>
						</td>
					</tr>
					
					<tr>
						<td colspan="3" align="center"><input type="submit" name="btnSubmit" value="Search" class="inplogin">
						<?php 
						if(isset($_POST['pis_mode']) && $_POST['pis_mode']!= '' && isset($_POST['from_date']) && $_POST['from_date']!= '')
						{ //echo $pageRecordCount;
						?>
							<input type="button" name="btnPis" value="Generate PIS" class="inplogin" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_pis_sicl_renewal.php?pisdate=<?php echo $_POST['from_date']?>&pismode=<?php echo $_POST['pis_mode']?>')">
						<?php
						}	
						?>
						</td>
						
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
      <td colspan="20">PIS Details</td>
    </tr>    
    <tr> 
			<td width="8%" align="center" valign="top" class="tbllogin">PIS Number</td>
			<td width="8%" align="center" valign="top" class="tbllogin">PIS Date</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">PIS Mode</td>			
			<td width="8%" align="center" valign="top" class="tbllogin">View</td>
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
			$branch_name = find_branch_name($rs[$i]['branch_id']);
?>
	
	<tr> 			
			<td width="8%" align="center" valign="top" ><?php echo str_pad($mysql_id, 7, "0", STR_PAD_LEFT); ?></td>
			<td width="8%" align="center" valign="top" ><?php echo date('d/m/Y', strtotime($rs[$i]['pis_date'])); ?></td>
			<td width="8%" align="center" valign="top" >
				<?php echo find_branch_name($rs[$i]['branch_id']); ?>
			</td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['total']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['pis_mode']; ?></td>			
			<td width="8%" align="center" valign="top" ><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_pis_sicl_renewal_admin.php?id=<?php echo base64_encode($mysql_id); ?>')">View</a></td>
			
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
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=1,width=900,height=600,left = 62,top = 34');");
}
// End -->
</script>

