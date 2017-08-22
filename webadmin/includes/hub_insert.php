<?php

include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
//print_r($_POST);

set_time_limit(0); 

chkPageAccess($USER_TYPE, 'superadmin,admin'); // $USER_TYPE is coming from index.php

if(isset($_POST['btnInsert']))
{
	echo 'Hello';
	$selAdmin = mysql_query("SELECT id from admin order by id asc");
	while($getAdmin = mysql_fetch_array($selAdmin))
	{
		echo $getAdmin['id'].'<br />';
		//mysql_query("INSERT INTO branch_hub_entry set 
			//branch_id=".$getAdmin['id'].",
			//hub_id=2,
			//hub_since='2012-02-01'
		//");
		echo "INSERT INTO branch_hub_entry set 
			branch_id=".$getAdmin['id'].",
			hub_id=2,
			hub_since='2012-02-01'
		";
		echo '<br />Insertion is blocked for security reason';
	}
}


// Write functions here



?>


<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>

<form name="addForm" id="addForm" action="" method="post" style="border:0px solid red">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
		<tr>
			<td><input type="submit" value="insert" name="btnInsert"></td>
		</tr> 

  </tbody>
</table>
</form>

<?php //$objDB->close(); ?>

