<?php

if($_GET[COA]){
	$cmd="'bash /var/www/ilunne/boss/bash/coa.sh ".$_POST[login]." ".$_POST[valor]."'";
	// echo $cmd;
	$command = '/usr/bin/python /var/www/ilunne/boss/py/ssh.py '.$cmd;
	// echo $command;
	$output = shell_exec($command);
	echo "<pre>".$output."</pre>";
	// /usr/bin/python /var/www/ilunne/boss/py/ssh.py echo 'User-Name="jhonathan_barros", Nas-Identifier="10.111.114.2", ERX-Service-Deactivate:1="IPV4", ERX-Service-Activate:1="IPV4(100M,100M)"' | /usr/bin/radclient -d /usr/share/freeradius/ -sx 10.111.114.2 coa esqueci
	exit;
}




?>

<meta charset="UTF-8">
<link rel="shortcut icon" href="favicon.ico" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 
 <div class='container'> 
 	<div class='row'>
		<div class='card-panel indigo lighten-5'>
		<form action="tab.php">
			<div class="input-field col s1">
			  <input placeholder="" id="id" type="text" class="active" disabled="">
			  <label class="active"   for="key">id:</label>
			</div>
			<div class="input-field col s2">
			  <input class="modal-trigger" data-target="pesquisa_login" placeholder="" id="id_usuario" type="text" class="active" disabled="">
			  <label class="active"   for="key">id_usuario:</label>
			</div>
			<div class="input-field col s2">
			  <input class="modal-trigger" data-target="pesquisa_login" placeholder="" id="username" type="text" class="active" disabled="">
			  <label class="active"   for="key">username:</label>
			</div>
			<div class="input-field col s3">
			  <input placeholder='"IPV4(130M,220M)"' id="value" type="text" class="active">
			  <label class="active"   for="key">value:</label>
			</div>
			<div class="input-field col s2">
				<a class='btn' id="crud" valor="enviar" type="submit">enviar</a>
			</div>
			<div class="input-field col s2">
				<a id="limpar" class='btn orange' type="reset">Limpar</a>
			</div>
		</form> 
			&#160;
			

 
 <?php
 

 

include "database.php";

$sql = " select id, username, value, id_usuario from radreply where attribute = 'ERX-Service-Activate:1';  "." \n ";
$sql = " select * from radreply where attribute = 'ERX-Service-Activate:1';  "." \n ";
// $sql = "select * from radusuarios where id='4264' limit 10";


$comentadas	= DBExecute($sql);
	if(mysqli_num_rows($comentadas)){
		while($retorno = mysqli_fetch_assoc($comentadas)){
			$newtec[] = $retorno;
		}
	}
	echo "<pre>";
	

foreach($newtec as $k => $v){
	// echo "hop\n";
	$h="\n<thead>\n"; // Iniciar o cabeçalho do código
	$h .= "\t<tr>\n";
	$r .= "\t<tr>\n"; // Inicia Gerar as linhas da tabela
	$coa = "<a class='coa material-icons small' ";
	$e = "<a class='editar material-icons small' ";
	$d = "<a class='deletar material-icons red-text small' ";
	foreach($v as $a => $b){
		// var_dump($a);
		$h .= "\t\t<th>$a</th>\n";		// itera o cabeçalho
		$r .= "\t\t\t<td title='$a'>$b</td>\n";		// itera as linhas
		$e .=  "$a='$b' "; // itera os botões
		$coa .=  "$a='$b' "; // itera os botões
		$d .=  "$a='$b' "; // itera os botões
	}
	$e .= ">edit</a>";
	$coa .= ">send</a>";
	$d .= ">delete</a>";
	$r .= "\t\t\t<td>$coa $e $d</td>\n";		// itera as linhas
	$r .= "\t</tr>\n"; // Inicia Gerar as linhas da tabela
	
	$h .= "\t</tr>";
	$h.="\n</thead>\n"; // Fecha o Cabeçalho

}


echo "</pre>\n";

echo "\n<table>\n";
echo $h;
echo $r;
echo "\n</table>\n";

?>
 
 
 
  </div>
   </div>
 </div>
 
<div id="pesquisa_login" class="modal">
		<div  class="modal-content">
			<h6>Pesquise o login:</h6>
	<!--		<nav><!-- -->
				<div class="nav-wrapper">
				  <form>
					<div class="input-field">
					  <input id="search2" type="search" class="pesquisa_login" required>
					  <label class="label-icon" for="search2"><i class="material-icons">search</i></label>
					  <i class="material-icons">close</i>
					</div>
				  </form>
				</div>
	<!--		</nav><!-- -->
		 
			<span class="lista_login center-align hoverable"></span>
		
		</div>
</div>
 
 <script src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  
 <script src="gs.js?<?php echo time(); ?>"></script>  
 <script src="materialize.js"></script> <!-- https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js -->