<nav class="fixed w-full z-50 bg-gray-700 py-4 px-4 md:px-8 flex justify-between items-center">
    <a href="index.php" class="flex items-center gap-3">
        <img class="w-8 h-8" width="128px" height="128px" src="./img/logo.png" />
        <span class="block md:hidden spacing-2 text-gray-50 text-2xl font-bold">GS</span>
        <span class="montserrat text-gray-50 text-2xl font-bold hidden md:block">Georgian Social</span>
    </a>
    <div class="space-x-4">
        <?php if (isset($_SESSION['username'])) : ?>
            <span class="text-gray-50">Hello,
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
            <a href="app.php" class="bg-green-600 text-green-50 py-2 px-4 rounded transition-transform transform hover:scale-110 inline-flex items-center justify-center">Enter</a>
        <?php else : ?>
            <a href="login.php" class="text-gray-50">Login</a>
            <a href="register.php" class="bg-green-600 text-green-50 py-2 px-4 rounded transition-transform transform hover:scale-110 inline-flex items-center justify-center">Register</a>
        <?php endif; ?>
    </div>
</nav>