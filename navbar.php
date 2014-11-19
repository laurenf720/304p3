<html>
	<body>
		<div id="nav">
			<ul>
				<?php 
					if (!isset($_SESSION['logged'])){
						echo "<li><a href=\"../304p3/index.php\">Home</a></li>";
						echo "<li><a href=\"../304p3/search.php\">Search</a></li>";
						echo "<li><a href=\"../304p3/cart.php\">Shopping Cart</a></li>";
						echo "<li><div class=\"rightpos\" style=\"cursor: pointer;\"><a href=\"../304p3/userloginpage.php\">Login</a></div></li>"; 
					}
					elseif(!isset($_SESSION['type'])){
						echo "<li><a href=\"../304p3/index.php\">Home</a></li>";
						echo "<li><div class=\"rightpos\" style=\"cursor: pointer;\"><a id=\"welcomebutton\">Welcome ".$_SESSION["login_user"]."!</a></div> </li>"; 
					}
					elseif (isset($_SESSION['logged']) and $_SESSION['logged']== true) {
						echo "<li><a href=\"../304p3/index.php\">Home</a></li>";
						echo "<li><a href=\"../304p3/search.php\">Search</a></li>";
						if ($_SESSION['type'] == 'manager'){
							echo "<li><a id=\"button\" href=\"../304p3/manAddQuantityPage.php\">Update Stock</a></li>";
							echo "<li><a href=\"../304p3/dailyReport.php\">Daily Report</a></li>";
							echo "<li><a href=\"../304p3/addDeliveryDatePage.php\">Process Delivery Date</a></li>";
						}
						if ($_SESSION['type'] == 'clerk' or $_SESSION['type'] == 'manager'){
							echo "<li><a href=\"../304p3/return.php\">Returns</a></li>";
						}
						else {
							echo "<li><a href=\"../304p3/cart.php\">Shopping Cart</a></li>";
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