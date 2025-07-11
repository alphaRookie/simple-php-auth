<?php
//AIM : CHECK IFTHE CODE ENTERED BY USER IS VALID
session_start(); 
include("../../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = trim($_POST['code']);//Get the code from form, "trim" to automatically remove extra spaces("  123456")

    // CHECK IF CODE EXIST AND NOT EXPIRED, THEN REDIRECT TO reset_pass.php
    $now = date("Y-m-d H:i:s");//get current time in PHP
    $stmt = $con->prepare("SELECT * FROM users WHERE reset_code = ? AND reset_expires > ?");//"Find a user whose "reset_code" matches the code given, AND whose code hasn't expired yet."
    // Bind the email parameter to prevent SQL injection
    $stmt->bind_param("ss", $code, $now);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {//if not matched the code sent to email / the code has expired, send back to view.php with error message
        $_SESSION['error'] = "Invalid or Code expired.";//call the session error, then show message whatever inside ""
        header("Location: view.php");
        exit;
    }

    // Otherwise save email to session and redirect
    $user = $result->fetch_assoc();//turn `$result` into associative array
    $_SESSION['reset_email'] = $user['Email'];
    header("Location: ../../resetpass/view.php");// Go to password reset page
    exit;
}
?>
