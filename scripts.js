document.addEventListener("DOMContentLoaded", function (){
	CheckPHPVersion()
});
function CheckPHPVersion()
{
	//let DataArr = {lvl:document.querySelector("[name=aid]").value};
	$.ajax({
		async:false,
		type: "POST",
	    url: "functions/check_php_version.php",
	    dataType: 'json',
	    data: "",
	    success: function(data)
	    {
	    	document.querySelector('#php_version').innerText = data;
	    	document.querySelector('#php_version').classList.add('check_stl');
	    	document.querySelector('.stg_one_one').classList.remove('hidden_elem');
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	        	document.querySelector('#php_version').innerText = 'не определена! Установка невозможна. Ошибка:Время ожидания ответа истекло!';
	        	document.querySelector('#php_version').classList.add('error_stl');
	            //alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	        	document.querySelector('#php_version').innerText = 'не определена! Установка невозможна. \n'+status+'\n'+e;
	        	document.querySelector('#php_version').classList.add('error_stl');
	            //alert('Сервер не смог обработать запрос. \n'+status+'\n'+e);
	        }	
	    }
	});
}
function CheckPGConnection()
{
	let DataArr = {srv:document.querySelector("[name=srv]").value,db:document.querySelector("[name=db]").value,usr:document.querySelector("[name=usr]").value,pwd:document.querySelector("[name=pwd]").value,port:document.querySelector("[name=port]").value};
	$.ajax({
		async:false,
		type: "POST",
	    url: "functions/check_pg_conn.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	if (data == 1) 
	    	{
	    		document.querySelector('#stg_one_one_res').innerText = 'Соединение установлено.';
	    		document.querySelector(".stg_one_two").classList.remove('hidden_elem');
	    		document.querySelector("[name=srv]").disabled = true;
	    		document.querySelector("[name=db]").disabled = true;
	    		document.querySelector("[name=usr]").disabled = true;
	    		document.querySelector("[name=pwd]").disabled = true;
	    		document.querySelector("[name=port]").disabled = true;
	    	}
	    	else
	    	{
	    		document.querySelector('#stg_one_one_res').innerText = 'Соединение не установлено. '+data;
	    	}
	    	//document.querySelector('#php_version').innerText = data;
	    	//location.reload();
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	        	document.querySelector('#stg_one_one_res').innerText = 'Соединение не установлено. Время ожидания ответа истекло. '+data;
	            //alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            //alert(status); // Другая ошибка
	            //alert('Сервер не смог обработать запрос. \n'+status+'\n'+e);
	            document.querySelector('#stg_one_one_res').innerText = 'Соединение не установлено. '+status+'\n'+e;
	            //alert(status);
	            //alert(e);
	        }	
	    }
	});
}
function CancelCheckPGConnection()
{
	document.querySelector('#stg_one_one_res').innerText = '';
	document.querySelector(".stg_one_two").classList.add('hidden_elem');
	document.querySelector("[name=srv]").disabled = false;
	document.querySelector("[name=db]").disabled = false;
	document.querySelector("[name=usr]").disabled = false;
	document.querySelector("[name=pwd]").disabled = false;
	document.querySelector("[name=port]").disabled = false;
}
function SaveStageOne()
{
	let DataArr = {srv:document.querySelector("[name=srv]").value,db:document.querySelector("[name=db]").value,usr:document.querySelector("[name=usr]").value,pwd:document.querySelector("[name=pwd]").value,port:document.querySelector("[name=port]").value};
	$.ajax({
		async:false,
		type: "POST",
	    url: "functions/create_conn_str.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	document.querySelector(".stage_one").classList.add('hidden_elem');
	    	document.querySelector(".stage_two").classList.remove('hidden_elem');
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            alert('Сервер не смог обработать запрос. \n'+status+'\n'+e);
	        }	
	    }
	});
}
function CheckAndConfigureModuls()
{
	let wh_mod = document.querySelector("#wh_m");
	let tsk_mod = document.querySelector("#tsk_m");
	//wh_mod.disabled = true;
	//tsk_mod.disabled = true;
	document.querySelector(".stg_two_one").classList.remove("hidden_elem");
	if (tsk_mod.checked == true) 
	{
		document.querySelector("#tsk_m_mod1").classList.remove("hidden_elem");
	}
	else
	{
		document.querySelector("#tsk_m_mod1").classList.add("hidden_elem");
	}
}
function CheckTGConn()
{
	let DataArr = {Bot:document.querySelector("[name=bot_id]").value,Chat:document.querySelector("[name=chat_id]").value};

	$.ajax({
		async:false,
		type: "POST",
	    url: "functions/check_tg_connect.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	    	if (data == 1) 
	    	{
	    		document.querySelector("#stg_two_one_tsk_mod1_res").innerText = "Подключено!";
	    	}
	    	else
	    	{
	    		document.querySelector("#stg_two_one_tsk_mod1_res").innerText = data;
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
	            alert('Сервер не смог обработать запрос. \n'+status+'\n'+e);
	        }	
	    }
	});
}
async function InstallModules() {
  let DataArr = {base: true};
  let Tasks = document.querySelector('[name=tasks]').checked;
  let Warehouses = document.querySelector('[name=warehouses]').checked;
  document.querySelector('#tg_btn').disabled = true;
  document.querySelector('#install_btn').disabled = true;
  document.querySelector('.base').classList.remove('hidden_elem');
  $.ajax({
    type: "POST",
    url: "functions/install_base_module.php",
    dataType: 'json',
    data: DataArr,
    success: async function(data) {
      if (data === 1) {
      	document.querySelector('.base').querySelector('.circle').classList.add('green');
        try {
          let results = await Promise.all([
           	InstallOtherModules('tsk',Tasks),
            InstallOtherModules('wrh',Warehouses)
          ]);
          SaveStageTwo();
        } 
        catch (error) 
        {
          // Обработка ошибки, если одна из асинхронных функций завершилась с ошибкой
          console.error('Error during async operations:', error);
          alert(error);
          // Дополнительные действия по обработке ошибки
        }
      }
    },
    error: function(jqXHR, status, e) {
		document.querySelector('.base').querySelector('.circle').classList.add('red');
		if (status === "timeout") 
		{
			alert("Время ожидания ответа истекло!");
		} 
		else 
		{
			alert('Сервер не смог обработать запрос. \n' + status + '\n' + e);
		}
	}
  });
}
async function InstallOtherModules(modulname, stat) {
  return new Promise((resolve, reject) => {
    let module_src = '';
    switch (modulname) {
      case 'tsk':
      	if (stat) 
      	{
      	   	document.querySelector('.tsk').classList.remove('hidden_elem');
        	module_src = 'install_tasks_module';
      	}
      	else
        {
        	return resolve(1);
        }
        break;
      case 'wrh':
      	if (stat) 
      	{
      		document.querySelector('.wrh').classList.remove('hidden_elem');
        	module_src = 'install_warehouses_module';
        }
        else
        {
        	return resolve(1);
        }
        break;
      default:
        return resolve(1);
    }

    let DataArr = { access: true };
    $.ajax({
      type: "POST",
      url: "functions/" + module_src + ".php",
      dataType: 'json',
      data: DataArr,
      success: function (data) {
        // закрасить полоску загрузки
        if (data == 1) 
        {
			document.querySelector('.'+modulname).querySelector('.circle').classList.add('green');
        	resolve(data);
        }	
      },
      error: function (jqXHR, status, e) {
      	document.querySelector('.'+modulname).querySelector('.circle').classList.add('red');
        if (status === "timeout") {
          alert("Время ожидания ответа истекло!");
        } else {
          alert('Сервер не смог обработать запрос. \n' + status + '\n' + e);
        }
        reject(e);
      }
    });
  });
}
function SaveStageTwo()
{
	document.querySelector('.stage_two').classList.add('hidden_elem');
	document.querySelector('.stage_three').classList.remove('hidden_elem');
}
/*function InstallModules()
{
	let DataArr = {base:true};
	let Tasks = document.querySelector('[name=tasks]').checked;
	let Warehouses = document.querySelector('[name=warehouses]').checked;
	$.ajax({
		async:false,
		type: "POST",
	    url: "functions/install_base_module.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	 		if (data = 1) 
	 		{
	 			// await будет ждать массив с результатами выполнения всех промисов
	 			//ПОПРОБОВАТЬ ВОТ ЭТУ ТЕМУ!
				
				//let results = await Promise.all([
  				//InstallOtherModules('tsk'),
  				//InstallOtherModules('wrh')
				//]);
				

	 			//закрасить полоску загрузки
	 			//показать полоску загрузки этих модулей
	 			if (Tasks) 
	 			{
	 				InstallOtherModules('tsk');
	 			}
	 			if (Warehouses) 
	 			{
	 				InstallOtherModules('wrh');
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
	            alert('Сервер не смог обработать запрос. \n'+status+'\n'+e);
	        }	
	    }
	});
}
async function InstallOtherModules(modulname)
{
	let module_src = '';
	switch(modulname)
	{
		case 'tsk':
			module_src = 'install_tasks_module';
		break;
		case 'wrh':
			module_src = 'install_warehouses_module';
		break;
		default:
			return 0;
		break;
	}
	let DataArr = {access:true};
	$.ajax({
		async:false,
		type: "POST",
	    url: "functions/"+module_src+".php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
	 		//закрасить полоску загрузки
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            alert('Сервер не смог обработать запрос. \n'+status+'\n'+e);
	        }	
	    }
	});
}
*/