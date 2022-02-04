<head>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listar ONU Online/Offline Fiberhome</title>
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


<div class="col s12">
    <h4 class="header">Lista ONU</h4>
    <div class="card horizontal">

      <div class="card-stacked">
        <div class="card-content">
          <p>Selecione OLT, SLOT e PON e clique em Pesquisar</p>

<div class='row'>
	<div class="input-field col s6 m6">
	<select id='OLT' name='OLT'>
	  <option value="172.21.2.2" selected>Sede</option>
	  <option value="10.168.169.2">Incra 8</option>
	  <option value="172.21.1.2">Vendinha</option>
	  <option value="172.21.3.2">Monte Alto</option>
	  <option value="10.169.66.10">Morada dos Pássaros</option>
	  <option value="10.169.67.2">Padre Lúcio</option>
	</select>
	<label>OLT</label>
	</div>
	
	<div class="input-field col s3 m2">
	<select id='SLOT' name='SLOT'>
	  <option value="1" selected>1</option>
	  <option value="2">2</option>
	  <option value="3">3</option>
	  <option value="4">4</option>
	  <option value="5">5</option>
	  <option value="6">6</option>
	  <option value="7">7</option>
	  <option value="8">8</option>
	  <option value="9" disabled>9</option>
	  <option value="10" disabled>10</option>
	  <option value="11">11</option>
	  <option value="12">12</option>
	  <option value="13">13</option>
	  <option value="14">14</option>
	  <option value="15">15</option>
	  <option value="16">16</option>
	</select>
	<label>SLOT</label>
	</div>

	<div class="input-field col s3 m2">
	<select id='PONN' name='PONN'>
	  <option value="1" selected>1</option>
	  <option value="2">2</option>
	  <option value="3">3</option>
	  <option value="4">4</option>
	  <option value="5">5</option>
	  <option value="6">6</option>
	  <option value="7">7</option>
	  <option value="8">8</option>
	  <option value="9">9</option>
	  <option value="10">10</option>
	  <option value="11">11</option>
	  <option value="12">12</option>
	  <option value="13">13</option>
	  <option value="14">14</option>
	  <option value="15">15</option>
	  <option value="16">16</option>
	</select>
	<label>PON</label>
	</div>
	
	<div class="input-field col s6 m1">
		<a id='slt' class="waves-effect waves-light btn-small blue">slot</a>
		
		
	</div>
	<div class="input-field col s6 m1">
		
		<a id='btn' class="waves-effect waves-light btn-small orange right">Atualizar</a>
		
	</div>
	
	
</div>

        </div>

      </div>
    </div>
  </div>

<div class="row">
    <div class="col s12 m7 ">
      <div class="card-panel ">
        <span id='lista' class="white-text">Aguardando escolha.
        </span>
      </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<script src="listarOnline.js?<?php echo time(); ?>"></script>