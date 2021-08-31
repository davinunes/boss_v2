<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>A REME SA</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<div class="container">
	<div class="card ">
		<div class="card-content">

<?php
	if($_GET[data]){
		$today = date("Y-m-d", strtotime($_GET[data]));
		$dia = date("d-m-Y", strtotime("$today"));
	}else{
		$today = date("Y-m-d");
		$dia = date("d-m-Y", strtotime("$today"));
	}
	// var_dump($today);
	$hoje = date("Y-m-d");
	$ontem 	= date('Y-m-d', strtotime('-1 days', strtotime($today)));
	
	$amanha = date('Y-m-d', strtotime('+1 days', strtotime($today)));
	
	echo "<div class='card-title'>Relatório de remessas do dia $dia</div>";
	echo "<p >Apresentando a quantidade de titulos solicitados e a razão da quantidade de ordens executadas. Clique no numero da remessa para detalhamento</p>";
	
	include "database.php";
	$remessasDeHoje = hoje($today);
	// var_dump($remessasDeHoje);
	echo "<table>\n";
	echo "	<thead>\n
			<tr><th>Carteira</th><th>Remessa</th><th>Entradas</th><th>Baixas</th></tr>\n
			</thead>\n
			<tbody>
	";
	foreach($remessasDeHoje as $k => $v){
		$entradas = qe($v[id]);
		$baixas = qb($v[id]);
		$entradasConfirmadas = 0;
		$baixasConfirmadas = 0;
		$diana = uRem($v[id]);
		
		foreach($diana as $a => $b){
			$tipo = $b[tipo_remessa] == 01 ? "Entrada" : "Baixa";
			if($tipo == "Entrada"){
				$tmp = uRet($b[id_receber]);
				if(sizeof($tmp) > 0){
					$entradasConfirmadas++;
				}
				
			}elseif($tipo == "Baixa"){
				$tmp = bRet($b[id_receber]);
				if(sizeof($tmp) > 0){
					$baixasConfirmadas++;
				}
			}
		}
		if($entradas != 0 and $entradas == $entradasConfirmadas){
			$deuCerto = "blue lighten-4";
		}else{
			$deuCerto = "teal lighten-5";
		}

		
		
		echo "\t<tr class='$deuCerto'><td>$v[id_carteira]</td> <td class='remessa'><b>$v[id]</b></td><td>$entradas/$entradasConfirmadas</td><td>$baixas/$baixasConfirmadas</td></tr>\n";
		// var_dump($entradas);
	}
	echo "</tbody>\n </table>\n";
	
	$base = "/".$_SERVER[REQUEST_URI];
	echo '<div class="card-action">';
		echo "<a class='btn ' href=?data=".$ontem.">Anterior</a>\n";
		echo "<a class='btn ' href=?data=#>Hoje</a>\n";
		echo "<a class='btn ' href=?data=".$amanha.">Proximo</a>\n";
	echo '</div card-action>';
	// var_dump($_SERVER);
?>

		</div card>
	</div card-content>
</div container>

<div class="container">
	<div class="card ">
		<div class="card-content">
			<div id="inspetor">
			</div inspetor>
		</div>
	</div>
</div>
	
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="script.js"></script>