<?php 
    require_once __DIR__ . '/../../../shared/security.php';
    force_https();

    session_start(); 

    // Generates a secure CSRF token if one doesn't exist yet
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    require_once __DIR__ . '/../../../shared/escape_session.php';

echo "<pre>Debug Session: ";
print_r($_SESSION);
echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../style.css">
    <title>Change profile</title>
</head>
<body>
    <header>
    <div class="nav">
        <div class="logo">
           <p><a href="../../home/controller.php">Logo</a></p>
        </div>
    </div>
    </header>

    <main>
        <div class="container">
        <div class="box form-box">

        <header>
            <p>Change Info</p>
            <?php echo "For user with Username: " . $safe_username; ?>
        </header>

            <?php require_once __DIR__ . '/../../../shared/message.php'; ?> <!-- calling the message, so message can appear here (imagine sticky note, we can put it anywhere in the book and move it) -->

            <form action="controller.php" method="post">
                <div class="field">
                    <label>Username:</label>
                    <input type="text" name="username" id="username" autocomplete="off" placeholder="New Username"  value="<?php echo $safe_username ?>" required >
                </div>

                <div class="field">
                    <label>Birthdate:</label>
                    <input type="date" name="birthdate" id="birthdate" autocomplete="off"  value="<?php echo $safe_birthdate ?>" required >
                </div>

                <!-- CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo $safe_token ?>">

                <div class="field">
                    <button type="submit" name="submit" class="button" value="Update Info">Update Info</button>
                </div>
            </form>
        </div><!--box form-box-->
        </div><!--container-->
    </main>
</body>
</html>