<?php
    require_once __DIR__ . '/../../../shared/security.php';
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

    require_once __DIR__ . '/../../../shared/config.php';//calling the one who opens the door to database
    require_once __DIR__ . '/../../../model/users.php';// call model

    //make sure if user's account is indeed exist
    $id = $_SESSION['id'];
    $user_pass = getPassbyId($con, $id);

    
    if (!$user_pass) {
        $_SESSION['error'] = "Can't find the data. The account may have been deleted.";
        header("Location: ../../../register/view.php");
        exit;
    }
    $_SESSION['password']  = $user_pass['Password'];

    //take input from user, then check and validate
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
        $newpass          = $_POST['password'];
        $confirm_newpass  = $_POST['confirm_password'];

        if (strlen($newpass) && strlen($confirm_newpass) < 8) {
            $_SESSION['error'] = "The Password must be atleast 8 characters";
            header("Location: view.php");
            exit;
        }
        else if($newpass !== $confirm_newpass){
            $_SESSION['error'] = "The Password must match each other";
            header("location: view.php");
            exit;
        }
        else if (password_verify($newpass, $user_pass['Password'])) {//make sure user wont enter the same password as before
            $_SESSION['error'] = "No changes detected";
            header("location: view.php");
            exit;
        }
        else{//if all good, we update the password

            $hashed = password_hash($newpass, PASSWORD_DEFAULT);//hash first

            $id = $_SESSION['id'];
            updatePassbyId($con, $hashed, $id);
            
            unset($_SESSION['csrf_token']);//unset the token
            $_SESSION['success'] = "Profile updated successfully!";//call the session success, then show message whatever inside ""
            session_regenerate_id(true);// 📌Creates fresh ID and deletes old one📌
    
            header("Location: ../../home/controller.php");//redirect to homepage
            exit;
        }
    }

    else{//if user hasn't submitted the form send back to form page
        header("Location: view.php");
        exit;
    }
//add more validation password later
?>