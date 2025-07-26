<?php
//AIM: USER ENTER THEIR EMAIL
    
    require_once __DIR__ . '/../shared/security.php';
    force_https();

    session_start();

    // Generates a secure CSRF token if one doesn't exist yet
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    require_once __DIR__ . '/../shared/escape_session.php';
    //and no need to call DB here
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
        <header>Find your Email</header>

        <?php require_once __DIR__ . '/../shared/message.php'; ?> <!-- calling the message, so message can appear here (imagine sticky note, we can put it anywhere in the book and move it) -->

        <form action="send_code.php" method="post"> <!-- This form submits the email, so we need logic to check email existence -->
            <div class="field"><!-- field is the decoration inside the box -->
                <label>Email:</label>
                <input type="email" name="email" placeholder="Enter email" required>
            </div>

            <!-- CSRF token -->
            <input type="hidden" name="csrf_token" value="<?php echo $safe_token ?>">

            <div class="field">
                <input type="submit" class="button" value="Submit">
            </div>
        </form>
    </div>
    </div>
</body>
</html>
