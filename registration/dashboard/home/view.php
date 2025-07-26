<!-- No need to start the session, bcoz it already active from the controller -->
<?php
echo "<pre>Debug Session: ";
print_r($_SESSION);
echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <title>Home</title>
</head>
<!--top part-->
<body>
    <header>
    <div class="nav">
        <div class="logo"> <p>Logo</p> </div>    
        <div class="right-links">

            <!-- Change Profile button -->
            <a href="../update/update_info/view.php?Id=<?php echo urlencode($safe_id) ?>" class="button">Change Info</a>

            <!-- Change Email button -->
            <a href="../update/update_email/view.php?Id=<?php echo urlencode($safe_id) ?>" class="button">Change Email</a>

            <!-- Change Password button -->
            <a href="../update/update_pass/view.php?Id=<?php echo urlencode($safe_id) ?>" class="button">Change Pass</a>

            <!-- Logout button with hidden CSRF token -->      
            <form method="POST" action="../logout.php">
                <input type="hidden" name="csrf_token" value="<?php echo $safe_token ?>"> <!-- 📌NEVER PUT ; INSIDE EMBEDDED VALUE(value="")📌 -->
                <button type="submit" class="button">Logout</button>
            </form>
  
        </div>
    </div>
    </header>
<!--body part-->
    <main>
    <div class="main-box">
        <div class="top">

            <?php require_once __DIR__ . '/../../shared/message.php'; ?>

            <div class="box">
                <p>Yo <b><?php echo $safe_username ?></b>, whts up?</p>
            </div>
            <div class="box">
                <p>Your email is <b><?php echo $safe_email ?></b></p>
            </div>
        </div> 

        <div class="bottom">
            <div class="box">
               <p>And you are <b><?php echo $safe_age ?></b> y.o</p>
            </div> 
        </div>
    </div>
    </main>

<!--footer part-->
<div class="nav">
    <footer>
        <p>&copy; 2025 My Portfolio. All rights reserved.</p>
        <p><a href="#privacy">Privacy Policy</a> | <a href="#terms">Terms of Service</a></p>
    </footer>
</div>
</body>
</html>