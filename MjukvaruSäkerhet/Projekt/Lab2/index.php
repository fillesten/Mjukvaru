<?php
session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
//connectar till databasen 
$conn = mysqli_connect("localhost", "test", "frest", "test") or die("Error connecting to database." . mysqli_error($conn));
if (isset($_GET["session"])) { 
	if ($_GET["session"] == "Wrong_Credentials") { 
		$sql_insert = "INSERT INTO calls(Timestamp, Ip, Calls) VALUES (?, ?, ?)";
	
		$time = date('Y-m-d H:i:s');
		$Ip_address = getenv("REMOTE_ADDR");
		$Calls = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$stmt_insert = mysqli_prepare($conn, $sql_insert);

		mysqli_stmt_bind_param($stmt_insert, "sss", $time, $Ip_address, $Calls);
		mysqli_stmt_execute($stmt_insert); //create a call request for failed login
	}
	elseif ($_GET["session"] == "SQL-inj_try") {
		$sql_insert = "INSERT INTO calls(Timestamp, Ip, Calls) VALUES (?, ?, ?)";
		$time = date('Y-m-d H:i:s');
		$Ip_address = getenv("REMOTE_ADDR");
		$Calls = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$stmt_insert = mysqli_prepare($conn, $sql_insert);

		mysqli_stmt_bind_param($stmt_insert, "sss", $time, $Ip_address, $Calls);
		mysqli_stmt_execute($stmt_insert); //create a call request for when a user tried to do a SQL-injection
	}
}


if(!empty($_REQUEST["login"])){
	// Connect to the mysql database and check for user
	$sql_insert = "INSERT INTO calls(Timestamp, Ip, Calls) VALUES (?, ?, ?)";

	$time = date('Y-m-d H:i:s'); //sparar tidsstämpel
	$Ip_address = getenv("REMOTE_ADDR");//sparar IP-adress
	$Calls = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$stmt_insert = mysqli_prepare($conn, $sql_insert);
	
	if(strlen($_REQUEST["user"]) && strlen($_REQUEST["pass"])){ // Check if username and password not empty
		
		$user = mysqli_real_escape_string($conn, $_REQUEST["user"]);// tar bort konstiga tecken
		$pass = mysqli_real_escape_string($conn, $_REQUEST["pass"]);
		
		$sql = "SELECT * FROM users WHERE Username= ? AND Password = ?"; //förbereder en parametriserad query
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, "ss", $user, $pass); //binder parametrarna

		if (mysqli_stmt_execute($stmt)) { // LOGGED IN WITH test -> frest
			$result = mysqli_stmt_get_result($stmt); //alla rows som matchas sparas i $result
			if(mysqli_num_rows($result) == 1){ // If ONE username exists with the correct password take the user further		
				$_SESSION["username"] = $user; // Create session variable "username" if authenticated user (eg. exist in db)
				
				$Calls = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?session=Logged_In"; //Hämtar hela URL:en och lägger till logged in till session
				mysqli_stmt_bind_param($stmt_insert, "sss", $time, $Ip_address, $Calls); // binder parametrarna från insert queryn
				mysqli_stmt_execute($stmt_insert); //create a call request for a successful login	
				header("Location: inside.php"); //skickar vidare användaren till inside				
				mysqli_close($conn); 
				exit();
			} // end of database user checkup
		}
			// Tecken 
			if (!preg_match("#^[a-zA-Z0-9åäöÅÄÖ]+$#", $_REQUEST["pass"])) { 	
				session_destroy();
				header("location: index.php?session=SQL-inj_try"); 
				exit(); 
			}

			session_destroy();
			header("location: index.php?session=Wrong_Credentials"); 
			exit();
	} 
		

} // end of empty string element check  
	 // end of "login" button pressed

?>	
	<!DOCTYPE html>
	<head>
	<title>SQL Injection Test</title>
	<meta charset='utf-8' />
	<style>
	</style>	
	</head>
	<body>
	<form method="post" action="">
	<p>
		Användarnamn <input type="text" name="user"/> <br/>
		Lösenord <input type="text" name="pass"/>
	</p>
	<p>
		<input type="submit" name="login" value="Sign In"/>	
	</p>
	</body>
	</html>

