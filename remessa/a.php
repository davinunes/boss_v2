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
	
	
	include "database.php";
	
	$sql .= " SELECT id, data_emissao, valor_total, status, filial_id, id_tipo_documento, modelo_nf, numero_nf ";
	$sql .= " FROM `vd_saida`";
	$sql .= " WHERE ";
	$sql .= " filial_id = '2'";
	$sql .= " and modelo_nf = '21' order by data_emissao, id";
	
	echo $sql;
	
	$result	= DBExecute($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	echo "<pre>";
	// var_dump($dados);
	echo "</pre>";
	
	$i = 1;
	foreach($dados as $nf){
		$sql  = "update vd_saida";
		$sql .= " set numero_nf = '$i'";
		if($nf[status] == 'A'){
			$sql .= ", status = 'C'";
		}
		$sql .= " where id = $nf[id]";
		echo $sql."<br>";
		// $result	= DBExecute($sql);
		$i++;
	}
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