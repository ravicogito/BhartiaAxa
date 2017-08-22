<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("../webadmin/includes/new_functions.php");
date_default_timezone_set('Asia/Calcutta');
set_time_limit(0); 
ignore_user_abort(true);
$objDB = new DB();
        require_once 'Excel/reader.php';
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('CP1251');
        $data->read('branch_code.xls');
		
		for ($x = 2; $x <= count($data->sheets[0]["cells"]); $x++)
		 {
			$branch=isset($data->sheets[0]["cells"][$x][1]) ? realTrim($data->sheets[0]["cells"][$x][1]) : '';
			$branch_code=isset($data->sheets[0]["cells"][$x][2]) ? realTrim($data->sheets[0]["cells"][$x][2]) : '';
			$hub=isset($data->sheets[0]["cells"][$x][3]) ? realTrim($data->sheets[0]["cells"][$x][3]) : '';   
                                
				$firstUpdate = "update admin SET 
					branch_code1 = '".$branch_code."' 
					WHERE
					branch_name = '".$branch."' AND role_id = '4'";
                                        mysql_query($firstUpdate);
		}
                echo 'Successfully Uploaded Excel';
?>   