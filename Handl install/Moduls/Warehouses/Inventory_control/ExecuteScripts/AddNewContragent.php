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
			$QueryStr = "SELECT count(*) AS cnt FROM warehouses.contragent WHERE inn = :inn";
			$DB = $this->DBConnect;
			$conn = $DB->GetConn();
			$Query = $conn->prepare($QueryStr);
			$Query->bindParam(':inn', $_POST['inn'], PDO::PARAM_STR); 
			$Query->execute();
			$data = $Query->fetchAll(PDO::FETCH_ASSOC);
			if ($data[0]['cnt'] >= 1) 
			{
				echo json_encode(1);
				exit();
			}
			$QueryStr = "INSERT INTO warehouses.contragent VALUES (DEFAULT,:name,:city,:inn)";
			$Query = $conn->prepare($QueryStr);
			$Query->bindParam(':name', $_POST['name'], PDO::PARAM_STR); 
			$Query->bindParam(':city', $_POST['city'], PDO::PARAM_STR); 
			$Query->bindParam(':inn', $_POST['inn'], PDO::PARAM_STR); 
			
			try
			{
				$Query->execute();
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