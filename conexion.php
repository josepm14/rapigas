<?php
$host = "rapigas-continental-1699.b.aivencloud.com";
$port = 19507;
$dbname = "defaultdb";
$username = "avnadmin";
$password = "AVNS_Pwvxo5EFD8AykKHGvk1";

// Ruta al certificado descargado
$ssl_ca = __DIR__ . "/ca.pem";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    $options = [
        PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // 🔹 necesario en algunos entornos
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    //echo "✅ Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>

