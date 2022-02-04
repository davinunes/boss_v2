<?php

include "../key.php";

function DBConnect(){ # Abre Conexão com Database
	$link = @mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());
	mysqli_set_charset($link, DB_CHARSET) or die(mysqli_error($link));
	return $link;
}

function DBClose($link){ # Fecha Conexão com Database
	@mysqli_close($link) or die(mysqli_error($link));
}

function ajustaTexto($string) {

    // matriz de entrada
    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

    // matriz de saída
    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_' );

    // devolver a string
    return str_replace($what, $by, $string);
}

function DBEscape($dados){ # Proteje contra SQL Injection
	$link = DBConnect();
	
	if(!is_array($dados)){
		$dados = mysqli_real_escape_string($link,$dados);
	}else{
		$arr = $dados;
		foreach($arr as $key => $value){
			$key	= mysqli_real_escape_string($link, $key);
			$value	= mysqli_real_escape_string($link, $value);
			
			$dados[$key] = $value;
		}
	}
	DBClose($link);
	return $dados;
}

function DBExecute($query){ # Executa um Comando na Conexão
	$link = DBConnect();
	$result = mysqli_query($link,$query) or die(mysqli_error($link));
	
	DBClose($link);
	return $result;
}

function lista($linhas = 0, $key = ''){
		$sql  ="select * from veiculos_viagens ";
		$sql .="WHERE  ";
		$sql .="responsavel_id = '$key' ";
		$sql .="order by data desc ";
		$linhas > 0 ? $sql  .= "limit $linhas " : "";
		$result	= DBExecute($sql);

		if(!mysqli_num_rows($result)){

		}else{
			while($retorno = mysqli_fetch_assoc($result)){
				$dados[] = $retorno;
			}
		}
		
		echo "<table>";
		echo "<thead>
				<tr>
					<th>ID</th>
					<th>Veiculo</th>
					<th>Odometro</th>
					<th>Data</th>
					<th>Descritivo</th>
					<th>OS</th>
					<th>Local</th>
					<th>Foto</th>
					</th>
				</tr>
			</thead>";
		foreach($dados as $a){
			echo "<tr>
					<td>$a[id]</td>
					<td>$a[veiculo_id]</td>
					<td>$a[odometro]</td>
					<td>$a[data]</td>
					<td>$a[descritivo]</td>
					<td>$a[os]</td>
					<td></td>
					<td><img height='120' src='$a[foto]'></td>
				</tr>";
		}
		echo "</table>";
	}

function get_carros($login){
	$sql = "SELECT * FROM `veiculos`" ;
	// $sql .= "from radpop_radio_cliente_fibra o " ;
	// $sql .= "left join radusuarios u on u.id = o.id_login " ;
	// $sql .= "left join radpop_radio t on t.id = o.id_transmissor " ;
	// $sql .= "where u.login = '$login'" ;
	// $sql .= "and t.fabricante_modelo = 'FBT'" ;
	
	$result	= DBExecute($sql);
	// var_dump($sql);
	if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}
	echo "<div class='input-field col s12 l6'>
			<select class='browser-default' id='veiculo_id'>
				<option value= '' disabled selected>Selecione o veículo</option>";
	foreach($dados as $carro){
		echo "<option value='".$carro[id]."'>".strtoupper($carro[placa])." - ".strtoupper($carro[descricao])."</option>";
	}
	echo "</select>
          <label for='veiculo_id' class='active'>Veiculo</label>
        </div>";
	
}

function rel($dti=false, $dtf=false, $carro=false, $motorista=false){
		$sql  =" select a.*, v.placa,u.nome, v.descricao from veiculos_viagens a ";
		$sql .=" left join veiculos v on v.id =  a.veiculo_id ";
		$sql .=" left join usuarios u on u.id =  a.responsavel_id ";
		$sql .=" WHERE  ";
		$dti != "" ? $sql .= " a.data >= '$dti' and " : "";
		$dtf != "" ? $sql .= " a.data <= '$dtf' and " : "";
		$carro != null ? $sql .= " a.veiculo_id = '$carro' and " : "";
		$motorista ? $sql .= " a.responsavel_id = '$motorista' and " : "";
		$sql .= " 1 ";
		$sql .=" order by a.data desc ";
		
		$result	= DBExecute($sql);

		if(!mysqli_num_rows($result)){

		}else{
			while($retorno = mysqli_fetch_assoc($result)){
				$dados[] = $retorno;
			}
		}
		
		echo "<table>";
		echo "<thead>
				<tr>
					<th>ID</th>
					<th>Veiculo</th>
					<th>Descrição</th>
					<th>Responsável</th>
					<th>Odometro</th>
					<th>Data</th>
					<th>Descritivo</th>
					<th>OS</th>
					<th>Local</th>
					<th>Foto</th>
					</th>
				</tr>
			</thead>";
		foreach($dados as $a){
			echo "<tr>
					<td>$a[id]</td>
					<td>$a[placa]</td>
					<td>$a[descricao]</td>
					<td>$a[nome]</td>
					<td>$a[odometro]</td>
					<td>$a[data]</td>
					<td>$a[descritivo]</td>
					<td>$a[os]</td>
					<td></td>
					<td><img class='animate' height='120' src='$a[foto]'></td>
				</tr>";
		}
		echo "</table>";
	}

if($_POST[metodo] == "insert"){
	$veiculo_id = $_POST[veiculo_id];
	$odometro = $_POST[odometro];
	$os = $_POST[os];
	$responsavel_id = $_POST[responsavel_id];
	$foto = $_POST[foto];
	$descritivo = ajustaTexto($_POST[descritivo]);
	$latitude = $_POST[latitude];
	$longitude = $_POST[longitude];
	$precisao = $_POST[precisao];

	$sql  = " INSERT INTO `veiculos_viagens` " ;
	$sql .= " (`veiculo_id`, `odometro`, `descritivo`, `os`, `responsavel_id`, `foto`,`latitude`,`longitude`,`precisao`)" ;
	$sql .= " VALUES" ;
	$sql .= " ('$veiculo_id','$odometro','$descritivo','$os','$responsavel_id','$foto','$latitude','$longitude','$precisao')" ;
	
	if(DBExecute($sql)){
		echo "ok";
	}else{
		echo "erro";
	}
}
?>