<?php

require_once __DIR__ . '/../models/location.php';
require_once __DIR__ . '/../../database/database.php';
// LocationController.php
class LocationController {
    private $locationModel;

    // Constructor to initialize the model
    public function __construct($conn) {
        $this->locationModel = new LocationModel($conn);
    }
    // Method to get a location by ID
    public function getLocationById($id) {
        return $this->locationModel->getLocationById($id);
    }
    public function createLocation() {
        // read JSON body (locations api), validate, call model, return JSON

        //read raw JSON body 
        $raw = file_get_contents("php://input");
        $data = json_decode($raw, true); // true => associative array
        // Basic validation if 'name' field is present and not empty
        if (!isset($data['name']) || empty(trim($data['name']))) {
            http_response_code(400);
            echo json_encode(['error' => 'Name is required']);
            return;
        }
        // Call model to create location (assuming createLocation method exists)
        $newLocationId = $this->locationModel->createLocation($data);
        if ($newLocationId) {
            http_response_code(201); // Created
            echo json_encode(['id' => $newLocationId]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create location']);
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
// Simple routing based on 'action' parameter this is how we call the functions in this controller
if (isset($_GET['action'])) {
    $controller = new LocationController($conn);
    if ($_GET['action'] === 'createLocation') {
        $controller->createLocation();
    } elseif ($_GET['action'] === 'deleteLocation') {
        $controller->deleteLocationByName();
    } elseif ($_GET['action'] === 'getLocationById') {
        $controller->getLocationById($_GET['id'])  ;
    }
}