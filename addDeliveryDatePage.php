<html>
<head>
	<title>AMS Store</title>
	<link href="AMS.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	<?php include 'addDeliveryDate.php';?>	
	<?php include 'navbar.php';?>
		<div id="wrap">
			<h1 style="text-align:center">Update Delivery Date of Order</h1>
			<p></p>
		</div>
		<div align="center">
		
		<div align="center">
			<form id="addQuantityForm" name="addQuantityForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<table id="addItemQuantity" class="addItem" style="background-color:white">
					<thead>
						<tr>
							<th class="addItemHeader" colspan=3>Update Delivery Date of Orders</th>
						</tr> 	
					</thead>
			        <tr>
			       		<td class="addItemData">Receipt ID of Order</td>
						<td class="addItemData">Delivered Date</td>
			       	</tr>
			       	<tr>
			       		<td><input id="receiptid" type="text" size=30 name="receiptIDOrder[]" placeholder="Enter Receipt ID"></td>
						<td><input id="delivereddate" type="date" size=30 name="deliveredDate[]" placeholder="YYYY-MM-DD"></td>
			       	</tr>	
					
					
			    </table>
				<table>
				<tr>
					<td colspan=2 style="text-align:right"><input type="button" value="Add Row" onClick="addRow('addItemQuantity')"/>
					<td><input type="submit" border=0 name="submit" value="Update Delivered Date"/>
				</tr>
				</table>
    			<div class="error"><?php echo $error; ?></div>
				<div class="warning"><?php echo $warning; ?></div>
				<span class="message"><?php echo $message; ?></span>
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
		function confOverWrite(){
			var form = document.createElement("form");
			var element1 = document.createElement("input");
			form.method = "POST";
			form.action = "addDeliveryDate.php";
			element1.name = "confirmed";
			if(confirm("Overwrite existing delivered date")){
				element1.value = true;
				form.appendChild(element1);
			}
			else{
				element1.value = false;
				form.appendChild(element1);
			}
			document.body.appendChild(form);
			form.submit();
		}
	</script>		
	
</html>
		