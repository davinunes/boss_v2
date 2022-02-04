<?php
// var_dump($_GET);

$command = '/usr/bin/python /var/www/ilunne/boss/py/showVersion.py '.$_GET[OLT];

$output = shell_exec($command);

echo "<pre>";
var_dump($output);
echo "</pre>";
$output = explode("--- -------------- -- --- --- ------------ ---------- ----------------------------------------", $output);
$output = explode("Command execute success.", $output[1]);
$output = explode("\n", $output[0]);


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


?>