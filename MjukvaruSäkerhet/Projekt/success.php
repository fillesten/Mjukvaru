<?php
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["password"])) { header("Location: index.php"); exit(); }

$email = $_SESSION["email"];
$password = $_SESSION["password"];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Success Page</title>
</head>
<body>
	<h1>Please confirm that you want to login, <?php echo $email; ?>!</h1>
	<h2>A LINK HAS been sent to your email, follow the instructions there</h2>
	<p>Your password is: <?php echo $password; ?></p>
</body>
</html>
