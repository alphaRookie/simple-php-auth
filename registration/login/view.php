<?php 
    require_once __DIR__ . '/../shared/security.php';//include the security sessions
    force_https();

    session_start();

    // Generates a secure CSRF token if one doesn't exist yet
    // 📌 previously i need to submit twice so the token can match because i call escape session before creating the token📌
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
    <link rel="stylesheet" href="../style.css">
    <title>Login form</title>
</head>
<body>
    <div class="container">
    <div class="box form-box">
        <header>Login</header>
        <form action="controller.php" method="post">

            <?php require_once __DIR__ . '/../shared/message.php'; ?> <!-- calling the message, so message can appear here (imagine sticky note, we can put it anywhere in the book and move it) -->

            <div class="field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter Email" value="<?php echo $safe_temp_email ?>" required> <!-- 📌User dont have to type their email again if the password is wrong 📌 -->

            <div class="field">
                <label for="password">Password </label>
                <input type="password" name="password" id="password" placeholder="Enter Password" required>
            </div>

            <!-- Embedd CSRF Token in form 
                 Why? This ensures the token is submitted with the form data but isn't visible to the user-->
            <input type="hidden" name="csrf_token" value="<?php echo $safe_token ?>">

            <div class="field">
                <input type="submit" class="button" name="submit" value="Submit">
            </div>

            <div class="link">
                <a href="../forgotpass/find_email.php">Forgot Password?</a>
            </div>

            <div class="link">
                Dont have account? <a href="../register/view.php">Sign up here!</a>
            </div>
        </form>
    </div><!--this part is still part of designed box f-->
    </div>
</body>
</html>

<!--
Look at the temp session i called,
      ?? → "Use this if the value is exists, otherwise use whatever comes after(this case is '')."
      '' → An empty string (just nothing, like ""). We use it to make sure the PHP dont throw an error if the session variable doesn't exist.
-->