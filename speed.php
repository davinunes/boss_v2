<?php

	if($_GET['metodo'] == "int"){
		$command = '/usr/bin/python /var/www/ilunne/boss/py/junos.py int '.$_GET['login'];
		
			$output = shell_exec($command);
			$output = preg_replace('/\\s\\s+/', ' ', $output);
			$output = explode(" ", $output);
			// var_dump($output);
			$user['interface'] = $output[0];
			$user['ipv4'] = $output[1];

			echo json_encode($user);
			exit;
	}
	
	if($_GET['metodo'] == "fibra"){
		$command = '/usr/bin/python /var/www/ilunne/boss/py/teste.py '.$_GET['OLT'].' '.$_GET['SLOT'].' '.$_GET['PON'].' '.$_GET['ONU'];
		
			$output = shell_exec($command);
			// $output = preg_replace('/\\s\\s+/', ' ', $output);
			$output = explode("\n", $output);
			// echo "<pre>";
			// var_dump($output);
			$onu['status']=preg_replace('/\\s\\s+/', ' ', $output['1']);
				$onu['status']=explode(" is ", $onu['status']);
				$onu['status']=str_replace("\r","",$onu['status']);
				$onu['status']=$onu['status'][1];
			// Se a ONU estiver ativa trataremos os demais comandos:
			if($onu['status'] == "active."){
				$onu['temperatura']=preg_replace('/\\s\\s+/', ' ', $output['7']);
					$onu['temperatura']=explode(" : ", $onu['temperatura']);
					$onu['temperatura']=explode("\t", $onu['temperatura'][1]);
					$onu['temperatura']=$onu['temperatura'][0];
				$onu['potencia']=preg_replace('/\\s\\s+/', ' ', $output['10']);
					$onu['potencia']=explode(" : ", $onu['potencia']);
					$onu['potencia']=explode("\t", $onu['potencia'][1]);
					$onu['potencia']=$onu['potencia'][0];
				$onu['sinal']=preg_replace('/\\s\\s+/', ' ', $output['11']);
					$onu['sinal']=explode(" : ", $onu['sinal']);
					$onu['sinal']=explode("\t", $onu['sinal'][1]);
					$onu['sinal']=$onu['sinal'][0];
				$onu['retorno']=preg_replace('/\\s\\s+/', ' ', $output['12']);
					$onu['retorno']=explode(" : ", $onu['retorno']);
					$onu['retorno']=explode("\t", $onu['retorno'][1]);
					$onu['retorno']=$onu['retorno'][0];
					
				$onu['portas']=preg_replace('/\\s\\s+/', ' ', $output['15']);
					$onu['portas']=explode("ITEM = ", $onu['portas']);
					$onu['portas']=str_replace("\r","",$onu['portas']);
					$onu['portas']=$onu['portas'][1];
					for($i=1, $status=18, $rate=22, $modo=23;$i<=$onu['portas'];$i++, $status+=9, $rate+=9, $modo+=9){
						$onu["porta"][$i][status]=preg_replace('/\\s\\s+/', ' ', $output[$status]);
							$onu["porta"][$i][status]=explode(": ", $onu["porta"][$i][status]);
							$onu["porta"][$i][status]=str_replace("\r","",$onu["porta"][$i][status][1]);
							if($onu["porta"][$i][status] == "Linked"){
								$onu["porta"][$i][rate]=preg_replace('/\\s\\s+/', ' ', $output[$rate]);
								$onu["porta"][$i][rate]=explode(": ", $onu["porta"][$i][rate]);
								$onu["porta"][$i][rate]=str_replace("\r","",$onu["porta"][$i][rate][1]);
							$onu["porta"][$i][modo]=preg_replace('/\\s\\s+/', ' ', $output[$modo]);
								$onu["porta"][$i][modo]=explode(": ", $onu["porta"][$i][modo]);
								$onu["porta"][$i][modo]=str_replace("\r","",$onu["porta"][$i][modo][1]);
							}
						
					}
			}else{
				echo "ONU offline, inexistente ou desautorizada";
			}
			

			// var_dump($onu);
			if($onu[temperatura] and $onu[temperatura] < 42 ){
				$alerta = "<i class='material-icons tiny blue-text text-darken-4'>pool</i>";
			}elseif($onu[temperatura] and $onu[temperatura] < 60 ){
				$alerta = "<i class='material-icons tiny orange-text text-darken-4'>whatshot</i>";
			}elseif($onu[temperatura] and $onu[temperatura] < 70 ){
				$alerta = "<i class='material-icons tiny red-text text-darken-4'>whatshot</i>";
			}elseif($onu[temperatura] and $onu[temperatura] >= 70 ){
				$alerta = "<i class='material-icons tiny red-text text-darken-4'>whatshot</i><i class='material-icons tiny red-text text-darken-4'>whatshot</i><i class='material-icons tiny red-text text-darken-4'>whatshot</i>";
			}else{
				$alerta = "";
			}

			echo "<p class='purple lighten-4'><b>Dados da ONU ".strtoupper($_GET['mac']).":
			</b> <ul class='purple lighten-4'>
			Circuito: ".strtoupper($_GET['OLTID'])."-".strtoupper($_GET['SLOT'])."-".strtoupper($_GET['PON'])."-".strtoupper($_GET['ONU'])."
			<br><li> 
			 <i class='material-icons tiny red-text text-accent-4'>whatshot</i> Temperatura:\t ".$onu[temperatura]."&#176; $alerta</li> <li>
			 <i class='material-icons tiny  purple-text text-darken-4'>flare</i> Sinal na ONU:\t ".$onu[sinal]."</li> <li>
			 <i class='material-icons tiny  orange-text text-accent-4'>flare</i> Sinal na OLT:\t ".$onu[retorno]."</li></ul></p>";
			$int = 1;
			foreach($onu[porta] as $p ){
				$mensagem = "";
				$alerta = "";
				if($p[status] == "Linked"){
					$icone = "<i class='material-icons tiny'>cloud</i>";
					if($p[modo] == "full"){
						$cor = "indigo accent-1";
						
					}else{
						$cor = "lime darken-4";
						$alerta = "<i class='material-icons tiny orange-text text-accent-4'>warning</i>";
						$mensagem .= " Portas half podem representar problemas... ";
					}
					if($p[rate] == "10M"){
						$cor = "red ";
						$alerta = "<i class='material-icons tiny indigo-text text-darken-4'>warning</i>";
						$mensagem .= " $alerta Portas em 10Mbps podem representar problemas... ";
					}
					// $cor = $p[modo] == "full" ? "indigo" : "lime darken-4";
					// $cor = $p[rate] == "10" ? "red accent-4" : $cor;
					echo "<p class='$cor'>$icone Porta $int ativa a ".$p[rate]." ".$p[modo]." $mensagem </p>";
				}else{
					$icone = "<i class='material-icons tiny'>cloud_off</i>";
					echo "<p class='grey'>$icone Porta $int inativa</p>";
				}
				$int++;
			}
			// echo json_encode($onu);
			
			exit;
	}
	
	if($_GET['metodo'] == "vel"){
		$command = '/usr/bin/python /var/www/ilunne/boss/py/junos.py vel '.$_GET['interface'];
		
		$output = shell_exec($command);
		$output = preg_replace('/\\s\\s+/', ' ', $output);
		$output = explode(" ", $output);
		$bps['upload'] = $output[5];
		$bps['download'] = $output[11];

		echo json_encode($bps);
		exit;
	}
	
	if($_GET['metodo'] == "nat"){
		$command = '/usr/bin/python /var/www/ilunne/boss/py/junos.py nat '.$_GET['ipv4'];
		
		$output = shell_exec($command);
		$output = preg_replace('/\\s\\s+/', ' ', $output);
		$output = explode("\n", $output);
		$output = explode(" ", $output[1]);
		// $bps['upload'] = $output[5];
		// $bps['download'] = $output[11];

		echo json_encode($output[2]);
		exit;
	}
	
	if($_GET['metodo'] == "natspy"){
		$command = '/usr/bin/python /var/www/ilunne/boss/py/junos.py natspy '.$_GET['ipv4'];
		
		$output = shell_exec($command);
		echo $output;
		exit;
	}
	
	
	if(!$_GET['login']){
		exit;
	}
	
	
	session_start();
	
	
	
	$login = $_GET['login'];
	echo '<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 <script src="materialize.js"></script>
 <link rel="stylesheet" href="speed.css">';
 
 
	echo "<div id='download' login='$login' status='offline' interface=''></div>";
	echo "<div id='dados' download='0' upload='0'></div>";
	
	echo '
	
	<div class="container">
		<div class="row">
			<div class="col s12">
				<div class="card teal lighten-5">
					<div class="card-content">
	';
	if($_SESSION[os]){
		include "database.php";
		$sos = $_SESSION[os];
		$stec = $_SESSION[tec];
		$sfunc = $_SESSION[func];
		$stecnico = mb_convert_case($_SESSION[nome], MB_CASE_TITLE, "ISO-8859-1");
		$dados = dbOs($sos);
		$dados = $dados[0];
		$sstatus = $dados[status];
		
		echo "<span id='dadosOs' os='$sos' tec='$stec' func='$sfunc' status='$sstatus' class='card-title'> <h3><b>Diagnosticando para a OS $sos -> $stecnico</b></h3></span>";
		
		
	}
	
	echo '			<div class="row">
						<div class="col s6">
							<figure  class="highcharts-figure">
								  <div style="display:none;" id="container"></div>
								  <h4 id="endoflife" class="highcharts-description"> </h4>
							</figure>
						</div>
						<div class="col s6">
							<figure  class="highcharts-figure">
								<div style="display:none;" id="nat"></div>
							</figure>
						</div>
					</div>
					<div class="row">
						<div class="col s6" id="onu">
							<h4>Se houver uma onu cadastrada e for FiberHome, dados de diagnóstico serão mostrados aqui.</h4>
						</div>
						
					</div>
					<div class="row">
						<div class="col s12" id="snapshot">
							Após finalizar o monitoramento será possivel consultar as conexões de CGNAT.
								<pre>
									<p id="natsnap"></p>
								</pre>
								
							</div>
						</div>
					</div>
	

	';
	if($_SESSION[os]){
		echo '
			<div class="card-action">
				<a class=" waves-effect waves-light btn-large" id="saveonu">Salvar Status da ONU</a>
				<a class=" waves-effect waves-light btn-large" id="savetest">Salvar resultado do monitoramento</a>
				<a style="display:none;" class="right blue natsnap waves-effect waves-light btn-large" >SnapShot das conexões NAT</a>
				
			</div>
		';
		
	}
	
	echo '
	<div class="card-content" id="hist">
		<h4>Buscando histórico de Conexões</h4>
	</div>
	</div>
	</div>
	</div>
	</div>

'
;
	

	echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
			<script src="https://code.highcharts.com/highcharts.js"></script>
			<script src="https://code.highcharts.com/modules/exporting.js"></script>
			<script src="https://code.highcharts.com/modules/export-data.js"></script>
			<script src="https://code.highcharts.com/modules/accessibility.js"></script>';
  
  
  echo '<script src="func.js"></script>';
  echo '<script src="speed.js?'.time().'"></script>';

?>