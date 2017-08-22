<?php
/*echo 'hello';
die();*/
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");

$objDB = new DB();
$hub = trim($_GET['hub']);
if($hub!==''){
$hub = " AND hub_id='".$hub."'";
} else {
 $hub = '';
}

//echo $hub;
//die();
$branch_sql = 'select * from admin where role_id NOT IN(1,2,5,6,3) '.$hub.' AND branch_user_id = 0 AND status = 1 ORDER BY branch_name';
$branch_query = mysql_query($branch_sql);
$branch_num_row = mysql_num_rows($branch_query);
//echo $branch_num_row;
if($branch_num_row < 1){
	echo '<select name="branch_name" id="branch_name" class="inplogin_select" style="width:140px;" >
							 <option value="">--Select--</option>';
							 echo '</select>';
}else{

//echo '<tr>
						//<td width="150"><strong>Branch</strong></td>
						//<td width="20"><strong>:</strong></td>
						//<td width="150">
						echo	'<select name="branch_name" id="branch_name" class="inplogin_select" style="width:140px;" >
							 <option value="">--Select--</option>';
							 

while($branch_row = mysql_fetch_array($branch_query)){
//echo "<pre>";
//print_r($branch_row);
//exit;
//for($i=0;$i<$branch_num_row;$i++){
	//$i=0;
//$brancharr[$i][] = $branch_row['id'];
//$brancharr[$i][] = $branch_row['name'];



							
				echo '<option value="'.$branch_row['id'].'">'.$branch_row['branch_name'].'</option>';
							
							

//$i++;
}
echo '</select>';
					//	</td>
					//</tr>';
//$micrarr[] = $micr_row['micr_code'];
//echo json_encode($brancharr);
//echo 'Bank='.$micr_row['dd_bank_name'].'Branch='.$micr_row['dd_bank_branch'].'IFS='.$micr_row['ifs_code'];



}