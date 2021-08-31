<?php


$command = '/usr/bin/python /var/www/ilunne/boss/almecegas/sinal.py '.$_GET[onu];
	
$output = shell_exec($command);

$output = explode("show onu remote optical info", $output);
$output = explode("show onu power", $output[2]);
$potencias = explode(" dbm\r\n", $output[2]);
$tmp = explode(" C\r\n", $output[0])[0];
$tmp = explode("temperature", $tmp);
// echo "<pre>";
$dados[olttx] 		= explode(" : ", $potencias[0])[1];
$dados[oltrx] 		= explode(" : ", $potencias[1])[1];
$dados[onutx] 		= explode(" : ", $potencias[2])[1];
$dados[onurx] 		= explode(" : ", $potencias[3])[1];
$dados[temperatura] = explode(": ", $tmp[1])[1];
echo "Sinal OLT/ONU: ".$dados[oltrx]."/".$dados[onurx]."<br>\n";
echo "Potencia da OLT/ONU: ".$dados[olttx]."/".$dados[onutx]."<br>\n";
echo "Temperatura da ONU: ".$dados[temperatura]."<br>\n";

?>