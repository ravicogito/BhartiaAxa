<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");

date_default_timezone_set('Asia/Calcutta');

//print_r($_SESSION);

set_time_limit(0); 
ini_set('memory_limit','3000M');
ignore_user_abort(true);


$pageOwner = "'superadmin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

$msg = '';

$objDB = new DB();

function find_micr_ifs_id($micr_code, $ifs_code)
	{
		$occupation_id = 0;
		$selOccupationData = mysql_query("SELECT id FROM micr_master WHERE micr_code='".realTrim($micr_code)."' AND ifs_code='".realTrim($ifs_code)."'");
		$numOccupationData = mysql_num_rows($selOccupationData);
		if($numOccupationData > 0)
		{
			$getOccupationData = mysql_fetch_array($selOccupationData);
			$occupation_id = $getOccupationData['id'];
		}
		return $occupation_id;
	}


		######## DEALING WITH EXCEL DATA STARTS #############

	if(isset($_FILES['zip']) && $_FILES['zip']['tmp_name'] != '')
	{
		if(!is_dir('micr'))
		{
			mkdir('micr', 0777);
		}
		move_uploaded_file($_FILES['zip']['tmp_name'], 'micr/'.$_FILES['zip']['name']); // file uploaded
		chmod('micr/'.$_FILES['zip']['name'], 0777);

		$filenameArr = explode('.', $_FILES['zip']['name']);
		$filenameWOExtension = $filenameArr[0];

		#### EXTRACTING THE FILE STARTS

		$filename = '';
		
		$msg = 'Successfully Uploaded';
		if(file_exists('micr/'.$filenameWOExtension.'.xls'))
		{
			$filename = 'micr/'.$filenameWOExtension.'.xls';
			chmod($filename, 0777);
		}
		if(file_exists('micr/'.$filenameWOExtension.'.XLS'))
		{
			$filename = 'micr/'.$filenameWOExtension.'.XLS';
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
			
			$bank_name = isset($data->sheets[0]["cells"][$x][1]) ? realTrim($data->sheets[0]["cells"][$x][1]) : '';
			//echo 'bank_name '.$bank_name.'<br />';
			
			$ifs_code = isset($data->sheets[0]["cells"][$x][2]) ? realTrim($data->sheets[0]["cells"][$x][2]) : '';
			//echo 'ifs code '.$ifs_code.'<br />';

			$micr_code = isset($data->sheets[0]["cells"][$x][3]) ? realTrim($data->sheets[0]["cells"][$x][3]) : '';
			//echo 'micr_code '.$micr_code.'<br />';

			$branch_name = isset($data->sheets[0]["cells"][$x][4]) ? realTrim($data->sheets[0]["cells"][$x][4]) : '';
			//echo 'branch_name '.$branch_name.'<br />';

			$branch_address = isset($data->sheets[0]["cells"][$x][5]) ? realTrim($data->sheets[0]["cells"][$x][5]) : '';
			//echo 'branch_address '.$branch_address.'<br />';

			$contact = isset($data->sheets[0]["cells"][$x][6]) ? realTrim($data->sheets[0]["cells"][$x][6]) : '';
			//echo 'contact '.$contact.'<br />';

			//$city = isset($data->sheets[0]["cells"][$x][7]) ? realTrim($data->sheets[0]["cells"][$x][7]) : '';
			//echo 'city '.$city.'<br />';

			//$district = isset($data->sheets[0]["cells"][$x][8]) ? realTrim($data->sheets[0]["cells"][$x][8]) : '';
			//echo 'district '.$district.'<br />';

			//$state = isset($data->sheets[0]["cells"][$x][9]) ? realTrim($data->sheets[0]["cells"][$x][9]) : '';
			//echo 'state '.$state.'<br />';



			$branch = $branch_name.' '.$branch_address.' Phone : '.$contact  ;			

			$micr_id = find_micr_ifs_id($micr_code, $ifs_code);			
			

			if(intval($micr_id) == 0)
			{
				
			$firstInsert = "INSERT INTO micr_master SET
					micr_code = '".realTrim($micr_code)."',
					ifs_code = '".realTrim($ifs_code)."',
					bank_name = '".realTrim($bank_name)."',
					branch = '".realTrim($branch)."'
			";
			//echo $firstInsert.'<br />';
			//exit;
			mysql_query($firstInsert);
			//$lastPremiumID = mysql_insert_id();

			}

			if(intval($x % 1000) == 0)
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
		
    <tr class="TDHEAD"> 
      <td colspan="3">Upload XML<!-- Upload ZIP of the XML --></td>
    </tr>

		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left">With the help of this page administrator can upload the MICR and IFS codes from an excel file.  
			
			</td>
    </tr>

    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
        * marked fields are mandatory</font></b></td>
    </tr>
		
		<input type="hidden" name="branch_name" id="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
		
		<tr> 
      <td class="tbllogin" valign="top" align="right">Upload MICR / IFS excel<font color="#ff0000">*</font></td>
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