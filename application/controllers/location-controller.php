<?php

require_once __DIR__ . '/../models/location.php';
require_once __DIR__ . '/../../database/database.php';
// LocationController.php
class LocationController {
    private $locationModel;

    public function __construct() {
        $db = new Database();
        $conn = $db->connect();
        $this->locationModel = new LocationModel($conn);
    }
    // Method to get a location by ID
    public function getLocationById($id) {
        return $this->locationModel->getLocationById($id);
    }

    // Method to create a new location
    public function createLocation() {
        // read JSON body (locations api), validate, call model, return JSON

            // Accept either JSON body (API clients) or regular form POST (browser form)
            $raw = file_get_contents("php://input");
            $data = null;
            if ($raw) {
                $data = json_decode($raw, true); // try JSON payload
            }

            // If no JSON payload, fall back to form-encoded POST data
            if (!is_array($data) || empty($data)) {
                $data = $_POST ?? [];
            }

            // Basic validation if 'name' field is present and not empty
            if (!isset($data['name']) || empty(trim($data['name']))) {
                // If client expects JSON, return JSON error; otherwise redirect back with error
                if (stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false || $raw) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Name is required']);
                    return;
                } else {
                    // Simple browser redirect back to the dashboard with an error flag
                    header('Location: ../views/location-dashboard.php?error=missing_name');
                    return;
                }
            }

            // Call model to create location
            $newLocationId = $this->locationModel->createLocation($data);
            if ($newLocationId) {
                if (stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false || $raw) {
                    http_response_code(201); // Created
                    echo json_encode(['id' => $newLocationId]);
                    return;
                } else {
                    // Redirect back to the dashboard (relative path) with success flag
                    header('Location: ../views/location-dashboard.php?created=1&id=' . urlencode($newLocationId));
                    return;
                }
            } else {
                if (stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false || $raw) {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to create location']);
                    return;
                } else {
                    header('Location: ../views/location-dashboard.php?error=save_failed');
                    return;
                }
            }


    }
    public function deleteLocationByName() {
        // read JSON body (locations api), validate, call model, return JSON

        //read raw JSON body 
        $raw = file_get_contents("php://input");
        $data = json_decode($raw, true); // true => associative array
        // Basic validation if 'id' field is present and not empty
        if (!isset($data['id']) || empty(trim($data['id']))) {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
            return;
        }
        // Call model to delete location (assuming deleteLocation method exists)
        $deleted = $this->locationModel->deleteLocationByName($data['id']);
        if ($deleted) {
            http_response_code(200); // OK
            echo json_encode(['message' => 'Location deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete location']);
        }
    }
}

// simple routing logic
if (isset($_GET['action'])) {
    $controller = new LocationController();
    if ($_GET['action'] === 'createLocation') {
        $controller->createLocation();
    } elseif ($_GET['action'] === 'deleteLocation') {
        $controller->deleteLocationByName();
    } elseif ($_GET['action'] === 'getLocationById') {
        $controller->getLocationById($_GET['id'])  ;
    }
}