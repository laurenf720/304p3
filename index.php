<html>
	<head>
		<title>AMS Store</title>
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
						<tr><td><a style="color:black; font-size:13px; font-weight:normal" href="/../304p3/settings.php">SETTINGS</a></td></tr>
						<tr><td><a style="color:black; font-size:13px; font-weight:normal" href="/../304p3/logout.php">LOGOUT</a></td></tr>
					</table>					
				</div>
			</ul>
		</div>

		<h1 style="text-align:center">AMS Employees</h1>
		<div align="center">
			<form id="loginform" name="loginform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<table class="login">
					<thead>
						<tr>
							<th colspan=2 style="border-bottom:1px solid">Employee Login Form</th>
						</tr>
					</thead>
			        <tr>
			       		<td><label>Username: </label></td>
			       	</tr>
			       	<tr>
			       		<td><input id="username" type="text" size=30 name="username" placeholder="Enter your username"></td>
			       	</tr>
			       	<tr>
			       		<td><label>Password: </label></td>
			       	</tr>
			       	<tr>
			       		<td><input id="password" type="password" size=30 name="password" placeholder="Enter your password"></td>
			       	</tr>
			        <tr>
			        	<td colspan=2 style="text-align:center"><input type="submit" name="submit" border=0 value="LOGIN"></td>
			        </tr>
			    </table>
    			<span><?php echo $error; ?></span>
			</form>
		</div>
	</body>
	<script src="ams.js"></script>
</html>