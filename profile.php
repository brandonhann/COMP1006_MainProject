<?php
session_start();
include("database.php");

if (!isset($_GET['username'])) {
    echo "<p>You must provide a username to view this page.</p>";
    echo "<a href='index.php'>Go back</a>";
    exit();
}

$username = $_GET['username'];

// fetch user details
$sql = "SELECT UserID, Username, Email, PostAmount, DATE_FORMAT(AccountCreationDate, '%d/%m/%Y') as AccountCreationDate FROM Users WHERE Username = ?";
$statement = $database->executeStatement($sql, [$username]);
$result = $statement->get_result();
if ($resultRow = $result->fetch_assoc()) {
    $user = $resultRow;
} else {
    echo "<p>User not found.</p>";
    exit();
}

// fetch the user's image
$sql = "SELECT Image FROM Users WHERE UserID = ?";
$statement = $database->executeStatement($sql, [$user['UserID']]);
$result = $statement->get_result();
if ($imageRow = $result->fetch_assoc()) {
    $userImage = $imageRow['Image'];
} else {
    $userImage = './storage/default.jpg';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // remove post
    $postID = $database->sanitize($_POST['postID']);

    // only allow deletion if current user is owner of post
    if (isset($_SESSION['username']) && $_SESSION['username'] == $username) {
        $sql = "DELETE FROM Posts WHERE PostID = ? AND UserID = ?";
        $database->executeStatement($sql, [$postID, $user['UserID']]);
    }

    header('Location: profile.php?username=' . $username);
    exit();
}

// fetch last 5 posts of the user
$sql = "SELECT * FROM Posts WHERE UserID = ? ORDER BY PostID DESC LIMIT 5";
$statement = $database->executeStatement($sql, [$user['UserID']]);
$posts = $statement->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Georgian Social is a social media website made by Brandon Hann">
    <title>Georgian Social - Home</title>
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
    <main class="flex-grow flex w-11/12 md:w-2/3 mx-auto flex-col md:flex-row justify-center mt-10 mb-20 md:mb-0">
        <!-- User Profile -->
        <div class="w-full md:w-1/3 p-4 bg-white bg-opacity-50 border border-gray-200 shadow-md rounded-md md:mr-4">
            <h2 class="text-xl text-center font-bold mb-4 text-gray-800">
                <?php echo htmlspecialchars($user['Username']); ?>
            </h2>
            <img src="<?php echo $userImage; ?>" alt="Profile Picture" class="w-48 h-48 mx-auto rounded-full object-cover mb-4">

            <!-- Image Upload Form -->
            <div class="mb-4 text-center">
                <label for="fileToUpload" class="block text-gray-700 font-medium mb-2">Select image to upload:</label>
                <form action="uploadImage.php" method="post" enctype="multipart/form-data" class="flex flex-col items-center">
                    <input type="file" name="fileToUpload" id="fileToUpload" class="border border-gray-300 rounded px-2 py-1 mb-2">
                    <input type="submit" value="Upload Image" name="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-4 rounded">
                </form>
            </div>

            <!-- Delete Image Form -->
            <div class="mb-4">
                <form action="deleteImage.php" method="post" class="flex justify-center">
                    <button type="submit" name="deleteImage" value="Delete" class="bg-red-500 hover:bg-red-600 text-white py-1 px-4 rounded">Delete Image</button>
                </form>
            </div>

            <p class="text-center text-gray-600 mb-4">
                Joined: <span class="font-bold"><?php echo $user['AccountCreationDate']; ?></span>
            </p>
            <p class="text-center text-gray-600 mb-4">
                Total posts: <span class="font-bold"><?php echo $user['PostAmount']; ?></span>
            </p>
            <p class="text-center text-gray-600 mb-4">
                Email: <span class="font-bold"><?php echo $user['Email']; ?></span>
            </p>
            <!-- Go back button -->
            <div class="flex justify-center mt-4">
                <a href="app.php" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">Go back</a>
            </div>
        </div>

        <!-- User's Posts -->
        <div class="flex-grow mt-4 md:mt-0">
            <!-- Feed content -->
            <div id="feedContent" class="space-y-4">
                <!-- fetch posts from database and display them -->
                <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
                    <div class="p-4 bg-white bg-opacity-50 border border-gray-200 shadow-md rounded-md">
                        <p class="text-gray-600">
                            <i class="fas fa-quote-left text-gray-400 pr-2"></i>
                            <?php echo $post['ContentText']; ?>
                            <i class="fas fa-quote-right text-gray-400 pl-2"></i>
                        </p>
                        <?php if (isset($_SESSION['username']) && $_SESSION['username'] == $username) : ?>
                            <form id="deletePostForm<?php echo $post['PostID']; ?>" method="POST">
                                <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>" />
                                <button type="submit" class="text-red-500 hover:text-red-600" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
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