<?php 
// AIM: ENTER THE VERIFICATION CODE 
    session_start(); 
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../../style.css">
    <title>Verify Code</title>
</head>
<body>
    <div class="container">
    <div class="box form-box">
        <header>Enter the Code</header>

        <?php include("../../shared/message.php"); ?> <!-- calling the message, so message can appear -->

        <form action="controller.php" method="post"> <!-- send the form to the "controller.php" for verification -->
            <div class="field">
                <label>Enter 6-digit code:</label>
                <input type="number" name="code" required>
            </div>
            <div class="field">
                <input type="submit" class="button" value="Verify">
            </div>
        </form>
    </div>
    </div>
</body>
</html>

buat yang kayak kotak2 untuk isi nomornya!
ubah semua die pake error message
ubah ke otentikasi lewat email