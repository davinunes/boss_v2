function moeda(){
        var myAudio = new Audio('moeda.mp3');
		myAudio.play();
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


// Quando clicar no botão enviar
$(document).on('click', '#filtrar', function(){
	dados={
		dti:$("#dti").val(),
		dtf:$("#dtf").val(),
		veiculo_id:$("#veiculo_id").val()
		
	}
	console.log(dados);
	$("#filtrar").html('<div class="preloader-wrapper small active">      <div class="spinner-layer spinner-blue">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-red">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-yellow">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-green">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>    </div>');
	$.post('relfiltro.php', dados, function(retorno) {
		$("#rel").html(retorno);
		$("#filtrar").html("Filtrar");
	});

});
$(document).on('dblclick', '.animate', function(){
	$(this).animate({height:"100%"});
});

$(document).on('click', '.animate', function(){
	$(this).animate({height:"120px"});
});

