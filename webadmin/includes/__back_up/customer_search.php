<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
//print_r($_POST);

set_time_limit(0); 

$pageOwner = "'admin','superadmin','hub','branch'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

//print_r($_POST);
extract($_POST);

/*if(isset($mode) && $mode == 'del') // delete transaction
{
	$premium_array = find_premium_for_this_transaction($transaction_id);
	//print_r($premium_array);
	mysql_query("UPDATE customer_master SET total_premium_given= total_premium_given - '".$premium_array['premium_number']."' WHERE id='".$premium_array['customer_id']."'"); // roll back nimber of premiums
	
	mysql_query("UPDATE installment_master SET is_deleted=1 WHERE id=".$transaction_id);
	//echo "UPDATE installment_master SET is_deleted=1 WHERE id=".$transaction_id;

	$_SESSION[SUCCESS_MSG] = "Record deleted successfully...";
	header("location: index.php?p=".$_REQUEST['p']."");
	exit();

}*/

$objDB = new DB();

$where = "WHERE is_deleted=0 ";
$OrderBY = " ORDER BY id ASC ";


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

	if(isset($search_field))
	{				
		$_SESSION['search_field'] = $search_field;
	}
	if(isset($search_field_value))
	{
		if($search_field == 'customer_id')
		{
			$_SESSION['search_field_value'] = find_id_through_customer_id(realTrim($search_field_value));
		}
		else
		{
			$_SESSION['search_field_value'] = realTrim($search_field_value);
		}
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
	if(isset($first_name))
	{
		$_SESSION['first_name'] = realTrim($first_name);
	}
	if(isset($last_name))
	{
		$_SESSION['last_name'] = realTrim($last_name);
	}
	
	#echo $_SESSION['search_field'].'<br />'.$_SESSION['search_field_value'];
	
	if(isset($_SESSION['search_field']) && $_SESSION['search_field'] != '' && isset($_SESSION['search_field_value']) && $_SESSION['search_field_value'] != '') 
	{
		$where.= ' AND '.$_SESSION['search_field'].'="'.$_SESSION['search_field_value'].'"';
	}
	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '') // this is actually branch id
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name'].'"';
	}
	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '') // this is actually branch id
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name'].'"';
	}
	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '') // this is actually branch id
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name'].'"';
	}
	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '') // this is actually branch id
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name'].'"';
	}
	if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
	{
		$where.= ' AND deposit_date >="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
	}
	if(isset($_SESSION['to_date']) && $_SESSION['to_date'] != '') 
	{
		$where.= ' AND deposit_date <="'.date('Y-m-d', strtotime($_SESSION['to_date'])).'"';
	}
	if(isset($_SESSION['first_name']) && $_SESSION['first_name'] != '') 
	{
		$where.= ' AND first_name LIKE "%'.$_SESSION['first_name'].'%"';
	}
	if(isset($_SESSION['last_name']) && $_SESSION['last_name'] != '') 
	{
		$where.= ' AND last_name LIKE "%'.$_SESSION['last_name'].'%"';
	}


	##### CODE FOR SEARCHING 

$Query = "select count(id) as CNT from installment_master  ".$where;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;
#include_once("../utility/pagination.php");


$Query = "SELECT id, application_no, branch_id, customer_id, deposit_date, comitted_amount, amount, tenure, transaction_charges, first_name, last_name, phone, email, employee_code, payment_mode_service, receipt_number, payment_type FROM installment_master ".$where.$OrderBY;
//echo $Query;
if(isset($search_field) && $search_field != '' && isset($search_field_value) && $search_field_value != '')
{
	$objDB->setQuery($Query);
	$rs = $objDB->select();

	$pageRecordCount = count($rs);
}

//==========================================================

/*$_SESSION['LIST_PAGE'][$GLOBALS['p']] = array(
'pg' 		=> $currentPage,
'search' 	=> $searchString,
'st' 		=> $sortType,
'sf' 		=> $sortField,
'dpp' 		=> $dataPerPage,
'mid' 		=> $mid,
'pid' 		=> $pid
);*/

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
	
	function dppList(dpp)
	{
		//alert(dpp1);
		document.sortFrm.dpp.value = dpp;
		document.sortFrm.pg.value= 1;
		document.sortFrm.submit();
		return true;
	}

	function dochk()
	{
		if(document.addForm.search_field.value.search(/\S/) == -1)
		{
			alert("Please select Search Field");
			document.addForm.search_field.focus();
			return false;
		}

		if(document.addForm.search_field_value.value.search(/\S/) == -1)
		{
			alert("Please enter Search Field Value");
			document.addForm.search_field_value.focus();
			return false;
		}
		
	}
//-->
</script>

<form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="14">
        <? showMessage(); ?>
      </td>
    </tr>
		<!-- <tr> 
      <td colspan="14" align="right">
        <a title=" Export to Excel " href="<?=URL?>transaction_excel.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr>
 -->
		<tr> 
      <td colspan="14" style="padding-left: 0px;" align="left"><b><font color="#ff0000">All 
        * marked fields are mandatory</font></b></td>
    </tr>
		<tr> 
      <td colspan="14" align="left">
				<table>
					<tr>
						<td width="150"><strong>Search field</strong><font color="#ff0000"> *</font></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="search_field" id="search_field" class="inplogin_select" style="width:300px;">
								<option value="" >Select Search Field</option>
								<option value="application_no" <?php echo (isset($search_field) && $search_field == 'application_no' ? 'selected' : ''); ?>>Application Number</option>
								<option value="customer_id" <?php echo (isset($search_field) && $search_field == 'customer_id' ? 'selected' : ''); ?>>Customer ID</option>
								<option value="receipt_number" <?php echo (isset($search_field) && $search_field == 'receipt_number' ? 'selected' : ''); ?>>Receipt Number</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Search Field Value</strong><font color="#ff0000"> *</font></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="search_field_value" id="search_field_value" type="text" class="inplogin" value="<?php echo (isset($search_field_value) ? $search_field_value : ''); ?>" style="width:300px;" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Branch</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="branch_name" id="" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
							<?php 
								$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE id!=2 ORDER BY branch_name ASC");
								while($getBranch = mysql_fetch_array($selBranch))
								{					
							?>
								<option value="<?php echo $getBranch['id'];?>" <?php echo (isset($_SESSION['branch_name']) && ($_SESSION['branch_name']  == $getBranch['id']) ? 'selected' : ''); ?>><?php echo $getBranch['branch_name'];?></option>
							<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150"><strong>From Date</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="from_date" id="from_date" type="text" class="inplogin" value="<?php echo (isset($_SESSION['from_date']) ? $_SESSION['from_date'] : ''); ?>" maxlength="20" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.from_date,'dd-mm-yyyy',this)" width="20" height="18"><br /><a href="javascript:void(0)" onclick="javascript:document.addForm.from_date.value=''">Clear</a>
						</td>
					</tr>
					<tr>
						<td width="150"><strong>To Date</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="to_date" id="to_date" type="text" class="inplogin" value="<?php echo (isset($_SESSION['to_date']) ? $_SESSION['to_date'] : ''); ?>" maxlength="20" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.to_date,'dd-mm-yyyy',this)" width="20" height="18"><br /><a href="javascript:void(0)" onclick="javascript:document.addForm.to_date.value=''">Clear</a>
						</td>
					</tr>
					<tr>
						<td width="150"><strong>First Name</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="first_name" id="first_name" type="text" class="inplogin" value="<?php echo (isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''); ?>" maxlength="20" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Last Name</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="last_name" id="last_name" type="text" class="inplogin" value="<?php echo (isset($_SESSION['last_name']) ? $_SESSION['last_name'] : ''); ?>" maxlength="20" /> 
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center"><input type="submit" name="btnSubmit" value="Search"></td>
						
					</tr>
				</table>
        
      </td>
    </tr>

		<?php 
			if(isset($search_field) && $search_field != '' && isset($search_field_value) && $search_field_value != '')
			{
		?>
		<tr> 
      <td colspan="14" align="right">
        <? #include_once("../utility/pagination_display.php");?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="14">Transaction Listing</td>
    </tr>
    
    <tr> 
      <td width="8%" align="center" valign="top" class="tbllogin">Customer ID</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Receipt No.</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Deposit Date</td>			
			<td width="8%" align="center" valign="top" class="tbllogin">First Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Last Name</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Committed Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Tenure (Months)</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Payment Type</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Service Charges</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Employee Code</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Phone</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Email</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Edit</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Receipt</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Delete</td> -->

    </tr>
<?php
		if(isset($pageRecordCount) && $pageRecordCount > 0)
		{
		 $employee_code = '';
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
			$employee_code = $rs[$i]['employee_code'];
			$payment_mode_service = $rs[$i]['payment_mode_service'];

			$dis_application_no = $rs[$i]['application_no'];
			$dis_tenure = $rs[$i]['tenure'];
			$dis_comitted_amount = $rs[$i]['comitted_amount'];
			if($rs[$i]['employee_code'] != '' ) { $employee_code = $rs[$i]['employee_code']; }

?>
	<tr> 
      <td width="8%" align="center" valign="top" ><?php echo $trusted_id; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo ucwords($rs[$i]['receipt_number']); ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $branch_name; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo date('d-m-Y', strtotime($rs[$i]['deposit_date'])); ?></td>			
			<td width="8%" align="center" valign="top" ><?php echo ucwords($rs[$i]['first_name']); ?></td>
			<td width="8%" align="center" valign="top" ><?php echo ucwords($rs[$i]['last_name']); ?></td>
			<!-- <td width="8%" align="center" valign="top" ><?php echo $rs[$i]['comitted_amount']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['tenure']; ?></td> -->
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['amount']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['payment_type']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['transaction_charges']; ?>
				<!-- <br /><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_service_charge_edit.php?id=<?php echo base64_encode($mysql_id); ?>')">Edit</a> -->
			</td>
			<!-- <td width="8%" align="center" valign="top" ><?php echo $employee_code; ?></td> -->
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['phone']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['email']; ?></td> 
			<!-- <td width="8%" align="center" valign="top" ><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_edit.php?id=<?php echo base64_encode($mysql_id); ?>')">Edit</a></td> -->
			<td width="8%" align="center" valign="top" >
			<?php if($employee_code == ''){ 
							if($payment_mode_service != ''){	
			?>
				<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window.php?id=<?php echo base64_encode($mysql_id); ?>')">Generate</a>
	<?php				}
						} else {
						echo $employee_code;
						}
	?>	
			</td>
			<!-- <td width="8%" align="center" valign="top" ><a title="Delete" href="#" onclick="javascript:confirmDelete(<?=$mysql_id; ?>)"><img src="images/delete_icon.gif" border="0" /></a></td> -->
    </tr>

		

<?php		
		}
?>
		<tr class="TDHEAD"> 
      <td colspan="14">Summary</td>
    </tr>

<!-- 
Committed amount
Tenure
Total premium given
Total Service charge
Service Charge Given
Service Charge Due
-->

<tr > 
	<td colspan="14">
		<div>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td width="200" align="right" height="30"><strong>Application Number</strong></td>
					<td width="20" align="center" height="30"><strong>:</strong></td>
					<td width="200" align="left" height="30"><?= $dis_application_no; ?></td>
				</tr>
				<tr>
					<td width="200" align="right" height="30"><strong>Customer ID</strong></td>
					<td width="20" align="center" height="30"><strong>:</strong></td>
					<td width="200" align="left" height="30"><?= $trusted_id; ?></td>
				</tr>
				<tr>
					<td width="200" align="right" height="30"><strong>Comitted Amount</strong></td>
					<td width="20" align="center" height="30"><strong>:</strong></td>
					<td width="200" align="left" height="30"><?= $dis_comitted_amount; ?></td>
				</tr>
				<tr>
					<td width="200" align="right" height="30"><strong>Tenure</strong></td>
					<td width="20" align="center" height="30"><strong>:</strong></td>
					<td width="200" align="left" height="30"><?= $dis_tenure; ?></td>
				</tr>
				<?php
					$dis_total_service_charge = calculateServiceCharge($dis_comitted_amount, $dis_tenure);
				?>
				<tr>
					<td width="200" align="right" height="30"><strong>Total Service Charge</strong></td>
					<td width="20" align="center" height="30"><strong>:</strong></td>
					<td width="200" align="left" height="30"><?php echo ($employee_code == '' ? number_format($dis_total_service_charge, 2, '.', '') : $employee_code); ?></td>
				</tr>
				<?php
					$dis_service_charge_given = totalServiceChargeGiven($dis_application_no);
				?>
				<tr>
					<td width="200" align="right" height="30"><strong>Service Charge Given</strong></td>
					<td width="20" align="center" height="30"><strong>:</strong></td>
					<td width="200" align="left" height="30"><?php echo ($employee_code == '' ?  number_format($dis_service_charge_given, 2,'.', '') : $employee_code); ?></td>
				</tr>
				<?php
					$dis_service_charge_due = $dis_total_service_charge - $dis_service_charge_given;	
				?>
				<tr>
					<td width="200" align="right" height="30"><strong>Service Charge Due</strong></td>
					<td width="20" align="center" height="30"><strong>:</strong></td>
					<td width="200" align="left" height="30"><?php echo ($employee_code == '' ? number_format($dis_service_charge_due, 2, '.', '') : $employee_code); ?></td>
				</tr>
			</table>
		</div>
	</td>
</tr>
<?php
	}
	else
	{
		?>

			<tr> 
				 <td colspan="14" style="color:#ff0000; text-align:center;">No Record found</td>
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
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=1,width=800,height=600,left = 112,top = 84');");
}
// End -->
</script>

<?php //$objDB->close(); ?>

