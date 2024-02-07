<?php
require 'vendor/autoload.php';

use App\Config\Database;
use App\Model\UserModel;
use App\Model\TransactionModel;
use App\Route\UserRoute;
use App\Route\TransactionRoute;

$database = new Database();
$db = $database->connect();

$userModel = new UserModel($db);
$transactionModel = new TransactionModel($db);

$userRoute = new UserRoute($userModel);
$transactionRoute = new TransactionRoute($transactionModel);

$router = new \AltoRouter();

$router->setBasePath('/backend-expense-tracker');

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
$router->map('GET', '/transactions', function() use ($transactionRoute) {
    $userId = $_GET['userId']; // Suponiendo que pasas el userId como query param
    $response = $transactionRoute->getTransactions($userId);
    echo json_encode($response);
});

$router->map('POST', '/transactions', function() use ($transactionRoute) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $transactionRoute->createTransaction($data['userId'], $data['text'], $data['amount']);
    echo json_encode($response);
});

$router->map('GET', '/transactions/[:id]', function($id) use ($transactionRoute) {
    $response = $transactionRoute->getTransaction($id);
    echo json_encode($response);
});

$router->map('PUT', '/transactions/[:id]', function($id) use ($transactionRoute) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $transactionRoute->updateTransaction($id, $data['text'], $data['amount']);
    echo json_encode($response);
});

$router->map('DELETE', '/transactions/[:id]', function($id) use ($transactionRoute) {
    $response = $transactionRoute->deleteTransaction($id);
    echo json_encode($response);
});

// Agregar encabezados CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Si la solicitud es de tipo OPTIONS, devuelve los encabezados CORS y termina el script
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Match the current request
$match = $router->match();

header('Content-Type: application/json');
if($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']); 
} else {
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo json_encode(['message' => 'Route not found']);
}
