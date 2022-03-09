<?php
include "../database.php";

function telegram($msg) {
		$telegrambot='1573595662:AAHnG-8LiM9ipKGZ_cd64YBuhaZZkPG33-0';
		$telegramchatid=-374877714;
        
        $url='https://api.telegram.org/bot'.$telegrambot.'/sendMessage';$data=array('chat_id'=>$telegramchatid,'text'=>$msg,'parse_mode'=>'MarkdownV2');
        $options=array('http'=>array('method'=>'POST','header'=>"Content-Type:application/x-www-form-urlencoded\r\n",'content'=>http_build_query($data),),);
        $context=stream_context_create($options);
        $result=file_get_contents($url,false,$context);
        return $result;
	}

function check_onu($login){
	$sql = "select o.id, o.id_transmissor, o.id_perfil, o.vlan, o.onu_numero, o.ponid, o.slotno, o.ponno, o.onu_numero, o.mac, o.id_contrato, o.id_login, u.login, t.ip " ;
	$sql .= "from radpop_radio_cliente_fibra o " ;
	$sql .= "left join radusuarios u on u.id = o.id_login " ;
	$sql .= "left join radpop_radio t on t.id = o.id_transmissor " ;
	$sql .= "where o.mac = '$login'" ;
	$sql .= "and t.fabricante_modelo = 'FBT'" ;
	
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){
		return false;
	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0];
}

function check_precontrato($cid){
	$sql = "select status " ;
	$sql .= "from cliente_contrato " ;
	$sql .= "where id = '$cid'" ;
	
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){
		return false;
	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0][status];
}

function ativa_contrato($cid){
	$hoje = date("Y-m-d");
	$sql = "update cliente_contrato	" ;
	$sql .= " set status = 'A', status_internet = 'A', data = '$hoje', data_ativacao = '$hoje' ";
	$sql .= "where id = '$cid'" ;
	
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){
		return false;
	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados[0][status];
}



function delete_onu($onu){
	$sql = "delete " ;
	$sql .= "from radpop_radio_cliente_fibra " ;
	$sql .= "where id = '$onu'" ;
	
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){
		return false;
	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados;
}

function check_onu_por_login($login){
	$sql = "select o.id, o.id_transmissor, o.id_perfil, o.vlan, o.onu_numero, o.ponid, o.slotno, o.ponno, o.onu_numero, o.mac, o.id_contrato, o.id_login, u.login, t.ip " ;
	$sql .= "from radpop_radio_cliente_fibra o " ;
	$sql .= "left join radusuarios u on u.id = o.id_login " ;
	$sql .= "left join radpop_radio t on t.id = o.id_transmissor " ;
	$sql .= "where o.id_login = '$login'" ;
	$sql .= "and t.fabricante_modelo = 'FBT'" ;
	
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){
		return false;
	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	return $dados;
}

if($_GET['metodo'] == "crud_onu"){
	
	$sql  ="insert into radpop_radio_cliente_fibra ";
	$sql .=" (nome, onu_tipo, id_transmissor, vlan, id_perfil, onu_numero, ponid, slotno, ponno, mac, id_contrato, id_login  ) ";
	$sql .=" values ";
	$sql .="('$_POST[nome]', '$_POST[tipo]', '$_POST[transmissor]', '$_POST[vlan]', '$_POST[perfil]', '0', '0-0-$_POST[slot]-$_POST[pon]', '$_POST[slot]', '$_POST[pon]', '$_POST[mac]', '$_POST[contrato]', '$_POST[login]') ";
	
	// var_dump($sql);
	$result	= DBExecute($sql);
	echo "$result";
	
}

if($_GET['metodo'] == "att_num_onu"){
	
	
	
	$sql  ="update radpop_radio_cliente_fibra ";
	$sql .=" set onu_numero = '$_GET[numero]' ";
	$sql .=" where ";
	$sql .=" mac = '$_GET[mac]'";
	
	// var_dump($sql);
	$result	= DBExecute($sql);
	echo "$result";
	
}

if($_GET['metodo'] == "soautoriza"){
	
	// host mac slot pon topo
	$command = '/usr/bin/python /var/www/ilunne/boss/py/soautoriza.py '.$_POST[olt].' '.$_POST[mac].' '.$_POST[slot].' '.$_POST[pon].' '.$_POST[tipo];
	$output = shell_exec($command);
	
	// cd .
	$output = explode("cd .", $output);
	$output = explode("ONU: ", $output[0]);
	$output = explode(" ", $output[1]);
	$output = explode("-", $output[0]);
	
	echo $output[2];
}

if($_GET['metodo'] == "configurabridge"){
	
	// host mac slot pon topo
	$command = '/usr/bin/python /var/www/ilunne/boss/py/configurabridge.py '.$_POST[olt].' '.$_POST[onu_num].' '.$_POST[slot].' '.$_POST[pon].' '.$_POST[vlan];
	$output = shell_exec($command);
	
	// cd .
	$output = explode("cd .", $output);
	$output = explode("cd lan", $output[0]);

	
	echo $output[1];
}

// fecharOs($os, $tec, $msg)

if($_GET['metodo'] == "log"){
	
	fecharOs($_POST[os], $_POST[tec], $_POST[tabela]);
	
	// var_dump($sql);
	$result	= DBExecute($sql);
	echo "$result";
	
}

if($_GET['metodo'] == "telegram"){
	
	$a = telegram($_GET[mensagem]);

	echo $a;
}

if($_GET['metodo'] == "pre"){
	
	$a = check_precontrato($_GET[id]);
	if($a == "P"){
		require('api.php');
		$host = 'https://acessodf.net/webservice/v1';
		$token = '6:e6a38134ca5be7e571282d7cca2e0eb9420b32b152aa701e6ad5127306535277';//token gerado no cadastro do usuario (verificar permissões)
		$selfSigned = false; //true para certificado auto assinado
		$api = new IXCsoft\WebserviceClient($host, $token, $selfSigned);
		$params = array(
			'qtype' => 'cliente_contrato_ativar_cliente.id',
			'id_contrato' => $_GET[id]
		);
		// var_dump($params);
		$api->get('cliente_contrato_ativar_cliente', $params);
		$retorno = $api->getRespostaConteudo(true);// false para json | true para array
		echo "<span><p class='red-text text-darken-2'><b>".$retorno[message]."</b></p></span>";
		
	}
}
if($_GET['metodo'] == "historico"){
	

		require('api.php');
		$host = 'https://acessodf.net/webservice/v1';
		$token = '6:e6a38134ca5be7e571282d7cca2e0eb9420b32b152aa701e6ad5127306535277';//token gerado no cadastro do usuario (verificar permissões)
		$selfSigned = false; //true para certificado auto assinado
		$api = new IXCsoft\WebserviceClient($host, $token, $selfSigned);
		$params = array(
    'qtype' => 'radacct.username',//campo de filtro
    'query' => 'luciano_fontes',//valor para consultar
    'oper' => '=',//operador da consulta
    'page' => '1',//página a ser mostrada
    'rp' => '30',//quantidade de registros por página
    'sortname' => 'radacct.radacctid',//campo para ordenar a consulta
    'sortorder' => 'desc'//ordenação (asc= crescente | desc=decrescente)
);
		// var_dump($params);
		$api->get('radacct', $params);
		$retorno = $api->getRespostaConteudo(true);// false para json | true para array
		echo '<table>';
		foreach($retorno[registros] as $r){
			$Dias = ;
			$Horas = (int)($r[acctsessiontime]/60);
			$Minutos = ;
			$Segundos = ;
			echo "<tr>
					<td>$r[callingstationid]</td>
					<td>$r[framedipaddress]</td>
					<td>$r[framedipv6prefix]</td>
					<td>$r[acctstarttime]</td>
					<td>$r[acctstoptime]</td>
					<td>$tempo</td>
					<td>$r[acctinputoctets]</td>
					<td>$r[acctoutputoctets]</td>
				</tr>";
		}
		echo '</table>';

}

if($_POST['metodo'] == "pesca_login"){
	
	if($_POST[palavra]){
	$palavra = filter_input(INPUT_POST, 'palavra', FILTER_SANITIZE_STRING);
	}
	
	
	$sql = "select l.id, l.login, l.id_contrato, c.razao from radusuarios l left join cliente c on l.id_cliente = c.id   where l.login like '%$palavra%'" ;
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
	$e = "<a class='escolher_login material-icons' ";

	foreach($v as $a => $b){
		// var_dump($a);
		$h .= "\t\t<th>$a</th>\n";		// itera o cabeçalho
		$r .= "\t\t\t<td title='$a'>$b</td>\n";		// itera as linhas
		$e .=  "$a='$b' "; // itera os botões

	}
	$h .= "\t\t<th>Selecionar</th>\n";
	$e .= ">playlist_add_check</a>";

	$r .= "\t\t\t<td>$e $d</td>\n";		// itera as linhas
	$r .= "\t</tr>\n"; // Inicia Gerar as linhas da tabela
	
	$h .= "\t</tr>";
	$h.="\n</thead>\n"; // Fecha o Cabeçalho

}

echo "\n<table class='responsive-table'>\n";
echo $h;
echo $r;
echo "\n</table>\n";
	
}


?>