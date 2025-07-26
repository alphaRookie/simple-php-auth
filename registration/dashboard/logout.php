<?php
    require_once __DIR__ . '/../shared/security.php';
    force_https();
    secure_session();

    session_start();

    // The controller can only be accessed if user submit the form (via POST).
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: home/controller.php");
        exit;
    }

    // CSRF CHECK 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = "Security token mismatch. Please try again.";
            header('Location: home/controller.php'); // Send them back
            exit; // STOP execution
        }
        
        // We destroy session first
        session_destroy();

        // then start fresh session if needed
        session_start();
        $_SESSION['success'] = "Logged out successfully.";
        header("Location: ../login/view.php");
        exit;
    }
?>