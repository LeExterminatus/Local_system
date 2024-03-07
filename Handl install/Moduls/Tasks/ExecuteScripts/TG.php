<?php
function SendMessageTG($Mes_txt, $deadline,$task_id)
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
	//$telegramBotToken = '6172398450:AAHKvvyakvtb165sd4gXZb2VFVdrsr00Qpk';
	//$telegramChatId = '-1001962003800';

	if (strpos($Mes_txt,'Закупка.') === false) 
	{
		$messageText = 'Новая задача (ID: '.$task_id.'). Текст задачи: '.$Mes_txt; // текст сообщения
	}
	else
	{
		$msg_temp = str_replace('Закупка.','',$Mes_txt);
		$messageText = 'Новая заявка на закупку (ID: '.$task_id.'). Состав закупки: '.$msg_temp; // текст сообщения
	}
	
	if ($deadline !== null) 
	{
		$messageText .= ' Срок исполнения до: '.$deadline;
	}
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

}
//SendMessageTG('Ntcn',null);
?>