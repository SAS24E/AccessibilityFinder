<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles.css">
    <title>Accessiblity Finder</title>
</head>

<body>
    <header class="site-header">
        <h1>Accessiblity Finder</h1>
        <nav>
            <a class="site-navigation-button" href="../../index.php">Home</a>
        </nav>
    </header>

    <main>
        <div>
            <h2>Login to Your Account</h2>
            <p class='text-box'>If you already have an account, please log in below. If you don't have an account yet,
                you can <a href="register-dashboard.php">register here</a>.</p>
        </div>

        <!-- Login Form -->
        <div class="form-container">
            <?php if (isset($_GET['error'])): ?>
                    <p style="color:red; text-align: center;">Invalid user credentials. Please try again.</p>
            <?php endif; ?>

            <form action="../controllers/user-controller.php?action=login" method="POST">
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
</body>

</html>