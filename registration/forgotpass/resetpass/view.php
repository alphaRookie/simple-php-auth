<?php 
//AIM: USER TYPE THE NEW PASSWORD
    require_once __DIR__ . '/../../shared/security.php';
    force_https();

    session_start(); 

    // Generates a secure CSRF token if one doesn't exist yet
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    require_once __DIR__ . '/../../shared/escape_session.php';
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../../style.css"> <!-- one stair up climbing we put "../" -->
    <title>Reset your Password</title>
</head>  
<body>
    <div class="container"><!-- manage that one box itself (positioning, etc) -->
    <div class="box form-box"><!-- set how inside the box looks like -->
        <header>Set New Password</header>

        <?php require_once __DIR__ . '/../../shared/message.php'; ?> <!-- calling the message, so message can appear here (imagine sticky note, we can put it anywhere in the book and move it) -->

        <form action="controller.php" method="post">
            <div class="field">
                <label>New Password:</label>
                <input type="password" name="password" placeholder="Set New Password" required>
            </div> 

            <div class="field">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" autocomplete="off" placeholder="Confirm New Password" required>
            </div>

            <!-- CSRF token -->
            <input type="hidden" name="csrf_token" value="<?php echo $safe_token ?>">

            <div class="field">
                <input type="submit" class="button" value="Change Password">
            </div>
        </form>
    </div>
    </div>
</body>
</html>