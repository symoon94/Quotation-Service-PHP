<?php
// This class has a constructor to connect to a database. The given
// code assumes you have created a database named 'quotes' inside MariaDB.
//
// Call function startByScratch() to drop quotes if it exists and then create
// a new database named quotes and add the two tables (design done for you).
// The function startByScratch() is only used for testing code at the bottom.
// 
// Authors: Rick Mercer and Sooyoung Moon
//
class DatabaseAdaptor
{
    private $DB; // The instance variable used in every method below
    // Connect to an existing data based named 'first'
    public function __construct()
    {
        $dataBase = 'mysql:dbname=quotes;charset=utf8;host=127.0.0.1';
        $user = 'root';
        $password = ''; // Empty string with XAMPP install
        try {
            $this->DB = new PDO($dataBase, $user, $password);
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo ('Error establishing Connection');
            exit();
        }
    }

    // This function exists only for testing purposes. Do not call it any other time.
    public function startFromScratch()
    {
        $stmt = $this->DB->prepare("DROP DATABASE IF EXISTS quotes;");
        $stmt->execute();

        // This will fail unless you created database quotes inside MariaDB.
        $stmt = $this->DB->prepare("create database quotes;");
        $stmt->execute();

        $stmt = $this->DB->prepare("use quotes;");
        $stmt->execute();

        $update = " CREATE TABLE quotations ( " .
            " id int(20) NOT NULL AUTO_INCREMENT, added datetime, quote varchar(2000), " .
            " author varchar(100), rating int(11), flagged tinyint(1), PRIMARY KEY (id));";
        $stmt = $this->DB->prepare($update);
        $stmt->execute();

        $update = "CREATE TABLE users ( " .
            "id int(6) unsigned AUTO_INCREMENT, username varchar(64),
            password varchar(255), PRIMARY KEY (id) );";
        $stmt = $this->DB->prepare($update);
        $stmt->execute();
    }


    // ^^^^^^^ Keep all code above for testing  ^^^^^^^^^


    /////////////////////////////////////////////////////////////

    public function verifyCredentials($accountName, $psw)
    {
        $stmt = $this->DB->prepare("SELECT username, password FROM users "  .
            " where username = :bind_param;");
        $stmt->bindParam(':bind_param', $accountName);
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if (count($array) === 0)
            return false;  // $accountName does not exist
        else if ($array[0]['username'] === $accountName && password_verify($psw, $array[0]['password']))
            return true;  // Assume accountNames ae unique, no more than 1
        else
            return false;
    }

    public function checkExistUser($accountName)
    {
        $arr = $this->getAllUsers();

        foreach ($arr as $userInfo) {
            if ($userInfo['username'] === $accountName) {
                return true;
            }
        }
        return false;
    }

    // Complete these four straightfoward functions and run as a CLI application

    public function getAllQuotations()
    {
        $stmt = $this->DB->prepare("SELECT * FROM quotations ORDER BY rating DESC;");
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function getAllUsers()
    {
        $stmt = $this->DB->prepare("SELECT * FROM users;");
        $stmt->execute();
        $array = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function addQuote($quote, $author)
    {
        $update = "INSERT INTO quotations (added, quote, author, rating, flagged) VALUES ( NOW(), :bind_quote, :bind_author, 0, 0);";
        $stmt = $this->DB->prepare($update);

        $stmt->bindParam(':bind_quote', $quote);
        $stmt->bindParam(':bind_author', $author);

        $stmt->execute();
    }

    public function deleteQuote($id)
    {
        $update = "DELETE FROM quotations WHERE id = :bind_param;";
        $stmt = $this->DB->prepare($update);

        $stmt->bindParam(':bind_param', $id);

        $stmt->execute();
    }
    public function increaseRating($id)
    {
        $update = "UPDATE quotations SET rating = rating + 1 WHERE id = :bind_param;";
        $stmt = $this->DB->prepare($update);
        $stmt->bindParam(':bind_param', $id);
        $stmt->execute();
    }

    public function decreaseRating($id)
    {
        $update = "UPDATE quotations SET rating = rating - 1 WHERE id = :bind_param;";
        $stmt = $this->DB->prepare($update);

        $stmt->bindParam(':bind_param', $id);

        $stmt->execute();
    }

    public function addUser($accountname, $psw)
    {
        $hashed_pwd = password_hash($psw, PASSWORD_DEFAULT);
        $update = "INSERT INTO users (username, password) VALUES ( :bind_name, :bind_pwd);";
        $stmt = $this->DB->prepare($update);

        $stmt->bindParam(':bind_name', $accountname);
        $stmt->bindParam(':bind_pwd', $hashed_pwd);

        $stmt->execute();
    }
}  // End class DatabaseAdaptor

// Remember to uncomment this test code after you get all 4 function correct !!!!!!!
// $theDBA = new DatabaseAdaptor();
// $theDBA->addUser("abcd", "1234");
// assert ($theDBA->checkExistUser("abcd"));
// // Add the only two tables we will need: quotes and accounts.
// $theDBA->startFromScratch();  // Call a function used for testing only.
// $arr = $theDBA->getAllQuotations();
// assert(empty($arr));  // if one of these fail, Rick's startFromScratch may be wrong
// $arr = $theDBA->getAllUsers();
// assert(empty($arr));

// $theDBA->addUser("Fan", "1234");
// $theDBA->addUser("George", "abcd");
// $theDBA->addUser("Nhan", "Nguyen");
// $arr = $theDBA->getAllUsers();
// assert($arr[0]['username'] === 'Fan');
// assert($arr[0]['id'] == 1);  // === can't be used, MariaDB ints are not PHP ints
// assert($arr[1]['username'] === 'George');
// assert($arr[1]['id'] == 2);
// assert($arr[2]['username'] === 'Nhan');
// assert($arr[2]['id'] == 3);

// assert($theDBA->verifyCredentials('Fan', '1234'));
// assert($theDBA->verifyCredentials('George', 'abcd'));
// assert($theDBA->verifyCredentials('Nhan', 'Nguyen'));
// assert(!$theDBA->verifyCredentials('Huh', '1234'));
// assert(!$theDBA->verifyCredentials('Fan', 'xyz'));

// // Test table quotes
// $theDBA->addQuote('one', 'A');
// $theDBA->addQuote('two', 'B');
// $theDBA->addQuote('three', 'C');

// $arr = $theDBA->getAllQuotations();
// assert(count($arr) == 3);
// assert($arr[0]['quote'] === 'one');
// assert($arr[0]['author'] === 'A');
// assert($arr[0]['rating'] == 0); // Can't use ===. MariaDB ints are not PHP ints
// assert($arr[0]['flagged'] == 0);
// assert($arr[1]['quote'] === 'two');
// assert($arr[1]['author'] === 'B');
// assert($arr[1]['rating'] == 0);
// assert($arr[1]['flagged'] == 0);

// // No assert tests for NOW() are possible without some extra unnecassary work

// // Here a few test for the 3rd quote
// assert($arr[2]['id'] == 3);
// assert($arr[2]['author'] === 'C');
// assert($arr[2]['quote'] === 'three');


// // My quotes
// $theDBA->addQuote("Feeling confident -- or pretending that you feel confident -- is necessary to reach for opportunities. It\'s a cliche, but opportunities are rarely offered; they\'re seized.", "Sheryl Sandberg");
// $theDBA->addQuote("To learn requires intense concentration.", "Cal Newport");
// $theDBA->addQuote("To get real diversity of thought, you need to find the people who genuinely hold different views and invite them into the conversation.", "Adam Grant");
