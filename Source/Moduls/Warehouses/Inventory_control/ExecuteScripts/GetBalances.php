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
			//проверять, какую категорию ищет юзер!!!
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
				$CheckQuery->bindValue(':warehouse', $_POST['warehouse_id']);
				$CheckQuery->bindValue(':user', $this->UserId);
				$CheckQuery->execute();
				$Checkdata = $CheckQuery->fetchAll(PDO::FETCH_ASSOC);
				if (count($Checkdata) <= 0)
				{
					//echo json_encode($_POST['warehouse']);
					exit();
				}
			}

			$search_prod = $_POST['search_prod_txt'];
			$search_prod_txt = mb_strtoupper($_POST['search_prod_txt']);
			$search_prod_kod = $_POST['search_prod_txt'];

			/*
			$QueryStr = "SELECT tbl.product, tbl.remaining_quantity, PRD.name, 
						PRD.measure AS measure_id, MS.name AS measure, PRD.category AS category_id,
						CTG.name AS categori, PRD.buh_kode, PRD.designation
						FROM (
						SELECT INCC.product,
						SUM(INCC.quantity) - COALESCE(
						(SELECT SUM(EXPC.quantity)
						FROM warehouses.expense_composition AS EXPC
						WHERE INCC.id = EXPC.income_id), 0
						) AS remaining_quantity
						FROM warehouses.income AS INC
						LEFT JOIN warehouses.income_composition AS INCC
						ON INC.id = INCC.income_id
						WHERE INC.warehouse = :warehouse
						GROUP BY INCC.product, INCC.id
						) AS TBL
						LEFT JOIN warehouses.products AS PRD
						ON TBL.product = PRD.id
						LEFT JOIN warehouses.categories AS CTG ON CTG.id = PRD.category
						LEFT JOIN warehouses.measure AS MS ON MS.id = PRD.measure
						WHERE TBL.remaining_quantity <> 0";
			*/
			$QueryStr = "SELECT product, remaining_quantity, PRD.name, 
						PRD.measure AS measure_id, MS.name AS measure, PRD.category AS category_id,
						CTG.name AS categori, PRD.buh_kode, PRD.designation
						FROM 
						(SELECT ZTBL.product, SUM(ZTBL.remaining_quantity) AS remaining_quantity FROM (SELECT INCC.product, SUM(INCC.quantity) - COALESCE((SELECT SUM(expc.quantity)
						FROM warehouses.expense_composition AS expc WHERE INCC.id = expc.income_id), 0) AS remaining_quantity
						FROM warehouses.income AS INC
						LEFT JOIN warehouses.income_composition AS INCC
						ON INC.id = INCC.income_id
						WHERE INC.warehouse = :warehouse
						GROUP BY INCC.product, INCC.id) AS ZTBL
						GROUP BY ZTBL.product) AS TTL
						LEFT JOIN warehouses.products AS PRD
						ON product = PRD.id
						LEFT JOIN warehouses.categories AS CTG ON CTG.id = PRD.category
						LEFT JOIN warehouses.measure AS MS ON MS.id = PRD.measure
						WHERE remaining_quantity <> 0";
				if ($_POST['cat_id'] != -1 OR $_POST['cat_id'] != '-1') 
				{

					//echo json_encode($_POST['cat_id']);
					//exit();

					$QueryStr .= " AND PRD.category = :cat";
					if ($_POST['search_prod_txt'] != 0 AND $_POST['search_prod_txt'] !== '' AND $_POST['search_prod_txt'] != null) 
					{
						$QueryStr.=" AND (prd.designation LIKE '%' || :search_prod || '%' OR UPPER(PRD.name) LIKE '%' || :search_prod_txt || '%' OR prd.buh_kode::text LIKE '%' || :search_prod_buh || '%')";
						$Query = $conn->prepare($QueryStr); 
						$Query->bindValue(':search_prod_txt', $search_prod_txt); 
						$Query->bindValue(':search_prod', $search_prod);
						$Query->bindValue(':search_prod_buh', $search_prod_kod);
					}
					else
					{
						$Query = $conn->prepare($QueryStr); 
					}
					$Query->bindValue(':cat', $_POST['cat_id']);
				}
				else
				{
					if ($_POST['search_prod_txt'] != 0 AND $_POST['search_prod_txt'] !== '' AND $_POST['search_prod_txt'] != null) 
					{
						$QueryStr.=" AND (prd.designation LIKE '%' || :search_prod || '%' OR UPPER(PRD.name) LIKE '%' || :search_prod_txt || '%' OR prd.buh_kode::text LIKE '%' || :search_prod_buh || '%')";
						$Query = $conn->prepare($QueryStr); 
						$Query->bindValue(':search_prod_txt', $search_prod_txt); 
						$Query->bindValue(':search_prod', $search_prod);
						$Query->bindValue(':search_prod_buh', $search_prod_kod);
					}
					else
					{
						$Query = $conn->prepare($QueryStr); 
					}
				}
				
				$Query->bindValue(':warehouse', $_POST['warehouse_id']); 
				
				//echo json_encode($QueryStr);
				//exit();
				
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