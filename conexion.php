<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Datos de conexión a la base de datos
$host = 'localhost';
$db   = 'MAESTROS';
$user = 'root';
$pass = 'Peces2.2';
$charset = 'utf8mb4';

// DSN (Data Source Name) - La cadena de conexión
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones de configuración para PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Creamos la conexión a la base de datos
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Si algo sale mal, capturamos el error y morimos
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>