<div id = "password_alert" class = "row" style="display: none">
	<div class = "col-sm-offset-2 col-sm-5">
		<div class = "alert alert-danger alert-dismissible" role="alert">
			<button class="close" type="button" data-dismiss="alert">×</button>
			Passwords <strong>did not</strong> match, please enter the password again !
		</div>
	</div>
</div>

<div id = "handle_taken" class = "row" style="display: none">
	<div class = "col-sm-offset-2 col-sm-5">
		<div class = "alert alert-danger alert-dismissible" role="alert">
			<button class="close" type="button" data-dismiss="alert">×</button>
			handle is already taken, try something else
		</div>
	</div>
</div>

<div class = "row"> <!-- try to put a background image -->
<form class = "form-horizontal" action = "validate_sign_up_info" method = "post" id = "sign_up_form">
	<div class = "container">
		<div class = "form-group">
			<label class = "control-label col-sm-2" style="color:white;">User Name</label>
			<div class = "col-sm-3">
				<input type = "text" class = "form-control" name = "username" id = "username" placeholder = "username" required = "required">
			</div>
		</div>

		<div class = "form-group">
			<label class = "control-label col-sm-2" style="color:white;">Handle</label>
			<div class = "col-sm-3">
				<input type = "text" class = "form-control" name = "handle" id = "handle" placeholder="handle" required = "required">
			</div>
		</div>

		<div class = "form-group">
			<label class = "control-label col-sm-2" style="color:white;">Password</label>
			<div class = "col-sm-3">
				<input type = "password" class = "form-control _password" name = "password" required = "required" id = "pass">
			</div>
		</div>

		<div class = "form-group">
			<label class = "control-label col-sm-2" style="color:white;">Confirm</label>
			<div class = "col-sm-3">
				<input type = "password" class = "form-control _password" name = "confirm_password" required = "required" id = "cnf_pass">
			</div>
		</div>
	</div>
</form>

<div class = "col-sm-offset-3">
	<button type = "button" class = "btn btn-primary col-sm-2" id = "sign_up">Sign Up</button>
</div>
</div>

<div id = "success_modal" class = "modal fade">
	<div class = "modal-dialog">
		<div class = "modal-content">
			<div class = "modal-body">
				<p><strong>Account creation Successful !</strong></p>
				<div class="row">
					<div class = "col-sm-offset-9 col-sm-3">
						<button type = "button" class = "btn btn-info" data-dismiss="modal" id="redirect_home"> Close </button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		BASE_URL = "http://localhost/polls/index.php/"; 
		$("._password").keypress(function(){
			/* restore password error */
			$('#password_alert').hide();
		});

		$('#handle').keypress(function(){
			/* restore handle error */
			$('#handle_taken').hide();
		});

		$('#sign_up').on('click', function(){
			//validate input
			var username = $('#username').val();
			var handle = $('#handle').val();
			var pass = $('#pass').val();
			var cnf_pass = $('#cnf_pass').val();

			if (pass != cnf_pass){
				//check if password is correct
				$('#password_alert').show();
				$('#pass').val('');
				$('#cnf_pass').val('');
			}
			else{
				/*
					Need to use localhost instead of site_url
					because site_url function has the following output:
					http://127.0.0.1/APPNAME/index.php/ which is different 
					from the calling url http://localhost/APPNAME/index.php 
					so chrome interprets it as a CORS cross site ajax call 
					which is blocked for security
				*/

				
				$.ajax({
					type : "POST",
					url : BASE_URL + "welcome/validate_sign_up_info",
					data : {
						'username'  : username,
						'handle'    : handle,
						'password'	: pass
					},
					success : function(result){
						if (result == "ok"){
							$('#success_modal').modal({backdrop:'static'});
							$('#success_modal').modal('show');
						}
						else{
							/*
								Handle already taken, clear handle and passwords
								show handle error
							*/
							$('#handle').val('');
							$('#pass').val('');
							$('#cnf_pass').val('');
							$('#handle_taken').show();
						}
					},
					error : function(ts){
						alert("error " + ts.responseText);
					}
				});
			}
		});
		$('#redirect_home').click(function(){
			window.location = BASE_URL + "welcome/login";
		});
	});
</script>