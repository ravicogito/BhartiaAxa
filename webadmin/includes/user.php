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

	if (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_add_edit"){

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

	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_delete")

	{

		user_delete($_REQUEST['user_id']);

	}

	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_total_delete")

	{

		user_total_delete();

	}

	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="manage_church")

	{

		manage_subscription($_REQUEST['user_id']);

	}

	elseif (isset($_REQUEST['mode']) && $_REQUEST['mode']=="church_assign")

	{

		church_assign($_REQUEST['user_id']);

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

	$searchString 	= outputEscapeString(loadVariable('search',''));

	$sortField 		= loadVariable('sf','branch_name');

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

$where = " WHERE role_id NOT IN(1,2,5,6) AND branch_user_id=0 ";





//if($searchString <> "" && $searchField <> "" )

//{

	if($searchField=="user_name"){

		$where .= " AND name like '%".mysql_real_escape_string($searchString)."%' ";

	}elseif($searchField=="user_username"){

		$where .= " AND username like '%".mysql_real_escape_string($searchString)."%' ";

	}elseif($searchField=="user_branch_name"){

		$where .= " AND branch_name like '%".mysql_real_escape_string($searchString)."%' ";

	}elseif($searchField=="user_hub"){



		$sql = mysql_query("select  id  from admin where role_id = 3  AND branch_name like '%".mysql_real_escape_string($searchString)."%'");



		$data = mysql_fetch_array($sql);

		if(!empty($data['id'])){

		$where .= " AND hub_id = ".$data['id'];

		}

	}

//}



//echo $where;

$OrderBY = " ";



if($sortField <> "" && $sortType <> "")

{

	$OrderBY .= " ORDER BY ".$sortField." ".$sortType;

}





//=======================================================

$Query = "select count(id) as CNT from admin  ".$where;

$objDB->setQuery($Query);

$rsTotal = $objDB->select();

$displayTotal=$rsTotal[0]['CNT'];

$extraParam = "&p=".$GLOBALS['p'];



$dpp = true;

$totalRecordCount = $rsTotal[0]['CNT'];

$pageSegmentSize = 15;

include_once("../utility/pagination.php");





$Query = "select  *  from admin ".$where.$OrderBY.$Limit;

//echo $Query;

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



	function sortListCode(sf,st)

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

<?php $_SESSION['search_op']	= 	$searchField; 

      $_SESSION['search_data']	=	$searchString;

	

?>



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

	<tr> 

      <td colspan="25" align="right">

        <a title=" Export to Excel " href="<?=URL?>user_excel.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>

      </td>

    </tr>



    <TR class=TDHEAD> 

      <TD colSpan=3>User Management Panel</TD>

    </TR>

    <TR> 

        <TD width="15%">Search:</TD>

		<td>

		<select name="searchField" id="searchField" class="inplogin"  >

			<option value="">Select Search Option</option>

			<option value="user_branch_name" <? if($searchField=='user_branch_name') echo 'selected';?>>Branch Name </option>

			<option value="user_name" <? if($searchField=='user_name') echo 'selected';?>>Name </option>

			<option value="user_username" <? if($searchField=='user_username') echo 'selected';?>>Username </option>

			<option value="user_hub" <? if($searchField=='user_hub') echo 'selected';?>>HUB </option>

			 </select></td>

      <TD width="85%">

	  

	  <INPUT class="inplogin" name="search" id="search" value="<?=stripslashes($searchString)?>"> 

        <INPUT class="inplogin" type="submit" value="Search"> <INPUT class="inplogin" onclick="window.location.href='index.php?p=user&mid=<?=$mid?>&all=1'" type="button" value="Show All" name="btnShowAll">

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

          <TD width="5%" align=right><A title=" Refresh the page" href="#" onClick="javascript:window.location.href='index.php?p=user&mid=<?=$mid?>&pid=<?=$pid?>&all=1';"><IMG src="images/icon_reload.gif" border=0></A></TD>

          

     <TD align=right width="2%"><a title=" Add " href="javascript:user_add();" >

		 <?php if((intval($_SESSION[ROLE_ID]) == 1) || (intval($_SESSION[ROLE_ID]) == 2)){ ?>

		 <img src="images/plus_icon.gif" border="0" /></a><?php } ?></TD>

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

          

      <TD width="9%" align="left" valign="middle" ><a title="Check All User"  href="#" onclick="javascript:chkAll(<?=count($rs)?>);">Check All</a></TD>



      <TD width="8%" align="left"><a title="Clear All User"  href="" onclick="javascript:clrAll('<?=count($rs)?>');">Clear All</a></TD>

          

      <TD align="right" width="5%"><a title="Delete User" href="#" onclick="javascript:totalDelete(<?=count($rs)?>)"><img src="images/delete_icon.gif" border="0" /></a></TD>

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

      <TD width="9%" align="center" class="heading_text"><!--Sl #--></TD>

      <TD width="22%" class="heading_text" align="left"><img src="images/sort_down.gif" width="11" height="9" border="0" style="cursor:pointer;" onClick="javascript:sortList('name','ASC');"> Name(Username) <img src="images/sort_up.gif" width="11" height="9" border="0" style="cursor:pointer;" onClick="javascript:sortList('name','DESC');"></TD>

	  <TD  width="15%" class="heading_text" align="left"> Branch </TD>

		<TD  width="15%" class="heading_text" align="left"> Users </TD>

		<TD  width="15%" class="heading_text" align="left"> Hub Status </TD>

		<TD  width="15%" class="heading_text" align="left"> Hub Name </TD>

	  <TD  width="15%" class="heading_text" align="left"> Password </TD>

		<!--<TD align=center width="10%" class="heading_text">Branch Code</TD>-->



		<TD width="10%" class="heading_text" align="left"><img src="images/sort_down.gif" width="11" height="9" border="0" style="cursor:pointer;" onClick="javascript:sortListCode('branch_code','ASC');"> Branch Code <img src="images/sort_up.gif" width="11" height="9" border="0" style="cursor:pointer;" onClick="javascript:sortListCode('branch_code','DESC');"></TD>





	  <TD align=center width="10%" class="heading_text">Status</TD>

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

      <TD align="center" class="<?=$cls?>">

	  <input type="checkbox" name="chk<?=$i;?>" id="chk<?=$i;?>" value="<?=$rs[$i]['id']?>"/> 	  

	  <? /*=($i+$start+1)*/?></TD>

      <TD class="<?=$cls?>" align="left"><?=$rs[$i]['name']?>(<?=$rs[$i]['username']?>)</TD>

	 

	  <TD class="<?=$cls?>" align="left"><?=$rs[$i]['branch_name']; ?></TD>

		<TD class="<?=$cls?>" align="left"><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_view_users.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">View</a></TD>

		<TD class="<?=$cls?>" align="left">

		<?=$rs[$i]['role_id'] == 3 ? 'Yes' : 'No'; ?><br />

		<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_hub_status_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Change</a>		

		</TD>

		<TD class="<?=$cls?>" align="left">







		<?php  

		

		$sel_hub_id = mysql_query("SELECT hub_id FROM branch_hub_entry WHERE branch_id=".$rs[$i]['id']." ORDER BY id DESC LIMIT 0,1");



		if(mysql_num_rows($sel_hub_id) > 0)

		{

			$get_hub_id = mysql_fetch_array($sel_hub_id);

			$hubID = $get_hub_id['hub_id'];

			$sel_branch = mysql_query("SELECT branch_name FROM admin WHERE id='".$hubID."'");



			if(mysql_num_rows($sel_branch) > 0)

			{

				$get_branch = mysql_fetch_array($sel_branch);

				$hub_name = $get_branch['branch_name'] != '' ? $get_branch['branch_name'] : 'HO';

			}

		}

		?>





		<?php //$hub = find_hub_name($rs[$i]['id']); 

		echo $hub_name; ?><br />

		<?php if($rs[$i]['role_id'] != 3){ ?>

		<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_hub_edit.php?id=<?php echo base64_encode($rs[$i]['id']); ?>')">Change</a>

		<?php } ?>	

		</TD>

		<TD class="<?=$cls?>" align="left"> 

			<?php 

				if(intval($_SESSION[ROLE_ID]) == 1) 

				{ 

					echo $rs[$i]['password']; 

				}

				else

				{

					echo 'Access Denied';	

				}

				?>

			

			</TD>

		<TD class="<?=$cls?>" align="center"><?=$rs[$i]['branch_code']; ?></TD>

	   

	  <TD align=center><?php if((intval($_SESSION[ROLE_ID]) == 1) || (intval($_SESSION[ROLE_ID]) == 2)){ ?>

	  <a title="Change Status" href="#" onclick="javascript:statuschange(<?=$rs[$i]['id']?>)">

	  <?php if ($rs[$i]['status'] == '1' ) { ?><img src="images/unlock_icon.gif" border="0" /><? } ?>

	  <?php if ($rs[$i]['status'] == '0' ) { ?><img src="images/locked_icon.gif" border="0" /><? } ?>

	  </a>

		<?php } ?>

	  </TD>

      <TD align=center class="<?=$cls?>"><?php if((intval($_SESSION[ROLE_ID]) == 1) || (intval($_SESSION[ROLE_ID]) == 2)){ ?><a title="Edit Details" href="javascript:user_edit(<?=$rs[$i]['id']?>)"><img src="images/edit_icon.gif" border=0 /></a><?php } ?></TD>

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

          

      <TD width="9%" align="left" valign="middle" ><a title="Check All User"  href="#" onclick="javascript:chkAll(<?=count($rs)?>);">Check All</a></TD>



      <TD width="8%" align="left"><a title="Clear All User"  href="" onclick="javascript:clrAll('<?=count($rs)?>');">Clear All</a></TD>

          

      <TD align="right" width="5%"><a title="Delete User" href="#" onclick="javascript:totalDelete(<?=count($rs)?>)"><img src="images/delete_icon.gif" border="0" /></a></TD>

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

		$Query = "SELECT * FROM admin WHERE id=".$user_id;		

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

				echo "Add User";

		    }

			else

			{

				echo "Edit User";

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

				<td width="23%" align="right" valign="top" class="tbllogin">Username <font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

					<input type="text" class="validate[required] inplogin" name="username" id="username" size="40" <? if(isset($rec[0]['username']) && $rec[0]['username']!="") {?> value="<?=stripslashes($rec[0]['username'])?>" <? }?>/>

				</td>

			  </tr>

			  <tr>

				<td width="23%" align="right" valign="top" class="tbllogin">Password <font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

					<input type="password" class="validate[required] inplogin" name="password" id="password" size="40" <? if(isset($rec[0]['password']) && $rec[0]['password']!="") {?> value="<?=$rec[0]['password']?>" <? }?>/>

				</td>

			  </tr>

			  <tr>

				<td width="23%" align="right" valign="top" class="tbllogin">Email</td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

					<input type="text" class="inplogin" name="email" id="email" size="40" <? if(isset($rec[0]['email']) && $rec[0]['email']!="") {?> value="<?=stripslashes($rec[0]['email'])?>" <? }?>/>

				</td>

			  </tr>

			  <tr>

				<td width="23%" align="right" valign="top" class="tbllogin">Name <font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

					<input type="text" class="validate[required] inplogin" name="name" id="name" size="40" <? if(isset($rec[0]['name']) && $rec[0]['name']!="") {?> value="<?=stripslashes($rec[0]['name'])?>" <? }?>/>

				</td>

			  </tr>



            <!------------------------------------------------------------------------------------------>



            <?php $get_mainbranch = mysql_query("SELECT count(*) as num_mbranch FROM admin WHERE mainbranch_id!=0"); 



                  $cnt_mbranch = mysql_num_rows($get_mainbranch);//exit;      



            ?> 

            <tr>

				<td width="23%" align="right" valign="top" class="tbllogin">Main Branch Status<font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

			 <select class="validate[required] inplogin" name="main_status" id="main_status" class="inplogin_select" style="width:260px;" onchange="checkMainBranch(this.value);">

				    <option value="">--Select Main Branch Status--</option>

					<option value="2" <?php if($rec[0]['mainbranch_status']==2){echo 'selected';}?>>No</option>

					<option value="1" <?php if($cnt_mbranch == 0){?>selected <?php } if($rec[0]['mainbranch_status']==1){echo 'selected';}?> >Yes</option>				

			 </select>

				</td>

			  </tr>

            <?php //$display = ($cnt_mbranch == 0)?'none':''; 

            		$display = ($rec[0]['mainbranch_status'] == 2)?'':'none'; 
                    $displaymbcode = ($rec[0]['mainbranch_status'] == 1)?'':'none'; 
            ?>

            <tr style="display: <?php echo $display;?>;" id="mainbdid">

				<td width="23%" align="right" valign="top" class="tbllogin">Main Branch <font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

				<?php

					$sel_mainbranch=mysql_query("SELECT `id`,`mainbranch_id`,`branch_name` FROM admin WHERE mainbranch_id!=0 AND mainbranch_id=id ORDER BY branch_name ASC");

				?>

					<select class="inplogin" name="main_branch" id="main_branch" style="width:260px;">

						<option value="">Select Main Branch</option>

						<?php if(mysql_num_rows($sel_mainbranch) > 0){

							while($get_result = mysql_fetch_array($sel_mainbranch)){

								//if(!isset($get_place['id'])){ $get_place['id']= ''; }

								//if(!isset($rec[0]['state'])){ $rec[0]['state']= ''; }

						?>

							<option value="<?php echo $get_result['mainbranch_id']?>" <?php echo ($get_result['mainbranch_id'] == $rec[0]['mainbranch_id'] ? 'selected' : ''); ?>><?php echo $get_result['branch_name']; ?></option>

					<?php }	

						}

					?>

					</select>

					<!-- <input type="text" class="validate[required] inplogin" name="state" id="state" size="40" <? if(isset($rec[0]['branch_code']) && $rec[0]['branch_code']!="") {?> value="<?=stripslashes($rec[0]['branch_code'])?>" <? }?>/> -->

				</td>

			  </tr>

              <tr id="mainbrcode" style="display: <?php echo $displaymbcode;?>;">

				<td width="23%" align="right" valign="top" class="tbllogin">Main Branch Code <font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

				<input type="text" class="validate[required] inplogin" name="mainbranch_code" id="mainbranch_code" size="40" 
				<? if(isset($rec[0]['mainbranch_code']) && $rec[0]['mainbranch_code']!="") {?> value="<?=stripslashes($rec[0]['mainbranch_code'])?>" <? }?>/>

				</td>

			  </tr>

            <!------------------------------------------------------------------------------------------>



			  <tr>

				<td width="23%" align="right" valign="top" class="tbllogin">Branch Name <font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

					<input type="text" class="validate[required] inplogin" name="branch_name" id="branch_name" size="40" <? if(isset($rec[0]['branch_name']) && $rec[0]['branch_name']!="") {?> value="<?=stripslashes($rec[0]['branch_name'])?>" <? }?>/>

				</td>

			  </tr>

			

				<tr>

				<td width="23%" align="right" valign="top" class="tbllogin">Branch Code <font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

					<input type="text" class="validate[required] inplogin" name="branch_code" id="branch_code" size="40" <? if(isset($rec[0]['branch_code']) && $rec[0]['branch_code']!="") {?> value="<?=stripslashes($rec[0]['branch_code'])?>" <? }?>/>

				</td>

			  </tr>



				<tr>

				<td width="23%" align="right" valign="top" class="tbllogin">State <font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

				<?php

					$sel_place=mysql_query("SELECT * FROM place_master ORDER BY place ASC");		

				?>

					<select class="validate[required] inplogin" name="state" id="state" style="width:260px;">

						<option value="">Select</option>

						<?php

						if(mysql_num_rows($sel_place) > 0)

						{

							while($get_place = mysql_fetch_array($sel_place))

							{

								if(!isset($get_place['id'])){ $get_place['id']= ''; }

								if(!isset($rec[0]['state'])){ $rec[0]['state']= ''; }

						?>

							<option value="<?php echo $get_place['id']?>" <?php echo ($get_place['id'] == $rec[0]['state'] ? 'selected' : ''); ?>><?php echo $get_place['place']; ?></option>

					<?php

							}	

						}

					?>

					</select>

					<!-- <input type="text" class="validate[required] inplogin" name="state" id="state" size="40" <? if(isset($rec[0]['branch_code']) && $rec[0]['branch_code']!="") {?> value="<?=stripslashes($rec[0]['branch_code'])?>" <? }?>/> -->

				</td>

			  </tr>



				<tr>

				<td width="23%" align="right" valign="top" class="tbllogin">Branch Address <font color="#ff0000">*</font></td>

				<td width="2%" align="center" valign="top" class="tbllogin">:</td>

				<td width="75%" align="left" valign="top">

					<textarea class="validate[required] inplogin" style="width:260px; height:70px;" name="address" id="address"><? if(isset($rec[0]['address']) && $rec[0]['address']!="") { echo stripslashes($rec[0]['address']); }?></textarea>

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

	//echo $row_id."111111111111";exit;

	$objDB = new DB();

	

	$name 					= loadVariable('name','');

	$username 			    = loadVariable('username','');

	$password 			    = loadVariable('password','');

	$email 			        = loadVariable('email','');

	

	$main_branch			= loadVariable('main_branch',''); 

	$main_branch  = ($main_branch=="")?0:$main_branch;

    $mainbranch_status  = loadVariable('main_status',''); 

	$mainbranch_code	= loadVariable('mainbranch_code',''); 

    $mainbranch_code	=($mainbranch_code =="")?'':$mainbranch_code;

	$branch_name			= loadVariable('branch_name','');

	$branch_code = loadVariable('branch_code','');

	$address = loadVariable('address','');

	$state = loadVariable('state','');

	

	

	if ($row_id=='')

	{

		

		if($name == '' || $username =='' || $password =='' || $branch_name =='' || $branch_code =='')

		{	

			$_SESSION[ERROR_MSG] = "Please enter data for all mandetory fields...";

			header("location: index.php?p=".$_REQUEST['p']."");

			exit();

		}

		

		$Query  = "select * from admin WHERE username = '".$username."' ";

		$objDB->setQuery($Query);

		$rsu = $objDB->select();

		if(count($rsu) > 0 )

		{

			$_SESSION[ERROR_MSG] = "User already exist...";

			header("location: index.php?p=".$_REQUEST['p']."");

			exit();

		}



		/*$Query  = "select * from admin WHERE email = '".$email."' ";

		$objDB->setQuery($Query);

		$rsu = $objDB->select();

		if(count($rsu) > 0 )

		{

			$_SESSION[ERROR_MSG] = "User email already exist...";

			header("location: index.php?p=".$_REQUEST['p']."");

			exit();

		}*/

		$Query  = " INSERT INTO admin SET ";

		$Query .= " name 							= '".$name."',";

		$Query .= " username 						= '".$username."',";

		$Query .= " password 						= '".$password."',";

		$Query .= " second_password 				= '".$password."',";

		$Query .= " email  							= '".$email."',";

        $Query .= "subhub_id  							= '0',";

		$Query .= " mainbranch_role_id  			= '8',";

		$Query .= " mainbranch_status  				= '".$mainbranch_status."',";

		$Query .= " mainbranch_code  				= '".$mainbranch_code."',";

		$Query .= " mainbranch_id  					= '".$main_branch."',";

		$Query .= " branch_name  					= '".$branch_name."',";

		$Query .= " branch_code  					= '".$branch_code."',";

		$Query .= " address  						= '".realTrim($address)."',";

		$Query .= " state  							= '".realTrim($state)."',";

		$Query .= " status							= '1'";

	   // echo $Query;exit;

				

		$objDB->setQuery($Query);

		$insertId = $objDB->insert();

		$main_site_user_id=$insertId;



		$lastInsertID = mysql_insert_id();



        if($mainbranch_status == 1){

		mysql_query("UPDATE  admin SET 	mainbranch_id = '".$lastInsertID."' WHERE id='".$lastInsertID."'");

	    }

        

		mysql_query("INSERT INTO branch_hub_entry SET 										

										branch_id = '".$lastInsertID."',

										hub_id = 2,

										hub_since = '".date('Y-m-d')."'

				"); // at the time of branch creation hub is Head office (admin)



		//============registration to phpbb3=============

		//include("forum_registration.php");

		//================================

		$_SESSION[SUCCESS_MSG] = "User Added successfully...";

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

		if($name == '' || $username =='')

		{	

			$_SESSION[ERROR_MSG] = "Please enter data for all mandetory fields...";

			//cat_add_edit($row_id);

			header("location: index.php?p=".$_REQUEST['p']."&user_id=".$row_id."");

			exit();

		}

		$Query  = "select * from admin WHERE username = '".$username."'  and id<>'".$row_id."'";

		$objDB->setQuery($Query);

		$rsu = $objDB->select();

		if(count($rsu) > 0 )

		{

			$_SESSION[ERROR_MSG] = "User already exists...";

			header("location: index.php?p=".$_REQUEST['p']."");

			exit();

		}

		############################################################
		//echo $row_id."aaaaaaa".$main_branch."bbbbbbb".$mainbranch_status;exit;
		if($row_id == $main_branch && $mainbranch_status == 2){

		    
		    $_SESSION[ERROR_MSG] = "Main Branch name should be  different as Main Branch Status is 'No' ";

			header("location: index.php?p=".$_REQUEST['p']."");

			exit();
        }
		############################################################

		/*$Query  = "select * from admin WHERE email = '".$email."'  and id<>'".$row_id."'";

		$objDB->setQuery($Query);

		$rsu = $objDB->select();

		if(count($rsu) > 0 )

		{

			$_SESSION[ERROR_MSG] = "User email already exist...";

			header("location: index.php?p=".$_REQUEST['p']."");

			exit();

		}*/

		

		$Query  = " UPDATE admin SET ";

		$Query .= " name							= '".$name."',";

		$Query .= " username 						= '".$username."',";

		if($password != "")

		{

		$Query .= " password 						= '".$password."',";

		$Query .= " second_password  				= '".$password."',";

		}

		$Query .= " mainbranch_role_id  			= '8',";

		$Query .= " mainbranch_status  				= '".$mainbranch_status."',";

		$Query .= " mainbranch_code  				= '".$mainbranch_code."',";

		if($mainbranch_status == 1){

		$Query .= " mainbranch_id  					= '".$row_id."',";

        }else{

        $Query .= " mainbranch_id  					= '".$main_branch."',";	

        }



		$Query .= " branch_name  					= '".$branch_name."',";

		$Query .= " branch_code  				    = '".$branch_code."',";

		$Query .= " address  						= '".realTrim($address)."',";

		$Query .= " state  							= '".realTrim($state)."',";

		$Query .= " email 							= '".$email."'";



	

		$Query .= " WHERE id='".$row_id."'";

	

		//echo $Query;die();

		$objDB->setQuery($Query);

		$rs = $objDB->update();

		

		$_SESSION[SUCCESS_MSG] = "User updated successfully...";

		$objDB->close();

		header("location: index.php?p=".$_REQUEST['p']."");

		exit();

	}

}

function user_status($row_id = '')

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



?>



<script language="javascript">



<!-- Begin

function popUp(URL) {

day = new Date();

id = day.getTime();

eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=1,width=800,height=400,left = 112,top = 84');");

}

// End -->



function checkMainBranch(val){

 //alert(val+'aaaaaaaaaaaaa');

  if(val==1){

  	var status = confirm(" To select 'Yes' :: the enter branch should be Main Branch");

  	document.getElementById('mainbdid').style.display = 'none';
    document.getElementById('mainbrcode').style.display = '';
    //document.getElementById("main_branch").disabled=true;

  }else{

  	var status = confirm("To select 'No'  :: please select Main Branch from Main Branch select box");

  	 document.getElementById('mainbdid').style.display = '';
     document.getElementById('mainbrcode').style.display ='none';  
     
    /* var mbrnch_status  = document.getElementById('main_status').value();
     var brnch_id  = document.getElementById('user_id').value();
     var mbrnch_id = document.getElementById('main_branch').value();

     alert(brnch_id);alert(mbrnch_id);alert(brnch_id);

     if(((brnch_id == mbrnch_id) && (mbrnch_status==2))){
     	alert("yess");
         document.getElementById('main_branch').value('');
     }else{
     	alert("no");
     }*/

  	//document.getElementById("main_branch").disabled=false;

  }

}

</script>

