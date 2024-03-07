<?php
header('content-type: application/json');
class ControlDBConnectPG
{
	private static $db = null;
	public $Server;
	public $Database;
	public $UID;
	public $PWD;
	public $port;
	public $conn;

	public function __construct()
	{
		$ini_array = parse_ini_file("conf.ini");
		$this->Server = $ini_array['Server'];
		$this->Database = $ini_array['Database'];
		$this->UID = $ini_array['UID'];
		$this->PWD = $ini_array['PWD'];
		$this->port = $ini_array['port'];
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
$DB = ControlDBConnectPG::GetDB();
$conn = $DB->GetConn();
$conn->beginTransaction();

$QueryStr = file_get_contents('createDB_warehouses.sql');
$sqlCommands = explode(';', $QueryStr); 

foreach ($sqlCommands as $sqlCommand) 
{
    $sqlCommand = trim($sqlCommand);
    if (!empty($sqlCommand)) 
    {
        $Query = $conn->prepare($sqlCommand);
        $Query->execute();
    }
}

$conn->commit();
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$zip = new ZipArchive;
if ($zip->open('Moduls/Warehouses.zip') === TRUE)
{
    $zip->extractTo($rootPath."/IVC");
    $zip->close();

    echo json_encode(1);
} 
else 
{
    echo json_encode("Возникла проблема с распаковкой архива модуля складского учёта. Проверьте, не содержится ли папка с именем IVC по корневой директории сайта.");
}

?>