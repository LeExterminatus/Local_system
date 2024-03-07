<?php
class Inv_cntrl extends GUIGenerator
{
	public $warehouses_arr;
	public $warehouses_separate_arr;
	public $measures_arr;
	//public $users_list;

	public function RequireModuls()
	{
		parent::RequireModuls();
		echo "<link rel='stylesheet' type='text/css' href='style.css'>";
		echo "<script src='control.js'></script>";
		echo "<script src='balances_control.js'></script>";
		echo "<script src='expence_control.js'></script>";
		echo "<script src='income_list_control.js'></script>";
		echo "<script src='expence_list_control.js'></script>";
	}
	public function MainGeneration()
	{
		$this->CollectMeasures();
		$this->GenerateFormAddContragent();
		$this->GenerateContragentSelectForm();
		$this->ColletUserInfoForWarehouses();
		$this->GenerateProductSelectForm();
		$this->GenerateFormAddProduct();
		$this->GenerateProductSelectForm_Expence();
		echo "<div class='main_container'>";
			echo "<div class='menu'>";
				echo "<button id='balances_container' class='activ_tab_indicator'>Остатки на складе</button>";
				echo "<button id='income_container'>Внести приход</button>";
				echo "<button id='expense_container'>Внести расход</button>";
				echo "<button id='income_list_container'>Список приходов</button>";
				echo "<button id='expence_list_container'>Список расходов</button>";
				echo "<button disabled id='move_container'>Перемещение товара</button>";
				echo "<button disabled id='booking_container'>Забронированый товар</button>";
			echo "</div>";
			echo "<div class='balances_container'>";
				//echo "Остатки";
				$this->GenerateFormBalances();
			echo "</div>";	
			echo "<div class='income_container hidden_elem'>";
				$this->GenerateFormProductIncome();
			echo "</div>";	
			echo "<div class='expense_container hidden_elem'>";
				//echo "Расход";
				$this->GenerateFormExpence();
			echo "</div>";	
			echo "<div class='income_list_container hidden_elem'>";
				$this->GenerateFormIncomeList();
			echo "</div>";	
			echo "<div class='expence_list_container hidden_elem'>";
				$this->GenerateFormExpenceList();
			echo "</div>";	
			echo "<div class='move_container hidden_elem'>";
				echo "перемещения";
			echo "</div>";	
			echo "<div class='booking_container hidden_elem'>";
				echo "бронь";
			echo "</div>";	
		echo "</div>";
	}
	public function GenerateBlockForm()
	{
		echo "<div class='block_content hidden_elem'>";
		echo "</div>";
	}
	public function GenerateFormAddContragent()
	{
		echo "<div class='add_contragent block_content hidden_elem'>";
			echo "<div class='form_container add_contragent_form'>";
				echo "<div class='form_name_head'>";
					echo "Внести нового поставщика";
				echo "</div>";
				echo "<div class='form_body_container'>";
					echo "<div class='form_body_item_in_container'>";
						echo "Наименование <input type='text' id='contragent_name_for_add'>";
						echo "Город <input type='text' id='contragent_city_for_add'>";
						echo "ИНН <input type='number' id='contragent_inn_fora_add'>";
					echo "</div>";
					echo "<div class='form_bottom_btn'>";
						echo "<button id='sumbit_form_add_contragent_btn'>Внести</button>";
						echo "<button id='close_form_add_contragent_btn'>Отмена</button>";
					echo "</div>";
				echo "</div>";		
			echo "</div>";
		echo "</div>";
	}
	public function GenerateFormAddProduct()
	{
		echo "<div class='add_product block_content hidden_elem'>";
			echo "<div class='form_container add_product_form'>";
				echo "<div class='form_name_head'>";
					echo "Внести новый товар";
				echo "</div>";
				echo "<div class='form_body_container'>";
					echo "<div class='form_body_item_in_container'>";
						echo "<div class='prod_kod_frm'>Код <input type='number' id='new_prod_id'></div>";
						echo "Наименование <textarea id='new_prod_name'></textarea>";
						echo "Единица измерения <select id='new_prod_measure'>";//</select> <input type='text' id='contragent_city_for_add'>";
							for ($i=0; $i < count($this->measures_arr); $i++) 
							{
								echo "<option value='".$this->measures_arr[$i]['id']."'>".$this->measures_arr[$i]['name']."</option>";
							}
						echo "</select>";
						echo "Категория <select id='new_prod_cat'>";
						echo "</select>";
						echo "Код по бухгалтерии (необязательно) <input type='number' id='new_prod_etc'>";
					echo "</div>";
					echo "<div class='form_bottom_btn'>";
						echo "<button id='sumbit_form_add_product_btn'>Внести</button>";
						echo "<button id='close_form_add_product_btn'>Отмена</button>";
					echo "</div>";
				echo "</div>";		
			echo "</div>";
		echo "</div>";
	}
	public function GenerateContragentSelectForm()
	{
		echo "<div class='select_contragent block_content hidden_elem'>";
		echo "<div class='form_container select_contragent_form'>
				<div class='form_name_head'>
					Выбор контрагента
				</div>
				<div class='search_line_in_form'>
					<input placeholder='Поиск...' type='text' id='contragent_search'>
					<button id='add_contragent_form'>Внести нового поставщика</button>
				</div>
				<div class='form_body_container'>
					<div class='contragent_list'>";
		echo "		</div>
				</div>
			<div class='form_bottom_btn'>
				<button id='select_contragent_btn' disabled='disabled' class='select_btn'>Выбрать</button>
				<button id='cancel_select_contragent_btn'>Отмена</button>
			</div>
		</div>";
		echo "</div>";
	}
	public function GenerateProductSelectForm()
	{
		echo "<div class='select_product block_content hidden_elem'>";
		echo "<div class='form_container select_product_form'>
				<div class='form_name_head'>
					Выбор товара
				</div>
				<div class='search_line_in_form'>";
					for($i=0; $i < count(array_keys($this->warehouses_separate_arr)); $i++) 
					{ 
						//echo array_keys($this->warehouses_separate_arr)[$i];
						echo "<select class='hidden_elem' id='cat_".array_keys($this->warehouses_separate_arr)[$i]."' onchange='GenerateProducttList()'>";
						echo "<option disabled selected hidden>Выберите категорию</option>";
						for ($y=0; $y < count($this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]]); $y++) 
						{ 
							echo "<option spec_cat='".$this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]][$y]['editable']."' value='".$this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]][$y]['category_id']."'>".$this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]][$y]['category_name']."</option>";
						}
						echo "</select>";
					}
		echo			"<input placeholder='Поиск...' type='text' id='product_search'>
					<button id='add_product_form'>Внести новый товар</button>
				</div>
				<div class='form_body_container'>
					<div class='product_list'>";
		echo "		</div>
				</div>
			<div class='form_price_n_quntity'>
				Количество <input class='select_product_quantity_inp' type='number' placeholder='Введите количество'>
				Цена за штуку (меру) <input class='select_product_price_inp' type='number' placeholder='Введите цену'> руб.
			</div>
			<div class='form_bottom_btn'>
				<button id='select_product_btn' disabled='disabled' class='select_btn'>Выбрать</button>
				<button id='cancel_select_product_btn'>Отмена</button>
			</div>
		</div>";
		echo "</div>";
	}
	public function GenerateFormProductIncome()
	{
		echo "<div class='contragent_info'>";
			echo "<div><button id='select_contragent_form'>ВЫБРАТЬ ПОСТАВЩИКА</button></div>";
			echo "<div>Название: <a class='contragent_name'></div></a>";
			echo "<div>Город: <a class='contragent_city'></a></div>";
			echo "<div>ИНН: <a class='contragent_inn'></a></div>";
		echo "</div>";
		echo "<div class='product_info'>";
			echo "<div class='warehouse_form'>Склад: ";
				echo "<select onchange='ToggleWarehouseArea()'>";
				$tmp_id = 0;
				$warehose_id_array = array();
				for ($i=0; $i < count($this->warehouses_arr); $i++) 
				{ 
					if ($tmp_id !== $this->warehouses_arr[$i]['warehouse_id']) 
					{
						$warehose_id_array[] = $this->warehouses_arr[$i]['warehouse_id'];
						$tmp_id = $this->warehouses_arr[$i]['warehouse_id'];
						echo "<option value='".$tmp_id."'>".$this->warehouses_arr[$i]['warehouse_name']."</option>";
					}
				}
				echo "<select>";
			echo "</div>";
			
			echo "<div class='warehouse_btn_form'><button id='select_product_form'>ДОБАВИТЬ ТОВАР</button></div>";
			echo "<div class='product_grid_container'>";

				for ($i=0; $i < count($warehose_id_array); $i++) 
				{ 
					if ($i == 0) 
					{
						echo "<table id='_".$warehose_id_array[$i]."'>";
					}
					else
					{
						echo "<table class='hidden_elem' id='_".$warehose_id_array[$i]."'>";
					}
							echo "<thead>";
								echo "<tr>";
									echo "<th>";
										echo "Наименование";
									echo "</th>";
									echo "<th>";
										echo "Кол-во";
									echo "</th>";
									echo "<th>";
										echo "Единица измерения";
									echo "</th>";
									echo "<th>";
										echo "Цена за штуку";
									echo "</th>";
									echo "<th>";
										echo "Категория";
									echo "</th>";
									echo "<th>";
										echo "Код по ИВЦ";
									echo "</th>";
									echo "<th>";
										echo "Код по бухгалтерии";
									echo "</th>";
									echo "<th>";
										//echo "Действие";
									echo "</th>";
								echo "</tr>";
							echo "</thead>";
							//
							echo "<tbody>";
							echo "</tbody>";
						echo "</table>";
					}
			echo "</div>";
			echo "<div class='income_btn_panel'>";
				echo "<button id='income_recored_btn'>ЗАПИСАТЬ ПРИХОД</button>";
			echo "</div>";
		echo "</div>";
	}
	public function CollectMeasures()
	{
		$DB = $this->DBConnect;
		$conn = $DB->GetConn();
		$QueryStr = "SELECT * FROM warehouses.measure order by id desc";
		$Query = $conn->prepare($QueryStr);
		$Query->execute();
		$this->measures_arr = $Query->fetchAll(PDO::FETCH_ASSOC);
	}
	public function ColletUserInfoForWarehouses()
	{
		

		$DB = $this->DBConnect;
		$conn = $DB->GetConn();
		
		if ($this->UserInfoArray[0]['idstatus'] > 2) 
		{
			// делаю выгрузку для админа
			$QueryStr = "SELECT WH.id AS warehouse_id, WH.name AS warehouse_name, CT.id AS category_id, CT.name AS category_name, CT.editable FROM warehouses.warehouses as WH
			LEFT JOIN warehouses.categories_in_warehouses AS CIW ON CIW.warehouse = WH.id
			LEFT JOIN warehouses.categories AS CT ON CT.id = CIW.category order by WH.id";
			$Query = $conn->prepare($QueryStr);
		}
		else
		{
			// выгружаем для конкретного юзера
			$QueryStr = "SELECT WH.id AS warehouse_id, WH.name AS warehouse_name, CT.id AS category_id, CT.name AS category_name, CT.editable FROM warehouses.warehouses_managers AS WM
			LEFT JOIN warehouses.warehouses AS WH ON WH.id = WM.warehouse
			LEFT JOIN warehouses.categories_in_warehouses AS CIW ON CIW.warehouse = WH.id
			LEFT JOIN warehouses.categories AS CT ON CT.id = CIW.category
			WHERE WM.manager = :usr order by WH.id";
			$Query = $conn->prepare($QueryStr);
			$Query->bindParam(':usr', $this->UserId, PDO::PARAM_STR); 
		}
		$Query->execute();
		$this->warehouses_arr = $Query->fetchAll(PDO::FETCH_ASSOC);

		$this->warehouses_separate_arr = [];

		foreach ($this->warehouses_arr as $elem) {
		    $ware_numb = $elem["warehouse_id"];
		    
		    if (array_key_exists($ware_numb, $this->warehouses_separate_arr)) {
		        $this->warehouses_separate_arr[$ware_numb][] = $elem;
		    } else {
		        $this->warehouses_separate_arr[$ware_numb] = [$elem];
		    }
		}

	}
	public function GenerateFormBalances()
	{
		echo "<div class='balances_info'>";
			echo "<div class='balances_warehouse_info'>Склад: ";
				echo "<select onchange='ToggleWarehouseCategory_Balances()'>";
				$tmp_id = 0;
				$warehose_id_array = array();
				for ($i=0; $i < count($this->warehouses_arr); $i++) 
				{ 
					if ($tmp_id !== $this->warehouses_arr[$i]['warehouse_id']) 
					{
						$warehose_id_array[] = $this->warehouses_arr[$i]['warehouse_id'];
						$tmp_id = $this->warehouses_arr[$i]['warehouse_id'];
						echo "<option value='".$tmp_id."'>".$this->warehouses_arr[$i]['warehouse_name']."</option>";
					}
				}
				echo "<select>";
				echo "<button onclick='LoadBalances()'>Обновить</button>";
			echo "</div>";
			echo "<div class='balances_product_info'>
					<div class='search_line_in_form'>";
						for($i=0; $i < count(array_keys($this->warehouses_separate_arr)); $i++) 
						{ 
							//echo array_keys($this->warehouses_separate_arr)[$i];
							echo "<select class='hidden_elem' id='cat_".array_keys($this->warehouses_separate_arr)[$i]."' onchange='LoadBalances()'>";
							echo "<option value='-1' selected>Все категории</option>";
							for ($y=0; $y < count($this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]]); $y++) 
							{
								echo "<option spec_cat='".$this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]][$y]['editable']."' value='".$this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]][$y]['category_id']."'>".$this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]][$y]['category_name']."</option>";
							}
							echo "</select>";
						}
			echo			"<input placeholder='Поиск...' type='text' id='product_search_balances'>
					</div>
					<div class='balances_body_container'>
						<div class='balances_list'>";
						//тут будут остатки
			echo "<table>";
				echo "<thead>";
					echo "<tr>";
						echo "<th>";
							echo "Наименование";
						echo "</th>";
						echo "<th>";
							echo "Остаток";
						echo "</th>";
						echo "<th>";
							echo "Единица измерения";
						echo "</th>";
						echo "<th>";
							echo "Категория";
						echo "</th>";
						echo "<th>";
							echo "Код по ИВЦ";
						echo "</th>";
						echo "<th>";
							echo "Код по бухгалтерии";
						echo "</th>";
					echo "</tr>";
				echo "</thead>";
				//
				echo "<tbody>";
				echo "</tbody>";
			echo "</table>";
			echo "		</div>
					</div>
			</div>";
		echo "</div>";
	}
	public function GenerateFormExpence()
	{
		echo "<div class='warehouse_form_expence'>Склад: ";
			echo "<select onchange='ToggleWarehouseArea_Expence()'>";
			$tmp_id = 0;
			$warehose_id_array = array();
			for ($i=0; $i < count($this->warehouses_arr); $i++) 
			{ 
				if ($tmp_id !== $this->warehouses_arr[$i]['warehouse_id']) 
				{
					$warehose_id_array[] = $this->warehouses_arr[$i]['warehouse_id'];
					$tmp_id = $this->warehouses_arr[$i]['warehouse_id'];
					echo "<option value='".$tmp_id."'>".$this->warehouses_arr[$i]['warehouse_name']."</option>";
				}
			}
			echo "<select>";
		echo "</div>";
		echo "<div class='expence_info'>";
			echo "Кто забирает товар: <input type='text' list='users_list'> ";
			$this->LoadUsersList();
			echo "Забирающего нет в базе данных <input type='checkbox'>";
		echo "</div>";
		echo "<div class='expence_composition'>";
			echo "<div class='warehouse_btn_form'><button id='select_product_form_expence'>ДОБАВИТЬ ТОВАР</button></div>";
			echo "<div class='product_grid_container_expence'>";

				for ($i=0; $i < count($warehose_id_array); $i++) 
				{ 
					if ($i == 0) 
					{
						echo "<table id='_".$warehose_id_array[$i]."'>";
					}
					else
					{
						echo "<table class='hidden_elem' id='_".$warehose_id_array[$i]."'>";
					}
							echo "<thead>";
								echo "<tr>";
									echo "<th>";
										echo "Наименование";
									echo "</th>";
									echo "<th>";
										echo "Количество";
									echo "</th>";
									echo "<th>";
										echo "Единица измерения";
									echo "</th>";
									echo "<th>";
										echo "Категория";
									echo "</th>";
									echo "<th>";
										echo "Код по ИВЦ";
									echo "</th>";
									echo "<th>";
										echo "Код по бухгалтерии";
									echo "</th>";
									echo "<th>";
										//echo "Действие";
									echo "</th>";
								echo "</tr>";
							echo "</thead>";
							//
							echo "<tbody>";
							echo "</tbody>";
						echo "</table>";
					}
			echo "</div>";
			echo "<div class='expence_btn_panel'>";
				echo "<button id='expence_recored_btn'>ЗАПИСАТЬ РАСХОД</button>";
			echo "</div>";
		echo "</div>";
	}
	public function LoadUsersList()
	{
		$DB = $this->DBConnect;
		$conn = $DB->GetConn();
		$QueryStr = "SELECT users.lastname || ' ' || users.firstname || ' ' || users.patronimic || ' - ' || DV.description AS name, users.iduser FROM users
					LEFT JOIN divisions AS DV ON DV.iddivision = users.division";
		$Query = $conn->prepare($QueryStr);
		$Query->execute();
		$users_list = $Query->fetchAll(PDO::FETCH_ASSOC);
		
		echo "<datalist id='users_list'>";
		for ($i=0; $i < count($users_list); $i++) 
		{ 
			echo "<option value='".$users_list[$i]['iduser']."'>".$users_list[$i]['name']."</option>";
		}
		echo "</datalist>";
	}
	public function GenerateProductSelectForm_Expence()
	{
		echo "<div class='select_product_expence block_content hidden_elem'>";
		echo "<div class='form_container select_product_form_expence'>
				<div class='form_name_head'>
					Выбор товара
				</div>
				<div class='search_line_in_form'>";
					for($i=0; $i < count(array_keys($this->warehouses_separate_arr)); $i++) 
					{ 
						//echo array_keys($this->warehouses_separate_arr)[$i];
						echo "<select class='hidden_elem' id='cat_".array_keys($this->warehouses_separate_arr)[$i]."' onchange='LoadBalancesForExpences()'>";
						echo "<option value='-1' selected>Все категории</option>";
						for ($y=0; $y < count($this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]]); $y++) 
						{ 
							echo "<option spec_cat='".$this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]][$y]['editable']."' value='".$this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]][$y]['category_id']."'>".$this->warehouses_separate_arr[array_keys($this->warehouses_separate_arr)[$i]][$y]['category_name']."</option>";
						}
						echo "</select>";
					}
		echo			"<input placeholder='Поиск...' type='text' id='product_search_expence'>
				</div>
				<div class='form_body_container'>
					<div class='product_list_expence'>";
		echo "<table>";
			echo "<thead>
					<tr>
						<th>
							Наименование
						</th>
						<th>
							Остаток
						</th>
						<th>
							Ед.изм
						</th>
						<th>
							Категория
						</th>
						<th>
							Код ИВЦ
						</th>
						<th>
							Код бухгалтерии
						</th>";
			echo "</thead>";
			echo "<tbody></tbody>";
		echo "</table>";
		echo "		</div>
				</div>
			<div class='form_price_n_quntity'>
				Количество <input class='select_product_quantity_expence' type='number' placeholder='Введите количество'>
			</div>
			<div class='form_bottom_btn'>
				<button id='select_product_btn_expence' disabled='disabled' class='select_btn'>Выбрать</button>
				<button id='cancel_select_product_btn_expence'>Отмена</button>
			</div>
		</div>";
		echo "</div>";
	}
	public function GenerateFormIncomeList()
	{
		/*$QueryStr = "SELECT DISTINCT INC.warehouse, WH.name FROM warehouses.income AS INC 
					LEFT JOIN warehouses.warehouses AS WH ON WH.id = INC.warehouse
					WHERE INC.recieved = :user";
		*/
		echo "<div class='IncomeList_info'>";
			echo "<div class='warehouses_incomelist'>Склад: ";
				echo "<select onchange=''>";
				$tmp_id = 0;
				$warehose_id_array = array();
				$warehouse_counter = 0;
				for ($i=0; $i < count($this->warehouses_arr); $i++) 
				{ 
					if ($tmp_id !== $this->warehouses_arr[$i]['warehouse_id']) 
					{
						$warehose_id_array[] = $this->warehouses_arr[$i]['warehouse_id'];
						$tmp_id = $this->warehouses_arr[$i]['warehouse_id'];
						echo "<option value='".$tmp_id."'>".$this->warehouses_arr[$i]['warehouse_name']."</option>";
						$warehouse_counter ++;
					}
				}
				if ($warehouse_counter > 1) 
				{
					echo "<option value='-1'>Все склады</option>";
				}
				echo "<select>";
				echo "<button onclick=''>Обновить</button>";
			echo "</div>";
			echo "<div class='IncomeList_info_container'>";
				// --test
				
				// --test
			echo "</div>";
		echo "</div>";
	}
	public function GenerateFormExpenceList()
	{
		echo "<div class='expenceList_info'>";
			echo "<div class='warehouses_expencelist'>Склад: ";
				echo "<select onchange=''>";
				$tmp_id = 0;
				$warehose_id_array = array();
				$warehouse_counter = 0;
				for ($i=0; $i < count($this->warehouses_arr); $i++) 
				{ 
					if ($tmp_id !== $this->warehouses_arr[$i]['warehouse_id']) 
					{
						$warehose_id_array[] = $this->warehouses_arr[$i]['warehouse_id'];
						$tmp_id = $this->warehouses_arr[$i]['warehouse_id'];
						echo "<option value='".$tmp_id."'>".$this->warehouses_arr[$i]['warehouse_name']."</option>";
						$warehouse_counter ++;
					}
				}
				if ($warehouse_counter > 1) 
				{
					echo "<option value='-1'>Все склады</option>";
				}
				echo "<select>";
				echo "<button onclick=''>Обновить</button>";
			echo "</div>";
			echo "<div class='expenceList_info_container'>";
				// --test
				
				// --test
			echo "</div>";
		echo "</div>";
	}
}
?>