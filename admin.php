<?php
	
function keyboard_admin() {
  
  var_dump($keyboard = json_encode($keyboard = ['keyboard' => [['Рассылка всем','🔃Обновить','Статистика-р'],['Добавить рекламу']],
  'resize_keyboard' => true,
  'one_time_keyboard' => false,
  'selective' => true]),true);
  return $keyboard;
}

function delete_keyboard()
{
  var_dump($keyboard = json_encode($keyboard =  array('remove_keyboard' => true)));
  return $keyboard;
}

function keyboard_edit_stat() {
  
  var_dump($keyboard = json_encode($keyboard = ['keyboard' => [['Обновить статистику','Добавить фейков','/admin'],],
  'resize_keyboard' => true,
  'one_time_keyboard' => false,
  'selective' => true]),true);
  return $keyboard;
}

?>