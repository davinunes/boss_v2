$(document).ready(function(){
	$('.modal').modal();
	
	$(".editar").click(function(){
//		M.toast({html: 'Formulario Atualizado!!!', classes: 'rounded'});
		var id = $(this).attr("id");
		var id_usuario = $(this).attr("id_usuario");
		var username = $(this).attr("username");
		var value = $(this).attr("value");
		
		$("#id").val(id);
		$("#id_usuario").val(id_usuario);
		$("#username").val(username);
		$("#value").val(value);
		$("#crud").text("atualizar");
	});
	
	$("#limpar").click(function(){
//		M.toast({html: 'Formulario Atualizado!!!', classes: 'rounded'});
	
		$("#id").val("");
		$("#id_usuario").val("");
		$("#username").val("");
		$("#value").val("");
		$("#crud").text("enviar");
	});
	
	$(".pesquisa_login").keyup(function(){
		
		//Recuperar o valor do campo
		var pesquisa = $(this).val();
		//console.log(pesquisa);
		var metodo = "pesquisar_login";
		//Verificar se tem algo digitado
		if(pesquisa.length > 2){
			var dados = {
				palavra : pesquisa,
				metodo : metodo
				}
			$.post('database.php', dados, function(retorna){
				//Mostra dentro da div os resultado obtidos 
				$(".lista_login").html(retorna);
				// console.log(retorna);
			});
		}
	});
	
	$("#crud").click(function(){
		
		var dados = {
			metodo : "gs_crud",
			id : $("#id").val(),
			id_usuario : $("#id_usuario").val(),
			username : $("#username").val(),
			value : $("#value").val(),
			crud : $("#crud").text()
			}
			if(dados["username"] == "" || dados["id_usuario"] == ""){
				M.toast({html: 'Uaii!!!', classes: 'rounded'});
			}else{
				console.log(dados);
				$.post('database.php', dados, function(retorna){
					if(retorna == "ok"){
						M.toast({html: 'Feito!!!', classes: 'rounded'});
						setTimeout(function(){
						  window.location.reload(true);
						}, 2500);
						
					}
				});
			}
			
		
	});
	
	$(".coa").click(function(){
		
		var dados = {
			atributo : $(this).attr("attribute"),
			login : $(this).attr("username"),
			op : $(this).attr("op"),
			valor : $(this).attr("value")
			}
			
			console.log(dados);
			$.post('gs.php?COA=1', dados, function(retorna){
				
					M.toast({html: retorna, classes: 'rounded'});
					setTimeout(function(){
					 // window.location.reload(true);
					}, 2500);
					
				
			});
			
			
		
	});
	
	
	
	$(".deletar").click(function(){
				
		var dados = {
			metodo : "gs_crud",
			id : $(this).attr("id"),
			crud : "remover"
			}
			var apagar = confirm('Deseja REALMENTE EXCLUIR o registro de '+$(this).attr("username")+" ?");
			if(apagar){
				
				console.log(dados);
				$.post('database.php', dados, function(retorna){
					if(retorna == "ok"){
						M.toast({html: 'Feito!!!', classes: 'rounded'});
						setTimeout(function(){
						  window.location.reload(true);
						}, 2500);
					}
				});
			}
			
		
	});
});

$(document).on('click', '.escolher_login', function(){
	// console.log("Clicou em um Botão editar na Trupa3o");
		var id_usuario = $(this).attr("id");
		var username = $(this).attr("login");
		

		$("#id_usuario").val(id_usuario);
		$("#username").val(username);
		$("#value").val('"IPV4(100M,100M)"');
		$("#search2").val("");
		$(".lista_login").html("");
		
	
});