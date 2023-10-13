<?php 
    $page_title = "Konton";

    include "includes/header.php";
    error_reporting(-1);            // Reporta alla errors
    ini_set("display_errors", 1);

    // $db = new mysqli("localhost", "admin", "password", "fist2000");
    // if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }
    # lokal anslutning
    
    $db = new mysqli("$dbservername", "$dbusername", "$dbpassword", "$dbname"); 
    if ($db->connect_errno > 0 ) { die ('Fel vid anslutning [' . $db->connect_error . ']'); }

    
?>
<!-- 
    # AUTHOR: Filip Stenegren, fist2000, TDATG
    # konton.php
    # Visar alla resgistrerade konton på databasen
-->
<h3 class="profileP">Alla registrerade konton</h3>

<?php
    $sqlQuery = "SELECT * FROM accounts;";
    if(!$result = $db->query($sqlQuery)) { die('Fel vid SQL-fråga [' . $db->error . ']'); }

    while($row = $result->fetch_assoc()){
        echo "<p class='profileP'>". " Username: " ."<a href='account.php?accUname=". $row["Uname"] ."'>".$row["Uname"] . "</a><br/>".
        "Namn: " . $row["Fullname"] . "<br/>"
        ." Email " .$row["Email"]. " <a href='Mailto:" . $row["Email"] . "'>".  "Maila" . "</a></p><hr/>";
        
    }
?>

<?php 

    include "includes/footer.php";
        
?>