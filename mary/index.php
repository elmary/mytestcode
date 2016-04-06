<html>
	<head>
		<title>My Test Page</title>
		<link rel = "stylesheet" href = "styles.css" />
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>	
		<script src="js/bootstrap.js"></script>
		<link rel="stylesheet" href="css/bootstrap.css"  type="text/css"/>
	</head>
	
	<body>
		<div id="form-content" class="modal hide fade in add-message">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">x</a>
				<h3>Send me a message</h3>
			</div>
			<div class="modal-body">
				<form class="contact message-form" name="contact">
					<div class = "errmsg"></div>
					<label>Name:</label>
					<input type = "text" name = "name" class = "mname"/>
					
					<label>Message:</label>
					<textarea name = "message" rows = "5" class = "message"></textarea>
					
					<label>Date:</label>
					<input type = "text" name = "msg_date" class = "date mdate" />
				</form>
			</div>
			<div class="modal-footer">
				<input class="btn btn-success" type="button" value="Send!" id="submit">
				<input class="btn btn-success hide" type="button" value="Update!" id="update">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
			</div>
		</div>
		<div id="thanks"><p><a data-toggle="modal" href="#form-content" class="btn btn-primary btn-large">Send Message</a></p></div>
		
		<div class = "displayMessages"></div>
		<script>
		$(".date").datepicker();
		$("#submit").click(function(){
			
			var the_url = "functions.php?func=add";
			var mform = $(".message-form").serialize();
			
			$.ajax({
				type: 'POST',
				url: the_url,
				data: mform,
				dataType: 'json',
				cache:true,
				success: function(data) {
					if(data[0] == "saved")
					{
						alert("success");
						displayData();
						$(".message-form").trigger("reset");
						$(".add-message").hide();
					}else
					{
						$(".errmsg").html(data[0]);
					}
					
					console.log(data);
				},
				error:function(x)
				{
					console.log(x.responseText);
				}
			});
			return false;
		})
		
		displayData();
		function displayData()
		{
			var the_url = "functions.php?func=display";
			$.ajax({
				type: 'POST',
				url: the_url,
				dataType: 'json',
				cache:true,
				success: function(data) {
					$(".displayMessages").html(data[0]);
				},
				error:function(x)
				{
					console.log(x.responseText);
				}
			});
		}
		
		$(".displayMessages").on("click",".delete",function(){
			if(confirm("Are you sure to delete this message?"))
			{
				var mid = $(this).attr("mid");
				var the_url = "functions.php?func=delete";
				$.ajax({
					type: 'POST',
					url: the_url,
					data:{"id":mid},
					cache:true,
					success: function(data) {
						displayData();
					},
					error:function(x)
					{
						console.log(x.responseText);
					}
				});
			}
		})
		
		$(".displayMessages").on("click",".edit",function(){
			var tparent = $(this).parent().parent().parent();
			var name = tparent.find("td:first-child").text();
			var message = tparent.find("td:nth-child(2)").text();
			var mdate = tparent.find("td:nth-child(3)").text();
			var mid = $(this).attr("mid");
			
			
			$(".message-form .mname").val(name);
			$(".add-message .message").val(message);
			$(".add-message .mdate").val(mdate);
			
			$(".date").datepicker();
			
			$("#submit").hide();
			$("#update").show();
			$(".add-message h3").html("Update Message");
			
			$("body").on("click","#update",function(){
				var the_url = "functions.php?func=edit";
				var mform = $(".message-form").serialize();
				$.ajax({
					type: 'POST',
					url: the_url,
					data: mform+"&mid="+mid,
					dataType: 'json',
					cache:true,
					success: function(data) {
						if(data[0] == "saved")
						{
							alert("success");
							displayData();
							$(".message-form").trigger("reset");
							$("#submit").show();
							$("#update").hide();
							$(".add-message h3").html("Send Me a Message");
							$(".add-message").hide();
						}else
						{
							$(".errmsg").html(data[0]);
						}
						
						console.log(data);
					},
					error:function(x)
					{
						console.log(x.responseText);
					}
				});	
			});
		})
		
		$("#thanks").click(function(){
			$("#submit").show();
			$("#update").hide();
			$(".add-message h3").html("Send Me a Message");
			$(".message-form").trigger("reset");
		})
		</script>
	</body>
</html>


