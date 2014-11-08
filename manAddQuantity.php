<?php
	session_start();
	$error='';
	$message='';

	$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
	

    // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	if (isset($_POST["submit"]) && $_POST["submit"] == "Add Items") {
    				
			$stockUpdate=$connection->prepare("update item set stock=? where upc=?");
			$stockPriceUpdate=$connection->prepare("update item set stock=?,price=? where upc=?");
			$checkUPC=$connection->prepare("select upc, quantity, price from item where upc=?");
			$upc=$_POST['UPC'];
			$quantity=$_POST['Quantity'];
			$price=$_POST['Price'];
			$errorCount=0;
			$updatedRows;
			
			foreach($upc as $a => $b) {		
				//Ensure that there is a value in UPC field, otherwise, skip it
				if(!is_null($b)){
					// To protect MySQL injection for Security purposes
					$tempUpc = stripslashes($upc+a);
					$tempStock = stripslashes($quantity+a);
					$tempPrice = stripslashes($Price+a);
					$tempUpc = mysql_real_escape_string($tempUpc);
					$tempStock = mysql_real_escape_string($tempStock);
					$tempPrice = mysql_real_escape_string($tempPrice);
					
					//check if the upc exists in DB
					$checkUPC->bind_param("s",$tempUpc);
					$result=$checkUPC->execute();
					//UPC does not exist in database
					if($result->num_rows == 0){
						$error .= "$tempUpc was not found in the database" . PHP_EOL;
						$errorCount += 1;
					}
					//Check Quantity for invalid values
					elseif(is_nan($tempStock) ||$quantity+a <= 0){
						$errorCount += 1;
						$error .= "$tempUpc contained an invalid quantity: $tempStock" . PHP_EOL;
					}
					else
					{
						$tempStock += $result->fetch_assoc()['quantity'];
						//Do not update price
						if(isnull($result->fetch_assoc()['price'])){
							$stockUpdate->bind_param("ss",$tempStock,$tempUpc);
							$stockUpdate->execute();
						}
						//Update the price
						else{
							$stockPriceUpdate->bind_param("sss",$tempStock,$tempPrice,$tempUpc);
							$stockPriceUpdate->execute();
						}
						$updatedRows += 1;
					}
				}
			}
			
			$stockPriceupdate->close();
			$stockUpdate->close();
			$checkUPC->close();
			
			$error .= "Total $errorCount errors were encountered during insert";
			$message = "$updatedRows were updated.";//updated x quantities, updated y prices
    	}
    }
    mysqli_close($connection);
?>