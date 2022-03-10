 // Iniciando biblioteca
var resize = new window.resize();
resize.init();
 
// Declarando variáveis
var imagens;
var imagem_atual;
var imagens_postadas;
 
 $(document).ready(function(){
	 
	 // ´Ponteiro para só mostrar o botão se a OS não estiver fechada
	 var permiteImagem = $("#coment").attr("status");
	 
	 // Ponteiro para verificar se quem está logado é adm
	 var adm = $("#coment").attr("adm");
	 if(permiteImagem == "EN" || permiteImagem == "F"){
		$('#modalup').hide();
	}
	if(adm == "1" ){
		$('#modalup').show();
			if(permiteImagem == "F"){
				$('#modalup').hide();
			}
	}
	 
	 
	 //Detecta se a página está aberta em um celular
	 var cell = detectar_mobile();
	 if(cell){ //Verifica se é celular
		 console.log("Isto é um celular");
		 // M.toast({html: 'Dispositivo Movel', classes: 'rounded'});
		 
			
			$("#principio").removeClass("container");
			
			$("p").css({
				"font-size"		: 	"2.5em",
				"line-height"	:	"110%",
				"text-align"	:	"justify"
				
			});
			
			$("nav>li>a").css({
				"font-size"		: 	"3em"
				
			});
			
			$(".modal").css({
				"width"		: 	"80%",
				"min-height"	:	"60%"
				
			});
			
			$("input").css({
				"font-size"		: 	"2.5em",
				"line-height"	:	"110%",
				"text-align"	:	"justify"
				
			});
			$(".comentar").css({
				"height"		: 	"55%",
				"font-size"		: 	"2.5em",
				"line-height"	:	"110%",
				"text-align"	:	"justify"
				
			});
			$(".titulo").css({
				"font-size"		: 	"2.5em",
				"line-height"	:	"110%",
				"text-align"	:	"justify"
				
			});
	 }else{
		 console.log("Nao é um celular");
			$("p").css({
				"font-size"		: 	"20px",
				"line-height"	:	"110%",
				"text-align"	:	"justify"
			
			});
			$(".comentar").css({
				"height"		: 	"90px",
				"line-height"	:	"110%",
				"text-align"	:	"justify"
					
			});
			
			$(".modal").css({
				"width"		: 	"80%",
				"min-height"	:	"80%"
				
			});
	 }
	 
    $('.modal').modal();
	$('select').formSelect();
	
	//Verifica o estado da OS para mudar a cor de fundo do relatório
	var state = $("#estado").attr("state");
	//console.log(state);
	if(state == "F"){
		$("#conteudo").addClass("purple accent-1");
	}else if(state == "EN"){
		$("#conteudo").addClass("blue lighten-1");
	}
	
	
	// Quando selecionado as imagens, envia elas para a OS
    $('#imagem').on('change', function () {
        enviar();
    });
	
	// Quando selecionado as imagens, envia elas para a OS
    $('#imagem2').on('change', function () {
		avatar = $('#imagem2')[0].files;
        console.log(avatar[0]);
		resize.photo(avatar[0], 400, 'dataURL', function (imagem) {
					orient = 1;
					var func = $("#coment").attr("func");
					var colaborador = $("#colaborador").val();
					if(typeof colaborador === 'undefined'){
						var tec = $("#coment").attr("tec");
						var update = "sim";
					}else{
						var tec = colaborador;
						var update = colaborador == func ? "sim" : "nope";
					}
					
					var dados = {
						colaborador: colaborador,
						update: update,
						imagem: imagem,
						metodo: "avatar",
						func: func,
						tec: tec
					}
					console.log(dados);
					// Salvando imagem no servidor
					$.post('fotos.php', dados, function(retorno) {
					console.log("retorno: ");
					console.log(retorno);
					});
					M.toast({html: 'Avatar alterado com sucesso!', classes: 'rounded'});
					setTimeout(function(){
						  window.location.reload(true);
						}, 1550);
					
			 
				});
    });
	
	//Alterar o tecnico da OS
	$(".linkar").click(function(){
		var dados = {
			metodo:"mudaTec",
			os : $(this).attr("os"),
			func: $(this).attr("func")
		}
		$.post( "database.php", dados, function( retorna ) {
		//	console.log(retorna);
			if (retorna === "ok"){
				M.toast({html: 'SUCESSO!!!', classes: 'rounded'});
				$("#btn-clear").click();
				window.location.reload(true);
			}
	
		});
		
	});
	
	//Puxa as OS antigas
	$(".legacy").click(function(){
		let login = $(this).attr("login");
		console.log(login);
		return;
		var dados = {
			login : login
		}
		$.post( "historico.php", dados, function( retorna ) {
	//		console.log(retorna);
			if (retorna != ""){
				$("#msgs").html(retorna);
				$('html,body').scrollTop(0);
				M.toast({html: 'SUCESSO!!!', classes: 'rounded'});
				var cell = detectar_mobile();
						 if(cell){ //Verifica se é celular
							 console.log("Isto é um celular");
					//		 alert("Celular!");
								$("#principio").removeClass("container");
								
								$("p").css({
									"font-size"		: 	"2.5em",
									"line-height"	:	"110%",
									"text-align"	:	"justify"
									
								});
								
								$("nav>li>a").css({
									"font-size"		: 	"3em"
									
								});
								
								$(".modal").css({
									"width"		: 	"80%",
									"min-height"	:	"60%"
									
								});
								
								$("input").css({
									"font-size"		: 	"2.5em",
									"line-height"	:	"110%",
									"text-align"	:	"justify"
									
								});
								$(".comentar").css({
									"height"		: 	"55%",
									"font-size"		: 	"2.5em",
									"line-height"	:	"110%",
									"text-align"	:	"justify"
									
								});
								$(".titulo").css({
									"font-size"		: 	"2.5em",
									"line-height"	:	"110%",
									"text-align"	:	"justify"
									
								});
						 }else{
							 console.log("Nao é um celular");
								$("p").css({
									"font-size"		: 	"20px",
									"line-height"	:	"110%",
									"text-align"	:	"justify"
								
								});
								$(".comentar").css({
									"height"		: 	"90px",
									"line-height"	:	"110%",
									"text-align"	:	"justify"
										
								});
								
								$(".modal").css({
									"width"		: 	"80%",
									"min-height"	:	"80%"
									
								});
						 }
				
			}
	
		});
		$(this).remove();
	});
	
	$("#reagendar").click(function(){
		console.log("reagendar");
		var dados = {
			metodo:"reabrir",
			os : $(this).attr("os")
		}
		$.post( "database.php", dados, function( retorna ) {
			if (retorna === "ok"){
				M.toast({html: 'SUCESSO!!!', classes: 'rounded'});
				$("#btn-clear").click();
				window.location.reload(true);
			}
	
		});
		
	});
	
	$(".brand-logo").click(function(){
		$(".colapso").toggle();
		
	});
	
	
	$(".dia").click(function(){
		var dados = {
			metodo:"agenda",
			os : $(this).attr("os"),
			data: $(this).attr("data")
		}
		$.post( "database.php", dados, function( retorna ) {
			console.log(retorna);
			if (retorna === "ok"){
				M.toast({html: 'SUCESSO!!!', classes: 'rounded'});
				$("#btn-clear").click();
				window.location.reload(true);
			}
	
		});
		
	});
	
	
	$("#comentar").click(function(){
		console.log("Clicou");
		
		var stats = $("#coment").attr("status");
		var msg = $("#coment").val();
		var os = $("#coment").attr("os");
		var tec = $("#coment").attr("tec");
		var func = $("#coment").attr("func");
		var dados = {
			metodo:"close",
			msg : msg,
			os: os,
			tec: tec
			
		}
		if(stats != "F"){
			if(stats != "EN" ||  adm == "1" ){
				$.post( "database.php", dados, function( retorna ) {
				console.log(retorna);
				if (retorna === "ok"){
					M.toast({html: 'SUCESSO!!!', classes: 'rounded'});
					$("#btn-clear").click();
					window.location.reload(true);
				}
			
				});
			}
		}else{
			alert("OS ja foi encaminhada ou fechada!");
		}
		console.log(dados);

	});
	
	$("#encaminhar").click(function(){
	var encaminhar = confirm('Depos que encaminhar, nao podera mais postar mensagem! Continuar?');
	if (encaminhar){
		
		console.log("Clicou em encaminhar");
		
		var stats = $("#coment").attr("status");
		var msg = $("#coment").val();
		var tec = $("#coment").attr("tec");
		var func = $("#coment").attr("func");
		if(msg == ""){
			alert("Precisa escrever uma mensagem!");
			return;
		}
		var os = $("#coment").attr("os");
		var tec = $("#coment").attr("tec");
		var dados = {
			metodo:"close",
			msg : msg,
			os: os,
			tec: tec,
			func: func,
			enc: "sim"
			
		}
		if(stats != "EN" && stats != "F"){
			$.post( "database.php", dados, function( retorna ) {
			console.log(retorna);
			if (retorna === "ok"){
				M.toast({html: 'SUCESSO!!!', classes: 'rounded'});
				$("#btn-clear").click();
				window.location.reload(true);
			}
			
		});
		}else{
			alert("OS ja foi Encaminhada ou Fechada!");
		}
		console.log(dados);
	}

	});
	
	$("#adm_fechar").click(function(){
	var encaminhar = confirm('Depos que Fechar, não mais poderá comentar por esta plataforma! Continuar?');
	if (encaminhar){
		
		console.log("Clicou em adm-fechar");
		
		var stats = $("#coment").attr("status");
		var msg = $("#coment").val();
		var tec = $("#coment").attr("tec");
		var func = $("#coment").attr("func");
		if(msg == ""){

			msg = "Realizando fechamento da OS!";
			
		}
		var os = $("#coment").attr("os");
		var tec = $("#coment").attr("tec");
		var dados = {
			metodo:"close",
			msg : msg,
			os: os,
			func:func,
			tec: tec,
			enc: "fechar"
			
		}
		if(stats != "F"){
			$.post( "database.php", dados, function( retorna ) {
			console.log(retorna);
			if (retorna === "ok"){
				M.toast({html: 'SUCESSO!!!', classes: 'rounded'});
				$("#btn-clear").click();
				window.location.reload(true);
			}
			
		});
		}else{
			alert("OS ja foi Fechada!");
		}
		console.log(dados);
	}

	});
	
	$("#changeOs").click(function(){
		$.post( "os.php", {troca:"1"}, function( retorna ) {
			console.log("MudarOS");
			window.location.reload(true);
		});
	});
	
	$("#2changeOs").click(function(){
		$.post( "os.php", {troca:"1"}, function( retorna ) {
			console.log("MudarOS");
			window.location.reload(true);
		});
	});
	
	$("#mudasenha").click(function(){
		$.post( "senha.php", {vipw:"1"}, function( retorna ) {
			console.log("MudarSenha");
			window.location.reload(true);
		});
	});
	
	$("#desiste").click(function(){
		$.post( "senha.php", {desiste:"1"}, function( retorna ) {
			console.log("MudarSenha");
			window.location.reload(true);
		});
	});
	
	$("#logout").click(function(){
		$.post( "index.php", {logout:"1"}, function( retorna ) {
			console.log("Sair do Sistema");
			window.location.reload(true);
		});
	});
	
	$("#alteraSenha").click(function(){
		var senha = $("#senha").val();
		var novasenha = $("#novasenha").val();
		var confirmar = $("#confirmar").val();
		
		if(novasenha == confirmar){
			var dados = {
				senha: senha,
				novasenha: novasenha
			}
			$.post( "senha.php", dados, function( retorna ) {
				console.log(retorna);
				window.location.reload(true);
			});
		}else{
			alert("Nova senha difere da confirmacao");
		}
		
	});
	
	$(this).keydown(function(e) {
		var lista = $("#listadodia").attr("status");
		console.log("Lista do Dia: "+lista);
		console.log("Tecla pressionada: "+e.which);
		var digitar = $("#fechar").attr("class");
		console.log(digitar);
		if(e.which == 37) {
			//Seta para esquerda
			if(lista == 0){
				var a1 = $("#aanterior").attr("href");
			}else if (lista == 1){
				var a1 = $("#diminue").attr("href");
			}
			
			console.log("Acessar: "+a1);
			if(typeof a1 === 'undefined'){
				M.toast({html: 'Já é a primeira OS do dia!', classes: 'rounded'});
			}else if(digitar != "modal open"){
				location.href = a1;
			}
			
			
		}else if(e.which == 39){
			//Seta para direita
			console.log("Direita");
			if(lista == 0){
				var a1 = $("#aproxima").attr("href");
			}else if (lista == 1){
				var a1 = $("#aumenta").attr("href");
			}
			console.log("Acessar: "+a1);
			if(typeof a1 === 'undefined'){
				M.toast({html: 'Já é a ultima OS do dia!', classes: 'rounded'});
			}else if(digitar != "modal open"){
				location.href = a1;
			}
		}else if(e.which == 38){
			//Seta para cima
			if(lista == 0){
				$("#legacy").click();
			}else if (lista == 1){
				if(digitar != "modal open"){
					location.href = "index.php";
				}
			}
		}
	});

  });
  

  
$(document).on({
    'custom/paste/images': function(event, blobs) {
		orient = 1;
		console.log("Colando print na OS");
		var lista2 = $("#listadodia").attr("status");
//		var orient = 1;
		//Verifica o estado da OS para mudar de cor
		var state2 = $("#estado").attr("state");
		console.log(state2);
		if(state2 != "F"){
			if(lista2 == 0){
		//	console.log(blobs[0]);
				resize.photo(blobs[0], 900, 'dataURL', function (imagem) {
//					console.log(imagem);
					
					var stats = $("#coment").attr("status");
					var os = $("#coment").attr("os");
					var tec = $("#coment").attr("tec");
					var dados = {
						imagem: imagem,
						metodo: "foto",
						os: os,
						tec: tec
					}
					console.log(dados);
					// Salvando imagem no servidor
					$.post('fotos.php', dados, function(retorno) {
//					console.log("retorno: ");
//					console.log(retorno);
					});
					M.toast({html: 'Print Inserido com Sucesso!!! Atualize a página para visualizar!', classes: 'rounded'});
					//window.location.reload(true);
			 
				});
		}
		}
		
    }
});
  
 $( window ).on( "swipeleft", function( event ) 
  {
    M.toast({html: 'Esquerda', classes: 'rounded'});
  } );
  
 $( window ).on( "swiperight", function( event ) 
  {
    M.toast({html: 'Direira', classes: 'rounded'});
  } );
  


function getLocation() {
	var x = document.getElementById("demo");
	console.log(x);
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
        x.innerHTML = "GPS Não é suportado pelo navegador";

    }
}

function showPosition(position) {
	var x = document.getElementById("demo");
	x.innerHTML = "";
    x.innerHTML = "Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude;
	$("#demo").attr('latitude', position.coords.latitude);
	$("#demo").attr('longitude', position.coords.longitude);
}

function showError(error) {
	var x = document.getElementById("demo");
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}

  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems, {
      direction: 'left',
      hoverEnabled: false
    });
  });