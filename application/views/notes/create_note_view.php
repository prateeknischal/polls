<div class = "row">
	<form class = "form-horizontal" action = "insert_new_post" method = "post" id = "create_note">
		<div class = "form-group">
			<label class = "control-label col-sm-2" style = "color : white;">Title</label>
			<div class = "col-sm-6">
				<input type = "text" class = "form-control" name = "title" placeholder="Title" required="required">
			</div>
		</div>

		<div class = "form-group">
			<label class = "control-label col-sm-2" style = "color : white;">content</label>
			<div class = "col-sm-6">
				<textarea rows = 5 class = "form-control" name = "content" placeholder="Content" required="required"></textarea>
			</div>
		</div>

		<div class = "form-group">
			<div style = "display:none;">
				<input name = "handles" id = "handles">
			</div>
		</div>

		<div class = "form-group">
			<div class = "col-sm-offset-2 col-sm-10">
				<button class = "btn btn-success col-sm-2" id = "submit">Submit</button>
			</div>
		</div>
	</form>

	<div class = "row">
		<div class = "col-sm-offset-2 col-sm-6">
			<div id = "not_found" class = "alert alert-danger" style = "display:none;"> 
				No user found
				<!-- <button class = "close" type = "button" data-dismiss="alert">×</button> -->
			</div>
			<div id = "found" class = "alert alert-success" style = "display:none;">
				User added Successfully !
				<!-- <button class = "close" type = "button" data-dismiss="alert">×</button> -->
			</div>
		</div>
	</div>

	<div class = "row"> 
		<label class = "col-sm-offset-2 col-sm-2" style="color:#FFFFFF">Share this with other users</label>
	</div>

	<div class = "row">
		<div class = "col-sm-offset-2 col-sm-2">
			<input type = "text" id = "assoc_handle" class = "form-control">
		</div>
		<div class = "col-sm-2">
			<button id = "add_user" class = "btn btn-default"> Add </button>
		</div>
	</div>


	<div class = "row" style = "padding-top : 15px;">
		<div class = "col-sm-offset-2 col-sm-6">
			<textarea rows = 2 id = "added_users" class = "form-control" disabled="disabled"></textarea>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		BASE_URL = "http://localhost/polls/index.php/";
		var assoc_user_handles = new Array(); 

		$('#add_user').click(function(){
			var handle = $('#assoc_handle').val();
			$.ajax({
				type : "POST",
				url  : BASE_URL + "notes/get_user_details",
				data : {
					'assoc_handle' : handle
				},
				success : function (result){
					if (result == "__no_user_found__"){
						$('#not_found').show();
					}
					else{
						var j = JSON.parse(result);
						assoc_user_handles.push(j.handle);

						var list = $('#added_users').val();
						list = list + j.handle +",";
						$('#added_users').val(list);
						$('#found').show();
					}
					$('#assoc_handle').val('');
				},
				error : function(err){
					alert(err.responseText);
				}
			});
		});

		$('#assoc_handle').keypress(function(){
			$('#found').hide();
			$('#not_found').hide();
		});

		$('#submit').click(function(){
			$('#handles').val(assoc_user_handles);
			$('#create_note').submit();
		});
	});
</script>