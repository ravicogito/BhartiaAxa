<?

//include_once("../includes/other_functions.php");
if(isset($_SESSION[ADMIN_SESSION_VAR])) 
{
$Query = "select username,password,branch_name  from ".SITE_TABLE_PREFIX."admin  where id='".$_SESSION[ADMIN_SESSION_VAR]."'";
//echo "<br />".$Query;
$objDB->setQuery($Query);
$rs = $objDB->select();
$_SESSION['branch_code'] = $rs[0]['password'];
	
}
?>
<script type="text/javascript">
<!--
var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;
// open hidden layer
function mopen(id)
{	
	// cancel close timer
	mcancelclosetime();
	// close old layer
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
	// get new layer and show it
	ddmenuitem = document.getElementById(id);
	ddmenuitem.style.visibility = 'visible';
}
// close showed layer
function mclose()
{
	if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
}
// go close timer
function mclosetime()
{
	closetimer = window.setTimeout(mclose, timeout);
}
// cancel close timer
function mcancelclosetime()
{
	if(closetimer)
	{
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}
// close layer when click-out
document.onclick = mclose; 
// -->
</script>

<link rel="stylesheet" href="<?=URL?>webadmin/includes/style.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<TABLE cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
  <TBODY>
    <TR style="background-color:#F2F2F2;">
      <TD colspan="2" vAlign="center" align="left" height="10">&nbsp;</TD>
    </TR>
     <TR style="background-color:#F2F2F2;"> 
      <TD   align="left" width="500" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr><td ><h2>BHARATI AXA</h2></td></tr>
			  <?php 
					$HUB_NAME = '';
					$hubQry = mysql_query("SELECT branch_name FROM admin WHERE id=(SELECT hub_id FROM admin WHERE id='".$_SESSION[ADMIN_SESSION_VAR]."')");
					#echo $hubQry;
					if(mysql_num_rows($hubQry) > 0)
					{
						$getQry = mysql_fetch_array($hubQry);
						$HUB_NAME = $getQry['branch_name'];
					}
										
				?>
			  <tr><td class="paratext2">Administrator Control Panel</td></tr></table></TD>
	  <TD valign="middle" align="right" height=20> 
	  	<? if(isset($_SESSION[ADMIN_SESSION_VAR])) {?>
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
			  	
                <td width="308" align="right" style="color:#08457B;font-family:Verdana, Arial, Helvetica, sans-serif;font-weight:bold">Welcome <?=$rs[0]['username']; ?>&nbsp;&nbsp;(<?php echo ($rs[0]['branch_name'] != '' ? $rs[0]['branch_name'] : 'HO'); ?>)
								 <br /><span style="color:#990000;">Your HUB : <?php echo $HUB_NAME; ?>
								
								</span> 
								</td>
				<td width="70" align="right" style="padding-right:5px;">
					<!--<table width="70" border="0" cellspacing="0" cellpadding="0">
                  		<tr>
                    		<td width="125" align="center" class="logout"><a href="manual/index.php" target="_blank">Manual</a></td>
                  		</tr>
               	  </table>-->
					
				</td>
                <td width="125" align="right" style="padding-right:5px;">
				<? 
				//if(isset($_SESSION[ADMIN_SESSION_VAR]) && $_SESSION[ADMIN_SESSION_VAR] == 2)
				if(isset($_SESSION[ADMIN_SESSION_VAR]))
				{
				 if($_SESSION[ROLE_ID] != '7'){  // ============ Added ===============
				?>
					<table width="125" border="0" cellspacing="0" cellpadding="0">
                  		<tr>
                    		<td width="125" align="center" class="logout"><a href="index.php?p=changepass">Change Password </a></td>
                  		</tr>
               	  </table>
				<?
				 }  // ============= Added  ===========
				}
				?>	
				</td>
				<td width="70" align="left">
					
					<table width="65" border="0" cellspacing="0" cellpadding="0">
                  		<tr>
                    		<td align="center" class="logout"><a href="index.php?a=logout">Logout </a></td>
                  		</tr>
                	</table>
				</td>
              </tr>
            </table>
		<? }?>
      </TD>
    </TR>
	<TR>
	<td colspan="2"></td>
	</TR>
	<TR> 
      <TD colspan="2" class=paratext2>&nbsp;</TD>
    </TR>
<? if(isset($_SESSION[ADMIN_SESSION_VAR])) {?>
	<tr>
           <td align="left" valign="top" colspan="2" class="padding1"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr >
                      <td align="left" valign="bottom">
					  <div id="access">
						<div class="menu-header">
						<ul>
         
            <li><a href="index.php?p=home"  <? if($p == "home") echo 'class="select"'; else echo 'class="menu"'; ?> >Home</a> </li>
			<? 
			if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '1')) // Superadmin
			{
			?>
			<li><a href="#" target="_self" >Master</a>
        <ul>
        <li><a href="index.php?p=user" <? if($p == "user") echo 'class="select"'; else echo 'class="menu"'; ?>>User</a></li>		
		<li><a href="index.php?p=plan" <? if($p == "plan") echo 'class="select"'; else echo 'class="menu"'; ?>>Plan</a></li>
		<li><a href="index.php?p=place" <? if($p == "place") echo 'class="select"'; else echo 'class="menu"'; ?>>Place</a></li>
		<li><a href="index.php?p=state_master" <? if($p == "state_master") echo 'class="select"'; else echo 'class="menu"'; ?>>State</a></li>
		<li><a href="index.php?p=frequency" <? if($p == "frequency") echo 'class="select"'; else echo 'class="menu"'; ?>>Payment Frequency</a></li>
		<li><a href="index.php?p=payment_mode" <? if($p == "payment_mode") echo 'class="select"'; else echo 'class="menu"'; ?>>Payment Mode</a></li>					
		<li><a href="index.php?p=id_proof" <? if($p == "id_proof") echo 'class="select"'; else echo 'class="menu"'; ?>>Id Proof</a></li>				
		<li><a href="index.php?p=address_proof" <? if($p == "address_proof") echo 'class="select"'; else echo 'class="menu"'; ?>>Address Proof</a></li>
		<li><a href="index.php?p=age_proof" <? if($p == "age_proof") echo 'class="select"'; else echo 'class="menu"'; ?>>Age Proof</a></li>
		<li><a href="index.php?p=relationship" <? if($p == "relationship") echo 'class="select"'; else echo 'class="menu"'; ?>>Relationship</a></li>	        
		<li><a href="index.php?p=business_type" <?php if($p == "business_type") echo 'class="select"'; else echo 'class="menu"'; ?>>Business Type</a></li>
		<li><a href="index.php?p=sp_details" target="_self" >SP Details</a></li>
		<li><a href="index.php?p=campaign_master_add" <?php if($p == "campaign_master_add") echo 'class="select"'; else echo 'class="menu"'; ?>>Campaign Master Add</a></li>
        <li><a href="index.php?p=campaign_list" <?php if($p == "campaign_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Campaign Master list</a></li>
		<!--<li><a href="index.php?p=phase" <? if($p == "phase") echo 'class="select"'; else echo 'class="menu"'; ?>>Phase</a></li>
		<li><a href="index.php?p=installment_no" <? if($p == "installment_no") echo 'class="select"'; else echo 'class="menu"'; ?>>Installment No.</a></li>
        
		<li><a href="index.php?p=sum_assured" <? if($p == "sum_assured") echo 'class="select"'; else echo 'class="menu"'; ?>>Sum Assured</a></li>
		
        <li><a href="index.php?p=age" <? if($p == "age") echo 'class="select"'; else echo 'class="menu"'; ?>>Age</a></li>
        <li><a href="index.php?p=frequency" <? if($p == "frequency") echo 'class="select"'; else echo 'class="menu"'; ?>>Payment Frequency</a></li>
		
		<!--<li><a href="index.php?p=sicl_policy" <? if($p == "sicl_policy") echo 'class="select"'; else echo 'class="menu"'; ?>>Sicl Policy List</a></li>
		<li><a href="index.php?p=gtfs_policy" <? if($p == "gtfs_policy") echo 'class="select"'; else echo 'class="menu"'; ?>>Gtfs Policy List</a></li>
		<li><a href="index.php?p=sicl_import" <? if($p == "sicl_import") echo 'class="select"'; else echo 'class="menu"'; ?>>Upload SICL Policies</a></li>
        <li><a href="index.php?p=gtfs_import" <? if($p == "gtfs_import") echo 'class="select"'; else echo 'class="menu"'; ?>>Upload GTFS Policies</a></li>
        <li><a href="index.php?p=micr_import" <? if($p == "micr_import") echo 'class="select"'; else echo 'class="menu"'; ?>>Upload MICR</a></li>
		<li><a href="index.php?p=occupation" <? if($p == "occupation") echo 'class="select"'; else echo 'class="menu"'; ?>>Occupation</a></li>
        
        <!-- <li><a href="index.php?p=micr" <? if($p == "micr") echo 'class="select"'; else echo 'class="menu"'; ?>>MICR</a></li>
		<li><a href="index.php?p=health" <? if($p == "health") echo 'class="select"'; else echo 'class="menu"'; ?>>Health</a></li>-->
        
        
      
        </ul>
    </li>
			<?php	
			}
			?>
			<li><a href="#" target="_self" >New Business</a>
        <ul>
			<?php
			
if(isset($_SESSION[ROLE_ID]) && $_SESSION[ROLE_ID] == '4' || $_SESSION[ROLE_ID] == '1') 
			{
			?>
        <li><a href="index.php?p=manual_entry_branch" <? if($p == "manual_entry_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>New Entry</a></li>
			
			<?php
			}
			?>
        <li><a href="index.php?p=transaction_list_branch" <? if($p == "transaction_list_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>New Entry List</a></li>
        
        </ul>
    </li>
	<!--<li><a href="#" target="_self">Renewal</a>
        <ul>
        
        <li><a href="#" target="_self" >Renewal (SZ) </a>
            <ul>
<?php
			
if(isset($_SESSION[ROLE_ID]) && $_SESSION[ROLE_ID] == '4') 
			{
			?>
            <li><a href="index.php?p=renewal_entry_sz" <? if($p == "renewal_entry") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Entry (SZ)</a></li>
			<?php
}
?>
            <li><a href="index.php?p=renewal_list_sz" <? if($p == "renewal_list_sz") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (SZ)</a></li>
            
            </ul>
        </li>
<li><a href="#" target="_self" >Renewal (GE) </a>
            <ul>
<?php
			
if(isset($_SESSION[ROLE_ID]) && $_SESSION[ROLE_ID] == '4') 
			{
			?>
            <li><a href="index.php?p=renewal_entry_ge" <? if($p == "renewal_entry_ge") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Entry (GE)</a></li>
			<?php
}
?>
            <li><a href="index.php?p=renewal_list_ge" <? if($p == "renewal_list_ge") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (GE)</a></li>
            
            </ul>
        </li>
       
        </ul>
    </li>-->
	
	<!--<li><a href="#" target="_self">Short Premium</a>
        <ul>
		<?php
			
if(isset($_SESSION[ROLE_ID]) && $_SESSION[ROLE_ID] == '4' || $_SESSION[ROLE_ID] == '1') 
			{
			?>
            <li><a href="index.php?p=short_premium_entry" <? if($p == "short_premium_entry") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium Entry</a></li>
			<?php
}
?>
            <li><a href="index.php?p=short_premium_list" <? if($p == "short_premium_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List</a></li>
		</ul>
		</li>-->
		
		<!--<li><a href="#" target="_self">Short Premium (Renewal)</a>
        <ul>
		<?php
			
if(isset($_SESSION[ROLE_ID]) && $_SESSION[ROLE_ID] == '4') 
			{
			?>
            <li><a href="index.php?p=short_premium_renewal_entry_sz" <? if($p == "short_premium_renewal_entry_sz") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium Entry (SZ)</a></li>
			<?php
}
?>
            <li><a href="index.php?p=short_premium_renewal_list_sz" <? if($p == "short_premium_renewal_list_sz") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List (SZ)</a></li>
			
<?php
			
if(isset($_SESSION[ROLE_ID]) && $_SESSION[ROLE_ID] == '4') 
			{
			?>
            <li><a href="index.php?p=short_premium_renewal_entry_ge" <? if($p == "short_premium_renewal_entry_ge") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium Entry (GE)</a></li>
			<?php
}
?>
            <li><a href="index.php?p=short_premium_renewal_list_ge" <? if($p == "short_premium_renewal_list_ge") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List (GE)</a></li>
		</ul>
		</li>-->
		

<li><a href="#" target="_self">PIS</a>
        <ul>
		<?php
			if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '1' || $_SESSION[ROLE_ID] == '4')) 
			{
		?>
            <li><a href="index.php?p=pis_branch" <? if($p == "pis_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS New</a></li>
			
			<!--<li><a href="index.php?p=pis_branch_short_premium" <? if($p == "pis_branch_short_premium") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Short Premium</a></li>-->
		<?php
   
		   } 
		   else{
		?>
			<li><a href="index.php?p=pis_branch" <? if($p == "pis_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS New List</a></li>
			
			<li><a href="index.php?p=pis_branch_short_premium" <? if($p == "pis_branch_short_premium") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Short Premium List</a></li>


		<?php
			   
		   }
		?>	
        </ul>
			
</li>
<?php
	/* if($_SESSION[ROLE_ID] == '4'){ */
?>
<!--<li>
	<a href="index.php?p=branch_despatch" <?php /* if($p == "branch_despatch") echo 'class="select"'; else echo 'class="menu"'; */ ?>>Despatch</a>
</li>!-->
<?php
	/* } */
?>				
		<!--<li><a href="#" target="_self" >PIS (GE) </a>
            <ul>
            
          <li><a href="index.php?p=pis_branch_renewal_ge" <? if($p == "pis_branch_renewal_sz") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal (GE)</a></li>
		  <li><a href="index.php?p=pis_branch_short_renewal_ge" <? if($p == "pis_branch_short_renewal_ge") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Short Premium Renewal(GE)</a></li>
           
            </ul>
			
        </li>-->

		
       
<?php 	
if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '1')) // Superadmin
{
?>
			
		<li>
			<a href="#" target="_self" >Deleted List</a>
			<ul>
				<li><a href="index.php?p=d_transaction_list" target="_self" >Deleted Transaction</a></li>
				<!--<li><a href="index.php?p=d_short_premium_list" target="_self" >Deleted Short Premium</a></li>-->				                                
			</ul>
		</li>
		<li>
			<a href="#" target="_self" >Deleted PIS</a>
			<ul>
				<li><a href="index.php?p=d_pis_branch" target="_self" >Deleted Transaction PIS</a></li>
				<!--<li><a href="index.php?p=d_ pis_branch_short_premium" target="_self" >Deleted Short Premium PIS</a></li>-->				                                
			</ul>
		</li>
<?php
}
?>				
<?php 	
if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '1')) // Superadmin
{
?>
			
		<!--<li>
			<a href="#" target="_self" >Report</a>
			<ul>
				<li><a href="index.php?p=outstanding_report" target="_self" >Outstanding Report</a></li>
				<li><a href="index.php?p=cashiering_report" target="_self" >Cashiering Report</a></li>
				<li><a href="index.php?p=premium_billed_report" target="_self" >Premium Billed Report</a></li>
				<li><a href="index.php?p=d_transaction_list" target="_self" >New Business</a></li>
					<li><a href="index.php?p=d_renewal_list_sz" target="_self" >Renewal (SZ)</a></li>
					<li><a href="index.php?p=d_renewal_list_ge" target="_self" >Renewal (GE)</a></li>
					<li><a href="index.php?p=d_short_premium_fresh" target="_self" >Short Premium (fresh)</a></li>
					<li><a href="index.php?p=d_short_premium_renewal_sz" target="_self" >Renewal Short Premium(SZ)</a></li>
					<li><a href="index.php?p=d_short_premium_renewal_ge" target="_self" >Renewal Short Premium(GE)</a></li>
					<li><a href="#" target="_self" >PIS(SZ)</a>
						<ul>
						   <li><a href="index.php?p=d_pis_branch" target="_self" >PIS New(SZ)</a></li> 
						   <li><a href="index.php?p=d_pis_branch_renewal_sz" target="_self" >PIS Renewal (SZ)</a></li> 
						   <li><a href="index.php?p=d_ pis_branch_short_fresh_sz" target="_self" >PIS Short Premium Fresh (SZ)</a></li> 
						   <li><a href="index.php?p=d_pis_branch_short_renewal_sz" target="_self" >PIS Short Premium Renewal(SZ)</a></li> 
						</ul>
					</li>
					<li>
						<a href="#" target="_self" >PIS(GE)</a>
						<ul>
						   <li><a href="index.php?p=d_pis_branch_renewal_ge" target="_self" >PIS Renewal (GE)</a></li> 
						   <li><a href="index.php?p=d_pis_branch_short_renewal_ge" target="_self" >PIS Short Premium Renewal(GE)</a></li> 
						</ul>
					</li>
                                
			</ul>
		</li>-->
<?php
}
?>
			
			
					
          </ul>
          <br class="spacer" />
        </div>
		</div>
					  </td>
                      <td align="right" valign="middle"  class="middle_blank_td">&nbsp;</td>
                    </tr>
                  </table></td> 
            </tr>
	<? }?>
  </TBODY>
</TABLE>
