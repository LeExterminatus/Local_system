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

			//проверка доступа к операции
			$this->GetUserInfo();
			if ($this->UserInfoArray[0]['idstatus'] > 2) 
			{
				
			}
			else
			{
				$CheckQueryStr = "SELECT id FROM warehouses.warehouses_managers WHERE warehouses.warehouses_managers.manager = :user AND warehouses.warehouses_managers.warehouse = :warehouse";
				$CheckQuery = $conn->prepare($CheckQueryStr); 
				$CheckQuery->bindValue(':warehouse', $_POST['wareouse_id']);
				$CheckQuery->bindValue(':user', $this->UserId);
				$CheckQuery->execute();
				$Checkdata = $CheckQuery->fetchAll(PDO::FETCH_ASSOC);
				if (count($Checkdata) <= 0)
				{
					//echo json_encode('');
					exit();
				}
			}
			//echo json_encode($_POST);
			//exit();

			$warehouse_id = $_POST['wareouse_id'];
			$contragent_id = $_POST['contragent_id'];

			$prod_line_quantity = (count($_POST)-3) / 8;

			//echo json_encode($prod_line_quantity);
			//exit();
			try 
			{
				$conn->beginTransaction();
				$InvomeQuery = "INSERT INTO warehouses.income VALUES (DEFAULT,'".$this->Date."',:contragent_id,".$this->UserId.",:warehouse_id) returning id";

				$Query = $conn->prepare($InvomeQuery);

				$Query->bindParam(':contragent_id', $contragent_id, PDO::PARAM_STR);
				$Query->bindParam(':warehouse_id', $warehouse_id, PDO::PARAM_STR);

				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);

				$income_id = $data[0]['id'];

				$IncomeCompositionQuery = "INSERT INTO warehouses.income_composition VALUES";
				for ($i=0; $i < $prod_line_quantity; $i++) 
				{ 
					//собираем запрос для состава прихода
					if ($_POST['id_'.$i] == null OR $_POST['id_'.$i] == 'null') 
					{
						//внести товар в бд
						if ($_POST['buh_kode_'.$i] == 0 OR $_POST['buh_kode_'.$i] == null OR $_POST['buh_kode_'.$i] == '') 
						{
							$QueryStr = "INSERT INTO warehouses.products VALUES (DEFAULT,:name,:measure,:cat,null,:designation) returning id";
							$QueryTmp = $conn->prepare($QueryStr);
						}
						else
						{
							$QueryStr = "INSERT INTO warehouses.products VALUES (DEFAULT,:name,:measure,:cat,:buh_kode,:designation) returning id";
							$QueryTmp = $conn->prepare($QueryStr);
							$QueryTmp->bindParam(':buh_kode', $_POST['buh_kode_'.$i], PDO::PARAM_STR); 
						}
						$QueryTmp->bindParam(':name', $_POST['name_'.$i], PDO::PARAM_STR);
						$QueryTmp->bindParam(':measure', $_POST['measure_'.$i], PDO::PARAM_STR);
						$QueryTmp->bindParam(':cat', $_POST['category_'.$i], PDO::PARAM_STR);
						$QueryTmp->bindParam(':designation', $_POST['designation_'.$i], PDO::PARAM_STR);

						$QueryTmp->execute();
						$data = $QueryTmp->fetchAll(PDO::FETCH_ASSOC);
						$prod_id = $data[0]['id']; // это ид товара
					}
					else
					{
						$prod_id = $_POST['id_'.$i];
					}
					if ($_POST['quantity_'.$i] < 0 OR $_POST['price_'.$i] < 0) 
					{
						$conn->rollBack();
						echo json_encode(2);
						exit();
					}
					$IncomeCompositionQuery .= " (DEFAULT, ".$prod_id.", ".$_POST['quantity_'.$i].", ".$_POST['price_'.$i].", ".$income_id."),";
					/*$IncomeCompositionQuery .= " (DEFAULT, :prod_id_".$i.", :qunatity".$i.", :price".$i.", :income_id".$i."),";
					$Query->bindParam(':prod_id_'.$i, $prod_id, PDO::PARAM_STR);
					$Query->bindParam(':qunatity'.$i, $_POST['quantity_'.$i], PDO::PARAM_STR);
					$Query->bindParam(':price'.$i, $_POST['price_'.$i], PDO::PARAM_STR);
					$Query->bindParam(':income_id'.$i, $income_id, PDO::PARAM_STR);
					*/
				}
				$IncomeCompositionQuery = substr($IncomeCompositionQuery, 0, -1);

				$Query = $conn->prepare($IncomeCompositionQuery);
			
				$Query->execute();
				$conn->commit();
				echo json_encode(0); // успешно
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