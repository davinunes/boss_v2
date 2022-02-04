$(document).ready(function(){
    $('select').formSelect();
	$('select#OLT').change();
	
});

$(document).on('click', '#btn', function(){
	
	$('#btn').html('<div class="preloader-wrapper small active">      <div class="spinner-layer spinner-blue">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-red">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-yellow">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>      <div class="spinner-layer spinner-green">        <div class="circle-clipper left">          <div class="circle"></div>        </div><div class="gap-patch">          <div class="circle"></div>        </div><div class="circle-clipper right">          <div class="circle"></div>        </div>      </div>    </div>');
	
	
    olt = $("#OLT").val();
	slot = $("#SLOT").val();
	pon = $("#PONN").val();
	
	url = 'listaOnline.php?OLT='+olt+'&SLOT='+slot+'&PONN='+pon;
	
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

// $("select#SLOT option")[0].text.split("[")[1].split("]")
