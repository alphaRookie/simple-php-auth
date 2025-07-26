<?php
    // 1. Force HTTPS first, so all session data (including cookies) is encrypted from the start.
    function force_https() {
        if ($_SERVER['HTTP_HOST'] !== 'localhost' && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {//learnign purpose, so error wont appear like that one
            $https_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];//
            header("Location: $https_url");//Forces the browser to reload the same page, but in HTTPS instead of HTTP.
            exit;
        }
    }   

    // 2. SESSION SECURITY
    function secure_session() {
        ini_set('session.cookie_httponly', 1); //Makes cookies invisible to JS             [Hackers can use stolen cookies to act as users]
        ini_set('session.cookie_secure', 1); // Only sends cookies over HTTPS connections  [Cookies can be stolen from network traffic]
        ini_set('session.use_strict_mode', 1); //server will reject any session ID that was not created by the server (hacker can't create fake ticket to access)
    }
//change the if(...) part with this: (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") //if HTTPS empty or it's set to "off"...
?>