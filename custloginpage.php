<html>
	<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">

	</head>
	<body>
		<?php include 'custlogin.php';?>
		<div id="nav">
			<ul>
				<?php 
					if (!isset($_SESSION['logged']) || $_SESSION['logged']==false){
						echo "<li><a href=\"../304p3/emploginpage.php\">Employee Login</a></li>";
						echo "<li><a href=\"../304p3/index.php\">Home</a></li>";
						echo "<li><a href=\"../304p3/search.php\">Search</a></li>";
						echo "<li><div class=\"rightpos\" style=\"cursor: pointer;\"><a href=\"../304p3/custloginpage.php\">Customer Login</a></div></li>"; 
					}
					elseif (isset($_SESSION['logged']) and $_SESSION['logged']== true) {
						echo "<li><a href=\"../304p3/index.php\">Home</a></li>";
						echo "<li><a href=\"../304p3/search.php\">Search</a></li>";
						if ($_SESSION['type'] == 'manager'){
							echo "<li><a id=\"button\">Manager Action 1</a></li>";
							echo "<li><a id=\"button\">Manager Action 2</a></li>";
						}
						elseif ($_SESSION['type'] == 'clerk'){
							echo "<li><a id=\"button\">Clerk Action 1</a></li>";
							echo "<li><a id=\"button\">Clerk Action 2</a></li>";
						}
						else {
							echo "<li><a id=\"button\">Customer Action 1</a></li>";
							echo "<li><a id=\"button\">Customer Action 2</a></li>";
						}
						echo "<li><div class=\"rightpos\" style=\"cursor: pointer;\"><a id=\"welcomebutton\">Welcome ".$_SESSION["login_user"]."!</a></div> </li>"; 
					}
				?>
			</ul>
		</div>
		</div>

		<div id="dropdown_menu" class="hidden_menu"> 
			<table id="container">
				<tr><td><a class="sub-menu" href="/../304p3/settings.php">SETTINGS</a></td></tr>
				<tr><td><a class="sub-menu" href="/../304p3/logout.php">LOGOUT</a></td></tr>
			</table>					
		</div>

		<div id="wrap">
			<h1 style="text-align:center">AMS Customers</h1>
			<p></p>
		</div>
		<div align="center">
			<form id="loginform" name="loginform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<table class="login" style="background-color:white">
					<thead>
						<tr>
							<th colspan=2 style="border-bottom:1px solid">Customer Login Form</th>
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
					<tr>
						<td colpan=2 style="text-align:center">Not a customer? Register <a href="/../304p3/custRegisterPage.php">here</a>
			    </table>
    			<span class="error"><?php echo $error; ?></span>
			</form>
		</div>
	</body>
	<script src="ams.js"></script>
</html>