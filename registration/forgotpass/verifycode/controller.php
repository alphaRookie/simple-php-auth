<?php
//AIM: CHECK IF THE CODE ENTERED BY USER IS VALID

    require_once __DIR__ . '/../../shared/security.php';
    force_https();
    secure_session();

    session_start(); 

    // The controller can only be accessed if user submit the form (via POST).
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: view.php");
        exit;
    }

    // Check if CSRF token is set and matched the session token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Invalid CSRF token.";
        header("Location: view.php");
        exit;
    }
    
    // ❗️Check if the session for reset has started and is still valid (session timeoutfor password reset)❗️
    if (!isset($_SESSION['reset_started']) || (time() - $_SESSION['reset_started']) > 900) {// resets expired after 15 minutes
        $_SESSION['error'] = "Session expired. Start again.";
        header("Location: ../find_email.php");
        exit;
    }

    require_once __DIR__ . '/../../shared/config.php';
    require_once __DIR__ . '/../../model/users.php';// call model

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $code = trim($_POST['code']);//Get the code from form, "trim" to automatically remove extra spaces("  123456")

        if (strlen($code) != 6 || !ctype_digit($code)) {//always strict validation first
            $_SESSION['error'] = "Enter the 6-DIGIT code";//call the session error, then show message whatever inside ""
            header("Location: view.php");
            exit;
        }
 
        // NEXT WE CAN CHECK(by model) IF CODE EXIST AND NOT EXPIRED, THEN REDIRECT TO "resetpass/view.php"
        $now = date("Y-m-d H:i:s");//get current time in PHP
        
        $code_legit = checkResetCode($con, $code, $now);

        if (!$code_legit) {//if not matched the code sent to email / the code has expired, send back to view.php with error message
            $_SESSION['error'] = "Invalid or Code expired.";//call the session error, then show message whatever inside ""
            header("Location: view.php");
            exit;
        }

        // OTHERWISE SAVE EMAIL TO SESSION AND REDIRECT
        $_SESSION['reset_email'] = $code_legit['Email'];// like saying "Save the email to the session, so I can use it later when resetting the password."
        $email = $code_legit['Email'];

        // ❗️Clear the reset code right after a code is successfully verified❗️
        clearResetCode($con, $email);

        unset($_SESSION['csrf_token']);//unset the token

        header("Location: ../resetpass/view.php");// Go to password reset page
        exit;
    }

/* 
    ### Why set $_SESSION['reset_email'] here? and why not earlier in prev file?
         -> You should only let someone reset the password after they prove they’re the real owner of the email (by entering the correct code/token).

    Last part xplanation:
        You can’t reset “someone’s” password if you don’t know who they are.
        That’s why we fetch the user's data from the database result.
        After we fetch, we can know user's email. And now save it to the session
        Later we can use it to update the user's password in the database
*/  
?>
