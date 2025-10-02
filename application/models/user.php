<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db){
        $this->conn = $db;
    }

    public function register($data){
        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (name, email, password) 
             VALUES (:name, :email, :password)"
        );
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }

    public function login($email, $password){
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
}
