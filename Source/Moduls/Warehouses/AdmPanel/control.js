function AddNewWarehouse()
{
	let name = document.querySelector('#add_warehouse_name').value;
	DataArr = {Access:1,name:name};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/AddNewWarehouse.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			CustomAlert(0,'Склад был успешно добавлен');
	    			setTimeout(function() {location.reload();}, 500);
	    			break;
	    		case 2:
	    			CustomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
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
function AddNewWarehouseManager()
{
	let manager = document.querySelector('#add_warehouse_manager_id').value;
	let warehouse = document.querySelector('#add_warehouse_control_id').value;
	DataArr = {Access:1,manager:manager,warehouse:warehouse};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/AddNewWarehouseManager.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			CustomAlert(0,'Ответственный был успешно добавлен');
	    			setTimeout(function() {location.reload();}, 500);
	    			break;
	    		case 2:
	    			CustomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
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
function AddCategoryInWarehouse()
{
	let warehouse = document.querySelector('#add_warehouse_warehouse_id').value;
	let category = document.querySelector('#add_warehouse_cat_id').value;
	DataArr = {Access:1,warehouse:warehouse,category:category};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/AddCategoryInWarehouse.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			CustomAlert(0,'Категория была успешно добавлена');
	    			setTimeout(function() {location.reload();}, 500);
	    			break;
	    		case 2:
	    			CustomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
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
function DeleteWarehouse()
{
	let warehouseid = document.activeElement.parentElement.parentElement.querySelector('td').innerText;
	DataArr = {Access:1,warehouseid:warehouseid};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/DeleteWarehouse.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			CustomAlert(0,'Склад был успешно удален');
	    			setTimeout(function() {location.reload();}, 500);
	    			break;
	    		case 1:
	    			CustomAlert(1,'На данный склад ссылаются записи в других таблицах, удаление через GUI невозможно.');
	    			break;
	    		case 2:
	    			CustomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
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
function DeleteWarehouseManager()
{
	let recordid = document.activeElement.parentElement.parentElement.querySelector('td').innerText;
	DataArr = {Access:1,recordid:recordid};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/DeleteWarehouseManager.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			CustomAlert(0,'Запись была успешно удалена');
	    			setTimeout(function() {location.reload();}, 500);
	    			break;
	    		case 1:
	    			CustomAlert(1,'На данный склад ссылаются записи в других таблицах, удаление через GUI невозможно.');
	    			break;
	    		case 2:
	    			CustomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
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
function DeleteCategory()
{
	let recordid = document.activeElement.parentElement.parentElement.querySelector('td').innerText;
	DataArr = {Access:1,recordid:recordid};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/DeleteCategory.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			CustomAlert(0,'Категория была успешно удалена');
	    			setTimeout(function() {location.reload();}, 500);
	    			break;
	    		case 1:
	    			CustomAlert(1,'На данную категорию ссылаются записи в других таблицах, удаление через GUI невозможно.');
	    			break;
	    		case 2:
	    			CustomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
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
function DeleteCategoryInWarehouse()
{
	let recordid = document.activeElement.parentElement.parentElement.querySelector('td').innerText;
	DataArr = {Access:1,recordid:recordid};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/DeleteCategoryInWarehouse.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			CustomAlert(0,'Связь была успешно удалена');
	    			setTimeout(function() {location.reload();}, 500);
	    			break;
	    		case 1:
	    			CustomAlert(1,'На данную связь ссылаются записи в других таблицах, удаление через GUI невозможно.');
	    			break;
	    		case 2:
	    			CustomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
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
function AddCategory()
{
	let editable = document.querySelector('#add_category_editable').value;
	let category = document.querySelector('#add_category_name').value;
	DataArr = {Access:1,category:category,editable:editable};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/AddNewCategory.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	switch(data)
	    	{
	    		case 0:
	    			CustomAlert(0,'Категория была успешно добавлена');
	    			setTimeout(function() {location.reload();}, 500);
	    			break;
	    		case 2:
	    			CustomAlert(2,'На сервере возникла ошибка, попробуйте позднее.');
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