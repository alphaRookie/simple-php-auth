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
                if(isset($_POST['submit'])){//if user click 'submit' button, we have to get the data for email and password(if not, shows the form)
                    $email = mysqli_real_escape_string($con, $_POST['email']);
                    $password = mysqli_real_escape_string($con, $_POST['password']);

                    //$result be like: “Yo DB, gimme any user where email = (what user typed) AND password = (what user typed)”
                    $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email' AND PASSWORD='$password'") or die("Select error");
                    //If DB finds that user, this line takes their data and stores it in $row.
                    $row = mysqli_fetch_assoc($result);

                    if(is_array($row) && !empty($row)){//“If user was found in DB...”
                        //Then it saves their info in session memory:
                        $_SESSION['valid'] = $row['Email'];
                        $_SESSION['username'] = $row['Username'];
                        $_SESSION['birthdate'] = $row['Birthdate'];
                        $_SESSION['id'] = $row['Id'];
                    }
                    else{//"or if user wasnt found..."
                        echo "<div class='error_message'>
                                <p>Wrong Username or password</p>
                              </div> <br>";
                        echo "<a href = 'login.php'> <button class='button'> Go Back </button>";
                    }

                    if(isset($_SESSION['valid'])){//If session is set (user logged in), redirect to home.php
                        header("location: home.php");
                    }
                }

                else{
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
                            Dont have account? <a href="register.php">Sign up here!</a>
                        </div>
                    </form>
        </div><!--this part is still part of designed box f-->
            <?php } ?>
    </div>
</body>
</html>