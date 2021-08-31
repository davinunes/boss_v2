<meta charset="UTF-8">
<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 
 
 <script src="exif.js"></script><!--https://cdn.jsdelivr.net/npm/exif-js  https://github.com/exif-js/exif-js -->
 <script src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  
 <script src="materialize.js"></script> <!-- https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js -->
 
 <script src="canvas-to-blob.min.js"></script>
 <script src="resize.js?<?php echo time(); ?>"></script>
 <script src="func.js?<?php echo time(); ?>"></script>
 <script src="paste.js"></script>
 
 <script src="https://code.highcharts.com/highcharts.js"></script>
			<script src="https://code.highcharts.com/modules/exporting.js"></script>
			<script src="https://code.highcharts.com/modules/export-data.js"></script>
			<script src="https://code.highcharts.com/modules/accessibility.js"></script>
 
 <title>BoOs</title>

 
 
 <script src="custom.js?<?php echo time(); ?>"></script>
 <style>
 
 @media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
	.collection-item
	{
		page-break-inside:avoid; 
	}
}

#toast-container {
  top: auto !important;
  right: auto !important;
  bottom: 70%;
  left:70%;
}
 
 </style>
 
 

<?php

include "database.php";
session_start();

if(isset($_POST[logout])){
	session_unset();
	session_destroy();
	header("location: index.php");
}
if(isset($_GET[rel]) and isset($_GET[os])){
	$_SESSION[os] = $_GET[os];
}elseif(!isset($_SESSION[tec])){
	include "login.php";
	echo "&#160;";
	exit;
}
if(isset($_SESSION[vipw])){
	include "senha.php";
	echo "&#160;";
	exit;
}

$os = $_SESSION[os];
$tec = $_SESSION[tec];
$func = $_SESSION[func];
$tecnico = mb_convert_case($_SESSION[nome], MB_CASE_TITLE, "ISO-8859-1");
$adm = $_SESSION[grupo] <= 2 ? true : false;
$dados = dbOs($os);
$dados = $dados[0];
$agenda = explode(" ",$dados[Agenda]);

if($tec == 6){
//	header("location: index2.php");
}

if($_GET[rel]){
	echo '
	<style>
	.no-print, .no-print *
    {
        display: none !important;
    }
	</style>';
	
	
}


if(isset($_SESSION[rosto]) and $_SESSION[rosto] != ""){
	$avatar = $adm ? "class='left modal-trigger' href='#perfil' id='avatar'" : "class='left' id='avatar'";
	$avatar = "class='left modal-trigger' href='#perfil' id='avatar'";
	$rosto = "<a $avatar ><img height='100%' src='$_SESSION[rosto]' alt='' class=''></a>";
}else{
	$avatar = $adm ? "class='left modal-trigger' href='#perfil' id='avatar'" : "class='left' id='avatar'";
	$avatar = "class='left modal-trigger' href='#perfil' id='avatar'";
	$rosto = "<a $avatar ><img height='100%' src='arquivos/boss/AVATAR_1_OP_6_dfae97e0d0d502c9d440d4ac363964f1.jpg' alt='' class=''></a>";
}

echo '<div id="principio" class="container">

	<div class=" row">
		<div class="col s12">
			<div class="card blue lighten-5">
				<div id="conteudo" class="card-content ">';
echo "<nav class='grey darken-3 no-print'>
		<div class='nav-wrapper'>
		  $rosto <span class='left '  >&#160; $tecnico</span>
		  <ul id='nav-mobile' class='right '>
		  
			<li><a id='mudasenha'><i class='left material-icons'>vpn_key</i></a></li>
			<li><a id='changeOs'><i class=' left material-icons'>assignment</i>O.S.</a></li>
			<li><a id='odometro' href='odometros'><i class='left material-icons large'>toys</i>Viagens</a></li>
			<li><a id='logout'><i class='left material-icons large'>exit_to_app</i></a></li>
		  </ul>
		</div>
	</nav>";
	
if(!isset($_SESSION[os])){
	echo "<div id='listadodia' status='1'>";
	include "os.php";
	echo "&#160;";
}else{
	echo "<div id='listadodia' status='0'>";
  
if($_SESSION[LISTA]){ // Verifica qual o indice da OS Atual, utilizado para percorrer as OS quando exibindo a OS Atual
	$index = array_search($_SESSION[os], $_SESSION[LISTA], true );
	$total = sizeof($_SESSION[LISTA]);
	if($index > 0){
		$bk = $_SESSION[LISTA][$index - 1];
	}
	if($index < $total){
		$nx = $_SESSION[LISTA][$index + 1];
	}
}
  
  
  
switch ($dados[status]){
	case "EN":
		echo "<br/><span cor='blue lighten-2' class='titulo card-title'><strong>OS</strong> $dados[OS] de <strong>$dados[Cliente]</strong> - ENCAMINHADA </span>";
		break;
	case "A":
		echo "<br/><span cor='blue lighten-5' class='titulo card-title'><strong>OS</strong> $dados[OS] de <strong>$dados[Cliente]</strong> - ABERTA </span>";
		break;
	case "F":
		echo "<br/><span cor='purple accent-1' class='titulo card-title'><strong>OS</strong> $dados[OS] de <strong>$dados[Cliente]</strong> - FECHADA </span>";
		break;
	case "AN":
		echo "<br/><span  cor='blue lighten-5'  class='titulo card-title'><strong>OS</strong> $dados[OS] de <strong>$dados[Cliente]</strong> - ANÁSLISE </span>";
		break;
	case "AS":
		echo "<br/><span  cor='blue lighten-5' class='titulo card-title'><strong>OS</strong> $dados[OS] de <strong>$dados[Cliente]</strong> - ASSUMIDA </span>";
		break;
	case "AG":
		echo "<br/><span  cor='blue lighten-5' class='titulo card-title'><strong>OS</strong> $dados[OS] de <strong>$dados[Cliente]</strong> - AGENDADA </span>";
		break;
	case "EX":
		echo "<br/><span  cor='blue lighten-5' class='titulo card-title'><strong>OS</strong> $dados[OS] de <strong>$dados[Cliente]</strong> - EXECUÇÃO </span>";
		break;
	default:
		break;
}

echo "<blockquote class='no-print'>";

if($adm){
	$newtec = funcionarios();
	$troca = ""; //reseta a variavel
	foreach($newtec as $linha){
		
		$troca .= "\n\t<div class='linkar chip brown lighten-4' os='$dados[OS]' func='$linha[tec]'><img src='$linha[img]'>".mb_convert_case($linha[Tecnico], MB_CASE_TITLE, "ISO-8859-1")."</div>\n";
	}
	$classe=' modal-trigger ';
}
$rue = "\t		<div class='modal' id='troca$dados[OS]'>
						<div class='modal-content'>
							<h4>Alterar Funcionario</h4>
							<p>Clique no novo Funcionario</p>
							$troca
						</div>
					</div>";

if($dados[Tecnico] != ""){
	echo "<div href='#troca$dados[OS]' class='$classe chip teal lighten-3'><img src='$dados[img]'>".$dados[Tecnico]." </div> ";
	echo "<div class='chip grey'>".++$index." de $total </div></br> ";
	echo $rue;
}
echo "<div class='chip brown lighten-3'>".$dados[Assunto]." </div> ";
// echo "<a href='http://ixc.acessodf.net/aplicativo/radusuarios/rel_22021.php?id=$dados[id_login]'><div class='chip deep-green darken-3'>".$dados[login]." </div> </a>";

if($dados[login]){ // Se tem o login configurado na OS
	$onu = get_onu($dados['login']);
	echo "<a href='speed.php?login=".$dados['login']." '><div class='chip deep-green darken-3'><b>".$dados['login']." </b></div> </a>";
}else{ // Senão lista todos os logins do cliente
	foreach(login($dados['cliente_id']) as $c){
		// var_dump($c['login']);
		echo "<a href='speed.php?login=".$c['login']." '><div class='chip orange darken-2'><b>".$c['login']." </b></div> </a>";
	}
	
}


echo "<div id='estado' state='".$dados[status]."' class='chip brown lighten-3'>".$dados[status]." </div> </br>";
if($adm){ //Botão para diminuir os dias
	echo "<div class='no-print dia chip light-blue lighten-3' os='$dados[OS]' data='".date('y/m/d', strtotime('-1 days', strtotime($agenda[0])))." $agenda[1]:00'> -1 </div> ";
}
echo "<div class='chip light-blue lighten-3'>".date('d/m/y', strtotime($agenda[0]))." </div> ";
if($adm){ //Botão para aumentar os dias
	echo "<div class='dia chip light-blue lighten-3' os='$dados[OS]' data='".date('y/m/d', strtotime('+1 days', strtotime($agenda[0])))." $agenda[1]:00'> +1 </div> ";
}
echo "<div class='chip deep-orange lighten-3'>".$agenda[1]." </div> ";
if($adm and $dados['status'] == "EN"){ //Botão para reabir a OS
	echo "<div id='reagendar' class='chip red lighten-3' os='".$dados['OS']."' > Reagendar OS </div> ";
}

//var_dump($dados);
echo " </br> ";
//echo ;
//echo $dados[endereco];

//echo $dados[Abertura];
echo "</blockquote>";

//mensagens(dbOs($os));
echo "<div id='msgs' style='min-height: 50vh';
>";
mensagens(dbMsg($os));
echo "</div>";
echo '<div class="row no-print card-action">';
echo '<div class="row no-print">';
echo "<button onclick='getLocation()' id='modalup' class=\" right waves-effect waves-light btn-large modal-trigger\" href=\"#fechar\"><i class='left  material-icons'>edit</i>Comentar</button>&#160;";
// if($adm){
	echo '<a href="fiberhome/" class="teal darken-3 modal-close waves-effect waves-orange btn-large"><i class="material-icons large left">fact_check</i>Toca do Dragão</a> ';
// }
echo '</div>';


echo "<a id='2changeOs' class=' teal lighten-2 waves-effect waves-light btn'><i class='left  material-icons'>assignment</i>Lista</a>&#160;";

echo "<a id='legacy' login='$dados[id_login]' class=' green lighten-2 waves-effect waves-light btn'><i class='left  material-icons'>chat</i>Histórico</a>&#160;";

if($bk){
	echo "<a id='aanterior' href='os.php?os=$bk' class=' aanterior deep-orange lighten-1 waves-effect waves-light btn'><i class='left  material-icons'>call_received</i>Anterior</a>&#160;";
}

if($nx){
	echo "<a id='aproxima' href='os.php?os=$nx' class=' aproxima brown lighten-1 waves-effect waves-light btn'><i class='right  material-icons'>call_made</i>Proxima</a>";
}
}
echo '</div>';

?>
&#160;
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Structure -->
  <div id="fechar" class="modal">
    <div class="modal-content">
      <h3>Comentar Ordem de Serviço <?=$dados['OS']?></h3>
	  <p>Clique em apenas comentar para postar uma mensagem e <strong>continuar com a posse da OS</strong>, ou clique em Comentar e encaminhar para postar a mensagem e <strong>encaminhar a OS para o financeiro</strong>.</p>
	  <form class="col s12" id="form0s">
		<div class="input-field col s12">
          <textarea rows='10' func="<?=$func?>" adm="<?=$adm?>" status="<?=$dados['status']?>" os="<?=$dados['OS']?>" tec="<?=$tec?>" id="coment" type="textarea" class=" comentar" 
placeholder='Descreva o maximo de detalhes do atendimento!
Qual sinal do Equipamento?
Quantos metros de cabo foram utilizados?
Quantas portas livres restaram na caixa de atendimento?


'></textarea>
          <label class="active" for="coment">Comentário sobre o serviço:</label>
        </div>
		<div class="progress s8">
            <div id="progresso" class="determinate" ></div>
        </div>
		<div id="fotografias" class = "row">
               <label>É possivel postar imagens como um comentário da OS</label>
               <div class = "file-field input-field">
                  <div class = "btn">
                     <i class="material-icons large left">collections</i> Foto
					 <input id="imagem" type="file" accept="image/*" multiple/>
                  </div>
                  
                  <div class = "file-path-wrapper">
                     <input class = "file-path validate" type = "text"
                        placeholder = "Pode enviar mais de uma por vez" />
                  </div>
               </div>    
            </div>
		
	  </form>

    </div>
    <div class="modal-footer">
		<button class="hide" onclick="getLocation()">Localização</button>

		<p class="hide" id="demo"></p>
      <a id="comentar"  class=" orange darken-3 modal-close waves-effect waves-orange btn-large"><i class="material-icons large left">description</i>Somente Comentar</a>
	  <a id="encaminhar"  class=" blue darken-3 modal-close waves-effect waves-orange btn-large"><i class="material-icons large left">forward</i>Comentar e Encaminhar</a>
	  
<?php
	if($adm){
		echo '  <a id="adm_fechar"  class=" red darken-3 modal-close waves-effect waves-orange btn-large"><i class="material-icons large left">done_all</i>Fechar OS</a>';
		
	}else{
	echo '  ';
	}
	  
?>
    </div>
  </div>
  
    <div id="perfil" class="modal">
    <div class="modal-content">
      <h3>Trocar foto de perfil</h3>
	  <p>Escolha outra foto para o Perfil</strong>.</p>
	  <form class="col s12" id="form0s">
		
		
					  
					  <?php
					  
					  if($adm){
						echo 	'<div class="row"><label>Selecione o Usuário para alterar a foto:</label><div class="input-field col s12">';
						echo	"<select id='colaborador' class='browser-default'>";
					  
					  
					  
					  	$sql  =" select ";
						$sql .=" 	op.imagem as img, op.nome, tec.funcionario as Tecnico,op.id as idUser, tec.id as tec "; //op.imagem as img
						$sql .=" from ";
						$sql .=" 	usuarios op ";
						$sql .=" left join funcionarios tec on op.funcionario = tec.id ";
						$sql .=" where ";
						$sql .=" 	op.status = 'A' ";
						$lista	= DBExecute($sql);
						if(mysqli_num_rows($lista)){
							while($retorno = mysqli_fetch_assoc($lista)){
								$list[] = $retorno;
								}
							}
					  // var_dump($lista);
					  foreach($list as $a){
						  $selecionado = $a['idUser'] == $func ? "selected" : "";
						  $individuo = $a[nome];
						  echo "<option value='".$a[idUser]." $selecionado' data-icon='".$a[img]."'>$individuo</option>";
					  }
					  
					  
					  echo "</select></div></div>";
					  }
					  ?>
					
		<div id="fotoperfil" class = "row">
               
               <div class = "file-field input-field">
                  <div class = "btn">
                     <i class="material-icons large left">collections</i> Foto
					 <input id="imagem2" type="file" accept="image/*"/>
                  </div>
                  
                  <div class = "file-path-wrapper">
                     <input class = "file-path validate" type = "text"
                        placeholder = "Selecione a imagem" />
                  </div>
               </div>    
            </div>
		
	  </form>

    </div>
  </div>
  

