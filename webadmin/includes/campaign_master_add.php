<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");

date_default_timezone_set('Asia/Calcutta');
$msg = '';
//$PREMIUM_TYPE = 'INITIAL PAYMENT';
$objDB = new DB();
$pageOwner = "'branch'";

//chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

if(isset($_POST['Submit']) && $_POST['Submit'] != '')
{
	extract($_POST);
	
	$query="SELECT 
				count(*) AS total_count 
			FROM 
				t_99_campaign WHERE campaign = '".realTrim($_POST[txt_campaign])."'";	
				
				
	$result=mysql_query($query);			
	$data_count=mysql_fetch_assoc($result);
	if($data_count['total_count']<=0)
	{
		unset($_SESSION[ERROR_MSG]);
		$status=$_POST['chk_active_status'];
		if($status=="")
		{
			$status=0;
		}
		else
		{
			$status=1;
		}
											
								
		$insert_installment = "INSERT INTO t_99_campaign SET 
															hub_id = '".realTrim($_POST['ddl_hub_id'])."',
															branch_id = '".realTrim($_POST['branch_name'])."',
															campaign = '".realTrim($_POST['txt_campaign'])."',
															campaign_code = '".realTrim($_POST['txt_campaign_code'])."',
															campaign_date = '".date('Y-m-d')."',
															active_status = '".realTrim($status)."'";
					
		mysql_query($insert_installment);
		
		header("location:index.php?p=campaign_list");
                    
		//header("location: ".URL.'webadmin/index.php?p=campaign_list');
	}
	else
	{
		$_SESSION[ERROR_MSG] = "Campaign Name already exist !!!.";     
		header("location:index.php?p=campaign_master_add");
        
		//header("location: ".URL.'webadmin/index.php?p=campaign_master_add');
	}
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
       <span style="color:#F00"><strong> <?php echo $_SESSION[ERROR_MSG]; ?></strong></span></td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Add - Campaign</td>
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
		<option value="0">NONE</option>
        <?php 
				$selHubName = mysql_query("select * from admin WHERE (id=189) AND(status=1) ORDER BY name ASC ");   //for Plan dropdown
				
					
						while($getHub = mysql_fetch_array($selHubName))
						{	
												
				?>
        <option value="<?php echo $getHub['id']; ?>" ><?php echo $getHub['branch_name']; ?></option>
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
                        $selBranch = mysql_query("select * from admin WHERE (hub_id!=2) AND (id!=305) AND (name!='admin' && name!='admin11') AND(status=1) ORDER BY name ASC ");   //for Plan dropdown
                                while($getBranch = mysql_fetch_array($selBranch))
                                {	
                                                        
                        ?>
                <option value="<?php echo $getBranch['id']; ?>" ><?php echo $getBranch['name']; ?></option>
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
      <td valign="top" align="left"><input name="txt_campaign" id="txt_campaign" type="text" class="inplogin"  value="" maxlength="50" onKeyUp="this.value = this.value.toUpperCase();" ></td>

    </tr>
    
    <tr> 
      <td class="tbllogin" valign="top" align="right">Campaign Code<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="txt_campaign_code" id="txt_campaign_code" type="text" class="inplogin"  value="" maxlength="50" onkeyup="this.value = this.value.toUpperCase();" /></td>
    </tr>
	
   
       
    <tr>
        <td class="tbllogin" valign="top" align="right" >Active Status</td>
        <td class="tbllogin" valign="top" align="center">:</td>
        <td class="tbllogin" valign="top" align="left"><input type="checkbox" name="chk_active_status" id="chk_active_status" value="1"></td>
    </tr>

    
    <tr> 
     
      <td colspan="3" style="text-align:center">
        <input value="Add" class="inplogin" type="submit" name="Submit" id="addbtn" onclick="return dochk()"> 
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
		if(document.getElementById('branch_name').value =='0' || document.getElementById('branch_name').value =='')
		{
			alert("Please Select Branch !!.");
			document.addForm.branch_name.focus();
			return false;
		}
        if(document.addForm.txt_campaign.value=="")
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
xmlhttp.open("GET","getbranch_campaign.php?hub="+hub,true);
xmlhttp.send();
}

</script>
