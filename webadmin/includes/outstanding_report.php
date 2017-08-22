<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("new_functions.php");
date_default_timezone_set('Asia/Calcutta');

error_reporting(0);

set_time_limit(0);
ignore_user_abort(true);
//echo URL;
$pageOwner = "'superadmin','admin'";
chkPageAccess($_SESSION[ROLE_ID], $pageOwner);
$msg = '';
$objDB = new DB();

function find_application_id($policy_no) { // This function will determine whether this is coming from GAP
    $objDB = new DB();
    $gap_status = 0;

    $selGap_status = mysql_query("SELECT id FROM installment_master WHERE application_no='" . $policy_no . "'");
    $numGap_status = mysql_num_rows($selGap_status);
    if ($numGap_status > 0) {
        $getGap_status = mysql_fetch_array($selGap_status);
        $gap_status = $getGap_status['id'];
    }
    return $gap_status;
}

######## DEALING WITH EXCEL DATA STARTS #############


if (isset($_FILES['zip']) && $_FILES['zip']['tmp_name'] != '') {


    $temp_filename = $_FILES['zip']['tmp_name'];

    $msg = 'Successfully Uploaded';


    require_once 'Excel/reader.php';

    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    #$data->read('insurance_policies/'.$filename); // upload the xl sheet by renaming it to exceltestsheet.xls

    $data->read($temp_filename);




    $scnt = "0";

    $array_chk = array();

    for ($x = 2; $x <= count($data->sheets[0]["cells"]); $x++) {
        $proposal_no = isset($data->sheets[0]["cells"][$x][8]) ? realTrim($data->sheets[0]["cells"][$x][8]) : '';

        if ($proposal_no != '') {
            $scnt += "1";
            array_push($array_chk, $proposal_no);
        }

        $arr_unk = array_unique($array_chk);
    }

    if ($scnt != count($arr_unk)) {
        $_SESSION[ERROR_MSG] = "Duplicate Record Exist...";
        header("location: index.php?p=" . $_REQUEST['p'] . "");
        exit();
    } else {


        for ($x = 2; $x <= count($data->sheets[0]["cells"]); $x++) {
            // echo "PPPPPPPPPPPPPP".count($data->sheets[0]["cells"]);
            // 	die();
            // echo "<pre>";
            // print_r($data->sheets[0]["cells"][2]);
            // echo "<br>".$data->sheets[0]["cells"][$x][8];

            $channel_name = isset($data->sheets[0]["cells"][$x][1]) ? realTrim($data->sheets[0]["cells"][$x][1]) : '';

            $sp_code = isset($data->sheets[0]["cells"][$x][2]) ? realTrim($data->sheets[0]["cells"][$x][2]) : '';
            $sp_name = isset($data->sheets[0]["cells"][$x][3]) ? realTrim($data->sheets[0]["cells"][$x][3]) : '';
            $channel_branch_code = isset($data->sheets[0]["cells"][$x][4]) ? realTrim($data->sheets[0]["cells"][$x][4]) : '';
            $branch_name = isset($data->sheets[0]["cells"][$x][5]) ? realTrim($data->sheets[0]["cells"][$x][5]) : '';
            $branch_state = isset($data->sheets[0]["cells"][$x][6]) ? realTrim($data->sheets[0]["cells"][$x][6]) : '';
            $branch_city = isset($data->sheets[0]["cells"][$x][7]) ? realTrim($data->sheets[0]["cells"][$x][7]) : '';
            $proposal_no = isset($data->sheets[0]["cells"][$x][8]) ? realTrim($data->sheets[0]["cells"][$x][8]) : '';


            $current_proposal_status = isset($data->sheets[0]["cells"][$x][9]) ? realTrim($data->sheets[0]["cells"][$x][9]) : '';
            $cashiering_date = isset($data->sheets[0]["cells"][$x][10]) ? realTrim($data->sheets[0]["cells"][$x][10]) : '';
            $holder_name = isset($data->sheets[0]["cells"][$x][11]) ? realTrim($data->sheets[0]["cells"][$x][11]) : '';

            $product = isset($data->sheets[0]["cells"][$x][12]) ? realTrim($data->sheets[0]["cells"][$x][12]) : '';
            $cashier_branch = isset($data->sheets[0]["cells"][$x][13]) ? realTrim($data->sheets[0]["cells"][$x][13]) : '';
            $raise_date = isset($data->sheets[0]["cells"][$x][14]) ? realTrim($data->sheets[0]["cells"][$x][14]) : '';
            $open_oldest_date = isset($data->sheets[0]["cells"][$x][15]) ? realTrim($data->sheets[0]["cells"][$x][15]) : '';
            $open_latest_date = isset($data->sheets[0]["cells"][$x][16]) ? realTrim($data->sheets[0]["cells"][$x][16]) : '';
            $flag = isset($data->sheets[0]["cells"][$x][17]) ? realTrim($data->sheets[0]["cells"][$x][17]) : '';
            $medical = isset($data->sheets[0]["cells"][$x][18]) ? realTrim($data->sheets[0]["cells"][$x][18]) : '';
            $non_medical = isset($data->sheets[0]["cells"][$x][19]) ? realTrim($data->sheets[0]["cells"][$x][19]) : '';
            $comments = isset($data->sheets[0]["cells"][$x][20]) ? realTrim($data->sheets[0]["cells"][$x][20]) : '';
            $closed_date = isset($data->sheets[0]["cells"][$x][21]) ? realTrim($data->sheets[0]["cells"][$x][21]) : '';



            $category = isset($data->sheets[0]["cells"][$x][22]) ? realTrim($data->sheets[0]["cells"][$x][22]) : '';
            $file_number = isset($data->sheets[0]["cells"][$x][23]) ? realTrim($data->sheets[0]["cells"][$x][23]) : '';
            $number = isset($data->sheets[0]["cells"][$x][24]) ? realTrim($data->sheets[0]["cells"][$x][24]) : '';
            $office_number = isset($data->sheets[0]["cells"][$x][25]) ? realTrim($data->sheets[0]["cells"][$x][25]) : '';
            $mobile_number = isset($data->sheets[0]["cells"][$x][26]) ? realTrim($data->sheets[0]["cells"][$x][26]) : '';
            $email_id = isset($data->sheets[0]["cells"][$x][27]) ? realTrim($data->sheets[0]["cells"][$x][27]) : '';
            $cashiering_amount = isset($data->sheets[0]["cells"][$x][28]) ? realTrim($data->sheets[0]["cells"][$x][28]) : '';

            if ($cashiering_date != '') {
                $cashiering_date_UNIX_DATE = ($cashiering_date - 25569) * 86400;
                $cashiering_date = gmdate("d-m-Y", $cashiering_date_UNIX_DATE);
            }

            if ($raise_date != '') {
                $raise_date_UNIX_DATE = ($raise_date - 25569) * 86400;
                $raise_date = gmdate("d-m-Y", $raise_date_UNIX_DATE);
            }

            if ($open_oldest_date != '') {
                $open_oldest_date_UNIX_DATE = ($open_oldest_date - 25569) * 86400;
                $open_oldest_date = gmdate("d-m-Y", $open_oldest_date_UNIX_DATE);
            }

            if ($open_latest_date != '') {
                $open_latest_date_UNIX_DATE = ($open_latest_date - 25569) * 86400;
                $open_latest_date = gmdate("d-m-Y", $open_latest_date_UNIX_DATE);
            }

            if ($closed_date != '') {
                $closed_date_UNIX_DATE = ($closed_date - 25569) * 86400;
                $closed_date = gmdate("d-m-Y", $closed_date_UNIX_DATE);
            }


            if (intval($proposal_no) != 0) {
                // echo "PPPPPPPPPPPPPP";
                // die();


                $Query = "SELECT * FROM installment_master WHERE  transaction_code = '" . $proposal_no . "' ";
                $objDB->setQuery($Query);
                $rsp = $objDB->select();

                if (count($rsp) > 0) {

                    $query = "SELECT * FROM outstanding_report WHERE  proposal_no = '" . $proposal_no . "' ";
                    $objDB->setQuery($query);
                    $rst = $objDB->select();

                    if (count($rst) == 0) {

                        // echo "INSERT";
                        // die();

                        $firstUpdate = "INSERT outstanding_report SET
				channel_name = '" . $channel_name . "',
				sp_code = '" . $sp_code . "',
				sp_name = '" . $sp_name . "',
				channel_branch_code = '" . $channel_branch_code . "',
				branch_name = '" . $branch_name . "',
				branch_state= '" . $branch_state . "',

				branch_city= '" . $branch_city . "',
				proposal_no= '" . $proposal_no . "',
				current_proposal_status= '" . $current_proposal_status . "',
				cashiering_date= '" . $cashiering_date . "',
				holder_name= '" . $holder_name . "',
				product= '" . $product . "',
				cashier_branch= '" . $cashier_branch . "',
				raise_date= '" . $raise_date . "',
				open_oldest_date= '" . $open_oldest_date . "',
				open_latest_date= '" . $open_latest_date . "',
				flag= '" . $flag . "',
				medical= '" . $medical . "',
				non_medical= '" . $non_medical . "',
				comments= '" . $comments . "',
				closed_date= '" . $closed_date . "',
				category= '" . $category . "',
				file_number= '" . $file_number . "',
				number= '" . $number . "',
				office_number= '" . $office_number . "',
				mobile_number= '" . $mobile_number . "',
				email_id= '" . $email_id . "',
				create_date= '" . date('Y-m-d') . "',
				cashiering_amount= '" . $cashiering_amount . "'

				";

                        // echo $firstUpdate.'<br />';
                        // exit;
                        mysql_query($firstUpdate);
                    } else {

                        // echo "UPDATE";
                        // 	die();
                        $firstUpdate = "UPDATE outstanding_report SET
				channel_name = '" . $channel_name . "',
				sp_code = '" . $sp_code . "',
				sp_name = '" . $sp_name . "',
				channel_branch_code = '" . $channel_branch_code . "',
				branch_name = '" . $branch_name . "',
				branch_state= '" . $branch_state . "',

				branch_city= '" . $branch_city . "',
				proposal_no= '" . $proposal_no . "',
				current_proposal_status= '" . $current_proposal_status . "',
				cashiering_date= '" . $cashiering_date . "',
				holder_name= '" . $holder_name . "',
				product= '" . $product . "',
				cashier_branch= '" . $cashier_branch . "',
				raise_date= '" . $raise_date . "',
				open_oldest_date= '" . $open_oldest_date . "',
				open_latest_date= '" . $open_latest_date . "',
				flag= '" . $flag . "',
				medical= '" . $medical . "',
				non_medical= '" . $non_medical . "',
				comments= '" . $comments . "',
				closed_date= '" . $closed_date . "',
				category= '" . $category . "',
				file_number= '" . $file_number . "',
				number= '" . $number . "',
				office_number= '" . $office_number . "',
				mobile_number= '" . $mobile_number . "',
				email_id= '" . $email_id . "',
				create_date= '" . date('Y-m-d') . "',
				cashiering_amount= '" . $cashiering_amount . "'
				WHERE
				proposal_no= '" . $proposal_no . "'
				";

                        // echo $firstUpdate.'<br />';
                        // exit;
                        mysql_query($firstUpdate);
                    }
                }
            }
        }
    } // Else Brace
}

######## DEALING WITH XML DATA ENDS ###############
#### EXTRACTING THE FILE ENDS
?>
<link type="text/css" rel="stylesheet" href="<?= URL ?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="<?= URL ?>dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" src="<?= URL ?>js/site_scripts.js"></script>
<script type="text/javascript">
<!--

    function dochk()
    {

        if (document.addForm.zip.value.search(/\S/) == -1)
        {
            alert("Please select the excel file");
            document.addForm.zip.focus();
            return false;
        }
        if (!chkxls(document.addForm.zip.value))
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
        <?= $msg; ?>
                </td>
            </tr>
        <?php
//			$service_status = service_status(2);
//			if($service_status == 0)
//			{
        ?>
<!--		<tr> 
<td colspan="3" >
<marquee>
                    <h1 style="color:#ff0000;">This facility is currently deactivated by the administrator. If you want to add any record manually please <a href="<?= URL; ?>webadmin/index.php?p=manual_entry_branch" style="font-size:18px;">click here</a>.
                    </h1>
             </marquee>
</td>
</tr>-->
<?php
//exit; 
//}
?>
            <tr class="TDHEAD"> 
                <td colspan="3">Upload Outstanding Report<!-- Upload ZIP of the XML --></td>
            </tr>
            <tr> 
                <td colspan="3" style="padding-left: 70px;" align="left">With the help of this page administrator can upload the EXCEL file generated by the the Outstanding Report for existing policy no.  

                </td>
            </tr>
            <tr> 
                <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
                        * marked fields are mandatory</font></b></td>
            </tr>

        <input type="hidden" name="branch_name" id="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
        <tr><td align="center"><a href="<?php echo URL; ?>webadmin/policy_no_list/OUTSTANDING_REPORT.xls">Click To Download Excel Format</a></td></tr>
        <tr> 
            <td class="tbllogin" valign="top" align="right">Upload Outstanding Report (In .xls format)<font color="#ff0000">*</font></td>
            <td class="tbllogin" valign="top" align="center">:</td>
            <td valign="top" align="left"><input name="zip" id="zip" type="file" class="inplogin" ></td>
        </tr>

        <tr> 
            <td colspan="2">&nbsp;</td>
            <td> <input type="hidden" id="a" name="a" value="change_pass"> 
                <input value="Update" class="inplogin" type="submit" onclick="return dochk()"> 
                &nbsp;&nbsp;&nbsp; <input name="Reset" type="reset" class="inplogin" value="Reset"></td>
        </tr>
        </tbody>
    </table>
<?php
//$objDB->close(); ?>