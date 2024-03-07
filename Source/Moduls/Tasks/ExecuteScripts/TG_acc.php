<?php
function SendMessageTG($usr_acc, $task_id, $task_txt)
{
	if(file_exists('../TG.ini'))
	{
		$config_ini = parse_ini_file('../TG.ini');
		$telegramBotToken = (string)$config_ini['Bot'];
		$telegramChatId = (string)$config_ini['Chat'];
	}
	else
	{
		exit;
	}

	if (strpos($task_txt,'Закупка.') === false) 
	{
		$messageText = 'Задача (ID: '.$task_id.') принята - '.$usr_acc.' Текст задачи: '.$task_txt; // текст сообщения
	}
	else
	{
		$msg_temp = str_replace('Закупка.','',$task_txt);
		$messageText = 'Заявка на закупку (ID: '.$task_id.') отправлена на оплату. Состав заявки: '.$msg_temp; // текст сообщения
	}

	//$messageText = 'Задача (ID: '.$task_id.') принята - '.$usr_acc.' Текст задачи: '.$task_txt; // текст сообщения
	// Отправка запроса на API Telegram
	$apiEndpoint = "https://api.telegram.org/bot$telegramBotToken/sendMessage";
	$apiParams = array(
	    'chat_id' => $telegramChatId,
	    'text' => $messageText,
	);

	// Формирование запроса
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiParams));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Отправка запроса и получение ответа
	$response = curl_exec($ch);

	// Проверка статуса ответа
	if ($response === false) {
	    //echo 'Произошла ошибка при отправке сообщения в группу Telegram: ' . curl_error($ch);
	} else {
	    $responseData = json_decode($response, true);
	    if ($responseData['ok'] === true) {
	       // echo 'Сообщение успешно отправлено в группу Telegram!';
	    } else {
	        //echo 'Произошла ошибка при отправке сообщения в группу Telegram: ' . $responseData['description'];
	    }
	}

	// Закрытие соединения
	curl_close($ch);
	//echo json_encode($responseData); тут есть messageid, по которому можно будет делать цепочки сообщений и удалять ненужные сообщения! но потом.
}
//SendMessageTG('Ntcn',null);
?>