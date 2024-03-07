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

			$QueryStr = "SELECT PD.name, PD.buh_kode, PD.designation, INCC.quantity, MS.name AS measure, 
						CTG.name AS category, INCC.price
						FROM warehouses.income_composition AS INCC
						LEFT JOIN warehouses.products AS PD ON PD.id = INCC.product
						LEFT JOIN warehouses.measure AS MS ON MS.id = PD.measure
						LEFT JOIN warehouses.categories AS CTG ON CTG.id = PD.category
						WHERE INCC.income_id = :income_id";
						
			$DB = $this->DBConnect;
			$conn = $DB->GetConn();

			$Query = $conn->prepare($QueryStr); 
			$Query->bindValue(':income_id', $_POST['income_id']);

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