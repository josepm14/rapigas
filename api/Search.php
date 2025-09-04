<?php
// Incluir el archivo de conexión a la base de datos
require_once "../conexion.php";

// Verificar si se recibió el parámetro de búsqueda
if (isset($_GET['q'])) {
    // Agregar comodines % para buscar coincidencias parciales
    $busqueda = "%" . $_GET['q'] . "%";
    
    try {
        // Preparar la consulta SQL para buscar en nombre, apellido o DNI
        $stmt = $pdo->prepare("SELECT * FROM usuarios 
                              WHERE nombre LIKE ? 
                              OR apellido LIKE ?
                              OR dni LIKE ?");
        
        // Ejecutar la consulta con los parámetros de búsqueda
        $stmt->execute([$busqueda, $busqueda, $busqueda]);
        // Obtener todos los resultados en un array asociativo
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Establecer el encabezado de respuesta como JSON
        header('Content-Type: application/json');
        // Convertir y enviar los resultados en formato JSON
        echo json_encode($resultados);
        
    } catch (PDOException $e) {
        // En caso de error en la base de datos
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Error en la búsqueda']);
    }
} else {
    // Si no se proporcionó el parámetro de búsqueda
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Parámetro de búsqueda no proporcionado']);
}