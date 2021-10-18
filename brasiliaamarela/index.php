<style>

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<?php
// Acessa a OLT de Almecegas e coleta a lista de ONU

// $confere = preg_replace('/\\s\\s+/', ' ', $a);
$command = '/usr/bin/python /var/www/ilunne/boss/brasiliaamarela/almecegas.py';
	
$output = shell_exec($command);
echo "<pre>";
	echo $output;
echo "</pre>";

?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
<script type="text/javascript" src="script.js"></script>