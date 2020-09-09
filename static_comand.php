<?php 
////////////////////////////////////////////////////////////
//Парсер по сообщению для поиска реферальной ссылки
function parseReferal($command)
{
  $referal = explode("/start ", $command)[1];
  return $referal;
}

////////////////////////////////////////////////////////////
//стандартный функционал который присудствует в каждом боте
function valid() {
	$request_from_telegram = false;
	
if(isset($_POST)) {
		$data = file_get_contents("php://input");

		if (json_decode($data) != null)
			
$request_from_telegram = json_decode($data,1);
	}
	
return $request_from_telegram;
}

////////////////////////////////////////////////////////////
//Отправка сообщений
function sendMessage($chat_id,$text,$markup=null)
{
	$url = $GLOBALS['url'].'sendMessage?chat_id='.$chat_id.'&text='.urlencode($text).'&reply_markup='.$markup.'&parse_mode=Markdown';
	$url .= '&disable_web_page_preview=true';
      return get($url); 
}

////////////////////////////////////////////////////////////
//Отправка IMAGE
 function sendPhoto($chat_id,$url_img) {
 
  
  return get($GLOBALS['url'].'sendPhoto?chat_id='.$chat_id.'&photo='.$url_img.'&enctype=multipart/form-data');
  
}

////////////////////////////////////////////////////////////
//GET запрос
function get($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
curl_setopt($ch, CURLOPT_HEADER, 0);
	
$data = curl_exec($ch);
	
curl_close($ch);
	
return $data;
}

?>