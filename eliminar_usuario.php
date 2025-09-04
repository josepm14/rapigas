<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    // Validar datos requeridos
    $required_fields = ['id', 'nombre', 'apellido', 'dni', 'direccion', 'telefono', 'cargo', 'responsabilidad', 'usuario'];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "El campo $field es requerido"]);
            exit;
        }
    }

    $id = $_POST['id'];

    // Verificar si el usuario existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        exit;
    }

    // Validar DNI único (excluyendo el usuario actual)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE dni = ? AND id != ?");
    $stmt->execute([$_POST['dni'], $id]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'El DNI ya está registrado por otro usuario']);
        exit;
    }

    // Validar usuario único (excluyendo el usuario actual)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE usuario = ? AND id != ?");
    $stmt->execute([$_POST['usuario'], $id]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya está registrado por otro usuario']);
        exit;
    }

    // Actualizar usuario
    $sql = "UPDATE empleados SET 
            nombre = ?, apellido = ?, dni = ?, direccion = ?, 
            telefono = ?, cargo = ?, responsabilidad = ?, usuario = ?
            WHERE id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nombre'],
        $_POST['apellido'],
        $_POST['dni'],
        $_POST['direccion'],
        $_POST['telefono'],
        $_POST['cargo'],
        $_POST['responsabilidad'],
        $_POST['usuario'],
        $id
    ]);

    echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>