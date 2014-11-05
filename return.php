<html>
	<head>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta content="utf-8" http-equiv="encoding">
		<title>AMS Return</title>
	    <link href="AMS.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<?php include 'emplogin.php';?>
		<div id="nav">
			<ul>
				<?php 
					if (!isset($_SESSION['logged'])){
						echo "<li><a href=\"../304p3/emploginpage.php\">Employee Login</a></li>";
						echo "<li><a href=\"../304p3/index.php\">Home</a></li>";
						echo "<li><a href=\"../304p3/search.php\">Search</a></li>";
						echo "<li><div class=\"rightpos\" style=\"cursor: pointer;\"><a href=\"../304p3/custloginpage.php\">Customer Login</a></div></li>"; 
					}
					elseif (isset($_SESSION['logged']) and $_SESSION['logged']== true) {
						echo "<li><a href=\"../304p3/index.php\">Home</a></li>";
						echo "<li><a href=\"../304p3/search.php\">Search</a></li>";
						if ($_SESSION['type'] == 'manager'){
							echo "<li><a id=\"button\">Manager Action 1</a></li>";
							echo "<li><a id=\"button\">Manager Action 2</a></li>";
						}
						elseif ($_SESSION['type'] == 'clerk'){
							echo "<li><a href=\"../304p3/return.php\">Returns</a></li>";
							echo "<li><a id=\"button\">Clerk Action 2</a></li>";
						}
						else {
							echo "<li><a id=\"button\">Customer Action 1</a></li>";
							echo "<li><a id=\"button\">Customer Action 2</a></li>";
						}
						echo "<li><div class=\"rightpos\" style=\"cursor: pointer;\"><a id=\"welcomebutton\">Welcome ".$_SESSION["login_user"]."!</a></div> </li>"; 
					}
				?>
			</ul>
		</div>
		
		<div id="dropdown_menu" class="hidden_menu"> 
			<table id="container">
				<tr><td><a class="sub-menu" href="/../304p3/settings.php">SETTINGS</a></td></tr>
				<tr><td><a class="sub-menu" href="/../304p3/logout.php">LOGOUT</a></td></tr>
			</table>					
		</div>
		<!-- end of navigation bar -->


		<div id="wrap">
			<h1 style="text-align:center">Returning Items</h1>
			<p></p>
		</div>
		<div align="center">
			<form id="receipt" name="receipt" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		    	<table class="login" style="background-color:white">
		        	<tr><td>Receipt ID: </td><td><input type="text" size=30 name="new_receipt_id"</td></tr>
		        	<tr><td colspan=2 style="text-align:right"><input type="submit" name="submit" border=0 value="SUBMIT"></td></tr>
		    	</table>
			</form>
		</div>
		

		<?php 
			$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
		    if (mysqli_connect_errno()) {
		        printf("Connect failed: %s\n", mysqli_connect_error());
		        exit();
		    }
			mysql_select_db("AMS");
			$receipt_id=''; // to allow it to exist out of scope
			$p_date='';
			
			function printpurchases($receipt_id){
				$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
		    	if (mysqli_connect_errno()) {
		        	printf("Connect failed: %s\n", mysqli_connect_error());
		        	exit();
		   		}
				$result = $connection->query("select pdate from purchase where receiptid='$receipt_id'");
				$p_date = $result->fetch_assoc()['pdate'];
				echo "<div align=\"center\">
						<table cellpadding=\"5\" class=\"purchases\"><thead><tr><th align=\"left\"><h3>Receipt ID: $receipt_id</h3></th>";
				echo "<th align=\"left\"><h3>Purchase Date: $p_date</h3></th></tr>";
				echo "<tr></tr><tr><th class=\"purchaseheader\">UPC</th><th class=\"purchaseheader\">Title</th><th class=\"purchaseheader\">Type</th><th class=\"purchaseheader\">Category</th><th class=\"purchaseheader\">Company</th><th class=\"purchaseheader\">Price</th><th class=\"purchaseheader\">Quantity</th></tr></thead>";
					
				$output = $connection->query("SELECT item.upc, title, itype, category, company, price, quantity FROM purchaseItem, item WHERE purchaseItem.receiptid ='$receipt_id' AND purchaseItem.upc = item.upc");
						
				while($row=$output->fetch_assoc()) {
					echo "<tr>";
					echo "<td class=\"purchasedata\" style=\"border-left:1px solid black\">" . $row['upc'] . "</td>";
					echo "<td class=\"purchasedata\">" . $row['title'] . "</td>";
					echo "<td class=\"purchasedata\">" . $row['itype'] . "</td>";
					echo "<td class=\"purchasedata\">" . $row['category'] . "</td>";
					echo "<td class=\"purchasedata\">" . $row['company'] . "</td>";
					echo "<td class=\"purchasedata\">" . $row['price'] . "</td>";
					echo "<td class=\"purchasedata\">" . $row['quantity'] . "</td>";
					echo "</tr>";
				}
				printreturns($_SESSION['receiptid']);
				echo "</table></div>";
				mysqli_close($connection);
			}

			function printreturnform(){
				echo 
						"<div align=\"center\">
							<form id=\"return\" name=\"return\" method=\"post\" action=\"".htmlspecialchars($_SERVER["PHP_SELF"])."\">
								<table cellpadding=\"5px\"class=\"purchases\" style=\"background-color:white; margin-top: 15px\">
							    	<thead><tr><th colspan=2 style=\"border-bottom: 1px solid black\">Return Items</th></tr></thead>
							        <tr><td>Item UPC: </td><td><input type=\"text\" size=20 name=\"new_return_upc_id\"</td></tr>
							        <tr><td>Quantity: </td><td><input type=\"number\" size=5 name=\"new_return_quantity\"</td></tr>
							        <tr><td colspan=2 style=\"text-align:right\"><input type=\"submit\" name=\"submit\" border=0 value=\"RETURN\"></td></tr>
							    </table>
							</form>
							<p></p>
						</div>";
			}

			function printreturns($receipt_id){
				$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
		    	if (mysqli_connect_errno()) {
		        	printf("Connect failed: %s\n", mysqli_connect_error());
		        	exit();
		   		}

				$card_num = $connection->query("SELECT cardnumber FROM purchase WHERE receiptid = '$receipt_id'");
				$card_num = $card_num->fetch_object()->cardnumber;

				$amount_Return = $connection->query("SELECT sum(quantity*price) AS total FROM returns, returnitem, item WHERE returns.receiptid = '$receipt_id' AND returnitem.retid = returns.retid AND item.upc = returnitem.upc;");
				$amount_Return = $amount_Return->fetch_object()->total;
				if (empty($amount_Return)){
					$amount_Return="0.00";
				}
				$result	= $connection->query("SELECT returns.retid, item.upc, quantity, title, price FROM returns, returnitem, item WHERE returns.receiptid = '$receipt_id' AND returnitem.retid = returns.retid AND item.upc = returnitem.upc");

				echo "<tr><td></td></tr><thead><th align=\"left\"><h3>Return History</h3></th></thead>";
				echo "<tr></tr><tr><th class=\"purchaseheader\">RetID</th><th class=\"purchaseheader\">UPC</th><th class=\"purchaseheader\">Title</th><th class=\"purchaseheader\">Quantity</th><th class=\"purchaseheader\">Price</th></tr></thead>";
						
				while($row=mysqli_fetch_assoc($result)) {
						echo "<tr>";
						echo "<td class=\"purchasedata\" style=\"border-left:1px solid black\">" . $row['retid'] . "</td>";
						echo "<td class=\"purchasedata\">" . $row['upc'] . "</td>";
						echo "<td class=\"purchasedata\">" . $row['title'] . "</td>";
						echo "<td class=\"purchasedata\">" . $row['quantity'] . "</td>";
						echo "<td class=\"purchasedata\">" . $row['price'] . "</td>";
						echo "</tr>";
				}
				echo "<tr><td>Total amount returned to card: $card_num is : \$$amount_Return</td></tr>";
				mysqli_close($connection);
			}

			// checking for valid receipt ID
			if (isset($_POST["submit"]) && $_POST["submit"] == "SUBMIT"){
				if (empty($_POST['new_receipt_id'])) {
					echo "<div align=\"center\"><span class=\"error\"><b>* Please enter a valid Receipt ID</b></span></div>";
					unset ($_SESSION['receiptid']);
				}
				else {
					$validReceipt = false;
					$receipt_id=$_POST['new_receipt_id'];
					$receipt_id=stripslashes($receipt_id);
					$receipt_id=mysql_real_escape_string($receipt_id);
					$_SESSION['receiptid']=$receipt_id;
					printpurchases($_SESSION['receiptid']);
					
					// check if receipt is past return date
					$result = $connection->query("select pdate from purchase where receiptid='$receipt_id'");
					$p_date = $result->fetch_assoc()['pdate'];
					$current_Date = date ( "d-m-Y", time());
					$last_return_date = date('d-m-Y', strtotime($p_date. ' + 15 days'));
					if(strtotime($current_Date) > strtotime($last_return_date)){
						echo "<div align=\"center\"><span class=\"error\"><b>* Sorry. It has been over 15 days since your purchase!</b></span></div>";
					}
					else {
						$validReceipt = true;
						printreturnform();
					}

				}
			}
			
			elseif (isset($_POST["submit"]) && $_POST["submit"] == "RETURN"){

				if (empty($_POST['new_return_upc_id']) or empty($_POST['new_return_quantity'])){
					echo "<div align=\"center\"><span class=\"error\"><b>* Please enter a valid Item UPC and Quantity</b></span></div>";
				}
				$err_msg = "";
				$des_upc = $_POST['new_return_upc_id'];
				$des_upc = stripslashes($des_upc);
				$des_upc = mysql_real_escape_string($des_upc);
				$des_quant = $_POST['new_return_quantity'];
				$des_quant = stripslashes($des_quant);
				$des_quant = mysql_real_escape_string($des_quant);
				$receipt_id  = $_SESSION['receiptid'];

				$result = $connection->query("SELECT quantity FROM purchaseitem WHERE receiptid ='$receipt_id' AND upc = '$des_upc'");
				$rows 	= $result->num_rows;
				if($rows != 1){
					$err_msg = "UPC entered was invalid.";
				}
				else{
					if(!is_numeric($des_quant)){
						$err_msg = "Enter a valid quantity.";
					}
					elseif ($des_quant <= 0){
							$err_msg = "Please enter a positive return quantity.";
					}
					else{
						$pQuantity = $result->fetch_object()->quantity;
						if($pQuantity < $des_quant){
							$err_msg = "Return quantity is greater than order quantity.";
						}	
					}		
				}

				// invalid upc selected output the original table with the message underneath
				if($err_msg != ""){
					echo "<p><b>$err_msg</b></p>";
					echo "<p><b>Receipt ID: $receipt_id</b></p>";
							
					$output = $connection->query("SELECT item.upc, title, itype, category, company, price, quantity FROM purchaseItem, item WHERE purchaseItem.receiptid ='$receipt_id' AND purchaseItem.upc = item.upc");
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

				}
				printpurchases($_SESSION['receiptid']);
				printreturnform();
			}
			mysqli_close($connection);
		?>
		<script src="ams.js"></script>
	</body>
</html>