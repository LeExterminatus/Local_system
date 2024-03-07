<?php
session_start();
header('content-type: application/json');
require_once($_SERVER['DOCUMENT_ROOT']."/IVC/coreX.php");
class UserInfo extends ExecuteComand
{
	public $AjaxDefinition = 1;
	public function DefineConstr()
	{
		

		if ($_POST['Access'] == 1) 
		{
			$this->GetUserInfo();

			$DB = $this->DBConnect;
			$conn = $DB->GetConn();
			$QueryStr = "SELECT 
			Tasks.id,
			Tasks.text,
			Tasks.date,
			Tasks.deadline,
			USR.lastname||' '||left(USR.firstname,1)||'. '||left(USR.patronimic,1)||'.' AS username,
			TK_U.lastname||' '||left(TK_U.firstname,1)||'. '||left(TK_U.patronimic,1)||'.' AS accepted,
			EC_U.lastname||' '||left(EC_U.firstname,1)||'. '||left(EC_U.patronimic,1)||'.' AS executed,
			TL.date as accepted_date,
			EL.text as descr,
			EL.date as exec_date,
			DIV.briefly
			FROM
			(SELECT t.*
			FROM executive_tasks.tasks t
			LEFT JOIN executive_tasks.took_list tl ON t.id = tl.idtask
			) AS Tasks
			LEFT JOIN executive_tasks.took_list AS TL ON TL.idtask = Tasks.id
			LEFT JOIN executive_tasks.exec_list AS EL ON EL.idtask = Tasks.id
			LEFT JOIN public.users AS USR ON USR.iduser = Tasks.employee
			LEFT JOIN public.users AS TK_U ON TK_U.iduser = TL.employee
			LEFT JOIN public.users AS EC_U ON EC_U.iduser = EL.employee
			LEFT JOIN public.divisions AS DIV ON DIV.iddivision = Tasks.division
			WHERE Tasks.division = :division AND EC_U.iduser = :usr order by exec_date";
			$Query = $conn->prepare($QueryStr); 
			$Query->bindValue(':division', $this->UserInfoArray[0]['division']);
			$Query->bindValue(':usr', $this->UserId);
			$Query->execute();
			$data = $Query->fetchAll(PDO::FETCH_ASSOC);
			echo json_encode($data);
		}
		else
		{
			echo json_encode('У вас нет доступа!');
		}
	}
}
$Rgfdss = new UserInfo();
?>