<?php 
include_once("utility/config.php");

include_once("utility/dbclass.php");

include_once("utility/functions.php");

//include_once("includes/other_functions.php");

include_once("webadmin/includes/new_functions.php");

date_default_timezone_set('Asia/Calcutta');
error_reporting(0);
set_time_limit(0); 

$objDB = new DB();

$pageOwner = "'superadmin','admin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 

if((intval($_SESSION[ROLE_ID]) == 3) || (intval($_SESSION[ROLE_ID]) == 4))
{
	$_SESSION['branch_name'] = $_SESSION[ADMIN_SESSION_VAR]; // For branch and hub
}
if(empty($_SESSION['search_op']) && empty($_SESSION['search_data'])){
	$where = "WHERE role_id =4 AND branch_user_id=0 ";
	$OrderBY = " ORDER BY branch_name";

	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '')
	{	
		$where.=" AND id='".$_SESSION['branch_name']."'";
	}
}
else{
	    $search_opss 	= trim($_SESSION['search_op']);
		$search_data	= $_SESSION['search_data'];
		/*echo "<pre>";
		print_r($_SESSION);*/
		 
	    //$x = trim($search_op);//exit;
		//$y = trim($x);
		//echo $y;exit;
		//echo $z	=trim($y);//exit;
		///echo 
        //if ($z == "user_username") {
        	//echo '44444444444444444444444';exit;
       // }

		if($search_opss == "user_name") { 
			
	        $where = "WHERE role_id =4 AND name like '%".mysql_real_escape_string($search_data)."%' ";
			 //unset($_SESSION['search_op']);
		}
		if($search_opss == 'user_username') 
		{
			
			$where .= "WHERE role_id =4 AND username like '%".mysql_real_escape_string($search_data)."%' ";
			unset($_SESSION['search_op']);
		}
		if($search_opss == 'user_branch_name') 
		{

			$where .= "WHERE role_id =4 AND branch_name like '%".mysql_real_escape_string($search_data)."%' ";
			unset($_SESSION['search_op']);
		}
		if($search_opss == 'user_hub') 
		{
			$where .= "WHERE role_id = 3 AND branch_name like '%".mysql_real_escape_string($_SESSION['search_data'])."%' ";
		}
	}
$Query = "SELECT * FROM  `admin`  ".$where.$OrderBY.$Limit;

$selTransaction = mysql_query($Query);
//echo $Query;exit;
//;
//echo "SELECT * FROM installment_master WHERE is_deleted=0 ".$where." ORDER BY id DESC";
$numTransaction = mysql_num_rows($selTransaction);

    	$header .= "Name"."\t";
		$header .= "Main Branch"."\t";
		$header .= "Main Branch Code"."\t";
		$header .= "Branch"."\t";
		$header .= "User Name."."\t";
		$header .= "Password"."\t";
		$header .= "Branch Code"."\t";
		$header .= "Hub Name"."\t";
		//$header .= "Branch Address"."\t";
		$header .= "Branch Contact No."."\t";
		$header .= "\n";
	
		if($numTransaction > 0)
		{
			while($getTransaction = mysql_fetch_assoc($selTransaction))
			{
				$mysql_id = $getTransaction['id'];
				$branch_name = find_branch_name($getTransaction['branch_id']);
		
			$line = '';		
			
			$sel_branch = mysql_query("SELECT branch_name FROM admin WHERE id='".$getTransaction['hub_id']."'");

			if(mysql_num_rows($sel_branch) > 0){
				$get_branch = mysql_fetch_array($sel_branch);
				$hub_name = $get_branch['branch_name'] != '' ? $get_branch['branch_name'] : 'HO';
			}

            if($getTransaction['mainbranch_id']!=0){
		       $sel_mbranch = mysql_query("SELECT branch_name,mainbranch_code FROM admin WHERE id='".$getTransaction['mainbranch_id']."'");
		       $get_mbranch = mysql_fetch_array($sel_mbranch);
               $main_branch = $get_mbranch['branch_name']; 
               $mainbranch_code = $get_mbranch['mainbranch_code'];
            }else{
               $main_branch = "";	
               $mainbranch_code = "";
            }



			$line .= $getTransaction['name']."\t";
			$line .= $main_branch."\t";
			$line .= $mainbranch_code."\t";
			$line .= $getTransaction['branch_name']."\t";
			$line .= $getTransaction['username']." \t";
			$line .= $getTransaction['password']." \t";
			$line .= $getTransaction['branch_code']." \t";
			$line .= $hub_name." \t";
			//$line .= $getTransaction['address']." \t";
			$line .= $getTransaction['branch_phone']." \t";
			$data .= trim($line)."\n";
		}
	}
	$data = str_replace("\r", "", $data);
	if ($data == "")
	{
		$data = "\n(0) Records Found!\n";						
	}
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=User_Records".'_'.date('m-d-Y_H:i').".xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";

?>