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
// Handle registration form submission
    public function register(){
        //if user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header("Location: ../../public/index.php");
            exit;
        }
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password' => trim($_POST['password'] ?? '')
            ];
            // Attempt to register the user
            if ($this->user->register($data)) {
                header("Location: ../../public/index.php?registered=1");
                exit;
            } else {
                echo "<p style='color:red;'>Registration failed. Please try again.</p>";
            }
        }
    }

// Handle login form submission
    public function login(){
        //if user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header("Location: ../../public/index.php");
            exit;
        }
        // Check if the form is submitted
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
    public function logout(){
    // start session and destroy it
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // clear session data
    $_SESSION = [];
    // destoy session
    session_destroy();
    // redirect to home page
    header("Location: ../../public/index.php");
    exit();
    }
}

// Simple routing based on 'action' parameter this is how we call the functions in this controller
if (isset($_GET['action'])) {
    $controller = new UserController();
    if ($_GET['action'] === 'register') {
        $controller->register();
    } elseif ($_GET['action'] === 'login') {
        $controller->login();
    } elseif ($_GET['action'] === 'logout') {
        $controller->logout();
    }
}
