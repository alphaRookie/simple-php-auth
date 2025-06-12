<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registration Form</title>
</head>
<body>
    <div class="container"><!--a bookshelf-->
        <div class="box form-box"><!--contains of the book-->

        <?php
        include("config.php");//calling the one who opens the door to database
        if(isset($_POST['submit'])){//check if user has submitted these data. If done, we move next... 
            $username  = $_POST['username'];
            $email     = $_POST['email'];
            $birthdate = $_POST['birthdate'];
            $password  = $_POST['password'];

            //bcoz data has submitted, now we want to make sure that email used by him hasn't choosed before by other
            //now we verify the unique email, check and find through database(is choosen email exist?)
            $check = mysqli_query($con, "SELECT Email FROM users WHERE Email='$email'");

            if(mysqli_num_rows($check) !=0){//if email is not empty(!=0)/already exist so...
                echo "<div class='error_message'> 
                        <p>This email is used, try another one!</p> 
                      </div> <br>";
                echo "<a href='register.php'><button class='button'>Go back</button>";
            }
            else{// but if email doesnt exist, so registration is success
                mysqli_query($con, "INSERT INTO users(Username,Email,Birthdate,Password) VALUES('$username','$email','$birthdate','$password')") or die("error occured"); //insert new user into datsbase, of if fails shows "error occured"
                echo "<div class='success_message'> 
                        <p>Registration success!</p> 
                      </div><br>";
                echo "<a href='login.php'><button class='button'>Login now</button>"  ;
            }
        }

        else{// user has NOT submitted the form yet, so we show the form to let them fill it
        ?><!--barrier html and php. Bcoz php wont undertand this -->

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

                <div class="field">
                    <input type="submit" class="button" name="submit" value="Submit">
                </div>

                <div class="link">
                    Already a member? <a href="login.php">Login here!</a> <!-- bro this should be index.php not index.html if you're PHP'ing -->
                </div>
            </form>
        <?php 
        } // go back into PHP just to close the 'else'
        ?>
        </div>
    </div>
</body>
</html>
