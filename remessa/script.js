$(document).ready(function(){
	
	
	$(".remessa").click(function(){
		var remessa = $(this).text();
		
		url = 'inspetor.php?rem='+remessa;
		
		console.log(url);
		
		var ont = $.post(url, "", function(data){
		   
		   $("#inspetor").html(data);
			
		});
		
	});
	
	
	
});