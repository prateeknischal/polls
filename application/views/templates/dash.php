<? include_once('header_assets.php');
	$base_url = base_url()."index.php/";
	$base_url = str_replace("127.0.0.1", "localhost", $base_url);
?>
<nav class = "navbar navbar-default">
		<div class = "navbar-header">
			<a class = "navbar-brand" href = <? echo "\"".$base_url."welcome/home_dash"."\"";?> style = "background-color:orange;">Polls </a>
		</div>
		<div>
			<ul class = "nav navbar-nav">
				<li <?php if ($active == "home") echo "class = \"active\""; ?>><a href = <? echo "\"". $base_url."notes/login_home\"";?>>Home <strong style = 
				"color:green;">( <? echo $this->session->userdata('handle'); ?> )</strong></a></li>
				<li <?php if ($active == "dash") echo "class = \"active\""; ?>><a href = <? echo "\"". $base_url."notes/dash\"";?>> Dash </a></li>
				<li <?php if ($active == "logout") echo "class = \"active\""; ?>><a href = <? echo "\"". $base_url."welcome/logout_user\"";?>> logout </a></li>
				<li <?php if ($active == "about") echo "class = \"active\""; ?>><a href = <? echo "\"". $base_url."welcome/about\"";?> > About </a></li>
			</ul>
		</div>
<!-- url("http://127.0.0.1/polls/assets/img/comet.jpg"); -->
	</nav>