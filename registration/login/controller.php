<?php
    require_once __DIR__ . '/../shared/security.php';//include the security sessions
    force_https();
    secure_session();
    
    session_start();//Turns on "session memory" so site can remember who the user is across different pages.

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
    
    require_once __DIR__ . '/../shared/config.php';//❗️we call config here because the CSRF check must run before any DB operations❗️
    require_once __DIR__ . '/../model/users.php';// call model

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){//check if user has submitted the data (double check) 
        $email = trim($_POST['email']);
        $password = $_POST['password'];//just let the space be inlcuded (trim later if needed)
    
        // Email validation (simple, but make the it respond faster to mistakes )
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Please enter a valid email address";
            unset($_SESSION['temp_email']);// don't make temporary email appears when the email is invalid
            header("Location: view.php");
            exit;
        }
        //put more validation here later

        //call model to get all data by email
        $user = getAllbyEmail($con, $email);

        if(!$user){ //if the email didn't exist...
            $_SESSION['error'] = "Can't find the Email";//call the session error, then show dat message
            unset($_SESSION['temp_email']); //dont make temporary email appears when the email doens't exist
            header("Location: view.php");
            exit;
        }

        else{//if the email exist, now we check the password
            if(!password_verify($password, $user['Password'])) {// error if the password isn't matches the hashed password in DB
                $_SESSION['error'] = "Incorrect password.";
                $_SESSION['temp_email'] = $email;// 📌User dont have to type their email again if the password is wrong (we put this where we want this to appear)📌
                header("Location: view.php");
                exit;
            }

            //OTHERWISE, if password matches...
            unset($_SESSION['temp_email']);//remove the temporary email that appears when the password is wrong
            unset($_SESSION['csrf_token']);
            session_regenerate_id(true);// 📌Creates fresh ID and deletes old one (make stolen pre-login coookie useless)📌

            //it's like lend user keys so they can enter
            $_SESSION['id']        = $user['Id'];
            $_SESSION['email']     = $user['Email'];
            $_SESSION['username']  = $user['Username'];
            $_SESSION['birthdate'] = $user['Birthdate'];
            
            header("location: ../dashboard/home/controller.php");
            exit;
            } 
        }

    else{//if user hasn't submitted the data
        header("location: view.php");//send back to form page
        exit;
    }

/*
=> Further Xplanations <=

1. Email validation:
    - user enters email like "john" / "john@doe", etc. 
    - It search in DB for that email or whatever user typed, and returns error if not found.
 -> So, email validation helps it to stop before searching in DB (saves time and make the error appears faster)

2. Temporary email:
    - If the user enters wrong password, we store the email in session as 'temp_email'.
    - This allows us to pre-fill the email field in the login form, so the user doesn't have to retype it.
    ### When we unset the session variable ?
         the login is successful            (if not, session will continue)
         when the email is invalid          (if not, user will see the last email they typed in the form)
         when the password is incorrect     (if not, user will see the last email they typed in the form)
         when the email is not exist        (if not, user will see the last email they typed in the form)

3. About "session_regenerate_id(true);":
    ➤ It's all about creating a new session ID and delete the old one before redirecting to another page.
    
    ### Why update session ID before redirecting? Why not in the new page after redirecting?
        Because by that time, the old session ID has already been sent to the browser (via the cookie), and if an attacker had it or fixed it somehow, they could takeover it.

    ### When do we use it?
        - After login (imagine giving the owner key instead of guest key)
        - After password reset 
        - After related to any updating profile
        - Optional: After role changed (user to admin)
*/


// WHAT TO FIX LATER?

/* 
FIX THIS MESSAGE (Appears when the mysql in xampp isnt running)
Fatal error: Uncaught mysqli_sql_exception: MySQL server has gone away in C:\xampp\htdocs\registration\login\controller.php:26 Stack trace: #0 C:\xampp\htdocs\registration\login\controller.php(26): mysqli->prepare('SELECT * FROM u...') #1 {main} thrown in 
C:\xampp\htdocs\registration\login\controller.php on line 26
*/

//BUAT "session_regenerate_id(true);" DI TEMPAT-TEMPAT SESUAI KRITERIA DIATAS NANTI
// UNSET csrf_token DI EVERYTIME WANT TO REDIRECT TO DIFF PAGE THAT HAS DIFF TOKEN
// buat model untuk crud, dll (pelajari fungsi model)
// BUAT SEMUA temp_session UNIQUE NAME
// FIX mismatch token, set token a lalu b kemudian set di session yg sama

//buat preg_match untk validasi
//tarok apapun yg kira2 gk layak untuk ditarok di code skrg di obsidian(ya gk mgkin kan nyusahiin diri buat pass susah, arte limiting)
?>
 