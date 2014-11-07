<html>
<script>
function updateQuantity(upc, title) {
    'use strict';
    do{
	    var quantity = window.prompt("How much would you like in your cart?", "Enter a positive integer");
	    // if user presses cancel then break
	    if (quantity == null || quantity ==""){
	    	break;
	    }
	} while ( parseInt(quantity, 10) < 1 || isNaN(parseInt(quantity, 10)));
    // Set the value of a hidden HTML element in this form
    
    // var form = document.getElementById('itemaction');
    // form.upc.value = upc;
    // form.submitAction.value="AddToCart"
    // form.quantity.value = parseInt(quantity, 10);
    // form.title.value= title;
    // form.submit();
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
				$result = $connection->query("SELECT * FROM cart NATURAL JOIN item WHERE cid='$cid' ORDER BY upc");
				if ($result->num_rows == 0){
					echo "<span class=\"error\">* Your shopping cart is empty</span>";
				}
				else {
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
						echo "<td style=\"border-right: 1px black solid;\"><input type=\"submit\" name=\"submit\" class=\"detailsbutton\" onClick=\"javascript:updateQuantity('".$row['upc']."','".$cid."');\"border=0 value=\"Update Quantity\"></td>";;
						echo "</tr>";
					}

					echo "</table>";
				}

				mysqli_close($connection);
			}
		?>
		</div>
	</body>
	<script src="ams.js"></script>
</html>