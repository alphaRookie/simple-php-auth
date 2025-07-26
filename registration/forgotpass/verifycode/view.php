<?php 
//AIM: USER ENTER THE VERIFICATION CODE 

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
    <link rel="stylesheet" href="../../style.css">
    <title>Verify Code</title>
</head>
<body>
    <div class="container">
    <div class="box form-box">
        <header>Enter the Code</header>

        <?php require_once __DIR__ . '/../../shared/message.php'; ?> <!-- calling the message, so message can appear here (imagine sticky note, we can put it anywhere in the book and move it) -->

        <form action="controller.php" method="post"> <!-- send the form to the "controller.php" for verification -->
            <div class="field">
                <label>Enter 6-digit code:</label> <!-- pattern="\d{6}" = forcing 6-digit, maxlength="6" = block if typed more than 6 -->
                <input 
                    type="text" 
                    name="code" 
                    id="codeInput" 
                    inputmode="numeric"  
                    pattern="\d{6}" 
                    maxlength="6" 
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);" 
                    required
                ><!-- putting "text" and give more validation, instead of just "number" -->
            </div>

            <!-- CSRF token -->
            <input type="hidden" name="csrf_token" value="<?php echo $safe_token ?>">

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
trim kodenya

<!-- javascript here
oninput=	                Runs this JS every time the user types/pastes.
this.value	                The current text in the input box.
.replace(/[^0-9]/g, '')	    Removes ALL non-numbers ([^0-9] = "not digits").
.slice(0, 6)	            Cuts off after 6 digits (extra safety beyond maxlength).
-->