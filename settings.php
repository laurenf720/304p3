<html>
	<head>
		<title> AMS Website Settings Page </title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>

	<body style="background-color:#CDCDCD">
		<?php include 'emplogin.php';?>
		<div id="nav_bar">
			<ul>
				<li><a href="../304p3/index.php">Login</a></li>
				<li><a href="../304p3/search.php">Search</a></li>
				<?php 
					if (isset($_SESSION['logged']) and $_SESSION['logged']==true){
						echo "<li><div class=\"rightpos\" style=\"cursor: pointer;\"><a id=\"button\" style=\"cursor:pointer\">Welcome ".$_SESSION["login_user"]."!</a></div> </li>"; 
					}
				?>
				<div id="dropdown_menu" class="hidden_menu"> 
					<table id="container">
						<tr><td><a class="sub-menu" href="/../304p3/settings.php">SETTINGS</a></td></tr>
						<tr><td><a class="sub-menu" href="/../304p3/logout.php">LOGOUT</a></td></tr>
					</table>					
				</div>
			</ul>
		</div>

		<p>Settings</p>
	</body>
	<script src="ams.js"></script>
</html>