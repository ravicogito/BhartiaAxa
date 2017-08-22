<?php
ob_start();
session_start();
ini_set('display_error','On');

ini_set('allow_call_time_pass_reference','On');

ini_set("memory_limit","256M");

ini_set("session.gc_maxlifetime",8*60*60);
ini_set("session.gc_probability",1);
ini_set("session.gc_divisor",1);




//error_reporting(E_ALL);

//ini_set('error_reporting', E_ALL);

error_reporting(0);

date_default_timezone_set('Asia/Calcutta');

define('__CONFIG__','__CONFIG__');

/*print_r($_SERVER);
exit;*/

if($_SERVER['HTTP_HOST'] == 'senabidemoportal.com' || strpos($_SERVER['HTTP_HOST'],'192.168.1.')===0 || $_SERVER['SERVER_ADDR'] == '127.0.0.1')
{
	define('DBHOST','localhost');
	define('DBNAME','senabide_bharatiAXA');
	define('DBUSER','senabide_bharati');
	define('DBPASSWORD','Maakali2017');
	//define("ROOT_URL","/opt/lampp/htdocs/gold/home/");
	//define("URL","http://192.168.1.199/gold/");
	//define("URL","http://localhost/sicl/");
	define("URL","http://".$_SERVER['HTTP_HOST']."/bharatiAXA/");
	//define("ROOT_URL","/home/senabidemo/public_html/bharatiAXA/");
}
else
{
	//define('DBHOST','localhost');
	//define('DBNAME','karinfo_sicl');
	//define('DBUSER','karinfo_sicl');
	//define('DBPASSWORD','sicl123');
	
	//define("URL","http://karmickproduction.com/sicl/");
	//define("ROOT_URL",$_SERVER['DOCUMENT_ROOT']."/sicl/");
	define('DBHOST','localhost');
	define('DBNAME','senabide_bharatiAXA');
	define('DBUSER','senabide_bharati');
	define('DBPASSWORD','Maakali2017');
	define("URL","http://".$_SERVER['HTTP_HOST']."/bharatiAXA/");
	define("ROOT_URL","/home/senabidemo/public_html/bharatiAXA/");
	



	define('DBHOST','localhost');

	//define('DBNAME','onlineprize');
	define('DBNAME','senabide_bharatiAXA');
	define('DBUSER','senabide_bharati');
	define('DBPASSWORD','Maakali2017');
	
	//define("URL","http://".$_SERVER['HTTP_HOST']."/bharatiAXA/");
	//define("ROOT_URL","/opt/lampp/htdocs/lici/");
	define("URL","http://senabidemoportal.com/bharatiAXA/");
	define("ROOT_URL","/var/www/html/bharatiAXA/");
	
}

$secure_prefix="siclbajaj";
	
define('SITE_TABLE_PREFIX','');
define('USER_SESSION_VAR',$secure_prefix.'userId');
define('ADMIN_SESSION_VAR',$secure_prefix.'adminId');
define('SUCCESS_MSG',$secure_prefix.'successMsg');
define('ERROR_MSG',$secure_prefix.'errorMsg');
define('USER_UNSECURED_PAGES', "home");
define('ROLE_ID', $secure_prefix."ROLE_ID");
define('BRANCH_USER_ID', $secure_prefix."BRANCH_USER_ID");
define('SITE_NAME',$secure_prefix.'SITE_NAME');
define('SITE_NAME_VAL','LICI');



define('ADMIN_UNSECURED_PAGES', "login,forgot_pass");



define('COMPANY_NAME','BHARATI AXA');

define('DEFAULT_KEYWORD','');
define('DEFAULT_METADESC','');
define('DEFAULT_BROWSERTITLE','BHARATI AXA');
define('DEFAULT_AUTHOR','');
/*define("PAYPAL_URL","https://www.sandbox.paypal.com/cgi-bin/webscr");
define("PAYPAL_BUSINESS","seller@designerfotos.com"); 
define("CURRENCY","CAD");
*/
define("CURRENCY","USD");
define("PLAN_NAME","GAP");
define("SERVICE_CHARGE_QUANTITY",6);
define("SERVICE_CHARGE_PERCENTAGE_36",3);
define("SERVICE_CHARGE_PERCENTAGE_OTHER",5);
define("GOLD_ID","1");
define("SILVER_ID","2");
#echo PLAN_NAME;
?>