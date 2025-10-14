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

async function init() {
  const map = new maplibregl.Map({
    style: "/styles/dark.json",
    // style: "https://tiles.openfreemap.org/styles/liberty",
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
      .setHTML("<h3>You are approximately here!</h3>")
      .addTo(map);
  }
}

init();
