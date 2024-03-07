<?php
class Warehouses_Adm extends GUIGenerator
{
	public function RequireModuls()
	{
		parent::RequireModuls();
		echo "<link rel='stylesheet' type='text/css' href='style.css'>";
		echo "<script src='control.js'></script>";
	}
	public function MainGeneration()
	{
		//$this->GenerateBlockForm();
		$QueryStr = "SELECT * FROM Warehouses.warehouses";
		$DB = $this->DBConnect;
		$conn = $DB->GetConn();
		$Query = $conn->prepare($QueryStr);
		$Query->execute();
		$array = $Query->fetchAll(PDO::FETCH_ASSOC);
		echo "<div>";
		echo "Список складов";
		echo "<table>";
		echo "<tr class='action_panel'><td>Ид</td><td>Название</td><td>Действие</td></tr>";
		for ($i=0; $i < count($array); $i++) 
		{ 
			echo "<tr><td>".$array[$i]['id']."</td><td>".$array[$i]['name']."</td><td><button onclick='DeleteWarehouse()'>Удалить</button></td></tr>";
		}
		echo "<tr class='action_panel'><td>Название</td><td>Действие</td></tr>";
		echo "<tr><td><input id='add_warehouse_name' type='text'></td><td><button id='add_warehouse' onclick='AddNewWarehouse()'>Добавить</button></td></tr>";
		echo "</table>";
		$warehouses_arr = $array;

		$QueryStr = "SELECT 
		WM.id, WM.manager, WM.warehouse||' (Склад '||WH.name||')' AS warehouse,  US.lastname||' '||SUBSTRING(US.firstname,1,1)||'. '||SUBSTRING(US.patronimic,1,1)||'. - '||DV.briefly AS usr
		FROM Warehouses.warehouses_managers AS WM
		LEFT JOIN public.users AS US ON WM.manager = US.iduser
		LEFT JOIN public.divisions AS DV ON US.division = DV.iddivision
		LEFT JOIN Warehouses.warehouses AS WH ON WH.id = WM.warehouse";
		$DB = $this->DBConnect;
		$conn = $DB->GetConn();
		$Query = $conn->prepare($QueryStr);
		$Query->execute();
		$array = $Query->fetchAll(PDO::FETCH_ASSOC);
		echo "Список заведующих складом";
		echo "<table>";
		echo "<tr class='action_panel'><td>Ид</td><td>Ид сотрудника</td><td>Сотрудника</td><td>Ид склада</td><td>Действие</td></tr>";
		for ($i=0; $i < count($array); $i++) 
		{ 
			echo "<tr><td>".$array[$i]['id']."</td><td>".$array[$i]['manager']."</td><td>".$array[$i]['usr']."</td><td>".$array[$i]['warehouse']."</td><td><button onclick='DeleteWarehouseManager()'>Удалить</button></td></tr>";
		}
		echo "<tr class='action_panel'><td>Ид сотрудника</td><td>Ид склада</td><td>Действие</td></tr>";
		$QueryStr = "SELECT 
		US.iduser, US.lastname||' '||SUBSTRING(US.firstname,1,1)||'. '||SUBSTRING(US.patronimic,1,1)||'. - '||PS.description||' '||DV.briefly AS usr
		FROM users AS US
		LEFT JOIN divisions AS DV ON US.division = DV.iddivision
		LEFT JOIN posts AS PS ON PS.idpost = US.post";
		$DB = $this->DBConnect;
		$conn = $DB->GetConn();
		$Query = $conn->prepare($QueryStr);
		$Query->execute();
		$array = $Query->fetchAll(PDO::FETCH_ASSOC);
		echo "<datalist id='users'>";
		for ($i=0; $i < count($array); $i++) 
		{ 
			echo "<option value=".$array[$i]['iduser'].">".$array[$i]['usr']."</option>";
		}
		echo "</datalist>";
		echo "<tr><td><input id='add_warehouse_manager_id' list='users'></td><td><input id='add_warehouse_control_id' type='text'></td><td><button id='add_warehouse_manager' onclick='AddNewWarehouseManager()'>Добавить</button></td></tr>";
		echo "</table>";

		$QueryStr = "SELECT * FROM Warehouses.categories";
		$DB = $this->DBConnect;
		$conn = $DB->GetConn();
		$Query = $conn->prepare($QueryStr);
		$Query->execute();
		$array = $Query->fetchAll(PDO::FETCH_ASSOC);
		echo "Список категорий";
		echo "<table>";
		echo "<tr class='action_panel'><td>Ид</td><td>Название</td><td>Ввод</td><td>Действие</td></tr>";
		for ($i=0; $i < count($array); $i++) 
		{ 
			echo "<tr><td>".$array[$i]['id']."</td><td>".$array[$i]['name']."</td><td>".$array[$i]['editable']."</td><td><button onclick='DeleteCategory()'>Удалить</button></td></tr>";
		}
		echo "<tr class='action_panel'><td>Название</td><td>Ввод</td><td>Действие</td></tr>";
		echo "<tr><td><input id='add_category_name' type='text'></td><td><input id='add_category_editable' type='text'></td><td><button id='add_category' onclick='AddCategory()'>Добавить</button></td></tr>";
		echo "</table>";
		$category_arr = $array;

		$QueryStr = "SELECT CIW.id, CIW.category||' ('||CT.name||')' AS category, CIW.warehouse||' ('||WH.name||')' AS warehouse
		FROM Warehouses.categories_in_warehouses AS CIW
		LEFT JOIN Warehouses.warehouses AS WH ON WH.id = CIW.warehouse
		LEFT JOIN Warehouses.categories AS CT ON CT.id = CIW.category";
		$DB = $this->DBConnect;
		$conn = $DB->GetConn();
		$Query = $conn->prepare($QueryStr);
		$Query->execute();
		$array = $Query->fetchAll(PDO::FETCH_ASSOC);
		echo "Список категорий для склада";
		echo "<table>";
		echo "<tr class='action_panel'><td>Ид</td><td>Ид склада</td><td>Ид категории</td><td>Действие</td></tr>";
		for ($i=0; $i < count($array); $i++) 
		{ 
			echo "<tr><td>".$array[$i]['id']."</td><td>".$array[$i]['warehouse']."</td><td>".$array[$i]['category']."</td><td><button onclick='DeleteCategoryInWarehouse()'>Удалить</button></td></tr>";
		}
		echo "<tr class='action_panel'><td>Ид склада</td><td>Ид категории</td><td>Действие</td></tr>";
		echo "<datalist id='categories'>";
		for ($i=0; $i < count($category_arr); $i++) 
		{ 
			echo "<option value=".$category_arr[$i]['id'].">".$category_arr[$i]['name']."</option>";
		}
		echo "</datalist>";
		echo "<datalist id='warehouses'>";
		for ($i=0; $i < count($warehouses_arr); $i++) 
		{ 
			echo "<option value=".$warehouses_arr[$i]['id'].">".$warehouses_arr[$i]['name']."</option>";
		}
		echo "</datalist>";
		echo "<tr><td><input id='add_warehouse_warehouse_id' type='text' list='warehouses'></td><td><input id='add_warehouse_cat_id' type='text' list='categories'></td><td><button id='add_in_warehouse_categories' onclick='AddCategoryInWarehouse()'>Добавить</button></td></tr>";
		echo "</table>";
		echo "</div>";
	}
}
?>