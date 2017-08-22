<?

//include_once("../includes/other_functions.php");

if(isset($_SESSION[ADMIN_SESSION_VAR])) {

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

              <tr><td ><h2>LICI</h2></td></tr>

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

				?>

					<table width="125" border="0" cellspacing="0" cellpadding="0">

                  		<tr>

                    		<td width="125" align="center" class="logout"><a href="index.php?p=changepass">Change Password </a></td>

                  		</tr>

               	  </table>

				<?

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

        <li><a href="index.php?p=place" <? if($p == "place") echo 'class="select"'; else echo 'class="menu"'; ?>>Place</a></li>



		<li><a href="index.php?p=phase" <? if($p == "phase") echo 'class="select"'; else echo 'class="menu"'; ?>>Phase</a></li>

		<li><a href="index.php?p=installment_no" <? if($p == "installment_no") echo 'class="select"'; else echo 'class="menu"'; ?>>Installment No.</a></li>







        <li><a href="index.php?p=plan" <? if($p == "plan") echo 'class="select"'; else echo 'class="menu"'; ?>>Plan</a></li>

		<li><a href="index.php?p=sum_assured" <? if($p == "sum_assured") echo 'class="select"'; else echo 'class="menu"'; ?>>Sum Assured</a></li>

        <li><a href="index.php?p=age" <? if($p == "age") echo 'class="select"'; else echo 'class="menu"'; ?>>Age</a></li>

        <li><a href="index.php?p=frequency" <? if($p == "frequency") echo 'class="select"'; else echo 'class="menu"'; ?>>Payment Frequency</a></li>

		<li><a href="index.php?p=premium" <? if($p == "premium") echo 'class="select"'; else echo 'class="menu"'; ?>>Premium</a></li>

		<li><a href="index.php?p=premium_calculation" <? if($p == "premium_calculation") echo 'class="select"'; else echo 'class="menu"'; ?>>Premium Calculation</a></li>
		<li><a href="index.php?p=tenure" <? if($p == "tenure") echo 'class="select"'; else echo 'class="menu"'; ?>>Term</a></li>

        <li><a href="index.php?p=id_proof" <? if($p == "id_proof") echo 'class="select"'; else echo 'class="menu"'; ?>>Id Proof</a></li>

		<li><a href="index.php?p=income_proof" <? if($p == "income_proof") echo 'class="select"'; else echo 'class="menu"'; ?>>Income Proof</a></li>

        <li><a href="index.php?p=address_proof" <? if($p == "address_proof") echo 'class="select"'; else echo 'class="menu"'; ?>>Address Proof</a></li>

        <li><a href="index.php?p=age_proof" <? if($p == "age_proof") echo 'class="select"'; else echo 'class="menu"'; ?>>Age Proof</a></li>



		<li><a href="index.php?p=sicl_policy" <? if($p == "sicl_policy") echo 'class="select"'; else echo 'class="menu"'; ?>>Sicl Policy List</a></li>

		<li><a href="index.php?p=gtfs_policy" <? if($p == "gtfs_policy") echo 'class="select"'; else echo 'class="menu"'; ?>>Gtfs Policy List</a></li>







		<li><a href="index.php?p=sicl_import" <? if($p == "sicl_import") echo 'class="select"'; else echo 'class="menu"'; ?>>Upload SICL Policies</a></li>

        <li><a href="index.php?p=gtfs_import" <? if($p == "gtfs_import") echo 'class="select"'; else echo 'class="menu"'; ?>>Upload GTFS Policies</a></li>

        <li><a href="index.php?p=micr_import" <? if($p == "micr_import") echo 'class="select"'; else echo 'class="menu"'; ?>>Upload MICR</a></li>



		<li><a href="index.php?p=occupation" <? if($p == "occupation") echo 'class="select"'; else echo 'class="menu"'; ?>>Occupation</a></li>

        <li><a href="index.php?p=relationship" <? if($p == "relationship") echo 'class="select"'; else echo 'class="menu"'; ?>>Relationship</a></li>

        <li><a href="index.php?p=micr" <? if($p == "micr") echo 'class="select"'; else echo 'class="menu"'; ?>>MICR</a></li> 

		<li><a href="index.php?p=health" <? if($p == "health") echo 'class="select"'; else echo 'class="menu"'; ?>>Health</a></li>

        </ul>

    </li>









	<li><a href="index.php?p=transaction_list" <? if($p == "transaction_list") echo 'class="select"'; else echo 'class="menu"'; ?>>New Entry List (SICL)</a>

        

    </li>



	<li><a href="index.php?p=transaction_short_list" <? if($p == "transaction_short_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List (SICL) </a>

            

        </li>



<!--	<li><a href="#" target="_self" >Short Premium</a>

        <ul>

        

        <li><a href="index.php?p=transaction_short_list" <? if($p == "transaction_short_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List (SICL) </a>

            

        </li>





		<li><a href="index.php?p=transaction_short_list_gtfs" <? if($p == "transaction_short_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List (GTFS) </a>

            

        </li>

       

        </ul>

    </li> -->







	<li><a href="#" target="_self">Renewal</a>

        <ul>

        

        <li><a href="index.php?p=renewal_list" <? if($p == "renewal_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (SICL)</a>

            

        </li>





		<li><a href="index.php?p=renewal_list_gtfs" <? if($p == "renewal_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (GTFS)</a>

            

        </li>



		<li><a href="index.php?p=direct_renewal_list" <? if($p == "direct_renewal_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (SICL)</a>

            

        </li>





		<li><a href="index.php?p=direct_renewal_list_gtfs" <? if($p == "direct_renewal_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (GTFS)</a>

            

        </li>

       

        </ul>

    </li>



















<li><a href="#" target="_self" >Renewal Short Premium</a>

        <ul>

        

        <li><a href="index.php?p=renewal_short_list" <? if($p == "renewal_short_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium List (SICL)</a>

            

        </li>





		<li><a href="index.php?p=renewal_short_list_gtfs" <? if($p == "renewal_short_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium List (GTFS) </a>

            

        </li>

       

        </ul>

    </li>





	<li><a href="#" target="_self" >PIS</a>

        <ul>

        

        <li><a href="#" target="_self" >PIS (SICL) </a>

            <ul>

            <li><a href="index.php?p=pis_admin" <? if($p == "pis_admin") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS New (SICL)</a>

            <li><a href="index.php?p=pis_admin_sicl_renewal" <? if($p == "pis_admin_sicl_renewal") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal (SICL)</a>

            <li><a href="index.php?p=pis_sp_admin" <? if($p == "pis_sp_admin") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Short Premium (SICL)</a>

			<li><a href="index.php?p=pis_sp_renewal_admin" <? if($p == "pis_sp_renewal_admin") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal Short Premium (SICL)</a>

            </ul>

        </li>





		<li><a href="#" target="_self" >PIS (GTFS) </a>

            <ul>

            <li><a href="index.php?p=pis_admin_gtfs_renewal" <? if($p == "pis_admin_gtfs_renewal") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal (GTFS)</a>

            

			<li><a href="index.php?p=pis_sp_renewal_admin_gtfs" <? if($p == "pis_sp_renewal_admin_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal Short Premium (GTFS)</a>

            </ul>

        </li>

		</ul>

    </li>



		<li><a href="#" target="_self" >Deleted List</a>

        <ul>

        <li><a href="index.php?p=deleted_transaction" <? if($p == "deleted_transaction") echo 'class="select"'; else echo 'class="menu"'; ?>>Deleted Transaction (SICL New)</a></li>

        <li><a href="index.php?p=deleted_sp" <? if($p == "deleted_sp") echo 'class="select"'; else echo 'class="menu"'; ?>>Deleted Short Premium (SICL)</a></li>

        

		<li><a href="index.php?p=deleted_r" <? if($p == "deleted_r") echo 'class="select"'; else echo 'class="menu"'; ?>>Deleted Renewal (SICL)</a></li>

        <li><a href="index.php?p=deleted_r_gtfs" <? if($p == "deleted_r_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Deleted Renewal (GTFS)</a></li>

        <li><a href="index.php?p=deleted_r_sp" <? if($p == "deleted_r_sp") echo 'class="select"'; else echo 'class="menu"'; ?>>Deleted Renewal Short Premium (SICL)</a></li>

		<li><a href="index.php?p=deleted_r_sp_gtfs" <? if($p == "deleted_r_sp_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Deleted Renewal Short Premium (GTFS)</a></li>



		<li><a href="index.php?p=deleted_direct_r" <? if($p == "deleted_direct_r") echo 'class="select"'; else echo 'class="menu"'; ?>>Deleted Direct Renewal (SICL)</a></li>

		<li><a href="index.php?p=deleted_direct_r_gtfs" <? if($p == "deleted_direct_r_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Deleted Direct Renewal (GTFS)</a></li>

        

        </ul>

    </li>

       

        



    



			<?php	

			}

			

			if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '2')) // Admin

			{

			?>



			







	<li><a href="index.php?p=transaction_list" <? if($p == "transaction_list") echo 'class="select"'; else echo 'class="menu"'; ?>>New Entry List (SICL)</a>

        

    </li>



	

        

        <li><a href="index.php?p=transaction_short_list" <? if($p == "transaction_short_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List (SICL) </a>

            

        </li>





		







	<li><a href="#" target="_self">Renewal</a>

        <ul>

        

        <li><a href="index.php?p=renewal_list" <? if($p == "renewal_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (SICL)</a>

            

        </li>





		<li><a href="index.php?p=renewal_list_gtfs" <? if($p == "renewal_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (GTFS)</a>

            

        </li>

       

        </ul>

    </li>

<li><a href="#" target="_self">Direct Renewal</a>

        <ul>

        

        <li><a href="index.php?p=direct_renewal_list" <? if($p == "direct_renewal_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (SICL)</a>

            

        </li>





		<li><a href="index.php?p=direct_renewal_list_gtfs" <? if($p == "direct_renewal_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (GTFS)</a>

            

        </li>

       

        </ul>

    </li>





<li><a href="#" target="_self" >Renewal Short Premium</a>

        <ul>

        

        <li><a href="index.php?p=renewal_short_list" <? if($p == "renewal_short_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium List (SICL)</a>

            

        </li>





		<li><a href="index.php?p=renewal_short_list_gtfs" <? if($p == "renewal_short_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium List (GTFS) </a>

            

        </li>

       

        </ul>

    </li>





	<li><a href="#" target="_self" >PIS</a>

        <ul>

        

        <li><a href="#" target="_self" >PIS (SICL) </a>

            <ul>

            <li><a href="index.php?p=pis_admin" <? if($p == "pis_admin") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS New (SICL)</a>

            <li><a href="index.php?p=pis_admin_sicl_renewal" <? if($p == "pis_admin_sicl_renewal") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal (SICL)</a>

            <li><a href="index.php?p=pis_sp_admin" <? if($p == "pis_sp_admin") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Short Premium (SICL)</a>

			<li><a href="index.php?p=pis_sp_renewal_admin" <? if($p == "pis_sp_renewal_admin") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal Short Premium (SICL)</a>

            </ul>

        </li>





		<li><a href="#" target="_self" >PIS (GTFS) </a>

            <ul>

            <li><a href="index.php?p=pis_admin_gtfs_renewal" <? if($p == "pis_admin_gtfs_renewal") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal (GTFS)</a>

           

			<li><a href="index.php?p=pis_sp_renewal_admin_gtfs" <? if($p == "pis_sp_renewal_admin_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal Short Premium (GTFS)</a>

            </ul>

        </li>

		</ul>

    </li>



		

       

				

			



<?php	

			}

			

			if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '6')) // Admin

			{

			?>



			







	<li><a href="index.php?p=transaction_list" <? if($p == "transaction_list") echo 'class="select"'; else echo 'class="menu"'; ?>>New Entry List (SICL)</a>

        

    </li>



	

        <li><a href="index.php?p=transaction_short_list" <? if($p == "transaction_short_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List (SICL) </a>

            

        </li>





		

       

       







	<li><a href="#" target="_self">Renewal</a>

        <ul>

        

        <li><a href="index.php?p=renewal_list" <? if($p == "renewal_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (SICL)</a>

            

        </li>





		<li><a href="index.php?p=renewal_list_gtfs" <? if($p == "renewal_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (GTFS)</a>

            

        </li>

       

        </ul>

    </li>

<li><a href="#" target="_self">Direct Renewal</a>

        <ul>

        

        <li><a href="index.php?p=direct_renewal_list" <? if($p == "direct_renewal_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (SICL)</a>

            

        </li>





		<li><a href="index.php?p=direct_renewal_list_gtfs" <? if($p == "direct_renewal_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (GTFS)</a>

            

        </li>

       

        </ul>

    </li>





<li><a href="#" target="_self" >Renewal Short Premium</a>

        <ul>

        

        <li><a href="index.php?p=renewal_short_list" <? if($p == "renewal_short_list") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium List (SICL)</a>

            

        </li>





		<li><a href="index.php?p=renewal_short_list_gtfs" <? if($p == "renewal_short_list_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium List (GTFS) </a>

            

        </li>

       

        </ul>

    </li>





	



				

			<?php

			}

			

			

			if(isset($_SESSION[ROLE_ID]) && $_SESSION[ROLE_ID] == '3') // Hub

			{

			?>

			<!--################last update###################-->

			



	<!--<li><a href="#" target="_self" >HUB</a>

        <ul>

        <li><a href="index.php?p=transaction_list_hub" <? if(($p == "transaction_list_hub") || ($p == "view_branches")) echo 'class="select"'; else echo 'class="menu"'; ?>>HUB List</a></li>

        <li><a href="index.php?p=hub_mis" <? if($p == "hub_mis") echo 'class="select"'; else echo 'class="menu"'; ?>>HUB MIS</a></li>

        <li><a href="index.php?p=renewal_list_hub" <? if($p == "renewal_list_hub") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List HUB (SICL)</a></li>

		<li><a href="index.php?p=renewal_hub_mis" <? if($p == "renewal_hub_mis") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List HUB MIS (SICL)</a></li>

		<li><a href="index.php?p=renewal_list_hub_gtfs" <? if($p == "renewal_list_hub_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List HUB (GTFS)</a></li>

		<li><a href="index.php?p=renewal_hub_mis_gtfs" <? if($p == "renewal_hub_mis_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List HUB MIS (GTFS)</a></li>







		<li><a href="index.php?p=direce_renewal_list_hub" <? if($p == "direce_renewal_list_hub") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List HUB (SICL)</a></li>

		

		<li><a href="index.php?p=direce_renewal_list_hub_gtfs" <? if($p == "direce_renewal_list_hub_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List HUB (GTFS)</a></li>

		

        </ul>

    </li>-->



	<li><a href="#" target="_self" >New Entry</a>

        <ul>

        <li><a href="index.php?p=transaction_list_hub" <? if(($p == "transaction_list_hub") || ($p == "view_branches")) echo 'class="select"'; else echo 'class="menu"'; ?>>New Entry List</a></li>

        <li><a href="index.php?p=hub_mis" <? if($p == "hub_mis") echo 'class="select"'; else echo 'class="menu"'; ?>>New Entry List MIS</a></li>



        

		

        </ul>

    </li>

	



	<li><a href="#" target="_self" >Renewal Entry</a>

        <ul>

        

        <li><a href="#" target="_self" >Renewal Entry (SICL) </a>

            <ul>

            <li><a href="index.php?p=renewal_list_hub" <? if($p == "renewal_list_hub") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (SICL)</a></li>

           <li><a href="index.php?p=renewal_hub_mis" <? if($p == "renewal_hub_mis") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List MIS (SICL)</a></li>

            

            </ul>

        </li>





		<li><a href="#" target="_self" >Renewal Entry (GTFS) </a>

            <ul>

            <li><a href="index.php?p=renewal_list_hub_gtfs" <? if($p == "renewal_list_hub_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (GTFS)</a></li>

           <li><a href="index.php?p=renewal_hub_mis_gtfs" <? if($p == "renewal_hub_mis_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List MIS (GTFS)</a></li>

            

            </ul>

        </li>



		<li><a href="#" target="_self" >Direct Renewal Entry</a>

        <ul>

        <li><a href="index.php?p=direce_renewal_list_hub" <? if($p == "direce_renewal_list_hub") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (SICL)</a></li>

        



		<li><a href="index.php?p=direce_renewal_list_hub_gtfs" <? if($p == "direce_renewal_list_hub_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (GTFS)</a></li>

        

		

        </ul>

    </li>



       

        </ul>

    </li>



			

			

			<?php

			}

			if(isset($_SESSION[ROLE_ID]) && ($_SESSION[ROLE_ID] == '4')) // Branch

			{

			?>  



			<li><a href="#" target="_self" >New Business (SZ)</a>

        <ul>

        <li><a href="index.php?p=manual_entry_branch" <? if($p == "manual_entry_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>New Entry</a></li>

        <li><a href="index.php?p=transaction_list_branch" <? if($p == "transaction_list_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>New Entry List</a></li>

        

        </ul>

    </li>



	<li><a href="#" target="_self" >Short Premium (New Business)</a>

        <ul>

        

        <li><a href="#" target="_self" >Short Premium (SICL) </a>

            <ul>

            <li><a href="index.php?p=short_premium_entry" <? if($p == "short_premium_entry") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium Entry (SICL)</a>

            <li><a href="index.php?p=transaction_short_list_branch" <? if($p == "transaction_short_list_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List (SICL)</a>

            

            </ul>

        </li>





		<!--<li><a href="#" target="_self">Short Premium (GTFS) </a>

            <ul>

            <li><a href="index.php?p=short_premium_entry_gtfs" <? if($p == "short_premium_entry_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium Entry (GTFS)</a>

            <li><a href="index.php?p=transaction_short_list_branch_gtfs" <? if($p == "transaction_short_list_branch_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Short Premium List (GTFS)</a>

            

            </ul>

        </li>-->

       

        </ul>

    </li>







	<li><a href="#" target="_self">Renewal</a>

        <ul>

        

        <li><a href="#" target="_self" >Renewal (SICL) </a>

            <ul>

            <li><a href="index.php?p=renewal_entry_branch" <? if($p == "renewal_entry_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Entry (SICL)</a>

            <li><a href="index.php?p=renewal_list_branch" <? if($p == "renewal_list_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (SICL)</a>

            

            </ul>

        </li>





		<li><a href="#" target="_self">Renewal (GTFS) </a>

            <ul>

            <li><a href="index.php?p=renewal_entry_branch_gtfs" <? if($p == "renewal_entry_branch_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Entry (GTFS)</a>

            <li><a href="index.php?p=renewal_list_branch_gtfs" <? if($p == "renewal_list_branch_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal List (GTFS)</a>

            

            </ul>

        </li>

       

        </ul>

    </li>















<li><a href="#" target="_self">Direct Renewal</a>

        <ul>

        

        <li><a href="#" target="_self" >Direct Renewal (SICL) </a>

            <ul>

            <li><a href="index.php?p=direct_renewal_entry" <? if($p == "direct_renewal_entry") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal Entry (SICL)</a>

            <li><a href="index.php?p=direct_renewal_list_branch" <? if($p == "direct_renewal_list_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (SICL)</a>

            

            </ul>

        </li>





		<li><a href="#" target="_self">Direct Renewal (GTFS) </a>

            <ul>

            <li><a href="index.php?p=direct_renewal_entry_gtfs" <? if($p == "direct_renewal_entry_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal Entry (GTFS)</a>

            <li><a href="index.php?p=direct_renewal_list_branch_gtfs" <? if($p == "direct_renewal_list_branch_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Direct Renewal List (GTFS)</a>

            

            </ul>

        </li>

       

        </ul>

    </li>













<li><a href="#" target="_self" >Renewal Short Premium</a>

        <ul>

        

        <li><a href="#" target="_self" >Renewal Short Premium (SICL) </a>

            <ul>

            <li><a href="index.php?p=renewal_short_premium_entry" <? if($p == "renewal_short_premium_entry") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium Entry (SICL)</a>

            <li><a href="index.php?p=renewal_short_list_branch" <? if($p == "renewal_short_list_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium List (SICL)</a>

            

            </ul>

        </li>





		<li><a href="#" target="_self" >Renewal Short Premium (GTFS) </a>

            <ul>

            <li><a href="index.php?p=renewal_short_premium_entry_gtfs" <? if($p == "renewal_short_premium_entry_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium Entry (GTFS)</a>

            <li><a href="index.php?p=renewal_short_list_branch_gtfs" <? if($p == "renewal_short_list_branch_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Renewal Short Premium List (GTFS)</a>

            

            </ul>

        </li>

       

        </ul>

    </li>



<?php if($_SESSION[BRANCH_USER_ID] == 0) { ?>

	<li><a href="#" target="_self" >PIS</a>

        <ul>

        

        <li><a href="#" target="_self" >PIS (SZ) </a>

            <ul>

            <li><a href="index.php?p=pis_branch" <? if($p == "pis_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS New (SZ)</a>

            <li><a href="index.php?p=pis_branch_sicl_renewal" <? if($p == "pis_branch_sicl_renewal") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal (SICL)</a>

            <li><a href="index.php?p=pis_sp_branch" <? if($p == "pis_sp_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Short Premium (SICL)</a>

			<li><a href="index.php?p=pis_sp_renewal_branch" <? if($p == "pis_sp_renewal_branch") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal Short Premium (SICL)</a>

            </ul>

        </li>





		<li><a href="#" target="_self" >PIS (GTFS) </a>

            <ul>

            <li><a href="index.php?p=pis_branch_gtfs_renewal" <? if($p == "pis_branch_gtfs_renewal") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal (GTFS)</a>

            

			<li><a href="index.php?p=pis_sp_renewal_branch_gtfs" <? if($p == "pis_sp_renewal_branch_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>PIS Renewal Short Premium (GTFS)</a>

            </ul>

        </li>



       

        </ul>

    </li>

<?php } ?>

    <li><a href="#" target="_self" >Despatch</a>

        <ul>

        <li><a href="index.php?p=branch_despatch" <? if($p == "branch_despatch") echo 'class="select"'; else echo 'class="menu"'; ?>>Despatch (SICL NEW)</a></li>

        <li><a href="index.php?p=branch_despatch_renewal" <? if($p == "branch_despatch_renewal") echo 'class="select"'; else echo 'class="menu"'; ?>>Despatch Renewal (SICL)</a></li>

        <li><a href="index.php?p=branch_despatch_renewal_gtfs" <? if($p == "branch_despatch_renewal_gtfs") echo 'class="select"'; else echo 'class="menu"'; ?>>Despatch Renewal (GTFS)</a></li>

		

        </ul>

    </li>





			<?php

			}?>

			

					

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

