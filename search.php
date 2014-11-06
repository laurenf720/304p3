<html>

<script>
function addToCart(upc, title) {
    'use strict';
    do{
	    var quantity = window.prompt("How much would you like to add to cart?", "Enter a positive integer");
	    // if user presses cancel then break
	    if (quantity == null || quantity ==""){
	    	quantity=0;
	    	break;
	    }
	} while ( parseInt(quantity, 10) < 1 || isNaN(parseInt(quantity, 10)));
    // Set the value of a hidden HTML element in this form
    var form = document.getElementById('itemaction');
    form.upc.value = upc;
    form.submitAction.value="AddToCart"
    form.quantity.value = parseInt(quantity, 10);
    form.title.value= title;
    form.submit();
}

function getDetails(upc, title, type, category, company, year, price, stock){
	alert("UPC: "+ upc+"\nTitle: "+ title + "\nType: " + type + "\nCategory: " + category + "\nCompany: " + company + "\nYear: "+ year + "\nPrice: $" + price + "\nStock: " + stock);
}

function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }

</script>

	<head>
		<title> AMS Website Search Page </title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>

	<body>

		<?php 
		session_start();
		include 'navbar.php';
		?>
		
		<div id="wrap">
			<h1 align="center">Search for an AMS Item</h1>
			<p></p>
		</div>

		<div align=center>
			<!-- search bar -->
			<form id="itemsearch" name="itemsearch" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<table class="searchbar">
					<tr>	
						<td>Search in: 
							<select name="searchfield">
								<option value="All">All</option>
								<option value="Title">Title</option>
								<option value="Artist">Artist</option>
								<option value="Category">Category</option>
								<option value="Year">Year</option>
							</select>
						</td>
						<td><input type="search" name="search" placeholder="Search"></td>
						<td>Order By: 
							<select name="searchorder">
								<option value="title">Item Name</option>
								<option value="price ASC">Price - Low to High</option>
								<option value="price DESC">Price - High to Low</option>
							</select>
						</td>
						<td><input type="submit" name="submit" class="searchbutton" value="Search"></td>
					</tr>
				</table>
			</form>
			<!-- end of search bar -->

			<table cellpadding=5 class="itemlist"><thead><th>UPC</th><th>Name</th><th>Type</th><th>Company</th><th>Price</th><th colspan=2>Actions</th></thead>

			<?php
				function printItemList($searchfield, $searchtext, $searchorder) {
					$whereclause = false;
					if ($searchfield != "All"){
						$whereclause = true;
					}
					$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");

				    // Check that the connection was successful, otherwise exit
				    if (mysqli_connect_errno()) {
				        printf("Connect failed: %s\n", mysqli_connect_error());
				        exit();
				    }

					$result = $connection->query("SELECT * FROM item ORDER BY $searchorder");
			    
				    echo "<form id=\"itemaction\" name=\"itemaction\" action=\"";
					echo htmlspecialchars($_SERVER["PHP_SELF"]);
					echo "\" method=\"POST\">";
					echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
					echo "<input type=\"hidden\" name=\"quantity\" value=\"-1\"/>";
					echo "<input type=\"hidden\" name=\"title\" value=\"-1\"/>";
					echo "<input type=\"hidden\" name=\"submitAction\" value=\"action\"/>";

				    while ($row=$result->fetch_assoc()){
				    	echo "<tr>";
				    	echo "<td>".$row['upc']."</td>";
				    	echo "<td>".$row['title']."</td>";
				    	echo "<td>".$row['itype']."</td>";
				    	echo "<td>".$row['company']."</td>";
				    	echo "<td>$ ".$row['price']."</td>";

				    	echo "<td style=\"border-right: 1px black solid;\">
				    			<input type=\"button\" name=\"submit\" class=\"detailsbutton\" onClick=\"getDetails('".$row['upc']."','".$row['title']."','".$row['itype']."','".$row['category']."','".$row['company']."','".$row['iyear']."','".$row['price']."','".$row['stock']."'); \"border=0 value=\"View Details\" >";
				    	echo "<input type=\"submit\" name=\"submit\" class=\"cartbutton\" onClick=\"javascript:addToCart('".$row['upc']."','".$row['title']."');\"border=0 value=\"Add to Cart\"></td>";
				    	echo "</tr>";
				    }
				    echo "</form>";
				    mysqli_close($connection);
				}


				$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");

				// Check that the connection was successful, otherwise exit
				if (mysqli_connect_errno()) {
				    printf("Connect failed: %s\n", mysqli_connect_error());
				    exit();
				}

				$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
			    
				if ($_SERVER["REQUEST_METHOD"] == "POST" && !$pageWasRefreshed) {
					if (isset($_POST["submit"]) and $_POST["submit"] == "Add to Cart"){
						if (($_POST['quantity']) == 0){
							echo "<span class=\"error\">*You did not enter a valid a quantity</span>";
						}
						elseif (!isset($_SESSION['logged'])) {
							echo "<span class=\"error\">* Please login before you add something to your cart</span>";
						}
						else {
							$cid=$_SESSION['login_user'];
							$upc=$_POST['upc'];
							$quantity=$_POST['quantity'];
							$title=$_POST['title'];
							$upc=stripslashes($upc);
							$upc=mysql_real_escape_string($upc);
							$quantity=stripslashes($quantity);
							$quantity=mysql_real_escape_string($quantity);
							$title=stripslashes($title);
							$title=mysql_real_escape_string($title);

						    if (isset($_POST["submitAction"]) && $_POST["submitAction"] == "AddToCart") {
						    	$result = $connection->query("SELECT * FROM cart WHERE cid='$cid' and upc='$upc'");
						    	if ($result->num_rows == 1){
						    		$stmt = $connection->prepare("UPDATE cart SET quantity=quantity+(?) where cid=? and upc=?");
						    		$stmt->bind_param("iss", $quantity, $cid, $upc);
						    		$stmt->execute();
						    	}
						    	else{
								    $stmt = $connection->prepare("INSERT INTO cart (cid, upc, quantity) VALUES (?,?,?)");
								    $stmt->bind_param("ssi", $cid, $upc, $quantity);
								    $stmt->execute();
								}
								if($stmt->error) {
								    printf("<b>Error: %s.</b>\n", $stmt->error);
								} else {
								    echo "<b>Successfully added to cart: '".$title."' x ".$quantity."</b>";
								}
							}
						}
					}
					elseif (isset($_POST["submit"]) and $_POST["submit"] == "Search"){
						$searchfield=$_POST['searchfield'];
						$searchorder=$_POST['searchorder'];
						$searchtext=$_POST['search'];
						
						$searchfield=stripslashes($searchfield);
						$searchfield=mysql_real_escape_string($searchfield);
						$searchorder=stripslashes($searchorder);
						$searchorder=mysql_real_escape_string($searchorder);
						$searchtext=stripslashes($searchtext);
						$searchtext=mysql_real_escape_string($searchtext);

						printItemList('All','', $searchorder);

					}
			   	}
			   	mysqli_close($connection);
			?>
			</table>
		</div>

	</body>
	<script src="ams.js"></script>
</html>