<?php
class User
{
    private $conn;
    private $table = 'users';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Check if email already exists
    public function emailExists($email)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;
    }

    // Check if username already exists
    public function usernameExists($username)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $username]);
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;
    }
    // Check if nickname already exists
    public function nicknameExists($nickname)
    {
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE nick_name = :nick_name LIMIT 1");
        $stmt->execute([':nick_name' => $nickname]);
        return $stmt->fetch(PDO::FETCH_OBJ) !== false;
    }

    public function register($data)
    {
        $errors = [];

        // Check if username already exists
        if ($this->usernameExists($data['name'])) {
            $errors[] = 'username_exists';
        }

        if ($this->nicknameExists($data['nick_name'])) {
            $errors[] = 'nickname_exists';
        }

        // Check if email already exists
        if ($this->emailExists($data['email'])) {
            $errors[] = 'email_exists';
        }

        // If there are any errors, return them
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Prepare and execute the SQL statement to insert a new user
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO {$this->table} (name, nick_name, email, password) 
             VALUES (:name, :nick_name, :email, :password)"
            );
            // Bind parameters and hash the password before storing it
            $result = $stmt->execute([
                ':name' => $data['name'],
                ':nick_name' => $data['nick_name'] ?? $data['name'], // Use name as nickname if not provided
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]);
            return ['success' => $result];
        } catch (PDOException $e) {
            // Handle any other database errors
            return ['success' => false, 'errors' => ['database_error']];
        }
    }

    // Handles the database query for logging in a user
    public function login($email, $password)
    {
        // Prepare and execute the SQL statement to find the user by email
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        // If a row is found, verify the password ( so when its time to has passwords it will work)
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
    }

    // Fetch a single user by ID and return as an object
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
    // Return true if the given user id is an admin
    public function isAdmin($userId)
    {
        $stmt = $this->conn->prepare("SELECT is_admin FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && (int) $row['is_admin'] === 1;
    }

    // Get all users for the admin dashboard (no passwords)
    public function getAllUsers()
    {
        $sql = "SELECT id, name, email, created_at, biography, profile_image, is_admin, is_flagged
                FROM {$this->table}
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Admin: delete a user by id
    public function deleteUserById($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Admin: flag or unflag a user
    public function setUserFlag($id, $flagValue)
    {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_flagged = :flag WHERE id = :id");
        return $stmt->execute([
            ':flag' => (int) $flagValue,
            ':id' => (int) $id
        ]);
    }
}
