<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Config\Database;
use App\Model\UserModel;
use App\Model\TransactionModel;
use App\Route\UserRoute;
use App\Route\TransactionRoute;
use App\Middleware\AuthMiddleware;

$database = new Database();
$db = $database->connect();

$userModel = new UserModel($db);
$transactionModel = new TransactionModel($db);

$userRoute = new UserRoute($userModel);
$transactionRoute = new TransactionRoute($transactionModel);

$router = new \AltoRouter();

$router->setBasePath('/backend-expense-tracker');

$router->map('GET', '/', function() {
    $response = [
        'message' => 'Welcome to the Expense Tracker API',
    ];

    echo json_encode($response);
});


// Rutas para el usuario
$router->map('POST', '/users/login', function() use ($userRoute) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $userRoute->login($data['username'], $data['password']);
    echo json_encode($response);
});

$router->map('POST', '/users/logout', function() use ($userRoute) {
    $response = $userRoute->logout();
    echo json_encode($response);
});

// Rutas para las transacciones
$router->map('GET', '/transactions', AuthMiddleware::verifyToken(function($userId) use ($transactionRoute) {
    $response = $transactionRoute->getTransactions($userId);
    echo json_encode($response);
}));

$router->map('POST', '/transactions', AuthMiddleware::verifyToken(function($userId) use ($transactionRoute) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $transactionRoute->createTransaction($userId, $data['text'], $data['amount']);
    echo json_encode($response);
}));

$router->map('GET', '/transactions/[:id]', AuthMiddleware::verifyToken(function($userId, $id) use ($transactionRoute) {
    $response = $transactionRoute->getTransaction($userId, $id);
    echo json_encode($response);
}));

$router->map('PUT', '/transactions/[:id]', AuthMiddleware::verifyToken(function($userId, $id) use ($transactionRoute) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $transactionRoute->updateTransaction($userId, $id, $data['text'], $data['amount']);
    echo json_encode($response);
}));

$router->map('DELETE', '/transactions/[:id]', AuthMiddleware::verifyToken(function($userId, $params) use ($transactionRoute) {
    $id = $params['id'];
    $response = $transactionRoute->deleteTransaction($userId, $id);
    echo json_encode($response);
}));


header('Access-Control-Allow-Origin: ' . $_ENV['URL_APP']);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Match the current request
$match = $router->match();

header('Content-Type: application/json');
if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], [$match['params']]);
} else {
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo json_encode(['message' => 'Route not found']);
}
