<?php
//$con=mysqli_connect("35.154.178.109","monty","aws123","agent");
//$con=mysqli_connect("localhost","dmsplu","dmsplPwD12","gtfsaep");
$con=mysqli_connect("localhost","senabide_aep","T_9f!KK5&{xb","senabide_gtfsaepfinal");

if (mysqli_connect_errno())
	  {
	  	$data['result']="0";
		$data['msg']="Failed to connect to MySQL: " . mysqli_connect_error();
		
		$val=json_encode($data);
		echo $val;
		exit;
		
	  }
	  

$password="";
$secret_key="";
$agent_code="";
$phase_id="";

$check_password="123";
$check_secret_key="abcd234567";

$data=array();

if(isset($_POST['password']) && $_POST['password']!="")
{
	$password=$_POST['password'];
}

if(isset($_POST['secret_key']) && $_POST['secret_key']!="")
{
	$secret_key=$_POST['secret_key'];
}

if(isset($_POST['agent_code']) && $_POST['agent_code']!="")
{
	$agent_code=$_POST['agent_code'];
}

if(isset($_POST['phase_id']) && $_POST['phase_id']!="")
{
	$phase_id=$_POST['phase_id'];
}



if($password==$check_password && $secret_key==$check_secret_key && $agent_code!="" && $phase_id!="")
{
	
	
	
	//$sql="SELECT AG_CODE,AG_NAME,TEL_NO,PHASE_ID from AGENT_MST where AG_CODE='$agent_code' and PHASE_ID <= '$phase_id' and APPROVAL_FLAG='Y' limit 1";
	
	$sql="SELECT AG_CODE,AG_NAME,TEL_NO,PHASE_ID from AGENT_MST where AG_CODE='$agent_code' and PHASE_ID <= '$phase_id' limit 1";
	$mysql_query=mysqli_query($con,$sql);

		
	if(mysqli_num_rows($mysql_query)!=0)
	{
		$rec=mysqli_fetch_array($mysql_query);
		
		$data['result']="1";
		$data['AG_CODE']=$rec['AG_CODE'];
				
		if($agent_code=="999999999")
		{
			$data['AG_NAME']='DIRECT';			
			
		}
		else
		{
			$data['AG_NAME']=$rec['AG_NAME'];
			//$data['AG_NAME']='DIRECT';
		}
		
		
		$data['TEL_NO']=$rec['TEL_NO'];
		$data['PHASE_ID']=$rec['PHASE_ID'];
		$data['msg']="This ref no. is validated.";
		
	}
	else
	{
		$data['result']="0";
		$data['msg']="The data is not found.";
	}
	
	mysqli_close($con);

}
else
{
	$data['result']="0";
	$data['msg']="Some required paramater is missing.";

}

$val=json_encode($data);

echo $val;
exit;

?>