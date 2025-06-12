<?php
    session_start();
    include("config.php");
    
    if(!isset($_SESSION['valid'])){//If you're not logged in, kick you out to login
        header("location: login.php");
    }
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
                if(isset($_POST['submit'])){//check if user has submitted these data. If done, we move next... 
                    $username  = $_POST['username'];
                    $email     = $_POST['email'];
                    $birthdate = $_POST['birthdate'];
                    $password  = $_POST['password'];

                $id = $_SESSION['id'];

                $edit_query = mysqli_query($con, "UPDATE users SET Username='$username', Email='$email', Brthdate='$birthdate', Password='$password' WHERE Id='$id'") or die("error occured");
                if($edit_query){
                    echo "<div class='success_message'> 
                            <p>Profile Updated!</p> 
                          </div> <br>";
                    echo "<a href='home.php'><button class='button'>Go Home</button>";
                    }
                }
                else{
                    $id = $_SESSION['id'];
                    $query = mysqli_query($con, "SELECT * FROM users WHERE Id='$id'");

                    while($result = mysqli_fetch_assoc($query)){//must follow how it's written in column database(cant write "Password" as "password")
                        $res_username = $result['Username'];
                        $res_email = $result['Email'];
                        $res_birthdate = $result['Birthdate'];
                        $res_password = $result['Password'];
                    }

            ?>

                <header>
                    <p>Change profile</p>
                    <?php echo "For user with ID: " . $_SESSION['id'];?>
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
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Leave blank to keep current password" autocomplete="off" required>
                    </div>

    
                    <div class="field">
                        <input type="submit" class="button" name="submit" value="Update">
                    </div>
    
                    <div class="link">
                        Already have account? <a href="login.php">Login here!</a>
                    </div>
                </form>
            </div>
            <?php } ?>
        </div>
    </main>
</body>
</html>