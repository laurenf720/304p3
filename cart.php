<html>
	<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">

	</head>
	<body>
		
		<?php 
		session_start();
		include 'navbar.php';
		?>

		<div id="wrap">
			<h1 style="text-align:center">Shopping Cart</h1>
			<p></p>
		</div>
		<div align="center">
		<?php
			if (!isset($_SESSION['logged'])){
					echo "<span class=\"error\">* Please login before viewing your shopping cart</span>";
			}
			else {
				$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");

				// Check that the connection was successful, otherwise exit
				if (mysqli_connect_errno()) {
				    printf("Connect failed: %s\n", mysqli_connect_error());
				    exit();
				}
				
				$cid=$_SESSION['login_user'];
				$result = $connection->query("SELECT * FROM cart WHERE cid='$cid' ORDER BY upc");
				if ($result->num_rows == 0){
					echo "<span class=\"error\">* Your shopping cart is empty</span>";
				}
				else {
					while($row=$result->fetch_assoc()){
						echo $row['upc'];
					}
				}

				mysqli_close($connection);
			}
		?>
		</div>
	</body>
	<script src="ams.js"></script>
</html>