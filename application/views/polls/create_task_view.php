<div class = "row">
	<form class = "form-horizontal">
		<div class = "form-group" id = "create_task">
			<label class = "control-label col-sm-2" style = "color : white;">Title</label>
			<div class = "col-sm-6" >
				<input type = "text" class = "form-control" name = "title" id = "title" placeholder="Title" required="required">
			</div>
		</div>
	</form>
</div>

<div class = "row">
	<div class = "col-sm-offset-2 col-sm-2">
		<img src = "../../assets/img/solid_plus.png" style = "max-width: 50px" id="add">
	</div>
</div>
<div class = "row">
	<div class = "col-sm-offset-4 col-sm-8">
		<button class = "btn btn-primary col-sm-2" id = "submit">done</button>
	</div>
</div>

<form id = "create_task_form" action = "new_task_list" style = "display:none" method = "post">
	<input type = "text" id="list" name = "list">
</form>

<style type="text/css">
	._pad{
		padding-top: 20px;
	}
</style>
<script type="text/javascript">
	var global_counter = 1;
	var ops = new Array();
	function add_fields(){
		$('#create_task').append("<div class = \"col-sm-offset-2 col-sm-6 _pad\">"+
									"<input id = \"" + global_counter + "\"type = \"text\" class = \"form-control\" placeholder=\"Task " + global_counter + "\" required=\"required\">"+
								"</div>");
		global_counter++;
	}

	$(document).ready(function(){
		$('#submit').click(function(){
			ops.push($('#title').val());
			for (var i = 1; i < global_counter; i++){
				var s = $('#' + i).val();
				if (s.length != 0)
					ops.push(s);
			}
		});

		$('#add').click(function(){
			add_fields();
		});
		$('#submit').click(function(){
			$('#list').val(ops);
			$('#create_task_form').submit();
		});
	});
</script>