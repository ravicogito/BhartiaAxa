<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
ini_set('display_errors',1);


if(!defined('__CONFIG__'))
{
	header("location:../index.php");
	die();
}

$pageOwner = "'superadmin','admin','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

extract($_POST);
$today = date('Y-m-d');
if(isset($received) && count($received) > 0)
{
	$receiveStr = implode(',',$received);
	$receivedUpdate = mysql_query("UPDATE customer_folio_no SET hub_received=1, hub_receive_date='".$today."' WHERE id IN (".$receiveStr.") AND hub_id=".$_SESSION[ADMIN_SESSION_VAR]."");
}

if(isset($admin_approval) && count($admin_approval) > 0)
{
	$admin_approvalStr = implode(',',$admin_approval);
	$admin_approvalUpdate = mysql_query("UPDATE customer_folio_no SET admin_approval=1 WHERE id IN (".$admin_approvalStr.") ");
}

if(isset($redemption_request_received) && count($redemption_request_received) > 0)
{
	$redemption_request_receivedStr = implode(',',$redemption_request_received);
	$receivedUpdate = mysql_query("UPDATE customer_folio_no SET redemption_request_received	=1, hub_id=".$_SESSION[ADMIN_SESSION_VAR].", redemption_request_receive_date='".$today."' WHERE id IN (".$redemption_request_receivedStr.") AND hub_id = 0");
}

if(isset($redemption_request_verified_by_hub) && count($redemption_request_verified_by_hub) > 0)
{
	$redemption_request_verified_by_hubStr = implode(',',$redemption_request_verified_by_hub);
	$redemption_request_verified_by_hubUpdate = mysql_query("UPDATE customer_folio_no SET redemption_request_verified_by_hub=1 WHERE id IN (".$redemption_request_verified_by_hubStr.") AND hub_id=".$_SESSION[ADMIN_SESSION_VAR]."");
}

	
	//echo "mode".$_REQUEST['mode'];
	if (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_add_edit")
	{
		user_add_edit($_REQUEST['user_id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_add")
	{
		update_record($_REQUEST['user_id']);
	}	
	//elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_delete")
	//{
		//user_delete($_REQUEST['user_id']);
	//}
	
	else
	{
		show_list();
	}
	
function show_list(){
	$objDB = new DB();
	$pid 			= loadVariable('pid',0);
	$showAll 		= loadVariable('all',0);
	$searchField 	= loadVariable('searchField','');
	$searchString 	= outputEscapeString(loadVariable('search',''));
	$sortField 		= loadVariable('sf','folio_no');
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
$where = " WHERE applied_for_redemption = 1 ";

	if($searchField=="folio_no"){
		$where .= " AND folio_no like '%".mysql_real_escape_string($searchString)."%' ";
	}

//echo $where;
$OrderBY = " ";

if($sortField <> "" && $sortType <> "")
{
	$OrderBY .= " ORDER BY ".$sortField." ".$sortType;
}


//=======================================================
$Query = "select count(id) as CNT from customer_folio_no  ".$where;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;

include_once("../utility/pagination.php");


$Query = "select  *  from customer_folio_no ".$where.$OrderBY.$Limit;
#echo $Query;
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
<script>
	function confirmDelete(ID)
	{
			if(confirm('User will be deleted.\nAre you sure ? '))
			{
				
				document.frm_opts.mode.value='user_delete';
				document.frm_opts.user_id.value=ID;
				document.frm_opts.submit();
			}
	}
	function statuschange(ID)
	{
		document.frm_opts.mode.value='user_changestatus';
		document.frm_opts.user_id.value=ID;
		document.frm_opts.submit();
	}
//-------------------------------------------------
	function sortList(sf,st)
	{
		document.forms['sortFrm'].sf.value= sf ;
		document.forms['sortFrm'].st.value= st ;
		document.forms['sortFrm'].submit();
		return true;
	}

	function dppList(dpp)
	{
		document.forms['sortFrm'].dpp.value= dpp;
		document.forms['sortFrm'].pg.value= 1;
		document.forms['sortFrm'].submit();
		return true;
	}
	//-------------------------------------------------
	function user_add()
	{
		document.frm_opts.mode.value='user_add_edit';
		document.frm_opts.submit();
		return true;
	}
	function user_edit(ID)
	{
		//alert(ID);
		document.frm_opts.mode.value='user_add_edit';
		document.frm_opts.user_id.value=ID;
		document.frm_opts.submit();
		return true;
	}
	function manage_subscription(ID)
	{
		document.frm_opts.mode.value='manage_subscription';
		document.frm_opts.user_id.value=ID;
		document.frm_opts.submit();
		return true;
	}
	/////////////////////////////////////////////////////////////////
	function chkAll(maxi)
	{
		var i=0;
		for(i=0;i<maxi;i++)
		{
			document.getElementById('chk'+i).checked=true;
		}
	}
	function clrAll(maxi)
	{
		var i=0;
		for(i=0;i<maxi;i++)
		{
			document.getElementById("chk"+i).checked=false;
		}
	}
	
	function totalDelete(maxi)
	{
		var j=0;
		for(i=0;i<maxi;i++)
		{
			if(document.getElementById("chk"+i).checked==false)
			{
				//alert('False');
			}
			else
			{
				j++;
			}
		}
		if(j>0)
		{
			if(confirm('User will be deleted.\nAre you sure ? '))
			{
				document.myfrm_detail.mode.value='user_total_delete';
				document.myfrm_detail.submit();
			}
		}
		else
		{
			alert('You have to select the checkbox');
		}
	}
	/////////////////////////////////////////////////////////////////
	function check_search()
	{
		
		if(document.searchFrm.searchField.value=='')
		{
			document.searchFrm.searchField.focus();
			alert('Please Select Search Option');
			return false;
		}
		if(document.searchFrm.searchField.value!='')
		{
			if(document.searchFrm.searchField.value!='user_hub')
			{
				if(document.searchFrm.search.value=='')
				{
					document.searchFrm.search.focus();
					alert('Please Enter text');
					return false;
				}
			}
		}
		
		return true;
	}
</script>
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
<form name="sortFrm" action="" method="post">
<input type="hidden" name="st" id="st" value="<?=$sortType?>" >
<input type="hidden" name="sf" id="sf" value="<?=$sortField?>" >
<input type="hidden" name="pg" id="pg" value="<?=$currentPage?>" >
<input type="hidden" name="p" id="p" value="<?=$GLOBALS['p']?>" >
<input type="hidden" name="mid" id="mid" value="<?=$mid?>" >
<input type="hidden" name="dpp" id="dpp" value="<?=$dataPerPage?>" >
<INPUT type="hidden" name="search" id="search" value="<?=$searchString?>">
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
<form name="searchFrm" id="searchFrm" action="" method="post" onsubmit="return check_search();">
  <TABLE class=border cellSpacing=1 cellPadding=5 width="100%" align=center border=0 >
    <TBODY>
    <TR >
      <TD colSpan=3 align="center"><? showMessage(); ?></TD>
    </TR>
    <TR class=TDHEAD> 
      <TD colSpan=3>Redemption Management Panel</TD>
    </TR>
    <TR> 
        <TD width="15%">Search:</TD>
		<td>
		<select name="searchField" id="searchField" class="inplogin"  >
			<option value="">Select Search Option</option>
			<option value="folio_no" <? if($searchField=='folio_no') echo 'selected';?>>Folio No. </option></td>
      <TD width="85%">
	  
	  <INPUT class="inplogin" name="search" id="search" value="<?=stripslashes($searchString)?>"> 
        <INPUT class="inplogin" type="submit" value="Search"> <INPUT class="inplogin" onclick="window.location.href='index.php?p=redemption&mid=<?=$mid?>&all=1'" type="button" value="Show All" name="btnShowAll">
      </TD>
    </TR>
  </TBODY>
</TABLE>
</form> 

<br />
<TABLE cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
        <TR>
          
      <TD width="93%" align=left valign="middle" ><? include_once("../utility/pagination_display.php");?></TD>
          <TD width="5%" align=right><A title=" Refresh the page" href="#" onClick="javascript:window.location.href='index.php?p=redemption&mid=<?=$mid?>&pid=<?=$pid?>&all=1';"><IMG src="images/icon_reload.gif" border=0></A></TD>
          
     <TD align=right width="2%"><!-- <a title=" Add " href="javascript:user_add();" ><img src="images/plus_icon.gif" border="0" /></a> --></TD>
		</TR>
</TBODY>
</TABLE>
<script language="javascript" type="text/javascript">
<!--
function popitup(url) {
	//alert("aaaaaa");
	newwindow=window.open(url,'Point History','height=350,width=750,left = 212,top = 259');
	if (window.focus) {newwindow.focus()}
	return false;
}

// -->
</script>
<form name="myfrm_detail" action="<?=$_SERVER['PHP_SELF'];?>?p=<?=$_REQUEST['p']?>" method="post">

<TABLE class="border" cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
        <TR>
        
	  <TD width="78%">
	  <input type="hidden" name="mode" value="">
	<input type="hidden" name="user_id" value="">
	<input type="hidden" name="st" id="st" value="<?=$sortType?>" >
	<input type="hidden" name="sf" id="sf" value="<?=$sortField?>" >
	<input type="hidden" name="pg" id="pg" value="<?=$currentPage?>" >
	<input type="hidden" name="p" id="p" value="<?=$GLOBALS['p']?>" >
	<input type="hidden" name="mid" id="mid" value="<?=$mid?>" >
	<input type="hidden" name="dpp" id="dpp" value="<?=$dataPerPage?>" >
	<INPUT type="hidden" name="search" id="search" value="<?=stripslashes($searchString)?>">  
	<INPUT type="hidden" name="searchField" id="searchField" value="<?=$searchField?>">  </TD>
		</TR>
</TBODY>
</TABLE>

<TABLE class="border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
	<tr>
		<td colspan="28" style="border:0px solid red"><input type="submit" name="btnUpdate" value="Update" class="inplogin"></td>
	</tr>
    
    <TR class="TDHEAD"> 
      <!-- <TD width="9%" align="center" class="heading_text"></TD> -->
	  <TD  width="10%" class="heading_text" align="left"> Customer ID </TD>
		<TD  width="10%" class="heading_text" align="left"> Folio No. </TD>
		<TD  width="10%" class="heading_text" align="left"> Application No. </TD>
		<TD  width="10%" class="heading_text" align="left"> Commodity Name </TD>	
		<TD  width="10%" class="heading_text" align="left"> Tenure </TD>
			<TD  width="10%" class="heading_text" align="left"> Start Date </TD>
		<TD  width="10%" class="heading_text" align="left"> End Date </TD>
		<TD  width="10%" class="heading_text" align="left"> Product Code </TD>
		<TD  width="10%" class="heading_text" align="left"> Product Name </TD>
		<TD  width="10%" class="heading_text" align="left"> Bonus Percentage </TD>
		<TD  width="10%" class="heading_text" align="left"> Redemption Reason </TD>
		<TD  width="10%" class="heading_text" align="left"> Applied On </TD>
		<td width="10%" class="heading_text" align="left">Redemption</td>
		<td width="10%" class="heading_text" align="left">Redemption Request Received From Branch</td>
		<td width="10%" class="heading_text" align="left">Signature and Application Form Image</td>
		<td width="10%" class="heading_text" align="left">Request Verified by Hub</td>
		<td width="10%" class="heading_text" align="left">Admin Approval</td>
		<td width="10%" class="heading_text" align="left">Delivery Person for HO</td>
	<?php
		if(intval($_SESSION[ROLE_ID]) != 4) // NOT FOR BRANCH
			{ 
	?>
		<td width="10%" class="heading_text" align="left">Material Received By HUB/Division</td>
		<td width="10%" class="heading_text" align="left">Hub Received Person</td>
		<td width="10%" class="heading_text" align="left">Acknowledgement Receipt</td>
		<td width="10%" class="heading_text" align="left">Delivery Person for HUB</td>
		<td width="10%" class="heading_text" align="left">Final Delivery Receipt for HUB</td>
		<td width="10%" class="heading_text" align="left">Upload Delivery Receipt for HUB</td>
	<?php 
			}
	?>
      
    </TR>
	
    <? for($i=0;$i<count($rs);$i++) { 
	 if(($i % 2)==0)
	 {
	 	$cls="text5";
	 }
	 else
	 {
	 	$cls="text6";
	 }
	
	 //$num_order=getOrderByUserId($rs[$i]['user_id']);
	?>
    <TR class=body onmouseover="this.bgColor='#F7F7F7'" onmouseout="this.bgColor=''"> 
      <!-- <TD align="center" class="<?=$cls?>">
	  <input type="checkbox" name="chk<?=$i;?>" id="chk<?=$i;?>" value="<?=$rs[$i]['id']?>"/> 	  
	  </TD> -->
      <TD class="<?=$cls?>" align="left"><?=find_customer_id_through_id($rs[$i]['customer_id'])?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['folio_no']?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['application_no']?></TD>
			<TD class="<?=$cls?>" align="left"><?=find_commodity_name($rs[$i]['commodity_name'])?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['tenure']?></TD>
			<TD class="<?=$cls?>" align="left"><?=date('d-m-Y',strtotime($rs[$i]['start_date']))?></TD>
			<TD class="<?=$cls?>" align="left"><?=date('d-m-Y',strtotime($rs[$i]['end_date']))?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['product_code']?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['product_name']?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['bonus_percentage']?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['redemption_reason']?></TD>
			<TD class="<?=$cls?>" align="left"><?=date('d-m-Y',strtotime($rs[$i]['redemption_date']))?></TD>
			<td width="10%" align="center" valign="top" ><?php if($rs[$i]['redemption_date'] != '0000-00-00'){ ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_redemption.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">View</a>
			
			<?php } else {  ?>
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL;?>webadmin/window_redemption.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Apply</a>
			<?php } ?>
			</td>
			<td width="8%" align="center" valign="top" ><?php echo ($rs[$i]['redemption_request_received'] == '1' ? 'Yes' : 'No')?></td>

			<td width="10%" align="center" valign="top" ><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL;?>webadmin/window_upload.php?id=<?php echo base64_encode($rs[$i]['id']);?>')">View or Upload</td>

			<td width="8%" align="center" valign="top" ><?php echo ($rs[$i]['redemption_request_verified_by_hub'] == '1' ? 'Yes' : 'No')?></td>
			
			<td width="8%" align="center" valign="top" ><input type="checkbox" name="admin_approval[]" value="<?php echo $rs[$i]['id']; ?>" <?php echo ($rs[$i]['admin_approval'] == '1' ? 'checked' : '')?>></td>
			
			<td width="10%" align="center" valign="top" ><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL;?>webadmin/window_delivery_person.php?id=<?php echo base64_encode($rs[$i]['id']);?>')">View or Add</td>
			<?php
		if(intval($_SESSION[ROLE_ID]) != 4) // NOT FOR BRANCH
			{ 
	?>
			<td width="8%" align="center" valign="top" ><?php echo ($rs[$i]['hub_received'] == '1' ? 'Yes' : 'No')?></td>

			<td width="10%" align="center" valign="top" ><?php if($rs[$i]['hub_received'] == '1'){ ?><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL;?>webadmin/window_hub_received_person.php?id=<?php echo base64_encode($rs[$i]['id']);?>')">View</a> <?php } ?></td>

			<td width="10%" align="center" valign="top" ><?php if($rs[$i]['hub_received'] == '1'){ ?><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL;?>webadmin/window_acknowledgement.php?id=<?php echo base64_encode($rs[$i]['id']);?>')">Generate<?php } ?> </td>
			<td width="10%" align="center" valign="top" ><?php if($rs[$i]['hub_received'] == '1'){ ?><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL;?>webadmin/window_hub_delivery_person.php?id=<?php echo base64_encode($rs[$i]['id']);?>')">View</a> <?php } ?></td>

			<td width="10%" align="center" valign="top" ><?php if(($rs[$i]['hub_delivery_person_name'] != '') && ($rs[$i]['hub_delivery_person_emp_code'] != '')){ ?><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL;?>webadmin/window_final_delivery_receipt.php?id=<?php echo base64_encode($rs[$i]['id']);?>')">Generate<?php } ?> </td>

			<td width="10%" align="center" valign="top" ><?php if((intval($rs[$i]['receipt_generated']) == 1) && ($rs[$i]['hub_delivery_person_emp_code'] != '')){ ?><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL;?>webadmin/window_receipt_upload.php?id=<?php echo base64_encode($rs[$i]['id']);?>')">View<?php } ?> </td>
			

	<?php
			}	
	?>
      
    </TR>
		
    <? } ?>
		<tr>
			<td colspan="28" style="border:0px solid red"><input type="submit" name="btnUpdate" value="Update" class="inplogin"></td>
		</tr>
	
  </TBODY>
</TABLE>
	

</form>

<? 
}
?>