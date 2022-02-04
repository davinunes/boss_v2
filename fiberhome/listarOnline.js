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
		// console.log(data);
		// console.log(data[9]);
		
		$.each($("select#SLOT option"),function( index, value ){
			let op = $(this).val();
			if(data[op] == "----" || op == 9 || op == 10){
				console.log(data[op]);
				$(this).attr('disabled','disabled');
				$(this).addClass('hide');
			}else{
				$(this).removeAttr('disabled');
				$(this).removeClass('hide');
			}
			
		});
		$.each($("select#SLOT option"),function( index, value ){
			let op = $(this).attr('disabled');
			if(typeof op == undefined){
				$(this).attr('selected','selected');
			}
		});
		$('select').formSelect();

	$('#slt').html('Slot');
	},"json");
	console.log(url);
});

