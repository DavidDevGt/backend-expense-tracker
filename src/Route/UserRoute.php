<?php
namespace App\Route;

use App\Model\UserModel;

class UserRoute {
    private $userModel;

    public function __construct(UserModel $userModel) {
        $this->userModel = $userModel;
    }

    public function login($username, $password) {
        $user = $this->userModel->findUserByUsername($username);
        if ($user && password_verify($password, $user['hashed_password'])) {
            // Crear el payload del token
            $payload = [
                'userId' => $user['id'],
                'timestamp' => time()
            ];

            // Convertir el payload a JSON y cifrarlo
            $token = $this->encrypt(json_encode($payload));

            // Devolver respuesta de éxito con token
            return ['success' => true, 'message' => 'Login correcto', 'token' => $token];
        } else {
            // Devolver respuesta de fracaso
            return ['success' => false, 'message' => 'Login incorrecto'];
        }
    }

    public function logout() {
        // La lógica de logout se manejaría en el cliente eliminando el token almacenado
        return ['success' => true, 'message' => 'Logout exitoso'];
    }

    private function encrypt($data) {
        $encryption_key = openssl_random_pseudo_bytes(32);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }
}