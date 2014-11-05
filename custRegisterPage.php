<html>
<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<?php include 'custRegister.php';?>
		<?php include 'navbar.php';?>

		<div id="wrap">
			<h1 style="text-align:center">AMS Customers</h1>
			<p></p>
		</div>
		<div align="center">
			<?php
				// to prevent people from accessing register page when they are already logged in
				if (isset($_SESSION['logged']) and $_SESSION['logged'] == true){
					header("location: index.php");
				}
			?>
		
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
	