<?php
session_start();
include("database.php");

function getBaseUrl()
{
    $pathInfo = pathinfo($_SERVER['PHP_SELF']);
    return $pathInfo['dirname'] . '/' . $pathInfo['basename'];
}

if (!isset($_SESSION['username'])) {
    echo "<p>You must be logged in to view this page.</p>";
    echo "<a href='index.php'>Go back</a>";
    exit();
}

$username = $_SESSION['username'];

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    $email = '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errorMessage = '';
    $successMessage = '';

    if (!empty($_POST['newUsername'])) {
        $newUsername = $database->sanitize($_POST['newUsername']);
        $statement = $database->executeStatement("SELECT * FROM Users WHERE Username = ?", [$newUsername]);
        $result = $statement->get_result();
        if ($result->num_rows > 0) {
            $errorMessage = 'Username is already taken.';
        } else {
            $statement = $database->executeStatement("UPDATE Users SET Username = ? WHERE Username = ?", [$newUsername, $username]);
            if ($statement->affected_rows > 0) {
                $_SESSION['username'] = $newUsername;
                $successMessage = 'Username updated successfully.';
            } else {
                $errorMessage = 'Failed to update username.';
            }
        }
    }

    if (!empty($_POST['newEmail'])) {
        $newEmail = $database->sanitize($_POST['newEmail']);
        $statement = $database->executeStatement("SELECT * FROM Users WHERE Email = ?", [$newEmail]);
        $result = $statement->get_result();
        if ($result->num_rows > 0) {
            $errorMessage = 'Email is already taken.';
        } else {
            $statement = $database->executeStatement("UPDATE Users SET Email = ? WHERE Username = ?", [$newEmail, $username]);
            if ($statement->affected_rows > 0) {
                $_SESSION['email'] = $newEmail;
                $successMessage = 'Email updated successfully.';
            } else {
                $errorMessage = 'Failed to update email.';
            }
        }
    }

    if (!empty($_POST['oldPassword']) && !empty($_POST['newPassword'])) {
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $statement = $database->executeStatement("SELECT PasswordHash FROM Users WHERE Username = ?", [$username]);
        $result = $statement->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($oldPassword, $user['PasswordHash'])) {
                $statement = $database->executeStatement("UPDATE Users SET PasswordHash = ? WHERE Username = ?", [$hashedNewPassword, $username]);
                if ($statement->affected_rows > 0) {
                    $successMessage = 'Password updated successfully.';
                } else {
                    $errorMessage = 'Failed to update password.';
                }
            } else {
                $errorMessage = 'Old password is incorrect.';
            }
        } else {
            $errorMessage = 'User not found.';
        }
    }

    if (!empty($_POST['newEmail']) || !empty($_POST['newUsername'])) {
        $message = !empty($errorMessage) ? 'error=' . urlencode($errorMessage) : 'success=' . urlencode($successMessage);
        header('Location: ' . getBaseUrl() . '?' . $message, true, 303);
        exit;
    }
} else {
    $successMessage = !empty($_GET['success']) ? urldecode($_GET['success']) : '';
    $errorMessage = !empty($_GET['error']) ? urldecode($_GET['error']) : '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Georgian Social is a social media website made by Brandon Hann">
    <title>Georgian Social - Account</title>
    <link rel="icon" type="image/x-icon" href="./img/favicon-32x32.png">
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg2 flex flex-col min-h-screen">

    <!-- Navigation -->
    <?php
    include 'components/navigation2.php';
    ?>

    <!-- Main content -->
    <main class="flex-grow flex flex-col justify-center items-center mt-10 mb-20 md:mb-0 w-full mx-auto">
        <!-- Account Form -->
        <div class="w-full md:w-1/2 mx-auto mt-4 md:mt-0">
            <div class="mx-2 md:mx-0 p-4 bg-white bg-opacity-50 border border-gray-200 shadow-md rounded-md mb-4">
                <h3 class="text-xl text-center font-bold mb-4 text-gray-800">Change Username/Email</h3>

                <form method="POST" class="space-y-4">
                    <label for="newUsername" class="text-gray-800">New Username:</label>
                    <input type="text" id="newUsername" name="newUsername" class="w-full p-2 border border-gray-200 rounded-md bg-white text-gray-800 placeholder-gray-600">
                    <input type="submit" value="Change Username" class="cursor-pointer flex items-center gap-1 mx-auto py-2 px-8 bg-green-500 text-white rounded-md transition-transform transform hover:scale-110">
                </form>

                <form method="POST" class="space-y-4 mt-6">
                    <label for="newEmail" class="text-gray-800">New Email:</label>
                    <input type="text" id="newEmail" name="newEmail" class="w-full p-2 border border-gray-200 rounded-md bg-white text-gray-800 placeholder-gray-600">
                    <input type="submit" value="Change Email" class="cursor-pointer flex items-center gap-1 mx-auto py-2 px-8 bg-green-500 text-white rounded-md transition-transform transform hover:scale-110">
                </form>

                <form method="POST" class="flex flex-col space-y-4 mt-6">
                    <label for="oldPassword" class="text-gray-800">Old Password:</label>
                    <input type="password" id="oldPassword" name="oldPassword" class="w-full p-2 border border-gray-200 rounded-md bg-white text-gray-800 placeholder-gray-600">

                    <label for="newPassword" class="text-gray-800">New Password:</label>
                    <input type="password" id="newPassword" name="newPassword" class="w-full p-2 border border-gray-200 rounded-md bg-white text-gray-800 placeholder-gray-600">

                    <input type="submit" value="Change Password" class="cursor-pointer flex items-center gap-1 mx-auto py-2 px-8 bg-green-500 text-white rounded-md transition-transform transform hover:scale-110">
                </form>

                <?php if (!empty($successMessage)) : ?>
                    <p class="mt-4 text-center text-green-500"><?php echo $successMessage; ?></p>
                <?php endif; ?>
                <?php if (!empty($errorMessage)) : ?>
                    <p class="mt-4 text-center text-red-500"><?php echo $errorMessage; ?></p>
                <?php endif; ?>

                <!-- Go Back Button -->
                <div class="flex justify-center">
                    <a href="app.php" class="inline-flex justify-center mt-8 px-4 py-2 rounded-md bg-red-500 text-white transition-transform transform hover:scale-110">Go Back</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Mobile Navigation -->
    <?php
    include 'components/mobileNavigation.php';
    ?>

    <!-- Footer -->
    <div class="hidden md:block">
        <?php
        include 'components/footer.php';
        ?>
    </div>

</body>

</html>