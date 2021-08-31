<meta charset="UTF-8">
<link rel="shortcut icon" href="favicon.ico" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<?php

include "database.php";
session_start();
echo '<pre>';


$sql  =" select "; //
//	$sql .=" 	* ";
//	$sql .=" 	b.documento, ";
//	$sql .=" 	b.valor_recebido as Recebido, ";
//	$sql .=" 	b.id_conta as Conta, ";
	$sql .=" 	b.historico as Historico ";
	$sql .=" from ";
	$sql .=" 	fn_movim_finan b ";
//	$sql .=" left join usuarios op on op.id = m.id_operador";
//	$sql .=" left join su_oss_evento ev on ev.id = m.id_evento";
	$sql .=" where ";
	$sql .=" 	b.id = '206999' ";
	$sql .=" 	limit 10 ";
	
	$result	= DBExecute($sql);

	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
		var_dump($dados);
	}



?>











 <script src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  
 <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/exif-js"></script><!-- https://github.com/exif-js/exif-js -->
 <script src="canvas-to-blob.min.js"></script>
 <script src="resize.js"></script>
 <script src="func.js"></script>
 <script src="custom.js"></script>