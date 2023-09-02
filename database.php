<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Database
{
    private $connection;

    public function __construct()
    {
        $this->connect_db();
    }

    // connect to the database
    public function connect_db()
    {
        $this->connection = mysqli_connect('empty', 'empty', 'empty', 'empty');
        if (mysqli_connect_error()) {
            die("Database Connection Failed" . mysqli_connect_error() . mysqli_connect_error());
        }
    }

    // execute statement
    public function executeStatement($sql, $data = [])
    {
        $statement = $this->connection->prepare($sql);
        if ($statement === false) {
            die("Failed to prepare statement: " . $this->connection->error);
        }
        if ($data) {
            $statement->bind_param(str_repeat('s', count($data)), ...$data);
        }
        $statement->execute();

        // return the statement object
        return $statement;
    }

    // create a new user
    public function createUser($username, $hashed_password, $email, $uploaded_image_path)
    {
        // check if username exists
        $statement = $this->connection->prepare("SELECT * FROM Users WHERE Username = ?");
        $statement->bind_param('s', $username);
        $statement->execute();
        $result = $statement->get_result();

        // if username exists, return error
        if ($result->num_rows > 0) {
            return "usernameExists";
        }

        // check if email exists
        $statement = $this->connection->prepare("SELECT * FROM Users WHERE Email = ?");
        $statement->bind_param('s', $email);
        $statement->execute();
        $result = $statement->get_result();

        // if email exists return error
        if ($result->num_rows > 0) {
            return "emailExists";
        }

        // if username & email do not exist insert new user
        $statement = $this->connection->prepare("INSERT INTO Users (Username, PasswordHash, Email, Image) VALUES (?, ?, ?, ?)");
        $statement->bind_param('ssss', $username, $hashed_password, $email, $uploaded_image_path);
        $result = $statement->execute();

        // if user was successfully inserted return true else return false
        return $result ? true : false;
    }

    public function updateUserProfileImage($username, $imagePath)
    {
        $statement = $this->connection->prepare("UPDATE Users SET Image = ? WHERE Username = ?");
        $statement->bind_param('ss', $imagePath, $username);
        $statement->execute();
    }

    // santitize data
    public function sanitize($var)
    {
        return mysqli_real_escape_string($this->connection, $var);
    }
}

$database = new Database();
