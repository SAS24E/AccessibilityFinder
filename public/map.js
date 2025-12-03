import "https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js";

const middleOfUSA = [-100, 40];

// Try to get user location via IP geolocation
async function getLocation() {
  try {
    const response = await fetch("http://ip-api.com/json/");
    const json = await response.json();
    if (typeof json.lat === "number" && typeof json.lon === "number") {
      return [json.lon, json.lat];
    }
  } catch (error) {
    // ignore and fallback
  }
  return middleOfUSA;
}

// expose map and helpers at module scope
let map = null;
let searchMarker = null;

// Initialize the map and wire UI
async function init() {
  map = new maplibregl.Map({
    style: "https://tiles.openfreemap.org/styles/liberty",
    center: middleOfUSA,
    zoom: 2,
    container: "map",
  });

  // optional navigation controls
  map.addControl(new maplibregl.NavigationControl());

  const location = await getLocation();
  if (location !== middleOfUSA) {
    map.flyTo({ center: location, zoom: 8 });

    // Create a green marker for user location
    const userMarker = new maplibregl.Marker({ color: '#22c55e' })
      .setLngLat(location)
      .addTo(map);
    
    // Optional: Add popup that shows on click
    const userPopup = new maplibregl.Popup({ offset: 25 })
      .setHTML('<strong>📍 You are here</strong>');
    userMarker.setPopup(userPopup);
  }

  // wire search UI if present we will use search-input and search-button IDs to find them
  const input = document.getElementById('search-input');
  const btn = document.getElementById('search-button');
  if (btn && input) {
    btn.addEventListener('click', () => searchLocation(input.value));
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        searchLocation(input.value);
      }
    });
  }
}

// Search using Nominatim and show result on the map
// Nominatim usage policy: https://operations.osmfoundation.org/policies/nominatim/
async function searchLocation(query) {
  if (!query || query.trim().length === 0) {
    alert('Please enter a search term');
    return null;
  }

  // Use Nominatim (light use only). Limit results to 1.
  const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=${encodeURIComponent(
    query
  )}`;

  try {
    const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
    if (!res.ok) throw new Error('Geocoding request failed');
    const results = await res.json();
    if (!results || results.length === 0) {
      alert('Location not found');
      return null;
    }
  const { lat, lon, display_name, place_id, osm_id, osm_type } = results[0];
    const lng = parseFloat(lon);
    const latitude = parseFloat(lat);

    if (!map) {
      console.warn('Map not initialized yet');
      return { lat: latitude, lng };
    }

    map.flyTo({ center: [lng, latitude], zoom: 14 });

    // remove previous search marker
    if (searchMarker) searchMarker.remove();
    searchMarker = new maplibregl.Marker().setLngLat([lng, latitude]).addTo(map);
    const popup = new maplibregl.Popup().setHTML(`<strong>${display_name}</strong>`);
    searchMarker.setPopup(popup).togglePopup();

    // expose last search result (include nominatim/osm metadata)
    window.lastSearchResult = {
      lat: latitude,
      lng,
      display_name,
      nominatim_place_id: place_id ?? null,
      osm_id: osm_id ?? null,
      osm_type: osm_type ?? null,
    };
    return window.lastSearchResult;
  } catch (err) {
    console.error('Search failed', err);
    alert('Search failed. Check console for details.');
    return null;
  }
}

// expose to window for debugging/inline use
window.searchLocation = searchLocation;

// If the page includes the Add-by-Search UI elements, wire them here so
// the logic lives inside map.js and not in multiple views.
function wireAddBySearch() {
  // Prefer new, de-duplicated IDs used in the dashboard view. Fall back to old IDs for compatibility.
  const addBtn = document.getElementById('search-button-add') || document.getElementById('search-button');
  const addInput = document.getElementById('search-input-add') || document.getElementById('search-input');
  const status = document.getElementById('add-location-status');
  if (!addBtn || !addInput || !status) return;

  addBtn.addEventListener('click', async () => {
    status.textContent = 'Searching...';
    try {
      const result = await searchLocation(addInput.value);
      if (!result) {
        status.textContent = 'No result found';
        return;
      }
      status.textContent = 'Saving...';
      const payload = {
        name: result.display_name,
        address: result.display_name,
        latitude: result.lat,
        longitude: result.lng,
        nominatim_place_id: result.nominatim_place_id,
        osm_type: result.osm_type,
        osm_id: result.osm_id
      };

      // send to backend to save via the controller.
      const res = await fetch('../controllers/location-controller.php?action=createLocation', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      const json = await res.json();
      if (res.ok) {
        status.textContent = 'Saved (id ' + json.location_id + ')';
      } else {
        status.textContent = 'Save failed: ' + (json.error || json.message || res.statusText);
      }
    } catch (err) {
      console.error(err);
      status.textContent = 'Error: ' + err.message;
    }
  });
}

// try to wire add-by-search when DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', wireAddBySearch);
} else {
  wireAddBySearch();
}

// run init when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}

export { searchLocation };

