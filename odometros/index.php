<style>
	#toast-container {
	  bottom: auto !important;
	  left: auto !important;
	  top: 10%;
	  right:7%;
	}

</style>
<head>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<title>Registro de Odômetros </title>
</head>
<?php

include "database.php";
session_start();

if(!isset($_SESSION[tec])){
	header("location: ../index.php");
}


?>
<div id="container">
  <div class="row">
    <div class="col s12">
      <div class="card-panel hoverable white">
        <h2>
		<?php echo $_SESSION[nome]; ?>
        </h2>
<?php
	echo "<span id='responsavel_id' responsavel_id='$_SESSION[tec]'></span>";
	echo "<div class='row'>";
	echo "<div class='input-field col s12 l6'>
          <input placeholder='Odometro' id='odometro' type='number' class='validate'>
          <label for='odometro'>ODOMETRO</label>
        </div>";

	echo "<div class='input-field col s12 l6'>
          <input placeholder='Descrição do lançamento' id='descritivo' type='text' class='validate'>
          <label for='descritivo'>Descritivo</label>
        </div>";
		
	get_carros();

	echo "<div class='input-field col s12 l6'>
          <input placeholder='Ordem de Serviço' id='os' type='number' class='validate'>
          <label for='first_name'>Ordem de Serviço</label>
        </div>";
		
	echo "<div class='input-field col s12 l6'>
          <a id='enviar' onepause='0' class='center-align btn-large'>Enviar</a>
        </div>";
	
	echo "</div>";
	// echo "<pre>";
	// echo "</pre>";

?>
		<div id="camera_setup" >
		<div class="row"> 
		<button class="hide btn-large blue right" id="btnPlay">
		<span class="icon is-small">
		  <i class="fas fa-play"></i>
		</span>
		<span>Filmar</span>
		</button>
		<button class="btn-large blue left" id="btnPause">
		<span class="icon is-small">
		  <i class="fas fa-pause"></i>
		</span>
		<span>Pausar</span>
		</button>


		<button class="btn-large orange right" id="btnChangeCamera">
		<span class="icon">
		  <i class="fas fa-sync-alt"></i>
		</span>
		<span>Mudar Camera</span>
		</button>
		</div>
		 
		<label for='video'>Posicione a camera no odometro do veiculo e clique em pausar. Depois, preencha os demais daos e clique em registrar.</label>  
		<video autoplay id="video" width="100%"></video>
		
		</div>
		
		
		<div id="recente">
			<?php  lista(2,$_SESSION[tec]); ?>
		</div>
		<div id="geo"></div>
		</div>
    </div>
  </div>
 </div id="container">
<canvas class="hide" id="canvas"></canvas>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="cam.js?<?php echo time(); ?>"></script>
<script src="script.js?<?php echo time(); ?>"></script>