<?php
include_once("../utility/config.php");
include_once("../utility/dbclass.php");
include_once("../includes/other_functions.php");
$objDB = new DB();
if(!isset($_SESSION[ADMIN_SESSION_VAR]))
{
	header("location: index.php");
	exit();
}
$no_add=0;
$no_exist=0;

$myFile = "../csv/testFile.txt";
$fh = fopen($myFile, 'w') or die("can't open file");

     $filename="../csv/".$_REQUEST['csv_file'];
	
     $handle = fopen("$filename", "r");
     while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)
     {
	 
		if(mysql_real_escape_string($data[0])!="company name")
		{
			if(mysql_real_escape_string($data[1])!="")
			{
				
				$company_name 					= mysql_real_escape_string($data[0]);
				$seo_browser_title 				= mysql_real_escape_string(addslashes($data[1]));
				$seo_h1_tag		 				= mysql_real_escape_string(addslashes($data[2]));
				$seo_meta_keyword				= mysql_real_escape_string(addslashes($data[3]));
				$seo_meta_description			= mysql_real_escape_string(addslashes($data[4]));
				$company_web 					= mysql_real_escape_string(addslashes($data[5]));
				$company_desc 					= mysql_real_escape_string(addslashes($data[6]));
				$company_type   				= mysql_real_escape_string($data[7]);
				$company_tag 					= mysql_real_escape_string($data[8]);
				$headquaters_address 			= mysql_real_escape_string(addslashes($data[9]));
				$founded 						= mysql_real_escape_string($data[10]);
				$introduction					= mysql_real_escape_string(addslashes($data[11]));
				$emp_guide_graduate_roles		= mysql_real_escape_string(addslashes($data[12]));
				$emp_guide_application_status 	= mysql_real_escape_string(addslashes($data[13]));
				$close_date 					= mysql_real_escape_string(addslashes($data[14]));
				//echo $close_date."-".date("Y-m-d",strtotime($close_date));
				//exit;
				$close_date=date("Y-m-d",strtotime($close_date));
				$emp_guide_duration_programs 	= mysql_real_escape_string(addslashes($data[15]));
				$emp_guide_eligibility 			= mysql_real_escape_string(addslashes($data[16]));
				$emp_guide_graduate_salaries 	= mysql_real_escape_string(addslashes($data[17]));
				$emp_guide_training_development = mysql_real_escape_string(addslashes($data[18]));
				$emp_guide_women_in_workplace 	= mysql_real_escape_string(addslashes($data[19]));
				$service_offering 				= mysql_real_escape_string(addslashes($data[20]));
				$reputation 					= mysql_real_escape_string(addslashes($data[21]));
				$history 						= mysql_real_escape_string(addslashes($data[22]));
				$key_staff 						= mysql_real_escape_string(addslashes($data[23]));
				$global_footprint 				= mysql_real_escape_string(addslashes($data[24]));
				$recent_work 					= mysql_real_escape_string(addslashes($data[25]));
				$awards		 					= mysql_real_escape_string(addslashes($data[26]));
				$diversity_equality				= mysql_real_escape_string(addslashes($data[27]));
				$community						= mysql_real_escape_string(addslashes($data[28]));
				$hit_count						= mysql_real_escape_string(addslashes($data[29]));


				$company_type_id=getValue("tbl_company_type",$company_type,"company_type_name","company_type_id");
				
				if($company_type_id=="")
				{
					$Query  = " INSERT INTO tbl_company_type SET ";
					$Query .= " company_type_name						= '".$company_type."'";
					 $objDB->setQuery($Query);
					$company_type_id = $objDB->insert();
			
				}
				$Query  = "select * from tbl_site_data WHERE title = '".$company_name."' and  site_content_type = 'company' ";
				$objDB->setQuery($Query);
				$rsu = $objDB->select();
				if(count($rsu) > 0 )
				{
					//common
					$row_id=$rsu[0]['id'];
					$stringData = $company_name."\t". $company_type."\t". $hit_count."\n";
					fwrite($fh, $stringData);
					
					$no_exist ++;
					//======================================================================
					$Query  = " UPDATE ".SITE_TABLE_PREFIX."tbl_site_data SET ";
					$Query .= " title									= '".$company_name."', ";
					$Query .= " seo_browser_title						= '".$seo_browser_title."', ";
					$Query .= " seo_h1_tag								= '".$seo_h1_tag."', ";
					$Query .= " seo_meta_keyword						= '".$seo_meta_keyword."', ";
					$Query .= " seo_meta_description					= '".$seo_meta_description."', ";
					$Query .= " company_type_id							= '".$company_type_id."', ";
					$Query .= " company_headquaters_address				= '".$headquaters_address."', ";
					$Query .= " data_description						= '".$company_desc."', ";
					$Query .= " company_founded							= '".$founded."', ";
					$Query .= " company_introduction					= '".$introduction."', ";
					$Query .= " company_emp_guide_graduate_roles		= '".$emp_guide_graduate_roles."', ";
					$Query .= " company_emp_guide_application_status	= '".$company_mp_guide_application_status."', ";
					$Query .= " company_emp_guide_duration_programs		= '".$emp_guide_duration_programs."', ";
					$Query .= " company_web								= '".$company_web."', ";
					$Query .= " company_logo							= '".$logoImage."', ";
					$Query .= " company_emp_guide_eligibility			= '".$emp_guide_eligibility."', ";
					$Query .= " company_emp_guide_graduate_salaries		= '".$emp_guide_graduate_salaries."', ";
					$Query .= " company_emp_guide_training_development	= '".$emp_guide_training_development."', ";
					$Query .= " company_emp_guide_women_in_workplace	= '".$emp_guide_women_in_workplace."', ";
					$Query .= " company_service_offering				= '".$service_offering."', ";
					$Query .= " company_reputation						= '".$reputation."', ";
					$Query .= " company_history							= '".$history."', ";
					$Query .= " company_key_staff						= '".$key_staff."', ";
					$Query .= " company_global_footprint				= '".$global_footprint."', ";
					$Query .= " company_recent_work						= '".$recent_work."', ";
					$Query .= " company_awards							= '".$awards."', ";
					$Query .= " company_diversity_equality				= '".$diversity_equality."', ";
					$Query .= " company_community						= '".$community."', ";
					$Query .= " company_close_date						= '".$close_date."', ";
					$Query .= " company_tag								= '".$company_tag."', ";
					$Query .= " seo_url 								= '".$seo_url."',";
					$Query .= " hit_count								= '".$hit_count."', ";
					$Query .= " site_content_type 						= 'company',";
					$Query .= " update_date								= NOW() ";
					$Query .= " WHERE    id='".$row_id."'";
					//=======================================================================
					/*echo $Query."<br /><br /><br />";
			exit;*/
					$objDB->setQuery($Query);
					$rs = $objDB->update();
					
				}
				else
				{
									
					
					$Query  = " INSERT INTO tbl_site_data SET ";
					$Query .= " title									= '".$company_name."', ";
					$Query .= " seo_browser_title						= '".$seo_browser_title."', ";
					$Query .= " seo_h1_tag								= '".$seo_h1_tag."', ";
					$Query .= " seo_meta_keyword						= '".$seo_meta_keyword."', ";
					$Query .= " seo_meta_description					= '".$seo_meta_description."', ";
					$Query .= " company_type_id							= '".$company_type_id."', ";
					$Query .= " company_headquaters_address				= '".$headquaters_address."', ";
					$Query .= " data_description						= '".$company_desc."', ";
					$Query .= " company_founded							= '".$founded."', ";
					$Query .= " company_introduction					= '".$introduction."', ";
					$Query .= " company_emp_guide_graduate_roles		= '".$emp_guide_graduate_roles."', ";
					$Query .= " company_emp_guide_application_status	= '".$company_mp_guide_application_status."', ";
					$Query .= " company_emp_guide_duration_programs		= '".$emp_guide_duration_programs."', ";
					$Query .= " company_web								= '".$company_web."', ";
					$Query .= " company_logo							= '".$logoImage."', ";
					$Query .= " company_emp_guide_eligibility			= '".$emp_guide_eligibility."', ";
					$Query .= " company_emp_guide_graduate_salaries		= '".$emp_guide_graduate_salaries."', ";
					$Query .= " company_emp_guide_training_development	= '".$emp_guide_training_development."', ";
					$Query .= " company_emp_guide_women_in_workplace	= '".$emp_guide_women_in_workplace."', ";
					$Query .= " company_service_offering				= '".$service_offering."', ";
					$Query .= " company_reputation						= '".$reputation."', ";
					$Query .= " company_history							= '".$history."', ";
					$Query .= " company_key_staff						= '".$key_staff."', ";
					$Query .= " company_global_footprint				= '".$global_footprint."', ";
					$Query .= " company_recent_work						= '".$recent_work."', ";
					$Query .= " company_awards							= '".$awards."', ";
					$Query .= " company_diversity_equality				= '".$diversity_equality."', ";
					$Query .= " company_community						= '".$community."', ";
					$Query .= " company_close_date						= '".$close_date."', ";
					$Query .= " company_tag								= '".$company_tag."', ";
					$Query .= " seo_url 								= '".$seo_url."',";
					$Query .= " hit_count								= '".$hit_count."', ";
					$Query .= " site_content_type 						= 'company',";
					$Query .= " added_date		 						= NOW()";
		
					
					echo $Query."<br /><br /><br />";
				   
					$objDB->setQuery($Query);
					$insertId = $objDB->insert();
					$no_add ++;
				}
			}
		}
		else
		{
			if((mysql_real_escape_string($data[0])=="company name") && 
				(mysql_real_escape_string($data[1])=="browser_title") &&
				(mysql_real_escape_string($data[2])=="h1_tag") &&
				(mysql_real_escape_string($data[3])=="meta_keyword") &&
				(mysql_real_escape_string($data[4])=="meta_description") &&
				(mysql_real_escape_string($data[5])=="website") &&
				(mysql_real_escape_string($data[6])=="description") &&
				(mysql_real_escape_string($data[7])=="company_type(Investment Bank/Commerial Bank/Private Wealth Management/Law Firms/)") &&
				(mysql_real_escape_string($data[8])=="company_tag(Graduate/Internship/Cadetship/Other)") &&
				(mysql_real_escape_string($data[9])=="headquaters_address") &&
				(mysql_real_escape_string($data[10])=="founded") &&
				(mysql_real_escape_string($data[11])=="introduction") &&
				(mysql_real_escape_string($data[12])=="graduate_roles") &&
				(mysql_real_escape_string($data[13])=="application status") &&
				(mysql_real_escape_string($data[14])=="Closing date") &&
				(mysql_real_escape_string($data[15])=="duration programs") &&
				(mysql_real_escape_string($data[16])=="eligibility") &&
				(mysql_real_escape_string($data[17])=="graduate salaries") &&
				(mysql_real_escape_string($data[18])=="training development") &&
				(mysql_real_escape_string($data[19])=="women in workplace") &&
				(mysql_real_escape_string($data[20])=="service offering") &&
				(mysql_real_escape_string($data[21])=="reputation") &&
				(mysql_real_escape_string($data[22])=="history") &&
				(mysql_real_escape_string($data[23])=="key staff") &&
				(mysql_real_escape_string($data[24])=="global footprint") &&
				(mysql_real_escape_string($data[25])=="recent work") &&
				(mysql_real_escape_string($data[26])=="awards") &&
				(mysql_real_escape_string($data[27])=="diversity equality") &&
				(mysql_real_escape_string($data[28])=="community") &&
				(mysql_real_escape_string($data[29])=="hit_count") )
				
			{
				//correct format
				
			}
			else
			{
				
				$_SESSION[SUCCESS_MSG] = "CSV format is not correct...";
				header("location: index.php?p=company");
				exit();
			}
		}
     }
	fclose($handle);
	
	fclose($fh);
	$_SESSION[SUCCESS_MSG] = $no_add." No of Church Added successfully and ".$no_exist." No of data Updated seccessfully";
	header("location: index.php?p=company");
	exit();
?>