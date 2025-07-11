<?php
//Aim: Generate and send 6-digit if email found, otherwise show error message (both via view.php)
session_start();
include("../../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {//check if method is post
    $email = $_POST['email'];//get the email from form submitted(via forgot_pass.php)

    // SQL statement to check if user exists in DB (separatng data and logic)
    $stmt = $con->prepare("SELECT * FROM users WHERE Email = ?");//select all data from selected user
    // Bind the email parameter to prevent SQL injection
    $stmt->bind_param("s", $email);//insert the value of $email into the "?" placeholder
    $stmt->execute();//runs the query using the value given
    $result = $stmt->get_result();//get the result from database, if there are any rows that match

    // If the email sent from `forgot_pass` isnt exist...
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Email not found.";//call the session error, then show message whatever inside ""
        header("Location: view.php");// redirect to view.php to show error message
        exit;
    }
    
    // If the email is exists, we continue here...
    // no need to use else here, using else here is like "the police who still checks us even when all is good."

    // Generate a 6-digit random code, padding with zeros if necessary
    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT); // Example:"093421"
    $expires = date("Y-m-d H:i:s", time() + 300); // expires in 5 minutes

    //SQL statement to save the code and expiration time in the DB
    $stmt = $con->prepare("UPDATE users SET reset_code = ?, reset_expires = ? WHERE Email = ?");
    $stmt->bind_param("sss", $code, $expires, $email);
    $stmt->execute();

    // Simulated "email", // Store data in session to show on success page
    $_SESSION['success'] = "A code was sent to your email: $code";//call the session success, then show message whatever inside ""
    $_SESSION['sent_email'] = $email;//ubah ke otentikasi lewat email

    header("Location: view.php");//redirect to view.php to show error message
    exit;
}
?>
