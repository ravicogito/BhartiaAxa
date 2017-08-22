<?php
######################################################################################################
#
######### THIS PAGE IS USED FOR INSERTING THE DATA SUPPLIED BY RELIANCE TO GTFS_GOLD DATABASE #######
#					ONLY SUPERADMIN CAN ACCESS THIS PAGE FOR SECURITY PURPOSE
#
######################################################################################################

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");

/*if(isset($_FILES['zip']) && $_FILES['zip']['tmp_name'] != '')
	{
		echo "Hi";
		exit;
	}*/

date_default_timezone_set('Asia/Calcutta');

//print_r($_SESSION);

set_time_limit(0); 
ignore_user_abort(true);


$pageOwner = "'superadmin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

$msg = '';

$objDB = new DB();


		######## DEALING WITH XML DATA STARTS #############

		if(isset($_FILES['zip']) && $_FILES['zip']['tmp_name'] != '')
	{
		
		if(!is_dir('sicl_policies'))
		{
			mkdir('sicl_policies', 0777);
		}
		move_uploaded_file($_FILES['zip']['tmp_name'], 'sicl_policies/'.$_FILES['zip']['name']); // file uploaded
		chmod('sicl_policies/'.$_FILES['zip']['name'], 0777);

		$filenameArr = explode('.', $_FILES['zip']['name']);
		$filenameWOExtension = $filenameArr[0];

		#### EXTRACTING THE FILE STARTS

		$filename = '';
		
		$msg = 'Successfully Uploaded';
		if(file_exists('sicl_policies/'.$filenameWOExtension.'.xls'))
		{
			$filename = 'sicl_policies/'.$filenameWOExtension.'.xls';
			chmod($filename, 0777);
		}
		if(file_exists('sicl_policies/'.$_SESSION[ADMIN_SESSION_VAR].'/'.$filenameWOExtension.'.XLS'))
		{
			$filename = 'sicl_policies/'.$filenameWOExtension.'.XLS';
			chmod($filename, 0777);
		}
		//$filename = 'plans/plan165.xls';
		//echo "Hiii";
		//exit;
		require_once 'Excel/reader.php';
		//echo "Hiiii";
		//exit;

		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP1251');

		$data->read($filename); // upload the xl sheet by renaming it to exceltestsheet.xls
		
		
		for ($x = 2; $x <= count($data->sheets[0]["cells"]); $x++) {
			
			
			echo 'loop counter = '.$x.'<br />';
			echo '<pre>';
				print_r($data->sheets[0]["cells"][$x]);
			echo '</pre>';
			
			$plan = isset($data->sheets[0]["cells"][$x][1]) ? realTrim($data->sheets[0]["cells"][$x][1]) : '';
			echo 'plan '.$plan.'<br />';

			$plan_id = find_plan_id($plan);

			$term = isset($data->sheets[0]["cells"][$x][2]) ? realTrim($data->sheets[0]["cells"][$x][2]) : '';
			echo 'term '.$term.'<br />';
			
			$age = isset($data->sheets[0]["cells"][$x][3]) ? realTrim($data->sheets[0]["cells"][$x][3]) : '';
			echo 'age '.$age.'<br />';

			$age_id = find_age_id($age);
			
			
			$rate = isset($data->sheets[0]["cells"][$x][4]) ? realTrim($data->sheets[0]["cells"][$x][4]) : '';
			echo 'rate '.$rate.'<br />';

			$age_proof = isset($data->sheets[0]["cells"][$x][5]) ? realTrim($data->sheets[0]["cells"][$x][5]) : '';
			echo 'age_proof '.$age_proof.'<br />';
			
			
			$extra_amount_rate = isset($data->sheets[0]["cells"][$x][6]) ? realTrim($data->sheets[0]["cells"][$x][6]) : '';
			echo 'extra_amount_rate '.$extra_amount_rate.'<br />';


			
			

			###############################################
		

			$premium_id = find_premium_id($plan_id, $age_id, $term, $rate,$age_proof,$extra_amount_rate);

			if(intval($premium_id) == 0)
			{
				// INSERTING DATA INTO customer_master TABLE
			$firstInsert = "INSERT INTO new_plan_rate SET
					plan_id = '".$plan_id."',
					age_id = '".$age_id."',
					term_id = '".$term."',
					rate = '".$rate."',
					age_proof = '".$age_proof."',
					extra_amount_rate = '".$extra_amount_rate."'
			";
			echo $firstInsert.'<br />';
			//exit;
			mysql_query($firstInsert);
			//$lastPremiumID = mysql_insert_id();

			}
			else
			{
				$firstUpdate = "Update new_plan_rate SET
					plan_id = '".$plan_id."',
					age_id = '".$age_id."',
					term_id = '".$term."',
					rate = '".$rate."',
					age_proof = '".$age_proof."',
					extra_amount_rate = '".$extra_amount_rate."' WHERE id = '".$premium_id."'
			";
			echo $firstUpdate.'<br />';
			//exit;
			mysql_query($firstUpdate);

			}
		}		

}

		######## DEALING WITH XML DATA ENDS ###############

	#### EXTRACTING THE FILE ENDS


?>


<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>


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
      <td colspan="3">Upload Excel<!-- Upload ZIP of the XML --></td>
    </tr>

		<tr> 
      <td colspan="3" style="padding-left: 70px;" align="left">
			</td>
    </tr>

    <tr> 
      <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
        * marked fields are mandatory</font></b></td>
    </tr>
		
		<input type="hidden" name="branch_name" id="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
		
		<tr> 
      <td class="tbllogin" valign="top" align="right">Upload Excel <!-- Upload Zip (Reliance Money System) --><font color="#ff0000">*</font></td>
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
