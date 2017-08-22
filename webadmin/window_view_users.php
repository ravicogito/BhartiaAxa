<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");


$msg = '';

$objDB = new DB();
//if(!isset($_SESSION[ADMIN_SESSION_VAR]) || $_SESSION[ADMIN_SESSION_VAR] != 2)
//{
	//echo 'Hi';
	//header("location: index.php");
	//exit();
//}

$pageOwner = "'superadmin','admin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

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
	

	//header("location: index.php?p=".$_REQUEST['p']."");

	//exit();
	}
}

if (isset($_REQUEST['mode']) && $_REQUEST['mode']=="user_changestatus")
{
	user_status($_REQUEST['user_id']);
}



//$selBranch = mysql_query("SELECT * FROM admin WHERE branch_user_id= ORDER BY branch_name ASC");
// Write functions here


if(isset($_GET['id']) && !empty($_GET['id']))
{
	

	$user_id = base64_decode($_GET['id']);
	//echo $user_id;
	$Query = "SELECT * FROM admin WHERE id='".$user_id."' ORDER BY branch_name ASC";
	#echo $Query;
	$objDB->setQuery($Query);
	$rs = $objDB->select();

	#$selBranch = mysql_query("SELECT * FROM admin WHERE branch_user_id='".$user_id."' ORDER BY branch_name ASC");

}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> View Branch Users </title>
  <meta name="Generator" content="">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
	<script type="text/javascript">
	<!--
		function statuschange(ID)
		{
			document.addForm.mode.value='user_changestatus';
			document.addForm.user_id.value=ID;
			document.addForm.submit();
		}
	//-->
	</script>
	
	<link rel="shortcut icon" type="image/x-icon" href="<?=URL?>images/favicon.ico">
<link rel="stylesheet" href="<?=URL?>webadmin/css/default.css">
<link rel="stylesheet" href="<?=URL?>webadmin/css/dropdown.css">
<link rel="stylesheet" href="<?=URL?>css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?=URL?>css/template.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<script src="<?=URL?>js/jquery.min.js" type="text/javascript"></script>
<script src="<?=URL?>js/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="<?=URL?>js/jquery.validationEngine.js" type="text/javascript"></script>


<style type="text/css">
body{
	font-family:Arial, Verdana, Helvetica, sans-serif; font-size:12px; font-weight:normal;
	color:#404040; text-decoration:none;
	text-align:justify;
	background:url(images/adminbg.gif) repeat-x 0 0; margin:0 auto;
	}
	
.insideBORDER{

	border: solid 1px #CCCCCC;

}
/*################ Style Css Use in hotelTabMenu ################*/
.hotelTabMenu a{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; color:#666666; text-decoration:none; height:24px; padding:0px 10px 0px 10px; background:#EFEFEF; display:block; line-height:24px; border:solid 1px #CBCBCB;}
.hotelTabMenu a:hover{ font-weight:bold; color:#000; text-decoration:none; background:#fff; border-bottom:0px;}

	 
.hotelTabSelect{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; background:#FFF; color:#000; height:24px; line-height:24px; text-decoration:none; padding:0px 10px 0px 10px; display:block; border-top:solid 1px #CBCBCB; border-left:solid 1px #CBCBCB; border-right:solid 1px #CBCBCB; border-bottom:0px;}

.hotelTabSelect a{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; background:#FFF; color:#000; height:24px; line-height:24px; text-decoration:none; padding:0px 10px 0px 10px; display:block;}
</style>	

	
<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
 </head>

 <body>
 <center>
 <div>
 <form name="addForm" id="addForm" action="" method="post" >
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="user_id" value="">
	 <TABLE class="border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
			<TBODY>

				<tr class=TDHEAD>
					<td colspan="5" align="center">
						View Branch Users
					</td>
				</tr>

				<tr>
					<td colspan="5" >
						<p>
							<b>
								<font color="#009933"><?php echo (isset($_SESSION[SUCCESS_MSG]) ? $_SESSION[SUCCESS_MSG] : ''); ?></font>
							</b>
						</p>
					</td>
				</tr>
				
				<TR class="TDHEAD"> 
					<TD width="22%" class="heading_text" align="left"><img src="images/sort_down.gif" width="11" height="9" border="0" style="cursor:pointer;" onClick="javascript:sortList('name','ASC');"> Name(Username) <img src="images/sort_up.gif" width="11" height="9" border="0" style="cursor:pointer;" onClick="javascript:sortList('name','DESC');"></TD>
				<TD  width="15%" class="heading_text" align="left"> Branch </TD>
				<TD  width="15%" class="heading_text" align="left"> Password </TD>
				<TD align=center width="10%" class="heading_text">Branch Code</TD>
				<TD align=center width="10%" class="heading_text">Status</TD>
		<!-- <TD align=center width="12%" class="heading_text">Delete</TD> -->
				</TR>
			
				<? 
				if(count($rs) > 0)
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
			
			 //$num_order=getOrderByUserId($rs[$i]['user_id']);
			?>
				<TR class=body onmouseover="this.bgColor='#F7F7F7'" onmouseout="this.bgColor=''"> 
					
					<TD class="<?=$cls?>" align="left"><?=$rs[$i]['name']?>(<?=$rs[$i]['username']?>)</TD>
			 
				<TD class="<?=$cls?>" align="left"><?=$rs[$i]['branch_name']; ?></TD>
				<TD class="<?=$cls?>" align="left"><?=$rs[$i]['password']; ?></TD>
				<TD class="<?=$cls?>" align="center"><?=$rs[$i]['branch_code']; ?></TD>
				 
				<TD align=center>
				<a title="Change Status" href="#" onclick="javascript:statuschange(<?=$rs[$i]['id']?>)">
				<?php if ($rs[$i]['status'] == '1' ) { ?><img src="images/unlock_icon.gif" border="0" /><? } ?>
				<?php if ($rs[$i]['status'] == '0' ) { ?><img src="images/locked_icon.gif" border="0" /><? } ?>
				</a>
				</TD>
				<!-- <TD align=center class="<?=$cls?>">
				 <a title="Delete Details" href="#" onclick="javascript:confirmDelete(<?=$rs[$i]['id']?>)"><img src="images/delete_icon.gif" border="0" /></a> -->
			 <? /*}*/?>
				</TD>
				</TR>
				<? }
				}
				else
				{
				?>
				<tr >
					<td colspan="5" align="center">
						No Record Found.
					</td>
				</tr>
				<?php
				}
				?>
			
			</TBODY>
		</TABLE>
	 </form>
 </div>
 
 </center>
 </body>
</html>