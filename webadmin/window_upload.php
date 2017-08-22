<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
$msg = '';

$readonly = ''; 
$objDB = new DB();
$pageOwner = "'superadmin','admin','branch','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 
	
// Write functions here

if(isset($_GET['id']) && !empty($_GET['id']))
{
	

	$folio_id = base64_decode($_GET['id']);
		
	if(isset($_POST['signature_hidden']))
	{
		#echo '<pre>';
		#print_r($_POST);
		#echo '</pre>';
		#exit;
		extract($_POST);
		
				$uploads_dir = 'scanned_images';
				$form_image_tmp_name = $_FILES["form_image"]["tmp_name"];
				$form_image_name = time().$_FILES["form_image"]["name"];
				move_uploaded_file($form_image_tmp_name, "$uploads_dir/$form_image_name");

				$signature_image_tmp_name = $_FILES["signature"]["tmp_name"];
				$signature_image_name = time().$_FILES["signature"]["name"];
				move_uploaded_file($signature_image_tmp_name, "$uploads_dir/$signature_image_name");

				
				$folio_query = "UPDATE installment_master SET application_form_image = '".$form_image_name."', signature_image = '".$signature_image_name."' WHERE id='".$folio_id."'";
				//echo $folio_query;
				//exit;

		
				$update_folio = mysql_query($folio_query);

				if(is_file($uploads_dir.'/'.$application_form_hidden) && file_exists($uploads_dir.'/'.$application_form_hidden))
				{
					unlink($uploads_dir.'/'.$application_form_hidden);
				}

				if(is_file($uploads_dir.'/'.$signature_hidden) && file_exists($uploads_dir.'/'.$signature_hidden))
				{
					unlink($uploads_dir.'/'.$signature_hidden);
				}

				#echo $folio_query.'<br />';

				
?>
<script type="text/javascript">
<!--
	//window.opener.document.addForm.submit();
	//window.close();
//-->
</script>

<?php
	}

//$getFolioRecord = findFolioData($folio_id); // THIS ARRAY CONTAIN ALL RECORDS FOR A PARICULAR ID
	//print_r($getFolioRecord);
	#exit;

$getFolioRecord =findRedemptionData($folio_id);
//echo "<pre>";
//print_r($getFolioRecord);
	//$getMasterRecord = findCustomerData($getFolioRecord['customer_id']); // THIS ARRAY CONTAIN ALL RECORDS FOR A PARICULAR ID

	$first_name = $getFolioRecord['first_name'];
	//$middle_name = $getFolioRecord['middle_name'];
	//$last_name = $getFolioRecord['last_name'];
	$application_no = $getFolioRecord['application_no'];
	//$folio_no = $getFolioRecord['folio_no'];
	//$trusted_id = $getMasterRecord['customer_id'];
	$application_form_image = $getFolioRecord['application_form_image'];
	$signature_image = $getFolioRecord['signature_image'];
			
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> Upload Scanned Image </title>
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

<script type="text/javascript">
	//var _validFileExtensions = [".jpg",".gif",".jpeg",".png"];
	var _validFileExtensions = [".pdf"];

	function ValidateFile() {
			var arrInputs = document.addForm.getElementsByTagName("input");
			for (var i = 0; i < arrInputs.length; i++) {
					var oInput = arrInputs[i];
					if (oInput.type == "file") {
							var sFileName = oInput.value;
							if (sFileName.length > 0) {
									var blnValid = false;
									for (var j = 0; j < _validFileExtensions.length; j++) {
											var sCurExtension = _validFileExtensions[j];
											if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toUpperCase() == sCurExtension.toUpperCase()) {
													blnValid = true;
													break;
											}
									}

									if (!blnValid) {
											alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
											//document.getElementById('attachment1Err1').innerHTML = "Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", ");
											return false;
									}
							}
					}
			}

			return true;
	}
	</script>
	
	<script type="text/javascript">
<!--
	function update_trans_id()
	{
		document.getElementById("transaction_id").value = document.getElementById("receipt_number").value;
	}
	function dochk()
	{	
		if(document.addForm.form_image.value.search(/\S/) == -1)
		{
			alert("Please Select The Scanned Image of the Application Form");
			document.addForm.form_image.focus();
			return false;
		}	
		/*if(document.addForm.form_image.value.search(/\S/) != -1)
		{
			var1 = document.addForm.form_image.value.toUpperCase();
				
			//alert(var1);
			//return false;
			var2 = (document.addForm.app_no.value + "_form.pdf").toUpperCase();
			//alert(var1);
			//alert(var2);
			if(var1 != var2)
			{
				alert("Name of the Scanned Image for the Application Form will be " + var2);
				document.addForm.form_image.focus();
				return false;
			}
		}*/

		if(document.addForm.signature.value.search(/\S/) == -1)
		{
			alert("Please Select The Scanned Image of the Signature");
			document.addForm.signature.focus();
			return false;
		}	

		/*if(document.addForm.signature.value.search(/\S/) != -1)
		{
			var1 = document.addForm.signature.value.toUpperCase();
			var2 = (document.addForm.app_no.value + "_sign.pdf").toUpperCase();
			//alert(var1);
			//alert(var2);
			if(var1 != var2)
			{
				alert("Name of the Scanned Image for the Signature will be " + var2);
				document.addForm.signature.focus();
				return false;
			}
		}
*/
		if(!ValidateFile())
		{ 
			//alert('Hi');
			return false;
		}
	}
//-->
</script>

<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
 </head>

 <body>
 <center>
 <div>
 <form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()" enctype="multipart/form-data">	 
	 <table width="750" style="border:0px solid red;">
	 
		 <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>
      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Upload Scanned Image</td>
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
      <td class="tbllogin" valign="top" align="right">Application No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $application_no; ?></td>
    </tr>
		<!--<tr> 
      <td class="tbllogin" valign="top" align="right">Folio No.</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $folio_no; ?></td>
    </tr>
    <tr> 
      <td class="tbllogin" valign="top" align="right">Customer ID</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<?php echo $trusted_id; ?>
			</td>
    </tr>
-->
		<tr> 
      <td class="tbllogin" valign="top" align="right">Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php echo $first_name; ?></td>
    </tr>

		<tr> 
      <td class="tbllogin" valign="top" align="right">Application Form Image (.PDF)<br /><font color="#ff0000">(Format : Appliaction Number._FORM.PDF)</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="file" name="form_image" id="form_image" /></td>
    </tr>
		<?php if(!empty($application_form_image)){ ?>
		<tr>       
      <td valign="top" align="center" colspan="3"><!--<img src="scanned_images/<?php //echo $application_form_image; ?>" alt="No image uploaded" />--><a href="<?php echo URL; ?>webadmin/scanned_images/<?php echo $application_form_image; ?>" >View Application Form Image</a></td>
    </tr>
		<?php } ?>
		
		<tr> 
      <td class="tbllogin" valign="top" align="right">Signature Image (.PDF)<br /><font color="#ff0000">(Format : Appliaction Number._SIGN.PDF)</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input type="file" name="signature" id="signature" /></td>
    </tr>
		<input type="hidden" name="signature_hidden" id="signature_hidden" value="<?php echo $signature_image; ?>">
		<input type="hidden" name="application_form_hidden" id="application_form_hidden" value="<?php echo $application_form_image; ?>">
		
		<?php if(!empty($signature_image)){ ?>
		<tr>       
      <td valign="top" align="center" colspan="3"><!--<img src="scanned_images/<?php echo $signature_image; ?>" alt="No image uploaded" />--><a href="<?php echo URL; ?>webadmin/scanned_images/<?php echo $signature_image; ?>" >View Signature Image</a></td>
    </tr>
		<?php } ?>
    <?php
				//if(($goldRate != 'Not Added to the database') && ($silverRate != 'Not Added to the database') && ($readonly == ''))
				//{
		?>
		<tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
			<input type="hidden" id="app_no" name="app_no" value="<?php echo $getFolioRecord['application_no']; ?>"> 
			<?php if(intval($_SESSION[ROLE_ID]) != 4) { ?>
        <input value="Upload" class="inplogin" type="submit" onclick="return dochk()"> 
			<?php } ?>
				<input value="Close" class="inplogin" type="button" onclick="window.close()"><!-- 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"> --></td>
    </tr>
		<?php
				//}			
		?>
  </tbody>
	 </table>
	 </form>
 </div>
 
 </center>
 </body>
</html>