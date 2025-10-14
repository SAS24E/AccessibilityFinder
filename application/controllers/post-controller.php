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
}
?>
