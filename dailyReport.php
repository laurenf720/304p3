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
			<h1 style="text-align:center">Create a Daily Report</h1>
			<p></p>
		</div>
		<?php
			if (!isset($_SESSION['logged'])){
				header("location: userloginpage.php");
			}
		?>

		<form id="dailyreportform" name="dailyreportform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
			<table align="center" class="login" style="background-color:white">
			    <tr>
			       	<td><label>Pick a day: </label></td>
					<td><input id="dailyreportday" type="date" size=30 name="dailyreportday" placeholder="YYYY-MM-DD"></td>
			    </tr>
				<tr>
			    	<td colspan=2 style="text-align:center"><input type="submit" name="submit" class="cartbutton" border=0 value="Report"></td>
			    </tr>
			</table>
		</form>
		<div align="center">
			<?php
				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST["submit"]) and $_POST["submit"] == "Report"){
						$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");
						if (mysqli_connect_errno()) {
							printf("Connect failed: %s\n", mysqli_connect_error());
							exit();
						}
						$day = $_POST['dailyreportday'];
						$result=$connection->query("SELECT * FROM purchase where pdate='$day'");
						if($result->num_rows == 0) {
							echo "<span class=\"error\">* Sorry! No purchases were made on that day</span>";
						}
						else {
							while ($row=$result->fetch_assoc()){
								echo $row['delivereddate'];
							}
						}

						mysqli_close($connection);
					}
				}
			?>
		</div>
		
		
	</body>
	<script src="ams.js"></script>
</html>