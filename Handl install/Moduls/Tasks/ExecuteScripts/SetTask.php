<?php
session_start();
header('content-type: application/json');
require_once($_SERVER['DOCUMENT_ROOT']."/IVC/coreX.php");
require_once("TG.php");
class UserInfo extends ExecuteComand
{
	public $AjaxDefinition = 1;
	public function DefineConstr()
	{
		

		if ($_POST['Access'] == 1) 
		{
			$this->GetUserInfo();
		
			if ($_POST['deadline'] == '' OR $_POST['deadline'] == null) 
			{
				$deadline = null;
			}
			else
			{
				$deadline = date("Y-m-d H:i:s", strtotime($_POST['deadline']));
			}
				
			//echo json_encode($this->UserInfoArray[0]['division']);
			//exit();

			$DB = $this->DBConnect;
			$conn = $DB->GetConn();
			$QueryStr = "INSERT INTO executive_tasks.tasks (id,text,date,employee,division,deadline) VALUES (DEFAULT,:text,:date,:employe,:div,:dead) returning id";
			$Query = $conn->prepare($QueryStr); 
			$Query->bindValue(':text', $_POST['task_text']);
			$Query->bindValue(':date', $this->Date);
			$Query->bindValue(':employe', $this->UserId);
			$Query->bindValue(':div', $this->UserInfoArray[0]['division']);
			$Query->bindValue(':dead', $deadline);
			//$Query->execute();
			//$data = $Query->fetchAll(PDO::FETCH_ASSOC);
			$user_name = $this->UserInfoArray[0]['lastname'].' '.mb_substr($this->UserInfoArray[0]['firstname'], 0,1).'. '.mb_substr($this->UserInfoArray[0]['patronimic'], 0,1).'. ';
			$mess = $_POST['task_text'].' Задачу поставил: '.$user_name;
			try 
			{
				$conn->beginTransaction();
				$Query->execute();
				$data = $Query->fetchAll(PDO::FETCH_ASSOC);
				$conn->commit();
				if(file_exists('../TG.ini'))
				{
					SendMessageTG($mess ,$deadline, $data[0]['id']);
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