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

function DBEscape($dados){ # Proteje contra SQL Injection
	$link = DBConnect();
	
	if(!is_array($dados)){
		$dados = mysqli_real_escape_string($link,$dados);
	}else{
		$arr = $dados;
		foreach($arr as $key => $value){
			$key	= mysqli_real_escape_string($link, $key);
			$value	= mysqli_real_escape_string($link, $value);
			
			$dados[$key] = $value;
		}
	}
	DBClose($link);
	return $dados;
}

function DBExecute($query){ # Executa um Comando na Conexão
	$link = DBConnect();
	$result = mysqli_query($link,$query) or die(mysqli_error($link));
	
	DBClose($link);
	return $result;
}

function hoje($today){
	// $today = date("Y-m-d");
	$sql = "select id, id_carteira " ;
	$sql .= "from fn_areceber_cedente_remessaArquivo " ;
	$sql .= "where DATE_FORMAT(data, '%Y-%m-%d') = '$today' " ;
	$sql .= "order by  id_carteira desc" ;
	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados;
}

function hojeret($today){
	// $today = date("Y-m-d");
	$sql = "select id, id_carteira_cobranca " ;
	$sql .= "from fn_areceber_cedente_lote " ;
	$sql .= "where DATE_FORMAT(momento_ini_processo, '%Y-%m-%d') = '$today' " ;
	$sql .= "order by  id desc" ;
	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados;
}

function qe($remessa){
	$today = date("Y-m-d");
	$sql = "select count(id_receber) as qde " ;
	$sql .= "from fn_areceber_remessas " ;
	$sql .= "where id_remessa = '$remessa' " ;
	$sql .= "and tipo_remessa = '01'" ;
	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0]['qde'];
}

function qb($remessa){
	$today = date("Y-m-d");
	$sql = "select count(id_receber) as qde " ;
	$sql .= "from fn_areceber_remessas " ;
	$sql .= "where id_remessa = '$remessa' " ;
	$sql .= "and tipo_remessa = '02'" ;
	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0]['qde'];
}

function uRem($remessa){

	$sql = "select id_receber, tipo_remessa " ;
	$sql .= "from fn_areceber_remessas " ;
	$sql .= "where id_remessa = $remessa " ;
	$sql .= "order by  tipo_remessa asc, id_receber asc" ;

	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados;
}

function eRet($nn){ // Busca boletos com erro
	// JC = Titulo estava cancelado
	// JR = Ja recebido
	// $sql  = " select id_cobranca, nosso_numero, data_vencimento, motivo,cod_mov, status, id_lote_retorno, valor_pago,  " ;
	$sql  = " select * " ;
	$sql .= " from fn_areceber_cedente " ;
	$sql .= " where (status = 'JC' " ;
	$sql .= " or status = 'JR' or status = 'E') " ;
	$sql .= " and id_lote_retorno = '$nn' " ;

	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados;
}

function uRet($boleto){

	$sql  = " select nosso_numero, cod_mov, id_lote_retorno" ;
	$sql .= " from fn_areceber_cedente " ;
	$sql .= " where id_cobranca = $boleto " ;
	$sql .= " and cod_mov = 'Entrada Confirmada' " ;

	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados;
}

function bRet($boleto){

	$sql  = " select nosso_numero, cod_mov, id_lote_retorno" ;
	$sql .= " from fn_areceber_cedente " ;
	$sql .= " where id_cobranca = $boleto " ;
	$sql .= " and cod_mov = 'Baixa' " ;

	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados;
}

function bp($boleto){

	$sql  = " select status " ;
	$sql .= " from fn_areceber " ;
	$sql .= " where id = $boleto " ;

	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0][status];
}

function nome($boleto){

	$sql  = " select c.razao " ;
	$sql .= " from fn_areceber r " ;
	$sql .= " left join cliente c " ;
	$sql .= " on r.id_cliente = c.id " ;
	$sql .= " where r.id = $boleto " ;

	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0][razao];
}

function pnome($boleto){

	$sql  = " select c.razao, r.id_contrato, r.id, r.valor, r.obs, r.data_vencimento " ;
	$sql .= " from fn_areceber r " ;
	$sql .= " left join cliente c " ;
	$sql .= " on r.id_cliente = c.id " ;
	$sql .= " where c.razao like '%$boleto%' " ;
	$sql .= " and r.status = 'A' " ;
	$sql .= " order by c.razao asc, r.id_contrato desc, r.data_vencimento asc " ;

	
	// echo $sql;
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados;
}

// SELECT * FROM `fn_areceber_cedente` ORDER BY `fn_areceber_cedente`.`id_lote_retorno` DESC
?>