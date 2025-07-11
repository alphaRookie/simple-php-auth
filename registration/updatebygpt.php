<?php
session_start();
include("config.php");

// Redirect if not logged in
if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

if (isset($_POST['submit'])) {
    $username  = mysqli_real_escape_string($con, $_POST['username']);
    $email     = mysqli_real_escape_string($con, $_POST['email']);
    $birthdate = mysqli_real_escape_string($con, $_POST['birthdate']);
    $password  = $_POST['password'];

    // Update with or without password
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET Username='$username', Email='$email', Birthdate='$birthdate', Password='$hashed_password' WHERE Id='$id'";
    } else {
        $query = "UPDATE users SET Username='$username', Email='$email', Birthdate='$birthdate' WHERE Id='$id'";
    }

    $edit_query = mysqli_query($con, $query) or die("Error occurred while updating");

    if ($edit_query) {
        echo "<div class='success_message'><p>Profile Updated!</p></div><br>";
        echo "<a href='home.php'><button class='button'>Go Home</button></a>";
        exit;
    }
} else {
    // Get current user data
    $result = mysqli_query($con, "SELECT * FROM users WHERE Id='$id'");
    $user = mysqli_fetch_assoc($result);
}
echo "Updating user with ID: " . $_SESSION['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change profile</title>
    <link rel="stylesheet" href="style.css">
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
                <header>Change profile</header>
                <form action="" method="post">
                    <div class="field">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" value="<?php echo $user['Username']; ?>" required>
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo $user['Email']; ?>" required>
                    </div>

                    <div class="field">
                        <label for="birthdate">Birthdate</label>
                        <input type="date" name="birthdate" id="birthdate" value="<?php echo $user['Birthdate']; ?>" required>
                    </div>

                    <div class="field">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password" placeholder="Leave blank to keep current password">
                    </div>

                    <div class="field">
                        <input type="submit" class="button" name="submit" value="Update">
                    </div>

                    <div class="link">
                        Already have an account? <a href="index.php">Login here!</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
