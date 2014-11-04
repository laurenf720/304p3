<html>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">

<title>AMS Return</title>
<!--
    A simple stylesheet is provided so you can modify colours, fonts, etc.
-->
    <link href="AMS.css" rel="stylesheet" type="text/css">


<div id="nav">
			<ul>
				<?php 
					
						echo "<li><a href=\"../304p3/emploginpage.php\">Employee Login</a></li>";
						echo "<li><a href=\"../304p3/index.php\">Home</a></li>";
						echo "<li><a href=\"../304p3/search.php\">Search</a></li>";
						echo "<li><div class=\"rightpos\" style=\"cursor: pointer;\"><a href=\"../304p3/custloginpage.php\">Customer Login</a></div></li>"; 
						echo "<li><a href=\"../304p3/return.php\">Returns</a></li>";
						echo "<li><a id=\"button\">Clerk Action 2</a></li>";
						
					
				?>
			</ul>
		</div>





<body>
<h1>Returning Items</h1>

<form id="receipt" name="receipt" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <table border=0 cellpadding=0 cellspacing=0>
        <tr><td>Receipt ID</td><td><input type="text" size=30 name="new_receipt_id"</td></tr>
        <tr><td></td><td><input type="submit" name="submit" border=0 value="SUBMIT"></td></tr>
    </table>
	

<?php

    $connection = new mysqli("localhost", "root", "photon", "AMS");

   
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
	mysql_select_db("AMS");
    
	

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// if else here dealing with the 
		if(isset($_POST["submit"]) && $_POST["submit"] == "RETURN"){
		// this case only occurs if the receipt has already been validated
			$des_upc = $_POST['new_return_upc_id'];
			$des_upc = stripslashes($des_upc);
			$des_upc = mysql_real_escape_string($des_upc);
			
			
			
			$des_quant = $_POST['new_return_quantity'];
			$des_quant = stripslashes($des_quant);
			$des_quant = mysql_real_escape_string($des_quant);
			
			
			$receipt_id  = $_POST['new_next_receipt_id'];
			$receipt_id  = stripslashes($receipt_id);
			$receipt_id	 = mysql_real_escape_string($receipt_id);

	
			$result = $connection->query("SELECT quantity FROM purchaseitem WHERE receiptid ='$receipt_id' AND upc = '$des_upc'");
			
			$rows 	= $result->num_rows;
			$err_msg = "";
			if($rows != 1){
			;
			$err_msg = "UPC entered was invalid.";
			}
			else{
				if(empty($des_quant)){
				$err_msg = "Enter a valid quantity.";
				}
				else{
					if(!is_numeric($des_quant)){
					$err_msg = "Enter a valid quantity.";
						}
					else{
						if($des_quant <= 0){
						$err_msg = "Please enter a positive return quantity.";
						}
						else{
								$pQuantity = $result->fetch_object()->quantity ;
								if($pQuantity < $des_quant){
									$err_msg = "Return quantity is greater than order quantity.";
								}	
					}		}
				}
			}
			// invalid upc selected output the original table with the message underneath
			if($err_msg != ""){
			echo "<p><b>$err_msg</b></p>";
			echo "<p><b>Receipt ID: $receipt_id</b></p>";
						
						$output = $connection->query("SELECT item.upc, title, itype, category, company, price, quantity FROM purchaseItem, item WHERE purchaseItem.receiptid ='$receipt_id' AND purchaseItem.upc = item.upc");
						// trying copy pasta text
						echo "<table border='1'><tr><th>UPC</th><th>Title</th><th>Type</th><th>category</th><th>Company</th><th>Price</th><th>Quantity</th></tr>";
						//echo "<tr><td>" . htmlspecialchars($row['username']) . "</td><td>" . htmlspecialchars($row['time']) . "</td></tr>";
						while($row=mysqli_fetch_assoc($output))
						{
						echo "<tr>";
						echo "<td>" . $row['upc'] . "</td>";
						echo "<td>" . $row['title'] . "</td>";
						echo "<td>" . $row['itype'] . "</td>";
						echo "<td>" . $row['category'] . "</td>";
						echo "<td>" . $row['company'] . "</td>";
						echo "<td>" . $row['price'] . "</td>";
						echo "<td>" . $row['quantity'] . "</td>";
						echo "</tr>";
						};
						
						echo "<form id=\"return\" name=\"return\" method=\"post\" action=\"<\?php echo htmlspecialchars(\$_SERVER[\"PHP_SELF\"])\;\?>";
						// <tr><td>Receipt ID</td><td><input type=\"text\" value=\"$receipt_id\" size=5 name=\"new_next_receipt_id\"</td></tr>
						echo "<table border=0 cellpadding=0 cellspacing=0>
						<tr><td>Return Item UPC</td><td><input type=\"text\" size=5 name=\"new_return_upc_id\"</td></tr>
						<tr><td>Quantity</td><td><input type=\"text\" size=5 name=\"new_return_quantity\"</td></tr>
						<input type=\"hidden\" value=\"$receipt_id\" size=5 name=\"new_next_receipt_id\"/>
						<tr><td></td><td><input type=\"submit\" name=\"submit\" border=0 value=\"RETURN\"></td></tr>
						</table>";
						
						$result = $connection->query("SELECT * FROM returns WHERE receiptid = '$receipt_id'");
						$num 	= $result->num_rows;
						if($num < 1){
						
						// do not display the history table
						}
						else{
						
							$car_num = $connection->query("SELECT cardnumber FROM purchase WHERE receiptid = '$receipt_id'");
							$car_num = $car_num->fetch_object()->cardnumber;
							$amount_Return = $connection->query("SELECT sum(quantity*price) AS total FROM returns, returnitem, item WHERE returns.receiptid = 'test' AND returnitem.retid = returns.retid AND item.upc = returnitem.upc;");
							$amount_Return = $amount_Return->fetch_object()->total;
							$result	= $connection->query("SELECT returns.retid, item.upc, quantity, title, price FROM returns, returnitem, item WHERE returns.receiptid = 'test' AND returnitem.retid = returns.retid AND item.upc = returnitem.upc");
							
							echo "<p>Total amount returned to card: $car_num is : \$$amount_Return</p>";
							echo "<p>Return History:</p>";
							echo "<table border='1'><tr><th>RetID</th><th>UPC</th><th>Title</th><th>Quantity</th><th>Price</th></tr>";
							while($row=mysqli_fetch_assoc($result))
							{
							echo "<tr>";
							echo "<td>" . $row['retid'] . "</td>";
							echo "<td>" . $row['upc'] . "</td>";
							echo "<td>" . $row['title'] . "</td>";
							echo "<td>" . $row['quantity'] . "</td>";
							echo "<td>" . $row['price'] . "</td>";
							echo "</tr>";
						};
						
						}
						
						
						mysqli_close($connection);
			
			
			}
			else{
				// Desired Quantity is correct, upc is valid
				/*
				Need to have a new tuple in:
				RETURN
				RETURN Item
				And output total amount refunded this session to Card#?
				PurchaseItem Quantity should decrease
				*/
			
				$diff_quant = $pQuantity - $des_quant;
				$currDate = date('Y-m-d H:i:s');
				$result = $connection->query("INSERT INTO `ams`.`returns` (`retid`, `rdate`, `receiptid`) VALUES (NULL, '$currDate', '$receipt_id')");
				
				// need the retID of returns so I can add the associated values into ReturnItem table
				$val = $connection->query("SELECT retid FROM returns WHERE rdate = '$currDate'");
				$val = $val->fetch_object()->retid;
				$result = $connection->query("INSERT INTO `ams`.`returnitem` (`retid`, `upc`, `quantity`) VALUES ('$val', $des_upc, '$des_quant')");
				$stmt = $connection->query("UPDATE purchaseitem SET quantity = $diff_quant WHERE receiptid = '$receipt_id' AND upc = '$des_upc'");
				$output = $connection->query("SELECT item.upc, title, itype, category, company, price, quantity FROM purchaseItem, item WHERE purchaseItem.receiptid ='$receipt_id' AND purchaseItem.upc = item.upc");
				
				echo "<table border='1'><tr><th>UPC</th><th>Title</th><th>Type</th><th>category</th><th>Company</th><th>Price</th><th>Quantity</th></tr>";
				//echo "<tr><td>" . htmlspecialchars($row['username']) . "</td><td>" . htmlspecialchars($row['time']) . "</td></tr>";
				while($row=mysqli_fetch_assoc($output))
				{
				echo "<tr>";
				echo "<td>" . $row['upc'] . "</td>";
				echo "<td>" . $row['title'] . "</td>";
				echo "<td>" . $row['itype'] . "</td>";
				echo "<td>" . $row['category'] . "</td>";
				echo "<td>" . $row['company'] . "</td>";
				echo "<td>" . $row['price'] . "</td>";
				echo "<td>" . $row['quantity'] . "</td>";
				echo "</tr>";
				};
						
				echo "<form id=\"return\" name=\"return\" method=\"post\" action=\"<\?php echo htmlspecialchars(\$_SERVER[\"PHP_SELF\"])\;\?>";
				echo "<table border=0 cellpadding=0 cellspacing=0>
				<tr><td>Return Item UPC</td><td><input type=\"text\" size=5 name=\"new_return_upc_id\"</td></tr>
				<tr><td>Quantity</td><td><input type=\"text\" size=5 name=\"new_return_quantity\"</td></tr>
				<input type=\"hidden\" value=\"$receipt_id\" size=5 name=\"new_next_receipt_id\"/>
				<tr><td></td><td><input type=\"submit\" name=\"submit\" border=0 value=\"RETURN\"></td></tr>
				</table>";
				
				$result = $connection->query("SELECT * FROM returns WHERE receiptid = '$receipt_id'");
						$num 	= $result->num_rows;
						if($num < 1){
						
						// do not display the history table
						}
						else{
						
							$car_num = $connection->query("SELECT cardnumber FROM purchase WHERE receiptid = '$receipt_id'");
							$car_num = $car_num->fetch_object()->cardnumber;
							$amount_Return = $connection->query("SELECT sum(quantity*price) AS total FROM returns, returnitem, item WHERE returns.receiptid = 'test' AND returnitem.retid = returns.retid AND item.upc = returnitem.upc;");
							$amount_Return = $amount_Return->fetch_object()->total;
							$result	= $connection->query("SELECT returns.retid, item.upc, quantity, title, price FROM returns, returnitem, item WHERE returns.receiptid = 'test' AND returnitem.retid = returns.retid AND item.upc = returnitem.upc");
							
							echo "<p>Total amount returned to card: $car_num is : \$$amount_Return</p>";
							echo "<p>Return History:</p>";
							echo "<table border='1'><tr><th>RetID</th><th>UPC</th><th>Title</th><th>Quantity</th><th>Price</th></tr>";
							while($row=mysqli_fetch_assoc($result))
							{
							echo "<tr>";
							echo "<td>" . $row['retid'] . "</td>";
							echo "<td>" . $row['upc'] . "</td>";
							echo "<td>" . $row['title'] . "</td>";
							echo "<td>" . $row['quantity'] . "</td>";
							echo "<td>" . $row['price'] . "</td>";
							echo "</tr>";
						};
						
						}
				
				mysqli_close($connection);
				// Add the associated table into 
				
				
				
				
				
				// INSERT INTO `ams`.`returnitem` (`retid`, `upc`, `quantity`) VALUES ('4', '3', '2');
				
				
				
				
				
			}
		
		}
		if (isset($_POST["submit"]) && $_POST["submit"] == "SUBMIT"){
			if (empty($_POST['new_receipt_id'])) {
			echo "<b>Please enter a valid Receipt</b>";
			}
		else{
			$validReceipt = false;
			$receipt_id=$_POST['new_receipt_id'];
			$receipt_id=stripslashes($receipt_id);
			$receipt_id=mysql_real_escape_string($receipt_id);
			
			$result = $connection->query("select * from purchase where receiptid='$receipt_id'");
			
			$rows 	= $result->num_rows;
			if ($rows == 1){
					$current_Date = time();
					$purchase_date = $connection->query("select pdate from purchase where receiptid='$receipt_id'");
					$p_date = $purchase_date->fetch_object()->pdate;
	
					$p_date = strtotime($p_date);
					$p_date= strtotime("+ 15 day", $p_date);
					
					if($current_Date > $p_date){
					echo "<b>Sorry. It has been over 15 days since your purchase!</b>";
					}
					else{
						echo "<b>Receipt ID: $receipt_id</b>";
						echo "<p></p>";
						echo "<p>Purchase History:</p>";
					
						$output = $connection->query("SELECT item.upc, title, itype, category, company, price, quantity FROM purchaseItem, item WHERE purchaseItem.receiptid ='$receipt_id' AND purchaseItem.upc = item.upc");
						// trying copy pasta text
						echo "<table border='1'><tr><th>UPC</th><th>Title</th><th>Type</th><th>Category</th><th>Company</th><th>Price</th><th>Quantity</th></tr>";
						//echo "<tr><td>" . htmlspecialchars($row['username']) . "</td><td>" . htmlspecialchars($row['time']) . "</td></tr>";
						while($row=mysqli_fetch_assoc($output))
						{
						echo "<tr>";
						echo "<td>" . $row['upc'] . "</td>";
						echo "<td>" . $row['title'] . "</td>";
						echo "<td>" . $row['itype'] . "</td>";
						echo "<td>" . $row['category'] . "</td>";
						echo "<td>" . $row['company'] . "</td>";
						echo "<td>" . $row['price'] . "</td>";
						echo "<td>" . $row['quantity'] . "</td>";
						echo "</tr>";
						};
						
						
					
					
						
						echo "<form id=\"return\" name=\"return\" method=\"post\" action=\"<\?php echo htmlspecialchars(\$_SERVER[\"PHP_SELF\"])\;\?>";
						// <tr><td>Receipt ID</td><td><input type=\"text\" value=\"$receipt_id\" size=5 name=\"new_next_receipt_id\"</td></tr>
						echo "<table border=0 cellpadding=0 cellspacing=0>
						<tr><td>Return Item UPC</td><td><input type=\"text\" size=5 name=\"new_return_upc_id\"</td></tr>
						<tr><td>Quantity</td><td><input type=\"text\" size=5 name=\"new_return_quantity\"</td></tr>
						<input type=\"hidden\" value=\"$receipt_id\" size=5 name=\"new_next_receipt_id\"/>
						<tr><td></td><td><input type=\"submit\" name=\"submit\" border=0 value=\"RETURN\"></td></tr>
						</table>";
						
						// return history  "X amount has been refunded to Y Card"
						$result = $connection->query("SELECT * FROM returns WHERE receiptid = '$receipt_id'");
						$num 	= $result->num_rows;
						if($num < 1){
						
						// do not display the history table
						}
						else{
						
							$car_num = $connection->query("SELECT cardnumber FROM purchase WHERE receiptid = '$receipt_id'");
							$car_num = $car_num->fetch_object()->cardnumber;
							$amount_Return = $connection->query("SELECT sum(quantity*price) AS total FROM returns, returnitem, item WHERE returns.receiptid = 'test' AND returnitem.retid = returns.retid AND item.upc = returnitem.upc;");
							$amount_Return = $amount_Return->fetch_object()->total;
							$result	= $connection->query("SELECT returns.retid, item.upc, quantity, title, price FROM returns, returnitem, item WHERE returns.receiptid = 'test' AND returnitem.retid = returns.retid AND item.upc = returnitem.upc");
							
							echo "<p>Total amount returned to card: $car_num is : \$$amount_Return</p>";
							echo "<p>Return History:</p>";
							echo "<table border='1'><tr><th>RetID</th><th>UPC</th><th>Title</th><th>Quantity</th><th>Price</th></tr>";
							while($row=mysqli_fetch_assoc($result))
							{
							echo "<tr>";
							echo "<td>" . $row['retid'] . "</td>";
							echo "<td>" . $row['upc'] . "</td>";
							echo "<td>" . $row['title'] . "</td>";
							echo "<td>" . $row['quantity'] . "</td>";
							echo "<td>" . $row['price'] . "</td>";
							echo "</tr>";
						};
						
						}
						
						mysqli_close($connection);
						
						
						
						
						
						}
			}
			else {
					echo "<b>Receipt ID was not valid. Please enter another one.</b>";
				}
		}
		}
		
	}
?>




</form>
</body>
</html>
