<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesquisa de boletos em Aberto</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<div class="container">
	<div class="card ">
		<div class="card-content">

<?php
	
	
	echo "<div class='card-title'>Pesquisa de Boletos em Aberto por Nome</div>";
	echo "<p >Digite o maximo necessario do nome do cliente, serão apresentados dinamicamente somente boletos em aberto, para simples consulta</p>";
	echo '<div class="row">';
		echo '<div class="input-field col s6">
          <input placeholder="Placeholder" id="name" type="text">
          <label for="name">Nome</label>
        </div>';
	echo '</div>';
	
	include "database.php";
	$remessasDeHoje = hoje($today);
	// var_dump($remessasDeHoje);

	

	echo '<div class="card-action">';

	echo '</div card-action>';

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
<script src="script_pesquisa.js"></script>