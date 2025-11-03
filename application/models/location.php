
<?php

class LocationModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // General-purpose createLocation used by simple form and JSON API
    public function createLocation($data) {
        $sql = "INSERT INTO locations (name, address, latitude, longitude) VALUES (:name, :address, :latitude, :longitude)";
        $stmt = $this->conn->prepare($sql);
        $latitude = isset($data['latitude']) && is_numeric($data['latitude']) ? $data['latitude'] : 0.0;
        $longitude = isset($data['longitude']) && is_numeric($data['longitude']) ? $data['longitude'] : 0.0;
        $ok = $stmt->execute([
            ':name' => $data['name'],
            ':address' => $data['address'] ?? null,
            ':latitude' => $latitude,
            ':longitude' => $longitude
        ]);
        if ($ok) return (int)$this->conn->lastInsertId();
        return false;
    }

    // add location to database from nominatim search result
    public function createLocationBySearch($data) {
        $sql = "INSERT INTO locations (name, address, latitude, longitude, nominatim_place_id, osm_type, osm_id) 
                VALUES (:name, :address, :latitude, :longitude, :nominatim_place_id, :osm_type, :osm_id)";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            ':name' => $data['name'],
            ':address' => $data['address'] ?? null,
            ':latitude' => $data['latitude'],
            ':longitude' => $data['longitude'],
            ':nominatim_place_id' => $data['nominatim_place_id'] ?? null,
            ':osm_type' => $data['osm_type'] ?? null,
            ':osm_id' => $data['osm_id'] ?? null
        ]);
        if ($ok) return (int)$this->conn->lastInsertId();
        return false;
    }

   // delete location by name
    public function deleteLocationByName($query) {
        $sql = "DELETE FROM locations WHERE name LIKE :q";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':q', '%' . $query . '%', PDO::PARAM_STR);
        return $stmt->execute();
    }
}