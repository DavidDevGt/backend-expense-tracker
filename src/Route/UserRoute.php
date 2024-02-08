<?php
namespace App\Route;

use App\Model\UserModel;
use Firebase\JWT\JWT;

class UserRoute {
    private $userModel;

    public function __construct(UserModel $userModel) {
        $this->userModel = $userModel;
    }

    public function login($username, $password) {
        $user = $this->userModel->findUserByUsername($username);
        if ($user && password_verify($password, $user['hashed_password'])) {
            $payload = [
                'iat' => time(),
                'exp' => time() + (60 * 60),
                'user_id' => $user['id']
            ];

            $jwt = JWT::encode($payload, $_ENV['JWT_SECRET_KEY'], 'HS256');

            return ['success' => true, 'token' => $jwt, 'message' => 'Has iniciado sesión'];
        } else {
            return ['success' => false, 'message' => 'Usuario o contraseña incorrectos'];
        }
    }

    public function logout() {
        // El manejo real del token JWT (su eliminación o invalidación) debe realizarse en el cliente.
        return ['success' => true, 'message' => 'Has cerrado sesión'];
    }
}
