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
	
	function opcional($contrato){
		$sql3 .= " update cliente_contrato ";
		$sql3 .= " SET ";
		$sql3 .= " tipo_doc_opc2 = '634'  ";
		$sql3 .= " WHERE ";
		$sql3 .= " id = '$contrato'";
		
		DBExecute($sql3);
	}
	
	$sql .= " SELECT c.id, c.status, p.nome, p.valor_contrato, t.tipo_pessoa ";
	$sql .= " FROM cliente_contrato c ";
	$sql .= " LEFT JOIN vd_contratos p on p.id = c.id_vd_contrato  ";
	$sql .= " LEFT JOIN cliente t on c.id_cliente = t.id  ";
	$sql .= " WHERE ";
	$sql .= " c.status = 'A' and t.tipo_pessoa = 'J'";
	// $sql .= " and modelo_nf = '21' order by data_emissao, id";
	$sql .= " order by c.id desc ";
	
	$sql2 .= " SELECT SUM(p.valor_contrato) as total ";
	$sql2 .= " FROM cliente_contrato c ";
	$sql2 .= " LEFT JOIN vd_contratos p on p.id = c.id_vd_contrato  ";
	$sql2 .= " LEFT JOIN cliente t on c.id_cliente = t.id  ";
	$sql2 .= " WHERE ";
	$sql2 .= " c.status = 'A' and t.tipo_pessoa = 'J' and c.tipo_doc_opc2 = '634'";
	$sql2 .= " order by c.id desc";
	
	echo $sql;
	
	$result	= DBExecute($sql2);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados2[] = $retorno;
		}
	}
	echo "<pre>";
	var_dump($dados2);
	echo "</pre>";
	
	$i = $dados2[0][total];
	
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
	
	
	
	foreach($dados as $u){
		if($i < 12000){
			echo "<pre>";
				var_dump($u);
			echo "</pre>";
			opcional($u[id]);
			$i += $u[valor_contrato];
			var_dump($i);
		}
		
	}
	var_dump($i);
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