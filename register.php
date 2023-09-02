<?php
session_start();
include('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get user data from form
    $username = $database->sanitize($_POST['username']);
    $email = $database->sanitize($_POST['email']);
    $password = $database->sanitize($_POST['password']);

    // check if an image has been uploaded and rename using the provided username
    $uploaded_image_path = './storage/default.jpg'; // default path

    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $imageName = "pfp_" . $username . ".jpg";
        $potential_image_path = './storage/' . $imageName;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $potential_image_path)) {
            $uploaded_image_path = $potential_image_path;
        }
    }

    // hash the password w/ bcrypt
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // insert user into database
    $result = $database->createUser($username, $hashed_password, $email, $uploaded_image_path);

    if ($result === true) {
        // set session variable
        $_SESSION['username'] = $username;
        header('Location: app.php');
        exit();
    } else {
        if ($result == "usernameExists") {
            header('Location: register.php?error=usernameExists');
            exit();
        } elseif ($result == "emailExists") {
            header('Location: register.php?error=emailExists');
            exit();
        } else {
            error_log("Failed to create user: " . print_r($result, true));
            header('Location: register.php?error=unknown');
            exit();
        }
    }
} elseif (isset($_SESSION['username'])) {
    // check if user is logged in, if so redirect to app.php
    header('Location: app.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Georgian Social is a social media website made by Brandon Hann">
    <title>Georgian Social - Register</title>
    <link rel="icon" type="image/x-icon" href="./img/favicon-32x32.png">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg flex flex-col min-h-screen">

    <!-- Navigation -->
    <?php
    include 'components/navigation.php';
    ?>

    <main class="flex-grow flex items-center justify-center">
        <div class="bg-gray-50 bg-opacity-90 text-gray-900 rounded-md shadow-md p-8 m-4 w-full max-w-sm">
            <!-- Register Form -->
            <form id="registerForm" method="POST" action="register.php" enctype="multipart/form-data">
                <h2 class="flex items-center gap-1 justify-center mb-8 text-center text-3xl font-bold">Register <i class="fa-solid fa-user"></i></h2>
                <div class="mb-4">
                    <label class="block mb-2" for="username">Username</label>
                    <input class="w-full px-3 py-2 placeholder-gray-500 border rounded-md" type="text" id="username" name="username" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2" for="email">Email</label>
                    <input class="w-full px-3 py-2 placeholder-gray-500 border rounded-md" type="email" id="email" name="email" required>
                </div>
                <div class="mb-6">
                    <label class="block mb-2" for="password">Password</label>
                    <input class="w-full px-3 py-2 placeholder-gray-500 border rounded-md" type="password" id="password" name="password" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2" for="profileImage">Profile Image (optional)</label>
                    <input class="w-full px-3 py-2 placeholder-gray-500 border rounded-md" type="file" id="profileImage" name="profileImage" accept="image/*">
                </div>
                <button class="w-full py-2 px-4 bg-green-600 text-gray-50 rounded-md transition-transform transform hover:scale-110 hover:bg-green-600" type="submit">Register</button>
                <p class="my-4 text-center">Already have an account? <a href="login.php" class="text-green-600 underline">Login here</a></p>
                <?php if (isset($_GET['error'])) : ?>
                    <?php if ($_GET['error'] == 'usernameExists') : ?>
                        <p class="text-red-500 text-center">This username is already taken.</p>
                    <?php elseif ($_GET['error'] == 'emailExists') : ?>
                        <p class="text-red-500 text-center"">This email is already in use.</p>
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