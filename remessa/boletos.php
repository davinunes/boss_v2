<?php
include "database.php";

$rem = $_GET['rem'];
$tit = pnome($rem);

echo '<pre>';
// var_dump($tit);
echo '</pre>';

	echo "<table class='responsive-table'>\n";
	echo "	<thead>\n
			<tr><th>Boleto</th><th>Cliente</th><th>Contrato</th><th>Descrição</th><th>Valor</th><th>Vencimento</th></tr>\n
			</thead>\n
			<tbody>
	";
	foreach($tit as $aa){
		echo "
		<tr><td>$aa[id]</td>
		<td>$aa[razao]</td>
		<td>$aa[id_contrato]</td>
		<td>$aa[obs]</td>
		<td>$aa[valor]</td>
		<td>$aa[data_vencimento]</td></tr>
		";
	}
	
	echo "</tbody>\n
		</table>\n";
echo "<pre>";

echo "</pre>";


?>