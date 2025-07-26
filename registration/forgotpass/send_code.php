<?php
//AIM: GENERATE AND SEND 6-DIGIT CODE TO USER'S EMAIL IF ITS EXIST, OTHERWISE SHOW ERROR
    
    require_once __DIR__ . '/../shared/security.php';
    force_https();
    secure_session();

    session_start();

    // The controller can only be accessed if user submit the form (via POST).
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: find_email.php");
        exit;
    }

    // Check if CSRF token is set and matched the session token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "Invalid CSRF token.";
        header("Location: view.php");
        exit;
    }

    require_once __DIR__ . '/../shared/config.php';
    require_once __DIR__ . '/../model/users.php';// call model

    if ($_SERVER["REQUEST_METHOD"] == "POST") {//check if method is post
        $email = trim($_POST['email']);//get the email from form submitted(via find_email.php)

        //call model to get all data by email
        $user = getAllbyEmail($con, $email);

        // If the email isnt exist...
        if (!$user) {
            $_SESSION['error'] = "Email not found.";//call the session error, then show message whatever inside ""
            header("Location: find_email.php");// redirect to view.php to show error message
            exit;
        }
        
        // If the email is exists, otherwise...
        // no need to use else here, using unnecessary else too much is like "the police who still checks us even when all is good."

        // Generate a 6-digit random code, padding with zeros if necessary
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT); // Example:"093421"
        $expires = date("Y-m-d H:i:s", time() + 300); // expires in 5 minutes

        //call model to save the code and expiration time in the DB
        updateResetCode($con, $email, $code, $expires);

        // ❗️Add Session Timeout for Reset Password (If you let that session stay forever, someone else could hijack it.)❗️
        $_SESSION['reset_started'] = time();
        
        unset($_SESSION['csrf_token']);//unset the token

        // Simulated "email", // Store data in session to show on success page
        $_SESSION['success'] = "A code was sent to your email: $code";//call the session success, then show message whatever inside ""
        $_SESSION['sent_email'] = $email;//ubah ke otentikasi lewat email

        header("Location: verifycode/view.php");//redirect to verifycode/view.php to enter verification code
        exit;

//change to receive by email
}
?>
