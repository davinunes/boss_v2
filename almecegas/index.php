<style>

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<?php
// Acessa a OLT de Almecegas e coleta a lista de ONU

// $confere = preg_replace('/\\s\\s+/', ' ', $a);
$command = '/usr/bin/python /var/www/ilunne/boss/almecegas/almecegas.py';
	
$output = shell_exec($command);
$output = explode("---------------------------------------------------------------------------------------------------------------------", $output);
$output = explode("Onu Number: ", $output[1]);
$lista = $output[0];
$output = explode("OLT2#", $output[1]);
$output = explode("\r\n", $output[0]);
$total = $output[0];


// Daqui pra frente é montar a tabela com as informações obtidas
echo "<div class='container'>";
$lista = explode("\r\n", $lista);
echo "<table>";
echo "<thead>";
	echo "<tr>
				<th>ONU</th>
				<th>MAC</th>
				<th>Status</th>
				<th>Dados</th>
				<th>Link</th>
			</tr>";
echo "</thead>";
echo "<tbody>";
foreach($lista as $item){
	if(strlen($item) == 0) continue;
	$item = preg_replace('/\\s\\s+/', ' ', $item);
	$item = explode(" ", $item);
	$onu = preg_replace('/:/', '-', $item[0]);
	$onu = preg_replace('/\//', '-', $onu);
	$mac = preg_replace('/sn:/', '', $item[3]);
	$status = $item[6] == "operational" ? "online" : "<b>offline</b>";
	if($status == "online"){
		$link = "<a class='sinal btn orange darken-4' onu='#$onu' href2='sinal.php?onu=$item[0]'>Obter dados</a>";
	}else{
		$link = "";
	}
	// var_dump($item);
	echo "<tr>
			<td>".$item[0]."</td>
			<td>".$mac."</td>
			<td>".$status."</td>
			<td id='$onu'></td>
			<td>$link</td>
			</tr>\n";
}
echo "</tbody>";
echo "<tfoot>";
	echo "<tr><td colspan='3'>Totais:</td><td colspan='2'>$total</td></tr>";
echo "</tfoot>";
echo "</table>";
echo "</div class='container'>";

?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script type="text/javascript" src="script.js"></script>