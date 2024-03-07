var timerId;
var timerIdBlock;
document.addEventListener("DOMContentLoaded", function (){
	ToggleMainMenu();

	let tablist = document.querySelector('.menu').querySelectorAll('button');
	for (var i = 0; i < tablist.length; i++) 
	{
		tablist[i].addEventListener("click", ToggleTab);
	}
	GenerateContragentList();
	document.querySelector('#close_form_add_contragent_btn').addEventListener("click", ToggleAddContragentForm);
	document.querySelector('#add_contragent_form').addEventListener("click", ToggleAddContragentForm);

	document.querySelector('#cancel_select_contragent_btn').addEventListener("click", ToggleSelectContragentForm);
	document.querySelector('#select_contragent_form').addEventListener("click", ToggleSelectContragentForm);

	document.querySelector('#sumbit_form_add_contragent_btn').addEventListener("click", AddNewContagent);
	document.querySelector('#contragent_search').addEventListener("input", ContragentSearch);

	document.querySelector('#select_contragent_btn').addEventListener("click", SelectContragent);
	
	document.querySelector('#cancel_select_product_btn').addEventListener("click", ToggleSelectProductForm);
	document.querySelector('#select_product_form').addEventListener("click", ToggleSelectProductForm);
	//document.querySelector('#select_product_form').addEventListener("click", GenerateProducttList);

	document.querySelector('#add_product_form').addEventListener("click", ToggleAddProductForm);
	document.querySelector('#close_form_add_product_btn').addEventListener("click", ToggleAddProductForm);

	document.querySelector('#new_prod_cat').addEventListener("change", CheckCatProd);
	document.querySelector('#sumbit_form_add_product_btn').addEventListener("click", AddNewProduct);
	//document.querySelector('#sumbit_form_add_product_btn').addEventListener("click", GenerateProducttList);

	document.querySelector('#product_search').addEventListener("input", InputTimeDelay);

	document.querySelector('#select_product_btn').addEventListener("click", AddProductIncomeTable);
	document.querySelector('#income_recored_btn').addEventListener("click", AddIncome);
});
function InputTimeDelay()
{	
	//document.querySelector('.ReferenceBook').style.background = 'gray';
	if (timerId) 
	{
		clearTimeout(timerIdBlock);
		clearTimeout(timerId);
		//timerId = setTimeout(FilterData,2000,celltype);
		timerIdBlock = setTimeout(create_loadbar,1500)
		timerId = setTimeout(SearchProducts,2000);
	}
	else
	{
		//timerId = setTimeout(FilterData,2000,celltype);
		timerIdBlock = setTimeout(create_loadbar,1500)
		timerId = setTimeout(SearchProducts,2000);
	}
}
function SearchProducts()
{
	let search_prod_txt = document.querySelector('#product_search').value.trim();
	search_prod_txt = search_prod_txt.toUpperCase();
	GenerateProducttList(search_prod_txt);
	setTimeout(remove_loadbar,1000);
}
function ToggleTab()
{
	let OldTab = document.querySelector('.menu').querySelector('.activ_tab_indicator');
	let ActivTab = document.activeElement;
	if (OldTab !== null) 
	{
		OldTab.classList.remove('activ_tab_indicator');
		document.querySelector('.'+OldTab.id).classList.add('hidden_elem');
		ActivTab.classList.add('activ_tab_indicator');
		document.querySelector('.'+ActivTab.id).classList.remove('hidden_elem');
	}
	else
	{
		ActivTab.classList.add('activ_tab_indicator');
		document.querySelector('.'+ActivTab.id).classList.remove('hidden_elem');
	}
}
function ToggleBlockContentForm()
{
	let elem = document.querySelector('.block_content');
	if (elem.classList.contains('hidden_elem')) 
	{
		elem.classList.remove('hidden_elem');
	}
	else
	{
		elem.classList.add('hidden_elem');
	}

}
function ToggleAddContragentForm()
{
	//ToggleBlockContentForm()
	let elem = document.querySelector('.add_contragent');
	if (elem.classList.contains('hidden_elem')) 
	{
		elem.classList.remove('hidden_elem');
	}
	else
	{
		elem.classList.add('hidden_elem');
	}
}
function CheckCatProd()
{
	let add_prod_selector = document.querySelector('#new_prod_cat');
	let idx = document.querySelector('#new_prod_cat').selectedIndex;
	if (document.querySelector('#new_prod_cat')[idx].getAttribute('spec_cat') == 1) 
	{
		document.querySelector('.prod_kod_frm').classList.add('hidden_elem');
	}
	else
	{
		document.querySelector('.prod_kod_frm').classList.remove('hidden_elem');
	}
}
function ToggleAddProductForm()
{
	//ToggleBlockContentForm()
	let elem = document.querySelector('.add_product');
	if (elem.classList.contains('hidden_elem')) 
	{
		elem.classList.remove('hidden_elem');
		let category_for_warehouse = document.querySelector('.select_product_form').querySelector('.search_line_in_form select:not(.hidden_elem)');
		let add_prod_selector = document.querySelector('#new_prod_cat');
		if (add_prod_selector.length > 0) 
		{
			while (add_prod_selector.length > 0) 
			{
				add_prod_selector.remove(0);
			}
		}
		for (var i = 1; i < category_for_warehouse.length; i++) 
		{
			if (category_for_warehouse[i].getAttribute('spec_cat') == 0) 
			{

			}
			else
			{
				var option = category_for_warehouse[i];
				var newOption = document.createElement('option');
				newOption.value = option.value;
				newOption.text = option.text;
				newOption.setAttribute("spec_cat", category_for_warehouse[i].getAttribute('spec_cat'));
				add_prod_selector.add(newOption);
			}
		}
		CheckCatProd();
	}
	else
	{
		elem.classList.add('hidden_elem');
	}
}
function ToggleSelectContragentForm()
{
	//ToggleBlockContentForm()
	let elem = document.querySelector('.select_contragent');
	if (elem.classList.contains('hidden_elem')) 
	{
		elem.classList.remove('hidden_elem');
	}
	else
	{
		elem.classList.add('hidden_elem');
	}
}
function ToggleSelectProductForm()
{
	//ToggleBlockContentForm()
	let elem = document.querySelector('.select_product');
	if (elem.classList.contains('hidden_elem')) 
	{
		elem.classList.remove('hidden_elem');
		let category_in_form = document.querySelector('.select_product_form').querySelectorAll('.search_line_in_form select');
		let wareouse_id = document.querySelector('.warehouse_form select').value;
		for (var i = 0; i < category_in_form.length; i++) 
		{
			category_in_form[i].classList.add('hidden_elem');
		}
		document.querySelector('.select_product_form').querySelector('.search_line_in_form select#cat_'+wareouse_id).classList.remove('hidden_elem');
		GenerateProducttList();
	}
	else
	{
		elem.classList.add('hidden_elem');
	}
	
}
function ClearAddContragentForm()
{
	document.querySelector('#contragent_name_for_add').value = '';
	document.querySelector('#contragent_city_for_add').value = '';
	document.querySelector('#contragent_inn_fora_add').value = '';	
}
function SetRowFocus()
{ //нормальный способ записывать фокус, по средствам назначения класса элементу. нет привязки к области и лишних переменных
	let elem = document.activeElement;
	let DisabledBtnMass = elem.parentElement.parentElement.parentElement.querySelectorAll('.select_btn');
	let oldelem = elem.parentElement.querySelector('.focusElem');
	if (oldelem && (oldelem !== elem)) 
	{
		oldelem.classList.remove('focusElem');
	}

	if (elem.classList.contains('focusElem'))
	{
		for (var i = 0; i < DisabledBtnMass.length; i++) 
		{
			DisabledBtnMass[i].setAttribute('disabled','disabled');
		}
		elem.classList.remove('focusElem');
	}
	else
	{
		for (var i = 0; i < DisabledBtnMass.length; i++) 
		{
			DisabledBtnMass[i].removeAttribute('disabled');
		}
		elem.classList.add('focusElem');	
	}
}
function AddNewContagent()
{
	let name = document.querySelector('#contragent_name_for_add').value;
	let city = document.querySelector('#contragent_city_for_add').value;
	let inn = document.querySelector('#contragent_inn_fora_add').value;
	if (name.length <= 2 || city.lengt <= 2 || inn.length <= 9) 
	{
		CastomAlert(2,'Зполните корректно все поля!');
		return;
	}
	DataArr = {Access:1,name:name,city:city,inn:inn};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/AddNewContragent.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			ClearAddContragentForm();
	    			ToggleAddContragentForm();
	    			GenerateContragentList();
	    			CastomAlert(0,'Контрагент был успешно добавлен');
	    			break;
	    		case 1:
	    			CastomAlert(1,'Контрагент не был добавлен, такой ИНН уже есть в базе данных');
	    			break;
	    		case 2:
	    			CastomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
	    			break;
	    	}
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	        }	
	    }
	});
}
function AddNewProduct()
{
	let cat_type = document.querySelector('.prod_kod_frm').classList.contains('hidden_elem');

	let kod = document.querySelector('#new_prod_id').value;
	let name = document.querySelector('#new_prod_name').value;
	let measure = document.querySelector('#new_prod_measure').value;
	let cat = document.querySelector('#new_prod_cat').value;
	let buh = document.querySelector('#new_prod_etc').value;
	if (!cat_type && kod.length <= 6) 
	{
		CastomAlert(2,'Введите корректный код! Обычно это код от 7 символов для материалов и 16 для комплектующих.');
		return;
	}
	if (name.length <= 2) 
	{
		CastomAlert(2,'Наименование должно быть длиннее, чем 2 символа!');
		return;
	}
	DataArr = {Access:1,kod:kod,measure:measure,cat:cat,buh:buh,cat_type:cat_type,name:name};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/AddNewProduct.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			CastomAlert(0,'Товар был успешно добавлен');
	    			ClearAddProductForm();
	    			SearchProducts();
	    			ToggleAddProductForm();
	    			break;
	    		case 1:
	    			CastomAlert(1,'Товар с таким кодом уже есть в базе данных!');
	    			break;
	    		case 2:
	    			CastomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
	    			break;
	    	}
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	        }	
	    }
	});
}
function AddProductIncomeTable()
{
	let prod_quantity = document.querySelector(".select_product_quantity_inp").value; // введенное количество
	let prod_price = document.querySelector(".select_product_price_inp").value; // введенная цена
	let prod_select = document.querySelector(".select_product_form").querySelector('.focusElem');

	if (prod_quantity <= 0 || prod_quantity.length < 1) 
	{
		CastomAlert(2,'Укажите количество!');
		return;
	}

	if (prod_price <= 0 || prod_price.length < 1) 
	{
		CastomAlert(2,'Укажите цену!');
		return;
	}

	const prod_id = prod_select.id; // ВСЕГДА 
	let prod_designation = '';
	if (prod_select.querySelector('.prod_designation') !== null) 
	{
		prod_designation = prod_select.querySelector('.prod_designation').innerText;
	}

	let prod_measure_id = 0; // ид единицы измерения
	let prod_measure = ''; // название единицы измерения
	if (prod_select.querySelector('.prod_measure') !== null) // ЕСЛИ НЕ МАТЕРИАЛ, ТО ВСЕГДА ШТУКИ 
	{
		prod_measure_id = prod_select.querySelector('.prod_measure').id.substr(4); // ид единицы измерения
		prod_measure = prod_select.querySelector('.prod_measure').innerText; // название единицы измерения
	}
	else
	{
		prod_measure_id = 796; // ид единицы измерения
		prod_measure = 'Шт'; // название единицы измерения
	}

	let prod_buh_kode = '';
	if (prod_select.querySelector('.prod_buh_kode') !== null)
	{
		prod_buh_kode = prod_select.querySelector('.prod_buh_kode').innerText;
	}

	const prod_name = prod_select.querySelector('.prod_name').innerText; // ВСЕГДА

	const category = document.querySelector('.select_product_form').querySelector('.search_line_in_form select:not(.hidden_elem)');
	const cat_id = category[category.selectedIndex].value; //ид категории товара
	const cat_name = category[category.selectedIndex].innerText;
	const warehouse = document.querySelector('.product_grid_container').querySelector('table:not(.hidden_elem) tbody'); // таблица для выбранного склада

	// Создаем новую строку
	var newRow = warehouse.insertRow();
	newRow.id = prod_id;

	// Заполняем ячейки новой строки
	var prod_name_cell = newRow.insertCell();
	prod_name_cell.innerHTML = prod_name;

	var prod_quantity_cell = newRow.insertCell();
	var prod_quantity_input = document.createElement('input')
	prod_quantity_input.type = 'number';
	prod_quantity_input.value = prod_quantity;
	prod_quantity_cell.appendChild(prod_quantity_input);
	//prod_quantity_cell.setAttribute('contenteditable','true');
	//prod_quantity_cell.innerHTML = prod_quantity;

	var prod_measure_cell = newRow.insertCell();
	prod_measure_cell.innerHTML = prod_measure;
	prod_measure_cell.id = prod_measure_id;

	var prod_price_cell = newRow.insertCell();
	var prod_price_input = document.createElement('input')
	prod_price_input.type = 'number';
	prod_price_input.value = prod_price;
	prod_price_cell.appendChild(prod_price_input);
	//prod_price_cell.setAttribute('contenteditable','true');
	//prod_price_cell.innerHTML = prod_price;

	var cat_name_cell = newRow.insertCell();
	cat_name_cell.innerHTML = cat_name;
	cat_name_cell.id = cat_id;

	var prod_designation_cell = newRow.insertCell();
	prod_designation_cell.innerText = prod_designation;

	var prod_buh_kode_cell = newRow.insertCell();
	prod_buh_kode_cell.innerHTML = prod_buh_kode;

	var delete_btn_cell = newRow.insertCell();
	delete_btn_cell.innerHTML = "<button class='delete_btn' onclick='DeleteProd()'>X</button>";
	//document.querySelector(".select_product_form").querySelector('.focusElem').click();
	ClearSelectProductForm();
	ToggleSelectProductForm();
	//ClearSelectProductForm();
}
function ClearSelectProductForm()
{
	document.querySelector(".select_product_quantity_inp").value = ''; // введенное количество
	document.querySelector(".select_product_price_inp").value = ''; // введенная цена
	//document.querySelector(".select_product_form").querySelector('.focusElem').click();
	//console.log(document.querySelector(".select_product_form").querySelector('.focusElem'));
	//let search_prod_txt = document.querySelector('#product_search').value.trim();
}
function DeleteProd()
{
	document.activeElement.parentElement.parentElement.remove();
}
function CollectNIncomeProduct()
{
	const warehouse = document.querySelector('.product_grid_container').querySelector('table:not(.hidden_elem) tbody'); // таблица для выбранного склада

	//собрать всё, что есть в таблице и передать на сервер.
}
function AddIncome()
{
	let inn = '';
	let wareouse_id = '';
	let warehouse = ''; // таблица прихода
	var form_data = new FormData(); // объект для передачи списка товаров
	if (document.querySelector('.contragent_inn').id == null || document.querySelector('.contragent_inn').id == '') 
	{
		CastomAlert(2,'Выберите контрагента!');
		return;
	}
	else
	{
		inn = document.querySelector('.contragent_inn').id;
	}
	if (document.querySelector('.warehouse_form select').value == '') 
	{
		CastomAlert(2,'Выберите склад!');
		return;
	}
	else
	{
		wareouse_id = document.querySelector('.warehouse_form select').value;
	}
	if (document.querySelector('.product_grid_container').querySelector('table:not(.hidden_elem) tbody') == null) 
	{
		CastomAlert(2,'Возникла ошибка формирования таблицы прихода. Пожалуйста, перезагрузите страницу.');
		return;
	}
	else
	{
		warehouse = document.querySelector('.product_grid_container').querySelector('table:not(.hidden_elem) tbody');
	}
	if (warehouse.querySelectorAll('tr').length <= 0) 
	{
		CastomAlert(2,'Выберите хотя бы один товар!');
		return;
	}
	else
	{
		for (var i = 0; i < warehouse.querySelectorAll('tr').length; i++) 
		{
			form_data.append('id_'+i,warehouse.querySelectorAll('tr')[i].id);
			if (warehouse.querySelectorAll('tr')[i].id == null || warehouse.querySelectorAll('tr')[i].id == 'null') 
			{
				form_data.append('name_'+i,warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[0].innerText);
			}
			else
			{
				form_data.append('name_'+i,null);
			}
			//если нет ид, то нужно передать имя для внесения в нашу базу данных, иначе ничего не передавать!
			if (warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[2].id == 796) 
			{
				if (!Number.isInteger(parseFloat(warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[1].querySelector('input').value))) 
				{
					CastomAlert(2,'Штуки не могут быть дробным значением!');
					return;
				}
			}
			if (Math.sign(parseFloat(warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[1].querySelector('input').value)) !== 1) 
			{
				CastomAlert(2,'Введено недопустимое значение количества!');
				return;
			}
			if (parseFloat(warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[1].querySelector('input').value) > 1000000) 
			{
				CastomAlert(2,'Количество не может превышать 1 млн!');
				return;
			}
			if (parseFloat(warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[1].querySelector('input').value) <= 0) 
			{
				CastomAlert(2,'Количество не может быть меньше или равно нулю!');
				return;
			}
			form_data.append('quantity_'+i,warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[1].querySelector('input').value);
			form_data.append('measure_'+i,warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[2].id);
			/*if (warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[3].querySelector('input').value) 
			{
				// проверка на соответствие паттерну хn.x2
			}*/
			if (Math.sign(parseFloat(warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[3].querySelector('input').value)) !== 1) 
			{
				CastomAlert(2,'Введено недопустимое значение цены!');
				return;
			}
			form_data.append('price_'+i,warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[3].querySelector('input').value);
			form_data.append('category_'+i,warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[4].id);
			form_data.append('designation_'+i,warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[5].innerText);
			form_data.append('buh_kode_'+i,warehouse.querySelectorAll('tr')[i].querySelectorAll('td')[6].innerText);
		}
	}
	form_data.append('wareouse_id',wareouse_id);
	form_data.append('contragent_id',inn);
	form_data.append('Access',1);
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/AddNewIncome.php",
	    dataType: 'json',
	    cache: false,
	    contentType: false,
	    processData: false,
	    data: form_data,
	    success: function(data)
	    {
	    	if (data == 0) 
	    	{
	    		CastomAlert(0,'Приход успешно зафиксирован! Форма очищена.');
	    		document.querySelector('.contragent_info a.contragent_name').innerText = '';
				document.querySelector('.contragent_info a.contragent_city').innerText = '';
				document.querySelector('.contragent_info a.contragent_inn').innerText = '';
				document.querySelector('.contragent_info a.contragent_inn').id = '';
				while (warehouse.firstChild) 
				{
			    	warehouse.removeChild(warehouse.firstChild);
				}
				/*for (var i = 0; i <= warehouse.querySelectorAll('tr').length; i++) 
				{
					warehouse.querySelectorAll('tr')[0].remove();
				}
				*/
				LoadBalances();
				LoadBalancesForExpences();
	    	}
	    	else
	    	{
	    		if (data == 1) 
	    		{
	    			CastomAlert(2,'ВВЕДЕННЫЕ ДАННЫЕ НЕ КОРРЕКТНЫ!');
	    		}
	    		else
	    		{
	    			CastomAlert(2,'Ошибка на сервере. Попробуйте ещё раз.');
	    		}
	    	}
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	        }	
	    }
	});
}
function ClearAddProductForm()
{
	document.querySelector('#new_prod_id').value = '';
	document.querySelector('#new_prod_name').value = '';
	document.querySelector('#new_prod_etc').value = '';
}
function ToggleWarehouseArea()
{
	let warehouse_id = document.activeElement.value
	let containers = document.querySelectorAll('.product_grid_container table');
	for (var i = 0; i < containers.length; i++) 
	{
		containers[i].classList.add('hidden_elem');
	}
	document.querySelector('.product_grid_container table#_'+warehouse_id+'').classList.remove('hidden_elem');
}
function SelectContragent()
{
	let focus_contragent = document.querySelector('.contragent_list div.focusElem');
	document.querySelector('.contragent_info a.contragent_name').innerText = focus_contragent.querySelector('a.cntr_name').innerText;
	document.querySelector('.contragent_info a.contragent_city').innerText = focus_contragent.querySelector('a.cntr_city').innerText;
	document.querySelector('.contragent_info a.contragent_inn').innerText = focus_contragent.querySelector('a.cntr_inn').innerText;
	document.querySelector('.contragent_info a.contragent_inn').id = focus_contragent.id;
	ToggleSelectContragentForm();
}
function GenerateProducttList(search_prod_txt = 0) 
{
	const elem = document.querySelector('.select_product').querySelector('.product_list');
	if (elem.querySelectorAll('.contragent_items').length != null) 
	{
		document.querySelector('.select_product').querySelector('.select_btn').setAttribute('disabled','disabled');
		ClearSelectProductForm();
		while (elem.firstChild) 
		{
	    	elem.removeChild(elem.firstChild);
		}
	}
	const category = document.querySelector('.select_product_form').querySelector('.search_line_in_form select:not(.hidden_elem)');
	
	if (category.selectedIndex == 0) 
	{
		CastomAlert(1,'Выберите категорию.');
		return 0;
	}
	const cat_id = category[category.selectedIndex].value;
	
	DataArr = {Access:1,cat:cat_id,search_prod_txt:search_prod_txt};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/GetProductList.php",
	    dataType: 'json',
	    //data: JSON.stringify(DataArr),
	    data: DataArr,
	    //contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
	    success: function(data)
	    {
	    	if (data !=1 && data !=2) 
	    	{
	    		let div;
	    		let linkdesignation;
	    		let linkbuh_kode;
	    		let linkname;
	    		let linkmes;

	    		for (var i = 0; i < data.length; i++) 
	    		{
	    			div = document.createElement('div');
	    		
					div.className = 'product_items';
					div.tabIndex = 0;

					div.id = data[i]['id'];

					if (data[i]['designation'] != null) 
					{
						linkdesignation = document.createElement('a');
						linkdesignation.className = 'prod_designation';
						linkdesignation.textContent = data[i]['designation'];

						//div.appendChild(document.createTextNode('Обозначение '));
						div.appendChild(linkdesignation);
						div.appendChild(document.createTextNode(', '));
					}

					

					linkname = document.createElement('a');
					linkname.className = 'prod_name';
					linkname.textContent = data[i]['naim'];

					
					
					div.appendChild(linkname);
					if (data[i]['mes']) 
					{
						linkmes = document.createElement('a');
						linkmes.className = 'prod_measure';
						linkmes.textContent = data[i]['mes'];
						linkmes.id = 'mes_'+data[i]['kei'];
						div.appendChild(document.createTextNode(', '));
						div.appendChild(linkmes);
					}

					if (data[i]['buh_kode'] != null) 
					{
						linkbuh_kode = document.createElement('a');
						linkbuh_kode.className = 'prod_buh_kode';
						linkbuh_kode.textContent = data[i]['buh_kode'];

						div.appendChild(document.createTextNode(', Код по бухгалтерии '));
						div.appendChild(linkbuh_kode);
						//div.appendChild(document.createTextNode(', '));
					}

					elem.appendChild(div);
	    		}

	    		let ContragentList = document.querySelectorAll('.product_items');
				for (var i = 0; i < ContragentList.length; i++)
				{
					ContragentList[i].addEventListener("click", SetRowFocus);
				}
	    	}
	    	else
	    	{
	    		switch(data)
		    	{
		    		case 1:
		    			CastomAlert(1,'Список товаров этой категории пуст.');
		    			break;
		    		case 2:
		    			CastomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
		    			break;
		    	}
	    	}
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	        }	
	    }
	});
}
function GenerateContragentList() 
{
	const elem = document.querySelector('.select_contragent').querySelector('.contragent_list');
	if (elem.querySelectorAll('.contragent_items').length != null) 
	{
		while (elem.firstChild) 
		{
	    	elem.removeChild(elem.firstChild);
		}
	}
	DataArr = {Access:1};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/GetContragentList.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	if (data !=1 && data !=2) 
	    	{
	    		let div;
	    		let linkName;
	    		let linkCity;
	    		let linkInn;

	    		for (var i = 0; i < data.length; i++) 
	    		{
	    			div = document.createElement('div');
	    		
					div.className = 'contragent_items';
					div.tabIndex = 0;

					div.id = data[i]['id'];

					linkName = document.createElement('a');
					linkName.className = 'cntr_name';
					linkName.textContent = data[i]['name'];

					linkCity = document.createElement('a');
					linkCity.className = 'cntr_city';
					linkCity.textContent = data[i]['city'];

					linkInn = document.createElement('a');
					linkInn.className = 'cntr_inn';
					linkInn.textContent = data[i]['inn'];

					div.appendChild(linkName);
					div.appendChild(document.createTextNode(', гор. '));
					div.appendChild(linkCity);
					div.appendChild(document.createTextNode(', ИНН '));
					div.appendChild(linkInn);

					elem.appendChild(div);
	    		}

	    		let ContragentList = document.querySelectorAll('.contragent_items');
				for (var i = 0; i < ContragentList.length; i++)
				{
					ContragentList[i].addEventListener("click", SetRowFocus);
				}
	    	}
	    	else
	    	{
	    		switch(data)
		    	{
		    		case 1:
		    			CastomAlert(1,'Список контрагентов пуст.');
		    			break;
		    		case 2:
		    			CastomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
		    			break;
		    	}
	    	}
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	        }	
	    }
	});
}
function ContragentSearch()
{
	let List = document.querySelectorAll('.contragent_items');
	let text = document.querySelector('#contragent_search').value;
	if (text.length > 0 && text.length !== null) 
	{
		for (let i = 0; i < List.length; i++) 
		{
		    if (List[i].innerHTML.toLowerCase().indexOf(text.toLowerCase()) < 0 ) 
		    {
		    	if (List[i].classList.contains('focusElem')) 
		    	{

		    	}
		    	else
		    	{
		    		List[i].classList.add('hidden_elem');
		    	}
		    }
		    else
		    {
		    	List[i].classList.remove('hidden_elem');
		    }

		}
	}
	else
	{
		for (let i = 0; i < List.length; i++) 
		{
		    List[i].classList.remove('hidden_elem');
		}
	}
		
}
function CastomAlert(type,text)
{
	const divElement = document.createElement('div');

	// Добавляем текст внутрь элемента
	divElement.textContent = text;

	// Добавляем класс стиля
	divElement.classList.add('castom_alert'); // Замените 'стиль' на имя нужного класса
	switch (type)
	{
		case 0:
			divElement.classList.add('positive_alert');
			break;
		case 1:
			divElement.classList.add('warning_alert');
			break;
		case 2:
			divElement.classList.add('negative_alert');
			break;
	}
	// Вставляем элемент в тело (body) документа
	document.body.appendChild(divElement);

	// Удаляем элемент через 10 секунд
	setTimeout(() => {
	  divElement.remove();
	}, 3000); // 10000 миллисекунд = 10 секунд
}