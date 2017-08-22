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
$pageOwner = "'superadmin'";
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
        $proposal_no = isset($data->sheets[0]["cells"][$x][2]) ? realTrim($data->sheets[0]["cells"][$x][2]) : '';

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

        //===============================================================
        //             $cnt = count($data->sheets[0]["cells"]);
        // $cnt -= 1; 
        // $cnt += 3;
        //for ($x=3; $x<=$cnt ; $x++)
        for ($x = 2; $x <= count($data->sheets[0]["cells"]); $x++) {

            $premium_type = isset($data->sheets[0]["cells"][$x][1]) ? realTrim($data->sheets[0]["cells"][$x][1]) : '';
            $proposal_number = isset($data->sheets[0]["cells"][$x][2]) ? realTrim($data->sheets[0]["cells"][$x][2]) : '';
            $policy_number = isset($data->sheets[0]["cells"][$x][3]) ? realTrim($data->sheets[0]["cells"][$x][3]) : '';
            $transaction_date = isset($data->sheets[0]["cells"][$x][4]) ? realTrim($data->sheets[0]["cells"][$x][4]) : '';
            $gross_amount = isset($data->sheets[0]["cells"][$x][5]) ? realTrim($data->sheets[0]["cells"][$x][5]) : '';
            $net_amount = isset($data->sheets[0]["cells"][$x][6]) ? realTrim($data->sheets[0]["cells"][$x][6]) : '';
            $service_tax = isset($data->sheets[0]["cells"][$x][7]) ? realTrim($data->sheets[0]["cells"][$x][7]) : '';
            $due_date = isset($data->sheets[0]["cells"][$x][8]) ? realTrim($data->sheets[0]["cells"][$x][8]) : '';
            $product = isset($data->sheets[0]["cells"][$x][9]) ? realTrim($data->sheets[0]["cells"][$x][9]) : '';
            $premium_status = isset($data->sheets[0]["cells"][$x][10]) ? realTrim($data->sheets[0]["cells"][$x][10]) : '';
            $frequency = isset($data->sheets[0]["cells"][$x][11]) ? realTrim($data->sheets[0]["cells"][$x][11]) : '';
            $policy_status = isset($data->sheets[0]["cells"][$x][12]) ? realTrim($data->sheets[0]["cells"][$x][12]) : '';
            $doc = isset($data->sheets[0]["cells"][$x][13]) ? realTrim($data->sheets[0]["cells"][$x][13]) : '';
            $cashiering_date = isset($data->sheets[0]["cells"][$x][14]) ? realTrim($data->sheets[0]["cells"][$x][14]) : '';
            $channel_code = isset($data->sheets[0]["cells"][$x][15]) ? realTrim($data->sheets[0]["cells"][$x][15]) : '';
            $channel_name = isset($data->sheets[0]["cells"][$x][16]) ? realTrim($data->sheets[0]["cells"][$x][16]) : '';
            $sp_code = isset($data->sheets[0]["cells"][$x][17]) ? realTrim($data->sheets[0]["cells"][$x][17]) : '';
            $sp_name = isset($data->sheets[0]["cells"][$x][18]) ? realTrim($data->sheets[0]["cells"][$x][18]) : '';

            $policy_holder_name = isset($data->sheets[0]["cells"][$x][19]) ? realTrim($data->sheets[0]["cells"][$x][19]) : '';
            $branch_name = isset($data->sheets[0]["cells"][$x][20]) ? realTrim($data->sheets[0]["cells"][$x][20]) : '';
            $branch_city = isset($data->sheets[0]["cells"][$x][21]) ? realTrim($data->sheets[0]["cells"][$x][21]) : '';
            $branch_state = isset($data->sheets[0]["cells"][$x][22]) ? realTrim($data->sheets[0]["cells"][$x][22]) : '';
            $file_no = isset($data->sheets[0]["cells"][$x][23]) ? realTrim($data->sheets[0]["cells"][$x][23]) : '';
            $reference_no = isset($data->sheets[0]["cells"][$x][24]) ? realTrim($data->sheets[0]["cells"][$x][24]) : '';
            $benefit_term = isset($data->sheets[0]["cells"][$x][25]) ? realTrim($data->sheets[0]["cells"][$x][25]) : '';
            $payment_term = isset($data->sheets[0]["cells"][$x][26]) ? realTrim($data->sheets[0]["cells"][$x][26]) : '';

            if ($transaction_date != '') {
                $transaction_UNIX_DATE = ($transaction_date - 25569) * 86400;
                $transaction_date = gmdate("d-m-Y", $transaction_UNIX_DATE);
            }

            if ($due_date != '') {
                $due_date_UNIX_DATE = ($due_date - 25569) * 86400;
                $due_date = gmdate("d-m-Y", $due_date_UNIX_DATE);
            }

            if ($doc != '') {
                $doc_UNIX_DATE = ($doc - 25569) * 86400;
                $doc = gmdate("d-m-Y", $doc_UNIX_DATE);
            }

            if ($cashiering_date != '') {
                $cashiering_date_UNIX_DATE = ($cashiering_date - 25569) * 86400;
                $cashiering_date = gmdate("d-m-Y", $cashiering_date_UNIX_DATE);
            }

            if (intval($proposal_number) != 0) {
                // echo "PPPPPPPPPPPPPP";
                // die();


                $Query = "SELECT * FROM installment_master WHERE  transaction_code = '" . $proposal_number . "' ";
                $objDB->setQuery($Query);
                $rsp = $objDB->select();

                if (count($rsp) > 0) {

                    // echo "PPPPPPPPPPPPPP";
                    // die();

                    $query = "SELECT * FROM premium_billed_report WHERE  proposal_number = '" . $proposal_number . "' ";
                    $objDB->setQuery($query);
                    $rst = $objDB->select();

                    if (count($rst) == 0) {


                        $firstUpdate = "INSERT premium_billed_report SET
            premium_type = '" . $premium_type . "',
            proposal_number = '" . $proposal_number . "',
            policy_number = '" . $policy_number . "',
            transaction_date = '" . $transaction_date . "',
            gross_amount = '" . $gross_amount . "',
            net_amount= '" . $net_amount . "',
            service_tax= '" . $service_tax . "',
            due_date= '" . $due_date . "',
            product= '" . $product . "',
            premium_status= '" . $premium_status . "',
            frequency= '" . $frequency . "',
            policy_status= '" . $policy_status . "',
            doc= '" . $doc . "',
            cashiering_date= '" . $cashiering_date . "',
            channel_code= '" . $channel_code . "',
            channel_name= '" . $channel_name . "',
            sp_code= '" . $sp_code . "',
            sp_name= '" . $sp_name . "',
            policy_holder_name= '" . $policy_holder_name . "',
            branch_name= '" . $branch_name . "',
            branch_city= '" . $branch_city . "',
            branch_state= '" . $branch_state . "',
            file_no= '" . $file_no . "',
            reference_no= '" . $reference_no . "',
            benefit_term= '" . $benefit_term . "',
            payment_term= '" . $payment_term . "',
            created_date= '" . date('Y-m-d') . "'

            ";

                        // echo $firstUpdate.'<br />';
                        // exit;
                        mysql_query($firstUpdate);
                    } else {
                        $firstUpdate = "UPDATE premium_billed_report SET
            premium_type = '" . $premium_type . "',
            proposal_number = '" . $proposal_number . "',
            policy_number = '" . $policy_number . "',
            transaction_date = '" . $transaction_date . "',
            gross_amount = '" . $gross_amount . "',
            net_amount= '" . $net_amount . "',
            service_tax= '" . $service_tax . "',
            due_date= '" . $due_date . "',
            product= '" . $product . "',
            premium_status= '" . $premium_status . "',
            frequency= '" . $frequency . "',
            policy_status= '" . $policy_status . "',
            doc= '" . $doc . "',
            cashiering_date= '" . $cashiering_date . "',
            channel_code= '" . $channel_code . "',
            channel_name= '" . $channel_name . "',
            sp_code= '" . $sp_code . "',
            sp_name= '" . $sp_name . "',
            policy_holder_name= '" . $policy_holder_name . "',
            branch_name= '" . $branch_name . "',
            branch_city= '" . $branch_city . "',
            branch_state= '" . $branch_state . "',
            file_no= '" . $file_no . "',
            reference_no= '" . $reference_no . "',
            benefit_term= '" . $benefit_term . "',
            payment_term= '" . $payment_term . "',
            created_date= '" . date('Y-m-d') . "'
            WHERE 
            proposal_number = '" . $proposal_number . "'
            ";

                        // echo $firstUpdate.'<br />';
                        // exit;
                        mysql_query($firstUpdate);
                    }
                }
            }

            //}
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

            <tr class="TDHEAD"> 
                <td colspan="3">Upload Premium Billed Report<!-- Upload ZIP of the XML --></td>
            </tr>
            <tr> 
                <td colspan="3" style="padding-left: 70px;" align="left">With the help of this page administrator can upload the EXCEL file generated by the the Premium Billed Report for existing policy no.  

                </td>
            </tr>
            <tr> 
                <td colspan="3" style="padding-left: 70px;" align="left"><b><font color="#ff0000">All 
                        * marked fields are mandatory</font></b></td>
            </tr>

        <input type="hidden" name="branch_name" id="branch_name" value="<?php echo $_SESSION[ADMIN_SESSION_VAR]; ?>">
        <tr><td align="center"><a href="<?php echo URL; ?>webadmin/policy_no_list/PREMIUM_BILLED_REPORT.xls">Click To Download Excel Format</a></td></tr>
        <tr> 
            <td class="tbllogin" valign="top" align="right">Upload Premium Billed Report (In .xls format)<font color="#ff0000">*</font></td>
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