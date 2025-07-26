<?php
    require_once __DIR__ . '/../../shared/security.php';
    force_https();
    secure_session();

    session_start(); 

    // CSRF token generation (for logout form)
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    require_once __DIR__ . '/../../shared/escape_session.php';// 📌 only this case we use it in controller, bcoz this time we dont redirect to view.php like we usually do 📌
    
    //❗Always make sure if user who entered the page is logged in ❗
    if(!isset($_SESSION['email'])){// this gate only allow user enter if the session is set
        header("location: ../../login/controller.php");// if you're not logged in, kick you out to login
        exit;
    }

    require_once __DIR__ . '/../../shared/config.php';// 📌 Only call DB after validate the security 📌
    require_once __DIR__ . '/../../model/users.php';// call model

    //
    $id = $_SESSION['id'];//"Id" is more efficient and reliable to identify a unique user (why not email again? bcoz the email can be changed)
    $user = getAllbyId($con, $id);// get all data by Id

    if($user){//if user is exist then we store it into sessions (set fallback instead of creating else)
        $_SESSION['username']   = $user['Username'] ?? 'Unknown';//if the username is empty, we set it to "Unknown"
        $_SESSION['email']      = $user['Email'] ?? 'Unknown';
        $_SESSION['birthdate']  = $user['Birthdate'] ?? 'Unknown';
        $_SESSION['id']         = $user['Id'] ?? '0';
    }

    // Calculate age if birthdate exists
    if(!empty($_SESSION['birthdate'])) {
        $datebirth        = new DateTime($_SESSION['birthdate']);//birthdate
        $today            = new DateTime();//today
        $_SESSION['age']  = $today->diff($datebirth)->y;//calculate age in years
    }
    else{
        $_SESSION['age'] = 'Unknown';
    }

    // To load the UI (must be at the end)
    require_once 'view.php';
/*
XPLANATIONS:
    ### Why putting require_once 'view.php' at the end?
        PHP runs top to bottom. If you require_once 'view.php' before setting variables, then the view runs without data.
        If you put it after, then the view sees the data and works correctly.
    
    ### What is ENT_QUOTES and 'UTF-8' ?
       - ENT_QUOTES = Escaping value inside ' and "
       - 'UTF-8'    = Handle special characters from various languages
*/



//FIXING:

// Consider mana yg bisa right click dan mana yg ngak bisa.

/*
<a href> always uses GET (even if you don't write method="GET")
-> so i need to change it to post or escape
*/
?>