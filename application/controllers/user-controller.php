<?php
session_start();

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../../Database/database.php';
require_once __DIR__ . '/../models/post.php';

class UserController {
    private $user;

    public function __construct(){
        $db = new Database();
        $conn = $db->connect();
        $this->user = new User($conn);
    }
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
        $result = $this->user->register($data);
        
        if ($result['success']) {
            // If user registered successfully, log them in and redirect to home page
            $loggedInUser = $this->user->login($data['email'], $data['password']);
            if ($loggedInUser) {
                $_SESSION['user_id'] = $loggedInUser->id;
                $_SESSION['user_name'] = $loggedInUser->name;
            }
            header("Location: ../../public/index.php?registered=1");
            exit;
        } else {
            // Redirect back to registration page with specific errors
            $errors = $result['errors'] ?? ['registration_failed'];
            $errorString = implode(',', $errors);
            header("Location: ../views/register-dashboard.php?error=" . $errorString);
            exit;
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
            // If login fails, redirect back to login with error
            } else {
                header("Location: ../views/login-dashboard.php?error=1");
            }
        }
    }
    public function logout(){
        // start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // clear session data
        $_SESSION = [];
        // destroy session
        session_destroy();
        // redirect to home page
        header("Location: ../../public/index.php");
        exit;
    }

    // Show profile page for logged-in users
    public function profile(){
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../public/index.php");
            exit;
        }
        // getUserById() is defined in models/user.php
        // currently getUserById() returns a user object (name, email, password)
        $userId = $_SESSION['user_id'];
        $userData = $this->user->getUserById($userId);

        // If user not found, display an error message
        if (!$userData) {
            echo "<p style='color:red;'>User not found.</p>";
            return;
        }

        // Make $user available to the view
        $user = $userData;
        // Load posts for this user so the profile only shows their posts
        $postModel = new PostModel((new Database())->connect());
        $posts = $postModel->getPostsByUser($userId);
        require_once __DIR__ . '/../views/profile-dashboard.php';
    }

    // Update biography for logged-in users
    public function updateBio(){
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../public/index.php");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $biography = trim($_POST['biography'] ?? '');
            $userId = $_SESSION['user_id'];

            // Update the biography in the database
            if ($this->user->editBio($userId, $biography)) {
                header("Location: ../controllers/user-controller.php?action=profile");

                exit;
            // If update fails, show an error message
            } else {
                echo "<p style='color:red;'>Failed to update biography. Please try again.</p>";
            }
        }
    }
    public function uploadProfileImage() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../public/index.php");
        exit;
    }
    // Make sure the form was submitted and a file was uploaded
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
        $userId = $_SESSION['user_id'];
        $file = $_FILES['profile_image'];

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed) && $file['error'] === 0) {
            $newName = 'user_' . $userId . '_' . time() . '.' . $ext;
            $destination = __DIR__ . '/../../public/uploads/profile-pictures/' . $newName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $this->user->updateProfileImage($userId, $newName);
                header("Location: ../controllers/user-controller.php?action=profile");
                exit;
            } 
            else 
            {
                $_SESSION['upload_error'] = "Failed to upload file. Check permissions/ correct folders";
            }
        } 
        else 
        {
            $_SESSION['upload_error'] = "Invalid file type. Please upload a JPG, PNG, or GIF.";
        }

        // Always redirect back to profile page
        header("Location: ../controllers/user-controller.php?action=profile");
        exit;
    }
}
}

// Simple routing based on 'action' parameter this is how we call the functions in this controller
if (isset($_GET['action'])) {
    $controller = new UserController();
    if ($_GET['action'] === 'register') {
        $controller->register();
    } elseif ($_GET['action'] === 'login') {
        $controller->login();
    } elseif ($_GET['action'] === 'profile') {
        $controller->profile();
    } elseif ($_GET['action'] === 'logout') {
        $controller->logout();
    } elseif ($_GET['action'] === 'updateBio') {
        $controller->updateBio();
    } elseif ($_GET['action'] === 'uploadProfileImage') {
        $controller->uploadProfileImage();
    }
}
