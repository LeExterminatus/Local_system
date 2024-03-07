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
			Tasks.status,
			USR.lastname||' '||left(USR.firstname,1)||'. '||left(USR.patronimic,1)||'.' AS username,
			TK_U.lastname||' '||left(TK_U.firstname,1)||'. '||left(TK_U.patronimic,1)||'.' AS accepted,
			--EL_U.lastname||' '||left(EL_U.firstname,1)||'. '||left(EL_U.patronimic,1)||'.' AS executed,
			TL.date as accepted_date,
			DIV.briefly
			FROM
			(SELECT t.*,
			   CASE
			       WHEN tl.id IS NULL AND t.deadline IS NULL THEN 0
			       WHEN tl.id IS NULL AND t.deadline > CURRENT_TIMESTAMP THEN 2
			       WHEN tl.id IS NULL AND t.deadline <= CURRENT_TIMESTAMP THEN 3
			       --WHEN tl.id IS NOT NULL AND t.deadline IS NULL THEN 1
			       WHEN tl.id IS NOT NULL AND t.deadline >= CURRENT_TIMESTAMP THEN 1
			       WHEN tl.id IS NOT NULL AND t.deadline <= CURRENT_TIMESTAMP THEN 5
			       ELSE 4
			   END AS status
			FROM executive_tasks.tasks t
			LEFT JOIN executive_tasks.took_list tl ON t.id = tl.idtask
			WHERE NOT EXISTS (
			   SELECT 1 FROM executive_tasks.exec_list el WHERE el.idtask = t.id
			)) AS Tasks
			LEFT JOIN executive_tasks.took_list AS TL ON TL.idtask = Tasks.id
			--LEFT JOIN executive_tasks.exec_list AS EL ON EL.idtask = Tasks.id
			LEFT JOIN public.users AS USR ON USR.iduser = Tasks.employee
			LEFT JOIN public.users AS TK_U ON TK_U.iduser = TL.employee
			LEFT JOIN public.divisions AS DIV ON DIV.iddivision = Tasks.division
			--LEFT JOIN public.users AS EL_U ON EL_U.iduser = EL.employee
			WHERE Tasks.division = :division";
			if ($_POST['type'] == 3)//только закупки 
			{
				$QueryStr .= " AND Tasks.text LIKE 'Закупка.%'";
			}
			if ($_POST['type'] == 4)//без закупок 
			{
				$QueryStr .= " AND Tasks.text NOT LIKE 'Закупка.%'";
			}
			if ($_POST['type'] == 0) 
			{
				$QueryStr .= " AND TK_U.iduser = :usr";
				$QueryStr .= " order by deadline, date";
				$Query = $conn->prepare($QueryStr); 
				$Query->bindValue(':division', $this->UserInfoArray[0]['division']);
				$Query->bindValue(':usr', $this->UserId);
			}
			else
			{
				$QueryStr .= " order by deadline, date";
				$Query = $conn->prepare($QueryStr); 
				$Query->bindValue(':division', $this->UserInfoArray[0]['division']);
			}
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