<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
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
    <?php session_start(); 
            # allow users to register and login
        if(isset($_SESSION['user_id'])) {
            echo "<p style='text-align:right; margin-right:20px;'>Logged in as User ID: " . htmlspecialchars($_SESSION['user_id']) . " | <a href='../application/controllers/logout.php'>Logout</a></p>";
        } else {
            echo "<p style='text-align:right; margin-right:20px;'><a href='../application/views/login.php'>Login</a> | <a href='../application/views/register.php'>Register</a></p>";
        }
    ?>
        <h2>Welcome to Accessibility Finder!</h2>
    <div>
        <form action="" method="get">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <input type="submit" value="Login">
        </form>
    </div>


    </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>

