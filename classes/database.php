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

    // Function for logging in
    function loginUser($email, $password) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Users WHERE user_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['user_password'])) {
            return $user;
        } else {
            return false;
        }
    }

    // Functions for adding, viewing, and updating author's details
    function addAuthor($authorfirstname, $authorlastname, $authorbirthyear, $authornationality) {
    $con = $this->opencon();
    try {
        $con->beginTransaction();

        $stmt = $con->prepare("INSERT INTO authors (author_FN, author_LN, author_birthday, author_nat) VALUES (?, ?, ?, ?)");
        $stmt->execute([$authorfirstname, $authorlastname, $authorbirthyear, $authornationality]);

        $authorID = $con->lastInsertId();

        $con->commit();
        return $authorID;

        } catch (PDOException $e) {
            $con->rollback();
            return false;
        }
    }

    function viewAuthors()
        {
            $con = $this->opencon();
            return $con->query("SELECT * FROM Authors")->fetchAll();
        }

    function viewAuthorsID($id) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Authors WHERE author_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateAuthor($authorID, $authorfirstname, $authorlastname, $authorbirthyear, $authornationality) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();

            $stmt = $con->prepare("UPDATE authors SET author_FN = ?, author_LN = ?, author_birthday = ?, author_nat = ? WHERE author_id = ?");
            $stmt->execute([$authorfirstname, $authorlastname, $authorbirthyear, $authornationality, $authorID]);

            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollback();
            return false;
        }
    }

    // Functions for adding, viewing, and updating genres's details
    function addGenre($genrename) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO genres (genre_name) VALUES (?)");
            $stmt->execute([$genrename]);

            $genreID = $con->lastInsertId();

            $con->commit();
            return $genreID;
        } catch (PDOException $e) {
            $con->rollback();
            return false;
        }
    }

    function viewGenre()
        {
            $con = $this->opencon();
            return $con->query("SELECT * FROM Genres ORDER BY genre_id")->fetchAll();
        }

    function viewGenresID($id) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Genres WHERE genre_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        function updateGenre($genreID, $genrename) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();

            $stmt = $con->prepare("UPDATE genres SET genre_name = ? WHERE genre_id = ?");
            $stmt->execute([$genrename, $genreID]);

            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollback();
            return false;
        }
    }

    // Books
    function addBook($bookTitle, $bookISBN, $bookYear, $bookGenres, $bookQuantity) {
            $con = $this->opencon();

            try {
                $con->beginTransaction();

                $stmt = $con->prepare("INSERT INTO Books (book_title, book_isbn, book_pubyear, quantity_avail) VALUES (?, ?, ?, ?)");
                $stmt->execute([$bookTitle, $bookISBN, $bookYear, $bookQuantity]);

                $book_id = $con->lastInsertId();

                foreach ($bookGenres as $genre_id) {
                    $stmt = $con->prepare("INSERT INTO Books_Genres (book_id, genre_id) VALUES (?, ?)");
                    $stmt->execute([$book_id, $genre_id]);
                }

                $con->commit();
                return true;
            } catch (PDOException $e) {
                $con->rollBack();
                return false;
            }
        }

 
 
}
?>