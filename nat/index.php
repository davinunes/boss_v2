<?php

if(!$_GET['IP']){
	
	
	echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	';
echo '	<div class="container">';
echo '		<div class="col s12">';
echo '		  <div class="card-panel teal">';
echo '	<form>		<div class="input-field col s3">
				  <input placeholder="IP Publico" name="IP" type="text" class="validate">
				  <input placeholder="Porta" name="PORTA" type="number" class="validate">
				  
				</div>';
echo '			<input type="text" name="dti" placeholder="Data de inicio" id="dti" />
				<input type="text" name="dtf" placeholder="Data Fim" id="dtf" />
				
				<input type="submit" name="enviar" >';
echo '			</form>';
echo '		  </div>';
echo '		</div>';
echo '	</div>';

echo'
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://raw.githubusercontent.com/fawadtariq/materialize-datetimepicker/master/js/materializedatetimepicker.js"></script>
<script src="custom.js?<?php echo time(); ?>"></script>
';
	exit;
}


// Parametros da busca
$ipBuscado = $_GET['IP'];
$dtIni = date("Y-m-d H:i:s", strtotime($_GET['dti']) );
$dtExpireI = date("Y-m-d H:i:s", strtotime("$dtIni -1 day") );

$dtFim = $_GET['dtf'] ? date("Y-m-d H:i:s", strtotime($_GET['dtf']) ) : $dtIni;
$dtExpireF = date("Y-m-d H:i:s", strtotime("$dtFim -1 day") );
$door = $_GET['PORTA'];

// var_dump($dtIni);
// var_dump($dtFim);

// Configurações da Pesquisa

$publica[] = explode( '/', "143.208.72.192/26" );
$publica[] = explode( '/', "143.208.73.192/26" );
$publica[] = explode( '/', "143.208.74.192/26" );
$publica[] = explode( '/', "143.208.75.192/26" );

$privada[] = explode( '/', "100.64.56.0/21" );
$privada[] = explode( '/', "100.64.48.0/21" );
$privada[] = explode( '/', "100.64.40.0/21" );
$privada[] = explode( '/', "100.64.56.0/21" );

$range = "1953";
$porta = "1023";

function total_ips($a) {
	return( pow(2, ( 32 - $a ) ) );
}

$nated = false;

// var_dump(long2ip(ip2long("255.255.255.255") << ( 32 - $publica[1] ) ) );
// var_dump(total_ips($publica[1]));
// var_dump(total_ips($privada[1]));

// Realiza o Laço baseado no Privado
for($a=0;$a<sizeof($publica);$a++){

	$next = 0;
	for($i=1; $i < total_ips($privada[$a][1])-1; $i++){
		if($porta >= "65505"){
		$porta = "1023";
		$next++; 
		}
		
		// Sem parametros irá percorrer todas as possibilidades
		$ipNat = long2ip(ip2long($privada[$a][0])+$i);
		$ipPub = long2ip(ip2long($publica[$a][0])+1+$next);
		$pIni = ++$porta;
		$pFim = $porta+=$range;
		
		// Aplicado o filtro retornará conforme determinado
		if($ipPub == $ipBuscado){
			$nated = true;
			// echo $ipNat." = ";
			// echo $ipPub.":";
			// echo " ".$pIni."-".$pFim;
			// echo "</br>";
			
			if($door){
				if($pIni <= $door and $door <= $pFim){
					$resultado[] = [
						'Privado' => $ipNat,
						'Publico' => $ipPub,
						'Inicial' => $pIni,
						'Final' => $pFim
						];
				}
			}else{
				$resultado[] = [
					'Privado' => $ipNat,
					'Publico' => $ipPub,
					'Inicial' => $pIni,
					'Final' => $pFim
					];
			}
			

		}
	
	}
}
#var_dump($resultado);

$cont = 0;
foreach($resultado as $r){
	$q .= "	r.framedipaddress LIKE '$r[Privado]' ";
	$q .=  $cont++ < sizeof($resultado)-1 ? "OR \n" : "\n" ;
}



$sql .= "SELECT \n";
$sql .= "	c.razao as Nome, \n";
$sql .= "	c.cnpj_cpf as CPF, \n";
$sql .= "	r.username as Login, \n";
$sql .= "	r.radacctid as Conexao, \n";
$sql .= "	DATE_FORMAT(r.acctstarttime, '%d-%m-%Y %H:%i ') as Inicio, \n";
$sql .= "	DATE_FORMAT(r.acctstoptime, '%d-%m-%Y %H:%i ') as Fim, \n";
$sql .= "	r.callingstationid as MAC, \n";
$sql .= "	r.framedipaddress as IP \n";
$sql .= "FROM radacct r \n";
$sql .= "	LEFT JOIN radusuarios l ON r.username = l.login \n";
$sql .= "	LEFT JOIN cliente c ON l.id_cliente = c.id \n";
if(!$_GET['mac']){
	$sql .= "WHERE \n";
	$sql .= "	((r.acctstarttime <= '$dtIni' AND r.acctstoptime >= '$dtIni' \n";
	$sql .= "	OR (r.acctstoptime IS NULL AND r.acctstarttime BETWEEN '$dtExpireI' AND '$dtIni' )) OR \n";
	$sql .= "	(r.acctstarttime <= '$dtFim' AND r.acctstoptime >= '$dtFim' \n";
	$sql .= "	OR (r.acctstoptime IS NULL AND r.acctstarttime BETWEEN '$dtExpireF' AND '$dtFim' ))) \n";
	$sql .= "	AND ( \n";
	if($nated){
		$sql .= $q.")\n";
	}else{ 
		$sql .= "	r.framedipaddress LIKE '$ipBuscado' )\n";
	}
}else{
	$sql .= "WHERE \n";
	$sql .= "	r.callingstationid = '".$_GET['mac']."' \n";
	$sql .= "	and r.framedipaddress != '' \n";
}
$sql .= "	ORDER BY r.radacctid";

 // echo $sql;

include "database.php";

$result = DBExecute($sql);

if(!mysqli_num_rows($result)){

	}else{
		while($retorno = mysqli_fetch_assoc($result)){
			$dados[] = $retorno;
		}
	}

// var_dump($dados);

	foreach($dados as $k => $a){
		
		$h="<thead>\n"; // Iniciar o cabeçalho do código
		$h .= "\t<tr>\n";

		$r2="";
		foreach($a as $key => $val){			

			
			$h .= "\t\t<th class=''>$key</th>\n";		// itera o cabeçalho
			$r2 .= "\t\t<td title='$key' class=''>$val</td>\n";		// itera as linhas
			
		}
		$r1.="<tr>\n"; 		// Gerar as linhas da tabela
		$r1.=$r2;
		$r1.="</tr>\n"; //Fecha as linhas da tabela
		$h .= "\t</tr>\n";
		$h.="</thead>\n"; // Fecha o Cabeçalho
	}

echo '<link type="text/css" rel="stylesheet" href="tab.css"  media="screen,projection"/>';
echo "<table>
		<caption>
			<h2>Resultado da busca por conexões ativas entre "
			.date("d-m-Y H:i", strtotime($_GET['dti']) )." e "
			.date("d-m-Y H:i", strtotime($_GET['dtf']) )
			." atrás do ip $ipBuscado
			</h2>
		</caption>\n";
echo $h;
echo $r1;
echo "\n</table>";

?>



<!--

set services nat pool IPS-PUBLICOS-72 address 143.208.72.192/26
set services nat pool IPS-PUBLICOS-72 port automatic random-allocation
set services nat pool IPS-PUBLICOS-72 port deterministic-port-block-allocation block-size 1954
set services nat pool IPS-PUBLICOS-72 address-allocation
set services nat pool IPS-PUBLICOS-73 address 143.208.73.192/26
set services nat pool IPS-PUBLICOS-73 port automatic random-allocation
set services nat pool IPS-PUBLICOS-73 port deterministic-port-block-allocation block-size 1954
set services nat pool IPS-PUBLICOS-73 address-allocation
set services nat pool IPS-PUBLICOS-74 address 143.208.74.192/26
set services nat pool IPS-PUBLICOS-74 port automatic random-allocation
set services nat pool IPS-PUBLICOS-74 port deterministic-port-block-allocation block-size 1954
set services nat pool IPS-PUBLICOS-74 address-allocation
set services nat pool IPS-PUBLICOS-75 address 143.208.75.192/26
set services nat pool IPS-PUBLICOS-75 port automatic random-allocation
set services nat pool IPS-PUBLICOS-75 port deterministic-port-block-allocation block-size 1954
set services nat pool IPS-PUBLICOS-75 address-allocation
set services nat rule CG-NAT match-direction input
set services nat rule CG-NAT term 1 from source-address 100.64.32.0/21
set services nat rule CG-NAT term 1 from destination-address any-ipv4
set services nat rule CG-NAT term 1 then translated source-pool IPS-PUBLICOS-75
set services nat rule CG-NAT term 1 then translated translation-type deterministic-napt44
set services nat rule CG-NAT term 1 then translated mapping-type endpoint-independent
set services nat rule CG-NAT term 1 then translated address-pooling paired

set services nat rule CG-NAT term 2 from source-address 100.64.40.0/21
set services nat rule CG-NAT term 2 from destination-address any-ipv4
set services nat rule CG-NAT term 2 then translated source-pool IPS-PUBLICOS-74
set services nat rule CG-NAT term 2 then translated translation-type deterministic-napt44
set services nat rule CG-NAT term 2 then translated mapping-type endpoint-independent
set services nat rule CG-NAT term 2 then translated address-pooling paired

set services nat rule CG-NAT term 3 from source-address 100.64.48.0/21
set services nat rule CG-NAT term 3 from destination-address any-ipv4
set services nat rule CG-NAT term 3 then translated source-pool IPS-PUBLICOS-73
set services nat rule CG-NAT term 3 then translated translation-type deterministic-napt44
set services nat rule CG-NAT term 3 then translated mapping-type endpoint-independent
set services nat rule CG-NAT term 3 then translated address-pooling paired

set services nat rule CG-NAT term 4 from source-address 100.64.56.0/21
set services nat rule CG-NAT term 4 from destination-address any-ipv4
set services nat rule CG-NAT term 4 then translated source-pool IPS-PUBLICOS-72
set services nat rule CG-NAT term 4 then translated translation-type deterministic-napt44
set services nat rule CG-NAT term 4 then translated mapping-type endpoint-independent
set services nat rule CG-NAT term 4 then translated address-pooling paired

--!>