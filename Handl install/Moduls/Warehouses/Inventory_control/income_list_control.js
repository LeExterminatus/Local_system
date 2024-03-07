document.addEventListener("DOMContentLoaded", function (){
	GetIncomeList();
	document.querySelector('.warehouses_incomelist select').addEventListener("change", GetIncomeList);
	document.querySelector('.warehouses_incomelist button').addEventListener("click", GetIncomeList);
});
function GetCompositionIncome()
{
	let container = document.activeElement.parentElement.parentElement;
	let income_id = container.id;
	const elements = container.querySelectorAll(':scope > *');
// Проходимся по всем элементам, начиная с первого, и удаляем их
	if (elements.length > 1) 
	{
		for (let i = 1; i < elements.length; i++) {
		    container.removeChild(elements[i]);
		}
		return;
	}
		
	DataArr = {Access:1, income_id:income_id};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/GetIncomeListComposition.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 1:
	    			//CastomAlert(1,'Операций не зафиксировано');
	    			break;
	    		case 2:
	    			CastomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
	    			break;
	    		default:
	    			/*let div;

		    		for (var i = 0; i < data.length; i++) 
	    			{
	    				
						div = document.createElement('div');
						div.className = 'products_in_list';
						div.textContent = data[i]['name'];
						div.textContent += ' Количество: ' + data[i]['quantity'] + ', Ед.изм: ' + data[i]['measure'] + ', ';
						div.textContent += 'Цена за штуку: ' + data[i]['price'] + ' руб., ';
						if (data[i]['designation'] !== null)
						{
							div.textContent += 'Код ИВЦ ' + data[i]['designation'] + ', '; 
						}
						if (data[i]['buh_kode'] !== null) 
						{
							div.textContent += 'Код Бух ' + data[i]['buh_kode'] + ', ';	
						}
						div.textContent += data[i]['category'];
						container.appendChild(div);
	    			}
					break;
					*/
					let table = document.createElement('table');
					let headerRow = document.createElement('tr');
					let th1 = document.createElement('th');
					let th2 = document.createElement('th');
					let th3 = document.createElement('th');
					let th4 = document.createElement('th');
					let th5 = document.createElement('th');
					let th6 = document.createElement('th');
					let th7 = document.createElement('th');

					th1.textContent = 'НАЗВАНИЕ';
					th2.textContent = 'КОЛИЧЕСТВО';
					th3.textContent = 'ЕД. ИЗМ.';
					th4.textContent = 'ЦЕНА ЗА ШТУКУ';
					th5.textContent = 'КОД ИВЦ';
					th6.textContent = 'КОД БУХ';
					th7.textContent = 'КАТЕГОРИЯ';

					headerRow.appendChild(th1);
					headerRow.appendChild(th2);
					headerRow.appendChild(th3);
					headerRow.appendChild(th4);
					headerRow.appendChild(th5);
					headerRow.appendChild(th6);
					headerRow.appendChild(th7);

					table.appendChild(headerRow);

					for (var i = 0; i < data.length; i++) {
					let row = document.createElement('tr');
					let cell1 = document.createElement('td');
					let cell2 = document.createElement('td');
					let cell3 = document.createElement('td');
					let cell4 = document.createElement('td');
					let cell5 = document.createElement('td');
					let cell6 = document.createElement('td');
					let cell7 = document.createElement('td');

					cell1.textContent = data[i]['name'];
					cell2.textContent = data[i]['quantity'];
					cell3.textContent = data[i]['measure'];
					cell4.textContent = data[i]['price'] + ' руб.';

					if (data[i]['designation'] !== null) 
					{
						cell5.textContent = data[i]['designation'];
					}

					if (data[i]['buh_kode'] !== null) 
					{
						cell6.textContent = data[i]['buh_kode'];
					}

					cell7.textContent = data[i]['category'];

					row.appendChild(cell1);
					row.appendChild(cell2);
					row.appendChild(cell3);
					row.appendChild(cell4);
					row.appendChild(cell5);
					row.appendChild(cell6);
					row.appendChild(cell7);

					table.appendChild(row);
					}

					container.appendChild(table);
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
function GetIncomeList()
{
	let warehouse = document.querySelector('.warehouses_incomelist select').value;
	let container = document.querySelector('.IncomeList_info_container');
	while (container.firstChild) 
	{
    	container.removeChild(container.firstChild);
	}

	DataArr = {Access:1, warehouse:warehouse};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/GetIncomeList.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 1:
	    			CastomAlert(1,'Операций не зафиксировано');
	    			break;
	    		case 2:
	    			CastomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
	    			break;
	    		default:
	    			let div;
	    			let div_container;
	    			let linkid;
	    			let linkdate;
	    			let linkuser;
	    			let linkcontragent;
	    			let linkwarehouse;
	    			let btn;

		    		for (var i = 0; i < data.length; i++) 
	    			{
	    				
	    				div_container = document.createElement('div');
	    				//div_container.addEventListener("click", GetCompositionIncome);
						div_container.className = 'product_items_listelem';
						div_container.tabIndex = 0;
						div_container.id = data[i]['id'];
						div = document.createElement('div');

						div.appendChild(document.createTextNode('НОМЕР ПРИХОДА: '));
						linkid = document.createElement('a');
						linkid.className = 'ListNonBold';
						linkid.textContent = data[i]['id'];
						div.appendChild(linkid);
						div.appendChild(document.createTextNode(', '));

						div.appendChild(document.createTextNode('ДАТА: '));
						linkdate = document.createElement('a');
						linkdate.className = 'ListNonBold';
						linkdate.textContent = data[i]['date'];
						div.appendChild(linkdate);
						div.appendChild(document.createTextNode(', '));

						div.appendChild(document.createTextNode('ПРИНЯЛ: '));
						linkuser = document.createElement('a');
						linkuser.className = 'ListNonBold';
						linkuser.textContent = data[i]['name'];
						div.appendChild(linkuser);
						div.appendChild(document.createTextNode(', '));

						div.appendChild(document.createTextNode('КОНТРАГЕНТ: '));
						linkcontragent = document.createElement('a');
						linkcontragent.className = 'ListNonBold';
						linkcontragent.textContent = data[i]['contragent'];
						div.appendChild(linkcontragent);
						div.appendChild(document.createTextNode(', '));

						div.appendChild(document.createTextNode('СКЛАД: '));
						linkwarehouse = document.createElement('a');
						linkwarehouse.className = 'ListNonBold';
						linkwarehouse.textContent = data[i]['warehouse'] + ' ';
						div.appendChild(linkwarehouse);
						//div.appendChild(document.createTextNode(', '));

						btn = document.createElement('button');
						btn.textContent ='Показать/Скрыть';
						btn.addEventListener("click", GetCompositionIncome);	
						div.appendChild(btn);

						div_container.appendChild(div);
						container.appendChild(div_container);
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