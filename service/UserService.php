<?php
require_once __DIR__ . '/../model/User.php';

class UserService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function save(User $user) {
        $sql = "INSERT INTO users (username, email, password, role) 
                VALUES (:username, :email, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRole()
        ]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row['id'], $row['username'], $row['email'], $row['password'], $row['role']);
        }
        return null;
    }


    public function getByEmail($email) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new User($row['id'], $row['username'], $row['email'], $row['password'], $row['role']);
        }
        return null;
    }


    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM users ORDER BY username ASC');
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($row['id'], $row['username'], $row['email'], $row['password'], $row['role']);
        }
        return $users;
    }

    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }

    public function updateRole($userId, $newRole){
    $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    return $stmt->execute([$newRole, $userId]);
    }

}
