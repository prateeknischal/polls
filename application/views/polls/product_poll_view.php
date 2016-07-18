<div class = "row">
	<div class = "col-sm-offset-1 col-sm-4">
		<div class = "row">
		<div class = "thumbnail">
			<img id = "img_op_1" class = "th_img" src = <?= "\"".$product_info["op_1"]["thumbnailImage"]."\"" ;?>>
			<div class = "caption">
				<h4><?= substr($product_info["op_1"]["name"], 0, 50); ?></h4>
				<img src= <?= "\"".$product_info['op_1']['customerRatingImage']."\"";?> >
				<label class = "label label-success"><?= $product_info['op_1']['customerRating']; ?></label>
				<?= $product_info['op_1']['numReviews']. " Reviews"; ?>
				<div class = "scroll_div"><p><?
					echo htmlspecialchars_decode($product_info["op_1"]["longDescription"]);
					?>		
				</p></div>
			</div>
			<p>Price : $ <strong><?= $product_info['op_1']['salePrice']; ?></strong></p>
			<p><a href=<?= "\"".$product_info['op_1']['productUrl']."\"";?> target="_blank" class="btn btn-primary" role="button">Go To page</a></p>
		</div></div>
	</div>


	<div class = "col-sm-offset-1 col-sm-4">
	<div class = "row">
		<div class = "thumbnail">
			<img id = "img_op_2" class = "th_img" src = <?= "\"".$product_info["op_2"]["thumbnailImage"]."\"" ;?>>
			<div class = "caption">
				<h4><?= substr($product_info["op_2"]["name"], 0, 50); ?></h4>
				<img src= <?= "\"".$product_info['op_2']['customerRatingImage']."\"";?> >
				<label class = "label label-success"><?= $product_info['op_2']['customerRating']; ?></label>
				<?= $product_info['op_2']['numReviews']. " Reviews"; ?>
				<div class = "scroll_div"><p><? 
					echo htmlspecialchars_decode($product_info["op_2"]["longDescription"]);
					?>
				</p></div>
			</div>
			<p>Price : $ <strong><?= $product_info['op_2']['salePrice']; ?></strong></p>
			<p><a href=<?= "\"".$product_info['op_2']['productUrl']."\"";?> target="_blank" class="btn btn-primary" role="button">Go To page</a></p>
		</div>
	</div></div>
</div>

<div id = "modal_img_op_1" class = "modal fade">
	<div class = "modal-dialog">
		<div class = "modal-content">
			<div class = "modal-body">
				<img src= <?= "\"".$product_info['op_1']['largeImage']."\"";?>>
				<div class="row">
				<p class = "col-sm-offset-1 col-sm-10"><?= $product_info['op_1']['name'];?></p>
					<div class = "col-sm-offset-4 col-sm-3">
						<button type = "button" class = "btn btn-info" data-dismiss="modal"> Close </button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id = "modal_img_op_2" class = "modal fade">
	<div class = "modal-dialog">
		<div class = "modal-content">
			<div class = "modal-body">
				<img src= <?= "\"".$product_info['op_2']['largeImage']."\"";?>>
				<div class="row">
				<p class = "col-sm-offset-1 col-sm-10"><?= $product_info['op_2']['name'];?></p>
					<div class = "col-sm-offset-4 col-sm-3">
						<button type = "button" class = "btn btn-info" data-dismiss="modal"> Close </button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class = "row">
	<div class = "col-sm-offset-1 col-sm-9">
		<div class = "panel panel-primary">
			<div class = "panel-heading"> INFO </div>
			<div class = "panel-body">


			<?
				$handle = $this->session->userdata('handle');
				$v = unserialize($post_content['content']);
				$op_1 = $product_info['op_1']['name'];
				$op_2 = $product_info['op_2']['name'];

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

			?>

			<div class = "row">
				<div class = "col-sm-offset-5">
					<strong><?= ($vote_1 + $vote_2). " / ". count($assoc_handle) ." votes"; ?></strong>
				</div>
			</div>

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

<div class = "row" style = "padding-bottom : 100px">
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
						<? if ($handle != $post_content['admin_handle']) echo "disabled= \"disabled\"";?>> 
							Delete 
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<style type="text/css">
	.caption{
		overflow: scroll;
		height: 350px;
	}
	.progress-bar{
		background-color: #E91E63;
	}
</style>

<script type="text/javascript">
	$(document).ready(function(){
		$('.th_img').click(function(){
			var id = this.id;
			$("#modal_" + id).modal('show');
		});

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
					window.location = window.location;
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