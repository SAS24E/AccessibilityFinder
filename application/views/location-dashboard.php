<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/styles.css">
    <link href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" rel="stylesheet" />
    <title>Location Management</title>
</head>

<body>
    <header class="site-header">
        <h1>Accessiblity Finder</h1>
        <nav>
            <a class="site-navigation-button" href="../../public/index.php">Home</a>
        </nav>
    </header>
    <div style = "flex-direction : row; justify-content : space-around; display: flex;">

        <div class="location-form">
            <h2>Add New Location by Search</h2>
            <input type="text" id="search-input-add" placeholder="Search for a location to add to db">
            <button id="search-button-add">Search Location</button>
            <div id="add-location-status"></div>
        </div>

        <div class="location-form">
            <h2>Search Locations</h2>
            <input type="text" id="search-input-lookup" placeholder="Enter location name">
            <button id="search-button-lookup">Search</button>
            <div id="search-results"></div>
        </div>

        
        <div class="location-form">
            <h2>Delete Location</h2>
            <input type="text" id="delete-location-id" placeholder="Enter location to delete from db">
            <button id="delete-location-button">Delete Location</button>
            <div id="delete-status"></div>
        </div>
    </div>
    <div id="map" class="map-container-location-dashboard"></div>

    <footer class="site-footer">
        <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
    </footer>
    <script type="module" src="../../public/map.js"></script>
</body>

</html>