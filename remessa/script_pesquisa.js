$(document).ready(function(){
	
	
	$("#name").keyup(function(){
		var remessa = $(this).val();
		
		url = 'boletos.php?rem='+remessa;
		
		console.log(url);
		
		var ont = $.post(url, "", function(data){
		   
		   $("#inspetor").html(data);
			
		});
		
	});
	
	
	
});