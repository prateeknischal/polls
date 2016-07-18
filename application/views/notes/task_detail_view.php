<div class = "row">
	<div class = "col-sm-offset-3 col-sm-6">
		<div class = "panel panel-default">
			<div class = "panel-heading"  style = "background : #4CAF50; border-color: #4CAF50; color: white;"> 
				<strong><?= $post_content['title']; ?></strong>
			</div>
		<div class = "panel-body">
			<ul>
				<?
					$v = unserialize($post_content['content']);
					for ($i = 0; $i < count($v); $i++){
						if ($v[$i]['done'] == 0)
							echo "<li><input type = \"checkbox\" class = \"_check\" id = \"".$i."\">".$v[$i]['value']."</li>";
						else
							echo "<li><input type = \"checkbox\" id = \"".$i."\" checked disabled><strike>".$v[$i]['value']."</strike></li>";

					}
				?>
			</ul>
			<hr>
			<div class = "label label-success"> Meta Info </div>
			<div class = "col-sm-offset-1">
				<ul>
					<li>Post Admin 		: <strong><?echo $post_content['admin_handle'];?></strong></li>
					<li>Creation Time 	: <?echo $post_content['ts']; ?></li>
					<li>Associated members :
						<div class = "row">
							<div class = "col-sm-offset-1">
								<ul>
								<? for ($i = 0; $i < count($assoc_handle); $i++){
									echo "<li>". $assoc_handle[$i]['user_assoc'] . "</li>";
								}
								?>
								</ul>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class = "row">
	<div class = "col-sm-offset-3 col-sm-6">
		<div class = "row">
			<div class = "col-sm-6">
				<div class = "row">
					<div class = "col-sm-offset-3">
						<button class = "btn btn-warning col-sm-6" id = "go_back"> Back </button>
					</div>
				</div>
			</div>
			<div class = "col-sm-6">
				<div class = "row">
					<div class = "col-sm-offset-3">
						<button class = "btn btn-danger col-sm-6" id = "delete_note" 
						<? if ($this->session->userdata('handle') != $post_content['admin_handle']) echo "disabled = \"disabled\"";?>> 
							Delete 
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		BASE_URL = "http://localhost/polls/index.php/";
		var checked = new Array();
		var id = "<?= $post_content['post_id']; ?>";
		$('._check').click(function(){
			$("#go_back").html('Update');
		});

		$('#go_back').click(function(){
			for (var i = 0; i < parseInt("<?= count($v) ; ?>"); i++){
				if ($('#' + i).is(":checked") && !$('#' + i).is(":disabled"))
					checked.push(i);
			}
			
			$.ajax({
				type 	: "POST",
				url 	: BASE_URL + "notes/do_update_task_list",
				data  	: {
					"update" : checked,
					"update_post_id" : id
				},
				success : function(result){
					alert("changes saved");
					window.location = BASE_URL + "notes/dash";
				},
				error : function(err){
					alert(err.responseText);
				}
			});
		});

		$('#delete_note').click(function(){
			$.ajax({
				type 	: "POST",
				url 	: BASE_URL + "notes/delete_note",
				data 	:{
					"post_id" : id
				},
				success : function(result){
					alert("Note Deleted !");
					window.location = BASE_URL + "notes/dash";
				},
				error 	: function(err){
					alert("Some Error Occured ! " + err.responseText);
					window.location = BASE_URL + "notes/dash";
				}
			});
		});
	});
</script>