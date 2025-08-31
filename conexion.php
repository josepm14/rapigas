<?php
$host = "localhost";   // Host
$dbname = "gestion_usuario";     // Nombre BD
$username = "root";          // Usuario
$password = "";        // Contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Solo mostrar mensaje de conexión en modo debug
    // echo "✅ Conexión exitosa";
} catch (PDOException $e) {
    die("❌ Error en la conexión: " . $e->getMessage());
}
?>