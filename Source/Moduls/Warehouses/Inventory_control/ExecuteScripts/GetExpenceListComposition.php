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
						INC_C.income_id, PRD.name, EXPC_C.quantity, MS.name AS measure, INC_C.price,
						PRD.designation, PRD.buh_kode, CTG.name AS category
						--,* 
						FROM warehouses.expense_composition AS EXPC_C
						LEFT JOIN warehouses.income_composition AS INC_C ON INC_C.id = EXPC_C.income_id
						LEFT JOIN warehouses.products AS PRD ON PRD.id = INC_C.product
						LEFT JOIN warehouses.categories AS CTG ON CTG.id = PRD.category
						LEFT JOIN warehouses.measure AS MS ON MS.id = PRD.measure
						WHERE EXPC_C.expension_id = :expension_id";
						
			$DB = $this->DBConnect;
			$conn = $DB->GetConn();

			$Query = $conn->prepare($QueryStr); 
			$Query->bindValue(':expension_id', $_POST['expension_id']);

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