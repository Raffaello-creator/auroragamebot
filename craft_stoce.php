<?php

if (($json = valid()) == false) { echo get($url.'setWebhook?url='.$webhook); 
exit();}
	$uid = $json['message']['from']['id'];         
	$first_name = $json['message']['from']['first_name'];
	$username = $json['message']['from']['username'];
	$date = $json['message']["date"];
	$msgid = $json['message']['message_id'];
	$text = $json['message']['text'];


///////////////////////////////////////
//Команды крафта
switch($text)
{
case '/creprov1':
	$stoceuser = stoceuserget($uid);
	$stoce_provision = $stoceuser['provision'];
	$stoce_gun = $stoceuser['gun'];
	$stoce_food = $stoceuser['food'];
	$stoce_water = $stoceuser['water'];
	$stoce_health = $stoceuser['health'];
if ($stoce_food >= 1 || $stoce_water >= 1 || $stoce_gun >= 1 || $stoce_health >= 1)
{
	++$stoce_provision;
	if($stoce_food == 1)
	{$stoce_food = "0";}
 else
	{--$stoce_food;};

	if($stoce_water == 1)
	{$stoce_water = "0";}
 else
	{--$stoce_water;};

	if($stoce_gun == 1)
	{$stoce_gun = "0";}
 else
	{--$stoce_gun;};

	if($stoce_health == 1)
	{$stoce_health = "0";}
 else
	{--$stoce_health;};

	updateColumnsStoce(array("provision" => $stoce_provision,"food" => $stoce_food,"water" => $stoce_water,"gun" => $stoce_gun,"health" => $stoce_health),$uid);
	$ANSWER = "Предмет успешно создан!";
}
else
{
	$ANSWER = "Недостаточно компонентов!";

}

break;
//
//
//
//
case '/usp1':
	$stoceuser = stoceuserget($uid);
	$stoce_provision = $stoceuser['provision'];
	$stoce_gun = $stoceuser['gun'];
	$stoce_food = $stoceuser['food'];
	$stoce_water = $stoceuser['water'];
	$stoce_health = $stoceuser['health'];

if($stoce_provision >= 1)
{if($stoce_provision == 1)
	{$stoce_provision = "0";}
 else
	{--$stoce_provision;};
	++$stoce_food;
	++$stoce_water;
	++$stoce_gun;
	++$stoce_health;
	updateColumnsStoce(array("provision" => $stoce_provision,"food" => $stoce_food,"water" => $stoce_water,"gun" => $stoce_gun,"health" => $stoce_health),$uid);
	$ANSWER = "Успешно распаковано";
}
else
{
	$ANSWER = "Провизия закончилась.
	
	Для того что бы получить больше единиц провизии приглашайте в игру друзей.
	За одного приглашенного друга Вам будет начислено 5 ед. 📦провизии.";
}
break;
//
//
//
//


};
?>