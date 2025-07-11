<?php
//resetpass dan seterusnya belum kelar
//Aim: user sets a new password
session_start();
include("../config.php");
 
//check if the user come from the correct password reset flow?
if (!isset($_SESSION['reset_email'])) {//If not, block them with an error.
    // http_response_code(403); (suggestion)
        $_SESSION['error'] = "Unauthorized access.";//call the session error, then show message whatever inside ""
        header("Location: view.php");
        exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {//if correct will continue here (this part cant use else)
    //http_response_code(405);  (suggestion)
    $newpass = $_POST['password'];//get the new password

    if (strlen($newpass) < 8) {
        //http_response_code(400); (suggestion)
        $_SESSION['error'] = "Password must be atleast 8 character.";//call the session error, then show message whatever inside ""
        header("Location: view.php");
        exit;
    }
    //put else here if there is more validation, like "if password not match" or "if password is weak", etc.

        //after all validations, we hash the password
        $hash = password_hash($newpass, PASSWORD_DEFAULT);

        //Get the user's email from the session (stored in verify_code.php)
        $email = $_SESSION['reset_email'];

        //Update the database:
        //  - Set the new password
        //  - Remove the reset code (so it can't be reused)
        $stmt = $con->prepare("UPDATE users SET Password = ?, reset_code = NULL, reset_expires = NULL WHERE Email = ?");//update the user's password. Btw "reset_code" and "reset_expires" lives in DB, not sent from `verify_code.php`
        $stmt->bind_param("ss", $hash, $email);//insert the value of both into the "?" placeholder
        $stmt->execute();

        $_SESSION['success'] = "Password successfully updated.";//call the session error, then show message whatever inside ""

        session_destroy(); // clear the session(for security)
        
        header("Location: ../login.php");
        exit;

}
?>