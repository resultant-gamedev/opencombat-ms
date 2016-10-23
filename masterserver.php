<?php
if (!defined('OCMS') || OCMS != true) { exit(); }

class OCMasterServer
{
	private $config;
	private $conn;
	
	function __construct($cfg)
	{
		$this->config = $cfg;
		$this->conn = new mysqli($cfg['database']['host'], $cfg['database']['user'], $cfg['database']['pass'], $cfg['database']['db']);
		
		if ($this->conn->connect_error)
		{
			echo "Connection failed: " . $this->conn->connect_error;
			exit();
		}
	}
	
	function __destruct()
	{
		$this->conn->close();
	}
	
	private function escape_string($str)
	{
		return $this->conn->real_escape_string($str);
	}
	
	function fetch_server($name = '')
	{
		$query = "SELECT * FROM servers WHERE `name` LIKE '%".$this->escape_string($name)."%';";
		$result = $this->conn->query($query);
		
		$servers = array();
		
		while ($row = $result->fetch_assoc())
		{
			$servers[] = $row;
		}
		
		return $servers;
	}
	
	function register_server($ip, $port, $name)
	{
		if ($ip == '' || $port == '' || $name == '')
		{
			return false;
		}
		
		$query = "SELECT id FROM servers WHERE `ip`='".$this->escape_string($ip)."' AND `port`='".$this->escape_string($port)."' LIMIT 1;";
		$result = $this->conn->query($query);
		
		if ($result->num_rows > 0)
		{
			$id = $result->fetch_assoc()['id'];
			$query = "UPDATE servers SET `name`='".$this->escape_string($name)."' WHERE `id`='".$this->escape_string($id)."' LIMIT 1;";
			
			return ($this->conn->query($query) === true);
		}
		else
		{
			$query = "INSERT INTO servers (`ip`, `port`, `name`)
			VALUES ('".$this->escape_string($ip)."', '".$this->escape_string($port)."', '".$this->escape_string($name)."');";
			
			return ($this->conn->query($query) === true);
		}
		
		return false;
	}
}

?>
