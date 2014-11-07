<html>
<head>
	<title>AMS Store</title>
	<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	<?php include 'custRegister.php';?>	
	<?php include 'navbar.php';?>
	<?php
			// to prevent unauthorized access
			if ($_SESSION['type'] != 'manager'){
					header("location: index.php");
			}
		?>
		<div id="wrap">
			<h1 style="text-align:center">AMS Managers</h1>
			<p></p>
		</div>
		<div align="center">
		
		<div align="center">
			<form id="addQuantityForm" name="addQuantityForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<table id="addItemQuantity" class="addItem" style="background-color:white">
					<thead>
						<tr>
							<th class="addItemHeader" colspan=3>Add Items to Stock</th>
						</tr>
					</thead>
			        <tr>
			       		<td class="addItemData">UPC</td>
						<td class="addItemData">Quantity</td>
						<td class="addItemData">Price</td>
			       	</tr>
			       	<tr>
			       		<td><input id="UPC" type="text" size=30 name="UPC" placeholder="Enter UPC"></td>
						<td><input id="Quantity" type="text" size=30 name="Quantity" placeholder="Enter Quantity"></td>
						<td><input id="Price" type="text" size=30 name="Price" placeholder="Enter Price"></td>
			       	</tr>	
					
					
			    </table>
				<table>
				<tr>
					<td colspan=2 style="text-align:right"><input type="button" value="Add Row" onClick="addRow('addItemQuantity')"/>
					<td><input type="submit" border=0 name="submit" value="Add Items"/>
				</tr>
				</table>
    			<span class="error"><?php echo $error; ?></span>
			</form>
		</div>
		
	</body>
	
	<script>						
		function addRow(addItemQuantity) {
			var table = document.getElementById(addItemQuantity);
			var rowCount = table.rows.length;
			if(rowCount < 7) {
				var row = table.insertRow(rowCount);
				var colCount = table.rows[2].cells.length;
				for(var i=0; i<colCount; i++) {
					var newcell = row.insertCell(i);
					newcell.innerHTML = table.rows[2].cells[i].innerHTML;
				}
			}
			else{
				 alert("There is a maximum of five rows permitted per transaction");
					   
			}
		}
	</script>
</html>
		