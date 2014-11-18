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
			<h1 style="text-align:center">Complete Your Purchase</h1>
			<p></p>
		</div>
		<div align="center">
			<form id="checkoutform" name="checkoutform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<table class="login" style="background-color:white">
					<thead><tr><th colspan=2 style="border-bottom:1px solid black;">Fill in your credit card information:</th></tr></thead>
					<tr>
						<td><label>Credit Card Number: </label></td>
						<td><input type="text" size=20 placeholder="1234***********9"></td>
					</tr>
					<tr>
						<td><label>Credit Card Expiry Date: </label></td>
						<td>
							<select name="expmonth">
								<?php
									$count=1;
									while ($count <=12){
										echo "<option>".$count."</option>";
										$count ++;
									}
								?>
							</select>/
							<select name="expyear">
								<?php
									$year=intval(date("Y"));
									$maxyear=$year+23;
									while ($year <=$maxyear){
										echo "<option>".$year."</option>";
										$year ++;
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan=2 style="text-align:center"><input type="submit" name="submit" border=0 value="Complete Order"></td>
					</tr>
				</table>
			</form>


			<b>Your Bill:<b>
			<table cellpadding=5 class='itemdetail'>
				<thead>
					<tr><th style="border-bottom:1px solid black">UPC</th><th style="border-bottom:1px solid black">Name</th><th style="border-bottom:1px solid black">Type</th><th style="border-bottom:1px solid black">Artist</th><th style="border-bottom:1px solid black">Company</th><th style="border-bottom:1px solid black">Quantity</th><th style="border-bottom:1px solid black">Unit Price</th></tr>
				</thead>
				<?php
				$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
				// Check that the connection was successful, otherwise exit
				if (mysqli_connect_errno()) {
				    printf("Connect failed: %s\n", mysqli_connect_error());
				    exit();
				}

				$cid=$_SESSION['login_user'];
				$result = $connection->query("SELECT * FROM cart NATURAL JOIN item WHERE cid='$cid' ORDER BY upc");
				if ($result->num_rows == 0){
					header("location: cart.php");
				}
				$total=0.00;
				while($row=$result->fetch_assoc()){
					$total+=$row['price']*$row['quantity'];
					$upc = $row['upc'];
					echo "<tr>";
					echo "<td style=\"border-left:1px solid black\">".$row['upc']."</td>";
					echo "<td>".$row['title']."</td>";
					echo "<td>".$row['itype']."</td>";
					echo "<td><p></p>";
					$artistresult = $connection->query("SELECT lsname FROM leadsinger WHERE upc='$upc'");
					while ($artist=$artistresult->fetch_assoc()){
						if (!empty($artist['lsname'])){
							echo $artist['lsname']."<br>";
					    }
					}
					echo "</td>";
					echo "<td>".$row['company']."</td>";
					echo "<td>".$row['quantity']."</td>";
					echo "<td>$ ".$row['price']."</td>";
				}

				mysqli_close($connection);

				
				echo "<tfoot>
					<tbody>
						<tr class=\"dailyreportfoot\"><td colspan=6 style=\"text-align:right\"><b>Total:</b></td><td><b> $".number_format((float)$total, 2, '.', '')."</b></td>
						</tr>
					</tbody>
				</tfoot>";
				?>
			</table>
		</div>
	</body>
	<script src="ams.js"></script>
</html>