<?php
//Aim: User enters email
    session_start();
    //no need to put "inlcude()" here, bcoz no need to connect to DB (only shows form)
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style.css"> <!-- one stair up climbing we put "../" -->
    <title>Forgot Password</title>
</head>
<body>
    <div class="container"><!-- manage that one box itself (positioning, etc) -->
    <div class="box form-box"><!-- set how inside the box looks like -->
        <header>Forgot Password</header>
        <form action="sendingcode/controller.php" method="post"> <!-- This form submits the email, so we need logic to check email existence -->
            <div class="field"><!-- field is the decoration inside the box -->
                <label>Email:</label>
                <input type="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="field">
                <input type="submit" class="button" value="Send Code">
            </div>
        </form>
    </div>
    </div>
</body>
</html>
