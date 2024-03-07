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
			$search_prod = $_POST['search_prod_txt'];
			$search_prod_txt = mb_strtoupper($_POST['search_prod_txt']);
			$search_prod_kod = $_POST['search_prod_txt'];

			//echo json_encode($search_prod);
			//exit();
			/*
			switch ($_POST['cat']) 
			{
				case 1:
					$QueryStr = "SELECT DISTINCT ON (t1.kod) t1.kod as designation, t1.naim, p.id, p.buh_kode, 796 as kei
								FROM (
								    SELECT DISTINCT CAST(kod AS text) AS kod, naim
								    FROM importer.r10857 WHERE NOT EXISTS (
									    SELECT 1
									    FROM warehouses.products
									    WHERE category = 1 AND designation::text = CAST(importer.r10857.kod AS text)
									)
								    UNION
								    SELECT DISTINCT (designation::text) AS KOD, name
								    FROM warehouses.products
								    WHERE category = 1
								) AS t1
								LEFT JOIN warehouses.products p ON t1.kod = CAST(p.designation AS text)
								left join warehouses.measure M on M.id = 796";
					if ($_POST['search_prod_txt'] != 0 AND $_POST['search_prod_txt'] !== '' AND $_POST['search_prod_txt'] != null) 
					{
						
						//$QueryStr.=" WHERE t1.kod LIKE CONCAT('%', :search_prod, '%') OR UPPER(t1.naim) LIKE CONCAT('%', :search_prod_txt, '%')";
						$QueryStr.=" WHERE t1.kod LIKE '%' || :search_prod || '%' OR UPPER(t1.naim) LIKE '%' || :search_prod_txt || '%' OR p.buh_kode::text LIKE '%' || :search_prod_buh || '%'";
						$Query = $conn->prepare($QueryStr); 
						$Query->bindValue(':search_prod_txt', $search_prod_txt); 
						$Query->bindValue(':search_prod', $search_prod);
						$Query->bindValue(':search_prod_buh', $search_prod_kod);
						//$QueryStr.=" WHERE t1.kod LIKE '%' || '".$search_prod."' || '%' OR UPPER(t1.naim) LIKE '%' || '".$search_prod_txt."' || '%'";
						//$Query = $conn->prepare($QueryStr); 
					}
					else
					{
						$Query = $conn->prepare($QueryStr); 
					}
					break;
				case 2:
					$QueryStr = "SELECT DISTINCT ON (t1.kod) t1.kod as designation, t1.naim, p.id, p.buh_kode, COALESCE(p.measure::text, t1.kei) as kei, m.name as mes
								FROM (
								SELECT DISTINCT ON (km) CAST(km AS text) AS kod, naim, kei
								FROM importer.r10850
								WHERE NOT EXISTS (
								    SELECT 1
								    FROM warehouses.products
								    WHERE category = 2 AND designation::text = CAST(importer.r10850.km AS text)
								)
								UNION
								SELECT DISTINCT (designation::text) AS KOD, name, (measure::text) as kei
								FROM warehouses.products
								WHERE category = 2
								) AS t1
								LEFT JOIN warehouses.products p ON t1.kod = CAST(p.designation AS text)
								left join warehouses.measure M on M.id = COALESCE(p.measure, CAST(t1.kei AS INTEGER))
								WHERE t1.kod != '' AND t1.kei != '' AND t1.kei != '000'";
					if ($_POST['search_prod_txt'] != 0 AND $_POST['search_prod_txt'] !== '' AND $_POST['search_prod_txt'] != null) 
					{
						
						$QueryStr.=" AND (t1.kod LIKE '%' || :search_prod || '%' OR UPPER(t1.naim) LIKE '%' || :search_prod_txt || '%' OR p.buh_kode::text LIKE '%' || :search_prod_buh || '%') order by t1.kod";
						$Query = $conn->prepare($QueryStr); 
						$Query->bindValue(':search_prod_txt', $search_prod_txt); 
						$Query->bindValue(':search_prod', $search_prod);
						$Query->bindValue(':search_prod_buh', $search_prod_kod);
					}
					else
					{
						$QueryStr .= " order by t1.kod";
						$Query = $conn->prepare($QueryStr); 
					}
					break;
				case 6:
					$QueryStr = "SELECT DISTINCT ON (t1.text) t1.text as designation, t1.naim, p.id, p.buh_kode, 796 as kei
								FROM (
									SELECT DISTINCT CAST(nizd||' '||mod AS text), naim FROM importer.m10870 WHERE mod != 'Ц' AND NOT EXISTS (
											SELECT 1
											FROM warehouses.products
											WHERE category = 6 AND designation::text = CAST(importer.m10870.nizd||' '||importer.m10870.mod AS text)
										)
									UNION
									SELECT DISTINCT designation::text, name FROM warehouses.products WHERE category = 6
								) AS t1
								LEFT JOIN warehouses.products p ON t1.text = CAST(p.designation AS text)
								left join warehouses.measure M on M.id = 796";
					if ($_POST['search_prod_txt'] != 0 AND $_POST['search_prod_txt'] !== '' AND $_POST['search_prod_txt'] != null) 
					{
						
						//$QueryStr.=" WHERE t1.kod LIKE CONCAT('%', :search_prod, '%') OR UPPER(t1.naim) LIKE CONCAT('%', :search_prod_txt, '%')";
						$QueryStr.=" WHERE t1.text LIKE '%' || :search_prod || '%' OR UPPER(t1.naim) LIKE '%' || :search_prod_txt || '%' OR p.buh_kode::text LIKE '%' || :search_prod_buh || '%'";
						$Query = $conn->prepare($QueryStr); 
						$Query->bindValue(':search_prod_txt', $search_prod_txt); 
						$Query->bindValue(':search_prod', $search_prod);
						$Query->bindValue(':search_prod_buh', $search_prod_kod);
						//$QueryStr.=" WHERE t1.kod LIKE '%' || '".$search_prod."' || '%' OR UPPER(t1.naim) LIKE '%' || '".$search_prod_txt."' || '%'";
						//$Query = $conn->prepare($QueryStr); 
					}
					else
					{
						$Query = $conn->prepare($QueryStr); 
					}
					break;
				case 7:
					echo json_encode(2);
					exit();
					break;
				case 15:
					echo json_encode(2);
					exit();
					break;
				default:
					$QueryStr = "SELECT p.id, p.name as naim, p.measure as kei, p.category, p.buh_kode, p.designation
								FROM warehouses.products AS p
								LEFT JOIN warehouses.measure AS m ON m.id = p.measure 
								WHERE p.category = :cat";
					if ($_POST['search_prod_txt'] != 0 AND $_POST['search_prod_txt'] !== '' AND $_POST['search_prod_txt'] != null) 
					{
						
						//$QueryStr.=" WHERE t1.kod LIKE CONCAT('%', :search_prod, '%') OR UPPER(t1.naim) LIKE CONCAT('%', :search_prod_txt, '%')";
						$QueryStr.=" AND (p.designation LIKE '%' || :search_prod || '%' OR UPPER(p.name) LIKE '%' || :search_prod_txt || '%' OR p.buh_kode::text LIKE '%' || :search_prod_buh || '%')";
						$Query = $conn->prepare($QueryStr); 
						$Query->bindValue(':search_prod_txt', $search_prod_txt); 
						$Query->bindValue(':search_prod', $search_prod);
						$Query->bindValue(':search_prod_buh', $search_prod_kod);
						$Query->bindValue(':cat', $_POST['cat']); 
						//$QueryStr.=" WHERE t1.kod LIKE '%' || '".$search_prod."' || '%' OR UPPER(t1.naim) LIKE '%' || '".$search_prod_txt."' || '%'";
						//$Query = $conn->prepare($QueryStr); 
					}
					else
					{
						$Query = $conn->prepare($QueryStr); 
						$Query->bindValue(':cat', $_POST['cat']); 
					}	 
					break;
			}*/

			$QueryStr = "SELECT p.id, p.name as naim, p.measure as kei, p.category, p.buh_kode, p.designation
								FROM warehouses.products AS p
								LEFT JOIN warehouses.measure AS m ON m.id = p.measure 
								WHERE p.category = :cat";
			if ($_POST['search_prod_txt'] != 0 AND $_POST['search_prod_txt'] !== '' AND $_POST['search_prod_txt'] != null) 
			{
				
				//$QueryStr.=" WHERE t1.kod LIKE CONCAT('%', :search_prod, '%') OR UPPER(t1.naim) LIKE CONCAT('%', :search_prod_txt, '%')";
				$QueryStr.=" AND (p.designation LIKE '%' || :search_prod || '%' OR UPPER(p.name) LIKE '%' || :search_prod_txt || '%' OR p.buh_kode::text LIKE '%' || :search_prod_buh || '%')";
				$Query = $conn->prepare($QueryStr); 
				$Query->bindValue(':search_prod_txt', $search_prod_txt); 
				$Query->bindValue(':search_prod', $search_prod);
				$Query->bindValue(':search_prod_buh', $search_prod_kod);
				$Query->bindValue(':cat', $_POST['cat']); 
				//$QueryStr.=" WHERE t1.kod LIKE '%' || '".$search_prod."' || '%' OR UPPER(t1.naim) LIKE '%' || '".$search_prod_txt."' || '%'";
				//$Query = $conn->prepare($QueryStr); 
			}
			else
			{
				$Query = $conn->prepare($QueryStr); 
				$Query->bindValue(':cat', $_POST['cat']); 
			}	 

				
				
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