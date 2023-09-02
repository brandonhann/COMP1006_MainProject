<?php
session_start();
include("database.php");

if (!isset($_SESSION['username'])) {
    die("You must be logged in to delete an image.");
}

$username = $_SESSION['username'];

// fetch current image path
$sql = "SELECT Image FROM Users WHERE Username = ?";
$statement = $database->executeStatement($sql, [$username]);
$result = $statement->get_result();
$imagePath = $result->fetch_assoc()['Image'];

// check if not already the default image
if ($imagePath != './storage/default.jpg') {
    // delete image file
    unlink($imagePath);
    // update the database to set the image to default
    $sql = "UPDATE Users SET Image = './storage/default.jpg' WHERE Username = ?";
    $database->executeStatement($sql, [$username]);
    header('Location: profile.php?username=' . $username);
    exit();
} else {
    header('Location: profile.php?username=' . $username);
    exit();
}
