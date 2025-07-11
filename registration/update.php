<?php
    session_start();
    include("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Change profile</title>
</head>
<body>
    <header>
    <div class="nav">
        <div class="logo">
           <p><a href="home.php">Logo</a></p>
        </div>

        <div class="right-links">
            <a href="#">Change profile</a>
            <a href="logout.php"><button class="button">Logout</button></a>
        </div>
    </div>
    </header>

    <main>
    <div class="container">
    <div class="box form-box">

    <?php 
        if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){//check if user has submitted these data (double check)
            $username  = mysqli_real_escape_string($con, $_POST['username']); // 
            $email     = mysqli_real_escape_string($con, $_POST['email']);
            $birthdate = mysqli_real_escape_string($con, $_POST['birthdate']);
            $password  = mysqli_real_escape_string($con, $_POST['password']);
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

                $id = $_SESSION['id'];//take a user info of whose id selected
                $edit_query = mysqli_query($con, "UPDATE users SET Username='$username', Email='$email', Birthdate='$birthdate', Password='$hash_password' WHERE Id='$id'") or die("error occured");

                if($edit_query){//if the update success
                    echo "<div class='success_message'> 
                            <p>Profile Updated!</p> 
                          </div> <br>";
                    echo "<a href='home.php'><button class='button'>Go Home</button>";
                    exit;//stop here, so form wouldn't appear again
                }  
                else{//if the update fail
                    echo "<div class='error_message'>
                            <p>Update failed.</p>
                          </div>";
                }
        }

        else{//if user hasn't submitted the form...
            $id = $_SESSION['id'];
            $query = mysqli_query($con, "SELECT * FROM users WHERE Id='$id'");

            if($result = mysqli_fetch_assoc($query)){//show the existing data(user's current infos)
                $res_username = $result['Username'];//follow how is written in DB
                $res_email = $result['Email'];
                $res_birthdate = $result['Birthdate'];
                $res_password = $result['Password'];
            }
            else{//show this if user has no data saved in DB
                echo "<div class='error_message'>
                        <p>User not found. It may have been deleted</p>
                      </div><br>";
            }
        }
    ?>

    <header>
        <p>Change profile</p>
        <?php echo "For user with Username: " . $_SESSION['username'];?>
    </header>
        <form action="" method="post">
            <div class="field">
                <label for="username">New Username</label>
                <input type="text" name="username" id="username" value="<?php echo$res_username ?>" autocomplete="off" required>
            </div>   
    
            <div class="field">
                <label for="email">New Email</label>
                <input type="email" name="email" id="email" value="<?php echo$res_email ?>" autocomplete="off" required>
            </div>
                    
            <div class="field">
                <label for="birthdate">New Birthdate</label>
                <input type="date" name="birthdate" id="birthdate" value="<?php echo$res_birthdate ?>" autocomplete="off" required>
            </div>

            <div class="field">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" placeholder="Fill password" autocomplete="off">
            </div>
    
            <div class="field">
                <input type="submit" class="button" name="submit" value="Update">
            </div>
    
            <div class="link">
                Already have account? <a href="login.php">Login here!</a>
            </div>
        </form>
    </div><!--box form-box-->
    </div><!--container-->
    </main>
</body>
</html>

