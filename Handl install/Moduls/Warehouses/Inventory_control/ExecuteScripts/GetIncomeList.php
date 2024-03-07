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

			$QueryStr = "SELECT
						INC.id, INC.date,
						US.lastname || ' ' || left(US.firstname, 1) || '. ' || left(US.patronimic, 1) ||'.' AS name,
						CT.name || ' - ' || CT.city || ' (ИНН ' || CT.inn || ')' AS contragent,
						WH.name AS warehouse 
						FROM warehouses.income AS INC
						LEFT JOIN users AS US ON US.iduser = INC.recieved
						LEFT JOIN warehouses.contragent AS CT ON CT.id = INC.contragent
						LEFT JOIN warehouses.warehouses AS WH ON WH.id = INC.warehouse";
						
			$DB = $this->DBConnect;
			$conn = $DB->GetConn();

			//проверка доступа к операции
			$this->GetUserInfo();
			if ($this->UserInfoArray[0]['idstatus'] > 2) 
			{
				
			}
			else
			{
				$CheckQueryStr = "SELECT id FROM warehouses.warehouses_managers WHERE warehouses.warehouses_managers.manager = :user AND warehouses.warehouses_managers.warehouse = :warehouse";
				$CheckQuery = $conn->prepare($CheckQueryStr); 
				$CheckQuery->bindValue(':warehouse', $_POST['warehouse']);
				$CheckQuery->bindValue(':user', $this->UserId);
				$CheckQuery->execute();
				$Checkdata = $CheckQuery->fetchAll(PDO::FETCH_ASSOC);
				if (count($Checkdata) <= 0)
				{
					//echo json_encode('');
					exit();
				}
			}
			
			if ($_POST['warehouse'] == -1) 
			{
				//$QueryStr .= " WHERE INC.recieved = :user ORDER BY INC.id DESC";
				$QueryStr .= " ORDER BY INC.id DESC";
				$Query = $conn->prepare($QueryStr); 
				//$Query->bindValue(':user', $this->UserId);
			}
			else
			{
				//$QueryStr .= " WHERE INC.recieved = :user AND WH.id = :warehouse ORDER BY INC.id DESC";	
				$QueryStr .= " WHERE WH.id = :warehouse ORDER BY INC.id DESC";
				$Query = $conn->prepare($QueryStr); 
				//$Query->bindValue(':user', $this->UserId); 
				$Query->bindValue(':warehouse', $_POST['warehouse']);
			}

			
			//$Query = $conn->prepare($QueryStr); 
			try
			{
				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);
			} 
			catch(Exeption $e) 
			{
				echo json_encode(2);	
			}
			if ($data == null) 
			{
				echo json_encode(1);
				exit();
			}
			/*if (count($data[0]) <= 0) 
			{
				echo json_encode(1);
				exit();
			}*/
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