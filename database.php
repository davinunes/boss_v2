<?php

define('DB_HOSTNAME', 'localhost');
define('DB_DATABASE', 'ixcprovedor');
define('DB_USERNAME', 'boss');
define('DB_PASSWORD', 'ilunne');
define('DB_PREFIX', '');
define('DB_CHARSET', 'LATIN1');

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

function get_onu($login){
	$sql = "select o.id_transmissor, o.ponid, o.slotno, o.ponno, o.onu_numero, o.mac, o.id_contrato, o.id_login, u.login, t.ip, t.login, t.senha " ;
	$sql .= "from radpop_radio_cliente_fibra o " ;
	$sql .= "left join radusuarios u on u.id = o.id_login " ;
	$sql .= "left join radpop_radio t on t.id = o.id_transmissor " ;
	$sql .= "where u.login = '$login'" ;
	$sql .= "and t.fabricante_modelo = 'FBT'" ;
	
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0];
}

function dbMsg($termo){ # Lê os Veiculos da Tabela
	
	$sql  =" select "; //
	$sql .=" 	DATE_FORMAT(m.data, '%d-%m-%Y %T') as Data, ";
	$sql .=" 	op.nome as Operador, ";
	$sql .=" 	op.imagem as img, ";
	$sql .=" 	m.mensagem as Mensagem ";
	$sql .=" from ";
	$sql .=" 	su_oss_chamado_mensagem m ";
	$sql .=" left join usuarios op on op.id = m.id_operador";
	$sql .=" left join su_oss_evento ev on ev.id = m.id_evento";
	$sql .=" where ";
	$sql .=" 	id_chamado='$termo' ";

	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		return $dados;
	}
}

function tecnico($termo){ # Lê os Veiculos da Tabela
//	echo $sql;
	
	$sql  ="select ";
	$sql .="	nome, funcionario ";
	$sql .="from ";
	$sql .="	usuarios ";
	$sql .="where ";
	$sql .="	id='$termo' ";

	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		return $dados;
	}
}

function login($termo){ # Lê os Veiculos da Tabela
//	echo $sql;
	
	$sql  ="select ";
	$sql .="	login ";
	$sql .="from ";
	$sql .="	radusuarios ";
	$sql .="where ";
	$sql .="	id_cliente=$termo ";

	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		return $dados;
	}
}

function contrato($login){ # Lê os Veiculos da Tabela
//	echo $sql;
	
	$sql  ="select ";
	$sql .="	id_contrato ";
	$sql .="from ";
	$sql .="	radusuarios ";
	$sql .="where ";
	$sql .="	id=$login ";

	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		return $dados[0][id_contrato];
	}
}


function forcaStatus(){ # Verifica quais clientes tem o Parâmetro Força status ativado, ou seja: não bloqueia o cliente Liberado
//	echo $sql;
	
	$sql  ="select ";
	$sql .="	c.id, cl.razao ";
	$sql .="from ";
	$sql .="	cliente_contrato c ";
	$sql .="left join cliente cl on cl.id = c.id_cliente ";
	$sql .="where ";
	$sql .="	c.liberacao_bloqueio_manual='S' ";
	$sql .="and c.status = 'A'	 ";

	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		return $dados;
	}
}

function atrasados($contrato){ # Verifica quais clientes tem o Parâmetro Força status ativado, ou seja: não bloqueia o cliente Liberado
    $hoje = date('Y-m-d');
	
	$sql  ="select ";
	$sql .="	count(id) as atrasados ";
	$sql .="from ";
	$sql .="	fn_areceber ";
	// $sql .="left join cliente cl on cl.id = c.id_cliente ";
	$sql .="where ";
	$sql .="	id_contrato='$contrato' ";
	$sql .="and data_vencimento < '$hoje'	 ";
	$sql .="and status = 'A'	 ";

	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		return $dados;
	}
}

function dbOs($termo){ # Lê os Veiculos da Tabela
//	echo $sql;
	
	$sql  ="select ";
	$sql .="	os.id as OS, ";
	$sql .="	c.razao as Cliente, ";
	$sql .="	c.id as cliente_id, ";
	$sql .="	ass.assunto as Assunto, ";
	$sql .=" 	op.imagem as img, ";
	$sql .="	os.mensagem as Abertura, ";
	$sql .="	DATE_FORMAT(os.data_agenda,'%d-%m-%Y %H:%i') as Agenda, ";
	$sql .="	tec.funcionario as Tecnico, ";
	$sql .="	os.status, ";
	$sql .="	os.id_login, ";
	$sql .="	os.endereco, os.id_login, ";
	$sql .="	rd.login as login "; //os.id_login
	$sql .="from ";
	$sql .="	su_oss_chamado os ";
	$sql .="left join cliente c on os.id_cliente = c.id ";
	$sql .="left join su_oss_assunto ass on ass.id = os.id_assunto ";
	$sql .="left join funcionarios tec on tec.id = os.id_tecnico ";
	$sql .="left join radusuarios rd on rd.id = os.id_login ";
	$sql .="left join usuarios op on op.funcionario = tec.id ";
	$sql .="where ";
	$sql .="	os.id='$termo' ";

	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		return $dados;
	}
}

function historico($termo){ # Lê os Veiculos da Tabela
//	echo $sql;
	
	$sql  ="select ";
	$sql .="	os.id, ";
	$sql .="	os.id_login ";
	$sql .="from ";
	$sql .="	su_oss_chamado os ";
	$sql .="where ";
	$sql .="	os.id_login='$termo' ";

	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		return $dados;
	}
}

function mensagens($sql){ // itera as linhas da tabela
	$linhas=0;
	foreach($sql as $a){
		$linhas++;
		$a['Operador'] =  mb_convert_case($a['Operador'], MB_CASE_TITLE, "ISO-8859-1");
//		var_dump($a);
		if($a['img'] != ""){
			$r.= "\t<li class='collection-item avatar'>
			\n\t<img src='$a[img]' alt='' class='circle right'>
			\n\t<span class='operador blue-text title'><strong>$a[Operador]</strong> em $a[Data]</span>
			\n\t<p>$a[Mensagem] </p>\n";
		}else{
			$r.="\t<li class='collection-item '>\n";
			$r.="<span class='operador blue-text title'><strong>$a[Operador]</strong> em $a[Data]</span>";
			$r.="<p>$a[Mensagem] </p>\n";
		}

	}

	echo '<ul class="collection">';
	echo $r;
	echo "</ul>";
	echo "<br>";
}

function fecharOs($os, $tec, $msg){

	$dt = date('Y-m-d H:i:s');
	$sql  ="insert into su_oss_chamado_mensagem ";
	$sql .=" (mensagem, id_chamado, id_operador, data, id_evento  ) ";
	$sql .=" values ";
	$sql .=" ('$msg', '$os', '$tec', '$dt', '9') ";
	
//	echo $sql;
	if(DBExecute($sql)){
		echo "ok";
	}else{
		echo "erro";
	}

}

function postarFoto($os, $arquivo, $nome){

	$dt = date('Y-m-d H:i:s');
	$sql  ="insert into su_oss_chamado_arquivos ";
	$sql .=" (id_oss_chamado, descricao, local_arquivo, data_envio, classificacao_arquivo, nome_arquivo  ) ";
	$sql .=" values ";
	$sql .=" ('$os', 'Arquivo postado pelo tecnico no BOSS', '$arquivo', '$dt', 'P', '$nome' ) ";
	
//	echo $sql;
	if(DBExecute($sql)){
		echo "ok";
	}else{
		echo "erro";
	}

}

function avatar($func, $arquivo, $update){
	
	

	$sql  ="update  usuarios ";
	$sql .=" set ";
	$sql .=" imagem = '$arquivo' "; //
	$sql .=" where ";
	$sql .=" id = '$func' ";
	
	// echo $sql;
	if(DBExecute($sql)){
		session_start();
		if($update == "sim"){
			$_SESSION[rosto] = $arquivo;
		}
		
		echo "ok";
	}else{
		echo "erro";
	}

}

function reagendar($os){

	$sql  ="update  su_oss_chamado ";
	$sql .=" set ";
	$sql .=" status = 'AG' "; //
	$sql .=" where ";
	$sql .=" id = $os ";
	
//	echo $sql;
	if(DBExecute($sql)){
		echo "ok";
	}else{
		echo "erro";
	}

}

function transfer($os, $user){

	$dt = date('Y-m-d H:i:s');
	$sql  ="update  su_oss_chamado ";
	$sql .=" set ";
	$sql .=" status = 'EN', "; //
	$sql .=" setor = '3', ";
	$sql .=" id_tecnico = '$user' ";
	$sql .=" where ";
	$sql .=" id = $os ";
	
//	echo $sql;
	if(DBExecute($sql)){
	//	echo "ok";
	}else{
		echo "erro";
	}

}

function agenda($os, $data){
	//2016-12-28 19:28:34
	
	$dt = date('Y-m-d H:i:s');
	$sql  ="update  su_oss_chamado ";
	$sql .=" set ";
	$sql .=" data_agenda = '$data' ";
	$sql .=" where ";
	$sql .=" id = $os ";
	
	if(DBExecute($sql)){
		echo "ok";
	}else{
		echo "erro";
	}

}

function face($os, $func){

	$dt = date('Y-m-d H:i:s');
	$sql  ="update  su_oss_chamado ";
	$sql .=" set ";
	$sql .=" id_tecnico = '$func' ";
	$sql .=" where ";
	$sql .=" id = $os ";
	
	if(DBExecute($sql)){
		echo "ok";
	}else{
		echo "erro";
	}

}

function comenta($os, $user){

	$dt = date('Y-m-d H:i:s');
	$sql  ="update  su_oss_chamado ";
	$sql .=" set ";
	$sql .=" status = 'EN', "; //
	$sql .=" setor = '3', ";
	$sql .=" id_tecnico = '$user' ";
	$sql .=" where ";
	$sql .=" id = $os ";
	
//	echo $sql;
	if(DBExecute($sql)){
	//	echo "ok";
	}else{
		echo "erro";
	}

}

function charset($string){
	// If it's not already UTF-8, convert to it

    $string = mb_convert_encoding($string, 'latin1', 'utf-8');


return $string;
}

function finaliza($os, $user){

	$dt = date('Y-m-d H:i:s');
	$sql  ="update  su_oss_chamado ";
	$sql .=" set ";
	$sql .=" status = 'F', "; //
	$sql .=" setor = '3', ";
	$sql .=" id_tecnico = '$user' ";
	$sql .=" where ";
	$sql .=" id = $os ";
	
//	echo $sql;
	if(DBExecute($sql)){
	//	echo "ok";
	}else{
		echo "erro";
	}

}

function funcionarios(){
	//Selecionar todos os técnicos
	$sql  =" select ";
	$sql .=" 	op.imagem as img, tec.funcionario as Tecnico, tec.id as tec "; //op.imagem as img
	$sql .=" from ";
	$sql .=" 	funcionarios tec ";
	$sql .=" left join usuarios op on op.funcionario = tec.id ";
	$sql .=" where ";
	$sql .=" 	op.status = 'A' ";


	$comentadas	= DBExecute($sql);
	if(mysqli_num_rows($comentadas)){
		while($retorno = mysqli_fetch_assoc($comentadas)){
			$newtec[] = $retorno;
		}
	}
	
	return $newtec;
}

function fatd($data){
	
	$tecnico = $_GET['fatd'] ? $_GET['fatd'] : $_SESSION['func'];
	//Marca a data de hoje e de amanhã pra usar na pesquisa das OS
	$data = date('Y-m-d',strtotime($data));
		
	
	//Conta quantas OS não agendadas para o tecnico atual anterior a data de hoje
	$sql  =" select ";
	$sql .=" 	count(*) as ok"; //op.imagem as img
	$sql .=" from ";
	$sql .=" 	su_oss_chamado os ";
	$sql .=" where ";
	$sql .=" 	os.data_agenda < '$data'";
	$sql .=" 	and (os.status = 'AG' or os.status = 'EX')";
	$sql .=" 	and os.id_tecnico = '$tecnico'";

	$comentadas	= DBExecute($sql);
	if(mysqli_num_rows($comentadas)){
		while($retorno = mysqli_fetch_assoc($comentadas)){
			$mc = $retorno;
		}
	}
	$nokt = $mc[ok];
	
	//Marca se o usuário será admin do boss
	$adm = $_SESSION[grupo] <= 2 ? true : false;
	
	
	//Pesquisa as OS atrasadas do tecnico em questão
	$sql  =" select ";
	$sql .=" 	os.id as OS, "; //op.imagem as img
	$sql .=" 	c.razao as Cliente, ";
	$sql .=" 	op.imagem as img, ";
	$sql .=" 	ass.assunto as Assunto, ";
	$sql .=" 	os.mensagem as Abertura, ";
	$sql .=" 	DATE_FORMAT(os.data_agenda,'%d-%m-%Y %H:%ih') as Agenda, ";
	$sql .=" 	tec.funcionario as Tecnico, ";
	$sql .=" 	os.status, ";
	$sql .=" 	TIME_FORMAT(os.data_agenda, '%H:%i') as horario, ";
	$sql .=" 	rd.login as login "; //os.id_login
	$sql .=" from ";
	$sql .=" 	su_oss_chamado os ";
	$sql .=" left join cliente c on os.id_cliente = c.id ";
	$sql .=" left join su_oss_assunto ass on ass.id = os.id_assunto ";
	$sql .=" left join funcionarios tec on tec.id = os.id_tecnico ";
	$sql .=" left join usuarios op on op.funcionario = tec.id ";
	$sql .=" left join radusuarios rd on rd.id = os.id_login ";
	$sql .=" where ";
	$sql .=" 	os.data_agenda < '$data'";
	$sql .=" 	and os.id_tecnico = '$tecnico'";
	$sql .=" 	and (os.status = 'AG' or os.status = 'EX')";
	$sql .=" order by"; 
	$sql .=" 	os.data_agenda";

	$result	= DBExecute($sql);
	
	if(mysqli_num_rows($result)){
		$abertas = mysqli_num_rows($result);
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	
//	var_dump($_SESSION[LISTA]);
	$linhas = 0;

	// monta a lista
	foreach($dados as $a){
			
		$status = $a[status] == "F" ? "done_all" : "done";
		if($a[status] == "F"){
			$colapso = "colapso";
			$color = "purple accent-1";
		}elseif($a[status] == "EN"){
			$colapso = "colapso";
			$color = " blue lighten-2";
		}else{
			$colapso = "";
			$color = "";
		}
	//	var_dump($a);
		
		if($a[img] != ""){
			$r.= "\t	<a href='os.php?os=$a[OS]&index=$linhas' class='$colapso collection-item avatar $color'>
			\n\t		<img  $edit src='$a[img]' alt='' class='$classe circle' href='#troca$a[OS]'>
			\n\t		<span class='operador blue-text title'><strong>$a[Cliente] | $a[horario]</strong> </span>
			\n\t		<p>$a[OS]: $a[Assunto]</p>
			\n\t		
			\n\t		<div class='chip purple darken-4 white-text secondary-content'>$a[login]</div>
						</a>\n";
			
		}else{
			$r.= "\t	<a  href='os.php?os=$a[OS]&index=$linhas'  $edit  class='$colapso collection-item avatar $color'>
			\n\t		<i  $edit class='$classe material-icons circle' href='#troca$a[OS]'>$status</i>
			\n\t		<span class='operador blue-text title'><strong>$a[Cliente] | $a[horario]</strong> </span>
			\n\t		<p>$a[OS]: $a[Assunto]</p> 
			\n\t		<div class='chip purple darken-4 white-text secondary-content'>$a[login]</div>
						</a>\n";
		}
		$r.="\t		<div class='modal' id='troca$a[OS]'>
						<div class='modal-content'>
							<h4>Alterar Funcionario</h4>
							<p>Clique no novo Funcionario</p>
							$troca
						</div>
					</div>";
		$linhas++;
	}
	echo "<span>OS atrasadas vinculadas ao seu nome($tecnico): $nokt</span>";
	echo '<ul class="collection">';
	echo $r;
	echo "</ul>";

}

function hoje($data){
	
	//Marca a data de hoje e de amanhã pra usar na pesquisa das OS
	$data = date('Y-m-d',strtotime($data));
	$data2 = date('Y-m-d', strtotime('+1 days', strtotime($data)));


	$newtec = funcionarios();
	
	// Conta quantas OS estão encaminhadas ou fechadas
	$sql  =" select ";
	$sql .=" 	count(*) as ok"; //op.imagem as img
	$sql .=" from ";
	$sql .=" 	su_oss_chamado os ";
	$sql .=" where ";
//	$sql .=" 	os.data_agenda >= CURDATE( )";
	$sql .=" 	os.data_agenda >= '$data'";
//	$sql .=" 	and os.data_agenda < (CURDATE( ) + 1)";
	$sql .=" 	and os.data_agenda < '$data2' ";
	$sql .=" 	and (os.status = 'EN' or os.status = 'F')";

	$comentadas	= DBExecute($sql);
	if(mysqli_num_rows($comentadas)){
		while($retorno = mysqli_fetch_assoc($comentadas)){
			$mc = $retorno;
		}
	}
	
	$ok = $mc[ok];
	
	
	//Conta quantas OS não agendadas
	$sql  =" select ";
	$sql .=" 	count(*) as ok"; //op.imagem as img
	$sql .=" from ";
	$sql .=" 	su_oss_chamado os ";
	$sql .=" where ";
//	$sql .=" 	os.data_agenda >= CURDATE( )";
	$sql .=" 	os.data_agenda >= '$data'";
//	$sql .=" 	and os.data_agenda < (CURDATE( ) + 1)";
	$sql .=" 	and os.data_agenda < '$data2'";
	$sql .=" 	and (os.status = 'AG' or os.status = 'EX')";

	$comentadas	= DBExecute($sql);
	if(mysqli_num_rows($comentadas)){
		while($retorno = mysqli_fetch_assoc($comentadas)){
			$mc = $retorno;
		}
	}
	$nok = $mc[ok];
	
	//Marca se o usuário será admin do boss
	$adm = $_SESSION[grupo] <= 2 ? true : false;
	
	
	//Pesquisa as OS de Hoje
	$sql  =" select ";
	$sql .=" 	os.id as OS, "; //op.imagem as img
	$sql .=" 	c.razao as Cliente, ";
	$sql .=" 	op.imagem as img, ";
	$sql .=" 	ass.assunto as Assunto, ";
	$sql .=" 	os.mensagem as Abertura, ";
	$sql .=" 	DATE_FORMAT(os.data_agenda,'%d-%m-%Y %H:%ih') as Agenda, ";
	$sql .=" 	tec.funcionario as Tecnico, ";
	$sql .=" 	os.status, ";
	$sql .=" 	TIME_FORMAT(os.data_agenda, '%H:%i') as horario, ";
	$sql .=" 	rd.login as login "; //os.id_login
	$sql .=" from ";
	$sql .=" 	su_oss_chamado os ";
	$sql .=" left join cliente c on os.id_cliente = c.id ";
	$sql .=" left join su_oss_assunto ass on ass.id = os.id_assunto ";
	$sql .=" left join funcionarios tec on tec.id = os.id_tecnico ";
	$sql .=" left join usuarios op on op.funcionario = tec.id ";
	$sql .=" left join radusuarios rd on rd.id = os.id_login ";
	$sql .=" where ";
//	$sql .=" 	os.data_agenda >= CURDATE( )";
	$sql .=" 	os.data_agenda >= '$data'";
//	$sql .=" 	and os.data_agenda < (CURDATE( ) + 1)";
	$sql .=" 	and os.data_agenda < '$data2'";
	if($adm){
	$sql .=" 	and (os.status = 'AG' or os.status = 'EX' or os.status = 'EN' or os.status = 'F')";
	}else{
	$sql .=" 	and (os.status = 'AG' or os.status = 'EX')";
	}
	$sql .=" order by"; 
	$sql .=" 	os.data_agenda";

	$result	= DBExecute($sql);
	
	if(mysqli_num_rows($result)){
		$abertas = mysqli_num_rows($result);
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	
	//Popula a lista de OS de hoje, usada para o botão de proxima e anterior
	unset($_SESSION[LISTA]);
	foreach($dados as $a){
		$_SESSION[LISTA][] = $a[OS];
	}

//	var_dump($_SESSION[LISTA]);
	$linhas = 0;

	// monta a lista
	foreach($dados as $a){
		
		//Lista de tecnicos pra alterar
		if($adm){
			$troca = ""; //reseta a variavel
			foreach($newtec as $linha){
				$troca .= "\n\t<div class='linkar chip brown lighten-4' os='$a[OS]' func='$linha[tec]'><img src='$linha[img]'>".mb_convert_case($linha[Tecnico], MB_CASE_TITLE, "ISO-8859-1")."</div>\n";
			}
			$classe = " modal-trigger ";
		}
		
		
		
		
		//	var_dump($troca);
		$status = $a[status] == "F" ? "done_all" : "done";
		if($a[status] == "F"){
			$colapso = "colapso";
			$color = "purple accent-1";
		}elseif($a[status] == "EN"){
			$colapso = "colapso";
			$color = " blue lighten-2";
		}else{
			$colapso = "";
			$color = "";
		}
	//	var_dump($a);
		
		if($a[img] != ""){
			if($a[login]){
				$login = "<div class='chip purple darken-4 white-text secondary-content'>$a[login]</div>";
			}else{
				$login = "";
			}
			$r.= "\t	<a href='os.php?os=$a[OS]&index=$linhas' class='$colapso collection-item avatar $color'>
			\n\t		<img  $edit src='$a[img]' alt='' class='$classe circle' href='#troca$a[OS]'>
			\n\t		<span class='operador blue-text title'><strong>$a[Cliente] | $a[horario]</strong> </span>
			\n\t		<p>$a[OS]: $a[Assunto]</p>
			\n\t		
			\n\t		$login
						</a>\n";
			
		}else{
			$r.= "\t	<a  href='os.php?os=$a[OS]&index=$linhas'  $edit  class='$colapso collection-item avatar $color'>
			\n\t		<i  $edit class='$classe material-icons circle' href='#troca$a[OS]'>$status</i>
			\n\t		<span class='operador blue-text title'><strong>$a[Cliente] | $a[horario]</strong> </span>
			\n\t		<p>$a[OS]: $a[Assunto]</p> 
			\n\t		$login
						</a>\n";
		}
		$r.="\t		<div class='modal' id='troca$a[OS]'>
						<div class='modal-content'>
							<h4>Alterar Funcionario</h4>
							<p>Clique no novo Funcionario</p>
							$troca
						</div>
					</div>";
		$linhas++;
	}
	echo "<span>Comentadas: $ok | Pendentes: $nok</span>";
	echo '<ul class="collection">';
	echo $r;
	echo "</ul>";

}

if($_POST['metodo'] == "mudaTec"){


	if($_POST[func]){
	$func = filter_input(INPUT_POST, 'func', FILTER_SANITIZE_STRING);
	}
	if($_POST[os]){
	$os = filter_input(INPUT_POST, 'os', FILTER_SANITIZE_STRING);
	}

	face($os, $func);
}

if($_POST["metodo"] == "agenda"){


	if($_POST[data]){
	$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
	}
	if($_POST[os]){
	$os = filter_input(INPUT_POST, 'os', FILTER_SANITIZE_STRING);
	}

	agenda($os, $data);
}

if($_POST['metodo'] == "close"){
	if($_POST[msg]){
	$msg = charset(filter_input(INPUT_POST, 'msg', FILTER_SANITIZE_STRING));
	}
	if($_POST[tec]){
	$tec = filter_input(INPUT_POST, 'tec', FILTER_SANITIZE_STRING);
	}
	if($_POST[func]){
	$func = filter_input(INPUT_POST, 'func', FILTER_SANITIZE_STRING);
	}
	if($_POST[os]){
	$os = filter_input(INPUT_POST, 'os', FILTER_SANITIZE_STRING);
	}
	if($_POST[enc]){
	$enc = filter_input(INPUT_POST, 'enc', FILTER_SANITIZE_STRING);
	}
	
	if($enc == "sim"){
		transfer($os, $func);
	}elseif($enc == "fechar"){
		finaliza($os, $func);
	}
	
	if($msg and $tec and $os){
	fecharOs($os, $tec, $msg);
	}
}

if($_POST['metodo'] == "save"){
	if($_POST[msg]){
	$msg = $_POST[msg];
	}
	if($_POST[tec]){
	$tec = filter_input(INPUT_POST, 'tec', FILTER_SANITIZE_STRING);
	}
	if($_POST[func]){
	$func = filter_input(INPUT_POST, 'func', FILTER_SANITIZE_STRING);
	}
	if($_POST[os]){
	$os = filter_input(INPUT_POST, 'os', FILTER_SANITIZE_STRING);
	}
	if($_POST[enc]){
	$enc = filter_input(INPUT_POST, 'enc', FILTER_SANITIZE_STRING);
	}
	
	if($enc == "sim"){
		transfer($os, $func);
	}elseif($enc == "fechar"){
		finaliza($os, $func);
	}
	
	if($msg and $tec and $os){
	fecharOs($os, $tec, $msg);
	}
}

if($_POST['metodo'] == "reabrir"){
	
	if($_POST[os]){
	$os = filter_input(INPUT_POST, 'os', FILTER_SANITIZE_STRING);
	}

	reagendar($os);
}

if($_POST['metodo'] == "girar"){
	
	$filename = filter_input(INPUT_POST, 'filename', FILTER_SANITIZE_STRING);
//	var_dump($filename);
	$target = imagecreatefromjpeg($filename);
//	var_dump($target);
	$target = imagerotate($target, 90, 0);
	imagejpeg($target, $filename);
	imagedestroy($target);
	$target = null;
}

if($_GET['data']=="data"){
	session_start();
		echo "<pre>";
	$dt = date('Y-m-d H:i:s');
	echo date_default_timezone_get();
	echo "<br>";
	echo $dt;
	echo "<br>";
	$cache_limiter = session_cache_limiter();
	$cache_expire = session_cache_expire();
	echo "O limitador de cache está definido agora como $cache_limiter<br />"; 
echo "As sessões em cache irão expirar depois de $cache_expire minutos";
	echo session_cache_expire();
	var_dump($_SESSION);
}

if($_GET['metodo'] == "pesquisar_onu"){
	

	echo json_encode(get_onu($_GET[login]));

}

if($_GET['metodo'] == "forcaStatus"){
	echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">';
	echo "<div class='container'><table class='highlight '>";
	echo "<tr><th>Indice</th><th>Nome</th><th>Contrato</th><th>Atrasados</th></tr>";
	$i = 1;
	foreach(forcaStatus() as $a){
		$tulipa = atrasados($a[id]);
		$tulipa = $tulipa[0][atrasados];
		if($tulipa == 0){
			continue;
		}
		if($tulipa > 2){
			if($tulipa > 5){
				$classe =  " pink lighten-4";
			}else{
				$classe =  "blue-grey lighten-5";
			}
		}else{
			$classe =  "";
		}
		
		// var_dump($tulipa);
		echo "<tr class='$classe'><td>$i</td><td>$a[razao]</td><td>$a[id]</td><td>$tulipa</td></tr>";
		$i++;
	}
	echo "</table></div>";
}

if($_POST['metodo'] == "pesquisar_login"){
	
	if($_POST[palavra]){
	$palavra = filter_input(INPUT_POST, 'palavra', FILTER_SANITIZE_STRING);
	}
	
	
	$sql = "select l.id, l.login, c.razao from radusuarios l left join cliente c on l.id_cliente = c.id   where l.login like '%$palavra%'" ;
	// $sql = "select * from radusuarios where id='4264' limit 10";
	
	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	
	foreach($dados as $k => $v){
	// echo "hop\n";
	$h="\n<thead>\n"; // Iniciar o cabeçalho do código
	$h .= "\t<tr>\n";
	$r .= "\t<tr>\n"; // Inicia Gerar as linhas da tabela
	$e = "<a class='escolher_login modal-close material-icons small' ";

	foreach($v as $a => $b){
		// var_dump($a);
		$h .= "\t\t<th>$a</th>\n";		// itera o cabeçalho
		$r .= "\t\t\t<td title='$a'>$b</td>\n";		// itera as linhas
		$e .=  "$a='$b' "; // itera os botões

	}
	$e .= ">playlist_add_check</a>";

	$r .= "\t\t\t<td>$e $d</td>\n";		// itera as linhas
	$r .= "\t</tr>\n"; // Inicia Gerar as linhas da tabela
	
	$h .= "\t</tr>";
	$h.="\n</thead>\n"; // Fecha o Cabeçalho

}

echo "\n<table>\n";
echo $h;
echo $r;
echo "\n</table>\n";
	
}

if($_POST['metodo'] == "gs_crud"){
	
	$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
	$attribute = "ERX-Service-Activate:1";
	$op = '=';
	$value = $_POST[value];
	$id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_SANITIZE_STRING);
	$ultima_atualizacao = date('Y-m-d H:i:s');
	$crud = filter_input(INPUT_POST, 'crud', FILTER_SANITIZE_STRING);
	
	if($crud == "enviar"){
		$sql  ="insert into radreply ";
		$sql .=" (username, attribute, op, value, id_usuario, ultima_atualizacao  ) ";
		$sql .=" values ";
		$sql .=" ('$username', '$attribute', '$op', '$value', '$id_usuario', '$ultima_atualizacao' ) ";
	}elseif($crud == "atualizar"){
		$sql  ="update  radreply ";
		$sql .=" set ";
		$sql .=" username = '$username', "; //
		$sql .=" attribute = '$attribute', ";
		$sql .=" value = '$value', ";
		$sql .=" id_usuario = '$id_usuario', ";
		$sql .=" ultima_atualizacao = '$ultima_atualizacao' ";
		$sql .=" where ";
		$sql .=" id = '$id' ";
	}elseif($crud == "remover"){
		$sql  ="delete from radreply where id = '$id'";
	}
//	echo $sql;
	
//	echo $sql;
	if(DBExecute($sql)){
		echo "ok";
	}else{
		echo "erro";
	}
	
	
}

?>