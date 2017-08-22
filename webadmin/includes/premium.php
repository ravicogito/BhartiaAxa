<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
#ini_set('display_errors',1);


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
		//echo $_REQUEST['user_id'];
		//exit;
		user_add_edit($_REQUEST['user_id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_add")
	{
		update_record($_REQUEST['user_id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_changestatus")
	{
		user_status($_REQUEST['user_id']);
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
	$sortField 		= loadVariable('sf','plan_id');
	$sortType 		= loadVariable('st','ASC');
	$dataPerPage 	= loadVariable('dpp',25);
	$mid 			= loadVariable('mid',0);
	$mode			= loadVariable('mode','');
	$mid 			= loadVariable('mid','plan_id');

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

	if($searchField=="plan"){

		$plan_id1 = find_plan_id($searchString);
		$where .= " AND plan_id = '".$plan_id1."' ";
	}

//echo $where;
$OrderBY = " ";

if($sortField <> "" && $sortType <> "")
{
	$OrderBY .= " ORDER BY ".$sortField." ".$sortType;
}


//=======================================================
$Query = "select count(id) as CNT from new_plan_rate  ".$where;
#echo $Query;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
$pageSegmentSize = 15;

include_once("../utility/pagination.php");


$Query = "select  *  from new_plan_rate ".$where.$OrderBY.$Limit;
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
      <TD colSpan=3>Premium Management Panel</TD>
    </TR>
    <TR> 
        <TD width="15%">Search:</TD>
		<td>
		<select name="searchField" id="searchField" class="inplogin"  >
			<option value="">Select Search Option</option>
			<option value="plan" <? if($searchField=='plan') echo 'selected';?>>Plan</option></td>
      <TD width="85%">
	  
	  <INPUT class="inplogin" name="search" id="search" value="<?=stripslashes($searchString)?>"> 
        <INPUT class="inplogin" type="submit" value="Search"> <INPUT class="inplogin" onclick="window.location.href='index.php?p=premium&mid=<?=$mid?>&all=1'" type="button" value="Show All" name="btnShowAll">
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
          <TD width="5%" align=right><A title=" Refresh the page" href="#" onClick="javascript:window.location.href='index.php?p=plan&mid=<?=$mid?>&pid=<?=$pid?>&all=1';"><IMG src="images/icon_reload.gif" border=0></A></TD>
          
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
        <tr> 
      <td colspan="25" align="right">
        <a title=" Export to Excel " href="<?=URL?>premium_excel.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr>
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
	  <TD  width="15%" class="heading_text" align="left"> Insurance Plan </TD>
		<TD  width="15%" class="heading_text" align="left"><span class="tbllogin">Age</span></TD>
		<TD align=center width="11%" class="heading_text"><span class="tbllogin">Term</span></TD>
		<TD align=center width="11%" class="heading_text"><span class="tbllogin">Premium Amount Rate</span></TD>
		<TD align=center width="11%" class="heading_text"><span class="tbllogin">Age Proof</span></TD>
		<TD align=center width="11%" class="heading_text"><span class="tbllogin">Extra Premium Amount Rate</span></TD>
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
      
			<TD class="<?=$cls?>" align="left"><?php echo find_plan($rs[$i]['plan_id']);?></TD>
			<TD class="<?=$cls?>" align="left"><?php echo find_age($rs[$i]['age_id']);?></TD>
			
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['term_id']?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['rate']?></TD>
			
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['age_proof']?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['extra_amount_rate']?></TD>
      <TD align=center class="<?=$cls?>"><a title="Edit Details" href="javascript:user_edit(<?=$rs[$i]['id']?>)"><img src="images/edit_icon.gif" border=0 /></a></TD>
	  <!-- <TD align=center class="<?=$cls?>">
	   <a title="Delete Details" href="#" onclick="javascript:confirmDelete(<?=$rs[$i]['id']?>)"><img src="images/delete_icon.gif" border="0" /></a> -->
	 <? /*}*/?>
	  </TD>
    </TR>
    <? } ?>
	
  </TBODY>
</TABLE>
	

</form>

<? }
function user_add_edit($user_id = '')
{ 
	#echo 'Hi';
	$objDB = new DB();
	if($user_id != "")
	{	
		$current_mode = "Edit";
		$Query = "SELECT * FROM new_plan_rate WHERE id=".$user_id;		
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
				echo "Add Premium";
		    }
			else
			{
				echo "Edit Premium";
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
				<td width="23%" align="right" valign="top" class="tbllogin">Insurance Plan <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
					<!-- <input type="text" class="validate[required] inplogin" name="plan" id="plan" size="40" <? if(isset($rec[0]['plan']) && $rec[0]['plan']!="") {?> value="<?=stripslashes($rec[0]['plan'])?>" <? }?> onKeyPress="return keyRestrict(event, '0123456789')" /> -->
					<?php
						$selPlan = mysql_query("SELECT * FROM insurance_plan where is_new = 1");	
						$numPlan = mysql_num_rows($selPlan);
					?>

					<select name="plan_id" id="plan_id" class="validate[required] inplogin" >
				<option value="">Select</option>
				<?php 
					if($numPlan > 0)
					{
						while($getPlan = mysql_fetch_array($selPlan))
						{							
				?>
					<option value="<?php echo $getPlan['id']; ?>" <?php if(isset($rec[0]['plan_id'])){echo ($getPlan['id'] == $rec[0]['plan_id'] ? 'selected' : '');} ?>><?php echo $getPlan['plan_name']; ?></option>
				<?php
						}
					}
				?>
			</select>

				</td>
			  </tr>
			  
			  <tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Age <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
					<!-- <input type="text" class="validate[required] inplogin" name="plan" id="plan" size="40" <? if(isset($rec[0]['plan']) && $rec[0]['plan']!="") {?> value="<?=stripslashes($rec[0]['plan'])?>" <? }?> onKeyPress="return keyRestrict(event, '0123456789')" /> -->
					<?php
						$selAge = mysql_query("SELECT * FROM age_master");	
						$numAge = mysql_num_rows($selAge);
					?>

					<select name="age_id" id="age_id" class="validate[required] inplogin">
				<option value="">Select</option>
				<?php 
					if($numAge > 0)
					{
						while($getAge = mysql_fetch_array($selAge))
						{							
				?>
					<option value="<?php echo $getAge['id']; ?>" <?php if(isset($rec[0]['age_id'])){echo ($getAge['id'] == $rec[0]['age_id'] ? 'selected' : '');} ?>><?php echo $getAge['age']; ?></option>
				<?php
						}
					}
				?>
			</select>

				</td>
			  </tr>
			  
			  
			  
			  

				<tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Term <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
				<?php
					$selTenure = mysql_query("SELECT id, tenure FROM tenure_master WHERE status=1 ORDER BY tenure ASC "); //for Term dropdown
					$numTenure = mysql_num_rows($selTenure);
				?>
					<select name="term_id" id="term_id" class="validate[required] inplogin" >
					<option value="">Select</option>
					<?php 
						if($numTenure > 0)
						{
							while($getTenure = mysql_fetch_array($selTenure))
							{							
					?>
						<option value="<?php echo $getTenure['tenure']; ?>"  <?php if(isset($rec[0]['term_id'])){echo ($getTenure['tenure'] == $rec[0]['term_id'] ? 'selected' : '');} ?> 
						><?php echo $getTenure['tenure']; ?></option>
					<?php
							}
						}
					?>
				</select>
				</td>
			  </tr>

			  <tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Premium Amount <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
					<input type="text" class="validate[required] inplogin" name="rate" id="rate" size="40" <? if(isset($rec[0]['rate']) && $rec[0]['rate']!="") {?> value="<?=stripslashes($rec[0]['rate'])?>" <? }?> onKeyPress="return keyRestrict(event, '0123456789.')" />
				</td>
			  </tr>

			  <tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Age Proof</td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
				
					<select name="age_proof" id="age_proof" class="inplogin" >
					<option value="" >Select</option>
					<option value="STANDARD" 
					<?php if(isset($rec[0]['age_proof'])){echo ($rec[0]['age_proof'] == 'STANDARD' ? 'selected' : '');} ?>>STANDARD</option>

					<option value="STANDARD" 
					<?php if(isset($rec[0]['age_proof'])){echo ($rec[0]['age_proof'] == 'NSAP1' ? 'selected' : '');} ?>>NSAP1</option>
					<option value="STANDARD" 
					<?php if(isset($rec[0]['age_proof'])){echo ($rec[0]['age_proof'] == 'NSAP23' ? 'selected' : '');} ?>>NSAP23</option>
				</select>	
				</td>
			  </tr>
			<tr>
				<td width="23%" align="right" valign="top" class="tbllogin">Extra Premium Amount <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="75%" align="left" valign="top">
					<input type="text" class="validate[required] inplogin" name="extra_amount_rate" id="extra_amount_rate" size="40" <? if(isset($rec[0]['extra_amount_rate']) && $rec[0]['extra_amount_rate']!="") {?> value="<?=stripslashes($rec[0]['extra_amount_rate'])?>" <? }?> onKeyPress="return keyRestrict(event, '0123456789.')" />
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

	$plan_id				= loadVariable('plan_id','');
	$age_id 				= loadVariable('age_id','');
	$rate 					= loadVariable('rate','');
	$term_id 				= loadVariable('term_id','');
	$age_proof 				= loadVariable('age_proof','');
	$extra_amount_rate 		= loadVariable('extra_amount_rate','');
	
	
	if ($row_id=='')
	{
		
		if(($plan_id == '') || ($age_id == '') || ($rate == '') || ($term_id == '') || ($age_proof == '')|| ($extra_amount_rate == ''))
		{	
			$_SESSION[ERROR_MSG] = "Please enter all mandetory fields...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
		
		
		$Query  = "select * from new_plan_rate WHERE plan_id = '".$plan_id."' AND age_id ='".$age_id."' AND rate = '".$rate."'   AND term_id = '".$term_id."' AND age_proof = '".$age_proof."' AND extra_amount_rate = '".$extra_amount_rate."' ";
		$objDB->setQuery($Query);
		$rsu = $objDB->select();
		if(count($rsu) > 0 )
		{
			$_SESSION[ERROR_MSG] = "Record already exists...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
				
					
		$Query  = " INSERT INTO new_plan_rate SET ";
		$Query .= " plan_id 						= '".$plan_id."',";
		$Query .= " age_id 							= '".$age_id."',";
		$Query .= " rate 							= '".$rate."',";
		$Query .= " term_id 						= '".$term_id."',";
		$Query .= " age_proof 						= '".$age_proof."',";
		$Query .= " extra_amount_rate 				= '".$extra_amount_rate."'";
		
		//echo $Query;
		//exit;
				
		$objDB->setQuery($Query);
		$insertId = $objDB->insert();
		$main_site_user_id=$insertId;

		$lastInsertID = mysql_insert_id();

		//============registration to phpbb3=============
		//include("forum_registration.php");
		//================================
		$_SESSION[SUCCESS_MSG] = "Record Added successfully...";
		header("location: index.php?p=".$_REQUEST['p']."");
		exit();
	}
	else
	{
		
		if(($plan_id == '') || ($age_id == '') || ($rate == '') || ($term_id == '') || ($age_proof == '')|| ($extra_amount_rate == ''))
		{	
			$_SESSION[ERROR_MSG] = "Please enter all mandetory fields...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
		
		
		$Query  = "select * from new_plan_rate WHERE plan_id = '".$plan_id."' AND age_id ='".$age_id."' AND rate = '".$rate."'   AND term_id = '".$term_id."' AND age_proof = '".$age_proof."' AND id != '".$row_id."'";

		

		$objDB->setQuery($Query);
		$rsu = $objDB->select();
		if(count($rsu) > 0 )
		{
			$_SESSION[ERROR_MSG] = "Record already exists...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
				
					
		$Query  = " UPDATE new_plan_rate SET ";
		$Query .= " plan_id 						= '".$plan_id."',";
		$Query .= " age_id 							= '".$age_id."',";
		$Query .= " rate 							= '".$rate."',";
		$Query .= " term_id 						= '".$term_id."',";
		$Query .= " age_proof 						= '".$age_proof."',";
		$Query .= " extra_amount_rate 				= '".$extra_amount_rate."'";
		$Query .= " WHERE id='".$row_id."'";
	
		/*echo $Query;
		die();*/
		$objDB->setQuery($Query);
		$rs = $objDB->update();
		
		$_SESSION[SUCCESS_MSG] = "Data updated successfully...";
		$objDB->close();
		header("location: index.php?p=".$_REQUEST['p']."");
		exit();
	}
}



?>

