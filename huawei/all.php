<link type="text/css" rel="stylesheet" href="css.css"  media="screen,projection"/>
<?php

	$command = '/usr/bin/python /var/www/ilunne/boss/py/n40allUser.py';
	$output = shell_exec($command);
	$output = explode("------------------------------------------------------------------------------", $output);
	$output = explode("\n", $output[2]);
	
	foreach($output as $a){
		if(strlen($a) < 10) continue;
		$linha = preg_replace('/\\s\\s+/', ' ', $a);
		$linha = explode(" ", $linha);
		
		// var_dump($linha);
		$logins[] = $linha[2];
		$macs[] = $linha[5];
		$lista[] = array($linha[2],$linha[5],$linha[4]);
		
	}
	
	$login = array_unique( array_diff_assoc( $logins, array_unique( $logins ) ) );
	$mac = array_unique( array_diff_assoc( $macs, array_unique( $macs ) ) );
	
	echo "<table>";
	
	foreach($login as $l){
		$class=" style='border-top: 1px dashed #800!important; '";
		foreach($lista as $o){
			if($l == $o[0]){

				echo "<tr $class></tr>";
					echo "<td>$o[0]</td>";
					echo "<td>$o[1]</td>";
					echo "<td>$o[2]</td>";
					echo "<td><a href='http://$o[2]:3479'>3479</a></td>";
					echo "<td><a href='http://$o[2]/login.cgi'>Nokia</a></td>";
					echo "<td><a href='http://$o[2]'>80</a></td>";
				echo "</tr>\n";
				$class = '';
			}
			
		}
	}
	
	echo "</table>";
	// $output = preg_replace('/\\s\\s+/', ' ', $output[2]);
	// 
	// var_dump($mac);
	
	

?>