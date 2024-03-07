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

	public function __construct($dbs = 0)
	{
		$ini_array = parse_ini_file("conf.ini");
		$this->Server = $ini_array['Server'];
		if ($dbs == 0) 
		{
			$this->Database = $ini_array['Database'];
		}
		else
		{
			$this->Database = $dbs;
		}
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
function Delete($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            Delete(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}
$DB = ControlDBConnectPG::GetDB();
$conn = $DB->GetConn();

$QueryStr = "SELECT datname FROM pg_catalog.pg_database WHERE datname = 'ivc'";
$Query = $conn->prepare($QueryStr);
$Query->execute();
$array = $Query->fetchAll(PDO::FETCH_ASSOC);
if (count($array) >= 1) 
{
	$QueryStr = "DROP DATABASE ivc";
	$conn->exec($QueryStr);
}

$QueryStr = "CREATE DATABASE ivc";
$conn->exec($QueryStr); 
$conn = null; 

$DB = new ControlDBConnectPG('ivc');
$conn = $DB->GetConn();
$conn->beginTransaction();

$QueryStr = file_get_contents('createDB.sql');
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

$QueryStr = "CREATE FUNCTION public.getuserauthinfo(loginusr character varying) RETURNS TABLE(pass character varying, id integer)
    LANGUAGE plpgsql
    AS $$
BEGIN
	Return query
	SELECT Password AS Pass, IdUser AS Id FROM AuthorizationInfo WHERE Login = LoginUsr; --INTO PasswordUsr, IdUsr;
END;
$$;";
$Query = $conn->prepare($QueryStr);
$Query->execute();

$config_ini = parse_ini_file('conf.ini');
$config_ini['Database'] = 'ivc';
$ini_content = '';
foreach ($config_ini as $key => $value) 
{
    $ini_content .= "$key=$value" . PHP_EOL;
}

file_put_contents('conf.ini', $ini_content, LOCK_EX);
$rootPath = $_SERVER['DOCUMENT_ROOT'];
if(file_exists($rootPath.'/IVC'))
{
	Delete($rootPath.'/IVC');
}

$zip = new ZipArchive;
if ($zip->open('BaseModul.zip') === TRUE) 
{
    $zip->extractTo($rootPath);
    $zip->close();

    if (!copy($rootPath.'/LocalSystem_installer/functions/conf.ini', $rootPath.'/IVC/conf.ini')) 
    {
        echo json_encode("Не удалось скопировать файл conf.ini. Проверьте его наличие в папке LocalSystem_installer/functions/ и начните установку заново.");
        exit;
    }

    echo json_encode(1);
} 
else 
{
    echo json_encode("Возникла проблема с распаковкой архива базового модуля. Проверьте, не содержится ли папка с именем IVC по корневой директории сайта.");
}

?>