
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🗺️</text></svg>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Accessiblity Finder</title>
</head>
<body>
    <header class ="site-header">
        <h1>Accessiblity Finder</h1>
        <nav>
            <a class="site-navigation-button" href="../application/views/guest-dashboard.php">Guest Dashboard</a>
            <a class="site-navigation-button" href="../application/views/user-dashboard.php">User Dashboard</a>
        </nav>
    </header>

    <main>

    <?php session_start();
      // Display login/register or user info based on session (Good to see if user is logged in)
        if(isset($_SESSION['user_id'])) {
            // Show welcome message, profile link, and logout button for logged-in users
            echo "<p class='logged-in-bubble'>Welcome " . htmlspecialchars($_SESSION['user_name']) . " !";
            echo "<a class='site-navigation-button' href='../application/controllers/user-controller.php?action=profile'>Profile</a> ";
            echo "<a class='site-navigation-button' href='../application/controllers/user-controller.php?action=logout'>Logout</a></p>";
        } else {
            echo "<p style='text-align:right; margin-right:20px;'><a class='site-navigation-button' href='../application/views/login-dashboard.php'>Login</a> <a class='site-navigation-button' href='../application/views/register-dashboard.php'>Register</a></p>";
        }
    ?>
        
            <h2>Welcome to Accessibility Finder!</h2>
        <div class="text-box">
            <p>Your go-to platform for finding and sharing information about accessible restaurants. Whether you're a guest or a registered user, our platform is designed to help you discover dining options that cater to your accessibility needs.</p>
            <p>Explore our guest dashboard for basic search functionalities or sign up to access advanced features like creating and managing posts about your experiences at various restaurants.</p>
        </div>
        <div class="map-container">
        <link href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" rel="stylesheet" />
        <div>
        <!-- <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1748&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="World Map" style="width:100%;max-width:1200px;margin-top:20px; display:block; margin-left:auto; margin-right:auto;"> -->
    </main>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>
    <script type="module" src="map.js"></script>
</body>
</html>

