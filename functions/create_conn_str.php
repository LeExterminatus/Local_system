<?php
header('content-type: application/json');



$text = "Server=".$_POST['srv']."
Database=".$_POST['db']."
UID=".$_POST['usr']."
PWD=".$_POST['pwd']."
port=".$_POST['port'];
$file = fopen("conf.ini", "w");

fwrite($file, $text);
fclose($file);
echo json_encode(1);
?>