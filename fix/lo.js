

	 $("#login").click(function(){
		 
		var user = $('#username').val(); 
		var pass = $('#password').val();

  var data = $("#loginform").serialize();
 //  $("html").addClass("loading");
		$.ajax({
			data: data,
			type: "post",
			url: "lo.php",
			success: function(dataResult){
					var dataResult = JSON.parse(dataResult);
					if(dataResult.statusCode==200){
					    var success = (dataResult.success);
					    
					    
					}else if(dataResult.statusCode==201){
					    var exception = (dataResult.exception);
					    alert(exception);
					    
					}else{
					    var error = (dataResult.error);
					    alert(error);
					    
					}
			}
		});

    });


		