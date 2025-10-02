<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/styles.css">
    <title>Accessiblity Finder</title>
</head>
<body>

</body>
    <header class ="site-header">
        <h1>Accessiblity Finder</h1>
        <nav>
            <a class="site-navigation-button" href="../application/views/guest-dashboard.php">Guest Dashboard</a>
            <a class="site-navigation-button" href="../application/views/user-dashboard.php">User Dashboard</a>
        </nav>
    </header>

    <main>
        <!-- Login Form -->
        <div class="login-container">
            <form action="../controllers/user-controller.php?action=login" method="POST">
                <!-- Username field added -->
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <!-- Email field added -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <!-- Password field added -->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="Login">
            </form>
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>

