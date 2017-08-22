<?php
#ini_set('display_errors', 1);
if(!defined('__CONFIG__'))
{
	header("location:../index.php");
	die();
}


	$objDB = new DB();

	$pageOwner = "'hub'";
	chkPageAccess($_SESSION[ROLE_ID], $pageOwner);
	
$where = " WHERE hub_id='".$_SESSION[ADMIN_SESSION_VAR]."' AND branch_user_id=0";
$OrderBY = ' ORDER BY branch_name ASC';


//=======================================================

$Query = "select  id, branch_name  from admin ".$where.$OrderBY;
#echo $Query;
$objDB->setQuery($Query);
$rs = $objDB->select();



//==========================================================


?>

<form name="frm_opts" action="<?=$_SERVER['PHP_SELF'];?>?p=<?=$_REQUEST['p']?>" method="post">
<input type="hidden" id="settings_id" name="settings_id" value="" />
<input type="hidden" id="mode" name="mode" value="" />

<TABLE class="border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0 >
  <TBODY>
	
    <TR >
      <TD colSpan=3 align="center"><? showMessage(); ?></TD>
    </TR>
		<TR >
      <TD colSpan=3 align="left">This page contains the list of branches for a hub.</TD>
    </TR>
    <TR class="TDHEAD">       
      <TD width="22%" class="heading_text" align="left">Branch List</TD>
      
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
      
      <TD class="<?=$cls?>" align="left">
			<?php echo $rs[$i]['branch_name']; 
				if($rs[$i]['id'] == $_SESSION[ADMIN_SESSION_VAR])
				{
					echo '&nbsp;<strong>(Home Branch)</strong>';
				}
			?>
			
			</TD>
      
    </TR>
    <? } ?>
	
  </TBODY>
</TABLE>	

</form>