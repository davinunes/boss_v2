function tele(msg){
	msg = encodeURIComponent(msg);
	url = 'database.php?metodo=telegram&mensagem='+msg;
	console.log(url);
	$.post(url, "", function(dtx){
		console.log(dtx);
	});
}

function detectar_mobile() 
	{ 
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


$(document).ready(function(){
	
	$("td, th").css("padding", "5px");
	
	if(!detectar_mobile()){
		$("#card").addClass("container");
	}
	
	$(".buscar").click(function(){
		$("#resultado").html("");
		$("#olts").hide();
		$(".loader").show();
		var OLT = $(this).attr("olt");
		nome_olt = $(this).text();
		vlan = $(this).attr("vlan");
		transmissor = $(this).attr("transmissor");
		
		
		url = 'buscar.php?OLT='+OLT+'&vlan='+vlan+'&transmissor='+transmissor+'&nome_olt='+nome_olt;
		
		console.log(url);
		
		var ont = $.post(url, "", function(data){
		   
		   $(".loader").hide();
		   $("#resultado").html(data);
		   $("#olts").show();
			
		});
		
	});
	
	
	
});

$(document).on('click', '#texto', function(){
    $(".texto").toggle();
});

$(document).on('click', '.desprovisionar', function(){
    rmac = $(this).attr('mac');
	rolt = $(this).attr('olt');
	ridx = $(this).attr('id_tabela');
	rlgn = $(this).attr('login');
	rfnc = $(this).attr('tecnico');
	ross = $(this).attr('os');
	rpes = $(this).attr('nome');
	
	rurl = "exterminar.php?olt="+rolt+"&mac="+rmac+"&idx="+ridx+"&login="+rlgn+"&tecnico="+rfnc+"&os="+ross;
	console.log(rurl);
	
	var exterminar = confirm('Você tem certeza que quer excluir a ONU '+rmac+' do sistema e da OLT?');
	if(exterminar){
		console.log(rurl);
		M.toast({html: 'Excluindo, Aguarde um instante!!!', classes: 'rounded'});
		$.post(rurl, "", function(aba){
			console.log(aba);
			$("#ont"+ridx).remove();
			
			msg  = "*"+rpes+"*";
			msg += "\n``` excluiu a ONU cujo MAC "+rmac;
			msg += "\n estava associado ao login "+rlgn+" ```";
			
			tele(msg);
		});
	}
});

$(document).on('click', '#voltar', function(){
    $("#select").show();
	$("#comfirm").hide();
});

$(document).on('click', '#sinalonu', function(){
	url = $(this).attr('url');
	$(".loader").show();
	$.post(url, "", function(aaa){
		console.log(aaa);
		$('#log').append(aaa);
		$('#log').append("<br>");
		$(".loader").hide();
	});
});

$(document).on('click', '#doit', function(){
    // $("#select").hide();
	$("#comfirm").hide();
	$("table").hide();
	$(".loader").show();
	
		dados = {
			nome_olt : $("#_nome_olt").text(),
			olt : $("#_olt").text(),
			slot : $("#_slot").text(),
			pon : $("#_pon").text(),
			tipo : $("#_tipo").text(),
			mac : $("#_mac").text(),
			nome : $("#_login").text(),
			transmissor : $("#_transmissor").text(),
			vlan : $("#_vlan").text(),
			login : $("#_idlogin").text(),
			contrato : $("#_idcontrato").text(),
			perfil : $("#_perfil").text()
		}
		console.log(dados);
		
		
		lognaos  = "<p>Provisionamento de ONU:";
		lognaos += "<br> MAC: "+dados.mac;
		lognaos += "<br> OLT: "+dados.nome_olt;
		lognaos += "<br> Slot/PON: "+dados.slot+"-"+dados.pon;
		lognaos += "<br> Tipo: "+dados.tipo;
		lognaos += "<br> Vlan: "+dados.vlan;
		lognaos += "<br> Login: "+dados.nome;
		lognaos += "<br> Contrato: "+dados.contrato;
		lognaos += "<br> Perfil: "+dados.perfil;
		lognaos += "</p>";
		
		url = 'database.php?metodo=crud_onu';
		
		$.post(url, dados, function(data){
		   
		   if(data == "1"){
			   console.log(data);
			   $('#log').append("<pre>Cadastrado no Banco de Dados com Sucesso!</pre>");
			   url = 'database.php?metodo=soautoriza';
			   $.post(url, dados, function(data2){
				   console.log(data2);
				   $('#log').append("<pre>Onu autorizada como numero: "+data2+"</pre>");
					dados2 = {
						olt : $("#_olt").text(),
						slot : $("#_slot").text(),
						pon : $("#_pon").text(),
						onu_num : data2,
						vlan : $("#_vlan").text()
					}
					url = 'database.php?metodo=att_num_onu&numero='+data2+'&mac='+$("#_mac").text();
					$.post(url, "", function(data4){
						console.log(data4);

					});
					// Verifica se precisa ativar o contrato
					url = 'database.php?metodo=pre&id='+dados.contrato;
					$.post(url, "", function(data90){
						console.log(data90);
						$('#log').append(data90);

					});
					
					url = 'database.php?metodo=configurabridge';
						$.post(url, dados2, function(data3){
							console.log(data3);
							$('#log').append("<pre>"+data3+"</pre>");
							
							// $('#log').append("<pre>Aguarde 15 segundos enquanto verifio o sinal da dessa ONU!</pre>");
							
							// var onu200 = setTimeout(function(){

			
								urlsinal = '../speed.php?metodo=fibra&OLT='+dados.olt+'&SLOT='+dados.slot+'&mac='+dados.mac+'&PON='+dados.pon+'&ONU='+dados2.onu_num+'&OLTID='+dados.transmissor;
								console.log(url);

									if($('#os').attr('temos') == "1"){
										
										url = 'database.php?metodo=log';
										$("table").show();
										msg = {
											tabela : lognaos,
											tec : $('#os').attr('tec'),
											os : $('#os').attr('os')
										}
										
										$("table").hide();
										console.log(msg);
										$.post(url, msg, function(data20){
											console.log(data20);
										});
	
										
										mensagem  = "*"+$('#os').attr('colaborador')+"*";
										mensagem += "\n registrou na OS ["+msg.os+"](https://acessodf.net/boss?rel=1&os="+msg.os+")";
										mensagem += "\n que Provisionou a ONU "+dados.mac+" \n```";
										mensagem += "\n OLT: "+dados.nome_olt;
										mensagem += "\n PON: "+dados.slot+"-"+dados.pon;
										mensagem += "\n Tipo: "+dados.tipo;
										mensagem += "\n Vlan: "+dados.vlan;
										mensagem += "\n Login: "+dados.nome;
										

										mensagem += "\n```";
										
				
										
										tele(mensagem);
										
										$(".loader").hide();
										// $('#log').append(status_onu);
										$('#log').append("<pre>Pronto!\n</pre>");
										$('#log').append("<a class='btn hide' id='sinalonu' url='"+urlsinal+"'>Verificar Sinal da ONU</a>");
							
									}
								// });
							// },15200);
							// 
						});
							
			   });
		   }
			
		});
	
});

$(document).on('click', '.escolher_login', function(){
    $("#select").hide();
	$("#comfirm").show();
	login_id = $(this).attr('id');
	login = $(this).attr('login');
	id_contrato = $(this).attr('id_contrato');
	razao = $(this).attr('razao');
	tipo = $(this).attr('tipo');

	$('#_idlogin').text(login_id);
	$('#_login').text(login);
	$('#_idcontrato').text(id_contrato);
	$('#_tipo').text(tipo);

});

$(document).on('keyup', '#razao', function(){
	palavra = $(this).val();
    var dados = {
		metodo : "pesca_login",
		palavra : palavra
		
	}
	url = 'database.php';
	if(palavra.length < 3){
		
	}else{
		$.post(url, dados, function(data){
		   
		   $("#temporario").html(data);
			
		});
	}
});

$(document).on('click', '.provisionar', function(){
    olt = $(this).attr("olt");
	nome_olt = $(this).attr("nome_olt");
	mac = $(this).attr("mac");
	pon = $(this).attr("pon");
	slot = $(this).attr("slot");
	vlan = $(this).attr("vlan");
	tipo = $(this).attr("tipo");
	transmissor = $(this).attr("transmissor");
	url = 'selecionado.php?olt='+olt+'&mac='+mac+'&pon='+pon+'&slot='+slot+'&vlan='+vlan+'&transmissor='+transmissor+'&tipo='+tipo+'&nome_olt='+nome_olt;
	
	$.post(url, "", function(data){
		   
	   $("#saori").html(data);
		
	});
});