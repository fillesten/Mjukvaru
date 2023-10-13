<!DOCTYPE html>
<html>
<?php
session_start();
if(!empty($_REQUEST["login"])){
    $_SESSION["email"] = $_POST["email"];
    $_SESSION["password"] = $_POST["password"];
	
	if ($_SESSION["password"] == "password") {
        //this does not work in localhost but would work in a published server
        $to = $_SESSION["email"]; 
        $subject = "Hello World"; 
        $message = "Click here to verify that you want to login ;) \n https://studenter.miun.se/~fist2000/Mjukvaru/Projekt/attacked.php";
        $headers = "From: fist2000@student.miun.se\r\n";
        mail($to, $subject, $message, $headers);
        header("Location: success.php");
	} else { 
        echo "nånting vart tokfel med password"; 
    }
}
?>
<head>
	<title>Login Page</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="javascript.js"></script>
</head>
<body>
	<div class="login-box">
		<h2>Login</h2>
		<form action="" method="post" name="form" id="form">
			<label for="email">Email:</label>
			<input type="text" id="email" name="email" required placeholder="Email@placeholder.com">
			
			<label for="password">Password:</label>
			<input type="password" id="password" name="password" required>
			
		    
			<input type="submit" name="login" value="Sign In"/>	

		</form>
	</div>
</body>
</html>
