<?php
session_start();

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../../database/database.php';

class UserController {
    private $user;

    public function __construct(){
        $db = new Database();
        $conn = $db->connect();
        $this->user = new User($conn);
    }

    public function register(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];

            if ($this->user->register($data)) {
                header("Location: ../../public/index.php?registered=1");
                exit;
            } else {
                echo "Registration failed.";
            }
        }
    }

    public function login(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $loggedInUser = $this->user->login($email, $password);

            if ($loggedInUser) {
                $_SESSION['user_id'] = $loggedInUser->id;
                $_SESSION['user_name'] = $loggedInUser->name;
                header("Location: ../../public/index.php");
                exit;
            } else {
                echo "<p style='color:red;'>Invalid email or password.</p>";
            }
        }
    }
}

// Simple routing
if (isset($_GET['action'])) {
    $controller = new UserController();
    if ($_GET['action'] === 'register') {
        $controller->register();
    } elseif ($_GET['action'] === 'login') {
        $controller->login();
    }
}
