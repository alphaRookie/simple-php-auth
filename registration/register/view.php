<?php 
    require_once __DIR__ . '/../shared/security.php';//include the security sessions
    force_https();
    
    session_start();
    
    // Generates a secure CSRF token if one doesn't exist yet
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    require_once __DIR__ . '/../shared/escape_session.php';
    
echo "<pre>Debug Session: ";
print_r($_SESSION);
echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self';"> <!-- CSP Header: like a website bodyguard, only let trusted code run -->
    <link rel="stylesheet" href="../style.css">
    <title>Registration Form</title> 
</head>
<body>
    <div class="container"><!-- manage that one box itself (positioning, etc) -->
    <div class="box form-box"><!-- set how inside the box looks like -->
        <header>Sign up</header>
        <form action="controller.php" method="post"> <!-- POST back to itself -->

            <?php require_once __DIR__ . '/../shared/message.php'; ?> <!-- calling the message, so message can appear here (imagine sticky note, we can put it anywhere in the book and move it) -->

            <div class="field">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" autocomplete="off" placeholder="Enter Username" value="<?php echo $safe_temp_username ?>" required> <!-- User dont have to type their username again in some error -->
            </div>   

            <div class="field">
                <label for="birthdate">Birthdate</label>
                <input type="date" name="birthdate" id="birthdate" value="<?php echo $safe_temp_birthdate ?>" required> <!-- User dont have to type their birthdate again in some error -->
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" autocomplete="off" placeholder="Enter Email" value="<?php echo $safe_temp_email ?>" required> <!-- User dont have to type their email again in some error -->
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter Password" required>
            </div>
 
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo $safe_token ?>">

            <div class="field">
                <input type="submit" class="button" name="submit" value="Submit">
            </div>

            <div class="link">
                Already a member? <a href="../login/view.php">Login here!</a> 
            </div>
        </form>
    </div>
    </div>
</body>
</html>
