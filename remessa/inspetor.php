<?php
include "database.php";

$rem = $_GET['rem'];
$tit = uRem($rem);

	echo "<table class='responsive-table'>\n";
	echo "	<thead>\n
			<tr><th>Boleto</th><th>Nome</th><th>Posição</th><th>Solicitado</th><th>Confirmação</th><th>Status</th></tr>\n
			</thead>\n
			<tbody>
	";
	foreach($tit as $k => $v){
		$tipo = $v[tipo_remessa] == 01 ? "Entrada" : "Baixa";
		$estado = bp($v[id_receber]);
		$nome = nome($v[id_receber]);
		switch($estado){
			case "A":
				$estado = "Aberto";
				break;
			case "C":
				$estado = "Cancelado";
				break;
			case "R":
				$estado = "Recebido";
				break;
		}
		
		$retorno = $v[tipo_remessa] == 01 ? uRet($v[id_receber]) : bRet($v[id_receber]);
		$classe = $tipo == "Entrada" ? "blue lighten-5" : "grey lighten-2";

		echo "\t<tr class='$classe'><td>$v[id_receber]</td>
		<td>$nome</td>
				<td>$estado</td><td>$tipo</td><td>";

					if(sizeof($retorno) > 1){
						foreach($retorno as $k => $r){

							echo "No retorno ".$r[id_lote_retorno];
							end($retorno);
							if ($k != key($retorno)){
								echo "<br>";
							}
							$status = "<i class='green-text material-icons'>playlist_add_check</i>";
							
							
						}
					}elseif(sizeof($retorno) == 1){
						echo "No retorno ".$retorno[0][id_lote_retorno];
						$status = "<i class='green-text material-icons'>playlist_add_check</i>";
						
					}else{
						
						$status = "<i class='red-text material-icons'>warning</i>";
						
						
					}
					echo "</td><td>$status</tr></tr>\n";


		
		// if($estado == "Aberto" and $tipo == "Baixa"){
			// echo "</td><td>$status Por que foi solicitado <b>baixa</b> de um boleto em aberto?</tr></tr>\n";
		// }elseif(($estado == "Recebido" or $estado == "Cancelado") and $tipo == "Entrada"){
			// echo "</td><td>$status Por que foi solicitado <b>entrada</b> de um boleto em que já foi recebido ou cancelado?</tr></tr>\n";
		// }elseif($estado == "Aberto" and $tipo == "Entrada"){
			// echo "</td><td>$status</tr></tr>\n";
		// }else{
			// echo "</td><td> </tr></tr>\n";
		// }
		


	}
	echo "</tbody>\n
		</table>\n";
echo "<pre>";

echo "</pre>";


?>