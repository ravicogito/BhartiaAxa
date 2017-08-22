<?php
#ini_set('display_errors', 1);
if(!defined('__CONFIG__'))
{
	header("location:../index.php");
	die();
}

$pageOwner = "'superadmin','admin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); // $USER_TYPE is coming from index.php


	//echo "mode".$_REQUEST['mode'];	
	if (isset($_REQUEST['mode']) && $_REQUEST['mode']=="service_changestatus")
	{
		service_status($_REQUEST['settings_id']);
	}	
	
	else
	{
		show_list();
	}
	
function show_list(){
	$objDB = new DB();
	
	$mode			= loadVariable('mode','');
	
$where = " WHERE name != 'Cheque Bounce Charge' ";
$OrderBY = ' ORDER BY id DESC';


//=======================================================

$Query = "select  *  from entry_settings ".$where.$OrderBY;
#echo $Query;
$objDB->setQuery($Query);
$rs = $objDB->select();


$QueryCB = "SELECT * FROM entry_settings WHERE name='Cheque Bounce Charge'";
#echo $Query;
$objDB->setQuery($QueryCB);
$rsCB = $objDB->select();

//print_r($rsCB);



//==========================================================


?>
<script>	
	function statuschange(ID)
	{
		document.frm_opts.mode.value='service_changestatus';
		document.frm_opts.settings_id.value=ID;
		document.frm_opts.submit();
	}


</script>


<form name="frm_opts" action="<?=$_SERVER['PHP_SELF'];?>?p=<?=$_REQUEST['p']?>" method="post">
<input type="hidden" id="settings_id" name="settings_id" value="" />
<input type="hidden" id="mode" name="mode" value="" />

<TABLE class="border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
    <TR >
      <TD colSpan=3 align="center"><? showMessage(); ?></TD>
    </TR>
    <TR class="TDHEAD">       
      <TD width="22%" class="heading_text" align="left">Name</TD>
			<TD width="22%" class="heading_text" align="left">Purpose</TD>
	  
	  <TD align=center width="10%" class="heading_text">Status</TD>
      
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
      
      <TD class="<?=$cls?>" align="left"><?=$rs[$i]['name']?></TD>
			<TD class="<?=$cls?>" align="left"><?=$rs[$i]['purpose']?></TD>
	 
	  
	  <TD align=center>
	  <a title="Change Status" href="#" onclick="javascript:statuschange(<?=$rs[$i]['id']?>)">
	  <?php if ($rs[$i]['status'] == '1' ) { ?><img src="images/unlock_icon.gif" border="0" /><? } ?>
	  <?php if ($rs[$i]['status'] == '0' ) { ?><img src="images/locked_icon.gif" border="0" /><? } ?>
	  </a>
	  </TD>
      
    </TR>
    <? } ?>

	<TR class='body'>       
      <TD ><div style="float:left; width:50%; text-align:center;" class="logout"><a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/window_cheque_bounce.php')">Update Cheque Bounce Charge</a></div><div style="float:left"><strong>&nbsp;&nbsp;&nbsp;Current Charge : <?php echo $rsCB[0]['value'];?> INR</strong></div></TD>
	  <td colspan="2" align="left">&nbsp;</td>
      
    </TR>
	
  </TBODY>
</TABLE>	

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

<? }


function service_status($row_id = '')
{
	$objDB = new DB();
	if($row_id)
	{
	$Query  = "select * from entry_settings WHERE id = ".$row_id."";
	$objDB->setQuery($Query);
	$rsu = $objDB->select();

	if ($rsu[0]['status']== 1)

	{

	$Query  = "UPDATE entry_settings SET status = 0 WHERE id = ".$row_id."";

	}

	elseif ($rsu[0]['status']== 0)

	{

	$Query  = "UPDATE entry_settings SET status = 1 WHERE id = ".$row_id."";

	}

	//echo $Query;

	//die();


	$objDB->setQuery($Query);
	$rs = $objDB->update();



	$_SESSION[SUCCESS_MSG] = "Service Status Changed successfully...";

	header("location: index.php?p=".$_REQUEST['p']."");

	exit();
	}
}

?>