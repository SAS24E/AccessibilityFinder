<?php
require_once 'application/models/user.php';
require_once 'database/database.php';

class UserController {
    private $conn;
    private $table = 'users';

    public $id;
    public $name;
    public $email;

    public function __construct(){
        $this->conn = new Database;
    }

    public function register($data){
        $this->conn->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
        // Bind values
        $this->conn->bind(':name', $data['name']);
        $this->conn->bind(':email', $data['email']);
        $this->conn->bind(':password', $data['password']);

        // Execute
        if($this->conn->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function login($email, $password){
        $this->conn->query('SELECT * FROM users WHERE email = :email');
        $this->conn->bind(':email', $email);

        $row = $this->conn->single();

        $hashed_password = $row->password;
        if(password_verify($password, $hashed_password)){
            return $row;
        } else {
            return false;
        }
    }
}
?>
    