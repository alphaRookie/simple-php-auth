<?php
//no need to put session_start() here bcoz this isn't standalone file
require_once __DIR__ . '/../shared/config.php';

    //LOGIN, SEND_CODE1
    function getAllbyEmail($con, $email){
        $stmt = $con->prepare("SELECT * FROM users WHERE Email = ?");//select all data from the selected user
        $stmt->bind_param("s", $email);// Bind the email parameter to prevent SQL injection
        $stmt->execute();//run this query
        return $stmt->get_result()->fetch_assoc();//only SELECT use this AND MUST!!! (bcoz we need to take the data)
    }

    //REGISTER
        //1. Checking unique of email when user register
        function getEmailbyEmail($con, $email){
            $stmt = $con->prepare("SELECT Email FROM users WHERE Email = ?");// "?" means we gonna fill later
            $stmt->bind_param("s", $email); // "s" means string, and the "?" gonaa be filled $email
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        //2. Checking if user doesn't exist, so we insert new data
        function insertnewUser($con, $username, $email, $birthdate, $hash_password){
            $stmt = $con->prepare("INSERT INTO users (Username, Email, Birthdate, Password) VALUES (?, ?, ?, ?)");//just another way
            $stmt->bind_param("ssss", $username, $email, $birthdate, $hash_password);
            return $stmt->execute();//return true/false
        }

    //FORGOT PASSWORD
        // A. SEND_CODE2 (SEND_CODE1 same as login.php)
        function updateResetCode($con, $email, $code, $expires) {
            $stmt = $con->prepare("UPDATE users SET reset_code = ?, reset_expires = ? WHERE Email = ?");
            $stmt->bind_param("sss", $code, $expires, $email);
            return $stmt->execute();//return true/false
        }

        // B. VERIFYCODE
            // 1. Check if code is exist and isn't expired
            function checkResetCode($con, $code, $now){
                $stmt = $con->prepare("SELECT * FROM users WHERE reset_code = ? AND reset_expires > ?");
                $stmt->bind_param("ss", $code, $now);
                $stmt->execute();
                return $stmt->get_result()->fetch_assoc();
            }

            // 2. Clear the reset code
            function clearResetCode($con, $email){
                $stmt = $con->prepare("UPDATE users SET reset_code = NULL, reset_expires = NULL WHERE Email = ?");//we set the "reset_code" and "reset_expires" to NULL, so the user can't use the 6-DIGIT code again.
                $stmt->bind_param("s",$email);//$email is just placeholder, the actual value is $code_legit['Email']
                return $stmt->execute();
            }

        // C. RESETPASS
        function updatePass($con, $hash, $email){
            $stmt = $con->prepare("UPDATE users SET Password = ? WHERE Email = ?");//update the user's password
            $stmt->bind_param("ss", $hash, $email);
            return $stmt->execute();
        }

    //DASHBOARD
        //HOME
        function getAllbyId($con, $id){
            $stmt = $con->prepare("SELECT * FROM users WHERE Id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        //UPDATE
            // A. UPDATE_INFO
                // 1. get the username and birthdate from selected id
                function getInfobyId($con, $id){
                    $stmt = $con->prepare("SELECT Username, Birthdate FROM users WHERE Id = ?");
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    return $stmt->get_result()->fetch_assoc();
                }
                // 2. update username and password from selected Id
                function updateInfobyId($con, $username, $birthdate, $id){
                    $stmt = $con->prepare("UPDATE users SET Username= ?, Birthdate= ? WHERE Id= ?");
                    $stmt->bind_param("sss", $username, $birthdate, $id); 
                    return $stmt->execute(); // run the query
                }

            //UDPATE_PASSWORD
                // 1. get the password from selected Id
                function getPassbyId($con, $id){
                    $stmt = $con->prepare("SELECT Password FROM users WHERE Id= ?");
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    return $stmt->get_result()->fetch_assoc();
                }
                // 2. update the password from selected id
                function updatePassbyId($con, $hashed, $id){
                    $stmt = $con->prepare("UPDATE users SET Password= ? WHERE Id= ?");
                    $stmt->bind_param("ss", $hashed, $id); 
                    return $stmt->execute();
                }

           //UPDATE_EMAIL
                // 1. check if the account is exist in DB
                function getEmailbyId($con, $id){
                    $stmt = $con->prepare("SELECT Email FROM users WHERE Id = ?");
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    return $stmt->get_result()->fetch_assoc();
                }
                // 2. check if email already use before (same as REGISTER-1)
                // 3. update email by id
                function updateEmailbyId($con, $email, $id){
                    $stmt = $con->prepare("UPDATE users SET Email= ? WHERE Id= ?");//use Id bcoz it wont be changed
                    $stmt->bind_param("ss", $email, $id); 
                    return $stmt->execute(); 
                }


?>              