<?php
    session_start();
    include("config.php");
    
    if(!isset($_SESSION['email'])){// *you can enter to the VIP place if you have reservation time (session time this case)
        header("location: login.php");// if you're not logged in, kick you out to login
        exit;
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
                $id     = $_SESSION['id'];//use id to efficiently take user data(faster, safer)
                $query  = mysqli_query($con, "SELECT * FROM users WHERE Id='$id'") or die("Select error"); // get all data of selected user with ID:..
                $result = mysqli_fetch_assoc($query); //Turn the result into associative array

                if(!empty($result)){//if it exist then we store it into session
                    $res_username  = $result['Username'];//follow whats written inside DB
                    $res_email     = $result['Email'];
                    $res_birthdate = $result['Birthdate'];
                    $res_id        = $result['Id'];
                }
                else{//if didnt exist show "unknown"
                    $res_username  = "Unknown";
                    $res_email     = "Unknown";
                    $res_birthdate = "Unknown";
                    $res_id        = 0;
                }

                // Calculate age if birthdate exists
                $age = 'unknown';
                if(!empty($res_birthdate)) {
                    $datebirth = new DateTime($res_birthdate);//birthdate
                    $today     = new DateTime();//today
                    $age       = $today->diff($datebirth)->y;//calculate age in years
                }

                echo "<a href='update.php?Id=$res_id'><button class='button'>Change profile</button></a>";//change profile
                echo "<a href='logout.php'><button class='button'>Logout</button></a>";//logout button
            ?>

        </div>
    </div>
    </header>
<!--body part-->
    <main>
    <div class="main-box">
        <div class="top">
            <div class="box">
                <p>Yo <b><?php echo htmlspecialchars($res_username); ?></b>, whts up?</p><!-- htmlspecialchars to prevent XSS attacks, usually use to display something-->
            </div>
            <div class="box">
                <p>Your email is <b><?php echo htmlspecialchars($res_email); ?></b></p>
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