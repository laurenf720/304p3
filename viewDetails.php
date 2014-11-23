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
		?>

		<div id="wrap">
			<h1 style="text-align:center">Item Details</h1>
			<p></p>
		</div>
		<div align="center">
			<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST["submit"]) and $_POST["submit"] == "Back to Search"){
						unset($_SESSION['itemdetails']);
						header("location: search.php");
					}
				}

				if (!isset($_SESSION['itemdetails'])){
					echo "<span class=\"error\"><b>* You have not selected an item</b></span>";
				}
				else{
					$connection = getconnection();
				    $upc = $_SESSION['itemdetails'];
				    $query = "(SELECT item.upc,title, itype, category, company, iyear,price, stock, lsname FROM item LEFT JOIN leadsinger ON item.upc=leadsinger.upc WHERE item.upc='$upc')";
					$result=$connection->query($query);
					echo "<form id=\"itemdetails\" name=\"itemdetails\" action=\"";
					echo htmlspecialchars($_SERVER["PHP_SELF"]);
					echo "\" method=\"POST\">";
					echo "<table class=\"itemdetail\" cellpadding=5>";
					while ($row=$result->fetch_assoc()){
						echo "<tr><td>UPC:</td><td style=\"border-right: 0px\">".$row['upc']."</td></tr>";
						echo "<tr><td>Title:</td><td style=\"border-right: 0px\">".$row['title']."</td></tr>";
						echo "<tr><td>Type:</td><td style=\"border-right: 0px\">".$row['itype']."</td></tr>";
						echo "<tr><td>Artist(s):</td><td style=\"border-right: 0px\">";
						$artistresult = $connection->query("SELECT lsname FROM leadsinger WHERE upc='$upc'");
					    if($artistresult->num_rows == 0){
					    	echo " none";
					    }
					    else {
					    	while ($artist=$artistresult->fetch_assoc()){
						    	if (!empty($artist['lsname'])){
						    		echo $artist['lsname']."<br>";
						    	}
					    	}
					   	}
					   	echo "<tr><td>Track(s):</td><td style=\"border-right: 0px\">";
					   	$tracktitles = $connection->query("SELECT songtitle FROM hassong WHERE upc='$upc'");
					   	if ($tracktitles->num_rows == 0){
					   		echo " none";
					   	}
					   	else {
					   		echo "<ol>";
					   		while ($song=$tracktitles->fetch_assoc()){
					   			if (!empty($song['songtitle'])){
						    		echo "<li>".$song['songtitle']."</li>";
						    	}
					   		}
					   		echo "</ol>";
					   	}

					   	echo "</td></tr>";
						echo "<tr><td>Category:</td><td style=\"border-right: 0px\">".$row['category']."</td></tr>";
						echo "<tr><td>Company:</td><td style=\"border-right: 0px\">".$row['company']."</td></tr>";
						echo "<tr><td>Year:</td><td style=\"border-right: 0px\">".$row['iyear']."</td></tr>";
						echo "<tr><td>Price:</td><td style=\"border-right: 0px\">$ ".$row['price']."</td></tr>";
						echo "<tr><td style=\"border-bottom: 0px\">Stock:</td><td style=\"border-bottom: 0px; border-right: 0px\">".$row['stock']."</td></tr>";
						echo "<tr><td colspan=2 style=\"text-align:right; border-bottom: 0px; border-right: 0px\"><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Back to Search\"></td></tr>";
						
					    
					}
					echo "</table>";
					echo "</form>";

				    mysqli_close($connection);			
				}
			?>
			
		</div>
	</body>
	<script src="ams.js"></script>
</html>