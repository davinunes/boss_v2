$(document).ready(function(){
    $('select').formSelect();
});

$(document).on('click', '#btn', function(){
    olt = $("#OLT").val();
	pon = $("#SLOT").val();
	slot = $("#PONN").val();
	
	url = 'listaOnline.php?OLT='+olt+'&SLOT='+slot+'&PONN='+pon;
	
	$.post(url, "", function(data){
	   
		$("#lista").html(data);

	});
	console.log(url);
});