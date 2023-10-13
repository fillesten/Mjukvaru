<?php 
    $page_title = "Posts";
    include "includes/header.php";
    error_reporting(-1);            // Reporta alla errors
    ini_set("display_errors", 1);

    #lokal anslutning
    // $db = new mysqli("localhost", "admin", "password", "fist2000");
    // if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }
    
    $db = new mysqli("$dbservername", "$dbusername", "$dbpassword", "$dbname"); 
    if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }
    //visar error om man inte kan connecta till databasen

    # posts.php shows every single post in the database regardless of creator
    
    if (!empty($_REQUEST["addPost"])){ # förbereder ett SQL-kommando
        if (!empty($_POST["message"])) {
            $datetime = new DateTime();
            $date = $datetime->format("Y-m-d H:i:s");
            $stmt = $db->prepare("INSERT INTO posts (meddelande, PostDate, accountID) VALUE (?, ?, ?)");
            $stmt->bind_param("sss", $_POST["message"], $date,  $_SESSION["accountID"]);
            $stmt->execute();         
        }
        unset($_REQUEST["addPost"]); //disablear addPost-knappen
        header("location: posts.php"); # uppdaterar sidan så nya meddelandet syns
        exit();
    }
?>
<!-- 
    # AUTHOR: Filip Stenegren, fist2000, TDATG
    # posts.php
    # Denna sida printar ut exakt alla meddelanden och vilket konto som skrev det, om inloggad kan man också skriva ett meddelande
 -->

    <h3 class="profileP">Alla Foruminlägg!</h3>
    
    
    <?php 
    if (isset($_SESSION["Uname"])) {
        echo '<form action="?" method="POST">
            <p class="profileP"> Lägg till ett Meddelande:<br/> <textarea cols="40" rows="2" name="message" ></textarea> </p>
            <p class="profileP"> <input type="submit" name="addPost" value="LÄGG UT!" class="btn" /> </p>
        </form> ';

    }

    $sqlQuery = "SELECT * FROM posts;";
    if(!$result = $db->query($sqlQuery)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }

    while($row = $result->fetch_assoc()){

        $accId = $row["accountID"];        
        $sqlQueryUname = "SELECT Uname FROM accounts WHERE accountID = '$accId';";
        if(!$UnameResult = $db->query($sqlQueryUname)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }
        $Uname = $UnameResult->fetch_assoc();
        
        echo "<p class='profileP'>". " Message: <br/>" . $row["meddelande"]. "<br/>".
        "PostDate: " . $row["PostDate"] . 
        "<br/> Author: ". "<a href='account.php?accUname=". $Uname["Uname"] . "'>" . $Uname["Uname"] . "</a></p><hr/>";
    
    }  

?>
    <a href='qwre'></a>

<?php 
    include "includes/footer.php";
        
?>