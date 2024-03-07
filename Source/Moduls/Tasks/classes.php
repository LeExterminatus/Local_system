<?php
class Tasks extends GUIGenerator
{
	//public $PageControl = 2;
	public function RequireModuls()
	{
		parent::RequireModuls();
		echo "<link rel='stylesheet' type='text/css' href='style.css'>";
		echo "<script src='control.js'></script>";
	}
	public function MainGeneration()
	{
		//var_dump($this);
		echo "<div class='main_container'>";
			echo "<div class='tasks_select'><select><option value='1'>Все задачи</option><option value='0'>Мои задачи</option><option value='3'>Только закупки</option><option value='4'>Без закупок</option><option value='2'>Выполненные</option></select></div>";
			echo "<div class='tasks'>";
				
			echo "</div>";
			echo "<div class='btn_panel'>";
				echo "<button id='show_add_task_form'>Новая задача</button>";
			echo "</div>";
		echo "</div>";
		echo "<div class='new_task_container hidden_elem'>";
			echo "
				<div class='new_task_body'>
					<div class='new_task_head'>
						Новая задача
					</div>
					<div>
						<textarea placeholder='Введите текст задачи'></textarea>
					</div>
					<div class='new_task_deadline'>
						Введите срок исполнения задачи (если он есть)
						<input type='datetime-local'>
					</div>
					<div class='new_task_btn_panel'>
						<button id='record_addtask_form'>
							Записать!
						</button>
						<button id='cancel_addtask_form'>
							Отмена
						</button>
					</div>
				</div>
				";
		echo "</div>";
	}
	public function GenerateNewTaskForm()
	{

	}
	public function LoadEmployeList() 
	{
		$QueryStr = "SELECT 
		USR.division, USR.iduser, USR.post, USR.iduser,
		USR.lastname||' '||USR.firstname||' '||USR.patronimic AS username,
		DVS.description
		FROM USERS AS USR
		LEFT JOIN posts AS DVS ON DVS.idpost = USR.post
		WHERE division = :division AND status != 0
		order by post";
		$DB = $this->DBConnect;
		$conn = $DB->GetConn();
		$Query = $conn->prepare($QueryStr);
		$Query->bindParam(':division', $this->UserInfoArray[0]['division'], PDO::PARAM_STR); 
		$Query->execute();
		$data = $Query->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	public function LoadTasksFromMe()
	{
		$QueryStr = "SELECT * 
		FROM tasks.executorlist AS EL
		LEFT JOIN tasks.tasklist AS TL ON TL.id = EL.idtask
		WHERE EL.userid = 10 OR TL.control = 10";
	}
	public function LoadTasksForMe()
	{
		$QueryStr = "SELECT * 
		FROM tasks.executorlist AS EL
		LEFT JOIN tasks.tasklist AS TL ON TL.id = EL.idtask
		WHERE EL.userid = 10 OR TL.control = 10";
	}
}
?>