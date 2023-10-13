<?php
    
    #Here you can run all sorts of malicious code 
    #the point is to make the email have the same visual layout as the webiste that you want the user to confirm from 
    #alternatively you can just make the link look really similar so a link from say instagram.com?verify=true
    #instead looks like instagram.com?verifyy=true
    echo "<script>alert('You have been hacked')</script>;";

    echo "<script>window.location.href = 'https://studenter.miun.se/~fist2000/Mjukvaru/Projekt/hacked.php';</script>";
    
    exit();
?>