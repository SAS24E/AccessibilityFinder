import "https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js";

const middleOfUSA = [-100, 40];

async function getLocation() {
  try {
    const response = await fetch("http://ip-api.com/json/");
    const json = await response.json();
    if (typeof json.lat === "number" && typeof json.lon === "number") {
      return [json.lon, json.lat];
    }
  } catch (error) {}
  return middleOfUSA;
}
// initialize the map
async function init() {
  const map = new maplibregl.Map({
    style: "https://tiles.openfreemap.org/styles/liberty",
    center: middleOfUSA,
    zoom: 2,
    container: "map",
  });

  const location = await getLocation();
  if (location !== middleOfUSA) {
    map.flyTo({ center: location, zoom: 8 });

    new maplibregl.Popup({
      closeOnClick: false,
    })
      .setLngLat(location)
      .setHTML("<h3>You are around here!</h3>")
      .addTo(map);
  }

  // save location to server by posting JSON data
  async function saveLocationToServer(loc) {
    // use forward slashes and include the action parameter expected by your controller
    const url = "application/controllers/location-controller.php?action=createLocation";
    const res = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(loc),
    });

    // try to parse JSON; provide helpful fallback
    const json = await res.json().catch(() => ({ error: "Invalid JSON response from server" }));
    if (!res.ok) throw new Error(json.error || JSON.stringify(json));
    return json; // expected to contain at least the new id
  }

}

// run the initializer
init();
