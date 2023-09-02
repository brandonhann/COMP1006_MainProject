<?php
session_start();
include("database.php");

if (!isset($_SESSION['username'])) {
    echo "<p>You must be logged in to view this page.</p>";
    echo "<a href='index.php'>Go back</a>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postID = $database->sanitize($_POST['postID']);

    // Fetch UserID of post to ensure the user can only delete their own posts
    $sql = "SELECT UserID FROM Posts WHERE PostID = ?";
    $statement = $database->executeStatement($sql, [$postID]);
    $result = $statement->get_result();
    if ($resultRow = $result->fetch_assoc()) {
        $postUserID = $resultRow['UserID'];
    } else {
        echo "<p>Failed to fetch UserID of post.</p>";
        exit();
    }

    $username = $_SESSION['username'];

    // Fetch UserID of current user
    $sql = "SELECT UserID FROM Users WHERE Username = ?";
    $statement = $database->executeStatement($sql, [$username]);
    $result = $statement->get_result();
    if ($resultRow = $result->fetch_assoc()) {
        $currentUserID = $resultRow['UserID'];
    } else {
        echo "<p>Failed to fetch UserID of current user.</p>";
        exit();
    }

    // If the post belongs to the current user, delete it and decrement post count
    if ($currentUserID == $postUserID) {
        $sql = "DELETE FROM Posts WHERE PostID = ?";
        $database->executeStatement($sql, [$postID]);

        // Decrement the PostAmount for the user
        $sql = "UPDATE Users SET PostAmount = PostAmount - 1 WHERE UserID = ?";
        $database->executeStatement($sql, [$currentUserID]);

        header('Location: app.php');
        exit();
    } else {
        echo "<p>You can only delete your own posts.</p>";
        exit();
    }
}
