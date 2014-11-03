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
    	if (isset($_POST["submit"]) && $_POST["submit"] == "LOGIN"){
    		if (empty($_POST['username']) || empty($_POST['password'])) {
			$error = "Username or Password is invalid";
			session_write_close ();
			}
			else {
				$username=$_POST['username'];
				$password=$_POST['password'];
				// To protect MySQL injection for Security purpose
				$username = stripslashes($username);
				$password = stripslashes($password);
				$username = mysql_real_escape_string($username);
				$password = mysql_real_escape_string($password);

				$result = $connection->query("select * from customer where cid='$username' AND cpassword='$password'");
				$rows = $result->num_rows;
				if ($rows == 1){
					$row=$result->fetch_assoc();
					$_SESSION['login_user']=$username; 
					$_SESSION['type']="customer";
					$_SESSION['logged']=true;
					header("location: index.php");
				}
				else {
					$error = "* Username or Password is invalid";
				}

			}
    	}
    }
    mysqli_close($connection);
?>