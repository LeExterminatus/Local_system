<?php
session_start();
header('content-type: application/json');
require_once($_SERVER['DOCUMENT_ROOT']."/IVC/coreX.php");
require_once("TG_acc.php");
class UserInfo extends ExecuteComand
{
	public $AjaxDefinition = 1;
	public function DefineConstr()
	{
		

		if ($_POST['Access'] == 1) 
		{
			$this->GetUserInfo();
			$deadline = null;
			$DB = $this->DBConnect;
			$conn = $DB->GetConn();
			$QueryStr = "INSERT INTO executive_tasks.took_list (id,idtask,employee,date) VALUES (DEFAULT,:task_id,:employee,:date)";
			$Query = $conn->prepare($QueryStr); 
			$Query->bindValue(':date', $this->Date);
			$Query->bindValue(':employee', $this->UserId);
			$Query->bindValue(':task_id', $_POST['task_id']);
			//$Query->execute();
			//$data = $Query->fetchAll(PDO::FETCH_ASSOC);
			try 
			{
				$conn->beginTransaction();
				$Query->execute();
				$conn->commit();

				$user_name = $this->UserInfoArray[0]['lastname'].' '.mb_substr($this->UserInfoArray[0]['firstname'], 0,1).'. '.mb_substr($this->UserInfoArray[0]['patronimic'], 0,1).'. ';
				$QueryStr = "SELECT text FROM executive_tasks.tasks WHERE id =".$_POST['task_id'];
				$Query = $conn->prepare($QueryStr); 
				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);
				if(file_exists('../TG.ini'))
				{
					SendMessageTG($user_name,$_POST['task_id'],$data[0]['text']);
				}

				echo json_encode(1); // успешно
			} 
			catch (PDOException $e) 
			{
				// Обработка ошибок
				$conn->rollBack();

				echo json_encode($e->getMessage(),JSON_UNESCAPED_UNICODE); // ошибка транзации!
			}
		}
		else
		{
			echo json_encode('У вас нет доступа!');
		}
	}
}
$Rgfdss = new UserInfo();
?>