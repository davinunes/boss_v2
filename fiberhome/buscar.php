<?php
	if($_GET[OLT]){
		echo "<div class='texto' style='display: none;'><pre>";
		$command = '/usr/bin/python /var/www/ilunne/boss/py/descobertafh.py '.$_GET[OLT];
		
		$output = shell_exec($command);
		
		var_dump($output);
		// $output = preg_replace('/\\s\\s+/', ' ', $output);
		$output = explode("show discovery slot all pon all", $output);
		$output = explode("Command execute success.", $output[1]);
		$output = explode("ONU Unauth Table, ", $output[0]);
		
			
		echo "</pre></div>";
		$ponteiro = 0;
		foreach($output as $a){
			
			if(strlen($a) < 10) continue;
			$confere = preg_replace('/\\s\\s+/', ' ', $a);
			$confere = explode(" ", $confere);
			$a = explode("------------------------------------------------------------", $a);

			// 
			$loc = explode(",", $a[0]);
			$slot = explode(" = ", $loc[0]);
			$slot = $slot[1];
			$pon = explode(" = ", $loc[1]);
			$pon = $pon[1];
			if($confere[10] == "No" and $confere[11] == "OnuType"){
				// echo "Modelo Peculiar";
				$lista = explode("--- -------------- ------------ ---------- ------------------------ ------------ ---", $a[0]);
				$lista = explode("\n", $lista[1]);
				foreach($lista as $v){
					$v = preg_replace('/\\s\\s+/', ' ', $v);
					if(strlen($v) < 4) continue;
					$v = explode(" ", $v);
					$tipo = $v[1];
					$mac = $v[2];
					// var_dump($v);
					
					$ret[$ponteiro][olt] = $_GET[OLT];
					$ret[$ponteiro][nome_olt] = $_GET[nome_olt];
					$ret[$ponteiro][slot] = $slot;
					$ret[$ponteiro][pon] = $pon;
					$ret[$ponteiro][tipo] = $tipo;
					$ret[$ponteiro][mac] = $mac;
					
					$ponteiro++;
				}

				
			}else{
				$lista = explode("-------------------------------------------", $a[1]);			
				
				
				foreach($lista as $u){
					if(strlen($u) < 4) continue;
					$u = preg_replace('/\\s\\s+/', ' ', $u);
					$u = explode(" : ", $u);
					$tipo = explode("(", $u[2]); 
						$tipo = $tipo[0];
					$mac = explode(" ", $u[3]); 
						$mac = $mac[0];
					
					$ret[$ponteiro][olt] = $_GET[OLT];
					$ret[$ponteiro][nome_olt] = $_GET[nome_olt];
					$ret[$ponteiro][slot] = $slot;
					$ret[$ponteiro][pon] = $pon;
					$ret[$ponteiro][tipo] = $tipo;
					$ret[$ponteiro][mac] = $mac;
					
					$ponteiro++;
				}
				
			}
			// var_dump($mac);

			
			
			
			
		}
			
			if(!$ret){
				exit;
			}
			
			echo "<table class='responsive-table'>\n";
	echo "	<thead>\n
			<tr><th class='hide'>OLT</th><th>SLOT</th><th>PON</th><th>TIPO</th><th>MAC</th><th>AÇÃO</th></tr>\n
			</thead>\n
			<tbody>
	";
	
	
	foreach($ret as $t){
		echo "<tr>
		<td class='hide'>$t[nome_olt]</td>
		<td>$t[slot]</td>
		<td>$t[pon]</td>
		<td>$t[tipo]</td>
		<td>$t[mac]</td>
		<td><a class='btn provisionar' vlan='$_GET[vlan]' transmissor='$_GET[transmissor]' nome_olt='$_GET[nome_olt]' olt='$t[olt]' slot='$t[slot]' pon='$t[pon]' tipo='$t[tipo]' mac='$t[mac]'>PROVISIONAR</a></td>
		</tr>";
	}
	
	echo "</tbody>\n
		</table>\n";
	echo "<a id='texto'>Inspecionar Resultado</a>";	
		exit;
	}
?>