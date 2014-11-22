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
    		if (empty(trim($_POST['username'])) || empty(trim($_POST['password'])) || empty(trim($_POST['password2'])) || empty(trim($_POST['cname'])) || empty(trim($_POST['phone'])) || empty(trim($_POST['address']))) {
				$error = "Please fill in all fields";
				session_write_close ();
			}
			elseif ($_POST['password'] != $_POST['password2']) {
			$error = "The passwords do not match";
			session_write_close ();
			}
			//check if the username is taken			
			else {
				$statement1=$connection->prepare("insert into users values (?,?,?)");
				$statement2=$connection->prepare("insert into customer values(?, ?, ?)");
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
				
				$result = $connection->query("select uid from users where uid='$username'");
				$rows = $result->num_rows;
				//check if the username has been taken
				$statement1->bind_param("sss", $username, $password, $cname);
				$statement2->bind_param("sss",$username,$address,$phone);	
				if($rows != 0){
					$error = "The username $username has been taken, please try another name";
					session_write_close();
				}
				//register the customer
				
				else {
					$statement1->execute();
					$statement2->execute();
					header("location: custRegisterThankYou.php");
				}
				$statement1->close();
				$statement2->close();
			}
    	}
    }
    mysqli_close($connection);
?>