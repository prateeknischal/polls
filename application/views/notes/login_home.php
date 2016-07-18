<div class = "row">
	<div class = "col-sm-offset-8 col-sm-4">
		<img id = "content_tabs_show" src="../../assets/img/hollow_plus.png" style = "width:25%;">
		<img id = "content_tabs_hide" src="../../assets/img/hollow_minus.png" style = "width:25%;display:none;">
	</div>
</div>
<div class = "row" id = "content_tabs" style = "display:none;">
	<div class = "col-sm-offset-1">
		<div class = "col-sm-3">
			<button id = "new_note" type = "button" class = "btn btn-success col-sm-12" style = "height: 150px"> 
				<h1 class = "_text_color">Note</h1> 
			</button>
		</div>
		<div class = "col-sm-3">
			<button id = "new_task" type = "button" class = "btn btn-warning col-sm-12" style = "height: 150px"> 		<h1 class = "_text_color">Tasks</h1> 
			</button>
		</div>
		<div class = "col-sm-3">
			<button id = "new_poll" type = "button" class = "btn btn-info col-sm-12" style = "height: 150px"> 
				<h1 class = "_text_color">Poll</h1>  
			</button>
		</div>
	</div>
</div>

<div class = "row" style = "padding-top : 50px">
	<div class = "col-sm-offset-1 col-sm-8">
		<div class = "panel panel-info">
			<div class = "panel-heading">
				Latest events
			</div>

			<table class = "table">
				<tr>
					<th>Title</th>
					<th>Admin</th>
				</tr>
				<? for ($i = 0; $i < count($data); $i++) : ?>
					<tr class = "my_row" id = <?= "\"post_".$data[$i]['post_id']."\"" ;?>>
						<td><?= $data[$i]['title']; ?></td>
						<td><?= $data[$i]['admin_handle']; ?></td>
					</tr>
				<? endfor;?>
			</table>
		</div>
	</div>
</div>

<style>
	._text_color{
		color : white;
	}
	._text_color:hover{
		color : lightgrey;
	}

	th, tr{
		text-align: center;
	}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		BASE_URL = "http://localhost/polls/index.php/"; 
		$('#new_note').click(function(){
			window.location = BASE_URL + "notes/new_note";
		});

		$('#new_task').click(function(){
			window.location = BASE_URL + "notes/new_task";
		});

		$('#new_poll').click(function(){
			window.location = BASE_URL + "notes/new_poll";
		});

		$('#content_tabs_show').click(function(){
			$('#content_tabs').slideDown('fast');
			$('#content_tabs_hide').show();
			$('#content_tabs_show').hide();
		});
		$('#content_tabs_hide').click(function(){
			$('#content_tabs').slideUp('fast');
			$('#content_tabs_show').show();
			$('#content_tabs_hide').hide();
		});

		$('.my_row').click(function(){
			var id = this.id;
			window.location = BASE_URL + "notes/view_note_details/" + id;
		});
	});
</script>
