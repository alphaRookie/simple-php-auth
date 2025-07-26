<?php
// We dont use it too often in controller.php, we use in view.php bcoz 
    //INSIDE DASHBOARD (data from DB)
    $safe_id        = htmlspecialchars($_SESSION['id'] ?? '0', ENT_QUOTES, 'UTF-8');
    $safe_username  = htmlspecialchars($_SESSION['username'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
    $safe_email     = htmlspecialchars($_SESSION['email'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
    $safe_birthdate = htmlspecialchars($_SESSION['birthdate'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
    $safe_age       = htmlspecialchars($_SESSION['age'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
    

    //OUTSIDE DASHBOARD (Data from what user type)
    $safe_temp_username   = htmlspecialchars($_SESSION['temp_username'] ?? '', ENT_QUOTES, 'UTF-8');
    $safe_temp_email      = htmlspecialchars($_SESSION['temp_email'] ?? '', ENT_QUOTES, 'UTF-8');
    $safe_temp_birthdate  = htmlspecialchars($_SESSION['temp_birthdate'] ?? '', ENT_QUOTES, 'UTF-8');

    //EVERYWHERE
    $safe_token     = htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8');


// the '' part is empty, we can decide what to put there instead of get thrown an error 
?>