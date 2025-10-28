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
        <div class = "bio-section">
            <!-- The displaying biography -->
            <div class = "bio-display" id="bioDisplay">
                <p><strong>Biography:</strong></p>
                <p><?php echo htmlspecialchars($user->biography ?? ''); ?></p>
                <button type="button" onclick="toggleBioEdit()">Edit Bio</button>
            </div>

        <!-- Edit mode (hidden by default) -->
        <div class="bio-edit" id="bioEdit" style="display: none;">
            <form method="POST" action="../controllers/user-controller.php?action=updateBio">
                <p><strong>Edit Bio:</strong></p>
                <textarea name="biography" rows="5" cols="50" maxlength="500"><?php echo htmlspecialchars($user->biography ?? ''); ?></textarea>
                <br>
                <button type="submit">Save Changes</button>
                <button type="button" onclick="toggleBioEdit()">Cancel</button>
            </form>
        </div>
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>
<script>
    function toggleBioEdit() {
        const displayDiv = document.getElementById('bioDisplay');
        const editDiv = document.getElementById('bioEdit');
        
        if (displayDiv.style.display === 'none') {
            displayDiv.style.display = 'block';
            editDiv.style.display = 'none';
        } else {
            displayDiv.style.display = 'none';
            editDiv.style.display = 'block';
        }
    }
</script>
</body>
</html>