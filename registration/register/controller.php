<?php
//force HTTPS first, then we're safe to start session
    require_once __DIR__ . '/../shared/security.php';//include the security sessions
    force_https();
    secure_session();

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

    require_once __DIR__ . '/../shared/config.php';//calling the one who opens the door to databas
    require_once __DIR__ . '/../model/users.php';// call model 
    
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
        //take the data from the form
        $username  = $_POST['username'];
        $email     = trim($_POST['email']);//trim: dont include the space
        $birthdate = $_POST['birthdate'];
        $password  = $_POST['password'];

        // Add additional validations, check some condition, if all good we move to "else"
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Please enter a valid email address";
            //user dont have to type username and birthdate again after this error
            $_SESSION['temp_username'] = $username;
            $_SESSION['temp_birthdate'] = $birthdate;
            unset($_SESSION['temp_email']);//prevent the previous correct email appear (no need in this part)
            header("Location: view.php");
            exit;
        }
        else if (strlen($password) < 8) {
            $_SESSION['error'] = "Password must be at least 8 characters.";
            //user dont have to type username, birthdate, and email again after this error
            $_SESSION['temp_username'] = $username;
            $_SESSION['temp_birthdate'] = $birthdate;
            $_SESSION['temp_email'] = $email;
            header("Location: view.php");
            exit;
        }
        //put more validation like preg_match later by 'else if' here
        else {//if all good, we hash the password
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
        }

        // call model to get email by email
        $user = getEmailbyEmail($con, $email);
        
        if($user){//check if email is already used
            $_SESSION['error'] = "The Email is already in use. Please try another one ";//call the session error, then show dat message
            //user dont have to type username and birthdate again after this error
            $_SESSION['temp_username'] = $username;
            $_SESSION['temp_birthdate'] = $birthdate;
            unset($_SESSION['temp_email']);//prevent the previous correct email appear (no need in this part)
            header("Location: view.php");
            exit;
        }
        else{ //if Email deosnt exist, so we insert the new data 
            //call model to insert new user
            insertnewUser($con, $username, $email, $birthdate, $hash_password);

            //unset all of the temp sessions, so the session wont apppear again
            unset($_SESSION['temp_username']);
            unset($_SESSION['temp_birthdate']);
            unset($_SESSION['temp_email']); 
            
            unset($_SESSION['csrf_token']);//unset 
            $_SESSION['success'] = "Registration successful! You can Log in now.";//call the session success, then show message whatever inside ""

            header("Location: ../login/view.php");//redirect to login page
            exit;
        }
    }

    else{//if user hasn't submitted the form
        header("location:view.php");
        exit;
    }

// apkah email yg didaftarkan sensitivecase?
//buat preg_match
//buat email biar gk terlalu panjnag(google: "Maaf, panjang nama pengguna Anda harus antara 6 karakter dan 30 karakter.")
//jgn sampe dispam banyak yg daftar
//ubah date jadi dd/mm/yy

?>