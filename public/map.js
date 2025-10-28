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

    new maplibregl.Popup({ closeOnClick: false })
      .setLngLat(location)
      .setHTML("<h3>You are around here!</h3>")
      .addTo(map);
  }

  // wire search UI if present
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
    const { lat, lon, display_name } = results[0];
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

    // expose last search result
    window.lastSearchResult = { lat: latitude, lng, display_name };
    return window.lastSearchResult;
  } catch (err) {
    console.error('Search failed', err);
    alert('Search failed. Check console for details.');
    return null;
  }
}

// expose to window for debugging/inline use
window.searchLocation = searchLocation;

// run init when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}

export { searchLocation };
