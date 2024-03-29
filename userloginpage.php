<html>
	<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">

	</head>
	<body>
		<?php include 'userlogin.php';?>
		<?php include 'navbar.php';?>

		<div id="wrap">
			<h1 style="text-align:center">AMS Login</h1>
			<p></p>
		</div>
		<?php
			// to prevent people from accessing login page when they are already logged in
			if (isset($_SESSION['logged']) and $_SESSION['logged'] == true){
					header("location: index.php");
			}
		?>
		<div align="center">
			<form id="loginform" name="loginform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<table class="login" style="background-color:white">
					<thead>
						<tr>
							<th colspan=2 style="border-bottom:1px solid">Login Form</th>
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