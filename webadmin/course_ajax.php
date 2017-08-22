<?
include_once("../utility/config.php");

include_once("../utility/dbclass.php");

include_once("../utility/functions.php");

include_once("../includes/other_functions.php");


if($_GET['info']=='Module')
 {
 	$objDB = new DB();
 	$Query = "select * from ".SITE_TABLE_PREFIX."manage_module where course_type ='".$_REQUEST['c_type']."' and is_active = 'Y'";
	$objDB->setQuery($Query);
	$rs = $objDB->select();
	
 ?>
 		 <select name="module_name" id="module_name">
		 	<option value="">--Select Module--</option>
<? 
	for($i=0; $i<count($rs); $i++)
		{
?>				 
			<option value="<?=$rs[$i]['id']?>"><?=$rs[$i]['module_name']?></option>
<?
		}
?>			
		 </select>
<?   
 }
?>