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

function viewDetails(upc){
	var form = document.getElementById('itemaction');
	form.upc.value = upc;
	form.submitAction.value="ViewDetails";
	form.submit();
}

// function getDetails(upc, title, type, category, company, year, price, stock){
// 	alert("UPC: "+ upc+"\nTitle: "+ title + "\nType: " + type + "\nCategory: " + category + "\nCompany: " + company + "\nYear: "+ year + "\nPrice: $" + price + "\nStock: " + stock);
// }

function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
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
			<h1 align="center">Search for an AMS Item</h1>
			<p></p>
		</div>

		<div align=center>
			<!-- search bar -->
			<?php
				function printSearchbar(){
					if (!isset($_SESSION['searchtoggle'])){
						echo "<form id=\"itemsearch\" name=\"itemsearch\" method=\"post\" action=\"".htmlspecialchars($_SERVER["PHP_SELF"])."\">
							<table class=\"searchbar\">
								<tr>	
									<td>Search in: 
										<select name=\"searchfield\">
											<option value=\"All\">All</option>
											<option value=\"title\">Title</option>
											<option value=\"lsname\">Artist</option>
											<option value=\"itype\">Type</option>
											<option value=\"category\">Category</option>
											<option value=\"iyear\">Year</option>
											
										</select>
									</td>
									<td><input type=\"search\" name=\"search\" placeholder=\"Search\"></td>
									<td>Order By: 
										<select name=\"searchorder\">
											<option value=\"title ASC\">Item Name (A-Z)</option>
											<option value=\"title DESC\">Item Name (Z-A)</option>
											<option value=\"price ASC\">Price - Low to High</option>
											<option value=\"price DESC\">Price - High to Low</option>
										</select>
									</td>
									<td style=\"text-align:right\"><input type=\"submit\" name=\"submit\" class=\"searchbutton\" value=\"Search\"></td>
									<td style=\"text-align:right\"><input type=\"submit\" name=\"submit\" class=\"togglebutton\" value=\"Toggle\"></td>
								</tr>
							</table>
						</form>";
					}
					elseif(isset($_SESSION['searchtoggle']) and $_SESSION['searchtoggle'] == "advanced"){
						echo "<form id=\"itemsearch\" name=\"itemsearch\" method=\"post\" action=\"".htmlspecialchars($_SERVER["PHP_SELF"])."\">
							<table class=\"searchbar\">
								<tr>	
									<td>Artist: 
										<input type=\"search\" name=\"artistsearch\" placeholder=\"Enter Artist\">
									</td>
									<td>Category:
										<input type=\"search\" name=\"categorysearch\" placeholder=\"Enter Category\"></td>
									<td>Title:  
										<input type=\"search\" name=\"titlesearch\" placeholder=\"Enter Title\"></td>
									</td>
									<td>Quantity (Optional):  
										<input type=\"number\" name=\"quantitysearch\" placeholder=\"0\"></td>
									</td>
									<td><input type=\"submit\" name=\"submit\" class=\"searchbutton\" value=\"Search\"></td>
									<td><input type=\"submit\" name=\"submit\" class=\"togglebutton\" value=\"Toggle\"></td>
								</tr>
							</table>
						</form>";
					}
				}

				function printItemList($searchfield, $searchtext, $searchorder) {
					if (!isset($_SESSION['logged'])) {
							echo "<span class=\"error\">* Please login to add something to your cart</span>";
						}
					
					if ($searchfield == "All"){
						$searchfield='CONCAT (item.upc, title, itype, category, company, iyear)';
					}
					$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");

				    // Check that the connection was successful, otherwise exit
				    if (mysqli_connect_errno()) {
				        printf("Connect failed: %s\n", mysqli_connect_error());
				        exit();
				    }
				    $query = "(SELECT item.upc,title, itype, category, company, iyear,price, stock FROM item LEFT JOIN leadsinger ON item.upc=leadsinger.upc WHERE 
						$searchfield LIKE '%$searchtext%') 
						UNION (SELECT item.upc,title, itype, category, company, iyear,price, stock FROM item LEFT JOIN leadsinger ON item.upc=leadsinger.upc WHERE lsname LIKE '%$searchtext%') ORDER BY $searchorder";
					
					$result = $connection->query($query);
					if (!$result->num_rows == 0){
					   	echo "<table cellpadding=5 class=\"itemlist\"><thead><th>UPC</th><th>Name</th><th>Type</th><th>Artist</th><th>Company</th><th>Price</th><th colspan=2>Actions</th></thead>";
					    echo "<form id=\"itemaction\" name=\"itemaction\" action=\"";
						echo htmlspecialchars($_SERVER["PHP_SELF"]);
						echo "\" method=\"POST\">";
						echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
						echo "<input type=\"hidden\" name=\"quantity\" value=\"-1\"/>";
						echo "<input type=\"hidden\" name=\"title\" value=\"-1\"/>";
						echo "<input type=\"hidden\" name=\"submitAction\" value=\"action\"/>";

					    while ($row=$result->fetch_assoc()){
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
					    	echo "<td>$ ".$row['price']."</td>";

					    	echo "<td style=\"border-right: 1px black solid;\">
					    			<input type=\"submit\" name=\"submit\" class=\"detailsbutton\" onClick=\"javascript:viewDetails('".$row['upc']."');\" border=0 value=\"View Details\" >";
					    	if (isset($_SESSION['type']) and  $_SESSION['type']== "customer"){
					    		echo "<input type=\"submit\" name=\"submit\" class=\"cartbutton\" onClick=\"javascript:addToCart('".$row['upc']."','".$row['title']."');\"border=0 value=\"Add to Cart\"></td>";
					    	}
					    	echo "</tr>";
					    }
					    echo "</form>";
					}
					else {
						echo "<span class=\"error\">* Sorry, no items were found that match your search</span>";
					}
				    mysqli_close($connection);
				}

				function printadvancedsearch($artistsearch, $categorysearch, $titlesearch, $quantitysearch){
					if (!isset($_SESSION['logged'])) {
							echo "<span class=\"error\">* Please login to add something to your cart</span>";
						}
					if (empty($artistsearch)){
						$artistsearch="";
					}
					if (empty($categorysearch)){
						$categorysearch="";
					}
					if (empty($titlesearch)){
						$titlesearch="";
					}
					if (empty($quantitysearch)){
						$quantitysearch=0;
					}

					if ($artistsearch==""){
						$query = "(SELECT item.upc,title, itype, category, company, iyear,price, stock, lsname FROM item LEFT JOIN leadsinger ON item.upc=leadsinger.upc WHERE title LIKE '%$titlesearch%' and category LIKE '%$categorysearch%' and lsname LIKE '%$artistsearch%' and stock >= $quantitysearch)
						UNION
						(SELECT item.upc,title, itype, category, company, iyear,price, stock, lsname FROM item LEFT JOIN leadsinger ON item.upc=leadsinger.upc WHERE title LIKE '%$titlesearch%' and category LIKE '%$categorysearch%' and stock >= $quantitysearch)";
					}
					else {
						$query = "(SELECT item.upc,title, itype, category, company, iyear,price, stock, lsname FROM item LEFT JOIN leadsinger ON item.upc=leadsinger.upc WHERE title LIKE '%$titlesearch%' and category LIKE '%$categorysearch%' and lsname LIKE '%$artistsearch%' and stock >= $quantitysearch)";
					}
					

					$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
					if (mysqli_connect_errno()) {
					    printf("Connect failed: %s\n", mysqli_connect_error());
					    exit();
					}

					$result=$connection->query($query);

					if (!$result->num_rows == 0){
					   	echo "<table cellpadding=5 class=\"itemlist\"><thead><th>UPC</th><th>Name</th><th>Type</th><th>Artist</th><th>Company</th><th>Price</th><th colspan=2>Actions</th></thead>";
					    echo "<form id=\"itemaction\" name=\"itemaction\" action=\"";
						echo htmlspecialchars($_SERVER["PHP_SELF"]);
						echo "\" method=\"POST\">";
						echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
						echo "<input type=\"hidden\" name=\"quantity\" value=\"-1\"/>";
						echo "<input type=\"hidden\" name=\"title\" value=\"-1\"/>";
						echo "<input type=\"hidden\" name=\"submitAction\" value=\"action\"/>";

					    while ($row=$result->fetch_assoc()){
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
					    	echo "<td>$ ".$row['price']."</td>";

					    	echo "<td style=\"border-right: 1px black solid;\">
					    			<input type=\"submit\" name=\"submit\" class=\"detailsbutton\" onClick=\"javascript:viewDetails('".$row['upc']."');\" border=0 value=\"View Details\" >";
					    	if (isset($_SESSION['type']) and  $_SESSION['type']== "customer"){
					    		echo "<input type=\"submit\" name=\"submit\" class=\"cartbutton\" onClick=\"javascript:addToCart('".$row['upc']."','".$row['title']."');\"border=0 value=\"Add to Cart\"></td>";
					    	}
					    	echo "</tr>";
					    }
					    echo "</form>";
					}
					else {
						echo "<span class=\"error\">* Sorry, no items were found that match your search</span>";
					}

					mysqli_close($connection);
				}

				$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
				$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");

				// Check that the connection was successful, otherwise exit
				if (mysqli_connect_errno()) {
				    printf("Connect failed: %s\n", mysqli_connect_error());
				    exit();
				}
  
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST["submit"]) and $_POST["submit"] == "Add to Cart"){
						if (($_POST['quantity']) == 0){
							echo "<span class=\"error\">*You did not enter a valid a quantity</span>";
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
						    	$result=$connection->query("SELECT * FROM customer WHERE cid='$cid'");
						    	$rows=$result->num_rows;

						    	$result=$connection->query("SELECT stock FROM item WHERE upc='$upc'");
						    	$row=$result->fetch_assoc();
						    	if ($rows == 0){
						    		echo "<span class=\"error\"><b>*Oops! Looks like your account is not a customer account! Please register as a customer</b></span>";
						    	}
						    	elseif($row['stock']<$quantity){
						    		echo "<span class=\"error\">*Oops! We don't have enough for your order. Please verify the amount we have in stock</span>";
						    	}
						    	else {
						    		$result = $connection->query("SELECT * FROM cart WHERE cid='$cid' and upc='$upc'");
							    	if ($result->num_rows == 1){
							    		$row2=$result->fetch_assoc();
							    		if ($row2['quantity']+$quantity>$row['stock']){
							    			echo "<span class=\"error\">*Oops! We don't have enough for your order. You already have ".$row2['quantity']." in your cart</span>";
							    		}
							    		else{
							    			$stmt = $connection->prepare("UPDATE cart SET quantity=quantity+(?) where cid=? and upc=?");
							    			$stmt->bind_param("iss", $quantity, $cid, $upc);
							    			$stmt->execute();

							    			if($stmt->error) {
											    printf("<b>Error: %s.</b>\n", $stmt->error);
											} else {
											    echo "<b>Successfully added to cart: '".$title."' x ".$quantity."</b>";
											}
							    		}
							    	}
							    	else{
									    $stmt = $connection->prepare("INSERT INTO cart (cid, upc, quantity) VALUES (?,?,?)");
									    $stmt->bind_param("ssi", $cid, $upc, $quantity);
									    $stmt->execute();
									    if($stmt->error) {
										    printf("<b>Error: %s.</b>\n", $stmt->error);
										} else {
										    echo "<b>Successfully added to cart: '".$title."' x ".$quantity."</b>";
										}
									}
								}
							}
						}
					}
					elseif (isset($_POST["submit"]) and $_POST["submit"] == "Search"){
						if(!isset($_SESSION['searchtoggle'])){
							$searchfield=$_POST['searchfield'];
							$searchorder=$_POST['searchorder'];
							$searchtext=$_POST['search'];
							
							$searchfield=stripslashes($searchfield);
							$searchfield=mysql_real_escape_string($searchfield);
							$searchorder=stripslashes($searchorder);
							$searchorder=mysql_real_escape_string($searchorder);
							$searchtext=stripslashes($searchtext);
							$searchtext=mysql_real_escape_string($searchtext);

							$_SESSION['searchorder']=$searchorder;
							$_SESSION['searchfield']=$searchfield;
							$_SESSION['searchtext']=$searchtext;
						}
						elseif(isset($_SESSION['searchtoggle']) and $_SESSION['searchtoggle']=="advanced"){
							$artistsearch=$_POST['artistsearch'];
							$categorysearch=$_POST['categorysearch'];
							$titlesearch=$_POST['titlesearch'];
							$quantitysearch=$_POST['quantitysearch'];
							
							$artistsearch=stripslashes($artistsearch);
							$artistsearch=mysql_real_escape_string($artistsearch);
							
							$categorysearch=stripslashes($categorysearch);
							$categorysearch=mysql_real_escape_string($categorysearch);
							
							$titlesearch=stripslashes($titlesearch);
							$titlesearch=mysql_real_escape_string($titlesearch);

							$quantitysearch=stripslashes($quantitysearch);
							$quantitysearch=mysql_real_escape_string($quantitysearch);

							$_SESSION['artistsearch']=$artistsearch;
							$_SESSION['categorysearch']=$categorysearch;
							$_SESSION['titlesearch']=$titlesearch;
							$_SESSION['quantitysearch']=$quantitysearch;
						}
						
					}
					elseif(isset($_POST["submit"]) and $_POST["submit"] == "Toggle"){
						if(isset($_SESSION['searchtoggle'])){
							unset($_SESSION['searchtoggle']);
							unset($_SESSION['artistsearch']);
							unset($_SESSION['categorysearch']);
							unset($_SESSION['titlesearch']);
							unset($_SESSION['quantitysearch']);
						}
						else{
							$_SESSION['searchtoggle'] = "advanced";
						}
					}
					elseif(isset($_POST["submit"]) and $_POST["submit"] == "View Details"){
						if(isset($_POST["submitAction"]) and $_POST["submitAction"] == "ViewDetails"){
							$_SESSION['itemdetails']=$_POST['upc'];
							header("location: viewDetails.php");
						}
					}
			   	}
			   	printSearchbar();
			   	// end of logic - now printing item list
			   	if (!isset($_SESSION['searchtoggle'])){
				   	if (!(isset($_SESSION['searchtext']))){
						printItemList('All','', 'title');
					}
					else{
						printItemList($_SESSION['searchfield'],$_SESSION['searchtext'], $_SESSION['searchorder']);
					}
				}
				else{
					if (isset($_SESSION['artistsearch'])){
						printadvancedsearch($_SESSION['artistsearch'],$_SESSION['categorysearch'], $_SESSION['titlesearch'], $_SESSION['quantitysearch']);
					}
					else{
						printItemList('All','', 'title');
					}
				}
			   	mysqli_close($connection);
			?>
		</div>

	</body>
	<script src="ams.js"></script>
</html>