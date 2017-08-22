<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");


$msg = '';
//if(!isset($_SESSION[ADMIN_SESSION_VAR]) || $_SESSION[ADMIN_SESSION_VAR] != 2)
//{
//	//echo 'Hi';
//	header("location: index.php");
//	exit();
//}

$objDB = new DB();

$pageOwner = "'superadmin','admin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner);


// Write functions here


if(isset($_GET['id']) && !empty($_GET['id']))
{
	

	$user_id = base64_decode($_GET['id']);
	//echo $user_id;
	if(isset($_POST['hub_name']) && $_POST['hub_name'] != '')
	{
		//echo '<pre>';
		//print_r($_POST);
		//echo '</pre>';
		extract($_POST);
		
		if($hub_name == '0') // Becomes ordinary branch
		{
			
			
			$selBranches = mysql_query("SELECT id FROM admin WHERE hub_id=".$user_id." ORDER BY id DESC");
			if(mysql_num_rows($selBranches) > 0)
			{
				while($getBranches = mysql_fetch_array($selBranches))
				{
					$old_branch_id = $getBranches['id'];
					$update_hub = "INSERT INTO branch_hub_entry SET 										
												branch_id = '".$old_branch_id."',
												hub_id = 2,
												hub_since = '".date('Y-m-d')."'
					";
					//echo $update_installment;
					mysql_query($update_hub); // for history
					
				}
				mysql_query("UPDATE admin SET hub_id=2 WHERE hub_id='".$user_id."'"); // admin becomes new hub for these branches
			}
			mysql_query("UPDATE admin SET role_id=4, hub_id=2 WHERE id='".$user_id."'"); // hub admin is Converted to branch admin
			mysql_query("UPDATE admin SET role_id=4, hub_id=2 WHERE branch_user_id='".$user_id."'"); // Hub users become branch users
		}
		else // Becomes HUB
		{
			mysql_query("UPDATE admin SET role_id=3, hub_id='".$user_id."' WHERE id='".$user_id."'"); // Converted to hub
			
			$update_hub = "INSERT INTO branch_hub_entry SET 										
										branch_id = '".$user_id."',
										hub_id = '".$user_id."',
										hub_since = '".date('Y-m-d')."'
				";
				//echo $update_installment;
				mysql_query($update_hub);

				$selBranches = mysql_query("SELECT id FROM admin WHERE branch_user_id=".$user_id." ORDER BY id DESC");
				if(mysql_num_rows($selBranches) > 0)
				{
					while($getBranches = mysql_fetch_array($selBranches))
					{
						$old_branch_id = $getBranches['id'];
						$update_hub = "INSERT INTO branch_hub_entry SET 										
													branch_id = '".$old_branch_id."',
													hub_id = '".$user_id."',
													hub_since = '".date('Y-m-d')."'
						";
						//echo $update_installment;
						mysql_query($update_hub); // for history

					}
					
				}

				mysql_query("UPDATE admin SET role_id=3, hub_id='".$user_id."' WHERE branch_user_id='".$user_id."'"); // Branch users become Hub users
		}

?>
<script type="text/javascript">
<!--
	window.opener.document.sortFrm.submit();
	window.close();
//-->
</script>

<?php
	}
	
	$hub_status = find_hub_status($user_id);
	#echo $hub_status;

	$branch_name = find_branch_name($user_id);

	$hubID = find_hub_id($user_id);
	

}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Edit Hub Status</title>
  <meta name="Generator" content="">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
	
	<link rel="shortcut icon" type="image/x-icon" href="<?=URL?>images/favicon.ico">
<link rel="stylesheet" href="<?=URL?>webadmin/css/default.css">
<link rel="stylesheet" href="<?=URL?>webadmin/css/dropdown.css">
<link rel="stylesheet" href="<?=URL?>css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?=URL?>css/template.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<script src="<?=URL?>js/jquery.min.js" type="text/javascript"></script>
<script src="<?=URL?>js/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="<?=URL?>js/jquery.validationEngine.js" type="text/javascript"></script>
<script>	
		$(document).ready(function() {
			$("#frmadminform").validationEngine()
		});
		
		// JUST AN EXAMPLE OF CUSTOM VALIDATI0N FUNCTIONS : funcCall[validate2fields]
		function validate2fields(){
			if($("#firstname").val() =="" ||  $("#lastname").val() == ""){
				return false;
			}else{
				return true;
			}
		}
	</script>

<style type="text/css">
body{
	font-family:Arial, Verdana, Helvetica, sans-serif; font-size:12px; font-weight:normal;
	color:#404040; text-decoration:none;
	text-align:justify;
	background:url(images/adminbg.gif) repeat-x 0 0; margin:0 auto;
	}
	
.insideBORDER{

	border: solid 1px #CCCCCC;

}
/*################ Style Css Use in hotelTabMenu ################*/
.hotelTabMenu a{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; color:#666666; text-decoration:none; height:24px; padding:0px 10px 0px 10px; background:#EFEFEF; display:block; line-height:24px; border:solid 1px #CBCBCB;}
.hotelTabMenu a:hover{ font-weight:bold; color:#000; text-decoration:none; background:#fff; border-bottom:0px;}

	 
.hotelTabSelect{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; background:#FFF; color:#000; height:24px; line-height:24px; text-decoration:none; padding:0px 10px 0px 10px; display:block; border-top:solid 1px #CBCBCB; border-left:solid 1px #CBCBCB; border-right:solid 1px #CBCBCB; border-bottom:0px;}

.hotelTabSelect a{ font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; background:#FFF; color:#000; height:24px; line-height:24px; text-decoration:none; padding:0px 10px 0px 10px; display:block;}
</style>	

	
<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
 </head>

 <body>
 <center>
 <div>
 <form name="addForm" id="addForm" action="" method="post" >
	 
	 <table width="750" style="border:0px solid red;">
	 
		 <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Update Hub for a Branch</td>
    </tr>
		
		<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Branch Name</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top"><?php echo $branch_name; ?></td>
    </tr>
		<tr> 
      <td class="tbllogin" valign="top" align="right">HUB Status</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="hub_name" id="" class="inplogin_select">
					<option value="0" <?php echo ($hub_status == '4' ? 'selected' : ''); ?>>No</option>
					<option value="1" <?php echo ($hub_status != '4' ? 'selected' : ''); ?>>Yes</option>				
				</select>
			</td>
    </tr>
    
		

    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Update" class="inplogin" type="submit" >
				<input type="button" class="inplogin" name="btnCancel" value="Cancel" onclick="javascript:window.close()"></td>
    </tr>
  </tbody>
	 </table>
	 </form>
 </div>
 
 </center>
 </body>
</html>