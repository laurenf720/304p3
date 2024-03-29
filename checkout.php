<html>
	<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">

	</head>
	<body>
		
		<?php 
		session_start();
		include 'navbar.php';
		include 'databaseconnection.php';
		$connection = getconnection();
		$cid=$_SESSION['login_user'];
		$result = $connection->query("SELECT * FROM cart NATURAL JOIN item WHERE cid='$cid' ORDER BY upc");
		if ($result->num_rows == 0){
			header("location: cart.php");
		}
		mysqli_close($connection);

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
						<td><input name="creditcard" type="text" size=20 placeholder="1234***********9"></td>
					</tr>
					<tr>
						<td><label>Credit Card Expiry Date: </label></td>
						<td>
							<select name="expmonth">
								<?php
									$count=1;
									while ($count <=12){
										echo "<option>".str_pad($count, 2, "0", STR_PAD_LEFT)."</option>";
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
			<?php

				$connection = getconnection();

				if ($_SERVER["REQUEST_METHOD"] == "POST") {

					$currdate=date("Y-m-d");
					$cid=$_SESSION['login_user'];
					$error ='';
					if (isset($_POST["submit"]) and $_POST["submit"] == "Complete Order"){
						if (empty(trim($_POST['creditcard'])) or !is_numeric(trim($_POST['creditcard']))){
							echo "<span class=\"error\">* Oops! You did not enter a valid credit card number</span>";
						}
						elseif(strlen($_POST['creditcard'])>16){
							echo "<span class=\"error\">* Credit Cards cannot have more than 16 digits</span>";
						}
						elseif($_POST['expyear']."-".$_POST['expmonth']<$currdate){
							echo "<span class=\"error\">* Oops! Looks like your credit card is expired!</span>";
						}
						else{
							$creditcard=trim($_POST['creditcard']);
							$creditcard=stripslashes($creditcard);
							$creditcard=mysql_real_escape_string($creditcard);

							$expdate=$_POST['expmonth']."/".$_POST['expyear'];
							$expdate=stripslashes($expdate);
							$expdate=mysql_real_escape_string($expdate);

							// calculate when expected delivery is - shop can handle 10 deliveries in one day
							$result=$connection->query("SELECT * FROM purchase WHERE delivereddate IS NULL");
							$daycount=round($result->num_rows/10)+1; // plus one because we don't do same-day delivery
							$expecteddate=date('Y-m-d', strtotime($currdate. ' + '.$daycount.' days'));
							$stmt=$connection->prepare("INSERT INTO purchase(pdate, cid, cardnumber, expirydate, expecteddate) VALUES (?,?,?,?,?)");
							$stmt->bind_param("sssss", $currdate, $cid, $creditcard, $expdate, $expecteddate);
							$stmt->execute();
							if($stmt->error) {       
						        printf("<span class=\"error\"><b>Error: %s.</b></span>\n", $stmt->error);
						    }
						    else{
						    	$receiptid = intval($connection->insert_id);
						    	$result = $connection->query("SELECT * FROM cart NATURAL JOIN item WHERE cid='$cid'");
								// make sure all quantities are within stock amount before you do anything
								while($row=$result->fetch_assoc()){	
									if ($row['quantity']>$row['stock']){
										$error=$error."Please update the quantity for item '".$row['upc']."'<br>";
									}
								}
						    	
						    	if ($error == ''){
						    		$result = $connection->query("SELECT * FROM cart NATURAL JOIN item WHERE cid='$cid'");
									while($row=$result->fetch_assoc()){	
										$stmt=$connection->prepare("INSERT INTO purchaseitem(receiptid, upc, quantity) VALUES (?,?,?)");
										$stmt->bind_param("isi", $receiptid, $row['upc'], $row['quantity']);
										$stmt->execute();
										if($stmt->error) {       
										   printf("<span class=\"error\"><b>Error: %s.</b></span>\n", $stmt->error);
									    }
										else{
										    // update the item's stock
										    $newstock=$row['stock']-$row['quantity'];
										    $result2=$connection->query("UPDATE item SET stock=$newstock WHERE upc='".$row['upc']."'");
										    // empty the customer's cart
										    $result2=$connection->query("DELETE FROM cart WHERE cid='$cid'");
										    header("location: checkoutsuccess.php");
										}
						    		}
								}
								else {
									$deleterow=$connection->query("DELETE FROM purchase WHERE receiptid='$receiptid'");
									echo "<span class=\"error\">* Sorry! There is not enough stock to complete your order<br>".$error."</span>";
								}
						    }
						}
					}
				}

				mysqli_close($connection);
			?>
			<table style="width:750px">
				<tr><td><h3>Your Bill:</h3></td>
				<td style="text-align:right"><h4> Expected Delivery: 
				<?php
					$connection = getconnection();
					// calculate when expected delivery is - shop can handle 10 deliveries in one day
					$result=$connection->query("SELECT * FROM purchase WHERE delivereddate IS NULL");
					$daycount=round($result->num_rows/10)+1; // plus one because we don't do same-day delivery
					$expecteddate=date('Y-m-d', strtotime(date('Y-m-d'). ' + '.$daycount.' days'));
					echo $expecteddate;
					mysqli_close($connection);
				?></h4></td></tr>
			</table>
			<table cellpadding=5 class='itemdetail'>
				<thead>
					<tr><th style="border-bottom:1px solid black">UPC</th><th style="border-bottom:1px solid black">Name</th><th style="border-bottom:1px solid black">Type</th><th style="border-bottom:1px solid black">Artist</th><th style="border-bottom:1px solid black">Company</th><th style="border-bottom:1px solid black">Quantity</th><th style="border-bottom:1px solid black">Unit Price</th></tr>
				</thead>
				<?php
				$connection = getconnection();

				$cid=$_SESSION['login_user'];
				$result = $connection->query("SELECT * FROM cart NATURAL JOIN item WHERE cid='$cid' ORDER BY upc");
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
						<tr class=\"checkoutfoot\"><td colspan=6 style=\"text-align:right\"><b>Total:</b></td><td><b> $".number_format((float)$total, 2, '.', '')."</b></td>
						</tr>
					</tbody>
				</tfoot>";
				?>
			</table>
		</div>
	</body>
	<script src="ams.js"></script>
</html>