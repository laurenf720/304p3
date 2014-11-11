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
			<h1 style="text-align:center">Create a Daily Report</h1>
			<p></p>
		</div>
		<?php
			if (!isset($_SESSION['logged'])){
				header("location: userloginpage.php");
			}
		?>

		<form id="dailyreportform" name="dailyreportform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
			<table align="center" class="login" style="background-color:white">
			    <tr>
			       	<td><label>Pick a day: </label></td>
					<td><input id="dailyreportday" type="date" size=30 name="dailyreportday" placeholder="YYYY-MM-DD"></td>
			    </tr>
				<tr>
			    	<td colspan=2 style="text-align:center"><input type="submit" name="submit" class="cartbutton" border=0 value="Report"></td>
			    </tr>
			</table>
		</form>
		<div align="center">
			<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST["submit"]) and $_POST["submit"] == "Report"){
						$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
						if (mysqli_connect_errno()) {
							printf("Connect failed: %s\n", mysqli_connect_error());
							exit();
						}
						$day = $_POST['dailyreportday'];
						$result=$connection->query("SELECT upc, title, category, price, SUM(quantity) as units, (price*SUM(quantity)) as total FROM purchase NATURAL JOIN purchaseitem NATURAL JOIN item where pdate='$day' GROUP BY upc, category order by category");
						if($result->num_rows == 0) {
							echo "<span class=\"error\">* Sorry! No purchases were made on that day</span>";
						}
						else {
							echo "<table cellpadding=5 class=\"dailyreport\"><thead><tr><th>UPC</th><th>Title</th><th>Category</th><th>Unit Price</th><th>Units</th><th style=\"border-right:0px\">Total Price</th></tr></thead>";
							$totalsales=0.00;
							$totalunits=0;
							while ($row=$result->fetch_assoc()){
								$totalsales+=floatval($row['total']);
								$totalunits+=intval($row['units']);
								echo "<tr>";
								echo "<td>".$row['upc']."</td>";
								echo "<td>".$row['title']."</td>";
								echo "<td>".$row['category']."</td>";
								echo "<td>$ ".$row['price']."</td>";
								echo "<td>".$row['units']."</td>";
								echo "<td style=\"border-right:0px\">".$row['total']."</td>";
								echo "</tr>";

							}
							echo "<tfoot><tbody><tr class=\"dailyreportfoot\"><td colspan=4 style=\"text-align:right\"><b>Total Daily Sales:</b></td><td><b>".$totalunits."</b></td><td><b> $".$totalsales."</b></td></tr></tbody></tfoot>";
							echo "</table>";
						}

						mysqli_close($connection);
					}
				}
			?>
		</div>
		
		
	</body>
	<script src="ams.js"></script>
</html>