<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
date_default_timezone_set('Asia/Calcutta');
$msg = '';
//$PREMIUM_TYPE = 'INITIAL PAYMENT';
$objDB = new DB();
$pageOwner = "'Campaign'";
//chkPageAccess($_SESSION[ROLE_ID], $pageOwner);

if(@$_GET['ID'])
{
	$campaign_id=$_GET['ID'];
	$query="SELECT * FROM t_99_campaign WHERE campaign_id=$campaign_id";
	$result_campaign=mysql_query($query);
	$data_campaign=mysql_fetch_assoc($result_campaign);
}


if(@$_POST['Submit'])
{
	
	extract($_POST);
	
	$status=$_POST['chk_active_status'];
	if($status=="")
	{
		$status=0;
	}
	else
	{
		$status=1;
	}
	                                    
	                            
	$update_installment=" UPDATE t_99_campaign SET 
														hub_id = '".$_POST['ddl_hub_id']."',
														branch_id = '".$_POST['branch_name']."',
														campaign = '".$_POST['txt_campaign']."',
														campaign_code = '".$_POST['txt_campaign_code']."',
														active_status = '".$status."'
													WHERE
														(campaign_id=$campaign_id)";
	
	mysql_query($update_installment);
	?>
    <script type="text/javascript">
<!--
	window.opener.document.addForm.submit();
	window.close();
//-->
</script>

    <?php
	//header("location: ".URL.'webadmin/index.php?p=list_campaign');
			
}

?>

<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
<form name="addForm" id="addForm" action="" method="post" onsubmit="return chk_validation()">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Manual Entry (Campaign)</td>
    </tr>
		
    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
        * marked fields are mandatory</font></b></td>
    </tr>
		
		<?php
			if($msg != '')
			{
		?>
		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000"><?php echo $msg; ?></font></b></td>
    </tr>
		<?php
			}
		?>
    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">Preliminary Entry</font></b></td>
    </tr>
    
    <tr>
      <td class="tbllogin" valign="top" align="right">HUB </td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
      <select name="ddl_hub_id" id="ddl_hub_id" class="inplogin"  style="width:200px;" onchange="getbranch(this.value);">
		<option value="1">NONE</option>
        <?php 
				$selHubName = mysql_query("select * from admin WHERE (id=189) ORDER BY name ASC ");   //for Plan dropdown
				
					
						while($getHub = mysql_fetch_array($selHubName))
						{	
												
				?>
        					<option value="<?php echo $getHub['id']; ?>" <?php if($getHub['id']==$data_campaign['hub_id']){ ?> selected="selected"<?php }?>><?php echo $getHub['branch_name']; ?></option>
        <?php
						}
					
				?>
      </select>
      </td>
    </tr>
    
    <tr> 
      <td class="tbllogin" valign="top" align="right">Branch<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
      <div id="branch1">
          <select name="branch_name" id="branch_name" class="inplogin"  style="width:200px;">
                <option value="0">NONE</option>
                <?php 
                        $selBranch = mysql_query("select * from admin WHERE (hub_id!=2) AND (id!=305) AND (name!='admin' && name!='admin11') AND (hub_id=$data_campaign[hub_id]) ORDER BY name ASC ");   //for Plan dropdown
                                while($getBranch = mysql_fetch_array($selBranch))
                                {	
                                                        
                        ?>
                <option value="<?php echo $getBranch['id']; ?>" <?php if($getBranch['id']==$data_campaign['branch_id']){ ?> selected="selected"<?php }?>><?php echo $getBranch['name']; ?></option>
                <?php
                                }
                            
                        ?>
              </select>	
       </div>       		
      </td>
    </tr>
	
    <tr> 
      <td class="tbllogin" valign="top" align="right">Campaign<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="txt_campaign" id="txt_campaign" type="text" class="inplogin"  value="<?php echo $data_campaign['campaign']; ?>" maxlength="50" onKeyUp="this.value = this.value.toUpperCase();" ></td>

    </tr>
    
    <tr> 
      <td class="tbllogin" valign="top" align="right">Campaign Code<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="txt_campaign_code" id="txt_campaign_code" type="text" class="inplogin"  value="<?php echo $data_campaign['campaign_code']; ?>" maxlength="50" onkeyup="this.value = this.value.toUpperCase();" /></td>
    </tr>
	
   
       
    <tr>
        <td class="tbllogin" valign="top" align="right" >Active Status</td>
        <td class="tbllogin" valign="top" align="center">:</td>
        <td class="tbllogin" valign="top" align="left"><input type="checkbox" name="chk_active_status" id="chk_active_status" value="1" <?php if($data_campaign['active_status']==1){ ?>checked<?php }?>></td>
    </tr>

    
    <tr> 
     
      <td colspan="3" style="text-align:center">
        <input value="Update" class="inplogin" type="submit" name="Submit" id="addbtn" onclick="return dochk()"> 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
    </tr>
  </tbody>
</table>
</form>
<?php //$objDB->close(); ?>
<script>
   	function chk_validation()
	{
        if(document.getElementById('ddl_hub_id').value == '0')
		{
			alert("Please Select Hub !!.");
			document.addForm.ddl_hub_id.focus();
			return false;
		}
		if(document.getElementById('branch_name').value == '0' || document.getElementById('branch_name').value =='')
		{
			alert("Please Select Branch !!.");
			document.addForm.branch_name.focus();
			return false;
		}
        if(document.addForm.txt_campaign.value== "")
		{
			alert("Please Enter Campaign.");
			document.addForm.txt_campaign.focus();
			return false;
		}

        if(document.addForm.txt_campaign_code.value=="")
		{
			alert("Please Enter Campaign Code.");
			document.addForm.txt_campaign_code.focus();
			return false;
		}
        
                
                
	}

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
