<html>
	<head>
		<title> AMS Website Search Page </title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<?php include 'navbar.php';?>
		
		<div id="wrap">
			<h1>Search for an AMS Item</h1>
			<p></p>
		</div>
		
		<div align=center>
			<table cellpadding=5 class="itemlist"><thead><th>Name</th><th>Type</th><th>Company</th><th>Price</th><th colspan=2>Actions</th></thead>

			<?php
				$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");

			    // Check that the connection was successful, otherwise exit
			    if (mysqli_connect_errno()) {
			        printf("Connect failed: %s\n", mysqli_connect_error());
			        exit();
			    }

			    $result = $connection->query("select * from item order by title");
			    while ($row=$result->fetch_assoc()){
			    	echo "<tr>";
			    	echo "<td>".$row['title']."</td>";
			    	echo "<td>".$row['itype']."</td>";
			    	echo "<td>".$row['company']."</td>";
			    	echo "<td>$ ".$row['price']."</td>";
			    	echo "<td style=\"border-right: 1px black solid;\"><input type=\"button\" name=\"detailsbutton\" border=0 value=\"View Details\">";
			    	echo "<input type=\"button\" name=\"cartbutton\" border=0 value=\"Add to Cart\"></td>";
			    	echo "</tr>";
			    }

			?>
			</table>
		</div>
	</body>
	<script src="ams.js"></script>
</html>