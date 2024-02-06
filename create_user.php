<?php
require 'vendor/autoload.php';

use App\Config\Database;

$database = new Database();
$db = $database->connect();

// Datos del usuario
$username = "user123";
$password = "password123";

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, hashed_password) VALUES (?, ?)");

$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo "Usuario creado exitosamente.\n";
} else {
    echo "Error al crear el usuario: " . $stmt->error . "\n";
}

$stmt->close();
$db->close();
