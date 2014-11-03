<html>
<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	<?php include 'custRegister.php';?>
	<div id="nav">
			<ul>
				<?php 
					if (!isset($_SESSION['logged'])){
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
		
		
			<form id="registerForm" name="registerForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<table class="login" style="background-color:white">
					<thead>
						<tr>
							<th colspan=2 style="border-bottom:1px solid">Register:</th>
						</tr>
					</thead>
			        <tr>
			       		<td><label>Username: </label></td>
						<td><input id="username" type="text" size=30 name="username" placeholder="Enter your username"></td>
			       	</tr>
			       	<tr>
			       		<td><label>Password: </label></td>
						<td><input id="password" type="password" size=30 name="password" placeholder="Enter your password"></td>
			       	</tr>			  
					<tr>
						<td><label>Confirm your Password: </label></td>
			       		<td><input id="password2" type="password" size=30 name="password2" placeholder="Retype your Password"></td>
			       	</tr>
					<tr>
						<td><label>Full Name: </label></td><td><input id="cname" type="text" size=30 name="cname" placeholder="Enter your Full Name"></td>
			        </tr>
					<tr>
						<td><label>Phone: </label></td><td><input id="phone" type="text" size=30 name="phone" placeholder="Enter your Phone Number"></td>
					</tr>
					<tr>
						<td><label>Address: </label></td><td><input id="address" type="text" size=30 name="address" placeholder="Enter your Address"></td>
			        </tr>
					<tr>
					
			        	<td colspan=2 style="text-align:center"><input type="submit" name="submit" border=0 value="REGISTER"></td>
			        </tr>
			    </table>
    			<span class="error"><?php echo $error; ?></span>
			</form>
		</div>
	</body>
</html>
	