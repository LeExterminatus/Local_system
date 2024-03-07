<?php
session_start();
//header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');
header('content-type: application/json; charset=utf-8');
require_once($_SERVER['DOCUMENT_ROOT']."/IVC/coreX.php");
class UserInfo extends ExecuteComand
{
	public $AjaxDefinition = 1;
	public function DefineConstr()
	{

		if ($_POST['Access'] == 1) 
		{
			//echo json_encode($_POST);
			//exit();

			$expence_Arr = array();
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

			$prod_line_quantity = (count($_POST)-3) / 2;
			if ($_POST['received'] == -1) 
			{
				$received = $this->UserId;
			}
			else
			{
				$received = $_POST['received'];
			}
			for ($i=0; $i < $prod_line_quantity; $i++) 
			{
				$QueryStr = "SELECT TBL.product, SUM(TBL.remaining_quantity) as remaining_quantity, TBL.id FROM (SELECT INCC.id, INCC.product, SUM(INCC.quantity) - COALESCE((SELECT SUM(EXPC.quantity)
							FROM warehouses.expense_composition AS EXPC WHERE INCC.id = EXPC.income_id), 0) AS remaining_quantity
							FROM warehouses.income AS INC
							LEFT JOIN warehouses.income_composition AS INCC
							ON INC.id = INCC.income_id
							WHERE INC.warehouse = :warehouse
							GROUP BY INCC.product, INCC.id) AS TBL
							WHERE TBL.remaining_quantity <> 0 AND product = :product
							GROUP BY TBL.product, TBL.id";
				$Query = $conn->prepare($QueryStr); 
				$Query->bindValue(':warehouse', $_POST['warehouse']);
				$Query->bindValue(':product', $_POST['prod_id'.$i]);
				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);

				$temp_quantity = $_POST['quantity'.$i];
				if (($data[0]['remaining_quantity'] - $temp_quantity) < 0) 
				{

					
				    // списывание с приходов
				    for ($y=0; $y < count($data); $y++) 
				    { 

				    	//echo json_encode(count($data).'ds');
						//exit();
				    	if (($data[$y]['remaining_quantity'] - $temp_quantity) <= 0) 
						{
					        $temp = array();
					        $temp['id'] = $data[$y]['id'];
					        $temp['quantity'] = $data[$y]['remaining_quantity'];
					        $expence_Arr[] = $temp;

					        $temp_quantity = $temp_quantity - $data[$y]['remaining_quantity'];
				    	}
				    	else
				    	{
				    		break;
				    	}

				    }

				}
				else
				{
				    $temp = array();
				    $temp['id'] = $data[0]['id'];
				    $temp['quantity'] = $temp_quantity;
				    $expence_Arr[] = $temp;

				}

				if ($temp_quantity < 0) 
				{
					// юзер наебал клиенсткую часть и пробросил количество, превыщающее остаток
					echo json_encode(-1);
					exit();
				}
			}
			//echo json_encode($expence_Arr);
			//exit();
			try {
				$conn->beginTransaction();
				$QueryStr = "INSERT INTO warehouses.expense VALUES (DEFAULT,'".$this->Date."',".$this->UserId.",:received) returning id";
				$Query = $conn->prepare($QueryStr);

				$Query->bindParam(':received', $received, PDO::PARAM_STR);

				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);
			
				for ($i=0; $i < count($expence_Arr); $i++) 
				{ 
					$QueryStr = "INSERT INTO warehouses.expense_composition VALUES (DEFAULT,:income_id,:quantity,:expence_id)";
					$QueryTmp = $conn->prepare($QueryStr);
					$QueryTmp->bindParam(':income_id', $expence_Arr[$i]['id'], PDO::PARAM_STR); 
					$QueryTmp->bindParam(':quantity', round($expence_Arr[$i]['quantity'], 3), PDO::PARAM_STR); 
					$QueryTmp->bindParam(':expence_id', $data[0]['id'], PDO::PARAM_STR); 
					$QueryTmp->execute();
				}
				$conn->commit();
				echo json_encode(1); // успешно
			} 
			catch (PDOException $e) 
			{
    			// Обработка ошибок
    			$conn->rollBack();

    			echo json_encode($e->getMessage(),JSON_UNESCAPED_UNICODE); // ошибка транзации!
			}

		}
		else
		{
			echo json_encode('У вас нет доступа!');
		}
	}
}
$Rgfdss = new UserInfo();
?>