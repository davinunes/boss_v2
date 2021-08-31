function moeda(){
        var myAudio = new Audio('moeda.mp3');
		myAudio.play();
}

function getLocation() {
	var x = document.getElementById("geo");
	// console.log(x);
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
        M.toast({html: 'Navegador não suporta GPS!!!', classes: 'rounded'});

    }
}

function showPosition(position) {
	// console.log(position);
	var x = document.getElementById("geo");
	x.innerHTML = "";
    x.innerHTML = "Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude +"<br>Precisão: " +position.coords.accuracy;
	$("#geo").attr('latitude', position.coords.latitude);
	$("#geo").attr('longitude', position.coords.longitude);
	$("#geo").attr('precisao', position.coords.accuracy);
}

function showError(error) {
	var x = document.getElementById("geo");
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "Usuário NEGOU permissão de gps."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Localização indisponivel."
            break;
        case error.TIMEOUT:
            x.innerHTML = "Tempo excedido."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "Não sei o que aconteceu."
            break;
    }
}

function detectar_mobile(){ 
 if( navigator.userAgent.match(/Android/i)
	 || navigator.userAgent.match(/webOS/i)
	 || navigator.userAgent.match(/iPhone/i)
	 || navigator.userAgent.match(/iPad/i)
	 || navigator.userAgent.match(/iPod/i)
	 || navigator.userAgent.match(/BlackBerry/i)
	 || navigator.userAgent.match(/Windows Phone/i)
	 ){
			return true;
		}
		else {
			return false;
		}
}

if(!detectar_mobile()){
	// Se não for celular
	$("#container").addClass("container");
	$("#camera_setup").addClass("hide");
	
}else{
	// Se for um celular
	$("td,th").css({"padding":"1px"});
	$("div, input").css({"font-size": "clamp(1em, 1em + 0.5vw, 1.5em)"});
	$("input,select").css({"height":"8rem"});
}

// Renova a geolocalização a cada 1,5 segundo
setInterval(function(){
	getLocation();
},1500);

// Quando clicar no botão enviar
$(document).on('click', '#enviar', function(){
	
	if(!detectar_mobile()){
		foto = "";
	}else{
		// clica em pausar caso o meliante não tenha clicado ainda
		if($(this).attr("onepause") == "0"){
			$("#btnPause").click();
		}
		foto = canvas.toDataURL("image/png");
	}
	dados = {  
		metodo: "insert",
		veiculo_id:$("#veiculo_id").val(),
		odometro:$("#odometro").val(),
		os:$("#os").val(),
		responsavel_id:$("#responsavel_id").attr("responsavel_id"),
		foto:foto,
		descritivo:$("#descritivo").val(),
		latitude:$("#geo").attr("latitude"),
		longitude:$("#geo").attr("longitude"),
		precisao:$("#geo").attr("precisao")
	}
	console.log(dados);
	if(dados.veiculo_id == null || dados.odometro == "" || dados.descritivo == ""){
		 M.toast({html: 'Preencha os dados do veiculo, odometro e descritivo antes de enviar!', classes: 'rounded'});
	}else{
		$.post('database.php', dados, function(retorno) {
			if(retorno == "ok"){
				moeda();
				setTimeout(function(){
					window.location.reload(true);
				}, 1550);
			}
			M.toast({html: retorno, classes: 'red rounded'});
		});
	}

});

$(".animate").bind('dblclick',function(){
    
	   $(this).animate({height:"100%"});
   
});
$(".animate").bind('click',function(){
    
       $(this).animate({height:"120px"});
      
});
