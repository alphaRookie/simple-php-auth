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
    <title>Home</title>
</head>
<!--top part-->
<body>
    <header>
    <div class="nav">
        <div class="logo">
            <p>Logo</p>
        </div>

        <div class="right-links">
            <?php
            $id = $_SESSION['id'];//home.php is reading the user ID that was saved in login.php
            $query = mysqli_query($con, "SELECT * FROM users WHERE Id='$id'");
            $result = mysqli_fetch_assoc($query); //Turn the result into associative array

            if($result){
                $res_username   = $result['Username'];
                $res_email      = $result['Email'];
                $res_birthdate  = $result['Birthdate'];
                $res_id         = $result['Id'];
            }
            else {
                $res_username = "Unknown";
                $res_email = "Unknown";
                $res_birthdate = "Unknown";
                $res_id = 0;
            }
            // Calculate age if birthdate exists
            $age = '...';
            if(!empty($res_birthdate)) {
                $birthDate = new DateTime($res_birthdate);//birthdate
                $today = new DateTime();//today
                $age = $today->diff($birthDate)->y;//calculate age in years
            }

            echo "<a href='update.php?Id=$res_id'>Change profile</a>";
            ?>

            <a href="logout.php"><button class="button">Logout</button></a>
        </div>
    </div>
    </header>
<!--body part-->
    <main>
    <div class="main-box">
        <div class="top">
            <div class="box">
                <p>Yo <b><?php echo $res_username?></b>, whts up?</p>
            </div>
            <div class="box">
                <p>Your email is <b><?php echo $res_email?></b></p>
            </div>
        </div> 
        <div class="bottom">
            <div class="box">
               <p>And you are <b><?php echo $age?></b> y.o</p>
            </div> 
        </div>
    </div>
    </main>

<!--footer part-->
<div class="nav">
    <footer>
        <p>&copy; 2024 My Webpage. All rights reserved.</p>
        <p><a href="#privacy">Privacy Policy</a> | <a href="#terms">Terms of Service</a></p>
    </footer>
</div>


</body>
</html>