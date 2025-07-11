<?php
// Aim: A universal "blueprint" that is reusable, and handle how to display a  message,
// later when we call, we can decide what message to show

if (isset($_SESSION['error'])) {//if someone(from anywhere) calls the session error, show this...
    echo "<div class='error_message'>
            <p>" . htmlspecialchars($_SESSION['error']) . "</p> 
          </div><br>";
    unset($_SESSION['error']);//delete right after using once, so it doesn't appear again on page refresh or next request.
}

if (isset($_SESSION['success'])) {//if someone(from anywhere) calls the session success, show this...
    echo "<div class='success_message'>
            <p>" . htmlspecialchars($_SESSION['success']) . "</p>
          </div><br>";
    unset($_SESSION['success']);//delete right after using once
}
//The dot sticks the session variable between the HTML tags, so when echo runs, the browser receives the full stitched-together HTML and displays it



/*

Unset:  Deletes the message after it’s shown, 
        Message disappears on next refresh → feels clean
        
Exit: Ends the entire script immediately, 
        Anything after exit; won’t run — even your HTML or form

note:
✔ config.php bebas taruh di mana aja, karena isinya gak ngapa-ngapain secara visual
❗ message.php sebaiknya ditaruh di shared/, karena isinya tampil di browser, dan bakal dipakai di banyak tempat

Contoh skenario:
Kalau kamu taruh message.php di REGISTRATION/, terus file login.php mau pakai juga,
jadi login narik tampilan error dari dalam folder REGISTRATION.

Itu yang bikin bingung nanti kalau project-nya gede.

Sama aja kayak:
Kamu taruh bumbu dapur di kamar
Terus smua orang harus masuk kamar kamu buat masak 😆
*/
?>


