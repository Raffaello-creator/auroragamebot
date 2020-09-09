<?php
function keyboard_general() 
{
  var_dump($keyboard = json_encode($keyboard = ['keyboard' => [ ['📓Сюжетный квест','🏕Вылазка','🚩Рейд'],
																['🏭Склад/Мастерская','🕋Здания'],
																['📚Помощь','👤Получить бойца','📊Статистика'],
																['🔃Обновить'],],
  'resize_keyboard' => true,
  'one_time_keyboard' => false,
  'selective' => true]),true);
  return $keyboard;
};

function keyboard_help() 
{
  var_dump($keyboard = json_encode($keyboard = ['keyboard' => [ ['❓Квесты','❓Вылазка','❓Рейд'],
																['❓Склад/Мастерская','❓Здания'],
																['❓Солдаты','❓Ресурсы'],
																['➕Нет информации➕'],
																['🔃Обновить'],],
  'resize_keyboard' => true,
  'one_time_keyboard' => false,
  'selective' => true]),true);
  return $keyboard;
};



?>