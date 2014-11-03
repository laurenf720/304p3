<?php
	session_start();
	$error='';

	$connection = new mysqli("127.0.0.1", "root", "photon", "AMS");

    // Check that the connection was successful, otherwise exit
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	if (isset($_POST["submit"]) && $_POST["submit"] == "REGISTER") {
    		if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password2']) || empty($_POST['cname']) || empty($_POST['phone']) || empty($_POST['address'])) {
				$error = "Please fill in all fields";
				session_write_close ();
			}
			elseif ($_POST['password'] != $_POST['password2']) {
			$error = "The passwords do not match";
			session_write_close ();
			}
			
			else {
				$username=$_POST['username'];
				$password=$_POST['password'];
				$cname=$_POST['cname'];
				$phone=$_POST['phone'];
				$address=$_POST['address'];
				// To protect MySQL injection for Security purpose
				$username = stripslashes($username);
				$password = stripslashes($password);
				$cname = stripslashes($cname);
				$phone = stripslashes($phone);
				$address = stripslashes($address);
				$username = mysql_real_escape_string($username);
				$password = mysql_real_escape_string($password);
				$cname = mysql_real_escape_string($cname);
				$phone = mysql_real_escape_string($phone);
				$address = mysql_real_escape_string($address);
			}
    	}
    }
    mysqli_close($connection);
?>