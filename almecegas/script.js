$(document).on('click', '.sinal', function(){
	let esse = $(this);
	let onu = $(esse).attr("onu");
	let url = $(esse).attr("href2");
	// $(esse).removeClass("btn");
	$(esse).html('<div class="preloader-wrapper small active">      <div class="spinner-layer spinner-blue">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-red">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-yellow">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-green">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>    </div>');
	// $(esse).addClass("loader");
	console.log(onu);
	$.post(url, "", function(aaa){
		console.log(aaa);
		$(onu).html(aaa);
		// $(esse).addClass("btn");
		$(esse).html("Obter Dados");
		// $(esse).removeClass("loader");
	});
});