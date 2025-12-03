<?php

require_once __DIR__ . '/../models/location.php';
require_once __DIR__ . '/../../Database/database.php';
// LocationController.php
class LocationController
{
    private $locationModel;

    public function __construct()
    {
        $db = new Database();
        $conn = $db->connect();
        $this->locationModel = new LocationModel($conn);
    }

    public function createLocationBySearch()
    {
        header('Content-Type: application/json');
        $raw = file_get_contents("php://input");
        $data = json_decode($raw, true);

        // Accept either the JS search result shape ({lat, lng, display_name})
        // or a more explicit payload ({latitude, longitude, name, address}).
        $payload = [
            'name' => null,
            'address' => null,
            'latitude' => null,
            'longitude' => null,
            'nominatim_place_id' => null,
            'osm_type' => null,
            'osm_id' => null,
        ];

        if (is_array($data)) {
            // Nominatim client shape
            if (isset($data['lat']) && isset($data['lng'])) {
                $payload['name'] = $data['display_name'] ?? ($data['name'] ?? null);
                $payload['address'] = $data['display_name'] ?? ($data['address'] ?? null);
                $payload['latitude'] = $data['lat'];
                $payload['longitude'] = $data['lng'];
                $payload['nominatim_place_id'] = $data['nominatim_place_id'] ?? null;
                $payload['osm_type'] = $data['osm_type'] ?? null;
                $payload['osm_id'] = $data['osm_id'] ?? null;
            } else {
                // explicit shape
                $payload['name'] = $data['name'] ?? null;
                $payload['address'] = $data['address'] ?? null;
                $payload['latitude'] = $data['latitude'] ?? null;
                $payload['longitude'] = $data['longitude'] ?? null;
                $payload['nominatim_place_id'] = $data['nominatim_place_id'] ?? null;
                $payload['osm_type'] = $data['osm_type'] ?? null;
                $payload['osm_id'] = $data['osm_id'] ?? null;
            }
        }

        // Basic validation
        if (empty($payload['name']) || !isset($payload['latitude']) || !isset($payload['longitude'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input - name, latitude and longitude are required']);
            return;
        }

        // Call the model method that exists in LocationModel
        $locationId = $this->locationModel->createLocationBySearch($payload);
        if ($locationId) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Location created successfully', 'location_id' => (int) $locationId]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create location']);
        }

    }

}

// simple routing logic
if (isset($_GET['action'])) {
    $controller = new LocationController();
    if ($_GET['action'] === 'createLocation') {
        $controller->createLocationBySearch();
    }
}