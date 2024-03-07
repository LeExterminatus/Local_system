<?php
header('content-type: application/json');

$telegramBotToken = $_POST['Bot'];
$telegramChatId = $_POST['Chat'];
$messageText = "Проверка связи.";
$apiEndpoint = "https://api.telegram.org/bot$telegramBotToken/sendMessage";
$apiParams = array(
    'chat_id' => $telegramChatId,
    'text' => $messageText,
);

$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiParams));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

	// Проверка статуса ответа
	if ($response === false) {
	    echo json_encode('Произошла ошибка при отправке сообщения в группу Telegram: ' . curl_error($ch));
	    curl_close($ch);
		exit;
	} else {
	    $responseData = json_decode($response, true);
	    if ($responseData['ok'] === true) {
	       // echo 'Сообщение успешно отправлено в группу Telegram!';
	    } else {
	        echo json_encode('Произошла ошибка при отправке сообщения в группу Telegram: ' . $responseData['description']);
	        curl_close($ch);
	    	exit;
	    }
	}
curl_close($ch);
$text = "Bot=".$telegramBotToken."
Chat=".$telegramChatId;
$file = fopen("TG.ini", "w");

fwrite($file, $text);
fclose($file);
echo json_encode(1);
?>