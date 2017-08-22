<?php
if(isset($_REQUEST['id']) && $_REQUEST['id']<>"") {
include_once("../utility/config.php");

include_once("../utility/dbclass.php");

include_once("../utility/functions.php");

include_once("../includes/other_functions.php");
$objDB = new DB();

$Query ="select * from contact where m_id=".$_REQUEST['id']."";

$objDB->setQuery($Query);
$rec = $objDB->select();

?>
<table cellpadding="0" cellspacing="0" width="480" height="400" style="background-color:#E7E6E2">
 <tr align="left" valign="middle" style="background:#4A4A4A;"> 
 <td colspan="3" height="40" align="center" style="color:#FFFFFF;font-size:24px;font-weight:bold;font-family:Georgia, 'Times New Roman', Times, serif" >Contact Details</td>
 </tr>
 <tr><td width="10"  >&nbsp;</td>
 <td width="460" valign="top"><table cellpadding="2" cellspacing="2" width="105%" >
 	 <tr>
				<td width="33%" align="right" valign="top"><strong>Name :</strong></td>
				<td width="67%" align="left" valign="top"><? if(isset($rec[0]['name']) && $rec[0]['name']!="") {echo stripslashes($rec[0]['name']); }?>				</td>
	    </tr>
			   <tr>
				<td width="33%" align="right" valign="top"><strong>Company Name :</strong></td>
				
				<td width="67%" align="left" valign="top"><? if(isset($rec[0]['company_name']) && $rec[0]['company_name']!="") {echo stripslashes($rec[0]['company_name']); }?>				</td>
			  </tr>
			   
			  <tr>
				<td align="right" valign="top" ><strong>Email :</strong></td>
				
				<td align="left" valign="top"><? if(isset($rec[0]['email']) && $rec[0]['email']!="") {echo stripslashes($rec[0]['email']); }?>
				</td>
			  </tr>
			  <tr>
				<td width="33%" align="right" valign="top"><strong>Country :</strong></td>
				
				<td width="67%" align="left" valign="top"><? if(isset($rec[0]['country_id']) && $rec[0]['country_id']!="0") {
				echo getValue("country",$rec[0]['country_id'],"country_id","country_name_en") ;
				}?>				</td>
			  </tr>
			  <tr>
				<td width="33%" align="right" valign="top" ><strong>Phone No. :</strong></td>
				
				<td width="67%" align="left" valign="top"><? if(isset($rec[0]['phone']) && $rec[0]['phone']!="") {echo stripslashes($rec[0]['phone']); }?>				</td>
			  </tr>
			   <tr>
				<td width="33%" align="right" valign="top" ><strong>Website :</strong></td>
				
				<td width="67%" align="left" valign="top"><? if(isset($rec[0]['website']) && $rec[0]['website']!="") {echo stripslashes($rec[0]['website']); }?>				</td>
			  </tr>
			   <tr>
				<td width="33%" align="right" valign="top"><strong>Address :</strong></td>
				
				<td width="67%" align="left" valign="top"><? if(isset($rec[0]['addesss']) && $rec[0]['addesss']!="") {echo stripslashes($rec[0]['addesss']); }?>				</td>
			  </tr>
			  <tr>
				<td align="right" valign="top"><strong>Comments :</strong></td>
				
				<td align="left" valign="top"><? if(isset($rec[0]['comments']) && $rec[0]['comments']!="") { echo stripslashes($rec[0]['comments']); } else { echo "---"; }?>
				</td>
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