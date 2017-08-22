<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");

date_default_timezone_set('Asia/Calcutta');

//print_r($_SESSION);

set_time_limit(0); 
ignore_user_abort(true);


$pageOwner = "'superadmin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

$msg = '';

$objDB = new DB();

function find_application_id($application_no) // This function will determine whether this is coming from GAP
	{
		$objDB = new DB();
		$gap_status = 0;
		$selGap_status = mysql_query("SELECT id FROM gtfs_app_master WHERE 	application_no='".$application_no."'");
		$numGap_status = mysql_num_rows($selGap_status);
		if($numGap_status > 0)
		{
			$getGap_status = mysql_fetch_array($selGap_status);
			$gap_status = $getGap_status['id'];
		}
		return $gap_status;
	}


		######## DEALING WITH EXCEL DATA STARTS #############

	if(isset($_FILES['zip']) && $_FILES['zip']['tmp_name'] != '')
	{
		if(!is_dir('gtfs_policies'))
		{
			mkdir('gtfs_policies', 0777);
		}
		move_uploaded_file($_FILES['zip']['tmp_name'], 'gtfs_policies/'.$_FILES['zip']['name']); // file uploaded
		chmod('gtfs_policies/'.$_FILES['zip']['name'], 0777);

		$filenameArr = explode('.', $_FILES['zip']['name']);
		$filenameWOExtension = $filenameArr[0];

		#### EXTRACTING THE FILE STARTS

		$filename = '';
		
		$msg = 'Successfully Uploaded';
		if(file_exists('gtfs_policies/'.$filenameWOExtension.'.xls'))
		{
			$filename = 'gtfs_policies/'.$filenameWOExtension.'.xls';
			chmod($filename, 0777);
		}
		if(file_exists('gtfs_policies/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$filenameWOExtension.'.XLS'))
		{
			$filename = 'gtfs_policies/'.$filenameWOExtension.'.XLS';
			chmod($filename, 0777);
		}

		
		//$filename = 'gtfs_policies/test_policies.xls';
		require_once 'Excel/reader.php';

		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');

		$data->read($filename); // upload the xl sheet by renaming it to exceltestsheet.xls
		
		
		for ($x = 2; $x <= count($data->sheets[0]["cells"]); $x++) {

			//echo 'loop counter = '.$x.'<br />';
			//echo '<pre>';
				//print_r($data->sheets[0]["cells"][$x]);
			//echo '</pre>';

			//exit;
			
			$application_no = isset($data->sheets[0]["cells"][$x][1]) ? realTrim($data->sheets[0]["cells"][$x][1]) : '';
			//echo 'application_no '.$application_no.'<br />';

			$applicant_name = isset($data->sheets[0]["cells"][$x][2]) ? realTrim($data->sheets[0]["cells"][$x][2]) : '';
			//echo 'applicant_name '.$applicant_name.'<br />';

			$application_id = find_application_id($application_no);			
			

			if(intval($application_id) == 0)
			{
				// INSERTING DATA INTO customer_master TABLE
			$firstInsert = "INSERT INTO gtfs_app_master SET
					application_no = '".$application_no."',
					applicant_name = '".$applicant_name."'
			";
			//echo $firstInsert.'<br />';
			//exit;
			mysql_query($firstInsert);
			//$lastPremiumID = mysql_insert_id();

			}
			else
			{
			$firstUpdate = "update gtfs_app_master SET 
					applicant_name = '".$applicant_name."' WHERE
					application_no = '".$application_no."'
			";
			}

			if(intval($x % 500) == 0)
			{
				sleep(2);
			}

		}	
		
		unlink($filename);
	
	#### EXTRACTING THE FILE ENDS

		if(file_exists($_FILES['zip']['name']))
		{
			unlink($_FILES['zip']['name']);
		}
	}

		

		######## DEALING WITH XML DATA ENDS ###############

	#### EXTRACTING THE FILE ENDS


?>


<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>

<script type="text/javascript">
<!--
		
	function dochk()
	{
		
		if(document.addForm.zip.value.search(/\S/) == -1)
		{
			alert("Please select the excel file");
			document.addForm.zip.focus();
			return false;
		}
		if(!chkxls(document.addForm.zip.value))
		{
			alert("Only .xls files are allowed");
			document.addForm.zip.focus();
			return false;
		}

	}
//-->
</script>


<form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()" enctype="multipart/form-data">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>
				<?= $msg;?>
      </td>
    </tr>
		<?php 
			$service_status = service_status(2);
			if($service_status == 0)
			{
		?>
		<tr> 
      <td colspan="3" >
       <marquee>
				<h1 style="color:#ff0000;">This facility is currently deactivated by the administrator. If you want to add any record manually please <a href="<?= URL; ?>webadmin/index.php?p=manual_entry_branch" style="font-size:18px;">click here</a>.
				</h1>
			 </marquee>
      </td>
    </tr>
		<?php 
			exit; 
			}
		?>
    <tr class="TDHEAD"> 
      <td colspan="3">Upload XML<!-- Upload ZIP of the XML --></td>
    </tr>

		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left">With the help of this page administrator can upload the EXCEL file generated by the LICI for existing policy no..  
			
			</td>
    </tr>

    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
        * marked fields are mandatory</font></b></td>
    </tr>
		
		<input type="hidden" name="branch_name" id="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
		
		<tr> 
      <td class="tbllogin" valign="top" align="right">Upload GTFS Policy Number (In .xls format)<font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="zip" id="zip" type="file" class="inplogin" ></td>
    </tr>
    

    <tr> 
      <td colspan="2">&nbsp;</td>
      <td> <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Add" class="inplogin" type="submit" onclick="return dochk()"> 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
    </tr>
  </tbody>
</table>

<?php //$objDB->close(); ?>