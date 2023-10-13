<?php 
    $page_title = "Profile";
    include "includes/header.php";

    # kontrollerar om man är inloggad annars skickas man till inloggnignssidan 
    # för man har manövrerat sig till denna sida direkt från URL:en
    if (!isset($_SESSION["Uname"])) { 
        header("Location: login.php");
        exit();
    }

    error_reporting(-1);            // Reporta alla errors
    ini_set("display_errors", 1);   // displaya alla errors

    // $db = new mysqli("localhost", "admin", "password", "fist2000");
    // if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }

    
    $db = new mysqli("$dbservername", "$dbusername", "$dbpassword", "$dbname"); 
    if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }

    # skapar nytt meddelande, kräver endast att textrutan är ifylld och att man är inloggad
    if (!empty($_REQUEST["addPost"])){
        if (!empty($_POST["message"])) {
            
            $datetime = new DateTime();
            $date = $datetime->format("Y-m-d H:i:s");
            
            $stmt = $db->prepare("INSERT INTO posts (meddelande, PostDate, accountID) VALUE (?, ?, ?)");
            $stmt->bind_param("sss", $_POST["message"], $date,  $_SESSION["accountID"]);
            $stmt->execute();         
            
        }
        unset($_REQUEST["addPost"]); //disablear addPost-knappen
        header("location: profile.php");
        exit();
    }

    # får det specifika postID numret från GET via länken som man trycker  
    if(isset($_REQUEST["delPost"])){
        
        $stmt = $db->prepare("DELETE FROM posts WHERE postID = ?");
        $stmt->bind_param("s", $_REQUEST["delPost"]);
        $stmt->execute();

        unset($_REQUEST["delPost"]); // Disable button press
        header("Location: profile.php");
        exit();
    }


?>
<!-- 
    # AUTHOR: Filip Stenegren, fist2000, TDATG
    # profile.php
    # Visar sina egna kontouppgifter och alla inlägg som kontot gjort
-->

    <h1 class="profileP">Din Profil</h1>
    <?php
        # Skriver ut personuppgifter med sessionsvariablerna
        echo "<p class='profileP'>"."Info om ditt account ". $_SESSION["Fullname"] . "<br/>" .
        "Email: ". $_SESSION["Email"]. "<br/>".
        "Användarnamn: ". $_SESSION["Uname"]."<br/>". 
        "Ditt account har ID:et ". $_SESSION["accountID"]. "<br/>".
        "ditt lösenord fick hashen " .  $_SESSION["Pword"] . "</p>";
    ?>
    <form action="?" method="POST">
        <p class="profileP"> Lägg till ett Meddelande:<br/> <textarea cols="40" rows="2" name="message" ></textarea> </p>
        <p class="profileP"> <input type="submit" name="addPost" value="LÄGG UT!" class="btn" /> </p>
    </form>
    <h3 class="profileP">Foruminlägg</h3><br/>
    <?php 
        # skriver ut alla egna inlägg
        echo "<p class='profileP'>Dina meddelanden: <br/></p>";
        $accID = $_SESSION["accountID"];
        $sqlQuery = "SELECT * FROM posts WHERE accountID = $accID;";
        if(!$result = $db->query($sqlQuery)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }
        
        # skriver ut alla meddelanden men med en länk så GET kan fånga å använda postID:t för borttagning
        while($row = $result->fetch_assoc()){
            echo "<p class='profileP'>". " Message: <br/>" . $row["meddelande"]. "<br/>".
            "PostDate: " . $row["PostDate"] . 
            " <a href='profile.php?delPost=".$row["postID"]. "'> LÄGG NER </a></p><hr/>";
        }  
    ?>      

<?php 

    include "includes/footer.php";
        
?>