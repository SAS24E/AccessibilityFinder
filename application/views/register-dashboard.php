<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/styles.css">
    <title>Accessiblity Finder</title>
</head>
    <body>
        <header class ="site-header">
            <h1>Accessiblity Finder</h1>
            <nav>
                <a class="site-navigation-button" href="../../public/index.php">Home</a>
            </nav>
        </header>

        <main>
            <div>
                <h2>Create Your Account</h2>
                <p class='text-box'>Please fill in the details below to create your account. If you already have an account, you can <a href="login-dashboard.php">log in here</a>.</p>
            </div>
            
            <?php
        // Display error messages if present
        if (isset($_GET['error'])) {
            $errors = explode(',', $_GET['error']);
            
            foreach ($errors as $error) {
                if ($error === 'username_exists') {
                    echo "<p style='color:red; text-align:center;'>This username is already taken. Please choose a different username.</p>";
                } elseif ($error === 'email_exists') {
                    echo "<p style='color:red; text-align:center;'>This email address is already registered. Please use a different email or <a href='login-dashboard.php'>log in</a>.</p>";
                } elseif ($error === 'registration_failed' || $error === 'database_error') {
                    echo "<p style='color:red; text-align:center;'>Registration failed. Please try again.</p>";
                }
            }
        }
        ?>
            
            <!-- Registration Form -->
            <div class="login-container">
                <form action="../controllers/user-controller.php?action=register" method="POST">
                    <!-- Username field added -->
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <!-- Email field added -->
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <!-- Password field added -->
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <input type="submit" value="register">
                </form>
            </div>
        </main>

        <footer class="site-footer">
            <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
        </footer>
    </body>
</html>