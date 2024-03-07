<?php
session_start();
header('content-type: application/json');
require_once($_SERVER['DOCUMENT_ROOT']."/IVC/coreX.php");
class UserInfo extends ExecuteComand
{
	public $AjaxDefinition = 1;

	public function DefineConstr()
	{
		if ($_POST['Access'] == 1) 
		{
			$QueryStr = "SELECT * FROM warehouses.contragent";
			$DB = $this->DBConnect;
			$conn = $DB->GetConn();
			$Query = $conn->prepare($QueryStr); 
			try
			{
				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);
			} 
			catch(Exeption $e) 
			{
				echo json_encode(2);	
			}
			if (count($data[0]) <= 0) 
			{
				echo json_encode(1);
				exit();
			}
			echo json_encode($data);
		}
		else
		{
			echo json_encode('У вас нет доступа!');
		}
	}
}
$Rgfdss = new UserInfo();
?>