let timer;
document.addEventListener("DOMContentLoaded", function (){
	ToggleMainMenu();
	document.querySelector('#cancel_addtask_form').addEventListener("click", ToggleFormNewTask);
	document.querySelector('#show_add_task_form').addEventListener("click", ToggleFormNewTask);
	document.querySelector('#record_addtask_form').addEventListener("click", SetTask);
	document.querySelector('.tasks_select select').addEventListener("click", UpdateTaskList);
	UpdateTaskList();
	timer = setInterval(UpdateTaskList,30000);
});
function UpdateTaskList()
{
	let select = document.querySelector('.tasks_select select');
	if (select.value == 2) 
	{
		GetExecutedTasks();
	}
	else
	{
		GetTaskList(select.value);
	}
}
function ToggleFormNewTask()
{
	let add_form = document.querySelector('.new_task_container');
	if (add_form.classList.contains('hidden_elem')) 
	{
		add_form.classList.remove('hidden_elem');
	}
	else
	{
		add_form.classList.add('hidden_elem');
	}
}
function SetTask()
{
	let task_text = document.querySelector('.new_task_body textarea').value;
	let deadline = document.querySelector('.new_task_body input').value;
	let data = {Access:1,task_text:task_text,deadline:deadline};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/SetTask.php",
	    dataType: 'json',
	    data: data,
	    success: function(data)
	    {
	    	document.querySelector('.new_task_body textarea').value = '';
	    	document.querySelector('.new_task_body input').value = '';
	    	CustomAlert(0,'Задача поставлена!');
	    	GetTaskList();
	    	ToggleFormNewTask();
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            //alert(status); // Другая ошибка
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	            //alert(status);
	            //alert(e);
	        }	
	    }
	});
}
function GetExecutedTasks()
{
	let task_area = document.querySelector('.tasks');
	if (task_area.querySelectorAll('.task_container').length != null) 
	{
		while (task_area.querySelectorAll('.task_container').length > 0) 
		{
	    	task_area.removeChild(task_area.firstChild);
		}
	}
	let data = {Access:1};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/GetExecutedTasks.php",
	    dataType: 'json',
	    data: data,
	    success: function(data)
	    {
	    	if (data.length > 0)
	    	{
	    		let div;
	    		let header;
		    	let division;
		    	let user;
		    	let date;
		    	let deadline;

		    	let took;

		    	let text;

		    	let end_form;
		    	let textarea; 
	    		for (var i = 0; i < data.length; i++) 
	    		{
	    			div = document.createElement('div');
    		
					div.className = 'task_container';
					div.classList.add('executed');
					
					div.id = data[i]['id'];

					header = document.createElement('div');
					header.className = 'task_header';
					div.appendChild(header);

					division = document.createElement('a');
					division.className = 'task_division';
					division.textContent = data[i]['briefly'] +'-'+data[i]['id'];
					header.appendChild(division);

					user = document.createElement('a');
					user.className = 'task_user';
					user.textContent = ' Поставил задачу: '+data[i]['username'];
					header.appendChild(user);

					date = document.createElement('a');
					date.className = 'task_date';
					date.textContent = ' Дата постановки: '+data[i]['date'];
					header.appendChild(date);

					deadline = document.createElement('a');
					deadline.className = 'task_deadline';
					if (data[i]['deadline'] == null || data[i]['deadline'] == 'null') 
					{
						deadline.textContent = ' Без срока';
					}
					else
					{
						deadline.textContent = ' Срок: '+data[i]['deadline'];
					}
					header.appendChild(deadline);

					took = document.createElement('div');
					took.className = 'task_took';
					took.textContent = 'Взял задачу: '+data[i]['accepted']+' Дата:'+data[i]['accepted_date'];
					div.appendChild(took);

					text = document.createElement('div');
					text.className = 'task_text';
					text.textContent = data[i]['text'];
					div.appendChild(text);

					end_form = document.createElement('div');
					end_form.className = 'task_exec_comment';

					textarea = document.createElement('div');
					textarea.className = 'task_text';
					textarea.textContent = 'Проделанная работа:'+data[i]['descr'];
					
					end_btn = document.createElement('a');
					end_btn.textContent = 'Выполнил:'+data[i]['executed']+' Дата выполнения:'+data[i]['exec_date'];

					end_form.appendChild(textarea);
					end_form.appendChild(end_btn);
					div.appendChild(end_form);

					task_area.appendChild(div);
	    		}

	    	}
	    	else
	    	{
	    		CustomAlert(1,'У Вас нет выполненых задач');
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
	            //alert(status); // Другая ошибка
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	            //alert(status);
	            //alert(e);
	        }	
	    }
	});
}
function TakeTask()
{
	let task_id = document.activeElement.parentElement.parentElement.id;
	let data = {Access:1,task_id:task_id};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/TakeTask.php",
	    dataType: 'json',
	    data: data,
	    success: function(data)
	    {
	    	GetTaskList();
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            //alert(status); // Другая ошибка
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	            //alert(status);
	            //alert(e);
	        }	
	    }
	});
}
function ExecTask()
{
	let task_id = document.activeElement.parentElement.parentElement.id;
	let text = document.activeElement.parentElement.querySelector('textarea').value;
	let data = {Access:1,task_id:task_id,text:text};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/ExecTask.php",
	    dataType: 'json',
	    data: data,
	    success: function(data)
	    {
	    	GetTaskList();
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            //alert(status); // Другая ошибка
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	            //alert(status);
	            //alert(e);
	        }	
	    }
	});
}
function GetTaskList(type = 1)
{
	let data = {Access:1,type:type};
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/GetTasksList.php",
	    dataType: 'json',
	    data: data,
	    success: function(data)
	    {
	    	let task_area = document.querySelector('.tasks');
	    	/*if (task_area.querySelectorAll('.task_container').length != null) 
			{
				while (task_area.querySelectorAll('.task_container').length > 0) 
				{
			    	task_area.removeChild(task_area.firstChild);
				}
			}*/
			
			let div;
	    	
	    	let header;
	    	let division;
	    	let user;
	    	let date;
	    	let deadline;

	    	let took;

	    	let text;

	    	let action;
	    	let accept_btn;

	    	let end_form;
	    	let textarea; 
    		let end_btn;

			let elem;
			let task_array = [];
			for (var i = 0; i < data.length; i++) 
			{
				elem = task_area.querySelector('[id="'+data[i]['id']+'"]');
				if (elem != null) 
				{
					if (elem.getAttribute('status') !== data[i]['status']) 
					{
						elem.classList.remove('urgent','overdue','accepted');
						switch(data[i]['status'])
						{
							case 1:
								elem.classList.add('urgent');
							break;
							case 2:
								elem.classList.add('urgent');
							break;
							case 3:
								elem.classList.add('overdue');
							break;
							case 4:
								elem.classList.add('accepted');
							break;
							case 5:
								elem.classList.add('overdue');
							break;
						}
						elem.setAttribute('status',data[i]['status']);

						if (data[i]['accepted'] == null || data[i]['accepted'] == 'null') 
						{
							elem.querySelector('.task_took').innerText = 'Ещё никто не взял задачу';
							if (elem.querySelector('.task_action') == null) 
							{
								if (elem.querySelector('.task_exec_comment') != null) 
								{
									elem.querySelector('.task_exec_comment').remove();
								}
								action = document.createElement('div');
								action.className = 'task_action';
								accept_btn = document.createElement('button');
								accept_btn.textContent = 'ПРИНЯТЬ';
								accept_btn.addEventListener("click", TakeTask);
								action.appendChild(accept_btn);
								elem.appendChild(action);
							}
						}
						else
						{
							elem.querySelector('.task_took').innerText = 'Взял задачу: '+data[i]['accepted']+' Дата:'+data[i]['accepted_date'];//data[i]['accepted'];

							if (elem.querySelector('.task_exec_comment') == null) 
							{
								if (elem.querySelector('.task_action') != null) 
								{
									elem.querySelector('.task_action').remove();
								}
								end_form = document.createElement('div');
								end_form.className = 'task_exec_comment';

								textarea = document.createElement('textarea');
								textarea.placeholder = 'Опишите проделанную работу...';
								
								end_btn = document.createElement('button');
								end_btn.addEventListener("click", ExecTask);
								
								end_btn.textContent = 'ЗАПИСАТЬ';

								end_form.appendChild(textarea);
								end_form.appendChild(end_btn);
								elem.appendChild(end_form);
							}
						}
					}
				}
				else
				{
					div = document.createElement('div');
    		
					div.className = 'task_container';
					switch(data[i]['status'])
					{
						case 1:
							div.classList.add('urgent');
						break;
						case 2:
							div.classList.add('urgent');
						break;
						case 3:
							div.classList.add('overdue');
						break;
						case 4:
							div.classList.add('accepted');
						break;
						case 5:
							div.classList.add('overdue');
						break;
					}
					div.setAttribute('status',data[i]['status']);
					div.id = data[i]['id'];

					header = document.createElement('div');
					header.className = 'task_header';
					div.appendChild(header);

					division = document.createElement('a');
					division.className = 'task_division';
					division.textContent = data[i]['briefly']+'-'+data[i]['id'];
					header.appendChild(division);

					user = document.createElement('a');
					user.className = 'task_user';
					user.textContent = ' Поставил задачу: '+data[i]['username'];
					header.appendChild(user);

					date = document.createElement('a');
					date.className = 'task_date';
					date.textContent = ' Дата поставновки: '+data[i]['date'];
					header.appendChild(date);

					deadline = document.createElement('a');
					deadline.className = 'task_deadline';
					if (data[i]['deadline'] == null || data[i]['deadline'] == 'null') 
					{
						deadline.textContent = ' Без срока';
					}
					else
					{

						deadline.textContent = ' Срок: '+data[i]['deadline'];
					}
					header.appendChild(deadline);

					took = document.createElement('div');
					took.className = 'task_took';
					if (data[i]['accepted'] == null || data[i]['accepted'] == 'null') 
					{
						took.textContent = 'Ещё никто не взял задачу';
					}
					else
					{
						took.textContent = 'Взял задачу: '+data[i]['accepted']+' Дата:'+data[i]['accepted_date'];//data[i]['accepted'];
					}
					div.appendChild(took);

					text = document.createElement('div');
					text.className = 'task_text';
					text.textContent = data[i]['text'];
					div.appendChild(text);


					if (data[i]['accepted'] == null || data[i]['accepted'] == 'null') 
					{
						action = document.createElement('div');
						action.className = 'task_action';
						accept_btn = document.createElement('button');
						accept_btn.textContent = 'ПРИНЯТЬ';
						accept_btn.addEventListener("click", TakeTask);
						action.appendChild(accept_btn);
						div.appendChild(action);
					}
					else
					{
						end_form = document.createElement('div');
						end_form.className = 'task_exec_comment';

						textarea = document.createElement('textarea');
						textarea.placeholder = 'Опишите проделанную работу...';
						
						end_btn = document.createElement('button');
						end_btn.addEventListener("click", ExecTask);
						end_btn.textContent = 'ЗАПИСАТЬ';

						end_form.appendChild(textarea);
						end_form.appendChild(end_btn);
						div.appendChild(end_form);
					}
					task_area.appendChild(div);
				}
				task_array.push(data[i]['id']);
			}
			const elements = task_area.querySelectorAll('.task_container'); // Ваш массив с элементами на странице
			//const task_array = [...]; // Ваши выбранные идентификаторы элементов
			//console.log(task_array);
			// Удаляем элементы, чьи идентификаторы не содержатся в task_array
			elements.forEach(function(element) {
			  const elementId = parseInt(element.id); // Преобразуем element.id в число
			  if (!task_array.includes(elementId)) {
			    //console.log(element.id + ' ' + task_array);
			    element.remove(); // Удаление элемента из DOM
			  }
			});
			/*
	    	let div;
	    	
	    	let header;
	    	let division;
	    	let user;
	    	let date;
	    	let deadline;

	    	let took;

	    	let text;

	    	let action;
	    	let accept_btn;

	    	let end_form;
	    	let textarea; 
    		let end_btn;

    		for (var i = 0; i < data.length; i++) 
    		{
    			div = document.createElement('div');
    		
				div.className = 'task_container';
				switch(data[i]['status'])
				{
					case 1:
						div.classList.add('urgent');
					break;
					case 2:
						div.classList.add('urgent');
					break;
					case 3:
						div.classList.add('overdue');
					break;
					case 4:
						div.classList.add('accepted');
					break;
					case 5:
						div.classList.add('overdue');
					break;
				}
				div.setAttribute('status',data[i]['status']);
				div.id = data[i]['id'];

				header = document.createElement('div');
				header.className = 'task_header';
				div.appendChild(header);

				division = document.createElement('a');
				division.className = 'task_division';
				division.textContent = data[i]['briefly'];
				header.appendChild(division);

				user = document.createElement('a');
				user.className = 'task_user';
				user.textContent = data[i]['username'];
				header.appendChild(user);

				date = document.createElement('a');
				date.className = 'task_date';
				date.textContent = data[i]['date'];
				header.appendChild(date);

				deadline = document.createElement('a');
				deadline.className = 'task_deadline';
				if (data[i]['deadline'] == null || data[i]['deadline'] == 'null') 
				{
					deadline.textContent = ' Без срока';
				}
				else
				{
					deadline.textContent = data[i]['deadline'];
				}
				header.appendChild(deadline);

				took = document.createElement('div');
				took.className = 'task_took';
				if (data[i]['accepted'] == null || data[i]['accepted'] == 'null') 
				{
					took.textContent = 'Ещё никто не взял задачу';
				}
				else
				{
					took.textContent = data[i]['accepted'];
				}
				div.appendChild(took);

				text = document.createElement('div');
				text.className = 'task_text';
				text.textContent = data[i]['text'];
				div.appendChild(text);


				if (data[i]['accepted'] == null || data[i]['accepted'] == 'null') 
				{
					action = document.createElement('div');
					action.className = 'task_action';
					accept_btn = document.createElement('button');
					accept_btn.textContent = 'ПРИНЯТЬ';
					accept_btn.addEventListener("click", TakeTask);
					action.appendChild(accept_btn);
					div.appendChild(action);
				}
				else
				{
					end_form = document.createElement('div');
					end_form.className = 'task_exec_comment';

					textarea = document.createElement('textarea');
					textarea.placeholder = 'Опишите проделанную работу...';
					
					end_btn = document.createElement('button');
					end_btn.textContent = 'ЗАПИСАТЬ';

					end_form.appendChild(textarea);
					end_form.appendChild(end_btn);
					div.appendChild(end_form);
				}
				task_area.appendChild(div);
    		}
			*/
    		/*let ContragentList = document.querySelectorAll('.product_items');
			for (var i = 0; i < ContragentList.length; i++)
			{
				ContragentList[i].addEventListener("click", SetRowFocus);
			}*/
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло!");
	        } 
	        else 
	        {
	            //alert(status); // Другая ошибка
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	            //alert(status);
	            //alert(e);
	        }	
	    }
	});
}
function LoadContactList()
{
	$.ajax({
		async:false,
		type: "POST",
	    url: "ExecuteScripts/LoadContactList.php",
	    dataType: 'json',
	    data: 'Access=1',
	    success: function(data)
	    {
	    	let TmpDiv = '';
	    	let TmpButton = '';
	    	let TmpGroup = '';
	    	let TmpDivCont = '';
	    	let TmpGroupCont = '';
	    	let TmpGroupLabel = '';
	    	let TmpDivLabel = '';
	    	let TmpLabel = '';
	    	let TmpMainDivCont = '';
	    	let MenuArea = document.querySelector('.menu');
	    	for (var i = 0; i < data.length; i++) 
	    	{
	    		if (TmpDiv !== data[i]['iddivision']) 
	    		{	
	    			TmpDiv = data[i]['iddivision'];

	    			TmpDivCont = document.createElement("div");
	    			TmpDivCont.classList.add('Division');
	    			TmpDivCont.classList.add('hidden_elem');
	    			
	    			TmpMainDivCont = document.createElement("div");
	    			TmpMainDivCont.classList.add('DivisionContainer');
	    			//TmpMainDivCont.classList.add('hidden_elem');

	    			TmpDivLabel = document.createElement("a");
	    			TmpDivLabel.innerText = data[i]['division'];
	    			TmpDivLabel.classList.add('DivLabel');
	    			
	    			TmpMainDivCont.insertAdjacentElement('beforeend',TmpDivLabel);
	    			TmpMainDivCont.insertAdjacentElement('beforeend',TmpDivCont);
	    			MenuArea.insertAdjacentElement('beforeend',TmpMainDivCont);
	    		}
	    		
	    		if (TmpGroup !== data[i]['user_group']) 
	    		{
	    			TmpGroup = data[i]['user_group'];
	    			TmpGroupCont = document.createElement("div");
	    			TmpGroupCont.classList.add('Group');
	    			TmpGroupLabel = document.createElement("a");
	    			TmpGroupLabel.innerText = data[i]['groupdescr'];
	    			TmpGroupLabel.classList.add('GroupLabel');
	    			TmpGroupCont.insertAdjacentElement('beforeend',TmpGroupLabel);
	    			TmpDivCont.insertAdjacentElement('beforeend',TmpGroupCont);
	    		}

	    		TmpButton = document.createElement("button");
	    		TmpButton.id = data[i]['iduser'];
	    		TmpButton.innerText = data[i]['lastname']+' '+data[i]['firstname']+' '+data[i]['patronimic'];
	    		TmpLabel = document.createElement("a");
	    		TmpLabel.innerText = data[i]['postdescr'];
	    		TmpLabel.classList.add('PostLabel');
	    		TmpButton.addEventListener("click", function (){
					ToggleChatArea()
				});
				TmpGroupCont.insertAdjacentElement('beforeend',TmpLabel);
	    		TmpGroupCont.insertAdjacentElement('beforeend',TmpButton);
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
	            //alert(status); // Другая ошибка
	            alert('Сервер не смог обработать запрос. Попробуйте снова или обратитесь на ИВЦ \n'+status+'\n'+e);
	            //alert(status);
	            //alert(e);
	        }	
	    }
	});
}
