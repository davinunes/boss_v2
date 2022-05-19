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
	
	$onu[$slot][card] = $slot;
	$online = 0;
	$offline = 0;
	foreach($lista as $a){
		if(strlen($a) < 10) continue;
		$linha = preg_replace('/\\s\\s+/', ' ', $a);
		$linha = explode(" ", $linha);
		
		// Organizo o catálogo
		$item[num] = $linha[0];
		$item[ost] = $linha[4];
		$item[mac] = $linha[5];
		
		$ixc = check_onu($item[mac]);
		echo "<pre>".var_dump($linha)."</pre>";
		$status = check_precontrato($ixc[id_contrato]);
		
		$item[login] = $ixc[login];
		$item[perfil] = $ixc[id_perfil];
		$item[vlan] = $ixc[vlan];
		$item[ativo] = $status;
		$item[ixc_address] = $ixc[ponno].":".$ixc[slotno].":".$ixc[onu_numero];
		
		// Faço a contagem de ONU online e Offline nessa lista
		if($item[ost] == "up"){
			$online++;
		}else{
			$offline++;
		}
		
		$onu[$slot][pons][$pon][porta]= $pon;
		$onu[$slot][pons][$pon][quantidade]= $qtd;
		$onu[$slot][pons][$pon][onns]= $online;
		$onu[$slot][pons][$pon][offs]= $offline;
		$onu[$slot][pons][$pon][lista][] = $item;
	}
}
// Pronto, agora eu tenho todos os dados organizados. Preciso exibir isso como uma tabela.

// var_dump($onu);


// var_dump($onu);
echo "<table class=' centered highlight'>\n";
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
	$superbloco = "card$card[card]";
	echo "<tr class='deep-purple lighten-3 togglavel1' swap='$superbloco'><td colspan='10'>Card $card[card]</td></tr>\n";

	foreach($card[pons] as $porta){
		$bloco = "card$card[card]pon$porta[porta]";
		$altura = $porta[quantidade] +1;
		echo "\t<tr class='indigo lighten-4 togglavel2 $superbloco' swap='$bloco'><td colspan='10'>Porta $porta[porta] com $porta[quantidade] ONT sendo $porta[onns] up/$porta[offs] down </td></tr>\n";
		echo "<td class='indigo lighten-4 $bloco $superbloco' rowspan='$altura'></td>";
		foreach($porta[lista] as $ont){
			if($ont[ost] == "up"){
				$estaOnline=true;
				$classe = 'green lighten-5 isup';
				$cancelado = "<i class='material-icons'>cloud_done</i>";
			}else{
				$estaOnline=false;
				$classe = 'grey lighten-2';
				$cancelado = "<i class='material-icons'>cloud_off</i>";
			}
			
			if($ont[ativo] == "I"){
				$estaCancelado = true;
				$ativo = 'red-text text-darken-4';
				$cancelado = "CONTRATO CANCELADO";
			}else{
				$ativo = '';
				$estaCancelado = false;
			}
			
			if($ont[login] == ""){
				$semlogin = true;
			}else{
				$semlogin = false;
			}
			
			echo "\t\t
					<tr class='$classe $ativo  $bloco' >
					<td title='Login'>$ont[login]</td>
					<td title='MAC'>$ont[mac]</td>
					<td title='Ação'>
						<a class='btn plune' mac='$ont[mac]' olt='$_GET[OLT]' >Deletar</a>
						<a class='btn update' >Atualizar no IXC</a>
					</td>
					<td title='Obs'>$cancelado</td>
					<td title='Vlan'>$ont[vlan]</td>
					<td title='Perfil'>$ont[perfil]</td>
					<td title='Status'>$ont[ost]</td>
					<td title='Numero'>$ont[num]</td>
			</tr>";
			
		}
		
	}

}



echo "		</tbody>\n";
echo "</table>\n";


// view-source:https://acessodf.net/boss/fiberhome/listaOnline.php?OLT=172.21.2.2&SLOT=12&PONN=1

?>