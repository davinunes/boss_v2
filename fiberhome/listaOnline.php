<?php
var_dump($_GET);

$command = '/usr/bin/python /var/www/ilunne/boss/py/listaOnline.py '.$_GET[OLT].' '.$_GET[SLOT].' '.$_GET[PONN];

$output = shell_exec($command);

$output = explode("--- -------------- -- --- --- ------------ ---------- ----------------------------------------", $output);
$output = explode("Command execute success.", $output[1]);
$output = explode("\n", $output[0]);

include "database.php";


foreach($output as $a){
	if(strlen($a) < 10) continue;
	$linha = preg_replace('/\\s\\s+/', ' ', $a);
	$linha = explode(" ", $linha);
	
	$item[num] = $linha[0];
	$item[ost] = $linha[4];
	$item[mac] = $linha[5];
	
	$ixc = check_onu($item[mac]);
	
	$item[login] = $ixc[login];
	$item[perfil] = $ixc[id_perfil];
	$item[vlan] = $ixc[vlan];
	
	$onu[] = $item;
	
	
}
var_dump($onu);


// view-source:https://acessodf.net/boss/fiberhome/listaOnline.php?OLT=172.21.2.2&SLOT=12&PONN=1

?>