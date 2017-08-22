<?php
if(isset($_REQUEST['quote_id']) && $_REQUEST['quote_id']<>"") {
include_once("../utility/config.php");

include_once("../utility/dbclass.php");

include_once("../utility/functions.php");

include_once("../includes/other_functions.php");
$objDB = new DB();

$Query ="select * from get_a_quote where quote_id=".$_REQUEST['quote_id']."";

$objDB->setQuery($Query);
$rs = $objDB->select();

?>
<table cellpadding="0" cellspacing="0" width="480" height="400" style="background-color:#E7E6E2">
 <tr align="left" valign="middle" style="background:#4A4A4A;"> 
 <td colspan="3" height="40" align="center" ><img src="images/header_get_quote.gif" ></td>
 </tr>
 <tr><td width="10"  >&nbsp;</td>
 <td width="460"><table cellpadding="2" cellspacing="2" width="105%" >
 	<tr><td width="27%" valign="top"><strong>Contact Name</strong></td>
 	<td width="73%" valign="top"><?=$rs[0]['contact_name']?></td>
 	</tr>
	<tr><td width="27%" valign="top"><strong>Contact Email</strong></td>
 	<td width="73%" valign="top"><?=$rs[0]['contact_email']?></td>
 	</tr>
	<tr><td width="27%" valign="top"><strong>Send Date</strong></td>
 	<td width="73%" valign="top"><?=date("M j, Y",strtotime($rs[0]['added_date']))?></td>
 	</tr>
 	<tr><td width="27%" valign="top"><strong>Design</strong></td>
 	<td width="73%" valign="top"><?=$rs[0]['design']?></td>
 	</tr>
	<tr><td width="27%" valign="top"><strong>Style</strong></td>
 	<td width="73%" valign="top"><?=$rs[0]['style']?></td>
 	</tr>
	<tr><td width="27%" valign="top"><strong>Quantity</strong></td>
 	<td width="73%" valign="top"><?=$rs[0]['quantity']?></td>
 	</tr>
	
 </table>
 </td>
 <td width="10" >&nbsp;</td>
 </tr>
 <tr align="left" valign="middle" style="background:#4A4A4A;"> 
 <td colspan="3" height="30">&nbsp;</td>
 </tr>
</table>
<? }?>