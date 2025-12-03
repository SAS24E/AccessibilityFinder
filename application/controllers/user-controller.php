<?php
session_start();

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../../Database/database.php';
require_once __DIR__ . '/../models/post.php';

class UserController
{
    private $user;

    public function __construct()
    {
        $db = new Database();
        $conn = $db->connect();
        $this->user = new User($conn);
    }
    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: ../../index.php");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => trim($_POST['username'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'nick_name' => trim($_POST['nickname'] ?? ''),
                'password' => trim($_POST['password'] ?? '')
            ];

            $result = $this->user->register($data);

            if ($result['success']) {
                $loggedInUser = $this->user->login($data['email'], $data['password']);
                if ($loggedInUser) {
                    $_SESSION['user_id'] = $loggedInUser->id;
                    $_SESSION['user_name'] = $loggedInUser->name;
                    $_SESSION['is_admin'] = $loggedInUser->is_admin;
                }
                header("Location: ../../index.php?registered=1");
                exit;
            } else {
                $errors = $result['errors'] ?? ['registration_failed'];
                $errorString = implode(',', $errors);
                header("Location: ../views/register-dashboard.php?error=" . $errorString);
                exit;
            }
        }
    }

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: ../../public/index.php");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $loggedInUser = $this->user->login($email, $password);
            if ($loggedInUser) {
                $_SESSION['user_id'] = $loggedInUser->id;
                $_SESSION['user_name'] = $loggedInUser->name;
                $_SESSION['is_admin'] = $loggedInUser->is_admin;
                header("Location: ../../index.php");
                exit;
            } else {
                header("Location: ../views/login-dashboard.php?error=1");
            }
        }
    }
    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
        header("Location: ../../index.php");
        exit;
    }

    // Show profile page for logged-in users
    public function profile()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../index.php");
            exit;
        }
        $userId = $_SESSION['user_id'];
        $userData = $this->user->getUserById($userId);

        if (!$userData) {
            echo "<p style='color:red;'>User not found.</p>";
            return;
        }

        $user = $userData;
        // Load posts for this user so the profile only shows their posts
        $postModel = new PostModel((new Database())->connect());
        $posts = $postModel->getPostsByUser($userId);
        // lightweight count for displaying total posts on the profile
        $postCount = $postModel->getPostCountByUser($userId);
        require_once __DIR__ . '/../views/profile-dashboard.php';
    }

    // Update biography for logged-in users
    public function updateBio()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../index.php");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $biography = trim($_POST['biography'] ?? '');
            $userId = $_SESSION['user_id'];

            // Update the biography in the database
            if ($this->user->editBio($userId, $biography)) {
                header("Location: ../controllers/user-controller.php?action=profile");
                exit;
            } else {
                echo "<p style='color:red;'>Failed to update biography. Please try again.</p>";
            }
        }
    }

    public function uploadProfileImage()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../index.php");
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
            $userId = $_SESSION['user_id'];
            $file = $_FILES['profile_image'];

            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $allowed) && $file['error'] === 0) {
                $newName = 'user_' . $userId . '_' . time() . '.' . $ext;
                $destination = __DIR__ . '/../../uploads/profile-pictures/' . $newName;

                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $this->user->updateProfileImage($userId, $newName);
                    header("Location: ../controllers/user-controller.php?action=profile");
                    exit;
                } else {
                    $_SESSION['upload_error'] = "Failed to upload file. Check permissions or folders";
                }
            } else {
                $_SESSION['upload_error'] = "Invalid file type. Please upload a JPG, PNG, or GIF.";
            }

            header("Location: ../controllers/user-controller.php?action=profile");
            exit;
        }
    }
}

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
