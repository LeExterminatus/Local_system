<?php
header('content-type: application/json');
/*$ServerP = $_POST['srv'];
$DatabaseP = $_POST['db'];
$UIDP = $_POST['usr'];
$PWDP = $_POST['pwd'];
$portP = $_POST['port'];
*/

class ControlDBConnectPG
{
	private static $db = null;
	public $Server;
	public $Database;
	public $UID;
	public $PWD;
	public $port;
	public $conn;
//$ServerP,$DatabaseP,$UIDP,$PWDP,$portP
	public function __construct()
	{
		$this->Server = $_POST['srv'];
		$this->Database = $_POST['db'];
		$this->UID = $_POST['usr'];
		$this->PWD = $_POST['pwd'];
		$this->port = $_POST['port'];
		try 
		{
			$this->conn = new PDO('pgsql:host='.$this->Server.';port='.$this->port.';dbname='.$this->Database.';', ''.$this->UID.'', ''.$this->PWD.'',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch (PDOException $e)
		{
			die($e->getMessage());
		}
	}
	public static function GetDB() 
	{
    	if (self::$db == null) self::$db = new ControlDBConnectPG();
    	return self::$db;
  	}
  	public function GetConn()
  	{
  		return $this->conn;
  	}
}
$DBConnect = ControlDBConnectPG::GetDb();
if ($DBConnect) 
{
	echo json_encode(1);
}
else
{
	echo json_encode($DBConnect);
}
?>