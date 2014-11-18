<?php
	session_start();
	$error='';
	$message='';
	$warning='';

	$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");


/*

*/	
function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
}
	
	
	
	
    // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	if (isset($_POST["submit"]) && $_POST["submit"] == "Add Items") {
			$updateOrder = $connection->prepare("UPDATE purchase SET delivereddate =? WHERE receiptid=?");
			$checkReceipt = $connection->prepare("select * FROM purchase WHERE receiptid=?");
			$checkDelivered = $connection->prepare("select delivereddate FROM purchase WHERE receiptid=?");
			$receiptid=$_POST['receiptIDOrder'];
			$deliveryDate = $_POST['deliveredDate'];
			$errorCount=0;
			$warningCount=0;
			$updatedRows=0;
			$checkReceipt->bind_param("s",$tempReceipt);
			$checkDelivered->bind_param("s",$tempReceipt);
			$updateOrder->bind_param("ss", $tempDeliverDate, $tempReceipt);
    		/*		
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
			*/
			foreach($receiptid as $a => $b) {
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
					$tempReceipt = stripslashes($receiptid[$a]);
					$tempDeliverDate = stripslashes($deliveryDate[$a]);
					$tempReceipt = mysql_real_escape_string($tempReceipt);
					$tempDeliverDate = mysql_real_escape_string($tempDeliverDate);
					//check if the receiptID exists in DB
					if(!$checkReceipt->execute()){
						$error .= "Error $errorCount: " . mysqli_error($connection) . "\r\n";
						$errorCount += 1;
					}
					else{
						$current_Date = date ( "Y-m-d", time());
						$result=$checkReceipt->get_result();
						//Receipt ID does not exist in database warn the user
						if($result->num_rows == 0){
							$warning .= "Warning $warningCount: Receipt ID with $tempReceipt was not found in the database.\r\n";
							$warningCount += 1;
						}
						//Invalid date if deliveredDate is greater than todays date OR less than purchaseDate
						elseif(!validateDate($tempDeliverDate) || $tempDeliverDate < $result->fetch_assoc()['pdate'] || $tempDeliverDate > $current_Date){
							$error .= "Error $errorCount: ReceiptID $tempReceipt contained an invalid input date: $tempDeliverDate\r\n";
							$errorCount += 1;
						}
						else {
						// Update the delivered date
							$checkDelivered->execute();
							$alreadyDelivered = $checkDelivered->get_result();
							$delivDate = $alreadyDelivered->fetch_assoc()['delivereddate'];
							if(!is_null($delivDate)){
									$warning .= "Warning $warningCount: Receipt ID with $tempReceipt already had a delivery date of $delivDate and was overwritten.\r\n";
									$warningCount += 1;
									echo "<script type=\"text/javascript\">confirm123();</script>";
									echo "<b>HELLO</b>";
							}
							
							if(!$updateOrder->execute()){
							$error .= "Error $errorCount: " . mysqli_error($connection) . "\r\n";
									$errorCount += 1;
							}
							$updatedRows += 1;
						}
					}
				}
			}
			
			$updateOrder->close();			
			$checkReceipt->close();			
			$checkDelivered->close();
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

<script> function confirm123(){
  if(confirm("Overwrite existing delivered date12345612312312312")){
  confirm("HELLO");
  };
}
</script>