<?php
	include 'databaseconnection.php';
	session_start();
	$error='';
	$message='';
	$warning='';

	$connection = getconnection();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	if (isset($_POST["submit"]) && $_POST["submit"] == "Add Items") {
    				
			$stockUpdate=$connection->prepare("update item set stock=? where upc=?");
			$stockPriceUpdate=$connection->prepare("update item set stock=?, price=? where upc=?");
			$checkUpc=$connection->prepare("select upc, stock, price from item where upc=?");
			$upc=$_POST['UPC'];
			$quantity=$_POST['Quantity'];
			$price=$_POST['Price'];
			$errorCount=0;
			$warningCount=0;
			$updatedRows=0;
			$checkUpc->bind_param("s",$tempUpc);
			$stockPriceUpdate->bind_param("sss",$tempStock,$tempPrice,$tempUpc);
			$stockUpdate->bind_param("ss",$tempStock,$tempUpc);
			
			foreach($upc as $a => $b) {
				if(!ctype_alnum($b)){
					//If blank skip it
					if($b != ''){
						$error .= "Error $errorCount: There are invalid characters in the UPC: $b\r\n";
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
					if(!$checkUpc->execute()){
						$error .= "Error $errorCount: " . mysqli_error($connection) . "\r\n";
						$errorCount += 1;
					}
					$result=$checkUpc->get_result();
					//Set a blank stock equal to zero to enable the user to just update the price
					if($tempStock == ''){
						$tempStock = 0;
					}
					//UPC does not exist in database warn the user and redirect them to the correct form
					if($result->num_rows == 0){
						$warning .= "Warning $warningCount: Item with UPC $tempUpc was not found in the database, use <a href=\"../304p3/manAddItem.php\">Add New Item</a> instead to add new items\r\n";
						$warningCount += 1;
					}
					//Check Quantity for invalid values
					elseif(!is_numeric($tempStock) || $tempStock < 0){
						$error .= "Error $errorCount: Item with UPC $tempUpc contained an invalid quantity: $tempStock\r\n";
						$errorCount += 1;
					}
					else {
					
						$tempStock += $result->fetch_assoc()['stock'];
						//Update price and stock				
						if(is_numeric($tempPrice) && $tempPrice > 0){
							if(!$stockPriceUpdate->execute()) {
								$error .= "Error $errorCount: " . mysqli_error($connection) . "\r\n";
								$errorCount += 1;
							}
						}
						//Update the stock if price is not set or is invalid
						else  {
							if(isset($tempPrice) && $tempPrice != ''){
								$warning .="Warning $warningCount: Item with UPC $tempUpc contained an invalid price: $tempPrice but stock was updated\r\n";
								$warningCount += 1;
							}
							if(!$stockUpdate->execute()){
								$error .= "Error $errorCount: " . mysqli_error($connection) . "\r\n";
								$errorCount += 1;
							}
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
			if($warningCount >0){
				$warning .= "Total: $warningCount warning(s) were encountered during update\r\n";
			}
			$message .= "$updatedRows items were updated.";
    	}
    }
    mysqli_close($connection);
?>