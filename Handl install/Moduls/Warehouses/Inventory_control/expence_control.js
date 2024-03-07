document.addEventListener("DOMContentLoaded", function (){
	//LoadBalances();
	document.querySelector('#cancel_select_product_btn_expence').addEventListener("click", ToggleSelectProductForm_Expence);
	document.querySelector('#select_product_form_expence').addEventListener("click", ToggleSelectProductForm_Expence);
	document.querySelector('#select_product_btn_expence').addEventListener("click", SelectProduct_Expence);
	document.querySelector('#expence_recored_btn').addEventListener("click", AddExpension);
	document.querySelector('#product_search_expence').addEventListener("input", InputTimeDelay_Expence);
});
function ToggleWarehouseArea_Expence()
{
	let warehouse_id = document.activeElement.value
	let containers = document.querySelectorAll('.product_grid_container_expence table');
	for (var i = 0; i < containers.length; i++) 
	{
		containers[i].classList.add('hidden_elem');
	}
	document.querySelector('.product_grid_container_expence table#_'+warehouse_id+'').classList.remove('hidden_elem');
}
function ToggleSelectProductForm_Expence()
{
	//ToggleBlockContentForm()
	let elem = document.querySelector('.select_product_expence');
	if (elem.classList.contains('hidden_elem')) 
	{
		elem.classList.remove('hidden_elem');
		let category_in_form = document.querySelector('.select_product_form_expence').querySelectorAll('.search_line_in_form select');
		let wareouse_id = document.querySelector('.warehouse_form_expence select').value;
		for (var i = 0; i < category_in_form.length; i++) 
		{
			category_in_form[i].classList.add('hidden_elem');
		}
		document.querySelector('.select_product_form_expence').querySelector('.search_line_in_form select#cat_'+wareouse_id).classList.remove('hidden_elem');
		//GenerateProducttList();
		LoadBalancesForExpences();
	}
	else
	{
		elem.classList.add('hidden_elem');
	}
	
}



function SetRowFocus_Expence()
{
	let elem = document.activeElement;
	let DisabledBtnMass = elem.parentElement.parentElement.parentElement.parentElement.parentElement.querySelectorAll('.select_btn');
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
/*function ToggleWarehouseCategory_Expences()
{
	let category_in_form = document.querySelector('.balances_product_info').querySelectorAll('.search_line_in_form select');
	let wareouse_id = document.querySelector('.balances_warehouse_info select').value;
	for (var i = 0; i < category_in_form.length; i++) 
	{
		category_in_form[i].classList.add('hidden_elem');
	}
	document.querySelector('.balances_product_info').querySelector('.search_line_in_form select#cat_'+wareouse_id).classList.remove('hidden_elem');
	LoadBalancesForExpences();
}
*/
function ClearSelectProductForm_Expence()
{
	document.querySelector('.select_product_form_expence').querySelector('.select_btn').setAttribute('disabled','disabled');
	document.querySelector('.select_product_quantity_expence').value = '';
}
function SelectProduct_Expence()
{
	let prod_quantity = document.querySelector(".select_product_quantity_expence").value; // введенное количество
	let prod_select = document.querySelector(".product_list_expence").querySelector('.focusElem');
	let quantity = prod_select.querySelectorAll('td')[1].innerText;
	let measure_type = prod_select.querySelectorAll('td')[2].id;
	let real_quantity = 0;
	let real_balance_quantity = 0;
	
	if (parseInt(measure_type) == 796) 
	{
		if (!Number.isInteger(parseFloat(prod_quantity))) 
		{
			CastomAlert(2,'Штуки не могут быть дробным значением!');
			return;
		}
		real_quantity = parseInt(prod_quantity);
		real_balance_quantity = parseInt(quantity);
	}
	else
	{
		real_quantity = parseFloat(prod_quantity);
		real_balance_quantity = parseFloat(quantity);
	}

	if (real_quantity <= 0) 
	{
		CastomAlert(2, 'Введите значение больше 0!');
		return;
	}
	else if(real_quantity > real_balance_quantity)
	{
		CastomAlert(2, 'Нельзя списать больше, чем есть на остатке!')
		return;
	}
	let ProductList = document.querySelector('.product_grid_container_expence table:not(.hidden_elem) tbody');
	//проверять, нет ли в списке расхода перерасхода по ид

	let Select_id = prod_select.id;

	if (ProductList.querySelector('[id="'+Select_id+'"]') !== null) 
	{
		CastomAlert(2, 'Товар "'+prod_select.querySelectorAll('td')[0].innerText+'" уже находится в списке!');
		return;
	}

	let copiedRow = prod_select.cloneNode(true);
	copiedRow.querySelectorAll('td')[1].innerText = real_quantity;
	copiedRow.classList.remove('focusElem');
	copiedRow.removeAttribute('tabindex');
	// Добавление ячейки в скопированную строку
	let deleteButton = document.createElement('button');
	deleteButton.addEventListener("click",DeleteProd);
	deleteButton.textContent = 'X';

	// Создание ячейки и добавление кнопки в ячейку
	let newCell = document.createElement('td');
	newCell.appendChild(deleteButton);

	// Вставка скопированной строки в другую таблицу
	copiedRow.appendChild(newCell);
	ProductList.appendChild(copiedRow);

	// Вставка скопированной строки в другую таблицу
	ProductList.appendChild(copiedRow);
	ToggleSelectProductForm_Expence();
}
function LoadBalancesForExpences(search_prod_txt = 0)
{
	let warehouse_id = document.querySelector('.warehouse_form_expence select').value;
	let cat_slelect = document.querySelector('.select_product_form_expence').querySelector('.search_line_in_form select#cat_'+warehouse_id)
	let cat_id = cat_slelect.options[cat_slelect.selectedIndex].value;
	//document.querySelector('.select_product_form_expence').querySelector('.select_btn').setAttribute('disabled','disabled');
	ClearSelectProductForm_Expence();

	let container = document.querySelector('.product_list_expence tbody');
	while (container.firstChild) 
	{
    	container.removeChild(container.firstChild);
	}

	DataArr = {Access:1, cat_id:cat_id, warehouse_id:warehouse_id, search_prod_txt:search_prod_txt};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/GetBalances.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 1:
	    			CastomAlert(1,'В данной категории остатков нет.');
	    			break;
	    		case 2:
	    			CastomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
	    			break;
	    		default:
	    			

		    		

		    		for (var i = 0; i < data.length; i++) 
	    			{
	    				var newRow = container.insertRow();
	    				newRow.addEventListener("click", SetRowFocus_Expence);
						newRow.id = data[i]['product'];
						newRow.setAttribute('tabindex','1');

						// Заполняем ячейки новой строки
						var prod_name_cell = newRow.insertCell();
						prod_name_cell.innerHTML = data[i]['name'];

						var prod_quantity_cell = newRow.insertCell();
						prod_quantity_cell.innerHTML = data[i]['remaining_quantity'];

						var prod_measure_cell = newRow.insertCell();
						prod_measure_cell.innerHTML = data[i]['measure'];
						prod_measure_cell.id = data[i]['measure_id'];

						var cat_name_cell = newRow.insertCell();
						cat_name_cell.innerHTML = data[i]['categori'];
						cat_name_cell.id = data[i]['category_id'];

						var prod_designation_cell = newRow.insertCell();
						prod_designation_cell.innerText = data[i]['designation'];

						var prod_buh_kode_cell = newRow.insertCell();
						prod_buh_kode_cell.innerHTML = data[i]['buh_kode'];
	    			}
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
function AddExpension()
{
	let warehouse_id = document.querySelector('.warehouse_form_expence select').value;
	let received = 0;
	let prod_list = document.querySelectorAll('.product_grid_container_expence table:not(.hidden_elem) tbody tr');

	if (document.querySelector('.expence_info input[type="text"]').value == '' && document.querySelector('.expence_info input[type="checkbox"]').checked == false) 
	{
		CastomAlert(2,'Выберите, кто принимает товар!');
		return;
	}

	if (document.querySelector('.expence_info input[type="checkbox"]').checked) 
	{
		received = -1;
	}
	else
	{
		received = document.querySelector('.expence_info input[type="text"]').value;
	}

	if (prod_list.length < 1) 
	{
		CastomAlert(2,'Добавьте хотя бы один товар!');
		return;
	}

	var form_data = new FormData(); // объект для передачи списка товаров
	for (var i = 0; i < prod_list.length; i++) 
	{
		form_data.append('prod_id'+i,prod_list[i].id);
		if (prod_list[i].querySelectorAll('td')[2].id == 796) 
		{
			if (!Number.isInteger(parseFloat(prod_list[i].querySelectorAll('td')[1].innerText))) 
			{
				CastomAlert(2,'Штуки не могут быть дробным значением!');
				return;
			}
		}
		if (Math.sign(parseFloat(prod_list[i].querySelectorAll('td')[1].innerText)) !== 1) 
		{
			CastomAlert(2,'Введено недопустимое значение количество!');
			return;
		}
		form_data.append('quantity'+i,prod_list[i].querySelectorAll('td')[1].innerText);
	}
	form_data.append('warehouse',warehouse_id);
	form_data.append('received',received);
	form_data.append('Access',1);	
	if (received == -1) 
	{
		CastomAlert(1,'Сообщите на ИВЦ состав расхода и полное ФИО, отдел и должность принявшего товар сотрудника.');
	}
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/AddExpence.php",
	    dataType: 'json',
	    cache: false,
	    contentType: false,
	    processData: false,
	    data: form_data,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case -1:
	    			CastomAlert(2,'КОЛИЧЕСТВО В РАСХОДЕ ПРЕВЫШАЕТ ОСТАТОК!');
	    			break;
	    		case 1:
	    			let prod_list_container = document.querySelector('.product_grid_container_expence table:not(.hidden_elem) tbody');
	    			while (prod_list_container.firstChild) 
					{
				    	prod_list_container.removeChild(prod_list_container.firstChild);
					}

					document.querySelector('.expence_info input[type="text"]').value = '';
					document.querySelector('.expence_info input[type="checkbox"]').checked = false;
	    			setTimeout(CastomAlert,3000,0,'Расход зафиксирован!')// CastomAlert(0,'Расход зафиксирован!');
	    			LoadBalances();
	    			//GenerateProducttList();
	    			break;
	    		default:
	    			CastomAlert(2,data);
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
function InputTimeDelay_Expence()
{	
	//document.querySelector('.ReferenceBook').style.background = 'gray';
	if (timerId) 
	{
		clearTimeout(timerIdBlock);
		clearTimeout(timerId);
		//timerId = setTimeout(FilterData,2000,celltype);
		timerIdBlock = setTimeout(create_loadbar,1500)
		timerId = setTimeout(SearchProducts_Expence,2000);
	}
	else
	{
		//timerId = setTimeout(FilterData,2000,celltype);
		timerIdBlock = setTimeout(create_loadbar,1500)
		timerId = setTimeout(SearchProducts_Expence,2000);
	}
}
function SearchProducts_Expence()
{
	let search_prod_txt = document.querySelector('#product_search_expence').value.trim();
	search_prod_txt = search_prod_txt.toUpperCase();
	LoadBalancesForExpences(search_prod_txt);
	setTimeout(remove_loadbar,1000);
}