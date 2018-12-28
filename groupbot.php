<?php

if (!isset($_REQUEST)) {
return;
}

$confirmation_token = ''; // токен подтверждения callback api
$token = ''; // токен группы с доступом к сообщениям
$secretKey = ''; // секретный ключ
$randomid = rand(); // генерация рандомного ID для отправки сообщения
$groupid = ''; // ID группы
$chatid = ''; // ID чата куда отправляются сообщения

$data = json_decode(file_get_contents('php://input'));

if(strcmp($data->secret, $secretKey) !== 0 && strcmp($data->type, 'confirmation') !== 0)
    return;

switch ($data->type) {
case 'confirmation':
echo $confirmation_token;
break;

// получаем сообщение
case 'message_new':
$user_id = $data->object->user_id;
$user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&access_token={$token}&v=5.0"));

$user_name = $user_info->response[0]->first_name;

$message = $data->object->body;

$request_params = array(
'random_id' => $randomid,
'chat_id' => $chatid,
'message' => $message,
'group_id' => $groupid,
'access_token' => $token,
'v' => '5.50'
);

$get_params = http_build_query($request_params);

file_get_contents('https://api.vk.com/method/messages.send?'. $get_params); // отправляем сообщение 

echo('ok');

break;

}
?> 
