<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");

$msg = '';

$objDB = new DB();

$pageOwner = "'superadmin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 	

// Write functions here

$app_id = base64_decode($_GET['id']); 
	
	if(isset($_POST['charge']) && !empty($_POST['charge']))
	{
		extract($_POST);
		

		//$update_folio = "UPDATE  customer_folio_no SET application_no = '".$charge."' WHERE id = '".$app_id."'";
		$update_installment = "UPDATE installment_master SET sum_assured = '".$charge."' WHERE id = '".$app_id."'";

		//$objDB->setQuery($update_folio);
		//$rs1 = $objDB->update();

		$objDB->setQuery($update_installment);
		$rs2 = $objDB->update();

		
?>
<script type="text/javascript">
<!--
	window.opener.document.addForm.submit();
//parent.parent.location.reload(1);
	//window.opener.location.reload(1);
	window.close();
//-->
</script>

<?php
	}

	$selTransaction = "SELECT sum_assured FROM installment_master WHERE id='".$app_id."'";
	$objDB->setQuery($selTransaction);
	$rs = $objDB->select();

	//print_r($rs);
	$value = $rs[0]['sum_assured'];
	//exit;
	
	//$numTransaction = mysql_num_rows($selTransaction);
	//if($numTransaction > 0)
		//{
			//$getTransaction = mysql_fetch_assoc($selTransaction);
			//$value = $getTransaction['value'];

		//}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Edit Sum Assured </title>
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

<script type="text/javascript">
<!--
	function dochk()
	{	
		if(document.addForm.charge.value.search(/\S/) == -1)
		{
			alert("Please choose Term.");
			document.addForm.charge.focus();
			return false;
		}		
		
	}
//-->
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
 <form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()">
	 
	 <table width="750" style="border:0px solid red;">
	 
		 <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Update Sum Assured</td>
    </tr>
		
	<tr> 
      <td width="27%" align="right" valign="top" class="tbllogin">Sum Assured</td>
      <td width="5%" align="center" valign="top" class="tbllogin">:</td>
      <td width="68%" align="left" valign="top">
	 <select name="charge" id="" class="inplogin_select" style="width:140px;">
								<option value="">Select All</option>
							<?php 
								$selBranch = mysql_query("SELECT sum_assured, id FROM sum_assured_master  ORDER BY sum_assured ASC");
								while($getBranch = mysql_fetch_array($selBranch))
								{					
							?>
								<option value="<?php echo $getBranch['sum_assured'];?>" <?php echo (isset( $value) && ($value  == $getBranch['sum_assured']) ? 'selected' : ''); ?>><?php echo $getBranch['sum_assured'];?></option>
							<?php } ?>
							</select>
	  </td>
    </tr>

    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Update" class="inplogin" type="submit" onclick="return dochk()"></td>
    </tr>
  </tbody>
	 </table>
	 </form>
 </div>
 
 </center>
 </body>
</html>