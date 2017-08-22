<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");
date_default_timezone_set('Asia/Calcutta');
$msg = '';

//error_reporting(E_ALL);


//$PREMIUM_TYPE = 'INITIAL PAYMENT';
$objDB = new DB();
$pageOwner = "'branch','admin','superadmin','subsuperadmin','hub'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner); 
//echo 'SITE IS UNDER MAINTENANCE';
#exit;

$id = base64_decode($_GET['id']);



$bnchid = mysql_query("SELECT * FROM admin WHERE id='".$_SESSION[ADMIN_SESSION_VAR]."'");
$bnchnum = mysql_num_rows($bnchid);
if($bnchnum > 0)
{
        $getbnch = mysql_fetch_assoc($bnchid);
        $sp_details_id = $getbnch['sp_details_id'];

        $new_sp =  explode("~", $sp_details_id);
        $imp_new_sp = implode(",", $new_sp);
        
        if($_SESSION[ROLE_ID] == "1" || $_SESSION[ROLE_ID] == "2"){
            $Query = "SELECT * FROM sp_master ";
        }else{
            $Query = "SELECT * FROM sp_master WHERE id IN(".$imp_new_sp.") ";
        }
        $objDB->setQuery($Query);
        $rsm = $objDB->select();
        
        
}else{
        $sp_name = "";
        $sp_code = "";
}



if(isset($_POST['branch_id']) && $_POST['branch_id'] != '')
{

	extract($_POST);


				$set_hub = "SELECT hub_id FROM branch_hub_entry WHERE branch_id = '".$_POST['branch_id']."' order by hub_since desc LIMIT 0,1";
				
				$set_hub_data = mysql_query($set_hub);
				$get_hub = mysql_fetch_array($set_hub_data);
                                   
                                $hub_name = new_hub_name($_POST['branch_id']);
                                $branch_name = find_branch_name($_POST['branch_id']);
                                

                                if(isset($_FILES['zip']) && $_FILES['zip']['tmp_name'] != '')
                                {

                                    $Query = "SELECT * FROM installment_master WHERE id='".$id."' ";
                                    $objDB->setQuery($Query);
                                    $rsk = $objDB->select();
                                    
                                    if($rsk[0]['upload_file'] != '' && file_exists('upload_zip/'.$rsk[0]['upload_file'])){
                                        unlink('upload_zip/'.$rsk[0]['upload_file']);
                                        chmod('upload_zip/'.$filename, 0777);
                                    }
                                    
                                    $filenameArr = explode('.', $_FILES['zip']['name']);
                                    $filenameWOExtension = $filenameArr[1];
                                    
                                    if($filenameWOExtension == 'rar' || $filenameWOExtension == 'zip'){
                                        $filename = time().$_FILES['zip']['name'];
                                        //die();
                                        move_uploaded_file($_FILES['zip']['tmp_name'], 'upload_zip/'.$filename); // file uploaded
                                        chmod('upload_zip/'.$filename, 0777);
                                    }else{
                                        $_SESSION[ERROR_MSG] = "You have to enter zip/rar file.";
                                        header("location: ".URL."webadmin/sp_details.php?id=".base64_encode($id)."");
                                        exit();
                                    }
                                }
                                
                                if($filename != ''){
                                    $query = " upload_file = '".$filename."' ";
                                }
                                
                                $sp_info = explode("~", $sp_info);
                                
                                $sp_name = $sp_info[0];
                                $sp_code = $sp_info[1];
                                
                                                             

				$insert_installment = "UPDATE installment_master SET 
                                                    
                                                    sp_name = '".realTrim($sp_name)."',
                                                    sp_code = '".realTrim($sp_code)."',".$query."

                                                    WHERE id = '".$id."'
				";
//                                echo "<pre>";
//				echo '<br />'.$insert_installment;
//				exit;
				mysql_query($insert_installment);
				
                                ?>
<script type="text/javascript">

	window.opener.document.addForm.submit();
	window.close();

</script>
<?php


}

if(isset($id)){

        $selTransaction = mysql_query("SELECT * FROM installment_master WHERE id='".$id."'");
	$numTransaction = mysql_num_rows($selTransaction);
	if($numTransaction > 0)
		{
		  
			$getTransaction = mysql_fetch_assoc($selTransaction);

			$id = $getTransaction['id'];
			$get_sp_name = $getTransaction['sp_name'];
                        $get_sp_code = $getTransaction['sp_code'];
                        $get_upload_file = $getTransaction['upload_file'];
                        

		}
		else
		{
			echo 'No record found';
			exit;
		}
}


$id = $_SESSION[ADMIN_SESSION_VAR];
$selBranch = mysql_query("SELECT branch_name, id FROM admin WHERE role_id NOT IN(1,2,3,5,6) AND branch_user_id = 0 ORDER BY branch_name ASC");
/*
echo "<pre>";
print_r($_POST);
die();
*/
?>

	<link rel="shortcut icon" type="image/x-icon" href="<?=URL?>images/favicon.ico">
<link rel="stylesheet" href="<?=URL?>webadmin/css/default.css">
<link rel="stylesheet" href="<?=URL?>webadmin/css/dropdown.css">
<link rel="stylesheet" href="<?=URL?>css/validationEngine.jquery.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<link rel="stylesheet" href="<?=URL?>css/template.css" type="text/css" media="screen" title="no title" charset="utf-8" />
<script src="<?=URL?>js/jquery.min.js" type="text/javascript"></script>
<script src="<?=URL?>js/jquery.validationEngine-en.js" type="text/javascript"></script>
<script src="<?=URL?>js/jquery.validationEngine.js" type="text/javascript"></script>


<script type="text/javascript">
	function dochk()
	{
            var zip = document.getElementById('zip').value; //sp_info
            var sp_info = document.getElementById('sp_info').value;
            
        
        if(sp_info == ''){
                alert("Please SP Info.");
                document.addForm.sp_info.focus();
                return false;
            }
        
        if(zip == ''){
                alert("Please Upload file.");
                document.addForm.zip.focus();
                return false;
            }

		$('#addbtn').hide();
	}

</script>



<link type="text/css" rel="stylesheet" href="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?=URL?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" src="<?=URL?>js/site_scripts.js"></script>
<form name="addForm" id="addForm" action="" method="post" onsubmit="return dochk()" enctype="multipart/form-data">
<TABLE class="table_border" cellSpacing=2 cellPadding=5 width="100%" align=center border=0>
  <tbody>
    <tr> 
      <td colspan="3">
        <? showMessage(); ?>      </td>
    </tr>
    <tr class="TDHEAD"> 
        <?php
        if(!isset($id)){
        ?>
      <td colspan="3">Manual Entry (Star Health)</td>
      <?php
        }else{
       ?>
      <td colspan="3">SP Details</td>
      <?php
        }
      ?>
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


    <?php
    if(!isset($id)){
    ?>
		<input type="hidden" name="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
                <?php
    }else{
        ?>
                <input type="hidden" name="branch_id" value="<?php echo $getTransaction['branch_id']; ?>">
            <?php
    }
                ?>

    <input type="hidden" name="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
    

    
<!--    <tr> 
      <td class="tbllogin" valign="top" align="right">SP Name</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
          <?php echo $sp_name; ?>
          <input name="sp_name" id="sp_name" type="hidden" value="<?php echo $sp_name; ?>" ></td>
    </tr>-->
    
    <tr> 
      <td class="tbllogin" valign="top" align="right">SP Info <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
          <select class="tbllogin" name="sp_info" id="sp_info">
              <option value="">-- Please Select --</option>
              <?php
              for($j=0; $j<count($rsm); $j++){

              ?>
              <option value="<?php echo $rsm[$j]['sp_name']."~".$rsm[$j]['sp_code'];?>"   <?php if($get_sp_code == $rsm[$j]['sp_code']){ echo "selected"; } ?>   ><?php echo $rsm[$j]['sp_name']." (".$rsm[$j]['sp_code'].")";?></option>
              <?php
              }
              ?>
          </select>
    </tr>
    

    
<!--    <tr> 
      <td class="tbllogin" valign="top" align="right">SP Code</td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left">
          <?php echo $sp_code; ?>
          <input name="sp_code" id="sp_code" type="hidden" value="<?php echo $sp_code; ?>" ></td>
	   <td class="tbllogin" valign="top" align="left">&nbsp;</td>
    </tr>-->
    
    <tr> 
      <td class="tbllogin" valign="top" align="right">Upload ZIP/RAR file <font color="#ff0000">*</font></td>
      <td class="tbllogin" valign="top" align="center">:</td>
      <td valign="top" align="left"><input name="zip" id="zip" type="file" class="inplogin" >
      <?php
      if(!empty($get_upload_file)){
      ?>
      <a href="<?php echo URL;?>webadmin/upload_zip/<?php echo $get_upload_file;?>">Download</a>
      <?php
      }
      ?>
      </td>
    </tr>
    


    <tr> 
      <td colspan="2">&nbsp;</td>
      <td>
          <input type="hidden" id="a" name="a" value="change_pass"> 
        <input value="Update" class="inplogin" type="submit" id="addbtn" onclick="return dochk()"> 
        &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
    </tr>
  </tbody>
</table>
</form>

