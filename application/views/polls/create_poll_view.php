<div class = "row">
	<form class = "form-horizontal" action = "insert_new_poll" method = "post" id = "create_poll">
		<div class = "form-group">
			<label class = "control-label col-sm-2" style = "color : white;">Title</label>
			<div class = "col-sm-6">
				<input type = "text" class = "form-control" name = "title" placeholder="Title" required="required">
			</div>
		</div>

		<div class = "form-group">
			<div class = "control-label col-sm-offset-2 col-sm-4">
				<input type = "text" class = "_opfield form-control" name = "op_1" id = "op_1" required="required" placeholder="AMD">
			</div>
		</div>

		<h3><span class="label label-primary col-sm-offset-4" style="background:#E91E63; border:#E91E63">Vs</span></h3>

		<div class = "form-group">
			<div class = "control-label col-sm-offset-2 col-sm-4">
				<input type = "text" class = "_opfield form-control" name = "op_2" id = "op_2" required="required" placeholder="nVidia">
			</div>
		</div>
		<div class = "form-group">
			<div style = "display:none;">
				<input name = "handles" id = "handles">
			</div>
		</div>
	</form>
</div>

<div class = "row">
	<div class = "col-sm-offset-3 col-sm-9">
		<button class = "btn btn-warning col-sm-2" id = "submit_poll" style="background: #512DA8; border: #512DA8;"> Submit </button>
	</div>
</div>

<div class = "row" style = "padding-top:50px;">
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
		<label class = "col-sm-offset-2 col-sm-2" style="color:#FFFFFF">Add your friends For Votes</label>
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

<div class = "row" style = "padding-top:10px">
	<div class = "col-sm-offset-2 col-sm-6">
		<div class = "alert alert-info">
			Heads up ! For Walmart product showdown just Enter the url as <strong>[product_url]</strong> (Within square brackets)
		</div>
	</div>
</div>

<script type="text/javascript">

	/*
	* http://stackoverflow.com/questions/5717093/check-if-a-javascript-string-is-an-url
	*/
	function ValidURL(str) {
		var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
		'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
		'((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
		'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
		'(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
		'(\\#[-a-z\\d_]*)?$','i'); // fragment locator
		return pattern.test(str);

	}

	function getID(str){
		var bs = 0;
		var ques = 0;
		var i = 0;
		while (i < str.length){
			if (str.charAt(i) == '/'){
				bs = i;
			}
			else if (str.charAt(i) == '?'){
				ques = i;
				break;
			}
			i++;
		}
		return str.substring(bs + 1, ques - 1);
	}

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

		$('#submit_poll').click(function(){
			var op_1 = $('#op_1').val();
			var op_2 = $('#op_2').val();
			op_1.trim();
			op_2.trim();
			var len_1 = op_1.length;
			var len_2 = op_2.length;
			if (
				op_1.charAt(0) == '['
				&& op_2.charAt(0) == '[' 
				&& op_1.charAt(len_1 - 1) == ']' 
				&& op_2.charAt(len_2 - 1) == ']'
			){
				link_1 = op_1.substring(1, len_1);
				link_2 = op_2.substring(1, len_2);
				if (ValidURL(link_1) && ValidURL(link_2)){
					alert("you entered product urls");

					var id_1 = getID(link_1);
					var id_2 = getID(link_2);

					$('#op_1').val(id_1 + "__");
					$('#op_2').val(id_2 + "__");
				}
			}

			$('#handles').val(assoc_user_handles);
			$('#create_poll').submit();
		});
	});
</script>

