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
			$stockPriceUpdate=$connection->prepare("update item set stock=?, price=? where upc=?");
			$checkUpc=$connection->prepare("select upc, stock, price from item where upc=?");
			$upc=$_POST['UPC'];
			$quantity=$_POST['Quantity'];
			$price=$_POST['Price'];
			$errorCount=0;
			$updatedRows=0;
			$checkUpc->bind_param("i",$tempUpc);
			$stockPriceUpdate->bind_param("sss",$tempStock,$tempPrice,$tempUpc);
			$stockUpdate->bind_param("ss",$tempStock,$tempUpc);
			
			foreach($upc as $a => $b) {
				if(!is_numeric($b)){
					//If blank skip it
					if($b != ''){
						$error .= "Error $errorCount: UPC was not a number\r\n";
						$errorCount+= 1;
					}
				}
				
				//Ensure that there is a value in UPC field, otherwise, skip it
				else{
					// To protect MySQL injection for Security purposes
					$tempUpc = stripslashes($upc[$a]);
					$tempStock = stripslashes($quantity[$a]);
					$tempPrice = stripslashes($price[$a]);
					$tempUpc = mysql_real_escape_string($tempUpc);
					$tempStock = mysql_real_escape_string($tempStock);
					$tempPrice = mysql_real_escape_string($tempPrice);
					
					//check if the upc exists in DB
					$checkUpc->execute();
					$result=$checkUpc->get_result();
					//UPC does not exist in database
					if($result->num_rows == 0){
						$error .= "Error $errorCount: Item with UPC $tempUpc was not found in the database\r\n";
						$errorCount += 1;
					}
					//Check Quantity for invalid values
					elseif(!is_numeric($tempStock) || $tempStock < 0){
						$error .= "Error $errorCount: Item with UPC $tempUpc contained an invalid quantity: $tempStock\r\n";
						$errorCount += 1;
					}
					else
					{
						$tempStock += $result->fetch_assoc()['stock'];
						//Update price						
						if(is_numeric($tempPrice) && $tempPrice > 0){
							$stockPriceUpdate->execute();
						}
						//Update the stock
						elseif(isset($tempPrice)){
							$stockUpdate->execute();
						}
						//Error
						else{
							$error .= "Error $errorCount: Item with UPC $tempUpc contained an invalid price: $tempPrice\r\n";
							$errorCount += 1;
						}
						$updatedRows += 1;
					}
				}
			}
			
			$stockUpdate->close();			
			$stockPriceUpdate->close();			
			$checkUpc->close();
			if($errorCount > 0){
				$error .= "Total: $errorCount error(s) were encountered during update\r\n";
			}
			$message .= "$updatedRows items were updated.";//updated x quantities, updated y prices
    	}
    }
    mysqli_close($connection);
?>