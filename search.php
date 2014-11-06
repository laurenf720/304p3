<html>

<script>
// function addToCart(upc) {
// 	var person = prompt("Please enter your name", "Harry Potter");
// }
//     // 'use strict';
//     //   // Set the value of a hidden HTML element in this form
//     //   var form = document.getElementById('itemaction');
//     //   form.upc.value = upc;
//     //   var quantity = prompt("How many would you like to buy?", "Enter quantity");
//     //   while (parseInt(quantity) == Number.NaN or parseInt(quantity)<1){
//     //   	 System.out.println("ERROR: Please enter a positive integer");
//     //   }
//     //   // Post this form
//     //   form.submit();
// }
function formSubmit(upc) {
    'use strict';
    do{
	    var selection = window.prompt("Please enter a number", "");
	    // if user presses cancel then break
	    if (selection == null || selection =="")
	    	break;
	} while ( parseInt(selection, 10) < 1 || isNaN(parseInt(selection, 10)));

    // Set the value of a hidden HTML element in this form
    var form = document.getElementById('itemaction');
    form.upc.value = upc;
    form.submitAction.value="AddToCart"
    // Post this form
    form.submit();
    
}
</script>

	<head>
		<title> AMS Website Search Page </title>
		<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>

	<body>
		<?php include 'navbar.php';?>
		
		<div id="wrap">
			<h1 align="center">Search for an AMS Item</h1>
			<p></p>
		</div>
		
		<div align=center>
			<table cellpadding=5 class="itemlist"><thead><th>UPC</th><th>Name</th><th>Type</th><th>Company</th><th>Price</th><th colspan=2>Actions</th></thead>

			<?php
				$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");

			    // Check that the connection was successful, otherwise exit
			    if (mysqli_connect_errno()) {
			        printf("Connect failed: %s\n", mysqli_connect_error());
			        exit();
			    }

			    $result = $connection->query("select * from item order by title");
			    
			       echo "<form id=\"itemaction\" name=\"itemaction\" action=\"";
				    echo htmlspecialchars($_SERVER["PHP_SELF"]);
				    echo "\" method=\"POST\">";
				    // Hidden value is used if the delete link is clicked
				    echo "<input type=\"hidden\" name=\"upc\" value=\"-1\"/>";
				   // We need a submit value to detect if delete was pressed 
				    echo "<input type=\"hidden\" name=\"submitAction\" value=\"action\"/>";

			    while ($row=$result->fetch_assoc()){
			    	echo "<tr>";
			    	echo "<td>".$row['upc']."</td>";
			    	echo "<td>".$row['title']."</td>";
			    	echo "<td>".$row['itype']."</td>";
			    	echo "<td>".$row['company']."</td>";
			    	echo "<td>$ ".$row['price']."</td>";

			    	echo "<td style=\"border-right: 1px black solid;\">
			    			<input type=\"button\" class=\"editbtn\" name=\"detailsbutton\" border=0 value=\"View Details\">";
			    	echo "<a href=\"javascript:formSubmit('".$row['upc']."');\">Add to Cart</a>";
			    	// echo "<form id=\"cart\"action=\"javascript:addToCart('".$row['upc']."');\"><input type=\"button\" name=\"cartbutton\" border=0 value=\"Add to Cart\"></form></td>";
			    	echo "</tr>";
			    }

			    echo "</form>";

			?>
			</table>
		</div>

	</body>
	<script src="ams.js"></script>
</html>