<?php
require_once __DIR__ . '/../models/post.php';


class PostController {
    private $model;

    public function __construct($db) {
        $this->model = new PostModel($db);
    }

    public function index() {
        return $this->model->getAllPosts();
    }

    // Return posts for a specific user
    public function getUserPosts($userId) {
        return $this->model->getPostsByUser($userId);
    }

    // Show the create post form
    public function createForm() {
        // get locations for the select box
        $locations = $this->model->getAllLocations();
        require_once __DIR__ . '/../views/create-post-dashboard.php';
    }

    // Handle create post submission
    public function create() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../public/index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../../public/index.php");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $location_id = intval($_POST['location_id'] ?? 0);
        $opinion = trim($_POST['opinion'] ?? '');
        $assistance = ($_POST['assistance_friendly'] ?? 'no') === 'yes' ? 'yes' : 'no';

        // get location name from id
        $location = $this->model->getLocationById($location_id);
        $location_name = $location ? $location['name'] : '';

        // handle image upload
        $imageFileName = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadsDir = __DIR__ . '/../../public/uploads';
            if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);
            $tmpName = $_FILES['image']['tmp_name'];
            $origName = basename($_FILES['image']['name']);
            $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $origName);
            $dest = $uploadsDir . '/' . $safeName;
            if (move_uploaded_file($tmpName, $dest)) {
                $imageFileName = $safeName;
            }
        }

        $data = [
            'location_id' => $location_id,
            'location_name' => $location_name,
            'user_id' => $userId,
            'opinion' => $opinion,
            'assistance_friendly' => $assistance,
            'image' => $imageFileName
        ];

        if ($this->model->createPost($data)) {
            header("Location: ../../public/index.php?created=1");
            exit;
        } else {
            echo "<p style='color:red;'>Failed to create post. Please try again.</p>";
        }
    }

    // Show manage posts view for logged-in user
    public function manage() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../public/index.php");
            exit;
        }
        $userId = $_SESSION['user_id'];
        $posts = $this->getUserPosts($userId);
        require_once __DIR__ . '/../views/manage-posts.php';
    }

    // Handle delete post (only owner)
    public function delete() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../public/index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../../public/index.php");
            exit;
        }

        $postId = intval($_POST['post_id'] ?? 0);
        $userId = $_SESSION['user_id'];

        if ($postId <= 0) {
            header("Location: ../../public/index.php");
            exit;
        }

        if ($this->model->deletePost($postId, $userId)) {
            header("Location: ../../application/controllers/post-controller.php?action=manage&deleted=1");
            exit;
        } else {
            echo "<p style='color:red;'>Failed to delete post or permission denied.</p>";
        }
    }
}

// Basic routing for direct calls
if (isset($_GET['action'])) {
    // create a DB connection similar to other controllers
    require_once __DIR__ . '/../../Database/database.php';
    $database = new Database();
    $db = $database->connect();
    $controller = new PostController($db);
    $action = $_GET['action'];
    if ($action === 'createForm') {
        $controller->createForm();
    } elseif ($action === 'create') {
        $controller->create();
    } elseif ($action === 'manage') {
        $controller->manage();
    } elseif ($action === 'delete') {
        $controller->delete();
    }
}

