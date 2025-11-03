<?php
class PostModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllPosts() {
        $sql = "SELECT posts.*, users.name AS username 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                ORDER BY posts.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostsByUser($userId) {
        // Include the poster's name as 'username' so views can display it consistently
        $sql = "SELECT posts.*, users.name AS username
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.user_id = :user_id
                ORDER BY posts.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllLocations() {
        $sql = "SELECT id, name FROM locations ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Probaly won't need this but leaving here incase its in use!
    public function getLocationById($id) {
        $sql = "SELECT * FROM locations WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPost($data) {
        $sql = "INSERT INTO posts (location_id, location_name, user_id, opinion, assistance_friendly, image) VALUES (:location_id, :location_name, :user_id, :opinion, :assistance_friendly, :image)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':location_id', $data['location_id'], PDO::PARAM_INT);
        $stmt->bindValue(':location_name', $data['location_name'], PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':opinion', $data['opinion'], PDO::PARAM_STR);
        $stmt->bindValue(':assistance_friendly', $data['assistance_friendly'], PDO::PARAM_STR);
        $stmt->bindValue(':image', $data['image'], PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function deletePost($postId, $userId) {
        // Only delete if the post belongs to the given user
        $sql = "DELETE FROM posts WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

