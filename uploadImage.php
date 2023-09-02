<?php
session_start();
include("database.php");

if (!isset($_SESSION['username'])) {
    die("You must be logged in to upload an image.");
}

$username = $_SESSION['username'];

$target_dir = "./storage/";
$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

// rename the file to pfp_username
$target_file = $target_dir . 'pfp_' . $username . '.' . $imageFileType;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// check if no file was uploaded
if ($_FILES["fileToUpload"]["error"] == UPLOAD_ERR_NO_FILE) {
    header('Location: profile.php?username=' . $username);  // redirect back to profile.php
    exit();
}

// check if image file is a real image
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // update database with new image path
            $sql = "UPDATE Users SET Image = ? WHERE Username = ?";
            $database->executeStatement($sql, [$target_file, $username]);
            header('Location: profile.php?username=' . $username);
        } else {
            echo "There was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}
