<?php
//force HTTPS first, then we're safe to start session
    // If the connection is not HTTPS...
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    // ...redirect to the same page using HTTPS instead
    $https_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $https_url");
    exit();
}

    session_start();
    include("config.php");//calling the one who opens the door to database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self';">//CSP Header: like a website bodyguard, only let trusted code run
    <link rel="stylesheet" href="style.css">
    <title>Registration Form</title> 
</head>
<body>
    <div class="container"><!-- manage that one box itself (positioning, etc) -->
        <div class="box form-box"><!-- set how inside the box looks like -->

        <?php
        // Rate limiting: max 10 tries per session
        if (!isset($_SESSION['reg_attempts'])) {
            $_SESSION['reg_attempts'] = 0;
        }
        $_SESSION['reg_attempts']++;

        if ($_SESSION['reg_attempts'] > 10) {
            die("Too many attempts. Try again later.");
        }

        //check if user has submitted these data and method is POST
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
        
            // 1. CSRF token check
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("CSRF validation failed. Please reload the page.");
            }

            // 2. CAPTCHA check: expected answer is 5
            if ($_POST['captcha_answer'] !== '5') {
                echo "<div class='error_message'><p>Wrong CAPTCHA answer.</p></div><br>";
                echo "<a href='register.php' class='button'> Go back </a>";
                exit;
            }

            //3. take what user type inside the form and store to variable
            $username  = $_POST['username'];
            $email     = $_POST['email'];
            $birthdate = $_POST['birthdate'];
            $password  = $_POST['password'];

            //4. Add additional validations, check some condition, if all good we move to "else"
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<div class='error_message'> 
                        <p>Invalid email format.</p> 
                    </div><br>";
                echo "<a href='register.php' class='button'> Go back </a>";
                exit;
            }
            else if (strlen($password) < 8) {
                echo "<div class='error_message'> 
                        <p>Password must be at least 8 characters.</p> 
                    </div><br>";
                echo "<a href='register.php' class='button'> Go back </a>";
                exit;
            }  
            else {
                //5. hash the password
                $hash_password = password_hash($password, PASSWORD_DEFAULT);
            }

            //6. use prepared statement to make sure that email used by user hasn't choosed by anyone
            $stmt = $con->prepare("SELECT Email FROM users WHERE Email = ?");// "?" means we gonna fill later
            // Bind the email parameter to prevent SQL injection
            $stmt->bind_param("s", $email); // "s" means string, and the "?" gonaa be filled $email
            $stmt->execute();//run this query
            $result = $stmt->get_result();//take the result
            
            if($result->num_rows !=0){//check if email is already exist in DB (not empty(!=0))
                echo "<div class='error_message'> 
                        <p>This email is used, try another one!</p> 
                      </div><br>";
                echo "<a href='register.php' class='button'> Go back </a>";
            }
            else{ 
                //7. email deosnt exist, so we insert the new data 
                $stmt = $con->prepare("INSERT INTO users (Username, Email, Birthdate, Password) VALUES (?, ?, ?, ?)");//insert values later
                $stmt->bind_param("ssss", $username, $email, $birthdate, $hash_password); // "ssss" = they all 4 are string, and now insert the values to the "?"
                $stmt->execute(); // run the query

                $_SESSION['reg_attempts'] = 0; // Reset rate limit counter if registration was successful

                echo "<div class='success_message'> 
                        <p>Registration success!</p>
                      </div><br>";
                echo "<a href='login.php' class='button'> Login now </a>"  ;
            }
        }

        else{// user has NOT submitted the form yet, so we show the form to let them fill it
        ?><!--barrier html and php. Bcoz php wont undertand this -->

            <!-- CSRF Token -->
            <?php
                $csrf_token = bin2hex(random_bytes(32)); // create a random, secure token
                $_SESSION['csrf_token'] = $csrf_token;   // save it in session
            ?>

            <header>Sign up</header>
            <form action="register.php" method="post"> <!-- POST back to itself -->
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" required>  
                </div>   

                <div class="field">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" name="birthdate" id="birthdate" required>
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <!-- CAPTCHA Question -->
                <div class="field">
                    <label for="captcha">What is 2 + 3?</label>
                    <input type="text" name="captcha_answer" id="captcha" required>
                </div>

                <div class="field">
                    <input type="submit" class="button" name="submit" value="Submit">
                </div>

                <div class="link">
                    Already a member? <a href="login.php">Login here!</a> <!-- bro this should be index.php not index.html if you're PHP'ing -->
                </div>
            </form>
        <?php } ?><!--going back into PHP just to close the 'else'-->
        </div>
    </div>
</body>
</html>

make separate file for all of the form(html) and logic(php) bcoz if we resfresh it gonna be repeated. unlike verify.php who only checks a code

Real-Life Analogy=
Combined = Ordering food at a restaurant:
    You ask for food (submit form).
    They check if its available (process).
    If not, you just ask again (safe).

Separated = Mailing a check:
    You write a check (form).
    Mail it to the bank (submit to process.php).
    If you mail it twice, you pay twice! (NEEDS redirect).