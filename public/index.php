<!DOCTYPE html>
<html lang="en">

<?php
// ==========================
// REQUIRE CONTROLLERS & DATABASE
// ==========================
session_start();
require_once __DIR__ . '/../application/controllers/post-controller.php';
require_once __DIR__ . '/../database/database.php'; // 

// ==========================
// INIT DATABASE & CONTROLLER
// ==========================
$database = new Database();
$db = $database->connect();
$controller = new PostController($db);
$posts = $controller->index();
?>

<head>
  <meta charset="UTF-8">
  <link rel="icon"
    href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🗺️</text></svg>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- MapLibre CSS -->
  <link href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" rel="stylesheet" />

  <title>Accessibility Finder</title>
</head>

<body>
  <header class="site-header">
    <div class="header-content">
      <h1>Accessibility Finder</h1>
      <nav class="header-nav">
        <a class="site-navigation-button" href="../application/views/about-us.php">About Us</a>
        <?php
        // ==========================
        // SESSION-BASED LOGIN CHECK IN HEADER
        // ==========================
        if (isset($_SESSION['user_id'])) {
          echo "<span class='header-welcome'>Welcome " . htmlspecialchars($_SESSION['user_name']) . "!</span>";
          echo "<a class='site-navigation-button' href='../application/controllers/user-controller.php?action=profile'>Profile</a>";
          echo "<a class='site-navigation-button' href='../application/controllers/user-controller.php?action=logout'>Logout</a>";
        } else {
          echo "<a class='site-navigation-button' href='../application/views/login-dashboard.php'>Login</a>";
          echo "<a class='site-navigation-button' href='../application/views/register-dashboard.php'>Register</a>";
        }
        ?>
      </nav>
    </div>
  </header>

  <main>
    <div class="map-posts-wrapper">
      <!-- Map container: main map -->
      <div id="search-bar-container">
        <input type="text" id="search-input" placeholder="Search for accessible locations...">
        <button id="search-button">Search</button>
        <div id="map" class="map-container-home"></div>
      </div>

      <!-- Posts Section with Create Button -->
      <div class="posts-section">
        <!-- Create Post Trigger (Only for logged-in users) -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="text-center mb-3">
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#createPostModal">
              + Create Post
            </button>
          </div>
        <?php endif; ?>

        <!-- =================== -->
        <!-- Display All Posts -->
        <!-- =================== -->
        <div class="posts-container">
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

      <!-- ===================================== -->
      <!-- Bootstrap Modal: Create New Post Form -->
      <!-- ===================================== -->
      <?php if (isset($_SESSION['user_id'])): ?>
      <div class="modal fade" id="createPostModal" tabindex="-1" role="dialog" aria-labelledby="createPostTitle"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">

            <div class="modal-header">
              <h5 class="modal-title" id="createPostTitle">Create a New Post</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <!-- FORM: Create New Post -->
              <form id="postForm" action="../application/controllers/post-controller.php?action=create" method="POST"
                enctype="multipart/form-data">

                <!-- =================== -->
                <!-- Location Search via Nominatim -->
                <!-- =================== -->
                <div class="form-group">
                  <label for="locationSearch">Location:</label>
                  <div class="input-group">
                    <input type="text" id="locationSearch" class="form-control"
                      placeholder="Search for a location..." required>
                    <div class="input-group-append">
                      <button type="button" id="searchLocationBtn" class="btn btn-primary">Search</button>
                    </div>
                  </div>
                  <small class="form-text text-muted" id="locationStatus">Type and press Search to find locations</small>
                </div>

                <!-- Search Results -->
                <div id="searchResults" class="mb-3" style="max-height: 200px; overflow-y: auto;"></div>

                <!-- Map Container for Modal -->
                <div id="modalMap" style="width: 100%; height: 400px; border-radius: 8px; margin-bottom: 20px; display: none;"></div>

                <!-- Hidden Fields -->
                <input type="hidden" id="latitude" name="latitude" required>
                <input type="hidden" id="longitude" name="longitude" required>
                <input type="hidden" id="location_name" name="location_name" required>

                <!-- =================== -->
                <!-- Post Details -->
                <!-- =================== -->
                <div class="form-group">
                  <label for="userOpinion">Your Opinion:</label>
                  <textarea class="form-control" id="userOpinion" name="opinion" rows="4" required></textarea>
                </div>

                <div class="form-group">
                  <label>Assistance Friendly:</label>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="assistance_friendly" id="assistanceYes" value="yes" required>
                    <label class="form-check-label" for="assistanceYes">Yes</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="assistance_friendly" id="assistanceNo" value="no" required>
                    <label class="form-check-label" for="assistanceNo">No</label>
                  </div>
                </div>

                <div class="form-group">
                  <label for="postImage">Upload Image:</label>
                  <input type="file" class="form-control-file" id="postImage" name="image">
                </div>
              </form>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" id="submitPostBtn" class="btn btn-primary">Submit Post</button>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </main>

  <footer class="site-footer">
    <p>&copy; 2025 AccessibilityFinder | Bit by Bit Team</p>
  </footer>

  <!-- ===================================== -->
  <!-- JAVASCRIPT INCLUDES (proper order) -->
  <!-- ===================================== -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>

  <!-- Main homepage map -->
  <script type="module" src="map.js"></script>

  <!-- ===================================== -->
  <!-- MODAL: LOCATION SEARCH & POST SUBMIT -->
  <!-- ===================================== -->
  <script>
    let modalMap, modalMarkers = [], searchResults = [];
    const $ = (id) => document.getElementById(id);
    const status = (text, color) => Object.assign($('locationStatus'), { className: `form-text text-${color}`, textContent: text });
    const clearMarkers = () => (modalMarkers.forEach(m => m.remove()), modalMarkers = []);
    
    const initMap = () => modalMap || (
      $('modalMap').style.display = 'block',
      modalMap = new maplibregl.Map({
        container: 'modalMap',
        style: 'https://tiles.openfreemap.org/styles/liberty',
        center: [-79.3832, 43.6532],
        zoom: 10
      }).addControl(new maplibregl.NavigationControl())
    );

    const addMarker = (r, i, color = '#007bff') => modalMarkers.push(
      new maplibregl.Marker({ color })
        .setLngLat([+r.lon, +r.lat])
        .setPopup(new maplibregl.Popup().setHTML(
          color === '#007bff' 
            ? `<strong>${r.display_name}</strong><br><button onclick="selectLocation(${i})" class="btn btn-sm btn-primary mt-2">Select</button>`
            : `<strong>✓ Selected</strong><br>${r.display_name}`
        ))
        .addTo(modalMap)
    );

    async function searchLocationInModal() {
      const query = $('locationSearch').value.trim();
      if (!query) return status('Please enter a location', 'danger');

      status('Searching...', 'info');
      $('searchResults').innerHTML = '';
      clearMarkers();

      try {
        searchResults = await (await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5`)).json();
        if (!searchResults.length) return status('No results found', 'warning');

        status('Click a result or marker to select:', 'muted');
        $('searchResults').innerHTML = `<div class="list-group">${searchResults.map((r, i) => 
          `<button type="button" class="list-group-item list-group-item-action" onclick="selectLocation(${i})">${r.display_name}</button>`
        ).join('')}</div>`;

        initMap();
        const bounds = new maplibregl.LngLatBounds();
        searchResults.forEach((r, i) => (addMarker(r, i), bounds.extend([+r.lon, +r.lat])));
        modalMap.fitBounds(bounds, { padding: 50, maxZoom: 15 });
      } catch {
        status('Error searching', 'danger');
      }
    }

    window.selectLocation = (i) => {
      const r = searchResults[i];
      [['latitude', r.lat], ['longitude', r.lon], ['location_name', r.display_name]].forEach(([id, val]) => $(id).value = val);
      status(`✓ Selected: ${r.display_name}`, 'success');
      $('searchResults').innerHTML = '';
      clearMarkers();
      addMarker(r, i, '#28a745');
      modalMap.flyTo({ center: [+r.lon, +r.lat], zoom: 14 });
    };

    $('submitPostBtn')?.addEventListener('click', async function() {
      const form = $('postForm');
      if (!form.checkValidity()) return form.reportValidity();

      const [name, lat, lon] = ['location_name', 'latitude', 'longitude'].map(id => $(id).value);
      if (!name || !lat || !lon) return alert('Please select a location first');

      Object.assign(this, { disabled: true, textContent: 'Creating...' });

      try {
        const data = await (await fetch('../application/controllers/location-controller.php?action=createLocation', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, latitude: lat, longitude: lon, address: name })
        })).json();

        data.location_id 
          ? (Object.assign(form.appendChild(document.createElement('input')), { type: 'hidden', name: 'location_id', value: data.location_id }), form.submit())
          : (alert('Error: ' + (data.error || 'Unknown')), Object.assign(this, { disabled: false, textContent: 'Submit Post' }));
      } catch {
        alert('Error submitting post');
        Object.assign(this, { disabled: false, textContent: 'Submit Post' });
      }
    });

    document.addEventListener('DOMContentLoaded', () => {
      $('searchLocationBtn')?.addEventListener('click', searchLocationInModal);
      $('locationSearch')?.addEventListener('keypress', e => e.key === 'Enter' && (e.preventDefault(), searchLocationInModal()));
    });

    $('#createPostModal').on('hidden.bs.modal', () => {
      ['locationSearch', 'latitude', 'longitude', 'location_name'].forEach(id => $(id).value = '');
      $('searchResults').innerHTML = '';
      status('Type and press Search to find locations', 'muted');
      Object.assign($('submitPostBtn'), { disabled: false, textContent: 'Submit Post' });
      clearMarkers();
      modalMap && ($('modalMap').style.display = 'none');
    });
  </script>

  <!-- ===================================== -->
  <!-- WELCOME POPUP -->
  <!-- ===================================== -->
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
    // ==============================
    // WELCOME POPUP FUNCTIONALITY
    // ==============================
    window.onload = function () {
      if (!localStorage.getItem('welcomeShown')) {
        document.getElementById("welcomePopup").style.display = "flex";
      }
    };
    document.getElementById("closePopup").onclick = function () {
      document.getElementById("welcomePopup").style.display = "none";
      localStorage.setItem('welcomeShown', 'true');
    };
    window.onclick = function (event) {
      let popup = document.getElementById("welcomePopup");
      if (event.target === popup) {
        popup.style.display = "none";
        localStorage.setItem('welcomeShown', 'true');
      }
    };
  </script>

</body>
</html>
