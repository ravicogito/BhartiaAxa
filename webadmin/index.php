<?php

include("../utility/config.php");
include("../utility/dbclass.php");
include("../utility/functions.php");
//error_reporting(0);
$objDB = new DB();
$p=loadVariable('p','home');
checkLogout();
$p = securityCheck($p);
//echo "hi - ".$p;
$page = '';
if(!file_exists('includes/'.$p.'.php')){
	$page = 'page_error.php';
}
else {
	$page = $p.'.php';
    $middleWidth = '1003';
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=COMPANY_NAME?>&nbsp;Admin Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<!--forcalender-->

<!--forcalender-->
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
</head>

<body>

<table width="100%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/adminbg.gif) repeat-x 0 0; margin:0 auto;">
 <? if(isset($_SESSION[ADMIN_SESSION_VAR])) { ?>
  <tr align="left" valign="top"> 

    <td height="85" > 

      <? include_once('includes/header.php')?>

    </td>

  </tr>
<? }?>
  <tr align="left" valign="top"> 
	<?
	$style ="";?>
    <? if(isset($_SESSION[ADMIN_SESSION_VAR])) {

	$middleWidth = '100%';
	$style  = 'style="background:#FCFCFC;"';
	?>



    <? } ?>

	<td width="<?=$middleWidth?>" <?=$style?>> 
	<table cellpadding="5" cellspacing="0" width="96%"  align="center">
		<tr>
			<td align="center" valign="top">
			 
				      <?php include_once('includes/'.$page); ?>
			</td>
		</tr>
	</table>
    </td>

  </tr>
 <? if(isset($_SESSION[ADMIN_SESSION_VAR])) { ?>

  <tr align="left" valign="middle" style="background:#0069C7; color:#FFFFFF;"> 

    <td height="35" > 

      <? include_once('includes/footer.php')?>

    </td>

  </tr>
<? } ?>
</table>

</body>

</html>

<?php ob_end_flush(); $objDB->close(); ?>

