<? $posts_count = count($data);
	$style = array(
		1 => "\"background : #2196F3; color : white; border-color : #2196F3;\"",
		2 => "\"background : #E91E63; color : white; border-color : #E91E63;\"",
		3 => "\"background : #4CAF50; color : white; border-color : #4CAF50;\""
		);
for ($pc = 0; $pc < $posts_count; $pc++){
	if ($pc % 3 == 0){
		echo "<div class = 'row'>\n";
		echo "	<div class = 'col-sm-offset-1 col-sm-3'>\n";
	}
	else
		echo "		<div class = 'col-sm-3'>\n";

		echo "			<div class = 'panel panel-default'>\n";

		echo "				<div class = 'panel-heading' style=".$style[$data[$pc]['post_type']].">"."\n";
		echo "					".$data[$pc]['title']."\n";
		echo "				</div>\n";

		echo "				<div class = 'panel-body' id = panel_".$data[$pc]['post_id']." style = \"background : #E0E0E0\">\n";
		if ($data[$pc]['post_type'] == 1)
			echo "					".substr($data[$pc]['content'], 0, 100)."\n";
		else if ($data[$pc]['post_type'] != 1){
			echo "Click To Expand";
		}
		echo "				</div>\n";
		echo "			</div>\n";	

	if ($pc % 3 == 2){	
		echo "	</div>\n";
		echo "</div>\n";
	}
	else
		echo "      </div>\n";
}
if ($posts_count % 3 != 0){
		echo "	</div>\n";
		echo "</div>\n";
}
?>

<script type="text/javascript">
	$(document).ready(function(){
		$('.panel-body').click(function(){
			// alert(this.id);
			BASE_URL = "http://localhost/polls/index.php/"; 
			window.location = BASE_URL + "notes/view_note_details/" + this.id;
		})

		$('.panel-body').mouseenter(function(){
			$('#'+this.id).css("background", "white");
		})
		$('.panel-body').mouseout(function(){
			$('#'+this.id).css("background", "#E0E0E0");
		})
	});
</script>
