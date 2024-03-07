document.addEventListener("DOMContentLoaded", function (){
	ToggleWarehouseCategory_Balances();
	document.querySelector('#product_search_balances').addEventListener("input", InputTimeDelay_Balances);
});
function ToggleWarehouseCategory_Balances()
{
	let category_in_form = document.querySelector('.balances_product_info').querySelectorAll('.search_line_in_form select');
	let wareouse_id = document.querySelector('.balances_warehouse_info select').value;
	for (var i = 0; i < category_in_form.length; i++) 
	{
		category_in_form[i].classList.add('hidden_elem');
	}
	document.querySelector('.balances_product_info').querySelector('.search_line_in_form select#cat_'+wareouse_id).classList.remove('hidden_elem');
	LoadBalances();
}
function LoadBalances(search_prod_txt = 0)
{
	let warehouse_id = document.querySelector('.balances_warehouse_info select').value;
	let cat_slelect = document.querySelector('.balances_product_info').querySelector('.search_line_in_form select#cat_'+warehouse_id)
	let cat_id = cat_slelect.options[cat_slelect.selectedIndex].value;
	let container = document.querySelector('.balances_list tbody');

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
						newRow.id = data[i]['product'];

						// Заполняем ячейки новой строки
						var prod_name_cell = newRow.insertCell();
						prod_name_cell.innerHTML = data[i]['name'];

						var prod_quantity_cell = newRow.insertCell();
						prod_quantity_cell.innerHTML = data[i]['remaining_quantity'];

						var prod_measure_cell = newRow.insertCell();
						prod_measure_cell.innerHTML = data[i]['measure'];

						var cat_name_cell = newRow.insertCell();
						cat_name_cell.innerHTML = data[i]['categori'];

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
function InputTimeDelay_Balances()
{	
	//document.querySelector('.ReferenceBook').style.background = 'gray';
	if (timerId) 
	{
		clearTimeout(timerIdBlock);
		clearTimeout(timerId);
		//timerId = setTimeout(FilterData,2000,celltype);
		timerIdBlock = setTimeout(create_loadbar,1500)
		timerId = setTimeout(SearchProducts_Balances,2000);
	}
	else
	{
		//timerId = setTimeout(FilterData,2000,celltype);
		timerIdBlock = setTimeout(create_loadbar,1500)
		timerId = setTimeout(SearchProducts_Balances,2000);
	}
}
function SearchProducts_Balances()
{
	let search_prod_txt = document.querySelector('#product_search_balances').value.trim();
	search_prod_txt = search_prod_txt.toUpperCase();
	LoadBalances(search_prod_txt);
	setTimeout(remove_loadbar,1000);
}