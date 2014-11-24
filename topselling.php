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
		if (!isset($_SESSION['logged'])){
				header("location: userloginpage.php");
			}
		?>

		<div id="wrap">
			<h1 style="text-align:center">Top Selling Items</h1>
			<p></p>
		</div>

		<form id="topsellingform" name="topsellingform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
			<table align="center" class="login" style="background-color:white">
				<thead>
					<tr><td colspan=4 style="text-align:center">Pick a date range for purchases</td></tr>
				</thead>
			    <tr>
			       	<td><label>Start Day: </label></td>
					<td><input id="topsellingdaystart" type="date" name="topday1" placeholder="YYYY-MM-DD"></td>
					<td><label>End Day: </label></td>
					<td><input id="topsellingdayend" type="date" name="topday2" placeholder="YYYY-MM-DD"></td>
			    </tr>
			    <tr>
			    	<td><label>Show top (Optional): </label></td>
			    	<td><input id="topsellingcount" type="number" name="topsellingcount" placeholder="10"></td>
			    </tr>
				<tr>
			    	<td colspan=4 style="text-align:center"><input type="submit" name="submit" class="cartbutton" border=0 value="Report"></td>
			    </tr>
			</table>
		</form>

		<div align="center">
			<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST["submit"]) and $_POST["submit"] == "Report"){
						$day1 = $_POST['topday1'];
						$day2 = $_POST['topday2'];
						$topcount=$_POST['topsellingcount'];

						$day1=stripslashes($day1);
						$day1=mysql_real_escape_string($day1);

						$day2=stripslashes($day2);
						$day2=mysql_real_escape_string($day2);

						$topcount=stripslashes($topcount);
						$topcount=mysql_real_escape_string($topcount);
						$count=0;
						if (empty($topcount)){
							$topcount=10;
						}
						if (empty($day2) or empty($day1)){
							echo "<span class=\"error\">* Please pick a date range</span>"; 
						}
						elseif ($day2<$day1){
							echo "<span class=\"error\">* Oops! The end day cannot be before the start day!</span>"; 
						}
						elseif(!is_numeric($topcount) or $topcount<=0){
							echo "<span class=\"error\">* 'Show top' field must be an integer greater than 0</span>"; 
						}
						else {
							$connection = getconnection();

							$query="select upc, title, price, company, stock, SUM(quantity) as units from item natural join purchase natural join purchaseitem where pdate<='$day2' and pdate>='$day1' group by upc order by SUM(quantity) DESC";
							$result=$connection->query($query);

							if ($result->num_rows == 0){
								echo "<span class=\"error\">* Sorry! No purchases were made during that date range</span>"; 
							}
							else{
								echo "<table cellpadding=5 class=\"dailyreport\"><thead>
									<tr><th colspan=6 style=\"border-right:0px\">Top ".$topcount." Selling Item(s) From ".$day1." to ".$day2."</th></tr>
									<tr><th>UPC</th><th>Title</th><th>Company</th><th>Unit Price</th><th>Stock</th><th style=\"border-right:0px\">Units Sold</th></tr>
								</thead>";
								while($row=$result->fetch_assoc() and $count < $topcount){
									if ($row['units']==0){
										// do nothing - item was returned and does not count
									}
									else{
										echo "<tr>";
										echo "<td>".$row['upc']."</td>";
										echo "<td>".$row['title']."</td>";
										echo "<td>".$row['company']."</td>";
										echo "<td>".$row['price']."</td>";
										echo "<td>".$row['stock']."</td>";
										echo "<td style=\"border-right:0px\">".$row['units']."</td>";
										echo "</tr>";
										$count +=1;
									}
								}
								echo "</table>";
							}

							mysqli_close($connection);
						}
					}
				}
			?>
		</div>
	</body>
	<script src="ams.js"></script>
</html>