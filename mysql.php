<?php


  
////////////////////////////////////////////////////////////
//Коннект в БД
function connect()
  
{
  $host = 'localhost';
    $db   = 'h96674mf_id13464'; // Имя БД
    $user = 'Matches DB name';  // Имя пользователя БД
    $pass = 'Wolf201026'; // Пароль БД
    $charset = 'utf8';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
  try {
    $pdo = new PDO($dsn, $user, $pass, $opt);
  
    return $pdo;
} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
    return false;
   // die('Подключение не удалось: ' . $e->getMessage());
}
}

////////////////////////////////////////////////////////////
//Сохранение рефералов
function RefSave($uid, $refs, $first_name, $username)
{
  
  $referals = 0;
  $pdo = connect();
  if ($pdo)
  {
    
      $stmt = $pdo->prepare('SELECT owner FROM ref WHERE referals = ?');
			$stmt->execute([$uid]);
      $referals = $stmt->rowCount();     
      if ($referals == 0)
      {
        $data = array("owner" => $refs,"referals" => $uid,
                      "first_name" => $first_name, "username" => $username,
                        "date_begin" => gmdate("d.m.Y H:i:s", time()+ ( 3 * 60 * 60 )));
        $st = $pdo->prepare("INSERT INTO  ref (owner, referals, first_name, username, date_begin) 
          VALUES(:owner, :referals, :first_name, :username, :date_begin)");
        $st->execute($data);
      }
      $stmt = $pdo->prepare('SELECT referals FROM ref WHERE owner = ?');
			$stmt->execute([$uid]);
      $referals = $stmt->rowCount();  
  }
  return $referals;
}
////////////////////////////////////////////////////////////
//Извлечение рефералов
function getReferal($uid)
{
  $referals = 0;
  
  $pdo = connect();
  if ($pdo)
  {
      $stmt = $pdo->prepare('SELECT * FROM ref WHERE owner = :owner and referals <> :id');
			$stmt->execute(array("owner" => $uid, "id" => $uid));
      $referals = $stmt->rowCount();  
  }
  return $referals;
}

////////////////////////////////////////////////////////////
//Извлечение всех пользователей
function getUsersAll()
{
  $real = 0;
  
  $pdo = connect();
  if ($pdo)
  {
      $stmt = $pdo->prepare('SELECT id FROM users');
			$stmt->execute();
      $real = $stmt->rowCount();  
  }
  return $real;
}

////////////////////////////////////////////////////////////
//Обновление таблицы пользователей
function updateColumns($data,$uid)
{
		$pdo = connect();
    foreach ($data as $key => $value)
    {      
			
      if ($value!=null && $value!="null" && $value!="")
      {
      
        $val = array(
          "value" => $value,
          "uid" => $uid          
        );
				$st = $pdo->prepare("UPDATE users  set {$key} = :value where id = :uid;");
				$st->execute($val);				 
      }
    }
	return true;
}


////////////////////////////////////////////////////////////
//Обновление таблицы склада пользователей
function updateColumnsStoce($data,$uid)
{
		$pdo = connect();
    foreach ($data as $key => $value)
    {      
			
      if ($value!=null && $value!="null" && $value!="")
      {
      
        $val = array(
          "value" => $value,
          "uid" => $uid          
        );
				$st = $pdo->prepare("UPDATE stoce  set {$key} = :value where id = :uid;");
				$st->execute($val);				 
      }
    }
	return true;
}

////////////////////////////////////////////////////////////
//Обновление таблицы статистики
function updateColumnsStatistic($data,$uid)
{
		$pdo = connect();
    foreach ($data as $key => $value)
    {      
			
      if ($value!=null && $value!="null" && $value!="")
      {
      
        $val = array(
          "value" => $value,
          "uid" => $uid          
        );
				$st = $pdo->prepare("UPDATE statistic  set {$key} = :value where id = :uid;");
				$st->execute($val);				 
      }
    }
	return true;
}


////////////////////////////////////////////////////////////
//Добавление нового AD
function addAD($message)
{
	  
		$pdo = connect();
		if ($pdo)
		{
				$data = array("message" => $message);
				$st = $pdo->prepare("INSERT INTO  ad (message) VALUES(:message)");
				$st->execute($data);			
		} 
}


////////////////////////////////////////////////////////////
//Поиск AD
function ADget($uid)
{
	$pdo = connect();
		if ($pdo)
		{
			$stmt = $pdo->prepare('SELECT message FROM ad WHERE id = ?');
			$stmt->execute([$uid]);
			$stat = [];
			foreach ($stmt as $row)
				{
						$stat['message'] = $row['message'];		
				}
			return $stat;
		}
	return false;
}


////////////////////////////////////////////////////////////
//Извлечение кол-во AD
function getADAll()
{
  $ad = 0;
  
  $pdo = connect();
  if ($pdo)
  {
      $stmt = $pdo->prepare('SELECT id FROM ad');
			$stmt->execute();
      $ad = $stmt->rowCount();  
  }
  return $ad;
}


////////////////////////////////////////////////////////////
//Сохранение сообщений юзера
function MessageSave($data)
{
		$pdo = connect();
		if ($pdo)
		{

			$st = $pdo->prepare("INSERT INTO  messages (user_id, username, first_name, message_id, text, date,ndate)
				VALUES (:user_id, :username, :first_name, :message_id, :text, :date, :ndate)");
			$st->execute($data);				 

			return true;
			
		}
	else{ return false;}
}

////////////////////////////////////////////////////////////
//Добавление нового пользователя
function addUser($uid,$username,$first_name)
{
	  
		$pdo = connect();
		if ($pdo)
		{
			
			//users
			$stmt = $pdo->prepare('SELECT id FROM users WHERE id = ?');
			$stmt->execute([$uid]);

			if ($stmt->rowCount() == 0) // юзера нет - пишем
			{
		
					$data = array("id" => $uid,"username" => $username,"first_name" => $first_name,
                        "date_begin" => gmdate("d.m.Y H:i:s", time()+ ( 3 * 60 * 60 )));
					$st = $pdo->prepare("INSERT INTO  users (id, username, first_name,status, date_begin, reputation) VALUES(:id, :username, :first_name,0, :date_begin, 0)");
					$st->execute($data);
//          account_init($uid);// Инициализация баланса
			}
		} 
}
////////////////////////////////////////////////////////////
//Добавление склада пользователя
function addStoceUser($uid)
{
	  
		$pdo = connect();
		if ($pdo)
		{
			
			//stoce
			$stmt = $pdo->prepare('SELECT id FROM stoce WHERE id = ?');
			$stmt->execute([$uid]);

			if ($stmt->rowCount() == 0) // склада нет - пишем
			{
		
					$data = array("id" => $uid);
					$st = $pdo->prepare("INSERT INTO  stoce (id, provision, gun,food, water, health) VALUES(:id,2,0,0,0,0)");
					$st->execute($data);
//          account_init($uid);// Инициализация баланса
			}
		} 
}
////////////////////////////////////////////////////////////
//Поиск пользователя
function userget($uid)
{
	$pdo = connect();
		if ($pdo)
		{
			$stmt = $pdo->prepare('SELECT username, first_name, status, reputation FROM users WHERE id = ?');
			$stmt->execute([$uid]);
			$stat = [];
			foreach ($stmt as $row)
				{
						$stat['username'] = $row['username'];
						$stat['first_name'] = $row['first_name'];
						$stat['status'] = $row['status'];
						$stat['reputation'] = $row['reputation'];
				}
			return $stat;
		}
	return false;
}
////////////////////////////////////////////////////////////
//Вывод статистики
function statisticget($uid)
{
	$pdo = connect();
		if ($pdo)
		{
			$stmt = $pdo->prepare('SELECT fakeuser, realuser, alluser, online FROM statistic WHERE id = ?');
			$stmt->execute([$uid]);
			$stat = [];
			foreach ($stmt as $row)
				{
						$stat['fakeuser'] = $row['fakeuser'];
						$stat['realuser'] = $row['realuser'];
						$stat['alluser'] = $row['alluser'];
						$stat['online'] = $row['online'];
				}
			return $stat;
		}
	return false;
}

////////////////////////////////////////////////////////////
//Поиск склада пользователя
function stoceuserget($uid)
{
	$pdo = connect();
		if ($pdo)
		{
			$stmt = $pdo->prepare('SELECT provision, gun, food, water, health FROM stoce WHERE id = ?');
			$stmt->execute([$uid]);
			$stat = [];
			foreach ($stmt as $row)
				{
						$stat['provision'] = $row['provision'];
						$stat['gun'] = $row['gun'];
						$stat['food'] = $row['food'];
						$stat['water'] = $row['water'];
						$stat['health'] = $row['health'];
				}
			return $stat;
		}
	return false;
}
////////////////////////////////////////////////////////////
//Сбор id всех пользователей
function getUsers($table) {
  $i = 0;
  $pdo = connect();
		if ($pdo)
		{
		$stmt = $pdo->prepare("SELECT distinct(id) FROM {$table}");
    $stmt->execute();
    foreach ($stmt as $row) {
          $arr[$i] = $row['id'];
          $i ++; 
    }  
    return $arr;
		}
}



?>