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

    //make sure if user's account is indeed exist in DB
    $id = $_SESSION['id'];
    $user_profile = getInfobyId($con, $id);//get username and birthdate by Id
    
    if (!$user_profile) {
        $_SESSION['error'] = "can't find the data. The account may have been deleted.";
        header("Location: ../../../register/view.php");
        exit;
    }
    $_SESSION['username']  = $user_profile['Username'];//store data from DB to session
    $_SESSION['birthdate'] = $user_profile['Birthdate'];

    //take input from user, then check and validate
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
        $username  = $_POST['username'];
        $birthdate = $_POST['birthdate'];

        if ($username === $user_profile['Username'] && $birthdate === $user_profile['Birthdate']) {// check if the value has no changed
            $_SESSION['error'] = "No changes detected.";
            header("Location: view.php");
            exit;
        }

        //If all good, we can update user info
        $id = $_SESSION['id'];
        updateInfobyId($con, $username, $birthdate, $id);
        
        unset($_SESSION['csrf_token']);//unset the token
        $_SESSION['success'] = "Profile updated successfully!";//call the session success, then show message whatever inside ""
        session_regenerate_id(true);// 📌Creates fresh ID and deletes old one 📌

        header("Location: ../../home/controller.php");//redirect to homepage
        exit;
    }

    else{//if user hasn't submitted the form send back to form page
        header("Location: view.php");
        exit;
    }
//FIX: kalo cuma salah satu yg salah terbaca beenr
?>