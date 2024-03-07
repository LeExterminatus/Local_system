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
			$DB = $this->DBConnect;
			$conn = $DB->GetConn();
			
				$QueryStr = "SELECT count(*) AS cnt FROM warehouses.products WHERE designation = :id";
				
				$Query = $conn->prepare($QueryStr);
				$Query->bindParam(':id', $_POST['kod'], PDO::PARAM_STR); 
				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);
				if ($data[0]['cnt'] >= 1) 
				{
					echo json_encode(1);
					exit();
				}
				$QueryStr = "INSERT INTO warehouses.products VALUES (DEFAULT,:name,:measure,:cat,:etc,:designation)";
				$Query = $conn->prepare($QueryStr);
				$Query->bindParam(':designation', $_POST['kod'], PDO::PARAM_STR);
				$Query->bindParam(':name', $_POST['name'], PDO::PARAM_STR); 
				$Query->bindParam(':measure', $_POST['measure'], PDO::PARAM_STR); 
				$Query->bindParam(':cat', $_POST['cat'], PDO::PARAM_STR); 
				if (!empty($_POST['buh'])) 
				{
				    $Query->bindParam(':etc', $_POST['buh'], PDO::PARAM_STR);
				}
				else
				{
				    $etc_kod = null;
				    $Query->bindParam(':etc', $etc_kod, PDO::PARAM_NULL);
				} 
				if (!empty($_POST['kod'])) 
				{
				    $Query->bindParam(':designation', $_POST['kod'], PDO::PARAM_STR);
				}
				else
				{
				    $designation = null;
				    $Query->bindParam(':designation', $designation, PDO::PARAM_NULL);
				} 
				//вносим то, что дал юзер
			

			
			
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