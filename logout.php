<html>
	<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<?php
			if (isset($_SESSION['logged'])){
				unset($_SESSION['logged']);
			}
		session_start();
		session_destroy();
		?>
		<div id="nav">
			<ul>
				<li><a href="../304p3/index.php">Home</a></li>
			</ul>
		</div>
		<div id="wrap">
			<h1>You have successfully logged out!</h1>
		</div>
	</body>
</html>