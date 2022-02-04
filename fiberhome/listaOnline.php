<?php
// var_dump($_GET);

$command = '/usr/bin/python /var/www/ilunne/boss/py/listaOnline.py '.$_GET[OLT].' '.$_GET[SLOT].' '.$_GET[PONN];

$output = shell_exec($command);


$output = explode("--- -------------- -- --- --- ------------ ---------- ----------------------------------------", $output);
$output = explode("Command execute success.", $output[1]);
$output = explode("\n", $output[0]);

// echo "<pre>";
// var_dump($output);
// echo "</pre>";

include "database.php";


foreach($output as $a){
	if(strlen($a) < 10) continue;
	if(strpos($a, 'continue') !== false) continue;
	$linha = preg_replace('/\\s\\s+/', ' ', $a);
	$linha = explode(" ", $linha);
	
	$item[num] = $linha[0];
	$item[ost] = $linha[4];
	$item[mac] = $linha[5];
	
	$ixc = check_onu($item[mac]);
	$status = check_precontrato($ixc[id_contrato]);
	
	$item[login] = $ixc[login];
	$item[perfil] = $ixc[id_perfil];
	$item[vlan] = $ixc[vlan];
	$item[ativo] = $status;
	
	$onu[] = $item;
	
	
}
// var_dump($onu);
echo "<table class='responsive-table centered highlight'>\n";
echo "	<thead>";
echo "	<tr>
			<th>Login</th>
			<th>Mac</th>
			<th>Obs</th>
			<th>Vlan</th>
			<th>Perfil</th>
			<th>Stat</th>
			<th>Num</th>
		</tr>";
echo "	</thead>";
echo "  	<tbody>";

foreach($onu as $t){
	if($t[ost] == "up"){
		$classe = 'green lighten-5';
	}else{
		$classe = 'grey lighten-4';
	}
	
	if($t[ativo] == "I"){
		$ativo = 'red-text text-darken-4';
		$cancelado = "CONTRATO CANCELADO";
	}else{
		$ativo = '';
		$cancelado = "";
	}
	
	echo "<tr class='$classe $ativo' >
	<td>$t[login]</td>
	<td>$t[mac]</td>
	<td>$cancelado</td>
	<td>$t[vlan]</td>
	<td>$t[perfil]</td>
	<td>$t[ost]</td>
	<td>$t[num]</td>
	</tr>";
}

echo "		</tbody>\n";
echo "</table>\n";


// view-source:https://acessodf.net/boss/fiberhome/listaOnline.php?OLT=172.21.2.2&SLOT=12&PONN=1

?>