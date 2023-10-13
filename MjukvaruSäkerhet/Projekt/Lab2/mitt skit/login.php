<?php 
    $page_title = "Login";
    include "includes/header.php";

    error_reporting(-1);            // Reporta alla errors
    ini_set("display_errors", 1);   // displaya alla errors  
    
    # lokal anslutning (kan ignoreras)
    // $db = new mysqli("localhost", "admin", "password", "fist2000");
    // if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }

    # kontaktar databasen eller visa felmeddelande vid error
    $db = new mysqli("$dbservername", "$dbusername", "$dbpassword", "$dbname"); 
    if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }

    # Utför en check om det som är angivet redan finns annars skapas ett konto
    if (!empty($_REQUEST["signup"])) {

        $formUname = $_POST["username"];
        $formEmail = $_POST["email"];

        $sqlQuery = "SELECT * FROM accounts WHERE Uname = '$formUname' OR Email = '$formEmail';";
        if(!$result = $db->query($sqlQuery)) { die ('Fel vid SQL-fråga [' . $db->error . ']'); }
        if(mysqli_num_rows($result)) { # om den hittar nånting så finns redan användarnamnet / emailen redan
            unset($_REQUEST["signup"]); //disablear sign up-knappen
            header("location: login.php?form=UnameTaken");
        }
        else { # förbereder SQL-kommandon å utför dom sedan med indata från formuläret
            $hashedpw = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO accounts (Uname, Pword, Email, Fullname) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $_POST["username"], $hashedpw, $_POST["email"], $_POST["fullname"]);
            // $stmt->bind_param("ssss", $_POST["username"], $_POST["password"], $_POST["email"], $_POST["fullname"]); 
            # gör samma fast hashar inte lösenordet ifall jag glömmer lösenordet
            $stmt->execute();
            unset($_REQUEST["signup"]); //disablear sign up-knappen
            header("location: login.php?form=signup");
            exit();
        }    
    }

    if (isset($_GET["form"])) { # ger meddelande om skapandet av kontot funka 
        if ($_GET["form"] == "UnameTaken") { echo "<p>Användarnnamnet eller Emailen finns redan</p>"; }
        else if ($_GET["form"] == "signup") { echo "<p>Du skapade ett konto</p>"; }
    }

    # Försöker logga in 
    if (!empty($_REQUEST["login"])) {
       
        $Uname_try = $_POST["username"];
        $Pword_try = $_POST["password"];

        # kollar antingen username eller email så man kan logga in med båda
        $sqlQuery = "SELECT * FROM accounts WHERE Uname = '$Uname_try' OR Email = '$Uname_try';";
        if(!$result = $db->query($sqlQuery)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }
        
        # om kommandot hittar svar jämföras det inmatade lösenordet med det hashade som sparats i databasen
        if ($row = $result->fetch_assoc()) { 
            $hashedpw = $row["Pword"];
            if (password_verify( $Pword_try, $hashedpw)) {
                unset($_REQUEST["login"]); //disablear sign up-knappen
                $_SESSION["Uname"] = $_POST["username"];
                $_SESSION["Pword"] = $hashedpw;
                $_SESSION["Email"] = $row["Email"];
                $_SESSION["Fullname"] = $row["Fullname"];
                $_SESSION["accountID"] = $row["accountID"];
                
                # sparar alla information i session variables så man kan se sin egna profil
                # och för att komtrollera inloggning vid andra sidor på hemsidan

                header("Location: posts.php");
                exit();
            }
            else {
                unset($_REQUEST["login"]); //disablear sign up-knappen
                header("location: login.php?error=wrongPw"); # skickar tilblaka en till samma sida med ett errormsg
                exit();
            }
        }
        else { #dehär är ifall username/emailen inte finns i databasen.
            unset($_REQUEST["login"]); //disablear sign up-knappen
            header("location: login.php?error=wrongUname"); # skickar tilblaka en till samma sida med ett errormsg
            exit();
        }
    }

    if (isset($_GET["error"])) { # De olika errormessages som finns 
        if ($_GET["error"] == "wrongUname") { echo "<p>No account created with that username</p>"; }
        else if($_GET["error"] == "wrongPw") { echo "<p>Credentials not matching</p>"; }
    }

?>
<!-- 
    # AUTHOR: Filip Stenegren, fist2000, TDATG
    # login.php
    # tillåter användaren att logga in (by default) men också att skapa konton
-->


    <!-- skapar ett form och button innanför en div för inloggning -->
    <div id="loginDiv" style="display: block;">
        <h2>Logga in</h2>
        <form action="?" method="post" id="loginForm" >
            <p> Username/Email:<br /><input type="text" name="username" required/> </p>
            <p> Password:<br/> <input type="password" name="password" required> </p>
            <p> <input type="submit" name="login" id="login" value="Log in" />  </p>
            <!-- <div id="wronginput" style="display: none;">Felaktigt användarnamn eller lösenord</div> -->
        </form>
        <button id="showSignup" onclick="showSignup()">Sign up instead</button>

    </div>
    <!-- skapar ett form  och button innanför en div för skapning av konto -->
    <div id="signupDiv" style="display: none;">
        <h2>Sign up</h2>
        <form action="?" id="signupForm" method="post">
            <p> Username:<br /><input type="text" name="username" required/> </p>
            <p> Email:<br/> <input type="text" name="email" required> </p>
            <p> Full name:<br/> <input type="text" name="fullname" required> </p>
            <p> Password:<br/> <input type="password" name="password" required> </p>
            <p> <input type="submit" name="signup" id="signup" value="Sign up" onclick="changeForm(1)"/> </p>
        </form>
        <button id="showLogin" onclick="showLogin()">Log in instead</button>

    </div>
    <p id="test">Vill du skapa konto? tryck på knappen ovan</p>


    <?php 
        include "includes/footer.php"
    ?>