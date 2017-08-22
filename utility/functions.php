<?php
function loadVariable($name,$default)
{
	if(isset($_REQUEST["$name"]))
		return $_REQUEST["$name"];
	else
		return $default;
}

function pr($arr,$e=1)
{
	if(is_array($arr))
	{
		echo "<pre>";
		print_r($arr);
		echo "</pre>";		
	}
	else
	{
		echo "<br>Not and array...<br>";
		echo "<pre>";
		var_dump($arr);
		echo "</pre>";
		
	}
	if($e==1)
	{
		exit();
	}
	else
	{
		echo "<br>";
	}
}


function inputEscapeString($str,$Type='DB',$htmlEntitiesEncode = true)
{
	if($Type === 'DB')
	{
		if(get_magic_quotes_gpc()===0)
		{
			$str = addslashes($str);
		}
	}
	elseif($Type === 'FILE')
	{
		if(get_magic_quotes_gpc()===1)
		{
			$str = stripslashes($str);	
		}
	}
	else
	{
		$str = $str;
	}
	
	if($htmlEntitiesEncode === true)
	{
		$str = htmlentities($str);
	}
	
	return $str;
}


function outputEscapeString($str,$Type = 'INPUT', $htmlEntitiesDecode = true )
{

	if(get_magic_quotes_runtime()==1)
	{
		$str = stripslashes($str);	
	}
	
	if($htmlEntitiesDecode === true)
	{
		$str = html_entity_decode($str);
	}
	
	if($Type == 'INPUT')
	{
		$str = htmlentities($str);
	}
	elseif($Type == 'TEXTAREA')
	{
		$str = $str;
	}
	elseif($Type == 'HTML')
	{
		$str = nl2br($str);
	}
	else
	{
		$str = $str;
	}
	
	return $str;
}


function loadFromSession($key,$var,&$ptr)
{
	global $p;
	if(isset($_REQUEST[$var]))
	{
		if($_REQUEST[$var]<>'')
		{
			return false;
		}
	}
	if(isset($_SESSION[$key][$p][$var]))
	{
		if($_SESSION[$key][$p][$var]<>'')
		{
			$ptr = $_SESSION[$key][$p][$var];
			return true;
		}
		else
			return false;
	}
	else
		return false;
}

function checkLogout()
{
	$a=loadVariable('a','');
	if($a=="logout")
	{
		$_SESSION[SUCCESS_MSG] = "";
		$pos = strpos($_SERVER['PHP_SELF'],'webadmin');
		if($pos)
		{
			if(isset($_SESSION[ADMIN_SESSION_VAR]))
			{
				#$_SESSION[ADMIN_SESSION_VAR] = "";
				#unset($_SESSION[ADMIN_SESSION_VAR]);
				#$_SESSION['LIST_PAGR'] = "";
				#unset($_SESSION['LIST_PAGE']);
				foreach ($_SESSION AS $key => $value)
				{
					unset($_SESSION[$key]);
				}
				$_SESSION[SUCCESS_MSG] = "You have successfully logged out...";
			}
		}
		else
		{
			if(isset($_SESSION[USER_SESSION_VAR]))
			{
				#$_SESSION[USER_SESSION_VAR]="";
				#unset($_SESSION[USER_SESSION_VAR]);
				foreach ($_SESSION AS $key => $value)
				{
					unset($_SESSION[$key]);
				}
				$_SESSION[SUCCESS_MSG] = "You have successfully logged out...";
			}
		}
		
		
	}
}

function showMessage()
{
	
	if(isset($_SESSION[SUCCESS_MSG]))
	{
		if($_SESSION[SUCCESS_MSG] <> "")
		{
			echo "<p ><font color='#009933'><b>".$_SESSION[SUCCESS_MSG]."</b></font></p>";
			$_SESSION[SUCCESS_MSG] = "";
			return true;		
		}
	}

	if(isset($_SESSION[ERROR_MSG]))
	{
		if($_SESSION[ERROR_MSG] <> "")
		{
			echo "<p style=\"background:#CCCCCC;padding:5px;text-align:center\" width=\"400px;\"><font color='#FF0000'><b>".$_SESSION[ERROR_MSG]."</b></font></p>";
			$_SESSION[ERROR_MSG] = "";
			return true;		
		}
	}
	
	return false;
	
}

function securityCheck($p)
{

	$pos = strpos($_SERVER['PHP_SELF'],'webadmin');
	$path = '';
	
	$pageArray = array();
	if($pos)
	{
		$path = 'webadmin';
		
		$pageArray = explode(',',ADMIN_UNSECURED_PAGES);
	}
	else
	{
		$pageArray = explode(',',USER_UNSECURED_PAGES);
	}
	
	if(in_array($p,$pageArray))
	{
		return $p;
	}
	else
	{
		if($pos)
		{
			if(isset($_SESSION[ADMIN_SESSION_VAR]))
			{
				return $p;
			}
			else
			{
				return 'login';
			}
		}
		else
		{
			if(isset($_SESSION[USER_SESSION_VAR]))
			{
				return $p;
			}
			else
			{
				return 'registration';
			}
		}
		
	}
}

function fillCombo($table,$value,$text,$selected = '',$condition = '',$orderby = '',$show = '')
{
	global $objDB;
	
	$Query = "select ".$value.",".$text." from ".$table." ";
	if($condition <> '')
		$Query .= $condition;
	if($orderby== '')
	{
	$Query .=" ORDER BY ".$text;
	}
	else
	{
	$Query .=" ORDER BY ".$orderby;
	}
	
	//echo $Query;
	//exit;
	$objDB->setQuery($Query);
	
	$rs = $objDB->select();
	
	$str = "";
	
	for($i=0;$i<count($rs);$i++)
	{
		$str .= "<option value=\"".$rs[$i][$value]."\" ";
		
			if(is_array($selected))
			{
				foreach($selected as $val)
				{
					if($val == $rs[$i][$value])
						$str .= " selected ";

				}
			}
			else
			{
				if($selected == $rs[$i][$value])
					$str .= " selected ";
			
			}
		if($show== '')
		{
		$str .= ">".$rs[$i][$text]."</option>\n";
		}
		else
		{
		$str .= ">".$rs[$i][$show]."</option>\n";
		}
	}
	
	return $str;
}

function getImageExtension($filename) 
{ 
	$filename = strtolower($filename) ; 
	$exts = split("[/\\.]", $filename) ; 
	$n = count($exts)-1;
	$exts = $exts[$n]; 
	return $exts; 
} 

function getAudioExtension($filename) 
{ 
	$filename = strtolower($filename) ; 
	$exts = split("[/\\.]", $filename) ; 
	$n = count($exts)-1;
	$exts = $exts[$n]; 
	return $exts; 
} 

function thumbnail($filethumb,$file,$Twidth,$Theight,$tag)
{
	list($width,$height,$type,$attr)=getimagesize($file);
	switch($type)
	{
		case 1:
			$img = imagecreatefromgif($file);
		break;
		case 2:
			$img=imagecreatefromjpeg($file);
		break;
		case 3:
			$img=imagecreatefrompng($file);
		break;
	}
	if($tag == "width") //width contraint
	{
		$Theight=round(($height/$width)*$Twidth);
	}
	elseif($tag == "height") //height constraint
	{
		$Twidth=round(($width/$height)*$Theight);
	}
	else
	{
		if($width > $height)
			$Theight=round(($height/$width)*$Twidth);
		else
			$Twidth=round(($width/$height)*$Theight);
	}
	$thumb=imagecreatetruecolor($Twidth,$Theight);
	
	if(imagecopyresampled($thumb,$img,0,0,0,0,$Twidth,$Theight,$width,$height))
	{
		
		switch($type)
		{
			case 1:
				imagegif($thumb,$filethumb);
			break;
			case 2:
				imagejpeg($thumb,$filethumb,100);
			break;
			case 3:
				imagepng($thumb,$filethumb);
			break;
		}
		chmod($filethumb,0666);
		return true;
	}
}

//==================================   Site Specific Functions ===========================================

function getMenuName($id)
{
	global $objDB;
	$val = '';
	$Query = "select menuName from ".SITE_TABLE_PREFIX."menus WHERE id='".$id."' ";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	
	if(count($rsTotal) == 1)
	{
		$val = $rsTotal[0]['menuName'];
	}
	return $val;
}

function getCMSName($id)
{
	global $objDB;
	$val = 'Home';
	$Query = "select title from ".SITE_TABLE_PREFIX."contents WHERE id='".$id."' ";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	
	if(count($rsTotal) == 1)
	{
		$val = $rsTotal[0]['title'];
	}
	return $val;
}


function getTotalSubCategory($id)
{
	global $objDB;
	$val = 0;
	$Query = "select count(id) as CNT from ".SITE_TABLE_PREFIX."job_categories WHERE parentId='".$id."'";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	if($rsTotal)
	{
		$val = $rsTotal[0]['CNT'];
	}
	
	return $val;
}

function getParentId($id)
{
	global $objDB;
	$val = 0;
	$Query = "select parentId from ".SITE_TABLE_PREFIX."job_categories WHERE id='".$id."'";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	
	if(count($rsTotal) == 1)
	{
		$val = $rsTotal[0]['parentId'];
	}
	
	return $val;
}


function getParentCMSId($id)
{
	global $objDB;
	$val = 0;
	$Query = "select parentId from ".SITE_TABLE_PREFIX."contents WHERE id='".$id."'";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	
	if(count($rsTotal) == 1)
	{
		$val = $rsTotal[0]['parentId'];
	}
	
	return $val;
}


function getTotalSubCMS($id)
{
	global $objDB;
	$val = 0;
	$Query = "select count(id) as CNT from ".SITE_TABLE_PREFIX."contents WHERE parentId='".$id."'";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	if($rsTotal)
	{
		$val = $rsTotal[0]['CNT'];
	}
	
	return $val;
}

function getCategoryName($id)
{
	global $objDB;
	$val = 'ROOT';
	$Query = "select name from ".SITE_TABLE_PREFIX."job_categories WHERE id='".$id."'";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	
	if(count($rsTotal) == 1)
	{
		$val = $rsTotal[0]['name'];
	}
	
	return $val;
}



function fillCategoryCombo($pid,$selected = '',$condition = '',$depth=0)
{
	global $objDB;
	
	$tab='';
	for($k=0;$k<$depth;$k++)
		$tab .=	"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	if($depth > 0)
		$tab .=	"-->";
	
	$Query = "select id,title from contents WHERE parentId='".$pid."' and status =1";

	if($condition <> '')
		$Query .= $condition;
	$Query .=" ORDER BY title";

	$objDB->setQuery($Query);
	
	$rs = $objDB->select();
	
	$str = "";
	
	for($i=0;$i<count($rs);$i++)
	{
		$str .= "<option value=\"".$rs[$i]['id']."\" ";
		
			if(is_array($selected))
			{
				foreach($selected as $val)
				{
					if($val == $rs[$i]['id'])
						$str .= " selected ";

				}
			}
			else
			{
				if($selected == $rs[$i]['id'])
					$str .= " selected ";
			
			}
		$str .= ">".$tab.$rs[$i]['title']."</option>\n";
		
		$str.= fillCategoryCombo($rs[$i]['id'],$selected,$condition,$depth+1);
		
	}
	
	return $str;
}


function aspectRatio($src, $imgWidth='', $imgHeight='')
					{
		
						$size = @getimagesize($src);
						
						$true_width = $size[0];
						$true_height = $size[1];
						
						$width = $imgWidth;
						$height = ($width/$true_width)*$true_height;
						if ($true_width >= $true_height)
						{
							$width = $imgWidth;
							$height = ($width/$true_width)*$true_height;
						}
						else
						{
							$height = $imgHeight;
							$width = ($height/$true_height)*$true_width;
						}
						return array('width'=>$width,'height'=>$height);
					}

/***************************************************************/
/**
 * get_redirect_url()
 * Gets the address that the provided URL redirects to,
 * or FALSE if there's no redirect. 
 *
 * @param string $url
 * @return string
 */
function get_redirect_url($url){
	$redirect_url = null; 
 
	$url_parts = @parse_url($url);
	if (!$url_parts) return false;
	if (!isset($url_parts['host'])) return false; //can't process relative URLs
	if (!isset($url_parts['path'])) $url_parts['path'] = '/';
 
	$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
	if (!$sock) return false;
 
	$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n"; 
	$request .= 'Host: ' . $url_parts['host'] . "\r\n"; 
	$request .= "Connection: Close\r\n\r\n"; 
	fwrite($sock, $request);
	$response = '';
	while(!feof($sock)) $response .= fread($sock, 8192);
	fclose($sock);
 
	if (preg_match('/^Location: (.+?)$/m', $response, $matches)){
		if ( substr($matches[1], 0, 1) == "/" )
			return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
		else
			return trim($matches[1]);
 
	} else {
		return false;
	}
 
}
 
/**
 * get_all_redirects()
 * Follows and collects all redirects, in order, for the given URL. 
 *
 * @param string $url
 * @return array
 */
 
function get_all_redirects($url){
	$redirects = array();
	while ($newurl = get_redirect_url($url)){
		if (in_array($newurl, $redirects)){
			break;
		}
		$redirects[] = $newurl;
		$url = $newurl;
	}
	return $redirects;
}
 
/**
 * get_final_url()
 * Gets the address that the URL ultimately leads to. 
 * Returns $url itself if it isn't a redirect.
 *
 * @param string $url
 * @return string
 */
function get_final_url($url){
	$redirects = get_all_redirects($url);
	if (count($redirects)>0){
		return array_pop($redirects);
	} else {
		return $url;
	}
}
/*******************************************************************************/

function getSuperAdminRoleID()
{
	global $objDB;
	$val = 0;
	$Query = "select id from ".SITE_TABLE_PREFIX."role_master WHERE role_name='superadmin'";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	if($rsTotal)
	{
		$val = $rsTotal[0]['id'];
	}
	
	return $val;
}

function getAdminRoleID()
{
	global $objDB;
	$val = 0;
	$Query = "select id from ".SITE_TABLE_PREFIX."role_master WHERE role_name='admin'";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	if($rsTotal)
	{
		$val = $rsTotal[0]['id'];
	}
	
	return $val;
}

function getHubRoleID()
{
	global $objDB;
	$val = 0;
	$Query = "select id from ".SITE_TABLE_PREFIX."role_master WHERE role_name='hub'";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	if($rsTotal)
	{
		$val = $rsTotal[0]['id'];
	}
	
	return $val;
}

function getBranchRoleID()
{
	global $objDB;
	$val = 0;
	$Query = "select id from ".SITE_TABLE_PREFIX."role_master WHERE role_name='branch'";
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	if($rsTotal)
	{
		$val = $rsTotal[0]['id'];
	}
	
	return $val;
}

function findUserType()
{
	$type = 'none';
	if(isset($_SESSION[ADMIN_SESSION_VAR]))
	{
		$SUPER_ADMIN_ROLE_ID = getSuperAdminRoleID();
		$ADMIN_ROLE_ID = getAdminRoleID();
		$HUB_ROLE_ID = getHubRoleID();
		$BRANCH_ROLE_ID = getBranchRoleID();

		if($SUPER_ADMIN_ROLE_ID == $_SESSION[ROLE_ID])
		{
			$type = 'superadmin';
		}
		else if($ADMIN_ROLE_ID == $_SESSION[ROLE_ID])
		{
			$type = 'admin';
		}
		else if($HUB_ROLE_ID == $_SESSION[ROLE_ID])
		{
			$type = 'hub';
		}
		else if($BRANCH_ROLE_ID == $_SESSION[ROLE_ID])
		{
			$type = 'branch';
		}
	}
	return $type;
}

// superadmin, admin, branch, hub, none is applicable for the first parameter. This is coming through the function findUserType($_SESSION)

// pass the valid options through the second parameter as a comma seperated value such as admin,superadmin

function chkPageAccess($user_type_from_session, $authentic_usertype) 
{
	global $objDB;
	$val = 0;
	$Query = "select id from ".SITE_TABLE_PREFIX."role_master WHERE role_name IN (".$authentic_usertype.") AND id=".$user_type_from_session;
	#echo $Query; exit;
	$objDB->setQuery($Query);
	$rsTotal = $objDB->select();
	//echo 'Hi';
	if(count($rsTotal) <= 0)
	{
		#$authentic_userArr = explode(',', $authentic_usertype);
		#echo $user_type_from_session.'<br />';
		#print_r($authentic_userArr); exit;
		#if(!in_array($user_type_from_session, $authentic_userArr))

		//{
			header("location: ".$URL."index.php");
			exit();
		//}
	}
	if(!isset($_SESSION[SITE_NAME]))
	{
		foreach ($_SESSION AS $key => $value)
		{
			unset($_SESSION[$key]); // DESTROYING THE SESSION DATA
		}
		header("location: ".$URL."index.php");
		exit();
	}
	if($_SESSION[SITE_NAME] != SITE_NAME_VAL)
	{
		foreach ($_SESSION AS $key => $value)
		{
			unset($_SESSION[$key]); // DESTROYING THE SESSION DATA
		}
		header("location: ".$URL."index.php");
		exit();
	}
}

 function getExtension($file_name) {



        $ext_name = $file_name;

        $ext_arr = explode('.', $ext_name);

        $total_val = count($ext_arr);

        if ($total_val > 1) {

            $ext = $ext_arr[$total_val - 1];

            $ext = strtolower($ext);

        } else {

            $ext = '';

        }

        return $ext;

    }
	
	function receive_mode($receive_cash,$receive_cheque,$receive_draft)
	{
		
		$mode = '';
		/*
		if($receive_cash!=0.00){
			$mode = "CASH";
		}if($receive_cheque!=0.00){
			if(!empty($mode)){
				$mode .= ", CHEQUE";
			}else{
				$mode .= "CHEQUE";
			}
		}if($receive_draft!=0.00){
			if(!empty($mode)){
				$mode .= ", DRAFT";
			}else{
				$mode .= "DRAFT";
			}
		}
		*/
		
		if($receive_cheque!=0.00){
			
			$mode = "CHEQUE";
		}
		else if($receive_draft!=0.00){
			
			$mode = "DRAFT";
		}
		else if($receive_cash!=0.00){
			
			$mode = "CASH";
		}
		
		return $mode;
	}
	
?>