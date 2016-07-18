<div class = "row">
	<div class="col-sm-offset-3 col-sm-6">
		<div class = "panel panel-default">
			<div class = "panel-heading" style = "background : #2196F3; color : white; border-color : #2196F3;">
				<? echo $post_content['title']; ?>
			</div>

			<div class = "panel-body" style = "background : #E0E0E0">
				<!-- content of the poll (currently supporting 2 options) -->
				<?
					$handle = $this->session->userdata('handle');
					if ($post_content['post_type'] == 1){
						echo $post_content['content'];
					}
					if ($post_content['post_type'] == 2){
						$v = unserialize($post_content['content']);
						$op_1 = $v['op_1']['value'];
						$op_2 = $v['op_2']['value'];

						$vote_1 = $v['op_1']['count'];
						$vote_2 = $v['op_2']['count'];

						$v_1 = ($vote_1 * 100.0)/max(1, ($vote_1 + $vote_2));
						$v_2 = ($vote_2 * 100.0)/max(1, ($vote_1 + $vote_2));

						echo "<div class = 'row'>"."\n";
						echo "	<div class = 'col-sm-offset-1 col-sm-10'>"."\n";
						echo "		<div class = \"row\"><strong>".ucwords($op_1)."</strong></div>";
						echo "		<div class = 'progress'>"."\n";
						echo "			<div class = \"progress-bar\" aria-valuenow = \"60\" aria-valuemin=\"0\" aria-valuemax = \"100\" style = \"width : ".$v_1."%\">"."\n";
						echo "				<div style = \"color : #000000\" >".$v_1."%</div>"."\n";
						echo "			</div>"."\n";
						echo "		</div>"."\n";
						echo "		<div class = \"row\"><strong>".ucwords($op_2)."</strong></div>";
						echo "		<div class = 'progress'>"."\n";
						echo "			<div class = \"progress-bar\" aria-valuenow = \"60\" aria-valuemin=\"0\" aria-valuemax = \"100\" style = \"width :".$v_2."%;\">"."\n";
						echo "				<div style = \"color : #000000\" >".$v_2 ."%</div>"."\n";
						echo "			</div>"."\n";
						echo "		</div>"."\n";
						echo "	</div>"."\n";
						echo "</div>"."\n";

						$ok = false;
						$handle = $this->session->userdata('handle');
						for ($i = 0; $i < count($assoc_handle); $i++){
							if ($handle == $assoc_handle[$i]['user_assoc'] && $assoc_handle[$i]['choice'] == 0){
								$ok = true;
								break;
							}
						}
							// voting mechanism
						if ($ok){
							echo "<div id = \"voting\" class = \"row\">"."\n";
							echo "	<div class = \"col-sm-offset-1 col-sm-10\">"."\n";
							echo "		<h3>Vote</h3>"."\n";
							echo "		<div class = \"row\">"."\n";
							echo "			<div class = \"col-sm-6\">"."\n";
							echo "					<button id = \"op_1\" class = \"btn btn-default col-sm-6 _op\" style = \"color : white; background : #E91E63; border-color:#E91E63\">Option 1</button>"."\n";
							echo "			</div>"."\n";
							echo "			<div class = \"col-sm-6\">"."\n";
							echo "				<button id = \"op_2\" class = \"btn btn-default col-sm-6 _op\" style = \"color : white; background : #2196F3; border-color:#2196F3\">Option 2</button>"."\n";
							echo "			</div>"."\n";
							echo "		</div>"."\n";
							echo "	</div>"."\n";
							echo "</div>"."\n";
						}
					}
					
				?>
				<? if ($post_content['post_type'] == 2) : ?>

					<div class = "row">
						<div class = "col-sm-offset-5">
							<strong><?= ($vote_1 + $vote_2). " / ". count($assoc_handle) ." votes"; ?></strong>
						</div>
					</div>
				<? endif; ?>

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
						<? if ($handle != $post_content['admin_handle']) echo "disabled = \"disabled\"";?>> 
							Delete 
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="err"></div>

<style type="text/css">
	.progress-bar{
		background-color: #E91E63;
	}
</style>

<? $t = $post_content['post_id']; ?>
<script type="text/javascript">
	$(document).ready(function(){
		BASE_URL = "http://localhost/polls/index.php/";
		var id = "<?= $post_content['post_id']; ?>";
		$('._op').click(function(){
			var clicked_id = this.id;
			$.ajax({
				type 	: "POST",
				url 	: BASE_URL + "notes/record_vote",
				data 	: {
					"post_id" : id,
					"vote" : clicked_id
				},
				success : function(result){
					$('#voting').slideUp("fast");
					window.location.reload();
				},
				error : function(err){
					$('#err').html(err.responseText);
				}
			});
		});

		$('#go_back').click(function(){
			window.location = BASE_URL + "notes/dash";
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