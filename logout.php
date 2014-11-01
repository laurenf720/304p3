<html>
	<head>
		<title> AMS Website Logout Page </title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>

	<body style="background-color:#CDCDCD">
		<?php
			if (isset($_SESSION['logged'])){
				unset($_SESSION['logged']);
			}
		session_start();
		session_destroy();
		?>
		<div id="nav_bar">
			<ul>
				<li><a href="../304p3/index.php">Login</a></li>
				<li><a href="../304p3/search.php">Search</a></li>
			</ul>
		</div>

		<h1>You have successfully logged out!</h1>
	</body>
</html>