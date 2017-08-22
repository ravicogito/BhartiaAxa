<?php
// if(!defined('__CONFIG__'))
// {
// 	header("location:../index.php") or die('home not found');
// }
#print_r($_SESSION);
$pageOwner = "'admin','admin11','superadmin','hub','branch','division','subadmin','subsuperadmin'";

//chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 
?>
<div class="mainbox" style="padding-top:40px;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center"><? showMessage(); ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding-left:25px;">
	<? 
		if(isset($_SESSION[ADMIN_SESSION_VAR]) && ($_SESSION[ADMIN_SESSION_VAR] == 2 || $_SESSION[ADMIN_SESSION_VAR] == 383))
		{
	?>
  <tr>
    <td style="background-color:#0069C7; padding:5px 0 5px 5px; color:#FFFFFF; font-weight:bold; font-size:13px;">User & Report</td>
  </tr>
   
   <? 
   		} 
		else
		{
   ?>
   <tr>
    <td style="background-color:#0069C7; padding:5px 0 5px 5px; color:#FFFFFF; font-weight:bold; font-size:13px;">Branch Report</td>
  </tr>
  <?
  		}
  ?>
  <tr>
    <td style="padding:10px; background:#fff; border:1px solid #999999; margin-bottom:3px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="3%" height="128">&nbsp;</td>
    <? 
		if(isset($_SESSION[ADMIN_SESSION_VAR]) && $_SESSION[ADMIN_SESSION_VAR] == 2)
		{
	?>
    <td width="18%" align="left" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table_border">
  <tr>
    <td height="66"><table width="100%" height="59" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="50" height="50"><img src="images/users.png" alt="" width="50" height="50"/></td>
    <td width="68%"><span class="style2"><a href="index.php?p=user">User</a></span></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td height="61" align="left" valign="top" >Manage user from here </td>
  </tr>
</table>
	
    </td>
	<?
		}
	?>
	
   
<? 
		if(isset($_SESSION[ADMIN_SESSION_VAR]) && $_SESSION[ADMIN_SESSION_VAR] == 2)
		{
	?>
		
		 <td width="3%" align="left" valign="top">&nbsp;</td>
		 <td width="17%" align="left" valign="top">
		 	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table_border">
			  <tr>
			    <td height="66"><table width="100%" height="59" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td width="32%" height="59"><img src="images/report.png" alt="" width="50" height="50"/></td>
			    <td width="68%"><span class="style2"><a href="index.php?p=transaction_list_branch">Transaction List</a></span></td>
			  </tr>
			</table></td>
			  </tr>
			  <tr>
			    <td height="61" align="left" valign="top" >Manage transaction list from here </td>
			  </tr>
			</table>
		 </td>
		 <td width="3%" align="left" valign="top">&nbsp;</td>
		 
		 <td width="3%" align="left" valign="top">&nbsp;</td>
		 
	<?php 
		}
	?>
	<? 
		if(isset($_SESSION[ADMIN_SESSION_VAR]) && $_SESSION[ADMIN_SESSION_VAR] != 2)
		{
	?>
		<td width="3%" align="left" valign="top">&nbsp;</td>
		<?php 
		 if($_SESSION[ROLE_ID] != 7){
		?>
		 <td width="17%" align="left" valign="top">
		 	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table_border">
			  <tr>
			    <td height="66"><table width="100%" height="59" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td width="32%" height="59"><img src="images/report.png" alt="" width="50" height="50"/></td>
			    <td width="68%"><span class="style2"><a href="index.php?p=manual_entry_branch">Manual Entry</a></span></td>
			  </tr>
			</table></td>
			  </tr>
			  <tr>
			    <td height="61" align="left" valign="top" >Entry manually from here </td>
			  </tr>
			</table>
		 </td>
		 <?php } ?>
		 <td width="3%" align="left" valign="top">&nbsp;</td>
		 <td width="17%" align="left" valign="top">
		 	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table_border">
			  <tr>
			    <td height="66"><table width="100%" height="59" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td width="32%" height="59"><img src="images/report.png" alt="" width="50" height="50"/></td>
			    <td width="68%"><span class="style2"><a href="index.php?p=transaction_list_branch">Transaction List</a></span></td>
			  </tr>
			</table></td>
			  </tr>
			  <tr>
			    <td height="61" align="left" valign="top" >Manage transaction list from here </td>
			  </tr>
			</table>
		 </td>
		 <td width="3%" align="left" valign="top">&nbsp;</td>
		 <!-- <td width="17%" align="left" valign="top">
		 	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table_border">
			  <tr>
			    <td height="66"><table width="100%" height="59" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td width="32%" height="59"><img src="images/report.png" alt="" width="50" height="50"/></td>
			    <td width="68%"><span class="style2"><a href="index.php?p=deleted_transaction_branch">Deleted Transaction</a></span></td>
			  </tr>
			</table></td>
			  </tr>
			  <tr>
			    <td height="61" align="left" valign="top" >View deleted transaction list from here </td>
			  </tr>
			</table>
		 </td> -->
		 <td width="3%" align="left" valign="top">&nbsp;</td>
		 <!-- <td width="17%" align="left" valign="top">
		 	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="table_border">
			  <tr>
			    <td height="66"><table width="100%" height="59" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td width="32%" height="59"><img src="images/report.png" alt="" width="50" height="50"/></td>
			    <td width="68%"><span class="style2"><a href="index.php?p=zip">Upload XML</a></span></td>
			  </tr>
			</table></td>
			  </tr>
			  <tr>
			    <td height="61" align="left" valign="top" >Upload XML generated through Reliance Software from here </td>
			  </tr>
			</table>
		 </td> -->
	<?php 
		}
	?>
<!-- NEW SECTION -->
    <td width="3%" align="left" valign="top">&nbsp;</td>
    
    <td width="17%" align="left" valign="top">&nbsp;
	</td>
    <td width="3%" align="left" valign="top">&nbsp;</td>
	<td width="17%" align="left" valign="top">&nbsp;
	</td>
	<td width="3%" align="left" valign="top">&nbsp;</td>
<td width="16%" align="left" valign="top">&nbsp;
</td>
  </tr>
</table>	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
