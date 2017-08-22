<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
//print_r($_POST);

set_time_limit(0); 

if(!isset($_SESSION[ADMIN_SESSION_VAR]) || $_SESSION[ADMIN_SESSION_VAR] == '2')
{
	header("location: index.php");
	exit();
}

extract($_POST);
if(isset($mode) && $mode == 'del') // delete transaction
{
	$premium_array = find_premium_for_this_transaction($transaction_id);
	mysql_query("UPDATE customer_master SET total_premium_given= total_premium_given - '".$premium_array['premium_number']."' WHERE id='".$premium_array['customer_id']."'"); // roll back nimber of premiums
	mysql_query("UPDATE installment_master SET is_deleted=1 WHERE id=".$transaction_id);

	$_SESSION[SUCCESS_MSG] = "Record deleted successfully...";
	header("location: index.php?p=".$_REQUEST['p']."");
	exit();

}

// Write functions here
function find_branch_code($branch_id)
{
	$branch_code = '';
	$selBranchData = mysql_query("SELECT branch_code FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_code = $getBranchData['branch_code'];
	}
	return $branch_code;
}

function find_branch_name($branch_id)
{
	$branch_name = '';
	$selBranchData = mysql_query("SELECT branch_name FROM admin WHERE id='".$branch_id."'");
	$numBranchData = mysql_num_rows($selBranchData);
	if($numBranchData > 0)
	{
		$getBranchData = mysql_fetch_array($selBranchData);
		$branch_name = $getBranchData['branch_name'];
	}
	return $branch_name;
}

function find_premium_number($customer_id)
{
	$premium_number = 0;
	$selPremiumNumber = mysql_query("SELECT total_premium_given FROM customer_master WHERE id='".$customer_id."'");
	$numPremiumNumber = mysql_num_rows($selPremiumNumber);
	if($numPremiumNumber > 0)
	{
		$getPremiumNumber = mysql_fetch_array($selPremiumNumber);
		$premium_number = $getPremiumNumber['total_premium_given'];
	}
	return intval($premium_number);
}

function find_premium_for_this_transaction($transaction_id)
{
	$return_array = array();
	$selPremiumNumber = mysql_query("SELECT amount / comitted_amount as premium, customer_id FROM installment_master WHERE id='".$transaction_id."'");
	$numPremiumNumber = mysql_num_rows($selPremiumNumber);
	if($numPremiumNumber > 0)
	{
		$getPremiumNumber = mysql_fetch_array($selPremiumNumber);
		$return_array['premium_number'] = $getPremiumNumber['premium'];
		$return_array['customer_id'] = $getPremiumNumber['customer_id'];
	}
	return $return_array;
}

function find_id_through_customer_id($customer_id)
{
	$cust_id = '';
	$selCustomerID = mysql_query("SELECT id FROM customer_master WHERE customer_id='".mysql_real_escape_string($customer_id)."'");
	$numCustomerID = mysql_num_rows($selCustomerID);
	if($numCustomerID > 0)
	{
		$getCustomerID = mysql_fetch_array($selCustomerID);
		$cust_id = $getCustomerID['id'];
	}
	return $cust_id;
}

function find_customer_id_through_id($id)
{
	$cust_id = '';
	$selCustomerID = mysql_query("SELECT customer_id FROM customer_master WHERE id='".$id."'");
	$numCustomerID = mysql_num_rows($selCustomerID);
	if($numCustomerID > 0)
	{
		$getCustomerID = mysql_fetch_array($selCustomerID);
		$cust_id = $getCustomerID['customer_id'];
	}
	return $cust_id;
}


$objDB = new DB();

$where = "WHERE is_deleted=0 AND branch_id='".$_SESSION[ADMIN_SESSION_VAR]."'";
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

$Query = "select count(id) as CNT from installment_master  ".$where;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;
include_once("../utility/pagination.php");


$Query = "SELECT id, branch_id, customer_id, deposit_date, comitted_amount, amount, tenure, transaction_charges, first_name, last_name, phone, email FROM installment_master ".$where.$OrderBY.$Limit;
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

<form name="addForm" id="addForm" action="" method="post">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="14">
        <? showMessage(); ?>
      </td>
    </tr>
		<tr> 
      <td colspan="14" align="right">
        <a title=" Export to Excel " href="<?=URL?>transaction_excel_branch.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr>
		<tr> 
      <td colspan="14" align="right">
        <? include_once("../utility/pagination_display.php");?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="14">Transaction Listing</td>
    </tr>
    
    <tr> 
      <td width="8%" align="center" valign="top" class="tbllogin">Customer ID</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Deposit Date</td>			
			<td width="8%" align="center" valign="top" class="tbllogin">First Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Last Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Committed Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Tenure (Months)</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Service Charges</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Phone</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Email</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Receipt</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Duplicate</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Delete</td> -->
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
			$trusted_id = find_customer_id_through_id($rs[$i]['customer_id']);
			$branch_name = find_branch_name($rs[$i]['branch_id']);
?>
	<tr> 
      <td width="8%" align="center" valign="top" ><?php echo $trusted_id; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $branch_name; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo date('d-m-Y', strtotime($rs[$i]['deposit_date'])); ?></td>			
			<td width="8%" align="center" valign="top" ><?php echo ucwords($rs[$i]['first_name']); ?></td>
			<td width="8%" align="center" valign="top" ><?php echo ucwords($rs[$i]['last_name']); ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['comitted_amount']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['tenure']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['amount']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['transaction_charges']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['phone']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['email']; ?></td> 
			<td width="8%" align="center" valign="top" ><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window.php?id=<?php echo base64_encode($mysql_id); ?>')">Generate</a></td>
			<!-- <td width="8%" align="center" valign="top" ><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_duplicate.php?id=<?php echo base64_encode($mysql_id); ?>')">Generate Duplicate</a></td> -->
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

