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
<div class='container'>

<?php
include "database.php";
echo '<form>';
get_carros();
echo '<input type="date" id="dti" value="'.date('Y-m-d').'"class="datepicker browser-default">';
echo '<input type="date" id="dtf" value="'.date('Y-m-d').'"class="datepicker browser-default">';
echo '<a id="filtrar" class="btn">Filtrar</a>';
echo "</form>";

echo "<div id='rel'></div>";


?>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="rel.js?<?php echo time(); ?>"></script>