<?php
// Aim: A universal "blueprint" that is reusable, and handle how to display a  message,
// later when we call, we can decide what message to show

if (isset($_SESSION['error'])) {//if someone(from anywhere) calls the session error, show this...
    echo "<div class='error_message'>
            <p>" . htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') . "</p> 
          </div><br>";
    unset($_SESSION['error']);//delete right after using once, so it doesn't appear again on page refresh or next request.
}

if (isset($_SESSION['success'])) {//if someone(from anywhere) calls the session success, show this...
    echo "<div class='success_message'>
            <p>" . htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') . "</p>
          </div><br>";
    unset($_SESSION['success']);//delete right after using once
}
//The dot sticks the session variable between the HTML tags, so when echo runs, the browser receives the full stitched-together HTML and displays it



/*

The flow:
session message is like a courier:
        1."Wake up if someone calls" =
           if (isset($_SESSION['success']))
           ➤ Only wake up if a message exists — don’t show anything unless there’s a “delivery order.”

        2."Take the packet where boss call" =
           $_SESSION['success'] = 'Registration successfully done.';
           ➤ The controller (your boss) creates the delivery and sends the courier out.

        3."Deliver it to the home that asked" =
           The UI page (like view.php) runs require_once 'message.php' to receive the package and echo the message.

        4."Go away after giving it" =
           unset($_SESSION['success']);
           ➤ After the job’s done and message is shown, courier fcks off. Clean. Silent. Gone.


Why unset instead of exit?
        Unset:  Deletes the message after it’s shown, 
                Message disappears on next refresh → feels clean
                
        Exit:   Ends the entire script immediately, 
                Anything after exit; won’t run — even your HTML or form

*/
?>


