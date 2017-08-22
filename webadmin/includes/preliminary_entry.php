<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");

date_default_timezone_set('Asia/Calcutta');
$msg = '';
$PREMIUM_TYPE = 'INITIAL PAYMENT';

$pageOwner = "'superadmin','admin','branch'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

#echo 'SITE IS UNDER MAINTENANCE';
#exit;

$objDB = new DB();






if(isset($_POST['branch_name']) && $_POST['branch_name'] != '')
{
	#echo '<pre>';
	#print_r($_POST);
	#echo '</pre>';
	extract($_POST);
	if(!(isset($micr_code))) {$micr_code = '';}
	if(!(isset($dd_date))) {$dd_date = '';}

	$product_name = find_product_name($plan);

	

	$dd_date = date('Y-m-d', strtotime($dd_date));

	$premium_multiple = find_premium_multiple($plan);
	$min_amount = find_min_amount($plan);
	$max_amount = find_max_amount($plan);
	
	if(trim($micr_code) != '')
	{
		$micr_id = find_micr_id($micr_code);
	}	
	
	else if(isset($micr_id) && intval($micr_id) == 0)
	{
		$msg = 'Invalid MICR Code';
	}
	
	else
	{	
			
			
	}

	//exit;
}

###### initialization of the variables start #######

if(!isset($branch_name)) { $branch_name = ''; } 



###### initialization of the variables end #######





//$a=loadVariable('a','');

$id = $_SESSION[ADMIN_SESSION_VAR];


/*
echo "<pre>";
print_r($_POST);
die();
*/



?>
<script type="text/javascript">
<!--
	
	function showHide(paymentType)
	{	
		//alert(paymentType);
		if(paymentType == 'CASH')
		{
			document.getElementById("dd_number").value='';			
			document.getElementById("dd_bank_name").value='';
			document.getElementById("dd_branch_name").value='';
			document.getElementById("dd_date").value=''; 
			document.getElementById("micr_code").value=''; 
			document.getElementById("ifs_code").value=''; 

			document.getElementById("dd_number").disabled=true;
			document.getElementById("dd_bank_name").disabled=true;
			document.getElementById("dd_branch_name").disabled=true;
			document.getElementById("dd_date").disabled=true;
			document.getElementById("micr_code").disabled=true;
			document.getElementById("ifs_code").disabled=true;
			document.getElementById("calChq").disabled=true;
		}
		else
		{
			document.getElementById("dd_number").disabled=false;
			document.getElementById("dd_bank_name").disabled=false;
			document.getElementById("dd_branch_name").disabled=false;
			document.getElementById("dd_date").disabled=false;
			document.getElementById("micr_code").disabled=false;
			document.getElementById("ifs_code").disabled=false;
			document.getElementById("calChq").disabled=false;
		}
	}
//-->
</script>

<script type="text/javascript">
function showbank(micrval){
	var datastring = 'micr='+micrval;
	/*alert(datastring);
	die();*/
	if(micrval == ""){
		alert("please select micr code");
		$("#micr_code").focus();
	}else{
	$.ajax({
             type: "POST",
             url: "ddbankajax.php",
             data:  datastring,
			 dataType: 'json',
			 cache: false,
             success: function(data){
			 if(data == 0){
			 	alert("This is not a valid Micr Code");
				$('input[id=dd_bank_name]').val('');
				$('textarea[id=dd_branch_name]').val('');
				/*$('input[id=ifs_code]').val('');*/
				$('input[id=micr_code]').val('');
				$("#micr_code").focus();
			 }else{
				 $('input[id=dd_bank_name]').val(data[0]);
				 $('textarea[id=dd_branch_name]').val(data[1]);
				/* $('input[id=ifs_code]').val(data[2]);*/
				 
				 /*document.getElementById("dd_bank_name").disabled=true;
				 document.getElementById("dd_branch_name").disabled=true;*/
				 /*document.getElementById("ifs_code").disabled=true;*/
				 }
              }
          });
	}
}
</script>



<script type="text/javascript">
<!--
	
	function dochk()
	{	
		if(document.addForm.payment_mode.value == 'DD' || document.addForm.payment_mode.value == 'CHEQUE')
		{
			if(document.addForm.dd_number.value.search(/\S/) == -1)
			{
				alert("Please enter DD/Cheque Number");
				document.addForm.dd_number.focus();
				return false;
			}
			if(document.addForm.dd_bank_name.value.search(/\S/) == -1)
			{
				alert("Please enter Bank Name");
				document.addForm.dd_bank_name.focus();
				return false;
			}
			if(document.addForm.dd_date.value.search(/\S/) == -1)
			{
				alert("Please enter DD/Cheque Date");
				document.addForm.dd_date.focus();
				return false;
			}
			if(document.addForm.ifs_code.value.search(/\S/) == -1)
			{
				alert("Please enter IFS Code");
				document.addForm.ifs_code.focus();
				return false;
			}
			if(document.addForm.micr_code.value.search(/\S/) == -1)
			{
				alert("Please enter MICR Code");
				document.addForm.micr_code.focus();
				return false;
			}
		}

	}
//-->
</script>

<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>

<form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Manual Entry</td>
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
      <td class="tbllogin" valign="top" align="right">Bank A/C<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	  <input name="bank_ac" id="bank_acy" type="radio" value="1" > Yes <input name="bank_ac" id="bank_acn" type="radio" value="0" > No </td>
    </tr>	

	<tr> 
      <td class="tbllogin" valign="top" align="right">IFS Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="ifs_code" id="ifs_code" type="text" class="inplogin"  value="<?php echo $ifs_code; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">MICR Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="micr_code" id="micr_code" type="text" class="inplogin"  value="<?php echo $micr_code; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();">&nbsp;
        <span class="tbllogin">
        <input type="button" name="loadmicr" id="loadmicr" value="show" onclick="showbank(micr_code.value);" />
        </span></td>
	   <td class="tbllogin" valign="top" align="left">&nbsp;</td>
		</tr>
	
		<tr> 
      <td class="tbllogin" valign="top" align="right">Bank Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dd_bank_name" id="dd_bank_name" type="text" class="inplogin"  value="<?php echo $dd_bank_name; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" readonly="readonly"></td>
    </tr>
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Branch Name with Address</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><?php /*?><input name="dd_branch_name" id="dd_branch_name" type="text" class="inplogin"  value="<?php echo $dd_branch_name; ?>" maxlength="100" onKeyUp="this.value = this.value.toUpperCase();" ><?php */?>
	  <textarea name="dd_branch_name" id="dd_branch_name" onKeyUp="this.value = this.value.toUpperCase();" rows="5" cols="20" readonly="readonly" class="inplogin"><?php echo $dd_branch_name; ?></textarea>
	  </td>
    </tr>

		

    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Add" class="inplogin" type="submit" onclick="return dochk()"> 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
    </tr>

  </tbody>
</table>
</form>

<?php //$objDB->close(); ?>

