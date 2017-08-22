<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
//print_r($_POST);

ini_set('display_errors', 1);
set_time_limit(0); 

$pageOwner = "'branch'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); // $USER_TYPE is coming from index.php

#print_r($_POST);
extract($_POST);

// Write functions here


function find_premium_for_this_transaction($transaction_id)
{
	$return_array = array();
	#echo "<br /> SELECT amount / comitted_amount as premium, customer_id FROM installment_master WHERE id='".$transaction_id."'";
	$selPremiumNumber = mysql_query("SELECT installment as premium, customer_id FROM installment_master WHERE id='".$transaction_id."'");
	$numPremiumNumber = mysql_num_rows($selPremiumNumber);
	if($numPremiumNumber > 0)
	{
		$getPremiumNumber = mysql_fetch_array($selPremiumNumber);
		$return_array['premium_number'] = $getPremiumNumber['premium'];
		$return_array['customer_id'] = $getPremiumNumber['customer_id'];
	}
	return $return_array;
}




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

	if(isset($from_date))
	{
		$_SESSION['from_date'] = $from_date;
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

	//if(isset($_SESSION['branch_name_string']) && $_SESSION['branch_name_string'] != '') // this is actually branch id
	//{
		//$where.= ' AND branch_id IN ('.$_SESSION['branch_name_string'].')';
	//}

	if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
	{
		$where.= ' AND deposit_date >="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
	}

	if(isset($_SESSION['to_date']) && $_SESSION['to_date'] != '') 
	{
		$where.= ' AND deposit_date <="'.date('Y-m-d', strtotime($_SESSION['to_date'])).'"';
	}

	if(isset($_SESSION['receipt_number']) && $_SESSION['receipt_number'] != '') 
	{
		$where.= ' AND receipt_number LIKE "%'.$_SESSION['receipt_number'].'%"';
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

	##### CODE FOR SEARCHING 

$Query = "select count(id) as CNT from installment_master  ".$where;
#echo $Query;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;
include_once("../utility/pagination.php");

$Query = "SELECT id, branch_id, customer_id, deposit_date, amount,  transaction_charges, payment_mode, payment_type, application_no, receipt_number, folio_no_id, receipt_generated FROM installment_master ".$where.$OrderBY.$Limit;

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

<form name="addForm" id="addForm" action="" method="post" style="border:0px solid red">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="22">
        <? showMessage(); ?>
      </td>
    </tr>
		<!-- <tr> 
      <td colspan="22" align="right">
        <a title=" Export to Excel " href="<?=URL?>transaction_excel_branch.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr> -->

	<tr> 
      <td colspan="22" align="left">
				<table>
					<!-- <tr>
						<td width="150"><strong>Branch</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="branch_name" id="" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
							<?php 
								$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2) ORDER BY branch_name ASC");
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
						<td width="150"><strong>Application Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="application_no" id="application_no" type="text" class="inplogin" value="<?php echo (isset($_SESSION['application_no']) ? $_SESSION['application_no'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Folio Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="folio_no" id="folio_no" type="text" class="inplogin" value="<?php echo (isset($_SESSION['folio_no']) ? $_SESSION['folio_no'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Receipt Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="receipt_number" id="receipt_number" type="text" class="inplogin" value="<?php echo (isset($_SESSION['receipt_number']) ? $_SESSION['receipt_number'] : ''); ?>" maxlength="50" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Customer ID</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="customer_id" id="customer_id" type="text" class="inplogin" value="<?php echo (isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>First Name</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="first_name" id="first_name" type="text" class="inplogin" value="<?php echo (isset($_SESSION['first_name']) ? $_SESSION['first_name'] : ''); ?>" maxlength="255" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Last Name</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="last_name" id="last_name" type="text" class="inplogin" value="<?php echo (isset($_SESSION['last_name']) ? $_SESSION['last_name'] : ''); ?>" maxlength="255" /> 
						</td>
					</tr>

					<tr>
						<td colspan="3" align="center"><input type="submit" name="btnSubmit" value="Search">&nbsp;<?php //if(isset($_POST) && (count($_POST)) > 0){ ?><!-- <a href="xml_download.php" style="text-decoration:none;" ><input type="button" name="btnXML" value="Download XML"></a> --><?php //} ?></td>
						
					</tr>
				</table>
        
      </td>
    </tr>

		<tr> 
      <td colspan="22" align="right">
        <? include_once("../utility/pagination_display.php");?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="22">Transaction Listing</td>
    </tr>
    
    <tr> 
      <td width="8%" align="center" valign="top" class="tbllogin">Customer ID</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Application Number</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Folio Number</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Receipt Number</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Deposit Date</td>			
			<td width="8%" align="center" valign="top" class="tbllogin">First Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Middle Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Last Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Nominee Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Committed Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Tenure (Months)</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Tax</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Payment Type</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Edit</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Employee Code</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Phone</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Email</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Subsequent Payment</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Receipt</td>
			<td width="8%" align="center" valign="top" class="tbllogin">New Plan With Existing Customers</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Redemption</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Upload Scanned Image</td> -->
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
			#echo '<br />'.$rs[$i]['customer_id']; // this is autoincrement id of the customer_master table

			$selMasterRecord = mysql_query("SELECT customer_id, first_name, middle_name, last_name, phone, email FROM customer_master WHERE id='".$rs[$i]['customer_id']."'");

			if(mysql_num_rows($selMasterRecord) > 0)
			{	
				$getMasterRecord = mysql_fetch_array($selMasterRecord);
			}

			$selFolioRecord = mysql_query("SELECT folio_no, committed_amount, tenure, nominee_name, redemption_date, applied_for_redemption FROM customer_folio_no WHERE id = '".$rs[$i]['folio_no_id']."'");

			if(mysql_num_rows($selFolioRecord) > 0)
			{
				$getFolioRecord = mysql_fetch_array($selFolioRecord);
			}

			$branch_name = find_branch_name($rs[$i]['branch_id']);
			$payment_mode_service = $rs[$i]['payment_mode'];

			$kyc_ok = kyc_ok($rs[$i]['customer_id']);
?>
	<tr> 
      <td width="8%" align="center" valign="top" ><?php echo $getMasterRecord['customer_id']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['application_no']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $getFolioRecord['folio_no']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['receipt_number']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $branch_name; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo date('d-m-Y', strtotime($rs[$i]['deposit_date'])); ?></td>			
			<td width="8%" align="center" valign="top" ><?php echo $getMasterRecord['first_name']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $getMasterRecord['middle_name']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $getMasterRecord['last_name']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $getFolioRecord['nominee_name']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $getFolioRecord['committed_amount']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $getFolioRecord['tenure']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['amount']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['transaction_charges']; ?>
				<!-- <br /><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_service_charge_edit.php?id=<?php echo base64_encode($mysql_id); ?>')">Edit</a> -->
			</td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['payment_type']; ?></td>
			<td width="8%" align="center" valign="top" >
			<?php if(strtoupper($rs[$i]['payment_type']) != 'SUBSEQUENT PAYMENT') { if(intval($getFolioRecord['applied_for_redemption']) == 0){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_edit_branch.php?id=<?php echo base64_encode($mysql_id); ?>')">Edit<?php }} ?></td>
			
			<!-- <td width="8%" align="center" valign="top" ><?php echo $employee_code; ?></td> -->
			<td width="8%" align="center" valign="top" ><?php echo $getMasterRecord['phone']; ?></td>
			<!-- <td width="8%" align="center" valign="top" ><?php echo $getMasterRecord['email']; ?></td>  -->
			<td width="8%" align="center" valign="top" >
			<?php //if(intval($kyc_ok) == 1) { 
				if(intval($getFolioRecord['applied_for_redemption']) == 0){	
			?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/subsequent_payment.php?id=<?php echo base64_encode($rs[$i]['folio_no_id']); ?>')">Pay Now
			<?php }
				//} ?></td>	
			<td width="8%" align="center" valign="top" >			
				<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window.php?id=<?php echo base64_encode($mysql_id); ?>')">Generate</a>
			<?php
				if(intval($rs[$i]['receipt_generated']) == 1)	{ echo '<br />Generated'; }
			?>
			</td>
			<?php
					
			?>
			<td width="8%" align="center" valign="top" >
			<?php //if(intval($kyc_ok) == 1) { ?>
			<input type="button" value="CREATE" style="cursor:pointer" class="inplogin" onclick="javascript:popUp('<?php echo URL; ?>webadmin/new_plan.php?id=<?php echo base64_encode($rs[$i]['customer_id']); ?>')" />
			<?php //} ?>
			</td>
			<!-- <td width="8%" align="center" valign="top" ><?php if($getFolioRecord['redemption_date'] != '0000-00-00'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_redemption.php?id=<?php echo base64_encode($rs[$i]['folio_no_id']); ?>')">View<br /></a>
			Applied
			<?php } else {  ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_redemption.php?id=<?php echo base64_encode($rs[$i]['folio_no_id']); ?>')">Apply<br /></a>
			<?php } ?>
			</td> -->
			<!-- <td width="8%" align="center" valign="top" ><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_upload.php?id=<?php echo base64_encode($rs[$i]['folio_no_id']); ?>')">Upload</td> -->
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

