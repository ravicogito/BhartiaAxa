<?php
class DB
{
	var $DB_HOST;
	var $DB_NAME;
	var $DB_USER;
	var $DB_PASSWORD;
	
	var $conn;
	var $SQL;
	var $errorMsg;
	var $successMsg;
	
	function displayError($stop=1)
	{
		echo "<p><font color='#FF0000'>".$this->errorMsg."</font></p>";
		if($stop==1)
			exit();
	}
		
	function dbconnect()
	{
		$this->conn = mysql_connect($this->DB_HOST,$this->DB_USER,$this->DB_PASSWORD);

		if(!$this->conn)
		{
			$this->errorMsg = mysql_errno($this->conn) . ": " . mysql_error($this->conn);
			$this->displayError();
		}
		
		$result = mysql_select_db($this->DB_NAME,$this->conn);
		
	
		
		if(!$result)
		{
			$this->errorMsg = mysql_errno($this->conn) . ": " . mysql_error($this->conn);
			$this->displayError();
		}
		
		
		
		
	}
		
	function __construct()
	{
		$this->errorMsg = "";
		$this->successMsg = "";

		$this->DB_HOST = DBHOST;
		$this->DB_NAME = DBNAME;
		$this->DB_USER = DBUSER;
		$this->DB_PASSWORD = DBPASSWORD;

		$this->conn = NULL;
		$this->SQL = "";
		$this->dbconnect();	
		$this->set_utf_env();		
	}
	function set_utf_env()
	{
		$this->setQuery('SET character_set_database=UTF8');
		$this->execute();
		$this->setQuery('SET character_set_client=UTF8');
		$this->execute();
		$this->setQuery('SET character_set_connection=UTF8');
		$this->execute();
		$this->setQuery('SET character_set_results=UTF8');
		$this->execute();
		$this->setQuery('SET character_set_server=UTF8');
		$this->execute();
		$this->setQuery('SET names UTF8');
		$this->execute();
	}
	/*
	function __construct($host,$dbname,$user,$pass)
	{
		$errorMsg = "";
		$successMsg = "";
		$DB_HOST = $host;
		$DB_NAME = $dbname;
		$DB_USER = $user;
		$DB_PASSWORD = $pass;
		$this->conn = NULL;
		$this->SQL = "";
		
		connect();		
	
	}
	*/
	
	public function setQuery($query)
	{
		$this->SQL = $query;
	}
	
	public function select()
	{
		if($this->SQL == "")
			return false;
		
		$rs = mysql_query($this->SQL,$this->conn);
		if($rs=== false)
		{
			$this->SQL = "";
			$errorMsg = mysql_errno($this->conn) . ": " . mysql_error($this->conn);
			$this->displayError();
		}
		
		$records = array();
		while(($row = mysql_fetch_array($rs,MYSQL_ASSOC)))
		{
			$records[] = $row;
		}
		
		$this->SQL = "";
		mysql_free_result($rs);
		return $records;	
	}
	
	
	public function update()
	{
		if($this->SQL == "")
			return false;

		$rs = mysql_query($this->SQL,$this->conn);
		if($rs=== false)
		{
			$this->SQL = "";
			$errorMsg = mysql_errno($this->conn) . ": " . mysql_error($this->conn);
			$this->displayError();
		}

		$this->SQL = "";
		return mysql_affected_rows();

	}

	public function execute()
	{
		if($this->SQL == "")
			return false;
		$rs = mysql_query($this->SQL,$this->conn);
		if($rs=== false)
		{
			$this->SQL = "";
			$errorMsg = mysql_errno($this->conn) . ": " . mysql_error($this->conn);
			$this->displayError();
		}
		
		$this->SQL = "";
		return mysql_affected_rows();
		
	}
	
	public function insert()
	{
		if($this->SQL == "")
			return false;
		
		$rs = mysql_query($this->SQL,$this->conn);
		if($rs=== false)
		{
			$this->SQL = "";
			$this->errorMsg = mysql_errno($this->conn) . ": " . mysql_error($this->conn);
			$this->displayError();
		}
		
		$this->SQL = "";
		return mysql_insert_id();
	}
	
	public function close()
	{
		$this->errorMsg = "";
		$this->successMsg = "";

		$this->DB_HOST = "";
		$this->DB_NAME = "";
		$this->DB_USER = "";
		$this->DB_PASSWORD = "";

		if($this->conn)
			mysql_close($this->conn);
			
		$this->SQL = "";
	}
	
}



?>