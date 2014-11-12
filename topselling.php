<html>
	<head>
		<title>AMS Store</title>
		<link href="AMS.css" rel="stylesheet" type="text/css">

	</head>
	<body>
		
		<?php 
		session_start();
		include 'navbar.php';
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

						if ($day2<$day1){
							echo "<span class=\"error\">* Oops! The end day cannot be before the start day!</span>"; 
						}
						if(!is_numeric($topcount) or $topcount<=0){
							echo "<span class=\"error\">* 'Show top' field must be an integer greater than 0</span>"; 
						}

						$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
						if (mysqli_connect_errno()) {
							printf("Connect failed: %s\n", mysqli_connect_error());
							exit();
						}

						

						
						

						mysqli_close($connection);
					}
				}
			?>
		</div>
	</body>
	<script src="ams.js"></script>
</html>