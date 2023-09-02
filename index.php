<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Georgian Social is a social media website made by Brandon Hann">
    <title>Georgian Social</title>
    <link rel="icon" type="image/x-icon" href="./img/favicon-32x32.png">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="flex flex-col min-h-screen bg2">

    <!-- Navigation -->
    <?php
    include 'components/navigation.php';
    ?>

    <!-- Banner Section -->
    <header class="text-gray-900 text-center">
        <div class="banner">
            <img class="banner-image" src="./img/banner1.jpg">
            <img class="banner-image" src="./img/banner2.jpg">
            <img class="banner-image" src="./img/banner3.jpg">
        </div>

        <div class="py-8 px-2 md:px-0">
            <h1 class="text-4xl font-bold mb-4">Welcome to Georgian Social!</h1>
            <p class="text-xl">Your digital platform to connect, share and learn at Georgian College. Stay updated and
                create lasting connections.</p>
        </div>
    </header>


    <main class="w-full text-gray-900 flex-grow container mx-auto px-2 md:px-0">

        <!-- Features Section -->
        <section class="py-16 text-center">
            <div class="container mx-auto px-6 flex flex-wrap">
                <!-- Feature 1 (Connect) -->
                <div class="w-full md:w-1/3 p-6 flex flex-col items-center">
                    <div class="w-24 h-24 mb-6 rounded-full bg-gray-200 flex items-center justify-center shadow">
                        <i class="fas fa-user-friends fa-3x"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Connect</h3>
                    <p>Easily find and connect with friends, teachers and colleagues.</p>
                </div>
                <!-- Feature 2 (Post) -->
                <div class="w-full md:w-1/3 p-6 flex flex-col items-center">
                    <div class="w-24 h-24 mb-6 rounded-full bg-gray-200 flex items-center justify-center shadow">
                        <i class="fas fa-pencil-alt fa-3x"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Post</h3>
                    <p>Create posts to share your thoughts, life events, or latest work.</p>
                </div>
                <!-- Feature 3 (Share) -->
                <div class="w-full md:w-1/3 p-6 flex flex-col items-center">
                    <div class="w-24 h-24 mb-6 rounded-full bg-gray-200 flex items-center justify-center shadow">
                        <i class="fas fa-share-alt fa-3x"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Share</h3>
                    <p>Share interesting posts, news, or content found across the web.</p>
                </div>
            </div>
        </section>

        <!-- App Showcase -->
        <section class="py-8 md:py-16">
            <div class="container mx-auto px-6 flex flex-wrap items-center">
                <div class="w-full md:w-1/2">
                    <img class="showcase mx-auto shadow-lg rounded-md" src="./img/showcase.jpg" />
                </div>
                <div class="w-full md:w-1/2 text-center md:text-left p-6">
                    <h2 class="text-4xl font-bold mb-4">Experience the best of Georgian College</h2>
                    <p class="text-xl">With Georgian Social, you can easily stay connected with your peers, share
                        experiences, collaborate on projects, and be part of an engaging academic community.</p>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="text-center py-16">
            <h2 class="text-4xl font-bold mb-4">Join Our Community!</h2>
            <p class="text-xl mb-8">Ready to get started? Register now and join the growing community of Georgian
                Social.</p>
            <a href="register.php" class="bg-green-600 text-green-50 py-2 px-4 rounded transition-transform transform hover:scale-110 inline-flex items-center justify-center">Register
                Now</a>
        </section>

    </main>

    <!-- Footer -->
    <?php
    include 'components/footer.php';
    ?>

    <script type="text/javascript" src="./js/banner.js"></script>
</body>

</html>