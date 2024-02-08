<?php

namespace App\Middleware;

class AuthMiddleware
{
    public static function verifyToken($callback)
    {
        return function () use ($callback) {
            //session_start();
            if (!isset($_SESSION['token']) || empty($_SESSION['token'])) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit();
            }

            $requestToken = isset(getallheaders()['Authorization']) ? explode(" ", getallheaders()['Authorization'])[1] : null;
            if ($requestToken !== $_SESSION['token']) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit();
            }

            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            $callback($userId);
        };
    }
}
