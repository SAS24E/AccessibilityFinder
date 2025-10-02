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

// Handle login form submission
    public function login(){
        //if user is already logged in, redirect to dashboard
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Attempt to log in the user
            $loggedInUser = $this->user->login($email, $password);
            // If login is successful, set session variables and redirect to dashboard
            if ($loggedInUser) {
                $_SESSION['user_id'] = $loggedInUser->id;
                $_SESSION['user_name'] = $loggedInUser->name;
                header("Location: ../../public/index.php");
                exit;
            // If login fails, show an error message
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
