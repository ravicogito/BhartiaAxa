<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../utility/functions.php");
include_once("includes/new_functions.php");

$objDB = new DB();

$where = "WHERE is_deleted=0 ";
$OrderBY = " ORDER BY id DESC ";

if(isset($branch_name))
	{
		$_SESSION['branch_name'] = realTrim($branch_name);
	}

	if(isset($from_date))
	{
		$_SESSION['from_date'] = $from_date;
	}

	if(isset($to_date))
	{
		$_SESSION['to_date'] = $to_date;
	}

	if(isset($receipt_number))
	{
		$_SESSION['receipt_number'] = realTrim($receipt_number);
	}

	if(isset($application_no))
	{
		$_SESSION['application_no'] = realTrim($application_no);
	}

	if(isset($folio_no))
	{
		$_SESSION['folio_no'] = realTrim($folio_no);
	}

	if(isset($customer_id))
	{
		$_SESSION['customer_id'] = realTrim($customer_id);
	}
	

	if(isset($first_name))
	{
		$_SESSION['first_name'] = realTrim($first_name);
	}
	if(isset($last_name))
	{
		$_SESSION['last_name'] = realTrim($last_name);
	}

	if(isset($_SESSION['branch_name']) && $_SESSION['branch_name'] != '') // this is actually branch id
	{
		$where.= ' AND branch_id="'.$_SESSION['branch_name'].'"';
	}

	if(isset($_SESSION['from_date']) && $_SESSION['from_date'] != '') 
	{
		$where.= ' AND deposit_date >="'.date('Y-m-d', strtotime($_SESSION['from_date'])).'"';
	}

	if(isset($_SESSION['to_date']) && $_SESSION['to_date'] != '') 
	{
		$where.= ' AND deposit_date <="'.date('Y-m-d', strtotime($_SESSION['to_date'])).'"';
	}

	if(isset($_SESSION['receipt_number']) && $_SESSION['receipt_number'] != '') 
	{
		$where.= ' AND receipt_number LIKE "%'.$_SESSION['receipt_number'].'%"';
	}

	if(isset($_SESSION['application_no']) && $_SESSION['application_no'] != '') 
	{
		$where.= ' AND application_no LIKE "%'.$_SESSION['application_no'].'%"';
	}

	if(isset($_SESSION['folio_no']) && $_SESSION['folio_no'] != '') 
	{
		$derivedFolioNo = find_folio_id($_SESSION['folio_no']);
		$where.= ' AND folio_no_id = "'.$derivedFolioNo.'"';
	}

	if(isset($_SESSION['customer_id']) && $_SESSION['customer_id'] != '') 
	{
		$derivedCustID = find_id_through_customer_id($_SESSION['customer_id']);
		$where.= ' AND customer_id = "'.$derivedCustID.'"';
	}

	if(isset($_SESSION['first_name']) && $_SESSION['first_name'] != '') 
	{
		$firstNameString = find_id_through_first_name($_SESSION['first_name']);
		#echo $firstNameString;
		if($firstNameString != '')
		{
			$where.= ' AND customer_id IN ( '.$firstNameString.')';
		}
	}

	if(isset($_SESSION['last_name']) && $_SESSION['last_name'] != '') 
	{
		$lastNameString = find_id_through_last_name($_SESSION['last_name']);
		#echo $lastNameString;
		if($lastNameString != '')
		{
			$where.= ' AND customer_id IN ( '.$lastNameString.')';
		}
	}

	##### CODE FOR SEARCHING 


$selTransaction = mysql_query("SELECT * FROM installment_master ".$where.$OrderBY);
$numTransaction = mysql_num_rows($selTransaction);
	

/*
echo "<pre>";
print_r($_POST);
die();
*/

#####################################
// return all available tables 
//$result_tbl = mysql_query( "SHOW TABLES FROM ".DB_NAME, $dbhandle );


$output = "<?xml version=\"1.0\" standalone=\"yes\"?>\n";
$output .= "<DocumentElement>\n";

// iterate over each table and return the fields for each table
if($numTransaction > 0)
{
	while($getTransaction = mysql_fetch_assoc($selTransaction))
	{
		$output .= "\t<Payment>\n";

		$customer_ID = $getTransaction['customer_id'];
		$selMasterRecord = mysql_query("SELECT * FROM customer_master WHERE id='".$customer_ID."'");
		if(mysql_num_rows($selMasterRecord) > 0)
		{	
			$getMasterRecord = mysql_fetch_assoc($selMasterRecord);
		}

		$folio_no_id = $getTransaction['folio_no_id'];
		$selFolio = mysql_query("SELECT * FROM customer_folio_no WHERE id='".$folio_no_id."'");
		$numFolio = mysql_num_rows($selFolio);

		if($numFolio > 0)
		{
			$getFolioRecord = mysql_fetch_assoc($selFolio);
		}

		 $output .= "\t\t<APPLICATIONFORMNO>".$getFolioRecord['application_no'];
		 $output .= "</APPLICATIONFORMNO>\n";

		 //$output .= "\t\t<DATEOFFILLINGFORM>".date('d/m/Y', strtotime($getTransaction['deposit_date']));
		 $output .= "\t\t<DEPOSITDATE>".$getTransaction['deposit_date'];
		 $output .= "</DEPOSITDATE>\n";

		 $output .= "\t\t<FIRSTNAME>".$getMasterRecord['first_name'];
		 $output .= "</FIRSTNAME>\n";

		 $output .= "\t\t<MIDDLENAME>".$getMasterRecord['middle_name'];
		 $output .= "</MIDDLENAME>\n";

		 $output .= "\t\t<LASTNAME>".$getMasterRecord['last_name'];
		 $output .= "</LASTNAME>\n";

		 $output .= "\t\t<FATHERNAME>".$getMasterRecord['fathers_name'];
		 $output .= "</FATHERNAME>\n";

		 $output .= "\t\t<DOB>".$getMasterRecord['dob_original'];
		 $output .= "</DOB>\n";

		 $output .= "\t\t<GENDER>".$getMasterRecord['gender'];
		 $output .= "</GENDER>\n";

		 $output .= "\t\t<GuardiansName>".$getMasterRecord['guardian_name'];
		 $output .= "</GuardiansName>\n";

		 $output .= "\t\t<AGENTNAME>".$getTransaction['agent_name'];
		 $output .= "</AGENTNAME>\n";

		 $output .= "\t\t<AGENTCODE>".$getTransaction['agent_code'];
		 $output .= "</AGENTCODE>\n";

		 $output .= "\t\t<ADDRESSLINE1>".$getMasterRecord['address1'];
		 $output .= "</ADDRESSLINE1>\n";

		 $output .= "\t\t<CITY>".$getMasterRecord['city'];
		 $output .= "</CITY>\n";

		 $output .= "\t\t<STATE>".find_place_name($getMasterRecord['state']);
		 $output .= "</STATE>\n";

		 $output .= "\t\t<PIN>".$getMasterRecord['zip'];
		 $output .= "</PIN>\n";

		 $output .= "\t\t<MOBILE>".$getMasterRecord['phone'];
		 $output .= "</MOBILE>\n";

		 $output .= "\t\t<PAN>".$getMasterRecord['pan'];
		 $output .= "</PAN>\n";

		 $output .= "\t\t<EMAIL>".$getMasterRecord['email'];
		 $output .= "</EMAIL>\n";


		 $output .= "\t\t<NOMINEENAME>".$getFolioRecord['nominee_name'];
		 $output .= "</NOMINEENAME>\n";

		 $output .= "\t\t<RELEATIONSHIPTYPE>".$getFolioRecord['nominee_relationship'];
		 $output .= "</RELEATIONSHIPTYPE>\n";

		 $output .= "\t\t<OUTLETCODE>".find_branch_code($getTransaction['branch_id']);
		 $output .= "</OUTLETCODE>\n";

		 $output .= "\t\t<OUTLETNAME>".find_branch_name($getTransaction['branch_id']);
		 $output .= "</OUTLETNAME>\n";

		 $output .= "\t\t<CATEGORY>".$getTransaction['category'];
		 $output .= "</CATEGORY>\n";

		 $output .= "\t\t<FOLIONO>".$getFolioRecord['folio_no'];
		 $output .= "</FOLIONO>\n";
		 
		 $output .= "\t\t<TENURE>".$getFolioRecord['tenure'];
		 $output .= "</TENURE>\n";

		 

		 $output .= "\t\t<COMMODITY>".find_commodity_name($getFolioRecord['commodity_name']);
		 $output .= "</COMMODITY>\n";

		 $output .= "\t\t<BONUS_PERCENTAGE>".number_format($getFolioRecord['bonus_percentage'],2,'.','');
		 $output .= "</BONUS_PERCENTAGE>\n";

		 $output .= "\t\t<CommitedAdvance>".$getFolioRecord['committed_amount'];
		 $output .= "</CommitedAdvance>\n";

		 $output .= "\t\t<RECEIPT_NO>".$getTransaction['receipt_number'];
		 $output .= "</RECEIPT_NO>\n";

		 $output .= "\t\t<PaymentType>".$getTransaction['payment_mode'];
		 $output .= "</PaymentType>\n";

		 $output .= "\t\t<AMOUNT>".$getTransaction['amount'];
		 $output .= "</AMOUNT>\n";

		 $output .= "\t\t<RECEIPT_TYPE>".$getTransaction['payment_type'];
		 $output .= "</RECEIPT_TYPE>\n";

		 $output .= "\t\t<ChequeDDNumber>".$getTransaction['dd_number'];
		 $output .= "</ChequeDDNumber>\n";

		 $output .= "\t\t<ChequeDDBankName>".$getTransaction['dd_bank_name'];
		 $output .= "</ChequeDDBankName>\n";
//echo $getTransaction['dd_date']; exit;
		 $ddDate = $getTransaction['dd_date'] == '1970-01-01' ? '' : date('d/m/Y', strtotime($getTransaction['dd_date']));
		 
		 $output .= "\t\t<ChequeDDDate>".$ddDate;
		 $output .= "</ChequeDDDate>\n";

		 $output .= "\t\t<PAYINSLIP>"; // USELESS
		 $output .= "</PAYINSLIP>\n";

		 $output .= "\t\t<PAYINSLIPNO>";
		 $output .= "</PAYINSLIPNO>\n";

		 $output .= "\t\t<CUSTOMER_ID>".$getMasterRecord['customer_id'];
		 $output .= "</CUSTOMER_ID>\n";

		 $output .= "\t\t<INSTALLMENT>".$getTransaction['installment'];
		 $output .= "</INSTALLMENT>\n";

		 $output .= "\t\t<BONUSPERCENTAGE>".$getFolioRecord['bonus_percentage'];
		 $output .= "</BONUSPERCENTAGE>\n";

		 $output .= "\t\t<STARTDATE>".$getFolioRecord['start_date'];
		 $output .= "</STARTDATE>\n";

		 $output .= "\t\t<ENDDATE>".$getFolioRecord['end_date'];
		 $output .= "</ENDDATE>\n";

		 $output .= "\t\t<PRODUCTCODE>".$getFolioRecord['product_code'];
		 $output .= "</PRODUCTCODE>\n";

		 $output .= "\t\t<PRODUCTNAME>".$getFolioRecord['product_name'];
		 $output .= "</PRODUCTNAME>\n";

		 $output .= "\t\t<DISCOUNTPERCENTAGE>".$getFolioRecord['discount_percentage'];
		 $output .= "</DISCOUNTPERCENTAGE>\n";

		 //$output .= "\t\t<TOTALPREMIUMGIVEN>".$getFolioRecord['total_premium_given'];
		 //$output .= "</TOTALPREMIUMGIVEN>\n";
		 
		 $output .= "\t</Payment>\n";
	}	 
}
$output .= "</DocumentElement>";

// tell the browser what kind of file is come in
header('Content-type: "text/xml"; charset="utf8"');
header('Content-disposition: attachment; filename="AJL_XML_'.date('d_m_Y_H_i_s').'.xml"');

// print out XML that describes the schema
echo $output;

// close the connection
//mysql_close($dbhandle);
?> 