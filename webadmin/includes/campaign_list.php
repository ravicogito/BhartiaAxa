<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
//print_r($_POST);
set_time_limit(0); 
//$pageOwner = "'branch'";
$pageOwner = "'superadmin','admin','subadmin','subsuperadmin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); // $USER_TYPE is coming from index.php
#print_r($_POST);
extract($_POST);

unset($_SESSION['branch_id']);
unset($_SESSION['hub_id']);
unset($_SESSION['campaign_code']);
unset($_SESSION['campaign_name']);


if(isset($mode) && $mode == 'del') // delete transaction
{	
	mysql_query("UPDATE t_99_campaign SET deleted=1,deleted_by_date=CURDATE() WHERE campaign_id=".$transaction_id);
	$_SESSION[SUCCESS_MSG] = "Record deleted successfully...";
	header("location: index.php?p=".$_REQUEST['p']."");
	exit();
}
// Write functions here
$objDB = new DB();
$where = " WHERE t_99_campaign.deleted_by_id= '0' ";
$OrderBY = " ORDER BY t_99_campaign.campaign ASC ";
//=======================================================
#############
//print_r($_POST);
$pid 			= loadVariable('pid',0);
	$showAll 		= loadVariable('all',0);
	$searchField 	= loadVariable('searchField','');
	$searchString 	= outputEscapeString(loadVariable('search',''));
	$sortField 		= loadVariable('sf','name');
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
	##############
	##### CODE FOR SEARCHING 
	if(isset($branch_name))
	{
		$_SESSION['branch_id'] = realTrim($branch_name);
	}
	
	if(isset($hub_id))
	{
		$_SESSION['hub_id'] = realTrim($hub_id);
	}
	if(isset($campaign_code))
	{
		$_SESSION['campaign_code'] = realTrim($campaign_code);
	}
	if(isset($campaign_name))
	{
		$_SESSION['campaign_name'] = realTrim($campaign_name);
	}
	
	if(isset($_SESSION['hub_id']) && $_SESSION['hub_id'] != '0') 
	{
		$where.= ' AND t_99_campaign.hub_id = "'.$_SESSION['hub_id'].'"';
	}
	if(isset($_SESSION['branch_id']) && $_SESSION['branch_id'] != '') 
	{
		$where.= ' AND t_99_campaign.branch_id = "'.$_SESSION['branch_id'].'"';
	}
	if(isset($_SESSION['campaign_code']) && $_SESSION['campaign_code'] != '') 
	{
		$where.= ' AND t_99_campaign.campaign_code = "'.$_SESSION['campaign_code'].'"';
	}
	if(isset($_SESSION['campaign_name']) && $_SESSION['campaign_name'] != '') 
	{
		$where.= ' AND  t_99_campaign.campaign ="'.$_SESSION['campaign_name'].'"';	
	}
	
	
	##### CODE FOR SEARCHING 
$Query = "SELECT t_99_campaign.*,admin.name FROM t_99_campaign INNER JOIN admin ON t_99_campaign.branch_id=admin.id  ".$where;

$rs_Total=mysql_query($Query);
$displayTotal=mysql_num_rows($rs_Total);
//echo $Query;
//exit;
//$objDB->setQuery($Query);
//$rsTotal = $objDB->select();
//$displayTotal=$rsTotal[0]['CNT'];
$extraParam = "&p=".$GLOBALS['p'];

$dpp = true;
$totalRecordCount = $displayTotal;
$pageSegmentSize = 15;

include_once("../utility/pagination.php");

$Query = "SELECT t_99_campaign.*,admin.name FROM t_99_campaign INNER JOIN admin ON t_99_campaign.branch_id=admin.id  ".$where.$OrderBY.$Limit;
//echo $Query;
//exit;
//$objDB->setQuery($Query);
$result_campaign=mysql_query($Query);

$pageRecordCount = mysql_num_rows($result_campaign);
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
//$selTransaction = mysql_query("SELECT t_99_campaign.*,admin.name FROM t_99_campaign INNER JOIN admin ON t_99_camoaign.branch_id=admin.id WHERE t_99_campaign.deleted=0 ORDER BY t_99_campaign.campaign DESC");
//$numTransaction = mysql_num_rows($selTransaction);
	
/*
echo "<pre>";
print_r($_POST);
die();
*/
?>
<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
<script type="text/javascript">
<!--
	function confirmDelete(ID)
	{
			if(confirm('Transaction will be deleted.\nAre you sure ? '))
			{
				
				document.sortFrm.mode.value='del';
				document.sortFrm.transaction_id.value=ID;
				document.sortFrm.submit();
			}
	}
	function dppList(dpp)
	{
		//alert(dpp1);
		document.sortFrm.dpp.value = dpp;
		document.sortFrm.pg.value= 1;
		document.sortFrm.submit();
		return true;
	}
//-->
</script>
<script>	
function getbranch(hub)
{
//alert(hub);
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
	//alert(hub);
  if (xmlhttp.readyState==4)
    {
	 //alert(xmlhttp.responseText);
	document.getElementById("branch1").innerHTML=xmlhttp.responseText;
   }
  }
xmlhttp.open("GET","getbranch.php?hub="+hub,true);
xmlhttp.send();
}

</script>
<form name="addForm" id="addForm" action="" method="post" style="border:0px solid red">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="26">
        <? showMessage(); ?>
      </td>
    </tr>
		<tr> 
      <td colspan="26" align="right">
        <a title=" Export to Excel " href="<?=URL?>campaign_excel_admin.php" ><img src="images/excel_icon.gif" border="0" title="Download" /></a>
      </td>
    </tr>
	<tr> 
      <td colspan="26" align="left">
				<table>
					<tr>
						<td width="150"><strong>Hub</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
					<select name="hub_id" id="hub_id" class="inplogin"  style="width:200px;" onchange="getbranch(this.value);">
                        <option value="0">Select All</option>
                        <?php 
						
						$selHubName = "select * from admin WHERE (id=189) AND (name!='admin' && name!='admin11') ORDER BY name ASC ";   //for Hub dropdown
						//$selHubName.="";
						$result_hub=mysql_query($selHubName);
						while($getHub = mysql_fetch_array($result_hub))
						{	
                        ?>
                        	<option value="<?php echo $getHub['id']; ?>" <?php if($getHub['id']  == $_SESSION['hub_id']) { ?> selected="selected"<?php }?>><?php echo $getHub['branch_name']; ?></option>
                        <?php
                        }
                        ?>
                      </select>
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Branch</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
                        <div id="branch1">
                    		<select name="branch_name" id="branch_name" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
							<?php  
							
								$branch_sql = 'select * from admin where  role_id = 4';
								//echo $branch_sql;
								$branch_query = mysql_query($branch_sql);
								$branch_num_row = mysql_num_rows($branch_query);
								
								while($brancharr = mysql_fetch_array($branch_query))
								{
								?>
									<option value="<?php echo $brancharr['id']; ?>" <?php if($brancharr['id']  == $_SESSION['branch_id']){ ?> selected="selected"<?php }?>><?php echo $brancharr['branch_name']; ?></option> 
								<?php 
								}
								?>
							
							</select>
                          </div>                          
						</td>
					</tr>
					<tr>
					
					<tr>
						<td width="150"><strong>Campaign</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="campaign_name" id="campaign_name" type="text" class="inplogin" value="<?php echo (isset($_SESSION['campaign_name']) ? $_SESSION['campaign_name'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr>
					<tr>
						<td width="150"><strong>Campaign Code</strong></td>
						<td width="20"><strong>:</strong></td>
						<td width="150">
							<input name="campaign_code" id="campaign_code" type="text" class="inplogin" value="<?php echo (isset($_SESSION['campaign_code']) ? $_SESSION['campaign_code'] : ''); ?>" maxlength="100" /> 
						</td>
					</tr>
					
					<tr>
						<td colspan="3" align="center"><input type="submit" name="btnSubmit" value="Search">&nbsp;<?php if(isset($_POST) && (count($_POST)) > 0){ ?><!-- <a href="xml_download.php" style="text-decoration:none;" ><input type="button" name="btnXML" value="Download XML"> --></a><?php } ?></td>
						
					</tr>
				</table>
        
      </td>
    </tr>
		<tr> 
      <td colspan="26" align="right">
  <br />
<TABLE cellSpacing=1 cellPadding=5 width="100%" align=center border=0>
  <TBODY>
        <TR>
          
      <TD width="93%" align=left valign="middle" ><?php include_once("../utility/pagination_display.php");?></TD>
          <TD width="5%" align=right><A title=" Refresh the page" href="javascript:void(0)" onClick="javascript:window.location.href='index.php?p=campaign_list&mid=<?=$mid?>&pid=<?=$pid?>&all=1';"><IMG src="images/icon_reload.gif" border=0></A></TD>
          
    
		</TR>
</TBODY>
</TABLE>



      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="26">Transaction Listing Of Campaign</td>
    </tr>
    
    <tr> 
                        <td width="8%" align="center" valign="top" class="tbllogin">Sl#</td>

			<td width="8%" align="center" valign="top" class="tbllogin">Hub</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Branch</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Campaign</td>
                        <td width="8%" align="center" valign="top" class="tbllogin">Campaign Code</td>
			<td width="8%" align="center" valign="top" class="tbllogin">Status</td>

			<td width="8%" align="center" valign="top" class="tbllogin">Edit</td>
    </tr>
<?php
	if($pageRecordCount > 0)
	{
		$icount=1;
		while($data_campaign=mysql_fetch_assoc($result_campaign)) 
		{ 

?>
	<tr> 
      <td width="8%" align="center" valign="top" ><?php echo $icount; ?></td>
	  
			<td width="8%" align="center" valign="top" >
			<?php echo find_branch_name($data_campaign['hub_id']); ?>						
			</td>

			<td width="8%" align="center" valign="top" >
			<?php echo $data_campaign['name']; ?><br />						
									
			</td>
             <td width="8%" align="center" valign="top" ><?php echo $data_campaign['campaign']; ?></td>
             <td width="8%" align="center" valign="top" ><?php echo $data_campaign['campaign_code']; ?></td>
			<td width="8%" align="center" valign="top" >
					<?php 
					if($data_campaign['active_status']==0)
					{
						echo "In Active";
					}
					else
					{
						echo "Active";
					}
					?>
            
			</td>
			<td width="8%" align="center" valign="top" >			
			<a href="javascript:void(0)" onclick="javascript:popUp('<?php echo URL; ?>webadmin/campaign_master_edit.php?ID=<?php echo $data_campaign['campaign_id']; ?>')">Edit</a>
			</td>
			
    </tr>
<?php
$icount++;		
		}
	}
?>
    
  </tbody>
</table>
</form>
<form name="sortFrm" action="" method="post">
	<input type="hidden" name="transaction_id" value="">
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
<SCRIPT LANGUAGE="JavaScript">
<!-- Idea by:  Nic Wolfe -->
<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=1,resizable=1,width=800,height=600,left = 112,top = 84');");
}
// End -->
</script>
<?php //$objDB->close(); ?>
