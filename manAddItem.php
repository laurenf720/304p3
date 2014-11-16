<html>
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
			<h1 style="text-align:center">Add New Item</h1>
			<p></p>
		</div>
		<div align="center">

			<form id="additemform" name="additemform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				<table class="additem" style="background-color:white">
					<thead>
						<tr>
							<th colspan=2 style="border-bottom:1px solid">Details for the New Item</th>
						</tr>
					</thead>
					<tr>
					    <td><label>UPC: </label></td>
					    <td><input id="upc" type="text" size=30 name="upc" placeholder="Enter a unique UPC for the new item"></td>
					</tr>
					<tr>
						<td><label>Title: </label></td>
						<td><input id="title" type="text" size=30 name="title" placeholder="Enter a title for the new item"></td>
					</tr>
					<tr>
						<td><label>Type:</label></td>
						<td>
							<select name="type">
								<option>-- SELECT ONE --</option>
								<option>CD</option>
								<option>DVD</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label>Category:</label></td>
						<td>
							<select name="category">
								<option>-- SELECT ONE --</option>
								<option value='rock'>Rock</option>
								<option value='pop'>Pop</option>
								<option value='rap'>Rap</option>
								<option value='country'>Country</option>
								<option value='classical'>Classical</option>
								<option value='new age'>New Age</option>
								<option value='instrumental'>Instrumental</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label>Company: </label></td>
					    <td><input id="company" type="text" size=30 name="company" placeholder="Enter the company for the new item"></td>
					</tr>
					<tr>
						<td><label>Year: </label></td>
					    <td><input id="year" type="number" name="year" placeholder=<?php echo date("Y");?>></td>
					</tr>
					<tr>
						<td><label>Price: </label></td>
					    <td>$<input id="price" type="number" name="price" placeholder="0.00"></td>
					</tr>
					<tr>
						<td><label>Stock: </label></td>
					    <td><input id="stock" type="number" name="stock" placeholder="0"></td>
					</tr>
					<tr>
						<td colspan=2 style="text-align:right">
							<input type="reset" name="cancelbutton" border=0 value="Cancel">
							<input type="submit" name="submit" border=0 value="Add Item">
						</td>
					</tr>
				</table>

				<?php
				$error='';
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					
					if (isset($_POST["submit"]) and $_POST["submit"] == "Add Item"){
						if (empty($_POST['upc']) or empty($_POST['title']) or empty($_POST['company']) or empty($_POST['year']) or empty($_POST['price']) or empty($_POST['stock']) or $_POST['type'] == "-- SELECT ONE --" or $_POST['category'] == "-- SELECT ONE --"){
							$error = "* Please fill in all fields" ;
						}
						elseif(!is_numeric($_POST['year']) or !is_numeric($_POST['price']) or !is_numeric($_POST['stock'])){
							$error = "* Year, Price and Stock fields must be numbers";
						}
						elseif(strlen($_POST['title']) > 50){
							$error = "* Item Title cannot be more than 50 characters";
						}
						elseif(strlen($_POST['upc']) > 11){
							$error = "* UPC cannot be more than 11 characters";
						}
						elseif($_POST['stock']<0 or $_POST['price']<0 or $_POST['year']<0){
							$error = "* Year, Price and Stock fields must by positive numbers";
						}
						else{
							$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");

							// Check that the connection was successful, otherwise exit
							if (mysqli_connect_errno()) {
							    printf("Connect failed: %s\n", mysqli_connect_error());
							    exit();
							}

							$upc = $_POST['upc'];
							$upc=stripslashes($upc);
							$upc=mysql_real_escape_string($upc);

							$title = $_POST['title'];
							$title=stripslashes($title);
							$title=mysql_real_escape_string($title);

							$company = $_POST['company'];
							$company=stripslashes($company);
							$company=mysql_real_escape_string($company);

							$year = $_POST['year'];
							$year=stripslashes($year);
							$year=mysql_real_escape_string($year);

							$price = $_POST['price'];
							$price=stripslashes($price);
							$price=mysql_real_escape_string($price);
							$price = number_format((float)$price, 2, '.', '');

							$stock = $_POST['stock'];
							$stock=stripslashes($stock);
							$stock=mysql_real_escape_string($stock);

							$type = $_POST['type'];
							$type=stripslashes($type);
							$type=mysql_real_escape_string($type);

							$category = $_POST['category'];
							$category=stripslashes($category);
							$category=mysql_real_escape_string($category);

							$result=$connection->query("select * from item where upc='$upc'");
							if ($result->num_rows != 0){
								$error = "* Sorry! That UPC already exists in the system";
							}
							else{
								$stmt=$connection->prepare("INSERT INTO item (upc, title, itype, category, company, iyear, price, stock) VALUES (?,?,?,?,?,?,?,?)");
								$stmt->bind_param("sssssidi", $upc, $title, $type, $category, $company, $year, $price, $stock);
								$stmt->execute();
								if($stmt->error) {
									    printf("<b>Error: %s.</b>\n", $stmt->error);
								} else {
								    echo "<b>Successfully added new item: ".$upc." - ".$title."</b>";
								}
							}

							mysqli_close($connection);
						}
					}
				}
				?>
		    	
		    	<span class="error"><?php echo $error;?></span>
			</form>
			
		</div>
		
	</body>
</html>
		