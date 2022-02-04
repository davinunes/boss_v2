<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Provisionar ONU Fiberhome</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<style>
.btn-mini {
color: #FFF;
font-size: 1.1rem;
text-align: center;
background-color: #F29023;
border: none;
border-radius: 2px;
line-height: 13px;
padding: 5px;
margin: 2px;
text-transform: uppercase;
vertical-align: middle;
-webkit-tap-highlight-color: transparent;
background-clip: padding-box;
box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
}

.btn-mini :hover :active {
background-color: #EF6C00;
box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
}


.loader {
  color: #bc2929;
  font-size: 90px;
  text-indent: -9999em;
  overflow: hidden;
  width: 1em;
  height: 1em;
  border-radius: 50%;
  margin: 72px auto;
  position: relative;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
  -webkit-animation: load6 1.7s infinite ease, round 1.7s infinite ease;
  animation: load6 1.7s infinite ease, round 1.7s infinite ease;
}
@-webkit-keyframes load6 {
  0% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  5%,
  95% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  10%,
  59% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
  }
  20% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
  }
  38% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
  }
  100% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
}
@keyframes load6 {
  0% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  5%,
  95% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
  10%,
  59% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
  }
  20% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
  }
  38% {
    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
  }
  100% {
    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
  }
}
@-webkit-keyframes round {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes round {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>
<?php
	session_start();
	$os = $_SESSION[os];
	$tec = $_SESSION[tec];
	$func = $_SESSION[func];
	$colaborador = $_SESSION[nome];
	if($os){
		echo "<span id='os' os='$os' tec='$tec' colaborador='$colaborador' temos='1'></span>";
		include "database.php";
		$dados_da_os1 = dbOs($os);
		// var_dump($dados_da_os1);
		
	}
	if(!isset($_SESSION[os])){
		header("location: ../index.php");
		exit;
	}
	
if($dados_da_os1[0][id_login]){
	
	$id_login = $dados_da_os1[0][id_login];
	$contrato = contrato($id_login);
	$nome = $dados_da_os1[0][login];
	$cliente = $dados_da_os1[0][Cliente];
	$onts = check_onu_por_login($id_login);
}


?>
<div id="card" class="">
	<div class="card ">
		<div class="card-content" id="saori">

<?php
	
	

	
	echo "<div class='card-title'>Pesquisa ONU por OLT e permite provisionar e registrar na OS</div>";
	echo "<p >Clique na OLT que deseja buscar e aguarde, depois, clique em provisionar</p>";
	echo "<p >Esta ação será registrada na OS $os de $cliente</p><br>";
	
	if($onts){
		
		echo "<span id='onts_cliente'>
		<table class=''>
			<caption>Existem ONTs vinculadas neste Login, <b>se</b> está realizando uma <b>troca</b>, remova antes. </caption>
			<thead>
				<tr>\n
					<th>ID</th>\n
					<th>MAC</th>\n
					<th>Circuito</th>\n
					<th>Excluir</th>\n

				</tr>\n
			</thead>";
	foreach($onts as $ont){
		echo"
			<tr id='ont$ont[id]'>\n	
				<td >$ont[id]</td>\n
				<td >$ont[mac]</td>\n
				<td >$ont[id_transmissor]-$ont[slotno]-$ont[ponno]-$ont[onu_numero]</td>\n
				<td >
					<a class='desprovisionar' mac='$ont[mac]' olt='$ont[ip]' id_tabela='$ont[id]' tecnico='$tec' login='$nome' os='$os' nome='$colaborador'>
						<i class=\"material-icons\">delete_forever</i>
					</a>
				</td>\n
			</tr>\n
	";
	}
	echo "		</table>
		</span onts_cliente>
		<br>";
}
	
	
	echo '<div class="row" id="olts">';
		echo '<a transmissor="6" vlan="1000" olt="172.21.2.2" class="btn-mini waves-effect waves-light buscar">Sede</a> ';
		echo '<a transmissor="8" vlan="3041" olt="10.168.169.2" class="btn-mini waves-effect waves-light buscar">Incra8</a> ';
		echo '<a transmissor="7" vlan="3042" olt="172.21.1.2" class="btn-mini waves-effect waves-light buscar">Vendinha</a> ';
		echo '<a transmissor="9" vlan="3044" olt="172.21.3.2" class="btn-mini waves-effect waves-light buscar">Monte Alto</a> ';
		echo '<a transmissor="11" vlan="3048" olt="10.169.67.2" class="btn-mini waves-effect waves-light buscar">Padre Lucio</a> ';
		echo '<a transmissor="10" vlan="3046" olt="10.169.66.10" class="btn-mini waves-effect waves-light buscar">Dos Pássaros</a> ';
		
	echo '</div>';
	
	echo '<div style="display: none;" class="row loader">Loading...</div>';
	
	echo "<div id='resultado'></div>";

	

	echo '<div class="card-action">';

	echo '</div card-action>';
	// echo '<pre>';
	// var_dump($_SESSION);
	// echo '</pre>';

?>

		</div card>
	</div card-content>
</div container>

	
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="script.js?<?php echo time(); ?>"></script>