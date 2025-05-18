<?php

class database{
 
    function opencon(): PDO{
        return new PDO(
            dsn: 'mysql:host=localhost;
            dbname=lms_app',
            username: 'root',
            password: '');
    }
 
    function signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $username, $password, $profile_picture_path) {
 
        $con = $this->opencon();
       
        try {
            $con->beginTransaction();
 
            // Insert into Users table
            $stmt = $con->prepare("INSERT INTO users (user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_username, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $birthday, $sex, $email, $phone, $username, $password]);
           
             // Get the newly inserted user_id
            $userID = $con->lastInsertId();
           
            // Insert into users_pictures
            $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUES (?, ?)");
            $stmt->execute([$userID, $profile_picture_path]);
 
            $con->commit();
 
            return $userID; //return user_id for further use (like inserting address)
 
        } catch (PDOException $e) {
 
            $con->rollback();
            return false;
 
        }
 
    }
 
    function insertAddress($userID, $street, $barangay, $city, $province) {
 
        $con = $this->opencon();
 
        try {
            $con->beginTransaction();
 
            //Insert into Address table
            $stmt = $con->prepare("INSERT INTO Address (ba_street, ba_barangay, ba_city, ba_province) VALUES (?, ?, ?, ?)");
            $stmt->execute([$street, $barangay, $city, $province]);
 
             //Get the newly inserted address_id
            $addressID = $con->lastInsertId();
 
             //Link User and Address into users_Address table
            $stmt = $con->prepare("INSERT INTO users_address (user_id, address_id) VALUES (?, ?)");
            $stmt->execute([$userID, $addressID]);
 
            $con->commit();
 
            return true;
 
        } catch (PDOException $e) {
 
            $con->rollback();
            return false;
 
        }
 
    }
 
 
}
?>