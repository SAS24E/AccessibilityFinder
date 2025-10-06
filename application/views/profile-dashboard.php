<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/styles.css">
    <title>Your Profile</title>
</head>
<body>
    <header class="site-header">
        <h1>Accessibility Finder</h1>
        <nav>
            <!-- Link to the Home page (index.php) and link to perform a logout -->
            <a class="site-navigation-button" href="../../public/index.php">Home</a>
            <a class="site-navigation-button" href="../controllers/user-controller.php?action=logout">Logout</a>
        </nav>
    </header>

    <main>
        <!-- Welcome message for the user-->
        <h2>Your Profile</h2>
        <p class="text-box">Welcome to your profile page! Here you can view and manage your account details.</p>
        <!-- Display user information -->
        <div class="profile-card">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user->name ?? ''); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user->email ?? ''); ?></p>
            <p> More profile features soon to come!</p>
            <!-- Add other fields as needed -->
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>
</body>
</html>