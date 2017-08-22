<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");

if(!defined('__CONFIG__'))
{
	header("location:../index.php");
	die();
}

$pageOwner = "'superadmin','admin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

	

	
	//echo "mode".$_REQUEST['mode'];
	if (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_add_edit")
	{
		user_add_edit($_REQUEST['user_id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_add")
	{
		update_record($_REQUEST['user_id']);
	}
	/*elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_changestatus")
	{
		user_status($_REQUEST['user_id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_delete")
	{
		user_delete($_REQUEST['user_id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_total_delete")
	{
		user_total_delete();
	}*/
	/*elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="manage_church")
	{
		manage_subscription($_REQUEST['user_id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="church_assign")
	{
		church_assign($_REQUEST['user_id']);
	}*/
	
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
	$sortField 		= loadVariable('sf','price_wef');
	$sortType 		= loadVariable('st','DESC');
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
$where = " WHERE 1 ";


	if($searchField=="price_wef"){
		$where .= " AND price_wef ='".date('Y-m-d',strtotime($searchString))."' ";
	}elseif($searchField=="price_wet"){
		$where .= " AND price_wet =  '".date('Y-m-d',strtotime($searchString))."' ";
	}

//echo $where;
$OrderBY = " ";

if($sortField <> "" && $sortType <> "")
{
	$OrderBY .= " ORDER BY  ".$sortField." ".$sortType;
}


//=======================================================
$Query = "select count(id) as CNT from gold_rate_master  ".$where;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;
include_once("../utility/pagination.php");


$Query = "select  *  from gold_rate_master ".$where.$OrderBY.$Limit;
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
      <TD colSpan=3>Rate Management Panel</TD>
    </TR>
    <TR> 
        <TD width="15%">Search:</TD>
		<td>
		<select name="searchField" id="searchField" class="inplogin"  >
			<option value="">Select Search Option</option>
			<option value="price_wef" <? if($searchField=='price_wef') echo 'selected';?>>Price WEF </option>
			<option value="price_wet" <? if($searchField=='price_wet') echo 'selected';?>>Price WET </option>
			 </select></td>
      <TD width="85%">
	  
	  <INPUT class="inplogin" name="search" id="search" value="<?=stripslashes($searchString)?>"> 
        <INPUT class="inplogin" type="submit" value="Search"> <INPUT class="inplogin" onclick="window.location.href='index.php?p=gold_rate&mid=<?=$mid?>&all=1'" type="button" value="Show All" name="btnShowAll">
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
          <TD width="5%" align=right><A title=" Refresh the page" href="#" onClick="javascript:window.location.href='index.php?p=gold_rate&mid=<?=$mid?>&pid=<?=$pid?>&all=1';"><IMG src="images/icon_reload.gif" border=0></A></TD>
          
     <TD align=right width="2%"><a title=" Add " href="javascript:user_add();" ><img src="images/plus_icon.gif" border="0" /></a></TD>
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
          
      <!-- <TD width="9%" align="left" valign="middle" ><a title="Check All User"  href="#" onclick="javascript:chkAll(<?=count($rs)?>);">Check All</a></TD>

      <TD width="8%" align="left"><a title="Clear All User"  href="" onclick="javascript:clrAll('<?=count($rs)?>');">Clear All</a></TD>
          
      <TD align="right" width="5%"><a title="Delete User" href="#" onclick="javascript:totalDelete(<?=count($rs)?>)"><img src="images/delete_icon.gif" border="0" /></a></TD> -->
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
    
    <TR class="TDHEAD"> 
     <TD  width="15%" class="heading_text" align="left"> Commodity </TD>  
	  <TD  width="15%" class="heading_text" align="left"> Price (Rs.)</TD>
		<TD  width="15%" class="heading_text" align="left"> Price WEF </TD>
		<TD  width="15%" class="heading_text" align="left"> Price WET </TD>
		<TD  width="15%" class="heading_text" align="left"> Unit </TD>
      <TD align=center width="11%" class="heading_text">Edit</TD>
<!-- <TD align=center width="12%" class="heading_text">Delete</TD> -->
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
    <TD class="<?=$cls?>" align="left"><?=find_commodity_name($rs[$i]['commodity_id'])?></TD>  
    <TD class="<?=$cls?>" align="left"><?=$rs[$i]['price']?></TD>
	 
	  <TD class="<?=$cls?>" align="left"><?= date('d-m-Y',strtotime($rs[$i]['price_wef'])); ?></TD>
		<TD class="<?=$cls?>" align="left"><?= date('d-m-Y',strtotime($rs[$i]['price_wet'])); ?></TD>
		<TD class="<?=$cls?>" align="left"><?=$rs[$i]['unit']; ?></TD>
		
    <TD align=center class="<?=$cls?>">&nbsp;
		<?php 
			//echo $rs[$i]['entered_by'].' '.$rs[$i]['verified_by'].' '.$_SESSION[ADMIN_SESSION_VAR];
			if(intval($rs[$i]['entered_by']) != intval($_SESSION[ADMIN_SESSION_VAR]))
			{ 
				if(intval($rs[$i]['verified_by'] == 0))
				{ 
		?>
				<a title="Edit Details" href="javascript:user_edit(<?=$rs[$i]['id']?>)"><img src="images/edit_icon.gif" border=0 /></a>
		<?php 
				}
			} 
		?></TD>
	  <!-- <TD align=center class="<?=$cls?>">
	   <a title="Delete Details" href="#" onclick="javascript:confirmDelete(<?=$rs[$i]['id']?>)"><img src="images/delete_icon.gif" border="0" /></a> -->
	 <? /*}*/?>
	  </TD>
    </TR>
    <? } ?>
	
  </TBODY>
</TABLE>
	
<TABLE class="border" cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
        <TR>
          
      <!-- <TD width="9%" align="left" valign="middle" ><a title="Check All User"  href="#" onclick="javascript:chkAll(<?=count($rs)?>);">Check All</a></TD>

      <TD width="8%" align="left"><a title="Clear All User"  href="" onclick="javascript:clrAll('<?=count($rs)?>');">Clear All</a></TD>
          
      <TD align="right" width="5%"><a title="Delete User" href="#" onclick="javascript:totalDelete(<?=count($rs)?>)"><img src="images/delete_icon.gif" border="0" /></a></TD> -->
	  <TD align=right width="78%">&nbsp;</TD>
		</TR>
</TBODY>
</TABLE>
</form>

<? }
function user_add_edit($user_id = '')
{ 
	$objDB = new DB();
	if($user_id != "")
	{	
		$current_mode = "Edit";
		$Query = "SELECT * FROM gold_rate_master WHERE id=".$user_id;		
		/*$rs  = mysql_query($sql);
		$rec = mysql_fetch_array($rs);*/
		$objDB->setQuery($Query);
		$rec = $objDB->select();
		
	}

	else

	{

		$current_mode = "add";

	} 
?>
<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>

<form name="editFrm" id="frmadminform" action="<?=$_SERVER['PHP_SELF']?>?p=<?=$_REQUEST['p']?>" method="post"   enctype="multipart/form-data"><!--onSubmit="return check();" -->
<input type="hidden" name="mode" id="mode" value="user_add" >
<input type="hidden" name="user_id" id="user_id" <? if(isset($rec[0]['id']) && $rec[0]['id']!="") {?> value="<?=stripslashes($rec[0]['id'])?>" <? }?>>
  <TABLE class="tableBorder" cellSpacing=2 cellPadding=5 width="99%" align=center border=0>
    <tbody>
      <tr> 
        <td > 
          <? showMessage(); ?>        </td>
      </tr>
      <tr class="text4"> 
        <td class="TDHEAD">
		<?
			if($user_id == "")
			{
				echo "Add Rate";
		    }
			else
			{
				echo "Edit Rate";
			}		
		?>
		 </td>
      </tr>
	    <tr>
	  	<td >
	  		<table class="tableBorder" cellSpacing=2 cellPadding=5 width="99%" align=center border=0>
      <tr> 
        <td style="padding-left: 10px;" align="left"><b><font color="#ff0000">All * marked fields are mandatory</font></b></td>
      </tr>
	 <tr>
	  	<td >
			<table cellSpacing=2 cellPadding=5 width="99%" align=center border="0">
				
				<tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Commodity <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
				<?php
					$selCommodity = mysql_query("select  *  from commodity_master WHERE 1 ORDER BY commodity asc");	
				?>
					<select name="commodity_id" id="commodity_id" class="validate[required] inplogin">
						<option value="">Select</option>					
					<?php
						if(mysql_num_rows($selCommodity) > 0)
						{
							while($getCommodity = mysql_fetch_array($selCommodity))
							{
								$selected = isset($rec[0]['commodity_id']) && ($rec[0]['commodity_id'] == $getCommodity['id']) ? 'selected' : ''; 
					?>
						<option value="<?php echo $getCommodity['id']; ?>" <?php echo $selected; ?>><?php echo $getCommodity['commodity']; ?></option>	
					<?php							
							}
						}
					?>
					</select>
				</td>
			  </tr>
			  
			  <tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Price (Rs/- per gram) <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
					<input type="text" class="validate[required] inplogin" name="price" id="price" size="40" <? if(isset($rec[0]['price']) && $rec[0]['price']!="") {?> value="<?=stripslashes($rec[0]['price'])?>" <? }?> onKeyPress="return keyRestrict(event,'0123456789.')" />
				</td>
			  </tr>
			  <tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Price WEF <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
					<input type="text" class="validate[required] inplogin" name="price_wef" id="price_wef" size="40" <? if(isset($rec[0]['price_wef']) && $rec[0]['price_wef']!="") {?> value="<?= date('d-m-Y',strtotime($rec[0]['price_wef']));?>" <? }?> readonly /><img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.editFrm.price_wef,'dd-mm-yyyy',this)" width="20" height="18">
				</td>
			  </tr>
			  <tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Price WET <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
					<input type="text" class="validate[required] inplogin" name="price_wet" id="price_wet" size="40" <? if(isset($rec[0]['price_wet']) && $rec[0]['price_wet']!="") {?> value="<?= date('d-m-Y',strtotime($rec[0]['price_wet']))?>" <? }?> readonly /><img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.editFrm.price_wet,'dd-mm-yyyy',this)" width="20" height="18">
				</td>
			  </tr>
			  <tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Unit <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top"><input type="text" class="validate[required] inplogin" name="unit" id="unit" size="40" value="1 Gram" readonly />
				</td>
			  </tr>
			</table>
		</td>
	</tr>
      <tr> 
        <td align="center"><input type="hidden" id="a" name="a" value="add_user">
		<input value="<?=$current_mode=='add'?'  Add  ':'Update'?>" class="inplogin" type="submit" >
          &nbsp;&nbsp;&nbsp; <input value="Cancel" onclick="javascript:window.location.href='index.php?p=<?=$_REQUEST['p']?>'" class="inplogin" type="button">
		  </td>
      </tr>
	  </table>
	  </td>
	  </tr>
    </tbody>
  </table>
</form>
	
<? }
function  update_record($row_id = '')
{
	$objDB = new DB();

	$price 					= loadVariable('price','');
	$price_wef 			    = loadVariable('price_wef','');
	$price_wet 			    = loadVariable('price_wet','');
	$unit 			= loadVariable('unit','');
	$commodity_id = loadVariable('commodity_id','');
	
	
	if ($row_id=='')
	{
		
		if($price =='' || $price_wef =='' || $price_wet =='' || $unit =='' || $commodity_id == '')
		{	
			$_SESSION[ERROR_MSG] = "Please enter data for all mandetory fields...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
		
		$Query  = "select * from gold_rate_master WHERE price_wef = '".date('Y-m-d', strtotime($price_wef))."' AND price_wet = '".date('Y-m-d',strtotime($price_wet))."' AND commodity_id = '".$commodity_id."'";
		$objDB->setQuery($Query);
		$rsu = $objDB->select();
		if(count($rsu) > 0 )
		{
			$_SESSION[ERROR_MSG] = "Record already exists...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
		
					
		$Query  = " INSERT INTO gold_rate_master SET ";
		$Query .= " commodity_id 							= '".$commodity_id."',";
		$Query .= " price 							= '".$price."',";
		$Query .= " price_wef 						= '".date('Y-m-d', strtotime($price_wef))."',";
		$Query .= " price_wet 						= '".date('Y-m-d',strtotime($price_wet))."',";
		$Query .= " entered_by 						= '".$_SESSION[ADMIN_SESSION_VAR]."',";
		$Query .= " unit 				= '".$unit."'";
				
		$objDB->setQuery($Query);
		$insertId = $objDB->insert();
		$main_site_user_id=$insertId;

		
		$_SESSION[SUCCESS_MSG] = "Record Added successfully...";
		header("location: index.php?p=".$_REQUEST['p']."");
		exit();
	}
	else
	{
		
		if($price =='' || $price_wef =='' || $price_wet =='' || $unit =='' || $commodity_id == '')
		{	
			$_SESSION[ERROR_MSG] = "Please enter data for all mandetory fields...";
			//cat_add_edit($row_id);
			header("location: index.php?p=".$_REQUEST['p']."&user_id=".$row_id."");
			exit();
		}
		$Query  = "select * from gold_rate_master WHERE price_wef = '".date('Y-m-d', strtotime($price_wef))."' AND price_wet = '".date('Y-m-d',strtotime($price_wet))."' AND commodity_id = '".$commodity_id."' and id<>'".$row_id."'";
		$objDB->setQuery($Query);
		$rsu = $objDB->select();
		if(count($rsu) > 0 )
		{
			$_SESSION[ERROR_MSG] = "Record already exists...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
		
					
		$Query  = " UPDATE gold_rate_master SET ";
		$Query .= " price							= '".$price."',";
		$Query .= " verified_by							= '".$_SESSION[ADMIN_SESSION_VAR]."',";
		$Query .= " price_wef 						= '".date('Y-m-d',strtotime($price_wef))."',";
		
		$Query .= " price_wet  							= '".date('Y-m-d',strtotime($price_wet))."'";
	
		$Query .= " WHERE id='".$row_id."'";
	
		/*echo $Query;
		die();*/
		$objDB->setQuery($Query);
		$rs = $objDB->update();
		
		$_SESSION[SUCCESS_MSG] = "Record updated successfully...";
		$objDB->close();
		header("location: index.php?p=".$_REQUEST['p']."");
		exit();
	}
}
/*function user_status($row_id = '')
{
	$objDB = new DB();
	if($row_id)
	{
	$Query  = "select * from admin WHERE id = ".$row_id."";
	$objDB->setQuery($Query);
	$rsu = $objDB->select();

	if ($rsu[0]['status']== 1)

	{

	$Query  = "UPDATE admin SET status = 0 WHERE id = ".$row_id."";
	mysql_query("UPDATE admin SET status = 0 WHERE branch_user_id = ".$row_id.""); // branch user deactivated

	}

	elseif ($rsu[0]['status']== 0)

	{

	$Query  = "UPDATE admin SET status = 1 WHERE id = ".$row_id."";
	mysql_query("UPDATE admin SET status = 1 WHERE branch_user_id = ".$row_id.""); // branch user activated
	}

	//echo $Query;

	//die();


	$objDB->setQuery($Query);
	$rs = $objDB->update();



	$_SESSION[SUCCESS_MSG] = "User Status Changed successfully...";

	header("location: index.php?p=".$_REQUEST['p']."");

	exit();
	}
}
function user_delete($row_id = '')
{
	$objDB = new DB();
	if($row_id)
	{
		
	$transaction_delete = "DELETE FROM installment_master WHERE branch_id=".$row_id."";
	$objDB->setQuery($transaction_delete);
	$rs = $objDB->execute(); // all transaction for the relative branch is deleted

	$customer_delete = "DELETE FROM customer_master WHERE branch_id=".$row_id."";
	$objDB->setQuery($customer_delete);
	$rs = $objDB->execute(); // all customers for the relative branch is deleted
	
	$Query  = "DELETE FROM admin WHERE id = ".$row_id."";
	$objDB->setQuery($Query);
	$rs = $objDB->execute();



	$_SESSION[SUCCESS_MSG] = "User deleted successfully...";
	header("location: index.php?p=".$_REQUEST['p']."");
	exit();
	}
}
function user_total_delete()
{
	$objDB = new DB();
	foreach ($_POST as $key => $value)
	{
		if(substr($key,0,3)=="chk")
		{
			$Query  = "DELETE FROM `admin` where `id` = '$value'";
			$objDB->setQuery($Query);
			$rs = $objDB->execute();
		}
	}
	
	$_SESSION[SUCCESS_MSG] = "User deleted successfully...";
	header("location: index.php?p=".$_REQUEST['p']."");
	exit();
}
*/

?>