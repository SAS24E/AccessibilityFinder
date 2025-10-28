<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db){
        $this->conn = $db;
    }

    public function register($data){
        // Prepare and execute the SQL statement to insert a new user
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (name, email, password) 
             VALUES (:name, :email, :password)"
        );
        // Bind parameters and hash the password before storing it
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }
    
    // Handles the database query for logging in a user
    public function login($email, $password){
        // Prepare and execute the SQL statement to find the user by email
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        // If a row is found, verify the password ( so when its time to has passwords it will work)
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
    }    

    // Fetch a single user by ID and return as an object
    public function getUserById($id){
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Updates the biography field in the db for a user
    public function editBio($id, $biography){
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET biography = :biography WHERE id = :id");
        return $stmt->execute([
            ':biography' => $biography,
            ':id' => $id
        ]);
    }
}
