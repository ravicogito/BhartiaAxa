<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
//print_r($_POST);

set_time_limit(0); 

$pageOwner = "'superadmin','admin','subadmin'";
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

	if(isset($hub_name))
	{
		$_SESSION['hub_name'] = realTrim($hub_name);
	}

	if(isset($branch_name))
	{
		$_SESSION['branch_name'] = realTrim($branch_name);
	}
	if(isset($from_date))
	{
		$_SESSION['from_date'] = $from_date;
	}

	if(isset($to_date))
	{
		$_SESSION['to_date'] = $to_date;
	}


	if(isset($pis_generated))
	{
		$_SESSION['pis_generated'] = $pis_generated;
	}

	if(isset($receipt_number))
	{
		$_SESSION['receipt_number'] = realTrim($receipt_number);
	}

	if(isset($application_no))
	{
		$_SESSION['application_no'] = realTrim($application_no);
	}

	if(isset($receipt_serial_no))
	{
		$_SESSION['receipt_serial_no'] = realTrim($receipt_serial_no);
	}

	if(isset($dd_number))
	{
		$_SESSION['dd_number'] = realTrim($dd_number);
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

	if(isset($_SESSION['hub_name']) && $_SESSION['hub_name'] != '') 
	{
		$where.= ' AND  hub_id ="'.$_SESSION['hub_name'].'"';

		
		
		
		//$branch_usr_str = find_branch_from_hub($_SESSION['hub_name']);
		//$branch_usr_str_with_admin = $branch_usr_str.','.$_SESSION['hub_name'];
		//$where.= " AND branch_id IN (".trim($branch_usr_str_with_admin,',').")";
	
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

	if(isset($_SESSION['receipt_number']) && $_SESSION['receipt_number'] != '') 
	{
		$where.= ' AND receipt_number LIKE "%'.$_SESSION['receipt_number'].'%"';
	}

	if(isset($_SESSION['application_no']) && $_SESSION['application_no'] != '') 
	{
		$where.= ' AND application_no LIKE "%'.$_SESSION['application_no'].'%"';
	}
	
	if(isset($_SESSION['receipt_serial_no']) && $_SESSION['receipt_serial_no'] != '') 
	{
		$where.= ' AND serial_no = "'.$_SESSION['receipt_serial_no'].'"';
	}

	if(isset($_SESSION['dd_number']) && $_SESSION['dd_number'] != '') 
	{
		$where.= ' AND dd_number = "'.$_SESSION['dd_number'].'"';
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

	if(isset($_SESSION['pis_generated']) && ($_SESSION['pis_generated'] != ''))
	{
		if($_SESSION['pis_generated'] == '0')
		{
		$where.= ' AND pis_id =0';
		}
		else if($_SESSION['pis_generated'] == '1')
		{
		$where.= ' AND pis_id !=0';
		}
		else
		{
		$where.= '';
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

$Query = "SELECT * FROM installment_master ".$where.$OrderBY.$Limit;

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
<script type="text/javascript">
function admincheck(id)
{
//alert(id);
//return false;




var datastring = 'id='+id;
var opn = 'option'+id;	
document.getElementById(opn).disabled=true;
//alert(opn);
//return false;
	$.ajax({
             type: "POST",
             url: "admincheckajax.php",
             data:  datastring,
			 dataType: 'json',
			 cache: false,
             success: function(data){
			 
				//alert("Hi");
				//return fa
				 
				
				//$('input[id="opn"]').disabled=true;
				  
				  
				  /*document.getElementById("dd_branch_name").disabled=true;*/
				 /*document.getElementById("ifs_code").disabled=true;*/
				 
              }
          });
	
}
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
        <a title=" Export to Excel " href="<?=URL?>transaction_excel.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr>

	<tr> 
      <td colspan="25" align="left">
				<table>
					<!--<tr>
						<td width="150"><strong>Branch</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<select name="branch_name" id="" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
							<?php 
								$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,3,5,6)  AND branch_user_id=0 ORDER BY branch_name ASC");
								while($getBranch = mysql_fetch_array($selBranch))
								{					
							?>
								<option value="<?php echo $getBranch['id'];?>" <?php echo (isset($_SESSION['branch_name']) && ($_SESSION['branch_name']  == $getBranch['id']) ? 'selected' : ''); ?>><?php echo $getBranch['branch_name'];?></option>
							<?php } ?>
							</select>
						</td>
					</tr>-->



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
								$branch_sql = 'select * from admin where  role_id = 4';
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
						<td width="150"><strong>Receipt Serial Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="receipt_serial_no" id="receipt_serial_no" type="text" class="inplogin" value="<?php echo (isset($_SESSION['receipt_serial_no']) ? $_SESSION['receipt_serial_no'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr>


					<tr>
						<td width="150"><strong>Cheque No.</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="dd_number" id="dd_number" type="text" class="inplogin" value="<?php echo (isset($_SESSION['dd_number']) ? $_SESSION['dd_number'] : ''); ?>" maxlength="100" /> 
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


					<!-- <tr>
						<td width="150"><strong>Folio Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="folio_no" id="folio_no" type="text" class="inplogin" value="<?php echo (isset($_SESSION['folio_no']) ? $_SESSION['folio_no'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr> -->
					<!-- <tr>
						<td width="150"><strong>Receipt Number</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="receipt_number" id="receipt_number" type="text" class="inplogin" value="<?php echo (isset($_SESSION['receipt_number']) ? $_SESSION['receipt_number'] : ''); ?>" maxlength="50" /> 
						</td>
					</tr> -->
					<!-- <tr>
						<td width="150"><strong>Customer ID</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="customer_id" id="customer_id" type="text" class="inplogin" value="<?php echo (isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr> -->
					<!-- <tr>
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
					</tr> -->

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
    
    <tr> 
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Scaned</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Send To INS.Co.</td>
			<td width="8%" align="center" valign="top" class="tbllogin">PIS Generated</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Customer ID</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Application Number</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Folio Number</td> -->
			 <td width="8%" align="center" valign="top" class="tbllogin">Receipt Number</td> 
			 <td width="8%" align="center" valign="top" class="tbllogin">Receipt Serial Number</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Branch Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Plan</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Deposit Date</td>			
			<td width="16%" align="center" valign="top" class="tbllogin">Name</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Middle Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Last Name</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Nominee Name</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Premium Amount</td>
			
			<td width="8%" align="center" valign="top" class="tbllogin">Service Tax</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Total Amount</td>

			<td width="8%" align="center" valign="top" class="tbllogin">Term (Years)</td>
			
			<td width="8%" align="center" valign="top" class="tbllogin">Main P. Mode</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Main P. Amount</td>

			<td width="8%" align="center" valign="top" class="tbllogin">Agent Name</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Agent Code</td>
			
			<td width="8%" align="center" valign="top" class="tbllogin">Cheque/DD No.</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Cheque/DD Date</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Bank Name</td>

			<td width="8%" align="center" valign="top" class="tbllogin">Alt. P. Mode</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Alt. P. Amount</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Sum Assured</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Tax</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Payment Type</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Total Installments Given</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin"><?php if($_SESSION[ROLE_ID] == '1'){?>Edit<?php }?></td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Employee Code</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Phone</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Email</td> -->
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">Subsequent Payment</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Receipt</td>
			<!-- <td width="8%" align="center" valign="top" class="tbllogin">New Plan With Existing Customers</td> -->
			<td width="8%" align="center" valign="top" class="tbllogin">Cancel</td>
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
			#echo '<br />'.$rs[$i]['customer_id']; // this is autoincrement id of the customer_master table

			//$selMasterRecord = mysql_query("SELECT customer_id, first_name, middle_name, last_name, phone, email FROM customer_master WHERE id='".$rs[$i]['customer_id']."'");

			//if(mysql_num_rows($selMasterRecord) > 0)
			//{	
				//$getMasterRecord = mysql_fetch_array($selMasterRecord);
			//}

			//$selFolioRecord = mysql_query("SELECT folio_no, committed_amount, tenure, nominee_name, redemption_date, applied_for_redemption,total_premium_given FROM customer_folio_no WHERE id = '".$rs[$i]['folio_no_id']."'");

			//if(mysql_num_rows($selFolioRecord) > 0)
			//{
				//$getFolioRecord = mysql_fetch_array($selFolioRecord);
			//}

			$branch_name = find_branch_name($rs[$i]['branch_id']);
			$payment_mode_service = $rs[$i]['payment_mode'];

			$plan = find_product_name($rs[$i]['plan']);

			//$kyc_ok = kyc_ok($rs[$i]['customer_id']);
?>
	<tr> 
      
	  
		<td width="8%" align="center" valign="top" ><?php echo (intval($rs[$i]['branch_scan']) != 0 ? 'Yes' : 'No'); ?></td>
		
	   <td width="8%" align="center" valign="top" ><?php echo (intval($rs[$i]['hub_despatched']) != 0 ? 'Yes' : 'No'); ?></td>

	  <td width="8%" align="center" valign="top" ><?php echo (intval($rs[$i]['pis_id']) != 0 ? 'Yes' : 'No'); ?></td>
			<!-- <td width="8%" align="center" valign="top" ><?php echo $getMasterRecord['customer_id']; ?></td> -->
			<td width="8%" align="center" valign="top" >
			<?php echo $rs[$i]['application_no']; ?><br />
			<?php if($_SESSION[ROLE_ID] == '1'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_app_no_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php } ?>			
			</td>


			<td width="8%" align="center" valign="top" >
			<?php echo $rs[$i]['transaction_id']; ?>
			</td>


			<td width="8%" align="center" valign="top" >
			<?php echo $rs[$i]['serial_no']; ?><br />
			<?php if($_SESSION[ROLE_ID] == '1'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_serial_no_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php } ?>			
			</td>


			<!-- <td width="8%" align="center" valign="top" ><?php echo $getFolioRecord['folio_no']; ?></td> -->
			<!-- <td width="8%" align="center" valign="top" >
				<?php echo $rs[$i]['receipt_number'];
						if(intval($rs[$i]['migrated_from_dmspl']) == 0){ ?><br /><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_change_receipt_no.php?id=<?php echo base64_encode($mysql_id); ?>')">Change<?php } ?>	
			</td> -->
			<td width="8%" align="center" valign="top" ><?php echo $branch_name; ?>
			
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_branch_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php } ?>		

			</td>
			<td width="8%" align="center" valign="top" ><?php echo $plan; ?>
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_plan_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php } ?></td>

			<td width="8%" align="center" valign="top" ><?php echo date('d-m-Y', strtotime($rs[$i]['deposit_date'])); ?></td>			
			<td width="16%" align="center" valign="top" ><?php echo $rs[$i]['first_name']; ?>
			
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_app_name_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php } ?>			
			</td>
			<!-- <td width="8%" align="center" valign="top" ><?php echo $rs[$i]['middle_name']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['last_name']; ?></td> -->
			<!-- <td width="8%" align="center" valign="top" ><?php echo $getFolioRecord['nominee_name']; ?></td> -->
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['amount']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['service_tax']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['total_value']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['tenure']; ?>
			
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_term_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php } ?>
			</td>
			

			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['payment_mode']; ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['main_amount']; ?></td>


			
			

			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['agent_name']; ?>
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){if($rs[$i]['agent_name'] != ""){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_agent_name_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php }} ?>
			</td>
			
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['agent_code']; ?>
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){if($rs[$i]['agent_code'] != ""){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_agent_code_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php }} ?>
			</td>


			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['dd_number']; ?>
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){if($rs[$i]['dd_number'] != ""){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_dd_number_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php }} ?>



			</td>
			<td width="8%" align="center" valign="top" ><?php if(($rs[$i]['dd_date'] != '0000-00-00')&&($rs[$i]['dd_date'] != '1970-01-01')){echo date('d-m-Y', strtotime($rs[$i]['dd_date'])); }?>
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){if($rs[$i]['dd_number'] != ""){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_dd_date_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php }} ?>
			</td>


			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['dd_bank_name']; ?>
			
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){if($rs[$i]['dd_number'] != ""){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_dd_bank_name_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php }} ?></td>



			<td width="8%" align="center" valign="top" ><?php if($rs[$i]['other_payment_mode'] != "NULL"){echo $rs[$i]['other_payment_mode'];} ?></td>
			<td width="8%" align="center" valign="top" ><?php if(substr($rs[$i]['other_amount'],0,1) != '-'){ echo $rs[$i]['other_amount'];} ?></td>
			<td width="8%" align="center" valign="top" ><?php echo $rs[$i]['sum_assured']; ?>
			<br />
			<?php if($_SESSION[ROLE_ID] == '1'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_sum_assured_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Edit<?php } ?></td>
			<!-- <td width="8%" align="center" valign="top" ><?php echo $rs[$i]['transaction_charges']; ?>
				
			</td> -->
			<!-- <td width="8%" align="center" valign="top" ><?php echo $rs[$i]['payment_type']; ?></td> -->
			<!-- <td width="8%" align="center" valign="top" >
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/update_installment.php?id=<?php echo base64_encode($rs[$i]['folio_no_id']); ?>')">Change</a>
			</td> -->
			<td width="8%" align="center" valign="top" > 
			<?php if($_SESSION[ROLE_ID] == '1'){?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_edit.php?id=<?php echo base64_encode($mysql_id); ?>')">Edit
			<?php }?>
			</td>
			
			<!-- <td width="8%" align="center" valign="top" ><?php echo $employee_code; ?></td> -->
			<!-- <td width="8%" align="center" valign="top" ><?php echo $getMasterRecord['phone']; ?></td> -->
			<!-- <td width="8%" align="center" valign="top" ><?php echo $getMasterRecord['email']; ?></td>  -->
			<!-- <td width="8%" align="center" valign="top" >
			<?php //if(intval($kyc_ok) == 1) { 
				if(intval($getFolioRecord['applied_for_redemption']) == 0){	
			?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/subsequent_payment.php?id=<?php echo base64_encode($rs[$i]['folio_no_id']); ?>')">Pay Now</a>
			<?php } 
			//} ?></td>	 -->
			<td width="8%" align="center" valign="top" >			
				<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window.php?id=<?php echo base64_encode($mysql_id); ?>')">Generate</a>		
			</td>
			<?php
					
			?>
			<!-- <td width="8%" align="center" valign="top" >
			<?php //if(intval($kyc_ok) == 1) { ?>
			<input type="button" value="CREATE" style="cursor:pointer" class="inplogin" onclick="javascript:popUp('<?php echo URL; ?>webadmin/new_plan.php?id=<?php echo base64_encode($rs[$i]['customer_id']); ?>')" />
			<?php //} ?>
			</td> -->
			<td width="8%" align="center" valign="top" >
			
			<?php if($_SESSION[ROLE_ID] == '1'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_cancel.php?id=<?php echo base64_encode($mysql_id); ?>')">Cancel</a>
			<?php } ?>
			</td>

			
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

