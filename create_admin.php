<?php
require 'vendor/autoload.php';

use App\Config\Database;

// Conexión a la base de datos
$database = new Database();
$db = $database->connect();

// Datos del usuario admin
$username = "admin";
$password = "password123"; // Contraseña en texto plano que será hasheada

// Hashear la contraseña
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Preparar la sentencia SQL para insertar el usuario admin
$stmt = $db->prepare("INSERT INTO users (username, hashed_password) VALUES (?, ?)");

// Vincular parámetros y ejecutar
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo "Usuario admin creado exitosamente.\n";
} else {
    echo "Error al crear el usuario admin: " . $stmt->error . "\n";
}

// Cerrar la sentencia y la conexión
$stmt->close();
$db->close();
