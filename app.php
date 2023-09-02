<?php
session_start();
include("database.php");

if (!isset($_SESSION['username'])) {
    echo "<p>You must be logged in to view this page.</p>";
    echo "<a href='index.php'>Go back</a>";
    exit();
}

$username = $_SESSION['username'];

// fetch user ID
$sql = "SELECT UserID FROM Users WHERE Username = ?";
$statement = $database->executeStatement($sql, [$username]);
$result = $statement->get_result();
if ($resultRow = $result->fetch_assoc()) {
    $userID = $resultRow['UserID'];
} else {
    echo "<p>Failed to fetch UserID.</p>";
    exit();
}

// fetch user's image path
$sql = "SELECT Posts.*, Users.Username, Users.Image AS UserProfileImage FROM Posts JOIN Users ON Posts.UserID = Users.UserID ORDER BY PostID DESC LIMIT 5";
$statement = $database->executeStatement($sql);
$result = $statement->get_result();
if ($imageRow = $result->fetch_assoc()) {
    $userImagePath = $imageRow['UserProfileImage'];
} else {
    $userImagePath = './storage/default.jpg';
}

// check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $database->sanitize($_POST['postContent']);

    // execute insert statement
    $sql = "INSERT INTO Posts (ContentText, UserID) VALUES (?, ?)";
    $database->executeStatement($sql, [$content, $userID]);

    // increment the PostAmount for user
    $sql = "UPDATE Users SET PostAmount = PostAmount + 1 WHERE UserID = ?";
    $database->executeStatement($sql, [$userID]);

    header('Location: app.php');
    exit();
}


// fetch last 5 posts from database
$sql = "SELECT Posts.*, Users.Username, Users.Image AS UserProfileImage FROM Posts JOIN Users ON Posts.UserID = Users.UserID ORDER BY PostID DESC LIMIT 5";
$statement = $database->executeStatement($sql);
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
        <!-- Sidebar -->
        <div class="w-full md:w-1/3 p-4 bg-white bg-opacity-50 border border-gray-200 shadow-md rounded-md md:mr-4">
            <h2 class="text-xl text-center font-bold mb-4 text-gray-800">
                Hello👋, <?php echo htmlspecialchars($username); ?>!
            </h2>
            <?php
            // fetch PostAmount for the current user
            $sql = "SELECT PostAmount FROM Users WHERE UserID = ?";
            $statement = $database->executeStatement($sql, [$userID]);
            $result = $statement->get_result();
            $postAmount = $result->fetch_assoc()['PostAmount'];
            ?>
            <p class="text-center text-gray-600 mb-4">
                Total posts: <span class="font-bold"><?php echo $postAmount; ?></span>
            </p>
            <nav class="flex flex-col space-y-2">
                <a href="profile.php?username=<?php echo htmlspecialchars($username); ?>" class="text-green-600 hover:text-green-800 bg-white border border-green-600 px-2 py-1 rounded flex items-center justify-center space-x-2">
                    <i class="fas fa-user-circle text-lg"></i>
                    <span class="text-lg">My Profile</span>
                </a>
                <a href="#" class="text-green-600 hover:text-green-800 bg-white border border-green-600 px-2 py-1 rounded flex items-center justify-center space-x-2">
                    <i class="fas fa-globe-americas text-lg"></i>
                    <span class="text-lg">Explore</span>
                </a>
                <a href="#" class="text-green-600 hover:text-green-800 bg-white border border-green-600 px-2 py-1 rounded flex items-center justify-center space-x-2">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="text-lg">Notifications</span>
                </a>
            </nav>
        </div>

        <!-- Feed -->
        <div class="flex-grow mt-4 md:mt-0">
            <div class="p-4 bg-white bg-opacity-50 border border-gray-200 shadow-md rounded-md mb-4">
                <form id="postForm" method="POST">
                    <textarea class="w-full p-2 border border-gray-200 rounded-md bg-white text-gray-800 placeholder-gray-600" placeholder="What's on your mind?" id="postContent" name="postContent" required></textarea>
                    <button class="flex items-center gap-1 mx-auto py-2 px-8 mt-2 bg-green-500 text-white rounded-md transition-transform transform hover:scale-110" type="submit">Post <i class="fa-sharp fa-solid fa-pen-to-square"></i></button>
                </form>
            </div>

            <!-- Feed content -->
            <div id="feedContent" class="space-y-4">
                <!-- fetch posts from database & display it -->
                <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
                    <div class="p-4 bg-white bg-opacity-50 border border-gray-200 shadow-md rounded-md">
                        <div class="flex justify-between">
                            <div class="flex">
                                <img class="h-12 w-12 rounded-full object-cover" src="<?php echo $post['UserProfileImage']; ?>" alt="Profile Picture of <?php echo htmlspecialchars($post['Username']); ?>">
                                <div class="ml-4">
                                    <h3 class="text-md font-bold text-blue-500">
                                        <a href="profile.php?username=<?php echo htmlspecialchars($post['Username']); ?>" class="hover:underline">
                                            <?php echo htmlspecialchars($post['Username']); ?>
                                        </a>
                                    </h3>
                                    <p class="text-gray-600">
                                        <i class="fas fa-quote-left text-gray-400 pr-2"></i>
                                        <?php echo $post['ContentText']; ?>
                                        <i class="fas fa-quote-right text-gray-400 pl-2"></i>
                                    </p>
                                </div>
                            </div>
                            <?php if ($post['UserID'] == $userID) : ?>
                                <form id="deletePostForm<?php echo $post['PostID']; ?>" method="POST" action="deletePost.php">
                                    <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>" />
                                    <button type="submit" class="text-red-500 hover:text-red-600" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
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