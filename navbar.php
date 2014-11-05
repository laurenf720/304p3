<html>
	<body>
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
							echo "<li><a href=\"../304p3/return.php\">Returns</a></li>";
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
	</body>
</html>