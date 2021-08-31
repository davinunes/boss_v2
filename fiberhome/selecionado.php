<?php


$olt = $_GET[olt];
$nome_olt = $_GET[nome_olt];
$slot = $_GET[slot];
$pon = $_GET[pon];
$mac = $_GET[mac];
$transmissor = $_GET[transmissor];
$vlan = $_GET[vlan];
$tipo = $_GET[tipo];

	session_start();
	$os = $_SESSION[os];
	$tec = $_SESSION[tec];
	$func = $_SESSION[func];

include "database.php";
$jacadastrada = check_onu($mac);

if($os){
	$dados_da_os = dbOs($os);
	// echo '<pre>';
	// var_dump($_SESSION);
	// echo '</pre>';
}
if($dados_da_os[0][id_login]){
	
	$id_login = $dados_da_os[0][id_login];
	$contrato = contrato($id_login);
	$nome = $dados_da_os[0][login];
	$onts = check_onu_por_login($id_login);
}
if($id_login and $nome){
	$pesquisa = "none";
	$confirmar = "block";
}else{
	$pesquisa = "block";
	$confirmar = "none";
}

echo "<div class='card-title'>Formulário para autorização da ONU <b>$mac</b> no Slot <b>$slot</b> PON <b>$pon</b> </div>";
echo"
	<span id='tabela_cliente'>
	<table class='responsive-table'>
		<caption>Dados para Provisionamento</caption>
		<thead>
			<tr>\n
				<th class='hide'>OLT IP</th>\n
				<th>OLT</th>\n
				<th>SLOT</th>\n
				<th>PON</th>\n
				<th>MAC</th>\n
				<th>tipo</th>\n
				<th class='hide'>id_transmissor</th>\n
				<th>vlan</th>\n
				<th class='hide'>id_login</th>\n
				<th>login</th>\n
				<th>id_contrato</th>\n
				<th class='hide'>id_perfil</th>\n

			</tr>\n
		</thead>
		<tr>\n
			<td class='hide' id='_olt'>$olt</td>\n
			<td id='_nome_olt'>$nome_olt</td>\n
			<td id='_slot'>$slot</td>\n
			<td id='_pon'>$pon</td>\n
			<td id='_mac'>$mac</td>\n
			<td id='_tipo'>$tipo</td>\n
			<td class='hide' id='_transmissor'>$transmissor</td>\n
			<td id='_vlan'>$vlan</td>\n
			<td class='hide' id='_idlogin'>$id_login</td>\n
			<td id='_login'>$nome</td>\n
			<td id='_idcontrato'>$contrato</td>\n
			<td class='hide' id='_perfil'>16</td>\n
		</tr>\n
	</table>
	</span>
	<br>
	<div style='display: none;' class='row loader'>Loading...</div>
";
if($jacadastrada){
	echo "<p class='red-text'>Esta ONT estava previamente cadastrada para o login <b>$jacadastrada[login]</b> e este registro foi deletado!</p>";
	echo "<p class='blue-text'>Será autorizada e vinculada ao novo login conforme dados acima.</p><br>";
	delete_onu($jacadastrada[id]);
}



echo "<div id='select' style='display: $pesquisa;'>";
echo "<p id='titulo'>Selecione o Cliente e o Login:</p>";
echo '<div id="linha" class="row">';
		echo '<div class="input-field col s6">
		  <input placeholder="Cliente" id="razao" type="text">
		  <label for="razao" class="active">Nome</label>
		</div>';
echo '</div>';
echo '<div id="temporario"></div>';
echo "</div select>";

echo "<div id='comfirm' style='display: $confirmar;'>";
	echo "	<a id='doit' class='btn red'>Concluir</a> 
			<a id='voltar' class='btn'>Escolher outro Cliente</a>";
echo "</div comfirm>";

echo "<div id='log'>";
echo "</div log>";


?>

