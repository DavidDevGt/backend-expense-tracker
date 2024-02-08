<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    public static function verifyToken($callback)
    {
        return function ($routeParams) use ($callback) {
            $headers = getallheaders();
            $token = isset($headers['Authorization']) ? explode(" ", $headers['Authorization'])[1] : null;

            if (!$token) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit();
            }

            try {
                $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
                $userId = $decoded->user_id;
            } catch (\Exception $e) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Unauthorized', 'error' => $e->getMessage()]);
                exit();
            }

            $callback($userId, $routeParams);
        };
    }
}
