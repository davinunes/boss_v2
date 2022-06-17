<?php

	include "database.php";
	
	if(isset($_GET[olt]) and isset($_GET[mac])){
		echo "<div class='texto' style='/*display: none;*/'><pre>";
		$command = '/usr/bin/python /var/www/ilunne/boss/py/extermina.py '.$_GET[olt].' '.$_GET[mac];
		
		$output = shell_exec($command);
		
		var_dump($output);
		
		echo "</pre></div>";
		
		if(isset($_GET[os]) and isset($_GET[idx])){
			
		
			$msg = "Exclui do sistema a ONU cujo MAC $_GET[mac] estava associado ao login $_GET[login]";
			// $oba = telegram($_GET[nome]." - ".$msg);
			fecharOs($_GET[os], $_GET[tecnico], $msg);
			
			delete_onu($_GET[idx]);
		
		}
		
	}
?>