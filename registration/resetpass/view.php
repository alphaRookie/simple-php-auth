<?php 
    session_start(); 
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
        <header>Set New Password</header>

        <?php include("../shared/message.php"); ?> <!-- calling the message, so message can appear -->

        <form action="controller.php" method="post">
            <div class="field">
                <input type="password" name="password" required>
            </div> 
            <div class="field">
                <input type="submit" class="button" value="Change Password">
            </div>
        </form>
    </div>
    </div>
</body>
</html>
fix biar uinya muncul, cgpt ytalfa

error message yg disini gk perlu pindahin halaman, cukup munculin dibawah kotak pengisian aja