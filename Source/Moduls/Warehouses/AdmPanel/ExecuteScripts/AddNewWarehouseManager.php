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
			try
			{
				$QueryStr = "INSERT INTO warehouses.warehouses_managers VALUES (DEFAULT,:manager,:warehouse)";
				$DB = $this->DBConnect;
				$conn = $DB->GetConn();
				$Query = $conn->prepare($QueryStr);
				$Query->bindParam(':manager', $_POST['manager'], PDO::PARAM_STR); 
				$Query->bindParam(':warehouse', $_POST['warehouse'], PDO::PARAM_STR); 
				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);
			} 
			catch(Exeption $e) 
			{
				echo json_encode(2);	
				exit();
			}
			
			echo json_encode(0);
			exit();
		}
		else
		{
			echo json_encode('У вас нет доступа!');
		}
	}
}
$Rgfdss = new UserInfo();
?>