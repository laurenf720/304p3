<html>
<script>
function updateQuantity(upc) {
    'use strict';
    do{
	    var quantity = window.prompt("How much would you like in your cart?", "Enter a positive integer");
	    // if user presses cancel then break
	    if (quantity == null || quantity ==""){
	    	form.quantity.value=-1;
	    	return;
	    }
	} while ( parseInt(quantity, 10) < 0 || isNaN(parseInt(quantity, 10)));
    //Set the value of a hidden HTML element in this form
    
    var form = document.getElementById('updatequantity');
    form.upc.value = upc;
    form.quantity.value = parseInt(quantity, 10);
    form.submit();
}
</script>

	<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">

	</head>
	<body>
		
		<?php 
		session_start();
		include 'navbar.php';
		include 'databaseconnection.php';
		?>

		<div id="wrap">
			<h1 style="text-align:center">Shopping Cart</h1>
			<p></p>
		</div>
		<div align="center">
		<?php
			function printcart(){
				$connection = getconnection();

				$cid=$_SESSION['login_user'];
				$result = $connection->query("SELECT * FROM cart NATURAL JOIN item WHERE cid='$cid' ORDER BY upc");
				if ($result->num_rows == 0){
					echo "<span class=\"error\">* Your shopping cart is empty</span>";
				}
				else {
					echo "<form id=\"updatequantity\" name=\"updatequantity\" action=\"";
					echo htmlspecialchars($_SERVER["PHP_SELF"]);
					echo "\" method=\"POST\">";
					echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
					echo "<input type=\"hidden\" name=\"quantity\" value=\"-1\"/>";
					echo "<input type=\"hidden\" name=\"submitAction\" value=\"updatequantity\"/>";
					echo "<table cellpadding=5 class=\"itemlist\"><thead>
						<tr><th colspan=7>Your Shopping Cart</th></tr>";
					echo "<tr><th>UPC</th><th>Name</th><th>Type</th><th>Artist</th><th>Company</th><th>Quantity</th><th>Actions</th></tr></thead>";

					while($row=$result->fetch_assoc()){
						$upc = $row['upc'];
						echo "<tr>";
						echo "<td>".$row['upc']."</td>";
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
						echo "<td style=\"border-right: 1px black solid;\"><input type=\"submit\" name=\"submit\" class=\"detailsbutton\" onClick=\"javascript:updateQuantity('".$row['upc']."');\"border=0 value=\"Update Quantity\"></td>";;
						echo "</tr>";
					}

					echo "</table>";
					echo "</form>";
					echo "<form id=\"checkoutform\" name=\"checkoutform\" action=\"";
					echo htmlspecialchars($_SERVER["PHP_SELF"]);
					echo "\" method=\"POST\">"; 
					echo "<input type=\"submit\" name=\"submit\" border=0 value=\"Proceed to Checkout\">";
					echo "</form>";
				}
				
				mysqli_close($connection);
			}

			$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
			
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$connection = getconnection();

				if (isset($_POST["submit"]) and $_POST["submit"] == "Update Quantity") {
					
					$cid=$_SESSION['login_user'];
					$upc=$_POST['upc'];
					$quantity=$_POST['quantity'];
					$upc=stripslashes($upc);
					$upc=mysql_real_escape_string($upc);
					$quantity=stripslashes($quantity);
					$quantity=mysql_real_escape_string($quantity);

					

					if ($quantity == 0){
						$result=$connection->query("DELETE FROM cart WHERE cid='$cid' AND upc='$upc'");
					}
					elseif($quantity == -1){
						// do nothing
					}
					else{
						$result=$connection->query("SELECT * FROM item WHERE upc='$upc'");
						$stock=$result->fetch_assoc()['stock'];
						if ($quantity > $stock){
							echo "<span class=\"error\">*Oops! We don't have enough in stock to update order.<br>
							There is currently only ".$stock." of item ".$upc." in stock.</span>";
						}
						else{
							$result=$connection->query("UPDATE cart SET quantity='$quantity' WHERE cid='$cid' AND upc='$upc'");
						}
					}
				}
				elseif(isset($_POST["submit"]) and $_POST["submit"] == "Proceed to Checkout"){
					header("location: checkout.php");
				}
				mysqli_close($connection);
			}

			// end of logic - printing cart
			if (!isset($_SESSION['logged'])){
					echo "<span class=\"error\">* Please login before viewing your shopping cart</span>";
			}
			elseif(isset($_SESSION['type']) and $_SESSION['type'] != "customer"){
				echo "<span class=\"error\">* Only customers have an online shopping cart </span><br>";
			}
			else {
				printcart();
			}

		?>
		</div>
	</body>
	<script src="ams.js"></script>
</html>