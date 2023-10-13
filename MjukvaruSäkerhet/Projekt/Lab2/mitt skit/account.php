<?php 
    $page_title = "Account";
    include "includes/header.php";
    error_reporting(-1);            // Reporta alla errors
    ini_set("display_errors", 1);

    // $db = new mysqli("localhost", "admin", "password", "fist2000");
    // if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }
    #lokal anslutnin
        
    $db = new mysqli("$dbservername", "$dbusername", "$dbpassword", "$dbname"); 
    if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }

    $accUname = $_GET["accUname"];
    $sqlQuery = "SELECT * FROM accounts WHERE Uname = '$accUname';";
    if(!$result = $db->query($sqlQuery)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }
            
        

?>
<!-- 
    # AUTHOR: Filip Stenegren, fist2000, TDATG
    # account.php
    # Kollar på ett specifikt konto och alla inlägg som de gjort. Gick in på sitt egna redirectar till profile.php
-->

<h3 class="profileP">Du kollar foruminläggen som skrevs av <?php echo $accUname ?> </h3>
<?php 

    if (isset($_GET["accUname"])) {  #blev jag redirectad eller är account.php tom
        if (isset($_SESSION["Uname"])) { # är man inloggad så kolla om det är sin egen profil --> om egen profil redirecta till profile då slipper kod i detta dokument för samma sak skulle göras
            if ($_SESSION["Uname"] == $_GET["accUname"]) { header("Location: profile.php");  }
            
            else { #logged in but not right acc -> shows all messages by specifik accounts
                $accUname = $_GET["accUname"];
                $sqlQuery = "SELECT accountID FROM accounts WHERE Uname = '$accUname';";
                if(!$result = $db->query($sqlQuery)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }
                
                $row = $result->fetch_assoc();
                $accID = $row["accountID"];
                $sqlQuery = "SELECT * FROM posts WHERE accountID = $accID;";
                if(!$result = $db->query($sqlQuery)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }

                while($row = $result->fetch_assoc()){
                    echo "<p class='profileP'>". " Message: <br/>" . $row["meddelande"]. "<br/>".
                    "PostDate: " . $row["PostDate"] . "</p><hr/>";
                }    
            }
        }
        else { #if im not logged in -> same as above
            $accUname = $_GET["accUname"];
            $sqlQuery = "SELECT accountID FROM accounts WHERE Uname = '$accUname';";
            if(!$result = $db->query($sqlQuery)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }
            
            $row = $result->fetch_assoc();
            $accID = $row["accountID"];
            $sqlQuery = "SELECT * FROM posts WHERE accountID = $accID;";
            if(!$result = $db->query($sqlQuery)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }

            while($row = $result->fetch_assoc()){
                echo "<p class='profileP'>". " Message: <br/>" . $row["meddelande"]. "<br/>".
                "PostDate: " . $row["PostDate"] . "</p><hr/>";
            }    
        }
        
    }
    else { header("Location: posts.php"); } #if you didnt get redirected so there wouldnt be a chosen account to look at

    
    include "includes/footer.php";
        
?>