<?php
// var_dump($_GET);

$command = '/usr/bin/python /var/www/ilunne/boss/py/showVersion.py '.$_GET[OLT];

$output = shell_exec($command);


$output = explode("SOFEVER    ", $output);
$output = explode("17             ", $output[1]);
$output = explode("\n", $output[0]);
// echo "<pre>";



foreach($output as $a){
	if(strlen($a) < 10) continue;
	$linha = preg_replace('/\\s\\s+/', ' ', $a);
	$linha = explode(" ", $linha);
	$slot[$linha[1]] = $linha[2];
	
}

foreach($slot as $k => $v){
	if($v != "----" and $k != 9 and $k != 10){
		echo "<option value='$k'>$k :: [$v]</option>\n";
	}
}


// echo json_encode($slot);
// var_dump($slot);
// echo "</pre>";

?>