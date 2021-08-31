<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Erros no Retorno</title>
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
	
	echo "<div class='card-title'>Relatório de erros do dia $dia</div>";
	echo "<p >Apresentando os boletos que foram apontados como erro no processamento do retorno da data citada</p>";
	
	include "database.php";
	$retornosDeHoje = hojeret($today);
	// var_dump($remessasDeHoje);
	echo "<table>\n";
	echo "	<thead>\n
			<tr><th>NossoNumero</th><th>Vencimento</th><th>Nome</th><th>Movimento</th><th>ERRO e Sugestão</th><th>Valor</th><th>Pago</th><th>Retorno</th></tr>\n
			</thead>\n
			<tbody>
	";
	foreach($retornosDeHoje as $k => $v){
		$erros = eRet($v['id']);
		foreach($erros as $e){
			switch ($e[status]){
				case "JC":
					$err = "<b>Já Cancelado</b><br>Dê baixa no boleto que o substituiu";
					break;
				case "JR":
					$err = "<b>Já Recebido</b> <br>Verificar se foi recebido no escritório em função do cliente enviar o comprovante: neste caso, nada a fazer.<br>Ou, se foi recebido em dinheiro no escritório e o cliente <b>TAMBÉM</b> pagou no banco: Nesse ultimo caso, entrar em contato com o cliente para informar e realizar a baixa do proximo mês.";
					break;
				case "E":
					$err = "<b>Estornado</b>(pelo banco)<br>Nada foi recebido, nada a fazer)";
					break;
				default:
					$err = $e[status];
			}
			$nome = nome($e[id_cobranca]);
			echo "\t<tr class=''><td>$e[id_cobranca]</td> <td class=''><b>$e[data_vencimento]</b></td><td>$nome</td><td>$e[cod_mov]</td><td>$err</td><td>$e[valor]</td><td>$e[valor_pago]</td><td>$e[id_lote_retorno]</td></tr>\n";
		}
		
		

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