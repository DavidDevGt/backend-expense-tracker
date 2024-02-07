<?php
namespace App\Route;

use App\Model\UserModel;

class UserRoute {
    private $userModel;

    public function __construct(UserModel $userModel) {
        $this->userModel = $userModel;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($username, $password) {
        $user = $this->userModel->findUserByUsername($username);
        if ($user && password_verify($password, $user['hashed_password'])) {
            session_regenerate_id();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            $_SESSION['token'] = bin2hex(random_bytes(32));

            return ['success' => true, 'token' => $_SESSION['token'], 'message' => 'Has iniciado sesión'];
        } else {
            return ['success' => false, 'message' => 'Usuario o contraseña incorrectos'];
        }
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();

        return ['success' => true, 'message' => 'Has cerrado sesión'];
    }
}
