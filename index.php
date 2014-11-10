<html>
	<head>
		<title> AMS Website Search Page </title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<?php include 'userlogin.php';?>
		<?php
			if (!isset($_SESSION['logged'])){
				header("location: userloginpage.php");
			}
		
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
		    	if (isset($_POST["submit"]) && $_POST["submit"] == "Customer"){
		    		$_SESSION['type']="customer";
		    	}
		    	elseif(isset($_POST["submit"]) && $_POST["submit"] == "Clerk"){
		    		$_SESSION['type']='clerk';
		    	}
		    	elseif(isset($_POST["submit"]) && $_POST["submit"] == "Manager"){
		    		$_SESSION['type']='manager';
		    	}
		    }
		    include 'navbar.php';
		?>
		
		<div id="wrap">
			<h1 align="center">Choose a type of user</h1>
			<p></p>
		</div>

		<div align="center">
			<form id="viewform" name="viewform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<table cellpadding=10 style="background-color:white; border:1px solid black; border-radius:5px; text-align:center; width:400px;">
					<tr><td><p> </p></td></tr>
					<tr><td><input class= "usertypebutton" id="customertype" type="submit" name="submit" value="Customer"></td></tr>
					<tr><td><input class= "usertypebutton" id="clerktype" type="submit" name="submit" value="Clerk"></td></tr>
					<tr><td><input class= "usertypebutton" id="managertype" type="submit" name="submit" value="Manager"></td></tr>
					<tr><td><p> </p></td></tr>
				</table>
			</form>
		</div>

	</body>
	<script src="ams.js"></script>
</html>
