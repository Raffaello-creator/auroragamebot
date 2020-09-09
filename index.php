<?php
////////////////////////////////////////////////////////////
//Вывод ошибок
ini_set('log_errors', 'On');
ini_set('error_log', 'php_errors.log');

////////////////////////////////////////////////////////////
//Библиотеки
include('static_comand.php');
include('config.php');
include('admin.php');
include('mysql.php');
include('prolog_com.php');
include('prolog_dialog.php');
include('menu.php');
include('craft_stoce.php');


////////////////////////////////////////////////////////////
//Проверка работоспособности файла
echo get($url.'setWebhook?url='.$webhook);

////////////////////////////////////////////////////////////
//собирает данные с присланого сообшения
if (($json = valid()) == false) { echo get($url.'setWebhook?url='.$webhook); 
exit();}
	$uid = $json['message']['from']['id'];         
	$first_name = $json['message']['from']['first_name'];
	$username = $json['message']['from']['username'];
	$date = $json['message']["date"];
	$msgid = $json['message']['message_id'];
	$text = $json['message']['text'];
	$user_info =  userget($uid); // извлекаем информацию о пользователе из базы данных
	$user_status = $user_info['status']; // извлекаем статус из массива



////////////////////////////////////////////////////////////
//Проверка реферальной системы
$ref = parseReferal($text);  // извлечение ref
if ($ref) 
{
	RefSave($uid,$ref, $first_name, $username);//если реферал то записать
	addUser($uid,$username,$first_name);//Добавление нового юзера
	addStoceUser($uid);
	$stoceuser = stoceuserget($ref);
	$p = $stoceuser['provision'];
	$provision = $p + 5;
	updateColumnsStoce(array("provision" => $provision),$ref);
	
	//Отправка первоначального сообшения рефералу
	$ANSWER = "Choose your language \n Выберите язык бота:";
	$keyboard = keyboard_l();
}	

////////////////////////////////////////////////////////////
//Сверяет присланое сообшение с кейсами которые имеет  выдает ответ
switch($text)
{
//
//
//
//
case '/start':

	addUser($uid,$username,$first_name);//Добавление нового юзера
	addStoceUser($uid);
	
	//$user_info =  userget($uid); // извлекаем информацию о пользователе из базы данных
	$user_status = $user_info['status']; // извлекаем статус из массива
	if ($user_status == "0")
{	//Отправка первоначального сообшения рефералу
	$ANSWER = "Choose your language \n Выберите язык бота:";
	$keyboard = keyboard_l();
}
else
{
	$url_img = "https://vk.com/albums134642584?z=photo134642584_457239852%2Fphotos134642584";
	sendPhoto($uid,$url_img);
	$adget = getADAll();
	$adpast = rand(1,$adget);
	$ad = ADget($adpast);
	$admessage = $ad['message'];
	$referals = getReferal($uid); //сбор рефералов
	$stoceuser = stoceuserget($uid);
	$stoce_provision = $stoceuser['provision'];
	//$user_info =  userget($uid); // извлекаем информацию о пользователе из базы данных
	$user_reputation = $user_info['reputation']; // извлекаем статус из массива
	$ANSWER = "Глава блок-поста: ".$first_name."
	➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖
	👤Солдат в распоряжении: ".$referals." 
	📦Единиц провизии: ".$stoce_provision."
	🎖Репутация: ".$user_reputation."
	➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖
	".$admessage;
	$keyboard = keyboard_general();
}
break;	
//
//
//
//	
case '👤Получить бойца':
	$referals = getReferal($uid); //сбор рефералов
	$ANSWER = "Ты привел в игру: ".$referals." человек.
	За каждого приглашенного, который перешел по ссылке: t.me/".$BOT_USERNAME."?start=".$uid." вы получите 1 солдата👤 и 5 единиц  провизия📦.
	Они потребуются для рейдов, вылазок и охраны блок-поста.";
break;
//
//
//
//	
case '🔃Обновить':
		$url_img = "https://vk.com/albums134642584?z=photo134642584_457239852%2Fphotos134642584";
	sendPhoto($uid,$url_img);
	$adget = getADAll();
	$adpast = rand(1,$adget);
	$ad = ADget($adpast);
	$admessage = $ad['message'];
	$referals = getReferal($uid); //сбор рефералов
	$stoceuser = stoceuserget($uid);
	$stoce_provision = $stoceuser['provision'];
	//$user_info =  userget($uid); // извлекаем информацию о пользователе из базы данных
	$user_reputation = $user_info['reputation']; // извлекаем статус из массива
	$ANSWER = "Глава блок-поста: ".$first_name."
	➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖
	👤Солдат в распоряжении: ".$referals." 
	📦Единиц провизии: ".$stoce_provision."
	🎖Репутация: ".$user_reputation."
	➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖➖
	".$admessage;
	$keyboard = keyboard_general();
break;
//
//
//
//
case '🏭Склад/Мастерская':
	$url_img = "https://vk.com/albums134642584?z=photo134642584_457239854%2Fphotos134642584";
	sendPhoto($uid,$url_img);
	$stoceuser = stoceuserget($uid);
	$stoce_provision = $stoceuser['provision'];
	$stoce_gun = $stoceuser['gun'];
	$stoce_food = $stoceuser['food'];
	$stoce_water = $stoceuser['water'];
	$stoce_health = $stoceuser['health'];
	$ANSWER = "На складе в наличии у Вас имеется:
	📦 Провизии: ".$stoce_provision." единиц  /usp1
	🔫 Оружие: ".$stoce_gun." единиц
	🥫 Еда: ".$stoce_food." единиц
	💧 Вода: ".$stoce_water." единиц
	💉 Мед. аптечка: ".$stoce_health." единиц
	
	Создание:
	1 ед.📦 Провизии /creprov1";

break;
//
//
//
//
case '📊Статистика':
$statistic_r = statisticget("1");
$all = $statistic_r['alluser'];
$online = $statistic_r['online'];
$ANSWER = "На данный момент в игре зарегистрировано: ".$all." пользователей.
Онлайн за последние 24 часа составляет: ".$online." человек.";
$keyboard = keyboard_general();
break;
//
//
//
//
case '📓Сюжетный квест':

$ANSWER = "В разработке";
$keyboard = keyboard_general();
break;
//
//
//
//
case '🏕Вылазка':
$ANSWER = "В разработке";
$keyboard = keyboard_general();
break;
//
//
//
//
case '🚩Рейд':
$ANSWER = "В разработке";
$keyboard = keyboard_general();
break;
//
//
//
//
case '🕋Здания':
$ANSWER = "В разработке";
$keyboard = keyboard_general();
break;
//
//
//
//
case '📚Помощь':
$ANSWER = "В данном разделе вы можете получить всю необходимую информацию для комфортной игры.
Если в разделе нету интересующего Вас информации просьба воспользоваться кнопкой ➕Нет информации➕ и описать что конкретно Вас интересует.
В ближайшее время Ваш запрос будет рассмотрен.";
$keyboard = keyboard_help();
break;
//
//
//
//
case 'Основать блок-пост':
$url_img = "https://vk.com/albums134642584?z=photo134642584_457239852%2Fphotos134642584";
	sendPhoto($uid,$url_img);
	updateColumns(array("status" => "1"),$uid);
	$ANSWER = "Отлично! Теперь для поддержания и развития нашей точки потребуется провизия📦 и люди👤.
	Первое ты можешь покупать готовое или собирать сухпайки из того что найдешь на вылазках.
	Солдат ты будешь набирать путем приглашения друзей в игру по ссылке:t.me/".$BOT_USERNAME."?start=".$uid.". Каждый  вступивший друг принесет вам одного солдата 👤 и 5 единиц провизии📦.
	Они потребуются для рейдов, вылазок и охраны блок-поста.
	Больше информации в разделе 📚Помощь";
	$keyboard = keyboard_general();
break;
//
//
//
//
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
case ($text=='/admin' and $uid=='409355104'):
	$ANSWER = "АЗМ ЕСТЬ БОГ";  
	$keyboard=keyboard_admin();
break;
//
//
//
//
case ($text == 'Рассылка всем'  and $uid == '409355104'):
	$ANSWER = 'Пришли текст для рассылки или нажми /cancel';
	updateColumns(array("status" => "100"),$uid);
break; 			
//
//
//
//
case ($text == '/cancel' and $uid == '409355104' and $user_status == '100'):
	$ANSWER = 'Отмена действия';
	updateColumns(array("status" => "1"),$uid);
break;	
//
//
//
//
case ($uid == '409355104' and $user_status == '100'):
	$users = getUsers('users');
	$ANSWER = "Отлично, рассылаю текст:\n\n".$text."\n
	на всех пользователей.\n\nВсего пользователей: ".count($users);
	sendMessage($uid,$ANSWER);
      

	updateColumns(array("status" => "1"),$uid);
for ($i = 0; $i < count($users); $i++) 
	{
    sendMessage($users[$i],$text);
	}
	$ANSWER = "Рассылка успешно отправлена!";
break;
//
//
//
//
case 'Статистика-р':
$statistic_r = statisticget("1");
$fake = $statistic_r['fakeuser'];
$real = $statistic_r['realuser'];
$all = $statistic_r['alluser'];
$online = $statistic_r['online'];

$ANSWER = "Реальная статистика бота:
Фейки: ".$fake."
Реалы: ".$real."
Всего: ".$all."
Онлайн: ".$online;
$keyboard = keyboard_edit_stat();
break;
//
//
//
//
case 'Обновить статистику':
$statistic_r = statisticget("1");
$fake = $statistic_r['fakeuser'];

$real = getUsersAll();
$all = "0";
$all = $fake + $real;
$min_online = $all * 45 /100 ;
$max_online = $all * 60 / 100;
$online = rand ($min_online,$max_online);

updateColumnsStatistic(array("alluser" => $all, "fakeuser" => $fake, "realuser" => $real, "online" => $online),"1");

$ANSWER = "Статистика обновлена";
$keyboard = keyboard_edit_stat();
break;
//
//
//
//
case 'Добавить фейков':
$statistic_r = statisticget("1");
$fake = $statistic_r['fakeuser'];
$a = rand(4, 18);
$fake = $fake + $a;

updateColumnsStatistic(array("fakeuser" => $fake),"1");

$ANSWER = "Добавлено: ".$a;
$keyboard = keyboard_edit_stat();
break;
//
//
//
//
case 'Добавить рекламу':

$ANSWER = "Введи рекламное сообшение:";
updateColumns(array("status" => "98"),$uid);

break;
//
//
//
//
case ($uid == '409355104' and $user_status == '98'):
	addAD($text);
updateColumns(array("status" => "1"),$uid);
$ANSWER = "Cообшение добавлено!";
$keyboard = keyboard_admin();


break;
}


	

	
	sendMessage($uid,$ANSWER,$keyboard);



?>