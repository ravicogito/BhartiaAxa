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
$role_id=$_SESSION[ROLE_ID];
chkPageAccess($role_id, $pageOwner); 

if(isset($_SESSION['error_msg']) && $_SESSION['error_msg']!="")
{
	$msg=$_SESSION['error_msg'];
	unset($_SESSION['error_msg']);
}

//echo 'SITE IS UNDER MAINTENANCE';
#exit;



#$selTenure = mysql_query("SELECT id, tenure FROM tenure_master WHERE status=1 ORDER BY tenure ASC "); //for Term dropdown
#$numTenure = mysql_num_rows($selTenure);
if(isset($_POST['submit']) && $_POST['submit']!='')
{

// $phase=trim($_POST['phase']);
$branch=trim($_POST['branch']);
//$agent_code=trim($_POST['agent_code']);

//$upload_xml=$_POST['upload_xml'];
/*
if($phase=="")
{
$msg="Please Enter Phase.";
}
else*/ if($branch=="")
{
$msg="Please Enter Branch.";
}
/*else if($agent_code=="")
{
$msg="Please Enter Agent Code.";
}*/
else if($_FILES["upload_xml"]["name"]=="")
{
$msg="Please Upload XML File.";
}
else if(getExtension($_FILES["upload_xml"]["name"])!="xml")
{
$msg="Please Upload only XML File.";
}


//------------- start for get hub id -------------//

$set_hub = "SELECT hub_id FROM branch_hub_entry WHERE branch_id = '".$branch."' order by hub_since desc LIMIT 0,1";
$set_hub_data = mysql_query($set_hub);
$get_hub = mysql_fetch_array($set_hub_data);

$hub_id=$get_hub['hub_id'];

//------------- end for get hub id --------------//


if($msg=="")
{

			$xml=simplexml_load_file($_FILES["upload_xml"]["tmp_name"]);
			//var_dump($xml);
			foreach($xml->GTFScolldata as $v)
			{
				
				//echo $v->policy_holder;
				//echo "<br>";
				
				$business_date=$v->batch_open_date;
				$business_date=date("Y-m-d", strtotime($business_date));
				
				$type_of_business=$v->transaction_type;
				//$application_no=$v->application_no;
				
				
				
				$application_no=$v->application_no;
				$policy_no=$v->policy_no;
				$company_code=$v->agent_code;
				
				if($company_code!='2000002609')
				{
					$_SESSION['error_msg']="This company name should be SZ. Please review the .xml file.";
					header("location: ".URL.'webadmin/index.php?p=short_premium_entry_sz');
					exit;
				}
				
				$money_receipt=$v->perm_receipt_no;
				
				$applicant_name=$v->policy_holder;
				//$applicant_dob=$v->DOB;
				//$applicant_dob=date("Y-m-d", strtotime($applicant_dob));
				//$applicant_age=(strtotime(date("Y-m-d"))-strtotime($applicant_dob));
				//$applicant_age = floor($applicant_age / (366*60*60*24));
				 
				$plan_name=$v->Product_Name;
				
				$receive_amount=$v->amount;
				$receive_mode=$v->pay_mode; // csh, chq, dft
				$cheque_no=$v->cheque_no;
				$cheque_date=$v->cheque_date;
				if($cheque_date!="")
				{
					$cheque_date=date("Y-m-d", strtotime($cheque_date));
				}
				$cheque_bank=$v->bank_name;
				$cheque_branch=$v->bank_branch;
				
				$reciept_status=$v->Reciept_Status;
				
				//-------- start for check receipt no -----------//
				
				switch ($receive_mode)
				{
					case "CSH":
					$database_field="cash_money_receipt";
					break;
					case "CHQ":
					$database_field="cheque_money_receipt";
					break;
					case "DFT":
					$database_field="draft_money_receipt";					
					break;
				
				}
				
				//$tot_receipt=mysql_num_rows(mysql_query("select id from  installment_master_sz_short_premium_fresh where $database_field='$money_receipt' and is_deleted='0'"));
				
				$tot_receipt=mysql_num_rows(mysql_query("select id from installment_master_sz_short_premium_fresh where (cash_money_receipt='$money_receipt' || cheque_money_receipt='$money_receipt' || draft_money_receipt='$money_receipt') and is_deleted='0'"));
				
				//-------- end for check receipt no -----------//
				
				// if($tot_receipt==0 && (strtolower($type_of_business)=='rwp'|| strtolower($type_of_business)=='fpr'))
				if($tot_receipt==0 && strtolower($type_of_business)!='rwp' && strtolower($type_of_business)!='frp' && $policy_no=="" && strtolower($reciept_status)=='live')
				{
				
					//-------- start for check application number ---------//
						$query_policy_no=mysql_query("select id from installment_master_sz_short_premium_fresh where application_no='$application_no' and is_deleted='0'");
						
						$tot_policy_no=mysql_num_rows($query_policy_no);
					
					//-------- end for check application number ---------//
					
					if($tot_policy_no==0) 
					{
					
						// insert the data
						
						$query="business_date='$business_date', ";
						$query.="type_of_business='$type_of_business', ";
						// $query.="phase_id='$phase', ";
						$query.="branch_id='$branch', ";
						$query.="hub_id='$hub_id', ";
						//$query.="agent_code='$agent_code', ";
						switch($receive_mode)
						{
							case "CSH":
							$query.="cash_money_receipt='$money_receipt', ";
							$query.="receive_cash='$receive_amount', ";
							
							break;
							case "CHQ":
							$query.="cheque_money_receipt='$money_receipt', ";
							$query.="receive_cheque='$receive_amount', ";
							
							break;
							case "DFT":
							$query.="draft_money_receipt='$money_receipt', ";
							$query.="receive_draft='$receive_amount', ";
							
							break;
						
						}
						
						$query.="applicant_name='$applicant_name', ";
						$query.="application_no='$application_no', ";
							
						$query.="proposal_no='$policy_no', ";
						
						/*$query.="application_no='$application_no', ";
						$query.="applicant_dob='$applicant_dob', ";
						$query.="applicant_age='$applicant_age', ";*/
						$query.="plan_name='$plan_name', ";
						
						switch($receive_mode)
						{
							// update the data
							
							case "CHQ":
							$query.="cheque_no='$cheque_no', ";
							$query.="cheque_date='$cheque_date', ";
							$query.="cheque_bank_name='$cheque_bank', ";
							$query.="cheque_branch_name='$cheque_branch', ";
							break;
							case "DFT":
							$query.="dd_no='$cheque_no', ";
							$query.="dd_date='$cheque_date', ";
							$query.="dd_bank_name='$cheque_bank', ";
							$query.="dd_branch_name='$cheque_branch', ";
							break;
						
						}
						$query.="premium='$receive_amount'";
						//echo $query;
						//exit;
						/*
						$query.="business_date='$business_date'";
						$query.="business_date='$business_date'";
						$query.="business_date='$business_date'";
						$query.="business_date='$business_date'";
						$query.="business_date='$business_date'";
						*/
						
						mysql_query("insert into installment_master_sz_short_premium_fresh set $query");
						
						//echo "insert into installment_master_branch set $query";
						//exit;
					}
					else
					{
						$rec=mysql_fetch_array($query_policy_no);
						$id=$rec['id'];
						
						$query="";
						switch($receive_mode)
						{
							case "CSH":
							$query.="cash_money_receipt='$money_receipt', ";
							$query.="receive_cash='$receive_amount', ";
							
							break;
							case "CHQ":
							$query.="cheque_money_receipt='$money_receipt', ";
							$query.="receive_cheque='$receive_amount', ";
							
							break;
							case "DFT":
							$query.="draft_money_receipt='$money_receipt', ";
							$query.="receive_draft='$receive_amount', ";
							
							break;
						
						}
						
						
						
						switch($receive_mode)
						{
							
							case "CHQ":
							$query.="cheque_no='$cheque_no', ";
							$query.="cheque_date='$cheque_date', ";
							$query.="cheque_bank_name='$cheque_bank', ";
							$query.="cheque_branch_name='$cheque_branch', ";
							break;
							case "DFT":
							$query.="dd_no='$cheque_no', ";
							$query.="dd_date='$cheque_date', ";
							$query.="dd_bank_name='$cheque_bank', ";
							$query.="dd_branch_name='$cheque_branch', ";
							break;
						
						}
						
						$query.="premium=premium+'$receive_amount'";
						
						mysql_query("update installment_master_sz_short_premium_fresh set $query where id='$id'");
						//echo "update installment_master_branch set $query";
					}
					
					}
					//exit;
					
					
			}
			header("location: ".URL.'webadmin/index.php?p=short_premium_list_sz');
}

}




//$a=loadVariable('a','');

$id = $_SESSION[ADMIN_SESSION_VAR];

/*
$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,3,5,6) AND branch_user_id = 0 ORDER BY branch_name ASC");

*/
/*
echo "<pre>";
print_r($_POST);
die();
*/



?>
<script language="JavaScript" type="text/JavaScript">
function isNumber(field) {
        var re = /^[0-9-'.'-',']*$/;
        if (!re.test(field.value)) {
            alert('Agent Code should be Numeric');
            field.value = field.value.replace(/[^0-9-'.'-',']/g,"");
        }
    }
</script>


<script type="text/javascript">

	

	function dochk()
	{
	/*if(document.addForm.phase.value.search(/\S/) == -1)
		{
			alert("Please Enter Phase.");
			document.addForm.phase.focus();
			return false;
		}
		else*/ if(document.addForm.branch.value.search(/\S/) == -1)
		{
			alert("Please Enter Branch.");
			document.addForm.branch.focus();
			return false;
		}
		/*else if(document.addForm.agent_code.value.search(/\S/) == -1)
		{
			alert("Please Enter Agent Code.");
			document.addForm.agent_code.focus();
			return false;
		}*/
		else if(document.addForm.upload_xml.value.search(/\S/) == -1)
		{
			alert("Please Upload XML File.");
			document.addForm.branch.focus();
			return false;
		}
		else if(document.addForm.upload_xml.value.substring(document.addForm.upload_xml.value.lastIndexOf('.') + 1).toLowerCase()!='xml')
		{
			alert("Please Upload only XML File.");
			document.addForm.branch.focus();
			return false;
		}
		else
		{
			return true;
		}
		}



function adalt_or_minor_div()
{
if(document.getElementById("is_adult").checked)
		{
		//alert('Hi');
		document.getElementById("for_minor").style.display="";
		document.getElementById("for_adult").style.display="none";
		}
		else
		{
		document.getElementById("for_minor").style.display="none";
		document.getElementById("for_adult").style.display="";
		}
return false;
}
</script>

<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>

<form name="addForm" id="addForm" enctype="multipart/form-data" action="" method="post" onsubmit="return dochk()">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>      </td>
    </tr>
    <tr class="TDHEAD"> 
      <td colspan="3">Manual Entry (Short Premium - Fresh)</td>
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

	

	
	<!--<tr> 
      <td class="tbllogin" valign="top" align="right">Phase<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><select name="phase" id="phase" class="inplogin">
				<option value="">Select</option>
				<?php 
					/*
					$selPhase = mysql_query("select id, phase from phase_master order by phase");   //for Plan dropdown
					$numPhase = mysql_num_rows($selPhase);
					if($numPhase > 0)
					{
						while($getPhase = mysql_fetch_array($selPhase))
						{	
						*/						
				?>
					<option value="<?php //echo $getPhase['id']; ?>"><?php //echo $getPhase['phase']; ?></option>
				<?php
					/*
						}
					}
					*/
				?>
			</select></td>
    </tr>-->
	<?php if($role_id!='4'): ?>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Branch Name<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><select name="branch" id="branch" class="inplogin">
				<option value="">Select</option>
				<?php 
				
					$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,3,5,6) AND branch_user_id = 0 ORDER BY branch_name ASC");
					$numBranch = mysql_num_rows($selBranch);
					if($numBranch > 0)
					{
						while($getBranch = mysql_fetch_array($selBranch))
						{	
												
				?>
					<option value="<?php echo $getBranch['id']; ?>"><?php echo $getBranch['branch_name']; ?></option>
				<?php
						}
					}
				?>
			</select></td>
    </tr>	
	<?php else:?>
	<input type="hidden" id="branch" name="branch" value="<?php echo $id; ?>">
	<?php endif; ?>
	<!--<tr> 
      <td class="tbllogin" valign="top" align="right">Agent Code <font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="agent_code" id="agent_code" type="text" /></td>
	</tr>-->
	<tr> 
      <td class="tbllogin" valign="top" align="right">Upload XML File<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="upload_xml" id="upload_xml" type="file" /></td>
    </tr>

		<tr>
		<td colspan="3">
		<div id="for_minor" style="display:none;margin-right: 266px;">
		<table cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
		
		<tr> 
      <td class="tbllogin" valign="top" align="right">Applicant DOB<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="dob_manual" id="dob_manual" type="text" class="inplogin"  value="<?php echo $dob_manual; ?>" maxlength="20" readonly /> &nbsp;<img src="images/cal.gif" alt="" style="border: 0pt none ; cursor: pointer; position: absolute;" onclick="displayCalendar(document.addForm.dob_manual,'dd-mm-yyyy',this)" width="20" height="18"></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Plan<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
	  <!--<input type="hidden" name="plan" id="plan" value="2" >-->
	  
	  <select name="plan_manual" id="plan_manual" class="inplogin">
				<option value="">Select</option>
				<?php 
				
					$selPlan = mysql_query("select id, plan_name from insurance_plan WHERE status=1 AND is_new=1 ORDER BY plan_name ASC ");   //for Plan dropdown
					$numPlan = mysql_num_rows($selPlan);
					if($numPlan > 0)
					{
						while($getPlan = mysql_fetch_array($selPlan))
						{	
												
				?>
					<option value="<?php echo $getPlan['id']; ?>" <?php echo ($getPlan['id'] == $tenure ? 'selected' : ''); ?>><?php echo $getPlan['plan_name']; ?></option>
				<?php
						}
					}
				?>
			</select>			</td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Term<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
			<select name="tenure_manual" id="tenure_manual" class="inplogin">
				<option value="">Select</option>
				<?php 
				$selTenure = mysql_query("SELECT id, tenure FROM tenure_master WHERE status=1 ORDER BY tenure ASC "); //for Term dropdown
$numTenure = mysql_num_rows($selTenure);
					if($numTenure > 0)
					{
						while($getTenure = mysql_fetch_array($selTenure))
						{							
				?>
					<option value="<?php echo $getTenure['tenure']; ?>" <?php echo ($getTenure['tenure'] == $tenure ? 'selected' : ''); ?>><?php echo $getTenure['tenure']; ?></option>
				<?php
						}
					}
				?>
			</select></td>
    </tr>

	<tr> 
      <td class="tbllogin" valign="top" align="right">Payment Frequency<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
					
				
				<select name="frequency_manual" id="frequency_manual" class="inplogin_select" onchange="javascript:showHide(this.value)">
				<option value="">Select</option>
				<?php 
				
					$selFrequency = mysql_query("select id, frequency from frequency_master WHERE status=1 ORDER BY frequency ASC ");   //for Plan dropdown
					$numFrequency = mysql_num_rows($selFrequency);
					if($numFrequency > 0)
					{
						while($getFrequency = mysql_fetch_array($selFrequency))
						{	
												
				?>
					<option value="<?php echo $getFrequency['id']; ?>" <?php echo ($getFrequency['id'] == $tenure ? 'selected' : ''); ?>><?php echo $getFrequency['frequency']; ?></option>
				<?php
						}
					}
				?>
			</select>					</td>
    </tr>
	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Sum Assured<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="sum_assured_manual" id="sum_assured_manual" type="text" class="inplogin"  value="<?php echo $sum_assured_manual; ?>" maxlength="20" onKeyPress="return keyRestrict(event, '0123456789.')">	  </td>
    </tr>
	

	<tr> 
      <td class="tbllogin" valign="top" align="right">Age Proof<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
				<select name="age_proof_manual" id="age_proof_manual" class="inplogin_select" >
					<option value="">Select</option>
					<option value="STANDARD" >STANDARD</option>
					<option value="NSAP1" >NSAP1</option>
					<option value="NSAP23" >NSAP2/NASP3</option>
				</select>					</td>
    </tr>
<script type="text/javascript">
function get_tax_and_total(amunt)
{
//alert (amunt);
document.getElementById('service_tax_manual').value = Math.floor(parseInt(amunt)*(.03090));
document.getElementById('total_value_manual').value = parseInt(amunt) + Math.floor(parseInt(amunt)*(.03090));
}
</script>
	<tr> 
      <td class="tbllogin" valign="top" align="right">Premium Amount<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="amount_manual" id="amount_manual" type="text" class="inplogin"  value="<?php echo $amount_manual; ?>" onKeyPress="return keyRestrict(event, '0123456789.')" onblur="get_tax_and_total(this.value);" >	  </td>
    </tr>	
	
	
	
	
	<tr> 
      <td class="tbllogin" valign="top" align="right">Service Tax<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="service_tax_manual" id="service_tax_manual" type="text" class="inplogin"  value="<?php echo $service_tax_manual; ?>" onKeyPress="return keyRestrict(event, '0123456789.')" readonly="readonly">	  </td>
    </tr>	
		
	<tr> 
      <td class="tbllogin" valign="top" align="right">Total Premium Amount<font color="#ff0000">*</font><br /></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="total_value_manual" id="total_value_manual" type="text" class="inplogin"  value="<?php echo $total_value_manual;?>" readonly="readonly">	  </td>
    </tr>	
	</table>
		</div>		</td>
		</tr>
		


	
	 
	
	
	
	
	
	
	

	
	
	
			
	


    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Add" name="submit" class="inplogin" type="submit"> 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
    </tr>

  </tbody>
</table>
</form>

<?php //$objDB->close(); ?>

