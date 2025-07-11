<?php 
// Aim: redirect to next step if the session is success. if error, redirect back to "forgot_pass.php"
    session_start(); 

    // Save the copy of session to a temp variable(which survives across page redirects.) so we can use it after message.php unsets it
    $success_status = isset($_SESSION['success']);//"Saving a copy of the session value before it's deleted."
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../../style.css">
    <title>Verify Code</title>
</head>
<body>
    <div class="container">
    <div class="box form-box"> 
        <header>Reset Code</header>

        <!-- Include message display here --> 
        <?php 
            include("../../shared/message.php"); 
            if ($success_status): // if "$_SESSION['success']" exist and set
        ?>
                <form action="../verifycode/view.php" method="POST"> <!-- send to the view instead of controller bcoz we need form to fill next -->
                    <button type="submit" class="button">Verify here!</button>
                </form>               

        <?php 
            else: // if "$_SESSION['success']" didnt exist, other way $_SESSION['error'] exist
        ?> 
                <form action="../forgot_pass.php" method="POST">
                    <button type="submit" class="button">Go back</button>
                </form>
        <?php endif; ?> <!-- Endif only use at the very end -->

    </div>
    </div>
</body>
</html>

tarok ini dibagian login dan register juga nanti