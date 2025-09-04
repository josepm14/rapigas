<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Validar datos requeridos
    $required_fields = ['nombre', 'apellido', 'dni', 'direccion', 'telefono', 'cargo', 'responsabilidad', 'usuario'];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "El campo $field es requerido"]);
            exit;
        }
    }

    // Validar DNI único
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE dni = ?");
    $stmt->execute([$_POST['dni']]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'El DNI ya está registrado']);
        exit;
    }

    // Validar usuario único
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE usuario = ?");
    $stmt->execute([$_POST['usuario']]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'El usuario ya está registrado']);
        exit;
    }

    // Insertar nuevo usuario
    $sql = "INSERT INTO empleados (nombre, apellido, dni, direccion, telefono, cargo, responsabilidad, usuario) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['dni'],
        $_POST['direccion'],
        $_POST['telefono'],
        $_POST['cargo'],
        $_POST['responsabilidad'],
        $_POST['usuario']
    ]);

    echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>