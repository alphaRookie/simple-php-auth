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
    $user_email = getEmailbyId($con, $id);//get email from user with id...
    
    if (!$user_email) {
        $_SESSION['error'] = "Can't find the data. The account may have been deleted.";
        header("Location: ../../../register/view.php");
        exit;
    }
    $_SESSION['email'] = $user_email['Email'];//store data from DB to session

    //take input from user, then check and validate
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
        $email = trim($_POST['email']);//trim: dont include the space

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {//make sure user enter the valid email
            $_SESSION['error'] = "Please enter a valid email address";
            header("Location: view.php");
            exit;
        }

        if ($email === $user_email['Email']) {//make sure user dont enter their own email again
            $_SESSION['error'] = "No changes detected";
            header("Location: view.php");
            exit;
        }

        //Check if new email already exists
        $email_exist = getEmailbyEmail($con, $email);

        if($email_exist){//check if email is already used
            $_SESSION['error'] = "The Email is already in use. Please try another one ";//call the session error, then show dat message
            header("Location: view.php");
            exit;
        }
        else{ //if Email doesnt exist, so we update the new data 
            $id = $_SESSION['id'];
            updateEmailbyId($con, $email, $id);//update email by Id
            
            unset($_SESSION['csrf_token']);//unset the token
            $_SESSION['success'] = "Email updated successfully!";//call the session success, then show message whatever inside ""
            session_regenerate_id(true);// 📌Creates fresh ID and deletes old one 📌

            header("Location: ../../home/controller.php");//redirect to homepage
            exit;
        }
    }

    else{//if user hasn't submitted the form send back to form page
        header("Location: view.php");
        exit;
    }
?>