<?php
session_start();

if (isset($_SESSION['username'])) {
    // check if user is logged in, if so redirect to app.php
    header('Location: app.php');
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get user data from form
    $username = $database->sanitize($_POST['username']);
    $password = $database->sanitize($_POST['password']);

    // prepare SQL to get user with entered username
    $statement = $database->executeStatement("SELECT * FROM Users WHERE Username = ?", [$username]);
    $result = $statement->get_result();

    // if username exists checks password
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['PasswordHash'])) {
            // if password is correct login the user
            $_SESSION['username'] = $user['Username'];  // set session variable
            $_SESSION['email'] = $user['Email'];  // set email in session variable
            header('Location: app.php');
            exit();
        } else {
            // if password incorrect returns error
            header('Location: login.php?error=incorrectPassword');
            exit();
        }
    } else {
        // if username doesn't exist returns error
        header('Location: login.php?error=usernameNotFound');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Georgian Social is a social media website made by Brandon Hann">
    <title>Georgian Social - Login</title>
    <link rel="icon" type="image/x-icon" href="./img/favicon-32x32.png">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg flex flex-col min-h-screen bg-gray-50">
    <!-- Navigation -->
    <?php
    include 'components/navigation.php';
    ?>

    <main class="flex-grow flex items-center justify-center">
        <div class="bg-gray-50 bg-opacity-90 text-gray-900 rounded-md shadow-md p-8 m-4 w-full max-w-sm">
            <!-- Login Form -->
            <form id="loginForm" method="POST" action="login.php">
                <h2 class="flex items-center gap-1 justify-center mb-8 text-center text-3xl font-bold">Login <i class="fa-solid fa-user"></i></h2>

                <div class="mb-4">
                    <label class="block mb-2" for="username">Username</label>
                    <input class="w-full px-3 py-2 placeholder-gray-500 border rounded-md" type="text" id="username" name="username" required>
                </div>
                <div class="mb-6">
                    <label class="block mb-2" for="password">Password</label>
                    <input class="w-full px-3 py-2 placeholder-gray-500 border rounded-md" type="password" id="password" name="password" required>
                </div>
                <button class="w-full py-2 px-4 bg-green-600 text-gray-50 rounded-md transition-transform transform hover:scale-110 hover:bg-green-600" type="submit">Login</button>
                <p class="my-4 text-center">Don't have an account? <a href="register.php" class="text-green-600 underline">Register
                        here</a></p>
                <!-- display error message -->
                <?php if (isset($_GET['error'])) : ?>
                    <?php if ($_GET['error'] == 'incorrectPassword') : ?>
                        <p class="text-red-500 text-center">Incorrect password.</p>
                    <?php elseif ($_GET['error'] == 'usernameNotFound') : ?>
                        <p class="text-red-500 text-center"">Username not found.</p>
                    <?php else : ?>
                        <p class=" text-red-500 text-center"">An unknown error occurred. Please try again.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php
    include 'components/footer.php';
    ?>

</body>

</html>