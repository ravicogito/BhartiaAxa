<?php
include_once("new_functions.php");
$pageOwner = "'superadmin','admin','branch','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 
?>
<form name="addJobFrm" id="addJobFrm" action="a_changepass.php" method="post">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Change Admin Password</td>
    </tr>
    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
        * marked fields are mandatory</font></b></td>
    </tr>
    <tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Old Password<font color="#ff0000">*</font></td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><input name="old_password" type="password" class="inplogin" id="old_password" value="" maxlength="20"></td>
    </tr>
    <tr> 
      <td class="tbllogin" valign="top" align="right">New Password<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="new_password" type="password" class="inplogin" id="new_password" value="" maxlength="20"></td>
    </tr>
    <tr> 
      <td class="tbllogin" valign="top" align="right">Confirm Password<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="confirm_password" type="password" class="inplogin" id="confirm_password" value="" maxlength="20"></td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Update" class="inplogin" type="submit"> 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
    </tr>
  </tbody>
</table>

