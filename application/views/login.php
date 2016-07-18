<div id = "login_fail" class = "row" style="display:none;">
	<div class = "col-sm-offset-2 col-sm-5">
		<div class = "alert alert-danger alert-dismissable">
			<button class="close" type="button" data-dismiss="alert">×</button>
			<strong>Incorrect Handle or Password</strong>
		</div>
	</div>
</div>
</div>
<form class = "form-horizontal" action = "validate_login" method = "post">
	<div class = "container-fluid">
		<div class = "form-group">
			<label class = "col-sm-2 control-label" style = "color : white;">Handle</label>
			<div class = "col-sm-3">
				<input name = "handle" type = "text" class = "form-control" placeholder = "@handle" id = "handle">
			</div>
		</div>

		<div class = "form-group">
			<label class = "col-sm-2 control-label" style = "color : white;">Password</label>
			<div class = "col-sm-3">
				<input name = "password" type = "password" class = "form-control" placeholder = "******" id = "password">
			</div>
		</div>
	</div>
</form>

<div class = "col-sm-offset-2 col-sm-6">
	<button type = "button" class = "btn btn-primary" id = "login">Sign In</button>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		BASE_URL = "http://localhost/polls/index.php/"; 
		/* reset the login fail notification */
		$('#handle').keypress(function(){
			$('#login_fail').hide();
		});
		/*reset the login fail notification */
		$('#password').keypress(function(){
			$('#login_fail').hide();
		});

		$('#login').click(function(){
			var handle = $('#handle').val();
			var password = $('#password').val();
			$.ajax({
				type : "POST",
				url  : BASE_URL + "welcome/validate_user_login",
				data : {
					'handle'   : handle,
					'password' : password
				},
				success : function(result){
					if (result == "false"){
						$('#handle').val('');
						$('#password').val('');
						$('#login_fail').show();
					}
					else{
						window.location = BASE_URL + "notes/dash";
					}
				},
				error : function (err){
					alert("error loging in : " + err.responseText);
				}
			});
		});
	});
</script>