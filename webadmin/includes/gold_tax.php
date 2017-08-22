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
	$sortField 		= loadVariable('sf','place_id');
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
$where = " WHERE commodity_id ='".GOLD_ID."' ";

	if($searchField=="place_id"){
		$where .= " AND place_id='".mysql_real_escape_string($searchString)."' ";
	}

//echo $where;
$OrderBY = " ";

if($sortField <> "" && $sortType <> "")
{
	#$OrderBY .= " ORDER BY ".$sortField." ".$sortType;
	$OrderBY .= " ORDER BY id ".$sortType;
}


//=======================================================
$Query = "select count(id) as CNT from tax_master  ".$where;
#echo $Query;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;

include_once("../utility/pagination.php");


#$Query = "select  *  from tax_master ".$where.$OrderBY.$Limit;
$Query = "SELECT t.id as id, t.tax_percentage AS tax_percentage, p.place as place_id FROM tax_master AS t, place_master AS p WHERE t.commodity_id='".GOLD_ID."' AND t.place_id = p.id ORDER BY p.place ASC".$Limit;
#echo $Query ;
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
      <TD colSpan=3>Gold Tax Management Panel</TD>
    </TR>
    <TR> 
        <TD width="15%">Search:</TD>
		<td>
		<select name="searchField" id="searchField" class="inplogin"  >
			<option value="">Select Search Option</option>
			<option value="place_id" <? if($searchField=='place_id') echo 'selected';?>>Place </option></td>
      <TD width="85%">
	  
	  <INPUT class="inplogin" name="search" id="search" value="<?=stripslashes($searchString)?>"> 
        <INPUT class="inplogin" type="submit" value="Search"> <INPUT class="inplogin" onclick="window.location.href='index.php?p=gold_tax&mid=<?=$mid?>&all=1'" type="button" value="Show All" name="btnShowAll">
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
          <TD width="5%" align=right><A title=" Refresh the page" href="#" onClick="javascript:window.location.href='index.php?p=gold_tax&mid=<?=$mid?>&pid=<?=$pid?>&all=1';"><IMG src="images/icon_reload.gif" border=0></A></TD>
          
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
      <!-- <TD width="9%" align="center" class="heading_text"></TD> -->
	  <TD  width="15%" class="heading_text" align="left"> Place </TD>
		<TD  width="15%" class="heading_text" align="left"> Tax Percentage </TD>
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
      <!-- <TD align="center" class="<?=$cls?>">
	  <input type="checkbox" name="chk<?=$i;?>" id="chk<?=$i;?>" value="<?=$rs[$i]['id']?>"/> 	  
	  </TD> -->
      <TD class="<?=$cls?>" align="left"><?=$rs[$i]['place_id']; ?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['tax_percentage']?></TD>
      <TD align=center class="<?=$cls?>"><a title="Edit Details" href="javascript:user_edit(<?=$rs[$i]['id']?>)"><img src="images/edit_icon.gif" border=0 /></a></TD>
	  <!-- <TD align=center class="<?=$cls?>">
	   <a title="Delete Details" href="#" onclick="javascript:confirmDelete(<?=$rs[$i]['id']?>)"><img src="images/delete_icon.gif" border="0" /></a> -->
	 <? /*}*/?>
	  </TD>
    </TR>
    <? } ?>
	
  </TBODY>
</TABLE>
	
<!-- <TABLE class="border" cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
        <TR>
          
      <TD width="9%" align="left" valign="middle" ><a title="Check All User"  href="#" onclick="javascript:chkAll(<?=count($rs)?>);">Check All</a></TD>

      <TD width="8%" align="left"><a title="Clear All User"  href="" onclick="javascript:clrAll('<?=count($rs)?>');">Clear All</a></TD>
          
      <TD align="right" width="5%"><a title="Delete User" href="#" onclick="javascript:totalDelete(<?=count($rs)?>)"><img src="images/delete_icon.gif" border="0" /></a></TD>
	  <TD align=right width="78%">&nbsp;</TD>
		</TR>
</TBODY>
</TABLE> -->
</form>

<? }
function user_add_edit($user_id = '')
{ 
	#echo 'Hi';
	$objDB = new DB();
	$sel_place=mysql_query("SELECT * FROM place_master ORDER BY place ASC");	
	if($user_id != "")
	{	
		$current_mode = "Edit";
		$Query = "SELECT * FROM tax_master WHERE id=".$user_id;		
		/*$rs  = mysql_query($sql);
		$rec = mysql_fetch_array($rs);*/
		$objDB->setQuery($Query);
		$rec = $objDB->select();
		#print_r($rec);
		
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
				echo "Add Tax Percentage for Gold";
		    }
			else
			{
				echo "Edit Tax Percentage for Gold";
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
				<td width="23%" align="right" valign="top" class="tbllogin">Place <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
					
					<select name="place_id" id="place_id" class="validate[required] inplogin" >
					<option value="">Select</option>
					<?php
						if(mysql_num_rows($sel_place) > 0)
						{
							while($get_place = mysql_fetch_array($sel_place))
							{
								if(!isset($get_place['id'])){ $get_place['id']= ''; }
								if(!isset($rec[0]['place_id'])){ $rec[0]['place_id']= ''; }
					?>
							<option value="<?php echo $get_place['id']?>" <?php echo ($get_place['id'] == $rec[0]['place_id'] ? 'selected' : ''); ?>><?php echo $get_place['place']; ?></option>
					<?php
							}	
						}
					?>
					</select>
				</td>
			  </tr>
				<tr>
					<td width="23%" align="right" valign="top" class="tbllogin">Tax Percentage <font color="#ff0000">*</font></td>
					<td width="2%" align="center" valign="top" class="tbllogin">:</td>
					<td width="75%" align="left" valign="top">
						<input type="text" class="validate[required] inplogin" name="tax_percentage" id="tax_percentage" size="20" onKeyPress="return keyRestrict(event, '0123456789.')" <? if(isset($rec[0]['tax_percentage']) && $rec[0]['tax_percentage']!="") {?> value="<?=stripslashes($rec[0]['tax_percentage'])?>" <? }?>/>
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

	$place_id 					= loadVariable('place_id','');
	$tax_percentage 					= loadVariable('tax_percentage','');
	
	
	if ($row_id=='')
	{
		
		if($place_id == '')
		{	
			$_SESSION[ERROR_MSG] = "Please enter data for all mandetory fields...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
		
		$Query  = "select * from tax_master WHERE place_id = '".$place_id."' AND commodity_id='".GOLD_ID."' ";
		$objDB->setQuery($Query);
		$rsu = $objDB->select();
		if(count($rsu) > 0 )
		{
			$_SESSION[ERROR_MSG] = "Tax already exists...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
				
					
		$Query  = " INSERT INTO tax_master SET ";
		$Query .= " commodity_id 							= '".GOLD_ID."',";
		$Query .= " place_id 							= '".$place_id."',";
		$Query .= " tax_percentage 							= '".$tax_percentage."'";
				
		$objDB->setQuery($Query);
		$insertId = $objDB->insert();
		$main_site_user_id=$insertId;

		$lastInsertID = mysql_insert_id();

		//============registration to phpbb3=============
		//include("forum_registration.php");
		//================================
		$_SESSION[SUCCESS_MSG] = "Tax Added successfully...";
		header("location: index.php?p=".$_REQUEST['p']."");
		exit();
	}
	else
	{
		/*$Query  = "select * from tbl_users WHERE user_id=".$row_id."";
		$objDB->setQuery($Query);
		$rsu = $objDB->select();*/
			
	  		
				//echo $cat_name;
				//exit;
		if($place_id == '')
		{	
			$_SESSION[ERROR_MSG] = "Please enter data for all mandetory fields...";
			//cat_add_edit($row_id);
			header("location: index.php?p=".$_REQUEST['p']."&user_id=".$row_id."");
			exit();
		}
		$Query  = "select * from tax_master WHERE place_id = '".$place_id."' AND commodity_id='".GOLD_ID."' and id<>'".$row_id."'";
		#echo $Query; die();
		$objDB->setQuery($Query);
		$rsu = $objDB->select();
		if(count($rsu) > 0 )
		{
			$_SESSION[ERROR_MSG] = "Tax already exists...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
				
					
		$Query  = " UPDATE tax_master SET ";
		$Query .= " tax_percentage							= '".$tax_percentage."'";

	
		$Query .= " WHERE id='".$row_id."'";
	
		/*echo $Query;
		die();*/
		$objDB->setQuery($Query);
		$rs = $objDB->update();
		
		$_SESSION[SUCCESS_MSG] = "Tax updated successfully...";
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
}*/
/*function user_delete($row_id = '')
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
}*/

/*function user_total_delete()
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
}*/

?>

<script language="javascript">

<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=1,width=800,height=400,left = 112,top = 84');");
}
// End -->
</script>