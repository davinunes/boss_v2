<?php


include "../key.php";

function DBConnect(){ # Abre Conexão com Database
	$link = @mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());
	mysqli_set_charset($link, DB_CHARSET) or die(mysqli_error($link));
	return $link;
}

function DBClose($link){ # Fecha Conexão com Database
	@mysqli_close($link) or die(mysqli_error($link));
}

function DBExecute($query){ # Executa um Comando na Conexão
	$link = DBConnect();
	$result = mysqli_query($link,$query) or die(mysqli_error($link));
	
	DBClose($link);
	return $result;
}


function lista(){ 
	
	$sql  ="select * ";
	$sql .="from ";
	$sql .="	pesquisa ";

	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		return $dados;
	}
}

function update($item, $atual, $anterior){ 
	
	$sql  =" update pesquisa ";
	$sql .=" set ";
	$sql .=" atual = '$atual', "; //
	$sql .=" anterior = '$anterior' "; //
	$sql .=" where ";
	$sql .=" item = $item ";

	$result	= DBExecute($sql);

}



?>