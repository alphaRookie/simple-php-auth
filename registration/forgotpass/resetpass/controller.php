<?php
//AIM: CHECK IF THE THE NEW PASSWORD IS VALID, THEN UPDATE THE USER'S PASSWORD IN THE DATABASE
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
    
    //check if the user come from the correct password reset flow? [Thats why we put this part at the beginning]
    if (!isset($_SESSION['reset_email'])) {//If not, block them with an error.
        $_SESSION['error'] = "Unauthorized access.";//call the session error, then show message whatever inside ""
        header("Location: view.php");
        exit;
    }

    require_once __DIR__ . '/../../shared/config.php';
    require_once __DIR__ . '/../../model/users.php';// call model

    if ($_SERVER["REQUEST_METHOD"] == "POST") {//if correct will continue here (this part cant use else)
        $newpass = $_POST['password'];//get the new password
        $confirm_newpass = $_POST['confirm_password'];//and confirm

        if (strlen($newpass) < 8) {
            $_SESSION['error'] = "Password must be atleast 8 characters.";//call the session error, then show message whatever inside ""
            header("Location: view.php");
            exit;
        }

        else if($newpass !== $confirm_newpass){
            $_SESSION['error'] = "The Password must match each other";
            header("location: view.php");
            exit;
        }
        //put else here if there is more validation, like "if password not match" or "if password is weak", etc.

        else{
            //after all validations, we hash the password
            $hash = password_hash($newpass, PASSWORD_DEFAULT);

            $email = $_SESSION['reset_email'];//get the email stored in session
            updatePass($con, $hash, $email);//Update user's password in the database

            // After successfully updating the password, clear all the sessions
            unset($_SESSION['reset_email']);
            unset($_SESSION['reset_started']);
            unset($_SESSION['sent_email']);

            unset($_SESSION['csrf_token']);//unset the token

            // we dont use session_destroy() here, because we still want the success message to be shown
            $_SESSION['success'] = "Password successfully updated.";//call the session error, then show message whatever inside ""
            session_regenerate_id(true);// 📌Creates fresh ID and deletes old one📌
            
            header("Location: ../../login/view.php");
            exit;
        }
    }
?>