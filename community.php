<?php
session_start();
include("database.php");

if (!isset($_SESSION['username'])) {
    echo "<p>You must be logged in to view this page.</p>";
    echo "<a href='index.php'>Go back</a>";
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT Username, PostAmount, AccountCreationDate FROM Users";
$statement = $database->executeStatement($sql);
$users = $statement->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Georgian Social is a social media website made by Brandon Hann">
    <title>Georgian Social - Community</title>
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
        <!-- Community Table -->
        <div class="w-full p-4 bg-white bg-opacity-50 border border-gray-200 shadow-md rounded-md md:mr-4">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Username</th>
                        <th class="px-4 py-2">Post Amount</th>
                        <th class="px-4 py-2">Account Creation Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                        <tr>
                            <td class="border px-4 py-2">
                                <a href="profile.php?username=<?php echo htmlspecialchars($user['Username']); ?>" class="text-blue-500 hover:underline">
                                    <?php echo htmlspecialchars($user['Username']); ?>
                                </a>

                            </td>
                            <td class="border px-4 py-2"><?php echo $user['PostAmount']; ?></td>
                            <td class="border px-4 py-2"><?php echo $user['AccountCreationDate']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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