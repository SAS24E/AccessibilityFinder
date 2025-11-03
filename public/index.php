
<!DOCTYPE html>
<html lang="en">

<?php
require_once __DIR__ . '/../application/controllers/post-controller.php';
require_once __DIR__ . '/../Database/database.php';


$database = new Database();
$db = $database->connect();

$controller = new PostController($db);
$posts = $controller->index();
?>

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🗺️</text></svg>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <!-- MapLibre CSS -->
    <link href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" rel="stylesheet" />
    <title>Accessiblity Finder</title>
</head>

<body>
    <header class="site-header">
        <h1>Accessiblity Finder</h1>
        <nav>
            <a class="site-navigation-button" href="../application/views/about-us.php">About Us</a>
            <a class="site-navigation-button" href="../application/views/profile-dashboard.php">Location Management</a>
            <a class ="site-navigation-button" href="../application/views/manage-posts.php">Manage Post</a>
            <a class ="site-navigation-button" href="../application/views/create-post-dashboard.php">Create Post</a>
        </nav>
    </header>

    <main>

        <?php session_start();
        // Display login/register or user info based on session (Good to see if user is logged in)
        if (isset($_SESSION['user_id'])) {
            // Show welcome message, profile link, and logout button for logged-in users
      echo "<p class='logged-in-bubble'>Welcome " . htmlspecialchars($_SESSION['user_name']) . " !";
      echo "<a class='site-navigation-button' href='../application/controllers/user-controller.php?action=profile'>Profile</a> ";
      echo "<a class='site-navigation-button' href='../application/controllers/user-controller.php?action=logout'>Logout</a></p>";
        } else {
            echo "<p style='text-align:right; margin-right:20px;'><a class='site-navigation-button' href='../application/views/login-dashboard.php'>Login</a> <a class='site-navigation-button' href='../application/views/register-dashboard.php'>Register</a></p>";
        }
        ?>

        <div class="post-container">
            <h2>Find Accessible Locations Near You!</h2>
            <p>Use the map below to explore accessible restaurants in your area. Click on the markers to see more details about each location.</p>
        </div>

        <div class="map-posts-wrapper">
        <!-- Map container: map.js initializes MapLibre on #map -->
        <div id="search-bar-container">
            <input type="text" id="search-input" placeholder="Search for accessible locations...">
            <button id="search-button">Search</button>
            <div id="map" class="map-container-home"></div>
        </div>

        <div class ="posts-container">
           <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['location_name']); ?></h2>
                <p><strong>Posted by:</strong> <?= htmlspecialchars($post['username']); ?></p>
                <p><?= nl2br(htmlspecialchars($post['opinion'])); ?></p>
                <p><strong>Assistance Friendly:</strong> <?= htmlspecialchars($post['assistance_friendly']); ?></p>
                <?php if (!empty($post['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($post['image']); ?>" alt="Post Image" width="200">
                <?php endif; ?>
                <p><em>Posted on <?= htmlspecialchars($post['created_at']); ?></em></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts available yet.</p>
    <?php endif; ?>
    </div>
    </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>
    <script type="module" src="map.js"></script>

    <!-- Welcome Popup -->
<div id="welcomePopup" class="popup-overlay">
  <div class="popup-box">
    <span class="close-btn" id="closePopup">&times;</span>
    <h2>Welcome to Accessibility Finder!</h2>
    <p>
      Your go-to platform for finding and sharing information about accessible restaurants.
      Whether you're a guest or a registered user, our platform is designed to help you
      discover dining options that cater to your accessibility needs.
    </p>
    <p>
      Explore our guest dashboard for basic search functionalities or sign up to access
      advanced features like creating and managing posts about your experiences at various restaurants.
    </p>
  </div>
</div>

<script>
  // Show popup on page load
  window.onload = function() {
    document.getElementById("welcomePopup").style.display = "flex";
  };

  // Close popup when clicking the X button
  document.getElementById("closePopup").onclick = function() {
    document.getElementById("welcomePopup").style.display = "none";
  };

  // Close popup when clicking outside the box
  window.onclick = function(event) {
    let popup = document.getElementById("welcomePopup");
    if (event.target === popup) {
      popup.style.display = "none";
    }
  };
</script>
</body>
</html>