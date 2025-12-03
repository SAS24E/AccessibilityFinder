<?php
class User
{
    private $conn;
    private $table = 'users';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function emailExists($email)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;
    }

    public function usernameExists($username)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $username]);
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;
    }

    public function nicknameExists($nickname)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE nick_name = :nick_name LIMIT 1");
        $stmt->execute([':nick_name' => $nickname]);
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;
    }

    public function register($data)
    {
        $errors = [];

        if ($this->usernameExists($data['name'])) {
            $errors[] = 'username_exists';
        }

        if ($this->nicknameExists($data['nick_name'])) {
            $errors[] = 'nickname_exists';
        }

        if ($this->emailExists($data['email'])) {
            $errors[] = 'email_exists';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO {$this->table} (name, nick_name, email, password) 
             VALUES (:name, :nick_name, :email, :password)"
            );
            $result = $stmt->execute([
                ':name' => $data['name'],
                ':nick_name' => $data['nick_name'] ?? $data['name'],
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]);
            return ['success' => $result];
        } catch (PDOException $e) {
            return ['success' => false, 'errors' => ['database_error']];
        }
    }

    public function login($email, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
    }

    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Updates the biography field in the db for a user
    public function editBio($id, $biography)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET biography = :biography WHERE id = :id");
        return $stmt->execute([
            ':biography' => $biography,
            ':id' => $id
        ]);
    }

    // Updates the profile_image field in the db for a user w/ filepath
    public function updateProfileImage($id, $imagePath)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET profile_image = :image WHERE id = :id");
        return $stmt->execute([
            ':image' => $imagePath,
            ':id' => $id
        ]);
    }

    public function isAdmin($userId)
    {
        $stmt = $this->conn->prepare("SELECT is_admin FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && (int) $row['is_admin'] === 1;
    }

    public function getAllUsers()
    {
        $sql = "SELECT id, name, email, created_at, biography, profile_image, is_admin, is_flagged
                FROM {$this->table}
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUserById($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function setUserFlag($id, $flagValue)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_flagged = :flag WHERE id = :id");
        return $stmt->execute([
            ':flag' => (int) $flagValue,
            ':id' => (int) $id
        ]);
    }
}
