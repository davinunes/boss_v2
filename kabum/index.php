<?php

include "/var/www/ilunne/boss/kabum/database.php";

function kabum($item){
	$url = file_get_contents("https://www.kabum.com.br/cgi-local/site/produtos/descricao.cgi?codigo=$item");
	preg_match_all('/<meta itemprop="price" content="(.+)"><strong>/m', $url, $conteudo);

	$valor = $conteudo[1][0];
	return $valor;
}

function telegram($msg){
	$token="1293778477:AAFbad-7ZlhwwiiHp9oO4SeTv1aEsa0EjPI";
	$chat="467782812";
	$site="https://api.telegram.org/bot$token/sendMessage?parse_mode=HTMP&chat_id=$chat&text=$msg";
	$url = file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat&text=$msg");
	echo $site;
}


foreach(lista() as $k){
	$item = $k['item'];
	$valorAgora = kabum($k['item']);
	if($valorAgora != $k['atual']){
		var_dump($valorAgora);
		update($k['item'], $valorAgora, $k['atual']);
		$msg = "Valor alterado: $valorAgora %0A%0A https://www.kabum.com.br/produto/$item ";
		
		telegram($msg);
	}else{
		$msg = "Nada mudou! %0A%0A https://www.kabum.com.br/produto/$item";
		// telegram($msg);
	}
	
}

// echo kabum("102932");
// echo "<br>";
// echo kabum("96480");



?>