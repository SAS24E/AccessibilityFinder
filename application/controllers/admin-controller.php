<?php
// Simple admin controller for managing users and posts

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Database/database.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/post.php';

class AdminController {
    private $userModel;
    private $postModel;

    public function __construct() {
        $db = new Database();
        $conn = $db->connect();

        $this->userModel = new User($conn);
        $this->postModel = new PostModel($conn);

        // Only allow access if a logged in user is an admin
        if (!isset($_SESSION['user_id']) || !$this->userModel->isAdmin($_SESSION['user_id'])) {
            header("Location: ../../public/index.php");
            exit;
        }
    }

    // Main admin dashboard
    public function index() {
        $users = $this->userModel->getAllUsers();
        $posts = $this->postModel->getAllPosts();

        require __DIR__ . '/../views/admin-dashboard.php';
    }

    // Delete a user (and cascade delete their posts)
    public function deleteUser() {
        if (!isset($_GET['id'])) {
            header("Location: admin-controller.php?action=index");
            exit;
        }

        $id = (int)$_GET['id'];

        // Prevent admin from deleting themselves if you want
        if ($id === (int)$_SESSION['user_id']) {
            header("Location: admin-controller.php?action=index");
            exit;
        }

        $this->userModel->deleteUserById($id);
        header("Location: admin-controller.php?action=index");
        exit;
    }

    // Delete a post
    public function deletePost() {
        if (!isset($_GET['id'])) {
            header("Location: admin-controller.php?action=index");
            exit;
        }

        $id = (int)$_GET['id'];
        $this->postModel->deletePostById($id);

        header("Location: admin-controller.php?action=index");
        exit;
    }

    // Flag or unflag a user
    public function flagUser() {
        if (!isset($_GET['id'])) {
            header("Location: admin-controller.php?action=index");
            exit;
        }

        $id = (int)$_GET['id'];
        $flag = isset($_GET['flag']) ? (int)$_GET['flag'] : 1;

        $this->userModel->setUserFlag($id, $flag);
        header("Location: admin-controller.php?action=index");
        exit;
    }

    // Flag or unflag a post
    public function flagPost() {
        if (!isset($_GET['id'])) {
            header("Location: admin-controller.php?action=index");
            exit;
        }

        $id = (int)$_GET['id'];
        $flag = isset($_GET['flag']) ? (int)$_GET['flag'] : 1;

        $this->postModel->setPostFlag($id, $flag);
        header("Location: admin-controller.php?action=index");
        exit;
    }
}

// Basic router for admin actions
if (isset($_GET['action'])) {
    $controller = new AdminController();

    $action = $_GET['action'];

    if ($action === 'index') {
        $controller->index();
    } elseif ($action === 'deleteUser') {
        $controller->deleteUser();
    } elseif ($action === 'deletePost') {
        $controller->deletePost();
    } elseif ($action === 'flagUser') {
        $controller->flagUser();
    } elseif ($action === 'flagPost') {
        $controller->flagPost();
    }
}