
<?php

class LocationModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllLocations() {
        $sql = "SELECT * FROM locations ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new location and return its ID
    public function createLocation($data){
        // Insert new location using prepared statement
        $sql = "INSERT INTO locations (name, address, latitude, longitude) VALUES (:name, :address, :latitude, :longitude)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(':address', $data['address'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':latitude', $data['latitude'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':longitude', $data['longitude'] ?? null, PDO::PARAM_STR);
        if($stmt->execute()){
            return $this->conn->lastInsertId(); // return new location ID
        } else {
            return false; // insertion failed
        }
    }

    // Get location by ID
    public function getLocationById($id){
        // Fetch location by ID using prepared statement
        $sql = "SELECT * FROM locations WHERE id = :id";
        // prepare and execute statement
        $stmt = $this->conn->prepare($sql);
        // bind value and execute
        $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        //return associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   // get location by name (partial match)
    public function findlocationsByName($query) {
        $sql = "SELECT * FROM locations WHERE name LIKE :q";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':q' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   // delete location by name
    public function deleteLocationByName($query) {
        $sql = "DELETE FROM locations WHERE name LIKE :q";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':q', '%' . $query . '%', PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Additional methods for updating and deleting locations can be added here

}