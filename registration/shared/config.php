<?php
//Every PHP file that needs to talk to your database (register, login, etc.) needs to include config.php first. Otherwise, you’re trying to talk to MySQL without even calling it.
$con = mysqli_connect("localhost", "root", "", "test") or die("couldn't connect");

// "$con" is like connector do database(the one who open the door)
// to call the guy who can open that door we need to call "config.php"

//move it to shared folder later!!!
?>
