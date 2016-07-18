<? include_once('header_assets.php'); ?>
	<nav class = "navbar navbar-default">
		<div class = "navbar-header">
			<a class = "navbar-brand" href = "home" style = "background-color:orange;">Polls </a>
		</div>
		<div>
			<ul class = "nav navbar-nav">
				<li <?php if ($active == "login") echo "class = \"active\""; ?>><?if ($active != "login"){echo "<a href = \"login\">Login</a>"; }else {echo "<a>Login</a>";}?> </li>
				<li <?php if ($active == "sign_up") echo "class = \"active\""; ?>><?if ($active != "sign_up"){echo "<a href = \"sign_up\">Sign Up</a>";} else {echo "<a>Sign Up</a>";}?></li>
				<li<?php if ($active == "about") echo "class = \"active\""; ?>><?if ($active != "about"){echo "<a href = \"about\">About</a>"; }else {echo "<a>About</a>";}?></li>
			</ul>
		</div>
<!-- url("http://127.0.0.1/polls/assets/img/comet.jpg"); -->
	</nav>

