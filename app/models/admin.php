<?php
require_once __DIR__ . '/../../core/Model.php';

class Admin extends Model {
    protected $table = 'admins';

    public function authenticate($username, $password) {
        $admin = $this->findBy('username', $username);
        
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        
        return false;
    }

    public function createAdmin($username, $password, $email) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        return $this->create([
            'username' => $username,
            'password' => $hashedPassword,
            'email' => $email
        ]);
    }
}