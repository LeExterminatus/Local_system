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
				$QueryStr = "DELETE FROM warehouses.warehouses_managers WHERE id = :recordid";
				$DB = $this->DBConnect;
				$conn = $DB->GetConn();
				$Query = $conn->prepare($QueryStr);
				$Query->bindParam(':recordid', $_POST['recordid'], PDO::PARAM_STR); 
				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);
			} 
			catch (PDOException $e) 
			{
			    if ($e->getCode() == "23503") 
			    {
			        // Обработка исключения, связанного с нарушениемграничения внешнего ключа
			        echo json_encode(1);	
					exit();
			    } 
			    else 
			    {
			        // Обработка других исключений
			    	echo json_encode(2);	
					exit();
			    }
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