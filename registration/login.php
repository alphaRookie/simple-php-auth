<?php
    session_start();
    //Turns on "session memory" so site can remember who the user is across different pages.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login form</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php
                include("config.php");
                if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){//check if user has submitted the data (double check) 
                    $email = mysqli_real_escape_string($con, $_POST['email']); // $con take data from DB
                    $password = mysqli_real_escape_string($con, $_POST['password']);

                    // get all data from user where email = (...) AND password = (...)”
                    $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email'") or die("Select error");
                    // unpack the data and stores it in $row (it's associative array)
                    $row = mysqli_fetch_assoc($result);

                    if($row && password_verify($password, $row['Password'])){//`$row` holds whole users data, `$row['Password']` holds the hashed password
                        //if login success, set these 4 differents sessions:
                        $_SESSION['email']     = $row['Email'];// what inside ['...'] must be same as what written in DB
                        $_SESSION['username']  = $row['Username'];
                        $_SESSION['birthdate'] = $row['Birthdate'];
                        $_SESSION['id']        = $row['Id'];

                    header("location:home.php");
                    exit;
                    }

                    else{//if user wasnt found in DB, send back
                        echo "<div class='error_message'>
                                <p>Wrong Username or password</p>
                              </div> <br>";
                        echo "<a href = 'login.php'> <button class='button'> Go Back </button>";
                    }
                }

                else{//if user hasn't submitted the data
            ?>
                    <header>Login</header>
                    <form action="" method="post">
                        <div class="field">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" required>
                        </div>   

                        <div class="field">
                            <label for="password">Password </label>
                            <input type="password" name="password" id="password" required>
                        </div>

                        <div class="field">
                            <input type="submit" class="button" name="submit" value="Submit">
                        </div>

                        <div class="link">
                            <a href="forgotpass/forgot_pass.php">Forgot Password?</a>
                        </div>

                        <div class="link">
                            Dont have account? <a href="register.php">Sign up here!</a>
                        </div>
                    </form>
        </div><!--this part is still part of designed box f-->
            <?php } ?>
    </div>
</body>
</html>

buat security kayak register.php
