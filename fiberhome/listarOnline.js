$(document).ready(function(){
    $('select').formSelect();
	$('select#OLT').change();
	
});

$(document).on('click', '#btn', function(){
	
	$('#btn').html('<div class="preloader-wrapper small active">      <div class="spinner-layer spinner-blue">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-red">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-yellow">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-green">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>    </div>');
	
	
    olt = $("#OLT").val();
	vlan = $("#OLT option:selected").attr("vlan");
	slot = $("#SLOT").val();
	pon = $("#PONN").val();
	
	url = 'listaOnline.php?OLT='+olt+'&SLOT='+slot+'&PONN='+pon+'&VLANOLT='+vlan;
	
	$.post(url, "", function(data){
	   
		$("#lista").html(data);
		$('#btn').html('Atualizar');

	});
	console.log(url);
});
$(document).on('change', 'select#OLT', function(){
	
	$('#slt').html('<div class="preloader-wrapper small active">      <div class="spinner-layer spinner-blue">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-red">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-yellow">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-green">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>    </div>');
	
	
    olt = $("#OLT").val();
	
	url = 'slotsAtivos.php?OLT='+olt;
	
	$.post(url, "", function(data){

		$("#SLOT").html(data);
		$('select').formSelect();

		$('#slt').html('Slot');
	});
	console.log(url);	
	
});
$(document).on('change', 'select#SLOT', function(){
	let idx = $(this).val();
	console.log(idx);
	let txt = $("select#SLOT option[value="+idx+"]")[0].text.split("[")[1].split("]")[0];
	
	if(txt == "GC8B"){
		$("#PONN").html("<option value='1'>1</option>\n<option value='2'>2</option>\n<option value='3'>3</option>\n<option value='4'>4</option>\n<option value='5'>5</option>\n<option value='6'>6</option>\n<option value='7'>7</option>\n<option value='8'>8</option>\n");
	}else{
		$("#PONN").html("<option value='1'>1</option>\n<option value='2'>2</option>\n<option value='3'>3</option>\n<option value='4'>4</option>\n<option value='5'>5</option>\n<option value='6'>6</option>\n<option value='7'>7</option>\n<option value='8'>8</option>\n<option value='9'>9</option>\n<option value='10'>10</option>\n<option value='11'>11</option>\n<option value='12'>12</option>\n<option value='13'>13</option>\n<option value='14'>14</option>\n<option value='15'>15</option>\n<option value='16'>16</option>\n");
	}
	
	$('select').formSelect();
	
});

$(document).on('click', '.togglavel1', function(){
	let classe = "."+$(this).attr('swap');
	console.log(classe);
	if($(classe).is(":visible")){
		$(classe).click();
	}else{
		$(classe).toggle();
	}
    
});

$(document).on('click', '.togglavel2', function(){
	let classe = "."+$(this).attr('swap');
	console.log(classe);
	$(classe).toggle();
    
});

$(document).on('click', '#isup', function(){

	$(".isup").toggle();
    
});

$(document).on('click', '.prune', function(){
    rmac = $(this).attr('mac');
	rolt = $(this).attr('olt');
	trupa = "#"+$(this).attr('trupa');
	
	rurl = "exterminar.php?olt="+rolt+"&mac="+rmac;
	console.log(rurl);
	
	var exterminar = confirm('Voc? tem certeza que quer excluir a ONU '+rmac+' da OLT?');
	if(exterminar){
		console.log(rurl);
		M.toast({html: 'Excluindo, Aguarde um instante!!!', classes: 'rounded'});
		$.post(rurl, "", function(aba){
			console.log(aba);
			M.toast({html: aba, classes: 'rounded'});
			$(trupa).remove();
			tele("ONU "+rmac+" foi deletada da OLT");
		});
	}
});

$(document).on('click', '.update', function(){
    rmac = $(this).attr('mac');
	pon = $(this).attr('pon');
	
	M.toast({html: 'Esse bot?o Ainda n?o Funciona. No futuro ir? corrigir no ixc o endere?o correto da ONU: '+pon, classes: 'rounded'});
	
});

function tele(msg){
	msg = encodeURIComponent(msg);
	url = 'database.php?metodo=telegram&mensagem='+msg;
	console.log(url);
	$.post(url, "", function(dtx){
		console.log(dtx);
	});
}


// $("select#SLOT option")[0].text.split("[")[1].split("]")
