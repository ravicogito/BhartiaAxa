<?php
if(!defined('__CONFIG__'))
{
	header("location:../index.php");
	die();
}
	//echo "mode".$_REQUEST['mode'];
	if (isset($_REQUEST['mode']) && $_REQUEST['mode']=="page_add_edit")
	{
		page_add_edit($_REQUEST['id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="page_add")
	{
		update_record($_REQUEST['id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="page_changestatus")
	{
		module_status($_REQUEST['id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="page_delete")
	{
		page_delete($_REQUEST['id']);
	}
	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="page_total_delete")
	{
		page_total_delete();
	}
	else
	{
		show_list();
	}
	
function show_list(){
	$objDB = new DB();
	$pid 			= loadVariable('pid',0);
	$showAll 		= loadVariable('all',0);
	$searchField 	= loadVariable('searchField','');
	$searchString 	= loadVariable('search','');
	$sortField 		= loadVariable('sf','page_name');
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
	

$where = " WHERE 1";


if($searchString <> "" && $searchField <> "" )
{
	if($searchField == "page_name"){
		$where .= " AND page_name like '%".mysql_real_escape_string($searchString)."%' ";
	}
	
	if($searchField == "msg_type"){
	
		if($searchString == 'error')
		{
			$where .= " AND msg_type = 'E' ";
		}
		
		if($searchString == 'status')
		{
			$where .= " AND msg_type = 'S' ";
		}
		
	}
}
$OrderBY = " ";

if($sortField <> "" && $sortType <> "")
{
	$OrderBY .= " ORDER BY ".$sortField." ".$sortType;
}


//=======================================================
$Query = "select count(id) as CNT from tbl_error_msg ".$where;
$objDB->setQuery($Query);
$rsTotal = $objDB->select();
$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $rsTotal[0]['CNT'];
//$dataPerPage = 10;
$pageSegmentSize = 15;
include_once("../utility/pagination.php");


$Query = "select * from tbl_error_msg ".$where.$OrderBY.$Limit;
//echo $Query;
//exit;
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
			if(confirm('Content will be deleted.\nAre you sure ? '))
			{
				
				document.frm_opts.mode.value='page_delete';
				document.frm_opts.id.value=ID;
				document.frm_opts.submit();
			}
	}
	function statuschange(ID)
	{
		document.frm_opts.mode.value='page_changestatus';
		document.frm_opts.id.value=ID;
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
	function page_add()
	{
		document.frm_opts.mode.value='page_add_edit';
		document.frm_opts.submit();
		return true;
	}
	function page_edit(ID)
	{
		//alert(ID);
		document.frm_opts.mode.value='page_add_edit';
		document.frm_opts.id.value=ID;
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
	function totalDelete(val)
	{
		//alert(val);
		var j=0;
		for(i=0;i<val;i++)
		{
			if(document.getElementById("chk"+i).checked==false)
			{
				//alert('aa');
			}
			else
			{
				j=j+1;
			}
		}
		//alert(j);
		if(j>0)
		{
			if(confirm('Content will be deleted.\nAre you sure ? '))
			{
				document.myfrm_detail.mode.value='page_total_delete';
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
		if(document.searchFrm.search.value=='')
		{
			document.searchFrm.search.focus();
			alert('Please Ente rtext');
			return false;
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
<INPUT type="hidden" name="search" id="search" value="<?=stripslashes($searchString)?>">
<INPUT type="hidden" name="searchField" id="searchField" value="<?=$searchField?>"> 
</form>
<form name="frm_opts" action="<?=$_SERVER['PHP_SELF'];?>?p=<?=$_REQUEST['p']?>" method="post" >
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="id" value="">
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
      <TD colSpan=3>Error Message Panel</TD>
    </TR>
    <TR> 
        <TD width="15%">Search:</TD>
		<td><select name="searchField" id="searchField" class="inplogin"  >
		<option value="">Select Search Option</option>
			<option value="page_name" <? if($searchField=='page_name') echo 'selected';?>>Page Name</option>
			<option value="msg_type" <? if($searchField=='msg_type') echo 'selected';?>>Message Type</option></select></td>
      <TD width="85%">
	  
	  <INPUT class="inplogin" name="search" id="search" value="<?=stripslashes($searchString)?>">
        <INPUT class="inplogin" type="submit" value="Search"> <INPUT class="inplogin" onclick="window.location.href='index.php?p=error_msg'" type="button" value="Show All" name="btnShowAll">
      </TD>
    </TR>
  </TBODY>
</TABLE>
</form> 

<TABLE cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
        <TR>
          
      <TD width="93%" align=left valign="middle" ><? include_once("../utility/pagination_display.php");?></TD>
          <TD width="5%" align=right><A title=" Refresh the page" href="#" onClick="javascript:window.location.href='index.php?p=error_msg';"><IMG src="images/icon_reload.gif" border=0></A></TD>
          
     <!--<TD align=right width="2%"><a title=" Add  " href="javascript:page_add();" ><img src="images/plus_icon.gif" border="0" /></a></TD>-->
		</TR>
</TBODY>
</TABLE>
<form name="myfrm_detail" action="<?=$_SERVER['PHP_SELF'];?>?p=<?=$_REQUEST['p']?>" method="post">
<TABLE class="border" cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
  <TBODY>

        <TR>
          
      <TD width="6%" align="left" valign="middle" ><a title="Check All Contact"  href="#" onclick="javascript:chkAll(<?=count($rs)?>);">Check All</a></TD>

      <TD width="7%" align="left"><a title="Clear All Contact"  href="" onclick="javascript:clrAll('<?=count($rs)?>');">Clear All</a></TD>
          
      <TD align="right" width="4%"><a title="Delete Contact" href="#" onclick="javascript:totalDelete(<?=count($rs)?>)"><img src="images/delete_icon.gif" border="0" /></a></TD>
	  <TD width="66%">
	  <input type="hidden" name="mode" value="">
	<input type="hidden" name="mid" value="">
	<input type="hidden" name="st" id="st" value="<?=$sortType?>" >
	<input type="hidden" name="sf" id="sf" value="<?=$sortField?>" >
	<input type="hidden" name="pg" id="pg" value="<?=$currentPage?>" >
	<input type="hidden" name="p" id="p" value="<?=$GLOBALS['p']?>" >
	<input type="hidden" name="mid" id="mid" value="<?=$mid?>" >
	<input type="hidden" name="dpp" id="dpp" value="<?=$dataPerPage?>" >
	<INPUT type="hidden" name="search" id="search" value="<?=$searchString?>">  </TD>
	<td width="17%">&nbsp;</td>
		</TR>
</TBODY>
</TABLE>
<TABLE class="border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
    
    <TR class="TDHEAD"> 
		<TD width="6%" align="center" class="heading_text">&nbsp;</TD>
      <TD width="6%" align="center" class="heading_text">Sl #</TD>
       <TD  width="19%" class="heading_text"><img src="images/sort_down.gif" width="11" height="9" border="0" style="cursor:pointer;" onClick="javascript:sortList('page_name','ASC');"> Page Name  <img src="images/sort_up.gif" width="11" height="9" border="0" style="cursor:pointer;" onClick="javascript:sortList('page_name','DESC');"></TD>
	   <TD  width="31%" class="heading_text"> Message Type </TD>
	    <TD  width="31%" class="heading_text"> Action </TD>
	   <TD align="center"  width="14%" class="heading_text">Edit</TD>
	    <TD align="center"  width="12%" class="heading_text">Status</TD>
       <TD align="center"  width="12%" class="heading_text">Delete</TD>
<!--<TD align=center width="11%" class="heading_text">Delete</TD>-->
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
	 
	 if($rs[$i]['msg_type'] == 'E')
	 {
	 	$ptype = "Error";
	 }
	 if($rs[$i]['msg_type'] == 'S')
	 {
	 	$ptype = "Status";
	 }
	 
	?>
    <TR class=body onmouseover="this.bgColor='#F7F7F7'" onmouseout="this.bgColor=''"> 
	<TD align="center" class="<?=$cls?>"><input type="checkbox" name="chk<?=$i;?>" id="chk<?=$i;?>" value="<?=$rs[$i]['id']?>"/> 	 </TD>
      <TD align="center" class="<?=$cls?>"><?=($i+$start+1)?></TD>
	  <TD class="<?=$cls?>"><?=$rs[$i]['page_name']?></TD>
	   <TD class="<?=$cls?>"><?=$ptype?></TD>
	   <TD class="<?=$cls?>"><?=$rs[$i]['msg_action']?></TD>
	        <TD align=center class="<?=$cls?>"><a title="Edit Details" href="javascript:page_edit(<?=$rs[$i]['id']?>)"><img src="images/edit_icon.gif" border=0 /></a></TD>
			 <TD align=center class="<?=$cls?>">
			 	  <a title="Change Status" href="#" onclick="javascript:statuschange(<?=$rs[$i]['id']?>)">
				  <?php if ($rs[$i]['is_active'] == 'Yes' ) { ?><img src="images/unlock_icon.gif" border="0" /><? } ?>
				  <?php if ($rs[$i]['is_active'] == 'No' ) { ?><img src="images/locked_icon.gif" border="0" /><? } ?>
				  </a>
			 </TD>
	  <TD align=center class="<?=$cls?>">
	  <a title="Delete Details" href="#" onclick="javascript:confirmDelete(<?=$rs[$i]['id']?>)"><img src="images/delete_icon.gif" border="0" /></a>
	
	  </TD>
    </TR>
    <? } ?>
	
  </TBODY>
</TABLE>
	<TABLE class="border" cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
        <TR>
          
      <TD width="9%" align="left" valign="middle" ><a title="Check All Contact"  href="#" onclick="javascript:chkAll(<?=count($rs)?>);">Check All</a></TD>

      <TD width="8%" align="left"><a title="Clear All Contact"  href="" onclick="javascript:clrAll('<?=count($rs)?>');">Clear All</a></TD>
          
      <TD align="right" width="5%"><a title="Delete Contact" href="#" onclick="javascript:totalDelete(<?=count($rs)?>)"><img src="images/delete_icon.gif" border="0" /></a></TD>
	  <TD align=right width="78%">&nbsp;</TD>
		</TR>
</TBODY>
</TABLE>
</form>
<? }
function page_add_edit($id = '')
{ 
	$objDB = new DB();
	if($id != "")
	{	
		$current_mode = "Edit";
		$Query = "SELECT * FROM tbl_error_msg WHERE id=".$id;		
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
<form name="editFrm" id="frmadminform" target="_self" action="<?=$_SERVER['PHP_SELF']?>?p=<?=$_REQUEST['p']?>" method="post"   enctype="multipart/form-data">
<input type="hidden" name="mode" id="mode" value="page_add" >
<input type="hidden" name="id" id="id" <? if(isset($rec[0]['id']) && $rec[0]['id']!="") {?> value="<?=stripslashes($rec[0]['id'])?>" <? }?>>
  <TABLE class="tableBorder" cellSpacing=2 cellPadding=5 width="99%" align=center border=0>
    <tbody>
      <tr> 
        <td > 
          <? showMessage(); ?>        </td>
      </tr>
      <tr class="text4"> 
        <td class="TDHEAD">
			<?
				if($id == "")
					{
						echo "Add Message";
					}
				else
					{
						echo "Edit Message";
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
          <td class="insideBORDER" style="border:0px;">
            <table border="0" cellspacing="0" cellpadding="0" align="left" style="border-left:solid 1px #CBCBCB; border-right:solid 1px #CBCBCB;border-bottom:solid 1px #CBCBCB;border-top:solid 1px #CBCBCB;" width="100%">
			
              <tr>
                <td valign="top" align="left">
				
				<table cellSpacing=2 cellPadding=5 width="99%" align=center border="0">
				
			  <tr>
				<td width="20%" align="right" valign="top" class="tbllogin">Message Type <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="78%" align="left" valign="top">
					<!--<input type="text" class="validate[required] inplogin" name="notice_title" id="notice_title" size="40" <? if(isset($rec[0]['notice_title']) && $rec[0]['notice_title']!="") {?> value="<?=stripslashes($rec[0]['notice_title'])?>" <? }?>/>-->
						<select name="msg_type" id="msg_type" class="validate[required] inplogin">
						<option value="">--Select--</option>
						<option value="E"<? if(isset($rec[0]['msg_type']) && $rec[0]['msg_type'] == 'E'){ echo "selected";}?>>Error</option>
						<option value="S"<? if(isset($rec[0]['msg_type']) && $rec[0]['msg_type'] == 'S'){ echo "selected";}?>>Status</option>
						
					</select>
						
				</td>
			  </tr>
			  <tr>
				<td width="20%" align="right" valign="top" class="tbllogin">Message Action <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="78%" align="left" valign="top">
					<input type="text" class="validate[required] inplogin" name="msg_action" id="msg_action" size="40" <? if(isset($rec[0]['msg_action']) && $rec[0]['msg_action']!="") {?> value="<?=stripslashes($rec[0]['msg_action'])?>" <? }?>/>	
				</td>
			  </tr>
			  <tr>
				<td width="20%" align="right" valign="top" class="tbllogin">Message <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="78%" align="left" valign="top">
					<?   
					//exit;
						/*include_once("../fckeditor/fckeditor.php");
						$oFCKeditor = new FCKeditor('notice_desc');
						$oFCKeditor->BasePath = '../fckeditor/';
						
						$oFCKeditor->Width = "100%";
						$oFCKeditor->Height = "400";
						if (isset($rec[0]['notice_desc']) && $rec[0]['notice_desc']!="")
						{
						/*$oFCKeditor->ToolbarSet = "Default";*/
						/*$oFCKeditor->Value = outputEscapeString($rec[0]['notice_desc'],'TEXTAREA');
						}
						$oFCKeditor->Create();*/
					?>
					<textarea name="msg_desc" id="msg_desc" class="validate[required] inplogin" style="width:260px;height:60px;" ><? if(isset($rec[0]['msg_desc']) && $rec[0]['msg_desc']!="") {?><?=stripslashes($rec[0]['msg_desc'])?><? }?></textarea>
				</td>
			  </tr>
			  
			  <tr>
				<td width="20%" align="right" valign="top" class="tbllogin">Page Shown <font color="#ff0000">*</font></td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="78%" align="left" valign="top">
					<select name="page_name" id="page_name" class="validate[required] inplogin">
						<option value="">--Select--</option>
						<option value="Home"<? if(isset($rec[0]['page_name']) && $rec[0]['page_name'] == 'Home'){ echo "selected";}?>>Home</option>
						<option value="Browse"<? if(isset($rec[0]['page_name']) && $rec[0]['page_name'] == 'Browse'){ echo "selected";}?>>Browse</option>
						<option value="Want to meet you"<? if(isset($rec[0]['page_name']) && $rec[0]['page_name'] == 'Want to meet you'){ echo "selected";}?>>Want to meet you</option>
						<option value="My Likes"<? if(isset($rec[0]['page_name']) && $rec[0]['page_name'] == 'My Likes'){ echo "selected";}?>>My Likes</option>
						<option value="Message"<? if(isset($rec[0]['page_name']) && $rec[0]['page_name'] == 'Message'){ echo "selected";}?>>Message</option>
					</select>
				</td>
			  </tr>
					  
			  
			   <!--<tr>
				<td width="20%" align="right" valign="top" class="tbllogin">Browser Title</td>
				<td width="2%" align="center" valign="top" class="tbllogin">:</td>
				<td width="78%" align="left" valign="top">
					<input type="text" class="inplogin" name="seo_browser_title" size="80" <? //if(isset($rec[0]['seo_browser_title']) && $rec[0]['seo_browser_title']!="") {?> value="<?//=stripslashes($rec[0]['seo_browser_title'])?>" <? //}?>/>
				</td>
			  </tr>-->
			  
			 		  
			  
			<!--  <tr>
			<td align="right" valign="top" width="15%" class="tbllogin">H1 Tag</td>
			<td align="center" valign="top" width="2%" class="tbllogin">:</td>
			<td align="left" width="83%" valign="top">
			<input type="text" class="inplogin"  name="seo_h1_tag" <? //if(isset($rec[0]['seo_h1_tag']) && $rec[0]['seo_h1_tag']!="") {?> value="<?//=stripslashes($rec[0]['seo_h1_tag'])?>" <? //}?>  size="80" />
			</td>
		  </tr>-->
		  
		 		  
		  <!-- <tr>
				<td align="right" valign="top" class="tbllogin">Meta Keyword</td>
				<td align="center" valign="top" class="tbllogin">:</td>
				<td align="left" valign="top">
					<textarea name="seo_meta_keyword" class="inplogin" id="seo_meta_keyword" style="width:500px;height:80px" ><? //if(isset($rec[0]['seo_meta_keyword']) && $rec[0]['seo_meta_keyword']!="") {?><?//=stripslashes($rec[0]['seo_meta_keyword'])?><? //}?></textarea>
				</td>
			  </tr>-->
			  
			 		  
			  
			   <!--<tr>
				<td align="right" valign="top" class="tbllogin">Meta Description </td>
				<td align="center" valign="top" class="tbllogin">:</td>
				<td align="left" valign="top">
					<textarea name="seo_meta_description" class="inplogin" id="seo_meta_description" style="width:500px;height:150px" ><? //if(isset($rec[0]['seo_meta_description']) && $rec[0]['seo_meta_description']!="") {?><?//=stripslashes($rec[0]['seo_meta_description'])?><? //}?></textarea>
				</td>
			  </tr>-->
			  <!--<tr>
				<td align="left" valign="top" class="tbllogin" colspan="3">Description : </td>
			  </tr>-->
			   
			 
			</table>
				
				
				</td>
              </tr>
            </table></td>
        </tr>
		
	  
		
	 
      <tr> 
        <td align="center"><!--<input value="Preview" onclick="javascript:preview();" class="inplogin" type="button">&nbsp;&nbsp;&nbsp;--><input type="hidden" id="a" name="a" value="add_user">
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
//echo "update record ".$row_id;
//exit;
	$msg_type 						= inputEscapeString(loadVariable('msg_type',''));
	$msg_action 					= inputEscapeString(loadVariable('msg_action',''));
	$msg_desc 						= inputEscapeString(loadVariable('msg_desc',''));
	$page_name 						= inputEscapeString(loadVariable('page_name',''));
	
	//============Create dynamic page url=====================
	//$seo_url=getSeoFriendlyUrl($title);
	//===============end page url==========
	/*echo "PAge Title: ".$title;
	echo "parent_id: ".$parent_id;
	echo "seo_url: ".$seo_url;
	exit;*/
	if ($row_id=='')
	{
		
		if($msg_type == '' || $msg_desc == '' || $msg_action == '')
		{	
			$_SESSION[ERROR_MSG] = "Please enter data for all mandetory fields...";
			//page_add_edit($row_id);
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
		$Query  = "select * from tbl_error_msg WHERE msg_type = '".$msg_type."' and page_name = '".$page_name."' 
		and msg_action = '".$msg_action."'";
		$objDB->setQuery($Query);
		$rsu = $objDB->select();
		if(count($rsu) > 0 )
		{
			$_SESSION[ERROR_MSG] = "Message already exist for this page...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
		
		
	
		/*$Query  = "select * from manage_module WHERE seo_url = '".$seo_url."' ";
		$objDB->setQuery($Query);
		$rsu = $objDB->select();
		if(count($rsu) > 0 )
		{
			$seo_url=$seo_url.$autoid;
		}*/
		$Query  = " INSERT INTO tbl_error_msg SET ";
		//$Query .= " seo_url 						= '".$seo_url."',";
		$Query .= " msg_type						= '".$msg_type."',";
		$Query .= " msg_action						= '".$msg_action."',";
		$Query .= " msg_desc						= '".$msg_desc."',";
		$Query .= " page_name 						= '".$page_name."',";
		/*$Query .= " seo_browser_title 			= '".$seo_browser_title."',";
		$Query .= " seo_h1_tag					= '".$seo_h1_tag."', ";
		$Query .= " seo_meta_keyword 			= '".$seo_meta_keyword."',";
		$Query .= " seo_meta_description		= '".$seo_meta_description."',";*/
		//$Query .= " is_active 						= 'Y',";
		$Query .= " create_date		 			= NOW()";
	
		$objDB->setQuery($Query);
		$insertId = $objDB->insert();
		
		$_SESSION[SUCCESS_MSG] = "Message Added successfully...";
		header("location: index.php?p=".$_REQUEST['p']."");
		exit();
	}
	else
	{
		$Query  = "select * from tbl_error_msg WHERE id=".$row_id."";
		$objDB->setQuery($Query);
		$rsu = $objDB->select();
			
		if($msg_type == '' || $msg_desc == '' || $msg_action == '')
		{	
			$_SESSION[ERROR_MSG] = "Please enter data for all mandetory fields...";
			//page_add_edit($row_id);
			header("location: index.php?p=".$_REQUEST['p']."&id=".$row_id."");
			exit();
		}
		$Query  = "select * from tbl_error_msg WHERE msg_desc = '".$msg_desc."' and page_name = '".$page_name."' 
		and msg_action = '".$msg_action."' and id<>'".$row_id."'";
		$objDB->setQuery($Query);
		$rsu = $objDB->select();
		if(count($rsu) > 0 )
		{
			$_SESSION[ERROR_MSG] = "Message already exist...";
			header("location: index.php?p=".$_REQUEST['p']."");
			exit();
		}
		
		
		$Query  = " UPDATE tbl_error_msg SET ";
		$Query .= " msg_type						= '".$msg_type."',";
		$Query .= " msg_action						= '".$msg_action."',";
		$Query .= " msg_desc						= '".$msg_desc."',";
		$Query .= " page_name 						= '".$page_name."'";
		/*$Query .= " seo_browser_title 			= '".$seo_browser_title."',";
		$Query .= " seo_h1_tag					= '".$seo_h1_tag."', ";
		$Query .= " seo_meta_keyword 			= '".$seo_meta_keyword."',";
		$Query .= " seo_meta_description		= '".$seo_meta_description."',";
		$Query .= " site_content_type 			= 'content', ";*/
		//$Query .= " update_date 				= NOW() ";
		$Query .= " WHERE    id='".$row_id."'";
	
		/*echo $Query;
		die();*/
		$objDB->setQuery($Query);
		$rs = $objDB->update();
		
		$_SESSION[SUCCESS_MSG] = "Message updated successfully...";
		$objDB->close();
		header("location: index.php?p=".$_REQUEST['p']."");
		exit();
	}
}

function module_status($row_id = '')
{
	$objDB = new DB();
	if($row_id)
	{
	$Query  = "select * from tbl_error_msg WHERE id = ".$row_id."";
	$objDB->setQuery($Query);
	$rsu = $objDB->select();

	if ($rsu[0]['is_active']=='Yes')

	{

	$Query  = "UPDATE tbl_error_msg SET is_active='No' WHERE id = ".$row_id."";

	}

	elseif ($rsu[0]['is_active']=='No')

	{

	$Query  = "UPDATE tbl_error_msg SET is_active='Yes' WHERE id = ".$row_id."";

	}

	//echo $Query;

	//die();

	$objDB->setQuery($Query);

	$rs = $objDB->execute();



	$_SESSION[SUCCESS_MSG] = "Message Status Change successfully...";

	header("location: index.php?p=".$_REQUEST['p']."");

	exit();
	}
}

function page_delete($row_id = '')
{
	$objDB = new DB();
	if($row_id)
	{
		
	$Query  = "DELETE FROM tbl_error_msg WHERE id = ".$row_id."";
	$objDB->setQuery($Query);
	$rs = $objDB->execute();

	$_SESSION[SUCCESS_MSG] = "Message deleted successfully...";
	header("location: index.php?p=".$_REQUEST['p']."");
	exit();
	}
}
function page_total_delete()
{
	$objDB = new DB();
	foreach ($_POST as $key => $value)
	{
		if(substr($key,0,3)=="chk")
		{
			$Query  = "DELETE FROM `tbl_error_msg` where `id` = '$value'";
			$objDB->setQuery($Query);
			$rs = $objDB->execute();
		}
	}
	
	$_SESSION[SUCCESS_MSG] = "Message deleted successfully...";
	header("location: index.php?p=".$_REQUEST['p']."");
	exit();
}
?>