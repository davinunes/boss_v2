<?php
// var_dump($_GET);
include "database.php";
$command = '/usr/bin/python /var/www/ilunne/boss/py/listaOnline.py '.$_GET[OLT].' '.$_GET[SLOT].' '.$_GET[PONN];
$command = '/usr/bin/python /var/www/ilunne/boss/py/listaTodasONUonline.py '.$_GET[OLT];


$output = shell_exec($command);


// Limpeza do TXT
$output = explode("A: Authorized  P: Preauthorized  R: System Reserved", $output);
$output = explode("Command execute success.", $output[1]);
$output = explode("-----  ONU Auth Table, ", $output[0]);

// Vamos montar um Array com os dados
foreach($output as $a){
	if(strlen($a) < 10) continue;
	// Separo o cabeçalho da lista
	$a = explode("--- -------------- -- --- --- ------------ ---------- ----------------------------------------", $a);
	$spi = $a[0];
	$lista = $a[1];
	
	//trabalho o cabeçalho
	$spi = explode(" -----", $spi);
	$spi = explode(", ", $spi[0]);
	$slot 	=  (explode(" = ", $spi[0]))[1];
	$pon 	=  (explode(" = ", $spi[1]))[1];
	$qtd 	=  (explode(" = ", $spi[2]))[1];
	
	// Agora eu sei o slot, a Pon e a Quatidade de Itens de cada PON
	
	// É hora de trabalhar a lista de ONU
	$lista = explode("\n", $lista);
	
	$onu[slot] = $slot;
	$onu[$slot][pon]= $pon;
	$onu[$slot][$pon][qtd] = $qtd;
	
	foreach($lista as $a){
		if(strlen($a) < 10) continue;
		$linha = preg_replace('/\\s\\s+/', ' ', $a);
		$linha = explode(" ", $linha);
		
		// Organizo o catálogo
		$item[num] = $linha[0];
		$item[ost] = $linha[4];
		$item[mac] = $linha[5];
		
		$ixc = check_onu($item[mac]);
		$status = check_precontrato($ixc[id_contrato]);
		
		$item[login] = $ixc[login];
		$item[perfil] = $ixc[id_perfil];
		$item[vlan] = $ixc[vlan];
		$item[ativo] = $status;
		
		$onu[$slot][$pon][lista][] = $item;
	}
}
// Pronto, agora eu tenho todos os dados organizados. Preciso exibir isso como uma tabela.

// var_dump($onu);


// var_dump($onu);
echo "<table class='responsive-table centered highlight'>\n";
// echo "	<thead>";
// echo "	<tr>
			// <th>Login</th>
			// <th>Mac</th>
			// <th>Obs</th>
			// <th>Vlan</th>
			// <th>Perfil</th>
			// <th>Stat</th>
			// <th>Num</th>
		// </tr>";
// echo "	</thead>";
echo "  	<tbody>";

foreach($onu as $card){
	//Crio uma linha separadora ou Crio uma coluna esticada
	echo "<tr class='teal'><td colspan='8'>Card X</td></tr>\n";
	foreach($card as $porta){
		echo "\t<tr><td colspan='8'>Porta X</td></tr>\n";
		foreach($porta[lista] as $ont){
			if($ont[ost] == "up"){
				$classe = 'green lighten-5';
			}else{
				$classe = 'grey lighten-4';
			}
			
			if($ont[ativo] == "I"){
				$ativo = 'red-text text-darken-4';
				$cancelado = "CONTRATO CANCELADO";
			}else{
				$ativo = '';
				$cancelado = "";
			}
			
			echo "\t\t
					<tr class='$classe $ativo' >
					<td>$ont[login]</td>
					<td>$ont[mac]</td>
					<td>$cancelado</td>
					<td>$ont[vlan]</td>
					<td>$ont[perfil]</td>
					<td>$ont[ost]</td>
					<td>$ont[num]</td>
			</tr>";
			
		}
		
	}

}



echo "		</tbody>\n";
echo "</table>\n";


// view-source:https://acessodf.net/boss/fiberhome/listaOnline.php?OLT=172.21.2.2&SLOT=12&PONN=1

?>