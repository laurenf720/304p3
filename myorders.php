<html>
	<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<?php include 'userlogin.php';?>
		<?php include 'navbar.php';
		if (!isset($_SESSION['login_user'])){
			header("location: userloginpage.php");
		}
		?>

		<div id="wrap">
			<h1 align="center">My Orders</h1>
			<p></p>
		</div>

		<div align="center">
			<?php
				$connection = getconnection();

				$result=$connection->query("SELECT * FROM purchase WHERE cid='".$_SESSION['login_user']."' ORDER BY pdate DESC");
				if ($result->num_rows == 0){
					echo "<span class=\"error\">* You have not made any purchases</span>";
				}
				else{
					while ($row=$result->fetch_assoc()){
						$result2=$connection->query("SELECT * FROM purchaseitem NATURAL JOIN item WHERE receiptid='".$row['receiptid']."'");
						echo "
							<table style=\"width:800px\">
								<tr>
									<td style=\"text-align:left\"><h4>Receipt ID: ".$row['receiptid']."</h4></td>
									<td style=\"text-align:right\"><h4> Purchase Date: ".$row['pdate']."</h4></td>";
									if (empty($row['delivereddate'])){
										echo "<td style=\"text-align:right\"><h4> Expected Delivery: ".$row['expecteddate']."</h4></td>";
									}
									else{
										echo "<td style=\"text-align:right\"><h4> Delivered Date: ".$row['delivereddate']."</h4></td>";
									}
									
								echo "</tr>
							</table>";

						echo "
							<table cellpadding=5 style=\"width:800px\"class='itemdetail'>
								<thead>
									<tr><th style=\"border-bottom:1px solid black\">UPC</th><th style=\"border-bottom:1px solid black\">Name</th><th style=\"border-bottom:1px solid black\">Type</th><th style=\"border-bottom:1px solid black\">Artist</th><th style=\"border-bottom:1px solid black\">Company</th><th style=\"border-bottom:1px solid black\">Quantity</th><th style=\"border-bottom:1px solid black\">Unit Price
									</th></tr>
								</thead>";
						$total=0.00;
						while ($row2=$result2->fetch_assoc()){
							$total+=$row2['price']*$row2['quantity'];
							$upc = $row2['upc'];
							echo "<tr>";
							echo "<td style=\"border-left:1px solid black\">".$row2['upc']."</td>";
							echo "<td>".$row2['title']."</td>";
							echo "<td>".$row2['itype']."</td>";
							echo "<td><p></p>";
							$artistresult = $connection->query("SELECT lsname FROM leadsinger WHERE upc='$upc'");
							while ($artist=$artistresult->fetch_assoc()){
								if (!empty($artist['lsname'])){
									echo $artist['lsname']."<br>";
							    }
							}
							echo "</td>";
							echo "<td>".$row2['company']."</td>";
							echo "<td>".$row2['quantity']."</td>";
							echo "<td>$ ".$row2['price']."</td>";
						}
						echo "<tfoot>
									<tbody>
										<tr class=\"checkoutfoot\"><td colspan=6 style=\"text-align:right\"><b>Total:</b></td><td><b>$ ".number_format((float)$total, 2, '.', '')."</b></td>
										</tr>
									</tbody>
								</tfoot>";
						echo "</table><p></p>";
					}
				}
				mysqli_close($connection);

			?>
		</div>
	</body>
	<script src="ams.js"></script>
</html>